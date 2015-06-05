<?php

require_once sfConfig::get('sf_lib_dir') . '/vendor/webpay/WebpayService.php';

class webpayActions extends sfActions {

    public function executeGeneratePayment (sfWebRequest $request) {

        $this->setLayout("newIndexLayout");

        try {

            $reserveId     = $request->getParameter("reserveId");
            $transactionId = $request->getParameter("transactionId");
            $GPSTransactionId = $request->getParameter("gps_transactionId", null);

           // proceso que verifica si la transaccion es de una reserva o el pago de un gps
            if(is_null($GPSTransactionId)){

                $Reserve = Doctrine_Core::getTable('Reserve')->find($reserveId);
                $Transaction = Doctrine_Core::getTable('Transaction')->find($transactionId);

                $Transaction->setPaymentMethodId(2);
                $Transaction->save();

                $webpaySettings = $this->getSettings();

                $wsInitTransactionInput = new wsInitTransactionInput();
                $wsTransactionDetail = new wsTransactionDetail();

                $wsInitTransactionInput->wSTransactionType = "TR_NORMAL_WS";
                $wsInitTransactionInput->returnURL = $this->generateUrl("webpay_return", array(), true);
                $wsInitTransactionInput->finalURL = $this->generateUrl("webpay_final", array(), true);

                $wsTransactionDetail->commerceCode = $webpaySettings["commerceCode"];
                $wsTransactionDetail->buyOrder = $Transaction->id;
                $wsTransactionDetail->amount = $Reserve->getPrice() + $Reserve->getMontoLiberacion() - $Transaction->getDiscountamount();

            } else {
                $GPSTransaction = Doctrine_Core::getTable("GPSTransaction")->find($GPSTransactionId);

                $webpaySettings = $this->getSettings();

                $wsInitTransactionInput = new wsInitTransactionInput();
                $wsTransactionDetail = new wsTransactionDetail();
                $wsInitTransactionInput->wSTransactionType = "TR_NORMAL_WS";
                $wsInitTransactionInput->returnURL = $this->generateUrl("webpay_gps_return", array(), true);
                $wsInitTransactionInput->finalURL = $this->generateUrl("webpay_gps_final", array(), true);
                
                $wsTransactionDetail->commerceCode = $webpaySettings["commerceCode"];
                $wsTransactionDetail->buyOrder = "G".$GPSTransaction->id;
                $wsTransactionDetail->amount = $GPSTransaction->getAmount();

            }

            $wsInitTransactionInput->transactionDetails = $wsTransactionDetail;
            error_log(print_r(json_encode($wsInitTransactionInput), true));
            $webpayService = new WebpayService($webpaySettings["url"]);

            $this->checkOutUrl = "#";

            $initTransactionResponse = $webpayService->initTransaction(
                    array("wsInitTransactionInput" => $wsInitTransactionInput)
            );

            $xmlResponse = $webpayService->soapClient->__getLastResponse();

            $SERVER_CERT_PATH = sfConfig::get('sf_lib_dir') . "/vendor/webpay/certificates/certificate_server.crt";
            $soapValidation = new SoapValidation($xmlResponse, $SERVER_CERT_PATH);
            $validationResult = $soapValidation->getValidationResult();

            if (!$validationResult) {
                $this->redirect("webpay_reject");
            }

            error_log(print_r(json_encode($initTransactionResponse), true));

            $wsInitTransactionOutput = $initTransactionResponse->return;

            $this->checkOutUrl = $wsInitTransactionOutput->url;
            $this->checkOutToken = $wsInitTransactionOutput->token;

        } catch (Execption $e) {
            error_log("[webpay/generatePayment] ".$e->getMessage());
            if ($request->getHost() == "www.arriendas.cl") {
                Utils::reportError($e->getMessage(), "webpay/generatePayment");
            }
        }
    }

