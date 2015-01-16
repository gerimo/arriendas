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
                throw new Exception("Falta la reserva", 1);
            }

            $Reserve = Doctrine_Core::getTable('Reserve')->find($reserveId);
            if (!$Reserve) {
                throw new Exception("Reserva no encontrada", 1);
            }

            if ($Reserve->getCar()->getUser()->getId() != $this->getUser()->getAttribute("userid")) {
                throw new Exception("La reserva corresponde a otro usuario", 1);
            }

            if ($Reserve->getConfirmed()) {
                throw new Exception("La reserva ya se encuentra aprobada", 1);
            }

            if ($Reserve->getUser()->getBlocked()){            
                throw new Exception("No se pudo aprobar la reserva. El arrendatario se encuentra bloqueado");
            }

            $Reserve->setConfirmed(1);
            $Reserve->setCanceled(0);
            $Reserve->setFechaConfirmacion(date("Y-m-d H:i:s"));
            $Reserve->save();

            $Transaction = $Reserve->getTransaction();
            /*$Transaction->setSelected(true);*/
            $Transaction->save();

            // Correos de aprobación
            
        } catch (Exception $e) {
            $return["error"] = true;
            $return["errorMessage"] = $e->getMessage();

            if ($e->getCode() == 2) {
                /*Utils::reportError($e->getMessage(), "profile/reserveChange");*/
            }
        }
    
        $this->renderText(json_encode($return));

        return sfView::NONE;
    }

    public function executeChange (sfWebRequest $request) {
    
        $return = array("error" => false);
    
        $newReserveId = $request->getPostParameter("newReserveId", null);

        try {
    
            if (is_null($newReserveId) || $newReserveId == "") {
                throw new Exception("Falta la reserva", 2);
            }

            $NewReserve = Doctrine_Core::getTable('Reserve')->find($newReserveId);

            if (!$NewReserve->getTransaction()->select()) {
                throw new Exception("No se pudo realizar el cambio. Por favor, intentalo nuevamente más tarde.", 1);
            }

            /*$oq = Doctrine_Core::getTable("OportunityQueue")->findOneByReserveId($reserveId);
            $oq->setIsActive(false);
            $oq->save();

            $Renter = $r->getUser();
            $Owner = $r->getCar()->getUser();

            $functions = new Functions;
            $formulario = $functions->generarFormulario(NULL, $r->getToken());
            $reporte = $functions->generarReporte($reserve->getCar()->getId());
            $contrato = $functions->generarContrato($r->getToken());
            $pagare = $functions->generarPagare($r->getToken());

            // DOCUMENTOS DUEÑO

            $subject = "¡Has ganado la oportunidad!";

            $body = "<p>".$Owner->getFirstname().",</p>";
            $body .= "<p>Han aceptado tu postulación por un total de <strong>".number_format(($r->getPrice()), 0, ',', '.')."</strong>, desde ".$r->getFechaInicio()." ".$r->getHoraInicio()." hasta ".$r->getFechaTermino()." ".$r->getHoraTermino().". Te recomendamos que llames al arrendatario cuanto antes. Sus datos son los siguientes:</p>";
            $body .= "<table>";
            $body .= "<tr><th style='text-align: left'>Nombre</th><td>".$Renter->getFirstname()." ".$Renter->getLastname()."</td></tr>";
            $body .= "<tr><th style='text-align: left'>Teléfono</th><td>".$Renter->getTelephone()."</td></tr>";
            $body .= "<tr><th style='text-align: left'>Correo electrónico</th><td>".$Renter->getEmail()."</td></tr>";
            $body .= "</table>";
            $body .= "<br><br>";
            $body .= "<p style='color: #aaa; font-size:14px; margin: 0; padding: 3px 0 0 0'>Atentamente</p>";
            $body .= "<p style='color: #aaa; font-size:14px; margin: 0; padding: 3px 0 0 0'>Equipo Arriendas.cl</p>";

            $mail = new Email();
            $message = $mail->getMessage()
                ->setSubject($subject)
                ->setBody($body, 'text/html')
                ->setFrom(array("soporte@arriendas.cl" => "Soporte Arriendas.cl"))
                ->setTo(array($Owner->getEmail() => $Owner->getFirstName()." ".$Owner->getLastname()))
                ->attach(Swift_Attachment::newInstance($contrato, 'contrato.pdf', 'application/pdf'))
                ->attach(Swift_Attachment::newInstance($formulario, 'formulario.pdf', 'application/pdf'))
                ->attach(Swift_Attachment::newInstance($reporte, 'reporte.pdf', 'application/pdf'));
            
            if (!is_null($Renter->getDriverLicenseFile())) {
                $filepath = $Renter->getDriverLicenseFile();
                if (is_file($filepath)) {
                    $message->attach(Swift_Attachment::fromPath($Renter->getDriverLicenseFile())->setFilename("LicenciaArrendatario-".$Renter->getLicenceFileName()));
                }
            }
            
            $mailer->send($message);

            // DOCUMENTOS ARRENDATARIO

            $subject = "Has cambiado el auto de tu reserva";

            $body = "<p>Hola ".$Renter->getFirstname().",</p>";
            $body .= "<p>Has cambiado el auto de tu reserva y esta ya esta confirmada. Recuerda que debes llenar el <strong>Formulario de entrega</strong> Y <strong>Devolución</strong> del vehículo.</p>";
            $body .= "<p>No des inicio al arriendo si el auto tiene más daños que los declarados.</p>";
            $body .= "<h3>Datos del propietario</h3>";
            $body .= "<table>";
            $body .= "<tr><th style='text-align: left'>Nombre</th><td>".$Owner->getFirstname()." ".$Owner->getLastname()."</td></tr>";
            $body .= "<tr><th style='text-align: left'>Teléfono</th><td>".$Owner->getTelephone()."</td></tr>";
            $body .= "<tr><th style='text-align: left'>Correo electrónico</th><td>".$Owner->getEmail()."</td></tr>";
            $body .= "<tr><th style='text-align: left'>Dirección</th><td>".$Owner->getAddress()."</td></tr>";
            $body .= "<tr><th style='text-align: left'>Marca</th><td>".$r->getCar()->getModel()->getBrand()->getName()."</td></tr>";
            $body .= "<tr><th style='text-align: left'>Modelo</th><td>".$r->getCar()->getModel()->getName()."</td></tr>";
            $body .= "</table>";
            $body .= "<p>Los datos del arriendo y la versión escrita del formulario de entrega, se encuentran adjuntos en formato PDF.</p>";
            $body .= "<br><br>";
            $body .= "<p style='color: #aaa; font-size:14px; margin: 0; padding: 3px 0 0 0'>Atentamente</p>";
            $body .= "<p style='color: #aaa; font-size:14px; margin: 0; padding: 3px 0 0 0'>Equipo Arriendas.cl</p>";

            $mail = new Email();
            $message = $mail->getMessage()
                ->setSubject($subject)
                ->setBody($body, 'text/html')
                ->setFrom(array("soporte@arriendas.cl" => "Soporte Arriendas.cl"))
                ->setTo(array($Renter->getEmail() => $Renter->getFirstName()." ".$Renter->getLastname()))
                ->attach(Swift_Attachment::newInstance($contrato, 'contrato.pdf', 'application/pdf'))
                ->attach(Swift_Attachment::newInstance($formulario, 'formulario.pdf', 'application/pdf'))
                ->attach(Swift_Attachment::newInstance($reporte, 'reporte.pdf', 'application/pdf'))
                ->attach(Swift_Attachment::newInstance($pagare, 'pagare.pdf', 'application/pdf'));
                
            $mailer->send($message);

            // DOCUMENTOS SOPORTE

            $subject = "Ha habido un cambio de auto en la Reserva ".$originalReserve->getId();

            $body = "<h3>Datos del propietario</h3>";
            $body .= "<table>";
            $body .= "<tr><th style='text-align: left'>Nombre</th><td>".$Owner->getFirstname()." ".$Owner->getLastname()."</td></tr>";
            $body .= "<tr><th style='text-align: left'>Teléfono</th><td>".$Owner->getTelephone()."</td></tr>";
            $body .= "<tr><th style='text-align: left'>Correo electrónico</th><td>".$Owner->getEmail()."</td></tr>";
            $body .= "<tr><th style='text-align: left'>Dirección</th><td>".$Owner->getAddress()."</td></tr>";
            $body .= "<tr><th style='text-align: left'>Marca</th><td>".$r->getCar()->getModel()->getBrand()->getName()."</td></tr>";
            $body .= "<tr><th style='text-align: left'>Modelo</th><td>".$r->getCar()->getModel()->getName()."</td></tr>";
            $body .= "</table>";
            $body .= "<h3>Datos del arrendatario</h3>";
            $body .= "<table>";
            $body .= "<tr><th style='text-align: left'>Nombre</th><td>".$Renter->getFirstname()." ".$Renter->getLastname()."</td></tr>";
            $body .= "<tr><th style='text-align: left'>Teléfono</th><td>".$Renter->getTelephone()."</td></tr>";
            $body .= "<tr><th style='text-align: left'>Correo electrónico</th><td>".$Renter->getEmail()."</td></tr>";
            $body .= "</table>";
            $body .= "<br><br>";
            $body .= "<p style='color: #aaa; font-size:14px; margin: 0; padding: 3px 0 0 0'>Atentamente</p>";
            $body .= "<p style='color: #aaa; font-size:14px; margin: 0; padding: 3px 0 0 0'>Equipo Arriendas.cl</p>";

            $mail = new Email();
            $message = $mail->getMessage()
                ->setSubject($subject)
                ->setBody($body, 'text/html')
                ->setFrom(array("no-reply@arriendas.cl" => "Notificaciones Arriendas.cl"))
                ->setTo(array("soporte@arriendas.cl" => "Soporte Arriendas.cl"))
                ->setBcc(array("cristobal@arriendas.cl" => "Cristóbal Medina Moenne"))
                ->attach(Swift_Attachment::newInstance($contrato, 'contrato.pdf', 'application/pdf'))
                ->attach(Swift_Attachment::newInstance($formulario, 'formulario.pdf', 'application/pdf'))
                ->attach(Swift_Attachment::newInstance($reporte, 'reporte.pdf', 'application/pdf'));
            
            if (!is_null($Renter->getDriverLicenseFile())) {
                $filepath = $Renter->getDriverLicenseFile();
                if (is_file($filepath)) {
                    $message->attach(Swift_Attachment::fromPath($Renter->getDriverLicenseFile())->setFilename("LicenciaArrendatario-".$Renter->getLicenceFileName()));
                }
            }

            $mailer->send($message);*/
    
        } catch (Exception $e) {

            $return["error"] = true;
            $return["errorMessage"] = $e->getMessage();

            if ($e->getCode() == 2) {
                /*Utils::reportError($e->getMessage(), "profile/reserveChange");*/
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

        $NewReserve = $Reserve->copy(true);
        $NewReserve->setCar($Car);
        $NewReserve->setDate($Reserve->getFechaTermino2());
        $NewReserve->setDuration(Utils::calculateDuration($from, $to));
        $NewReserve->setPrice(Car::getPrice($from, $to, $Car->price_per_hour, $Car->price_per_day, $Car->price_per_week, $Car->price_per_month));
        $NewReserve->setComentario("Reserva extendida");
        $NewReserve->setFechaReserva(date("Y-m-d H:i:s"));
        $NewReserve->setIdPadre($Reserve->id);
        $NewReserve->setExtendUserId($this->getUser()->getAttribute("userid"));
        $NewReserve->setConfirmed(false);

        if ($Reserve->getLiberadoDeGarantia()) {
            $NewReserve->montoLiberacion(0);
        } else {
            $NewReserve->montoLiberacion(Reserve::calcularMontoLiberacionGarantia(sfConfig::get("app_monto_garantia_por_dia"), $from, $to));
        }

        $NewReserve->save();

        $NewTransaction = $Reserve->getTransaction()->copy(true);
        $NewTransaction->setPrice($NewReserve->getPrice());
        $NewTransaction->setDate($Reserve->getFechaTermino2());
        $NewTransaction->setReserve($NewReserve);
        $NewTransaction->setCompleted(false);
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

            $return["price"] = Car::getPrice($from, $to, $Car->getPricePerHour(), $Car->getPricePerDay(), $Car->getPricePerWeek(), $Car->getPricePerMonth());
        } catch (Exception $e) {
            $return["error"] = true;
            $return["errorMessage"] = $e->getMessage();
        }
    
        $this->renderText(json_encode($return));

        return sfView::NONE;
    }

    public function executePay (sfWebRequest $request) {

        $userId = sfContext::getInstance()->getUser()->getAttribute('userid');

        $warranty = $request->getPostParameter("warranty", null);
        $payment  = $request->getPostParameter("payment-group", null);

        $carId = $request->getPostParameter("car", null);
        $from  = $request->getPostParameter("from", null);
        $to    = $request->getPostParameter("to", null);

        if (is_null($warranty) || is_null($payment) || is_null($carId) || is_null($from) || is_null($to)) {
            throw new Exception("No, no, no.", 1);
        }

        $datesError = $this->validateDates($from, $to);
        if ($datesError) {
            throw new Exception($datesError, 1);
        }

        $User = Doctrine_Core::getTable('User')->find($userId);
        if ($User->getBlockled()) {
            throw new Exception("Usuario no autorizado para generar pagos", 1);            
        }

        $Car = Doctrine_Core::getTable('Car')->find($carId);
        if (!$Car) {
            throw new Exception("No se encontró el auto.", 1);
        }

        if ($Car->hasReserve($from, $to)) {
            throw new Exception("El auto ya posee un reserva en las fechas indicadas.", 1);
        }

        // Guardo en session // Esto se deja 'xsiaca'
        $this->getUser()->setAttribute('fechainicio', date("d-m-Y", strtotime($from)));
        $this->getUser()->setAttribute('fechatermino', date("d-m-Y", strtotime($to)));
        $this->getUser()->setAttribute('horainicio', date("g:i A",strtotime($from)));
        $this->getUser()->setAttribute('horatermino', date("g:i A",strtotime($to)));

        $Reserve = new Reserve();
        $Reserve->setDuration(Utils::calculateDuration($from, $to));
        $Reserve->setDate(date("Y-m-d H:i:s", strtotime($from)));
        $Reserve->setUser($User);
        $Reserve->setCar($Car);

        if ($User->getBlocked()) {
            $Reserve->setVisibleOwner(false);
            $Reserve->setConfirmed(true);
        }

        if ($warranty) {
            $amountWarranty = sfConfig::get("app_monto_garantia");
            $Reserve->setLiberadoDeGarantia(false);
        } else {
            $amountWarranty = Reserve::calcularMontoLiberacionGarantia(sfConfig::get("app_monto_garantia_por_dia"), $from, $to);
            $Reserve->setLiberadoDeGarantia(true);
        }

        $Reserve->setPrice(Car::getPrice($from, $to, $Car->getPricePerHour(), $Car->getPricePerDay(), $Car->getPricePerWeek(), $Car->getPricePerMonth()));
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
        $Transaction->setSelected(true);
        $Transaction->save();

        $this->getRequest()->setParameter("reserveId", $Reserve->getId());
        $this->getRequest()->setParameter("transactionId", $Transaction->getId());

        $this->forward("khipu", "generatePayment");
    }

    public function executeReject (sfWebRequest $request) {

        $return = array("error" => false);
    
        $reserveId = $request->getPostParameter("reserveId", null);

        try {

            if (is_null($reserveId) || $reserveId == "") {
                throw new Exception("Falta la reserva", 1);
            }

            $Reserve = Doctrine_Core::getTable('Reserve')->find($reserveId);

            $Reserve->setCanceled(true);
            $Reserve->save();
        } catch (Exception $e) {
            $return["error"] = true;
            $return["errorMessage"] = $e->getMessage();
        }

        $this->renderText(json_encode($return));

        return sfView::NONE;
    }

    // FUNCIONES PRIVADAS
    private function validateDates ($from, $to) {

        $from = strtotime($from);
        $to   = strtotime($to);

        if ($from >= $to) {
            return "La fecha de inicio debe ser menor a la fecha de término.";
        }

        return false;
    }
}
