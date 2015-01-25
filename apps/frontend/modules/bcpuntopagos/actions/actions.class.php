<?php

/**
 * bcpuntopagos actions.
 *
 * @author     BetterChoice Fabrica de Software
 * @version    SVN: $Id: actions.class.php 23810 2012-10-15 11:07:44Z Israel Gonzalez $
 * Permitido instalar solo bajo condiciones contractuales
 * Queda prohibida explicitamente su distribucion
 */
class bcpuntopagosActions extends sfActions {

    /**
     * Presenta el formulario con los medidos de pago de Punto Pagos
     *
     * @param sfRequest $request A request object
     */
    public function enviarCorreoTransferenciaBancaria() {
        $idUsuario = sfContext::getInstance()->getUser()->getAttribute('userid');
        $usuario = Doctrine_Core::getTable('user')->findOneById($idUsuario);

        $correo = $usuario->getEmail();
        $name = $usuario->getFirstname();

        require sfConfig::get('sf_app_lib_dir') . "/mail/mail.php";
        $mail = new Email();
        $mail->setSubject('Realizar Depósito en Garantía');
        $mailBody = "<p>Hola $name</p><p>Realiza el depósito en garantía por transferecia bancaria de <b>$180.000</b>:</p><p><ul><li><b>Banco BCI</b></li><li>Cuenta Corriente: <b>70107459</b></li><li>Rut: <b>76208249-7</b></li><li><b>Rimoldi SPA</b></li><li><b>soporte@arriendas.cl</b></li></ul></p>";
        $mailBody .= "<p>Si pagas con Khipu, el deposito en garantía se debitará automáticamente.</p>";
        $mail->setBody($mailBody);
        $mail->setTo($correo);
        $mail->setCc('soporte@arriendas.cl');
        $mail->submit();
    }

    public function executeIndex(sfWebRequest $request) {


        $this->carMarcaModel = $request->getParameter("carMarcaModel");
        $this->duracionReserva = $request->getParameter("duracionReserva");
        $montoTotalPagoPorDia = $request->getParameter("duracionReservaPagoPorDia");
        $this->montoTotalPagoPorDia = $montoTotalPagoPorDia;


        $this->deposito = $request->getParameter("deposito");
        $this->montoDeposito = 0;
        if ($this->deposito == "depositoGarantia") {
            //$deposito = Doctrine_Core::getTable("liberacionDeposito")->findById(2);
            $this->montoDeposito = sfConfig::get("app_monto_garantia");
            $this->enviarCorreoTransferenciaBancaria();
        } else if ($this->deposito == "pagoPorDia") {
            //$deposito = Doctrine_Core::getTable("liberacionDeposito")->findById(1);
            $this->montoDeposito = $montoTotalPagoPorDia;
        }

        $customer_in_session = $this->getUser()->getAttribute('userid');

        if ($customer_in_session) {

            $this->ppId = null;

            if (!$request->hasParameter("id")) {

                $this->error = 'No se ha recibido un codigo de reserva';
            } else {

                try {

                    $order = Doctrine_Core::getTable("Transaction")->getTransactionByReserve($request->getParameter("id"));
                    $idReserve = $order->getReserveId();
                    $reserve = Doctrine_Core::getTable('reserve')->findOneById($idReserve);
                    if ($this->deposito == "depositoGarantia") {
                        $reserve->setLiberadoDeGarantia(0);
                        $reserve->setMontoLiberacion($this->montoDeposito);
                    } else if ($this->deposito == "pagoPorDia") {
                        $reserve->setLiberadoDeGarantia(1);
                        $reserve->setMontoLiberacion($this->montoDeposito);
                    }
                    $reserve->save();

                    $this->hasDiscountFB = $order->getDiscountfb();
                    $this->priceMultiply = 1 - (0.05 * $order->getDiscountfb());


                    if ($order != false) {

                        $this->ppId = $order->getId();
                        $this->ppMonto = $order->getPrice(); //reemplazar por metodo getMonto
                        $this->ppIdReserva = $request->getParameter("id");

                        if ($this->ppMonto <= 0 || $this->ppMonto == null) {

                            $this->error = 'Monto invalido o cero en transaccion #' . $this->ppId . '. No es posible procesar en Punto Pagos.';
                            $this->_log("Formulario", "Error", "Usuario: " . $customer_in_session . ". Order ID: " . $order->getId() . ". Error: " . $this->error);
                            $this->ppId = null;
                        }
                    } else {

                        $this->error = 'Orden no existe o ya esta procesada para la reserva: ' . $request->getParameter("id");
                        $this->_log("Formulario", "Error", "Usuario: " . $customer_in_session . ". Error: " . $this->error);
                        $this->ppId = null;
                    }
                } catch (Exception $e) {

                    $this->error = 'Ha ocurrido un error tratando de procesar el codigo de reserva indicado.<br/>' . $e->getMessage();
                    $this->_log("Formulario", "Error", "Usuario: " . $customer_in_session . ". Error: " . $this->error);
                    $this->ppId = null;
                }
            }
        } else {
            $this->redirect('@homepage');
        }
    }

