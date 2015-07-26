<?php

require_once sfConfig::get('sf_lib_dir') . '/vendor/khipu/KhipuService.php';

class khipuActions extends sfActions {

    public function executeGeneratePayment (sfWebRequest $request) {

        try {

            $reserveId     = $request->getParameter("reserveId");
            $transactionId = $request->getParameter("transactionId");
            $GPSTransactionId = $request->getParameter("gps_transactionId", null);

            if(is_null($GPSTransactionId)){

                $Reserve = Doctrine_Core::getTable('Reserve')->find($reserveId);
                $Transaction = Doctrine_Core::getTable('Transaction')->find($transactionId);

                $settings = $this->getSettings();
                        
                $khipuService = new KhipuService($settings["receiver_id"], $settings["secret"], $settings["create-payment-url"]);

                $data = array(
                    'receiver_id'    => $settings["receiver_id"],
                    'subject'        => "Arriendo " . $Reserve->getCar()->getModel()->getBrand()->getName()." ".$Reserve->getCar()->getModel()->getName(),
                    'body'           => "Reserva n° ".$Reserve->getId(),
                    'amount'         => $Reserve->getPrice() + $Reserve->getMontoLiberacion() - $Transaction->getDiscountamount(),
                    'notify_url'     => $this->generateUrl("khipuNotify", array(), true),
                    'return_url'     => $this->generateUrl("khipuReturn", array(), true),
                    'cancel_url'     => $this->generateUrl("khipuCancel", array(), true),
                    'transaction_id' => $transactionId,
                    'picture_url'    => "",
                    'custom'         => "",
                );

            }else{
                 $GPSTransaction = Doctrine_Core::getTable("GPSTransaction")->find($GPSTransactionId);
                $GPS = Doctrine_Core::getTable("gps")->find($GPSTransaction->gps_id);
                $Car = Doctrine_Core::getTable("cartmp")->find($GPSTransaction->car_tmp_id);
                $User = Doctrine_Core::getTable("user")->find($Car->user_id);
                $settings = $this->getSettings();
                        
                $khipuService = new KhipuService($settings["receiver_id"], $settings["secret"], $settings["create-payment-url"]);

                $data = array(
                    'receiver_id'    => $settings["receiver_id"],
                    'subject'        => "GPS :" . $GPS->description,
                    'body'           => "Transacción n° ".$GPSTransaction->id,
                    'amount'         => round($GPSTransaction->amount),
                    'notify_url'     => $this->generateUrl("khipuNotifyGPS", array(), true),
                    'return_url'     => $this->generateUrl("khipuReturn", array(), true),
                    'cancel_url'     => $this->generateUrl("khipuCancel", array(), true),
                    'transaction_id' => "G".$GPSTransaction->id,
                    'picture_url'    => "",
                    'custom'         => "",
                );

            }

           

            error_log("[khipu/generatePayment] ".print_r($data, true));

            $url = $khipuService->createPaymentURL($data)->url;
        } catch (Execption $e) {
            error_log("[khipu/generatePayment] ".$e->getMessage());
            if ($request->getHost() == "www.arriendas.cl") {
                Utils::reportError($e->getMessage(), "khipu/generatePayment");
            }
        }

        $this->redirect($url);
    }

    /////////////////////////////////////////////////////////////////
    
