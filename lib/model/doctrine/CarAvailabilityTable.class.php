<?php

class CarAvailabilityTable extends Doctrine_Table {

    public function findChangeOptions($reserveId) {

        $Reserve = Doctrine_Core::getTable("Reserve")->find($reserveId);
        if (!$Reserve) {
            error_log("[CarAvailabilityTable/findChangeOptions] No se encontro la Reserve ".$reserveId);
            return null;
        }

        // Buscamos los autos ya presentes en la reserva para no mostrarlos como opciones de cambio
        $originalReserveId = Doctrine_Core::getTable("Reserve")->findOriginalReserve($reserveId);
        $reserveCarIds = array();

        $q = Doctrine_Core::getTable("Reserve")
            ->createQuery('R')
            ->where("R.id = ? OR R.reserva_original = ?", array($originalReserveId, $originalReserveId));
        $results = $q->execute();
        foreach ($results as $r) {
            $reserveCarIds[] = $r->getCarId();
        }

        // Buscamos los autos con disponibilidad
        $q = Doctrine_Core::getTable("Car")
            ->createQuery('C')
            ->innerJoin('C.CarAvailabilities CA')
            ->innerJoin('C.Model M')
            ->where('C.activo = 1')
            ->andWhere('C.seguro_ok = 4')            
            ->andWhere("CA.is_deleted IS FALSE")
            ->andWhere("CA.day = ?", date("Y-m-d", strtotime($Reserve->getFechaInicio2())))
            ->andWhere('? BETWEEN CA.started_at AND CA.ended_at', date("H:i:s", strtotime($Reserve->getFechaInicio2())))
            ->whereNotIn('C.id', $reserveCarIds)
            ->orderBy('C.price_per_day ASC');

        if ($Reserve->getCar()->getModel()->getIdOtroTipoVehiculo() == 1) {
            $q->andWhere('M.id_otro_tipo_vehiculo IN (1,2)');
        } else {
            $q->andWhere('M.id_otro_tipo_vehiculo = ?', $Reserve->getCar()->getModel()->getIdOtroTipoVehiculo());
        }

        $Cars = $q->execute();
        error_log("Autos encontrados: ".count($Cars));

        $ReturnCars   = array();
        $reservePrice = $Reserve->getRentalPrice();

        foreach ($Cars as $Car) {

            $carPriceForReserve = CarTable::getPrice($Reserve->getFechaInicio2(), $Reserve->getFechaTermino2(), $Car->getPricePerHour(), $Car->getPricePerDay(), $Car->getPricePerWeek(), $Car->getPricePerMonth());

            error_log("Car ".$Car->id.": ".$carPriceForReserve." <= ".($Reserve->getRentalPrice() * 1.15));
            if ($carPriceForReserve <= ($Reserve->getRentalPrice() * 1.15)) {
                error_log("Car ".$Car->id);
                if (!$Car->hasReserve($Reserve->getFechaInicio2(), $Reserve->getFechaTermino2())) {
                    $ReturnCars[] = $Car;
                }
            }
        }
        
        return $ReturnCars;
    }
}