<?php

require_once __DIR__ . '/../../../lib/paypal/samples/PPBootStrap.php';

/**
 * paypal actions.
 *
 * @package    CarSharing
 * @subpackage paypal
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class paypalActions extends sfActions {

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

                    /* se convierte a dolares el cambio */
                    $conversionRate = floatval("0.00185185");
                    $this->finalPricePayPal = round(($finalPrice * $conversionRate), 2);

                    /* la transaccion es valida, inicializo paypal */
                    /* paypal init */
                    $paypalSettings = $this->getSettings();
                    /* details */
                    $setExpressCheckoutRequestDetails = new SetExpressCheckoutRequestDetailsType();
                    $setExpressCheckoutRequestDetails->ReturnURL = "http://www.magnetico.com.ar/jaguero/arriendas/frontend_dev.php/paypal/processPayment";
                    $setExpressCheckoutRequestDetails->CancelURL = "http://www.magnetico.com.ar/jaguero/arriendas/frontend_dev.php/paypal/processPaymentCanceled";

                    /* items */
                    $paymentDetailsArray = array();

                    /* foreach item */
                    $paymentDetails1 = new PaymentDetailsType();
                    $paymentDetails1->OrderTotal = new BasicAmountType("USD", $this->finalPricePayPal);
                    $paymentDetails1->OrderDescription = "Transacción de Arriendas.cl";
                    $paymentDetails1->PaymentAction = "Sale";
                    $paymentDetails1->InvoiceID = $order->getId();
                    $paymentDetails1->PaymentDetailsItem = new PaymentDetailsItemType();
                    $paymentDetails1->PaymentDetailsItem->Name = $this->carMarcaModel;
                    $paymentDetails1->PaymentDetailsItem->Quantity = 1;
                    $paymentDetails1->PaymentDetailsItem->Description = "Transacción de Arriendas.cl";
                    $paymentDetails1->PaymentDetailsItem->Amount = new BasicAmountType("USD", $this->finalPricePayPal);

                    /* seller */
                    $sellerDetails1 = new SellerDetailsType();
                    $sellerDetails1->PayPalAccountID = $paypalSettings["accountid"];
                    $paymentDetails1->SellerDetails = $sellerDetails1;

                    $paymentDetailsArray[0] = $paymentDetails1;
                    $setExpressCheckoutRequestDetails->PaymentDetails = $paymentDetailsArray;

                    $setExpressCheckoutReq = new SetExpressCheckoutReq();
                    $setExpressCheckoutReq->SetExpressCheckoutRequest = new SetExpressCheckoutRequestType($setExpressCheckoutRequestDetails);
                    $service = new PayPalAPIInterfaceServiceService($this->getConfig($paypalSettings));
                    $this->checkOutUrl = "#";
                    try {
                        $response = $service->SetExpressCheckout($setExpressCheckoutReq);
                        if ($response->Ack == $paypalSettings["status_success"]) {
                            //$this->checkOutUrl = "https://www.sandbox.paypal.com/cgi-bin/webscr?cmd=_express-checkout&token=" . $response->Token;
                            $this->checkOutUrl = $paypalSettings["url"] . "/cgi-bin/webscr?cmd=_express-checkout&token=" . $response->Token;
                        } else {
                            $msg = " | API Error Message : " . $response->Errors[0]->LongMessage;
                            $this->_log("Pago", "ApiError", "Usuario: " . $customer_in_session . ". Order ID: " . $order->getId() . $msg);
                            $this->redirect("paypal/processPaymentFailure");
                        }
                    } catch (Exception $ex) {
                        $msg = " | Exception : " . $ex->getMessage();
                        $this->_log("Exception", "Error", "Usuario: " . $customer_in_session . ". Order ID: " . $order->getId() . $msg);
                        $this->redirect("paypal/processPaymentFailure");
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
                                    $body = "<p>Hola $nameRenter:</p><p>Has pagado la reserva y esta ya esta confirmada.</p><p>Recuerda que debes llenar el FORMULARIO DE ENTREGA Y DEVOLUCIÓN del vehículo.</p><p>Puedes llenar el formulario <a href='http://www.arriendas.cl/profile/formularioEntrega/idReserve/$idReserve'>desde tu celular</a>.</p><pNo des inicio al arriendo si el auto tiene más daños que los declarados.</p><p>Datos del propietario:<br><br>Nombre: $nameOwner $lastnameOwner<br>Teléfono: $telephoneOwner<br>Correo: $emailOwner<br>Dirección: $addressCar</p><p>Los datos del arriendo y la versión escrita del formulario de entrega, se encuentran adjuntos en formato PDF.</p>";
                                    $message->setBody($mail->addFooter($body), 'text/html');
                                    $message->setTo($emailRenter);
                                    $message->attach(Swift_Attachment::newInstance($contrato, 'contrato.pdf', 'application/pdf'));
                                    $message->attach(Swift_Attachment::newInstance($formulario, 'formulario.pdf', 'application/pdf'));
                                    $message->attach(Swift_Attachment::newInstance($reporte, 'reporte.pdf', 'application/pdf'));
                                    $mailer->send($message);

                                    //mail Soporte
                                    $message = $mail->getMessage();
                                    $message->setSubject('Nueva reserva paga ' . idReserve . '');
                                    $body = "<p>Hola $nameRenter:</p><p>Has pagado la reserva y esta ya esta confirmada.</p><p>Recuerda que debes llenar el FORMULARIO DE ENTREGA Y DEVOLUCIÓN del vehículo.</p><p>Puedes llenar el formulario <a href='http://www.arriendas.cl/profile/formularioEntrega/idReserve/$idReserve'>desde tu celular</a>.</p><pNo des inicio al arriendo si el auto tiene más daños que los declarados.</p><p>Datos del propietario:<br><br>Nombre: $nameOwner $lastnameOwner<br>Teléfono: $telephoneOwner<br>Correo: $emailOwner<br>Dirección: $addressCar</p><p>Los datos del arriendo y la versión escrita del formulario de entrega, se encuentran adjuntos en formato PDF.</p>";
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
     * Executes index action
     *
     * @param sfRequest $request A request object
     */
    public function executeIndex(sfWebRequest $request) {
        //$this->forward('default', 'module');
    }

    public function executeOrderReview(sfWebRequest $request) {
        sfConfig::set('sf_web_debug', false);

        $PayPalNVP = new PayPalNVP();

        // Check to see if the Request object contains a variable named 'token'	
        $token = "";
        if (isset($_REQUEST['token'])) {

            $token = $_REQUEST['token'];
            $_SESSION["confirmtoken"] = $token;
        }

        // If the Request object contains the variable 'token' then it means that the user is coming from PayPal site.	
        if ($token != "" && $_SESSION["token"] == $token) {


            /*
              '------------------------------------
              ' Calls the GetExpressCheckoutDetails API call
              '
              ' The GetShippingDetails function is defined in PayPalFunctions.jsp
              ' included at the top of this file.
              '-------------------------------------------------
             */


            $resArray = $PayPalNVP->GetShippingDetails($token);
            $ack = strtoupper($resArray["ACK"]);
            if ($ack == "SUCCESS" || $ack == "SUCESSWITHWARNING") {

                $q = Doctrine::getTable('Transaction')->createQuery('t')->where('t.id = ? ', $_SESSION["tid"]);
                $t = $q->fetchOne();
                $this->price = $t->getPrice();
                $this->car = $t->getCar();

                /*
                  ' The information that is returned by the GetExpressCheckoutDetails call should be integrated by the partner into his Order Review
                  ' page
                 */
                $this->email = $resArray["EMAIL"]; // ' Email address of payer.
                $this->payerId = $resArray["PAYERID"]; // ' Unique PayPal customer account identification number.
                $this->payerStatus = $resArray["PAYERSTATUS"]; // ' Status of payer. Character length and limitations: 10 single-byte alphabetic characters.
                //$this->salutation			= $resArray["SALUTATION"]; // ' Payer's salutation.
                $this->firstName = $resArray["FIRSTNAME"]; // ' Payer's first name.
                //$this->middleName			= $resArray["MIDDLENAME"]; // ' Payer's middle name.
                $this->lastName = $resArray["LASTNAME"]; // ' Payer's last name.
                //$this->suffix				= $resArray["SUFFIX"]; // ' Payer's suffix.
                $this->cntryCode = $resArray["COUNTRYCODE"]; // ' Payer's country of residence in the form of ISO standard 3166 two-character country codes.
                //$this->business			= $resArray["BUSINESS"]; // ' Payer's business name.
                $this->shipToName = $resArray["PAYMENTREQUEST_0_SHIPTONAME"]; // ' Person's name associated with this address.
                $this->shipToStreet = $resArray["PAYMENTREQUEST_0_SHIPTOSTREET"]; // ' First street address.
                //$this->shipToStreet2		= $resArray["PAYMENTREQUEST_0_SHIPTOSTREET2"]; // ' Second street address.
                $this->shipToCity = $resArray["PAYMENTREQUEST_0_SHIPTOCITY"]; // ' Name of city.
                $this->shipToState = $resArray["PAYMENTREQUEST_0_SHIPTOSTATE"]; // ' State or province
                $this->shipToCntryCode = $resArray["PAYMENTREQUEST_0_SHIPTOCOUNTRYCODE"]; // ' Country code. 
                $this->shipToZip = $resArray["PAYMENTREQUEST_0_SHIPTOZIP"]; // ' U.S. Zip code or other country-specific postal code.
                $this->addressStatus = $resArray["ADDRESSSTATUS"]; // ' Status of street address on file with PayPal   
                //$this->invoiceNumber		= $resArray["INVNUM"]; // ' Your own invoice or tracking number, as set by you in the element of the same name in SetExpressCheckout request .
                //$this->phonNumber			= $resArray["PHONENUM"]; // ' Payer's contact telephone number. Note:  PayPal returns a contact telephone number only if your Merchant account profile settings require that the buyer enter one. 
            } else {
                //Display a user friendly Error on the page using any of the following error information returned by PayPal
                $ErrorCode = urldecode($resArray["L_ERRORCODE0"]);
                $ErrorShortMsg = urldecode($resArray["L_SHORTMESSAGE0"]);
                $ErrorLongMsg = urldecode($resArray["L_LONGMESSAGE0"]);
                $ErrorSeverityCode = urldecode($resArray["L_SEVERITYCODE0"]);

                echo "GetExpressCheckoutDetails API call failed. ";
                echo "Detailed Error Message: " . $ErrorLongMsg;
                echo "Short Error Message: " . $ErrorShortMsg;
                echo "Error Code: " . $ErrorCode;
                echo "Error Severity Code: " . $ErrorSeverityCode;
            }
        }
    }

    public function executeConfirm(sfWebRequest $request) {



        $PayPalNVP = new PayPalNVP();


        if ($_SESSION["token"] == $_SESSION["confirmtoken"]) {
            /*
              '------------------------------------
              ' The paymentAmount is the total value of
              ' the shopping cart, that was set
              ' earlier in a session variable
              ' by the shopping cart page
              '------------------------------------
             */


            $finalPaymentAmount = $_SESSION["Payment_Amount"];

            /*
              '------------------------------------
              ' Calls the DoExpressCheckoutPayment API call
              '
              ' The ConfirmPayment function is defined in the file PayPalFunctions.jsp,
              ' that is included at the top of this file.
              '-------------------------------------------------
             */

            $resArray = $PayPalNVP->ConfirmPayment($finalPaymentAmount);
            $ack = strtoupper($resArray["ACK"]);
            if ($ack == "SUCCESS" || $ack == "SUCCESSWITHWARNING") {

                $q = Doctrine::getTable('Transaction')->createQuery('t')->where('t.id = ? ', $_SESSION["tid"]);
                $t = $q->fetchOne();
                $t->setFechaPago(strftime("%Y%m%d%H%M%S"));
                $t->setCompleted(true);
                $t->save();


                /*
                  '********************************************************************************************************************
                  '
                  ' THE PARTNER SHOULD SAVE THE KEY TRANSACTION RELATED INFORMATION LIKE
                  '                    transactionId & orderTime
                  '  IN THEIR OWN  DATABASE
                  ' AND THE REST OF THE INFORMATION CAN BE USED TO UNDERSTAND THE STATUS OF THE PAYMENT
                  '
                  '********************************************************************************************************************
                 */

                $transactionId = $resArray["PAYMENTINFO_0_TRANSACTIONID"]; // ' Unique transaction ID of the payment. Note:  If the PaymentAction of the request was Authorization or Order, this value is your AuthorizationID for use with the Authorization & Capture APIs. 
                $transactionType = $resArray["PAYMENTINFO_0_TRANSACTIONTYPE"]; //' The type of transaction Possible values: l  cart l  express-checkout 
                $paymentType = $resArray["PAYMENTINFO_0_PAYMENTTYPE"];  //' Indicates whether the payment is instant or delayed. Possible values: l  none l  echeck l  instant 
                $orderTime = $resArray["PAYMENTINFO_0_ORDERTIME"];  //' Time/date stamp of payment
                $amt = $resArray["PAYMENTINFO_0_AMT"];  //' The final amount charged, including any shipping and taxes from your Merchant Profile.
                $currencyCode = $resArray["PAYMENTINFO_0_CURRENCYCODE"];  //' A three-character currency code for one of the currencies listed in PayPay-Supported Transactional Currencies. Default: USD. 
                $feeAmt = $resArray["PAYMENTINFO_0_FEEAMT"];  //' PayPal fee amount charged for the transaction
                //$settleAmt			= $resArray["PAYMENTINFO_0_SETTLEAMT"];  //' Amount deposited in your PayPal account after a currency conversion.
                $taxAmt = $resArray["PAYMENTINFO_0_TAXAMT"];  //' Tax charged on the transaction.
                //$exchangeRate		= $resArray["PAYMENTINFO_0_EXCHANGERATE"];  //' Exchange rate if a currency conversion occurred. Relevant only if your are billing in their non-primary currency. If the customer chooses to pay with a currency other than the non-primary currency, the conversion occurs in the customer's account.

                /*
                  ' Status of the payment:
                  'Completed: The payment has been completed, and the funds have been added successfully to your account balance.
                  'Pending: The payment is pending. See the PendingReason element for more information.
                 */

                $paymentStatus = $resArray["PAYMENTINFO_0_PAYMENTSTATUS"];

                /*
                  'The reason the payment is pending:
                  '  none: No pending reason
                  '  address: The payment is pending because your customer did not include a confirmed shipping address and your Payment Receiving Preferences is set such that you want to manually accept or deny each of these payments. To change your preference, go to the Preferences section of your Profile.
                  '  echeck: The payment is pending because it was made by an eCheck that has not yet cleared.
                  '  intl: The payment is pending because you hold a non-U.S. account and do not have a withdrawal mechanism. You must manually accept or deny this payment from your Account Overview.
                  '  multi-currency: You do not have a balance in the currency sent, and you do not have your Payment Receiving Preferences set to automatically convert and accept this payment. You must manually accept or deny this payment.
                  '  verify: The payment is pending because you are not yet verified. You must verify your account before you can accept this payment.
                  '  other: The payment is pending for a reason other than those listed above. For more information, contact PayPal customer service.
                 */

                $pendingReason = $resArray["PAYMENTINFO_0_PENDINGREASON"];

                /*
                  'The reason for a reversal if TransactionType is reversal:
                  '  none: No reason code
                  '  chargeback: A reversal has occurred on this transaction due to a chargeback by your customer.
                  '  guarantee: A reversal has occurred on this transaction due to your customer triggering a money-back guarantee.
                  '  buyer-complaint: A reversal has occurred on this transaction due to a complaint about the transaction from your customer.
                  '  refund: A reversal has occurred on this transaction because you have given the customer a refund.
                  '  other: A reversal has occurred on this transaction due to a reason not listed above.
                 */

                $reasonCode = $resArray["PAYMENTINFO_0_REASONCODE"];

                //return sfView::NONE;  
                $this->redirect('profile/transactions');
            } else {

                $this->getUser()->setFlash('show', true);
                $this->getUser()->setFlash('msg', 'No tiene saldo para realizar el pago');
                $this->forward('paypal', 'orderReview');

                //Display a user friendly Error on the page using any of the following error information returned by PayPal
                $ErrorCode = urldecode($resArray["L_ERRORCODE0"]);
                $ErrorShortMsg = urldecode($resArray["L_SHORTMESSAGE0"]);
                $ErrorLongMsg = urldecode($resArray["L_LONGMESSAGE0"]);
                $ErrorSeverityCode = urldecode($resArray["L_SEVERITYCODE0"]);

                echo "GetExpressCheckoutDetails API call failed. ";
                echo "Detailed Error Message: " . $ErrorLongMsg;
                echo "Short Error Message: " . $ErrorShortMsg;
                echo "Error Code: " . $ErrorCode;
                echo "Error Severity Code: " . $ErrorSeverityCode;
            }
        } else {

            $this->getUser()->setFlash('show', true);
            $this->getUser()->setFlash('msg', 'No se a ingresa');
            $this->forward('paypal', 'orderReview');
        }
    }

    public function executeCheckout(sfWebRequest $request) {
        sfConfig::set('sf_web_debug', false);

        $tid = $request->getParameter('tid');

        $q = Doctrine::getTable('Transaction')->createQuery('t')->where('t.id = ? ', $tid);
        $t = $q->fetchOne();

        $t->getPrice();


        $PayPalNVP = new PayPalNVP();

        //'------------------------------------
        //' The paymentAmount is the total value of 
        //' the shopping cart, that was set 
        //' earlier in a session variable 
        //' by the shopping cart page
        //'------------------------------------

        $_SESSION["Payment_Amount"] = $t->getPrice();

        $paymentAmount = $_SESSION["Payment_Amount"];

        //'------------------------------------
        //' The currencyCodeType and paymentType 
        //' are set to the selections made on the Integration Assistant 
        //'------------------------------------
        $currencyCodeType = "USD";
        $paymentType = "Sale";

        //'------------------------------------
        //' The returnURL is the location where buyers return to when a
        //' payment has been succesfully authorized.
        //'
        //' This is set to the value entered on the Integration Assistant 
        //'------------------------------------
        $returnURL = "http://" . $_SERVER['SERVER_NAME'] . $this->getController()->genUrl('paypal/orderReview');

        //'------------------------------------
        //' The cancelURL is the location buyers are sent to when they hit the
        //' cancel button during authorization of payment during the PayPal flow
        //'
        //' This is set to the value entered on the Integration Assistant 
        //'------------------------------------
        $cancelURL = "http://" . $_SERVER['SERVER_NAME'] . $this->getController()->genUrl('profile/transactions');

        //'------------------------------------
        //' Calls the SetExpressCheckout API call
        //'
        //' The CallShortcutExpressCheckout function is defined in the file PayPalFunctions.php,
        //' it is included at the top of this file.
        //'-------------------------------------------------
        $resArray = $PayPalNVP->CallShortcutExpressCheckout($paymentAmount, $currencyCodeType, $paymentType, $returnURL, $cancelURL);

        $ack = strtoupper($resArray["ACK"]);
        if ($ack == "SUCCESS" || $ack == "SUCCESSWITHWARNING") {
            $_SESSION["token"] = $resArray["TOKEN"];
            $_SESSION["tid"] = $tid;
            $PayPalNVP->RedirectToPayPal($resArray["TOKEN"]);
        } else {
            //Display a user friendly Error on the page using any of the following error information returned by PayPal
            $ErrorCode = urldecode($resArray["L_ERRORCODE0"]);
            $ErrorShortMsg = urldecode($resArray["L_SHORTMESSAGE0"]);
            $ErrorLongMsg = urldecode($resArray["L_LONGMESSAGE0"]);
            $ErrorSeverityCode = urldecode($resArray["L_SEVERITYCODE0"]);

            echo "SetExpressCheckout API call failed. ";
            echo "Detailed Error Message: " . $ErrorLongMsg;
            echo "Short Error Message: " . $ErrorShortMsg;
            echo "Error Code: " . $ErrorCode;
            echo "Error Severity Code: " . $ErrorSeverityCode;
        }


        return sfView::NONE;
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
        $rawSettings = sfYaml::load(dirname(dirname(__FILE__)) . "/config/paypal.yml");
        $env = $rawSettings["settings"]["enviroment"];
        return $rawSettings["settings"][$env];
    }

    protected function _log($step, $status, $msg) {

        $logPath = sfConfig::get('sf_log_dir') . '/paypal.log';
        $custom_logger = new sfFileLogger(new sfEventDispatcher(), array('file' => $logPath));
        $custom_logger->info($step . " - " . $status . ". " . $msg);
    }

}