    public function executeConfirmPayment(sfWebRequest $request) {

        error_log("[mobile] [khipu/confirmPayment] REQUESTED");

        $this->carMarcaModel = urldecode($request->getParameter("carMarcaModel"));
        $this->duracionReserva = urldecode($request->getParameter("duracionReserva"));
        $montoTotalPagoPorDia = $request->getParameter("duracionReservaPagoPorDia");

        $customer_in_session = $this->getUser()->getAttribute('userid');

        $this->deposito = $request->getParameter("deposito");
        $this->montoDeposito = 0;
        if ($this->deposito == "depositoGarantia") {
            $this->montoDeposito = 180000;
        } else if ($this->deposito == "pagoPorDia") {
            $this->montoDeposito = $montoTotalPagoPorDia;
        }

        if ($customer_in_session) {

            if (!$request->hasParameter("id") || $request->getParameter("id") == null) {
                $this->getUser()->setFlash('error', 'No se ha recibido un codigo de orden para la creacion del pago');
                $this->error = "";
                $this->redirect("bcpuntopagos/index");
            } else {
                $order = Doctrine_Core::getTable("Transaction")->getTransaction($request->getParameter('id'));
                $idReserve = $order->getReserveId();
                $this->ppId = $request->getParameter('id');
                $reserve = Doctrine_Core::getTable('reserve')->findOneById($idReserve);

                $carClass = $reserve->getCar();
                $typeFoto = $carClass->getPhotoS3();
                $baseUrl = $this->generateUrl("homepage", array(), TRUE);
                $carImageUrl = $baseUrl . "../images/Home/logo_arriendas2.png";
                if ($typeFoto == 0) {
                    if (strlen($carClass->getFotoPerfil()) > 0) {
                        $carImageUrl = $baseUrl . "../uploads/cars/thumbs/" . $carClass->getFotoPerfil();
                    }
                } else {
                    if (strlen($carClass->getFoto()) > 0) {
                        $carImageUrl = $baseUrl . $carClass->getFoto();
                    }
                }

                $montoLiberacion = $reserve->getMontoLiberacion();

                $this->hasDiscountFB = $order->getDiscountfb();
                $this->priceMultiply = 1 - (0.05 * $order->getDiscountfb());
                $this->ppMonto = $order->getPrice(); //reemplazar por metodo getMonto
                $this->ppIdReserva = $idReserve;

                $finalPrice = $order->getPrice() - $order->getDiscountamount() + $montoLiberacion;
                $finalPrice = number_format($finalPrice, 0, '.', '');

                $this->finalPrice = $finalPrice;

                if ($finalPrice > 0) {
                    /* la transaccion es valida, inicializo pago */
                    $settings = $this->getSettings();
                    
                    $khipuService = new KhipuService($settings["receiver_id"], $settings["secret"], $settings["create-payment-url"]);

                    $data = array(
                        'receiver_id' => $settings["receiver_id"],
                        'subject' => "Arriendo " . $this->carMarcaModel,
                        'body' => "Arriendo " . $this->carMarcaModel,
                        'amount' => $finalPrice,
                        'notify_url' => $this->generateUrl("khipuNotify", array(), true),
                        'return_url' => $this->generateUrl("khipuReturn", array(), true),
                        'cancel_url' => $this->generateUrl("khipuCancel", array(), true),
                        'transaction_id' => $request->getParameter('id'),
                        'picture_url' => $carImageUrl,
                        'custom' => 'el modelo en color rojo',
                    );

                    $this->redirect($khipuService->createPaymentURL($data)->url);

                    $this->checkOutUrl = "#";
                    try {
                        $msg = "Call khipu with url => " . $settings["create-payment-url"] . ", transaction_id:" . $data["transaction_id"] . "   notify_url: " . $data["notify_url"];
                        $this->logMessage($msg);
                        $this->_log("Call", "info", $msg);
                        $response = $khipuService->createPaymentURL($data);
                        $this->_log("Call_response", "info", "respuesta: ".  serialize($response));
                        $this->checkOutUrl = $response->url;
                        $khipuTransaction = array(
                            "payment-id" => $response->id,
                            "transaction-id" => $request->getParameter('id'),
                            "status" => "created"
                        );
                        $this->getUser()->setAttribute("khipu-transaction", $khipuTransaction);
                    } catch (Exception $ex) {
                        $msg = " | Exception : " . $ex->getMessage();
                        $this->_log("Exception", "Error", "Usuario: " . $customer_in_session . ". Order ID: " . $order->getId() . $msg);
                        $this->redirect("khipu/processPaymentFailure");
                    }
                } else {
                    $this->getUser()->setFlash('error', 'Precio final nulo');
                    $this->redirect("bcpuntopagos/index");
                }
            }
        } else {
            $this->redirect('@homepage');
        }
    }

