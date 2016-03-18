<?php

require_once sfConfig::get('sf_lib_dir') . '/vendor/fabpot/goutte.phar';

class reservesActions extends sfActions {

    public function executeIndex(sfWebRequest $request) {
        
        $this->setLayout("newIndexLayout");
        
        $userId = sfContext::getInstance()->getUser()->getAttribute('userid');

        $this->PaidReserves = Reserve::getPaidReserves($userId);

        $this->Reserves = Reserve::getReservesByUser($userId);

        $this->ChangeOptions = array();
        $this->CarsWithAvailability = array();

        foreach ($this->Reserves as $Reserve) {

            foreach ($Reserve->getChangeOptions() as $ChangeOption) {
                $this->ChangeOptions[$Reserve->getId()][] = $ChangeOption;
            }

            if (Utils::isWeekend()) {
                $this->CarsWithAvailability[$Reserve->getId()] = Doctrine_Core::getTable('CarAvailability')->findChangeOptions($Reserve->id);
            }
        }
    }

    public function executeAirport (sfWebRequest $request) {
        $this->setLayout("newIndexLayout");
    }

    public function executeAirportKiphu (sfWebRequest $request) {
        $reserveId     = $this->getUser()->getAttribute("reserveId", null);
        $transactionId = $this->getUser()->getAttribute("transactionId", null);

        $this->getRequest()->setParameter("reserveId", $reserveId);
        $this->getRequest()->setParameter("transactionId", $transactionId);

        $this->forward("khipu", "generatePayment");
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
                error_log("[".date("Y-m-d H:i:s")."] [reserves/approve] Hubo un problema al aprobar la Reserve ".$Reserve->id.". No se ha podido encontrar la OpportunityQueue para ser desactivada");
            }

            // Correo de notificación
            $User    = $Reserve->getUser();
            $UserCar     = $Reserve->getCar()->getUser();;

             // Notificaciones
            Notification::make($UserCar->id, 8, $Reserve->id); // Confirmar pago
            Notification::make($User->id, 16, $Reserve->id); // reserva pago


            $Functions  = new Functions;
            $formulario = $Functions->generarFormulario(NULL, $Reserve->token);
            $reporte    = $Functions->generarReporte($Reserve->getCar()->id);
            $contrato   = $Functions->generarContrato($Reserve->token);

            $subject = "¡Tu reserva ha sido aprobada!";
            $body    = $this->getPartial('emails/reserveApproved', array('Reserve' => $Reserve));
            $from    = array("soporte@arriendas.cl" => "Soporte Arriendas.cl");
            $to      = array($User->email => $User->firstname." ".$User->lastname);

            $message = Swift_Message::newInstance();
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
            
            $this->getMailer()->send($message);
            
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

