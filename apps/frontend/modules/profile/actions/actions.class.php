<?php

/**z
 * profile actions.
 *
 * @package    CarSharing
 * @subpackage profile
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class profileActions extends sfActions {

    /**
     * Executes index action
     *
     * @param sfRequest $request A request object
     */
    public function executeDoUpdatePassword(sfWebRequest $request) {

        try {

            $profile = Doctrine_Core::getTable('User')->find($this->getUser()->getAttribute('userid'));
            if($request->getParameter('password') != '') {
                if ($request->getParameter('password') == $request->getParameter('passwordAgain'))
                    $profile->setPassword(md5($request->getParameter('password')));
            }
            $profile->save();
        } catch (Exception $e) {
            echo $e->getMessage();
        }
        $this->redirect('profile/cars');
    }

    public function executeChangePassword(sfWebRequest $request) {
        $this->user = Doctrine_Core::getTable('user')->find(array($this->getUser()->getAttribute("userid")));

    }

    public function executeUploadImage(sfWebRequest $request) {
        //$this->setLayout(false);
        //$opcion = $_FILES($request->getParameter['archivo']['name']);

        /*
        $msg='';
        if(isset($_POST) and $_SERVER['REQUEST_METHOD'] == "POST"){

            $name = $_FILES[$request->getParameter('archivo')]['name'];
            $size = $_FILES[$request->getParameter('archivo')]['size'];
            $tmp = $_FILES[$request->getParameter('archivo')]['tmp_name'];

            //$ext = getExtension($name);
            
            if(strlen($name) > 0){
                if(in_array($ext,$valid_formats)){
                    if($size<(1024*1024)){
                        //Rename image name. 
                        $actual_image_name = time().".".$ext;
                        if($s3->putObjectFile($tmp, $bucket , $actual_image_name, S3::ACL_PUBLIC_READ) ){
                            $msg = "S3 Upload Successful."; 
                            $s3file='http://'.$bucket.'.s3.amazonaws.com/'.$actual_image_name;
                        }else $msg = "S3 Upload Fail.";
                    }else $msg = "Image size Max 1 MB";
                }else $msg = "Invalid file, please upload image file.";
            }else $msg = "Please select image file.";
            echo "<img src='$s3file' style='max-width:400px'/><br/><b>S3 File URL:</b>".$s3file;
            die();
            
            echo $name."-".$size."-".$tmp;
            die();
        }
        echo "ERROR";
        die();
        */

        require sfConfig::get('sf_app_lib_dir')."/s3upload/image_check.php";
        require sfConfig::get('sf_app_lib_dir')."/s3upload/s3_config.php";
        if (!empty($_FILES)) {
            $tempFile = $_FILES[$request->getPostParameter('Filedata')]['tmp_name'];
            $name = $_FILES[$request->getPostParameter('Filedata')]['name'];
            $size = $_FILES[$request->getPostParameter('Filedata')]['size'];
            $ext = getExtension($name);
            
            $bandera=FALSE;
            if(strlen($name) > 0){
                if(in_array($ext,$valid_formats)){
                    if($size<(1024*1024)){
                        //Rename image name. 
                        $actual_image_name = time().".".$ext;
                        if($s3->putObjectFile($tempFile, $bucket , $actual_image_name, S3::ACL_PUBLIC_READ) ){
                            $msg = "S3 Upload Successful."; 
                            $s3file='http://'.$bucket.'.s3.amazonaws.com/'.$actual_image_name;
                            $bandera=TRUE;
                        }else $msg = "S3 Upload Fail.";
                    }else $msg = "Image size Max 1 MB";
                }else $msg = "Invalid file, please upload image file.";
            }else $msg = "Please select image file.";
            
            //$targetPath = $_SERVER['DOCUMENT_ROOT'] . $_REQUEST['folder'] . '/';
            //$targetFile =  str_replace('//','/',$targetPath) . $name;

            if ($bandera){
                echo "<img src='$s3file' style='max-width:400px'/><br/><b>S3 File URL:</b>".$s3file;
            } else {
                echo $msg;
            }
        }
    }

    public function executeEliminarCarAjax(sfWebRequest $request){
        $idCar = $request ->getPostParameter('idCar');

        $car = Doctrine_Core::getTable("car")->findOneById($idCar);
        $car->setActivo(0);
        $car->save();

        die();
    }

    public function executePruebaMail(sfWebRequest $request){
        require sfConfig::get('sf_app_lib_dir')."/mail/mail.php";
        
        $path = sfConfig::get("sf_web_dir");

        $mail = new Email();
        
        //prueba de correo (datos adjuntos)
        $mail->setBody('<p>Prueba de envío de mail</p>');
        //$mail->setTo('mex_ici89@hotmail.com');
        $mail->setTo('mauri-tkd@hotmail.com');
        $mail->setCc('mauricio@arriendas.cl');
        //$mail->setFile($path.'/subida.txt');
        //$mail->setFile($path.'/subida.pdf');
        //$mail->setFile($path.'/subida.jpg');
        //mail->setFile($path.'/subida.pdf');
        
        echo $mail->submit();
        die();
    }

     
     public function executeEnviarConfirmacionSMS(sfWebRequest $request) {
	$token=rand(1000,9999);
	$fono=$request->getParameter("numero");
	if(substr($fono,0,1)=="0") {
	    $fono= substr($fono,1);
	}
	$url="http://api.infobip.com/api/v3/sendsms/plain?user=arriendas&password=arriendas&sender=Arriendas&SMSText=".$token."&GSM=569".$fono;
	$ch= curl_init();
	curl_setopt($ch,CURLOPT_URL,$url);
	curl_exec($ch);
	curl_close($ch);
	//guardamos el codigo en la tabla correspondiente
	$idUsuario = sfContext::getInstance()->getUser()->getAttribute('userid');
	$user= Doctrine_Core::getTable("User")->findOneById($idUsuario);
	$user->setCodigoConfirmacion($token);
	$user->save();
	return $this->renderText("OK");
     }
     
    public function executeValidarSMS(sfWebRequest $request) {
    	$token=$request->getParameter("token");
    	$idUsuario = sfContext::getInstance()->getUser()->getAttribute('userid');
    	$user= Doctrine_Core::getTable("User")->findOneById($idUsuario);
    	if($user->getCodigoConfirmacion() == $token) {
    	    $user->setConfirmedSMS(1);
    	    $user->save();
    	    return $this->renderText("1");
    	} else {
    	    return $this->renderText("0");
    	}
    }
     
    public function executeConfirmarSMS(sfWebRequest $request) {
	
	
    }

    public function executeExtenderReservaAjax(sfWebRequest $request){
        
        $id = $request ->getPostParameter('id');
        $fechaHasta = $request ->getPostParameter('fechaHasta');
        $horaHasta = $request ->getPostParameter('horaHasta');

        //$fechaHasta = '28-03-2013';
        //$horaHasta = '15:30:00';
        //$id = '605';

        //otros campos se obtienen de la reserva anterior
        $reserveAnterior = Doctrine_Core::getTable('reserve')->findOneById($id);
        $dateAnterior = $reserveAnterior->getDate();
        $duracionAnterior = $reserveAnterior->getDuration();
        $userId = $reserveAnterior->getUserId();
        $carId = $reserveAnterior->getCarId();

        //calcular date y duration
        $date = strtotime($dateAnterior);
        $date = $date+($duracionAnterior*60*60);

        //formatea la fechaHasta
        $fechaHasta = explode('-', $fechaHasta);
        $fechaHasta = $fechaHasta[2]."-".$fechaHasta[1]."-".$fechaHasta[0];
        $fechaHasta = strtotime($fechaHasta." ".$horaHasta);

        $duracion = ($fechaHasta-$date)/3600;

        $date = date("Y-m-d H:i:s",$date);
        $fechaHasta = date("Y-m-d H:i:s",$fechaHasta);
        //echo $date." ".$fechaHasta;

        //calcula el precio (obtiene el precio completo de las 2 reservas y le quita el precio de la reserva original)
        $car = Doctrine_Core::getTable('car')->findOneById($carId);
        $valorHora = $car -> getPricePerHour();
        $valorDia = $car -> getPricePerDay();
        $valorSemana = $car -> getPricePerWeek();
        $valorMes = $car -> getPricePerMonth();
        $precioCompleto = $this -> calcularMontoTotal($duracionAnterior+$duracion, $valorHora, $valorDia,$valorSemana,$valorMes);
        $precioNuevo = $this -> calcularMontoTotal($duracion, $valorHora, $valorDia,$valorSemana,$valorMes);
        //echo $duracionAnterior." ".$duracion."<br>";
        //echo $precioCompleto." ".$precioNuevo;

        //almacena la extensión de la reserva
        $reserve = new Reserve();
        $reserve->setDate($date);
        $reserve->setDuration($duracion);
        $reserve->setUserId($userId);
        $reserve->setCarId($carId);
        $reserve->setPrice($precioNuevo);
        $reserve->setIdPadre($id);
        $reserve->setComentario('Reserva extendida');
        $reserve->setFechaReserva($this->formatearHoraChilena(strftime("%Y-%m-%d %H:%M:%S")));
        $reserve->save();

        //$nameOwner
        //$nameRenter
        //$correo
        /*
        require sfConfig::get('sf_app_lib_dir')."/mail/mail.php";
        $mail = new Email();
        $mail->setSubject('Extensión de reserva');
        $mail->setBody("<p>Hola $nameOwner:</p><p>$nameRenter quiere extender la reserva pagada:</p><p></p>");
        $mail->setTo($correo);
        $mail->submit();
        */
        die();
        
    }
     
    public function executeEditarFechaPedidosAjax(sfWebRequest $request){
        
        $id = $request ->getPostParameter('id');
        $fechaDesde = $request ->getPostParameter('fechaDesde');
        $horaDesde = $request ->getPostParameter('horaDesde');
        $fechaHasta = $request ->getPostParameter('fechaHasta');
        $horaHasta = $request ->getPostParameter('horaHasta');
        
        /*
        $fechaDesde=  "20-01-13";
        $fechaHasta=  "20-01-13";
        $horaDesde =  "14:30";
        $horaHasta=   "17:30";
        $id = "631";
        */

        //deja la fecha recibida en formato yy-mm-dd
        $fechaDesde = explode('-', $fechaDesde);
        $fechaDesde = $fechaDesde[2]."-".$fechaDesde[1]."-".$fechaDesde[0];

        $fechaHasta = explode('-', $fechaHasta);
        $fechaHasta = $fechaHasta[2]."-".$fechaHasta[1]."-".$fechaHasta[0];

        $fechaDesde = strtotime($fechaDesde." ".$horaDesde);
        $fechaHasta = strtotime($fechaHasta." ".$horaHasta);

        $duracion = ($fechaHasta-$fechaDesde)/3600;

        $date = date("Y-m-d H:i:s",$fechaDesde);

        $reserve = Doctrine_Core::getTable('reserve')->findOneById($id);

        $idCar = $reserve -> getCarId();
        $car = Doctrine_Core::getTable('car')->findOneById($idCar);
        $valorHora = $car -> getPricePerHour();
        $valorDia = $car -> getPricePerDay();
        $valorSemana = $car -> getPricePerWeek();
        $valorMes = $car -> getPricePerMonth();
        $precio = $this -> calcularMontoTotal($duracion, $valorHora, $valorDia,$valorSemana,$valorMes);

        $reserve -> setDate($date);
        $reserve -> setDuration($duracion);
        $reserve -> setPrice($precio);

        $reserve -> save();

        die();
    }

    public function executeCambiarEstadoPedidoAjax(sfWebRequest $request){
        $idReserve = $request ->getPostParameter('idReserve');
        $accion = $request ->getPostParameter('accion');

        //$accion = 'preaprobar';
        //$idReserve = 663;

        $reserve = Doctrine_Core::getTable('reserve')->findOneById($idReserve);

        if($accion == 'preaprobar'){
            //echo "pre aprobar";
            $reserve->setConfirmed(1);
            $reserve->setCanceled(0);
            $reserve->setFechaConfirmacion($this->formatearHoraChilena(strftime("%Y-%m-%d %H:%M:%S")));
            //Validando si la persona contestó dentro de los 10 primeros minutos
            $horaLimite = strftime("%Y-%m-%d %H:%M:%S",strtotime('+10 minutes',strtotime($reserve->getFechaReserva())));
            if(strtotime($this->formatearHoraChilena(strftime("%Y-%m-%d %H:%M:%S"))) <= strtotime($horaLimite)){
                $reserve->setCambioEstadoRapido(1);
            }else{
                $reserve->setCambioEstadoRapido(0);
            }
        }else if($accion == 'rechazar'){
            //echo "rechazar";
            $reserve->setConfirmed(0);
            $reserve->setCanceled(1);          
        }
        $reserve->save();
        
        //obtiene fecha inicio, fecha termino, marca y modelo del auto
        $tokenReserve = $reserve->getToken();
        $fechaInicio = $reserve->getFechaInicio();
        $horaInicio = $reserve->getHoraInicio();
        $fechaTermino = $reserve->getFechaTermino();
        $horaTermino = $reserve->getHoraTermino();
        $marcaModelo = $reserve->getMarcaModelo();
        $correoRenter = $reserve->getEmailRenter();
        $nameRenter = $reserve->getNameRenter();
        $correoOwner = $reserve->getEmailOwner();
        $nameOwner = $reserve->getNameOwner();
        $precio = $reserve->getPrice();
        $precio = number_format($precio, 0, ',', '.');
        $idCar = $reserve->getCarId();

		$lastName = $reserve->getLastnameOwner();
		$telephone = $reserve->getTelephoneOwner();
		$lastNameRenter = $reserve->getLastNameRenter();
		$telephoneRenter = $reserve->getTelephoneRenter();

        if($accion == 'preaprobar'){
            //crea la fila en la tabla transaction
            $this->executeConfirmReserve($idReserve);

            //envía mail de preaprobación al arrendatario
//            require sfConfig::get('sf_app_lib_dir')."/mail/mail.php";
            //al arrendatario
            $mail = new Email();
            $mailer = $mail->getMailer();

            $message = $mail->getMessage();
            $message->setSubject('Se ha aprobado tu reserva! (Falta pagar)');
            $body = '<p>Hola '.$nameRenter.':</p><p>Se ha aprobado tu reserva!</p><p>Para acceder a los datos entrega del vehículo debes pagar 1000.- CLP.</p><p>Si tu arriendo no se concreta, Arriendas.cl no le pagará al dueño del auto y te daremos un auto a elección.</p><p>Has click <a href="http://www.arriendas.cl/profile/pedidos">aquí</a> para pagar.</p><p>Los datos del arriendo y el contrato se encuentran adjuntos en formato PDF.</p>';
            $message->setBody($mail->addFooter($body), 'text/html');
            $message->setTo($correoRenter);
            $functions = new Functions;
            $contrato = $functions->generarContrato($tokenReserve);
            $reporte = $functions->generarReporte($idCar, true);
            $message->attach(Swift_Attachment::newInstance($contrato, 'contrato.pdf', 'application/pdf'));
            $message->attach(Swift_Attachment::newInstance($reporte, 'reporte.pdf', 'application/pdf'));
            $mailer->send($message);
            
            $message = $mail->getMessage();
            $message->setSubject('Se ha aprobado una reserva');
            $body = "<p>Dueño: $nameOwner $lastName ($telephone) - $correoOwner</p><p>Arrendatario: $nameRenter $lastNameRenter ($telephoneRenter) - $correoRenter</p><p>Precio: $precio</p>";
            $message->setBody($mail->addFooter($body), 'text/html');
            $message->setTo('soporte@arriendas.cl');
            $mailer->send($message);

            if($reserve->getConfirmedSMSRenter()==1){
                $texto = "Se ha aprobado tu reserva! Debes pagar en Arriendas.cl - Si tu arriendo no se concreta, no le pagaremos al propietario del auto y te daremos un auto a eleccion";
                $this->enviarSMS($reserve->getTelephoneRenter(),$texto);
            }

            //al propietario
            $message = $mail->getMessage();
            $message->setSubject('Has aprobado una reserva!');
            $body = "<p>Hola $nameOwner:</p><p>Has aprobado una reserva!</p><p>Recuerda que puedes aprobar varios pedidos de reserva para la misma fecha. El primer arrendatario que pague, ganará el arriendo.</p><p>El contrato de arriendo se emitirá una vez que el arrendatario haya pagado. La versión preliminar se encuentra adjunta en formato PDF.</p><p>Su pago se te informará por este medio y verás el cambio en la pestaña 'Reservas' del sitio.</p>";
            $message->setBody($mail->addFooter($body), 'text/html');
            $message->setTo($correoOwner);
            $message->attach(Swift_Attachment::newInstance($contrato, 'contrato.pdf', 'application/pdf'));
            $mailer->send($message);
        }

        die();
    }

    public function executeTestMail(sfWebRequest $request)
    {
        $mail = new Email();
        $mailer = $mail->getMailer();
        $message = $mail->getMessage();
        $message->setSubject('El arrendatario ha pagado la reserva!');
        $body = "<p>Hola $nameOwner:</p><p>El arrendatario ha pagado la reserva!</p><p>Recuerda que debes llenar el FORMULARIO DE ENTREGA Y DEVOLUCIÓN del vehículo.</p><p>Puedes llenar el formulario <a href='http://www.arriendas.cl/profile/formularioEntrega/idReserve/$idReserve'>desde tu celular</a>.</p><p>No des inicio al arriendo si el auto tiene más daños que los declarados.</p><p>Datos del propietario:<br><br>Nombre: $nameRenter $lastnameRenter<br>Teléfono: $telephoneRenter<br>Correo: $emailRenter</p><p>Los datos del arriendo y la versión escrita del formulario de entrega, se encuentran adjuntos en formato PDF.</p>";
        $message->setBody($mail->addFooter($body), 'text/html');
        $message->setTo('ikleiman@uc.cl');
        $functions = new Functions;
        $formulario = $functions->generarFormulario(NULL, '0ae0444ab5971b18c22f576f55b027f3d7debd98');
        $reporte = $functions->generarReporte('221');
        $message->attach(Swift_Attachment::newInstance($formulario, 'formulario.pdf', 'application/pdf'));
        $message->attach(Swift_Attachment::newInstance($reporte, 'reporte.pdf', 'application/pdf'));
        $mailer->send($message);

        //$mail->setSubject('Se ha aprobado tu reserva! (Falta pagar)');
        //$mail->setBody("<p>Hola ivan:</p><p>El arrendatario ha pagado la reserva!</p><p>Recuerda que debes llenar el FORMULARIO DE ENTREGA Y DEVOLUCIÓN del vehículo.</p><p>Puedes llenar el formulario <a href='http://www.arriendas.cl/profile/formularioEntrega/idReserve/$idReserve'>desde tu celular</a> o puedes firmar la <a href='http://www.arriendas.cl/main/generarFormularioEntregaDevolucion/tokenReserve/$tokenReserve'>versión impresa</a>.</p><p>No des inicio al arriendo si el auto tiene más daños que los declarados.</p><p>Datos del propietario:<br><br>Nombre: $nameRenter $lastnameRenter<br>Teléfono: $telephoneRenter<br>Correo: $emailRenter</p><p>Los datos del arriendo se encuentran adjuntos en formato PDF.</p>");
        //$mail->setBody('<p>Hola ivan:</p><p>Se ha aprobado tu reserva!</p><p>Para acceder a los datos entrega del vehículo debes pagar 1000.- CLP.</p><p>Si tu arriendo no se concreta, Arriendas.cl no le pagará al dueño del auto y te daremos un auto a elección.</p><p>Has click <a href="http://www.arriendas.cl/profile/pedidos">aquí</a> para pagar.</p><p><a href="http://www.arriendas.cl/main/generarReporte/idAuto/">Datos del arriendo</a><br>El contrato se encuentra adjunto en formato PDF.</p>', 'text/html');

        //$contrato = $functions->generarContrato('0ae0444ab5971b18c22f576f55b027f3d7debd98');

    }
    
    public function enviarSMS($telefono,$texto){

        if(substr($telefono,0,1)=="0") {
            $telefono= substr($telefono,1);
        }
        $url="http://api.infobip.com/api/v3/sendsms/plain?user=arriendas&password=arriendas&sender=Arriendas&SMSText=".urlencode($texto)."&GSM=569".urlencode($telefono);
        $ch= curl_init();
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);//setopt de prueba para no mostrar el mensaje en la pantalla blanca.
        curl_exec($ch);
        curl_close($ch);
    }

    public function executeEliminarPedidoUpdateProfilesAjax(sfWebRequest $request){
        $idReserve = $request ->getPostParameter('idReserve');
        //$idReserve = 582;

        $idUsuario = sfContext::getInstance()->getUser()->getAttribute('userid');
        $reserve = Doctrine_Core::getTable('reserve')->findOneById($idReserve);

        if($reserve->getUserId() == $idUsuario){//es arrendatario
            $reserve->setVisibleRenter(0);
        }else{//es propietario
            $reserve->setVisibleOwner(0);
        }

        $confirmed = $reserve->getConfirmed();
        $canceled = $reserve->getCanceled();
        //al eliminar una reserva, se deben actualizar los valores de confirmado y cancelado
        if($confirmed){
            $reserve->setConfirmed(0);
            $reserve->setCanceled(1);
        }else if(!$canceled && !$confirmed){
            $reserve->setCanceled(1);            
        }

        $reserve->save();

        //envía correo a arrendatario, si es arrendatario no envía correo
        $idRenter = $reserve->getUserId();


        $fechaInicio = $reserve->getFechaInicio();
        $horaInicio = $reserve->getHoraInicio();
        $fechaTermino = $reserve->getFechaTermino();
        $horaTermino = $reserve->getHoraTermino();
        $marcaModelo = $reserve->getMarcaModelo();

        if($idRenter!=$idUsuario){
            $correo = $reserve->getEmailRenter();

            $body = "La reserva del vehículo $marcaModelo con fecha $fechaInicio $horaInicio hasta $fechaTermino $horaTermino ha sido rechazada por su propietario. Recuerda que puedes realizar multiples reservas a distintos vehículos para asegurar una aprobación.";

        }else{
            $correo = $reserve->getEmailOwner();

            $body = "La reserva del vehículo $marcaModelo con fecha $fechaInicio $horaInicio hasta $fechaTermino $horaTermino ha sido rechazada por el arrendatario.";
        }

        require sfConfig::get('sf_app_lib_dir')."/mail/mail.php";
        $mail = new Email();
        $mail->setBody($body);
        $mail->setTo($correo);
        $mail->submit();
        
        echo $idReserve;
        die();
        
    }

    public function executeFormularioEntregaAjax(sfWebRequest $request){
        $opcion = $request ->getPostParameter('opcion');
        //$opcion = 'declaracionDocumentosPropietario';
        $idReserve = $request ->getPostParameter('idReserve');

        if($request ->getPostParameter('kmInicial')){
            $kmInicial = $request ->getPostParameter('kmInicial');
        }

        if($request ->getPostParameter('kmFinal')){
            $kmFinal = $request ->getPostParameter('kmFinal');
        }

        if($request ->getPostParameter('horaLlegada')){
            $horaLlegada = $request ->getPostParameter('horaLlegada');
        }

        //$idReserve = 605;
        $reserve = Doctrine_Core::getTable('reserve')->findOneById($idReserve);

        if($opcion=='declaracionDocumentosPropietario'){
            //echo "declaracionDocumentosPropietario";
            $reserve->setDocumentosOwner(1);
        }elseif($opcion=='declaracionDocumentosArrendatario'){
            //echo "declaracionDocumentosArrendatario";
            $reserve->setDocumentosRenter(1);
        }elseif($opcion=='declaracionDaniosArrendatario'){
            //echo "declaracionDaniosArrendatario";
            $reserve->setDeclaracionDanios(1);
        }elseif($opcion=='declaracionDevolucionPropietario'){
            //echo "declaracionDevolucionPropietario";
            $reserve->setDeclaracionDevolucion(1);
        }elseif($opcion=='kilometrajeInicialArrendatario'){
            //echo "kilometrajeInicialArrendatario";
            $reserve->setKminicial($kmInicial);
        }elseif($opcion=='kilometrajeFinalPropietario'){
            //echo "kilometrajeFinalPropietario";
            $reserve->setKmfinal($kmFinal);
        }elseif($opcion=='horaLlegadaPropietario'){
            //echo "horaLlegadaPropietario";
            $reserve->setHoraDevolucion($horaLlegada);
        }

        $reserve->save();

        die();
    }

    
    public function executeFormularioEntrega(sfWebRequest $request){
        //se asume que se recibe el id de la tabla reserva desde la página anterior
        //$idReserve = 605;
        $idReserve= $request->getParameter("idReserve");

        $this->idReserve = $idReserve;
        //id del usuario que accede al sitio
        $idUsuario = sfContext::getInstance()->getUser()->getAttribute('userid');
        //$idUsuario = 823;
        //$idUsuario = 885;

        $reserve = Doctrine_Core::getTable('reserve')->findOneById($idReserve);

        //obtiene datos que ya se han ingresado en un acceso anterior al formulario
        $reserva['declaracionDocArrendatario'] = $reserve->getDocumentosRenter();
        //echo "<br>1:".$reserva['declaracionDocArrendatario'];
        $reserva['declaracionDocPropietario'] = $reserve->getDocumentosOwner();
        //echo "<br>2:".$reserva['declaracionDocPropietario'];
        $reserva['declaracionDanios'] = $reserve->getDeclaracionDanios();
        //echo "<br>3:".$reserva['declaracionDanios'];
        $reserva['declaracionDevolucion'] = $reserve->getDeclaracionDevolucion();
        //echo "<br>4:".$reserva['declaracionDevolucion'];
        $horaDevolucion = $reserve->getHoraDevolucion();
        //echo "<br>5:".$reserva['horaDevolucion'];
        $horaDevolucion = explode(':', $horaDevolucion);
        $reserva['horaDevolucion'] = $horaDevolucion[0].":".$horaDevolucion[1];
        $reserva['kmInicial'] = $reserve->getKminicial();
        //echo "<br>6:".$reserva['kmInicial'];
        $reserva['kmFinal'] = $reserve->getKmfinal();
        //echo "<br>7:".$reserva['kmFinal'];
        //die();


        $entrega = $reserve->getDate();
        $duracion = $reserve->getDuration();

        $entrega = strtotime($entrega);
        $this->fechaEntrega = date("d/m/y",$entrega);
        $this->horaEntrega = date("H:i",$entrega);

        $this->fechaDevolucion = date("d/m/y",$entrega+($duracion*60*60));
        $this->horaDevolucion = date("H:i",$entrega+($duracion*60*60));

        $carId = $reserve->getCarId();
        $arrendadorId = $reserve->getUserId();

        $carClass = Doctrine_Core::getTable('car')->findOneById($carId);

        $propietarioId = $carClass->getUserId();

        $modeloId = $carClass->getModel();

        $car['id'] = $carId;
        $car['marca'] = $carClass->getModel()->getBrand();
        $car['modelo'] = $carClass->getModel();
        $car['patente'] = $carClass->getPatente();

        $arrendadorClass = Doctrine_Core::getTable('user')->findOneById($arrendadorId);
        $propietarioClass = Doctrine_Core::getTable('user')->findOneById($propietarioId);

        if($propietarioId == $idUsuario){
            $car['propietario'] = true;
        }else{
            $car['propietario'] = false;
        }

        $arrendador['nombreCompleto'] = $arrendadorClass->getFirstname()." ".$arrendadorClass->getLastname();
        $arrendador['rut'] = $arrendadorClass->getRut();
        $arrendador['direccion'] = $arrendadorClass->getAddress();
        $arrendador['telefono'] = $arrendadorClass->getTelephone();

        $comunaId = $arrendadorClass->getComuna();
        $comunaClass = Doctrine_Core::getTable('comunas')->findOneByCodigoInterno($comunaId);
        $arrendador['comuna'] = ucfirst(strtolower($comunaClass['nombre']));

        $propietario['nombreCompleto'] = $propietarioClass->getFirstname()." ".$propietarioClass->getLastname();
        $propietario['rut'] = $propietarioClass->getRut();
        $propietario['direccion'] = $propietarioClass->getAddress();
        $propietario['telefono'] = $propietarioClass->getTelephone();

        $comunaId = $propietarioClass->getComuna();
        $comunaClass = Doctrine_Core::getTable('comunas')->findOneByCodigoInterno($comunaId);
        $propietario['comuna'] = ucfirst(strtolower($comunaClass['nombre']));

        $damageClass = Doctrine_Core::getTable('damage')->findByCarId($carId);
        //$damageClass = Doctrine_Core::getTable('damage')->findByCarId(553);
        $descripcionDanios = null;
        $i = 0;
        foreach ($damageClass as $damage) {
            $descripcionDanios[$i] = $damage->getDescription();
            $i++;
        }

        //die();
        $this->reserva = $reserva;
        $this->descripcionDanios = $descripcionDanios;
        $this->arrendador = $arrendador;
        $this->propietario = $propietario;
        $this->car = $car;

    }

    public function executePrueba(sfWebRequest $request){
        /*
        $boundleft = $request->getParameter('swLat');
        $boundright = $request->getParameter('neLat');
        $boundtop = $request->getParameter('swLng');
        $boundbottom = $request->getParameter('neLng');
        
        $boundleft = "-33.46432201813032";
        $boundright = "-33.39011012022805";
        $boundtop = "-70.65945967236325";
        $boundbottom = "-70.55165632763669";

        //searchResult
        echo "searchResult<br>";
        $q = Doctrine_Query::create()
                ->select('ca.id , av.id idav , mo.name modelo,
              br.name brand, ca.year year,
              ca.photoS3 photoS3, ca.address address, ci.name city,
              st.name state, co.name country,
              owner.firstname firstname,
              owner.lastname lastname,
              ca.price_per_day priceday,
              ca.price_per_hour pricehour,
                          ca.lat lat,
                          ca.lng lng,
              owner.id userid'
                )
                ->from('Car ca')
                ->innerJoin('ca.Availabilities av')
                ->innerJoin('ca.Model mo')
                ->leftJoin('ca.Reserves re')
                ->innerJoin('ca.User owner')
                ->innerJoin('mo.Brand br')
                ->innerJoin('ca.City ci')
                ->innerJoin('ci.State st')
                ->innerJoin('st.Country co')
                ->where('ca.lat > ?', $boundleft)
                ->andWhere('ca.activo = ?', 1)
                ->andWhere('ca.seguro_ok = ?', 4)
                ->andWhere('ca.lat < ?', $boundright)
                ->andWhere('ca.lng > ?', $boundtop)
                ->andWhere('ca.lng < ?', $boundbottom)
                ->orderBy('ca.price_per_day asc');
                //->orderBy('ca.id asc');
            $cars = $q->execute();
            for($i=0;$i<count($cars);$i++){
                echo ($i+1).") ".$cars[$i]->getPricePerDay()." V =>".$cars[$i]->autoVerificado()."<br>";
            }
            //die();
        //map
            $q2 = Doctrine_Query::create()
                ->select('ca.id, av.id idav ,
                ca.lat lat, ca.lng lng, ca.price_per_day, ca.price_per_hour,
                ca.photoS3 photoS3, mo.name modelo, br.name brand, ca.year year,
                ca.address address,
                us.username username, us.id userid')
                ->from('Car ca')
                ->innerJoin('ca.Availabilities av')
                ->leftJoin('ca.Reserves re')
                ->innerJoin('ca.Model mo')
                ->innerJoin('ca.User us')
                ->innerJoin('mo.Brand br')
                ->innerJoin('ca.City ci')
                ->innerJoin('ci.State st')
                ->innerJoin('st.Country co')
                ->where('ca.lat > ?', $boundleft)
                ->andWhere('ca.activo = ?', 1)
                //->andWhere('ca.comuna_id <> NULL OR ca.comuna_id <> 0')
                ->andWhere('ca.seguro_ok > 0')
                ->andWhere('ca.lat < ?', $boundright)
                ->andWhere('ca.lng > ?', $boundtop)
                ->andWhere('ca.lng < ?', $boundbottom)
                //->orderBy('ca.seguro_ok asc')
                ->orderBy('ca.price_per_day asc');
            $cars2 = $q2->execute();
            echo "<br>Map<br>";
            for($i=0;$i<count($cars2);$i++){
                echo ($i+1).") ".$cars2[$i]->getPricePerDay()." V =>".$cars2[$i]->autoVerificado()."<br>";
            }
            die();
            */
            //$this->fechaServidor = $this->formatearHoraChilena(strftime("%Y-%m-%d %H:%M:%S"));

        //$accion = 'preaprobar';
        //$idReserve = 663;
        $user = Doctrine_Core::getTable('user')->find(array($this->getUser()->getAttribute("userid")));
        //echo $reserve->getSendReserveLastWeek($this->formatearHoraChilena(strftime("%Y-%m-%d %H:%M:%S")));
        //var_dump($user->getSendReserveLastWeek($this->formatearHoraChilena(strftime("%Y-%m-%d %H:%M:%S"))));
        var_dump($user->getFirstReserve());
        die();
    }

    public function formatearHoraChilena($fecha){
        $horaChilena = strftime("%Y-%m-%d %H:%M:%S",strtotime('-4 hours',strtotime($fecha)));
        return $horaChilena;
    }

    public function executeUploadRatingStar(sfWebRequest $request){

        $index = $request->getParameter('i');
        $idRating = $request->getParameter('idStar');
        $tipoDeCalificacion = $request->getParameter('tipo'); //renter or owner

        if($index == 1){
            echo 'Muy Sucio';
        }else if($index == 2){
            echo 'Algo Sucio';
        }else if($index == 3){
            echo 'Aceptable';
        }else if($index == 4){
            echo 'Limpio';
        }else if($index == 5){
            echo 'Impecable';
        }
        $q = Doctrine_Manager::getInstance()->getCurrentConnection();
        if($tipoDeCalificacion == 1){ // como owner
            $query = "update arriendas.Rating set op_cleaning_about_renter='$index' where id='$idRating'";
            $result = $q->execute($query);
        }else if($tipoDeCalificacion == 2){ //como renter
            $query = "update arriendas.Rating set op_cleaning_about_owner='$index' where id='$idRating'";
            $result = $q->execute($query);
        }
        die();
    }

    public function executeCalificaciones(sfWebRequest $request){

        //Obtención de la data
        $idUsuario = sfContext::getInstance()->getUser()->getAttribute('userid'); //id del usuario actual
        $claseUsuario = Doctrine_Core::getTable('user')->findOneById($idUsuario);

        $this->listaDeArrendadoresDelAuto = $claseUsuario->getListaRentersPendientes_comoOwner(); //lista de arrendadores pendientes por calificar, como Duenio del Auto
        /*
        echo $this->listaDeArrendadoresDelAuto[0]['username']."<br>";
        echo $this->listaDeArrendadoresDelAuto[1]['username'];
        die();
        */

        $this->idCalificacionesOwner = $claseUsuario->getIDsCalificaciones_deRentersPendientes_comoOwner(); //Calificiones Pendientes, como Duenio del Auto

        $this->listaDeDueniosDelAutoArrendado = $claseUsuario->getListaOwnersPendientes_comoRenter();//lista de duenios pendientes por calificar, como Alquiler de un Auto
        $this->idCalificacionesRenter = $claseUsuario->getIDsCalificaciones_deOwnersPendientes_comoRenter();//Calificiones Pendientes, como Alquiler del Auto

        $this->puntualidad = $claseUsuario->getScorePuntualidad();
        $this->limpieza = $claseUsuario->getScoreLimpieza();
        $this->comentariosHechosPorMi = $claseUsuario->getMyComments_AsOwner_and_AsRenter();
        $this->comentariosSobreMi = $claseUsuario->getMyComments_aboutMe();


        //Envío de Formulario que guarda
        $RecargarPagina = false;
        $j = $request-> getPostParameter('posicion');
        $listoOK_j = 'listoOK'.$j;
        $listoOK = $request-> getPostParameter($listoOK_j);
        $typeSave = $request -> getPostParameter('guardandoComo');
        $idCalificacion = $request -> getPostParameter('idCalificacion');
        if($listoOK != ''){ //Si se mandó el formulario
            //auto
            $preg_usuaString = 'preg_usua'.$j;
            $preg_usua  = $request -> getPostParameter($preg_usuaString);
            if($preg_usua == '1'){
                $textoRecomenAuto = "Auto_Recomendado";
            }else if($preg_usua == '2'){
                $texto_autoNoString = 'textoRecomenAuto_NO'.$j;
                $textoRecomenAuto = $request -> getPostParameter($texto_autoNoString);
            }else if($preg_usua == '0'){
                $textoRecomenAuto  = "No Responde";
            }

            //desperfecto
            $preg_despString = 'preg_desp'.$j;
            $preg_desp  = $request -> getPostParameter($preg_despString);
            if($preg_desp == '1'){
                $textDespString = 'textoDesperfecto_SI'.$j;
                $textoDesperfecto  = $request -> getPostParameter($textDespString);
            }else if($preg_desp == '2'){
                $textoDesperfecto = "No_Presenta_Desperfecto";
            }else if($preg_desp == '0'){
                $textoDesperfecto  = "No Responde";
            }

            //dueño
            $preg_recoString = 'preg_reco'.$j;
            $preg_reco  = $request -> getPostParameter($preg_recoString);
            if($preg_reco == '1'){
                $textoRecomen = "Usuario_Recomendado";
            }else if($preg_reco == '2'){
                $textoRecoString = 'textoRecomen_NO'.$j;
                $textoRecomen  = $request -> getPostParameter($textoRecoString);
            }else if($preg_reco == '0'){
                $textoRecomen  = "No Responde";
            }

            //puntualidad
            $preg_puntString = 'preg_punt'.$j;
            $preg_punt  = $request -> getPostParameter($preg_puntString);
            if($preg_punt == '1'){
                $inicioImpuntualidad = 0;
                $terminoImpuntualidad = 0;
            }else if($preg_punt == '2'){
                $inicioImpString = 'star_impuntualidad'.$j;
                $terminoImpString = 'end_impuntualidad'.$j;
                $inicioImpuntualidad  = $request -> getPostParameter($inicioImpString);
                $terminoImpuntualidad  = $request -> getPostParameter($terminoImpString);
            }else if($preg_punt == '0'){
                $impuntualidad  = -1;
            }
            $comenString = 'algoBreve'.$j;
            $comentarioContraparte  = $request -> getPostParameter($comenString);

            $q = Doctrine_Manager::getInstance()->getCurrentConnection();

            $horaFechaActual = null;
            if($typeSave == 'renter'){
                $horaFechaActual = $this->formatearHoraChilena(strftime("%Y-%m-%d %H:%M:%S"));
                $query = "update arriendas.Rating set state_renter=1, opinion_about_owner='$comentarioContraparte', op_recom_car='$preg_usua', coment_no_recom_car='$textoRecomenAuto', op_desp_car='$preg_desp', coment_si_desp_car='$textoDesperfecto', op_recom_owner='$preg_reco', coment_no_recom_owner='$textoRecomen', op_puntual_about_owner='$preg_punt', time_delay_start_owner='$inicioImpuntualidad', time_delay_end_owner='$terminoImpuntualidad', fecha_calificacion_renter='$horaFechaActual' where id='$idCalificacion'";
            }else if($typeSave == 'owner'){
                $horaFechaActual = $this->formatearHoraChilena(strftime("%Y-%m-%d %H:%M:%S"));
                $query = "update arriendas.Rating set state_owner=1, opinion_about_renter='$comentarioContraparte', op_recom_renter='$preg_reco', coment_no_recom_renter='$textoRecomen', op_puntual_about_renter='$preg_punt', time_delay_start_renter='$inicioImpuntualidad', time_delay_end_renter='$terminoImpuntualidad', fecha_calificacion_owner='$horaFechaActual' where id='$idCalificacion'";
            }
            $result = $q->execute($query);

            //echo "Ha guardado Correctamente... Espere un momento, se actualizarán sus datos";
            echo "";
            die();
            
        }else{ //Si no se mandó el formulario...o halla Recarga por primera vez de la pagina
            if($RecargarPagina) die();
        }


    }
    public function executePruebaCss(sfWebRequest $request){
        
    }

    public function executeAseguraTuAuto(sfWebRequest $request) {


        $FormularioListo = $request -> getParameter('form');
        $this->idAuto = $request ->getParameter("id");

        $q = "SELECT * FROM car WHERE id=$this->idAuto";
        $query = Doctrine_Query::create()->query($q);
        $this->result = $query->toArray();

        //contador de fotos cargadas
        $this->fotosPartes = array();
        $photoCounter = $this->photoCounter();
	

        $this->partes = $this->partesAuto();
        $this->nombresPartes = $this->nombrePartesAuto();
	    //var_dump($this->nombresPartes);die();
        //if($FormularioListo != "ok"){
        //    $FormularioListo = false;
        // }

//        if($FormularioListo){
			if($FormularioListo == 'ok'){
	       
		   
		   //paso 1
	       //Cambio por widget para subir fotos de gran tamaño
    	    $fotoFrente= $request->getPostParameter("fotoFrente");
    	    $fotoCostadoDerecho= $request->getPostParameter("fotoCostadoDerecho");
    	    $fotoCostadoIzquierdo= $request->getPostParameter("fotoCostadoIzquierdo");
    	    $fotoTrasera= $request->getPostParameter("fotoTrasera");
    	
    	    $fotoPanel= $request->getPostParameter("fotoPanel");
    	
    	    $fotoRuedaDelDer= $request->getPostParameter("fotoRuedaDelDer");
    	    $fotoRuedaDelIzq= $request->getPostParameter("fotoRuedaDelIzq");
    	    $fotoRuedaTraIzq= $request->getPostParameter("fotoRuedaTraDer");
    	    $fotoRuedaTraDer= $request->getPostParameter("fotoRuedaTraIzq");
    	    $fotoRuedaRepuesto= $request->getPostParameter("fotoRuedaRepuesto");
    	    
    	    $fotoAccesorios1= $request->getPostParameter("fotoAccesorios1");
    	    $fotoAccesorios2= $request->getPostParameter("fotoAccesorios2");
    	    
    	    $fotoFrentePadron= $request->getPostParameter("fotoFrentePadron");
    	    $fotoReversoPadron= $request->getPostParameter("fotoReversoPadron");
	
            //paso 3
            $radioMarca = $request -> getPostParameter('marca_Radio');
            $tipoRadio = $request -> getPostParameter('tipo_Radio');
            $modeloRadio = $request -> getPostParameter('modelo_Radio');
            $parlantesMarca = $request -> getPostParameter('marca_Parlantes');
            $parlantesModelo  = $request -> getPostParameter('modelo_Parlantes');
            $twe_Marca = $request -> getPostParameter('marca_Tweeters');
            $twe_Modelo  = $request -> getPostParameter('modelo_Tweeters');
            $ecua_Marca = $request -> getPostParameter('marca_Ecualizador');
            $ecua_Modelo  = $request -> getPostParameter('modelo_Ecualizador');
            $ampli_Marca = $request -> getPostParameter('marca_Amplificador');
            $ampli_Modelo  = $request -> getPostParameter('modelo_Amplificador');
            $com_Marca = $request -> getPostParameter('marca_CompactCD');
            $com_Modelo  = $request -> getPostParameter('modelo_CompactCD');
            $subw_Marca = $request -> getPostParameter('marca_Subwoofer');
            $subw_Modelo  = $request -> getPostParameter('modelo_Subwoofer');
            $sist_Marca = $request -> getPostParameter('marca_SistemaDVD');
            $sist_Modelo  = $request -> getPostParameter('modelo_SistemaDVD');
            $otrosAcc  = $request -> getPostParameter('otrosAccesorios');
            $idAuto  = $request -> getPostParameter('idAuto');
            $seguro_ok = $request->getPostParameter('seguro_ok');
            //paso 4
            $tipoacc = $request -> getPostParameter('accesorio');
            $cantidad = count($tipoacc);
            $cadena = "";
            if($cantidad > 0){
                for($i=0;$i<count($tipoacc);$i++){
                    if($i==0) $cadena = $tipoacc[$i];
                    else $cadena = $cadena.",".$tipoacc[$i];
                }
            }
	    
            if($this->idAuto){
                //$ok = ($photoCounter >= 4 AND $seguro_ok != 4 ) ? 3 : $seguro_ok;
                $ok=4;
                //$idUsuario = sfContext::getInstance()->getUser()->getAttribute('userid');
                $q = Doctrine_Manager::getInstance()->getCurrentConnection();
                //$query = "update arriendas.Car set doors='$puertas', transmission='$transmision', uso_vehiculo_id='$usosVehiculo', foto_perfil='$fotoPerfilAuto[name]', padron='$fotoPadronFrente[name]', foto_padron_reverso='$fotoPadronReverso[name]' where id=$idAuto";
                $query = "update arriendas.Car
                        set radioMarca='$radioMarca',
                            radioModelo='$modeloRadio',
                            radioTipo='$tipoRadio',
                            parlantesMarca = '$parlantesMarca',
                            parlantesModelo = '$parlantesModelo',
                            tweeMarca = '$twe_Marca',
                            tweeModelo = '$twe_Modelo',
                            ecuaMarca = '$ecua_Marca',
                            ecuaModelo = '$ecua_Modelo',
                            ampliMarca = '$ampli_Marca',
                            ampliModelo = '$ampli_Modelo',
                            compMarca = '$com_Marca',
                            compModelo = '$com_Modelo',
                            subwMarca = '$subw_Marca',
                            subwModelo = '$subw_Modelo',
                            sistMarca = '$sist_Marca',
                            sistModelo = '$sist_Modelo',
                            otros = '$otrosAcc',
                            accesoriosSeguro = '$cadena',
                            verificationPhotoS3 = '0',
                            seguro_ok = '$ok'
                            where id='$this->idAuto'";
                    //die($query);
                $result = $q->execute($query);
                
        		//Revisamos si las fotos están cargadas o no. En este caso, el vehículo pasa a espera de revisión arriendas
        		$auto= Doctrine_Core::getTable("Car")->findOneById($this->idAuto);
        		if($auto->getVerificacionOK()) {
        		    $auto->setFechaFinVerificacion($this->formatearHoraChilena(strftime("%Y-%m-%d %H:%M:%S")));
        		    $auto->setSeguroOK(2);
        		    //Obtenemos el ID de verificacion mas alto
        		    $q= Doctrine_Manager::connection();
        		    $pdo=$q->execute("SELECT MAX(verification_id)+1 as valor FROM Car");
        		    $pdo->setFetchMode(Doctrine_Core::FETCH_ASSOC);
        		    $result = $pdo->fetchAll();
        		    $auto->setVerificationId($result[0]['valor']);
        		    $auto->save();
        		}
		
		
                //redireccionar
                $this->redirect('profile/cars/index');
                //die();
            }else{
                $ok=0;
                echo "Ha Ocurrido un Error al Intentar Guardar la Verificación";
                //redireccionar
                $this->redirect('profile/cars/index');
            }
        }

        //paso 2
        $idCar = $this->idAuto;
        $paso = $request -> getParameter('paso');
        $this->pasoAcceso = $paso;
        if($paso != null){

            sfContext::getInstance()->getConfiguration()->loadHelpers(array('Url'));
            $url = public_path("/uploads/damages/");
            
            $q = "SELECT * FROM damage WHERE car_id =$idCar";
            
            $query = Doctrine_Query::create()->query($q);
            $result = $query->toArray();

            $count = count($result);
            
            echo "<script>var id = new Array(); var coordX = new Array(); var coordY = new Array(); var lat = new Array(); var lng = new Array(); var descripcion = new Array(); var urlFoto = new Array(); var urlAbsoluta ='".$url."';";
            
            for($i=0;$i<$count;$i++){
                echo "id[".$i."] = '".$result[$i]['id']."';";
                echo "lat[".$i."] = '".$result[$i]['lat']."';";
                echo "lng[".$i."] = '".$result[$i]['lng']."';";
                echo "coordX[".$i."] = '".$result[$i]['coordX']."';";
                echo "coordY[".$i."] = '".$result[$i]['coordY']."';";
                echo "descripcion[".$i."] = '".$result[$i]['description']."';";
                echo "urlFoto[".$i."] = '".$result[$i]['urlFoto']."';";
            }

            echo "</script>";
            echo "<script>redireccionar = true;</script>";
        }else{
            echo "<script>redireccionar = false;</script>";
        }
    }

    public function executeDaniosMapaAuto(sfWebRequest $request){

        $fotoDanio = $request -> getFiles('foto');
        $urlFoto = $request -> getPostParameter('urlfoto');

        $descripcionDanio = $request -> getPostParameter('descripcion');
        $idCar = $request -> getPostParameter('idCar');
        $id = $request -> getPostParameter('id');

        $lat = $request -> getPostParameter('lat');
        $lng = $request -> getPostParameter('lng');
        $posicionX = $request -> getPostParameter('posicionX');
        $posicionY = $request -> getPostParameter('posicionY');

        //opcion seleccionada
        $opcion = $request -> getPostParameter('opcion'); //ingrear o eliminar

        
        //if($opcion == "ingresar"){

            //si lat, lng e idCar ya se encuentran en la base de datos, se debe eliminar el registro
            /*
            $q = "SELECT * FROM damage WHERE car_id =$idCar and lat=$lat and lng=$lng";
            
            $query = Doctrine_Query::create()->query($q);
            $result = $query->toArray();
            echo sizeof($result);
            die();
            */
            if($id!=null){

                $q = Doctrine_Manager::getInstance()->getCurrentConnection();
                $query = "delete from arriendas.Damages where id=$id";
                $q->execute($query);
                
                //echo $query;

            }
            if($opcion == 'ingresar'){
                
                $lat = number_format($lat,4);
                $lng = number_format($lng,2);

                //trunca las posiciones a 2 decimales
                $posicionX = number_format($posicionX,2);
                $posicionY = number_format($posicionY,2);
                
                 //subir archivo al servidor
                //echo $fotoDanio['name'];

                if($fotoDanio['name']==""){
                    $nombreFoto = $urlFoto;
                }else{
                    $uploadDir = sfConfig::get("sf_web_dir");
                    $path = $uploadDir . '/uploads/damages/';

                    if($fotoDanio['name']!=null){
                        $nombre = $fotoDanio['name'];
                        $tmp = $fotoDanio['tmp_name'];
                        list($txt, $ext) = explode(".", $nombre);
                        $nombreFoto = md5(rand()) . "." . $ext;
                        move_uploaded_file($tmp, $path . $nombreFoto);
                    }
                }
                //echo "desc:"+$descripcionDanio +" idcar:"+$idCar+" posX:"+$posicionX+" posY:"+$posicionY+" foto:"+$nombreFoto+" lat:"+$lat+" lng:"+$lng;
                //ingresa el daño: $nombreFoto, $posicionX, $posicionY, $descripcionDanio
                $damage = new Damage();
                $damage -> setDescription($descripcionDanio);
                $damage -> setCarId($idCar);
                $damage -> setCoordX($posicionX);
                $damage -> setCoordY($posicionY);
                $damage -> setUrlFoto($nombreFoto);
                $damage -> setLat($lat);
                $damage -> setLng($lng);
                $damage -> save();

            }

        $this->redirect('profile/aseguraTuAuto?id='.$idCar.'&paso=2');
    }

    public function executeAjaxCalendar(sfWebRequest $request) {
        $this->setLayout(false);
        sfConfig::set('sf_web_debug', false);

        $start = $request->getParameter('start');
        $end = $request->getParameter('end');

        $avs = Doctrine_Core::getTable('Availability')
                ->createQuery('a')
                ->where("a.Car.User.id = ?", $this->getUser()->getAttribute("userid"))
                ->andWhere("
		(a.date_from >= '$start' and a.date_from <= '$end') or 
		(a.date_from <= '$start' and a.date_to >= '$end') or
		(a.date_to >= '$start' and a.date_to <= '$end') or
		(a.date_from <= '$end' and a.date_to = 0) "
                )
                ->execute();


        $aaData = Array();

        foreach ($avs as $a) {

            $i = 0;


            $dateTmp = date("Y-m-d", strtotime($a->getDateFrom()));
            if ($a->getDateTo() != 0)
                $dateTo = date("Y-m-d", strtotime($a->getDateTo()));
            else
                $dateTo = date("Y-m-d", strtotime($end));

            $startDate = date("Y-m-d", strtotime($start));
            $endDate = date("Y-m-d", strtotime($end));


            while ($dateTmp <= $dateTo) {


                $from = $dateTmp . " " . $a->getHourFrom();
                $to = $dateTmp . " " . $a->getHourTo();


                $dayArray = getdate(strtotime($dateTmp . " - 1 days"));

                if ($a->getDay() == null || $a->getDay() == $dayArray['wday']) {

                    if ($startDate <= $dateTmp && $endDate >= $dateTmp)
                        $aaData[] = array('id' => $a->getId(), 'start' => $from, 'end' => $to, 'title' => $a->getCar()->getModel() . " " . $a->getCar()->getModel()->getBrand());
                }

                $dateTmp = date("Y-m-d", strtotime($dateTmp . " + 1 days"));
            }
        }


        return $this->renderText(json_encode($aaData));
    }

    public function executeRatingsHistory(sfWebRequest $request) {

        if ($request->getParameter('id') == "")
            $this->user = Doctrine_Core::getTable('user')->find(array($this->getUser()->getAttribute("userid")));
        else
            $this->user = Doctrine_Core::getTable('user')->find(array($request->getParameter('id')));
//$this->user->getMessegeTo();

        $this->ratingsCompleted = new Doctrine_Collection('rating');

        $reserves = $this->user->getReserves();
        $cars = $this->user->getCars();



        foreach ($cars as $c) {
            $carReserves = $c->getReserves();
            foreach ($carReserves as $cr) {
                if ($cr->getRating()->getId() != null)
                    if ($cr->getRating()->getUserOwnerOpnion() != "" && $cr->getRating()->getUserRenterOpnion() != "") {
                        $this->ratingsCompleted->add($cr->getRating());
                    }
            }
        }
    }

