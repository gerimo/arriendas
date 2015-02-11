<?php

class CarAvailabilityTable extends Doctrine_Table {

    public function findByDay($from, $to = false, $ownerId = false) {

        error_log("DATETIME: ".$from);
        error_log("TO: ".$to);
        error_log("OWNER ID: ".$ownerId);

        $q = Doctrine_Core::getTable("Car")
            ->createQuery('C')
            ->innerJoin('C.CarAvailabilities CA')
            ->andWhere("CA.is_deleted IS FALSE")
            ->andWhere("CA.day = ?", date("Y-m-d", strtotime($from)))
            ->andWhere('? BETWEEN CA.started_at AND CA.ended_at', date("H:i:s", strtotime($from)));

        $Cars = $q->execute();

        if ($to) {
            
            $ReturnCars = array();

            foreach ($Cars as $Car) {
                if (!$Car->hasReserve($from, $to, $ownerId)) {
                    $ReturnCars[] = $Car;
                }
            }

            error_log("Autos con disponibilidad: ".count($ReturnCars));
            return $ReturnCars;
        }

        return $Cars;
    }
}