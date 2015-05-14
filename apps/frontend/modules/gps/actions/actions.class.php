<?php

require_once sfConfig::get('sf_lib_dir') . '/vendor/mobile-detect/Mobile_Detect.php';

class gpsActions extends sfActions {

	public function executeShowMessage(sfWebRequest $request) {
		$this->setLayout("newIndexLayout");
		$userId = $this->getUser()->getAttribute("userid");
		$carId = $request->getParameter('car');

		$Car = Doctrine_core::getTable("car")->find($carId);

		$fecha = Date("Y-m-d H:i:s", strtotime("2015-05-09"));

		//restricciones de acceso a la vista.
		if($Car->has_gps || $Car->getUserId() != $userId || $Car->getFechaSubida() < $fecha){
			$this->redirect("cars");
		}

		$Gps = Doctrine_core::getTable("gps")->find(1);
		$this->gps_description = $Gps->getDescription();
		$this->gps_price = $Gps->getPrice();

		$this->carId = $request->getParameter("car");
	}

	public function executePayGps(sfWebRequest $request) {
		$this->setLayout("newIndexLayout");
	}

	public function executeCancelUploadCar(sfWebRequest $request) {
		$return = array("error" => false);

		try {
			$userId = $this->getUser()->getAttribute("userid");
			$carId = $request->getParameter('carId');
			$Car = Doctrine_core::getTable("car")->find($carId);
			error_log($Car->getUserId()."   ".$userid);
			if($Car->getUserId() == $userId){
				error_log("pasa");
				$Car->delete();
				$return["message"] = "El vehículo fué cancelado.";
			} else {
				throw new Exception("No se pudo cancelar el vehículo, intente nuevamente...", 1);
			}

		} catch (Exception $e) {
			$return["error"] = true;
			error_log("[".date("Y-m-d H:i:s")."] [gps/cancelUploadCar] ERROR: ".$e->getMessage());
			$return["errorMessage"] = $e->getMessage();
		}

		$this->renderText(json_encode($return));

        return sfView::NONE;

	}
}

?>