    public function executeConfirmPayment(sfWebRequest $request) {

        error_log("ConfirmPayment");
        $this->carMarcaModel = urldecode($request->getParameter("carMarcaModel"));
        $this->duracionReserva = urldecode($request->getParameter("duracionReserva"));
        $montoTotalPagoPorDia = $request->getParameter("duracionReservaPagoPorDia");

        $customer_in_session = $this->getUser()->getAttribute('userid');

        $this->deposito = $request->getParameter("deposito");
        $this->montoDeposito = 0;
        if ($this->deposito == "depositoGarantia") {
            $this->montoDeposito = 180000;
            //$this->enviarCorreoTransferenciaBancaria();
        } else if ($this->deposito == "pagoPorDia") {
            $this->montoDeposito = $montoTotalPagoPorDia;
        }

        if ($customer_in_session) {
            
            $transacionId = $request->getParameter('id');
            
            if (is_null($transacionId)) {
                $this->getUser()->setFlash('error', 'No se ha recibido un codigo de orden para la creacion en Webpay');
                $this->error = "";
                $this->redirect("bcpuntopagos/index");
            } else {

                /*$order = Doctrine_Core::getTable("Transaction")->getTransaction($transacionId);*/
                /*$idReserve = $order->getReserveId();*/
                $this->ppId = $transacionId;
                /*$reserve = Doctrine_Core::getTable('reserve')->findOneById($idReserve);*/

                $Transaction = Doctrine_Core::getTable("Transaction")->find($transacionId);
                $Reserve     = Doctrine_Core::getTable('Reserve')->find($Transaction->getReserveId());

                /*$montoLiberacion = $reserve->getMontoLiberacion();

                $this->hasDiscountFB = $order->getDiscountfb();
                $this->priceMultiply = 1 - (0.05 * $order->getDiscountfb());
                $this->ppMonto = $order->getPrice(); //reemplazar por metodo getMonto
                $this->ppIdReserva = $idReserve;

                $finalPrice = $order->getPrice() - $order->getDiscountamount() + $montoLiberacion;
                $finalPrice = number_format($finalPrice, 0, '.', '');*/
                $this->finalPrice = $finalPrice;

                $montoLiberacion = 0;
                if ($Reserve->getLiberadoDeGarantia()) {
                    $montoLiberacion = $Reserve->getMontoLiberacion();
                }

                $finalPrice = $Transaction->getPrice() + $montoLiberacion;

                if ($finalPrice > 0) {

                    /* la transaccion es valida, inicializo paypal */
                    /* paypal init */
                    $webpaySettings = $this->getSettings();

                    $wsInitTransactionInput = new wsInitTransactionInput();
                    $wsTransactionDetail = new wsTransactionDetail();

                    /* inicio WSWEBPAY */
                    /* Variables de tipo string */
                    $wsInitTransactionInput->wSTransactionType = "TR_NORMAL_WS";
                    $wsInitTransactionInput->buyOrder = $transacionId;

                    $wsInitTransactionInput->returnURL = $this->generateUrl("webpay_return", array(), true);
                    $wsInitTransactionInput->finalURL = $this->generateUrl("webpay_final", array(), true);

                    $wsTransactionDetail->commerceCode = $webpaySettings["commerceCode"];
                    $wsTransactionDetail->buyOrder = $transacionId;
                    $wsTransactionDetail->amount = $finalPrice;

                    $wsInitTransactionInput->transactionDetails = $wsTransactionDetail;
                    $webpayService = new WebpayService($webpaySettings["url"]);

                    $this->checkOutUrl = "#";

                    try {
                        $initTransactionResponse = $webpayService->initTransaction(
                                array("wsInitTransactionInput" => $wsInitTransactionInput)
                        );
                        $xmlResponse = $webpayService->soapClient->__getLastResponse();
                        $SERVER_CERT_PATH = sfConfig::get('sf_lib_dir') . "/vendor/webpay/certificates/certificate_server.crt";
                        $soapValidation = new SoapValidation($xmlResponse, $SERVER_CERT_PATH);
                        $validationResult = $soapValidation->getValidationResult();
                        if ($validationResult) {
                            /* Invocar sólo sí $validationResult es TRUE */
                            $wsInitTransactionOutput = $initTransactionResponse->return;
                            $this->checkOutUrl = $wsInitTransactionOutput->url;
                            $this->checkOutToken = $wsInitTransactionOutput->token;
                        } else {
                            $msg = " | API Error Message : " . "";
                            $this->_log("Pago", "ApiError", "Usuario: " . $customer_in_session . ". Order ID: " . $order->getId() . $msg);
                            $this->redirect("webpay_failure");
                        }
                    } catch (Exception $ex) {
                        $msg = " | Exception : " . $ex->getMessage();
                        $this->_log("Exception", "Error", "Usuario: " . $customer_in_session . ". Order ID: " . $order->getId() . $msg);
                        $this->redirect("webpay_failure");
                    }
                }
            }
        } else {
            $this->redirect('@homepage');
        }
    }

