<?php

class reserveActions extends sfActions {

	public function executeIndex(sfWebRequest $request) {
	}

	public function executeIsOriginalReserve(sfWebRequest $request) {

		$return = array("error" => false);

	    try {

	        $idReservaOriginal = $request->getPostParameter("idReservaOriginal", null);

	        if ($idReservaOriginal != "") {

	            $reserveId = Doctrine_Core::getTable('Reserve')->findOriginalReserve($idReservaOriginal);
	            
	            error_log($reserveId);

	            if ($reserveId == nul) {
	            	throw new Exception("Reserva no encontrada", 1);
	            	
	            } else {
	            	$return["original"] = $reserveId;
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
