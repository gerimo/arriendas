<?php

class reservesActions extends sfActions {

    public function executeIndex(sfWebRequest $request) {
        
        $this->setLayout("newIndexLayout");
        
        $userId = sfContext::getInstance()->getUser()->getAttribute('userid');

        $this->PaidReserves = Reserve::getPaidReserves($userId);

        $this->Reserves = Reserve::getReservesByUser($userId);
        $this->ChangeOptions = array();

        foreach ($this->Reserves as $Reserve) {

            foreach ($Reserve->getChangeOptions() as $ChangeOption) {
                $this->ChangeOptions[$Reserve->getId()][] = $ChangeOption;
            }
        }
    }

    public function executeApprove (sfWebRequest $request) {
    
        $return = array("error" => false);
    
        $reserveId = $request->getPostParameter("reserveId", null);

        try {

            if (is_null($reserveId) || $reserveId == "") {
                throw new Exception("Falta reserveId", 1);
            }

            $Reserve = Doctrine_Core::getTable('Reserve')->find($reserveId);
            if (!$Reserve) {
                throw new Exception("Reserve ".$Reserve->id." no encontrada", 1);
            }

            if ($Reserve->getCar()->getUser()->getId() != $this->getUser()->getAttribute("userid")) {
                throw new Exception("User ".$this->getUser()->getAttribute("userid")." intenta aprobar Reserve ".$Reserve->id." de User ".$Reserve->getCar()->getUser()->getId(), 1);
            }

            if ($Reserve->getConfirmed()) {
                throw new Exception("Reserve ".$Reserve->id." intenta ser aprobada y esta ya la está");
            }

            if ($Reserve->getUser()->getBlocked()){            
                throw new Exception("User ".$Reserve->getCar()->getUser()->id." intenta aprobar Reserve ".$Reserve->id." y este se encuentra bloqueado");
            }

            $Reserve->setConfirmed(1);
            /*$Reserve->setCanceled(0);*/
            $Reserve->setFechaConfirmacion(date("Y-m-d H:i:s"));

            // Cancelamos el envío de oportunidades
            $OpportunityQueue = Doctrine_Core::getTable('OpportunityQueue')->findOneByReserveId($Reserve->id);
            if ($OpportunityQueue) {
                $OpportunityQueue->setIsActive(false);
                $OpportunityQueue->save();
            } else {
                error_log("[".date("Y-m-d H:i:s")."] [reserves/approve] ERROR: Al aprobar la Reserve ".$Reserve->id." no se ha podido encontrar la OpportunityQueue para ser desactivada");
            }

            // Correo de notificación
            $User    = $Reserve->getUser();

            $mail    = new Email();
            $mailer  = $mail->getMailer();
            $message = $mail->getMessage();            

            $Functions  = new Functions;
            $formulario = $Functions->generarFormulario(NULL, $Reserve->token);
            $reporte    = $Functions->generarReporte($Reserve->getCar()->id);
            $contrato   = $Functions->generarContrato($Reserve->token);

            $subject = "¡Tu reserva ha sido aprobada!";
            $body    = $this->getPartial('emails/reserveApproved', array('Reserve' => $Reserve));
            $from    = array("soporte@arriendas.cl" => "Soporte Arriendas.cl");
            $to      = array($User->email => $User->firstname." ".$User->lastname);

            $message->setSubject($subject);
            $message->setBody($body, 'text/html');
            $message->setFrom($from);
            $message->setTo($to);
            /*$message->setBcc(array("cristobal@arriendas.cl" => "Cristóbal Medina Moenne"));*/

            $message->attach(Swift_Attachment::newInstance($contrato, 'contrato.pdf', 'application/pdf'));
            $message->attach(Swift_Attachment::newInstance($formulario, 'formulario.pdf', 'application/pdf'));
            $message->attach(Swift_Attachment::newInstance($reporte, 'reporte.pdf', 'application/pdf'));
            
            if (!is_null($User->getDriverLicenseFile())) {
                $filepath = $User->getDriverLicenseFile();
                if (is_file($filepath)) {
                    $message->attach(Swift_Attachment::fromPath($User->getDriverLicenseFile())->setFilename("LicenciaArrendatario-".$User->getLicenceFileName()));
                }
            }
            
            $mailer->send($message);
            
            $Reserve->save();

        } catch (Exception $e) {
            $return["error"] = true;
            $return["errorMessage"] = "Hubo un problema al aprobar la reserva. Por favor, intentalo más tarde.";

            if ($request->getHost() == "www.arriendas.cl") {
                Utils::reportError($e->getMessage(), "reserves/approve");
            } else {
                error_log("[".date("Y-m-d H:i:s")."] ERROR: ".$e->getMessage());
            }
        }
    
        $this->renderText(json_encode($return));

        return sfView::NONE;
    }

