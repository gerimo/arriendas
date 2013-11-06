<?php

/**z
 * profile actions.
 *
 * @package    CarSharing
 * @subpackage profile
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class profileActions extends sfActions {

    /**
     * Executes index action
     *
     * @param sfRequest $request A request object
     */
    public function executeAjaxCalendar(sfWebRequest $request) {
        $this->setLayout(false);
        sfConfig::set('sf_web_debug', false);

        $start = $request->getParameter('start');
        $end = $request->getParameter('end');

        $avs = Doctrine_Core::getTable('Availability')
                ->createQuery('a')
                ->where("a.Car.User.id = ?", $this->getUser()->getAttribute("userid"))
                ->andWhere("
		(a.date_from >= '$start' and a.date_from <= '$end') or 
		(a.date_from <= '$start' and a.date_to >= '$end') or
		(a.date_to >= '$start' and a.date_to <= '$end') or
		(a.date_from <= '$end' and a.date_to = 0) "
                )
                ->execute();


        $aaData = Array();

        foreach ($avs as $a) {

            $i = 0;


            $dateTmp = date("Y-m-d", strtotime($a->getDateFrom()));
            if ($a->getDateTo() != 0)
                $dateTo = date("Y-m-d", strtotime($a->getDateTo()));
            else
                $dateTo = date("Y-m-d", strtotime($end));

            $startDate = date("Y-m-d", strtotime($start));
            $endDate = date("Y-m-d", strtotime($end));


            while ($dateTmp <= $dateTo) {


                $from = $dateTmp . " " . $a->getHourFrom();
                $to = $dateTmp . " " . $a->getHourTo();


                $dayArray = getdate(strtotime($dateTmp . " - 1 days"));

                if ($a->getDay() == null || $a->getDay() == $dayArray['wday']) {

                    if ($startDate <= $dateTmp && $endDate >= $dateTmp)
                        $aaData[] = array('id' => $a->getId(), 'start' => $from, 'end' => $to, 'title' => $a->getCar()->getModel() . " " . $a->getCar()->getModel()->getBrand());
                }

                $dateTmp = date("Y-m-d", strtotime($dateTmp . " + 1 days"));
            }
        }


        return $this->renderText(json_encode($aaData));
    }

    public function executeRatingsHistory(sfWebRequest $request) {

        if ($request->getParameter('id') == "")
            $this->user = Doctrine_Core::getTable('user')->find(array($this->getUser()->getAttribute("userid")));
        else
            $this->user = Doctrine_Core::getTable('user')->find(array($request->getParameter('id')));
//$this->user->getMessegeTo();

        $this->ratingsCompleted = new Doctrine_Collection('rating');

        $reserves = $this->user->getReserves();
        $cars = $this->user->getCars();



        foreach ($cars as $c) {
            $carReserves = $c->getReserves();
            foreach ($carReserves as $cr) {
                if ($cr->getRating()->getId() != null)
                    if ($cr->getRating()->getUserOwnerOpnion() != "" && $cr->getRating()->getUserRenterOpnion() != "") {
                        $this->ratingsCompleted->add($cr->getRating());
                    }
            }
        }
    }