//public function executeEdit(sfWebRequest $request)
//{
// $this->user = Doctrine_Core::getTable('user')->find(array($this->getUser()->getAttribute("userid")));
//}






    public function executeGetAvInfo(sfWebRequest $request) {
        $this->setLayout(false);
        sfConfig::set('sf_web_debug', false);

        $id = $request->getParameter('id');

        $av = Doctrine_Core::getTable('Availability')
                ->createQuery('a')
                ->where("a.id = ?", $id)
                ->fetchOne();

        $aaData = Array();

        if ($av != null) {

            $datetime1 = $av->getDateTo();
            $datetime2 = $av->getDateFrom();


            if ($av->getDateTo() != 0) {

                $diff = abs(strtotime($datetime2) - strtotime($datetime1));
                $days = floor($diff / (60 * 60 * 24));
                if ($av->getDay() != null)
                    $week = $days / 7;
                else
                    $week = $days;
                $week = floor($week);
            }
            else {
                $week = "";
            }

//if($week > floor($week))
//$week = floor($week)+1;

            $aaData[] = array('id' => $av->getId(), 'start' => $av->getHourFrom(), 'end' => $av->getHourTo(), 'id' => $av->getId(), 'datefrom' => date("Y-m-d", strtotime($av->getDateFrom())), 'dateto' => date("Y-m-d", strtotime($av->getDateTo())), 'repeat' => $week, 'carid' => $av->getCar()->getId());
        }
        return $this->renderText(json_encode($aaData));
    }

    public function executeDeleteEvent(sfWebRequest $request) {
        $this->setLayout(false);
        sfConfig::set('sf_web_debug', false);

        $id = $request->getParameter('id');

        $av = Doctrine_Core::getTable('Availability')
                ->createQuery('a')
                ->where("a.id = ?", $id)
                ->fetchOne();

        $av->delete();

        $aaData[] = array('id' => null);
        return $this->renderText(json_encode($aaData));
    }

    public function executeSaveEvent(sfWebRequest $request) {

        $this->setLayout(false);
        sfConfig::set('sf_web_debug', false);

        $id = $request->getParameter('id');
        $date = date("Y-m-d", strtotime($request->getParameter('date')));
        $car = $request->getParameter('carid');
        $days = $request->getParameter('days');
        $start = $request->getParameter('start');
        $end = $request->getParameter('end');
        $repeat = $request->getParameter('repeat');
        $forever = $request->getParameter('forever');
        $dayofweek = $request->getParameter('dayofweek');

        if ($repeat == "all")
            $diff = (60 * 60 * 24) * ($days);
        else
            $diff = (60 * 60 * 24) * ($days * 7);

        $endDate = date("Y-m-d", (strtotime($date) + $diff));

        $av = Doctrine_Core::getTable('Availability')
                ->createQuery('a')
                ->where("a.id = ?", $id)
                ->fetchOne();

        $aaData = Array();

        if ($av == null) {
            $av = new Availability;
        }

        $av->setDateFrom($date);



        if ($repeat == "never")
            $av->setDateTo($date);
        else if ($forever == "checked")
            $av->setDateTo(0);
        else
            $av->setDateTo($endDate);

        if ($repeat == "1day")
            $av->setDay($dayofweek);

        $av->setHourFrom($start);
        $av->setHourTo($end);

        $car = Doctrine_Core::getTable('car')->find(array($car));
        $av->setCar($car);

        $av->save();

        $aaData[] = array('id' => $av->getId());
        return $this->renderText(json_encode($aaData));
    }

    public function executeJsCalendar(sfWebRequest $request) {
        $this->setLayout(false);
        sfConfig::set('sf_web_debug', false);
    }

    public function executeIndex(sfWebRequest $request) {

        $this->user = Doctrine_Core::getTable('user')->find(array($this->getUser()->getAttribute("userid")));
        $this->cars = $this->user->getCars();
    }

    public function executeProfile(sfWebRequest $request) {

        $this->user = Doctrine_Core::getTable('user')->find(array($this->getUser()->getAttribute("userid")));

//$this->user->getMessegeTo();
    }

    public function executePublicprofile(sfWebRequest $request) {

        $publicUserId = $request->getParameter('id');

        if ($publicUserId == "")
            $this->user = Doctrine_Core::getTable('user')->find(array($this->getUser()->getAttribute("userid")));
        else
            $this->user = Doctrine_Core::getTable('user')->find(array($publicUserId));

        //para columna derecha de datos verificados

        //crea la clase usuario con el id del usuario que es visitado
        $claseUsuario = Doctrine_Core::getTable('user')->findOneById($publicUserId);
        
        //verificación de los autos
        $this->verificacionAutos = $claseUsuario->getModeloAutoYVerificado();

        //telefono confirmado
        $this->telefonoConfirmado = $claseUsuario->getTelefonoConfirmado();
        if($this->telefonoConfirmado){
            $this->telefono = $claseUsuario->getTelefono();
        }else{
            $this->telefono = null;
        }
        
        //facebook confirmado
        $this->facebookConfirmado = $claseUsuario->getFacebookConfirmado();

        //email confirmado
        $this->emailConfirmado = $claseUsuario->getEmailConfirmado();
        if($this->emailConfirmado){
            $this->email = $claseUsuario->getEmail();
        }else{
            $this->email = null;
        }

        //para contenido de la página
        //obtiene puntualidad
        $this->puntualidad = $claseUsuario->getScorePuntualidad();
        //obtiene limpieza
        $this->limpieza = $claseUsuario->getScoreLimpieza();
        //obtiene velocidad de respuesta
        $this->velocidadRespuesta = $claseUsuario->getVelocidadRespuesta();
        //obtiene antiguedad
        $this->antiguedad = $claseUsuario->getAntiguedad();
        //obtiene número de transacciones
        $this->cantidadTransacciones = $claseUsuario->getCantidadTransacciones();
        
        //obtiene los comentarios (texto, nombre, fecha, fotoPerfil)
        $misComentarios = $claseUsuario->getMyComments_aboutMe();
        $comentariosOrd = array();
        for($y=0;$y<count($misComentarios);$y++){ //Ordeno del mas actual, al mas antiguo
            $comentariosOrd[$y] = $misComentarios[(count($misComentarios)-1)-$y];
        }
        $this->comentarios = $comentariosOrd;

        //autos columna derecha
        $user = Doctrine_Core::getTable('user')->find(array($publicUserId));
        $this->carsPublicProfile = $this->user->getCars();
    }

    public function executeEdit(sfWebRequest $request) {

        $this->redirect = $request->getParameter('redirect');
        $this->idRedirect = $request->getParameter('id');

        $user = Doctrine_Core::getTable('user')->find(array($this->getUser()->getAttribute("userid")));



        $this->userRegion = $user->getRegion();

        $this->userComuna = $user->getComuna();

        $this->user = $user;


        $q = Doctrine_Query::create()
                ->select('c.*')
                ->from('Comunas c');

        $this->comunas = $q->fetchArray();

        $q = Doctrine_Query::create()
                ->select('r.*')
                ->from('Regiones r');

        $this->regiones = $q->fetchArray();
    }

    public function executePagoDeposito(sfWebRequest $request) {
	
    }
    
    public function executeCars(sfWebRequest $request) {
        $user = Doctrine_Core::getTable('user')->find(array($this->getUser()->getAttribute("userid")));
        $this->cars = $user->getCars();
        //var_dump($this->cars);die();
    }

    public function executeCar(sfWebRequest $request) {
        $this->car = Doctrine_Core::getTable('car')->find(array($request->getParameter('id')));
        $this->brand = Doctrine_Core::getTable('Brand')->createQuery('a')->execute();
        $this->country = Doctrine_Core::getTable('Country')->createQuery('a')->execute();
        $this->selectedBrand = $this->car->getModel()->getBrand();
        $this->selectedModel = $this->car->getModel();
        $this->selectedState = $this->car->getCity()->getState();
        $this->selectedCity = $this->car->getCity();
        $this->selectedCountry = $this->car->getCity()->getState()->getCountry();
	    $this->damages = Doctrine_Core::getTable("Damage")->findByCar($this->car->getId());
	
    	//Revisamos que el usuario sea el propietario del auto
    	if ($this->car->getUserId() == $this->getUser()->getAttribute("userid")) {
    	    $this->getUser()->shutdown();
    	} else {
    	    $this->redirect("profile/cars","200");
    	}
    }

    private function getFileExtension($fileName) {
        $parts = explode(".", $fileName);
        return $parts[count($parts) - 1];
    }

    public function enviarReservaSMS($telefono,$fecha){

        //$texto = "Hola $nombre, te informanos que han reservado tu $auto para el $inicioDia a las $inicioHora. Para ver la reserva ingresa en http://arriendas.cl/profile/pedidos";
        $texto = "Has recibido una reserva en Arriendas.cl, Gana $1900 CLP por aprobarla durante los proximos 10 minutos. Ingresa en http://www.arriendas.cl/profile/pedidos";

        if(substr($telefono,0,1)=="0") {
            $telefono= substr($telefono,1);
        }
        $url="http://api.infobip.com/api/v3/sendsms/plain?user=arriendas&password=arriendas&sender=Arriendas&SMSText=".urlencode($texto)."&GSM=569".urlencode($telefono);
        $ch= curl_init();
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);//setopt de prueba para no mostrar el mensaje en la pantalla blanca.
        curl_exec($ch);
        curl_close($ch);
    }

    public function executeDoReserve(sfWebRequest $request) {

        $idUsuario = sfContext::getInstance()->getUser()->getAttribute('userid');
        $from = $request->getParameter('datefrom');
        $to = $request->getParameter('dateto');
        $carid = $request->getParameter('id');

		$car = Doctrine_Core::getTable('car')->find(array($request->getParameter('id')));

        //Validar Disponibilidad
		$startTime = strtotime($from);
		$endTime = strtotime($to);		
		$timeHasWorkday=false;
		$timeHasWeekend=false;
		for ($i = $startTime; $i <= $endTime; $i = $i + 86400) {
		  $thisDate = date('Y-m-d', $i); // 2010-05-01, 2010-05-02, etc
		  $dw = date( "w", $i);
			if ($dw==6 ||  $dw==0){
				$timeHasWeekend=true;
				break;
			}
		}
		for ($i = $startTime; $i <= $endTime; $i = $i + 86400) {
		  $thisDate = date('Y-m-d', $i); // 2010-05-01, 2010-05-02, etc
		  $dw = date( "w", $i);
			if ($dw>0 && $dw<5){
				$timeHasWorkday=true;
				break;
			}
		}

		if( $timeHasWorkday > $car->getDisponibilidadSemana() ){
			$this->getUser()->setFlash('msg', 'Este auto no tiene disponibilidad durante la semana');
	        $this->redirect('profile/reserve?id=' . $request->getParameter('id'));
		}elseif ($timeHasWeekend > $car->getDisponibilidadFinde() ) {
			$this->getUser()->setFlash('msg', 'Este auto no tiene disponibilidad durante los fines de semana');
	        $this->redirect('profile/reserve?id=' . $request->getParameter('id'));		
		};


		
        $reserve_id = '';
        if( $request->getParameter('reserve_id') ) $reserve_id = $request->getParameter('reserve_id');

        //Validate Dates
		if( $from == 0 || $to == 0 ) {
			$this->getUser()->setFlash('msg', 'Seleccione la fecha de inicio y de entrega');
	        $this->redirect('profile/reserve?id=' . $request->getParameter('id'));
		}

		
        $hourdesde = $request->getParameter('hour_from');
        $hourhasta = $request->getParameter('hour_to');

        if (preg_match('/^\d{1,2}\-\d{1,2}\-\d{4}$/', $from) && preg_match('/^\d{1,2}\-\d{1,2}\-\d{4}$/', $to)) {

			//CORROBORAR SI SE SOLICITO ANTES PERO TIENE DURACION DURANTE EL PEDIDO

            $startDate = date("Y-m-d H:i:s", strtotime($from . ' ' . $hourdesde));
            $endDate = date("Y-m-d H:i:s", strtotime($to . ' ' . $hourhasta));

			$rangeDates = array($startDate, $endDate,$startDate, $endDate,$startDate, $endDate);
            $diff = strtotime($endDate) - strtotime($startDate);


            $duration = $diff / 60 / 60;
            $durationReserva = $diff / 60 / 60;

            if ($diff > 0) {

                //comprueba que no haya una reserva PAGA para la fecha y hora señalada, al mismo auto, a cualquier usuario			
					$q = Doctrine_Query::create()
					  ->from('reserve r')
//					  ->leftJoin('transaction t ON r.id = t.reserve_id')
					  ->leftJoin('r.Transaction t')
					  ->where('t.completed = ?', true)
					  ->andwhere('r.car_id = ?', $carid)
					  ->andwhere('? BETWEEN r.date AND DATE_ADD(r.date, INTERVAL r.duration HOUR) OR ? BETWEEN r.date AND DATE_ADD(r.date, INTERVAL r.duration HOUR) OR r.date BETWEEN ? AND ? OR DATE_ADD(r.date, INTERVAL r.duration HOUR) BETWEEN ? AND ?', $rangeDates);
	
						$checkAvailability = $q->fetchArray();
	
						if( $checkAvailability ) {
							$this->getUser()->setFlash('msg', 'Ya hay una reserva paga para ese mismo auto en esa fecha y horario');
							$this->getRequest()->setParameter('carid', $carid);
							$this->getRequest()->setParameter('idreserve', $reserve_id);
							$this->forward('profile', 'reserve');
							die();
						};
					
                //END comprueba que no haya una reserva PAGA para la fecha y hora señalada, al mismo auto, a cualquier usuario			


				
                //comprueba que no haya una reserva vigente(fecha mayor a la actual) para la fecha y hora señalada, al mismo auto, al mismo usuario
                $reservas = Doctrine_Core::getTable("reserve")->findByUserId($idUsuario);
                $reservaPrevia = false;

//                foreach ($reservas as $reserva) {
//                    //comprueba que sea el mismo auto
//                    if($carid == $reserva->getCarId()){
//
//                        //comprueba que la reserva no esté eliminada o cancelada
//                        if(!$reserva->getCanceled()){
//
  //                          $date = $reserva->getDate();
    //                        $date = strtotime($date);
      //                      $duration = $reserva->getDuration();
   //                         $fechaActual = strtotime($this->formatearHoraChilena(strftime("%Y-%m-%d %H:%M:%S")));

//                            $dateInicio = strtotime($startDate);
  //                          $dateFin = strtotime($endDate);

    //                        if($date>=$fechaActual){
                                //echo $date." ".$dateInicio." ".$dateFin."<br>";

                                //fecha de inicio y fecha de termino se encuentra dentro del rango $dateInicio y $dateFin
      //                          if($date>=$dateInicio && $date<=$dateFin){
        //                            $reservaPrevia = true;
          //                      }

       //                         $date = $date+$duration*3600;
       //                         if($date>=$dateInicio && $date<=$dateFin){
       //                             $reservaPrevia = true;
        //                        }
          //                  }
            //            }

              //      }
               // }

                if(!$reservaPrevia){

                    //if (count($reservasEnEspera) < 5) {
                        
                        $reserve = new Reserve();
                        $reserve->setDuration($durationReserva);
                        $reserve->setDate($startDate);

                        $user = Doctrine_Core::getTable('User')->find(array($this->getUser()->getAttribute("userid")));
                        $reserve->setUser($user);
                        $car = Doctrine_Core::getTable('Car')->find(array($carid));
                        $reserve->setCar($car);

                        if ($car->getUser()->getAutoconfirm()) {
                            
                            $reserve->setConfirmed(true);
                        }

                        $reserve->setPrice( number_format( $this->calcularMontoTotal($durationReserva, $car->getPricePerHour(), $car->getPricePerDay(), $car->getPricePerWeek(), $car->getPricePerMonth()) , 2, '.', '') );
                        
                        //if($reserve_id) $reserve->setExtend($reserve_id);
                        
                        $reserve->setFechaReserva($this->formatearHoraChilena(strftime("%Y-%m-%d %H:%M:%S")));
                        $reserve->save();
                        //var_dump($reserve->getPrice());die();

                        //envía correo al propietario del auto
                        $fechaInicio = $reserve->getFechaInicio();
                        $horaInicio = $reserve->getHoraInicio();
                        $fechaTermino = $reserve->getFechaTermino();
                        $horaTermino = $reserve->getHoraTermino();
                        $marcaModelo = $reserve->getMarcaModelo();
                        $price = $reserve->getPrice();
                        $price=number_format(($price),0,',','.');
						$correo = $reserve->getEmailOwner();
                        $correoEmail = $reserve->getEmailOwnerCorreo();
                        $correoRenter = $reserve->getCorreoRenter();
                        $name = $reserve->getNameOwner();
                        $lastName = $reserve->getLastnameOwner();
                        $telephone = $reserve->getTelephoneOwner();
                        $nameRenter = $reserve->getNameRenter();
                        $lastNameRenter = $reserve->getLastNameRenter();
                        $telephoneRenter = $reserve->getTelephoneRenter();

                        require sfConfig::get('sf_app_lib_dir')."/mail/mail.php";
                        $mail = new Email();
                        $mail->setSubject('Has recibido un pedido de reserva!');
                        $mail->setBody("<p>Hola $name:</p><p>Has recibido un pedido de reserva por $$price por tu $marcaModelo desde <b>$fechaInicio $horaInicio</b> hasta <b>$fechaTermino $horaTermino</b> cuando te habrán devuelto el auto.</p><p>Para ver la reserva has click <a href='http://www.arriendas.cl/profile/pedidos'>aquí</a></p>");
                        $mail->setTo($correo);
                        if($correo != $correoEmail) $mail->setCc($correoEmail);
                        $mail->submit();
						
                        $mail = new Email();
                        $mail->setSubject('Nuevo pedido de reserva!');
                        $mail->setBody("<p>Dueño: $name $lastName ($telephone) - $correoEmail</p><p>Arrendatario: $nameRenter $lastNameRenter ($telephoneRenter) - $correoRenter</p><p>----------------------</p><p></p><p>Has recibido un pedido de reserva por el monto de $price por tu $marcaModelo desde el día <b>$fechaInicio</b> a las <b>$horaInicio</b> hasta el día <b>$fechaTermino</b> a las <b>$horaTermino</b> cuando te habrán devuelto el auto.</p><p>Para ver la reserva has click <a href='http://www.arriendas.cl/profile/pedidos'>aquí</a></p>");
                        $mail->setTo('soporte@arriendas.cl');
                        $mail->submit();
						
                        if($reserve->getConfirmedSMSOwner()==1){
                            $this->enviarReservaSMS($reserve->getTelephoneOwner(),$fechaInicio);
                        }

                        $tipoVehiculo = $reserve->getTypeCar();
                        $precioPorDia = $reserve->getPricePerDayCar();

                        $ciudad = "santiago";
                        $objeto_ciudad = Doctrine_Core::getTable("city")->findOneByName($ciudad);

                        $q = Doctrine_Query::create()
                            ->select('ca.id, mo.name model, br.name brand, ca.uso_vehiculo_id tipo_vehiculo, ca.year year, ca.transmission trans, ci.name city, st.name state, co.name country, ca.price_per_day priceday, ca.price_per_hour pricehour')
                            ->from('Car ca')
                            ->innerJoin('ca.Model mo')
                            ->innerJoin('ca.User owner')
                            ->innerJoin('mo.Brand br')
                            ->innerJoin('ca.City ci')
                            ->innerJoin('ci.State st')
                            ->innerJoin('st.Country co')
                            ->where('ca.activo = ?', 1)
                            ->andWhere('ca.seguro_ok = ?', 4)
                            ->andWhere('uso_vehiculo_id = ?', $tipoVehiculo)
                            ->andWhere('ca.city_id = ?', $objeto_ciudad->getId())
                            ->orderBy('ca.price_per_day asc');
                        $cars = $q->fetchArray();

                        $correoRenter = $reserve->getCorreoRenter();
                        $correoRenter2 = $reserve->getEmailRenter();
                        $nameRenter = $reserve->getNameRenter();
                        $mail2 = new Email();
                        $mail2->setSubject('Has realizado una reserva!');
                        $stringVar = "<p>Hola $nameRenter:</p><p>Has realizado un pedido de reserva al vehículo $marcaModelo desde <b>$fechaInicio $horaInicio</b> hasta <b>$fechaTermino $horaTermino</b>.</p><p>El precio es final e incluye Seguro de daños, robo y destrucción, TAGs y Asistencia.</p><p><u><b>Esta es una lista de otros autos disponibles en esta categoría:</b></u></p><ul>";
                        $cantidad = count($cars);
                        if($cantidad>=5) $cantidad = 5;
                        else $cantidad = count($cars);
                        for($i=0;$i<$cantidad;$i++){
                            if($tipoVehiculo == 1 && $precioPorDia < 24900){
                                $stringVar = $stringVar."<li>".$cars[$i]['brand']." ".$cars[$i]['model']." ";
                                if($cars[$i]['trans']==0) $stringVar = $stringVar."Manual ";
                                else $stringVar = $stringVar."Automático ";
                                $stringVar = $stringVar.$cars[$i]['year'].": <a href='http://www.arriendas.cl/cars/car/id/".$cars[$i]['id']."'>Ir a su perfil</a></li>";
                            }else if($tipoVehiculo == 1 && $precioPorDia >= 24900){
                                $stringVar = $stringVar."<p>".$cars[$i]['brand']." ".$cars[$i]['model']." ";
                                if($cars[$i]['trans']==0) $stringVar = $stringVar."Manual ";
                                else $stringVar = $stringVar."Automático ";
                                $stringVar = $stringVar.$cars[$i]['year'].": <a href='http://www.arriendas.cl/cars/car/id/".$cars[$i]['id']."'>Ir a su perfil</a></li>";
                            }else{
                                $stringVar = $stringVar."<p>".$cars[$i]['brand']." ".$cars[$i]['model']." ";
                                if($cars[$i]['trans']==0) $stringVar = $stringVar."Manual ";
                                else $stringVar = $stringVar."Automático ";
                                $stringVar = $stringVar.$cars[$i]['year'].": <a href='http://www.arriendas.cl/cars/car/id/".$cars[$i]['id']."'>Ir a su perfil</a></li>";
                            }
                        }
                        $stringVar = $stringVar."</ul><p>Haz click en el link para ver el auto</p>";

                        $mail2->setBody($stringVar);
                        $mail2->setTo($correoRenter);
                        if($correoRenter != $correoRenter2) $mail2->setCc($correoRenter2);
                        $mail2->submit();

                        //Enviando SMS si es es la primera reserva que realiza dentro de la ultima semana
                        $user = Doctrine_Core::getTable('user')->find(array($this->getUser()->getAttribute("userid")));
                        if(!$user->getSendReserveLastWeek($this->formatearHoraChilena(strftime("%Y-%m-%d %H:%M:%S")))){
                            if($user->getConfirmedSms()==1){
                                $texto = "Has emitido tu primera reserva de la semana en Arriendas.cl - Ante cualquier duda llamanos al 2 2333-3714 o escribenos a soporte@arriendas.cl";
                                $this->enviarSMS($user->getTelephone(),$texto);
                            }
                        }
                        if($user->getFirstReserve() == intval("1") && $user->getDriverLicenseFile() == NULL){
                            $mail3 = new Email();
                            $mail3->setSubject('Servicio al Cliente - Tu reserva en Arriendas.cl');
                            $mail3->setBody("<p>Hola $nameRenter:</p><p>Recuerda completar tu perfil y subir la imagen de tu licencia (arriba a la derecha, opción 'Mi Perfil').</p><p>Ante cualquier pregunta llámanos al 2 2333-3714.</p>");
                            $mail3->setTo($correoRenter);
                            $mail3->submit();
                        }
                            
                        $this->redirect('profile/reserveSend');
						die();
                }
                
                $this->getUser()->setFlash('msg', 'Ya hay una reserva confirmada para ese horario');
                $this->getRequest()->setParameter('carid', $carid);
                $this->getRequest()->setParameter('idreserve', $reserve_id);
                
            } else {
                
                $this->getUser()->setFlash('msg', 'Fecha de entrega debe ser mayor a fecha inicial');
            }
            
            $this->forward('profile', 'reserve');
            
        } else {
            
            $this->redirect('profile/reserve?id=' . $request->getParameter('id'));
        }
    }

    public function executeReserveConfirmed(sfWebRequest $request) {
        $this->getUser()->setFlash('msg', 'Tu reserva ha sido confirmada. No olvides llevar tu tarjeta de crédito para retirar el auto.');
    }

    public function executeReserveSend(sfWebRequest $request) {
		$url = $this->generateUrl('homepage');
        $this->getUser()->setFlash('msg', 'Reserva enviada. Realiza multiples reservas hasta recibir una aprobación. <a href="'.$url.'">Siguiente</a>');
    }

	 
	 
    public function executeDoSaveCar(sfWebRequest $request) {

        $car = new Car();
        $car->setPricePerDay(floor($request->getParameter('priceday')));
        $car->setPricePerHour(floor($request->getParameter('pricehour')));
        $car->setYear($request->getParameter('year'));
		$car->setPatente($request->getParameter('patente'));
		$car->setTipoBencina($request->getParameter('tipobencina'));
        $car->setKm($request->getParameter('km'));
        $car->setAddress($request->getParameter('address'));
        $model = Doctrine_Core::getTable('Model')->find(array($request->getParameter('model')));
        $car->setModel($model);
        $city = Doctrine_Core::getTable('City')->find(array($request->getParameter('city')));
        $car->setCity($city);
        $car->setLng($request->getParameter('lng'));
        $car->setLat($request->getParameter('lat'));
        $user = Doctrine_Core::getTable('User')->find(array($this->getUser()->getAttribute("userid")));
        $car->setUser($user);
        $car->setDescription($request->getParameter('description'));
        $car->save();

        if ($request->getParameter('main') != null) {
            $q = Doctrine_Query::create()->from('Photo')
                    ->where('Photo.Type = ?', 'main')
                    ->where('Photo.Car.Id = ?', $car->getId());
            $main = $q->fetchOne();

            if (!$main) {
                $main = new Photo;
            }
            $main->setCar($car);
            $main->setPath($request->getParameter('main'));
            $main->setType('main');
            $main->save();
        }

        for ($i = 1; $i <= 5; $i++) {
            if ($request->getParameter('photo' . $i) != null) {
                $q = Doctrine_Query::create()->from('Photo')
                        ->where('Photo.Type = ?', 'photo' . $i)
                        ->andWhere('Photo.Car.Id = ?', $car->getId());
                $photo = $q->fetchOne();

                if (!$photo) {
                    $photo = new Photo;
                }
                $photo->setCar($car);
                $photo->setPath($request->getParameter('photo' . $i));
                $photo->setType('photo' . $i);
                $photo->save();
            }
        }

        for ($i = 1; $i <= 5; $i++) {
            if ($request->getParameter('desperfecto' . $i) != null) {
                $q = Doctrine_Query::create()->from('Photo')
                        ->where('Photo.Type = ?', 'desperfecto' . $i)
                        ->andWhere('Photo.Car.Id = ?', $car->getId());
                $photo = $q->fetchOne();

                if (!$photo) {
                    $photo = new Photo;
                }
                $photo->setCar($car);
                $photo->setPath($request->getParameter('desperfecto' . $i));
                $photo->setType('desperfecto' . $i);
                $photo->save();
            }
        }



        $av = new Availability;
        
        $date = new DateTime('9999-12-31 23:59:59');

        $av->setDateFrom(date("Y-m-d H:i:s"));
        $av->setDateTo($date->format('Y-m-d H:i:s'));
        $av->setHourFrom("00:00:00");
        $av->setHourTo("23:59:00");
        $av->setCar($car);

        $av->save();


        $this->redirect('profile/cars');
    }

    public function executeDoUpdateCar(sfWebRequest $request) {
        $car = Doctrine_Core::getTable('Car')->find(array($request->getParameter('id')));
        $car->setPricePerDay(floor($request->getParameter('priceday')));
        $car->setPricePerHour(floor($request->getParameter('pricehour')));
        $car->setYear($request->getParameter('year'));
		$car->setPatente($request->getParameter('patente'));
		$car->setTipoBencina($request->getParameter('tipobencina'));
        $car->setKm($request->getParameter('km'));
        $car->setAddress($request->getParameter('address'));
        $model = Doctrine_Core::getTable('Model')->find(array($request->getParameter('model')));
        $car->setModel($model);
        $city = Doctrine_Core::getTable('City')->find(array($request->getParameter('city')));
        $car->setCity($city);
        $car->setLng($request->getParameter('lng'));
        $car->setLat($request->getParameter('lat'));
        $car->setDescription($request->getParameter('description'));
        $car->save();

		if($request->getParameter('danos') != '') {
		
			Doctrine_Core::getTable('Damage')->findByCarDelete($request->getParameter('id'));
			
			$danos = $request->getParameter('danos');
			$danos = explode(',', $danos);
			foreach($danos as $elem) {
				
				$dano = new Damage();
				$dano->setDescription(trim($elem));
				$dano->setCarId($request->getParameter('id'));
				$dano->save();
			}
		}

        if ($request->getParameter('main') != null) {
            $q = Doctrine_Query::create()->from('Photo')
                    ->where('Photo.Type = ?', 'main')
                    ->where('Photo.Car.Id = ?', $car->getId());
            $main = $q->fetchOne();

            if (!$main) {
                $main = new Photo;
            }
            $main->setCar($car);
            $main->setPath($request->getParameter('main'));
            $main->setType('main');
            $main->save();
        }

        for ($i = 1; $i <= 5; $i++) {
            if ($request->getParameter('photo' . $i) != null) {
                $q = Doctrine_Query::create()->from('Photo')
                        ->where('Photo.Type = ?', 'photo' . $i)
                        ->andWhere('Photo.Car.Id = ?', $car->getId());
                $photo = $q->fetchOne();

                if (!$photo) {
                    $photo = new Photo;
                }
                $photo->setCar($car);
                $photo->setPath($request->getParameter('photo' . $i));
                $photo->setType('photo' . $i);
                $photo->save();
            }
        }

        for ($i = 1; $i <= 5; $i++) {
            if ($request->getParameter('desperfecto' . $i) != null) {
                $q = Doctrine_Query::create()->from('Photo')
                        ->where('Photo.Type = ?', 'desperfecto' . $i)
                        ->andWhere('Photo.Car.Id = ?', $car->getId());
                $photo = $q->fetchOne();

                if (!$photo) {
                    $photo = new Photo;
                }
                $photo->setCar($car);
                $photo->setPath($request->getParameter('desperfecto' . $i));
                $photo->setType('desperfecto' . $i);
                $photo->save();
            }
        }


        $this->redirect('profile/cars');
    }

    public function executeRatings(sfWebRequest $request) {
        $this->user = Doctrine_Core::getTable('user')->find(array($this->getUser()->getAttribute("userid")));
        $reserves = $this->user->getReserves();

        $this->ratings = new Doctrine_Collection('rating');
        foreach ($reserves as $r) {

            if ($r->getRating()->getId() != null) {
                if (!$r->getRating()->getComplete()) {
                    $this->ratings->add($r->getRating());
                }
            }
        }


        $this->myRatings = new Doctrine_Collection('rating');
        $cars = $this->user->getCars();

        foreach ($cars as $c) {
            $carReserves = $c->getReserves();
            foreach ($carReserves as $cr) {
                if ($cr->getRating()->getId() != null)
                    if (!$cr->getRating()->getComplete()) {
                        $this->myRatings->add($cr->getRating());
                    }
            }
        }


        $this->ratingsCompleted = new Doctrine_Collection('rating');

        foreach ($cars as $c) {
            $carReserves = $c->getReserves();
            foreach ($carReserves as $cr) {
                if ($cr->getRating()->getId() != null)
                    if (!$cr->getRating()->getComplete()) {
                        $this->ratingsCompleted->add($cr->getRating());
                    }
            }
        }
    }

    public function executeConfirmMyRating(sfWebRequest $request) {




        $rating = Doctrine_Core::getTable('Rating')->find(array($request->getParameter('id')));
        $rating->setUserOwnerOpnion($request->getParameter('user_owner_opnion'));
        $rating->setIntime($request->getParameter('intime'));
        $rating->setQualified($request->getParameter('qualified'));
        $rating->setKm($request->getParameter('km'));
        $rating->setCleanSatisfied($request->getParameter('satisfied'));
        $rating->setComplete(true);

        $rating->save();

        $this->redirect('profile/ratings');
    }

    public function executeConfirmTheyRating(sfWebRequest $request) {

        $rating = Doctrine_Core::getTable('Rating')->find(array($request->getParameter('id')));
        $rating->setUserOwnerOpnion($request->getParameter('user_owner_opnion'));
        $rating->setIntime($request->getParameter('intime'));
        $rating->setQualified($request->getParameter('qualified'));
        $rating->setKm($request->getParameter('km'));
        $rating->setCleanSatisfied($request->getParameter('satisfied'));

        $rating->setComplete(true);

        $rating->save();

        $this->redirect('profile/ratings');
    }

	/* ejemplo llamado methos gettransaction() y savetransaction() */ 
	public function executeTransaction(sfWebRequest $request) {
		
		try {
			
			/*
			$idtrans = '';
			if($request->getParameter('idtrans')) $idtrans = $request->getParameter('idtrans'); 
			
			$trans = Doctrine_Core::getTable("Transaction")->getTransaction($idtrans);
			
			foreach ($trans as $value) {
				
				echo $value['id'];
			}
			*/

			$idreserve = '';
			if($request->getParameter('idreserve')) $idreserve = $request->getParameter('idreserve'); 
			
			$trans = Doctrine_Core::getTable("Transaction")->getTransactionByReserve($idreserve);
			
			echo $trans['id'];
			
		
		} catch(Exception $e) { die($e); }
		
		throw new sfStopException();
	}
	
	/*
	public function executeSaveTransaction(sfWebRequest $request) {
		
		$idreserve = '';
		if($request->getParameter('idreserve')) $idreserve = $request->getParameter('idreserve'); 
		
		$this->trans = Doctrine_Core::getTable("Transaction")->saveTransaction($idreserve);
	}
	
	public function executePuntoPagos() {
		
		$this->trans = Doctrine_Core::getTable("Transaction")->successTransaction(28, '121389126', 1, 1);
	}
	/**/

    public function executeTransactions(sfWebRequest $request) {
        
        $idUsuario = sfContext::getInstance()->getUser()->getAttribute('userid');
        //$idUsuario = 885;
        //var_dump($idUsuario);die();
        $transactionRenter = Doctrine_Core::getTable("Transaction")->findByUserId($idUsuario);
        $transaccionesRenter = null;
        foreach ($transactionRenter as $i => $tran) {
            //selecciona solo las transacciones pagadas (completed=1)
            if($tran['completed']){
                $idReserve = $tran['Reserve_id'];
                $reserve = Doctrine_Core::getTable("Reserve")->findOneById($idReserve);

                $transaccionesRenter[$i]['fechaDeposito'] = $tran->getDateFormato();
                $transaccionesRenter[$i]['fechaInicio'] = $reserve->getFechaInicio();
                $transaccionesRenter[$i]['horaInicio'] = $reserve->getHoraInicio();
                $transaccionesRenter[$i]['fechaTermino'] = $reserve->getFechaTermino();
                $transaccionesRenter[$i]['horaTermino'] = $reserve->getHoraTermino();

                $precio = $reserve->getPrice();

                $transaccionesRenter[$i]['monto'] = number_format($precio, 0, ',', '.');
                $transaccionesRenter[$i]['comisionArriendas'] = number_format($precio*0.15, 0, ',', '.');
                $transaccionesRenter[$i]['precioSeguro'] = number_format($precio*0.15, 0, ',', '.');
                $transaccionesRenter[$i]['neto'] = number_format($precio*0.7, 0, ',', '.');
            }
        }
        //var_dump($transaccionesRenter);die();
        $this->transaccionesRenter = $transaccionesRenter;

        $claseUsuario = Doctrine_Core::getTable('user')->findOneById($idUsuario);
        $transactionOwner = $claseUsuario->getTransaccionesWithOwner();

        $transaccionesOwner = null;
        foreach ($transactionOwner as $i => $tran) {
            //selecciona solo las transacciones pagadas (completed=1)
            if($tran['completed']){
                $idReserve = $tran['Reserve_id'];
                $reserve = Doctrine_Core::getTable("Reserve")->findOneById($idReserve);

                $transaccionesOwner[$i]['fechaDeposito'] = $tran->getDateFormato();
                $transaccionesOwner[$i]['fechaInicio'] = $reserve->getFechaInicio();
                $transaccionesOwner[$i]['horaInicio'] = $reserve->getHoraInicio();
                $transaccionesOwner[$i]['fechaTermino'] = $reserve->getFechaTermino();
                $transaccionesOwner[$i]['horaTermino'] = $reserve->getHoraTermino();

                $precio = $reserve->getPrice();

                $transaccionesOwner[$i]['monto'] = number_format($precio, 0, ',', '.');
                $transaccionesOwner[$i]['comisionArriendas'] = number_format($precio*0.15, 0, ',', '.');
                $transaccionesOwner[$i]['precioSeguro'] = number_format($precio*0.15, 0, ',', '.');
                $transaccionesOwner[$i]['neto'] = number_format($precio*0.7, 0, ',', '.');
            }
        }
        //var_dump($transaccionesOwner);die();
        $this->transaccionesOwner = $transaccionesOwner;

    }

    public function executeMessages(sfWebRequest $request) {
        /*
          echo $this->messages = $q->getSqlQuery();

          SELECT *
          FROM Message
          WHERE id IN (SELECT MAX(id) FROM Message GROUP BY conversation)
         */

        $q = Doctrine_Query::create()->from('message')
                ->where('message.user_to > ?', $this->getUser()->getAttribute("userid"))
//->andWhere('message.user_from > ?', 2)
                ->andwhere('message.id IN (SELECT MAX(id) FROM Message GROUP BY conversation)');
        $this->messages = $q->fetchArray();
    }

