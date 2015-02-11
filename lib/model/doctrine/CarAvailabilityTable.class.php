<?php

class CarAvailabilityTable extends Doctrine_Table {

    public function findASD($datetime, $from = false, $to = false, $ownerId = false) {

        error_log("DATETIME: ".$datetime);
        error_log("FROM: ".$from);
        error_log("TO: ".$to);
        error_log("OWNER ID: ".$ownerId);

        $q = Doctrine_Core::getTable("Car")
            ->createQuery('C')
            ->innerJoin('C.CarAvailabilities CA')
            ->andWhere("CA.is_deleted IS FALSE")
            ->andWhere("CA.day = ?", $datetime)
            ->andWhere('? BETWEEN CA.started_at AND CA.ended_at', $datetime);

        $Cars = $q->execute();

        if ($from && $to) {
            
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