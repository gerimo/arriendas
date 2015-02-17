    <?php

class CarTable extends Doctrine_Table {

    public function findCarsActives($limit = 50, $forWeek = false, $forWeekend = false) {

        $q = Doctrine_Core::getTable("Car")
            ->createQuery('C')
            ->innerJoin('C.Model M')
            ->innerJoin('C.Commune Co')
            ->innerJoin('Co.Region R')
            ->where('C.seguro_ok = 4')
            ->andWhere('C.activo = 1')
            ->andWhere('R.id = 13');

        if ($forWeek) {
            $q->andWhere("C.options & 1");
        }

        if ($forWeekend) {
            $q->andWhere("C.options & 2");
        }

        if ($limit) {
            $q->limit($limit);
        }

        return $q->execute();
    }

    public function findCars($offset, $limit, $from, $to, $withAvailability, $isMap, $NELat, $NELng, $SWLat, $SWLng, $regionId, $communeId, $isAutomatic, $isLowConsumption, $isMorePassengers, $nearToSubway) {

        $CarsFound = array();

        //error_log("OFFSET: ".$offset);
        //error_log("LIMIT: ".$limit);
        //error_log("FROM: ".$from);
        //error_log("TO: ".$to);
        //error_log("ISMAP: ".$isMap);
        //error_log("NELAT: ".$NELat);
        //error_log("NELNG: ".$NELng);
        //error_log("SWLAT: ".$SWLat);
        //error_log("SWLNG: ".$SWLng);
        //error_log("REGIONID: ".$regionId);
        //error_log("COMMUNEID: ".$communeId);
        //error_log("ISAUTOMATIC: ".$isAutomatic);
        //error_log("ISLOWCONSUMPTION: ".$isLowConsumption);
        //error_log("ISMOREPASSENGERS: ".$isMorePassengers);
        
        try {

            $q = Doctrine_Core::getTable("Car")
                ->createQuery('C')
                ->innerJoin('C.Commune CO')
                ->innerJoin('CO.Region R')
                ->innerJoin('C.Model M')
                ->Where('C.activo = 1')
                ->andWhere('C.seguro_ok = 4')
                ->orderBy('C.price_per_day ASC')
                ->offset($offset)
                ->limit($limit);

            // Si se pide disponibilidad, se buscan los autos de la tabla CarAvailability
            if ($withAvailability) {
                $q->innerJoin("C.CarAvailabilities CA");
                $q->andWhere("CA.is_deleted IS FALSE");
                $q->andWhere("CA.day = ?", date("Y-m-d", strtotime($from)));
                $q->andWhere('? BETWEEN CA.started_at AND CA.ended_at', date("H:i:s", strtotime($from)));
            }

            if ($isMap) {
                /*error_log("Map");*/
                $q->andWhere('C.lat < ?', $NELat);
                $q->andWhere('C.lng < ?', $NELng);
                $q->andWhere('C.lat > ?', $SWLat);
                $q->andWhere('C.lng > ?', $SWLng);
            } else {
                /*error_log("List");*/
                $q->andWhere("R.id = ?", $regionId);

                if ($communeId > 0) {
                    $q->andWhere("CO.id = ?", $communeId);
                }
            }

            if ($isAutomatic) {
                /*error_log("isAutomatic");*/
                $q->andWhere("C.transmission = 1");
            }

            if ($isLowConsumption) {
                /*error_log("isLowConsumption");*/
                $q->andWhere("C.tipobencina = 'Diesel'");
            }

            if ($isMorePassengers) {
                /*error_log("isMorePassengers");*/
                $q->andWhere("M.id_otro_tipo_vehiculo = 3");
            }

            if ($nearToSubway) {
                /*error_log($nearToSubway);*/
                //15 cuadras->18 minutos a pie
                $distanceMax = 1.5;

                $q->innerJoin('C.CarProximityMetros CPM');
                $q->andWhere('CPM.distance < ?', $distanceMax);
                $q->addOrderBy('CPM.distance ASC');

            }

            $Cars = $q->execute();

            foreach ($Cars as $i => $Car) {

                if (!$Car->hasReserve(date("Y-m-d H:i:s", strtotime($from)), date("Y-m-d H:i:s", strtotime($to)))) {

                    $count = 1;

                    $CarProximityMetro = $Car->getNearestMetro();

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
                        'count' => $count,
                        'nearestMetroDistance' => round($CarProximityMetro->distance, 1),
                        'nearestMetroName' => $CarProximityMetro->getMetro()->name,
                        'fecha_subida' =>$Car->fecha_subida,
                        'type' => $Car->getModel()->getCarType()->name,
                        'comunne' =>$Car->getCommune()->name,
                        'QuantityOfLatestRents' =>$Car->getQuantityOfLatestRents(),
                        'user_name' =>$Car->getUser()->firstname." ".$Car->getUser()->lastname,
                        'carId' => $Car->id,
                        'user_telephone' => $Car->getUser()->telephone
                    );

                    $count++;
                }
            }

        } catch (Exception $e) {
            error_log("[".date("Y-m-d H:i:s")."][CarTable::findCars()] ERROR: ".$e->getMessage());
        }

        return $CarsFound;
    }

    public function findDisabledCars($date = null) {

        if (is_null($date)) {
            $date = date("Y-m-d");
        }

        try {

            $q = Doctrine_Core::getTable("Car")
                ->createQuery('C')
                ->Where('C.activo = 0')
                ->andWhere('C.disabled_until IS NOT NULL')
                ->andWhere('C.disabled_until < ?', $date);

            $oCars = $q->execute();
        } catch (Exception $e) {
            error_log("[".date("Y-m-d H:i:s")."][CarTable::findDisabledCars()] ERROR: ".$e->getMessage());
        }

        return $oCars;
    }

    public function findCarsWithAvailability($day) {

        $q = Doctrine_Core::getTable("CarAvailability")
            ->createQuery('CA')
            ->distinct()
            ->where('CA.is_deleted = 0')
            ->andWhere('CA.day >= ?', $day);

        return $q->execute();
    }
    
    public static function getInstance() {

        return Doctrine_Core::getTable('Car');
    }

    public static function getPrice($from, $to, $pricePerHour, $pricePerDay, $pricePerWeek, $pricePerMonth ) {

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
        }
        
        return floor($pricePerDay * $days + $pricePerHour * $hours);
    }
}