    public function executeCalculatePrice (sfWebRequest $request) {

        $return = array("error" => false);

        if ($request->hasParameter('carId','from','to')) {
            $carId = $request->getPostParameter("carId", null);
            $from  = $request->getPostParameter("from", null);
            $to    = $request->getPostParameter("to", null);
        } else {
            $carId = $this->getUser()->getAttribute("carId");
            $from  = $this->getUser()->getAttribute("from");
            $to    = $this->getUser()->getAttribute("to");
        }

        try {

            $datesError = $this->validateDates($from, $to);
            if ($datesError) {
                throw new Exception($datesError, 2);
            }

            if (is_null($carId) || $carId == '' || $carId == 0) {
                throw new Exception("Falta el carId", 1);
            }

            $Car = Doctrine_Core::getTable('car')->findOneById($carId);
            if (!$Car) {
                throw new Exception("El Car ".$carId." no fue encontrado", 1);
            }

            $return["price"] = CarTable::getPrice($from, $to, $Car->price_per_hour, $Car->price_per_day, $Car->price_per_week, $Car->price_per_month);

        } catch (Exception $e) {

            $return["error"] = true;

            if ($e->getCode() >= 2) {
                $return["errorMessage"] = $e->getMessage();
            } else {
                $return["errorMessage"] = "Problemas a calcular el precio. Por favor, intentalo más tarde";
            }
            
            error_log("[".date("Y-m-d H:i:s")."] [reserves/calculatePrice] ERROR: ".$e->getMessage());
            
            if ($request->getHost() == "www.arriendas.cl" && $e->getCode() < 2) {
                Utils::reportError($e->getMessage(), "reserves/calculatePrice");
            }
        }
    
        $this->renderText(json_encode($return));

        return sfView::NONE;
    }

    public function executeCalculateTime (sfWebRequest $request) {

        $return = array("error" => false);

        $from  = $request->getPostParameter("from", null);
        $to    = $request->getPostParameter("to", null);

        try {

            $datesError = $this->validateDates($from, $to);
            if ($datesError) {
                throw new Exception($datesError, 1);
            }

            $return["tiempo"] = Car::getTime($from, $to);
        } catch (Exception $e) {
            $return["error"] = true;
            $return["errorMessage"] = $e->getMessage();
        }
    
        $this->renderText(json_encode($return));

        return sfView::NONE;
    }

