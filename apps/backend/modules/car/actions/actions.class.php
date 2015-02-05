<?php

class carActions extends sfActions {
	
	public function executeIndex(sfWebRequest $request) {
	}

	public function executeGetActivesCars(sfWebRequest $request) {

	    $return = array("error" => false);

	    try {


	        $idReserve  = $request->getPostParameter("idReserve", null);

	        $Reserve    = Doctrine_Core::getTable('Reserve')->find($idReserve);
	        $Cars       = Doctrine_Core::getTable('Car')->findCarsActives();

	        $return["data"] = array();
	        
	        foreach ($Cars as $i => $Car) {

	            if (!$Car->hasReserve($Reserve->getFechaInicio2(), $Reserve->getFechaTermino2())) {
	          		
	          		if($Car->transmission == 0) {
	          			$transmission = "Manual";
	          		} else {
						$transmission = "Automática";
					}

	                $return["data"][$i] = array(
                    "car_id"       		   => $Car->id,
                    "car_commune"   	   => $Car->getCommune()->name,
                    "car_brand"  		   => $Car->getModel()->getBrand()->name,
                    "car_model" 		   => $Car->getModel()->name,
                    "car_year"   		   => $Car->year,
                    "car_transmission"     => $transmission,
                    "car_benzine" 		   => $Car->tipobencina,
                    "car_type"	  		   => $Car->getModel()->getCarType()->name,
                    "car_subway"   		   => $Car->getNearestMetro()->getMetro()->name,
                    "car_cant"   		   => $Car->getQuantityOfLatestRents(),
                    "user_fullname"        => $Car->getUser()->firstname." ".$Car->getUser()->lastname,
                    "user_telephone"       => $Car->getUser()->telephone
                    
                );

	            }
	        }

	    } catch (Exception $e) {
	        $return["error"] = true;
	        $return["errorCode"] = $e->getCode();
	        $return["errorMessage"] = $e->getMessage();
	    }

	    $this->renderText(json_encode($return));
	    
	    return sfView::NONE;
	}

	public function executeIsActiveCar(sfWebRequest $request) {

		$return = array("error" => false);

	    try {

	        $idCar = $request->getPostParameter("idCar", null);

	        if ($idCar != "") {

	            $Car = Doctrine_Core::getTable('Car')->findByIdAndSeguroOkAndActivo($idCar,4,1);
	            
	            if (count($Car) == 0) {
	            	$return["original"] = false;
	            } else {
	            	$return["original"] = true;
	            }
	        } 
	    } catch (Exception $e) {
	        $return["error"] = true;
	        $return["errorCode"] = $e->getCode();
	        $return["errorMessage"] = $e->getMessage();
	    }

	    $this->renderText(json_encode($return));
	    
	    return sfView::NONE;
	}
}
