<?php

require_once sfConfig::get('sf_lib_dir') . '/vendor/webpay/WebpayService.php';

/**
 * paypal actions.
 *
 * @package    CarSharing
 * @subpackage paypal
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class webpayActions extends sfActions {

    public function executeConfirmPayment(sfWebRequest $request) {
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

            if (!$request->hasParameter("id") || $request->getParameter("id") == null) {

                $this->getUser()->setFlash('error', 'No se ha recibido un codigo de orden para la creacion en Punto Pagos');
                $this->error = "";
                $this->redirect("bcpuntopagos/index");
            } else {
                $order = Doctrine_Core::getTable("Transaction")->getTransaction($request->getParameter('id'));
                $idReserve = $order->getReserveId();
                $this->ppId = $idReserve;
                $reserve = Doctrine_Core::getTable('reserve')->findOneById($idReserve);

                $opcionLiberacion = $reserve->getLiberadoDeGarantia();
                if ($opcionLiberacion == 0) {
                    $montoLiberacion = 0;
                } else if ($opcionLiberacion == 1) {
                    $montoLiberacion = $reserve->getMontoLiberacion();
                }

                $this->hasDiscountFB = $order->getDiscountfb();
                $this->priceMultiply = 1 - (0.05 * $order->getDiscountfb());
                $this->ppMonto = $order->getPrice(); //reemplazar por metodo getMonto
                $this->ppIdReserva = $request->getParameter("id");

                $finalPrice = $order->getPrice() - $order->getDiscountamount() + $montoLiberacion;
                $finalPrice = number_format($finalPrice, 0, '.', '');
                $this->finalPrice = $finalPrice;

                if ($finalPrice > 0) {

                    /* la transaccion es valida, inicializo paypal */
                    /* paypal init */
                    $webpaySettings = $this->getSettings();

                    $wsInitTransactionInput = new wsInitTransactionInput();
                    $wsTransactionDetail = new wsTransactionDetail();

                    /* inicio WSWEBPAY */
                    /* Variables de tipo string */
                    $wsInitTransactionInput->wSTransactionType = "TRX_NORMAL_WS";
                    $wsInitTransactionInput->buyOrder = $order->getId();
                    $wsInitTransactionInput->returnURL = $this->generateUrl("webpayReturn", array(), true);
                    $wsInitTransactionInput->finalURL = $this->generateUrl("paypalCancel", array(), true);

                    $wsTransactionDetail->commerceCode = "597020000019";
                    $wsTransactionDetail->buyOrder = $order->getId();
                    $wsTransactionDetail->amount = $finalPrice;

                    $wsInitTransactionInput->transactionDetails = $wsTransactionDetail;

                    $webpayService = new WebpayService($webpaySettings["url"]);

                    /* fin WSWEBPAY */
                    $this->checkOutUrl = "#";

                    try {
                        $initTransactionResponse = $webpayService->initTransaction(
                                array("wsInitTransactionInput" => $wsInitTransactionInput)
                        );

                        $xmlResponse = $webpayService->soapClient->__getLastResponse();

                        $soapValidation = new SoapValidation($xmlResponse, SERVER_CERT);
                        $validationResult = $soapValidation->getValidationResult();
                        $this->logMessage(">>>>>>>>>>>>>>>>>>>>>>>>>Aca no anduvo Error");
                        if ($validationResult) {
                            /* Invocar sólo sí $validationResult es TRUE */
                            $wsInitTransactionOutput = $initTransactionResponse->return;
                            $this->checkOutUrl = $wsInitTransactionOutput["url"];
                        } else {
                            $msg = " | API Error Message : " . "";
                            $this->_log("Pago", "ApiError", "Usuario: " . $customer_in_session . ". Order ID: " . $order->getId() . $msg);
                            $this->redirect("webpay/processPaymentFailure");
                        }
                    } catch (Exception $ex) {
                        $msg = " | Exception : " . $ex->getMessage();
                        $this->_log("Exception", "Error", "Usuario: " . $customer_in_session . ". Order ID: " . $order->getId() . $msg);
                        $this->redirect("webpay/processPaymentFailure");
                    }
                }
            }
        } else {
            $this->redirect('@homepage');
        }
    }

    public function executeProcessPayment(sfWebRequest $request) {
        $customer_in_session = $this->getUser()->getAttribute('userid');
        if ($customer_in_session) {
            $token = $request->getParameter("token");
            /* paypal init */
            $paypalSettings = $this->getSettings();
            /* execute payment */
            $getExpressCheckoutDetailsReq = new GetExpressCheckoutDetailsReq();
            $getExpressCheckoutDetailsRequest = new GetExpressCheckoutDetailsRequestType($token);
            $getExpressCheckoutDetailsReq->GetExpressCheckoutDetailsRequest = $getExpressCheckoutDetailsRequest;
            $service = new PayPalAPIInterfaceServiceService($this->getConfig($paypalSettings));
            $response = $service->GetExpressCheckoutDetails($getExpressCheckoutDetailsReq);
            $orderId = null;
            if ($response->Ack == $paypalSettings["status_success"]) {
                $doExpressCheckoutPaymentReq = new DoExpressCheckoutPaymentReq();
                $doExpressCheckoutPaymentRequestDetails = new DoExpressCheckoutPaymentRequestDetailsType();
                $doExpressCheckoutPaymentRequestDetails->Token = $response->GetExpressCheckoutDetailsResponseDetails->Token;
                $doExpressCheckoutPaymentRequestDetails->PayerID = $response->GetExpressCheckoutDetailsResponseDetails->PayerInfo->PayerID;
                $doExpressCheckoutPaymentRequestDetails->PaymentDetails = $response->GetExpressCheckoutDetailsResponseDetails->PaymentDetails;
                $orderId = $response->GetExpressCheckoutDetailsResponseDetails->InvoiceID;
                $doExpressCheckoutPaymentRequest = new DoExpressCheckoutPaymentRequestType($doExpressCheckoutPaymentRequestDetails);
                $doExpressCheckoutPaymentReq->DoExpressCheckoutPaymentRequest = $doExpressCheckoutPaymentRequest;
                try {
                    $doExpressCheckoutPaymentResponse = $service->DoExpressCheckoutPayment($doExpressCheckoutPaymentReq);
                    if ($doExpressCheckoutPaymentResponse->Ack == $paypalSettings["status_success"]) {
                        if ($doExpressCheckoutPaymentResponse->DoExpressCheckoutPaymentResponseDetails->PaymentInfo != null) {
                            $paymentInfoArray = $doExpressCheckoutPaymentResponse->DoExpressCheckoutPaymentResponseDetails->PaymentInfo;
                            foreach ($paymentInfoArray as $payInfo) {
                                $this->transactionId = $payInfo->TransactionID;
                            }

                            $order = Doctrine_Core::getTable("Transaction")->getTransaction($orderId);
                            $this->idReserva = $order->getReserveId();
                            $reserve = Doctrine_Core::getTable('reserve')->findOneById($this->idReserva);

                            //cancel other reserves not paid for the same rental dates
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
                            }
                        }
                    } else {
                        /* doExpressCheckout Failure */
                        $msg = " | API Error Message : " . $response->Errors[0]->LongMessage;
                        $this->_log("Pago", "ApiError", "Usuario: " . $customer_in_session . ". Order ID: " . $orderId . $msg);
                        $this->redirect("paypal/processPaymentFailure");
                    }
                } catch (Exception $ex) {
                    /* exception on paypal service */
                    $msg = " | Exception : " . $ex->getMessage();
                    $this->_log("Exception", "Error", "Usuario: " . $customer_in_session . ". Order ID: " . $orderId . $msg);
                    $this->redirect("paypal/processPaymentFailure");
                }
            } else {
                /* getExpressCheckout Failure */
                $msg = " | API Error Message : " . $response->Errors[0]->LongMessage;
                $this->_log("Pago", "ApiError", "Usuario: " . $customer_in_session . ". Order ID: " . $orderId . $msg);
                $this->redirect("paypal/processPaymentFailure");
            }
        } else {
            /* not session */
            $this->redirect('@homepage');
        }
    }

    public function executeProcessPaymentCanceled(sfWebRequest $request) {
        $customer_in_session = $this->getUser()->getAttribute('userid');

        if ($customer_in_session) {
            $token = $request->getParameter("token");
            /* paypal init */
            $paypalSettings = $this->getSettings();
            /* execute payment */
            $getExpressCheckoutDetailsReq = new GetExpressCheckoutDetailsReq();
            $getExpressCheckoutDetailsRequest = new GetExpressCheckoutDetailsRequestType($token);
            $getExpressCheckoutDetailsReq->GetExpressCheckoutDetailsRequest = $getExpressCheckoutDetailsRequest;
            $service = new PayPalAPIInterfaceServiceService($this->getConfig($paypalSettings));
            $response = $service->GetExpressCheckoutDetails($getExpressCheckoutDetailsReq);
            $orderId = null;
            if ($response->Ack == $paypalSettings["status_success"]) {
                $orderId = $response->GetExpressCheckoutDetailsResponseDetails->InvoiceID;

                //$this->notificacion($token);
                $last_order_id = $orderId;
                $order = Doctrine_Core::getTable("Transaction")->getTransaction($last_order_id);
                $this->_log("Pago", "Cancelado", "Usuario: " . $customer_in_session . ". Order ID: " . $order->getId());
                $this->redirect("profile/pedidos");
            } else {
                /* getExpressCheckout Failure */
                $msg = " | API Error Message : " . $response->Errors[0]->LongMessage;
                $this->_log("Pago", "ApiError", "Usuario: " . $customer_in_session . ". Order ID: " . $orderId . $msg);
                $this->redirect("paypal/processPaymentFailure");
            }
        } else {
            $this->redirect('@homepage');
        }
    }

    public function executeProcessPaymentFailure(sfWebRequest $request) {
        
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
        $custom_logger->info($step . " - " . $status . ". " . $msg);
    }

}