    public function executeProcessPayment(sfWebRequest $request) {
        
        $this->setLayout("newIndexLayout");

        $customer_in_session = $this->getUser()->getAttribute('userid');
        if ($customer_in_session) {

            $token = $request->getPostParameter("token_ws");
            $webpaySettings = $this->getSettings();

            /* execute payment */
            $getTransactionResult = new getTransactionResult();
            $getTransactionResult->tokenInput = $token;
            error_log(print_r(json_encode($getTransactionResult), true));

            $webpayService = new WebpayService($webpaySettings["url"]);
            $getTransactionResultResponse = $webpayService->getTransactionResult($getTransactionResult);
            $transactionResultOutput = $getTransactionResultResponse->return;

            error_log(print_r(json_encode($getTransactionResultResponse), true));

            $xmlResponse = $webpayService->soapClient->__getLastResponse();
            
            $SERVER_CERT_PATH = sfConfig::get('sf_lib_dir') . "/vendor/webpay/certificates/certificate_server.crt";
            $soapValidation = new SoapValidation($xmlResponse, $SERVER_CERT_PATH);
            $validationResult = $soapValidation->getValidationResult();

            if (!$validationResult) {
                $this->redirect("webpay_reject");                
            }
            
            /*
             * Resultado de la autenticación para comercios Webpay Plus
             * TSY: Autenticación exitosa
             * TSN: autenticación fallida.
             * TO: Tiempo máximo excedido para autenticación.
             * ABO: Autenticación abortada por tarjetahabiente.
             * U3: Error interno en la autenticación.
             * Puede ser vacío si la transacción no se autentico.
             */

            if ($transactionResultOutput->VCI == "TSY") {

                $transactionId = $transactionResultOutput->buyOrder;
                $Transaction = Doctrine_Core::getTable("Transaction")->find($transactionId);

                if ($Transaction->getCompleted()) {
                    $this->redirect("webpay_failure");
                }

                /* informo a webpay que se recibio la notificación de transaccion */
                $acknowledgeTransaction = new acknowledgeTransaction();
                $acknowledgeTransaction->tokenInput = $token;
                error_log(print_r(json_encode($acknowledgeTransaction), true));
                $acknowledgeTransactionResponse = $webpayService->acknowledgeTransaction($acknowledgeTransaction);
                error_log(print_r(json_encode($acknowledgeTransactionResponse), true));
                
                $xmlResponse = $webpayService->soapClient->__getLastResponse();
                $SERVER_CERT_PATH = sfConfig::get('sf_lib_dir') . "/vendor/webpay/certificates/certifacate_server.crt";
                $soapValidation = new SoapValidation($xmlResponse, $SERVER_CERT_PATH);
                $validationResult = $soapValidation->getValidationResult();
                error_log("VALIDATION");
                error_log(print_r($validationResult, true));
                
                $transactionId = $transactionResultOutput->buyOrder;                
                $wsTransactionDetailOutput = $transactionResultOutput->detailOutput;

                /*
                 * Resultados posibles de la transaccion:
                 * 0 Transacción aprobada.
                 * -1 Rechazo de transacción.
                 * -2 Transacción debe reintentarse.
                 * -3 Error en transacción.
                 * -4 Rechazo de transacción.
                 * -5 Rechazo por error de tasa.
                 * -6 Excede cupo máximo mensual.
                 * -7 Excede límite diario por transacción.
                 * -8 Rubro no autorizado.
                 */

                switch ($wsTransactionDetailOutput->responseCode) {
                    case "0":
                        $Transaction = Doctrine_Core::getTable("Transaction")->find($transactionId);
                        $Reserve     = Doctrine_Core::getTable('Reserve')->find($Transaction->getReserveId());
                        $this->idReserva = $Reserve->getId();

                        $montoLiberacion = 0;
                        if ($Reserve->getLiberadoDeGarantia()) {
                            $montoLiberacion = $Reserve->getMontoLiberacion();
                        }

                        $finalPrice = $Transaction->getPrice() + $montoLiberacion;

                        if ($finalPrice > 0) {
                            if (!$Transaction->getCompleted()) {
                        
                                error_log("[webpay/processPayment] Nuevo pago recibido");

                                $Renter = $Reserve->getUser();
                                $Owner  = $Reserve->getCar()->getUser();

                                $Functions = new Functions;
                                $Functions->generarNroFactura($Reserve, $Transaction);

                                // Notificaciones
                                Notification::make($Renter->id, 3, $Reserve->id); // pago
                                
                                $Transaction->setCompleted(true);
                                $Transaction->save();

                                $Reserve->setFechaPago(date("Y-m-d H:i:s"));
                                $Reserve->save();

                                $formulario = $Functions->generarFormulario(NULL, $Reserve->token);
                                $reporte    = $Functions->generarReporte($Reserve->getCar()->id);
                                $contrato   = $Functions->generarContrato($Reserve->token);
                                $pagare     = $Functions->generarPagare($Reserve->token);

                                // Correo dueño
                                $subject = "¡Has recibido un pago! Apruébalo ahora";
                                $body    = $this->getPartial('emails/paymentDoneOwner', array('Reserve' => $Reserve));
                                $from    = array("soporte@arriendas.cl" => "Soporte Arriendas.cl");
                                $to      = array($Owner->email => $Owner->firstname." ".$Owner->lastname);

                                $message = Swift_Message::newInstance()
                                    ->setSubject($subject)
                                    ->setBody($body, 'text/html')
                                    ->setFrom($from)
                                    ->setTo($to)
                                    ->attach(Swift_Attachment::newInstance($contrato, 'contrato.pdf', 'application/pdf'))
                                    ->attach(Swift_Attachment::newInstance($formulario, 'formulario.pdf', 'application/pdf'))
                                    ->attach(Swift_Attachment::newInstance($reporte, 'reporte.pdf', 'application/pdf'));
                                
                                if (!is_null($Renter->getDriverLicenseFile())) {
                                    $filepath = $Renter->getDriverLicenseFile();
                                    if (is_file($filepath)) {
                                        $message->attach(Swift_Attachment::fromPath($Renter->getDriverLicenseFile())->setFilename("LicenciaArrendatario-".$Renter->getLicenceFileName()));
                                    }
                                }
                                
                                error_log("[webpay/processPayment] Enviando email al propietario");
                                $this->getMailer()->send($message);

                                if($Renter->getDriverLicenseFile()){ // envía un mail al usuario dependiendo si tiene foto de licencia o no
                                    // Correo arrendatario
                                    $subject = "La reserva ha sido pagada";
                                    $body    = $this->getPartial('emails/paymentDoneRenter', array('Reserve' => $Reserve));
                                    $from    = array("soporte@arriendas.cl" => "Soporte Arriendas.cl");
                                    $to      = array($Renter->email => $Renter->firstname." ".$Renter->lastname);

                                    $message = Swift_Message::newInstance()
                                        ->setSubject($subject)
                                        ->setBody($body, 'text/html')
                                        ->setFrom($from)
                                        ->setTo($to)
                                        ->attach(Swift_Attachment::newInstance($contrato, 'contrato.pdf', 'application/pdf'))
                                        ->attach(Swift_Attachment::newInstance($formulario, 'formulario.pdf', 'application/pdf'))
                                        ->attach(Swift_Attachment::newInstance($reporte, 'reporte.pdf', 'application/pdf'))
                                        ->attach(Swift_Attachment::newInstance($pagare, 'pagare.pdf', 'application/pdf'));

                                    error_log("[webpay/processPayment] Enviando email al arrendatario");
                                } else {

                                    $subject = "La reserva ha sido pagada";
                                    $body    = $this->getPartial('emails/paymentDoneRenterWithoutDriverLicense', array('Renter' => $Renter));
                                    $from    = array("soporte@arriendas.cl" => "Soporte Arriendas.cl");
                                    $to      = array($Renter->email => $Renter->firstname." ".$Renter->lastname);

                                    $message = Swift_Message::newInstance()
                                        ->setSubject($subject)
                                        ->setBody($body, 'text/html')
                                        ->setFrom($from)
                                        ->setTo($to);

                                    error_log("[webpay/processPayment] Enviando email al arrendatario sin licencia");

                                }   
                                $this->getMailer()->send($message);

                                // Correo soporte
                                $subject = "Nuevo pago. Reserva: ".$Reserve->id;
                                $body    = $this->getPartial('emails/paymentDoneSupport', array('Reserve' => $Reserve));
                                $from    = array("no-reply@arriendas.cl" => "Notificaciones Arriendas.cl");
                                $to      = array("soporte@arriendas.cl" => "Soporte Arriendas.cl");

                                $message = Swift_Message::newInstance()
                                    ->setSubject($subject)
                                    ->setBody($body, 'text/html')
                                    ->setFrom($from)
                                    ->setTo($to)
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
                                
                                error_log("[webpay/processPayment] Enviando email a soporte");
                                $this->getMailer()->send($message);

                                // Crea la fila calificaciones habilitada para la fecha de término de reserva + 2 horas (solo si no es una extension de otra reserva)
                                if (!$Reserve->getIdPadre()) {

                                    $Rating = new Rating();
                                    $Rating->setFechaHabilitadaDesde($Reserve->getFechaHabilitacionRating());
                                    $Rating->setIdOwner($Owner->id);
                                    $Rating->setIdRenter($Renter->id);
                                    $Rating->save();

                                    // Actualiza rating_id en la tabla Reserve
                                    $ratingId = $Rating->id;
                                    $Reserve->setRatingId($ratingId);
                                    $Reserve->save();
                                }

                                // Almacena reserveId en la tabla mail calificaciones
                                $Reserve->encolarMailCalificaciones();

                                error_log("[webpay/processPayment] ---------- HABEMUS PAGO --------");

                                $OpportunityQueue = Doctrine_Core::getTable('OpportunityQueue')->findOneByReserveId($Transaction->getReserveId());
                                if (!$OpportunityQueue) {
                                    $OpportunityQueue = new OpportunityQueue;
                                    $OpportunityQueue->setReserveId($Transaction->getReserveId());
                                    $OpportunityQueue->setPaidAt($Reserve->getFechaPago());
                                    $OpportunityQueue->save();
                                }

                                if(!$Reserve->getUser()->getDriverLicenseFile()){

                                    $subject = "Pago de usuario sin licencia de conducir";
                                    $body    = $this->getPartial('emails/paymentDoneUnverifiedUser', array('Transaction' => $Transaction));
                                    $from    = array("no-reply@arriendas.cl" => "Notificaciones Arriendas.cl");
                                    $to      = array("soporte@arriendas.cl" => "Soporte Arriendas.cl");

                                    $message = Swift_Message::newInstance()
                                        ->setSubject($subject)
                                        ->setBody($body, 'text/html')
                                        ->setFrom($from)
                                        ->setTo($to)
                                        ->setBcc(array("cristobal@arriendas.cl" => "Cristóbal Medina Moenne"));
                                    
                                    $this->getMailer()->send($message);
                                }

                                $this->redirect("reserves");
                            }
                        }
                        break;
                    case "-1":
                        /* transaccion rechazada por el medio de pago */
                        $msg = "Transaccion #:" . $transactionId . " fue rechazada.";
                        $this->_log("Pago", "Transaction Rejected", $msg);
                        $this->redirect("webpay_reject");
                        break;
                    case "-2":
                        /* transaccion rechazada por el medio de pago */
                        $msg = "Transaccion #:" . $transactionId . " debe reintentarse.";
                        $this->_log("Pago", "Transaction Rejected", $msg);
                        $this->redirect("webpay_reject");
                        break;
                    default:
                        /* se produjo un error en el medio de pago */
                        $msg = "Transaccion #: " . $transactionId . "  Error code:" . $wsTransactionDetailOutput->responseCode;
                        $this->_log("Pago", "ApiError", $msg);
                        $this->redirect("webpay_failure");
                        break;
                };
            } else {
                /* getExpressCheckout Failure */
                $msg = "API Error Message : " . $validationResult;
                $this->_log("Pago", "ApiError", $msg);
                $this->redirect("webpay_failure");
            }
        } else {
            /* not session */
            $this->redirect('@homepage');
        }
    }