    public function executeChange (sfWebRequest $request) {
    
        $return = array("error" => false);
    
        $newReserveId = $request->getPostParameter("newReserveId", null);
        $userId = $this->getUser()->getAttribute("userid");

        try {
    
            if (is_null($newReserveId) || $newReserveId == "") {
                throw new Exception("User ".$userId." intenta realizar un cambio de auto, pero falta el ID de la nueva Reserve", 1);
            }

            $NewReserve = Doctrine_Core::getTable('Reserve')->find($newReserveId);
            if (!$NewReserve) {
                throw new Exception("User ".$userId." está intentado realizar un cambio pero no se ha podido encontrar la Reserve ".$newReserveId, 1);
            }

            if (!$this->makeChange($NewReserve)) {
                throw new Exception("User ".$userId." está intentado realizar un cambio a Car ".$NewReserve->getCar()->id." pero este no se ha podido concretar", 2);
            }

            $Renter = $NewReserve->getUser();
            $Owner  = $NewReserve->getCar()->getUser();

            $mail    = new Email();
            $mailer  = $mail->getMailer();
            
            $functions  = new Functions;
            $formulario = $functions->generarFormulario(NULL, $NewReserve->token);
            $reporte    = $functions->generarReporte($NewReserve->getCar()->id);
            $contrato   = $functions->generarContrato($NewReserve->token);
            $pagare     = $functions->generarPagare($NewReserve->token);

            // CORREO DUEÑO
            $subject = "¡Has ganado la oportunidad!";
            $body    = $this->getPartial('emails/opportunityWonOwner', array('Reserve' => $NewReserve));
            $from    = array("soporte@arriendas.cl" => "Soporte Arriendas.cl");
            $to      = array($Owner->email => $Owner->firstname." ".$Owner->lastname);

            $message = $mail->getMessage();
            $message->setSubject($subject);
            $message->setBody($body, 'text/html');
            $message->setFrom($from);
            $message->setTo($to);
            // $message->setBcc(array("cristobal@arriendas.cl" => "Cristóbal Medina Moenne"));

            $message->attach(Swift_Attachment::newInstance($contrato, 'contrato.pdf', 'application/pdf'));
            $message->attach(Swift_Attachment::newInstance($formulario, 'formulario.pdf', 'application/pdf'));
            $message->attach(Swift_Attachment::newInstance($reporte, 'reporte.pdf', 'application/pdf'));
            
            if (!is_null($Renter->getDriverLicenseFile())) {
                $filepath = $Renter->getDriverLicenseFile();
                if (is_file($filepath)) {
                    $message->attach(Swift_Attachment::fromPath($Renter->getDriverLicenseFile())->setFilename("LicenciaArrendatario-".$Renter->getLicenceFileName()));
                }
            }
            
            $mailer->send($message);

            // CORREO ARRENDATARIO
            $subject = "Has cambiado el auto de tu reserva";
            $body    = $this->getPartial('emails/opportunityWonRenter', array('Reserve' => $NewReserve));
            $from    = array("soporte@arriendas.cl" => "Soporte Arriendas.cl");
            $to      = array($Renter->email => $Renter->firstname." ".$Renter->lastname);

            $message = $mail->getMessage();
            $message->setSubject($subject);
            $message->setBody($body, 'text/html');
            $message->setFrom($from);
            $message->setTo($to);
            // $message->setBcc(array("cristobal@arriendas.cl" => "Cristóbal Medina Moenne"));

            $message->attach(Swift_Attachment::newInstance($contrato, 'contrato.pdf', 'application/pdf'));
            $message->attach(Swift_Attachment::newInstance($formulario, 'formulario.pdf', 'application/pdf'));
            $message->attach(Swift_Attachment::newInstance($reporte, 'reporte.pdf', 'application/pdf'));
            $message->attach(Swift_Attachment::newInstance($pagare, 'pagare.pdf', 'application/pdf'));
                
            $mailer->send($message);

            // CORREO SOPORTE
            if ($NewReserve->reserva_original) {
                $originalReserveId = $NewReserve->reserva_original;
            } else {
                $originalReserveId = $NewReserve->id;
            }

            $subject = "Ha habido un cambio de auto en la Reserva ".$originalReserveId;
            $body    = $this->getPartial('emails/opportunityWonSupport', array('Reserve' => $NewReserve));
            $from    = array("no-reply@arriendas.cl" => "Notificaciones Arriendas.cl");
            $to      = array("soporte@arriendas.cl" => "Soporte Arriendas.cl");

            $message = $mail->getMessage();
            $message->setSubject($subject);
            $message->setBody($body, 'text/html');
            $message->setFrom($from);
            $message->setTo($to);
            $message->setBcc(array("cristobal@arriendas.cl" => "Cristóbal Medina Moenne"));

            $message->attach(Swift_Attachment::newInstance($contrato, 'contrato.pdf', 'application/pdf'));
            $message->attach(Swift_Attachment::newInstance($formulario, 'formulario.pdf', 'application/pdf'));
            $message->attach(Swift_Attachment::newInstance($reporte, 'reporte.pdf', 'application/pdf'));
            
            if (!is_null($Renter->getDriverLicenseFile())) {
                $filepath = $Renter->getDriverLicenseFile();
                if (is_file($filepath)) {
                    $message->attach(Swift_Attachment::fromPath($Renter->getDriverLicenseFile())->setFilename("LicenciaArrendatario-".$Renter->getLicenceFileName()));
                }
            }

            $mailer->send($message);
        } catch (Exception $e) {
            $return["error"] = true;
            $return["errorMessage"] = "No se pudo realizar el cambio. Por favor, intentalo nuevamente más tarde";
            error_log("[".date("Y-m-d H:i:s")."] [reserves/change] ERROR: ".$e->getMessage());
            if ($request->getHost() == "www.arriendas.cl") {
                Utils::reportError($e->getMessage(), "reserves/change");
            }
        }
    
        $this->renderText(json_encode($return));

        return sfView::NONE;
    }

