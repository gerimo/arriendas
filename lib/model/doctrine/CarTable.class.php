<?php

class CarTable extends Doctrine_Table {

    public static function findCars($from, $to, $isMap, $NELat, $NELng, $SWLat, $SWLng, $regionId, $communeId, $isAutomatic, $isLowConsumption, $isMorePassengers) {

        $CarsFound = array();
        $isWeekend = false;
        
        try {

            $q = Doctrine_Core::getTable("Car")
                ->createQuery('C')
                ->innerJoin('C.Commune CO')
                ->innerJoin('CO.Region R')
                ->innerJoin('C.Model M')
                ->Where('C.activo = 1')
                ->andWhere('C.seguro_ok = 4')
                ->orderBy('C.price_per_day ASC');

            $MD = new Mobile_Detect;
            if ($MD->isMobile()) {
                $q->limit(33);
            } else {
                $q->limit(33);
            }

            $weekendDays = Utils::isWeekend(true);
            // Si es Feriado o Fin de Semana, se buscan los autos de la tabla CarAvailability
            if (count($weekendDays) > 0) {
                if (in_array(date("Y-m-d", strtotime($from)), $weekendDays)) {
                    $q->innerJoin("C.CarAvailabilities CA");
                    $q->andWhere("CA.is_deleted IS FALSE");
                    $q->andWhere("CA.day = ?", date("Y-m-d", strtotime($from)));
                    $q->andWhere('? BETWEEN CA.started_at AND CA.ended_at', date("H:i:s", strtotime($from)));
                }
            }

            if ($isMap) {
                $q->andWhere('C.lat < ?', $NELat);
                $q->andWhere('C.lng < ?', $NELng);
                $q->andWhere('C.lat > ?', $SWLat);
                $q->andWhere('C.lng > ?', $SWLng);
            } else {
                
                $q->andWhere("R.id = ?", $regionId);

                if ($communeId > 0) {
                    $q->andWhere("CO.id = ?", $communeId);
                }
            }

            if ($isAutomatic) {
                $q->andWhere("C.transmission = 1");
            }

            if ($isLowConsumption) {
                $q->andWhere("C.tipobencina = 'Diesel'");
            }

            if ($isMorePassengers) {
                $q->andWhere("M.id_otro_tipo_vehiculo = 3");
            }

            $Cars = $q->execute();

            foreach ($Cars as $i => $Car) {
                if (!$Car->hasReserve(date("Y-m-d H:i:s", strtotime($from)), date("Y-m-d H:i:s", strtotime($to)))) {

                    $count = 1;

                    $CarsFound[] = array(
                        'id' => $Car->id,
                        'latitude' => $Car->lat,
                        'longitude' => $Car->lng,                        
                        /*'commune' => $Car->getCommune()->name,*/
                        'brand' => $Car->getModel()->getBrand()->name,
                        'model' => $Car->getModel()->name,
                        'year' => $Car->year,
                        'photoType' => $Car->photoS3,
                        'photo' => $Car->foto_perfil,
                        'price' => number_format(round(CarTable::getPrice($from, $to, $Car->getPricePerHour(), $Car->getPricePerDay(), $Car->getPricePerWeek(), $Car->getPricePerMonth())), 0, '', '.'),
                        'price_per_hour' => number_format(round($Car->getPricePerHour()), 0, '', '.'),
                        'price_per_day' => number_format(round($Car->getPricePerDay()), 0, '', '.'),
                        'price_per_week' => number_format(round($Car->getPricePerWeek()), 0, '', '.'),
                        'price_per_month' => number_format(round($Car->getPricePerMonth()), 0, '', '.'),
                        'transmission' => $Car->transmission == 1 ? 'Automática' : 'Manual',
                        'count' => $count
                    );

                    $count++;
                }
            }

        } catch (Exception $e) {
            error_log("[".date("Y-m-d H:i:s")."][CarTable::findCars()] ERROR: ".$e->getMessage());
        }

        return $CarsFound;
    }
    
    public static function getInstance() {

        return Doctrine_Core::getTable('Car');
    }

    public static function getPrice($from, $to, $pricePerHour, $pricePerDay, $pricePerWeek, $pricePerMonth) {

        $from = date("Y-m-d H:i:s", strtotime($from));
        $to   = date("Y-m-d H:i:s", strtotime($to));

        try {

            $duration = Utils::calculateDuration($from, $to);
            $days     = floor($duration / 24);
            $hours    = $duration % 24;

            if ($hours >= 6) {
                $days = $days + 1;
                $hours = 0;
            }

            if ($days >= 7 && $pricePerWeek > 0) {
                $pricePerDay = $pricePerWeek / 7;
            }

            if ($days >= 30 && $pricePerMonth > 0) {
                $pricePerDay = $pricePerMonth / 30;
            }

        } catch (Exception $e) {
            error_log("[".date("Y-m-d H:i:s")."][CarTable::getPrice()] ERROR: ".$e->getMessage());
            /*if ($request->getHost() == "www.arriendas.cl") {
                Utils::reportError($e->getMessage(), "CarTable::getPrice()");
            }*/
        }
        
        return floor($pricePerDay * $days + $pricePerHour * $hours);
    }
}