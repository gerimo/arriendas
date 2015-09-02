<?php

class CarTmpTable extends Doctrine_Table {

	public function findAllCar($limit = false) {

        $q = Doctrine_Core::getTable("cartmp")
            ->createQuery('C')
            ->Where('C.canceled = 0')
            // ->andWhere('C.car_id is null')
            ->orderBy('C.fecha_subida DESC');
            
        if ($limit) {
            $q->limit($limit);
        }

        return $q->execute();
    }

}