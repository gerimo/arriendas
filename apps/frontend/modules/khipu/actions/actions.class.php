<?php

require_once sfConfig::get('sf_lib_dir') . '/vendor/khipu/KhipuService.php';

/**
 * khipu actions.
 *
 * @package    CarSharing
 * @subpackage khipu
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class khipuActions extends sfActions {

    public function executeConfirmPayment(sfWebRequest $request) {
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

        $khipuService = new KhipuService($settings["receiver_id"], $settings["secret"], $settings["notification-validation-url"]);
        $response = $khipuService->notificationValidation($data);

        if ($response == 'VERIFIED' && $data["receiver_id"] == $settings["receiver_id"]) {
            /* Los parametros son correctos, ahora falta verificar que transacion_id, custom, subject y amount correspondan al pedido */
            $this->_log("NotifyPayment", "INFO", "cobro verificado");
            $this->_log("NotifyPayment", "INFO", "respuesta: ".  serialize($response));
            $order = Doctrine_Core::getTable("Transaction")->getTransaction($data["transaction_id"]);
            $this->idReserva = $order->getReserveId();
            $reserve = Doctrine_Core::getTable('reserve')->findOneById($this->idReserva);

            /* //cancel other reserves not paid for the same rental dates */
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

                /* verifica que la reserva no esté completa */
                if (!$order->getCompleted()) {
                    
                    $functions = new Functions;
                    $functions->generarNroFactura($reserve, $order);
                    
                    /* actualiza el estado completed */
                    $order->setCompleted(true);
                    $order->save();

                    /* envío de mail */
                    require sfConfig::get('sf_app_lib_dir') . "/mail/mail.php";


                    /* pedidos de reserva pagado (propietario) */
                    $mail = new Email();
                    $mailer = $mail->getMailer();

                    if ($order->getImpulsive()) {

                        $url = $this->generateUrl("aprobarReserve", array('idReserve' => $reserve->getId(), 'idUser' => $reserve->getCar()->getUserId()), TRUE);

                        $subject = "¡Has recibido un pago! Apruébalo ahora";

                        $body = "";
                        $body .= "<p>Hola $nameOwner,</p>";
                        $body .= "<p>Has recibido un pago por ".number_format(($reserve->getPrice()), 0, ',', '.').", desde ".$reserve->getFechaInicio()." ".$reserve->getHoraInicio()." hasta ".$reserve->getFechaTermino()." ".$reserve->getHoraTermino().".</p>";
                        $body .= "<p>Si no apruebas la reserva cuanto antes otro dueño podría ganar la reserva.";
                        $body .= "<p>Para recibir tu pago debes aprobar la reserva haciendo <a href='$url'>click aquí</a></p>";

                    } else {

                        $subject = "¡El arrendatario ha pagado la reserva!";

                        $body = "";
                        $body .= "<p>Hola $nameOwner,</p>";
                        $body .= "<p>El arrendatario ha pagado la reserva!</p>";
                        $body .= "<p>Recuerda que debes llenar el FORMULARIO DE ENTREGA Y DEVOLUCIÓN del vehículo.</p>";
                        $body .= "<p>Puedes llenar el formulario <a href='http://www.arriendas.cl/profile/formularioEntrega/idReserve/$idReserve'>desde tu celular</a>.</p>";
                        $body .= "<p>No des inicio al arriendo si el auto tiene más daños que los declarados.</p>";
                        $body .= "<p>Datos del propietario:<br><br>Nombre: $nameRenter $lastnameRenter<br>Teléfono: $telephoneRenter<br>Correo: $emailRenter</p>";
                        $body .= "<p>Los datos del arriendo y la versión escrita del formulario de entrega, se encuentran adjuntos en formato PDF.</p>";
                    }

                    $message = $mail->getMessage();
                    $message->setSubject($subject);
                    $message->setBody($mail->addFooter($body), 'text/html');
                    $message->setTo($emailOwner);
                    $functions = new Functions;
                    $formulario = $functions->generarFormulario(NULL, $tokenReserve);
                    $reporte = $functions->generarReporte($idCar);
                    $contrato = $functions->generarContrato($tokenReserve);
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


                    /* pedidos de reserva pagado (arrendatario) */
                    $message = $mail->getMessage();
                    $message->setSubject('La reserva ha sido pagada!');
                    $body = "<p>Hola $nameRenter:</p><p>Has pagado la reserva y esta ya esta confirmada.</p><p>Recuerda que debes llenar el FORMULARIO DE ENTREGA Y DEVOLUCIÓN del vehículo.</p><p>No des inicio al arriendo si el auto tiene más daños que los declarados.</p><p>Datos del propietario:<br><br>Nombre: $nameOwner $lastnameOwner<br>Teléfono: $telephoneOwner<br>Correo: $emailOwner<br>Dirección: $addressCar</p><p>Los datos del arriendo y la versión escrita del formulario de entrega, se encuentran adjuntos en formato PDF.</p>";
                    $message->setBody($mail->addFooter($body), 'text/html');
                    $message->setTo($emailRenter);
                    $message->attach(Swift_Attachment::newInstance($contrato, 'contrato.pdf', 'application/pdf'));
                    $message->attach(Swift_Attachment::newInstance($formulario, 'formulario.pdf', 'application/pdf'));
                    $message->attach(Swift_Attachment::newInstance($reporte, 'reporte.pdf', 'application/pdf'));
                    $pagare = $functions->generarPagare($tokenReserve);
                    $message->attach(Swift_Attachment::newInstance($pagare, 'pagare.pdf', 'application/pdf'));
                    $mailer->send($message);

                    /* mail Soporte */
                    $message = $mail->getMessage();
                    $message->setSubject('Nueva reserva paga ' . idReserve . '');
                    $body = "<p>Hola $nameRenter:</p><p>Has pagado la reserva y esta ya esta confirmada.</p><p>Recuerda que debes llenar el FORMULARIO DE ENTREGA Y DEVOLUCIÓN del vehículo.</p><p>No des inicio al arriendo si el auto tiene más daños que los declarados.</p><p>Datos del propietario:<br><br>Nombre: $nameOwner $lastnameOwner<br>Teléfono: $telephoneOwner<br>Correo: $emailOwner<br>Dirección: $addressCar</p><p>Los datos del arriendo y la versión escrita del formulario de entrega, se encuentran adjuntos en formato PDF.</p>";
                    $body .= "<p>En caso de siniestro debes dejar constancia en la comisaría más cercana INMEDITAMENTE y llamar al (02)2333 3714.</p>";
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

                    /* crea la fila calificaciones habilitada para la fecha de término de reserva + 2 horas (solo si no es una extension de otra reserva) */
                    if (!$reserve->getIdPadre()) {

                        $fecha = $reserve->getFechaHabilitacionRating();
                        $idOwner = $reserve->getIdOwner();
                        $idRenter = $reserve->getIdRenter();

                        $rating = new Rating();
                        $rating->setFechaHabilitadaDesde($fecha);
                        $rating->setIdOwner($idOwner);
                        $rating->setIdRenter($idRenter);
                        $rating->save();

                        /* actualiza rating_id en la tabla Reserve */
                        $ratingId = $rating->getId();
                        $reserve->setFechaPago(strftime("%Y-%m-%d %H:%M:%S"));
                        $reserve->setRatingId($ratingId);
                        $reserve->save();
                    }

                    /* almacena $idReserve en la tabla mail calificaciones */
                    $reserve->encolarMailCalificaciones();
                }
            }
        } else {
            $this->_log("NotifyPayment", "ERROR", "Hubo un error en el proceso de verificacion.");
        }
        die();
    }

    public function executePaymentInformation(sfWebRequest $request) {
        $customer_in_session = $this->getUser()->getAttribute('userid');
        if ($customer_in_session) {

            $settings = $this->getSettings();
            $khipuService = new KhipuService($settings["receiver_id"], $settings["secret"], $settings["payment-status-url"]);

            $khipuTransaction = $this->getUser()->getAttribute("khipu-transaction");

            $data = array('receiver_id' => $settings["receiver_id"],
                'payment_id' => $khipuTransaction["payment-id"]
            );
            $msg = "Call khipu with url => " . $settings["payment-status-url"] . ", payment_id:" . $data["payment_id"];
            $this->_log("Call", "info", $msg);

            $response = $khipuService->paymentStatus($data);
            switch ($response->status) {
                case "done":
                    $this->paymentMsg = "El pago ha sido realizado.";
                    /* update transaction info */
                    $khipuTransaction["status"] = "done";
                    $this->getUser()->setAttribute("khipu-transaction", $khipuTransaction);
                    $this->forward("khipu", "processPayment");
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
        $customer_in_session = $this->getUser()->getAttribute('userid');

        if ($customer_in_session) {
            $this->redirect("profile/pedidos");
        } else {
            $this->redirect('@homepage');
        }
    }

    public function executeProcessPaymentFailure(sfWebRequest $request) {
        
    }

    /**
     * Get environment khipu settings.
     * @return array setttings.
     */
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

}