    public function executeProcessPaymentGPS(sfWebRequest $request) {
        
        $this->setLayout("newIndexLayout");
        $customer_in_session = $this->getUser()->getAttribute('userid');
        if ($customer_in_session) {

            $token = $request->getPostParameter("token_ws");
            $webpaySettings = $this->getSettings();

            /* execute payment */
            $getTransactionResult = new getTransactionResult();
            $getTransactionResult->tokenInput = $token;
            error_log(print_r(json_encode($getTransactionResult), true));

            $webpayService = new WebpayService($webpaySettings["url"]);
            $getTransactionResultResponse = $webpayService->getTransactionResult($getTransactionResult);
            $transactionResultOutput = $getTransactionResultResponse->return;

            error_log(print_r(json_encode($getTransactionResultResponse), true));
            
            /*
             * Resultado de la autenticación para comercios Webpay Plus
             * TSY: Autenticación exitosa
             * TSN: autenticación fallida.
             * TO: Tiempo máximo excedido para autenticación.
             * ABO: Autenticación abortada por tarjetahabiente.
             * U3: Error interno en la autenticación.
             * Puede ser vacío si la transacción no se autentico.
             */

            if ($transactionResultOutput->VCI == "TSY") {

                /* informo a webpay que se recibio la notificación de transaccion */
                $acknowledgeTransaction = new acknowledgeTransaction();
                $acknowledgeTransaction->tokenInput = $token;
                error_log(print_r(json_encode($acknowledgeTransaction), true));
                $acknowledgeTransactionResponse = $webpayService->acknowledgeTransaction($acknowledgeTransaction);
                error_log(print_r(json_encode($acknowledgeTransactionResponse), true));
                
                $xmlResponse = $webpayService->soapClient->__getLastResponse();
                $SERVER_CERT_PATH = sfConfig::get('sf_lib_dir') . "/vendor/webpay/certificates/certifacate_server.crt";
                $soapValidation = new SoapValidation($xmlResponse, $SERVER_CERT_PATH);
                $validationResult = $soapValidation->getValidationResult();
                
                $GPSTransactionId = ltrim($transactionResultOutput->buyOrder, "G");                
                $wsTransactionDetailOutput = $transactionResultOutput->detailOutput;
                /*
                 * Resultados posibles de la transaccion:
                 * 0 Transacción aprobada.
                 * -1 Rechazo de transacción.
                 * -2 Transacción debe reintentarse.
                 * -3 Error en transacción.
                 * -4 Rechazo de transacción.
                 * -5 Rechazo por error de tasa.
                 * -6 Excede cupo máximo mensual.
                 * -7 Excede límite diario por transacción.
                 * -8 Rubro no autorizado.
                 */

                switch ($wsTransactionDetailOutput->responseCode) {
                    case "0":
                        $GPSTransaction = Doctrine_Core::getTable("GPSTransaction")->find($GPSTransactionId);
                        
                        if (!$GPSTransaction->getCompleted()) {
                    
                            error_log("[webpay/processPaymentGPS] Nuevo pago recibido");

                            // GENERAR FACTURA? *****************************************

                            //$Functions = new Functions;
                            //$Functions->generarNroFactura($Reserve, $Transaction);

                            // Notificaciones *******************************************

                            //Notification::make($Renter->id, 3, $Reserve->id); // pago
                            $User = Doctrine_Core::getTable("user")->find($customer_in_session);
                            $GPSTransaction->setCompleted(true);
                            $GPSTransaction->setPaidOn(date("Y-m-d H:i:s"));
                            $GPSTransaction->save();

                            $Car = Doctrine_Core::getTable("car")->find($GPSTransaction->car_id);
                            $Car->setHasGps(1);
                            $Car->save();

                            //$formulario = $Functions->generarFormulario(NULL, $Reserve->token);
                            //$reporte    = $Functions->generarReporte($Reserve->getCar()->id);
                            //$contrato   = $Functions->generarContrato($Reserve->token);
                            //$pagare     = $Functions->generarPagare($Reserve->token);

                            
                            error_log("[webpay/processPaymentGPS] Enviando email al usuario");

                            
                            $subject = "Has Comprado un GPS!";
                            $body    = $this->getPartial('emails/paymentDoneGPS', array('GPSTransaction' => $GPSTransaction, 'User' => $User));
                            $from    = array("soporte@arriendas.cl" => "Soporte Arriendas.cl");
                            $to      = array($User->email => $User->firstname." ".$User->lastname);

                            $message = Swift_Message::newInstance()
                                ->setSubject($subject)
                                ->setBody($body, 'text/html')
                                ->setFrom($from)
                                ->setTo($to);

                            
                            $this->getMailer()->send($message);
                            error_log("[webpay/processPaymentGPS] mensaje enviado");

                            // Correo soporte
                            $subject = "Nuevo pago. GPS: ".$GPSTransaction->id;
                            $body    = $this->getPartial('emails/paymentDoneGPS', array('GPSTransaction' => $GPSTransaction, 'User' => $User));
                            $from    = array("no-reply@arriendas.cl" => "Notificaciones Arriendas.cl");
                            $to      = array("soporte@arriendas.cl" => "Soporte Arriendas.cl");

                            $message = Swift_Message::newInstance()
                                ->setSubject($subject)
                                ->setBody($body, 'text/html')
                                ->setFrom($from)
                                ->setTo($to);
                            
                            error_log("[webpay/processPaymentGPS] Enviando email a soporte");
                            $this->getMailer()->send($message);
                            error_log("[webpay/processPaymentGPS] mensaje enviado");

                            // Crea la fila calificaciones habilitada para la fecha de término de reserva + 2 horas (solo si no es una extension de otra reserva)

                            error_log("[webpay/processPaymentGPS] ---------- HABEMUS PAGO GPS--------");

                            
                            $this->redirect("car_edit", array("id" => $GPSTransaction->car_id));
                        }
                        /*//cancel other reserves not paid for the same rental dates
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
                        if ($opcionLiberacion == 0) {
                            $montoLiberacion = 0;
                        } else if ($opcionLiberacion == 1) {
                            $montoLiberacion = $reserve->getMontoLiberacion();
                        }
                        $finalPrice = $order->getPrice() + $montoLiberacion;
                        if ($finalPrice > 0) {

                            $this->_log("Pago", "Exito", "Usuario: " . $customer_in_session . ". Order ID: " . $order->getId());
                            Doctrine_Core::getTable("Transaction")->successTransaction($orderId, $token, $paypalSettings["status_success"], 1);

                            $idReserve = $order->getReserveId();
                            $reserve = Doctrine_Core::getTable('reserve')->findOneById($idReserve);

                            $tokenReserve = $reserve->getToken();
                            $this->tokenReserve = $reserve->getToken();
                            $nameRenter = $reserve->getNameRenter();
                            $this->nameOwner = $reserve->getNameOwner();
                            $emailRenter = $reserve->getEmailRenter();
                            $this->emailOwner = $reserve->getEmailOwner();
                            $nameOwner = $reserve->getNameOwner();
                            $emailOwner = $reserve->getEmailOwner();
                            $lastnameRenter = $reserve->getLastnameRenter();
                            $this->lastnameOwner = $reserve->getLastnameOwner();
                            $lastnameOwner = $reserve->getLastnameOwner();
                            $telephoneRenter = $reserve->getTelephoneRenter();
                            $this->telephoneOwner = $reserve->getTelephoneOwner();
                            $telephoneOwner = $reserve->getTelephoneOwner();
                            $addressCar = $reserve->getAddressCar();
                            $idCar = $reserve->getCarId();

                            //verifica que la reserva no esté completa
                            if (!$order->getCompleted()) {
                                
                                $functions = new Functions;
                                $functions->generarNroFactura($reserve, $order);
                                
                                //actualiza el estado completed
                                $order->setCompleted(true);
                                $order->save();

                                //envío de mail
                                require sfConfig::get('sf_app_lib_dir') . "/mail/mail.php";


                                //pedidos de reserva pagado (propietario)
                                $mail = new Email();
                                $mailer = $mail->getMailer();

                                $message = $mail->getMessage();
                                $message->setSubject('El arrendatario ha pagado la reserva!');
                                $body = "<p>Hola $nameOwner:</p><p>El arrendatario ha pagado la reserva!</p><p>Recuerda que debes llenar el FORMULARIO DE ENTREGA Y DEVOLUCIÓN del vehículo.</p><p>Puedes llenar el formulario <a href='http://www.arriendas.cl/profile/formularioEntrega/idReserve/$idReserve'>desde tu celular</a>.</p><p>No des inicio al arriendo si el auto tiene más daños que los declarados.</p><p>Datos del propietario:<br><br>Nombre: $nameRenter $lastnameRenter<br>Teléfono: $telephoneRenter<br>Correo: $emailRenter</p><p>Los datos del arriendo y la versión escrita del formulario de entrega, se encuentran adjuntos en formato PDF.</p>";
                                $message->setBody($mail->addFooter($body), 'text/html');
                                $message->setTo($emailOwner);
                                $functions = new Functions;
                                $formulario = $functions->generarFormulario(NULL, $tokenReserve);
                                $reporte = $functions->generarReporte($idCar);
                                $contrato = $functions->generarContrato($tokenReserve);
                                $message->attach(Swift_Attachment::newInstance($contrato, 'contrato.pdf', 'application/pdf'));
                                $message->attach(Swift_Attachment::newInstance($formulario, 'formulario.pdf', 'application/pdf'));
                                $message->attach(Swift_Attachment::newInstance($reporte, 'reporte.pdf', 'application/pdf'));
                                $mailer->send($message);


                                //pedidos de reserva pagado (arrendatario)
                                $message = $mail->getMessage();
                                $message->setSubject('La reserva ha sido pagada!');
                                $body = "<p>Hola $nameRenter:</p><p>Has pagado la reserva y esta ya esta confirmada.</p><p>Recuerda que debes llenar el FORMULARIO DE ENTREGA Y DEVOLUCIÓN del vehículo.</p><p>No des inicio al arriendo si el auto tiene más daños que los declarados.</p><p>Datos del propietario:<br><br>Nombre: $nameOwner $lastnameOwner<br>Teléfono: $telephoneOwner<br>Correo: $emailOwner<br>Dirección: $addressCar</p><p>Los datos del arriendo y la versión escrita del formulario de entrega, se encuentran adjuntos en formato PDF.</p>";
                                $body .= "<p>En caso de siniestro debes dejar constancia en la comisaría más cercana INMEDITAMENTE y llamar al (02)333 3714.</p>";
                                $message->setBody($mail->addFooter($body), 'text/html');
                                $message->setTo($emailRenter);
                                $message->attach(Swift_Attachment::newInstance($contrato, 'contrato.pdf', 'application/pdf'));
                                $message->attach(Swift_Attachment::newInstance($formulario, 'formulario.pdf', 'application/pdf'));
                                $message->attach(Swift_Attachment::newInstance($reporte, 'reporte.pdf', 'application/pdf'));
                                $mailer->send($message);

                                //mail Soporte
                                $message = $mail->getMessage();
                                $message->setSubject('Nueva reserva paga ' . idReserve . '');
                                $body = "<p>Hola $nameRenter:</p><p>Has pagado la reserva y esta ya esta confirmada.</p><p>Recuerda que debes llenar el FORMULARIO DE ENTREGA Y DEVOLUCIÓN del vehículo.</p><p>No des inicio al arriendo si el auto tiene más daños que los declarados.</p><p>Datos del propietario:<br><br>Nombre: $nameOwner $lastnameOwner<br>Teléfono: $telephoneOwner<br>Correo: $emailOwner<br>Dirección: $addressCar</p><p>Los datos del arriendo y la versión escrita del formulario de entrega, se encuentran adjuntos en formato PDF.</p>";
                                $message->setBody($mail->addFooter($body), 'text/html');
                                $message->setTo("soporte@arriendas.cl");
                                $message->attach(Swift_Attachment::newInstance($contrato, 'contrato.pdf', 'application/pdf'));
                                $message->attach(Swift_Attachment::newInstance($formulario, 'formulario.pdf', 'application/pdf'));
                                $message->attach(Swift_Attachment::newInstance($reporte, 'reporte.pdf', 'application/pdf'));
                                
                                $renterUser = $reserve->getUser();
                                if (!is_null($renterUser->getDriverLicenseFile())) {
                                    $filepath = $renterUser->getDriverLicenseFile();
                                    if (is_file($filepath)) {
                                        $message->attach(Swift_Attachment::fromPath($renterUser->getDriverLicenseFile())->setFilename("LicenciaArrendatario-".$renterUser->getLicenceFileName()));
                                    }
                                }
               
                                
                                $mailer->send($message);

                                //crea la fila calificaciones habilitada para la fecha de término de reserva + 2 horas (solo si no es una extension de otra reserva)
                                if (!$reserve->getIdPadre()) {

                                    $fecha = $reserve->getFechaHabilitacionRating();
                                    $idOwner = $reserve->getIdOwner();
                                    $idRenter = $reserve->getIdRenter();

                                    $rating = new Rating();
                                    $rating->setFechaHabilitadaDesde($fecha);
                                    $rating->setIdOwner($idOwner);
                                    $rating->setIdRenter($idRenter);
                                    $rating->save();

                                    //actualiza rating_id en la tabla Reserve
                                    $ratingId = $rating->getId();
                                    $reserve->setFechaPago(strftime("%Y-%m-%d %H:%M:%S"));
                                    $reserve->setRatingId($ratingId);
                                    $reserve->save();

                                }

                                //almacena $idReserve en la tabla mail calificaciones
                                $reserve->encolarMailCalificaciones();
                            }
                        } else {
                            echo "No hay compras hechas para ser pagadas (Error de monto invalido)";
                            $this->_log("Pago", "Error", "Usuario: " . $customer_in_session . ". Order ID: " . $order->getId());
                            Doctrine_Core::getTable("Transaction")->successTransaction($orderId, $token, $paypalSettings["status_error"], 1);
                        }*/
                        break;
                    case "-1":
                        /* transaccion rechazada por el medio de pago */
                        $msg = "Transaccion #:" . $GPSTransactionId . " fue rechazada.";
                        $this->_log("Pago", "Transaction Rejected", $msg);
                        $this->redirect("webpay_reject");
                        break;
                    case "-2":
                        /* transaccion rechazada por el medio de pago */
                        $msg = "Transaccion #:" . $GPSTransactionId . " debe reintentarse.";
                        $this->_log("Pago", "Transaction Rejected", $msg);
                        $this->redirect("webpay_reject");
                        break;
                    default:
                        /* se produjo un error en el medio de pago */
                        $msg = "Transaccion #: " . $GPSTransactionId . "  Error code:" . $wsTransactionDetailOutput->responseCode;
                        $this->_log("Pago", "ApiError", $msg);
                        $this->redirect("webpay_failure");
                        break;
                };
            } else {
                /* getExpressCheckout Failure */
                $msg = "API Error Message : " . $validationResult;
                $this->_log("Pago", "ApiError", $msg);
                $this->redirect("webpay_failure");
            }
        } else {
            /* not session */
            $this->redirect('@homepage');
        }
    }