//public function executeEdit(sfWebRequest $request)
//{
// $this->user = Doctrine_Core::getTable('user')->find(array($this->getUser()->getAttribute("userid")));
//}






    public function executeGetAvInfo(sfWebRequest $request) {
        $this->setLayout(false);
        sfConfig::set('sf_web_debug', false);

        $id = $request->getParameter('id');

        $av = Doctrine_Core::getTable('Availability')
                ->createQuery('a')
                ->where("a.id = ?", $id)
                ->fetchOne();

        $aaData = Array();

        if ($av != null) {

            $datetime1 = $av->getDateTo();
            $datetime2 = $av->getDateFrom();


            if ($av->getDateTo() != 0) {

                $diff = abs(strtotime($datetime2) - strtotime($datetime1));
                $days = floor($diff / (60 * 60 * 24));
                if ($av->getDay() != null)
                    $week = $days / 7;
                else
                    $week = $days;
                $week = floor($week);
            }
            else {
                $week = "";
            }

//if($week > floor($week))
//$week = floor($week)+1;

            $aaData[] = array('id' => $av->getId(), 'start' => $av->getHourFrom(), 'end' => $av->getHourTo(), 'id' => $av->getId(), 'datefrom' => date("Y-m-d", strtotime($av->getDateFrom())), 'dateto' => date("Y-m-d", strtotime($av->getDateTo())), 'repeat' => $week, 'carid' => $av->getCar()->getId());
        }
        return $this->renderText(json_encode($aaData));
    }

    public function executeDeleteEvent(sfWebRequest $request) {
        $this->setLayout(false);
        sfConfig::set('sf_web_debug', false);

        $id = $request->getParameter('id');

        $av = Doctrine_Core::getTable('Availability')
                ->createQuery('a')
                ->where("a.id = ?", $id)
                ->fetchOne();

        $av->delete();

        $aaData[] = array('id' => null);
        return $this->renderText(json_encode($aaData));
    }

    public function executeSaveEvent(sfWebRequest $request) {

        $this->setLayout(false);
        sfConfig::set('sf_web_debug', false);

        $id = $request->getParameter('id');
        $date = date("Y-m-d", strtotime($request->getParameter('date')));
        $car = $request->getParameter('carid');
        $days = $request->getParameter('days');
        $start = $request->getParameter('start');
        $end = $request->getParameter('end');
        $repeat = $request->getParameter('repeat');
        $forever = $request->getParameter('forever');
        $dayofweek = $request->getParameter('dayofweek');

        if ($repeat == "all")
            $diff = (60 * 60 * 24) * ($days);
        else
            $diff = (60 * 60 * 24) * ($days * 7);

        $endDate = date("Y-m-d", (strtotime($date) + $diff));

        $av = Doctrine_Core::getTable('Availability')
                ->createQuery('a')
                ->where("a.id = ?", $id)
                ->fetchOne();

        $aaData = Array();

        if ($av == null) {
            $av = new Availability;
        }

        $av->setDateFrom($date);



        if ($repeat == "never")
            $av->setDateTo($date);
        else if ($forever == "checked")
            $av->setDateTo(0);
        else
            $av->setDateTo($endDate);

        if ($repeat == "1day")
            $av->setDay($dayofweek);

        $av->setHourFrom($start);
        $av->setHourTo($end);

        $car = Doctrine_Core::getTable('car')->find(array($car));
        $av->setCar($car);

        $av->save();

        $aaData[] = array('id' => $av->getId());
        return $this->renderText(json_encode($aaData));
    }

    public function executeJsCalendar(sfWebRequest $request) {
        $this->setLayout(false);
        sfConfig::set('sf_web_debug', false);
    }

    public function executeIndex(sfWebRequest $request) {

        $this->user = Doctrine_Core::getTable('user')->find(array($this->getUser()->getAttribute("userid")));
        $this->cars = $this->user->getCars();
    }

    public function executeProfile(sfWebRequest $request) {

        $this->user = Doctrine_Core::getTable('user')->find(array($this->getUser()->getAttribute("userid")));

//$this->user->getMessegeTo();
    }

    public function executePublicprofile(sfWebRequest $request) {

        if ($request->getParameter('id') == "")
            $this->user = Doctrine_Core::getTable('user')->find(array($this->getUser()->getAttribute("userid")));
        else
            $this->user = Doctrine_Core::getTable('user')->find(array($request->getParameter('id')));
//$this->user->getMessegeTo();
    }

    public function executeEdit(sfWebRequest $request) {
        $user = Doctrine_Core::getTable('user')->find(array($this->getUser()->getAttribute("userid")));



        $this->userRegion = $user->getRegion();

        $this->userComuna = $user->getComuna();

        $this->user = $user;


        $q = Doctrine_Query::create()
                ->select('c.*')
                ->from('Comunas c');

        $this->comunas = $q->fetchArray();

        $q = Doctrine_Query::create()
                ->select('r.*')
                ->from('Regiones r');

        $this->regiones = $q->fetchArray();
    }

    public function executePagoDeposito(sfWebRequest $request) {
	
    }
    
    public function executeCars(sfWebRequest $request) {
        $this->user = Doctrine_Core::getTable('user')->find(array($this->getUser()->getAttribute("userid")));
        $this->cars = $this->user->getCars();
    }

    public function executeCar(sfWebRequest $request) {
        $this->car = Doctrine_Core::getTable('car')->find(array($request->getParameter('id')));
        $this->brand = Doctrine_Core::getTable('Brand')->createQuery('a')->execute();
        $this->country = Doctrine_Core::getTable('Country')->createQuery('a')->execute();
        $this->selectedBrand = $this->car->getModel()->getBrand();
        $this->selectedModel = $this->car->getModel();
        $this->selectedState = $this->car->getCity()->getState();
        $this->selectedCity = $this->car->getCity();
        $this->selectedCountry = $this->car->getCity()->getState()->getCountry();
	$this->damages = Doctrine_Core::getTable("Damage")->findByCar($this->car->getId());
	
	//Revisamos que el usuario sea el propietario del auto
	if ($this->car->getUserId() == $this->getUser()->getAttribute("userid")) {
	    $this->getUser()->shutdown();
	} else {
	    $this->redirect("profile/index","200");
	}
    }

    private function getFileExtension($fileName) {
        $parts = explode(".", $fileName);
        return $parts[count($parts) - 1];
    }

    public function executeDoReserve(sfWebRequest $request) {

        $from = $request->getParameter('datefrom');
        $to = $request->getParameter('dateto');
        $carid = $request->getParameter('id');

		$reserve_id = '';
		if( $request->getParameter('reserve_id') ) $reserve_id = $request->getParameter('reserve_id');

        $hourdesde = $request->getParameter('hour_from');
        $hourhasta = $request->getParameter('hour_to');

        if (preg_match('/^\d{1,2}\-\d{1,2}\-\d{4}$/', $from) && preg_match('/^\d{1,2}\-\d{1,2}\-\d{4}$/', $to)) {


			try {
//CORROBORAR SI SE SOLICITO ANTES PERO TIENE DURACION DURANTE EL PEDIDO

            $startDate = date("Y-m-d H:i:s", strtotime($from . ' ' . $hourdesde));
            $endDate = date("Y-m-d H:i:s", strtotime($to . ' ' . $hourhasta));

            $diff = strtotime($endDate) - strtotime($startDate);


            $duration = $diff / 60 / 60;

            if ($diff > 0) {

                $carid = $request->getParameter('id');

                $has_reserve = Doctrine_Core::getTable('Reserve')
                        ->createQuery('a')
                        ->where('((a.date <= ? and date_add(a.date, INTERVAL a.duration HOUR) > ?) or (a.date <= ? and date_add(a.date, INTERVAL a.duration HOUR) > ?)) and (a.Car.id = ?) and (a.confirmed = ?)', array($startDate, $startDate, $endDate, $endDate, $carid, true))
                        ->fetchArray();

                /*
                 * SELECT * FROM Reserve r 
                  WHERE ('2012-05-22 00:00:00' between r.date and date_add(r.date, INTERVAL r.duration HOUR)
                  or '2012-05-27 00:00:00' between r.date and date_add(r.date, INTERVAL r.duration HOUR)
                  or r.date between '2012-05-22 00:00:00' and '2012-05-27 00:00:00'
                  or date_add(r.date, INTERVAL r.duration HOUR) between '2012-05-22 00:00:00' and '2012-05-27 00:00:00')
                  and confirmed = 0
                  and canceled = 0
                  and complete = 0



                try {
                    $q = Doctrine_Core::getTable('Reserve')
                            ->createQuery('r')
                            ->andWhere('(? between r.date and date_add(r.date, INTERVAL r.duration HOUR))', $startDate)
                            ->orWhere('? between r.date and date_add(r.date, INTERVAL r.duration HOUR)', $endDate)
                            ->orWhere('r.date between ? and ?', array($startDate, $endDate))
                            ->orWhere('date_add(r.date, INTERVAL r.duration HOUR) between ? and ?)', array($startDate, $endDate))
                            ->andWhere('r.confirmed = ?', false)
                            ->andWhere('r.canceled = ?', false)
                            ->andWhere('r.complete = ?', false)
                            ->andWhere('r.user_id = ?', $this->getUser()->getAttribute('userid'));
                    $reservasEnEspera = $q->execute();
                } catch (Exception $e) {
                    //echo $q->getSqlQuery();
                    //die($e);
                }
                //echo count($reservasEnEspera);
                //die;
				*/	
				

                if (count($has_reserve) == 0) {

                    if (count($reservasEnEspera) < 5) {
                    	
                        $reserve = new Reserve();
                        $reserve->setDuration($duration);
                        $reserve->setDate($startDate);

                        $user = Doctrine_Core::getTable('User')->find(array($this->getUser()->getAttribute("userid")));
                        $reserve->setUser($user);
                        $car = Doctrine_Core::getTable('Car')->find(array($carid));
                        $reserve->setCar($car);

                        if ($car->getUser()->getAutoconfirm()) {
                        	
                            $reserve->setConfirmed(true);
                        }
						
						//Formato para Chile Ej: 10000.00
						$reserve->setPrice( number_format( $this->calcularMontoTotal($duration, $car->getPricePerHour(), $car->getPricePerDay()) , 2, '.', '') );
						
						if($reserve_id) $reserve->setExtend($reserve_id);
						
                        $reserve->save();

                        if ($car->getUser()->getAutoconfirm()) {
                        	
                            $this->sendConfirmEmail($reserve);
                        } else {
                        	
                            $this->sendReserveEmail($reserve);
                        }

                        if ($car->getUser()->getAutoconfirm()) {
                        	
                            $this->forward('profile', 'reserveConfirmed');
                        } else {
                        	
                            $this->redirect('profile/reserveSend');
                        }
						
                    } else {
                    	
                        $this->getUser()->setFlash('msg', 'Ya tienes 5 reservas en espera de confirmación para este período');
						$this->forward('profile', 'reserve');
                    }
                }
				
                $this->getUser()->setFlash('msg', 'Ya hay una reserva confirmada para ese horario');
                $this->getRequest()->setParameter('carid', $carid);
				$this->getRequest()->setParameter('idreserve', $reserve_id);
				
            } else {
            	
                $this->getUser()->setFlash('msg', 'Fecha de entrega debe ser mayor a fecha inicial');
            }
			
			$this->forward('profile', 'reserve');
			
			} catch(Exception $e) { die($e); }
			
        } else {
        	
            $this->redirect('profile/reserve?id=' . $request->getParameter('id'));
        }
    }

    public function executeReserveConfirmed(sfWebRequest $request) {
        $this->getUser()->setFlash('msg', 'Tu reserva ha sido confirmada. No olvides llevar tu tarjeta de crédito para retirar el auto.');
    }

    public function executeReserveSend(sfWebRequest $request) {
        $this->getUser()->setFlash('msg', 'Su reserva ha sido enviada con éxito. En este momento se encuentra en proceso de aprobación. Gracias por utilizar nuestros servicios.<br/ ><br/ ><a href="pedidos">Continuar</a>');
    }

    public function executeDoSaveCar(sfWebRequest $request) {

        $car = new Car();
        $car->setPricePerDay(floor($request->getParameter('priceday')));
        $car->setPricePerHour(floor($request->getParameter('pricehour')));
        $car->setYear($request->getParameter('year'));
		$car->setPatente($request->getParameter('patente'));
		$car->setTipoBencina($request->getParameter('tipobencina'));
        $car->setKm($request->getParameter('km'));
        $car->setAddress($request->getParameter('address'));
        $model = Doctrine_Core::getTable('Model')->find(array($request->getParameter('model')));
        $car->setModel($model);
        $city = Doctrine_Core::getTable('City')->find(array($request->getParameter('city')));
        $car->setCity($city);
        $car->setLng($request->getParameter('lng'));
        $car->setLat($request->getParameter('lat'));
        $user = Doctrine_Core::getTable('User')->find(array($this->getUser()->getAttribute("userid")));
        $car->setUser($user);
        $car->setDescription($request->getParameter('description'));
        $car->save();

        if ($request->getParameter('main') != null) {
            $q = Doctrine_Query::create()->from('Photo')
                    ->where('Photo.Type = ?', 'main')
                    ->where('Photo.Car.Id = ?', $car->getId());
            $main = $q->fetchOne();

            if (!$main) {
                $main = new Photo;
            }
            $main->setCar($car);
            $main->setPath($request->getParameter('main'));
            $main->setType('main');
            $main->save();
        }

        for ($i = 1; $i <= 5; $i++) {
            if ($request->getParameter('photo' . $i) != null) {
                $q = Doctrine_Query::create()->from('Photo')
                        ->where('Photo.Type = ?', 'photo' . $i)
                        ->andWhere('Photo.Car.Id = ?', $car->getId());
                $photo = $q->fetchOne();

                if (!$photo) {
                    $photo = new Photo;
                }
                $photo->setCar($car);
                $photo->setPath($request->getParameter('photo' . $i));
                $photo->setType('photo' . $i);
                $photo->save();
            }
        }

        for ($i = 1; $i <= 5; $i++) {
            if ($request->getParameter('desperfecto' . $i) != null) {
                $q = Doctrine_Query::create()->from('Photo')
                        ->where('Photo.Type = ?', 'desperfecto' . $i)
                        ->andWhere('Photo.Car.Id = ?', $car->getId());
                $photo = $q->fetchOne();

                if (!$photo) {
                    $photo = new Photo;
                }
                $photo->setCar($car);
                $photo->setPath($request->getParameter('desperfecto' . $i));
                $photo->setType('desperfecto' . $i);
                $photo->save();
            }
        }



        $av = new Availability;
        
        $date = new DateTime('9999-12-31 23:59:59');

        $av->setDateFrom(date("Y-m-d H:i:s"));
        $av->setDateTo($date->format('Y-m-d H:i:s'));
        $av->setHourFrom("00:00:00");
        $av->setHourTo("23:59:00");
        $av->setCar($car);

        $av->save();


        $this->redirect('profile/index');
    }

    public function executeDoUpdateCar(sfWebRequest $request) {
        $car = Doctrine_Core::getTable('Car')->find(array($request->getParameter('id')));
        $car->setPricePerDay(floor($request->getParameter('priceday')));
        $car->setPricePerHour(floor($request->getParameter('pricehour')));
        $car->setYear($request->getParameter('year'));
		$car->setPatente($request->getParameter('patente'));
		$car->setTipoBencina($request->getParameter('tipobencina'));
        $car->setKm($request->getParameter('km'));
        $car->setAddress($request->getParameter('address'));
        $model = Doctrine_Core::getTable('Model')->find(array($request->getParameter('model')));
        $car->setModel($model);
        $city = Doctrine_Core::getTable('City')->find(array($request->getParameter('city')));
        $car->setCity($city);
        $car->setLng($request->getParameter('lng'));
        $car->setLat($request->getParameter('lat'));
        $car->setDescription($request->getParameter('description'));
        $car->save();

		if($request->getParameter('danos') != '') {
		
			Doctrine_Core::getTable('Damage')->findByCarDelete($request->getParameter('id'));
			
			$danos = $request->getParameter('danos');
			$danos = explode(',', $danos);
			foreach($danos as $elem) {
				
				$dano = new Damage();
				$dano->setDescription(trim($elem));
				$dano->setCarId($request->getParameter('id'));
				$dano->save();
			}
		}

        if ($request->getParameter('main') != null) {
            $q = Doctrine_Query::create()->from('Photo')
                    ->where('Photo.Type = ?', 'main')
                    ->where('Photo.Car.Id = ?', $car->getId());
            $main = $q->fetchOne();

            if (!$main) {
                $main = new Photo;
            }
            $main->setCar($car);
            $main->setPath($request->getParameter('main'));
            $main->setType('main');
            $main->save();
        }

        for ($i = 1; $i <= 5; $i++) {
            if ($request->getParameter('photo' . $i) != null) {
                $q = Doctrine_Query::create()->from('Photo')
                        ->where('Photo.Type = ?', 'photo' . $i)
                        ->andWhere('Photo.Car.Id = ?', $car->getId());
                $photo = $q->fetchOne();

                if (!$photo) {
                    $photo = new Photo;
                }
                $photo->setCar($car);
                $photo->setPath($request->getParameter('photo' . $i));
                $photo->setType('photo' . $i);
                $photo->save();
            }
        }

        for ($i = 1; $i <= 5; $i++) {
            if ($request->getParameter('desperfecto' . $i) != null) {
                $q = Doctrine_Query::create()->from('Photo')
                        ->where('Photo.Type = ?', 'desperfecto' . $i)
                        ->andWhere('Photo.Car.Id = ?', $car->getId());
                $photo = $q->fetchOne();

                if (!$photo) {
                    $photo = new Photo;
                }
                $photo->setCar($car);
                $photo->setPath($request->getParameter('desperfecto' . $i));
                $photo->setType('desperfecto' . $i);
                $photo->save();
            }
        }


        $this->redirect('profile/index');
    }

    public function executeRatings(sfWebRequest $request) {
        $this->user = Doctrine_Core::getTable('user')->find(array($this->getUser()->getAttribute("userid")));
        $reserves = $this->user->getReserves();

        $this->ratings = new Doctrine_Collection('rating');
        foreach ($reserves as $r) {

            if ($r->getRating()->getId() != null) {
                if (!$r->getRating()->getComplete()) {
                    $this->ratings->add($r->getRating());
                }
            }
        }


        $this->myRatings = new Doctrine_Collection('rating');
        $cars = $this->user->getCars();

        foreach ($cars as $c) {
            $carReserves = $c->getReserves();
            foreach ($carReserves as $cr) {
                if ($cr->getRating()->getId() != null)
                    if (!$cr->getRating()->getComplete()) {
                        $this->myRatings->add($cr->getRating());
                    }
            }
        }


        $this->ratingsCompleted = new Doctrine_Collection('rating');

        foreach ($cars as $c) {
            $carReserves = $c->getReserves();
            foreach ($carReserves as $cr) {
                if ($cr->getRating()->getId() != null)
                    if (!$cr->getRating()->getComplete()) {
                        $this->ratingsCompleted->add($cr->getRating());
                    }
            }
        }
    }

    public function executeConfirmMyRating(sfWebRequest $request) {




        $rating = Doctrine_Core::getTable('Rating')->find(array($request->getParameter('id')));
        $rating->setUserOwnerOpnion($request->getParameter('user_owner_opnion'));
        $rating->setIntime($request->getParameter('intime'));
        $rating->setQualified($request->getParameter('qualified'));
        $rating->setKm($request->getParameter('km'));
        $rating->setCleanSatisfied($request->getParameter('satisfied'));
        $rating->setComplete(true);

        $rating->save();

        $this->redirect('profile/ratings');
    }

    public function executeConfirmTheyRating(sfWebRequest $request) {

        $rating = Doctrine_Core::getTable('Rating')->find(array($request->getParameter('id')));
        $rating->setUserOwnerOpnion($request->getParameter('user_owner_opnion'));
        $rating->setIntime($request->getParameter('intime'));
        $rating->setQualified($request->getParameter('qualified'));
        $rating->setKm($request->getParameter('km'));
        $rating->setCleanSatisfied($request->getParameter('satisfied'));

        $rating->setComplete(true);

        $rating->save();

        $this->redirect('profile/ratings');
    }

	/* ejemplo llamado methos gettransaction() y savetransaction() */ 
	public function executeTransaction(sfWebRequest $request) {
		
		try {
			
			/*
			$idtrans = '';
			if($request->getParameter('idtrans')) $idtrans = $request->getParameter('idtrans'); 
			
			$trans = Doctrine_Core::getTable("Transaction")->getTransaction($idtrans);
			
			foreach ($trans as $value) {
				
				echo $value['id'];
			}
			*/

			$idreserve = '';
			if($request->getParameter('idreserve')) $idreserve = $request->getParameter('idreserve'); 
			
			$trans = Doctrine_Core::getTable("Transaction")->getTransactionByReserve($idreserve);
			
			echo $trans['id'];
			
		
		} catch(Exception $e) { die($e); }
		
		throw new sfStopException();
	}
	
	/*
	public function executeSaveTransaction(sfWebRequest $request) {
		
		$idreserve = '';
		if($request->getParameter('idreserve')) $idreserve = $request->getParameter('idreserve'); 
		
		$this->trans = Doctrine_Core::getTable("Transaction")->saveTransaction($idreserve);
	}
	
	public function executePuntoPagos() {
		
		$this->trans = Doctrine_Core::getTable("Transaction")->successTransaction(28, '121389126', 1, 1);
	}
	/**/

    public function executeTransactions(sfWebRequest $request) {

        $this->user = Doctrine_Core::getTable('user')->find(array($this->getUser()->getAttribute("userid")));

//GetPositiveRatings()

        $q = Doctrine_Query::create()
                ->select('r.id , ca.id idcar , mo.name model, 
	  br.name brand, ca.year year, 
	  ca.address address, ci.name city, 
	  st.name state, co.name country, 
	  owner.firstname firstname, owner.lastname lastname,
	  r.date date, owner.id userid'
                )
                ->from('Reserve r')
                ->innerJoin('r.Car ca')
                ->innerJoin('ca.Model mo')
                ->innerJoin('ca.User owner')
                ->innerJoin('r.User user')
                ->innerJoin('mo.Brand br')
                ->innerJoin('ca.City ci')
                ->innerJoin('ci.State st')
                ->innerJoin('st.Country co')
                ->where('user.id = ?', $this->getUser()->getAttribute("userid"))
                ->andWhere('r.confirmed = ?', true)
                ->andWhere('r.complete = ?', false);
        $this->rentings = $q->execute();


        $this->transactions = Doctrine_Core::getTable('Transaction')
                ->createQuery('a')
                ->where('a.User.Id = ?', array($this->user->getId()))
                ->andWhere('a.completed != ?', true)
                ->execute();

        $this->paidTransactions = Doctrine_Core::getTable('Transaction')
                ->createQuery('a')
                ->innerJoin('a.Reserve re')
                ->innerJoin('re.Car ca')
                ->where('ca.User.Id = ?', array($this->user->getId()))
                ->andWhere('a.completed = ?', true)
                ->execute();


//	$this->transactions = $this->user->getTransactions();

        $this->reportTypes = Doctrine_Core::getTable('ReportType')->createQuery('a')->execute();

        $this->reports = new Doctrine_Collection('Report');
        $this->currentReports = new Doctrine_Collection('Report');

        foreach ($this->user->getCars() as $car) {

            foreach ($car->getReserves() as $reserve) {


                foreach ($reserve->getReports() as $r) {

                    if ($r->getRenterComment() != "" && $r->getOwnerComment() == "") {
                        $this->currentReports->add($r);
                    }

                    if ($r->getRenterComment() != "" && $r->getOwnerComment() != "") {
                        $this->reports->add($r);
                    }
                }
            }
        }
    }

    public function executeMessages(sfWebRequest $request) {
        /*
          echo $this->messages = $q->getSqlQuery();

          SELECT *
          FROM Message
          WHERE id IN (SELECT MAX(id) FROM Message GROUP BY conversation)
         */

        $q = Doctrine_Query::create()->from('message')
                ->where('message.user_to > ?', $this->getUser()->getAttribute("userid"))
//->andWhere('message.user_from > ?', 2)
                ->andwhere('message.id IN (SELECT MAX(id) FROM Message GROUP BY conversation)');
        $this->messages = $q->fetchArray();
    }