    public function executeExtend (sfWebRequest $request) {
    
        $reserveId = $request->getPostParameter("reserveId", null);
        $from      = $request->getPostParameter("from", null);
        $to        = $request->getPostParameter("to", null);

        $datesError = $this->validateDates($from, $to);
        if ($datesError) {
            throw new Exception($datesError, 1);
        }

        if (is_null($reserveId) || $reserveId == "") {
            throw new Exception("Falta el ID de Reserve para poder extender", 1);
        }

        $Reserve = Doctrine_Core::getTable('Reserve')->find($reserveId);
        if (!$Reserve) {
            throw new Exception("No se encontró la Reserve ".$reserveId, 1);
        }

        if (is_null($from) || $from == "") {
            throw new Exception("Falta fecha desde", 1);
        }

        if (date("d-m-Y H:i", strtotime($Reserve->getFechaTermino2())) != $from) {
            throw new Exception("No está permitido cambiar la fecha de Inicio", 1);
        }

        if (is_null($to) || $to == "") {
            throw new Exception("Falta fecha hasta", 1);
        }

        $Car = $Reserve->getCar();        

        $NewReserve = $Reserve->copy(true);
        $NewReserve->setCar($Car);
        $NewReserve->setDate($Reserve->getFechaTermino2());
        $NewReserve->setDuration(Utils::calculateDuration($from, $to));
        $NewReserve->setPrice(CarTable::getPrice($from, $to, $Car->price_per_hour, $Car->price_per_day, $Car->price_per_week, $Car->price_per_month));
        $NewReserve->setComentario("Reserva extendida");
        $NewReserve->setFechaReserva(date("Y-m-d H:i:s"));
        $NewReserve->setIdPadre($Reserve->id);
        $NewReserve->setExtendUserId($this->getUser()->getAttribute("userid"));
        $NewReserve->setConfirmed(false);

        if ($Reserve->getLiberadoDeGarantia()) {
            $NewReserve->setMontoLiberacion(Reserve::calcularMontoLiberacionGarantia(sfConfig::get("app_monto_garantia_por_dia"), $from, $to));
        } else {
            $NewReserve->setMontoLiberacion(0);
        }

        $NewReserve->save();

        $NewTransaction = $Reserve->getTransaction()->copy(true);
        $NewTransaction->setPrice($NewReserve->getPrice());
        $NewTransaction->setDate($Reserve->getFechaTermino2());
        $NewTransaction->setReserve($NewReserve);
        $NewTransaction->setCompleted(false);
        $NewTransaction->setShowSuccess(0);
        $NewTransaction->save();

        $this->getRequest()->setParameter("reserveId", $NewReserve->getId());
        $this->getRequest()->setParameter("transactionId", $NewTransaction->getId());

        $this->forward("khipu", "generatePayment");
    }

    public function executeGetExtendPrice (sfWebRequest $request) {

        $return = array("error" => false);
    
        $reserveId = $request->getPostParameter("reserveId", null);
        $from      = $request->getPostParameter("from", null);
        $to        = $request->getPostParameter("to", null);

        try {

            $datesError = $this->validateDates($from, $to);
            if ($datesError) {
                throw new Exception($datesError, 1);
            }
    
            if (is_null($reserveId) || $reserveId == "") {
                throw new Exception("Falta la reserva", 1);
            }

            $Reserve = Doctrine_Core::getTable('Reserve')->find($reserveId);

            if (is_null($from) || $from == "") {
                throw new Exception("Falta fecha desde", 1);
            }

            if (date("d-m-Y H:i", strtotime($Reserve->getFechaTermino2())) != $from) {
                throw new Exception("No está permitido cambiar la fecha de Inicio", 1);
            }

            if (is_null($to) || $to == "") {
                throw new Exception("Falta fecha hasta", 1);
            }

            $Car = $Reserve->getCar();

            if ($Car->hasReserve($from, $to)) {
                throw new Exception("La extensión no se puede realizar debido a que el auto ya posee una reserva en la fecha consultada", 1);
            }

            $price = CarTable::getPrice($from, $to, $Car->getPricePerHour(), $Car->getPricePerDay(), $Car->getPricePerWeek(), $Car->getPricePerMonth());

            if ($Reserve->getLiberadoDeGarantia()) {
                $price += Reserve::calcularMontoLiberacionGarantia(sfConfig::get("app_monto_garantia_por_dia"), $from, $to);
            }

            $return["price"] = $price;

        } catch (Exception $e) {
            $return["error"] = true;
            $return["errorMessage"] = $e->getMessage();

            error_log("[".date("Y-m-d H:i:s")."] [reserves/getExtendPrice] ERROR: ".$e->getMessage());

            if ($request->getHost() == "www.arriendas.cl") {
                Utils::reportError($e->getMessage(), "reserves/getExtendPrice");
            }
        }
    
        $this->renderText(json_encode($return));

        return sfView::NONE;
    }