    public function executeNotifyPayment(sfWebRequest $request) {

        $this->_log("NotifyPayment", "INFO", "Start validation");

        $userId = $this->getUser()->getAttribute("userid");
        
        $settings = $this->getSettings();

        $data = array(
            "api_version" => $_POST['api_version'],
            "receiver_id" => $_POST['receiver_id'],
            "notification_id" => $_POST['notification_id'],
            "subject" => $_POST['subject'],
            "amount" => $_POST['amount'],
            "currency" => $_POST['currency'],
            "custom" => $_POST['custom'],
            "transaction_id" => $_POST['transaction_id'],
            "payer_email" => $_POST['payer_email'],
            "notification_signature" => $_POST['notification_signature']
        );

        try {

            $khipuService = new KhipuService($settings["receiver_id"], $settings["secret"], $settings["notification-validation-url"]);
            $response = $khipuService->notificationValidation($data);

            error_log("[mobile] [khipu/notifyPayment] ".print_r($response, true));

            if ($response == 'VERIFIED' && $data["receiver_id"] == $settings["receiver_id"]) {
                
                // Los parametros son correctos, ahora falta verificar que transacion_id, custom, subject y amount correspondan al pedido 
                $this->_log("NotifyPayment", "INFO", "cobro verificado");
                $this->_log("NotifyPayment", "INFO", "respuesta: ".  serialize($response));

                $Transaction = Doctrine_Core::getTable("Transaction")->find($data["transaction_id"]);
                $Reserve     = Doctrine_Core::getTable('Reserve')->find($Transaction->getReserveId());

                $montoLiberacion = 0;
                if ($Reserve->getLiberadoDeGarantia()) {
                    $montoLiberacion = $Reserve->getMontoLiberacion();
                }

                $finalPrice = $Transaction->getPrice() + $montoLiberacion;

                if ($finalPrice > 0) {

                    $this->_log("Pago", "Exito", "Usuario: " . $userId . ". Order ID: " . $Transaction->getId());

                    if (!$Transaction->getCompleted()) {
                        
                        error_log("[khipu/notifyPayment] [".date("Y-m-d H:i:s")."] Nuevo pago recibido");

                        $Renter = $Reserve->getUser();
                        $Owner  = $Reserve->getCar()->getUser();

                        $Functions = new Functions;
                        $Functions->generarNroFactura($Reserve, $Transaction);
                        
                        $Transaction->setCompleted(true);
                        $Transaction->save();

                        $Reserve->setFechaPago(date("Y-m-d H:i:s"));
                        $Reserve->save();

                        $mail   = new Email();
                        $mailer = $mail->getMailer();

                        $formulario = $Functions->generarFormulario(NULL, $Reserve->token);
                        $reporte    = $Functions->generarReporte($Reserve->getCar()->id);
                        $contrato   = $Functions->generarContrato($Reserve->token);
                        $pagare     = $Functions->generarPagare($Reserve->token);

                        // Correo dueño
                        $subject = "¡Has recibido un pago! Apruébalo ahora";
                        $body    = $this->getPartial('emails/paymentDoneOwner', array('Reserve' => $Reserve));
                        $from    = array("soporte@arriendas.cl" => "Soporte Arriendas.cl");
                        $to      = array($Owner->email => $Owner->firstname." ".$Owner->lastname);

                        $message = $mail->getMessage();
                        $message->setSubject($subject);
                        $message->setBody($body, 'text/html');
                        $message->setFrom($from);
                        $message->setTo($to);
                        
                        $message->attach(Swift_Attachment::newInstance($contrato, 'contrato.pdf', 'application/pdf'));
                        $message->attach(Swift_Attachment::newInstance($formulario, 'formulario.pdf', 'application/pdf'));
                        $message->attach(Swift_Attachment::newInstance($reporte, 'reporte.pdf', 'application/pdf'));
                        
                        if (!is_null($Renter->getDriverLicenseFile())) {
                            $filepath = $Renter->getDriverLicenseFile();
                            if (is_file($filepath)) {
                                $message->attach(Swift_Attachment::fromPath($Renter->getDriverLicenseFile())->setFilename("LicenciaArrendatario-".$Renter->getLicenceFileName()));
                            }
                        }
                        
                        error_log("[khipu/notifyPayment] [".date("Y-m-d H:i:s")."] Enviado email al propietario");
                        $mailer->send($message);

                        // Correo arrendatario
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

                            error_log("[khipu/notifyPayment] Enviando email al arrendatario");
                        } else {
                            $Reserve->setPayEmailPending(1);
                            $subject = "La reserva ha sido pagada";
                            $body    = $this->getPartial('emails/paymentDoneRenterWithoutDriverLicense', array('Renter' => $Renter));
                            $from    = array("soporte@arriendas.cl" => "Soporte Arriendas.cl");
                            $to      = array($Renter->email => $Renter->firstname." ".$Renter->lastname);

                            $message = Swift_Message::newInstance()
                                ->setSubject($subject)
                                ->setBody($body, 'text/html')
                                ->setFrom($from)
                                ->setTo($to);

                            error_log("[khipu/notifyPayment] Enviando email al arrendatario sin licencia");

                        }   
                        $this->getMailer()->send($message);

                        // Correo soporte
                        $subject = "Nuevo pago. Reserva: ".$Reserve->id;
                        $body    = $this->getPartial('emails/paymentDoneSupport', array('Reserve' => $Reserve));
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
                        
                        error_log("[khipu/notifyPayment] [".date("Y-m-d H:i:s")."] Enviando email a soporte");
                        $mailer->send($message);

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

                        error_log("[khipu/notifyPayment] [".date("Y-m-d H:i:s")."] ---------- HABEMUS PAGO --------");

                        $OpportunityQueue = Doctrine_Core::getTable('OpportunityQueue')->findOneByReserveId($Reserve->id);
                        if (!$OpportunityQueue) {
                            $OpportunityQueue = new OpportunityQueue();
                            $OpportunityQueue->setReserve($Reserve);
                            $OpportunityQueue->save();
                        }
                    }
                }
            } else {
                error_log("[khipu/notifyPayment] [".date("Y-m-d H:i:s")."] Error en el proceso de verificacion: Response: ".$response.", Receiver local: ".$settings["receiver_id"].", Receiver request: ".$data["receiver_id"]);
                $this->_log("NotifyPayment", "ERROR", "Hubo un error en el proceso de verificacion.");
            }
        } catch (Exception $e) {
            error_log("[khipu/notifyPayment] [".date("Y-m-d H:i:s")."] ERROR: ".$e->getMessage());
            Utils::reportError($e->getMessage(), "khipu/notifyPayment");
        }

