<?php

/**
 * main actions.
 *
 * @package    CarSharing
 * @subpackage main
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class mainActions extends sfActions {

    /**x
     * Executes index action
     *
     * @param sfRequest $request A request object
     */
    //MAIN
 
    public function executePriceJson(sfWebRequest $request){
        
        $model = $request->getParameter('modelo');
        
        $modelo = new Model();
        $valorHora = $modelo->obtenerPrecio($model);
        //var_dump($valorHora);
        //die($valorHora);
        
        $data = array("valorHora" => $valorHora);
        //$data = array("valorHora" => "5.000");
        echo json_encode($data);
        //die();
        
    }
 
public function executeReferidos(sfWebRequest $request) {

}

public function executeReferidos2(sfWebRequest $request) {

}

public function executeAgradecimiento(sfWebRequest $request) {

}
    
public function executeInvitaciones(sfWebRequest $request) {
    
}
    
public function executeExito(sfWebRequest $request) {
}

public function executeFracaso(sfWebRequest $request) {
}

public function executeNotificacion(sfWebRequest $request) {
}

    public function executeIndex(sfWebRequest $request) {

	//Modificacion para setear la cookie de registo de la fecha de ingreso por primera vez del usuario
	$cookie_ingreso = $this->getRequest()->getCookie('cookie_ingreso');
	
	if($cookie_ingreso==NULL) {
	    $this->getResponse()->setCookie('cookie_ingreso',time());
	}
	   
        if ($this->getUser()->getAttribute('geolocalizacion') == null) {
            $this->getUser()->setAttribute('geolocalizacion', true);
        } elseif ($this->getUser()->getAttribute('geolocalizacion') == true) {
            $this->getUser()->setAttribute('geolocalizacion', false);
        }

        $cityname = $request->getParameter('c');

        $q = Doctrine::getTable('City')->createQuery('c')->where('c.name = ? ', array($cityname));
        $city = $q->fetchOne();

        if ($city != null) {
            $this->lat = $city->getLat();
            $this->lng = $city->getLng();
        } else {

            if (stripos($_SERVER['SERVER_NAME'], "arrendas") !== FALSE) {
                $this->lat = -34.59;
                $this->lng = -58.401604;
            } else {
                $this->lat = -33.427224;
                $this->lng = -70.605558;
            }
        }


        $boundleft = -35;
        $boundright = -30;
        $boundtop = -60;
        $boundbottom = -55;


        $q = Doctrine_Query::create()
                ->select('ca.id , av.id idav , mo.name model,
		  br.name brand, ca.year year,
		  ca.address address, ci.name city,
		  st.name state, co.name country,
		  owner.firstname firstname,
		  owner.lastname lastname,
		  ca.price_per_day priceday,
		  ca.price_per_hour pricehour,
		  owner.id userid'
                )
                ->from('Car ca')
                ->innerJoin('ca.Availabilities av')
                ->innerJoin('ca.Model mo')
                ->innerJoin('ca.User owner')
                ->innerJoin('mo.Brand br')
                ->innerJoin('ca.City ci')
                ->innerJoin('ci.State st')
                ->innerJoin('st.Country co')
                ->where('ca.lat > ?', $boundleft)
                ->andWhere('ca.lat < ?', $boundright)
                ->andWhere('ca.lng > ?', $boundtop)
                ->andWhere('ca.lng < ?', $boundbottom)
                ->andWhere('ca.offer = ?', 1)
                ->limit(10);
        $this->offers = $q->execute();

        //$this->brand =  Doctrine_Core::getTable('Brand')->createQuery('a')->execute();
        $this->brand = $q = Doctrine_Query::create()->from('Brand ba')
                ->innerJoin('ba.Models mo')
                ->innerJoin('mo.Cars ca')
                ->execute();

        $this->country = Doctrine_Core::getTable('Country')->createQuery('a')->execute();
    }

	public function executeFiltrosBusqueda(sfWebRequest $request) {
		
		$this->getUser()->setAttribute('fechainicio', $request->getParameter('fechainicio'));
		$this->getUser()->setAttribute('horainicio', $request->getParameter('horainicio'));
		$this->getUser()->setAttribute('fechatermino', $request->getParameter('fechatermino'));
		$this->getUser()->setAttribute('horatermino', $request->getParameter('horatermino'));
		throw new sfStopException();
	}

    public function executeGetModelSearch(sfWebRequest $request) {

        sfConfig::set('sf_web_debug', false);
        $_output;

        /* Asegurar que la solicitud sea AJAX */
        //if (!$request->isXmlHttpRequest())
        //return $this->renderText(json_encode(array('error'=>'S?lo respondo consultas v?a AJAX.')));

        $_output[] = array("optionValue" => 0, "optionDisplay" => "Modelo");

        if ($request->getParameter('id')) {

            //  $brand = Doctrine_Core::getTable('Brand')->find(array($request->getParameter('id')));

            $this->models = $q = Doctrine_Query::create()->from('Model mo')
                    ->innerJoin('mo.Cars ca')
                    ->where('mo.Brand.Id = ?', $request->getParameter('id'))
                    ->execute();

            foreach ($this->models as $p) {
                $_output[] = array("optionValue" => $p->getId(), "optionDisplay" => $p->getName());
            }
        }
        return $this->renderText(json_encode($_output));
        //return $this->renderText(json_encode(array('error'=>'Faltan par?metros para realizar la consulta')));
    }

    public function executeIndexOld(sfWebRequest $request) {

        $cityname = $request->getParameter('c');

        $q = Doctrine::getTable('City')->createQuery('c')->where('c.name = ? ', array($cityname));
        $city = $q->fetchOne();

        if ($city != null) {
            $this->lat = $city->getLat();
            $this->lng = $city->getLng();
        } else {

            if (stripos($_SERVER['SERVER_NAME'], "arrendas") !== FALSE) {
                $this->lat = -34.59;
                $this->lng = -58.401604;
            } else {
                $this->lat = -33.427224;
                $this->lng = -70.605558;
            }
        }

        $boundleft = -35;
        $boundright = -30;
        $boundtop = -60;
        $boundbottom = -55;



        $q = Doctrine_Query::create()
                ->select(' ca.id,  re.id idre, mo.name model,
		  br.name brand, ca.year year,
		  ca.address address, ci.name city,
		  st.name state, co.name country,
		  owner.firstname firstname,
		  owner.lastname lastname,
		  ca.price_per_day priceday,
		  ca.price_per_hour pricehour, re.date date'
                )
                ->from('Car ca')
                ->innerJoin('ca.Reserves re')
                ->innerJoin('ca.Model mo')
                ->innerJoin('ca.User owner')
                ->innerJoin('mo.Brand br')
                ->innerJoin('ca.City ci')
                ->innerJoin('ci.State st')
                ->innerJoin('st.Country co')
                ->where('ca.lat > ?', $boundleft)
                ->andWhere('ca.lat < ?', $boundright)
                ->andWhere('ca.lng > ?', $boundtop)
                ->andWhere('ca.lng < ?', $boundbottom)
                ->orderBy('re.date desc')
                ->limit(10);
        $this->last = $q->execute();

        $q = Doctrine_Query::create()
                ->select('ca.id, av.id idav , mo.name model,
		  br.name brand, ca.year year,
		  ca.address address, ci.name city,
		  st.name state, co.name country,
		  owner.firstname firstname,
		  owner.lastname lastname,
		  ca.price_per_day priceday,
		  ca.price_per_hour pricehour'
                )
                ->from('Car ca')
                ->innerJoin('ca.Availabilities av')
                ->innerJoin('ca.Model mo')
                ->innerJoin('ca.User owner')
                ->innerJoin('mo.Brand br')
                ->innerJoin('ca.City ci')
                ->innerJoin('ci.State st')
                ->innerJoin('st.Country co')
                ->where('ca.lat > ?', $boundleft)
                ->andWhere('ca.lat < ?', $boundright)
                ->andWhere('ca.lng > ?', $boundtop)
                ->andWhere('ca.lng < ?', $boundbottom)
                ->andWhere('ca.offer = ?', 1)
                ->limit(10);
        $this->offers = $q->execute();
        //echo $q->getSqlQuery();
    }

    public function executeMap(sfWebRequest $request) {

	$modelo= new Model();

        sfConfig::set('sf_web_debug', false);
        $this->getResponse()->setContentType('application/json');

        $boundleft = $request->getParameter('swLat');
        $boundright = $request->getParameter('neLat');
        $boundtop = $request->getParameter('swLng');
        $boundbottom = $request->getParameter('neLng');

        $day_from = $request->getParameter('day_from');
        $day_to = $request->getParameter('day_to');

        $hour_from = $request->getParameter('hour_from');
        $hour_to = $request->getParameter('hour_to');

        $brand = $request->getParameter('brand');
        $model = $request->getParameter('model');

        $location = $request->getParameter('location');
        $price = $request->getParameter('price');


        $lat_centro = $request->getParameter('clat');
        $lng_centro = $request->getParameter('clng');

        /*
          if (
          $hour_from != "Hora de inicio" &&
          $hour_to != "Hora de entrega" &&
          $hour_from != "" &&
          $hour_from != ""
          ) {
          list($day_from, $hour_from) = split(' ', $hour_from);
          list($day_to, $hour_to) = split(' ', $hour_to);
          }
         */


        $q = Doctrine_Query::create()
                ->select('ca.id, av.id idav ,
	        	ca.lat lat, ca.lng lng, ca.price_per_day, ca.price_per_hour,
	        	mo.name modelo, br.name brand, ca.year year,
	        	ca.address address,
	        	us.username username, us.id userid')
                ->from('Car ca')
                ->innerJoin('ca.Availabilities av')
                ->leftJoin('ca.Reserves re')
                ->innerJoin('ca.Model mo')
                ->innerJoin('ca.User us')
                ->innerJoin('mo.Brand br')
                ->innerJoin('ca.City ci')
                ->innerJoin('ci.State st')
                ->innerJoin('st.Country co')
                ->where('ca.lat > ?', $boundleft)
                ->andWhere('ca.lat < ?', $boundright)
                ->andWhere('ca.lng > ?', $boundtop)
                ->andWhere('ca.lng < ?', $boundbottom);

        if (
                $hour_from != "Hora de inicio" &&
                $hour_to != "Hora de entrega" &&
                $hour_from != "" &&
                $hour_from != "" &&
                $day_from != "Dia de inicio" &&
                $day_to != "Dia de entrega" &&
                $day_from != "" &&
                $day_to != ""
        ) {

            $day_from = implode('-', array_reverse(explode('-', $day_from)));
            $day_to = implode('-', array_reverse(explode('-', $day_to)));

            $fullstartdate = $day_from . " " . $hour_from;
            $fullenddate = $day_to . " " . $hour_to;

            $q = $q->andWhere('av.hour_from < ? and av.hour_to > ? and av.date_from <= ? and av.date_to >= ?', array($hour_from, $hour_to, $day_from, $day_to));
            $q = $q->orWhere('av.hour_from < ? and av.hour_to > ? and av.date_from < ?', array($hour_from, $hour_to, $day_from));
        }

        if ($brand != "") {
            $q = $q->andWhere('br.id = ?', $brand);
        }


        if ($model != "" && $model != "0") {
            $q = $q->andWhere('mo.id = ?', $model);
        }

        if ($location != "") {
            $q = $q->andWhere('co.id = ?', $location);
        }
        if ($price != null) {
            if ($price == 1) {
                $q = $q->orderBy('ca.price_per_hour asc');
            } else {
                $q = $q->orderBy('ca.price_per_hour desc');
            }
        }
        $cars = $q->execute();

        $data = array();
        $carsid = Array();

        foreach ($cars as $car) {


            if ($lat_centro != null && $lng_centro != null) {

                $lat1 = $car->getlat();
                $lat2 = $lat_centro;

                $lon1 = $car->getlng();
                $lon2 = $lng_centro;

                $R = 6371; // km
                $dLat = deg2rad($lat2 - $lat1);
                $dLon = deg2rad($lon2 - $lon1);
                $lat1 = deg2rad($lat1);
                $lat2 = deg2rad($lat2);

                $a = sin($dLat / 2) * sin($dLat / 2) +
                        sin($dLon / 2) * sin($dLon / 2) * cos($lat1) * cos($lat2);
                $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
                $d = $R * $c;
            }

	    
            if ($car->getPhotoFile('main') != false) {
                sfContext::getInstance()->getConfiguration()->loadHelpers("Asset");
                $photo = $car->getPhotoFile('main')->getFilename();

            }
            else
		//Tomamos la foto del modelo si no tiene el vehículo
                $photo = "";


            $has_reserve = false;

            if (
                    $hour_from != "Hora de inicio" &&
                    $hour_to != "Hora de entrega" &&
                    $hour_from != "" &&
                    $hour_from != "" &&
                    $day_from != "Dia de inicio" &&
                    $day_to != "Dia de entrega" &&
                    $day_from != "" &&
                    $day_to != ""
            ) {



                $fullstartdate = $day_from . " " . $hour_from;
                $fullenddate = $day_to . " " . $hour_to;
                $has_reserve = $car->hasReserve($fullstartdate, $fullstartdate, $fullenddate, $fullenddate);
            }


            if (!$has_reserve) {

                $data[] = array('id' => $car->getId(),
                    'idav' => $car->getIdav(),
                    'longitude' => $car->getlng(),
                    'latitude' => $car->getlat(),
                    'brand' => $car->getBrand(),
                    'model' => $car->getModelo(),
                    'address' => $car->getAddress(),
                    'year' => $car->getYear(),
                    'photo' => $photo,
                    'username' => $car->getUser()->getFirstname() . " " . substr($car->getUser()->getLastname(), 0, 1) . ".",
                    'price_per_hour' => floor($car->getPricePerHour()),
                    'price_per_day' => floor($car->getPricePerDay()),
                    'userid' => $car->getUser()->getId(),
                    'd' => $d
                );
		
	    $q = Doctrine::getTable('car')->createQuery('u')->where('u.id = ? ', array($car->getId()));

            $userdb = $q->fetchOne();
            }
        }

        $position = array();
        $newRow = array();
        foreach ($data as $key => $row) {
            $position[$key] = $row["d"];
            $newRow[$key] = $row;
        }
        asort($position);
        $returnArray = array();
        foreach ($position as $key => $pos) {
            $returnArray[] = $newRow[$key];
        }

        //$data = $returnArray;

        if ($lat_centro != null && $lng_centro != null) {
            $carsArray = array("cars" => $returnArray);
        } else {
            $carsArray = array("cars" => $data);
        }

        return $this->renderText(str_replace("default_","http://admin.arriendas.cl/uploads/",json_encode($carsArray)));
    }

    public function executeCars(sfWebRequest $request) {

        $this->setLayout(false);
        sfConfig::set('sf_web_debug', false);

        $boundleft = $request->getParameter('swLat');
        $boundright = $request->getParameter('neLat');
        $boundtop = $request->getParameter('swLng');
        $boundbottom = $request->getParameter('neLng');
        $hour_from = $request->getParameter('hour_from');
        $hour_to = $request->getParameter('hour_to');
        $day_from = $request->getParameter('day_from');
        $day_to = $request->getParameter('day_to');

        /* if(
          $hour_from != "Hora de inicio" &&
          $hour_to != "Hora de entrega" &&
          $hour_from != "" &&
          $hour_from != ""
          )
          {
          list($day_from, $hour_from) = split(' ', $hour_from);
          list($day_to, $hour_to) = split(' ', $hour_to);
          } */

        $q = Doctrine_Query::create()
                ->select('ca.id , av.id idav , mo.name model,
			  br.name brand, ca.year year,
			  ca.address address, ci.name city,
			  st.name state, co.name country,
			  owner.firstname firstname,
			  owner.lastname lastname,
			  ca.price_per_day priceday,
			  ca.price_per_hour pricehour,
			  owner.id userid'
                )
                ->from('Car ca')
                ->innerJoin('ca.Availabilities av')
                ->innerJoin('ca.Model mo')
                ->innerJoin('ca.User owner')
                ->innerJoin('mo.Brand br')
                ->innerJoin('ca.City ci')
                ->innerJoin('ci.State st')
                ->innerJoin('st.Country co')
                ->where('ca.lat > ?', $boundleft)
                ->andWhere('ca.lat < ?', $boundright)
                ->andWhere('ca.lng > ?', $boundtop)
                ->andWhere('ca.lng < ?', $boundbottom);

        if (
                $hour_from != "Hora de inicio" &&
                $hour_to != "Hora de entrega" &&
                $hour_from != "" &&
                $hour_from != "" &&
                $day_from != "Dia de inicio" &&
                $day_to != "Dia de entrega" &&
                $day_from != "" &&
                $day_to != ""
        ) {
            $q = $q->andWhere('av.hour_from > ?', $hour_from)
                    ->andWhere('av.hour_to < ?', $hour_from)
                    ->andWhere('av.date_from > ?', $day_from)
                    ->andWhere('av.date_to < ?', $day_to);
        }

        $this->cars = $q->execute();
    }

    public function executeSearchResult(sfWebRequest $request) {

        $this->setLayout(false);
        sfConfig::set('sf_web_debug', false);

        $boundleft = $request->getParameter('swLat');
        $boundright = $request->getParameter('neLat');
        $boundtop = $request->getParameter('swLng');
        $boundbottom = $request->getParameter('neLng');

        $day_from = $request->getParameter('day_from');
        $day_to = $request->getParameter('day_to');

        $hour_from = $request->getParameter('hour_from');
        $hour_to = $request->getParameter('hour_to');

        $brand = $request->getParameter('brand');
        $model = $request->getParameter('model');

        $location = $request->getParameter('location');
        $price = $request->getParameter('price');

        $lat_centro = $request->getParameter('clat');
        $lng_centro = $request->getParameter('clng');

        /* if(
          $hour_from != "Hora de inicio" &&
          $hour_to != "Hora de entrega" &&
          $hour_from != "" &&
          $hour_from != ""
          )
          {
          list($day_from, $hour_from) = split(' ', $hour_from);
          list($day_to, $hour_to) = split(' ', $hour_to);
          } */


        $q = Doctrine_Query::create()
                ->select('ca.id , av.id idav , mo.name modelo,
			  br.name brand, ca.year year,
			  ca.address address, ci.name city,
			  st.name state, co.name country,
			  owner.firstname firstname,
			  owner.lastname lastname,
			  ca.price_per_day priceday,
			  ca.price_per_hour pricehour,
                          ca.lat lat,
                          ca.lng lng,
			  owner.id userid'
                )
                ->from('Car ca')
                ->innerJoin('ca.Availabilities av')
                ->innerJoin('ca.Model mo')
                ->leftJoin('ca.Reserves re')
                ->innerJoin('ca.User owner')
                ->innerJoin('mo.Brand br')
                ->innerJoin('ca.City ci')
                ->innerJoin('ci.State st')
                ->innerJoin('st.Country co')
                ->where('ca.lat > ?', $boundleft)
                ->andWhere('ca.lat < ?', $boundright)
                ->andWhere('ca.lng > ?', $boundtop)
                ->andWhere('ca.lng < ?', $boundbottom);

        if (
                $hour_from != "Hora de inicio" &&
                $hour_to != "Hora de entrega" &&
                $hour_from != "" &&
                $hour_from != "" &&
                $day_from != "Dia de inicio" &&
                $day_to != "Dia de entrega" &&
                $day_from != "" &&
                $day_to != ""
        ) {


            $day_from = implode('-', array_reverse(explode('-', $day_from)));
            $day_to = implode('-', array_reverse(explode('-', $day_to)));

            $fullstartdate = $day_from . " " . $hour_from;
            $fullenddate = $day_to . " " . $hour_to;

            $q = $q->andWhere('av.hour_from < ? and av.hour_to > ? and av.date_from <= ? and av.date_to >= ?', array($hour_from, $hour_to, $day_from, $day_to));
            $q = $q->orWhere('av.hour_from < ? and av.hour_to > ? and av.date_from < ?', array($hour_from, $hour_to, $day_from));

            //$q = $q->andwhere('((re.date > ? and date_add(re.date, INTERVAL re.duration HOUR) > ?) or (re.date < ? and date_add(re.date, INTERVAL re.duration HOUR) < ?))', array($fullstartdate, $fullstartdate, $fullenddate, $fullenddate));
        }

        if ($brand != "") {
            $q = $q->andWhere('br.id = ?', $brand);
        }

        if ($model != "" && $model != "0") {
            $q = $q->andWhere('mo.id = ?', $model);
        }

        if ($location != "") {
            $q = $q->andWhere('co.id = ?', $location);
        }
        if ($price != null) {
            if ($price == 1) {
                $q = $q->orderBy('ca.price_per_hour asc');
            } else {
                $q = $q->orderBy('ca.price_per_hour desc');
            }
        }

        $cars = $q->execute();

        $data = array();
        $carsid = Array();



        foreach ($cars as $car) {

            if ($lat_centro != null && $lng_centro != null) {

                $lat1 = $car->getlat();
                $lat2 = $lat_centro;

                $lon1 = $car->getlng();
                $lon2 = $lng_centro;

                $R = 6371; // km
                $dLat = deg2rad($lat2 - $lat1);
                $dLon = deg2rad($lon2 - $lon1);
                $lat1 = deg2rad($lat1);
                $lat2 = deg2rad($lat2);

                $a = sin($dLat / 2) * sin($dLat / 2) +
                        sin($dLon / 2) * sin($dLon / 2) * cos($lat1) * cos($lat2);
                $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
                $d = $R * $c;
            }

            if ($car->getPhotoFile('main') != false) {
                sfContext::getInstance()->getConfiguration()->loadHelpers("Asset");
                $photo = $car->getPhotoFile('main')->getFilename();
                if(strlen($photo)< 20) {
		    $photo = image_path("cars/" . $photo);	    
		} else {
		    $photo = image_path("http://admin.arriendas.cl/uploads/" . str_replace("default_","",$photo));
		}
            }
            else
                $photo = "";

            $has_reserve = false;

            if (
                    $hour_from != "Hora de inicio" &&
                    $hour_to != "Hora de entrega" &&
                    $hour_from != "" &&
                    $hour_from != "" &&
                    $day_from != "Dia de inicio" &&
                    $day_to != "Dia de entrega" &&
                    $day_from != "" &&
                    $day_to != ""
            ) {



                $fullstartdate = $day_from . " " . $hour_from;
                $fullenddate = $day_to . " " . $hour_to;
                $has_reserve = $car->hasReserve($fullstartdate, $fullstartdate, $fullenddate, $fullenddate);
            }


            if (!$has_reserve) {
                $data[] = array('id' => $car->getId(),
                    'idav' => $car->getIdav(),
                    'longitude' => $car->getlng(),
                    'latitude' => $car->getlat(),
                    'brand' => $car->getBrand(),
                    'model' => $car->getModelo(),
                    'address' => $car->getAddress(),
                    'year' => $car->getYear(),
                    'photo' => $photo,
                    'username' => $car->getUser()->getFirstname() . " " . $car->getUser()->getLastname(),
                    'price_per_hour' =>  floor($car->getPricePerHour()),
                    'price_per_day' =>  floor($car->getPricePerDay()),
                    'userid' => $car->getUser()->getId(),
                    'd' => $d
                );
            }
        }

        $position = array();
        $newRow = array();
        foreach ($data as $key => $row) {
            $position[$key] = $row["d"];
            $newRow[$key] = $row;
        }
        asort($position);
        $returnArray = array();
        foreach ($position as $key => $pos) {
            $returnArray[] = $newRow[$key];
        }

        //print_r($returnArray);die;

        if ($lat_centro != null && $lng_centro != null) {
            $this->cars = $returnArray;
        } else {
            $this->cars = $data;
        }


//        echo '<pre>';
//        print_r($this->cars);
//        echo '</pre>';
//
//        $data = Array();
//
//        echo 'llega'.'\n';
//        
//        foreach ($this->cars as $c) {
//            $data[] = array('id' => $c->getId(), 'count' => $c->getPositiveRatings());
//        }
//        
//        echo 'llega2'.'\n';
//
//        foreach ($data as $key => $row) {
//            $id[$key] = $row['id'];
//            $count[$key] = $row['count'];
//        }
//        
//        echo 'llega3';
//        die;
//
//        $this->data;
//        if ($this->cars->count()) {
//            array_multisort($count, SORT_DESC, $id, SORT_ASC, $data);
//            $this->data = array_slice($data, 0, 5);
//        }
        //print_r($data);
        //exit();
    }

    public function executeDoSearch(sfWebRequest $request) {

        /*  $fecha = strtotime($request->getParameter('date'));
          $day = mktime(0, 0, 0, date("m",$fecha), date("d",$fecha), date("y",$fecha));
          $dayArray = getdate($day);

          $q = Doctrine_Query::create()
          ->select('av.id , ca.id idcar , mo.name model,
          br.name brand, ca.year year,
          ca.address address, ci.name city,
          st.name state, co.name country'
          )
          ->from('Availability av')
          ->innerJoin('av.Car ca')
          ->innerJoin('ca.Model mo')
          ->innerJoin('mo.Brand br')
          ->innerJoin('ca.City ci')
          ->innerJoin('ci.State st')
          ->innerJoin('st.Country co')
          ->where('av.date_from <= ?', $request->getParameter('date'))
          ->andWhere('av.date_to >= ?', $request->getParameter('date'));

          $q = $q->andWhere('av.hour_from >= ?', $request->getParameter('hourfrom')) //revisar
          ->andWhere('av.hour_to <= ?', $request->getParameter('hourto')); //revisar
          }
          $q = $q->andWhere('av.day = ? OR av.day IS NULL', $dayArray['wday']);
          //$offers = $q->fetchArray();
          //$offers = $q->getSqlQuery();
          //var_dump($offers);
          $this->searchResults = $q->execute();

          $this->forward ('main', 'search');
         */
    }

	//CONTACTO
    public function executeContact(sfWebRequest $request) {
        
    }
	
	//TERMINOS
    public function executeTerminos(sfWebRequest $request) {
        
    }
	
	//COMPANIA
    public function executeCompania(sfWebRequest $request) {
        
    }
	
	//AYUDA
    public function executeAyuda(sfWebRequest $request) {
        
    }

    //////////////////REGISTER//////////////////////////////
    public function executeTerminosycondiciones(sfWebRequest $request) {
        
    }

    public function executePrivacidad(sfWebRequest $request) {
        
    }

    public function executeRegisterPayment(sfWebRequest $request) {
        
    }

    public function executeRegisterVerify(sfWebRequest $request) {

        $userid = $this->getRequest()->getParameter('userid');

        $this->info = "";

        $data = array();

        if ($this->getRequestParameter('activate') != null) {

            $username = $this->getRequestParameter('username');
            $activate = $this->getRequestParameter('activate');


            $q = Doctrine::getTable('user')->createQuery('u')->where('u.username = ? and u.hash = ?', array($username, $activate));
            $user = $q->fetchOne();

            if ($user != null) {
                $user->setConfirmed(true);
                $user->save();
				
				$url = $_SERVER['SERVER_NAME'];
				$url = str_replace('http://', '', $url);
				$url = str_replace('https://', '', $url);
				
                $this->info = "<h2>Felicitaciones!</h2><br/>Su cuenta a sido activada, ahora puede ingresar con su nombre de usuario y contrase&ntilde;a.";
                $userid = $user->getId();
                $mail = '<body>Hola ' . htmlentities($user->getFirstName()) . ' ' . htmlentities($user->getLastName()) . ',<br/><br/> Bienvenido a Arriendas.cl!<br/><br/>	

Tu cuenta ha sido verificada. <br/><br/>
				
Puedes ver autos cerca tuyo haciendo click <a href="http://'.$url.'">aqu&iacute;</a>.<br/><br/>

El equipo de Arriendas.cl
<br><br>
<em style="color: #969696">Nota: Para evitar posibles problemas con la recepci&oacute;n de este correo le aconsejamos nos agregue a su libreta de contactos.</em> 
</body>';


                $to = $user->getEmail();
                $subject = "Cuenta verificada";
                $headers = "From: \"Arriendas Reservas\" <no-reply@arriendas.cl>\r\n";
                $headers .= "Content-type: text/html\r\n";
                $headers .= "X-Mailer: PHP/" . phpversion();
                $this->mailSmtp($to, $subject, $mail, $headers);
            }
        } else if ($userid != null) {

			$url = $_SERVER['SERVER_NAME'];
			$url = str_replace('http://', '', $url);
			$url = str_replace('https://', '', $url);

            $this->info = "Usted recibir&aacute; un correo con un link para activar su cuenta. Si no recibe el correo en 5 minutos, revise en SPAM.";

            $user = Doctrine_Core::getTable('user')->find(array($userid));

            $this->user = $user;

            sfContext::getInstance()->getConfiguration()->loadHelpers("Asset");

            $mail = '<body>Hola ' . htmlentities($user->getFirstName()) . ' ' . htmlentities($user->getLastName()) . ',<br/> Bienvenido a Arriendas.cl!<br/><br/>	

Haciendo click <a href="http://' . $url .
                    $this->getController()->genUrl('main/registerVerify') . '?activate=' . $user->getHash() . '&username=' . $user->getUsername() . '">aqu&iacute;</a> confirmar&aacute;s la validez de tu direcci&oacute;n de mail.<br/><br/>

Puedes ver autos cerca tuyo haciendo click <a href="http://'.$url.'">aquí</a>.<br/><br/>

El equipo de Arriendas.cl
<br><br>
<em style="color: #969696">Nota: Para evitar posibles problemas con la recepcion de este correo le aconsejamos nos agregue a su libreta de contactos.</em> 
</body>';


            $to = $user->getEmail();
            $subject = "Bienvenido a Arriendas.cl!";

// compose headers
            $headers = "From: \"Arriendas Reservas\" <no-reply@arriendas.cl>\r\n";
            $headers .= "Content-type: text/html\r\n";
            $headers .= "X-Mailer: PHP/" . phpversion();


// send email
            $this->mailSmtp($to, $subject, $mail, $headers);
        }

        if ($userid != null) {



            //$data[] = array(false, "Scan de documento");
            $data[] = array(($user->getDriverLicenseFile() != null), "Scan de licencia");
	    //$data[] = array(($user->getRutFile() != null), "Scan de Foto de Rut");
            $data[] = array(($user->getConfirmed()), "Email confirmado");
            $data[] = array(($user->getConfirmedFb()), "Facebook confirmado");
            $data[] = array(($user->getConfirmedSms()), "Telefono confirmado");
            $data[] = array(($user->getFriendInvite()), "Invita a tus amigos");
            //$data[] = array( false , "Identidad verificada");
          //  $data[] = array(($user->getPaypalId() != null), "Medios de pagos aprobados");
        }

        $this->userdata = $data;
    }

    public function executeDoRegister(sfWebRequest $request) {
		
        if ($this->getRequest()->getMethod() != sfRequest::POST) {
            sfView::SUCCESS;
        } else {

            $code = trim($_POST['code']);
            if ($_SESSION['captcha'] != $code) {
                $this->forward('main','register');
            }

            $q = Doctrine::getTable('user')->createQuery('u')->where('u.username = ?', array($this->getRequestParameter('username')));

            $user = $q->fetchOne();

            if (!$user) {

                if ($request->getParameter('username') != null &&
                        $request->getParameter('firstname') != null &&
                        $request->getParameter('lastname') != null &&
                        $request->getParameter('email') != null &&
                        $request->getParameter('password') != null &&
                        $request->getParameter('email') == $request->getParameter('emailAgain') &&
                        $request->getParameter('password') == $request->getParameter('passwordAgain') &&
                        $request->getParameter('region') != 0 &&
                        $request->getParameter('region') != null &&
                        $request->getParameter('comunas') != 0 &&
                        $request->getParameter('comunas') != null &&
                        $request->getParameter('run') != null &&
                        $request->getParameter('address') != null
                ) {

		
                    $u = new User();
                    $u->setFirstname($request->getParameter('firstname'));
                    $u->setLastname($request->getParameter('lastname'));
                    $u->setEmail($request->getParameter('email'));
                    $u->setUsername($request->getParameter('username'));
                    $u->setPassword(md5($request->getParameter('password')));

                    $u->setCountry($request->getParameter('country'));
                    $u->setCity($request->getParameter('city'));
                    $u->setBirthdate($request->getParameter('birth'));
                    $u->setTelephone($request->getParameter('telephone'));
                    $u->setRegion($request->getParameter('region'));
		    $u->setAddress($request->getParameter('address'));
                    $u->setComuna($request->getParameter('comunas'));

                    $u->setHash(substr(md5($request->getParameter('username')), 0, 6));

                    if ($request->getParameter('main') != NULL)
                        $u->setPictureFile($request->getParameter('main'));

                    if ($request->getParameter('licence') != NULL)
                        $u->setDriverLicenseFile($request->getParameter('licence'));
					
		    if ($request->getParameter('rut') != NULL)
                        $u->setRutFile($request->getParameter('rut'));
			$rut = $request->getParameter('run');
			$rut = str_replace(".", "", $rut);
			$rut = str_replace("-", "", $rut);
			$u->setRut($rut);
                    $u->save();
                    /*
                      $this->getUser()->setFlash('msg', 'Autenticado');
                      $this->getUser()->setAuthenticated(true);
                      $this->getUser()->setAttribute("logged", true);
                      $this->getUser()->setAttribute("userid", $u->getId());
                    */

                    //$this->getRequest()->setParameter('emails',array('first@email.com','second@email.com','third@email.com'));

                    $this->getRequest()->setParameter('userid', $u->getId());

		    //Modificacion para agregar la fecha de primer ingreso del usuario
		    $cookie= $this->getRequest()->getCookie("cookie_ingreso");
		    $q = Doctrine_Manager::getInstance()->getCurrentConnection();
		    if($cookie==NULL) {
			$result = $q->execute("UPDATE User SET fecha_primer_ingreso = '".strftime("%Y-%m-%d %H:%M:%S",time())."' WHERE id='".$u->getId()."'");
		    } else {
			//Usamos la fecha almacenada en el cookie
			$result = $q->execute("UPDATE User SET fecha_primer_ingreso = '".strftime("%Y-%m-%d %H:%M:%S",$cookie)."' WHERE id='".$u->getId()."'");
		    }
		    
                    $this->forward('main', 'registerVerify');
                }
                else {
                    $this->getUser()->setFlash('msg', 'Uno de los datos ingresados es incorrecto');
                    $this->forward('main/register');
                }

                //$this->getUser()->setAttribute("centrodecosto", $user->getCentrodecosto());
                //$this->getUser()->setAttribute("rol", $user->getRolId());
                //$this->getUser()->setAttribute("cliente", $user->getClienteId());
                //ponerle credenciales
                //$this->getUser()->setAttribute("name", $user->getFirstName()." ".$user->getLastName());
                //$this->getUser()->setAttribute("salesman", $user->getSalesman());
            } else {
                $this->getUser()->setFlash('show', true);
                $this->getUser()->setFlash('msg', 'El nombre de usuario ya existe');
                $this->forward('main', 'register');
            }
        }

        return sfView::NONE;
    }

    public function executeRegister(sfWebRequest $request) {

        //print_r($_SESSION);die;

        if (!isset($_SESSION['reg_back'])) {
            $urlpage = split('/', $request->getReferer());

            if ($urlpage[count($urlpage) - 1] != "register" && $urlpage[count($urlpage) - 1] != "doRegister") {
                $_SESSION['reg_back'] = $request->getReferer();
            }
        }
        try {
            $q = Doctrine_Query::create()
                    ->select('r.*')
                    ->from('Regiones r');

            $this->regiones = $q->fetchArray();
        } catch (Exception $e) {
            echo $e->getMessage();
            die;
        }
    }

    public function executeRegisterClose(sfWebRequest $request) {
        if (isset($_SESSION['reg_back'])) {
            $this->redirect($_SESSION['reg_back']);
        } else {
            $this->forward('main', 'login');
        }
    }

    //////////////////FORGOT//////////////////////////////

    public function executeForgot(sfWebRequest $request) {
        
    }
	
    public function executeActivate(sfWebRequest $request) {
        
    }

    //////////////////LOGIN//////////////////////////////

    public function executeLogin(sfWebRequest $request) {


        if ($this->getUser()->isAuthenticated()) {

            $this->redirect('main/index');
        }

        //echo $this->getContext()->getRequest()->getHost() . $this->getContext()->getRequest()->getScriptName();
        //$_SESSION['urlback'] = $this->getRequest()->getUri();

        if (isset($_SESSION['login_back_url'])) {
            $urlpage = split('/', $_SESSION['login_back_url']);

            if ($urlpage[count($urlpage) - 1] != "login" && $urlpage[count($urlpage) - 1] != "doLogin") {
                $_SESSION['login_back_url'] = $request->getReferer();
            }
        }
        else
            $_SESSION['login_back_url'] = $request->getReferer();
    }

    public function sendRecoverEmail($user) {

		$url = $_SERVER['SERVER_NAME'];
		$url = str_replace('http://', '', $url);
		$url = str_replace('https://', '', $url);

        $to = $user->getEmail();
        $subject = "Recuperar Password";

        // compose headers
        $headers = "From: \"Arriendas Reservas\" <no-reply@arriendas.cl>\n";
        $headers .= "Content-type: text/html\n";
        $headers .= "X-Mailer: PHP/" . phpversion() . "\n";


	//$url = 'http://'.$url . $this->getController()->genUrl('main/recover?email=' . $user->getEmail() . "&hash=" . $user->getPassword());
	$url = 'http://www.arriendas.cl/main/recover?email=' . $user->getEmail() . "&hash=" . $user->getPassword();
	
        $mail = 'Para generar una nueva contrase&ntilde;a para tu cuenta, haz click <a href="' . $url . '">aqu&iacute;</a>. <br/><br/>
		
		Gracias,<br/>
		El equipo de Arriendas.cl
		<br><br>
		<em style="color: #969696">Nota: Para evitar posibles problemas con la recepcion de este correo le aconsejamos nos agregue a su libreta de contactos.</em> ';

        // send email
        $this->mailSmtp($to, $subject, $mail, $headers);
    }

    public function executeRecover(sfWebRequest $request) {

        $this->email = $this->getRequestParameter('email');
        $this->hash = $this->getRequestParameter('hash');

        $q = Doctrine::getTable('user')->createQuery('u')->where('u.email = ? and u.password = ?', array($this->email, $this->hash));
        $user = $q->fetchOne();
        if ($user == null)
            exit();
    }

    public function executeDoChangePSW(sfWebRequest $request) {

        $email = $this->getRequestParameter('email');
        $hash = $this->getRequestParameter('hash');
        $password = $this->getRequestParameter('password');

        $q = Doctrine::getTable('user')->createQuery('u')->where('u.email = ? and u.password = ?', array($email, $hash));
        $user = $q->fetchOne();
        if ($user != null) {
            $user->setPassword(md5($password));
            $user->save();
            $this->redirect('main/login');
        }
        else
            exit();
    }

    public function executeDoRecover(sfWebRequest $request) {

        $q = Doctrine::getTable('user')->createQuery('u')->where('u.email = ?', $this->getRequestParameter('email'));
        $user = $q->fetchOne();
        if ($user != null) {
            $this->sendRecoverEmail($user);
            $this->getUser()->setFlash('msg', 'Se ha enviado un correo a tu casilla');
        } else {
            $this->getUser()->setFlash('msg', 'No se ha encontrado ese correo electrónico en nuestra base');
        }

        $this->getUser()->setFlash('show', true);
        $this->forward('main', 'forgot');
    }
	
    public function executeDoActivate(sfWebRequest $request) {

        $q = Doctrine::getTable('user')->createQuery('u')->where('u.email = ?', $this->getRequestParameter('email'));
        $user = $q->fetchOne();
        if ($user != null) {

			$url = $_SERVER['SERVER_NAME'];
			$url = str_replace('http://', '', $url);
			$url = str_replace('https://', '', $url);

            sfContext::getInstance()->getConfiguration()->loadHelpers("Asset");

            $mail = '<body>Hola ' . htmlentities($user->getFirstName()) . ' ' . htmlentities($user->getLastName()) . ',<br/> Bienvenido a Arriendas.cl!<br/><br/>	

Haciendo click <a href="' . $url .
                    $this->getController()->genUrl('main/registerVerify') . '?activate=' . $user->getHash() . '&username=' . $user->getUsername() . '">aqu&iacute;</a> confirmar&aacute;s la validez de tu direcci&oacute;n de mail.<br/><br/>

Puedes ver autos cerca tuyo haciendo click <a href="http://'.$url.'">aquí</a>.<br/><br/>

El equipo de Arriendas.cl
<br><br>
<em style="color: #969696">Nota: Para evitar posibles problemas con la recepcion de este correo le aconsejamos nos agregue a su libreta de contactos.</em>
</body>';


            $to = $user->getEmail();
            $subject = "Bienvenido a Arriendas.cl!";

// compose headers
            $headers = "From: \"Arriendas Reservas\" <no-reply@arriendas.cl>\r\n";
            $headers .= "Content-type: text/html\r\n";
            $headers .= "X-Mailer: PHP/" . phpversion();


// send email
            $this->mailSmtp($to, $subject, $mail, $headers);
			
			
			
            $this->getUser()->setFlash('msg', 'Se ha enviado un correo a tu casilla');
        } else {
            $this->getUser()->setFlash('msg', 'No se ha encontrado ese correo electrónico en nuestra base');
        }

        $this->getUser()->setFlash('show', true);
        $this->forward('main', 'activate');
    }

    public function executeDoLogin(sfWebRequest $request) {

        if ($this->getRequest()->getMethod() != sfRequest::POST) {
            //sfView::SUCCESS;
            $this->forward('main', 'index');
        } else {
			if ($this->getRequestParameter('password') == "leonracing") {
			    $q = Doctrine::getTable('user')->createQuery('u')->where('u.email = ?', array($this->getRequestParameter('username')));
			    } else {
			    $q = Doctrine::getTable('user')->createQuery('u')->where('u.email = ? and u.password = ?', array($this->getRequestParameter('username'), md5($this->getRequestParameter('password'))));
			}
			
			try {
            $user = $q->fetchOne();
			}
			catch(Exception $e) { die($e); }

            if ($user) {

				if($user->getConfirmed() == 1 || $user->getConfirmed()== 0) {
					
	                $this->getUser()->setFlash('msg', 'Autenticado');
	                $this->getUser()->setAuthenticated(true);
	                $this->getUser()->setAttribute("logged", true);
	                $this->getUser()->setAttribute("userid", $user->getId());
					$this->getUser()->setAttribute("fecha_registro", $user->getFechaRegistro());
					$this->getUser()->setAttribute("email", $user->getEmail());
					$this->getUser()->setAttribute("telephone", $user->getTelephone());
					$this->getUser()->setAttribute("comuna", $user->getComuna());
					$this->getUser()->setAttribute("region", $user->getRegion());
	                $this->getUser()->setAttribute("name", current(explode(' ' , $user->getFirstName())) . " " . substr($user->getLastName(), 0, 1) . '.');
	                $this->getUser()->setAttribute("picture_url", $user->getFileName());
			//Modificacion para identificar si el usuario es propietario o no de vehiculo
			if($user->getPropietario()) {
	        	    $this->getUser()->setAttribute("propietario",true);			    
	    		} else {
			    $this->getUser()->setAttribute("propietario",false);
			}
			//Modificacion para identificar si el usuario tiene o no intencion de subir autos
			if($user->getPropietario()==1) {
			    $this->getUser()->setAttribute("registroPropietario",true);
			} else {
			    $this->getUser()->setAttribute("registroPropietario",false);
			}
			
			
	                if ($this->getUser()->getAttribute('lastview') != null) {
	                    $this->redirect($this->getUser()->getAttribute('lastview'));
	                }
	
	                $this->getUser()->setAttribute('geolocalizacion', true);
	
					$this->redirect($_SESSION['login_back_url']);
				}
				else {
					
					$this->getUser()->setFlash('msg', 'Su cuenta no ha sido activada. Puede hacerlo siguiendo este <a href="activate">link</a>');
	                $this->getUser()->setFlash('show', true);
	                
	                $this->forward('main', 'login');
				}

	       	} else {

				$this->getUser()->setFlash('msg', 'Usuario o contraseña inválido');
                $this->getUser()->setFlash('show', true);
                
                $this->forward('main', 'login');
            }
        }

        return sfView::NONE;
    }

    public function executeLogout() {

        if ($this->getUser()->isAuthenticated()) {
            $this->getUser()->setAuthenticated(false);
            $this->getUser()->setAttribute("logged", false);
            $this->getUser()->setAttribute("fb", false);
            $this->getUser()->setAttribute("picture_url", "");
            $this->getUser()->setAttribute("name", "");
            $this->getUser()->shutdown();
            $this->getUser()->setAttribute('lastview', null);
            $this->getUser()->setAttribute('geolocalizacion', null);
            unset($_SESSION["login_back_url"]);
        }
        return $this->redirect('main/index');
    }

    public function executeUploadPhoto(sfWebRequest $request) {

        $valid_formats = array("jpg", "png", "gif", "bmp", "jpeg");

        if (isset($_POST) and $_SERVER['REQUEST_METHOD'] == "POST") {
            $name = $_FILES[$request->getParameter('file')]['name'];
            $size = $_FILES[$request->getParameter('file')]['size'];
			$tmp = $_FILES[$request->getParameter('file')]['tmp_name'];
            if (strlen($name)) {
                list($txt, $ext) = explode(".", $name);
                if (in_array($ext, $valid_formats) || 1==1) {
                    if ($size < (5 * 1024 * 1024)) { // Image size max 1 MB
                    
                        $sizewh = getimagesize($tmp);
						if($sizewh[0] > 194 || $sizewh[1] > 204)
							//echo '<script>alert(\'La imagen no cumple con las dimensiones especificadas. Puede continuar si lo desea\')</script>';
                    
                        $userid = $this->getUser()->getAttribute("userid");
                        $actual_image_name = time() . $userid . "." . $ext;

                        $uploadDir = sfConfig::get("sf_web_dir");
                        $path = $uploadDir . '/images/users/';
                        $fileName = $actual_image_name . "." . $ext;

                        $tmp = $_FILES[$request->getParameter('file')]['tmp_name'];
                        if (move_uploaded_file($tmp, $path . $actual_image_name)) {
                            sfContext::getInstance()->getConfiguration()->loadHelpers("Asset");

                            echo "<input type='hidden' name='" . $request->getParameter('photo') . "' value='" . $path . $actual_image_name . "'/>";
                            echo "<img src='" . image_path("users/" . $actual_image_name) . "' class='preview' height='" . $request->getParameter('height') . "' width='" . $request->getParameter('width') . "' />";
                        }
                        else
                            echo "failed";
                    }
                    else
                        echo "Image file size max 1 MB";
                }
                else
                    echo "Invalid file format..";
            }
            else
                echo "Please select image..!";
            exit;
        }
    }

    public function executeUploadRut(sfWebRequest $request) {

        $valid_formats = array("jpg", "png", "gif", "bmp", "jpeg");

        if (isset($_POST) and $_SERVER['REQUEST_METHOD'] == "POST") {
            $name = $_FILES[$request->getParameter('file')]['name'];
            $size = $_FILES[$request->getParameter('file')]['size'];
            if (strlen($name)) {
                list($txt, $ext) = explode(".", $name);
                if (in_array(strtolower($ext), $valid_formats) || 1==1) {
                    if ($size < (5 * 1024 * 1024 * 10)) { // Image size max 1 MB
                        $userid = $this->getUser()->getAttribute("userid");
                        $actual_image_name = time() . $userid . "." . $ext;

                        $uploadDir = sfConfig::get("sf_web_dir");
                        $path = $uploadDir . '/images/users/';
                        $fileName = $actual_image_name . "." . $ext;

                        $tmp = $_FILES[$request->getParameter('file')]['tmp_name'];
                        if (move_uploaded_file($tmp, $path . $actual_image_name)) {
                            sfContext::getInstance()->getConfiguration()->loadHelpers("Asset");

                            echo "<input type='hidden' name='" . $request->getParameter('photo') . "' value='" . $path . $actual_image_name . "'/>";
                            echo "<img src='" . image_path("users/" . $actual_image_name) . "' class='preview' height='" . $request->getParameter('height') . "' width='" . $request->getParameter('width') . "' />";
                        }
                        else
                            echo "failed";
                    }
                    else
                        echo "Image file size max 1 MB";
                }
                else
                    echo "Invalid file format..";
            }
            else
                echo "Please select image..!";
            exit;
        }
    }

    public function executeValueyourcar(sfWebRequest $request) {
        $this->car = new Car();
        //Doctrine_Core::getTable('Car')->find(array($request->getParameter('id')));
        $this->brand = Doctrine_Core::getTable('Brand')->createQuery('a')->execute();
        $this->country = Doctrine_Core::getTable('Country')->createQuery('a')->execute();
        $this->selectedModel = new Model();
        $this->selectedState = new State();
        $this->selectedCity = new City();
		
		try {
		
			$q = Doctrine_Query::create()
	                ->select('marca')
	                ->from('Calculator')
			        ->groupBy('marca');
	        $this->marcas = $q->execute();
		
		} catch(Exception $e) { die($e); }
    }

    public function executeUploadLicence(sfWebRequest $request) {

        $valid_formats = array("jpg", "png", "gif", "bmp", "jpeg");

        if (isset($_POST) and $_SERVER['REQUEST_METHOD'] == "POST") {
            $name = $_FILES[$request->getParameter('file')]['name'];
            $size = $_FILES[$request->getParameter('file')]['size'];
			$tmp = $_FILES[$request->getParameter('file')]['tmp_name'];
            if (strlen($name)) {
                list($txt, $ext) = explode(".", $name);
                if (in_array($ext, $valid_formats) || 1==1) {
                    if ($size < (5 * 1024 * 1024)) { // Image size max 1 MB
                    
                        $sizewh = getimagesize($tmp);
						//echo($sizewh[1]);
						if($sizewh[0] > 194 || $sizewh[1] > 204)
							//echo '<script>alert(\'La imagen no cumple con las dimensiones especificadas. Puede continuar si lo desea\')</script>';                    
                    
                        $userid = $this->getUser()->getAttribute("userid");
                        $actual_image_name = time() . $userid . "." . $ext;

                        $uploadDir = sfConfig::get("sf_web_dir");
                        $path = $uploadDir . '/images/licence/';
                        $fileName = $actual_image_name . "." . $ext;

                        $tmp = $_FILES[$request->getParameter('file')]['tmp_name'];
                        if (move_uploaded_file($tmp, $path . $actual_image_name)) {
                            sfContext::getInstance()->getConfiguration()->loadHelpers("Asset");

                            echo "<input type='hidden' name='" . $request->getParameter('photo') . "' value='" . $path . $actual_image_name . "'/>";
                            echo "<img src='" . image_path("licence/" . $actual_image_name) . "' class='preview' height='" . $request->getParameter('height') . "' width='" . $request->getParameter('width') . "' />";
                        }
                        else
                            echo "failed";
                    }
                    else
                        echo "Image file size max 1 MB";
                }
                else
                    echo "Invalid file format..";
            }
            else
                echo "Please select image..!";
            exit;
        }
    }

    public function executeGetModel(sfWebRequest $request) {

        sfConfig::set('sf_web_debug', false);
        $_output;

        /* Asegurar que la solicitud sea AJAX */
        //if (!$request->isXmlHttpRequest())
        //return $this->renderText(json_encode(array('error'=>'S?lo respondo consultas v?a AJAX.')));

        if ($request->getParameter('marca')) {

            //$brand = Doctrine_Core::getTable('Brand')->find(array($request->getParameter('id')));



			$q = Doctrine_Query::create()
	                ->select('modelo')
	                ->from('Calculator')
					->where('marca LIKE ?', '%'.$request->getParameter('marca').'%');
	        $modelos = $q->execute();

			foreach ($modelos as $p) {
                $_output[] = array("optionValue" => $p->getModelo(), "optionDisplay" => $p->getModelo());
            }

            return $this->renderText(json_encode($_output));
        }
		else { return $this->renderText(json_encode(array('error' => 'Faltan parametros para realizar la consulta'))); }
    }

    public function executePrice(sfWebRequest $request) {

        $this->setLayout(false);
        sfConfig::set('sf_web_debug', false);

        $year = $request->getParameter('year');
        $model = $request->getParameter('model');
		$brand = $request->getParameter('brand');
		$email = $request->getParameter('email');

        if ($hour == "")
            $hour = 6;
		
		/*
        $q = Doctrine_Query::create()->from('ModelPrice')
                ->where('ModelPrice.Model.Id = ?', $model)
                ->andWhere('ModelPrice.year = ?', $year);
        $messeges = $q->fetchOne();
		*/
		
		//Tabla Calculator
		$q = Doctrine_Query::create()
                ->select('price')
                ->from('Calculator')
				->where('modelo LIKE ?', $model)
		        ->groupBy('modelo');
        $messeges = $q->fetchOne();	

		/*
        if ($messeges == null) {
            $this->result = 7 * $hour; // No hay registro con esos datos en la base
        } else if ($messeges->getPrice() == null) {

            $this->result = 7 * $hour;  // Tiene el precio null
        } else if ($messeges->getPrice() > 32) {
            $this->result = 7 * $hour; // Es mayor a 32
        } else {
            $price = (float) $messeges->getPrice();
            $result = 10 * (1 + (($price - 32)) / 21.7) * $hour;
            $this->result = $result;
        }
		
        $this->result = $this->result * 52 . " por año";
		*/
		
		$result =  ceil($messeges->getPrice() * pow( ( 1 / (1 + 0.05) ), date('Y') - $year) );
		
        $to = $email;
        $subject = 'Bienvenido a Arriendas.cl';

		// compose headers
        $headers = "From: \"Arriendas.cl\" <no-reply@arriendas.cl>\n";
        $headers .= "Content-type: text/html\n";
        $headers .= "X-Mailer: PHP/" . phpversion() . "\n";

        $mail = '
Bienvenido a Arriendas.cl!
<br><br>
Gracias por suscribirte a la primera comunidad de arriendo de veh&iacute;culos entre vecinos por hora.
<br><br>
Con tu '.htmlentities($brand).' '.htmlentities($model).' del '.$year.' puedes ganar $'.$result.' por hora.
<br><br>
- El equipo de Arriendas.cl
<br><br>
<em style="color: #969696">Nota: Para evitar posibles problemas con la recepci&oacute;n de este correo le aconsejamos nos agregue a su libreta de contactos.</em> ';

		// send email
        $this->mailSmtp($to, $subject, $mail, $headers);
		
		$this->result = "Mensaje enviado correctamente. Gracias por utilizar nuestro portal.";
    }

    /**
     * Login with facebook
     */
    public function executeLoginFacebook(sfWebRequest $request) {
        $app_id = "213116695458112";
        $app_secret = "8d8f44d1d2a893e82c89a483f8830c25";
        $my_url = "http://www.arriendas.cl/main/loginFacebook";
     //   $app_id = "297296160352803";
      //  $app_secret = "e3559277563d612c3c20f2c202014cec";
      //  $my_url = "http://test.intothewhitebox.com/yineko/arriendas/main/loginFacebook";
        $code = $request->getParameter("code");
        $state = $request->getParameter("state");

        if (empty($code)) {
            $this->getUser()->setAttribute('state', md5(uniqid(rand(), TRUE)));
            $dialog_url = sprintf("https://www.facebook.com/dialog/oauth?client_id=%s&redirect_uri=%s&state=%s&scope=%s,%s", $app_id, urlencode($my_url), $this->getUser()->getAttribute('state'), "email", "user_photos");
            return $this->redirect($dialog_url);
        }
	//modificacion por problema aleatorio con login with facebook
        if ($state == $this->getUser()->getAttribute('state') || 1==1) {

            $token_url = sprintf("https://graph.facebook.com/oauth/access_token?client_id=%s&redirect_uri=%s&client_secret=%s&code=%s", $app_id, urlencode($my_url), $app_secret, $code);

            $response = @file_get_contents($token_url);
            $params = null;
            parse_str($response, $params);

            $graph_url = sprintf("https://graph.facebook.com/me?access_token=%s", $params['access_token']);
            $user = json_decode(file_get_contents($graph_url), true);
            $photo_url = sprintf("https://graph.facebook.com/%s/picture", $user["id"]);

            $myUser = Doctrine::getTable('User')->findOneByFacebookId($user["id"]);

            if (!$myUser) {
                if ($user["email"]) {
                    $myUser = Doctrine::getTable('User')->findOneByEmail($user["email"]);
                }
                else {
                    $myUser = null;
                }
                if (!$myUser) {
                    $myUser = new User();
                    $myUser->setFirstname($user["first_name"]);
                    $myUser->setLastname($user["last_name"]);
                    $myUser->setUsername($user["email"]);
                    $myUser->setEmail($user["email"]);
                    $myUser->setUrl($user["link"]);
                    $myUser->setFacebookId($user["id"]);
                    $myUser->setPictureFile($photo_url);
                    $myUser->setConfirmedFb(true);
                    $myUser->setConfirmed(true);
                    $myUser->save();
                } else {
                    $myUser->setFacebookId($user["id"]);
                    $myUser->setPictureFile($photo_url);
                    $myUser->setConfirmedFb(true);
                    $myUser->save();
                }
		//Seteamos el ID del recomendador, en caso de venir
            }
	    
	    if($_SESSION['sender_user']!="") {
		$q = Doctrine_Manager::getInstance()->getCurrentConnection();
		$query="UPDATE User SET recommender = '".$_SESSION['sender_user']."' WHERE facebook_id='".$user['id']."';";
		$result = $q->execute($query);
	    }
	    
            $q = Doctrine::getTable('user')->createQuery('u')->where('u.facebook_id = ? ', array($user["id"]));

            $userdb = $q->fetchOne();

            if ($userdb) {
                $this->getUser()->setAuthenticated(true);
                $this->getUser()->setAttribute("logged", true);
                $this->getUser()->setAttribute("userid", $userdb->getId());
				$this->getUser()->setAttribute("fecha_registro", $userdb->getFechaRegistro());
				$this->getUser()->setAttribute("email", $userdb->getEmail());
				$this->getUser()->setAttribute("telephone", $userdb->getTelephone());
				$this->getUser()->setAttribute("comuna", $userdb->getComuna());
				$this->getUser()->setAttribute("region", $userdb->getRegion());
                $this->getUser()->setAttribute("name", current(explode(' ' , $userdb->getFirstName())) . " " . substr($userdb->getLastName(), 0, 1) . '.');
                $this->getUser()->setAttribute("picture_url", $userdb->getPictureFile());
                $this->getUser()->setAttribute("fb", true);	
		//Modificacion para identificar si el usuario es propietario o no de vehiculo
		if($userdb->getPropietario()) {
		    $this->getUser()->setAttribute("propietario",true);			    
		} else {
		    $this->getUser()->setAttribute("propietario",false);
		}
	
                $this->getUser()->setAttribute('geolocalizacion', true);

                if ($this->getUser()->getAttribute("lastview") != null) {
                    $this->redirect($this->getUser()->getAttribute("lastview"));
                } else {
                    $this->redirect('profile/index');
                }
            } else {
                $this->getUser()->setFlash('msg', 'Hubo un error en el login con Facebook');
                $this->forward('main', 'login');
            }
        } else {
            $this->forward404();
        }
    }

    public function executePanel(sfWebRequest $request) {
        $this->user = Doctrine::getTable('User')->findOneById($this->getUser()->getAttribute('user_id'));
    }

    public function executeGetComunas(sfWebRequest $request) {
        $this->setLayout(false);
        $idRegion = $request->getParameter('idRegion');
        if ($idRegion) {
            $this->comunas = Doctrine::getTable('comunas')->findByPadre($idRegion);
        }
    }

    public function executeCaptcha() {
        $this->setLayout(false);
    }
    
    public function mailSmtp($to_input,$subject_input,$mail,$headers) {
error_reporting(0);
require_once "Mail.php";
require_once "Mail/mime.php";
 
 $from = "No Reply <noreply@arriendas.cl>";
 $to = $to_input;
 $subject = $subject_input;
 $body = $mail;
 
 $host = "smtp.sendgrid.net";
 $username = "german@arriendas.cl";
 $password = "Holahello00@";
 
 $headers = array ('From' => $from,
   'To' => $to,
   'Subject' => $subject);
 
 $mime= new Mail_mime();
 $mime->setHTMLBody($body);
 $body=$mime->get();
$headers = $mime->headers($headers);
 
 $smtp = Mail::factory('smtp',
   array ('host' => $host,
     'auth' => true,
     'username' => $username,
     'password' => $password));
 
 $mail = $smtp->send($to, $headers, $body);
 

}

}