    public function executePay (sfWebRequest $request) {

        $userId = $this->getUser()->getAttribute('userid');

        

        if ($request->hasParameter('warranty','payment-group','car','from','to')) {
            $warranty = $request->getPostParameter("warranty", null);
            $payment  = $request->getPostParameter("payment-group", null); // Se saco la selección de khipu

            $carId = $request->getPostParameter("car", null);
            $from  = $request->getPostParameter("from", null);
            $to    = $request->getPostParameter("to", null);        
        } else {            
            $warranty = $this->getUser()->getAttribute("warranty");
            $payment  = $this->getUser()->getAttribute("payment",null);

            $carId    = $this->getUser()->getAttribute("carId");
            $from     = $this->getUser()->getAttribute("from");
            $to       = $this->getUser()->getAttribute("to");
        }
        
        try {

            if (is_null($warranty) || is_null($carId) || is_null($from) || is_null($to)) {
                throw new Exception("El User ".$userId." esta intentando pagar pero uno de los campos es nulo. Garantia: ".$warranty.", Car: ".$carId.", Desde: ".$from.", Hasta: ".$to, 1);
            }
            
            $datesError = $this->validateDates($from, $to);
            if (!is_null($datesError)) {
                throw new Exception($datesError, 2);
            }
            
            $User = Doctrine_Core::getTable('User')->find($userId);
            if ($User->getBlocked()) {
                throw new Exception("Rechazado el pago de User ".$userId." (".$User->firstname." ".$User->lastname.") debido a que se encuentra bloqueado, por lo que no esta autorizado para generar pagos", 1);            
            }
            
            $Car = Doctrine_Core::getTable('Car')->find($carId);
            if (!$Car) {
                throw new Exception("El User ".$userId." esta intentando pagar pero no se encontró el auto.", 1);
            }
            
            if ($Car->hasReserve($from, $to)) {
                throw new Exception("El User ".$userId." esta intentando pagar pero el Car ".$carId." ya posee un reserva en las fechas indicadas.", 1);
            }
            
            $Reserve = new Reserve();
            $Reserve->setDuration(Utils::calculateDuration($from, $to));
            $Reserve->setDate(date("Y-m-d H:i:s", strtotime($from)));
            $Reserve->setUser($User);
            $Reserve->setCar($Car);
            
            if ($warranty) {
                $amountWarranty = sfConfig::get("app_monto_garantia");
                $Reserve->setLiberadoDeGarantia(false);
            } else {
                $amountWarranty = Reserve::calcularMontoLiberacionGarantia(sfConfig::get("app_monto_garantia_por_dia"), $from, $to);
                $Reserve->setLiberadoDeGarantia(true);
            }
            
            $Reserve->setPrice(CarTable::getPrice($from, $to, $Car->getPricePerHour(), $Car->getPricePerDay(), $Car->getPricePerWeek(), $Car->getPricePerMonth()));
            $Reserve->setMontoLiberacion($amountWarranty);
            $Reserve->setFechaReserva(date("Y-m-d H:i:s"));
            $Reserve->setConfirmed(false);
            $Reserve->setImpulsive(true);
            
            $Reserve->save();
            
            $Transaction = new Transaction();
            $Transaction->setCar($Car->getModel()->getName()." ".$Car->getModel()->getBrand()->getName());
            $Transaction->setPrice($Reserve->getPrice());
            $Transaction->setUser($Reserve->getUser());
            $Transaction->setDate(date("Y-m-d H:i:s"));
            
            $TransactionType = Doctrine_Core::getTable('TransactionType')->find(1);
            $Transaction->setTransactionType($TransactionType);
            $Transaction->setReserve($Reserve);
            $Transaction->setCompleted(false);
            $Transaction->setImpulsive(true);
            
            $Transaction->save();
        } catch (Exception $e) {
            error_log("[".date("Y-m-d H:i:s")."] [reserves/pay] ".$e->getMessage());
            if ($request->getHost() == "www.arriendas.cl" && $e->getCode() < 2) {
                Utils::reportError($e->getMessage(), "reserves/pay");
            }
            $this->redirect("homepage");
        }

        $this->getRequest()->setParameter("reserveId", $Reserve->getId());
        $this->getRequest()->setParameter("transactionId", $Transaction->getId());

        $this->forward("khipu", "generatePayment");
    }