        die();
    }

    public function executePaymentInformation(sfWebRequest $request) {

        error_log("[mobile] [khipu/paymentInformation] ".print_r($request->getParameterHolder(), true));

        $this->setLayout("newIndexLayout");

        $customer_in_session = $this->getUser()->getAttribute('userid');
        
        if ($customer_in_session) {

            $settings = $this->getSettings();
            $khipuService = new KhipuService($settings["receiver_id"], $settings["secret"], $settings["payment-status-url"]);

            $khipuTransaction = $this->getUser()->getAttribute("khipu-transaction");

            $data = array(
                'receiver_id' => $settings["receiver_id"],
                'payment_id' => $khipuTransaction["payment-id"]
            );
            $msg = "Call khipu with url => " . $settings["payment-status-url"] . ", payment_id:" . $data["payment_id"];
            $this->_log("Call", "info", $msg);

            $response = $khipuService->paymentStatus($data);
            error_log("[mobile] [khipu/paymentInformation] ".print_r($response, true));
            switch ($response->status) {
                case "done":
                    $this->paymentMsg = "El pago ha sido realizado.";
                    /* update transaction info */
                    $khipuTransaction["status"] = "done";
                    $this->getUser()->setAttribute("khipu-transaction", $khipuTransaction);
                    /*$this->forward("khipu", "processPayment");*/

                    // ESTO ES MOMENTANEO MIENTRAS SE RESUELVE EL TEMA DE LAS MULTIPLES URLS DE NOTIFICACION
                    error_log("[khipu/notifyPayment] Nuevo pago recibido");

                    $Transaction = Doctrine_Core::getTable('Transaction')->find($khipuTransaction['transaction-id']);

                    if (!$Transaction->getCompleted()) {
                        $Reserve = $Transaction->getReserve();

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
                        
                        error_log("[khipu/notifyPayment] Enviando email al propietario");
                        $this->getMailer()->send($message);

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

                        error_log("[khipu/notifyPayment] Enviando email al arrendatario");
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
                        
                        error_log("[khipu/notifyPayment] Enviando email a soporte");
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

                        error_log("[khipu/notifyPayment] ---------- HABEMUS PAGO --------");

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
                    }
                    // AQUI TERMINA
                    $this->redirect("reserves");
                    break;
                case "pending":
                    $this->paymentMsg = "El pago aun no ha sido realizado.";
                    break;
                case "verifying":
                    $this->paymentMsg = "El pago esta siendo procesado. Actualiza esta página en unos segundos para volver a verificar.";
                    break;
                default :
                    break;
            }
        } else {
            /* not session */
            $this->redirect('@homepage');
        }
    }

    public function executeProcessPayment(sfWebRequest $request) {

        $customer_in_session = $this->getUser()->getAttribute('userid');

        if ($customer_in_session) {

            $khipuTransaction = $this->getUser()->getAttribute("khipu-transaction");
            $order = Doctrine_Core::getTable("Transaction")->getTransaction($khipuTransaction["transaction-id"]);
            $idReserve = $order->getReserveId();
            $reserve = Doctrine_Core::getTable('reserve')->findOneById($idReserve);

            $this->getUser()->getAttributeHolder()->remove('khipu-transaction');

            $this->nameOwner = $reserve->getNameOwner();
            $this->emailOwner = $reserve->getEmailOwner();
            $this->lastnameOwner = $reserve->getLastnameOwner();
            $this->telephoneOwner = $reserve->getTelephoneOwner();
        } else {
            /* not session */
            $this->redirect('@homepage');
        }
    }

    public function executeProcessPaymentCanceled(sfWebRequest $request) {

        error_log("[ProcessPaymentCanceled] REQUESTED");

        $customer_in_session = $this->getUser()->getAttribute('userid');

        if ($customer_in_session) {
            $this->redirect("reserves");
        } else {
            $this->redirect('@homepage');
        }
    }

    public function executeProcessPaymentFailure(sfWebRequest $request) {}

    protected function getSettings() {
        $rawSettings = sfYaml::load(dirname(dirname(__FILE__)) . "/config/khipu.yml");
        $env = sfConfig::get('sf_environment');
        return $rawSettings["settings"][$env];
    }

    protected function _log($step, $status, $msg) {
        $logPath = sfConfig::get('sf_log_dir') . '/khipu.log';
        $custom_logger = new sfFileLogger(new sfEventDispatcher(), array('file' => $logPath));
        $custom_logger->info($step . " - " . $status . ". " . $msg);
    }

    public function executeNotifyPaymentGPS(sfWebRequest $request) {

        $this->_log("NotifyPaymentGPS", "INFO", "Start validation");

        $userId = $this->getUser()->getAttribute("userid");
        
        $settings = $this->getSettings();

        $data = array(
            "api_version" => $_POST['api_version'],
            "receiver_id" => $_POST['receiver_id'],
            "notification_id" => $_POST['notification_id'],
            "subject" => $_POST['subject'],
            "amount" => $_POST['amount'],
            "currency" => $_POST['currency'],
            "custom" => $_POST['custom'],
            "transaction_id" => $_POST['transaction_id'],
            "payer_email" => $_POST['payer_email'],
            "notification_signature" => $_POST['notification_signature']
        );

        try {

            $khipuService = new KhipuService($settings["receiver_id"], $settings["secret"], $settings["notification-validation-url"]);
            $response = $khipuService->notificationValidation($data);

            error_log("[khipu/notifyPaymentGPS] ----------------- ENTRANDO NOTIFICACION -----------------");
            error_log("[khipu/notifyPaymentGPS] ".print_r($request->getPostParameters(), true));
            error_log("[khipu/notifyPaymentGPS] ".print_r($response, true));

            if ($response == 'VERIFIED' && $data["receiver_id"] == $settings["receiver_id"]) {
                
                // Los parametros son correctos, ahora falta verificar que transacion_id, custom, subject y amount correspondan al pedido 
                $this->_log("NotifyPaymentGPS", "INFO", "cobro verificado");
                $this->_log("NotifyPaymentGPS", "INFO", "respuesta: ".  serialize($response));

                $GPSTransactionId = ltrim($data["transaction_id"], 'G');
                $GPSTransaction = Doctrine_Core::getTable("GPSTransaction")->find($GPSTransactionId);

                $this->_log("Pago", "Exito", "Usuario: " . $userId . ". GPSTransaction ID: " . $GPSTransaction->id);

                if (!$GPSTransaction->getCompleted()) {
                    
                    error_log("[khipu/notifyPaymentGPS] Nuevo pago recibido");

                    // $Functions = new Functions;
                    // $Functions->generarNroFactura($Reserve, $Transaction);

                    // Notificaciones
                    // Notification::make($Renter->id, 3, $Reserve->id); // pago
                    
                    $User = Doctrine_Core::getTable("user")->find($userId);

                    $GPSTransaction->setCompleted(true);
                    $GPSTransaction->setPaidOn(date("Y-m-d H:i:s"));
                    
                    $CarTmp = Doctrine_Core::getTable("cartmp")->find($GPSTransaction->car_tmp_id);

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
                    $Car->setIsAirportDelivery($CarTmp->getIsAirPortDelivery());
                    $Car->setHasGps(1);
                    $Car->setCityId(27);

                    $GPSTransaction->save();
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
                    /*$message->setBcc(array("cristobal@arriendas.cl" => "Cristóbal Medina Moenne"));*/
                    
                    $mailer->send($message);

                    //$formulario = $Functions->generarFormulario(NULL, $Reserve->token);
                    //$reporte    = $Functions->generarReporte($Reserve->getCar()->id);
                    //$contrato   = $Functions->generarContrato($Reserve->token);
                    //$pagare     = $Functions->generarPagare($Reserve->token);

                    
                    error_log("[khipu/notifyPaymentGPS] Enviando email al usuario");

                    
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
                    error_log("[khipu/notifyPaymentGPS] mensaje enviado");

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
                    
                    error_log("[khipu/notifyPaymentGPS] Enviando email a soporte");
                    $this->getMailer()->send($message);
                    error_log("[khipu/notifyPaymentGPS] mensaje enviado");

                    error_log("[khipu/notifyPaymentGPS] ---------- HABEMUS PAGO --------");
                }
                
            } else {
                error_log("[khipu/notifyPaymentGPS] Error en el proceso de verificacion: Response: ".$response.", Receiver local: ".$settings["receiver_id"].", Receiver request: ".$data["receiver_id"]);
                $this->_log("NotifyPaymentGPS", "ERROR", "Hubo un error en el proceso de verificacion.");
            }
        } catch (Exception $e) {
            error_log("[khipu/notifyPayment] ERROR: ".$e->getMessage());
            Utils::reportError($e->getMessage(), "khipu/notifyPaymentGPS");
        }

        die();
    }


}
