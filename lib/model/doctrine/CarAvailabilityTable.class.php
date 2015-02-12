<?php

class CarAvailabilityTable extends Doctrine_Table {

    public function findChangeOptions($reserveId) {

        $Reserve = Doctrine_Core::getTable("Reserve")->find($reserveId);
        if (!$Reserve) {
            error_log("[CarAvailabilityTable/findChangeOptions] No se encontro la Reserve ".$reserveId);
            return null;
        }

        $q = Doctrine_Core::getTable("Car")
            ->createQuery('C')
            ->innerJoin('C.CarAvailabilities CA')
            ->innerJoin('C.Model M')
            ->andWhere('C.seguro_ok = 4')
            ->andWhere('C.activo = 1')
            ->andWhere("CA.is_deleted IS FALSE")
            ->andWhere("CA.day = ?", date("Y-m-d", strtotime($Reserve->getFechaInicio2())))
            ->andWhere('? BETWEEN CA.started_at AND CA.ended_at', date("H:i:s", strtotime($Reserve->getFechaInicio2())))
            ->orderBy('C.price_per_day ASC');

        if ($Reserve->getCar()->getModel()->getIdOtroTipoVehiculo() == 1) {
            $q->andWhere('M.id_otro_tipo_vehiculo IN (1,2)');
        } else {
            $q->andWhere('M.id_otro_tipo_vehiculo = ?', $Reserve->getCar()->getModel()->getIdOtroTipoVehiculo());
        }

        $Cars = $q->execute();

        $ReturnCars   = array();
        $reservePrice = $Reserve->getRentalPrice();

        foreach ($Cars as $Car) {

            $carPriceForReserve = CarTable::getPrice($Reserve->getFechaInicio2(), $Reserve->getFechaTermino2(), $Car->getPricePerHour(), $Car->getPricePerDay(), $Car->getPricePerWeek(), $Car->getPricePerMonth());

            if ($carPriceForReserve <= ($Reserve->getRentalPrice() * 1.15)) {
                if (!$Car->hasReserve($from, $to, $ownerId)) {
                    $ReturnCars[] = $Car;
                }
            }
        }
        
        return $ReturnCars;
    }
}