public function executeAgreePdf(sfWebRequest $request)
{

  $carid = $request->getParameter('id');
  
  if($carid) {
 
  $user = Doctrine_Core::getTable('user')->find(array($this->getUser()->getAttribute("userid")));
  $comunaUser = Doctrine_Core::getTable('comunas')->find(array($user->getComuna()));
  $car = Doctrine_Core::getTable('Car')->find(array($carid));
  $dueno = Doctrine_Core::getTable('user')->find(array($car->getUserId()));
  $comunaDueno = Doctrine_Core::getTable('comunas')->find(array($dueno->getComuna()));
  
  $diff = strtotime($request->getParameter('termino')) - strtotime($request->getParameter('inicio'));
  $duration = $diff / 60 / 60;
  $price = $this->calcularMontoTotal($duration, $car->getPricePerHour(), $car->getPricePerDay(), $car->getPricePerWeek(), $car->getPricePerMonth());
  $price = ceil($price * 0.7);
  
  
  $duracion_contrato= ceil($duration);
  $fecha_inicio= $request->getParameter('inicio');
  $fecha_inicio= substr($fecha_inicio,7,2)."-".substr($fecha_inicio,5,2)."-".substr($fecha_inicio,0,3);
  
  //Daños
  $danos = Doctrine_Query::create()->from("Damage")->where("car_id = ? ",$car->getId());
  $dam = array(); foreach ($danos->execute() as $value) { $dam[] = $value; }	
  $listado_danos = implode(', ',$dam);

  $config = sfTCPDFPluginConfigHandler::loadConfig('pdf_configs');
 
  $doc_title    = "test title";
  $doc_subject  = "test description";
  $doc_keywords = "test keywords";
 
  //create new PDF document (document units are set by default to millimeters)
  $pdf = new sfTCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true);
 
  $pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);
 
  //set margins
  $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
 
  //set auto page breaks
  $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
  $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
  $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
  $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO); //set image scale factor
 
  $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
  $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
 
  //initialize document
  $pdf->AliasNbPages();
  $pdf->AddPage();
 
  $pdf->SetFont("FreeSerif", "", 12);
 
  $utf8text = file_get_contents(K_PATH_CACHE. "contrato_arriendas_chile.txt", false); // get utf-8 text form file
  $utf8text = utf8_encode($utf8text);
  
  //Reemplazo variables
  $utf8text = str_replace('[$fecha_arriendo]', date('d/m/Y'), $utf8text);
  $utf8text = str_replace('[$nombre_dueno]', $dueno->getFirstname()." ".$dueno->getLastname(), $utf8text);
  $utf8text = str_replace('[$rut_dueno]', $dueno->getRut(), $utf8text);
  $utf8text = str_replace('[$domicilio_dueno]', $dueno->getAddress(), $utf8text);
  $utf8text = str_replace('[$comuna_dueno]', $comunaDueno->getNombre(), $utf8text);
  $utf8text = str_replace('[$nombre_usuario]', $user->getFirstname()." ".$user->getLastname(), $utf8text);
  $utf8text = str_replace('[$rut_usuario]', $user->getRut(), $utf8text);
  $utf8text = str_replace('[$domicilio_usuario]', $user->getAddress(), $utf8text);
  $utf8text = str_replace('[$comuna_usuario]', $comunaUser->getNombre(), $utf8text);
  $utf8text = str_replace('[$marca_auto]', $car->getModel()->getBrand(), $utf8text);
  $utf8text = str_replace('[$modelo_auto]', $car->getModel(), $utf8text);
  $utf8text = str_replace('[$ano_auto]', $car->getYear(), $utf8text);
  $utf8text = str_replace('[$patente_auto]', $car->getPatente(), $utf8text);
  $utf8text = str_replace('[$listado_danos_usuario]', $listado_danos, $utf8text);
  //$utf8text = str_replace('[$listado_danos_seguro]', $dueno->getRut(), $utf8text);
    $uft8text= str_replace('[$inicio]',$fecha_inicio,$utf8text);
  $uft8text= str_replace('[$duracion]',$duracion_contrato,$utf8text);
  $utf8text = str_replace('[$precio_arriendo]', '$ '.number_format($price, 0, ',', '.'), $utf8text);
  
  $pdf->SetFillColor(255, 255, 255, true);
  $pdf->Write(5,$utf8text, '', 1);
 
  // Close and output PDF document
  $pdf->Output('contrato_arriendas_'.date('dmY').'pdf', 'I');
 
  // Stop symfony process
  throw new sfStopException();
  
  }
}

    
    