    public function executeProcessPaymentFinalGPS(sfWebRequest $request) {
        error_log("ProcessPaymentFinalGPS");
        $customer_in_session = $this->getUser()->getAttribute('userid');
        $carId = $request->getParameter("carId");
        if ($customer_in_session) {
            $this->redirect("car_edit", array("id" => $carId));
        } else {
            $this->redirect('@homepage');
        }
    }

    public function executeProcessPaymentFinal(sfWebRequest $request) {
        error_log("ProcessPaymentFinal");
        $customer_in_session = $this->getUser()->getAttribute('userid');
        if ($customer_in_session) {
            $this->redirect("webpay_failure");
        } else {
            $this->redirect('@homepage');
        }
    }

    public function executeProcessPaymentFailure(sfWebRequest $request) {
        error_log("ProcessPaymentFailure");
        $this->setLayout("newIndexLayout");
    }

    public function executeProcessPaymentRejected(sfWebRequest $request) {
        error_log("ProcessPaymentRejected");
        $this->setLayout("newIndexLayout");
    }

    /**
     * Get environment config for paypal service.
     * @return array conf.
     */
    protected function getConfig($settings = null) {
        if (is_null($settings)) {
            $settings = $this->getSettings();
        }
        $config = array(
            'mode' => $settings["mode"],
            "acct1.UserName" => $settings["username"],
            "acct1.Password" => $settings["password"],
            "acct1.Signature" => $settings["signature"]
        );
        return $config;
    }

    /**
     * Get environment paypal settings.
     * @return array setttings.
     */
    protected function getSettings() {
        $rawSettings = sfYaml::load(dirname(dirname(__FILE__)) . "/config/webpay.yml");
        $env = sfConfig::get('sf_environment');
        return $rawSettings["settings"][$env];
    }

    protected function _log($step, $status, $msg) {
        $logPath = sfConfig::get('sf_log_dir') . '/webpay.log';
        $custom_logger = new sfFileLogger(new sfEventDispatcher(), array('file' => $logPath));
        $custom_logger->info($step . " - " . $status . " - " . $msg);
    }

}
