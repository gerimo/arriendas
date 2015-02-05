<?php

class reserveActions extends sfActions {

	public function executeIndex(sfWebRequest $request) {
	}

	public function executeIsOriginalReserve(sfWebRequest $request) {

		$return = array("error" => false);

	    try {

	        $idReservaOriginal = $request->getPostParameter("idReservaOriginal", null);

	        if ($idReservaOriginal != "") {

	            $Reserve = Doctrine_Core::getTable('Reserve')->findByIdAndComentarioAndReservaOriginal($idReservaOriginal,"null", 0);
	            
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
}
