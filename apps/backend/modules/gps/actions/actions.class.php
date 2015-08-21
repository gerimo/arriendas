<?php

require_once sfConfig::get('sf_lib_dir') . '/vendor/mobile-detect/Mobile_Detect.php';

class gpsActions extends sfActions {

	public function executeIndex(sfWebRequest $request) {

	}

	public function executeCarsUploadWithoutGps(sfWebRequest $request) {

	}

	public function executeGeneratePay(sfWebRequest $request) {
		$return = array("error" => false);

        try {

            $carTmpId = $request->getPostParameter("carTmpId");

            $CarTmp = Doctrine_Core::getTable("cartmp")->find($carTmpId);

            if($CarTmp->car_id){
            	throw new Exception("El pago para este vehículo ya fué procesado", 1);
            }

            $userId = $CarTmp->getUserId();
            $User = Doctrine_Core::getTable("user")->find($userId);

            $Car = new Car();

            $fechaHoy = Date("Y-m-d H:i:s");
            $Car->setFechaSubida($fechaHoy);
            // $url = $this->generateUrl('car_price');

            $Car->setAddress($CarTmp->getAddress());
            $Car->setCommuneId($CarTmp->getCommuneId());
            $Car->setModelId($CarTmp->getModelId());
            $Car->setYear($CarTmp->getYear());
            $Car->setDoors($CarTmp->getDoors());
            $Car->setTransmission($CarTmp->getTransmission());
            $Car->setTipoBencina($CarTmp->getTipoBencina());
            $Car->setPatente($CarTmp->getPatente());
            $Car->setUserId($CarTmp->getUserId());
            $Car->setColor($CarTmp->getColor());
            $Car->setLat($CarTmp->getLat());
            $Car->setLng($CarTmp->getLng());
            $Car->setBabyChair($CarTmp->getBabyChair());
            $Car->setCapacity($CarTmp->getCapacity());
            $Car->setAccesoriosSeguro($CarTmp->getAccesoriosSeguro());
            $Car->setIsAirportDelivery($CarTmp->getIsAirportDelivery());
            $Car->setHasGps(1);
            $Car->setCityId(27);

            $Car->save();
            
            CarProximityMetro::setNewCarProximityMetro($Car);

            $CarTmp->setCarId($Car->id);
            $CarTmp->save();

            // Correo de notificación de un nuevo vehículo a soporte de arriendas
            $mail    = new Email();
            $mailer  = $mail->getMailer();
            $message = $mail->getMessage();            

            $subject = "¡Se ha registrado un nuevo vehículo!";
            $body    = $this->getPartial('emails/carCreateSupport', array('Car' => $Car));
            $from    = array("no-reply@arriendas.cl" => "Notificaciones Arriendas.cl");
            $to      = array("soporte@arriendas.cl");

            $message->setSubject($subject);
            $message->setBody($body, 'text/html');
            $message->setFrom($from);
            $message->setTo($to);
            /*$message->setBcc(array("cristobal@arriendas.cl" => "Cristóbal Medina Moenne"));*/
            
            $mailer->send($message);

            // Correo de notificación del registro del vehículo al usuario
            $subject = "¡Tu vehículo ha sido registrado!";
            $body    = $this->getPartial('emails/carCreateOwner', array('Car' => $Car));
            $from    = array("soporte@arriendas.cl" => "Soporte Arriendas.cl");
            $to      = array($Car->getUser()->email => $Car->getUser()->firstname." ".$Car->getUser()->lastname);

            $message->setSubject($subject);
            $message->setBody($body, 'text/html');
            $message->setFrom($from);
            $message->setTo($to);
            
            $mailer->send($message);

            // notificación de compra de gps
            Notification::make($Car->getUser()->id, 5); 

            $subject = "Has Comprado un GPS!";
            $body    = $this->getPartial('emails/paymentDoneGPS', array('GPSTransaction' => $GPSTransaction, 'User' => $User));
            $from    = array("soporte@arriendas.cl" => "Soporte Arriendas.cl");
            $to      = array($Car->getUser()->email => $Car->getUser()->firstname." ".$Car->getUser()->lastname);

            $message->setSubject($subject);
            $message->setBody($body, 'text/html');
            $message->setFrom($from);
            $message->setTo($to);
            
            $mailer->send($message);

            // Correo soporte
            $subject = "Nuevo pago. GPS: ".$GPSTransaction->id;
            $body    = $this->getPartial('emails/paymentDoneGPS', array('GPSTransaction' => $GPSTransaction, 'User' => $User));
            $from    = array("no-reply@arriendas.cl" => "Notificaciones Arriendas.cl");
            $to      = array("soporte@arriendas.cl" => "Soporte Arriendas.cl");

            $message->setSubject($subject);
            $message->setBody($body, 'text/html');
            $message->setFrom($from);
            $message->setTo($to);
            
            $mailer->send($message);

        } catch (Exception $e) {
            $return["error"]        = true;
            $return["errorCode"]    = $e->getCode();
            $return["errorMessage"] = $e->getMessage();
        }

        $this->renderText(json_encode($return));
        
        return sfView::NONE;
	}

	public function executeCarFindAll(sfWebRequest $request) {
        $return = array("error" => false);

        try {

            $limit = $request->getPostParameter("limit", null);

            $return["data"] = array();

            $Cars = Doctrine_Core::getTable('cartmp')->findAllCar($limit);

            if(count($Cars) == 0){
                throw new Exception("No se encuentran autos", 1);
            }

            foreach($Cars as $i => $Car){
            	$Model = Doctrine_Core::getTable("model")->find($Car->model_id);
            	$Brand = Doctrine_Core::getTable("brand")->find($Model->brand_id);
            	$Commune = Doctrine_Core::getTable("commune")->find($Car->commune_id);

                $return["data"][$i] = array(
                    'id' => $Car->id,
                    'brand' => $Brand->name,
                    'model' => $Model->name,
                    'year' => $Car->year,
                    'transmission' => $Car->transmission == 1 ? 'Automática' : 'Manual',
                    'comunne' =>$Commune->name,
                    'user_id' =>$Car->user_id,
                    'car_id' =>$Car->car_id == null ? '1' : $Car->car_id
                );
                  
            }

        } catch (Exception $e) {
        	error_log($e->getMessage());
            $return["error"]        = true;
            $return["errorCode"]    = $e->getCode();
            $return["errorMessage"] = $e->getMessage();
        }

        $this->renderText(json_encode($return));
        
        return sfView::NONE;
    }

}