public function executeAgreePdf2(sfWebRequest $request)
{

    //Se modifica la lógica de generación del contrato, de formq que el parámetro recibo sea el ID de la reserva,
    //no el del auto
    /*  Inicio de la modificacion
  $carid = $request->getParameter('id');
  
  if($carid) {
 
  $user = Doctrine_Core::getTable('user')->find(array($this->getUser()->getAttribute("userid")));
  $comunaUser = Doctrine_Core::getTable('comunas')->find(array($user->getComuna()));
  $car = Doctrine_Core::getTable('Car')->find(array($carid));
  $dueno = Doctrine_Core::getTable('user')->find(array($car->getUserId()));
  $comunaDueno = Doctrine_Core::getTable('comunas')->find(array($dueno->getComuna()));
  
  Fin de la modificacion
  */
    $reserveid= $request->getParameter('id');
    $reserve= ReserveTable::getInstance()->findById($reserveid);
    $userId= $reserve[0]->getUserId();
    $carid= $reserve[0]->getCarId();
  
  
  $user = Doctrine_Core::getTable('user')->find($userId);
  $comunaUser = Doctrine_Core::getTable('comunas')->find(array($user->getComuna()));
  $car = Doctrine_Core::getTable('Car')->find(array($carid));
  $dueno = Doctrine_Core::getTable('user')->find(array($car->getUserId()));
  $comunaDueno = Doctrine_Core::getTable('comunas')->find(array($dueno->getComuna()));
  
  $diff = strtotime($request->getParameter('termino')) - strtotime($request->getParameter('inicio'));
  $duration = $diff / 60 / 60;
  $price = $this->calcularMontoTotal($duration, $car->getPricePerHour(), $car->getPricePerDay(), $car->getPricePerWeek(), $car->getPricePerMonth());
  $price = ceil($price * 0.7);
  
  $duracion_contrato= ceil($duration);
  $fecha_inicio= $request->getParameter('inicio');
  $fecha_inicio= substr($fecha_inicio,7,2)."-".substr($fecha_inicio,5,2)."-".substr($fecha_inicio,0,3);
  
  //Daños
  $danos = Doctrine_Query::create()->from("Damage")->where("car_id = ? ",$car->getId());
  $dam = array(); foreach ($danos->execute() as $value) { $dam[] = $value; }	
  $listado_danos = implode(', ',$dam);

  $config = sfTCPDFPluginConfigHandler::loadConfig('pdf_configs');
 
  $doc_title    = "test title";
  $doc_subject  = "test description";
  $doc_keywords = "test keywords";
 
  //create new PDF document (document units are set by default to millimeters)
  $pdf = new sfTCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true);
 
  $pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);
 
  //set margins
  $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
 
  //set auto page breaks
  $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
  $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
  $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
  $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO); //set image scale factor
 
  $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
  $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
 
  //initialize document
  $pdf->AliasNbPages();
  $pdf->AddPage();
 
  $pdf->SetFont("FreeSerif", "", 12);
 
  $utf8text = file_get_contents(K_PATH_CACHE. "contrato_arriendas_chile.txt", false); // get utf-8 text form file
  $utf8text = utf8_encode($utf8text);
  
  //Reemplazo variables
  $utf8text = str_replace('[$fecha_arriendo]', date('d/m/Y'), $utf8text);
  $utf8text = str_replace('[$nombre_dueno]', $dueno->getFirstname()." ".$dueno->getLastname(), $utf8text);
  $utf8text = str_replace('[$rut_dueno]', $dueno->getRut(), $utf8text);
  $utf8text = str_replace('[$domicilio_dueno]', $dueno->getAddress(), $utf8text);
  $utf8text = str_replace('[$comuna_dueno]', $comunaDueno->getNombre(), $utf8text);
  $utf8text = str_replace('[$nombre_usuario]', $user->getFirstname()." ".$user->getLastname(), $utf8text);
  $utf8text = str_replace('[$rut_usuario]', $user->getRut(), $utf8text);
  $utf8text = str_replace('[$domicilio_usuario]', $user->getAddress(), $utf8text);
  $utf8text = str_replace('[$comuna_usuario]', $comunaUser->getNombre(), $utf8text);
  $utf8text = str_replace('[$marca_auto]', $car->getModel()->getBrand(), $utf8text);
  $utf8text = str_replace('[$modelo_auto]', $car->getModel(), $utf8text);
  $utf8text = str_replace('[$ano_auto]', $car->getYear(), $utf8text);
  $utf8text = str_replace('[$patente_auto]', $car->getPatente(), $utf8text);
  $utf8text = str_replace('[$listado_danos_usuario]', $listado_danos, $utf8text);
  //$utf8text = str_replace('[$listado_danos_seguro]', $dueno->getRut(), $utf8text);
  $utf8text = str_replace('[$precio_arriendo]', '$ '.number_format($price, 0, ',', '.'), $utf8text);
  $uft8text = str_replace('[$fecha_inicio]', $fecha_inicio, $utf8text);
  $uft8text = str_replace('[$duracion]', $duracion_contrato, $utf8text);
  
  $pdf->SetFillColor(255, 255, 255, true);
  $pdf->Write(5,$utf8text, '', 1);
 
  // Close and output PDF document
  $pdf->Output('contrato_arriendas_'.date('dmY').'pdf', 'I');
 
  // Stop symfony process
  throw new sfStopException();
  

}

     public function executeReserve(sfWebRequest $request) {

	 if ($this->getRequest()->getParameter('carid') != null)
            $carid = $this->getRequest()->getParameter('carid');
        else
            $carid = $request->getParameter('id');

		$this->reserve = NULL;
		if( $request->getParameter('idreserve') ) {

	        $q = Doctrine_Query::create()
	                ->select('r.id , r.date date, ADDDATE(r.date, INTERVAL r.duration HOUR) fechafin')
	                ->from('Reserve r')
	                ->where('r.id = ?', $request->getParameter('idreserve'));
	        $this->reserve = $q->fetchArray();
		}

        $this->car = Doctrine_Core::getTable('car')->find(array($carid));

        $this->user = $this->car->getUser();
        $this->getUser()->setAttribute('lastview', $request->getReferer());
		
		$this->fndreserve = array('fechainicio' => '', 'horainicio' => '', 'fechatermino' => '', 'horatermino' => '');
		
		if($this->getUser()->getAttribute("fechainicio")) $this->fndreserve['fechainicio'] = $this->getUser()->getAttribute("fechainicio");
		if($this->getUser()->getAttribute("horainicio")) $this->fndreserve['horainicio'] = $this->getUser()->getAttribute("horainicio");
		if($this->getUser()->getAttribute("fechatermino")) $this->fndreserve['fechatermino'] = $this->getUser()->getAttribute("fechatermino");
		if($this->getUser()->getAttribute("horatermino")) $this->fndreserve['horatermino'] = $this->getUser()->getAttribute("horatermino");

        //obtiene la fecha y hora de la última reserva vigente (a cualquier vehículo)
        $idUsuario = sfContext::getInstance()->getUser()->getAttribute('userid');

        $reservas = Doctrine_Core::getTable("Reserve")->findByUserId($idUsuario);

        $ultimaFechaValida = null;
        $ultimaHoraValida = null;
        $ultimaFecha = null;
        $ultimoId = null;
        $duracion = null;
        foreach ($reservas as $reserva) {
            if(!$ultimaFecha){
                $ultimoId = $reserva->getId();
                $ultimaFecha = strtotime($reserva->getDate());
                $duracion = $reserva->getDuration();
            }else{
                if($ultimoId<$reserva->getId()){
                    $ultimoId = $reserva->getId();
                    $ultimaFecha = strtotime($reserva->getDate());
                    $duracion = $reserva->getDuration();
                }
            }
        }

        //verifica que la fecha sea mayor a la fecha actual
        if($ultimaFecha){
            $fechaActual = $this->formatearHoraChilena(strftime("%Y-%m-%d %H:%M:%S"));
            $fechaAlmacenadaDesde = date("YmdHis",$ultimaFecha);
            //echo $fechaActual." ".$fechaAlmacenada;
            if($fechaActual < $fechaAlmacenadaDesde){

                $this->ultimaFechaValidaDesde = date("d-m-Y",$ultimaFecha);
                $this->ultimaHoraValidaDesde = date("H:i:s",$ultimaFecha);
                $this->ultimaFechaValidaHasta = date("d-m-Y",$ultimaFecha+$duracion*3600);
                $this->ultimaHoraValidaHasta = date("H:i:s",$ultimaFecha+$duracion*3600);
            }
        }


        /////información del auto
        //$this->car = Doctrine_Core::getTable('car')->find($carid);

        $this->df = ''; if($request->getParameter('df')) $this->df = $request->getParameter('df');
        $this->hf = ''; if($request->getParameter('hf')) $this->df = $request->getParameter('hf');
        $this->dt = ''; if($request->getParameter('dt')) $this->df = $request->getParameter('dt');
        $this->ht = ''; if($request->getParameter('ht')) $this->df = $request->getParameter('ht');

        $this->user = $this->car->getUser();
        $this->aprobacionDuenio = $this->user->getPorcentajeAprobacion();
        $this->velocidadMensajes = $this->user->getVelocidadRespuesta_mensajes();

        
        $this->primerNombre = ucwords(current(explode(' ' ,$this->user->getFirstname())));
        $this->inicialApellido = ucwords(substr($this->user->getLastName(), 0, 1)).".";
        //Modificaci—n para llevar el conteo de la cantidad de consulas que recibe el perfil del auto
        $q= Doctrine_Query::create()
            ->update("car")
            ->set("consultas","consultas + 1")
            ->where("id = ?",$request->getParameter('id'));
        $q->execute();

        //Cargamos las fotos por defecto de los autos
        //duplicated query
		//Doctrine_Core::getTable('car')->find(array($request->getParameter('id')));
		$auto= $this->car; 
        $id_comuna=$auto->getComunaId($request->getParameter('id'));

        if($id_comuna){
            $comuna = Doctrine_Core::getTable('comunas')->findOneByCodigoInterno($id_comuna);
            if($comuna->getNombre() == null){
                $this->nombreComunaAuto = ", ".$auto->getCity().".";
            }else{
                $comuna=strtolower($comuna->getNombre());
                $comuna=ucwords($comuna);
                //$this->nombreComunaAuto =", ".$comuna.", ".$auto->getCity();
                $this->nombreComunaAuto =", ".$comuna.".";
            }
        }else{
            $this->nombreComunaAuto = "";
        }
        $arrayImagenes = null;
        $arrayDescripcion = null;
        $i=0;
        if($auto->getSeguroFotoFrente() != null && $auto->getSeguroFotoFrente() != "") {
            $rutaFotoFrente=$auto->getSeguroFotoFrente();
            $arrayImagenes[$i] = $rutaFotoFrente;
            $arrayDescripcion[$i] = "Foto Frente";
            $i++;
        }
        if($auto->getSeguroFotoCostadoDerecho() != null && $auto->getSeguroFotoCostadoDerecho() != "") {
            $rutaFotoCostadoDerecho=$auto->getSeguroFotoCostadoDerecho();
            $arrayImagenes[$i] = $rutaFotoCostadoDerecho;
            $arrayDescripcion[$i] = "Foto Costado Derecho";
            $i++;
        }
        if(strpos($auto->getSeguroFotoCostadoIzquierdo(),"http")!=-1 && $auto->getSeguroFotoCostadoIzquierdo() != "") {
            $rutaFotoCostadoIzquierdo=$auto->getSeguroFotoCostadoIzquierdo();
            $arrayImagenes[$i] = $rutaFotoCostadoIzquierdo;
            $arrayDescripcion[$i] = "Foto Costado Izquierdo";
            $i++;
        }
        if(strpos($auto->getSeguroFotoTraseroDerecho(),"http")!=-1 && $auto->getSeguroFotoTraseroDerecho() != "") {
            $rutaFotoTrasera= $auto->getSeguroFotoTraseroDerecho();
            $arrayImagenes[$i] = $rutaFotoTrasera;
            $arrayDescripcion[$i] = "Foto Trasera";
            $i++;
        }   
        if(strpos($auto->getTablero(),"http")!=-1 && $auto->getTablero() != "") {
            $rutaFotoPanel=$auto->getTablero();  
            $arrayImagenes[$i] = $rutaFotoPanel;
            $arrayDescripcion[$i] = "Foto del Panel";
            $i++; 
        }
        if(strpos($auto->getAccesorio1(),"http")!=-1 && $auto->getAccesorio1() != "") {
            $rutaFotoAccesorios1= $auto->getAccesorio1();
            $arrayImagenes[$i] = $rutaFotoAccesorios1;
            $arrayDescripcion[$i] = "Foto de Accesorio 1";
            $i++; 
        }  
        if(strpos($auto->getAccesorio2(),"http")!=-1 && $auto->getAccesorio2() != "") {
            $rutaFotoAccesorios2=$auto->getAccesorio2();
            $arrayImagenes[$i] = $rutaFotoAccesorios2;
            $arrayDescripcion[$i] = "Foto de Accesorio 2";
        }
        $this->opcionFotosEnS3 = $auto->getVerificationPhotoS3();
        $this->arrayDescripcionFotos = $arrayDescripcion;
        $this->arrayFotos = $arrayImagenes;
        $arrayFotoDanios = null;
        $arrayDescripcionDanios = null;
        $danios= Doctrine_Core::getTable('damage')->findByCar(array($auto->getId()));
        for($i=0;$i<count($danios);$i++){
            $arrayFotoDanios[$i] = $danios[$i]->getUrlFoto();
            $arrayDescripcionDanios[$i] = $danios[$i]->getDescription();
        }
        $this->arrayFotosDanios = $arrayFotoDanios;
        $this->arrayDescripcionesDanios = $arrayDescripcionDanios;

    }

     public function executePayReserve(sfWebRequest $request) {

		$this->reserve = '';
		$this->hideNoDiscountLabel= $request->getParameter('hideNoDiscountLabel');
		
		if( $request->getParameter('id') ) {
			
			try {

		        $this->reserve = Doctrine_Core::getTable('reserve')->find(array( $request->getParameter('id') ));
		        $this->car = Doctrine_Core::getTable('car')->find(array( $this->reserve->getCarId() ));
				$this->model = Doctrine_Core::getTable('Model')->find(array( $this->car->getModel()->getId() ));
		        $this->user = $this->car->getUser();
				
				$this->trans = Doctrine_Core::getTable("Transaction")->getTransactionByReserve($this->reserve->getId());
				
		        $this->getUser()->setAttribute('lastview', $request->getReferer());
			
				//get number of shares for reserve ID
				$curl = curl_init();
				curl_setopt_array($curl, array(
					CURLOPT_RETURNTRANSFER => 1,
					CURLOPT_URL => 'http://graph.facebook.com/http://arriendas.cl/fb/'.$request->getParameter('id').'/',
					CURLOPT_USERAGENT => 'Codular Sample cURL Request'
				));
				$resp = curl_exec($curl);
				curl_close($curl);
				$response = json_decode($resp);

				if (isset($response->{'shares'})) {
					$this->nShares = $response->{'shares'};
				}else{
					$this->nShares = 0;
				};
				
				//test $this->nShares=0;
				
				if (!$this->nShares) $this->nShares==0;
				if ($this->hideNoDiscountLabel==true){
					$this->trans->setDiscountfb(false);
					$this->trans->setDiscountamount(0);					
					$this->priceMultiply = 1;
				}else{
					if ($this->nShares==0){
						$this->trans->setDiscountfb(false);
						$this->trans->setDiscountamount(0);					
						$this->priceMultiply = 1;
					}else{
						$this->trans->setDiscountfb(true);				
						$this->trans->setDiscountamount($this->reserve->getPrice()*0.05);
						$this->priceMultiply = 0.95;
					};
				};

					$this->trans->save();			
				

                //var_dump($this->reserve);
                //die();

                //$deposito = Doctrine_Core::getTable("liberacionDeposito")->findById(1);
                //$this->monto = $deposito[0]['monto'];
                $this->monto = 5800;
                $this->montoDiaUnico = 5800;
                //$depo = Doctrine_Core::getTable("liberacionDeposito")->findById(2);
                //$this->garantia = $depo[0]['monto'];
                $this->garantia = 122330;

                $idArrendatario = $this->reserve->getUserId();
                $arrendatario = Doctrine_Core::getTable('user')->find($idArrendatario);
                $this->licenseUp = $arrendatario->getDriverLicenseFile();

			} catch(Exception $e) { die($e); }
		}
    }
	 
     public function executeFbDiscount(sfWebRequest $request) {

		$this->reserve = '';
		$this->deposito=$request->getParameter('deposito');
		$this->carMarcaModel = $request->getParameter("carMarcaModel");
		$this->duracionReserva = $request->getParameter("duracionReserva");
		$this->valorTotalActualizado = $request->getParameter("valorTotalActualizado");

		
		if( $request->getParameter('id') ) {
			
			try {

					//check if facebook discount has been used
					$idUsuario = sfContext::getInstance()->getUser()->getAttribute('userid');
					$q = Doctrine_Query::create()
					  ->from('transaction t')
					  ->where('t.user_id = ?', $idUsuario)
					  ->andwhere('t.discountfb = ?', true);
	
						$discountAlready = $q->fetchArray();
	
						if( $discountAlready ) {
							$this->getRequest()->setParameter('hideNoDiscountLabel', true);
							$this->forward('profile', 'payReserve');
						};
					
                //die();
		        $this->reserve = Doctrine_Core::getTable('reserve')->find(array( $request->getParameter('id') ));

                //var_dump($this->reserve);
                //die();
		        $this->car = Doctrine_Core::getTable('car')->find(array( $this->reserve->getCarId() ));
				$this->model = Doctrine_Core::getTable('Model')->find(array( $this->car->getModel()->getId() ));
		        $this->user = $this->car->getUser();
				
				$this->trans = Doctrine_Core::getTable("Transaction")->getTransactionByReserve($this->reserve->getId());
				
		        $this->getUser()->setAttribute('lastview', $request->getReferer());

                //$deposito = Doctrine_Core::getTable("liberacionDeposito")->findById(1);
                //$this->monto = $deposito[0]['monto'];
                $this->monto = 5800;
                $this->montoDiaUnico = 5800;
                //$depo = Doctrine_Core::getTable("liberacionDeposito")->findById(2);
                //$this->garantia = $depo[0]['monto'];
                $this->garantia = 122330;

				$this->deposito = $request->getParameter("deposito");
				$this->montoDeposito = 0;
				if($this->deposito == "depositoGarantia"){
					//$deposito = Doctrine_Core::getTable("liberacionDeposito")->findById(2);
					$this->montoDeposito = 122330;
					$this->enviarCorreoTransferenciaBancaria();
				}else if($this->deposito == "pagoPorDia"){
					//$deposito = Doctrine_Core::getTable("liberacionDeposito")->findById(1);
					$this->montoDeposito = $montoTotalPagoPorDia;
				}
				
	
	$idArrendatario = $this->reserve->getUserId();
                $arrendatario = Doctrine_Core::getTable('user')->find($idArrendatario);
                $this->licenseUp = $arrendatario->getDriverLicenseFile();

			} catch(Exception $e) { die($e); }
		}
    }


		public function executePedidos(sfWebRequest $request){
        //id del usuario actual
        $idUsuario = sfContext::getInstance()->getUser()->getAttribute('userid');
        //$idUsuario = 885;
        //$idUsuario = 79;
        
        //ARRENDATARIO
        $reserve = Doctrine_Core::getTable('reserve')->findByUserId($idUsuario);

        $reservasRealizadas = null;

        foreach ($reserve as $i => $reserva) {

            if($reserva->getVisibleRenter()){

                //obtiene completed de transaction
                $q = "SELECT completed FROM transaction WHERE reserve_id=".$reserva->getId();
                $query = Doctrine_Query::create()->query($q);
                $transaction = $query->toArray();

                $fechaReserva = strtotime($reserva->getDate());
                $fechaActual = strtotime($this->formatearHoraChilena(strftime("%Y-%m-%d %H:%M:%S")));
                $duracion = $reserva->getDuration();

                //obtiene en que estado se encuentra (pagada(3), preaprobada(2), rechazada(1) y espera(0))
                if(isset($transaction[0]) && $transaction[0]['completed']==1){ //la reserva está pagada
                    //$reservasRealizadas[$i]['estado'] = 3;
                    $estado = 3;
                }else if($reserva->getConfirmed()){//la reserva no está pagada
                    //$reservasRealizadas[$i]['estado'] = 2;
                    $estado = 2;
                }else if($reserva->getCanceled()){
                    //$reservasRealizadas[$i]['estado'] = 1;
                    $estado = 1;
                }else{
                    //$reservasRealizadas[$i]['estado'] = 0;
                    $estado = 0;
                }

                //comprueba si muestra o no la reserva, dependiendo del estado
                if(($estado==3 && ($duracion*3600)+$fechaReserva>$fechaActual) || ($estado==2 && $fechaReserva>$fechaActual) || (($estado==1 || $estado==0) && $fechaReserva>$fechaActual)){

                    //obtiene el id de la reserva
                    $reservasRealizadas[$i]['idReserve'] = $reserva->getId();

                    //establece el estado
                    if($estado==3){
                        $reservasRealizadas[$i]['estado'] = 3;
                    }elseif ($estado==2) {
                        $reservasRealizadas[$i]['estado'] = 2;
                    }elseif ($estado==1) {
                        $reservasRealizadas[$i]['estado'] = 1;
                    }else{
                        $reservasRealizadas[$i]['estado'] = 0;
                    }

                    //fecha y hora de inicio y término
                    $reservasRealizadas[$i]['posicion'] = $reserva->getDate();
                    $reservasRealizadas[$i]['fechaInicio'] = $reserva->getFechaInicio();
                    $reservasRealizadas[$i]['horaInicio'] = $reserva->getHoraInicio();
                    $reservasRealizadas[$i]['fechaTermino'] = $reserva->getFechaTermino();
                    $reservasRealizadas[$i]['horaTermino'] = $reserva->getHoraTermino();
                    $reservasRealizadas[$i]['tiempoArriendo'] = $reserva->getTiempoArriendoTexto();
                    $reservasRealizadas[$i]['duracion'] = $reserva->getDuration();
                    $reservasRealizadas[$i]['token'] = $reserva->getToken();

                    //obtiene valor
                    $reservasRealizadas[$i]['valor'] = number_format(intval($reserva->getPrice()),0,'','.');


                    $carId = $reserva->getCarId();

                    $carClass = Doctrine_Core::getTable('car')->findOneById($carId);

                    $propietarioId = $carClass->getUserId();

                    $reservasRealizadas[$i]['carId'] = $carId;
                    $reservasRealizadas[$i]['marca'] = $carClass->getModel()->getBrand();
                    $reservasRealizadas[$i]['modelo'] = $carClass->getModel();
                    $reservasRealizadas[$i]['patente'] = $carClass->getPatente();
                    $reservasRealizadas[$i]['photoType'] = $carClass->getPhotoS3();
                    $typeFoto = $carClass->getPhotoS3();
                    if($typeFoto == 0){
                        $reservasRealizadas[$i]['fotoCar'] = $carClass->getFotoPerfil();
                    }else{
                        $reservasRealizadas[$i]['fotoCar'] = $carClass->getFoto();
                    }
                    $reservasRealizadas[$i]['lat'] = $carClass->getLat();
                    $reservasRealizadas[$i]['lng'] = $carClass->getLng();
                    $reservasRealizadas[$i]['direccion'] = $carClass->getAddress();
                    $reservasRealizadas[$i]['comuna'] = $carClass->getNombreComuna();

                    $propietarioClass = Doctrine_Core::getTable('user')->findOneById($propietarioId);

                    $reservasRealizadas[$i]['contraparteId'] = $propietarioId;
                    $reservasRealizadas[$i]['nombre'] = $propietarioClass->getFirstname();
                    $reservasRealizadas[$i]['apellido'] = $propietarioClass->getLastname();
                    $reservasRealizadas[$i]['telefono'] = $propietarioClass->getTelephone();
                    $reservasRealizadas[$i]['facebook'] = $propietarioClass->getFacebookId();
                    $reservasRealizadas[$i]['urlFoto'] = $propietarioClass->getPictureFile();
                    $reservasRealizadas[$i]['apellidoCorto'] = $reservasRealizadas[$i]['apellido'][0].".";

                }//end if
            }
        }

        //PROPIETARIO $idUsuario

        $reservasRecibidas = null;

        $q = "SELECT r.id FROM reserve r, car c WHERE c.id=r.car_id and c.user_id=$idUsuario";
        $query = Doctrine_Query::create()->query($q);
        $reserve = $query->toArray();

        foreach ($reserve as $i => $reserva) {
            $reserva = Doctrine_Core::getTable('reserve')->findOneById($reserva['id']);

            if($reserva->getVisibleOwner()){

                //obtiene completed de transaction
                $q = "SELECT completed FROM transaction WHERE reserve_id=".$reserva->getId();
                $query = Doctrine_Query::create()->query($q);
                $transaction = $query->toArray();
                
                //obtiene en que estado se encuentra (pagada(3), preaprobada(2), rechazada(1) y espera(0))
                if(isset($transaction[0]) && $transaction[0]['completed']==1){ //la reserva está pagada
                    //$reservasRealizadas[$i]['estado'] = 3;
                    $estado = 3;
                }else if($reserva->getConfirmed()){//la reserva no está pagada
                    //$reservasRealizadas[$i]['estado'] = 2;
                    $estado = 2;
                }else if($reserva->getCanceled()){
                    //$reservasRealizadas[$i]['estado'] = 1;
                    $estado = 1;
                }else{
                    //$reservasRealizadas[$i]['estado'] = 0;
                    $estado = 0;
                }

                //comprueba si muestra o no la reserva, dependiendo del estado
                // 3 -> muestra hasta 1 hora después del término
                // 2 -> muestra hasta 1 hora después del inicio
                // 1 -> muestra hasta la hora de inicio
                // 0 -> muestra hasta la hora de inicio

                $fechaReserva = strtotime($reserva->getDate());
                $fechaActual = strtotime($this->formatearHoraChilena(strftime("%Y-%m-%d %H:%M:%S")));

                $duracion = $reserva->getDuration();

                if(($estado==3 && ($duracion*3600)+$fechaReserva>$fechaActual) || ($estado==2 && $fechaReserva>$fechaActual) || (($estado==1 || $estado==0) && $fechaReserva>$fechaActual)){

                    //obtiene el id de la reserva
                    $reservasRecibidas[$i]['idReserve'] = $reserva->getId();

                    //establece el estado
                    if($estado==3){
                        $reservasRecibidas[$i]['estado'] = 3;
                    }elseif ($estado==2) {
                        $reservasRecibidas[$i]['estado'] = 2;
                    }elseif ($estado==1) {
                        $reservasRecibidas[$i]['estado'] = 1;
                    }else{
                        $reservasRecibidas[$i]['estado'] = 0;
                    }

                    //fecha y hora de inicio y término
                    $reservasRecibidas[$i]['posicion'] = $reserva->getDate();
                    $reservasRecibidas[$i]['fechaInicio'] = $reserva->getFechaInicio();
                    $reservasRecibidas[$i]['horaInicio'] = $reserva->getHoraInicio();
                    $reservasRecibidas[$i]['fechaTermino'] = $reserva->getFechaTermino();
                    $reservasRecibidas[$i]['horaTermino'] = $reserva->getHoraTermino();
                    $reservasRecibidas[$i]['tiempoArriendo'] = $reserva->getTiempoArriendoTexto();
                    $reservasRecibidas[$i]['duracion'] = $reserva->getDuration();
                    $reservasRecibidas[$i]['token'] = $reserva->getToken();

                    //obtiene valor
                    $reservasRecibidas[$i]['valor'] = number_format(intval($reserva->getPrice()),0,'','.');


                    $carId = $reserva->getCarId();

                    $carClass = Doctrine_Core::getTable('car')->findOneById($carId);

                    $propietarioId = $reserva->getUserId();

                    $reservasRecibidas[$i]['carId'] = $carId;
                    $reservasRecibidas[$i]['marca'] = $carClass->getModel()->getBrand();
                    $reservasRecibidas[$i]['modelo'] = $carClass->getModel();
                    $reservasRecibidas[$i]['patente'] = $carClass->getPatente();
                    $reservasRecibidas[$i]['photoType'] = $carClass->getPhotoS3();
                    $typeFoto = $carClass->getPhotoS3();
                    if($typeFoto == 0){
                        $reservasRecibidas[$i]['fotoCar'] = $carClass->getFotoPerfil();
                    }else{
                        $reservasRecibidas[$i]['fotoCar'] = $carClass->getFoto();
                    }
                    //$reservasRecibidas[$i]['lat'] = $carClass->getLat();
                    //$reservasRecibidas[$i]['lng'] = $carClass->getLng();
                    //$reservasRecibidas[$i]['direccion'] = $carClass->getAddress();

                    $propietarioClass = Doctrine_Core::getTable('user')->findOneById($propietarioId);

                    $reservasRecibidas[$i]['contraparteId'] = $propietarioId;
                    $reservasRecibidas[$i]['nombre'] = $propietarioClass->getFirstname();
                    $reservasRecibidas[$i]['apellido'] = $propietarioClass->getLastname();
                    $reservasRecibidas[$i]['telefono'] = $propietarioClass->getTelephone();
                    $reservasRecibidas[$i]['direccion'] = $propietarioClass->getAddress();
                    $reservasRecibidas[$i]['facebook'] = $propietarioClass->getFacebookId();
                    $reservasRecibidas[$i]['urlFoto'] = $propietarioClass->getPictureFile();
                    $reservasRecibidas[$i]['apellidoCorto'] = $reservasRecibidas[$i]['apellido'][0].".";
                    $reservasRecibidas[$i]['comuna'] = $propietarioClass->getNombreComuna();

                }//end if
            }
        }

        //quita los arreglos null
        if(isset($reservasRealizadas)){
            $reservasRealizadas = array_values($reservasRealizadas);
        }

        $reservasRealizadasAux = array();
        //ordena los array por fecha
        if(isset($reservasRealizadas)){
            $j = 0;

            do{
                $menor = 0;
                for ($i=1; $i < count($reservasRealizadas); $i++) {
                    if(isset($reservasRealizadas[$i]['posicion'])){
                        if($reservasRealizadas[$i]['posicion']<$reservasRealizadas[$menor]['posicion']){
                            $menor = $i;
                        }
                    }
                }
                $reservasRealizadasAux[$j] = $reservasRealizadas[$menor];

                //elimina la posicion del array
                unset($reservasRealizadas[$menor]);
                if($reservasRealizadas){
                    $reservasRealizadas = array_values($reservasRealizadas);
                }

                $j++;

            }while($reservasRealizadas);
        }

        $reservasRecibidasAux = array();


        //quita los arreglos null
        if(isset($reservasRecibidas)){
            $reservasRecibidas = array_filter($reservasRecibidas);
        }

        //ordena las reservas recibidas
        if(isset($reservasRecibidas)){
            $j = 0;

            do{
                $menor = 0;
                for ($i=0; $i < count($reservasRecibidas); $i++) {

                    if(isset($reservasRecibidas[$menor]['posicion'])){
                        if(isset($reservasRecibidas[$i]) && $reservasRecibidas[$i]['posicion']<$reservasRecibidas[$menor]['posicion']){
                            $menor = $i;
                        }
                    }else{
                        if(isset($reservasRecibidas[$i])){
                            $menor = $i;
                        }
                    }

                }
                if(isset($reservasRecibidas[$menor])) $reservasRecibidasAux[$j] = $reservasRecibidas[$menor];

                //elimina la posicion del array
                unset($reservasRecibidas[$menor]);
                if($reservasRecibidas){
                    $reservasRecibidas = array_values($reservasRecibidas);
                }

                $j++;

            }while($reservasRecibidas);
        }

        //obtiene las fechas de pedidos de arrendatario que se encuentren en espera (estado=0)
        $fechaReservasRealizadas = array();
        if(isset($reservasRealizadasAux)){
            foreach ($reservasRealizadasAux as $i => $reservasRealizadas) {
                if(isset($reservasRealizadas['estado']) && $reservasRealizadas['estado']==0){
                    //almacena las distintas fechas y las almacena en un array con la posicion igual a la de $reservasRealizadasAux

                    if(!$fechaReservasRealizadas){ //no hay elemento
                        $fechaReservasRealizadas[$i]['fechaInicio'] = $reservasRealizadas['fechaInicio'];
                        $fechaReservasRealizadas[$i]['horaInicio'] = $reservasRealizadas['horaInicio'];
                        $fechaReservasRealizadas[$i]['fechaTermino'] = $reservasRealizadas['fechaTermino'];
                        $fechaReservasRealizadas[$i]['horaTermino'] = $reservasRealizadas['horaTermino'];
                        $fechaReservasRealizadas[$i]['cantCar'] = 0;
                    }

                    //verifica que la fecha y hora no se encuentren almacenadas
                    $existe = false;
                    foreach ($fechaReservasRealizadas as $reserva) {
                        if(isset($reserva['fechaInicio'])){
                            if($reserva['fechaInicio']==$reservasRealizadas['fechaInicio'] && $reserva['horaInicio']==$reservasRealizadas['horaInicio'] && $reserva['fechaTermino']==$reservasRealizadas['fechaTermino'] && $reserva['horaTermino']==$reservasRealizadas['horaTermino']){
                                $existe = true;
                            }
                        }
                    }

                    if(!$existe){
                        $fechaReservasRealizadas[$i]['fechaInicio'] = $reservasRealizadas['fechaInicio'];
                        $fechaReservasRealizadas[$i]['horaInicio'] = $reservasRealizadas['horaInicio'];
                        $fechaReservasRealizadas[$i]['fechaTermino'] = $reservasRealizadas['fechaTermino'];
                        $fechaReservasRealizadas[$i]['horaTermino'] = $reservasRealizadas['horaTermino'];
                        $fechaReservasRealizadas[$i]['cantCar'] = 0;
                    }

                }
            }
        }

        foreach ($fechaReservasRealizadas as $i => $fechaReserva) {
            if(isset($fechaReserva['fechaInicio'])){
                foreach ($reservasRealizadasAux as $reserva) {
                    if(isset($fechaReserva['fechaInicio']) && isset($reserva['fechaInicio']) && $fechaReserva['fechaInicio']==$reserva['fechaInicio'] && $fechaReserva['horaInicio']==$reserva['horaInicio'] && $fechaReserva['fechaTermino']==$reserva['fechaTermino'] && $fechaReserva['horaTermino']==$reserva['horaTermino']){
                        if(!isset($fechaReserva['cantCar'])){
                            $fechaReservasRealizadas[$i]['cantCar'] = 1;
                        }else{
                            $fechaReservasRealizadas[$i]['cantCar']++;
                        }
                    }
                }
            }
        }

        /*
        foreach ($reservasRealizadasAux as $reserva) {
            echo $reserva['idReserve']." ".$reserva['estado']."<br>";
        }
        die();
        */
        
        $this->fechaReservasRealizadas = $fechaReservasRealizadas;
        $this->reservasRealizadas = $reservasRealizadasAux;
        $this->reservasRecibidas = $reservasRecibidasAux;
    }

    public function executeAddCarExito(sfWebRequest $request){

		$idCar = $request->getParameter('id');
        $idUsuario = sfContext::getInstance()->getUser()->getAttribute('userid');
        $usuario = Doctrine_Core::getTable('user')->findOneById($idUsuario);
			$correo = $usuario->getEmail();
			$name = $usuario->getFirstname();
			$lastname = $usuario->getLastname();
			$telephone = $usuario->getTelephone();
			$direccion = $usuario->getAddress();



			$car = Doctrine_Core::getTable('car')->findOneById($idCar);
			$brand = $car->getMarcaModelo();
			$patente = $car->getPatente();
			$comuna = $car->getNombreComuna();

		$usuario->setPropietario(true);
   	    $this->getUser()->setAttribute("propietario",true);			    
		$usuario->save();
		
//		$this->logMessage($usuario->getPropietario());
	
        require sfConfig::get('sf_app_lib_dir')."/mail/mail.php";
        $mail = new Email();
        $mail->setSubject('Responde este e-mail para subir tu '.$brand.' ('.$patente.') en Arriendas.cl');
        $mail->setBody("<p>Hola $name,</p>
		<p>Has subido un auto!</p>
		<p>Para verlo publicado responde a este correo escribiendo tu DIRECCION, COMUNA y NUMERO DE CELULAR.</p>
		<p>Ante cualquier duda, llámanos al 2333-3714.</p>");
        $mail->setTo($correo);
        $mail->setCc('soporte@arriendas.cl');
        $mail->submit();
		
		
		
        $this->emailUser = $correo;
        $this->partes = $this->partesAuto();
        $this->nombresPartes = $this->nombrePartesAuto();
        $this->id = $idCar;

    }

    public function executeAddCarExitoFotos(sfWebRequest $request){
        $idCar = $request -> getParameter('id');
        $this->idAuto = $idCar;
        $this->fotosPartes = array();
        $photoCounter = $this->photoCounter();

        //nos aseguramos que seguro_ok tenga un valor. Se actualiza a 3 si tiene mas de 4 fotos, en caso contrario conserva el valor que tiene almacenado    
        $ok = ($photoCounter >= 4 ) ? 3 : 5;

        //actualiza los datos asociados al vehículo, por medio de la $idCar
        $q = Doctrine_Manager::getInstance()->getCurrentConnection();
        $query = "update arriendas.Car set seguro_ok='$ok' where id=$idCar";
        $result = $q->execute($query);

        $this->redirect('profile/cars');
    }

    public function executeAddCar(sfWebRequest $request) {
        $idCar = $request -> getParameter('id');
        $this->idAuto = $idCar;
        //obtener url absoluta
        sfContext::getInstance()->getConfiguration()->loadHelpers(array('Url'));
        $this->urlAbsoluta = url_for('main/priceJson');
        
        $this->partes = $this->partesAuto();
        $this->nombresPartes = $this->nombrePartesAuto();
        

        if($idCar!=""){

            sfContext::getInstance()->getConfiguration()->loadHelpers(array('Url'));
            $url = public_path("/uploads/cars/");
            
            $q = "SELECT * FROM car WHERE id=$idCar";
            
            $query = Doctrine_Query::create()->query($q);
            $car = $query->toArray();
            $car[0]['url'] = $url;

            //selecciona el id de la marca a partir del modelo
            $model = $car[0]['Model_id'];
            $q = "SELECT brand_id FROM model WHERE id=$model";
            $query = Doctrine_Query::create()->query($q);
            $modelo = $query->toArray();

            $car[0]['marca'] = $modelo[0]['brand_id'];

            $codigoInterno = $car[0]['comuna_id'];

            //obtiene el nombre de la comuna dada la comuna_id
            $q = "SELECT nombre FROM comunas WHERE codigoInterno=$codigoInterno";
            $query = Doctrine_Query::create()->query($q);
            $comuna = $query->toArray();

            if(isset($comuna[0]['nombre'])){
                $car[0]['nombreComuna'] = $comuna[0]['nombre'];
            }

            $this->car = $car;
        
             //Generamos la ruta para cargar la foto del vehículo si esta en el S3
             $posicion= strpos($car[0]['foto_perfil'],"http");
             if($posicion != 1) {
                    $this->rutaArchivo= $car[0]['foto_perfil'];
             } else {
                    $this->rutaArchivo= "http://www.arriendas.cl/images/img_publica_auto/AutoVistaAerea.png";
             }

            //Forma Miguel de subir la foto del auto
            $claseCar = Doctrine_Core::getTable('car')->findOneById($idCar);
            $this->auto = $claseCar;

            //Cargamos las fotos por defecto de los autos
            $this->fotosPartes = array();

            $photoCounter = $this->photoCounter();

        }else{
            $this->car = null;
            $this->rutaArchivo= "http://www.arriendas.cl/images/img_publica_auto/AutoVistaAerea.png";

            $this->auto = null;
        }

        //Doctrine_Core::getTable('Car')->find(array($request->getParameter('id')));

        $this->brand = $this->ordenarBrand(Doctrine_Core::getTable('Brand')->createQuery('a')->execute());

        $this->comunas = Doctrine_Core::getTable('Comunas')->createQuery('a')->execute();
        $this->getResponse()->setHttpHeader('Access-Control-Allow-Origin', '*');

//      
//      
    }
    public function ordenarBrand($brand){

        $brandOrdenados = array();
        for($i=0;$i<count($brand);$i++){
            $brandOrdenados[$i] = $brand[$i]->getName();
        }
        sort($brandOrdenados);

        $brandOrdenadosConId = array();
        $k=0;
        for($i=0;$i<count($brandOrdenados);$i++){
            for($j=0;$j<count($brand);$j++){
                if($brandOrdenados[$i]==$brand[$j]->getName()){
                    $brandOrdenadosConId[$k]['name']=$brand[$j]->getName();
                    $brandOrdenadosConId[$k]['id']=$brand[$j]->getId();
                    $k++;
                }
            }
        }

        return $brandOrdenadosConId;

    }

    public function executeAddAvailability(sfWebRequest $request) {

        $this->user = Doctrine_Core::getTable('user')->find(array($this->getUser()->getAttribute("userid")));
        $this->cars = $this->user->getCars();
    }

    public function executeAprobarKm(sfWebRequest $request) {
        $id = $request->getParameter('id');
        $tipo = $request->getParameter('tipo');

        try {

            $reserva = Doctrine_Core::getTable('reserve')->find(array($id));

            if ($reserva->getUserId() != $this->getUser()->getAttribute('userid')) {

                if ($tipo == 'inicial') {
                    $reserva->setIniKmOwnerConfirmed(true);
                } else {
                    $reserva->setEndKmOwnerConfirmed(true);
                }
            } else {
                if ($tipo == 'inicial') {
                    $reserva->setIniKmConfirmed(true);
                } else {
                    $reserva->setEndKmConfirmed(true);
                }
            }

            $reserva->save();

            echo 'OK';
        } catch (Exception $e) {
            echo 'Error ' . $e->getMessage();
        }

        return sfView::NONE;
    }

    public function executeDesaprobarKm(sfWebRequest $request) {
        $id = $request->getParameter('id');
        $tipo = $request->getParameter('tipo');

        try {

            $reserva = Doctrine_Core::getTable('reserve')->find(array($id));
            

            if ($tipo == 'inicial') {
                $reserva->setIniKmOwnerConfirmed(false);
                $reserva->setIniKmConfirmed(false);
                $reserva->setKminicial(0);

            } else {
                $reserva->setEndKmOwnerConfirmed(false);
                $reserva->setEndKmConfirmed(false);
                $reserva->setKmfinal(0);
            }

            if ($this->getUser()->getAttribute('userid') == $reserva->getCar()->getUserId()){
                $this->sendConfirmKmsEmail($reserva, 'usuario');
            } else {
                $this->sendConfirmKmsEmail($reserva, 'owner');
            }
            $reserva->save();

            echo 'OK';
        } catch (Exception $e) {
            echo 'Error ' . $e->getMessage();die;
        }

        return sfView::NONE;
    }

    /* public function executeRentings(sfWebRequest $request)
      {

      $this->car = Doctrine_Core::getTable('car')->find(array($request->getParameter('id')));

      }
     */

    public function executeRental(sfWebRequest $request) {

        $q = Doctrine_Query::create()
                ->select('r.id , ca.id idcar , mo.name model, 
	  br.name brand, ca.year year, 
	  ca.address address, ci.name city, 
	  st.name state, co.name country, 
	  user.firstname firstname, user.lastname lastname,
	  r.date date, ADDDATE(r.date, INTERVAL r.duration HOUR) fechafin'
                )
                ->from('Reserve r')
                ->innerJoin('r.Car ca')
                ->innerJoin('ca.Model mo')
                ->innerJoin('ca.User owner')
                ->innerJoin('r.User user')
                ->innerJoin('mo.Brand br')
                ->innerJoin('ca.City ci')
                ->innerJoin('ci.State st')
                ->innerJoin('st.Country co')
                ->where('owner.id = ?', $this->getUser()->getAttribute("userid"))
                ->andWhere('r.confirmed = ?', true)
                ->andWhere('r.complete = ?', false)
                ->andWhere('r.canceled = ?', false)
                ->andWhere('r.ini_km_owner_confirmed = ?', true)
                ->andWhere('r.ini_km_confirmed = ?', true);

//->andWhere('r.date > ?',  date('Y-m-d H:i:s.u'));
        $this->ownerinprogress = $q->execute();

        $q = Doctrine_Query::create()
                ->select('r.id , ca.id idcar , mo.name model, 
	  br.name brand, ca.year year, 
	  ca.address address, ci.name city, 
	  st.name state, co.name country, 
	  user.firstname firstname, user.lastname lastname,
	  r.date date, ADDDATE(r.date, INTERVAL r.duration HOUR) fechafin'
                )
                ->from('Reserve r')
                ->innerJoin('r.Car ca')
                ->innerJoin('ca.Model mo')
                ->innerJoin('ca.User owner')
                ->innerJoin('r.User user')
                ->innerJoin('mo.Brand br')
                ->innerJoin('ca.City ci')
                ->innerJoin('ci.State st')
                ->innerJoin('st.Country co')
                ->where('owner.id = ?', $this->getUser()->getAttribute("userid"))
                ->andWhere('r.confirmed = ?', true)
                ->andWhere('r.complete = ?', false)
                ->andWhere('r.canceled = ?', false)
                ->andWhere('(r.ini_km_owner_confirmed = ?', false)
                ->orWhere('r.ini_km_confirmed = ?)', false);
        $this->cars = $q->execute();

        try {
            $q = Doctrine_Query::create()
                    ->select('r.id , ca.id idcar , mo.name model, 
	  br.name brand, ca.year year, 
	  ca.address address, ci.name city, 
	  st.name state, co.name country, 
	  user.firstname firstname, user.lastname lastname,
	  r.date date, ADDDATE(r.date, INTERVAL r.duration HOUR) fechafin'
                    )
                    ->from('Reserve r')
                    ->innerJoin('r.Car ca')
                    ->innerJoin('ca.Model mo')
                    ->innerJoin('ca.User owner')
                    ->innerJoin('r.User user')
                    ->innerJoin('mo.Brand br')
                    ->innerJoin('ca.City ci')
                    ->innerJoin('ci.State st')
                    ->innerJoin('st.Country co')
                    ->where('r.user_id = ?', $this->getUser()->getAttribute("userid"))
                    ->andWhere('r.confirmed = ?', true)
                    ->andWhere('r.complete = ?', false)
                    ->andWhere('r.canceled = ?', false)
                    ->andWhere('r.ini_km_owner_confirmed = ?', true)
                    ->andWhere('r.ini_km_confirmed = ?', true);

//->andWhere('r.date > ?',  date('Y-m-d H:i:s.u'));
            $this->inprogress = $q->execute();
        } catch (Exception $e) {
            
        }
		
            $q = Doctrine_Query::create()
                    ->select('r.id , ca.id idcar , mo.name model, 
	  br.name brand, ca.year year, 
	  ca.address address, ci.name city, 
	  st.name state, co.name country, 
	  user.firstname firstname, user.lastname lastname,
	  r.date date, ADDDATE(r.date, INTERVAL r.duration HOUR) fechafin '
                    )
                    ->from('Reserve r')
                    ->innerJoin('r.Car ca')
                    ->innerJoin('ca.Model mo')
                    ->innerJoin('ca.User owner')
                    ->innerJoin('r.User user')
                    ->innerJoin('mo.Brand br')
                    ->innerJoin('ca.City ci')
                    ->innerJoin('ci.State st')
                    ->innerJoin('st.Country co')
                    ->where('owner.id = ?', $this->getUser()->getAttribute("userid"))
                    ->andWhere('r.confirmed = ?', false)
                    ->andWhere('r.complete = ?', false)
                    ->andWhere('r.canceled = ?', false);
            $this->toconfirm = $q->execute();		
    }

    public function executeRentalToConfirm(sfWebRequest $request) {

        try {
            $q = Doctrine_Query::create()
                    ->select('r.id , ca.id idcar , mo.name model, 
	  br.name brand, ca.year year, 
	  ca.address address, ci.name city, 
	  st.name state, co.name country, 
	  user.firstname firstname, user.lastname lastname,
	  r.date date, ADDDATE(r.date, INTERVAL r.duration HOUR) fechafin '
                    )
                    ->from('Reserve r')
                    ->innerJoin('r.Car ca')
                    ->innerJoin('ca.Model mo')
                    ->innerJoin('ca.User owner')
                    ->innerJoin('r.User user')
                    ->innerJoin('mo.Brand br')
                    ->innerJoin('ca.City ci')
                    ->innerJoin('ci.State st')
                    ->innerJoin('st.Country co')
                    ->where('owner.id = ?', $this->getUser()->getAttribute("userid"))
                    ->andWhere('r.confirmed = ?', false)
                    ->andWhere('r.complete = ?', false)
                    ->andWhere('r.canceled = ?', false);
            $this->toconfirm = $q->execute();
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    public function getMes($month) {
        
    }

    private function photoCounter()
    {
        $this->fotosPartes = array();
        $photoCounter = 0;
        $auto= Doctrine_Core::getTable('car')->findOneById($this->idAuto);
        
        if($auto->getSeguroFotoFrente() != null && $auto->getSeguroFotoFrente() != "") {
            $this->fotosPartes['seguroFotoFrente'] = $auto->getSeguroFotoFrente();
            $photoCounter++;
        } else {
            $this->fotosPartes['seguroFotoFrente'] = null;
        }
        if($auto->getSeguroFotoCostadoDerecho() != null && $auto->getSeguroFotoCostadoDerecho() != "") {
            $this->fotosPartes['seguroFotoCostadoDerecho'] = $auto->getSeguroFotoCostadoDerecho(); 
            $photoCounter++;
        } else {
            $this->fotosPartes['seguroFotoCostadoDerecho'] = null;
        }
        if($auto->getSeguroFotoCostadoIzquierdo() != null && $auto->getSeguroFotoCostadoIzquierdo() != "") {
            $this->fotosPartes['seguroFotoCostadoIzquierdo'] = $auto->getSeguroFotoCostadoIzquierdo(); 
            $photoCounter++;
        } else {
            $this->fotosPartes['seguroFotoCostadoIzquierdo'] = null;
        }
        if($auto->getSeguroFotoTraseroDerecho() != null && $auto->getSeguroFotoTraseroDerecho() != "") {
            $this->fotosPartes['seguroFotoTraseroDerecho'] = $auto->getSeguroFotoTraseroDerecho();
            $photoCounter++;
        } else {
            $this->fotosPartes['seguroFotoTraseroDerecho'] = null;
        }   
        if($auto->getTablero() != null && $auto->getTablero() != "") {
            $this->fotosPartes['tablero'] = $auto->getTablero();
            $photoCounter++;   
        } else {
            $this->fotosPartes['tablero'] = null;
        }   
        if($auto->getLlantaDelDer() != null && $auto->getLlantaDelDer() != "") {
            $this->fotosPartes['llanta_del_der'] = $auto->getLlantaDelDer();
            $photoCounter++;
        } else {
            $this->fotosPartes['llanta_del_der'] = null;
        }   
        if($auto->getLlantaDelIzq() != null && $auto->getLlantaDelIzq() != "") {
            $this->fotosPartes['llanta_del_izq'] = $auto->getLlantaDelIzq();
            $photoCounter++;
        } else {
            $this->fotosPartes['llanta_del_izq'] = null;
        }
        if($auto->getLlantaTraDer() != null && $auto->getLlantaTraDer() != "") {
            $this->fotosPartes['llanta_tra_der'] = $auto->getLlantaTraDer(); 
            $photoCounter++;  
        } else {
            $this->fotosPartes['llanta_tra_der'] = null;
        }       
        if($auto->getLlantaTraIzq() != null && $auto->getLlantaTraIzq() != "") {
            $this->fotosPartes['llanta_tra_izq'] = $auto->getLlantaTraIzq();
            $photoCounter++;
        } else {
            $this->fotosPartes['llanta_tra_izq'] = null;
        }       
        if($auto->getRuedaRepuesto() != null && $auto->getRuedaRepuesto() != "") {
            $this->fotosPartes['rueda_repuesto'] = $auto->getRuedaRepuesto();
            $photoCounter++; 
        } else {
            $this->fotosPartes['rueda_repuesto'] = null;
        }
        if($auto->getAccesorio1() != null && $auto->getAccesorio1() != "") {
            $this->fotosPartes['accesorio1'] = $auto->getAccesorio1();
            $photoCounter++;
        } else {
            $this->fotosPartes['accesorio1'] = null;
        }   
        if($auto->getAccesorio2() != null && $auto->getAccesorio2() != "") {
            $this->fotosPartes['accesorio2'] = $auto->getAccesorio2();
            $photoCounter++;
        } else {
            $this->fotosPartes['accesorio2'] = null;
        }   
        if($auto->getPadron() != null && $auto->getPadron() != "") {
            $this->fotosPartes['padron'] = $auto->getPadron();
            $photoCounter++;
        } else {
            $this->fotosPartes['padron']  = null;
        }
        if($auto->getFotoPadronReverso() != null && $auto->getFotoPadronReverso() != "") {
            $this->fotosPartes['foto_padron_reverso'] = $auto->getFotoPadronReverso();
            $photoCounter++;
        } else {
            $this->fotosPartes['foto_padron_reverso']  = null;
        }

        return $photoCounter;
    }

    private function partesAuto()
    {
        return array('seguroFotoFrente','seguroFotoCostadoDerecho','seguroFotoCostadoIzquierdo','seguroFotoTraseroDerecho','tablero','rueda_repuesto','accesorio1', 'accesorio2','padron','foto_padron_reverso');
    }

    private function nombrePartesAuto()
    {
        return array('Foto Frente', 'Foto Costado Derecho', 'Foto Costado Izquierdo', 'Foto Trasera', 'Foto Panel', 'Foto Rueda Repuesto', 'Foto Accesorios 1', 'Foto Accesorios 2', 'Foto Frente Padrón', 'Foto Reverso Padrón');
    }

    public function sendConfirmEmail($reserve) {

		$url = $_SERVER['SERVER_NAME'];
		$url = str_replace('http://', '', $url);
		$url = str_replace('https://', '', $url);

        $to = $reserve->getUser()->getEmail();
        $subject = "Reserva Confirmada";

// compose headers
        $headers = "From: \"Arriendas Reservas\" <no-reply@arriendas.cl>\n";
        $headers .= "Content-type: text/html\n";
        $headers .= "X-Mailer: PHP/" . phpversion() . "\n";

        ereg("([0-9]{2,4})-([0-9]{1,2})-([0-9]{1,2}) ([0-9]{1,2}):([0-9]{1,2}):([0-9]{1,2})", $reserve->getDate(), $datetime);

        $date_from = strftime("%A %e de %B", mktime($datetime[4], $datetime[5], 0, $datetime[2], $datetime[3], $datetime[1]));
        $hour_from = date("H:s", mktime($datetime[4], $datetime[5], 0, $datetime[2], $datetime[3], $datetime[1]));

        $date_to = strftime("%A %e de %B", mktime($datetime[4] + $reserve->getDuration(), $datetime[5], 0, $datetime[2], $datetime[3], $datetime[1]));
        $hour_to = date("H:s", mktime($datetime[4] + $reserve->getDuration(), $datetime[5], 0, $datetime[2], $datetime[3], $datetime[1]));
        $url = 'http://'.$url . $this->getController()->genUrl('profile/publicprofile?id=' . $reserve->getCar()->getUser()->getId());

        $mail = 'Su reserva para el auto ' . $reserve->getCar()->getModel()->getBrand()->getName() . ' ' . $reserve->getCar()->getModel()->getName() .
                ' del usuario ' . $reserve->getCar()->getUser()->getFirstName() . ' ' . $reserve->getCar()->getUser()->getLastName() . ' ha sido confirmada.<br/><br/>
	
	Debes retirar el auto el d&iacute;a <b>' . $date_from . '</b> a las <b>' . $hour_from . '</b> y devolverlo el d&iacute;a <b>' . $date_to . '</b> a las <b>' . $hour_to . '</b>.<br/>
	Recuerda que debes verificar el estado del auto antes de subirte. Si el auto presenta otros daños debes cancelar la reserva. <br /><br />
	
	El equipo de Arriendas.cl
	<br><br>
	<em style="color: #969696">Nota: Para evitar posibles problemas con la recepcion de este correo le aconsejamos nos agregue a su libreta de contactos.</em> 
	';


/*
	Para ver el perfil del due&ntilde;o del auto ' .
                $reserve->getCar()->getUser()->getFirstname() . ' ' . $reserve->getCar()->getUser()->getLastname() . ' has click <a href="' . $url . '">aqui</a>. <br/><br/>
*/ 

// send email
        $this->smtpMail($to, $subject, $mail, $headers);

//MAIL DUEÑO

        $to = $reserve->getCar()->getUser()->getEmail();
        $subject = "Has recibido una reserva!";

// compose headers

		$url = $_SERVER['SERVER_NAME'];
		$url = str_replace('http://', '', $url);
		$url = str_replace('https://', '', $url);

        $headers = "From: \"Arriendas Reservas\" <no-reply@arriendas.cl>\n";
        $headers .= "Content-type: text/html\n";
        $headers .= "X-Mailer: PHP/" . phpversion() . "\n";

        ereg("([0-9]{2,4})-([0-9]{1,2})-([0-9]{1,2}) ([0-9]{1,2}):([0-9]{1,2}):([0-9]{1,2})", $reserve->getDate(), $datetime);

        $date_from = strftime("%A %e de %B", mktime($datetime[4], $datetime[5], 0, $datetime[2], $datetime[3], $datetime[1]));
        $hour_from = date("H:s", mktime($datetime[4], $datetime[5], 0, $datetime[2], $datetime[3], $datetime[1]));

        $date_to = strftime("%A %e de %B", mktime($datetime[4] + $reserve->getDuration(), $datetime[5], 0, $datetime[2], $datetime[3], $datetime[1]));
        $hour_to = date("H:s", mktime($datetime[4] + $reserve->getDuration(), $datetime[5], 0, $datetime[2], $datetime[3], $datetime[1]));
        $url = 'http://'.$url . $this->getController()->genUrl('profile/publicprofile?id=' . $reserve->getUser()->getId());

        $mail = 'Has recibido una reserva por tu ' .
                $reserve->getCar()->getModel()->getBrand()->getName() . ' ' . $reserve->getCar()->getModel()->getName() .
                ' desde el d&iacute;a ' . $date_from . ' a las ' . $hour_from .
                ' hasta el d&iacute;a ' . $date_to . ' a las ' . $hour_to . ' cuando el usuario ' . $reserve->getUser()->getFirstname() . ' ' . $reserve->getUser()->getLastname() . ' devuelva el auto. <br/>'.
		'Recuerda que debes verificar el estado del auto en la devolución. De haber ocurrido daños al auto durante el arriendo, debes informar a Arriendas.cl en el acto.<br /><br />
	
	El equipo de Arriendas.cl
	<br><br>
	<em style="color: #969696">Nota: Para evitar posibles problemas con la recepcion de este correo le aconsejamos nos agregue a su libreta de contactos.</em> 
	';
	
	
	
	/*
		
	Para ver el perfil del usuario ' .
                $reserve->getUser()->getFirstname() . ' ' . $reserve->getUser()->getLastname() . ' has click <a href="' . $url . '">aqui</a>. <br/><br/>
	*/
	

// send email
        $this->smtpMail($to, $subject, $mail, $headers);
    }

    public function sendReserveEmail($reserve) {

        $to = $reserve->getUser()->getEmail();
        $subject = 'Has realizado una reserva!';

// compose headers
        $headers = "From: \"Arriendas Reservas\" <no-reply@arriendas.cl>\n";
        $headers .= "Content-type: text/html\n";
        $headers .= "X-Mailer: PHP/" . phpversion() . "\n";

        $mail = 'Se ha solicitado una reserva para el auto ' . $reserve->getCar()->getModel()->getBrand()->getName() . ' ' . $reserve->getCar()->getModel()->getName() .
                ' del usuario ' . $reserve->getCar()->getUser()->getFirstName() . ' ' . $reserve->getCar()->getUser()->getLastName() . '.
	
	Gracias
	Arriendas.cl
	<br><br>
	<em style="color: #969696">Nota: Para evitar posibles problemas con la recepcion de este correo le aconsejamos nos agregue a su libreta de contactos.</em> 
	';

// send email
        $this->smtpMail($to, $subject, $mail, $headers);

//MAIL DUEÑO

        $to = $reserve->getCar()->getUser()->getEmail();
        $subject = 'Has recibido un pedido de reserva!';

// compose headers
		$url = $_SERVER['SERVER_NAME'];
		$url = str_replace('http://', '', $url);
		$url = str_replace('https://', '', $url);

        $headers = "From: \"Arriendas Reservas\" <no-reply@arriendas.cl>\n";
        $headers .= "Content-type: text/html\n";
        $headers .= "X-Mailer: PHP/" . phpversion() . "\n";

        ereg("([0-9]{2,4})-([0-9]{1,2})-([0-9]{1,2}) ([0-9]{1,2}):([0-9]{1,2}):([0-9]{1,2})", $reserve->getDate(), $datetime);

        $date_from = strftime("%A %e de %B", mktime($datetime[4], $datetime[5], 0, $datetime[2], $datetime[3], $datetime[1]));
        $hour_from = date("H:s", mktime($datetime[4], $datetime[5], 0, $datetime[2], $datetime[3], $datetime[1]));

        $date_to = strftime("%A %e de %B", mktime($datetime[4] + $reserve->getDuration(), $datetime[5], 0, $datetime[2], $datetime[3], $datetime[1]));
        $hour_to = date("H:s", mktime($datetime[4] + $reserve->getDuration(), $datetime[5], 0, $datetime[2], $datetime[3], $datetime[1]));
        $url = 'http://'.$url . $this->getController()->genUrl('profile/publicprofile?id=' . $reserve->getUser()->getId());

		$site = $_SERVER['SERVER_NAME'];
		$site = str_replace('http://', '', $site);
		$site = str_replace('https://', '', $site);

        $mail = 'Has recibido un pedido de reserva por tu ' .
                $reserve->getCar()->getModel()->getBrand()->getName() . ' ' . $reserve->getCar()->getModel()->getName() .
                ' desde el día <b>' . $date_from . '</b> a las <b>' . $hour_from .
                '</b> hasta el día <b>' . $date_to . '</b> a las <b>' . $hour_to . '</b> cuando te habrán devuelto el auto. <br/><br/>
	
		Para ver la reserva has click <a href="http://'.$site . $this->getController()->genUrl('profile/rentalToConfirm').'">aquí</a><br/><br/>
	
	El equipo de Arriendas.cl
	<br><br>
	<em style="color: #969696">Nota: Para evitar posibles problemas con la recepcion de este correo le aconsejamos nos agregue a su libreta de contactos.</em>	
	';
	
	
	/*
		Para ver el perfil del usuario ' .
                $reserve->getUser()->getFirstname() . ' ' . $reserve->getUser()->getLastname() . ' has click <a href="' . $url . '">aqui</a>. <br/><br/>
	*/
	

// send email
        $this->smtpMail($to, $subject, $mail, $headers,"german@arriendas.cl");
    }

    public function sendEndReserveEmail($reserve) {

        $to = $reserve->getUser()->getEmail();
        $subject = 'Finalizacion de Reserva';

// compose headers
        $headers = "From: \"Arriendas Reservas\" <no-reply@arriendas.cl>\n";
        $headers .= "Content-type: text/html\n";
        $headers .= "X-Mailer: PHP/" . phpversion() . "\n";

        $mail = 'Ha finalizado la reseva para el auto ' . $reserve->getCar()->getModel()->getBrand()->getName() . ' ' . $reserve->getCar()->getModel()->getName() .
                ' del usuario ' . $reserve->getCar()->getUser()->getFirstName() . ' ' . $reserve->getCar()->getUser()->getLastName() . ' que usted realizo.
	
	Gracias
	Arriendas.cl
	<br><br>
	<em style="color: #969696">Nota: Para evitar posibles problemas con la recepcion de este correo le aconsejamos nos agregue a su libreta de contactos.</em> 
	';

// send email
        $this->smtpMail($to, $subject, $mail, $headers);

//MAIL ALQUILANDO
//MAIL DUEÑO

        $to = $reserve->getCar()->getUser()->getEmail();
        $subject = 'Finalizacion de Reserva';

// compose headers
        $headers = "From: \"Arriendas Reservas\" <no-reply@arriendas.cl>\n";
        $headers .= "Content-type: text/html\n";
        $headers .= "X-Mailer: PHP/" . phpversion() . "\n";

        $mail = 'Ha solicitado la reserva de su auto ' . $reserve->getCar()->getModel()->getBrand()->getName() . ' ' . $reserve->getCar()->getModel()->getName() .
                ' alquilado por el usuario ' . $reserve->getUser()->getFirstName() . ' ' . $reserve->getUser()->getLastName() . '.
	
	Gracias
	Arriendas.cl
	<br><br>
	<em style="color: #969696">Nota: Para evitar posibles problemas con la recepcion de este correo le aconsejamos nos agregue a su libreta de contactos.</em> 
	';

// send email
        $this->smtpMail($to, $subject, $mail, $headers);
    }

    public function executeCancelReserveConfirmation(sfWebRequest $request) {

        $reserve = Doctrine_Core::getTable('Reserve')->find(array($request->getParameter('id')));
        $reserve->setCanceled(true);
        $reserve->save();
		
        $to = $reserve->getUser()->getEmail();
        $subject = 'Reserva Cancelada';

// compose headers
        $headers = "From: \"Arriendas Reservas\" <no-reply@arriendas.cl>\n";
        $headers .= "Content-type: text/html\n";
        $headers .= "X-Mailer: PHP/" . phpversion() . "\n";

        $mail = 'Su reserva para el auto ' . $reserve->getCar()->getModel()->getBrand()->getName() . ' ' . $reserve->getCar()->getModel()->getName() .
                ' del usuario ' . $reserve->getCar()->getUser()->getFirstName() . ' ' . $reserve->getCar()->getUser()->getLastName() . ' ha sido cancelada.<br/><br/>
	
	El equipo de Arriendas.cl
	<br><br>
	<em style="color: #969696">Nota: Para evitar posibles problemas con la recepcion de este correo le aconsejamos nos agregue a su libreta de contactos.</em> 
	';

// send email
        $this->smtpMail($to, $subject, $mail, $headers);

        $this->redirect('profile/rentalToConfirm');
    }

    public function executeConfirmReserve($idReserve) {

        $reserve = Doctrine_Core::getTable('Reserve')->find(array($idReserve));
        $reserve->setConfirmed(true);
        $reserve->save();

        $transaccion = Doctrine_Core::getTable('Transaction')->findByReserveId(array($idReserve));

        if (!count($transaccion)) {
        	
            $transaction = new Transaction();
            $carString = $reserve->getCar()->getModel()->getName() . " " . $reserve->getCar()->getModel()->getBrand()->getName();
            $transaction->setCar($carString);
            $transaction->setPrice( $reserve->getPrice() );
            $transaction->setUser($reserve->getUser());
            $transaction->setDate(date("Y-m-d H:i:s"));
            $transactionType = Doctrine_Core::getTable('TransactionType')->find(1);
            $transaction->setTransactionType($transactionType);
            $transaction->setReserve($reserve);
            $transaction->setCompleted(false);
            $transaction->save();
        } /*else {

            $q = Doctrine_Query::create()
                    ->update('Transaction t')
                    ->set('t.date', '?', date("Y-m-d H:i:s"))
                    ->where('t.reserve_id = ?', $request->getParameter('id'));
            $q->execute();
        }


        $startDate = date("Y-m-d H:i:s", strtotime($reserve->getDate()));
        $endDate = date("Y-m-d H:i:s", strtotime($reserve->getDate() . " + " . $reserve->getDuration() . " hour"));
        $userid = $reserve->getUser()->getId();

        //$this->sendConfirmEmail($reserve);

        $reserve = Doctrine_Core::getTable('Reserve')
                ->createQuery('a')
                ->where('((a.date <= ? and date_add(a.date, INTERVAL a.duration HOUR) > ?) or (a.date <= ? and date_add(a.date, INTERVAL a.duration HOUR) > ?)) and (a.User.id = ? and a.confirmed = false and a.complete = false)', array($startDate, $startDate, $endDate, $endDate, $userid))
                ->execute();


        foreach ($reserve as $r) {
            Doctrine_Query::create()
                    ->delete()
                    ->from('Transaction')
                    ->andWhere('reserve_id = ?', $r->getId())
                    ->execute();
            $r->delete();
        }

        //$this->redirect('profile/pedidos');
        */
    }

    public function executeCompleteReserve(sfWebRequest $request) {
        $rating = new Rating();
        $rating->save();

        $reserve = Doctrine_Core::getTable('Reserve')->find(array($request->getParameter('id')));
        $reserve->setComplete(true);
        $reserve->setRating($rating);
        $reserve->save();

        $this->sendEndReserveEmail($reserve);

        $this->redirect('profile/pedidos');
    }

    public function executeUpload() {
        $fileName = $this->getRequest()->getFileName('file');

        $this->getRequest()->moveFile('file', sfConfig::get('sf_upload_dir') . '/' . $fileName);

        $this->redirect('media/show?filename=' . $fileName);
    }

    public function executeDoSaveAvailability(sfWebRequest $request) {

        $availibility = new Availability();
        $availibility->setDateFrom($request->getParameter('date_from'));
        $availibility->setDateTo($request->getParameter('date_to'));
        $availibility->setHourFrom($request->getParameter('hour_from'));
        $availibility->setHourTo($request->getParameter('hour_to'));
        $availibility->setDay($request->getParameter('day'));
        $car = Doctrine_Core::getTable('Car')->find(array($request->getParameter('car')));
        $availibility->setCar($car);
        $availibility->save();

        $this->redirect('profile/cars');
    }

    public function executeDoUpdateProfile(sfWebRequest $request) {

        try {

            $profile = Doctrine_Core::getTable('User')->find($this->getUser()->getAttribute('userid'));
            $profile->setFirstname($request->getParameter('firstname'));
            $profile->setLastname($request->getParameter('lastname'));
            $profile->setEmail($request->getParameter('email'));
            $profile->setRegion($request->getParameter('region'));
            $profile->setComuna($request->getParameter('comunas'));
	        $profile->setAddress($request->getParameter('address'));
            $profile->setBirthdate($request->getParameter('birth'));
	        $profile->setApellidoMaterno($request->getParameter('apellidoMaterno'));
	        $profile->setSerieRut($request->getParameter('serie_run'));
    	    if($request->getParameter('password') != '') {
                if ($request->getParameter('password') == $request->getParameter('passwordAgain'))
                    $profile->setPassword(md5($request->getParameter('password')));
			}
            if($profile->getTelephone() != $request->getParameter('telephone')){//Si se ingresa un nuevo telefono celular distinto al de la base de datos, el usuario podrá confirmarlo de nuevo
                $profile->setTelephone($request->getParameter('telephone'));
                $profile->setConfirmedSms(0);
            }else{
                $profile->setTelephone($request->getParameter('telephone'));
            }
            if ($request->getParameter('main') != NULL)
                $profile->setPictureFile($request->getParameter('main'));
            if ($request->getParameter('licence') != NULL)
                $profile->setDriverLicenseFile($request->getParameter('licence'));
			if ($request->getParameter('rut') != NULL)
                $profile->setRutFile($request->getParameter('rut'));
			$profile->setRut($request->getParameter('run'));
            $profile->save();
            $this->getUser()->setAttribute('picture_url', $profile->getFileName());
            $this->getUser()->setAttribute("name", current(explode(' ' , $profile->getFirstName())) . " " . substr($profile->getLastName(), 0, 1) . '.');
            $this->getUser()->setAttribute("picture_url", $profile->getPictureFile());
            $this->getUser()->setAttribute("firstname", $profile->getFirstName());
			$this->getUser()->setAttribute("fecha_registro", $profile->getFechaRegistro());
			$this->getUser()->setAttribute("email", $profile->getEmail());
			$this->getUser()->setAttribute("telephone", $profile->getTelephone());
			$this->getUser()->setAttribute("comuna", $profile->getComuna());
			$this->getUser()->setAttribute("region", $profile->getRegion());
        } catch (Exception $e) {
            echo $e->getMessage();
        }

        if($request->getParameter('redirect') && $request->getParameter('idRedirect')){
            $this->redirect('profile/'.$request->getParameter('redirect')."?id=".$request->getParameter('idRedirect'));
        }else{
            $this->redirect('profile/cars');
        }
    }

    public function executeGetModel(sfWebRequest $request) {

        sfConfig::set('sf_web_debug', false);
        $_output;

        /* Asegurar que la solicitud sea AJAX */
//if (!$request->isXmlHttpRequest())
//return $this->renderText(json_encode(array('error'=>'S�lo respondo consultas v�a AJAX.')));

        if ($request->getParameter('id')) {

            $brand = Doctrine_Core::getTable('Brand')->find(array($request->getParameter('id')));

            foreach ($brand->getModels() as $p) {
                $_output[] = array("optionValue" => $p->getId(), "optionDisplay" => $p->getName());
            }

            return $this->renderText(json_encode($_output));
        }
        return $this->renderText(json_encode(array('error' => 'Faltan par�metros para realizar la consulta')));
    }

    public function executeGetState(sfWebRequest $request) {

        sfConfig::set('sf_web_debug', false);
        $_output;

        /* Asegurar que la solicitud sea AJAX */
//if (!$request->isXmlHttpRequest())
//return $this->renderText(json_encode(array('error'=>'S�lo respondo consultas v�a AJAX.')));

        if ($request->getParameter('id')) {

            $country = Doctrine_Core::getTable('Country')->find(array($request->getParameter('id')));

            foreach ($country->getStates() as $p) {
                $_output[] = array("optionValue" => $p->getId(), "optionDisplay" => $p->getName());
            }

            return $this->renderText(json_encode($_output));
        }
        return $this->renderText(json_encode(array('error' => 'Faltan par&aacute;metros para realizar la consulta')));
    }

    public function executeGetCity(sfWebRequest $request) {

        sfConfig::set('sf_web_debug', false);
        $_output;

        /* Asegurar que la solicitud sea AJAX */
//if (!$request->isXmlHttpRequest())
//return $this->renderText(json_encode(array('error'=>'S�lo respondo consultas v�a AJAX.')));

        if ($request->getParameter('id')) {

            $state = Doctrine_Core::getTable('State')->find(array($request->getParameter('id')));

            foreach ($state->getCities() as $p) {
                $_output[] = array("optionValue" => $p->getId(), "optionDisplay" => $p->getName());
            }

            return $this->renderText(json_encode($_output));
        }
        return $this->renderText(json_encode(array('error' => 'Faltan par�metros para realizar la consulta')));
    }

    public function executeUploadPhotoAjax(sfWebRequest $request) {
        $valid_formats = array("jpg", "png", "gif", "bmp", "jpeg");

        if (isset($_POST) and $_SERVER['REQUEST_METHOD'] == "POST") {
            $name = $_FILES[$request->getParameter('file')]['name'];
            $size = $_FILES[$request->getParameter('file')]['size'];
            $tmp = $_FILES[$request->getParameter('file')]['tmp_name'];
            
            if (strlen($name)) {
                list($txt, $ext) = explode(".", $name);
                if (in_array($ext, $valid_formats) || 1==1) {
                    if ($size < (5 * 1024 * 1024)) { // Image size max 1 MB
                        
                        $idAuto = $request -> getParameter('id');
                        $userid = $this->getUser()->getAttribute("userid");
                        $actual_image_name = time() . $userid . "." . $ext;

                        $uploadDir = sfConfig::get("sf_web_dir");
                        $path = $uploadDir . '/uploads/vistas_seguro/';
                        $fileName = $actual_image_name;
                        $nombreTabla = $request->getParameter('photo');
                        
                        $tmp = $_FILES[$request->getParameter('file')]['tmp_name'];
                        //echo "Datos: 1:".$tmp." 2:".$path." 3:".$actual_image_name;
                        if (move_uploaded_file($tmp, $path . $actual_image_name)) {
                            sfContext::getInstance()->getConfiguration()->loadHelpers("Asset");
                            $q = Doctrine_Manager::getInstance()->getCurrentConnection();
                            //echo "<p>Guardando FOTO...</p>";
                            $query = "update arriendas.Car set $nombreTabla='$fileName' where id='$idAuto'";
                            $result = $q->execute($query);
                            
                            echo "<input type='hidden' name='" . $request->getParameter('photo') . "' value='" . $path . $actual_image_name . "'/>";
                            echo "<img src='" . image_path("../uploads/vistas_seguro/" . $actual_image_name) . "' class='preview' height='" . $request->getParameter('height') . "' width='" . $request->getParameter('width') . "' />";
                            
                        }
                        else
                            echo "failed";
                    }
                    else
                        echo "Image file size max 5 MB";
                }
                else
                    echo "Invalid file format..";
            }
            else
                echo "Please select image..!";
            exit;
        }
    }
    
    
   public function executeSubirauto(sfWebRequest $request){


        $idUsuario = sfContext::getInstance()->getUser()->getAttribute('userid');

        /* obtiene los datos del formulario */
        $ubicacion = $request -> getPostParameter('ubicacion');
        $comuna = $request -> getPostParameter('comunaId');
        $lat = $request -> getParameter('lat');
        $lng = $request -> getParameter('lng');
        //$marca = $request -> getPostParameter('brand'); //se deduce del modelo del auto
        $modelo = $request -> getPostParameter('model');
        $anio = $request -> getPostParameter('anio');
        $puertas = $request -> getPostParameter('puertas');
        $transmision = $request -> getPostParameter('transmision');
        $tipoBencina = $request -> getPostParameter('tipoBencina');
        $usosVehiculo = $request -> getPostParameter('usosVehiculo');
        $precioHora = $request -> getPostParameter('precioHora');
        $precioDia = $request -> getPostParameter('precioDia');
        $precioSemana = $request -> getPostParameter('precioSemana');
        $precioMes = $request -> getPostParameter('precioMes');        
		$disponibilidad = $request -> getPostParameter('disponibilidad');
        
		if ($disponibilidad==1){
		$disponibilidadSemana = 1;
        $disponibilidadFinde = 0;
        }elseif ($disponibilidad==2){
		$disponibilidadSemana = 0;
        $disponibilidadFinde = 1;
        }elseif ($disponibilidad==3){
		$disponibilidadSemana = 1;
        $disponibilidadFinde = 1;
        };
		
		$patente = $request -> getPostParameter('patente');
        $color = $request -> getPostParameter('color');

        $fotoPerfilAuto = $request -> getParameter('foto_perfil'); //Se cambió por el metodo utilizado
        $fotoPadronFrente = $request -> getFiles('fotoPadronFrente');
        $fotoPadronReverso = $request -> getFiles('fotoPadronReverso');

        $urlPerfil = $request -> getPostParameter('urlPerfil');
        $urlPadronFrente = $request -> getPostParameter('urlPadronFrente');
        $urlPadronReverso = $request -> getPostParameter('urlPadronReverso');

        $idCar = $request -> getPostParameter('idCar');
        $seguro_ok = $request->getPostParameter('seguro_ok');
        $this->idAuto = $idCar;
        //variables para subir fotos
        $uploadDir = sfConfig::get("sf_web_dir");
        $path = $uploadDir . '/uploads/cars/';

        $creado = false;
        if($idCar!=""){//el vehículo ya está registrado
            $creado = true;
            //elimina campo de la tabla Availability
            $q = Doctrine_Manager::getInstance()->getCurrentConnection();
            $query = "delete from arriendas.Availability where car_id=$idCar";
            $q->execute($query);

            $this->fotosPartes = array();
            $photoCounter = $this->photoCounter();

            //nos aseguramos que seguro_ok tenga un valor. Se actualiza a 3 si tiene mas de 4 fotos, en caso contrario conserva el valor que tiene almacenado    
            $ok = ($photoCounter >= 4 AND $seguro_ok != 4 ) ? 3 : $seguro_ok;

            //actualiza los datos asociados al vehículo, por medio de la $idCar
            $q = Doctrine_Manager::getInstance()->getCurrentConnection();
            $query = "update arriendas.Car set address='$ubicacion', comuna_id='$comuna',
			    model_id='$modelo', year='$anio', doors='$puertas', transmission='$transmision', photoS3='0',
			    tipoBencina='$tipoBencina', uso_vehiculo_id='$usosVehiculo', price_per_hour='$precioHora',price_per_week='$precioSemana',price_per_month='$precioMes',
			    disponibilidad_semana='$disponibilidadSemana' , disponibilidad_finde='$disponibilidadFinde', price_per_day='$precioDia' ,lat=$lat, lng=$lng, patente='$patente', color='$color', seguro_ok='$ok' where id=$idCar";
            $result = $q->execute($query);
            

            //si se ingresa una nueva foto, se almacena y se actualiza el valor en la base de datos, de lo contrario se mantiene el campo
            if($fotoPerfilAuto != ""){
                $q = Doctrine_Manager::getInstance()->getCurrentConnection();
                $query = "update arriendas.Car set foto_perfil='$fotoPerfilAuto' where id=$idCar";
                $result = $q->execute($query);
            }


        }else{//el vehículo no está registrado

            $auto = new Car();

            $auto -> setUserId($idUsuario);
            $auto -> setAddress($ubicacion);
            $auto -> setModelId($modelo);
            $auto -> setLat($lat);
            $auto -> setLng($lng);
            $auto -> setCityId('27'); //falta ciudad en el formulario
            $auto -> setComunaId($comuna);
            $auto -> setSeguroOK('5');
            $auto -> setPhotoS3(0);//guardado de photos en servidor local
            $auto -> setYear($anio);
            $auto -> setDoors($puertas); //fata método doors
            $auto -> setTransmission($transmision); //fata método transmission
            $auto -> setTipoBencina($tipoBencina);
            $auto -> setUsoVehiculoId($usosVehiculo); //falta método usosVehículo
            $auto -> setPricePerHour($precioHora);
            $auto -> setPricePerDay($precioDia);
            $auto -> setPricePerWeek($precioSemana);
            $auto -> setPricePerMonth($precioMes);
            $auto -> setDisponibilidadSemana($disponibilidadSemana);
            $auto -> setDisponibilidadFinde($disponibilidadFinde);

            $auto -> setPatente($patente);
            $auto -> setColor($color);

	        $auto -> setFotoPerfil($fotoPerfilAuto);
            
	        $auto->setFechaSubida($this->formatearHoraChilena(strftime("%Y-%m-%d %H:%M:%S")));
	            
            $auto -> save();

            $idCar = $auto->getId();
            

            $q = Doctrine_Manager::getInstance()->getCurrentConnection();
            $query = "update arriendas.Car set doors='$puertas', transmission='$transmision', uso_vehiculo_id='$usosVehiculo
            ' where id=$idCar";

            $result = $q->execute($query);

        }

    //Agregamos la data correspondiente a la tabla Availability
    $avai= new Availability();
    $avai->setDateFrom("2000-01-01 00:00:00");
    $avai->setDateTo("9999-03-01 00:00:00");
    $avai->setHourFrom("00:00:00");
    $avai->setHourTo("23:59:59");
    $avai->setCarId($idCar);
    $avai->save();

    //redireccionar
    if(!$creado) $this->redirect('profile/addCarExito?id=' . $idCar);
    else $this->redirect('profile/cars');

    }

    public function executeUploadPhoto(sfWebRequest $request) {

        $valid_formats = array("jpg", "png", "gif", "bmp", "jpeg");

        if (isset($_POST) and $_SERVER['REQUEST_METHOD'] == "POST") {
            $name = $_FILES[$request->getParameter('file')]['name'];
            $size = $_FILES[$request->getParameter('file')]['size'];
			$tmp = $_FILES[$request->getParameter('file')]['tmp_name'];
            if (strlen($name)) {
                list($txt, $ext) = explode(".", $name);
                if (in_array($ext, $valid_formats) || 1==1) {
                    if ($size < (5 * 1024 * 1024)) { // Image size max 1 MB
                    
                        $userid = $this->getUser()->getAttribute("userid");
                        $actual_image_name = time() . $userid . "." . $ext;

                        $uploadDir = sfConfig::get("sf_web_dir");
                        $path = $uploadDir . '/images/cars/';
                        $fileName = $actual_image_name . "." . $ext;

                        $tmp = $_FILES[$request->getParameter('file')]['tmp_name'];
                        if (move_uploaded_file($tmp, $path . $actual_image_name)) {
                            sfContext::getInstance()->getConfiguration()->loadHelpers("Asset");

                            echo "<input type='hidden' name='" . $request->getParameter('photo') . "' value='" . $path . $actual_image_name . "'/>";
                            echo "<img src='" . image_path("cars/" . $actual_image_name) . "' class='preview' height='" . $request->getParameter('height') . "' width='" . $request->getParameter('width') . "' />";
                        }
                        else
                            echo "failed";
                    }
                    else
                        echo "Image file size max 5 MB";
                }
                else
                    echo "Invalid file format..";
            }
            else
                echo "Please select image..!";
            exit;
        }



        /* 	foreach ($request->getFiles() as $fileName) {
          $fileSize = $fileName['size'];
          $fileType = $fileName['type'];
          $extesion = getFileExtension($fileName['name']);


          if(!is_dir($csv))
          mkdir($csv, 0777);
          move_uploaded_file($fileName['tmp_name'], "$folder/$fileName");
          }
         */
    }

    public function executeUploadPhotoCar(sfWebRequest $request) {

        $valid_formats = array("jpg", "png", "gif", "bmp", "jpeg");

        if (isset($_POST) and $_SERVER['REQUEST_METHOD'] == "POST") {
            $name = $_FILES[$request->getParameter('file')]['name'];
            $size = $_FILES[$request->getParameter('file')]['size'];
            $tmp = $_FILES[$request->getParameter('file')]['tmp_name'];
            if (strlen($name)) {
                list($txt, $ext) = explode(".", $name);
                if (in_array($ext, $valid_formats) || 1==1) {
                    if ($size < (5 * 1024 * 1024)) { // Image size max 1 MB
                    
                        $userid = $this->getUser()->getAttribute("userid");
                        $actual_image_name = time() . $userid . "." . $ext;

                        $uploadDir = sfConfig::get("sf_web_dir");
                        $path = $uploadDir . '/uploads/cars/';
                        //$fileName = $actual_image_name . "." . $ext;

                        $tmp = $_FILES[$request->getParameter('file')]['tmp_name'];

                        if (move_uploaded_file($tmp, $path . $actual_image_name)) {
                            sfContext::getInstance()->getConfiguration()->loadHelpers("Asset");
                            //var_dump($path.$actual_image_name);die();
                            $urlAlojamiento = '/uploads/cars/thumbs/';
                            $imagenPequena = $this->generarImagenThumb($path.$actual_image_name,$actual_image_name,$urlAlojamiento);
                            echo "<input type='hidden' name='" . $request->getParameter('photo') . "' value='" . $actual_image_name . "'/>";
                            echo "<img src='" . image_path("../uploads/cars/" . $actual_image_name) . "' class='preview' height='" . $request->getParameter('height') . "' width='" . $request->getParameter('width') . "' />";
                            //echo "<img src='" . image_path("../uploads/cars/thumbs/" . $imagenPequena) . "' class='preview' height='" . $request->getParameter('height') . "' width='" . $request->getParameter('width') . "' />";
                        }
                        else
                            echo "failed";
                    }
                    else
                        echo "Image file size max 1 MB";
                }
                else
                    echo "Invalid file format..";
            }
            else
                echo "Please select image..!";
            exit;
        }
    }

    public function generarImagenThumb($rutaImagenOriginal,$nameFile,$alojamiento){
        //Creamos una variable imagen a partir de la imagen original
        $img_original = imagecreatefromjpeg($rutaImagenOriginal);
        //var_dump($img_original);die();
        //Se define el maximo ancho o alto que tendra la imagen final
        $max_ancho = 100;
        $max_alto = 100;
        //Ancho y alto de la imagen original
        list($ancho,$alto)=getimagesize($rutaImagenOriginal);
        //Se calcula ancho y alto de la imagen final
        $x_ratio = $max_ancho / $ancho;
        $y_ratio = $max_alto / $alto;
        
        //Si el ancho y el alto de la imagen no superan los maximos, 
        //ancho final y alto final son los que tiene actualmente
        if( ($ancho <= $max_ancho) && ($alto <= $max_alto) ){//Si ancho 
            $ancho_final = $ancho;
            $alto_final = $alto;
        }
        /*
         * si proporcion horizontal*alto mayor que el alto maximo,
         * alto final es alto por la proporcion horizontal
         * es decir, le quitamos al alto, la misma proporcion que 
         * le quitamos al alto
         * 
        */
        elseif (($x_ratio * $alto) < $max_alto){
            $alto_final = ceil($x_ratio * $alto);
            $ancho_final = $max_ancho;
        }
        /*
         * Igual que antes pero a la inversa
        */
        else{
            $ancho_final = ceil($y_ratio * $ancho);
            $alto_final = $max_alto;
        }
        //Creamos una imagen en blanco de tamaño $ancho_final  por $alto_final .
        $tmp=imagecreatetruecolor($ancho_final,$alto_final);    
        
        //Copiamos $img_original sobre la imagen que acabamos de crear en blanco ($tmp)
        imagecopyresampled($tmp,$img_original,0,0,0,0,$ancho_final, $alto_final,$ancho,$alto);
        //Se destruye variable $img_original para liberar memoria
        imagedestroy($img_original);
        //Definimos la calidad de la imagen final
        $calidad=95;

        $uploadDir = sfConfig::get("sf_web_dir");
        $path = $uploadDir . $alojamiento;
        //Se crea la imagen final en el directorio indicado
        imagejpeg($tmp, $path.$nameFile,$calidad);
        return $nameFile;
    }

    public function executeUploadPhotoVerification(sfWebRequest $request) {
        $valid_formats = array("jpg", "png", "gif", "bmp", "jpeg");

        if (isset($_POST) and $_SERVER['REQUEST_METHOD'] == "POST") {
            $name = $_FILES[$request->getParameter('file')]['name'];
            $size = $_FILES[$request->getParameter('file')]['size'];
            $tmp = $_FILES[$request->getParameter('file')]['tmp_name'];
            $idAuto = $request->getParameter('idCar');
            $nombre = $request->getParameter('photo');
            if (strlen($name)) {
                list($txt, $ext) = explode(".", $name);
                if (in_array($ext, $valid_formats) || 1==1) {
                    if ($size < (5 * 1024 * 1024)) { // Image size max 1 MB
                    
                        $userid = $this->getUser()->getAttribute("userid");
                        $actual_image_name = time() . $userid . "." . $ext;

                        $uploadDir = sfConfig::get("sf_web_dir");
                        $path = $uploadDir . '/uploads/verificaciones/';
                        //$fileName = $actual_image_name . "." . $ext;

                        $tmp = $_FILES[$request->getParameter('file')]['tmp_name'];

                        if (move_uploaded_file($tmp, $path . $actual_image_name)) {
                            sfContext::getInstance()->getConfiguration()->loadHelpers("Asset");
                            //var_dump($path.$actual_image_name);die();
                            $urlAlojamiento = '/uploads/verificaciones/thumbs/';
                            $imagenPequena = $this->generarImagenThumb($path.$actual_image_name,$actual_image_name,$urlAlojamiento);
                            //echo "<input type='hidden' name='" . $request->getParameter('photo') . "' value='" . $actual_image_name . "'/>";
                            $auto = Doctrine_Core::getTable('car')->findOneById($idAuto);
                            if($nombre == 'seguroFotoFrente') $auto->setSeguroFotoFrente($actual_image_name);
                            if($nombre == 'seguroFotoCostadoDerecho') $auto->setSeguroFotoCostadoDerecho($actual_image_name);
                            if($nombre == 'seguroFotoCostadoIzquierdo') $auto->setSeguroFotoCostadoIzquierdo($actual_image_name);
                            if($nombre == 'seguroFotoTraseroDerecho') $auto->setSeguroFotoTraseroDerecho($actual_image_name);
                            if($nombre == 'tablero') $auto->setTablero($actual_image_name);
                            if($nombre == 'llanta_del_der') $auto->setLlantaDelDer($actual_image_name);
                            if($nombre == 'llanta_del_izq') $auto->setLlantaDelIzq($actual_image_name);
                            if($nombre == 'llanta_tra_der') $auto->setLlantaTraDer($actual_image_name);
                            if($nombre == 'llanta_tra_izq') $auto->setLlantaTraIzq($actual_image_name);
                            if($nombre == 'rueda_repuesto') $auto->setRuedaRepuesto($actual_image_name);
                            if($nombre == 'accesorio1') $auto->setAccesorio1($actual_image_name);
                            if($nombre == 'accesorio2') $auto->setAccesorio2($actual_image_name);
                            if($nombre == 'padron') $auto->setPadron($actual_image_name);
                            if($nombre == 'foto_padron_reverso') $auto->setFotoPadronReverso($actual_image_name); 
        
                            $auto->setFechaInicioVerificacion($this->formatearHoraChilena(strftime("%Y-%m-%d %H:%M:%S")));
                            $auto->save();

                            echo "<img src='" . image_path("../uploads/verificaciones/" . $actual_image_name) . "' class='preview' height='" . $request->getParameter('height') . "' width='" . $request->getParameter('width') . "' />";

                            //echo "<img src='" . image_path("../uploads/cars/thumbs/" . $imagenPequena) . "' class='preview' height='" . $request->getParameter('height') . "' width='" . $request->getParameter('width') . "' />";
                        }
                        else
                            echo "failed";
                    }
                    else
                        echo "Image file size max 1 MB";
                }
                else
                    echo "Invalid file format..";
            }
            else
                echo "Please select image..!";
            exit;
        }
    }

    public function executeDetalleReserva(sfWebRequest $request) {
        if ($this->getUser()->isAuthenticated()) {

            $id = $request->getParameter('id');

            try {

                $q = Doctrine_Query::create()
                        ->select('r.id , ca.id idcar , mo.name model, 
	  br.name brand, ca.year year, 
          ca.price_per_hour pricehour, ca.price_per_day priceday,
	  ca.address address, ci.name city, 
	  st.name state, co.name country, 
	  user.firstname firstname, user.lastname lastname,
	  r.date date, r.duration'
                        )
                        ->from('Reserve r')
                        ->innerJoin('r.Car ca')
                        ->innerJoin('ca.Model mo')
                        ->innerJoin('ca.User owner')
                        ->innerJoin('r.User user')
                        ->innerJoin('mo.Brand br')
                        ->innerJoin('ca.City ci')
                        ->innerJoin('ci.State st')
                        ->innerJoin('st.Country co')
                        ->where('r.user_id = ?', $this->getUser()->getAttribute("userid"))
                        ->andWhere('r.confirmed = ?', 0)
                        ->andWhere('r.complete = ?', 0)
                        ->andWhere('r.id = ?', $id);
//->andWhere('r.date > ?',  date('Y-m-d H:i:s.u'));

                $this->misreservas = $q->execute();
//echo $q->getSqlQuery();die;
            } catch (Exception $e) {
                echo $e->getMessage();
            }
        } else {
            $this->getUser()->setAttribute('lastview', $request->getUri());
            $this->forward('main', 'login');
        }
    }

    public function executeDeleteReserve(sfWebRequest $request) {

        $this->setLayout(false);

        $idReserva = $request->getParameter('idReserva');

        if (!is_nan($idReserva) && !is_null($idReserva)) {

            $reserve = Doctrine_Core::getTable('Reserve')->find(array($idReserva));

            $inicio = strtotime(date("Y-m-d H:i:s", strtotime($reserve->getDate())));
            $now = strtotime(date("Y-m-d H:i:s"));

            $minutos = ($inicio - $now) / 60;

            if ($minutos < 30) {
                $reserve->setSancion(true);
            }

            $reserve->setCanceled(true);
            $reserve->save();
        }

        $this->forward('profile', 'pedidos');

        return sfView::NONE;
    }

    public function executeEditReserve(sfWebRequest $request) {

        $idReserva = $request->getParameter('idReserva');
        $datedesde = $request->getParameter('fechainicio');
        $datehasta = $request->getParameter('fechafin');
        $hourdesde = "";
        $hourhasta = "";

        $desde = explode(" ", $datedesde);
        $datedesde = $desde[0];
        $hourdesde = $desde[1];

        $hasta = explode(" ", $datehasta);
        $datehasta = $hasta[0];
        $hourhasta = $hasta[1];

//echo $datehasta.'\n'.$hourhasta;die;
//print_r($request);die;
//$duration = $request->getParameter('duration');
//if($date != ""  && $duration != "")
        if ($datedesde != "" && $datehasta != "" && $hourdesde != "" && $hourhasta != "" &&
                preg_match('/^(([0-1][0-9])|([2][0-3])):([0-5][0-9]):([0-5][0-9])$/', $hourdesde) &&
                preg_match('/^(([0-1][0-9])|([2][0-3])):([0-5][0-9]):([0-5][0-9])$/', $hourhasta) &&
                preg_match('/^\d{4}\-\d{1,2}\-\d{1,2}$/', $datedesde) &&
                preg_match('/^\d{4}\-\d{1,2}\-\d{1,2}$/', $datehasta)) {

//CORROBORAR SI SE SOLICITO ANTES PERO TIENE DURACION DURANTE EL PEDIDO

            $startDate = date("Y-m-d H:i:s", strtotime($datedesde . " " . $hourdesde));
            $endDate = date("Y-m-d H:i:s", strtotime($datehasta . " " . $hourhasta));

            $diff = strtotime($endDate) - strtotime($startDate);

            $duration = $diff / 60 / 60;


            $carid = $request->getParameter('carid');


            $has_reserve = Doctrine_Core::getTable('Reserve')
                    ->createQuery('a')
                    ->where('((a.date <= ? and date_add(a.date, INTERVAL a.duration HOUR) > ?) or (a.date <= ? and date_add(a.date, INTERVAL a.duration HOUR) > ?)) and (a.Car.id = ?)', array($startDate, $startDate, $endDate, $endDate, $carid))
                    ->andWhere('a.id != ?', $idReserva)
                    ->andWhere('a.confirmed = ?', true)
                    ->andWhere('a.complete = ?', false)
                    ->fetchArray();



            if (count($has_reserve) == 0) {

                if ($diff > 0) {

                    try {

                        Doctrine_Query::create()
                                ->update('Reserve r')
                                ->set('r.date', '?', $startDate)
                                ->set('r.duration', '?', $duration)
                                ->set('r.confirmed', '?', false)
                                ->where('r.id = ?', $idReserva)
                                ->execute();
                        echo '0';
                    } catch (Exception $e) {
                        echo 'Error al modificar su reserva'; //.' error: '.$e->getMessage()
                    }
                } else {
                    echo 'Fecha de retiro debe ser mayor a fecha de entrega';
                }
            } else {
                echo 'Ya hay una reserva confirmada para ese horario';
            }
        } else {
            echo 'Por favor ingrese fechas con formato YYYY-MM-DD HH:MM';
        }
        return sfView::NONE;
    }

    public function executeDoModificarArriendo(sfWebRequest $request) {
        $this->setLayout(false);
    }

    public function executeModificarArriendo(sfWebRequest $request) {

        $id = $request->getParameter('id');

        $q = Doctrine_Query::create()
                ->select('r.id , ca.id idcar , r.confirmed, mo.name model, 
	  br.name brand, ca.year year, 
          ca.price_per_hour pricehour, ca.price_per_day priceday,
	  ca.address address, ci.name city, 
	  st.name state, co.name country, 
	  user.firstname firstname, user.lastname lastname,
	  r.date date, r.duration, date_add(r.date, INTERVAL r.duration HOUR) fechafin'
                )
                ->from('Reserve r')
                ->innerJoin('r.Car ca')
                ->innerJoin('ca.Model mo')
                ->innerJoin('ca.User owner')
                ->innerJoin('r.User user')
                ->innerJoin('mo.Brand br')
                ->innerJoin('ca.City ci')
                ->innerJoin('ci.State st')
                ->innerJoin('st.Country co')
//->where('r.user_id = ?', $this->getUser()->getAttribute("userid"))
//->andWhere('r.confirmed = ?', 1)
//->andWhere('r.complete = ?', 0)
//->andWhere('r.canceled = ?', 0)
                ->andWhere('r.id = ?', $id);

        $this->reserva = $q->fetchOne();
    }

    public function executeGuardarKms(sfWebRequest $request) {
        $this->setLayout(false);



        $idReserva = $request->getParameter('id');
        $tipo = $request->getParameter('tipo');
        $kms = $request->getParameter('kms');

        //$kms = number_format($kms, 2, '.', ',');

        if ($kms > 0) {

            try {

                $reserve = Doctrine::getTable('Reserve')->find(array($idReserva));

                if ($tipo == 'inicial') {
                    $reserve->setKminicial($kms);
                } elseif ($tipo == 'final') {
                    $reserve->setKmfinal($kms);
                }

                if ($this->getUser()->getAttribute('userid') != $reserve->getUserId()) {
                    $reserve->setIniKmOwnerConfirmed(true);
                    $this->sendConfirmKmsEmail($reserve, 'usuario');
                } else {
                    $reserve->setIniKmConfirmed(true);
                }

                $reserve->save();

                echo 'ok';
            } catch (Exception $e) {
                echo 'error: ' . $e->getMessage();
            }
        } else {
            echo 'Debe ingresar km ' . $tipo;
        }
        return sfView::NONE;
    }

    public function sendConfirmKmsEmail($reserve, $usuario) {

        if ($usuario == 'owner') {

            $to = $reserve->getCar()->getUser()->getEmail();

            $subject = 'Tiene una reserva por confirmar kms!';

            $headers = "From: \"Arriendas Reservas\" <no-reply@arriendas.cl>\n";
            $headers .= "Content-type: text/html\n";
            $headers .= "X-Mailer: PHP/" . phpversion() . "\n";

            $mail = 'Se ha solicitado confirmación de kms para el auto ' . $reserve->getCar()->getModel()->getBrand()->getName() . ' ' . $reserve->getCar()->getModel()->getName() .
                    ' reservado por el usuario ' . $reserve->getUser()->getFirstName() . ' ' . $reserve->getUser()->getLastName() . '.
	
	Gracias
	Arriendas.cl
	<br><br>
	<em style="color: #969696">Nota: Para evitar posibles problemas con la recepcion de este correo le aconsejamos nos agregue a su libreta de contactos.</em> 
	';
        } else {
            $to = $reserve->getUser()->getEmail();

            $subject = 'Tiene una reserva por confirmar kms!';

            $headers = "From: \"Arriendas Reservas\" <no-reply@arriendas.cl>\n";
            $headers .= "Content-type: text/html\n";
            $headers .= "X-Mailer: PHP/" . phpversion() . "\n";

            $mail = 'Se ha solicitado confirmación de kms para el auto ' . $reserve->getCar()->getModel()->getBrand()->getName() . ' ' . $reserve->getCar()->getModel()->getName() .
                    ' del usuario ' . $reserve->getCar()->getUser()->getFirstName() . ' ' . $reserve->getCar()->getUser()->getLastName() . '.
	
	Gracias
	Arriendas.cl
	<br><br>
	<em style="color: #969696">Nota: Para evitar posibles problemas con la recepcion de este correo le aconsejamos nos agregue a su libreta de contactos.</em> 
	';
        }

        $this->smtpMail($to, $subject, $mail, $headers);
    }
    
    public function calcularMontoTotal($duration = 0, $preciohora = 0, $preciodia = 0, $preciosemana = 0, $preciomes = 0) {
	
	$this->logMessage('calcularMontoTotal', 'err');
	$this->logMessage('duration '. $duration, 'err');
	$this->logMessage('preciohora '. $preciohora, 'err');
	$this->logMessage('preciodia '. $preciodia, 'err');
	$this->logMessage('preciosemana '. $preciosemana, 'err');
	$this->logMessage('preciomes '. $preciomes, 'err');

	
        $dias = floor($duration / 24);
        $horas = ($duration / 24) - $dias;
						
        if ($horas >= 0.25) {
            $dias = $dias + 1;
            $horas = 0;
        } else {
            $horas = round($horas * 24,0);
        }

		$this->logMessage('dias '. $dias, 'err');

			
		if ($dias >=7 && $preciosemana>0){
			$preciodia=$preciosemana/7;
		}

		if ($dias >=30 && $preciomes>0){
			$preciodia=$preciomes/30;
		}

		$this->logMessage('preciodia '. $preciodia, 'err');

		
        $montototal = floor($preciodia * $dias + $preciohora * $horas);
        
        return $montototal;
    }

    
public function smtpMail($to_input,$subject_input,$mail,$headers,$cco=null) {
error_reporting(0);
require_once "Mail.php";
require_once "Mail/mime.php";
 
 $from = "Soporte Arriendas.cl <soporte@arriendas.cl>";
 $to = $to_input;
 $subject = $subject_input;
 $body = $mail;
 
 $host = "smtp.mailgun.org";
 $username = "postmaster@arriendacl.mailgun.org";
 $password = "4ntw7cqojjy3";
 
 if($cco==null){
 $headers = array ('From' => $from,
   'To' => $to,
   'charset' => 'UTF-8',
   'Subject' => $subject);
 } else {
    $headers = array ('From' => $from,
   'To' => $to,
   'Bcc' => $cco,
   'charset' => 'UTF-8',
   'Subject' => $subject);
 }
 $headers["Content-Type"] = 'text/html; charset=UTF-8';
 
 $mime= new Mail_mime();
 $mime->setHTMLBody($body);

 $mimeparams=array(); 


// It refused to change to UTF-8 even if the header was set to this, after adding the following lines it worked.

$mimeparams['text_encoding']="8bit"; 
$mimeparams['text_charset']="UTF-8"; 
$mimeparams['html_charset']="UTF-8"; 
$mimeparams['head_charset']="UTF-8"; 

 
 $body=$mime->get($mimeparams);
$headers = $mime->headers($headers);
 
 $smtp = Mail::factory('smtp',
   array ('host' => $host,
     'auth' => true,
     'username' => $username,
     'password' => $password));
 
 if($mail = $smtp->send($to, $headers, $body)){
    return true;
 }else{
    return false;
 }
 

}

public function executeSubeTuAuto(sfWebRequest $request) {
}
    
}

