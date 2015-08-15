<?php

require_once sfConfig::get('sf_lib_dir') . '/vendor/mobile-detect/Mobile_Detect.php';

class gpsActions extends sfActions {

	public function executeShowMessage(sfWebRequest $request) {
		$this->setLayout("newIndexLayout");

		$userId = $this->getUser()->getAttribute("userid");

        // recibe el id de un registro de auto temporal.
		$carTmpId = $request->getParameter('car');
        $CarTmp = Doctrine_core::getTable("cartmp")->findOneByIdAndCanceled($carTmpId, 0);
        // Cambiar fecha el dia de subida a prod
		//$fecha = Date("Y-m-d H:i:s", strtotime("2015-08-15"));

        // Comprueba si existen transacciones por visualizar
        $GPSTransactions = Doctrine_core::getTable("GPSTransaction")->findByCompletedAndViewed(1,0);
        foreach ($GPSTransactions as $GPSTransaction) {
            $carTmpId_transaction = $GPSTransaction->car_tmp_id;
            if($carTmpId_transaction == $carTmpId){
                if($CarTmp->getUserId() == $userId){

                    $this->redirect('gps/showPayedMessageGPS?transactionId='. $GPSTransaction->id);

                }
            }

        }
        if($CarTmp->car_id){
            $this->redirect("homepage");  
        }

        if($CarTmp->user_id != $userId || $CarTmp->canceled){
            $this->redirect("homepage");  
        }

        // GPS UTILIZADO POR DEFECTO PARA REALIZAR LOS PAGOS...
		$Gps = Doctrine_core::getTable("gps")->find(1);

		$this->gps_description = $Gps->description;
		$this->gps_price = $Gps->price;
		$this->gpsId = $Gps->id;

        // id de car_tmp
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

        $Gps = Doctrine_core::getTable("gps")->find($gpsId);
        try {

            if (empty($carId) || $carId == 0 || empty($gpsId) || $gpsId == 0 || empty($gps_price) || $gps_price == 0) {
                throw new Exception("El User ".$userId." esta intentando pagar pero uno de los campos es nulo. Car_tmp: ".$carId.", GPS: ".$gpsId.", Precio: ".$gps_price, 1);
            }

            if ($Gps->price != $gps_price) {
                throw new Exception("El User ".$userId." esta intentando pagar pero el precio que visualizó: ".$gps_price.", no coincide con el de la base de datos: ".$Gps->price, 1);
            }

            if ($User->getBlocked()) {
                throw new Exception("Rechazado el pago de User ".$userId." (".$User->firstname." ".$User->lastname.") debido a que se encuentra bloqueado, por lo que no esta autorizado para generar pagos", 1);            
            }

            $CarTmp = Doctrine_core::getTable("cartmp")->find($carId);

            if ($CarTmp->canceled) {
                throw new Exception("El User ".$userId." esta intentando pagar un gps pero el pago fué eliminado", 1);
            }

            $GPSTransaction = new GPSTransaction();
            //$GPSTransaction->setCarId($Car->id);
            $GPSTransaction->setCarTmpId($CarTmp->id);
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

    public function executePago(sfWebRequest $request) {
        
        $userId = $this->getUser()->getAttribute('userid');        
        $carId  = $request->getPostParameter("carId", null);
        $gpsId   = $request->getPostParameter("gpsId", null);
        $gps_price   = $request->getPostParameter("price", null);
        $gps_description   = $request->getPostParameter("description", null);
        $payment  = $request->getPostParameter("payment", null);
        $User = Doctrine_Core::getTable('User')->find($userId);
        $this->forward404If(!$User);

        $Gps = Doctrine_core::getTable("gps")->find($gpsId);
        try {

            if (empty($carId) || $carId == 0 || empty($gpsId) || $gpsId == 0 || empty($gps_price) || $gps_price == 0) {
                throw new Exception("El User ".$userId." esta intentando pagar pero uno de los campos es nulo. Car_tmp: ".$carId.", GPS: ".$gpsId.", Precio: ".$gps_price, 1);
            }

            if ($Gps->price != $gps_price) {
                throw new Exception("El User ".$userId." esta intentando pagar pero el precio que visualizó: ".$gps_price.", no coincide con el de la base de datos: ".$Gps->price, 1);
            }

            if ($User->getBlocked()) {
                throw new Exception("Rechazado el pago de User ".$userId." (".$User->firstname." ".$User->lastname.") debido a que se encuentra bloqueado, por lo que no esta autorizado para generar pagos", 1);            
            }

            $CarTmp = Doctrine_core::getTable("cartmp")->find($carId);

            if ($CarTmp->canceled) {
                throw new Exception("El User ".$userId." esta intentando pagar un gps pero el pago fué eliminado", 1);
            }

            $GPSTransaction = new GPSTransaction();
            //$GPSTransaction->setCarId($Car->id);
            $GPSTransaction->setCarTmpId($CarTmp->id);
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

			$CarTmp = Doctrine_core::getTable("cartmp")->find($carId);

			if($CarTmp->user_id == $userId){
				$CarTmp->setCanceled(1);
                $CarTmp->save();
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

        try{
            if($Car->getUser()->id != $userId){
                
                $this->redirect("homepage");                  

            }
            $GPSTransaction->setViewed(1);
            $GPSTransaction->save();

        } catch(Exception $e){
            error_log("[".date("Y-m-d H:i:s")."] [gps/showPayedMessageGPS] ERROR: ".$e->getMessage()." Problemas en el proceso de pago, no se generó el veículo.");
        }
        
    }
}

?>