public function executeAgreePdf(sfWebRequest $request)
{

  $carid = $request->getParameter('id');
  
  if($carid) {
 
  $user = Doctrine_Core::getTable('user')->find(array($this->getUser()->getAttribute("userid")));
  $comunaUser = Doctrine_Core::getTable('comunas')->find(array($user->getComuna()));
  $car = Doctrine_Core::getTable('Car')->find(array($carid));
  $dueno = Doctrine_Core::getTable('user')->find(array($car->getUserId()));
  $comunaDueno = Doctrine_Core::getTable('comunas')->find(array($dueno->getComuna()));
  
  $diff = strtotime($request->getParameter('termino')) - strtotime($request->getParameter('inicio'));
  $duration = $diff / 60 / 60;
  $price = $this->calcularMontoTotal($duration, $car->getPricePerHour(), $car->getPricePerDay());
  $price = ceil($price * 0.7);
  
  
  $duracion_contrato= ceil($duration);
  $fecha_inicio= $request->getParameter('inicio');
  $fecha_inicio= substr($fecha_inicio,7,2)."-".substr($fecha_inicio,5,2)."-".substr($fecha_inicio,0,3);
  
  //Daños
  $danos = Doctrine_Query::create()->from("Damage")->where("car_id = ? ",$car->getId());
  $dam = array(); foreach ($danos->execute() as $value) { $dam[] = $value; }	
  $listado_danos = implode(', ',$dam);

  $config = sfTCPDFPluginConfigHandler::loadConfig('pdf_configs');
 
  $doc_title    = "test title";
  $doc_subject  = "test description";
  $doc_keywords = "test keywords";
 
  //create new PDF document (document units are set by default to millimeters)
  $pdf = new sfTCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true);
 
  $pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);
 
  //set margins
  $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
 
  //set auto page breaks
  $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
  $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
  $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
  $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO); //set image scale factor
 
  $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
  $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
 
  //initialize document
  $pdf->AliasNbPages();
  $pdf->AddPage();
 
  $pdf->SetFont("FreeSerif", "", 12);
 
  $utf8text = file_get_contents(K_PATH_CACHE. "contrato_arriendas_chile.txt", false); // get utf-8 text form file
  $utf8text = utf8_encode($utf8text);
  
  //Reemplazo variables
  $utf8text = str_replace('[$fecha_arriendo]', date('d/m/Y'), $utf8text);
  $utf8text = str_replace('[$nombre_dueno]', $dueno->getFirstname()." ".$dueno->getLastname(), $utf8text);
  $utf8text = str_replace('[$rut_dueno]', $dueno->getRut(), $utf8text);
  $utf8text = str_replace('[$domicilio_dueno]', $dueno->getAddress(), $utf8text);
  $utf8text = str_replace('[$comuna_dueno]', $comunaDueno->getNombre(), $utf8text);
  $utf8text = str_replace('[$nombre_usuario]', $user->getFirstname()." ".$user->getLastname(), $utf8text);
  $utf8text = str_replace('[$rut_usuario]', $user->getRut(), $utf8text);
  $utf8text = str_replace('[$domicilio_usuario]', $user->getAddress(), $utf8text);
  $utf8text = str_replace('[$comuna_usuario]', $comunaUser->getNombre(), $utf8text);
  $utf8text = str_replace('[$marca_auto]', $car->getModel()->getBrand(), $utf8text);
  $utf8text = str_replace('[$modelo_auto]', $car->getModel(), $utf8text);
  $utf8text = str_replace('[$ano_auto]', $car->getYear(), $utf8text);
  $utf8text = str_replace('[$patente_auto]', $car->getPatente(), $utf8text);
  $utf8text = str_replace('[$listado_danos_usuario]', $listado_danos, $utf8text);
  //$utf8text = str_replace('[$listado_danos_seguro]', $dueno->getRut(), $utf8text);
    $uft8text= str_replace('[$inicio]',$fecha_inicio,$utf8text);
  $uft8text= str_replace('[$duracion]',$duracion_contrato,$utf8text);
  $utf8text = str_replace('[$precio_arriendo]', '$ '.number_format($price, 0, ',', '.'), $utf8text);
  
  $pdf->SetFillColor(255, 255, 255, true);
  $pdf->Write(5,$utf8text, '', 1);
 
  // Close and output PDF document
  $pdf->Output('contrato_arriendas_'.date('dmY').'pdf', 'I');
 
  // Stop symfony process
  throw new sfStopException();
  
  }
}

    
    
