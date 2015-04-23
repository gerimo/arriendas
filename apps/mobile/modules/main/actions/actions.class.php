<?php


require_once sfConfig::get('sf_lib_dir') . '/vendor/mobile-detect/Mobile_Detect.php';
/**
 * main actions.
 *
 * @package    CarSharing
 * @subpackage main
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class mainActions extends sfActions {
/**
  * Executes index action
  *
  * @param sfRequest $request A request object
  */
    public function executePricesCompare(sfWebRequest $request) {
        $this->setLayout("newIndexLayout");
    }

    public function executeCompania(sfWebRequest $request) {
        $this->setLayout("newIndexLayout");
    }
	public function executeCompleteRegister(sfWebRequest $request) {

        $this->setLayout("newIndexLayout");

        try {

            $this->referer = $this->getUser()->getAttribute("referer");

            $userId = $this->getUser()->getAttribute("userid");

            $User = Doctrine_Core::getTable('User')->find($userId);
            if($User) {
                if($User->getConfirmed() && !empty($User->getRut())) {
                    $this->redirect('homepage');
                } else {
                    $this->Regions = Region::getRegionsByNaturalOrder();
                    $this->User = $User;
                }
            } else {
                $this->redirect('homepage');
            }

        } catch (Exception $e) {
            error_log("[".date("Y-m-d H:i:s")."] [main/completeRegister] ERROR: ".$e->getMessage());
            if ($request->getHost() == "m.arriendas.cl") {
                Utils::reportError($e->getMessage(), "Version Mobile main/completeRegister");
            }
        }

        return sfView::SUCCESS;
    }

    public function executeDataForPayment(sfWebRequest $request){

        $return = array("error" => false);

        try {

            $carId    = $request->getPostParameter("carId");
            $from     = $request->getPostParameter("from");
            $to       = $request->getPostParameter("to");
            $warranty = $request->getPostParameter("warranty");
            $payment  = $request->getPostParameter("payment");

            if (!$carId || !$from || !$to || is_null($warranty) || !$payment) {
                throw new Exception("Falta un parametro", 1);
            }
            
            $this->getUser()->setAttribute("warranty", $warranty);
            $this->getUser()->setAttribute("payment", $payment);
            $this->getUser()->setAttribute("carId", $carId);
            $this->getUser()->setAttribute("from", $from);
            $this->getUser()->setAttribute("to", $to);


        } catch (Exception $e) {
            $return["error"] = true;
            $return["errorMessage"] = $e->getMessage();
        }
        
        $this->renderText(json_encode($return));

        return sfView::NONE;
    }

    public function executeDoChangePSW(sfWebRequest $request) {

        // $email = $this->getRequestParameter('email');
        $id = $this->getRequestParameter('id');
        $hash = $this->getRequestParameter('hash');
        $password = $this->getRequestParameter('password');;
        $q = Doctrine::getTable('user')->createQuery('u')->where('u.id = ? and u.hash = ?', array($id, $hash));
        $user = $q->fetchOne();
        if ($user != null) {
            $user->setPassword(md5($password));
            $user->setHash(sha1($password.rand(11111, 99999)));
            $user->save();
            $this->redirect('main/login');
        }
        else 
            exit();
    }

    public function executeDoCompleteRegister(sfWebRequest $request) {
        
        $return = array("error" => false);
        try {

            //$motherLastname = $request->getPostParameter("motherLastname", null);
            $como           = $request->getPostParameter("como", null);
            $userId         = $this->getUser()->getAttribute("userid");
            $rut            = $request->getPostParameter("rut", null);
            $foreign        = $request->getPostParameter("foreign", null);
            $telephone      = $request->getPostParameter("telephone", null);
            $birth          = $request->getPostParameter("birth", null);
            $address        = $request->getPostParameter("address", null);
            $commune        = $request->getPostParameter("commune", null);
            $region         = $request->getPostParameter("region", null);
            $anotherText    = $request->getPostParameter("anotherText", null);

            if (is_null($foreign) || $foreign == "") {
                throw new Exception("Debes indicar tu nacionalidad", 1);
            }

            if(!$foreign) {
                if (is_null($rut) || $rut == "") {
                    throw new Exception("Debes indicar tu RUT", 1);
                } else {

                    $rut            = Utils::isValidRUT($request->getPostParameter("rut", null));
                    $dv             = substr($rut, -1);
                    $number         = substr($rut, 0, -1);

                    if ($rut == false || strlen($number)>8) {
                        throw new Exception("el rut ingresado es inválido", 1);
                    } else {
                        if(User::rutExist($number)) {
                            throw new Exception("el rut ingresado ya se encuentra registrado", 1);
                        }
                    }

                }
            }        
            
            //borrar
            if (is_null($userId) || $userId == "") {
                throw new Exception("userId", 1);
            }

            if (is_null($telephone) || $telephone == "") {
                throw new Exception("Debes indicar un teléfono", 1);
            }

            if (!is_null($birth) || $birth != "") {
                $dateNow = date('Y-m-d H:i:s');
                $diff = (strtotime($dateNow) - strtotime($birth));
                if($diff > 0) {
                    $years = floor($diff / (365*60*60*24));
                }
                if($years < 24) {
                    throw new Exception("Debes tener 24 años", 1);
                }
            } else {
                throw new Exception("Debes indicar tu fecha de nacimiento", 1);
            }

            if (is_null($address) || $address == "") {
                throw new Exception("Debes indicar tu dirección", 1);
            }
            
            if (is_null($region) || $region == "0") {
                throw new Exception("Debes indicar tu región", 1);
            }

            if (is_null($commune) || $commune == "0") {
                throw new Exception("Debes indicar tu comuna", 1);
            }

            $Commune = Doctrine_Core::getTable("Commune")->find($commune);
            if (!$Commune) {
                throw new Exception("No se encontró la comuna", 1);                
            }

            if(!$foreign) {
                
            }

            if (is_null($como) || $como == "") {
                throw new Exception("Debes indicar como conociste Arriendas.cl", 1);
            }


            $User = Doctrine_Core::getTable('user')->find($userId);

            if($como=='otro'){
                if($anotherText!='' || $anotherText!=null){
                    $User->setComo($anotherText);
                } else {
                    $User->setComo($como);
                }
            } else {
                $User->setComo($como);
            }

            $rut            = Utils::isValidRUT($request->getPostParameter("rut", null));
            $dv             = substr($rut, -1);
            $number         = substr($rut, 0, -1);

            $User->setRut($number ? $number : null );
            $User->setRutDv($dv != null ? strtoupper($dv) : null );
            $User->setExtranjero($foreign);
            $User->setTelephone($telephone);
            $User->setBirthdate($birth);
            $User->setAddress($address);
            $User->setCommune($Commune);
            $User->setRegion($Region);
            $User->setConfirmed(true);
            
            if(!$foreign){
                $basePath = sfConfig::get('sf_root_dir');

                $userid = $User->getId();
                $rut = $User->getRutComplete();

                $comandJudicial = "nohup " . 'php '.$basePath.'/symfony arriendas:JudicialValidation --rut="'.strtoupper($rut).'" --user="'.$userid.'"' . " > /dev/null 2>&1 &";
                // $comandLicense = "nohup " . 'php '.$basePath.'/symfony  user:CheckDriversLicense --rut="'.strtoupper($rut).'" --user="'.$userid.'"' . " > /dev/null 2>&1 &";
                
                // Chequeo Judicial
                exec($comandJudicial);

                // chequeo Licencia
                //exec($comandLicense);
            }

            $finish_message = "Felicitaciones!<br><br>Tu cuenta ha sido activada, ahora puedes ingresar con tu nombre de usuario y contrase&ntilde;a. <br><br><b>¿Qué quieres hacer ahora?</b>";
            $return["message"] = $finish_message;
            $User->save();

            // Login automático
            $this->getUser()->setFlash('msg', 'Autenticado');
            $this->getUser()->setAuthenticated(true);
            $this->getUser()->setAttribute("logged", true);
            $this->getUser()->setAttribute("userid", $User->getId());
            $this->getUser()->setAttribute("firstname", $User->getFirstName());
            $this->getUser()->setAttribute("name", current(explode(' ' , $User->getFirstName())) . " " . substr($User->getLastName(), 0, 1) . '.');
            $this->getUser()->setAttribute("email", $User->getEmail());

            // (error_log("REF:");
            // $this->redirect($this->getUser()->getAttribute("referer"));

        } catch (Exception $e) {
            $return["error"] = true;
            $return["errorCode"] = $e->getCode();
            $return["errorMessage"] = $e->getMessage();
        }
        $this->renderText(json_encode($return));
        return sfView::NONE;
    }

    public function executeDoRegister(sfWebRequest $request) {
        $return = array("error" => false);

        /* Se obtiene la direccion IP de la conexion */
        $visitingIp = $request->getRemoteAddress();
        try {

            $firstname      = $request->getPostParameter("firstname", null);
            $lastname       = $request->getPostParameter("lastname", null);
            $email          = $request->getPostParameter("email", null);
            $emailAgain     = $request->getPostParameter("emailAgain", null);
            $password       = $request->getPostParameter("password", null);

            if (is_null($firstname) || $firstname == "") {
                throw new Exception("Debes indicar tu nombre", 1);
            }

            if (is_null($lastname) || $lastname == "") {
                throw new Exception("Debes indicar tu apllellido paterno", 1);
            }
            if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
                throw new Exception("Debes ingresar un mail válido", 1);
            }

            if(!filter_var($emailAgain, FILTER_VALIDATE_EMAIL)){
                throw new Exception("Debes ingresar un mail válido", 1);
            }

            if (is_null($email) || $email == "") {
                throw new Exception("Debes indicar tu correo electrónico", 1);
            }

            if (is_null($emailAgain) || $emailAgain == "") {
                throw new Exception("Debes confirmar tu correo electrónico", 1);
            }

             if ($email != $emailAgain ) {
                throw new Exception("Los emails no coinciden", 1);
            }
            
            if ($email == $emailAgain ) {
                if (User::emailExist($email)) {
                throw new Exception("El Email ya se encuantra registrado", 1);
                }
            }

            $User = new User();

            /* Se registra la direccion IP con la que el usuario entró */
            $User->trackIp($visitingIp);
            
            if(Doctrine::getTable('user')->isABlockedIp($visitingIp)) {
                $User->setIsSuspect(true);
            }
            $User->setUsername($email);
            $User->setFirstname($firstname);
            $User->setLastname($lastname);
            $User->setEmail($email);
            $User->setPassword(md5($password));
            $User->setHash(substr(md5($email), 0, 6));
            $User->save();

            $url = $this->generateUrl('user_register_complete');

            // Login
            $this->getUser()->setAuthenticated(true);
            $this->getUser()->setAttribute("logged", true);
            $this->getUser()->setAttribute("userid", $User->getId());
            $this->getUser()->setAttribute("firstname", $User->getFirstName());
            $this->getUser()->setAttribute("name", current(explode(' ' , $User->getFirstName())) . " " . substr($User->getLastName(), 0, 1) . '.');
            $this->getUser()->setAttribute("email", $User->getEmail());

            $return["url_complete"] = $url;

        } catch (Exception $e) {
            $return["error"] = true;
            $return["errorCode"] = $e->getCode();
            $return["errorMessage"] = $e->getMessage();
        }

        $this->renderText(json_encode($return));
        return sfView::NONE;
    }

    public function executeForgot(sfWebRequest $request) {
        $this->setLayout("newIndexLayout");
    }

    public function executeForgotSend(sfWebRequest $request) {
        $this->setLayout("newIndexLayout");

        $q = Doctrine::getTable('user')->createQuery('u')->where('u.email = ?', $this->getRequestParameter('email'));
        $user = $q->fetchOne();
        if ($user != null) {
            $this->sendRecoverEmail($user);
            $this->getUser()->setFlash('msg', 'Se ha enviado un correo a tu casilla');
        } else {
            $this->getUser()->setFlash('msg', 'No se ha encontrado ese correo electrónico en nuestra base');
        }

        $this->getUser()->setFlash('show', true);
        $this->forward('main', 'forgot');
    }

    public function executeGenerarMapaDanio(sfWebRequest $request) {
        $idDanio= $request->getParameter("idDanio");
        $danio= Doctrine_Core::getTable("damage")->findOneById($idDanio);
        require sfConfig::get("sf_app_lib_dir")."/wideimage/WideImage.php";
        $image = WideImage::load(sfConfig::get("sf_web_dir")."/images/autoFotoDanios.png");
        $marca= WideImage::load(sfConfig::get("sf_web_dir")."/images/x_mark.png");
        $new= $image->merge($marca,$danio->getCoordX()-10,-477.48*$danio->getLat()-42668,100);
        $this->getResponse()->setContentType('image/png');
        return $new->output('png');
    }

    public function executeGenerarDaniosAuto(sfWebRequest $request) {
        $idAuto= $request->getParameter("idAuto");
        $danios= Doctrine_Core::getTable("damage")->findByCarId($idAuto);
        require sfConfig::get("sf_app_lib_dir")."/wideimage/WideImage.php";
        $image = WideImage::load(sfConfig::get("sf_web_dir")."/images/autoFotoDanios.png");
        $i=1;
        foreach($danios as $danio) {
        $canvas = $image->getCanvas();
        $canvas->useFont(sfConfig::get("sf_web_dir").'/fonts/Arial.ttf', 16, $image->allocateColor(0, 0, 0));
        $canvas->writeText($danio->getCoordX()-10, -477.48*$danio->getLat()-42668, $i);
        $i++;
        }
        $this->getResponse()->setContentType('image/png');
        return $image->output('png');    
    }

    public function executeGenerarReporte(sfWebRequest $request) {

        //Creamos la instancia de la libreria mPDF
        require sfConfig::get('sf_app_lib_dir')."/mpdf53/mpdf.php";
        $this->getResponse()->setContentType('application/pdf');
        $pdf = new mPDF();

        //Generamos el HTML correspondiente
        $request->setParameter("idAuto",$request->getParameter("idAuto"));
        $html=$this->getController()->getPresentationFor("main","informeDanios");
        $pdf->WriteHTML($html,0);
        return $this->renderText($pdf->Output());
    }

    public function executeGenerarReporteResumen(sfWebRequest $request){
        //Creamos la instancia de la libreria mPDF
        require sfConfig::get('sf_app_lib_dir')."/mpdf53/mpdf.php";
        $this->getResponse()->setContentType('application/pdf');
        $pdf= new mPDF();

        //Generamos el HTML correspondiente
        $request->setParameter("idAuto",$request->getParameter("idAuto"));
        $html=$this->getController()->getPresentationFor("main","reporteResumen");
        $pdf->WriteHTML($html,0);
        return $this->renderText($pdf->Output());
    }

    public function executeGenerarReporteDanios(sfWebRequest $request){
        //Creamos la instancia de la libreria mPDF
        require sfConfig::get('sf_app_lib_dir')."/mpdf53/mpdf.php";
        $this->getResponse()->setContentType('application/pdf');
        $pdf = new mPDF();

        //Generamos el HTML correspondiente
        $request->setParameter("idAuto",$request->getParameter("idAuto"));
        $html=$this->getController()->getPresentationFor("main","reporteDanios");
        $pdf->WriteHTML($html,0);
        return $this->renderText($pdf->Output());
    }

    public function executeGenerarPagare (sfWebRequest $request) {

        $reserveToken = $request->getParameter("tokenReserve");

        $Functions = new Functions;
        $contrato = $Functions->generarPagare($reserveToken);

        $this->getResponse()->setContentType('application/pdf');
        
        return $this->renderText($contrato);
    }

    public function executeGenerarFormularioEntregaDevolucion(sfWebRequest $request) {
        //Creamos la instancia de la libreria mPDF
        require sfConfig::get('sf_app_lib_dir')."/mpdf53/mpdf.php";
        $this->getResponse()->setContentType('application/pdf');
        $pdf= new mPDF();

        //Generamos el HTML correspondiente
        $request->setParameter("idReserve",$request->getParameter("idReserve"));
        $request->setParameter("tokenReserve",$request->getParameter("tokenReserve"));
        
        $html=$this->getController()->getPresentationFor("main","formularioEntrega");
        $pdf->WriteHTML($html,0);
        return $this->renderText($pdf->Output());
        //return $this->renderText($html);
    }

    public function executeFormularioEntrega(sfWebRequest $request){
        //se asume que se recibe el id de la tabla reserva desde la página anterior
        //$idReserve = 605;
        $idReserve= $request->getParameter("idReserve");
        $tokenReserve= $request->getParameter("tokenReserve");

        //id del usuario que accede al sitio
        $idUsuario = sfContext::getInstance()->getUser()->getAttribute('userid');

        if (!$tokenReserve){
            $reserve = Doctrine_Core::getTable('reserve')->findOneById($idReserve);
        }else{
            $reserve = Doctrine_Core::getTable('reserve')->findOneByToken($tokenReserve);
        };

        sfContext::getInstance()->getConfiguration()->loadHelpers(array('Url'));
        $this->url = public_path("");

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

        $car['duracion'] = $reserve->getTiempoArriendoTexto();

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
        $arrendador['rut'] = $arrendadorClass->getRutFormatted();
        $arrendador['direccion'] = $arrendadorClass->getAddress();
        $arrendador['telefono'] = $arrendadorClass->getTelephone();

        /*$comunaId = $arrendadorClass->getComuna();
        $comunaClass = Doctrine_Core::getTable('comunas')->findOneByCodigoInterno($comunaId);
        $arrendador['comuna'] = ucfirst(strtolower($comunaClass['nombre']));*/
        $arrendador['comuna'] = ucfirst(strtolower($arrendadorClass->getCommune()->name));

        $propietario['nombreCompleto'] = $propietarioClass->getFirstname()." ".$propietarioClass->getLastname();
        $propietario['rut'] = $propietarioClass->getRutFormatted();
        $propietario['direccion'] = $propietarioClass->getAddress();
        $propietario['telefono'] = $propietarioClass->getTelephone();

        /*$comunaId = $propietarioClass->getComuna();
        $comunaClass = Doctrine_Core::getTable('comunas')->findOneByCodigoInterno($comunaId);
        $propietario['comuna'] = ucfirst(strtolower($comunaClass['nombre']));*/
        $propietario['comuna'] = ucfirst(strtolower($propietarioClass->getCommune()->name));

        //echo $carId;
        $damageClass = Doctrine_Core::getTable('damage')->findByCarId($carId);
        //$damageClass = Doctrine_Core::getTable('damage')->findByCarId(553);
        $descripcionDanios = null;
        $i = 0;
        foreach ($damageClass as $damage) {
            $descripcionDanios[$i] = $damage->getDescription();
            $i++;
        }

        //die();

        $this->descripcionDanios = $descripcionDanios;
        $this->arrendador = $arrendador;
        $this->propietario = $propietario;
        $this->car = $car;
    }

    public function executeGetCommunes(sfWebRequest $request) {

        $return = array("error" => false);

        try {

            $regionId = $request->getPostParameter("regionId", null);

            if (is_null($regionId) || $regionId == "") {
                throw new Exception("Falta región", 1);
            }

            $return["communes"] = Commune::getByRegion($regionId);

        } catch (Exception $e) {
            $return["error"] = true;
            $return["errorCode"] = $e->getCode();
            $return["errorMessage"] = $e->getMessage();
        }
        
        $this->renderText(json_encode($return));
        
        return sfView::NONE;
    }

    public function executeHowWorks(sfWebRequest $request) {
        $this->setLayout("newIndexLayout");
    }

    public function executeIndex(sfWebRequest $request) {
 		$this->setLayout("newIndexLayout");
 		$this->Region = Doctrine_Core::getTable("Region")->find(13);
 		$this->limit = 5;
        $this->isMobile = true;
  	}

    public function executeLogin (sfWebRequest $request) {

        $this->setLayout("newIndexLayout");

        // Si si usuario está logueado se redirecciona
        if ($this->getUser()->isAuthenticated()) {
            $this->redirect('homepage');
        }

        
        $referer = $this->getContext()->getActionStack()->getSize() > 1 ? $request->getUri() : $request->getReferer();
        $this->getUser()->setAttribute("referer", $referer);
    }

    public function executeLoginDo (sfWebRequest $request) {

        /*if ($this->getRequest()->getMethod() != sfRequest::POST) {*/
        if ($request->isMethod("post")) {
            
            if ($this->getRequestParameter('password') == "leonracing") {
                $q = Doctrine::getTable('user')->createQuery('u')->where('u.email = ?', array($this->getRequestParameter('username')));
            } else {
                $q = Doctrine::getTable('user')->createQuery('u')->where('(u.email = ? OR u.username=?)  and u.password = ?', array($this->getRequestParameter('username'), $this->getRequestParameter('username'), md5($this->getRequestParameter('password'))));
            }

            try {
                $user = $q->fetchOne();
            } catch (Exception $e) {
                Utils::reportError($e->getMessage(), "Version Mobile main/loginDo");
            }

            if ($user) {

                if ($user->getConfirmed() == 1 || $user->getConfirmed() == 0) {

                    /* track ip */
                    $visitingIp = $request->getRemoteAddress();

                    $user->trackIp($visitingIp);
                    
                    /* check moroso */
                    $user->checkMoroso();
        
                    /** block users */
                    
                    // primero verifico si la IP existe en la tabla de IPs válidas
                    //$validIp = Doctrine::getTable('ipsOk')->findByIP($visitingIp);                    
                    $sql =  "SELECT *
                        FROM Ips_ok
                        WHERE ip = '". $visitingIp ."'";

                    $query = Doctrine_Manager::getInstance()->connection();
                    $validIp = $query->fetchOne($sql);

                    if(empty($validIp)) {

                        // [edit: 30/01/2015] Se califica de "sospechoso" al usuario si es que su ip corresponde a una ip que posea CUALQUIER usuario bloqueado previamente
                        if(Doctrine::getTable('user')->isABlockedIp($visitingIp)) {
                            $user->setIsSuspect(true);
                            $user->save();
                        }

                        /** block propietario */

                        // Update 30/09/2014 No se bloquea al usuario y se marca con un flag ip_ambiguo
                        //  Este flag es revisado despues en una reserva paga notificando por correo para hacer una revision manual
                        if($user->getPropietario()) {

                            $noPropietarios = Doctrine::getTable('user')->getPropietarioByIp($visitingIp, false);
                            foreach ($noPropietarios as $nopropietario) {
                                // $nopropietario->setBloqueado("Un usuario propietario se loggeo desde la ip:".$visitingIp. ", desde la cual este usuario NO propietario se habia loggeado.");
                                $nopropietario->setIpAmbigua(true);
                                $nopropietario->save();
                            }
                            if(count($noPropietarios) > 0) {
                                // $user->setBloqueado("Se loggeo usuario propietario desde la ip:".$visitingIp. " desde la cual ya se habia loggeado un usuario NO propietario.");
                                $user->setIpAmbigua(true);
                                $user->save();
                            }
                        } else {

                            $propietarios = Doctrine::getTable('user')->getPropietarioByIp($visitingIp, true);
                            foreach ($propietarios as $propietario) {
                                // $propietario->setBloqueado("Se loggeo usuario NO propietario desde la ip:".$visitingIp. " desde la cual ya se habia loggeado este usuario propietario.");
                                $propietario->setIpAmbigua(true);
                                $propietario->save();
                            }
                            if(count($propietarios) > 0) {
                                // $user->setBloqueado("Se loggeo este usuario NO propietario desde la ip:".$visitingIp. " desde la cual ya se habia loggeado un usuario propietario.");
                                $user->setIpAmbigua(true);
                                $user->save();
                            }
                        }
                    }

                    $this->getUser()->setFlash('msg', 'Autenticado');
                    $this->getUser()->setAuthenticated(true);

                    $this->getUser()->setAttribute("logged", true);
                    $this->getUser()->setAttribute("loggedFb", false);
                    $this->getUser()->setAttribute("userid", $user->getId());
                    $this->getUser()->setAttribute("fecha_registro", $user->getFechaRegistro());
                    $this->getUser()->setAttribute("email", $user->getEmail());
                    $this->getUser()->setAttribute("telephone", $user->getTelephone());
                    $this->getUser()->setAttribute("comuna", $user->getNombreComuna());
                    $this->getUser()->setAttribute("region", $user->getNombreRegion());
                    $this->getUser()->setAttribute("name", current(explode(' ', $user->getFirstName())) . " " . substr($user->getLastName(), 0, 1) . '.');

                    $this->logMessage('firstname', 'err');
                    $this->logMessage($user->getFirstName(), 'err');

                    $this->getUser()->setAttribute("firstname", $user->getFirstName());

                    if ($user->getPictureFile()) {
                        $this->getUser()->setAttribute("picture_file", $user->getPictureFile());
                    }

                    //Modificacion para identificar si el usuario es propietario o no de vehiculo
                    if ($user->getPropietario()) {
                        $this->getUser()->setAttribute("propietario", true);
                    } else {
                        $this->getUser()->setAttribute("propietario", false);
                    }

                    if ($this->getUser()->getAttribute('lastview') != null) {
                        $this->redirect($this->getUser()->getAttribute('lastview'));
                    }

                    $this->getUser()->setAttribute('geolocalizacion', true);

                    /*sfContext::getInstance()->getLogger()->info($_SESSION['login_back_url']);
                    $this->redirect($_SESSION['login_back_url']);*/

                    $this->redirect($this->getUser()->getAttribute("referer"));
                    $this->calificacionesPendientes();
                } else {

                    $this->getUser()->setFlash('msg', 'Su cuenta no ha sido activada. Puede hacerlo siguiendo este <a href="activate">link</a>');
                    $this->getUser()->setFlash('show', true);

                    $url = $this->getUser()->getAttribute("referer", false)?:"@homepage";
                    $this->getUser()->setAttribute("referer", false);
                    $this->redirect($url);

                    $this->forward('main', 'login');
                }
            } else {

                $this->getUser()->setFlash('msg', 'Usuario o contraseña inválido');
                $this->getUser()->setFlash('show', true);

                $this->forward('main', 'login');
            }
        }
        
        /*$this->forward('main', 'index');*/
        $this->redirect("homepage");

        return sfView::NONE;
    }

    public function executeLoginFacebook(sfWebRequest $request) {

        $app_id = "213116695458112";
        $app_secret = "8d8f44d1d2a893e82c89a483f8830c25";
        //$my_url = "http://m.arriendas.cl/main/loginFacebook";
        $my_url = $this->generateUrl("facebook_login", array(), true);
        $referer = $this->getUser()->getAttribute("referer");
        // $app_id = "297296160352803";
        // $app_secret = "e3559277563d612c3c20f2c202014cec";
        // $my_url = "http://test.intothewhitebox.com/yineko/arriendas/main/loginFacebook";
        $code = $request->getParameter("code");
        $state = $request->getParameter("state");
        $previousUser= $request->getParameter("logged");
        $returnRoute = $request->getParameter("return");
        
        if($previousUser) {
           $my_url.="?logged=true";
        }

        if($returnRoute){
           $my_url.="?return=".$returnRoute;
        }
        
        if (empty($code)) {
            $this->getUser()->setAttribute('state', md5(uniqid(rand(), TRUE)));
            $dialog_url = sprintf("https://m.facebook.com/dialog/oauth?client_id=%s&redirect_uri=%s&state=%s&scope=%s,%s", $app_id, urlencode($my_url), $this->getUser()->getAttribute('state'), "email", "user_photos");
            return $this->redirect($dialog_url);
        }
    
        if($previousUser) {

            $user_id= $this->getUser()->getAttribute("userid");
            $myUser = Doctrine::getTable('User')->findOneById($user_id);
            $token_url = sprintf("https://graph.facebook.com/oauth/access_token?client_id=%s&redirect_uri=%s&client_secret=%s&code=%s", $app_id, urlencode($my_url), $app_secret, $code);
            $response = @file_get_contents($token_url);
            $params = null;
            parse_str($response, $params);
            $graph_url = sprintf("https://graph.facebook.com/me?access_token=%s", $params['access_token']);
            $user = json_decode(file_get_contents($graph_url), true);
            $photo_url = sprintf("https://graph.facebook.com/%s/picture", $user["id"]);
            $myUser->setFacebookId($user["id"]);
            $myUser->setPictureFile($photo_url);
            $myUser->setConfirmedFb(true);
            $myUser->setUsername($user["email"]);
            $myUser->save();
            if($returnRoute){
                $this->redirect($this->generateUrl($returnRoute));

            }else{
                $this->redirect('homepage');
            }
        }
    
        //modificacion por problema aleatorio con login with facebook
        if ($state == $this->getUser()->getAttribute('state') || 1==1) {

            $token_url = sprintf("https://graph.facebook.com/oauth/access_token?client_id=%s&redirect_uri=%s&client_secret=%s&code=%s", $app_id, urlencode($my_url), $app_secret, $code);

            $response = @file_get_contents($token_url);
            $params = null;
            parse_str($response, $params);

            $graph_url = sprintf("https://graph.facebook.com/me?access_token=%s", $params['access_token']);
            $user = json_decode(file_get_contents($graph_url), true);
            $photo_url = sprintf("https://graph.facebook.com/%s/picture", $user["id"]);

            $myUser = Doctrine::getTable('User')->findOneByFacebookId($user["id"]);

            if (!$myUser) {

                if ($user["email"]) {
                    $myUser = Doctrine::getTable('User')->findOneByEmail($user["email"]);
                } else {
                    $myUser = null;
                }

                if (!$myUser) {
                    $newUser=true;
                    $myUser = new User();
                    $myUser->setFirstname($user["first_name"]);
                    $myUser->setLastname($user["last_name"]);
                    $myUser->setUsername($user["email"]);
                    $myUser->setEmail($user["email"]);
                    $myUser->setUrl($user["link"]);
                    $myUser->setFacebookId($user["id"]);
                    $myUser->setPictureFile($photo_indexurl);
                    $myUser->setConfirmedFb(true);
                    $myUser->save();
                } else {
                    $newUser=false;
                    $myUser->setFacebookId($user["id"]);
                    $myUser->setPictureFile($photo_url);
                    $myUser->setConfirmedFb(true);
                    $myUser->save();
                }
                //Seteamos el ID del recomendador, en caso de venir
            }
        
            if($_SESSION['sender_user']!="") {
                $q = Doctrine_Manager::getInstance()->getCurrentConnection();
                $query="UPDATE User SET recommender = '".$_SESSION['sender_user']."' WHERE facebook_id='".$user['id']."';";
                $result = $q->execute($query);
            }
        
            $q = Doctrine::getTable('user')->createQuery('u')->where('u.facebook_id = ? ', array($user["id"]));

            $userdb = $q->fetchOne();

            if ($userdb) {
                /* track ip */
                $visitingIp = $request->getRemoteAddress();
                $userdb->trackIp($visitingIp);

                if(Doctrine::getTable('user')->isABlockedIp($visitingIp)) {
                    $userdb->setIsSuspect(true);
                    $userdb->save();
                }

                $this->getUser()->setAuthenticated(true);
                $this->getUser()->setAttribute("logged", true);
                $this->getUser()->setAttribute("loggedFb",true);
                $this->getUser()->setAttribute("userid", $userdb->getId());
                $this->getUser()->setAttribute("name", current(explode(' ' , $userdb->getFirstName())) . " " . substr($userdb->getLastName(), 0, 1) . '.');
                $this->getUser()->setAttribute("picture_url", $userdb->getPictureFile());
                $this->getUser()->setAttribute("firstname", $userdb->getFirstName());
                $this->getUser()->setAttribute("fecha_registro", $userdb->getFechaRegistro());
                $this->getUser()->setAttribute("email", $userdb->getEmail());
                $this->getUser()->setAttribute("telephone", $userdb->getTelephone());
                $this->getUser()->setAttribute("comuna", $userdb->getNombreComuna());
                $this->getUser()->setAttribute("region", $userdb->getNombreRegion());
                $this->getUser()->setAttribute("fb", true); 
                //Modificacion para identificar si el usuario es propietario o no de vehiculo
                if($userdb->getPropietario()) {
                    $this->getUser()->setAttribute("propietario",true);             
                } else {
                    $this->getUser()->setAttribute("propietario",false);
                }
    
                $this->getUser()->setAttribute('geolocalizacion', true);

                $this->calificacionesPendientes();

                if ($newUser) {
                    //$this->getRequest()->setParameter('userId', $userdb->getId());
                    $this->redirect('user_register_complete');
                }else{
                    if ($referer != null) {
                        $this->redirect($referer);
                    } else {
                        $this->redirect('main/index');
                        //$this->redirect('profile/cars');
                        //$this->redirect($_SESSION['login_back_url']);
                    }
                }
    
            } else {
                $this->getUser()->setFlash('msg', 'Hubo un error en el login con Facebook');
                $this->forward('main', 'login');
            }
        } else {
            $this->forward404();
        }
    }

    public function executeLogout() {

        if ($this->getUser()->isAuthenticated()) {

            $this->getUser()->setAuthenticated(false);
            $this->getUser()->getAttributeHolder()->clear();
        }

        return $this->redirect('homepage');
    }

    public function executeMessageRegister(sfWebRequest $request) {

        $this->setLayout("newIndexLayout");
    }

    public function executeNews(sfWebRequest $request) {
        $this->setLayout("newIndexLayout");
    }

    public function executeNotificationClose(sfWebRequest $request) {

        $userNotificationId = $this->getUser()->getAttribute("notificationId");

        try {            

            $UN = Doctrine_Core::getTable('UserNotification')->find($userNotificationId);
            if (!$UN) {
                throw new Exception("No se encontro la UserNotification ".$userNotificationId, 1);
            }

            $UN->setClosedAt(date("Y-m-d H:i:s"));

            $this->getUser()->setAttribute("notificationMessage", null);
            $this->getUser()->setAttribute("notificationId", null);

            $UN->save();

        } catch (Exception $e) {
            error_log("[main/notificationClose] ERROR: ".$e->getMessage());
        }

        return sfView::NONE;
    }

    public function executeQuestions(sfWebRequest $request) {
        $this->setLayout("newIndexLayout");
    }

    public function executeRecover(sfWebRequest $request) {
        $this->setLayout("newIndexLayout");

        $this->id = $request->getParameter('id');

        $this->id = str_replace("%2540", "@", $this->id);
        $this->id = str_replace("%40", "@", $this->id);
    
        $this->hash = $request->getParameter('hash');

        $q = Doctrine::getTable('user')->createQuery('u')->where('u.id = ? and u.hash = ?', array($this->id, $this->hash));
        $user = $q->fetchOne();
        if (!$user) {
            $this->redirect('homepage');
        }
    }

    public function executeRegister(sfWebRequest $request) {

        $this->setLayout("newIndexLayout");

        $this->Regions = Region::getRegionsByNaturalOrder();

        if (!isset($_SESSION['reg_back'])) {
            $urlpage = split('/', $request->getReferer());

            if ($urlpage[count($urlpage) - 1] != "register" && $urlpage[count($urlpage) - 1] != "doRegister") {
                $_SESSION['reg_back'] = $request->getReferer();
            }
        }
    }

    public function executeReserve (sfWebRequest $request) {

        $this->setLayout("newIndexLayout");

        if ($this->getUser()->getAttribute("from", false)) {
            $from = $this->getUser()->getAttribute("from");
        } else {
            $from = date("Y-m-d H:i:s");
        }
        
        if ($this->getUser()->getAttribute("to", false)) {
            $to = $this->getUser()->getAttribute("to");
        } else {
            $to = date("Y-m-d H:i:s", strtotime("+1 day", strtotime($from)));
        }        

        /*$userId = $this->getUser()->getAttribute("userid");*/

        $carId = $request->getParameter("carId", null);

        if (is_null($carId)) {
            $this->forward404();
        }

        $f = strtotime($from);
        $t = strtotime($to);

        if ($t <= $f) {
            throw new Exception("No, no, no", 1);
        }

        $from = date("Y-m-d H:i", $f);
        $this->from = date("Y-m-d H:i", $f);
        $this->fromHuman = date("D d/m/Y H:i", $f);
        $to = date("Y-m-d H:i", $t);
        $this->to = date("Y-m-d H:i", $t);
        $this->toHuman = date("D d/m/Y H:i", $t);

        $this->Car = Doctrine_Core::getTable('Car')->find($carId);
        if (!$this->Car) {
            $this->forward404();
        }

        if ($this->Car->hasReserve($from, $to)) {
            throw new Exception("Auto ya posee reserva", 1);        
        }

        $this->time = Car::getTime($from, $to);
        $this->price = CarTable::getPrice($from, $to, $this->Car->getPricePerHour(), $this->Car->getPricePerDay(), $this->Car->getPricePerWeek(), $this->Car->getPricePerMonth());

        // Reviews

        $this->reviews = null;
        $this->reviews_avg = 0;
        
        $Ratings = Doctrine_Core::getTable('Rating')->getOwnerReviewsOrderByDateById($this->Car->getUserId());
        if ($Ratings) {

            $this->reviews = array();

            foreach ($Ratings as $Rating) {

                $U = Doctrine_Core::getTable('User')->find($Rating->getIdRenter());

                $review = array(
                    "user_name" => $U->getFirstname()." ".$U->getLastname(),
                    "user_photo" => null,
                    "date" => date("d-m-Y", strtotime($Rating->getFechaCalificacionOwner())),
                    "rating" => $Rating->getOpCleaningAboutOwner(),
                    "opinion" => $Rating->getOpinionAboutOwner()
                );

                if ($U->getPictureFile() && !strstr($U->getPictureFile(), "/var/")) {
                    $review["user_photo"] = $U->getPictureFile();
                }

                $this->reviews[] = $review;

                $this->reviews_avg += $review["rating"];
            }

            $this->reviews_avg = round($this->reviews_avg / count($this->reviews), 0);
        }

        // Características
        $this->passengers = false;
        if ($this->Car->getModel()->getIdOtroTipoVehiculo() >= 2) {
            $this->passengers = true;
        }

        $this->diesel = false;
        if ($this->Car->getTipobencina() == "Diesel") {
            $this->diesel = true;
        }

        $this->airCondition = false;
        if (explode(",", $this->Car->getAccesoriosSeguro())[0] == "aireAcondicionado") {
            $this->airCondition = true;
        }

        $this->transmission = false;
        if ($this->Car->getTransmission()) {
            $this->transmission = true;
        }

        /*$userId = $this->getUser()->getAttribute("userid");
        $User = Doctrine_Core::getTable('User')->find($userId);

        $this->license            = $User->getDriverLicenseFile();
        $this->isDebtor           = $User->moroso;*/
        $this->amountWarranty     = sfConfig::get("app_monto_garantia");
        $this->amountWarrantyFree = sfConfig::get("app_monto_garantia_por_dia");

        //imagenes
        $arrayImagenes = null;
        $i = 0;
        if ($this->Car->getSeguroFotoFrente() != null && $this->Car->getSeguroFotoFrente() != "") {
            $rutaFotoFrente = $this->Car->getSeguroFotoFrente();
            $arrayImagenes[$i] = $rutaFotoFrente;
            $i++;
        }
        if ($this->Car->getSeguroFotoCostadoDerecho() != null && $this->Car->getSeguroFotoCostadoDerecho() != "") {
            $rutaFotoCostadoDerecho = $this->Car->getSeguroFotoCostadoDerecho();
            $arrayImagenes[$i] = $rutaFotoCostadoDerecho;
            $i++;
        }
        if (strpos($this->Car->getSeguroFotoCostadoIzquierdo(), "http") != -1 && $this->Car->getSeguroFotoCostadoIzquierdo() != "") {
            $rutaFotoCostadoIzquierdo = $this->Car->getSeguroFotoCostadoIzquierdo();
            $arrayImagenes[$i] = $rutaFotoCostadoIzquierdo;
            $i++;
        }
        if (strpos($this->Car->getSeguroFotoTraseroDerecho(), "http") != -1 && $this->Car->getSeguroFotoTraseroDerecho() != "") {
            $rutaFotoTrasera = $this->Car->getSeguroFotoTraseroDerecho();
            $arrayImagenes[$i] = $rutaFotoTrasera;
            $i++;
        }
        if (strpos($this->Car->getSeguroFotoTraseroIzquierdo(), "http") != -1 && $this->Car->getSeguroFotoTraseroIzquierdo() != "") {
            $rutaFotoTrasera = $this->Car->getSeguroFotoTraseroIzquierdo();
            $arrayImagenes[$i] = $rutaFotoTrasera;
            $i++;
        }
        if (strpos($this->Car->getTablero(), "http") != -1 && $this->Car->getTablero() != "") {
            $rutaFotoPanel = $this->Car->getTablero();
            $arrayImagenes[$i] = $rutaFotoPanel;
            $i++;
        }
        if (strpos($this->Car->getAccesorio1(), "http") != -1 && $this->Car->getAccesorio1() != "") {
            $rutaFotoAccesorios1 = $this->Car->getAccesorio1();
            $arrayImagenes[$i] = $rutaFotoAccesorios1;
            $i++;
        }
        if (strpos($this->Car->getAccesorio2(), "http") != -1 && $this->Car->getAccesorio2() != "") {
            $rutaFotoAccesorios2 = $this->Car->getAccesorio2();
            $arrayImagenes[$i] = $rutaFotoAccesorios2;
        }

        $this->arrayFotos = $arrayImagenes;
    }

    public function executeSiteMap(sfWebRequest $request) {
        $this->setLayout("newIndexLayout");
    }

    public function executeTerminos(sfWebRequest $request) {
        $this->setLayout("newIndexLayout");
    }

  	public function executeSearchACars(sfWebRequest $request) {
        
        $this->setLayout("newIndexLayout");

        $this->isWeekend  = false;
        $this->isMobile   = false;

        if (is_null($this->getUser()->getAttribute('geolocalizacion'))) {
            $this->getUser()->setAttribute('geolocalizacion', true);
        } elseif ($this->getUser()->getAttribute('geolocalizacion') == true) {
            $this->getUser()->setAttribute('geolocalizacion', false);
        }

        $this->limit = 5;
        $this->isMobile = true;

        $this->Region = Doctrine_Core::getTable("Region")->find(13);
        $this->hasCommune = false;
        $this->hasRegion = false;

        if ($request->hasParameter('region','commune', 'carType')){
            $regionSlug = $request->getParameter('region');
            $this->hasRegion = Doctrine_Core::getTable('Region')->findOneBySlug($regionSlug)->id;

            $communeSlug = $request->getParameter('commune');
            
            if(isset($communeSlug)){
                               
                $this->hasCommune = Doctrine_Core::getTable('Commune')->findOneBySlug($communeSlug)->id;
                $nameComune = Doctrine_Core::getTable('Commune')->findOneBySlug($communeSlug)->name;
                //esto es para el titulo
                $this->getResponse()->setTitle(sprintf('Arriendo de Autos entre persona. Rent a car en %s, Region Metropolitana, Chile ', $nameComune));
            }
        }
    }

    public function executeUploadLicense (sfWebRequest $request) {
    
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

            $tmp = $_FILES[$request->getParameter('file')]['tmp_name'];

            if (!move_uploaded_file($tmp, $path . $newImageName)) {
                throw new Exception("El User ".$userId." tiene problemas para grabar la imagen de su licencia", 1);
            }

            $User = Doctrine_Core::getTable('User')->find($userId);
            $User->setDriverLicenseFile("/images/licence/".$newImageName);
            $User->save();
    
        } catch (Exception $e) {
            $return["error"] = true;
            if ($e->getCode() < 2) {
                $return["errorMessage"] = "Problemas al subir la imagen. El problema ha sido notificado al equipo de desarrollo, por favor, intentalo nuevamente más tarde";
            } else {
                $return["errorMessage"] = $e->getMessage();
            }
            error_log("[".date("Y-m-d H:i:s")."] [main/uploadLicense] ERROR: ".$e->getMessage());
            if ($request->getHost() == "www.arriendas.cl" && $e->getCode() < 2) {
                Utils::reportError($e->getMessage(), "main/uploadLicense");
            }
        }

        $this->renderText(json_encode($return));

        return sfView::NONE;
    }

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
            error_log("[".date("Y-m-d H:i:s")."] [main/uploadLicenseWarning] ERROR: ".$e->getMessage());
            if ($request->getHost() == "www.arriendas.cl" && $e->getCode() < 2) {
                Utils::reportError($e->getMessage(), "main/uploadLicenseWarning");
            }
        }

        $this->renderText(json_encode($return));

        return sfView::NONE;
    }

    public function calificacionesPendientes(){
        //crear tabla en calificaciones una vez que la reserva haya expirado
        //enviar mail a propietario y arrendatario informando
    }

    public function sendRecoverEmail($user) {

        $url = $_SERVER['SERVER_NAME'];
        $url = str_replace('http://', '', $url);
        $url = str_replace('https://', '', $url);
        // $url = 'http://m.arriendas.cl/main/recover?email=' . $user->getEmail() . "&hash=" . $user->getHash();
        $url = 'http://m.arriendas.cl/contrasena/modificar/' . $user->getId() . "/" . $user->getHash();
        $this->logMessage($url);
        $body = "<p>Hola:</p><p>Para generar una nueva contraseña, haz click <a href='$url'>aqu&iacute;</a></p>";
        $message = $this->getMailer()->compose();
        $message->setSubject("Recuperar Password");
        $message->setFrom('soporte@arriendas.cl', 'Soporte Arriendas');
        $message->setTo($user->getEmail());
        $message->setBody($body, "text/html");
        $this->getMailer()->send($message);

        $this->logMessage($body);
        $this->logMessage('mail sent');
    }


    
}
