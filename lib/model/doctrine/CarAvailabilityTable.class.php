<?php

class CarAvailabilityTable extends Doctrine_Table {

    public function findByDay($datetime, $form = false, $to = false, $ownerId = false) {
        
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

            return $ReturnCars;
        }

        return $Cars;
    }
}