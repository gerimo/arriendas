<?php

require_once sfConfig::get('sf_lib_dir') . '/vendor/mobile-detect/Mobile_Detect.php';

class carActions extends sfActions {
	
	public function executeIndex(sfWebRequest $request) {
	}

	public function executeGetActivesCars(sfWebRequest $request) {

	    $return = array("error" => false);

	    try {

	        $reserveId 			= $request->getPostParameter("reserveId", null);
            $isAutomatic        = $request->getPostParameter('isAutomatic', false) === 'true' ? true : false;
            $isLowConsumption   = $request->getPostParameter('isLowConsumption', false) === 'true' ? true : false;
            $isMorePassengers   = $request->getPostParameter('isMorePassengers', false) === 'true' ? true : false;

	        $Reserve    = Doctrine_Core::getTable('Reserve')->find($reserveId);
	        $Cars       = Doctrine_Core::getTable('Car')->findCars($Reserve->getFechaInicio2(), $Reserve->getFechaTermino2(), 50, false, false, false, false, false, 13, false, $isAutomatic, $isLowConsumption, $isMorePassengers, false);

	        $return["data"] = array();
	        
	        foreach ($Cars as $i => $Car) {
	            $return["data"][$i] = array(
	                "car_id"       		   => $Car['id'],
	                "car_commune"   	   => $Car['comunne'],
	                "car_brand"  		   => $Car['brand'],
	                "car_model" 		   => $Car['car_model'],
	                "car_year"   		   => $Car['year'],
	                "car_transmission"     => $Car['transmission'],
	                "car_price"  		   => $Car['price'],
	                "car_type"	  		   => $Car['type'],
	                "car_subway"   		   => $Car['nearestMetroName'],
	                "car_cant"   		   => $Car['QuantityOfLatestRents'],
	                "user_fullname"        => $Car['user_name'],
	                "user_telephone"       => $Car['user_telephone']
	        	);
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