public function executeAgreePdf2(sfWebRequest $request)
{

    //Se modifica la lógica de generación del contrato, de formq que el parámetro recibo sea el ID de la reserva,
    //no el del auto
    /*  Inicio de la modificacion
  $carid = $request->getParameter('id');
  
  if($carid) {
 
  $user = Doctrine_Core::getTable('user')->find(array($this->getUser()->getAttribute("userid")));
  $comunaUser = Doctrine_Core::getTable('comunas')->find(array($user->getComuna()));
  $car = Doctrine_Core::getTable('Car')->find(array($carid));
  $dueno = Doctrine_Core::getTable('user')->find(array($car->getUserId()));
  $comunaDueno = Doctrine_Core::getTable('comunas')->find(array($dueno->getComuna()));
  
  Fin de la modificacion
  */
    $reserveid= $request->getParameter('id');
    $reserve= ReserveTable::getInstance()->findById($reserveid);
    $userId= $reserve[0]->getUserId();
    $carid= $reserve[0]->getCarId();
  
  
  $user = Doctrine_Core::getTable('user')->find($userId);
  $comunaUser = Doctrine_Core::getTable('comunas')->find(array($user->getComuna()));
  $car = Doctrine_Core::getTable('Car')->find(array($carid));
  $dueno = Doctrine_Core::getTable('user')->find(array($car->getUserId()));
  $comunaDueno = Doctrine_Core::getTable('comunas')->find(array($dueno->getComuna()));
  
  $diff = strtotime($request->getParameter('termino')) - strtotime($request->getParameter('inicio'));
  $duration = $diff / 60 / 60;
  $price = $this->calcularMontoTotal($duration, $car->getPricePerHour(), $car->getPricePerDay());
  $price = ceil($price * 0.7);
  
  $duracion_contrato= ceil($duration);
  $fecha_inicio= $request->getParameter('inicio');
  $fecha_inicio= substr($fecha_inicio,7,2)."-".substr($fecha_inicio,5,2)."-".substr($fecha_inicio,0,3);
  
  //Daños
  $danos = Doctrine_Query::create()->from("Damage")->where("car_id = ? ",$car->getId());
  $dam = array(); foreach ($danos->execute() as $value) { $dam[] = $value; }	
  $listado_danos = implode(', ',$dam);

  $config = sfTCPDFPluginConfigHandler::loadConfig('pdf_configs');
 
  $doc_title    = "test title";
  $doc_subject  = "test description";
  $doc_keywords = "test keywords";
 
  //create new PDF document (document units are set by default to millimeters)
  $pdf = new sfTCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true);
 
  $pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);
 
  //set margins
  $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
 
  //set auto page breaks
  $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
  $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
  $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
  $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO); //set image scale factor
 
  $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
  $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
 
  //initialize document
  $pdf->AliasNbPages();
  $pdf->AddPage();
 
  $pdf->SetFont("FreeSerif", "", 12);
 
  $utf8text = file_get_contents(K_PATH_CACHE. "contrato_arriendas_chile.txt", false); // get utf-8 text form file
  $utf8text = utf8_encode($utf8text);
  
  //Reemplazo variables
  $utf8text = str_replace('[$fecha_arriendo]', date('d/m/Y'), $utf8text);
  $utf8text = str_replace('[$nombre_dueno]', $dueno->getFirstname()." ".$dueno->getLastname(), $utf8text);
  $utf8text = str_replace('[$rut_dueno]', $dueno->getRut(), $utf8text);
  $utf8text = str_replace('[$domicilio_dueno]', $dueno->getAddress(), $utf8text);
  $utf8text = str_replace('[$comuna_dueno]', $comunaDueno->getNombre(), $utf8text);
  $utf8text = str_replace('[$nombre_usuario]', $user->getFirstname()." ".$user->getLastname(), $utf8text);
  $utf8text = str_replace('[$rut_usuario]', $user->getRut(), $utf8text);
  $utf8text = str_replace('[$domicilio_usuario]', $user->getAddress(), $utf8text);
  $utf8text = str_replace('[$comuna_usuario]', $comunaUser->getNombre(), $utf8text);
  $utf8text = str_replace('[$marca_auto]', $car->getModel()->getBrand(), $utf8text);
  $utf8text = str_replace('[$modelo_auto]', $car->getModel(), $utf8text);
  $utf8text = str_replace('[$ano_auto]', $car->getYear(), $utf8text);
  $utf8text = str_replace('[$patente_auto]', $car->getPatente(), $utf8text);
  $utf8text = str_replace('[$listado_danos_usuario]', $listado_danos, $utf8text);
  //$utf8text = str_replace('[$listado_danos_seguro]', $dueno->getRut(), $utf8text);
  $utf8text = str_replace('[$precio_arriendo]', '$ '.number_format($price, 0, ',', '.'), $utf8text);
  $uft8text = str_replace('[$fecha_inicio]', $fecha_inicio, $utf8text);
  $uft8text = str_replace('[$duracion]', $duracion_contrato, $utf8text);
  
  $pdf->SetFillColor(255, 255, 255, true);
  $pdf->Write(5,$utf8text, '', 1);
 
  // Close and output PDF document
  $pdf->Output('contrato_arriendas_'.date('dmY').'pdf', 'I');
 
  // Stop symfony process
  throw new sfStopException();
  

}

     public function executeReserve(sfWebRequest $request) {
        if ($this->getRequest()->getParameter('carid') != null)
            $carid = $this->getRequest()->getParameter('carid');
        else
            $carid = $request->getParameter('id');

		$this->reserve = NULL;
		if( $request->getParameter('idreserve') ) {

	        $q = Doctrine_Query::create()
	                ->select('r.id , r.date date, ADDDATE(r.date, INTERVAL r.duration HOUR) fechafin')
	                ->from('Reserve r')
	                ->where('r.id = ?', $request->getParameter('idreserve'));
	        $this->reserve = $q->fetchArray();
		}

        $this->car = Doctrine_Core::getTable('car')->find(array($carid));
        $this->user = $this->car->getUser();
        $this->getUser()->setAttribute('lastview', $request->getReferer());
		
		$this->fndreserve = array('fechainicio' => '', 'horainicio' => '', 'fechatermino' => '', 'horatermino' => '');
		
		if($this->getUser()->getAttribute("fechainicio")) $this->fndreserve['fechainicio'] = $this->getUser()->getAttribute("fechainicio");
		if($this->getUser()->getAttribute("horainicio")) $this->fndreserve['horainicio'] = $this->getUser()->getAttribute("horainicio");
		if($this->getUser()->getAttribute("fechatermino")) $this->fndreserve['fechatermino'] = $this->getUser()->getAttribute("fechatermino");
		if($this->getUser()->getAttribute("horatermino")) $this->fndreserve['horatermino'] = $this->getUser()->getAttribute("horatermino");

    }

     public function executePayReserve(sfWebRequest $request) {

		$this->reserve = '';
		if( $request->getParameter('id') ) {
			
			try {

		        $this->reserve = Doctrine_Core::getTable('Reserve')->find(array( $request->getParameter('id') ));
		        $this->car = Doctrine_Core::getTable('car')->find(array( $this->reserve->getCar()->getId() ));
				$this->model = Doctrine_Core::getTable('Model')->find(array( $this->car->getModel()->getId() ));
		        $this->user = $this->car->getUser();
				
				$this->trans = Doctrine_Core::getTable("Transaction")->getTransactionByReserve($this->reserve->getId());
				
		        $this->getUser()->setAttribute('lastview', $request->getReferer());
			
			} catch(Exception $e) { die($e); }
		}
    }
	 
     public function executePedidos(sfWebRequest $request) {
        $q = Doctrine_Query::create()
                ->select('r.id , ca.id idcar , mo.name model, 
	  br.name brand, ca.year year, 
	  ca.address address, ci.name city, 
	  st.name state, co.name country, 
	  user.firstname firstname, user.lastname lastname,
	  r.date date, ADDDATE(r.date, INTERVAL r.duration HOUR) fechafin'
                )
                ->from('Reserve r')
                ->innerJoin('r.Car ca')
                ->innerJoin('ca.Model mo')
                ->innerJoin('ca.User owner')
                ->innerJoin('r.User user')
                ->innerJoin('mo.Brand br')
                ->innerJoin('ca.City ci')
                ->innerJoin('ci.State st')
                ->innerJoin('st.Country co')
                ->where('owner.id = ?', $this->getUser()->getAttribute("userid"))
                ->andWhere('r.confirmed = ?', true)
                ->andWhere('r.complete = ?', false)
                ->andWhere('r.canceled = ?', false)
                ->andWhere('(r.ini_km_owner_confirmed = ?', false)
                ->orWhere('r.ini_km_confirmed = ?)', false);
        $this->cars = $q->execute();

        try {

            $q = Doctrine_Query::create()
                    ->select('r.id , ca.id idcar , mo.name model, 
	  br.name brand, ca.year year, 
          ca.price_per_hour pricehour, ca.price_per_day priceday,
	  ca.address address, ci.name city, 
	  st.name state, co.name country, 
	  user.firstname firstname, user.lastname lastname,
	  r.date date, r.duration, ADDDATE(r.date, INTERVAL r.duration HOUR) fechafin'
                    )
                    ->from('Reserve r')
                    ->innerJoin('r.Car ca')
                    ->innerJoin('ca.Model mo')
                    ->innerJoin('ca.User owner')
                    ->innerJoin('r.User user')
                    ->innerJoin('mo.Brand br')
                    ->innerJoin('ca.City ci')
                    ->innerJoin('ci.State st')
                    ->innerJoin('st.Country co')
                    ->where('r.user_id = ?', $this->getUser()->getAttribute("userid"))
                    ->andWhere('r.confirmed = ?', false)
                    ->andWhere('r.complete = ?', false)
                    ->andWhere('r.canceled = ?', false)
                    ->orderBy('r.date');
//->andWhere('r.date > ?',  date('Y-m-d H:i:s.u'));

            $this->misreservas = $q->execute();
//echo $q->getSqlQuery();die;
        } catch (Exception $e) {
            echo $e->getMessage();
        }
        try {

            $q = Doctrine_Query::create()
                    ->select('r.id , ca.id idcar , mo.name model, 
	  br.name brand, ca.year year, 
          ca.price_per_hour pricehour, ca.price_per_day priceday,
	  ca.address address, ci.name city, 
	  st.name state, co.name country, 
	  user.firstname firstname, user.lastname lastname,
	  r.date date, r.duration,ADDDATE(r.date, INTERVAL r.duration HOUR) fechafin'
                    )
                    ->from('Reserve r')
                    ->innerJoin('r.Car ca')
                    ->innerJoin('ca.Model mo')
                    ->innerJoin('ca.User owner')
                    ->innerJoin('r.User user')
                    ->innerJoin('mo.Brand br')
                    ->innerJoin('ca.City ci')
                    ->innerJoin('ci.State st')
                    ->innerJoin('st.Country co')
                    ->where('r.user_id = ?', $this->getUser()->getAttribute("userid"))
                    ->andWhere('r.confirmed = ?', true)
                    ->andWhere('r.canceled = ?', false)
                    ->andWhere('r.complete = ?', false)
                    ->andWhere('(r.ini_km_owner_confirmed = ?', false)
                    ->orWhere('r.ini_km_confirmed = ?)', false);

//->andWhere('r.date > ?',  date('Y-m-d H:i:s.u'));

            $this->misreservasconfirmadas = $q->execute();
//echo $q->getSqlQuery();die;
        } catch (Exception $e) {
            echo $e->getMessage();
        }

        try {
            $q = Doctrine_Query::create()
                    ->select('r.id , ca.id idcar , mo.name model, 
	  br.name brand, ca.year year, 
	  ca.address address, ci.name city, 
	  st.name state, co.name country, 
	  user.firstname firstname, user.lastname lastname,
	  r.date date, ADDDATE(r.date, INTERVAL r.duration HOUR) fechafin'
                    )
                    ->from('Reserve r')
                    ->innerJoin('r.Car ca')
                    ->innerJoin('ca.Model mo')
                    ->innerJoin('ca.User owner')
                    ->innerJoin('r.User user')
                    ->innerJoin('mo.Brand br')
                    ->innerJoin('ca.City ci')
                    ->innerJoin('ci.State st')
                    ->innerJoin('st.Country co')
                    ->where('r.user_id = ?', $this->getUser()->getAttribute("userid"))
                    ->andWhere('r.confirmed = ?', true)
                    ->andWhere('r.complete = ?', false)
                    ->andWhere('r.canceled = ?', false)
                    ->andWhere('r.ini_km_owner_confirmed = ?', true)
                    ->andWhere('r.ini_km_confirmed = ?', true);

//->andWhere('r.date > ?',  date('Y-m-d H:i:s.u'));
            $this->inprogress = $q->execute();
        } catch (Exception $e) {
            
        }
    }


    public function executeAddCar(sfWebRequest $request) {
        $this->car = new Car();
//Doctrine_Core::getTable('Car')->find(array($request->getParameter('id')));
        $this->brand = Doctrine_Core::getTable('Brand')->createQuery('a')->execute();
        $this->country = Doctrine_Core::getTable('Country')->createQuery('a')->execute();
        $this->selectedBrand = new Brand();
        $this->selectedModel = new Model();
        $this->selectedState = new State();
        $this->selectedCity = new City();
        $this->selectedCountry = new Country();
    }

    public function executeAddAvailability(sfWebRequest $request) {

        $this->user = Doctrine_Core::getTable('user')->find(array($this->getUser()->getAttribute("userid")));
        $this->cars = $this->user->getCars();
    }

    public function executeAprobarKm(sfWebRequest $request) {
        $id = $request->getParameter('id');
        $tipo = $request->getParameter('tipo');

        try {

            $reserva = Doctrine_Core::getTable('reserve')->find(array($id));

            if ($reserva->getUserId() != $this->getUser()->getAttribute('userid')) {

                if ($tipo == 'inicial') {
                    $reserva->setIniKmOwnerConfirmed(true);
                } else {
                    $reserva->setEndKmOwnerConfirmed(true);
                }
            } else {
                if ($tipo == 'inicial') {
                    $reserva->setIniKmConfirmed(true);
                } else {
                    $reserva->setEndKmConfirmed(true);
                }
            }

            $reserva->save();

            echo 'OK';
        } catch (Exception $e) {
            echo 'Error ' . $e->getMessage();
        }

        return sfView::NONE;
    }

    public function executeDesaprobarKm(sfWebRequest $request) {
        $id = $request->getParameter('id');
        $tipo = $request->getParameter('tipo');

        try {

            $reserva = Doctrine_Core::getTable('reserve')->find(array($id));
            

            if ($tipo == 'inicial') {
                $reserva->setIniKmOwnerConfirmed(false);
                $reserva->setIniKmConfirmed(false);
                $reserva->setKminicial(0);

            } else {
                $reserva->setEndKmOwnerConfirmed(false);
                $reserva->setEndKmConfirmed(false);
                $reserva->setKmfinal(0);
            }

            if ($this->getUser()->getAttribute('userid') == $reserva->getCar()->getUserId()){
                $this->sendConfirmKmsEmail($reserva, 'usuario');
            } else {
                $this->sendConfirmKmsEmail($reserva, 'owner');
            }
            $reserva->save();

            echo 'OK';
        } catch (Exception $e) {
            echo 'Error ' . $e->getMessage();die;
        }

        return sfView::NONE;
    }

    /* public function executeRentings(sfWebRequest $request)
      {

      $this->car = Doctrine_Core::getTable('car')->find(array($request->getParameter('id')));

      }
     */

    public function executeRental(sfWebRequest $request) {

        $q = Doctrine_Query::create()
                ->select('r.id , ca.id idcar , mo.name model, 
	  br.name brand, ca.year year, 
	  ca.address address, ci.name city, 
	  st.name state, co.name country, 
	  user.firstname firstname, user.lastname lastname,
	  r.date date, ADDDATE(r.date, INTERVAL r.duration HOUR) fechafin'
                )
                ->from('Reserve r')
                ->innerJoin('r.Car ca')
                ->innerJoin('ca.Model mo')
                ->innerJoin('ca.User owner')
                ->innerJoin('r.User user')
                ->innerJoin('mo.Brand br')
                ->innerJoin('ca.City ci')
                ->innerJoin('ci.State st')
                ->innerJoin('st.Country co')
                ->where('owner.id = ?', $this->getUser()->getAttribute("userid"))
                ->andWhere('r.confirmed = ?', true)
                ->andWhere('r.complete = ?', false)
                ->andWhere('r.canceled = ?', false)
                ->andWhere('r.ini_km_owner_confirmed = ?', true)
                ->andWhere('r.ini_km_confirmed = ?', true);

//->andWhere('r.date > ?',  date('Y-m-d H:i:s.u'));
        $this->ownerinprogress = $q->execute();

        $q = Doctrine_Query::create()
                ->select('r.id , ca.id idcar , mo.name model, 
	  br.name brand, ca.year year, 
	  ca.address address, ci.name city, 
	  st.name state, co.name country, 
	  user.firstname firstname, user.lastname lastname,
	  r.date date, ADDDATE(r.date, INTERVAL r.duration HOUR) fechafin'
                )
                ->from('Reserve r')
                ->innerJoin('r.Car ca')
                ->innerJoin('ca.Model mo')
                ->innerJoin('ca.User owner')
                ->innerJoin('r.User user')
                ->innerJoin('mo.Brand br')
                ->innerJoin('ca.City ci')
                ->innerJoin('ci.State st')
                ->innerJoin('st.Country co')
                ->where('owner.id = ?', $this->getUser()->getAttribute("userid"))
                ->andWhere('r.confirmed = ?', true)
                ->andWhere('r.complete = ?', false)
                ->andWhere('r.canceled = ?', false)
                ->andWhere('(r.ini_km_owner_confirmed = ?', false)
                ->orWhere('r.ini_km_confirmed = ?)', false);
        $this->cars = $q->execute();

        try {
            $q = Doctrine_Query::create()
                    ->select('r.id , ca.id idcar , mo.name model, 
	  br.name brand, ca.year year, 
	  ca.address address, ci.name city, 
	  st.name state, co.name country, 
	  user.firstname firstname, user.lastname lastname,
	  r.date date, ADDDATE(r.date, INTERVAL r.duration HOUR) fechafin'
                    )
                    ->from('Reserve r')
                    ->innerJoin('r.Car ca')
                    ->innerJoin('ca.Model mo')
                    ->innerJoin('ca.User owner')
                    ->innerJoin('r.User user')
                    ->innerJoin('mo.Brand br')
                    ->innerJoin('ca.City ci')
                    ->innerJoin('ci.State st')
                    ->innerJoin('st.Country co')
                    ->where('r.user_id = ?', $this->getUser()->getAttribute("userid"))
                    ->andWhere('r.confirmed = ?', true)
                    ->andWhere('r.complete = ?', false)
                    ->andWhere('r.canceled = ?', false)
                    ->andWhere('r.ini_km_owner_confirmed = ?', true)
                    ->andWhere('r.ini_km_confirmed = ?', true);

//->andWhere('r.date > ?',  date('Y-m-d H:i:s.u'));
            $this->inprogress = $q->execute();
        } catch (Exception $e) {
            
        }
		
            $q = Doctrine_Query::create()
                    ->select('r.id , ca.id idcar , mo.name model, 
	  br.name brand, ca.year year, 
	  ca.address address, ci.name city, 
	  st.name state, co.name country, 
	  user.firstname firstname, user.lastname lastname,
	  r.date date, ADDDATE(r.date, INTERVAL r.duration HOUR) fechafin '
                    )
                    ->from('Reserve r')
                    ->innerJoin('r.Car ca')
                    ->innerJoin('ca.Model mo')
                    ->innerJoin('ca.User owner')
                    ->innerJoin('r.User user')
                    ->innerJoin('mo.Brand br')
                    ->innerJoin('ca.City ci')
                    ->innerJoin('ci.State st')
                    ->innerJoin('st.Country co')
                    ->where('owner.id = ?', $this->getUser()->getAttribute("userid"))
                    ->andWhere('r.confirmed = ?', false)
                    ->andWhere('r.complete = ?', false)
                    ->andWhere('r.canceled = ?', false);
            $this->toconfirm = $q->execute();		
    }

    public function executeRentalToConfirm(sfWebRequest $request) {

        try {
            $q = Doctrine_Query::create()
                    ->select('r.id , ca.id idcar , mo.name model, 
	  br.name brand, ca.year year, 
	  ca.address address, ci.name city, 
	  st.name state, co.name country, 
	  user.firstname firstname, user.lastname lastname,
	  r.date date, ADDDATE(r.date, INTERVAL r.duration HOUR) fechafin '
                    )
                    ->from('Reserve r')
                    ->innerJoin('r.Car ca')
                    ->innerJoin('ca.Model mo')
                    ->innerJoin('ca.User owner')
                    ->innerJoin('r.User user')
                    ->innerJoin('mo.Brand br')
                    ->innerJoin('ca.City ci')
                    ->innerJoin('ci.State st')
                    ->innerJoin('st.Country co')
                    ->where('owner.id = ?', $this->getUser()->getAttribute("userid"))
                    ->andWhere('r.confirmed = ?', false)
                    ->andWhere('r.complete = ?', false)
                    ->andWhere('r.canceled = ?', false);
            $this->toconfirm = $q->execute();
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    public function getMes($month) {
        
    }

    public function sendConfirmEmail($reserve) {

		$url = $_SERVER['SERVER_NAME'];
		$url = str_replace('http://', '', $url);
		$url = str_replace('https://', '', $url);

        $to = $reserve->getUser()->getEmail();
        $subject = "Reserva Confirmada";

// compose headers
        $headers = "From: \"Arriendas Reservas\" <no-reply@arriendas.cl>\n";
        $headers .= "Content-type: text/html\n";
        $headers .= "X-Mailer: PHP/" . phpversion() . "\n";

        ereg("([0-9]{2,4})-([0-9]{1,2})-([0-9]{1,2}) ([0-9]{1,2}):([0-9]{1,2}):([0-9]{1,2})", $reserve->getDate(), $datetime);

        $date_from = strftime("%A %e de %B", mktime($datetime[4], $datetime[5], 0, $datetime[2], $datetime[3], $datetime[1]));
        $hour_from = date("H:s", mktime($datetime[4], $datetime[5], 0, $datetime[2], $datetime[3], $datetime[1]));

        $date_to = strftime("%A %e de %B", mktime($datetime[4] + $reserve->getDuration(), $datetime[5], 0, $datetime[2], $datetime[3], $datetime[1]));
        $hour_to = date("H:s", mktime($datetime[4] + $reserve->getDuration(), $datetime[5], 0, $datetime[2], $datetime[3], $datetime[1]));
        $url = 'http://'.$url . $this->getController()->genUrl('profile/publicprofile?id=' . $reserve->getCar()->getUser()->getId());

        $mail = 'Su reserva para el auto ' . $reserve->getCar()->getModel()->getBrand()->getName() . ' ' . $reserve->getCar()->getModel()->getName() .
                ' del usuario ' . $reserve->getCar()->getUser()->getFirstName() . ' ' . $reserve->getCar()->getUser()->getLastName() . ' ha sido confirmada.<br/><br/>
	
	Debes retirar el auto el d&iacute;a <b>' . $date_from . '</b> a las <b>' . $hour_from . '</b> y devolverlo el d&iacute;a <b>' . $date_to . '</b> a las <b>' . $hour_to . '</b>.<br/>
	Recuerda que debes verificar el estado del auto antes de subirte. Si el auto presenta otros daños debes cancelar la reserva. <br /><br />
	
	El equipo de Arriendas.cl
	<br><br>
	<em style="color: #969696">Nota: Para evitar posibles problemas con la recepcion de este correo le aconsejamos nos agregue a su libreta de contactos.</em> 
	';


/*
	Para ver el perfil del due&ntilde;o del auto ' .
                $reserve->getCar()->getUser()->getFirstname() . ' ' . $reserve->getCar()->getUser()->getLastname() . ' has click <a href="' . $url . '">aqui</a>. <br/><br/>
*/ 

// send email
        $this->smtpMail($to, $subject, $mail, $headers);

//MAIL DUEÑO

        $to = $reserve->getCar()->getUser()->getEmail();
        $subject = "Has recibido una reserva!";

// compose headers

		$url = $_SERVER['SERVER_NAME'];
		$url = str_replace('http://', '', $url);
		$url = str_replace('https://', '', $url);

        $headers = "From: \"Arriendas Reservas\" <no-reply@arriendas.cl>\n";
        $headers .= "Content-type: text/html\n";
        $headers .= "X-Mailer: PHP/" . phpversion() . "\n";

        ereg("([0-9]{2,4})-([0-9]{1,2})-([0-9]{1,2}) ([0-9]{1,2}):([0-9]{1,2}):([0-9]{1,2})", $reserve->getDate(), $datetime);

        $date_from = strftime("%A %e de %B", mktime($datetime[4], $datetime[5], 0, $datetime[2], $datetime[3], $datetime[1]));
        $hour_from = date("H:s", mktime($datetime[4], $datetime[5], 0, $datetime[2], $datetime[3], $datetime[1]));

        $date_to = strftime("%A %e de %B", mktime($datetime[4] + $reserve->getDuration(), $datetime[5], 0, $datetime[2], $datetime[3], $datetime[1]));
        $hour_to = date("H:s", mktime($datetime[4] + $reserve->getDuration(), $datetime[5], 0, $datetime[2], $datetime[3], $datetime[1]));
        $url = 'http://'.$url . $this->getController()->genUrl('profile/publicprofile?id=' . $reserve->getUser()->getId());

        $mail = 'Has recibido una reserva por tu ' .
                $reserve->getCar()->getModel()->getBrand()->getName() . ' ' . $reserve->getCar()->getModel()->getName() .
                ' desde el d&iacute;a ' . $date_from . ' a las ' . $hour_from .
                ' hasta el d&iacute;a ' . $date_to . ' a las ' . $hour_to . ' cuando el usuario ' . $reserve->getUser()->getFirstname() . ' ' . $reserve->getUser()->getLastname() . ' devuelva el auto. <br/>'.
		'Recuerda que debes verificar el estado del auto en la devolución. De haber ocurrido daños al auto durante el arriendo, debes informar a Arriendas.cl en el acto.<br /><br />
	
	El equipo de Arriendas.cl
	<br><br>
	<em style="color: #969696">Nota: Para evitar posibles problemas con la recepcion de este correo le aconsejamos nos agregue a su libreta de contactos.</em> 
	';
	
	
	
	/*
		
	Para ver el perfil del usuario ' .
                $reserve->getUser()->getFirstname() . ' ' . $reserve->getUser()->getLastname() . ' has click <a href="' . $url . '">aqui</a>. <br/><br/>
	*/
	

// send email
        $this->smtpMail($to, $subject, $mail, $headers);
    }

    public function sendReserveEmail($reserve) {

        $to = $reserve->getUser()->getEmail();
        $subject = 'Has realizado una reserva!';

// compose headers
        $headers = "From: \"Arriendas Reservas\" <no-reply@arriendas.cl>\n";
        $headers .= "Content-type: text/html\n";
        $headers .= "X-Mailer: PHP/" . phpversion() . "\n";

        $mail = 'Se ha solicitado una reserva para el auto ' . $reserve->getCar()->getModel()->getBrand()->getName() . ' ' . $reserve->getCar()->getModel()->getName() .
                ' del usuario ' . $reserve->getCar()->getUser()->getFirstName() . ' ' . $reserve->getCar()->getUser()->getLastName() . '.
	
	Gracias
	Arriendas.cl
	<br><br>
	<em style="color: #969696">Nota: Para evitar posibles problemas con la recepcion de este correo le aconsejamos nos agregue a su libreta de contactos.</em> 
	';

// send email
        $this->smtpMail($to, $subject, $mail, $headers);

//MAIL DUEÑO

        $to = $reserve->getCar()->getUser()->getEmail();
        $subject = 'Has recibido un pedido de reserva!';

// compose headers
		$url = $_SERVER['SERVER_NAME'];
		$url = str_replace('http://', '', $url);
		$url = str_replace('https://', '', $url);

        $headers = "From: \"Arriendas Reservas\" <no-reply@arriendas.cl>\n";
        $headers .= "Content-type: text/html\n";
        $headers .= "X-Mailer: PHP/" . phpversion() . "\n";

        ereg("([0-9]{2,4})-([0-9]{1,2})-([0-9]{1,2}) ([0-9]{1,2}):([0-9]{1,2}):([0-9]{1,2})", $reserve->getDate(), $datetime);

        $date_from = strftime("%A %e de %B", mktime($datetime[4], $datetime[5], 0, $datetime[2], $datetime[3], $datetime[1]));
        $hour_from = date("H:s", mktime($datetime[4], $datetime[5], 0, $datetime[2], $datetime[3], $datetime[1]));

        $date_to = strftime("%A %e de %B", mktime($datetime[4] + $reserve->getDuration(), $datetime[5], 0, $datetime[2], $datetime[3], $datetime[1]));
        $hour_to = date("H:s", mktime($datetime[4] + $reserve->getDuration(), $datetime[5], 0, $datetime[2], $datetime[3], $datetime[1]));
        $url = 'http://'.$url . $this->getController()->genUrl('profile/publicprofile?id=' . $reserve->getUser()->getId());

		$site = $_SERVER['SERVER_NAME'];
		$site = str_replace('http://', '', $site);
		$site = str_replace('https://', '', $site);

        $mail = 'Has recibido un pedido de reserva por tu ' .
                $reserve->getCar()->getModel()->getBrand()->getName() . ' ' . $reserve->getCar()->getModel()->getName() .
                ' desde el día <b>' . $date_from . '</b> a las <b>' . $hour_from .
                '</b> hasta el día <b>' . $date_to . '</b> a las <b>' . $hour_to . '</b> cuando te habrán devuelto el auto. <br/><br/>
	
		Para confirmar la reserva haga click <a href="http://'.$site . $this->getController()->genUrl('profile/rentalToConfirm').'">aquí</a><br/><br/>
	
	El equipo de Arriendas.cl
	<br><br>
	<em style="color: #969696">Nota: Para evitar posibles problemas con la recepcion de este correo le aconsejamos nos agregue a su libreta de contactos.</em>	
	';
	
	
	/*
		Para ver el perfil del usuario ' .
                $reserve->getUser()->getFirstname() . ' ' . $reserve->getUser()->getLastname() . ' has click <a href="' . $url . '">aqui</a>. <br/><br/>
	*/
	

// send email
        $this->smtpMail($to, $subject, $mail, $headers,"german@arriendas.cl");
    }

    public function sendEndReserveEmail($reserve) {

        $to = $reserve->getUser()->getEmail();
        $subject = 'Finalizacion de Reserva';

// compose headers
        $headers = "From: \"Arriendas Reservas\" <no-reply@arriendas.cl>\n";
        $headers .= "Content-type: text/html\n";
        $headers .= "X-Mailer: PHP/" . phpversion() . "\n";

        $mail = 'Ha finalizado la reseva para el auto ' . $reserve->getCar()->getModel()->getBrand()->getName() . ' ' . $reserve->getCar()->getModel()->getName() .
                ' del usuario ' . $reserve->getCar()->getUser()->getFirstName() . ' ' . $reserve->getCar()->getUser()->getLastName() . ' que usted realizo.
	
	Gracias
	Arriendas.cl
	<br><br>
	<em style="color: #969696">Nota: Para evitar posibles problemas con la recepcion de este correo le aconsejamos nos agregue a su libreta de contactos.</em> 
	';

// send email
        $this->smtpMail($to, $subject, $mail, $headers);

//MAIL ALQUILANDO
//MAIL DUEÑO

        $to = $reserve->getCar()->getUser()->getEmail();
        $subject = 'Finalizacion de Reserva';

// compose headers
        $headers = "From: \"Arriendas Reservas\" <no-reply@arriendas.cl>\n";
        $headers .= "Content-type: text/html\n";
        $headers .= "X-Mailer: PHP/" . phpversion() . "\n";

        $mail = 'Ha solicitado la reserva de su auto ' . $reserve->getCar()->getModel()->getBrand()->getName() . ' ' . $reserve->getCar()->getModel()->getName() .
                ' alquilado por el usuario ' . $reserve->getUser()->getFirstName() . ' ' . $reserve->getUser()->getLastName() . '.
	
	Gracias
	Arriendas.cl
	<br><br>
	<em style="color: #969696">Nota: Para evitar posibles problemas con la recepcion de este correo le aconsejamos nos agregue a su libreta de contactos.</em> 
	';

// send email
        $this->smtpMail($to, $subject, $mail, $headers);
    }

    public function executeCancelReserveConfirmation(sfWebRequest $request) {

        $reserve = Doctrine_Core::getTable('Reserve')->find(array($request->getParameter('id')));
        $reserve->setCanceled(true);
        $reserve->save();
		
        $to = $reserve->getUser()->getEmail();
        $subject = 'Reserva Cancelada';

// compose headers
        $headers = "From: \"Arriendas Reservas\" <no-reply@arriendas.cl>\n";
        $headers .= "Content-type: text/html\n";
        $headers .= "X-Mailer: PHP/" . phpversion() . "\n";

        $mail = 'Su reserva para el auto ' . $reserve->getCar()->getModel()->getBrand()->getName() . ' ' . $reserve->getCar()->getModel()->getName() .
                ' del usuario ' . $reserve->getCar()->getUser()->getFirstName() . ' ' . $reserve->getCar()->getUser()->getLastName() . ' ha sido cancelada.<br/><br/>
	
	El equipo de Arriendas.cl
	<br><br>
	<em style="color: #969696">Nota: Para evitar posibles problemas con la recepcion de este correo le aconsejamos nos agregue a su libreta de contactos.</em> 
	';

// send email
        $this->smtpMail($to, $subject, $mail, $headers);

        $this->redirect('profile/rentalToConfirm');
    }

    public function executeConfirmReserve(sfWebRequest $request) {

        $reserve = Doctrine_Core::getTable('Reserve')->find(array($request->getParameter('id')));
        $reserve->setConfirmed(true);
        $reserve->save();

        $transaccion = Doctrine_Core::getTable('Transaction')->findByReserveId(array($request->getParameter('id')));

        if (!count($transaccion)) {
        	
            $transaction = new Transaction();
            $carString = $reserve->getCar()->getModel()->getName() . " " . $reserve->getCar()->getModel()->getBrand()->getName();
            $transaction->setCar($carString);
            $transaction->setPrice( $reserve->getPrice() );
            $transaction->setUser($reserve->getUser());
            $transaction->setDate(date("Y-m-d H:i:s"));
            $transactionType = Doctrine_Core::getTable('TransactionType')->find(1);
            $transaction->setTransactionType($transactionType);
            $transaction->setReserve($reserve);
            $transaction->setCompleted(false);
            $transaction->save();
        } else {

            $q = Doctrine_Query::create()
                    ->update('Transaction t')
                    ->set('t.date', '?', date("Y-m-d H:i:s"))
                    ->where('t.reserve_id = ?', $request->getParameter('id'));
            $q->execute();
        }


        $startDate = date("Y-m-d H:i:s", strtotime($reserve->getDate()));
        $endDate = date("Y-m-d H:i:s", strtotime($reserve->getDate() . " + " . $reserve->getDuration() . " hour"));
        $userid = $reserve->getUser()->getId();

        $this->sendConfirmEmail($reserve);

        $reserve = Doctrine_Core::getTable('Reserve')
                ->createQuery('a')
                ->where('((a.date <= ? and date_add(a.date, INTERVAL a.duration HOUR) > ?) or (a.date <= ? and date_add(a.date, INTERVAL a.duration HOUR) > ?)) and (a.User.id = ? and a.confirmed = false and a.complete = false)', array($startDate, $startDate, $endDate, $endDate, $userid))
                ->execute();

//echo count($reserve);die;

        foreach ($reserve as $r) {
            Doctrine_Query::create()
                    ->delete()
                    ->from('Transaction')
                    ->andWhere('reserve_id = ?', $r->getId())
                    ->execute();
            $r->delete();
        }

        $this->redirect('profile/rental');
    }

    public function executeCompleteReserve(sfWebRequest $request) {
        $rating = new Rating();
        $rating->save();

        $reserve = Doctrine_Core::getTable('Reserve')->find(array($request->getParameter('id')));
        $reserve->setComplete(true);
        $reserve->setRating($rating);
        $reserve->save();

        $this->sendEndReserveEmail($reserve);

        $this->redirect('profile/rental');
    }

    public function executeUpload() {
        $fileName = $this->getRequest()->getFileName('file');

        $this->getRequest()->moveFile('file', sfConfig::get('sf_upload_dir') . '/' . $fileName);

        $this->redirect('media/show?filename=' . $fileName);
    }

    public function executeDoSaveAvailability(sfWebRequest $request) {

        $availibility = new Availability();
        $availibility->setDateFrom($request->getParameter('date_from'));
        $availibility->setDateTo($request->getParameter('date_to'));
        $availibility->setHourFrom($request->getParameter('hour_from'));
        $availibility->setHourTo($request->getParameter('hour_to'));
        $availibility->setDay($request->getParameter('day'));
        $car = Doctrine_Core::getTable('Car')->find(array($request->getParameter('car')));
        $availibility->setCar($car);
        $availibility->save();

        $this->redirect('profile/index');
    }

    public function executeDoUpdateProfile(sfWebRequest $request) {

        try {

            $profile = Doctrine_Core::getTable('User')->find($this->getUser()->getAttribute('userid'));
            $profile->setFirstname($request->getParameter('firstname'));
            $profile->setLastname($request->getParameter('lastname'));
            $profile->setEmail($request->getParameter('email'));
            $profile->setRegion($request->getParameter('region'));
            $profile->setComuna($request->getParameter('comunas'));
	    $profile->setAddress($request->getParameter('address'));
            $profile->setBirthdate($request->getParameter('birth'));
	    if($request->getParameter('password') != '') {
	            if ($request->getParameter('password') == $request->getParameter('passwordAgain'))
	                $profile->setPassword(md5($request->getParameter('password')));
			}
            $profile->setTelephone($request->getParameter('telephone'));
            if ($request->getParameter('main') != NULL)
                $profile->setPictureFile($request->getParameter('main'));
            if ($request->getParameter('licence') != NULL)
                $profile->setDriverLicenseFile($request->getParameter('licence'));
			if ($request->getParameter('rut') != NULL)
                $profile->setRutFile($request->getParameter('rut'));
			$profile->setRut($request->getParameter('run'));
            $profile->save();
            $this->getUser()->setAttribute('picture_url', $profile->getFileName());
        } catch (Exception $e) {
            echo $e->getMessage();
        }


        $this->redirect('profile/index');
    }

    public function executeGetModel(sfWebRequest $request) {

        sfConfig::set('sf_web_debug', false);
        $_output;

        /* Asegurar que la solicitud sea AJAX */
//if (!$request->isXmlHttpRequest())
//return $this->renderText(json_encode(array('error'=>'S�lo respondo consultas v�a AJAX.')));

        if ($request->getParameter('id')) {

            $brand = Doctrine_Core::getTable('Brand')->find(array($request->getParameter('id')));

            foreach ($brand->getModels() as $p) {
                $_output[] = array("optionValue" => $p->getId(), "optionDisplay" => $p->getName());
            }

            return $this->renderText(json_encode($_output));
        }
        return $this->renderText(json_encode(array('error' => 'Faltan par�metros para realizar la consulta')));
    }

    public function executeGetState(sfWebRequest $request) {

        sfConfig::set('sf_web_debug', false);
        $_output;

        /* Asegurar que la solicitud sea AJAX */
//if (!$request->isXmlHttpRequest())
//return $this->renderText(json_encode(array('error'=>'S�lo respondo consultas v�a AJAX.')));

        if ($request->getParameter('id')) {

            $country = Doctrine_Core::getTable('Country')->find(array($request->getParameter('id')));

            foreach ($country->getStates() as $p) {
                $_output[] = array("optionValue" => $p->getId(), "optionDisplay" => $p->getName());
            }

            return $this->renderText(json_encode($_output));
        }
        return $this->renderText(json_encode(array('error' => 'Faltan par&aacute;metros para realizar la consulta')));
    }

    public function executeGetCity(sfWebRequest $request) {

        sfConfig::set('sf_web_debug', false);
        $_output;

        /* Asegurar que la solicitud sea AJAX */
//if (!$request->isXmlHttpRequest())
//return $this->renderText(json_encode(array('error'=>'S�lo respondo consultas v�a AJAX.')));

        if ($request->getParameter('id')) {

            $state = Doctrine_Core::getTable('State')->find(array($request->getParameter('id')));

            foreach ($state->getCities() as $p) {
                $_output[] = array("optionValue" => $p->getId(), "optionDisplay" => $p->getName());
            }

            return $this->renderText(json_encode($_output));
        }
        return $this->renderText(json_encode(array('error' => 'Faltan par�metros para realizar la consulta')));
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
                    
                        $userid = $this->getUser()->getAttribute("userid");
                        $actual_image_name = time() . $userid . "." . $ext;

                        $uploadDir = sfConfig::get("sf_web_dir");
                        $path = $uploadDir . '/images/cars/';
                        $fileName = $actual_image_name . "." . $ext;

                        $tmp = $_FILES[$request->getParameter('file')]['tmp_name'];
                        if (move_uploaded_file($tmp, $path . $actual_image_name)) {
                            sfContext::getInstance()->getConfiguration()->loadHelpers("Asset");

                            echo "<input type='hidden' name='" . $request->getParameter('photo') . "' value='" . $path . $actual_image_name . "'/>";
                            echo "<img src='" . image_path("cars/" . $actual_image_name) . "' class='preview' height='" . $request->getParameter('height') . "' width='" . $request->getParameter('width') . "' />";
                        }
                        else
                            echo "failed";
                    }
                    else
                        echo "Image file size max 5 MB";
                }
                else
                    echo "Invalid file format..";
            }
            else
                echo "Please select image..!";
            exit;
        }



        /* 	foreach ($request->getFiles() as $fileName) {
          $fileSize = $fileName['size'];
          $fileType = $fileName['type'];
          $extesion = getFileExtension($fileName['name']);


          if(!is_dir($csv))
          mkdir($csv, 0777);
          move_uploaded_file($fileName['tmp_name'], "$folder/$fileName");
          }
         */
    }

    public function executeDetalleReserva(sfWebRequest $request) {
        if ($this->getUser()->isAuthenticated()) {

            $id = $request->getParameter('id');

            try {

                $q = Doctrine_Query::create()
                        ->select('r.id , ca.id idcar , mo.name model, 
	  br.name brand, ca.year year, 
          ca.price_per_hour pricehour, ca.price_per_day priceday,
	  ca.address address, ci.name city, 
	  st.name state, co.name country, 
	  user.firstname firstname, user.lastname lastname,
	  r.date date, r.duration'
                        )
                        ->from('Reserve r')
                        ->innerJoin('r.Car ca')
                        ->innerJoin('ca.Model mo')
                        ->innerJoin('ca.User owner')
                        ->innerJoin('r.User user')
                        ->innerJoin('mo.Brand br')
                        ->innerJoin('ca.City ci')
                        ->innerJoin('ci.State st')
                        ->innerJoin('st.Country co')
                        ->where('r.user_id = ?', $this->getUser()->getAttribute("userid"))
                        ->andWhere('r.confirmed = ?', 0)
                        ->andWhere('r.complete = ?', 0)
                        ->andWhere('r.id = ?', $id);
//->andWhere('r.date > ?',  date('Y-m-d H:i:s.u'));

                $this->misreservas = $q->execute();
//echo $q->getSqlQuery();die;
            } catch (Exception $e) {
                echo $e->getMessage();
            }
        } else {
            $this->getUser()->setAttribute('lastview', $request->getUri());
            $this->forward('main', 'login');
        }
    }

    public function executeDeleteReserve(sfWebRequest $request) {

        $this->setLayout(false);

        $idReserva = $request->getParameter('idReserva');

        if (!is_nan($idReserva) && !is_null($idReserva)) {

            $reserve = Doctrine_Core::getTable('Reserve')->find(array($idReserva));

            $inicio = strtotime(date("Y-m-d H:i:s", strtotime($reserve->getDate())));
            $now = strtotime(date("Y-m-d H:i:s"));

            $minutos = ($inicio - $now) / 60;

            if ($minutos < 30) {
                $reserve->setSancion(true);
            }

            $reserve->setCanceled(true);
            $reserve->save();
        }

        $this->forward('profile', 'pedidos');

        return sfView::NONE;
    }

    public function executeEditReserve(sfWebRequest $request) {

        $idReserva = $request->getParameter('idReserva');
        $datedesde = $request->getParameter('fechainicio');
        $datehasta = $request->getParameter('fechafin');
        $hourdesde = "";
        $hourhasta = "";

        $desde = explode(" ", $datedesde);
        $datedesde = $desde[0];
        $hourdesde = $desde[1];

        $hasta = explode(" ", $datehasta);
        $datehasta = $hasta[0];
        $hourhasta = $hasta[1];

//echo $datehasta.'\n'.$hourhasta;die;
//print_r($request);die;
//$duration = $request->getParameter('duration');
//if($date != ""  && $duration != "")
        if ($datedesde != "" && $datehasta != "" && $hourdesde != "" && $hourhasta != "" &&
                preg_match('/^(([0-1][0-9])|([2][0-3])):([0-5][0-9]):([0-5][0-9])$/', $hourdesde) &&
                preg_match('/^(([0-1][0-9])|([2][0-3])):([0-5][0-9]):([0-5][0-9])$/', $hourhasta) &&
                preg_match('/^\d{4}\-\d{1,2}\-\d{1,2}$/', $datedesde) &&
                preg_match('/^\d{4}\-\d{1,2}\-\d{1,2}$/', $datehasta)) {

//CORROBORAR SI SE SOLICITO ANTES PERO TIENE DURACION DURANTE EL PEDIDO

            $startDate = date("Y-m-d H:i:s", strtotime($datedesde . " " . $hourdesde));
            $endDate = date("Y-m-d H:i:s", strtotime($datehasta . " " . $hourhasta));

            $diff = strtotime($endDate) - strtotime($startDate);

            $duration = $diff / 60 / 60;


            $carid = $request->getParameter('carid');


            $has_reserve = Doctrine_Core::getTable('Reserve')
                    ->createQuery('a')
                    ->where('((a.date <= ? and date_add(a.date, INTERVAL a.duration HOUR) > ?) or (a.date <= ? and date_add(a.date, INTERVAL a.duration HOUR) > ?)) and (a.Car.id = ?)', array($startDate, $startDate, $endDate, $endDate, $carid))
                    ->andWhere('a.id != ?', $idReserva)
                    ->andWhere('a.confirmed = ?', true)
                    ->andWhere('a.complete = ?', false)
                    ->fetchArray();



            if (count($has_reserve) == 0) {

                if ($diff > 0) {

                    try {

                        Doctrine_Query::create()
                                ->update('Reserve r')
                                ->set('r.date', '?', $startDate)
                                ->set('r.duration', '?', $duration)
                                ->set('r.confirmed', '?', false)
                                ->where('r.id = ?', $idReserva)
                                ->execute();
                        echo '0';
                    } catch (Exception $e) {
                        echo 'Error al modificar su reserva'; //.' error: '.$e->getMessage()
                    }
                } else {
                    echo 'Fecha de retiro debe ser mayor a fecha de entrega';
                }
            } else {
                echo 'Ya hay una reserva confirmada para ese horario';
            }
        } else {
            echo 'Por favor ingrese fechas con formato YYYY-MM-DD HH:MM';
        }
        return sfView::NONE;
    }

    public function executeDoModificarArriendo(sfWebRequest $request) {
        $this->setLayout(false);
    }

    public function executeModificarArriendo(sfWebRequest $request) {

        $id = $request->getParameter('id');

        $q = Doctrine_Query::create()
                ->select('r.id , ca.id idcar , r.confirmed, mo.name model, 
	  br.name brand, ca.year year, 
          ca.price_per_hour pricehour, ca.price_per_day priceday,
	  ca.address address, ci.name city, 
	  st.name state, co.name country, 
	  user.firstname firstname, user.lastname lastname,
	  r.date date, r.duration, date_add(r.date, INTERVAL r.duration HOUR) fechafin'
                )
                ->from('Reserve r')
                ->innerJoin('r.Car ca')
                ->innerJoin('ca.Model mo')
                ->innerJoin('ca.User owner')
                ->innerJoin('r.User user')
                ->innerJoin('mo.Brand br')
                ->innerJoin('ca.City ci')
                ->innerJoin('ci.State st')
                ->innerJoin('st.Country co')
//->where('r.user_id = ?', $this->getUser()->getAttribute("userid"))
//->andWhere('r.confirmed = ?', 1)
//->andWhere('r.complete = ?', 0)
//->andWhere('r.canceled = ?', 0)
                ->andWhere('r.id = ?', $id);

        $this->reserva = $q->fetchOne();
    }

    public function executeGuardarKms(sfWebRequest $request) {
        $this->setLayout(false);



        $idReserva = $request->getParameter('id');
        $tipo = $request->getParameter('tipo');
        $kms = $request->getParameter('kms');

        //$kms = number_format($kms, 2, '.', ',');

        if ($kms > 0) {

            try {

                $reserve = Doctrine::getTable('Reserve')->find(array($idReserva));

                if ($tipo == 'inicial') {
                    $reserve->setKminicial($kms);
                } elseif ($tipo == 'final') {
                    $reserve->setKmfinal($kms);
                }

                if ($this->getUser()->getAttribute('userid') != $reserve->getUserId()) {
                    $reserve->setIniKmOwnerConfirmed(true);
                    $this->sendConfirmKmsEmail($reserve, 'usuario');
                } else {
                    $reserve->setIniKmConfirmed(true);
                }

                $reserve->save();

                echo 'ok';
            } catch (Exception $e) {
                echo 'error: ' . $e->getMessage();
            }
        } else {
            echo 'Debe ingresar km ' . $tipo;
        }
        return sfView::NONE;
    }

    public function sendConfirmKmsEmail($reserve, $usuario) {

        if ($usuario == 'owner') {

            $to = $reserve->getCar()->getUser()->getEmail();

            $subject = 'Tiene una reserva por confirmar kms!';

            $headers = "From: \"Arriendas Reservas\" <no-reply@arriendas.cl>\n";
            $headers .= "Content-type: text/html\n";
            $headers .= "X-Mailer: PHP/" . phpversion() . "\n";

            $mail = 'Se ha solicitado confirmación de kms para el auto ' . $reserve->getCar()->getModel()->getBrand()->getName() . ' ' . $reserve->getCar()->getModel()->getName() .
                    ' reservado por el usuario ' . $reserve->getUser()->getFirstName() . ' ' . $reserve->getUser()->getLastName() . '.
	
	Gracias
	Arriendas.cl
	<br><br>
	<em style="color: #969696">Nota: Para evitar posibles problemas con la recepcion de este correo le aconsejamos nos agregue a su libreta de contactos.</em> 
	';
        } else {
            $to = $reserve->getUser()->getEmail();

            $subject = 'Tiene una reserva por confirmar kms!';

            $headers = "From: \"Arriendas Reservas\" <no-reply@arriendas.cl>\n";
            $headers .= "Content-type: text/html\n";
            $headers .= "X-Mailer: PHP/" . phpversion() . "\n";

            $mail = 'Se ha solicitado confirmación de kms para el auto ' . $reserve->getCar()->getModel()->getBrand()->getName() . ' ' . $reserve->getCar()->getModel()->getName() .
                    ' del usuario ' . $reserve->getCar()->getUser()->getFirstName() . ' ' . $reserve->getCar()->getUser()->getLastName() . '.
	
	Gracias
	Arriendas.cl
	<br><br>
	<em style="color: #969696">Nota: Para evitar posibles problemas con la recepcion de este correo le aconsejamos nos agregue a su libreta de contactos.</em> 
	';
        }

        $this->smtpMail($to, $subject, $mail, $headers);
    }
    
    public function calcularMontoTotal($duration = 0, $preciohora = 0, $preciodia = 0) {

        $dias = floor($duration / 24);
        $horas = ($duration / 24) - $dias;

        if ($horas >= 0.25) {
            $dias = $dias + 1;
            $horas = 0;
        } else {
			$horas = round($horas * 24,0);
  }
        
        $montototal = floor($preciodia * $dias + $preciohora * $horas);
        
        return $montototal;
    }

    