    /**
     * Crea la transaccion hacia punto pagos segun datos de config/puntopagos.yml
     *
     * @param sfRequest $request A request object
     */
    public function executeCreacion(sfWebRequest $request) {
        $this->carMarcaModel = $request->getParameter("carMarcaModel");
        $this->duracionReserva = $request->getParameter("duracionReserva");

        $customer_in_session = $this->getUser()->getAttribute('userid');

        if ($customer_in_session) {

            if (!$request->hasParameter("id") || $request->getParameter("id") == null) {

                $this->getUser()->setFlash('error', 'No se ha recibido un codigo de orden para la creacion en Punto Pagos');
                $this->redirect("bcpuntopagos/index");
            } else {

                if (!$request->hasParameter("pp_medio_pago") || $request->getParameter("pp_medio_pago") == null) {

                    $this->getUser()->setFlash('error', 'Debe especificar un medio de pago');
                    $this->redirect("bcpuntopagos/index?id=" . $request->getParameter("idReserva"));
                }
//                if($request->getParameter("pp_medio_pago") == "3"){
//                    $this->forward("webpay","confirmPayment");
//                }
                if($request->getParameter("pp_medio_pago") == "20"){
                    $this->forward("paypal","confirmPayment");
                }
                if($request->getParameter("pp_medio_pago") == "21"){
                    $this->forward("khipu","confirmPayment");
                }

                $order = Doctrine_Core::getTable("Transaction")->getTransaction($request->getParameter('id'));
                $idReserve = $order->getReserveId();
                $reserve = Doctrine_Core::getTable('reserve')->findOneById($idReserve);

                $opcionLiberacion = $reserve->getLiberadoDeGarantia();
                if ($opcionLiberacion == 0)
                    $montoLiberacion = 0;
                else if ($opcionLiberacion == 1)
                    $montoLiberacion = $reserve->getMontoLiberacion();

                $finalPrice = $order->getPrice() - $order->getDiscountamount() + $montoLiberacion;
                $finalPrice = number_format($finalPrice, 0, '.', '');

                if ($finalPrice > 0) {

                    $conf = $this->getConfiguration();
                    $conf = $conf["betterchoice"]["puntopagos"];
                    $enviroment = $conf["enviroment"];

                    //Parameters configured in config/puntopagos.yml file:
                    $PUNTOPAGOS_URL = $conf[$enviroment]["url"];
                    $PUNTOPAGOS_SECRET = $conf[$enviroment]["secret"];
                    $PUNTOPAGOS_KEY = $conf[$enviroment]["key"];

                    //The three parameters for creation in Punto Pagos: ammount, payment method id and transaction id
                    $ammount = $finalPrice;
                    $payment_id = $request->getParameter('pp_medio_pago');
                    $trx_id = $order->getId();

                    //Execution itself about punto pagos creation process
                    $funcion = "transaccion/crear";
                    $ammount_str = number_format($ammount, 2, '.', '');

                    $http_request = "";
                    $http_request .= $data_creation_step = '{"trx_id":"' . $trx_id . '","medio_pago":' . $payment_id . ',"monto":' . $ammount_str . '}';
                    $http_request .= "<br/><br/>";

                    $fecha = gmdate("D, d M Y H:i:s", time()) . " GMT";
                    $mensaje = $funcion . "\n" . $trx_id . "\n" . $ammount_str . "\n" . $fecha;
                    $signature = base64_encode(hash_hmac('sha1', $mensaje, $PUNTOPAGOS_SECRET, true));
                    $firma = "PP " . $PUNTOPAGOS_KEY . ":" . $signature;

                    $header = array();
                    $http_request .= $header[] = "Accept: application/json;";
                    $http_request .= $header[] = "Accept-Charset: utf-8;";
                    $http_request .= $header[] = "Accept-Language: en-us,en;q=0.5";
                    $http_request .= $header[] = "Content-type: application/json";
                    $http_request .= $header[] = "Fecha: " . $fecha;
                    $http_request .= $header[] = "Autorizacion: " . $firma;

                    $url_pp = $PUNTOPAGOS_URL . "/transaccion/crear";

                    $curl = curl_init($url_pp);

                    curl_setopt($curl, CURL_VERSION_SSL, "SSL_VERSION_SSLv3");
                    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
                    curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
                    curl_setopt($curl, CURL_HTTP_VERSION_1_1, 1);
                    curl_setopt($curl, CURLOPT_POST, 1);
                    curl_setopt($curl, CURLOPT_POSTFIELDS, $data_creation_step);
                    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

                    $http_response = curl_exec($curl);
                    $http_info = curl_getinfo($curl);
                    curl_close($curl);

                    $response = json_decode($http_response);
                    $codeHttp = $http_info["http_code"];

                    if ($codeHttp == 200) {

                        if ($response->token != "") {
                            //We store in Session Last Order Id in Punto Pagos. We'll need after payment execution itself
                            $this->getUser()->setAttribute('PP_LAST_ORDER_ID', $order->getId());
                            $this->_log("Creacion", "Exito", "Usuario: " . $customer_in_session . ". Order ID: " . $order->getId() . ". Token Punto Pagos: " . $response->token);
                            $this->redirect($PUNTOPAGOS_URL . '/transaccion/procesar/' . $response->token);
                        } else {

                            $this->_log("Creacion", "Error", "Usuario: " . $customer_in_session . ". Order ID: " . $order->getId() . ". Error: " . $response->respuesta . " - " . $response->error);
                            $this->getUser()->setFlash('error', "Ha ocurrido un error. Error (" . $response->respuesta . "): " . $response->error);

                            $this->redirect('bcpuntopagos/index?id=' . $request->getParameter("idReserva"));
                        }
                    } else {

                        $this->_log("Creacion", "Error", "Usuario: " . $customer_in_session . ". Order ID: " . $order->getId() . ". Error: Conexion no exitosa hacia: " . $url_pp . ". Header de respuesta: " . json_encode($http_info));
                        $this->getUser()->setFlash('error', "Fallo al tratar de recibir respuesta de " . $url_pp . ". Header de respuesta: " . json_encode($http_info));

                        $this->redirect('bcpuntopagos/index?id=' . $request->getParameter("idReserva"));
                    }
                }
            }
        } else {
            $this->redirect('@homepage');
        }
    }