    public function executeReject (sfWebRequest $request) {

        $return = array("error" => false);
    
        $reserveId = $request->getPostParameter("reserveId", null);

        try {

            if (is_null($reserveId) || $reserveId == "") {
                throw new Exception("Falta reserveId");
            }

            $Reserve = Doctrine_Core::getTable('Reserve')->find($reserveId);

            $Reserve->setConfirmed(false);
            $Reserve->setCanceled(true);

            // Correo de notificación
            $mail    = new Email();
            $mailer  = $mail->getMailer();
            $message = $mail->getMessage();
            $User    = $Reserve->getUser();

            $message->setSubject("La reserva ha sido rechazada");
            $message->setBody($this->getPartial('emails/reserveRejected', array('Reserve' => $Reserve)), 'text/html');
            $message->setFrom(array("soporte@arriendas.cl" => "Soporte Arriendas.cl"));
            $message->setTo(array($User->email => $User->firstname." ".$User->lastname));
            /*$message->setBcc(array("cristobal@arriendas.cl" => "Cristóbal Medina Moenne"));*/
            
            $mailer->send($message);

            $Reserve->save();
        } catch (Exception $e) {
            $return["error"] = true;
            $return["errorMessage"] = $e->getMessage();
            error_log("[".date("Y-m-d H:i:s")."] [reserves/reject] ".$e->getMessage());
            if ($request->getHost() == "www.arriendas.cl") {
                Utils::reportError($e->getMessage(), "reserves/reject");
            }
        }

        $this->renderText(json_encode($return));

        return sfView::NONE;
    }

    // FUNCIONES PRIVADAS
    private function makeChange ($newActiveReserveId) {

        $numero_factura = 0;

        try {

            $NewActiveReserve = Doctrine_Core::getTable('Reserve')->find($newActiveReserveId);

            $originalReserveId = $NewActiveReserve->id;
            if ($NewActiveReserve->reserva_original > 0) {
                $originalReserveId = $NewActiveReserve->reserva_original;
            }

            $ActiveReserve = Doctrine_Core::getTable('Reserve')->findActiveReserve($originalReserveId);

            $NewActiveReserve->setNumeroFactura($ActiveReserve->getNumeroFactura());
            $NewActiveTransaction = $NewActiveReserve->getTransaction();
            $NewActiveTransaction->setNumeroFactura($ActiveReserve->getTransaction()->getNumeroFactura());
            $NewActiveTransaction->setCompleted(true);
            $NewActiveTransaction->save();
            $NewActiveReserve->save();

            $Rating = $NewActiveReserve->getRating();
            $Rating->setIdOwner($NewActiveReserve->getCar()->getUser()->id);
            $Rating->save();

            $ActiveReserve->setNumeroFactura(0);
            $ActiveTransaction = $ActiveReserve->getTransaction();
            $ActiveTransaction->setNumeroFactura(0);
            $ActiveTransaction->setCompleted(false);
            $ActiveTransaction->save();
            $ActiveReserve->save();
        } catch (Exception $e) {
            error_log("[".date("Y-m-d H:i:s")."] [reserves/makeChange] ERROR: ".$e->getMessage());
            return false;
        }

        return true;
    }

    private function validateDates ($from, $to) {

        $from = strtotime($from);
        $to   = strtotime($to);

        if ($from >= $to) {
            return "La fecha de término debe ser al menos 3 horas superior a la fecha de inicio.";
        }

        return null;
    }
}