public function smtpMail($to_input,$subject_input,$mail,$headers,$cco=null) {
error_reporting(0);
require_once "Mail.php";
require_once "Mail/mime.php";
 
 $from = "Soporte Arriendas.cl <soporte@arriendas.cl>";
 $to = $to_input;
 $subject = $subject_input;
 $body = $mail;
 
 $host = "smtp.sendgrid.net";
 $username = "german@arriendas.cl";
 $password = "Holahello00@";
 
 if($cco==null){
 $headers = array ('From' => $from,
   'To' => $to,
   'charset' => 'UTF-8',
   'Subject' => $subject);
 } else {
    $headers = array ('From' => $from,
   'To' => $to,
   'Bcc' => $cco,
   'charset' => 'UTF-8',
   'Subject' => $subject);
 }
 $headers["Content-Type"] = 'text/html; charset=UTF-8';
 
 $mime= new Mail_mime();
 $mime->setHTMLBody($body);

 $mimeparams=array(); 


// It refused to change to UTF-8 even if the header was set to this, after adding the following lines it worked.

$mimeparams['text_encoding']="8bit"; 
$mimeparams['text_charset']="UTF-8"; 
$mimeparams['html_charset']="UTF-8"; 
$mimeparams['head_charset']="UTF-8"; 

 
 $body=$mime->get($mimeparams);
$headers = $mime->headers($headers);
 
 $smtp = Mail::factory('smtp',
   array ('host' => $host,
     'auth' => true,
     'username' => $username,
     'password' => $password));
 
 $mail = $smtp->send($to, $headers, $body);
 

}
    
}

