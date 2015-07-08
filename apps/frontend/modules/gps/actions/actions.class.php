<?php

require_once sfConfig::get('sf_lib_dir') . '/vendor/mobile-detect/Mobile_Detect.php';

class gpsActions extends sfActions {

	public function executeShowMessage(sfWebRequest $request) {
		$this->setLayout("newIndexLayout");

		$userId = $this->getUser()->getAttribute("userid");
		$carId = $request->getParameter('car');

		$Car = Doctrine_core::getTable("car")->find($carId);

        // Cambiar fecha el dia de subida a prod
		$fecha = Date("Y-m-d H:i:s", strtotime("2015-07-07"));

        // Comprueba si existen transacciones por visualizar
        $GPSTransactions = Doctrine_core::getTable("GPSTransaction")->findByCompletedAndViewed(1,0);

        foreach ($GPSTransactions as $GPSTransaction) {
            $carId_transaction = $GPSTransaction->car_id;
            if($carId_transaction == $carId){
                $Car = Doctrine_core::getTable("car")->find($carId);
                error_log("message");
                if($Car->getUser()->id == $userId){
                    $this->redirect('gps/showPayedMessageGPS?transactionId='. $GPSTransaction->id);

                }
            }

        }

		//restricciones de acceso a la vista.
		if($Car->has_gps || $Car->getUserId() != $userId || $Car->getFechaSubida() < $fecha){
			$this->redirect("cars");
		}

		$Gps = Doctrine_core::getTable("gps")->find(1);

		$this->gps_description = $Gps->description;
		$this->gps_price = $Gps->price;
		$this->gpsId = $Gps->id;

		$this->carId = $request->getParameter("car");
	}

	public function executePayGps(sfWebRequest $request) {
		
		$userId = $this->getUser()->getAttribute('userid');        
		$carId  = $request->getParameter("carId", null);
        $gpsId   = $request->getParameter("gpsId", null);
        $gps_price   = $request->getParameter("price", null);
        $gps_description   = $request->getParameter("description", null);
        $payment  = $request->getPostParameter("payment", null);
        $User = Doctrine_Core::getTable('User')->find($userId);
        $this->forward404If(!$User);
   		
        error_log($payment);
   		// chequeo judicial
        $Gps = Doctrine_core::getTable("gps")->find($gpsId);
        try {

            if (empty($carId) || $carId == 0 || empty($gpsId) || $gpsId == 0 || empty($gps_price) || $gps_price == 0) {
                throw new Exception("El User ".$userId." esta intentando pagar pero uno de los campos es nulo. Car: ".$carId.", GPS: ".$gpsId.", Precio: ".$gps_price, 1);
            }

            if ($Gps->price != $gps_price) {
                throw new Exception("El User ".$userId." esta intentando pagar pero el precio que visualizó: ".$gps_price.", no coincide con el de la base de datos: ".$Gps->price, 1);
            }

            if ($User->getBlocked()) {
                throw new Exception("Rechazado el pago de User ".$userId." (".$User->firstname." ".$User->lastname.") debido a que se encuentra bloqueado, por lo que no esta autorizado para generar pagos", 1);            
            }
            
            $Car = Doctrine_Core::getTable('Car')->find($carId);

            if (!$Car) {
                throw new Exception("El User ".$userId." esta intentando pagar un gps pero no se encontró el auto.", 1);
            }
            
            if ($Car->has_gps) {
                throw new Exception("El User ".$userId." esta intentando pagar pero el Car ".$carId." ya posee un GPS.", 1);
            }

            if (!$payment) {
                throw new Exception("El User ".$userId." esta intentando pagar un gps pero no se especifico el medio de pago", 1);
            }

            $GPSTransaction = new GPSTransaction();
            error_log("pasa");
            $GPSTransaction->setCarId($carId);
            $GPSTransaction->setGpsId($gpsId);
            $GPSTransaction->setAmount($gps_price);

            $GPSTransaction->setCompleted(0);
            $GPSTransaction->setPaidOn(null);

           	$GPSTransaction->save();

        } catch (Exception $e) {
            error_log("[GPS/payGps] ".$e->getMessage());
            if ($request->getHost() == "www.arriendas.cl" && $e->getCode() < 2) {
                Utils::reportError($e->getMessage(), "GPS/payGps");
            }
            $this->redirect("homepage");
        }

        
        $this->getRequest()->setParameter("gps_transactionId", $GPSTransaction->getId());
        

        if ($payment == 2) {
            error_log("[GPS/payGPS] Procesando pago por Khipu");
            $this->forward("khipu", "generatePayment");
        } elseif ($payment == 1) {
            error_log("[GPS/payGPS] Procesando pago por webpay");
            $this->forward("webpay", "generatePayment");
        } else {
            $this->forward404("No se encontró el medio de pago");
        }

	}

	public function executeCancelUploadCar(sfWebRequest $request) {
		$return = array("error" => false);

		try {
			$userId = $this->getUser()->getAttribute("userid");
			$carId = $request->getParameter('carId');
			$Car = Doctrine_core::getTable("car")->find($carId);
			if($Car->getUserId() == $userId){
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

    public function executeShowPayedMessageGPS(sfWebRequest $request) {
        $this->setLayout("newIndexLayout");
        $userId = $this->getUser()->getAttribute("userid");
        $transactionId  = $request->getParameter("transactionId", null);


        $GPSTransaction = Doctrine_core::getTable("GPSTransaction")->find($transactionId);        

        $carId = $GPSTransaction->car_id;
        $Car = Doctrine_core::getTable("car")->find($carId);

        if($Car->getUser()->id != $userId){
            
            $this->redirect("homepage");                  

        }
        error_log("CAMBIA");
        $GPSTransaction->setViewed(1);
        $GPSTransaction->save();

    }
}

?>