    /**
     * Process suceess by verifying notification step to Punto Pagos and change status to order
     *
     * @param sfRequest $request A request object
     */
    public function executeExito(sfWebRequest $request) {

        $customer_in_session = $this->getUser()->getAttribute('userid');

        if ($customer_in_session) {

            $token = $request->getParameter('t');
            $token2 = $request->getParameter('lr');

            if ($token) {
                $this->notificacion($token);
                $last_order_id = $this->getUser()->getAttribute('PP_LAST_ORDER_ID');
                $conf = $this->getConfiguration();
                $conf = $conf["betterchoice"]["puntopagos"];
                $enviroment = $conf["enviroment"];
                $STATE_SUCCESSFULL = $conf[$enviroment]["status_success"];
                $STATE_ERROR = $conf[$enviroment]["status_error"];
            } else {
                $last_order_id = $token2;
            }

            $order = Doctrine_Core::getTable("Transaction")->getTransaction($last_order_id);

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
            if ($opcionLiberacion == 0)
                $montoLiberacion = 0;
            else if ($opcionLiberacion == 1)
                $montoLiberacion = $reserve->getMontoLiberacion();

            $finalPrice = $order->getPrice() + $montoLiberacion;
            if ($finalPrice > 0) {

                $this->_log("Pago", "Exito", "Usuario: " . $customer_in_session . ". Order ID: " . $order->getId());
                Doctrine_Core::getTable("Transaction")->successTransaction($last_order_id, $token, $STATE_SUCCESSFULL, 1);

//							$this->logMessage('exito', 'err');

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
                    
                    $renterUser = $reserve->getUser();
                    if (!is_null($renterUser->getDriverLicenseFile())) {
                        $filepath = $renterUser->getDriverLicenseFile();
                        if (is_file($filepath)) {
                            $message->attach(Swift_Attachment::fromPath($renterUser->getDriverLicenseFile())->setFilename("LicenciaArrendatario-".$renterUser->getLicenceFileName()));
                        }
                    }
                    
                    $mailer->send($message);


                    //pedidos de reserva pagado (arrendatario)
                    $message = $mail->getMessage();
                    $message->setSubject('La reserva ha sido pagada!');
                    $body = "<p>Hola $nameRenter:</p><p>Has pagado la reserva y esta ya esta confirmada.</p><p>Recuerda que debes llenar el FORMULARIO DE ENTREGA Y DEVOLUCIÓN del vehículo.</p><p>No des inicio al arriendo si el auto tiene más daños que los declarados.</p><p>Datos del propietario:<br><br>Nombre: $nameOwner $lastnameOwner<br>Teléfono: $telephoneOwner<br>Correo: $emailOwner<br>Dirección: $addressCar</p><p>Los datos del arriendo y la versión escrita del formulario de entrega, se encuentran adjuntos en formato PDF.</p>";
                    $body .= "<p>En caso de siniestro debes dejar constancia en la comisaría más cercana INMEDITAMENTE y llamar al (02)2333 3714.</p>";
                    $message->setBody($mail->addFooter($body), 'text/html');
                    $message->setTo($emailRenter);
                    $message->attach(Swift_Attachment::newInstance($contrato, 'contrato.pdf', 'application/pdf'));
                    $message->attach(Swift_Attachment::newInstance($formulario, 'formulario.pdf', 'application/pdf'));
                    $message->attach(Swift_Attachment::newInstance($reporte, 'reporte.pdf', 'application/pdf'));
                    
                    $pagare = $functions->generarPagare($tokenReserve);
                    $message->attach(Swift_Attachment::newInstance($pagare, 'pagare.pdf', 'application/pdf'));
                                    
                    $mailer->send($message);

                    //mail Soporte
                    $message = $mail->getMessage();
                    $message->setSubject('Nueva reserva paga ' . $idReserve . '');
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

                    // mail si posee ip ambigua
                    try {

                        $renter = $reserve->getRenter();
                        $owner  = $reserve->getOwner();
                        $ip = $renter->getTrackedIps();

                        if ($renter->getIpAmbigua()) {

                            // si posee la ip ambigua se envia el correo
                            $body = "" .
                                "<p>El pago ID {$order->getId()}, reserva ID {$reserve->getId()} tiene las IP {$ip} en común</p>" .
                                "<p>Datos del propietario:".
                                    "<br><br>Nombre: {$owner->getFirstname()} {$owner->getLastname()}".
                                    "<br>Teléfono: {$owner->getTelephone()}".
                                    "<br>Correo: {$owner->getEmail()}".
                                    "<br>Id: {$owner->getId()}</p>".
                                "<p>Datos del arrendatario:".
                                    "<br><br>Nombre: {$renter->getFirstname()} {$renter->getLastname()}".
                                    "<br>Teléfono: {$renter->getTelephone()}".
                                    "<br>Correo: {$renter->getEmail()}".
                                    "<br>Id: {$renter->getId()}</p>".
                                "";
                            $message = $mail->getMessage();
                            $message->setSubject('Reserva Paga con IP coincidente ');
                            $message->setBody($mail->addFooter($body), 'text/html');
                            $message->setTo("soporte@arriendas.cl");
                            $mailer->send($message);
                        }
                    } catch (Exception $ex) {
                        error_log($ex->getMessage());
                    }

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
                Doctrine_Core::getTable("Transaction")->successTransaction($last_order_id, $token, $STATE_ERROR, 1);
            }
        } else {
            $this->redirect('@homepage');
        }
    }
    
    
    /**
     * Fuerza a mostrar pagina de pago exitoso para casos de pagos por fuera de Arriendas
     * (por ej. Khipu)
     *
     * @param sfRequest $request A request object
     */
    public function executeShowExito(sfWebRequest $request) {
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
                    $comunaId = $propietarioClass->getComuna();
                    $comunaClass = Doctrine_Core::getTable('comunas')->findOneByCodigoInterno($comunaId);
                    
                    
                    $this->nameOwner      = $reserve->getNameOwner();
                    $this->emailOwner     = $reserve->getEmailOwner();
                    $this->lastnameOwner  = $reserve->getLastnameOwner();
                    $this->telephoneOwner = $reserve->getTelephoneOwner();
                    $this->tokenReserve   = $reserve->getToken();
                    $this->comunaOwner    = $comunaClass->getNombre();
                    $this->addressOwner   =$propietarioClass->getAddress();

                    $this->durationFrom   = $reserve->getFechaInicio2();
                    $this->durationTo     = $reserve->getFechaTermino2();

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

    /**
     * Cambia orden a status fallido e Informa a usuario en pagina de exito
     *
     * @param sfRequest $request A request object
     */
    public function executeFracaso(sfWebRequest $request) {
        $customer_in_session = $this->getUser()->getAttribute('userid');

        if ($customer_in_session) {

            $token = $request->getParameter('t');
            $this->notificacion($token);

            $last_order_id = $this->getUser()->getAttribute('PP_LAST_ORDER_ID');
            $conf = $this->getConfiguration();
            $conf = $conf["betterchoice"]["puntopagos"];
            $enviroment = $conf["enviroment"];
            $STATE_FAILURE = $conf[$enviroment]["status_failure"];
            $STATE_ERROR = $conf[$enviroment]["status_error"];

            $order = Doctrine_Core::getTable("Transaction")->getTransaction($last_order_id);
            $idReserve = $order->getReserveId();
            $reserve = Doctrine_Core::getTable('reserve')->findOneById($idReserve);

            $opcionLiberacion = $reserve->getLiberadoDeGarantia();
            if ($opcionLiberacion == 0)
                $montoLiberacion = 0;
            else if ($opcionLiberacion == 1)
                $montoLiberacion = $reserve->getMontoLiberacion();

            $finalPrice = $order->getPrice() + $montoLiberacion;
            if ($finalPrice > 0) {

                $this->_log("Pago", "Fracaso", "Usuario: " . $customer_in_session . ". Order ID: " . $order->getId());
                Doctrine_Core::getTable("Transaction")->successTransaction($last_order_id, $token, $STATE_FAILURE, 1);
            } else {
                echo "No hay compras hechas para ser pagadas (Error de monto invalido)";
                $this->_log("Pago", "Error", "Usuario: " . $customer_in_session . ". Order ID: " . $order->getId());
                Doctrine_Core::getTable("Transaction")->successTransaction($last_order_id, $token, $STATE_ERROR, 1);
            }
        } else {
            $this->redirect('@homepage');
        }
    }

    /**
     * Se usa para el proceso de notificacion de Punto Pagos
     *
     * @param sfRequest $request A request object
     */
    public function executeNotificacion(sfWebRequest $request) {
        echo "Notificacion Punto Pagos"; //We prefer doing trasaction step by curl and not literally by url
        exit;
    }

    /**
     * Hello word only for basic test porpouse
     *
     * @param sfRequest $request A request object
     */
    public function executeHello(sfWebRequest $request) {
        $conf = $this->getConfiguration();
        $conf = $conf["betterchoice"]["puntopagos"];
        $enviroment = $conf["enviroment"];
        echo "Hello Word! Punto Pagos (" . $enviroment . ")";
        exit;
    }

    protected function getConfiguration() {
        return sfYaml::load(dirname(dirname(__FILE__)) . "/config/puntopagos.yml");
    }

    protected function notificacion($token) {

        //Rescatamos el Ultimo ID de orden enviado a crear a Punto Pagos
        $orderId = $this->getUser()->getAttribute('PP_LAST_ORDER_ID');

        if ($orderId) {

            $order = Doctrine_Core::getTable("Transaction")->getTransaction($orderId);
            $idReserve = $order->getReserveId();
            $reserve = Doctrine_Core::getTable('reserve')->findOneById($idReserve);

            $opcionLiberacion = $reserve->getLiberadoDeGarantia();
            if ($opcionLiberacion == 0)
                $montoLiberacion = 0;
            else if ($opcionLiberacion == 1)
                $montoLiberacion = $reserve->getMontoLiberacion();

            $finalPrice = $order->getPrice() + $montoLiberacion;

            $conf = $this->getConfiguration();
            $conf = $conf["betterchoice"]["puntopagos"];
            $enviroment = $conf["enviroment"];
            //Campos configurados segun config/puntopagos.yml:
            $PUNTOPAGOS_URL = $conf[$enviroment]["url"];
            $PUNTOPAGOS_SECRET = $conf[$enviroment]["secret"];
            $PUNTOPAGOS_KEY = $conf[$enviroment]["key"];

            $funcion = "transaccion/traer";
            $trx_id = $order->getId();
            $ammount = $finalPrice;
            $ammount_str = number_format($ammount, 2, '.', '');

            $http_request = "";

            $fecha = gmdate("D, d M Y H:i:s", time()) . " GMT";
            $mensaje = $funcion . "\n" . $token . "\n" . $trx_id . "\n" . $ammount_str . "\n" . $fecha;

            $this->_log("Notificacion", "Mensage", $mensaje);

            $signature = base64_encode(hash_hmac('sha1', $mensaje, $PUNTOPAGOS_SECRET, true));
            $firma = "PP " . $PUNTOPAGOS_KEY . ":" . $signature;

            $this->_log("Notificacion", "Firma", $firma);


            $header = array();
            $http_request .= $header[] = "Accept: application/json;";
            $http_request .= $header[] = "Accept-Charset: utf-8;";
            $http_request .= $header[] = "Accept-Language: en-us,en;q=0.5";
            $http_request .= $header[] = "Content-type: application/json";
            $http_request .= $header[] = "Fecha: " . $fecha;
            $http_request .= $header[] = "Autorizacion: " . $firma;

            $url_pp = $PUNTOPAGOS_URL . "/transaccion/" . $token;

            $curl = curl_init($url_pp);
            curl_setopt($curl, CURL_VERSION_SSL, "SSL_VERSION_SSLv3");
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
            curl_setopt($curl, CURL_HTTP_VERSION_1_1, 1);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

            $http_response = curl_exec($curl);

            $response = json_decode($http_response);

            curl_close($curl);

            if ($response->error != null) {
                $this->_log("Notificacion", "Error", "Usuario: " . $customer_in_session . ". Order ID: " . $order->getId() . ". Token Punto Pagos: " . $response->token . ". JSON Punto Pagos: " . $http_response);
            } else {
                $this->_log("Notificacion", "Exito", "Usuario: " . $customer_in_session . ". Order ID: " . $order->getId() . ". Token Punto Pagos: " . $response->token . ". JSON Punto Pagos: " . $http_response);
            }
        }
    }

    protected function _log($step, $status, $msg) {

        $logPath = sfConfig::get('sf_log_dir') . '/puntopagos.log';
        $custom_logger = new sfFileLogger(new sfEventDispatcher(), array('file' => $logPath));
        $custom_logger->info($step . " - " . $status . ". " . $msg);
    }
    
    
    /**
     * action para testing de numeración de facturas
     * 
     * @param sfWebRequest $request
     * @return type
     */
    public function executeFacturacionTest(sfWebRequest $request){
        $idReserve = $request->getParameter('idReserve');
        $idTransaction = $request->getParameter('idTransaction');
        
        $order = Doctrine_Core::getTable("Transaction")->getTransaction($idTransaction);
        $reserve = Doctrine_Core::getTable('Reserve')->findOneById($idReserve);
        
        $functions = new Functions;
        $functions->generarNroFactura($reserve, $order);
        
        return sfView::NONE;
    }

}
