<?php

class opportunityActions extends sfActions
{

	public function executeIndex(sfWebRequest $request) {

		}

	public function executeCreate(sfWebRequest $request) {
		
		}

	public function executeIsOriginalReserve(sfWebRequest $request) {

		$return = array("error" => false);

        try {

            $idReservaOriginal = $request->getPostParameter("idReservaOriginal", null);

            if ($idReservaOriginal != "") {

	            $Reserve = Doctrine_Core::getTable('Reserve')->isOriginalReserve($idReservaOriginal);
	            
	            if (count($Reserve) == 0) {
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

	public function executeIsActiveCar(sfWebRequest $request) {

		$return = array("error" => false);

        try {

            $idCar = $request->getPostParameter("idCar", null);

            if ($idCar != "") {

	            $Car = Doctrine_Core::getTable('Car')->isActive($idCar);
	            
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