    public function executeCalculateAmountWarrantyFree (sfWebRequest $request) {
        
        $return = array("error" => false);

        $from  = $request->getPostParameter("from", null);
        $to    = $request->getPostParameter("to", null);

        try {

            $datesError = Utils::validateDates($from, $to);
            if ($datesError) {
                throw new Exception($datesError, 2);
            }

            //$return["amountWarrantyFree"] = Reserve::calcularMontoLiberacionGarantia(sfConfig::get("app_monto_garantia_por_dia"), $from, $to);
            $return["amountWarrantyFree"] = Reserve::calcularMontoLiberacionGarantia(round(Doctrine_Core::getTable('variables')->find(3)->getValue(), 0, PHP_ROUND_HALF_UP), $from, $to);

        } catch (Exception $e) {

            $return["error"] = true;

            if ($e->getCode() >= 2) {
                $return["errorMessage"] = $e->getMessage();
            } else {
                $return["errorMessage"] = "Problemas a calcular el monto de liberación de garantía. Por favor, intentalo más tarde";
            }
            
            error_log("[".date("Y-m-d H:i:s")."] [reserves/calculateAmountWarrantyFree] ERROR: ".$e->getMessage());
            
            if ($request->getHost() == "www.arriendas.cl" && $e->getCode() < 2) {
                Utils::reportError($e->getMessage(), "reserves/calculateAmountWarrantyFree");
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

            $datesError = Utils::validateDates($from, $to);
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

            // se exrae la comisión base.
            $baseCommissionValue = Doctrine_Core::getTable('variables')->find(1);

            // se exrae la comisión para el pago con TransBank.
            $transBankCommission = Doctrine_Core::getTable('variables')->find(2);

            $price = CarTable::getPrice($from, $to, $Car->price_per_hour, $Car->price_per_day, $Car->price_per_week, $Car->price_per_month);

            $baseCommission = $price *($baseCommissionValue->value / 100);

            $transBankCommission = $price * ($transBankCommission->value / 100);

            $return["price"]                = $price;
            $return["baseCommission"]       = number_format($baseCommission, 0, ',', '.');
            $return["transBankCommission"]  = number_format($transBankCommission, 0, ',', '.');
            $return["baseCommissionValue"]       = $baseCommission;
            $return["transBankCommissionValue"]  = $transBankCommission;

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

            $datesError = Utils::validateDates($from, $to);
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
        $userId       = $this->getUser()->getAttribute("userid");

        $NewActiveReserve = Doctrine_Core::getTable('Reserve')->find($newReserveId);
        $this->forward404If(!$NewActiveReserve);

        if (!$this->makeChange($NewActiveReserve)) {
            $return["error"] = true;
            $return["errorMessage"] = "No se pudo realizar el cambio. Por favor, intentalo nuevamente más tarde";
        }
    
        $this->renderText(json_encode($return));

        return sfView::NONE;
    }

    public function executeChangeWithAvailability (sfWebRequest $request) {

        $return = array("error" => false);
    
        $carId      = $request->getPostParameter("carId", null);
        $reserveId  = $request->getPostParameter("reserveId", null);
        $userId     = $this->getUser()->getAttribute("userid");

        $Car = Doctrine_Core::getTable('Car')->find($carId);
        $this->forward404If(!$Car);

        $originalReserveId = Doctrine_Core::getTable('Reserve')->findOriginalReserve($reserveId);

        $OriginalReserve = Doctrine_Core::getTable('Reserve')->find($originalReserveId);
        $this->forward404If(!$OriginalReserve);

        try {
            
            $this->forward404If(!$Car->hasAvailability($OriginalReserve->getFechaInicio2()));

            $carPrice = CarTable::getPrice($OriginalReserve->getFechaInicio2(), $OriginalReserve->getFechaTermino2(), $Car->getPricePerHour(), $Car->getPricePerDay(), $Car->getPricePerWeek(), $Car->getPricePerMonth());
            $originalCarPrice = $OriginalReserve->getRentalPrice();

            if ($carPrice > $originalCarPrice * 1.15) {
                error_log("[reserves/changeWithAvailability] El User ".$userId." esta intentando cambiar por el Car ".$carId." con mas de un 15% de diferencia en el precio con Reserve ".$reserveId);
                $this->forward404();
            }

            $O = $OriginalReserve->copy(true);
            $O->setCar($Car);
            $O->setFechaReserva(date("Y-m-d H:i:s"));
            $O->setFechaConfirmacion(date("Y-m-d H:i:s"));
            $O->setConfirmed(true);
            $O->setImpulsive(true);
            $O->setReservaOriginal($OriginalReserve->getId());
            $O->setComentario('Reserva oportunidad - auto con disponibilidad');            
            $O->setUniqueToken(true);
            $O->save();

            $OT = $OriginalReserve->getTransaction()->copy(true);
            $OT->setCar($Car->getModel()->getBrand()->getName() ." ". $Car->getModel()->getName());
            $OT->setReserve($O);
            $OT->setDate(date("Y-m-d H:i:s"));
            $OT->setCompleted(false);
            $OT->setImpulsive(true);
            $OT->setTransaccionOriginal($OriginalReserve->getTransaction()->getId());

            if ($carPrice > $originalCarPrice) {
                $OT->setPrice($carPrice);
                $OT->setDiscountAmount($carPrice - $originalCarPrice);
                $O->setPrice($carPrice);
                $O->save();
            } elseif ($carPrice < $originalCarPrice) {
                $OT->setReverseDiscount($originalCarPrice - $carPrice);
            }


            $OT->save();

            Notification::make($O->getUser()->id, 17, $O->id); // Cambio de reserva


            if (!$this->makeChange($O)) {
                $return["error"] = true;
                $return["errorMessage"] = "No se pudo realizar el cambio. Por favor, intentalo nuevamente más tarde";
            }

        } catch (Exception $e) {
            $return["error"] = true;
            $return["errorMessage"] = "No se pudo realizar el cambio. Por favor, intentalo nuevamente más tarde";
            error_log("[reserves/changeWithAvailability] ERROR: ".$e->getMessage());
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

        $datesError = Utils::validateDates($from, $to);
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
        $baseCommissionValue = Doctrine_Core::getTable('variables')->find(1)->getValue();

        $Car = $Reserve->getCar();

        $NewReserve = $Reserve->copy(true);
        $NewReserve->setCar($Car);
        $NewReserve->setDate($Reserve->getFechaTermino2());
        $NewReserve->setDuration(Utils::calculateDuration($from, $to));
        $NewReserve->setPrice($this->calculateExtendedPrice($Reserve, $to));
        $NewReserve->setComentario("Reserva extendida");
        $NewReserve->setFechaReserva(date("Y-m-d H:i:s"));
        $NewReserve->setIdPadre($Reserve->id);

        $baseCommission = $NewReserve->getPrice() * ($baseCommissionValue / 100);
        $NewReserve->setBaseCommission($baseCommission);
        $NewReserve->setTransbankCommission(0);

        $NewReserve->setExtendUserId($this->getUser()->getAttribute("userid"));
        $NewReserve->setConfirmed(false);

        if ($Reserve->getLiberadoDeGarantia()) {
            //$NewReserve->setMontoLiberacion(Reserve::calcularMontoLiberacionGarantia(sfConfig::get("app_monto_garantia_por_dia"), $from, $to));
            $NewReserve->setMontoLiberacion(Reserve::calcularMontoLiberacionGarantia(round(Doctrine_Core::getTable('variables')->find(3)->getValue(), 0, PHP_ROUND_HALF_UP), $from, $to));
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

        $NewTransaction->setBaseCommission($baseCommission);
        $NewTransaction->setTransbankCommission(0);

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

        $baseCommissionValue = Doctrine_Core::getTable('variables')->find(1)->getValue();

        try {

            $datesError = Utils::validateDates($from, $to);
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

            $price = $this->calculateExtendedPrice($Reserve, $to);
            $baseCommission = $price * ($baseCommissionValue / 100);
            if ($Reserve->getLiberadoDeGarantia()) {
                //$price += Reserve::calcularMontoLiberacionGarantia(sfConfig::get("app_monto_garantia_por_dia"), $from, $to);
                $price += Reserve::calcularMontoLiberacionGarantia(round(Doctrine_Core::getTable('variables')->find(3)->getValue(), 0, PHP_ROUND_HALF_UP), $from, $to);

            }

            $return["commission"]= $baseCommission;
            $return["price"] = $price;

        } catch (Exception $e) {
            $return["error"] = true;
            $return["errorMessage"] = $e->getMessage();

            error_log("[frontend] [reserves/getExtendPrice] ERROR: ".$e->getMessage());

            if ($request->getHost() == "www.arriendas.cl") {
                Utils::reportError($e->getMessage(), "reserves/getExtendPrice");
            }
        }
    
        $this->renderText(json_encode($return));

        return sfView::NONE;
    }

    public function executePay (sfWebRequest $request) {

        $userId = $this->getUser()->getAttribute('userid');

        // se exrae la comisión base.
        $baseCommissionValue = Doctrine_Core::getTable('variables')->find(1);

        // se exrae la comisión para el pago con TransBank.
        $transBankCommissionValue = Doctrine_Core::getTable('variables')->find(2);        

        if ($request->hasParameter('warranty','payment','car','from','to','baseCommission')) {
            $warranty = $request->getPostParameter("warranty", null);
            $payment  = $request->getPostParameter("payment", null); // Se saco la selección de khipu

            $carId  = $request->getPostParameter("car", null);
            $from   = $request->getPostParameter("from", null);
            $to     = $request->getPostParameter("to", null);

            $baseCommission  = $request->getPostParameter("baseCommission");
            $commissionTbank = $request->getPostParameter("commissionTbank");

            $isAirportDelivery = $request->getPostParameter("isAirportDelivery", null);      
        } else {            
            $warranty = $this->getUser()->getAttribute("warranty");
            $payment  = $this->getUser()->getAttribute("payment", null);

            $carId    = $this->getUser()->getAttribute("carId");
            $from     = $this->getUser()->getAttribute("from");
            $to       = $this->getUser()->getAttribute("to");

            $baseCommission  = $request->getPostParameter("baseCommission");
            $commissionTbank = $request->getPostParameter("commissionTbank");
        }
        $User = Doctrine_Core::getTable('User')->find($userId);
        $this->forward404If(!$User);
        
        // Chequeo judicial
        /*try {

            $UnverifiedMail = false;

            if(!$User->getChequeoJudicial()) {
                
                $client = new \Goutte\Client();

                $crawler = $client->request('GET', 'http://reformaprocesal.poderjudicial.cl/ConsultaCausasJsfWeb/page/panelConsultaCausas.jsf');

                $viewStateId = $crawler->filter('input[name="javax.faces.ViewState"]')->attr('value');

                if (strlen($viewStateId) > 0) {

                    $params = array(
                        'formConsultaCausas:idFormRut' => $User->rut,
                        'formConsultaCausas:idFormRutDv' => strtoupper($User->rut_dv),
                        'formConsultaCausas:idSelectedCodeTribunalRut' => "0",
                        'formConsultaCausas:buscar1.x' => "66",
                        'formConsultaCausas:buscar1.y' => "19",
                        'formConsultaCausas' => "formConsultaCausas",
                        'javax.faces.ViewState' => $viewStateId,
                    );
                    $crawler = $client->request('POST', 'http://reformaprocesal.poderjudicial.cl/ConsultaCausasJsfWeb/page/panelConsultaCausas.jsf', $params);

                    $nodeCount = count($crawler->filter('.extdt-cell-div'));

                    if ($nodeCount > 1) {
                        $User->setBlocked(true);
                        //$User->setBloqueado("Se ha bloqueado al usuario antes de efectuar la reserva, por poseer antecedentes judiciales.");
                    }

                    $User->setChequeoJudicial(true);
                    $User->save();
                } else {
                    $UnverifiedMail = true;
                }
            }
        } catch(Exception $e) {
            error_log("[reserves/pay] Verificacion judicial: ".$e->getMessage());
            $UnverifiedMail = true;
        }*/

        try {

            if (is_null($warranty) || is_null($carId) || is_null($from) || is_null($to) || is_null($baseCommission)) {
                throw new Exception("El User ".$userId." esta intentando pagar pero uno de los campos es nulo. Garantia: ".$warranty.", Car: ".$carId.", Desde: ".$from.", Hasta: ".$to.", comision base: ".$baseCommission.", Tbank comision: ".$commissionTbank, 1);
            }
            
            $datesError = Utils::validateDates($from, $to);
            if ($datesError) {
                throw new Exception($datesError, 2);
            }
            
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

            if ($User->moroso) {
                $warranty = true;
            }
            
            if ($warranty == 1) {
                $amountWarranty = sfConfig::get("app_monto_garantia");
                $Reserve->setLiberadoDeGarantia(false);
            } else {
                //$amountWarranty = Reserve::calcularMontoLiberacionGarantia(sfConfig::get("app_monto_garantia_por_dia"), $from, $to);
                $amountWarranty = Reserve::calcularMontoLiberacionGarantia(round(Doctrine_Core::getTable('variables')->find(3)->getValue(), 0, PHP_ROUND_HALF_UP), $from, $to);

                $Reserve->setLiberadoDeGarantia(true);
            }

            if ($payment == 2 && $amountWarranty == 180000) {
                throw new Exception("Excepción de webpay con garantía de $180.000", 1);
            }
            
            $Reserve->setPrice(CarTable::getPrice($from, $to, $Car->getPricePerHour(), $Car->getPricePerDay(), $Car->getPricePerWeek(), $Car->getPricePerMonth()));
            $Reserve->setMontoLiberacion($amountWarranty);
            $Reserve->setFechaReserva(date("Y-m-d H:i:s"));
            $Reserve->setConfirmed(false);
            $Reserve->setImpulsive(true);

            if($baseCommission == $Reserve->getPrice() *($baseCommissionValue->value / 100)){
                $baseCommission = $Reserve->getPrice() *($baseCommissionValue->value / 100);
            }else{
                throw new Exception("Excepción diferencias de comision base", 1);
            }
            
            if(($transBankCommission == $Reserve->getPrice() * ($transBankCommissionValue->value / 100)) ){
                $transBankCommission = $Reserve->getPrice() * ($transBankCommissionValue->value / 100);                
            } else {
                if($transBankCommission != 0){

                    throw new Exception("Excepción diferencias de comision TransBank", 1);
                }else{
                    $transBankCommission = 0;
                }
            }

            $Reserve->setTransbankCommission($transBankCommission);
            $Reserve->setBaseCommission($baseCommission);


            $Reserve->save();
            
            $Transaction = new Transaction();
            $Transaction->setCar($Car->getModel()->getName()." ".$Car->getModel()->getBrand()->getName());
            $Transaction->setPrice($Reserve->getPrice());
            $Transaction->setUser($Reserve->getUser());
            $Transaction->setDate(date("Y-m-d H:i:s"));

            $Transaction->setBaseCommission($baseCommission);
            $Transaction->setTransbankCommission($transBankCommission);
            
            $TransactionType = Doctrine_Core::getTable('TransactionType')->find(1);
            $Transaction->setTransactionType($TransactionType);
            $Transaction->setReserve($Reserve);
            $Transaction->setCompleted(false);
            $Transaction->setImpulsive(true);
            
            $Transaction->save();

            /*if($UnverifiedMail) {          

                $subject = "¡Se ha registrado un pago de un usuario sin verificacion judicial!";
                $body    = $this->getPartial('emails/paymentDoneUnverifiedUser', array('Transaction' => $Transaction));
                $from    = array("no-reply@arriendas.cl" => "Notificaciones Arriendas.cl");
                $to      = array("soporte@arriendas.cl");

                $message = Swift_Message::newInstance();
                $message->setSubject($subject);
                $message->setBody($body, 'text/html');
                $message->setFrom($from);
                $message->setTo($to);
                $message->setBcc(array("cristobal@arriendas.cl" => "Cristóbal Medina Moenne"));
                
                $this->getMailer()->send($message);
            }*/

        } catch (Exception $e) {
            error_log("[reserves/pay] ".$e->getMessage());
            if ($request->getHost() == "www.arriendas.cl" && $e->getCode() < 2) {
                Utils::reportError($e->getMessage(), "reserves/pay");
            }
            $this->redirect("homepage");
        }

        $this->getUser()->setAttribute("reserveId", $Reserve->getId());

        $this->getRequest()->setParameter("reserveId", $Reserve->getId());
        $this->getRequest()->setParameter("transactionId", $Transaction->getId());

        if ($Car->getIsAirportDelivery() && $isAirportDelivery) {
            $Reserve->setIsAirportDelivery(true);
            $Reserve->save();
            $this->getUser()->setAttribute("reserveId", $Reserve->getId());
            $this->getUser()->setAttribute("transactionId", $Transaction->getId());
            $this->redirect('reserve_airport');
        }

        if ($payment == 1) {
            $this->forward("khipu", "generatePayment");
        } elseif ($payment == 2) {
            $this->forward("webpay", "generatePayment");
        } else {
            $this->forward404("No se encontró el medio de pago");
        }
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

            $User = $Reserve->getUser();
            $CarUser = $Reserve->getCar()->getUser();

             // Notificaciones
            Notification::make($CarUser->id, 7, $Reserve->id); // Rechazar pago

            $message = Swift_Message::newInstance();
            $message->setSubject("La reserva ha sido rechazada");
            $message->setBody($this->getPartial('emails/reserveRejected', array('Reserve' => $Reserve)), 'text/html');
            $message->setFrom(array("soporte@arriendas.cl" => "Soporte Arriendas.cl"));
            $message->setTo(array($User->email => $User->firstname." ".$User->lastname));
            /*$message->setBcc(array("cristobal@arriendas.cl" => "Cristóbal Medina Moenne"));*/
            
            $this->getMailer()->send($message);

            $Reserve->save();
        } catch (Exception $e) {
            $return["error"] = true;
            $return["errorMessage"] = $e->getMessage();
            error_log("[frontend] [reserves/reject] ".$e->getMessage());
            if ($request->getHost() == "www.arriendas.cl") {
                Utils::reportError($e->getMessage(), "reserves/reject");
            }
        }

        $this->renderText(json_encode($return));

        return sfView::NONE;
    }

    public function executeSuccess (sfWebRequest $request) {
        $this->setLayout("newIndexLayout");
        $customer_in_session = $this->getUser()->getAttribute('userid');

        if ($customer_in_session) {
            $codigo_pago = null; // FIXME
            
            $conf = $this->getConfiguration();
            $conf = $conf["betterchoice"]["puntopagos"];
            $enviroment = $conf["enviroment"];
            $STATE_SUCCESSFULL = $conf[$enviroment]["status_success"];
            $STATE_ERROR = $conf[$enviroment]["status_error"];
            $order = Doctrine_Core::getTable("Transaction")->getPendingToShowByUser($customer_in_session);
            if($order){
                $last_order_id = $order->getId();

                $this->idReserva = $order->getReserveId();
                $reserve = Doctrine_Core::getTable('reserve')->findOneById($this->idReserva);
                
                $startDate0 = $reserve->getDate();
                $startDate = date("Y-m-d H:i:s", strtotime($startDate0));
                $endDate = date("Y-m-d H:i:s", strtotime($startDate0) + ($reserve->getDuration() * 60 * 60));

                $rangeDates = array($startDate, $endDate, $startDate, $endDate, $startDate, $endDate);
                $carid = $reserve->getCarId();

                $q = Doctrine_Query::create()
                        ->update('reserve r')
                        ->set('r.canceled', '?', 1)
                        ->set('r.visible_renter', '?', 0)
                        ->set('r.visible_owner', '?', 0)
                        ->set('r.cancel_reason', '?', 2)
                        ->where('r.car_id = ?', $carid)
                        ->andwhere('r.id <> ?', $reserve->getId())
                        ->andwhere('? BETWEEN r.date AND DATE_ADD(r.date, INTERVAL r.duration HOUR) OR ? BETWEEN r.date AND DATE_ADD(r.date, INTERVAL r.duration HOUR) OR r.date BETWEEN ? AND ? OR DATE_ADD(r.date, INTERVAL r.duration HOUR) BETWEEN ? AND ?', $rangeDates)
                        ->execute();


                $opcionLiberacion = $reserve->getLiberadoDeGarantia();
                if ($opcionLiberacion == 0)
                    $montoLiberacion = 0;
                else if ($opcionLiberacion == 1)
                    $montoLiberacion = $reserve->getMontoLiberacion();

                $finalPrice = $order->getPrice() + $montoLiberacion;
                if ($finalPrice > 0) {

                    $this->_log("Pago", "Exito", "Usuario: " . $customer_in_session . ". Order ID: " . $order->getId());
                    Doctrine_Core::getTable("Transaction")->successTransaction($last_order_id, $codigo_pago, $STATE_SUCCESSFULL, 1);
                    
                    $order->setShowSuccess(true);
                    $order->save();

                    $carId = $reserve->getCarId();
                    $carClass = Doctrine_Core::getTable('car')->findOneById($carId);
                    $propietarioId = $carClass->getUserId();
                    $propietarioClass = Doctrine_Core::getTable('user')->findOneById($propietarioId);                    
                    
                    $this->nameOwner      = $reserve->getNameOwner();
                    $this->emailOwner     = $reserve->getEmailOwner();
                    $this->lastnameOwner  = $reserve->getLastnameOwner();
                    $this->telephoneOwner = $reserve->getTelephoneOwner();
                    $this->tokenReserve   = $reserve->getToken();
                    $this->comunaOwner    = $propietarioClass->getCommune()->getName();
                    $this->addressOwner   = $propietarioClass->getAddress();

                    $this->durationFrom   = $reserve->getFechaInicio2();
                    $this->durationTo     = $reserve->getFechaTermino2();

                    $oT = $reserve->getTransaction();

                    $this->paymentMethodId = $oT->getPaymentMethodId();

                    if ($this->paymentMethodId == 2) {

                        $this->payType = "Crédito";

                        if ($oT->getWebpayType() == "VD") {
                            $this->payType = "Débito";
                            $this->sharesType = "Venta débito";
                        } elseif ($oT->getWebpayType() == "VN") {
                            $this->sharesType = "Sin cuotas";
                        } elseif ($oT->getWebpayType() == "VC") {
                            $this->sharesType = "Cuotas normales";
                        } else {
                            $this->sharesType = "Sin interés";
                        }

                        $this->sharesNumber = $oT->getWebpaySharesNumber();
                        $this->authorization = $oT->getWebpayAuthorization();
                        $this->lastDigits = $oT->getWebpayLastDigits();
                        $this->amount = $reserve->getPrice() + $reserve->getMontoLiberacion() - $oT->getDiscountamount();

                        $this->reserveId = $reserve->getId();
                        $this->transactionId = $oT->getId();
                    }

                } else {
                    echo "No hay compras hechas para ser pagadas (Error de monto invalido)";
                    $this->_log("Pago", "Error", "Usuario: " . $customer_in_session . ". Order ID: " . $order->getId());
                    Doctrine_Core::getTable("Transaction")->successTransaction($last_order_id, $token, $STATE_ERROR, 1);
                }
                $this->setTemplate('exito');
            } else{
                exit; 
            }
        } else {
            $this->redirect('@homepage');
        }       
    }

    // méodo en "main actions", (haciendo pruebas)
    public function executeUploadLicenseWarning (sfWebRequest $request) {
        $error="";
        $return = array("error" => false);
        $valid_formats = array("jpg", "png", "gif", "bmp", "jpeg");
        try {            

            $name = $_FILES[$request->getParameter('file')]['name'];
            $size = $_FILES[$request->getParameter('file')]['size'];
            $tmp  = $_FILES[$request->getParameter('file')]['tmp_name'];

            list($txt, $ext) = explode(".", $name);

            $ext = strtolower($ext);
            
            if (strlen($name) == 0) {
                throw new Exception("Por favor, selecciona una imagen", 2);   
            }
                
            if (!in_array($ext, $valid_formats)) {
                throw new Exception("Formato de la imagen no permitido", 2);
            }

            if ($size >= (5 * 1024 * 1024)) { // Image size max 1 MB
                throw new Exception("La imagen excede el máximo permitido (1 MB)", 2);
            }
                
            $userId = $this->getUser()->getAttribute("userid");
            
            $newImageName = time() . "-" . $userId . "." . $ext;

            $path = sfConfig::get("sf_web_dir") . '/images/licence/';
            $error = $error." Directorio e fotos: ".$path;
            $tmp = $_FILES[$request->getParameter('file')]['tmp_name'];

            if (!move_uploaded_file($tmp, $path . $newImageName)) {
                throw new Exception("El User ".$userId." tiene problemas para grabar la imagen de su licencia", 1);
            }

            $User = Doctrine_Core::getTable('User')->find($userId);
            $User->setDriverLicenseFile("/images/licence/".$newImageName);
            $User->save();

            $Reserves = Doctrine_Core::getTable("reserve")->findByUserId($userId);
            $error = $error." Cantidad de reservas: ".count($Reserves);
            foreach ($Reserves as $Reserve) {
                if($Reserve->getPayEmailPending()){
                    // Correo arrendatario
                    $subject = "La reserva ha sido pagada";
                    $body    = $this->getPartial('emails/paymentDoneRenter', array('Reserve' => $Reserve));
                    $from    = array("soporte@arriendas.cl" => "Soporte Arriendas.cl");
                    $to      = array($Reserve->getUser()->email => $Reserve->getUser()->firstname." ".$Reserve->getUser()->lastname);

                    $message = Swift_Message::newInstance()
                        ->setSubject($subject)
                        ->setBody($body, 'text/html')
                        ->setFrom($from)
                        ->setTo($to)
                        ->attach(Swift_Attachment::newInstance($contrato, 'contrato.pdf', 'application/pdf'))
                        ->attach(Swift_Attachment::newInstance($formulario, 'formulario.pdf', 'application/pdf'))
                        ->attach(Swift_Attachment::newInstance($reporte, 'reporte.pdf', 'application/pdf'))
                        ->attach(Swift_Attachment::newInstance($pagare, 'pagare.pdf', 'application/pdf'));

                    error_log("[reserves/uploadLicenseWarning] Enviando email al arrendatario");
                    $this->getMailer()->send($message);
                    $Reserve->setPayEmailPending(0);
                }
                $Reserve->save();
            }
            $error = $error." problemas enviando el mail";
     
        } catch (Exception $e) {
            $return["error"] = true;
            if ($e->getCode() < 2) {
                $return["errorMessage"] = "Problemas al subir la imagen. El problema ha sido notificado al equipo de desarrollo, por favor, intentalo nuevamente más tarde";
            } else {
                $return["errorMessage"] = $e->getMessage() . " " . $error;
            }
            error_log("[".date("Y-m-d H:i:s")."] [reserves/uploadLicenseWarning] ERROR: ".$e->getMessage());
            if ($request->getHost() == "www.arriendas.cl" && $e->getCode() < 2) {
                Utils::reportError($e->getMessage(), "reserves/uploadLicenseWarning");
            }
        }

        $this->renderText(json_encode($return));

        return sfView::NONE;
    }

    public function executeWarningUploadLicense(sfWebRequest $request) {
        $this->setLayout("newIndexLayout");
        $userId = $this->getUser()->getAttribute('userid');
        $User = Doctrine_Core::getTable("user")->find($userId);
        $countOrders = Doctrine_Core::getTable("Transaction")->countPendingToShowByUser($User->id);

        // si no existen transacciones pagadas ya vistas por el usuario 
        // o si el usuario ya tiene foto de licencia, entonces no puede ver la vista de advertencia
        if($countOrders == 0 || $User->driver_license_file) {
            $this->redirect('homepage');
        }

    }

    // FUNCIONES PRIVADAS
    
    private function calculateExtendedPrice($Reserve, $to) {

        $accumulatedPrice = 0;
        $auxReserve       = $Reserve;
        $iterate          = true;        
        
        while ($iterate) {

            $originalFrom     = $auxReserve->getDate();
            $originalDuration = $auxReserve->getDuration();
            $accumulatedPrice = $auxReserve->getPrice();
            
            if ($auxReserve->getIdPadre()) {
                $auxReserve = Doctrine_Core::getTable('Reserve')->find($auxReserve->getIdPadre());
            } else {
                $iterate = false;
            }
        }

        $Car = $Reserve->getCar();

        $extendedPrice = CarTable::getExtendedPrice($originalFrom,$originalDuration, $to, $Car->getPricePerHour(), $Car->getPricePerDay(), $Car->getPricePerWeek(), $Car->getPricePerMonth());
        
        return $extendedPrice;
        //return $extendedPrice - $accumulatedPrice;
    }
    /*
    private function calculateExtendedPrice($Reserve, $to) {

        $accumulatedPrice = 0;
        $auxReserve       = $Reserve;
        $iterate          = true;        
        
        while ($iterate) {

            $originalFrom     = $auxReserve->getDate();
            $accumulatedPrice = $auxReserve->getPrice();
            
            if ($auxReserve->getIdPadre()) {
                $auxReserve = Doctrine_Core::getTable('Reserve')->find($auxReserve->getIdPadre());
            } else {
                $iterate = false;
            }
        }

        $Car = $Reserve->getCar();

        $extendedPrice = CarTable::getExtendenPrice($originalFrom, $to, $Car->getPricePerHour(), $Car->getPricePerDay(), $Car->getPricePerWeek(), $Car->getPricePerMonth());

        return $extendedPrice;
    }
    */

    private function makeChange ($NewActiveReserve) {

        try {

            $ActiveReserve = Doctrine_Core::getTable('Reserve')->findActiveReserve($NewActiveReserve->id);

            $NewActiveReserve->setNumeroFactura($ActiveReserve->getNumeroFactura());
            $NewActiveReserve->save();

            $NewActiveTransaction = $NewActiveReserve->getTransaction();
            $NewActiveTransaction->setNumeroFactura($ActiveReserve->getTransaction()->getNumeroFactura());
            $NewActiveTransaction->setCompleted(true);
            $NewActiveTransaction->save();            

            $Rating = $NewActiveReserve->getRating();
            $Rating->setIdOwner($NewActiveReserve->getCar()->getUser()->id);
            $Rating->save();

            $ActiveReserve->setNumeroFactura(0);
            $ActiveReserve->save();

            $ActiveTransaction = $ActiveReserve->getTransaction();
            $ActiveTransaction->setNumeroFactura(0);
            $ActiveTransaction->setCompleted(false);
            $ActiveTransaction->save();

            $Renter = $NewActiveReserve->getUser();
            $Owner  = $NewActiveReserve->getCar()->getUser();
            
            $functions  = new Functions;
            $formulario = $functions->generarFormulario(NULL, $NewActiveReserve->token);
            $reporte    = $functions->generarReporte($NewActiveReserve->getCar()->id);
            $contrato   = $functions->generarContrato($NewActiveReserve->token);
            $pagare     = $functions->generarPagare($NewActiveReserve->token);

            // CORREO DUEÑO
            $subject = "¡Has ganado la oportunidad!";
            $body    = $this->getPartial('emails/opportunityWonOwner', array('Reserve' => $NewActiveReserve));
            $from    = array("soporte@arriendas.cl" => "Soporte Arriendas.cl");
            $to      = array($Owner->email => $Owner->firstname." ".$Owner->lastname);

            $message = Swift_Message::newInstance();
            $message->setSubject($subject);
            $message->setBody($body, 'text/html');
            $message->setFrom($from);
            $message->setTo($to);
            // $message->setBcc(array("cristobal@arriendas.cl" => "Cristóbal Medina Moenne"));

            $message->attach(Swift_Attachment::newInstance($contrato, 'contrato.pdf', 'application/pdf'));
            $message->attach(Swift_Attachment::newInstance($formulario, 'formulario.pdf', 'application/pdf'));
            $message->attach(Swift_Attachment::newInstance($reporte, 'reporte.pdf', 'application/pdf'));
            $message->attach(Swift_Attachment::newInstance($pagare, 'pagare.pdf', 'application/pdf'));
            
            if (!is_null($Renter->getDriverLicenseFile())) {
                $filepath = $Renter->getDriverLicenseFile();
                if (is_file($filepath)) {
                    $message->attach(Swift_Attachment::fromPath($Renter->getDriverLicenseFile())->setFilename("LicenciaArrendatario-".$Renter->getLicenceFileName()));
                }
            }
            
            $this->getMailer()->send($message);

            // CORREO ARRENDATARIO
            $subject = "Has cambiado el auto de tu reserva";
            $body    = $this->getPartial('emails/opportunityWonRenter', array('Reserve' => $NewActiveReserve));
            $from    = array("soporte@arriendas.cl" => "Soporte Arriendas.cl");
            $to      = array($Renter->email => $Renter->firstname." ".$Renter->lastname);

            $message = Swift_Message::newInstance();
            $message->setSubject($subject);
            $message->setBody($body, 'text/html');
            $message->setFrom($from);
            $message->setTo($to);
            // $message->setBcc(array("cristobal@arriendas.cl" => "Cristóbal Medina Moenne"));

            $message->attach(Swift_Attachment::newInstance($contrato, 'contrato.pdf', 'application/pdf'));
            $message->attach(Swift_Attachment::newInstance($formulario, 'formulario.pdf', 'application/pdf'));
            $message->attach(Swift_Attachment::newInstance($reporte, 'reporte.pdf', 'application/pdf'));
            $message->attach(Swift_Attachment::newInstance($pagare, 'pagare.pdf', 'application/pdf'));
                
            $this->getMailer()->send($message);

            // CORREO SOPORTE
            if ($NewActiveReserve->reserva_original) {
                $originalReserveId = $NewActiveReserve->reserva_original;
            } else {
                $originalReserveId = $NewActiveReserve->id;
            }

            $subject = "Ha habido un cambio de auto en la Reserva ".$originalReserveId;
            $body    = $this->getPartial('emails/opportunityWonSupport', array('Reserve' => $NewActiveReserve));
            $from    = array("no-reply@arriendas.cl" => "Notificaciones Arriendas.cl");
            $to      = array("soporte@arriendas.cl" => "Soporte Arriendas.cl");

            $message = Swift_Message::newInstance();
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

            $this->getMailer()->send($message);
        } catch (Exception $e) {
            error_log("[reserves/makeChange] User ".$userId." está intentado realizar un cambio a Car ".$NewActiveReserve->getCar()->id." pero este no se ha podido concretar. ERROR: ".$e->getMessage());
            if ($_SERVER['SERVER_NAME'] == "www.arriendas.cl") {
                Utils::reportError($e->getMessage(), "reserves/makeChange");
            }
            return false;
        }

        return true;
    }

    protected function getConfiguration() {
        return sfYaml::load(dirname(dirname(__FILE__)) . "/config/puntopagos.yml");
    }

    protected function _log($step, $status, $msg) {

        $logPath = sfConfig::get('sf_log_dir') . '/puntopagos.log';
        $custom_logger = new sfFileLogger(new sfEventDispatcher(), array('file' => $logPath));
        $custom_logger->info($step . " - " . $status . ". " . $msg);
    }
}
