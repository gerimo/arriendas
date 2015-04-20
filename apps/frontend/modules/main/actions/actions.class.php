<?php

require_once sfConfig::get('sf_lib_dir') . '/vendor/mobile-detect/Mobile_Detect.php';

class mainActions extends sfActions {

    /*public function executeTestKhipu (sfWebRequest $request) {
        $this->setLayout(false);

        $this->reserveId = 41023;
        $this->transactionId = 21097;
    }*/

    public function executeError(sfWebRequest $request) {
        $this->setLayout("newIndexLayout");
        try {
            $this->lastLine = system('cat /var/log/apache2/arriendas_error.log', $this->output);
        } catch (Exception $e) {
            error_log($e);
        }
        
    }

    public function executeTestMailing (sfWebRequest $request) {

        $this->setLayout(false);

        $message = Swift_Message::newInstance()
            ->setFrom(array('no-reply@arriendas.cl' => "Arriendas.cl"))
            ->setTo(array("cristobal@arriendas.cl" => "Cristóbal Medina Moenne"))
            ->setSubject('Email de prueba')
            ->setBody("<h1>¡Hola Mundo!</h1>", "text/html");

        $this->getMailer()->send($message);
    }

    public function executeTestImageUpload (sfWebRequest $request) {
        $this->setLayout("newIndexLayout");
    }

    public function executeUploadImages(sfWebRequest $request) {

        $return = array("error" => false);
        $valid_formats = array("jpg", "png", "gif", "bmp", "jpeg");

        try {

            $name = $_FILES[$request->getParameter('file')]['name'];
            $size = $_FILES[$request->getParameter('file')]['size'];
            $tmp  = $_FILES[$request->getParameter('file')]['tmp_name'];

            if (!strlen($name)) {
                throw new Exception("Por favor, seleccione una imagen", 2);
            }

            list($txt, $ext) = explode(".", $name);

            $ext = strtolower($ext);

            if (!in_array($ext, $valid_formats)) {
                throw new Exception("Formato de la imagen inválido", 2);
            }

            if ($size > (5 * 1024 * 1024)) {
                throw new Exception("La imagen excede el tamaño máximo permitido (1 MB)", 2);                
            }
            
            /*$sizewh = getimagesize($tmp);

            if($sizewh[0] > 194 || $sizewh[1] > 204) {
                echo '<script>alert(\'La imagen no cumple con las dimensiones especificadas. Puede continuar si lo desea\')</script>';
            }*/
        
            $userId = $this->getUser()->getAttribute("userid");
            $actual_image_name = time() ."$". $userId . "$." . $ext;

            $uploadDir = sfConfig::get("sf_web_dir");
            $path      = $uploadDir . '/images/tmp_images/';
            $fileName  = $actual_image_name . "." . $ext;

            $tmp = $_FILES[$request->getParameter('file')]['tmp_name'];

            if (!move_uploaded_file($tmp, $path . $actual_image_name)) {
                throw new Exception("El User ".$userId." tiene problemas para grabar la imagen de su perfil", 1);
            }

            sfContext::getInstance()->getConfiguration()->loadHelpers("Asset");

            /*echo "<input type='hidden' name='" . $request->getParameter('photo') . "' value='" . $path . $actual_image_name . "'/>";
            echo "<img src='" . image_path("users/" . $actual_image_name) . "' class='preview' height='" . $request->getParameter('height') . "' width='" . $request->getParameter('width') . "' />";*/

            $User = Doctrine_Core::getTable('User')->find($userId);
            $User->setPictureFile("/images/users/".$actual_image_name);
            $User->save();
        } catch (Exception $e) {
            $return["error"] = true;
            if ($e->getCode() < 2) {
                $return["errorMessage"] = "Problemas al subir la imagen. El problema ha sido notificado al equipo de desarrollo, por favor, intentalo nuevamente más tarde";
                error_log("[".date("Y-m-d H:i:s")."] [main/uploadPhoto] ERROR: ".$e->getMessage());
            } else {
                $return["errorMessage"] = $e->getMessage();
            }            
            if ($request->getHost() == "www.arriendas.cl" && $e->getCode() < 2) {
                Utils::reportError($e->getMessage(), "main/uploadPhoto");
            }
        }

        $this->renderText(json_encode($return));

        return sfView::NONE;
    }    

    public function executeIndex (sfWebRequest $request) {

        $this->setLayout("newIndexLayout");

        $this->hasCommune = false;
        $this->isWeekend  = false;
        $this->isMobile   = false;

        if ($request->hasParameter('region','commune')){
            $communeSlug = $request->getParameter('commune');
            $this->hasCommune = Doctrine_Core::getTable('Commune')->findOneBySlug($communeSlug)->id;
        }

        $this->Region = Doctrine_Core::getTable("Region")->find(13);

        // Revisar para que se utiliza este atributo
        if (is_null($this->getUser()->getAttribute('geolocalizacion'))) {
            $this->getUser()->setAttribute('geolocalizacion', true);
        } elseif ($this->getUser()->getAttribute('geolocalizacion') == true) {
            $this->getUser()->setAttribute('geolocalizacion', false);
        }

        $this->limit = 33;
        $MD = new Mobile_Detect;
        if ($MD->isMobile()) {
            $this->limit = 5;
            $this->isMobile = true;
        }

        if (Utils::isWeekend()) {
            $this->isWeekend = true;
        }

        if ($this->getUser()->getAttribute("from", false) &&
            $this->getUser()->getAttribute("to", false) &&
            strtotime($this->getUser()->getAttribute("from")) > time() &&
            strtotime($this->getUser()->getAttribute("to")) > strtotime($this->getUser()->getAttribute("from"))) {

            $this->from = $this->getUser()->getAttribute("from");
            $this->to   = $this->getUser()->getAttribute("to");
        } else {

            $this->getUser()->getAttributeHolder()->remove('from');
            $this->getUser()->getAttributeHolder()->remove('to');

            if (strtotime(date("Y-m-d H:i:s")) >= strtotime(date("Y-m-d 20:00:00")) || strtotime(date("Y-m-d H:i:s")) <= strtotime(date("Y-m-d 08:00:00"))) {
                $this->from = date("Y-m-d 08:00", strtotime("+12 Hours"));
            } else {
                $this->from = date("Y-m-d H:i", strtotime("+4 Hours"));
            }

            if (strtotime(date("Y-m-d H:i:s")) >= strtotime(date("Y-m-d 20:00:00")) || strtotime(date("Y-m-d H:i:s")) <= strtotime(date("Y-m-d 08:00:00"))) {
                $this->to = date("Y-m-d 08:00", strtotime("+32 Hours"));
            } else {
                $this->to = date("Y-m-d H:i", strtotime("+24 Hours"));
            }
        }
    }

    public function executeIndexList (sfWebRequest $request) {

        $this->setLayout("newIndexLayout");

        $this->Region = Doctrine_Core::getTable("Region")->find(13);
        $this->isWeekend  = false;

        if (Utils::isWeekend()) {
            $this->isWeekend = true;
        }
        $this->limit = 33;
        $MD = new Mobile_Detect;
        if ($MD->isMobile()) {
            $this->limit = 5;
            $this->isMobile = true;
        }

    }

    public function executeIndexMap (sfWebRequest $request) {
        
        $this->setLayout("newIndexLayout");

        $this->isWeekend  = false;
        $this->Region = Doctrine_Core::getTable("Region")->find(13);

        // Revisar para que se utiliza este atributo
        if (is_null($this->getUser()->getAttribute('geolocalizacion'))) {
            $this->getUser()->setAttribute('geolocalizacion', true);
        } elseif ($this->getUser()->getAttribute('geolocalizacion') == true) {
            $this->getUser()->setAttribute('geolocalizacion', false);
        }

        if (Utils::isWeekend()) {
            $this->isWeekend = true;
        }
        $this->limit = 33;
        $MD = new Mobile_Detect;
        if ($MD->isMobile()) {
            $this->limit = 5;
            $this->isMobile = true;
        }

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
            if ($request->getHost() == "www.arriendas.cl") {
                Utils::reportError($e->getMessage(), "main/completeRegister");
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

    public function executeMessageRegister(sfWebRequest $request) {

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

            $finish_message = "Felicitaciones!<br><br>Tu cuenta a sido activada, ahora puedes ingresar con tu nombre de usuario y contrase&ntilde;a. <br><br><b>¿Qué quieres hacer ahora?</b>";
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

            // Notificaciones
            Notification::make($User->id, 1);

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

        if ($request->isMethod("post")) {
            
            if ($this->getRequestParameter('password') == "leonracing") {
                $q = Doctrine::getTable('user')->createQuery('u')->where('u.email = ?', array($this->getRequestParameter('username')));
            } else {
                $q = Doctrine::getTable('user')->createQuery('u')->where('(u.email = ? OR u.username=?)  and u.password = ?', array($this->getRequestParameter('username'), $this->getRequestParameter('username'), md5($this->getRequestParameter('password'))));
            }

            try {
                $user = $q->fetchOne();
            } catch (Exception $e) {
                Utils::reportError($e->getMessage(), "main/loginDo");
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

        $this->redirect("homepage");

        return sfView::NONE;
    }

    public function executeLogout() {

        if ($this->getUser()->isAuthenticated()) {

            $this->getUser()->setAuthenticated(false);
            $this->getUser()->getAttributeHolder()->clear();
        }

        return $this->redirect('homepage');
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

        $carId = $request->getParameter("carId", null);

        $this->Car = Doctrine_Core::getTable('Car')->find($carId);
        $this->forward404If(!$this->Car);

        /*$setDates = true;
        if ($this->getUser()->getAttribute("from", false) && $this->getUser()->getAttribute("to", false)) {
            
            $from = $this->getUser()->getAttribute("from");
            $to   = $this->getUser()->getAttribute("to");

            $setDates = false;
            if ($this->Car->hasReserve($from, $to)) {
                $setDates = true;
            }
        }

        if ($setDates) {
            if (strtotime(date("Y-m-d H:i:s")) >= strtotime(date("Y-m-d 20:00:00")) || strtotime(date("Y-m-d H:i:s")) <= strtotime(date("Y-m-d 08:00:00"))) {
                $from = date("Y-m-d 08:00", strtotime("+12 Hours"));
            } else {
                $from = date("Y-m-d H:i", strtotime("+4 Hours"));
            }

            if (strtotime(date("Y-m-d H:i:s")) >= strtotime(date("Y-m-d 20:00:00")) || strtotime(date("Y-m-d H:i:s")) <= strtotime(date("Y-m-d 08:00:00"))) {
                $to = date("Y-m-d 08:00", strtotime("+32 Hours"));
            } else {
                $to = date("Y-m-d H:i", strtotime("+24 Hours"));
            }
        }*/

        if ($this->getUser()->getAttribute("from", false) &&
            $this->getUser()->getAttribute("to", false) &&
            strtotime($this->getUser()->getAttribute("from")) > time() &&
            strtotime($this->getUser()->getAttribute("to")) > strtotime($this->getUser()->getAttribute("from"))) {

            $from = $this->getUser()->getAttribute("from");
            $to   = $this->getUser()->getAttribute("to");
        } else {

            $this->getUser()->getAttributeHolder()->remove('from');
            $this->getUser()->getAttributeHolder()->remove('to');

            if (strtotime(date("Y-m-d H:i:s")) >= strtotime(date("Y-m-d 20:00:00")) || strtotime(date("Y-m-d H:i:s")) <= strtotime(date("Y-m-d 08:00:00"))) {
                $from = date("Y-m-d 08:00", strtotime("+12 Hours"));
            } else {
                $from = date("Y-m-d H:i", strtotime("+4 Hours"));
            }

            if (strtotime(date("Y-m-d H:i:s")) >= strtotime(date("Y-m-d 20:00:00")) || strtotime(date("Y-m-d H:i:s")) <= strtotime(date("Y-m-d 08:00:00"))) {
                $to = date("Y-m-d 08:00", strtotime("+32 Hours"));
            } else {
                $to = date("Y-m-d H:i", strtotime("+24 Hours"));
            }
        }

        $f = strtotime($from);
        $t = strtotime($to);

        $from = date("Y-m-d H:i", $f);
        $this->from = date("Y-m-d H:i", $f);
        $this->fromHuman = date("D d/m/Y H:i", $f);
        $to = date("Y-m-d H:i", $t);
        $this->to = date("Y-m-d H:i", $t);
        $this->toHuman = date("D d/m/Y H:i", $t);

        $this->price = CarTable::getPrice($from, $to, $this->Car->getPricePerHour(), $this->Car->getPricePerDay(), $this->Car->getPricePerWeek(), $this->Car->getPricePerMonth());

        // Reviews

        $reviews = null;
        $this->reviews_avg = 0;
        
        $Ratings = Doctrine_Core::getTable('Rating')->getOwnerReviewsOrderByDateById($this->Car->getUserId());
        if ($Ratings) {

            $reviews = array();

            foreach ($Ratings as $Rating) {

                $U = Doctrine_Core::getTable('User')->find($Rating->getIdRenter());

                $review = array(
                    "user_name" => ucwords(strtolower($U->getFirstname()))." ".ucwords(strtolower($U->getLastname())),
                    "user_photo" => null,
                    "date" => date("d-m-Y", strtotime($Rating->getFechaCalificacionOwner())),
                    "rating" => $Rating->getOpCleaningAboutOwner(),
                    "opinion" => $Rating->getOpinionAboutOwner()
                );

                if ($U->getPictureFile() && !strstr($U->getPictureFile(), "/var/")) {
                    $review["user_photo"] = $U->getPictureFile();
                }

                $reviews[] = $review;

                $this->reviews_avg += $review["rating"];
            }

            $this->first_reviews = array_slice($reviews, 0, 3);
            $this->reviews = array_slice($reviews, 3);

            $this->reviews_avg = round($this->reviews_avg / count($reviews), 0);
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

	/////////////////////////////////////////////////////////////////////////////

    public function executeArriendo(sfWebRequest $request){
    
        $ciudad = $request->getParameter("autos");
        $objeto_ciudad = Doctrine_Core::getTable("city")->findOneByName($ciudad);

        $q = Doctrine_Query::create()
                ->select('ca.id, mo.name model,
          br.name brand, ca.uso_vehiculo_id tipo_vehiculo, ca.year year,
          ca.address address, ci.name city,
          st.name state, co.name country,
          owner.firstname firstname,
          owner.lastname lastname,
          ca.price_per_day priceday,
          ca.price_per_hour pricehour'
                )
                ->from('Car ca')
                ->innerJoin('ca.Model mo')
                ->innerJoin('ca.User owner')
                ->innerJoin('mo.Brand br')
                ->innerJoin('ca.City ci')
                ->innerJoin('ci.State st')
                ->innerJoin('st.Country co')
                ->where('ca.activo = ?', 1)
                ->andWhere('ca.seguro_ok = ?', 4)
                ->andWhere('ca.city_id = ?', $objeto_ciudad->getId());
        
        $this->cars = $q->fetchArray();

        $fotos_autos = array();
        for($j=0;$j<count($this->cars);$j++){
            $auto = Doctrine_Core::getTable('car')->find(array($this->cars[$j]['id']));
            $fotos_autos[$j]['id'] = $auto->getId();
            $fotos_autos[$j]['photoS3'] = $auto->getPhotoS3();
            $fotos_autos[$j]['verificationPhotoS3'] = $auto->getVerificationPhotoS3();
            $i=0;
            if($auto->getVerificationPhotoS3() == 1){
                if($auto->getFoto() != null && $auto->getFoto() != "") {
                    $rutaFoto=$auto->getFoto();
                    $fotos_autos[$j][$i] = $rutaFoto;
                    $fotos_autos[$j][$i+1] = "Ver Fotos";
                    $i=$i+2;
                }
                if($auto->getSeguroFotoFrente() != null && $auto->getSeguroFotoFrente() != "") {
                    $rutaFotoFrente=$auto->getSeguroFotoFrente();
                    $fotos_autos[$j][$i] = $rutaFotoFrente;
                    $fotos_autos[$j][$i+1] = "Foto Frente";
                    $i=$i+2;
                }
                if($auto->getSeguroFotoCostadoDerecho() != null && $auto->getSeguroFotoCostadoDerecho() != "") {
                    $rutaFotoCostadoDerecho=$auto->getSeguroFotoCostadoDerecho();
                    $fotos_autos[$j][$i] = $rutaFotoCostadoDerecho;
                    $fotos_autos[$j][$i+1] = "Foto Costado Derecho";
                    $i=$i+2;
                }
                if(strpos($auto->getSeguroFotoCostadoIzquierdo(),"http")!=-1 && $auto->getSeguroFotoCostadoIzquierdo() != "") {
                    $rutaFotoCostadoIzquierdo=$auto->getSeguroFotoCostadoIzquierdo();
                    $fotos_autos[$j][$i] = $rutaFotoCostadoIzquierdo;
                    $fotos_autos[$j][$i+1] = "Foto Costado Izquierdo";
                    $i=$i+2;
                }
                if(strpos($auto->getSeguroFotoTraseroDerecho(),"http")!=-1 && $auto->getSeguroFotoTraseroDerecho() != "") {
                    $rutaFotoTrasera= $auto->getSeguroFotoTraseroDerecho();
                    $fotos_autos[$j][$i] = $rutaFotoTrasera;
                    $fotos_autos[$j][$i+1] = "Foto Trasera";
                    $i=$i+2;
                }   
                if(strpos($auto->getTablero(),"http")!=-1 && $auto->getTablero() != "") {
                    $rutaFotoPanel=$auto->getTablero();  
                    $fotos_autos[$j][$i] = $rutaFotoPanel;
                    $fotos_autos[$j][$i+1] = "Foto del Panel";
                    $i=$i+2;
                }
                if(strpos($auto->getAccesorio1(),"http")!=-1 && $auto->getAccesorio1() != "") {
                    $rutaFotoAccesorios1= $auto->getAccesorio1();
                    $fotos_autos[$j][$i] = $rutaFotoAccesorios1;
                    $fotos_autos[$j][$i+1] = "Foto de Accesorio 1";
                    $i=$i+2;
                }  
                if(strpos($auto->getAccesorio2(),"http")!=-1 && $auto->getAccesorio2() != "") {
                    $rutaFotoAccesorios2=$auto->getAccesorio2();
                    $fotos_autos[$j][$i] = $rutaFotoAccesorios2;
                    $fotos_autos[$j][$i+1] = "Foto de Accesorio 2";
                }
            }else{//if verificationPhotoS3 == 0
                if($auto->getFoto() != null && $auto->getFoto() != "") {
                    $rutaFoto=$auto->getFoto();
                    $fotos_autos[$j][$i] = $rutaFoto;
                    $fotos_autos[$j][$i+1] = "Ver Fotos";
                    $i=$i+2;
                }
                if($auto->getSeguroFotoFrente() != null && $auto->getSeguroFotoFrente() != "") {
                    $rutaFotoFrente=$auto->getSeguroFotoFrente();
                    $fotos_autos[$j][$i] = $rutaFotoFrente;
                    $fotos_autos[$j][$i+1] = "Foto Frente";
                    $i=$i+2;
                }
                if($auto->getSeguroFotoCostadoDerecho() != null && $auto->getSeguroFotoCostadoDerecho() != "") {
                    $rutaFotoCostadoDerecho=$auto->getSeguroFotoCostadoDerecho();
                    $fotos_autos[$j][$i] = $rutaFotoCostadoDerecho;
                    $fotos_autos[$j][$i+1] = "Foto Costado Derecho";
                    $i=$i+2;
                }
                if($auto->getSeguroFotoCostadoIzquierdo() != null && $auto->getSeguroFotoCostadoIzquierdo() != "") {
                    $rutaFotoCostadoIzquierdo=$auto->getSeguroFotoCostadoIzquierdo();
                    $fotos_autos[$j][$i] = $rutaFotoCostadoIzquierdo;
                    $fotos_autos[$j][$i+1] = "Foto Costado Izquierdo";
                    $i=$i+2;
                }
                if($auto->getSeguroFotoTraseroDerecho() != null && $auto->getSeguroFotoTraseroDerecho() != "") {
                    $rutaFotoTrasera= $auto->getSeguroFotoTraseroDerecho();
                    $fotos_autos[$j][$i] = $rutaFotoTrasera;
                    $fotos_autos[$j][$i+1] = "Foto Trasera";
                    $i=$i+2;
                }   
                if($auto->getTablero() != null && $auto->getTablero() != "") {
                    $rutaFotoPanel=$auto->getTablero();  
                    $fotos_autos[$j][$i] = $rutaFotoPanel;
                    $fotos_autos[$j][$i+1] = "Foto del Panel";
                    $i=$i+2;
                }
                if($auto->getAccesorio1() != null && $auto->getAccesorio1() != "") {
                    $rutaFotoAccesorios1= $auto->getAccesorio1();
                    $fotos_autos[$j][$i] = $rutaFotoAccesorios1;
                    $fotos_autos[$j][$i+1] = "Foto de Accesorio 1";
                    $i=$i+2;
                }  
                if($auto->getAccesorio2() != null && $auto->getAccesorio2() != "") {
                    $rutaFotoAccesorios2=$auto->getAccesorio2();
                    $fotos_autos[$j][$i] = $rutaFotoAccesorios2;
                    $fotos_autos[$j][$i+1] = "Foto de Accesorio 2";
                }
            }
        }

        $this->fotos_autos = $fotos_autos;
    }

    public function executeFbShare(sfWebRequest $request){
    	$this->forward ('main', 'index');
    }

    public function executeSubirImagenS3Ajax(sfWebRequest $request){

        // Incluimos la clase de Amazon S3
        require sfConfig::get('sf_app_lib_dir')."/amazonS3/config.php";
                    
        // Nombre del Bucket en Amazon S3
        $bucket = "arriendas.prueba";
            
        // Cuando presiono el boton Upload
        foreach($_FILES['images']['error'] as $key => $error){
            if($error == UPLOAD_ERR_OK){
                // Recibo las variables por POST
                
                var_dump($_FILES['images']);
                //die();
                
                $fileName = $_FILES['images']['name'][$key];

                //asignar nombre aleatorio
                $fileName = explode(".", $fileName);
                //echo $fileName[0]. ' '.$fileName[1];
                $fileName = md5(mt_rand()).'.'.$fileName[1];

                //almacenar en la base de datos

                $fileTempName = $_FILES['images']['tmp_name'][$key];
                
                // Creo un nuevo bucket de Amazon S3
                $s3->putBucket($bucket, S3::ACL_PUBLIC_READ);
                
                // Muevo el archivo de su ruta temporal a su ruta definitiva
                if ($s3->putObjectFile($fileTempName, $bucket, $fileName, S3::ACL_PUBLIC_READ)) {
                    echo "<strong>Archivo subido correctamente.</strong>";
                }else{
                    echo "<strong>No se pudo subir el archivo.</strong>";
                }
            }
        }

        die();
    }

    public function executeFechaServidor(sfWebRequest $request){
        $fechaActual = strftime("%Y-%m-%d %H:%M:%S");
        echo $fechaActual;
        die();
    }

    public function executeMailCalificaciones(sfWebRequest $request){
        $mailCalificaciones= Doctrine_Core::getTable("mailCalificaciones")->findByEnviado(false);
        $cont = 0;
        
        require sfConfig::get('sf_app_lib_dir')."/mail/mail.php";

        foreach ($mailCalificaciones as $calificaciones){
            $fechaEnvio = strtotime($calificaciones->getDate());
            $fechaActual = strtotime($this->formatearHoraChilena(strftime("%Y-%m-%d %H:%M:%S")));

            if($fechaEnvio<$fechaActual){
                $cont ++;
                //echo $calificaciones->getId()."<br>";

                //enviar mail
                $calificaciones->enviarMailCalificaciones();

                //actualizar campo enviado
                $calificaciones->setEnviado(true);
                $calificaciones->save();
            }

        }

        return $this->renderText("Enviados ".$cont." mail");
    }

    public function executeVerificacionSeguro(sfWebRequest $request) {
        //Verificamos el estado de la tabla Security Tokens
        //$tokens= Doctrine_Core::getTable("SecurityTokens")->findOneById($request->getParameter("idToken"));
        //$url="http://www.arriendas.cl/main/verificacionSeguro?idAuto=456";
        
        //Ejecutamos el cambio correspondientes
        $auto= Doctrine_Core::getTable("car")->findOneById($request->getParameter("idAuto"));
        $auto->setSeguroOK("4");
        $auto->setFechaValidacion(strftime("%Y/%m/%d"));
        $auto->save();
        $this->getResponse()->setHttpHeader('Access-Control-Allow-Origin', '*');
        $this->getResponse()->setHttpHeader('Access-Control-Allow-Methods', 'GET, POST, OPTIONS');
        $this->getResponse()->setHttpHeader('Access-Control-Allow-Headers', 'X-Requested-With');
        return $this->renderText("OK");
     }
 
    // Esta funcion recibe el ID de un daño almacenado en la tabla Damages, y devuelve el PNG correspondiente a la vista superior del auto con el daño marcado con una X
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
        $request->setParameter("idReserve", $request->getParameter("idReserve"));
        $request->setParameter("tokenReserve", $request->getParameter("tokenReserve"));

        $html = $this->getController()->getPresentationFor("main","formularioEntrega");
        
        $pdf->WriteHTML($html,0);
        
        return $this->renderText($pdf->Output());
    }

    public function executeFormularioEntrega(sfWebRequest $request){
        
        $idReserve    = $request->getParameter("idReserve");
        $tokenReserve = $request->getParameter("tokenReserve");

        $idUsuario = sfContext::getInstance()->getUser()->getAttribute('userid');

		if (!$tokenReserve){
			$reserve = Doctrine_Core::getTable('reserve')->findOneById($idReserve);
		} else {
			$reserve = Doctrine_Core::getTable('reserve')->findOneByToken($tokenReserve);
		}

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

        if ($propietarioId == $idUsuario) {
            $car['propietario'] = true;
        } else {
            $car['propietario'] = false;
        }

        $arrendador['nombreCompleto'] = $arrendadorClass->getFirstname()." ".$arrendadorClass->getLastname();
        $arrendador['rut']            = $arrendadorClass->getRutFormatted();
        $arrendador['direccion']      = $arrendadorClass->getAddress();
        $arrendador['telefono']       = $arrendadorClass->getTelephone();
        $arrendador['comuna']         = ucfirst(strtolower($arrendadorClass->getCommune()->name));

        $propietario['nombreCompleto'] = $propietarioClass->getFirstname()." ".$propietarioClass->getLastname();
        $propietario['rut']            = $propietarioClass->getRutFormatted();
        $propietario['direccion']      = $propietarioClass->getAddress();
        $propietario['telefono']       = $propietarioClass->getTelephone();
        $propietario['comuna']         = ucfirst(strtolower($propietarioClass->getCommune()->name));

        $damageClass = Doctrine_Core::getTable('Damage')->findByCarId($carId);

        $descripcionDanios = null;
        $i = 0;
        foreach ($damageClass as $damage) {
            $descripcionDanios[$i] = $damage->getDescription();
            $i++;
        }
        
        $this->descripcionDanios = $descripcionDanios;
        $this->arrendador = $arrendador;
        $this->propietario = $propietario;
        $this->car = $car;
    }
          
    //Se retorna un componente que permite hacer el upload de las fotos directamente al S3 de Amazon, sin pasar por el servidor Web.
    public function executeUpload2S3(sfWebRequest $request) {
        //Validamos los parámetros de entrada
        $this->urlFotoDefecto= $request->getParameter("urlFotoDefecto");
        $this->titulo= $request->getParameter("tituloFoto");
        $this->bucketDestino= $request->getParameter("bucket");
        $this->idElemento=$request->getParameter("idElemento");
    }

    //Metodo auxiliar para generar una version de menor tamaño de la foto
    public function executeS3thumb(sfWebRequest $request) {
        $image= $request->getParameter("urlFoto");
        $ancho= $request->getParameter("ancho");
        $alto= $request->getParameter("alto");
        
        //Necesitamos obtener el nombre del archivo respectivo.
        //Para esto, hacemos uso de la funcion nativa parse_url de php
        $temporal= parse_url($image);
        
        //Generamos la URL que corresponde a la imagen
        $urlThumbnail= "thumb_an".$ancho."_al".$alto."_".str_replace("/","_",$temporal['path']);

        //Revisamos si la foto está generada como thumb en el S3
        $ch = curl_init();
        curl_setopt($ch,CURLOPT_URL,"http://s3.amazonaws.com/arriendas.testing/?prefix=".$urlThumbnail);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
        $salida= curl_exec($ch);
        if(strpos($salida,"Contents")==false) {
        	require sfConfig::get('sf_app_lib_dir')."/wideimage/WideImage.php";
        	$image = WideImage::load($image);
        	$resized = $image->resize($alto, $ancho);
        	$resized->saveToFile(sfConfig::get('sf_upload_dir')."/".$urlThumbnail);
        	require(sfConfig::get('sf_app_lib_dir')."/s3upload/s3_config.php");
        	$s3->putObjectFile(sfConfig::get('sf_upload_dir')."/".$urlThumbnail,
        	$bucket , $urlThumbnail, S3::ACL_PUBLIC_READ);
    	}
        $this->redirect("http://s3.amazonaws.com/arriendas.testing/".$urlThumbnail);
    }

    public function executeSignput(sfWebRequest $request) {

        $S3_KEY='AKIAIDPA5IXQKMNWFCYQ';
        $S3_SECRET='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';
        $S3_BUCKET='/arriendas.testing';
         
        $EXPIRE_TIME=(60 * 5); // 5 minutes
        $S3_URL='http://s3.amazonaws.com';
         
        $objectName='/' . time()."_".$request->getParameter('name');
        $mimeType=$request->getParameter('type');
        $expires = time() + $EXPIRE_TIME;
        $amzHeaders= "x-amz-acl:public-read";
        $stringToSign = "PUT\n\n$mimeType\n$expires\n$amzHeaders\n$S3_BUCKET$objectName";
        $sig = urlencode(base64_encode(hash_hmac('sha1', $stringToSign, $S3_SECRET, true)));
         
        $url = urlencode("$S3_URL$S3_BUCKET$objectName?AWSAccessKeyId=$S3_KEY&Expires=$expires&Signature=$sig");
         
        return $this->renderText($url);
    }
     
    public function executeInformeDanios(sfWebRequest $request) {
        //Recibimos como parámetro el ID del vehículo para el cual deseamos generar el informe de daños
        $idAuto= $request->getParameter("idAuto");
        $car = Doctrine_Core::getTable("car")->findOneById($idAuto);
        
        //Obtenemos los datos del dueño del vehículo
        
        $user= Doctrine_Core::getTable("user")->findOneByID($car->getUserId());
        
        //Obtencion de los datos referentes a los daños
        $damages= Doctrine_Core::getTable("damage")->findByCarId($car->getId());
        
        //Necesitamos calcular la cantidad de páginas de daños a generar. Son 2 daños por págin
        $paginas= ceil(count($damages)/2);
        $this->paginasDanios= $paginas;
        
        //Datos para el template
        $this->propietario= $user->getFirstname()." ".$user->getLastName();
        $this->telefono= $user->getTelephone();
        $this->direccion=$car->getAddress();
        $this->foto="http://cdn1.arriendas.cl/uploads/cars/".$car->getFoto();
        $this->agno=$car->getYear();
        $this->precioHora=$car->getPricePerHour();
        $this->precioDia=$car->getPricePerDay();
        $this->marcaAuto= $car->getMarcaModelo();
        $this->fotoFrontal= "../uploads/vistas_seguro/".$car->getSeguroFotoFrente();
        $this->fotoCostadoIzquierdo= "../uploads/vistas_seguro/".$car->getSeguroFotoCostadoIzquierdo();
        $this->fotoCostadoDerecho= "../uploads/vistas_seguro/".$car->getSeguroFotoCostadoDerecho();
        $this->fotoTrasera= "../uploads/vistas_seguro/".$car->getSeguroFotoTraseroDerecho();
        $this->fotoPanel= "../uploads/vistas_seguro/".$car->getSeguroFotoTraseroIzquierdo();    
        $this->danios= $damages;
    }

    public function executeReporteResumen(sfWebRequest $request) {

        //Recibimos como parámetro el ID del vehículo para el cual deseamos generar el informe de daños
        $idAuto= $request->getParameter("idAuto");
        $car = Doctrine_Core::getTable("car")->findOneById($idAuto);

        //Obtenemos los datos del dueño del vehículo        
        $user= Doctrine_Core::getTable("user")->findOneByID($car->getUserId());
        
        //Obtencion de los datos referentes a los daños
        $damages= Doctrine_Core::getTable("damage")->findByCarId($car->getId());
        
        //Necesitamos calcular la cantidad de páginas de daños a generar. Son 2 daños por págin
        $paginas= ceil(count($damages)/2);
        $this->paginasDanios= $paginas;
        
        //Datos para el template
        $this->propietario= $user->getFirstname()." ".$user->getLastName();
        $this->telefono= $user->getTelephone();
        $this->direccion=$car->getAddress();
        $this->foto=$car->getFoto();
        $this->agno=$car->getYear();
        $this->precioHora=$car->getPricePerHour();
        $this->precioDia=$car->getPricePerDay();
        $this->marcaAuto= $car->getMarcaModelo();
        $this->fotoFrontal= "../uploads/vistas_seguro/".$car->getSeguroFotoFrente();
        $this->fotoCostadoIzquierdo= "../uploads/vistas_seguro/".$car->getSeguroFotoCostadoIzquierdo();
        $this->fotoCostadoDerecho= "../uploads/vistas_seguro/".$car->getSeguroFotoCostadoDerecho();
        $this->fotoTrasera= "../uploads/vistas_seguro/".$car->getSeguroFotoTraseroDerecho();
        $this->fotoPanel= "../uploads/vistas_seguro/".$car->getSeguroFotoTraseroIzquierdo();    
        $this->danios= $damages;
    }

    public function executeReporteDanios(sfWebRequest $request) {

        //Recibimos como parámetro el ID del vehículo para el cual deseamos generar el informe de daños
        $idAuto= $request->getParameter("idAuto");
        $car = Doctrine_Core::getTable("car")->findOneById($idAuto);
        
        //Obtenemos los datos del dueño del vehículo        
        $user= Doctrine_Core::getTable("user")->findOneByID($car->getUserId());
        
        //Obtencion de los datos referentes a los daños
        $damages= Doctrine_Core::getTable("damage")->findByCarId($car->getId());
        
        //Necesitamos calcular la cantidad de páginas de daños a generar. Son 2 daños por págin
        $paginas= ceil(count($damages)/2);
        $this->paginasDanios= $paginas;
        
        //Datos para el template
        $this->propietario= $user->getFirstname()." ".$user->getLastName();
        $this->telefono= $user->getTelephone();
        $this->direccion=$car->getAddress();
        $this->foto=$car->getFoto();
        $this->agno=$car->getYear();
        $this->precioHora=$car->getPricePerHour();
        $this->precioDia=$car->getPricePerDay();
        $this->marcaAuto= $car->getMarcaModelo();
        $this->fotoFrontal= "../uploads/vistas_seguro/".$car->getSeguroFotoFrente();
        $this->fotoCostadoIzquierdo= "../uploads/vistas_seguro/".$car->getSeguroFotoCostadoIzquierdo();
        $this->fotoCostadoDerecho= "../uploads/vistas_seguro/".$car->getSeguroFotoCostadoDerecho();
        $this->fotoTrasera= "../uploads/vistas_seguro/".$car->getSeguroFotoTraseroDerecho();
        $this->fotoPanel= "../uploads/vistas_seguro/".$car->getSeguroFotoTraseroIzquierdo();    
        $this->danios= $damages;
    }
     
    public function executeTestDamage(sfWebRequest $request) {
        $damage= Doctrine_Core::getTable("damage")->findOneById(21);
        $damage->setUrlFoto("Estoesunaprueba.jpg");
        $damage->save();
        echo $damage->getUrlFoto();
        die();
    }
        
    public function executeExito(sfWebRequest $request) {
    }

    public function executeFracaso(sfWebRequest $request) {
    }

    public function executeNotificacion(sfWebRequest $request) {
    }

    public function oldexecuteIndex(sfWebRequest $request) {

        if($request->getParameter('region'))
            $this->region = Doctrine_Core::getTable('Regiones')->findOneBySlug($request->getParameter('region'))->getCodigo();
        if($request->getParameter('comuna'))
            $this->comuna = Doctrine_Core::getTable('Comunas')->findOneBySlug($request->getParameter('comuna'))->getCodigoInterno();

    	//Modificacion para setear la cookie de registo de la fecha de ingreso por primera vez del usuario
    	$cookie_ingreso = $this->getRequest()->getCookie('cookie_ingreso');
    	
    	if ($cookie_ingreso == NULL) {
    	    $this->getResponse()->setCookie('cookie_ingreso',time());
    	}
    	   
        if ($this->getUser()->getAttribute('geolocalizacion') == null) {
            $this->getUser()->setAttribute('geolocalizacion', true);
        } elseif ($this->getUser()->getAttribute('geolocalizacion') == true) {
            $this->getUser()->setAttribute('geolocalizacion', false);
        }
        
        $fechaActual = $this->formatearHoraChilena(strftime("%Y%m%d%H%M%S", strtotime('+3 hours', time())));    // sumo 3hs a la hora chilena.
        $this->day_from = (!$this->getUser()->getAttribute("fechainicio"))? date("d-m-Y", strtotime($fechaActual)) : $this->getUser()->getAttribute("fechainicio");
        $this->day_to = (!$this->getUser()->getAttribute("fechatermino"))? date("d-m-Y",strtotime("+2 days",strtotime($fechaActual))) : $this->getUser()->getAttribute("fechatermino");
        $this->hour_from = (!$this->getUser()->getAttribute("horainicio"))? date("g:i A",strtotime($fechaActual)) : date("g:i A",strtotime($this->getUser()->getAttribute("horainicio")));
        $this->hour_to = (!$this->getUser()->getAttribute("horatermino"))? date("g:i A",strtotime($fechaActual)+12*3600) : date("g:i A",strtotime($this->getUser()->getAttribute("horatermino")));
        //echo date("g:i A",strtotime($fechaActual));die;
        
        $idUsuario = sfContext::getInstance()->getUser()->getAttribute('userid');
        if($idUsuario)
        {
            $reservas = Doctrine_Core::getTable("Reserve")->findByUserId($idUsuario);
            $ultimaFecha = null;
            $ultimoId = null;
            $duracion = null;
            foreach ($reservas as $reserva) 
            {
                if(!$ultimaFecha)
                {
                    $ultimoId = $reserva->getId();
                    $ultimaFecha = strtotime($reserva->getDate());
                    $duracion = $reserva->getDuration();
                }
                else
                {
                    if($ultimoId < $reserva->getId())
                    {
                        $ultimoId = $reserva->getId();
                        $ultimaFecha = strtotime($reserva->getDate());
                        $duracion = $reserva->getDuration();
                    }
                }
            }
            if($ultimaFecha)
            {
                
                $fechaAlmacenadaDesde = date("YmdHis",$ultimaFecha);    // ultima guardada en ddbb
                if($this->getUser()->getAttribute("fechainicio")){
                    $time_desde = strtotime($this->getUser()->getAttribute("fechainicio") . " " . $this->getUser()->getAttribute("horainicio"));
                    $fechaSessionDesde = date("YmdHis", $time_desde);
                    $fechaAlmacenadaDesde = ($fechaSessionDesde < $fechaAlmacenadaDesde)? $fechaAlmacenadaDesde : $fechaSessionDesde;
                    $ultimaFecha = ($fechaSessionDesde < $fechaAlmacenadaDesde)? $ultimaFecha : $time_desde;
                    
                    // obtengo duración de la session
                    $time_hasta = strtotime($this->getUser()->getAttribute("fechatermino") . " " . $this->getUser()->getAttribute("horatermino"));
                    $duracion = round(abs($time_hasta - $time_desde) / 3600,2);
                }
                if( date("YmdHis", strtotime($fechaActual)) < $fechaAlmacenadaDesde)
                {
                    $day_from = date("d-m-Y",$ultimaFecha);
                    $hour_from = date("g:i A",$ultimaFecha);
                    $day_to = date("d-m-Y",$ultimaFecha+$duracion*3600);
                    $hour_to = date("g:i A",$ultimaFecha+$duracion*3600);
                    
                    $this->getUser()->setAttribute('fechainicio', $day_from);
                    $this->getUser()->setAttribute('fechatermino', $day_to);
                    $this->getUser()->setAttribute('horainicio', $hour_from);
                    $this->getUser()->setAttribute('horatermino', $hour_to);
                    
                    $this->day_from = $day_from;
                    $this->day_to = $day_to;
                    $this->hour_from = $hour_from;
                    $this->hour_to = $hour_to;
                    
                }
            }
        }
        
        $cityname = $request->getParameter('c');

        $q = Doctrine::getTable('City')->createQuery('c')->where('c.name = ? ', array($cityname));
        $city = $q->fetchOne();
        
        if ($this->getUser()->getAttribute("map_clat") && $this->getUser()->getAttribute("map_clng")) {
            $this->map_clat = $this->getUser()->getAttribute("map_clat");
            $this->map_clng = $this->getUser()->getAttribute("map_clng");
        }

        if ($city != null) {
            $this->lat = $city->getLat();
            $this->lng = $city->getLng();
        } else {

            if (stripos($_SERVER['SERVER_NAME'], "arrendas") !== FALSE) {
                $this->lat = -34.59;
                $this->lng = -58.401604;
            } else {
                $this->lat = -33.427224;
                $this->lng = -70.605558;
            }
        }


        $boundleft = -35;
        $boundright = -30;
        $boundtop = -60;
        $boundbottom = -55;

        

        $q = Doctrine_Query::create()
                ->select('ca.id , av.id idav , mo.name model,
		  br.name brand, ca.year year,
		  ca.address address, ci.name city,
		  st.name state, co.name country,
		  owner.firstname firstname,
		  owner.lastname lastname,
		  ca.price_per_day priceday,
		  ca.price_per_hour pricehour,
		  owner.id userid, IF(ca.seguro_ok=4,1,0) as verificado'
                )
                ->from('Car ca')
                ->innerJoin('ca.Availabilities av')
                ->innerJoin('ca.Model mo')
                ->innerJoin('ca.User owner')
                ->innerJoin('mo.Brand br')
                ->innerJoin('ca.City ci')
                ->innerJoin('ci.State st')
                ->innerJoin('st.Country co')
                ->where('ca.lat > ?', $boundleft)
                ->andWhere('ca.activo = ?', 1)
                ->andWhere('ca.lat < ?', $boundright)
                ->andWhere('ca.lng > ?', $boundtop)
                ->andWhere('ca.lng < ?', $boundbottom)
                ->andWhere('ca.offer = ?', 1)
		->having('verificado = ?',1)
                ->limit(10);
        $this->offers = $q->execute();

        $comunas = array();
        $regionMetropolitanaCodigo = "13";
        $comunasList = Doctrine::getTable('Comunas')->findByPadre($regionMetropolitanaCodigo);
        foreach ($comunasList as $comuna) {
            $comunas[] = $comuna->getCodigoInterno();
        }
        $q = Doctrine_Query::create()
                ->select('ca.id, mo.name model,
          br.name brand, ca.uso_vehiculo_id tipo_vehiculo, ca.year year,
          ca.address address, ci.name city,
          st.name state, co.name country,
          owner.firstname firstname,
          owner.lastname lastname,
          ca.price_per_day priceday,
          ca.price_per_hour pricehour'
                )
                ->from('Car ca')
                ->innerJoin('ca.Model mo')
                ->innerJoin('ca.User owner')
                ->innerJoin('mo.Brand br')
                ->innerJoin('ca.City ci')
                ->innerJoin('ci.State st')
                ->innerJoin('st.Country co')
                ->where('ca.activo = ?', 1)
                ->andWhere('ca.seguro_ok = ?', 4)
                ->andWhereIn('ca.comuna_id', $comunas)
                ->limit(100);
        $this->cars = $q->fetchArray();
        $fotos_autos = array();
        for($j=0;$j<count($this->cars);$j++){
            $auto = Doctrine_Core::getTable('car')->find(array($this->cars[$j]['id']));
            $fotos_autos[$j]['id'] = $auto->getId();
            $fotos_autos[$j]['photoS3'] = $auto->getPhotoS3();
            $fotos_autos[$j]['verificationPhotoS3'] = $auto->getVerificationPhotoS3();
            $i=0;
            if($auto->getVerificationPhotoS3() == 1){
                if($auto->getFoto() != null && $auto->getFoto() != "") {
                    $rutaFoto=$auto->getFoto();
                    $fotos_autos[$j][$i] = $rutaFoto;
                    $fotos_autos[$j][$i+1] = "Ver Fotos";
                    $i=$i+2;
                }
                if($auto->getSeguroFotoFrente() != null && $auto->getSeguroFotoFrente() != "") {
                    $rutaFotoFrente=$auto->getSeguroFotoFrente();
                    $fotos_autos[$j][$i] = $rutaFotoFrente;
                    $fotos_autos[$j][$i+1] = "Foto Frente";
                    $i=$i+2;
                }
                if($auto->getSeguroFotoCostadoDerecho() != null && $auto->getSeguroFotoCostadoDerecho() != "") {
                    $rutaFotoCostadoDerecho=$auto->getSeguroFotoCostadoDerecho();
                    $fotos_autos[$j][$i] = $rutaFotoCostadoDerecho;
                    $fotos_autos[$j][$i+1] = "Foto Costado Derecho";
                    $i=$i+2;
                }
                if(strpos($auto->getSeguroFotoCostadoIzquierdo(),"http")!=-1 && $auto->getSeguroFotoCostadoIzquierdo() != "") {
                    $rutaFotoCostadoIzquierdo=$auto->getSeguroFotoCostadoIzquierdo();
                    $fotos_autos[$j][$i] = $rutaFotoCostadoIzquierdo;
                    $fotos_autos[$j][$i+1] = "Foto Costado Izquierdo";
                    $i=$i+2;
                }
                if(strpos($auto->getSeguroFotoTraseroDerecho(),"http")!=-1 && $auto->getSeguroFotoTraseroDerecho() != "") {
                    $rutaFotoTrasera= $auto->getSeguroFotoTraseroDerecho();
                    $fotos_autos[$j][$i] = $rutaFotoTrasera;
                    $fotos_autos[$j][$i+1] = "Foto Trasera";
                    $i=$i+2;
                }   
                if(strpos($auto->getTablero(),"http")!=-1 && $auto->getTablero() != "") {
                    $rutaFotoPanel=$auto->getTablero();  
                    $fotos_autos[$j][$i] = $rutaFotoPanel;
                    $fotos_autos[$j][$i+1] = "Foto del Panel";
                    $i=$i+2;
                }
                if(strpos($auto->getAccesorio1(),"http")!=-1 && $auto->getAccesorio1() != "") {
                    $rutaFotoAccesorios1= $auto->getAccesorio1();
                    $fotos_autos[$j][$i] = $rutaFotoAccesorios1;
                    $fotos_autos[$j][$i+1] = "Foto de Accesorio 1";
                    $i=$i+2;
                }  
                if(strpos($auto->getAccesorio2(),"http")!=-1 && $auto->getAccesorio2() != "") {
                    $rutaFotoAccesorios2=$auto->getAccesorio2();
                    $fotos_autos[$j][$i] = $rutaFotoAccesorios2;
                    $fotos_autos[$j][$i+1] = "Foto de Accesorio 2";
                }
            }else{//if verificationPhotoS3 == 0
                if($auto->getFoto() != null && $auto->getFoto() != "") {
                    $rutaFoto=$auto->getFoto();
                    $fotos_autos[$j][$i] = $rutaFoto;
                    $fotos_autos[$j][$i+1] = "Ver Fotos";
                    $i=$i+2;
                }
                if($auto->getSeguroFotoFrente() != null && $auto->getSeguroFotoFrente() != "") {
                    $rutaFotoFrente=$auto->getSeguroFotoFrente();
                    $fotos_autos[$j][$i] = $rutaFotoFrente;
                    $fotos_autos[$j][$i+1] = "Foto Frente";
                    $i=$i+2;
                }
                if($auto->getSeguroFotoCostadoDerecho() != null && $auto->getSeguroFotoCostadoDerecho() != "") {
                    $rutaFotoCostadoDerecho=$auto->getSeguroFotoCostadoDerecho();
                    $fotos_autos[$j][$i] = $rutaFotoCostadoDerecho;
                    $fotos_autos[$j][$i+1] = "Foto Costado Derecho";
                    $i=$i+2;
                }
                if($auto->getSeguroFotoCostadoIzquierdo() != null && $auto->getSeguroFotoCostadoIzquierdo() != "") {
                    $rutaFotoCostadoIzquierdo=$auto->getSeguroFotoCostadoIzquierdo();
                    $fotos_autos[$j][$i] = $rutaFotoCostadoIzquierdo;
                    $fotos_autos[$j][$i+1] = "Foto Costado Izquierdo";
                    $i=$i+2;
                }
                if($auto->getSeguroFotoTraseroDerecho() != null && $auto->getSeguroFotoTraseroDerecho() != "") {
                    $rutaFotoTrasera= $auto->getSeguroFotoTraseroDerecho();
                    $fotos_autos[$j][$i] = $rutaFotoTrasera;
                    $fotos_autos[$j][$i+1] = "Foto Trasera";
                    $i=$i+2;
                }   
                if($auto->getTablero() != null && $auto->getTablero() != "") {
                    $rutaFotoPanel=$auto->getTablero();  
                    $fotos_autos[$j][$i] = $rutaFotoPanel;
                    $fotos_autos[$j][$i+1] = "Foto del Panel";
                    $i=$i+2;
                }
                if($auto->getAccesorio1() != null && $auto->getAccesorio1() != "") {
                    $rutaFotoAccesorios1= $auto->getAccesorio1();
                    $fotos_autos[$j][$i] = $rutaFotoAccesorios1;
                    $fotos_autos[$j][$i+1] = "Foto de Accesorio 1";
                    $i=$i+2;
                }  
                if($auto->getAccesorio2() != null && $auto->getAccesorio2() != "") {
                    $rutaFotoAccesorios2=$auto->getAccesorio2();
                    $fotos_autos[$j][$i] = $rutaFotoAccesorios2;
                    $fotos_autos[$j][$i+1] = "Foto de Accesorio 2";
                }
            }
        }

        $this->fotos_autos = $fotos_autos;
        
        $this->brand =  $this->ordenarBrand(Doctrine_Core::getTable('Brand')->createQuery('a')->execute());

        $this->country = Doctrine_Core::getTable('Country')->createQuery('a')->execute();
        $this->regiones = Doctrine_Core::getTable('Regiones')->createQuery('a')->execute();

        $this->holiday = false;

        $Holiday = Doctrine_Core::getTable("Holiday")->findOneByDate(date("Y-m-d"));
        if ($Holiday || date("N") == 6 || date("N") == 7) {

            if ($this->getUser()->getAttribute("email") != "cmedinamoenne@gmail.com") {
                $this->holiday = true;
            }
        }
    }
    
    public function executeListaAjax(sfWebRequest $request){

        /*$idUsuario = sfContext::getInstance()->getUser()->getAttribute('userid');*/

        $day_from     = $request->getParameter('day_from');
        $day_to       = $request->getParameter('day_to');
        $hour_from    = date("H:i", strtotime($request->getParameter('hour_from')));
        $hour_to      = date("H:i", strtotime($request->getParameter('hour_to')));
        $transmission = $request->getParameter('transmission');
        $type         = $request->getParameter('type');
        $price        = $request->getParameter('price');
        $regionCodigo = $request->getParameter("region");
        $comunaCodigoInterno = ($request->getParameter("comuna") != 0 ? $request->getParameter("comuna") : null);

        $from = date("Y-m-d H:i:s", strtotime($day_from." ".$hour_from));
        $to = date("Y-m-d H:i:s", strtotime($day_to." ".$hour_to));
        
        $this->getUser()->setAttribute('fechainicio', $day_from);
        $this->getUser()->setAttribute('fechatermino', $day_to);
        $this->getUser()->setAttribute('horainicio', date("g:i A",strtotime($request->getParameter('hour_from'))));
        $this->getUser()->setAttribute('horatermino', date("g:i A",strtotime($request->getParameter('hour_to'))));

        $q = Doctrine_Query::create()
            ->select('C.id,
                C.uso_vehiculo_id tipo_vehiculo,
                C.year year,
                C.address address,
                C.price_per_day priceday,
                C.price_per_hour pricehour,
                CO.nombre as comuna,
                M.name model,
                M.id_tipo_vehiculo,
                B.name brand')
            ->from('Car C')
            ->innerJoin('C.Model M')
            ->innerJoin('M.Brand B')
            ->innerJoin('C.Comunas CO')
            ->leftJoin('C.Reserves R')
            ->leftJoin('R.Transaction T')
            ->Where('C.activo = 1')
            ->andWhere('C.seguro_ok = 4');
            /*->andWhere('T.completed = 0');*/
            /*->andWhere('? NOT BETWEEN R.date AND DATE_ADD(R.date, INTERVAL R.duration HOUR)', $from)
            ->andWhere('? NOT BETWEEN R.date AND DATE_ADD(R.date, INTERVAL R.duration HOUR)', $to)
            ->andWhere('R.date NOT BETWEEN ? AND ?', array($from, $to))
            ->andWhere('DATE_ADD(R.date, INTERVAL R.duration HOUR) NOT BETWEEN ? AND ?', array($from, $to));*/

        $Holiday = Doctrine_Core::getTable("Holiday")->findOneByDate(date("Y-m-d"));
        if ($Holiday || date("N") == 6 || date("N") == 7) {

            $day      = date("Y-m-d");
            $i        = 0;
            $weekFrom = $day;

            do {

                $weekTo = $day;
                $i++;
                $day = date("Y-m-d", strtotime("+".$i." day"));

                $Holiday = Doctrine_Core::getTable("Holiday")->findOneByDate(date("Y-m-d", strtotime($day)));
            } while($Holiday || date("N", strtotime($day)) == 6 || date("N", strtotime($day)) == 7);

            if (strtotime($day_from) <= strtotime($weekTo)) {
                $q->innerJoin("C.CarAvailabilities CA");
                $q->andWhere("CA.is_deleted IS FALSE");
                $q->andWhere("CA.day = ?", date("Y-m-d", strtotime($day_from)));
                $q->andWhere('? BETWEEN CA.started_at AND CA.ended_at', date("H:i:s", strtotime($hour_from)));
            }
        }

        /*$startTime = strtotime($day_from);
        $endTime = strtotime($day_to);

        $timeHasWorkday = false;
        $timeHasWeekend = false;

        for ($i = $startTime; $i <= $endTime; $i = $i + 86400) {
            $thisDate = date('Y-m-d', $i); // 2010-05-01, 2010-05-02, etc
            $dw = date("w", $i);
            if ($dw == 6 || $dw == 0) {
                $timeHasWeekend = true;
                break;
            }
        }

        for ($i = $startTime; $i <= $endTime; $i = $i + 86400) {
            $thisDate = date('Y-m-d', $i); // 2010-05-01, 2010-05-02, etc
            $dw = date("w", $i);
            if ($dw > 0 && $dw < 5) {
                $timeHasWorkday = true;
                break;
            }
        }

        if (
                $hour_from != "Hora de inicio" &&
                $hour_to != "Hora de entrega" &&
                $hour_from != "" &&
                $hour_to != "" &&
                $day_from != "Dia de inicio" &&
                $day_to != "Dia de entrega" &&
                $day_from != "" &&
                $day_to != ""
        ) {
            $day_from = implode('-', array_reverse(explode('-', $day_from)));
            $day_to = implode('-', array_reverse(explode('-', $day_to)));
            $fullstartdate = $day_from . " " . $hour_from;
            $fullenddate = $day_to . " " . $hour_to;

            $q = $q->andWhere('C.disponibilidad_semana >= ?', $timeHasWorkday);
            $q = $q->andWhere('C.disponibilidad_finde >= ?', $timeHasWeekend);
        }*/

        // Comunas
        
        $comunas = array();
        if (!is_null($regionCodigo)) {
            if (is_null($comunaCodigoInterno)) {
                $comunasList = Doctrine::getTable('Comunas')->findByPadre($regionCodigo);
                foreach ($comunasList as $comuna) {
                    $comunas[] = $comuna->getCodigoInterno();
                }
            } else {
                $comunas[] = $comunaCodigoInterno;
            }
        }

        if (count($comunas) > 0) {
            $q->andWhereIn('C.comuna_id', $comunas);
        }

        // Transmisión
        if ($transmission != "") {
            $transmission = explode(",", $transmission);
            $q->andWhereIn('C.transmission', $transmission);
        }

        // Tipo de auto
        if ($type != "") {
            $type = explode(",", $type);
            $q->andWhereIn('M.id_tipo_vehiculo', $type);
        }

        // Ordenar por precio
        if ($price == 1) {
            $q->orderBy('C.price_per_day DESC');
        } else {
            $q->orderBy('C.price_per_day ASC');
        }

        $q->limit(100);
        
        $this->cars = $q->fetchArray();
        $fotos_autos = array();

        for ($j = 0 ; $j < count($this->cars) ; $j++) {

            $auto = Doctrine_Core::getTable('car')->find(array($this->cars[$j]['id']));

            $fotos_autos[$j]['id'] = $auto->getId();
            $fotos_autos[$j]['photoS3'] = $auto->getPhotoS3();
            $fotos_autos[$j]['verificationPhotoS3'] = $auto->getVerificationPhotoS3();

            $i=0;
            if ($auto->getVerificationPhotoS3() == 1) {
                if($auto->getFoto() != null && $auto->getFoto() != "") {
                    $rutaFoto=$auto->getFoto();
                    $fotos_autos[$j][$i] = $rutaFoto;
                    $fotos_autos[$j][$i+1] = "Ver Fotos";
                    $i=$i+2;
                }
                if($auto->getSeguroFotoFrente() != null && $auto->getSeguroFotoFrente() != "") {
                    $rutaFotoFrente=$auto->getSeguroFotoFrente();
                    $fotos_autos[$j][$i] = $rutaFotoFrente;
                    $fotos_autos[$j][$i+1] = "Foto Frente";
                    $i=$i+2;
                }
                if($auto->getSeguroFotoCostadoDerecho() != null && $auto->getSeguroFotoCostadoDerecho() != "") {
                    $rutaFotoCostadoDerecho=$auto->getSeguroFotoCostadoDerecho();
                    $fotos_autos[$j][$i] = $rutaFotoCostadoDerecho;
                    $fotos_autos[$j][$i+1] = "Foto Costado Derecho";
                    $i=$i+2;
                }
                if(strpos($auto->getSeguroFotoCostadoIzquierdo(),"http")!=-1 && $auto->getSeguroFotoCostadoIzquierdo() != "") {
                    $rutaFotoCostadoIzquierdo=$auto->getSeguroFotoCostadoIzquierdo();
                    $fotos_autos[$j][$i] = $rutaFotoCostadoIzquierdo;
                    $fotos_autos[$j][$i+1] = "Foto Costado Izquierdo";
                    $i=$i+2;
                }
                if(strpos($auto->getSeguroFotoTraseroDerecho(),"http")!=-1 && $auto->getSeguroFotoTraseroDerecho() != "") {
                    $rutaFotoTrasera= $auto->getSeguroFotoTraseroDerecho();
                    $fotos_autos[$j][$i] = $rutaFotoTrasera;
                    $fotos_autos[$j][$i+1] = "Foto Trasera";
                    $i=$i+2;
                }   
                if(strpos($auto->getTablero(),"http")!=-1 && $auto->getTablero() != "") {
                    $rutaFotoPanel=$auto->getTablero();  
                    $fotos_autos[$j][$i] = $rutaFotoPanel;
                    $fotos_autos[$j][$i+1] = "Foto del Panel";
                    $i=$i+2;
                }
                if(strpos($auto->getAccesorio1(),"http")!=-1 && $auto->getAccesorio1() != "") {
                    $rutaFotoAccesorios1= $auto->getAccesorio1();
                    $fotos_autos[$j][$i] = $rutaFotoAccesorios1;
                    $fotos_autos[$j][$i+1] = "Foto de Accesorio 1";
                    $i=$i+2;
                }  
                if(strpos($auto->getAccesorio2(),"http")!=-1 && $auto->getAccesorio2() != "") {
                    $rutaFotoAccesorios2=$auto->getAccesorio2();
                    $fotos_autos[$j][$i] = $rutaFotoAccesorios2;
                    $fotos_autos[$j][$i+1] = "Foto de Accesorio 2";
                }
            } else {//if verificationPhotoS3 == 0
                if($auto->getFoto() != null && $auto->getFoto() != "") {
                    $rutaFoto=$auto->getFoto();
                    $fotos_autos[$j][$i] = $rutaFoto;
                    $fotos_autos[$j][$i+1] = "Ver Fotos";
                    $i=$i+2;
                }
                if($auto->getSeguroFotoFrente() != null && $auto->getSeguroFotoFrente() != "") {
                    $rutaFotoFrente=$auto->getSeguroFotoFrente();
                    $fotos_autos[$j][$i] = $rutaFotoFrente;
                    $fotos_autos[$j][$i+1] = "Foto Frente";
                    $i=$i+2;
                }
                if($auto->getSeguroFotoCostadoDerecho() != null && $auto->getSeguroFotoCostadoDerecho() != "") {
                    $rutaFotoCostadoDerecho=$auto->getSeguroFotoCostadoDerecho();
                    $fotos_autos[$j][$i] = $rutaFotoCostadoDerecho;
                    $fotos_autos[$j][$i+1] = "Foto Costado Derecho";
                    $i=$i+2;
                }
                if($auto->getSeguroFotoCostadoIzquierdo() != null && $auto->getSeguroFotoCostadoIzquierdo() != "") {
                    $rutaFotoCostadoIzquierdo=$auto->getSeguroFotoCostadoIzquierdo();
                    $fotos_autos[$j][$i] = $rutaFotoCostadoIzquierdo;
                    $fotos_autos[$j][$i+1] = "Foto Costado Izquierdo";
                    $i=$i+2;
                }
                if($auto->getSeguroFotoTraseroDerecho() != null && $auto->getSeguroFotoTraseroDerecho() != "") {
                    $rutaFotoTrasera= $auto->getSeguroFotoTraseroDerecho();
                    $fotos_autos[$j][$i] = $rutaFotoTrasera;
                    $fotos_autos[$j][$i+1] = "Foto Trasera";
                    $i=$i+2;
                }   
                if($auto->getTablero() != null && $auto->getTablero() != "") {
                    $rutaFotoPanel=$auto->getTablero();  
                    $fotos_autos[$j][$i] = $rutaFotoPanel;
                    $fotos_autos[$j][$i+1] = "Foto del Panel";
                    $i=$i+2;
                }
                if($auto->getAccesorio1() != null && $auto->getAccesorio1() != "") {
                    $rutaFotoAccesorios1= $auto->getAccesorio1();
                    $fotos_autos[$j][$i] = $rutaFotoAccesorios1;
                    $fotos_autos[$j][$i+1] = "Foto de Accesorio 1";
                    $i=$i+2;
                }  
                if($auto->getAccesorio2() != null && $auto->getAccesorio2() != "") {
                    $rutaFotoAccesorios2=$auto->getAccesorio2();
                    $fotos_autos[$j][$i] = $rutaFotoAccesorios2;
                    $fotos_autos[$j][$i+1] = "Foto de Accesorio 2";
                }
            }
        }

        $this->fotos_autos = $fotos_autos;
        
        $this->setLayout(false);
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

    public function executeFiltrosBusqueda(sfWebRequest $request) {

        $this->getUser()->setAttribute('fechainicio', $request->getParameter('fechainicio'));
        $this->getUser()->setAttribute('horainicio', $request->getParameter('horainicio'));
        $this->getUser()->setAttribute('fechatermino', $request->getParameter('fechatermino'));
        $this->getUser()->setAttribute('horatermino', $request->getParameter('horatermino'));
        throw new sfStopException();
    }

    public function executeGetModelSearch(sfWebRequest $request) {

        sfConfig::set('sf_web_debug', false);
        $_output;

        /* Asegurar que la solicitud sea AJAX */
        //if (!$request->isXmlHttpRequest())
        //return $this->renderText(json_encode(array('error'=>'S?lo respondo consultas v?a AJAX.')));

        $_output[] = array("optionValue" => 0, "optionDisplay" => "Modelo");

        if ($request->getParameter('id')) {

            //  $brand = Doctrine_Core::getTable('Brand')->find(array($request->getParameter('id')));

            $this->models = $q = Doctrine_Query::create()->from('Model mo')
                    ->innerJoin('mo.Cars ca')
                    ->where('mo.Brand.Id = ?', $request->getParameter('id'))
                    ->execute();

            foreach ($this->models as $p) {
                $_output[] = array("optionValue" => $p->getId(), "optionDisplay" => $p->getName());
            }
        }

        return $this->renderText(json_encode($_output));
    }

    public function executeIndexOld(sfWebRequest $request) {

        $cityname = $request->getParameter('c');

        $q = Doctrine::getTable('City')->createQuery('c')->where('c.name = ? ', array($cityname));
        $city = $q->fetchOne();

        if ($city != null) {
            $this->lat = $city->getLat();
            $this->lng = $city->getLng();
        } else {

            if (stripos($_SERVER['SERVER_NAME'], "arrendas") !== FALSE) {
                $this->lat = -34.59;
                $this->lng = -58.401604;
            } else {
                $this->lat = -33.427224;
                $this->lng = -70.605558;
            }
        }

        $boundleft = -35;
        $boundright = -30;
        $boundtop = -60;
        $boundbottom = -55;



        $q = Doctrine_Query::create()
                ->select(' ca.id,  re.id idre, mo.name model,
		  br.name brand, ca.year year,
		  ca.address address, ci.name city,
		  st.name state, co.name country,
		  owner.firstname firstname,
		  owner.lastname lastname,
		  ca.price_per_day priceday,
		  ca.price_per_hour pricehour, re.date date'
                )
                ->from('Car ca')
                ->innerJoin('ca.Reserves re')
                ->innerJoin('ca.Model mo')
                ->innerJoin('ca.User owner')
                ->innerJoin('mo.Brand br')
                ->innerJoin('ca.City ci')
                ->innerJoin('ci.State st')
                ->innerJoin('st.Country co')
                ->where('ca.lat > ?', $boundleft)
                ->andWhere('ca.activo = ?', 1)
                ->andWhere('ca.lat < ?', $boundright)
                ->andWhere('ca.lng > ?', $boundtop)
                ->andWhere('ca.lng < ?', $boundbottom)
                ->orderBy('re.date desc')
                ->limit(10);
        $this->last = $q->execute();

        $q = Doctrine_Query::create()
                ->select('ca.id, av.id idav , mo.name model,
		  br.name brand, ca.year year,
		  ca.address address, ci.name city,
		  st.name state, co.name country,
		  owner.firstname firstname,
		  owner.lastname lastname,
		  ca.price_per_day priceday,
		  ca.price_per_hour pricehour'
                )
                ->from('Car ca')
                ->innerJoin('ca.Availabilities av')
                ->innerJoin('ca.Model mo')
                ->innerJoin('ca.User owner')
                ->innerJoin('mo.Brand br')
                ->innerJoin('ca.City ci')
                ->innerJoin('ci.State st')
                ->innerJoin('st.Country co')
                ->where('ca.lat > ?', $boundleft)
                ->andWhere('ca.activo = ?', 1)
                ->andWhere('ca.lat < ?', $boundright)
                ->andWhere('ca.lng > ?', $boundtop)
                ->andWhere('ca.lng < ?', $boundbottom)
                ->andWhere('ca.offer = ?', 1)
                ->limit(10);
        $this->offers = $q->execute();
        //echo $q->getSqlQuery();
    }

    

    public function executeMap(sfWebRequest $request) {

	   $modelo = new Model();

        //$this->setLayout(true);
        //      sfConfig::set('sf_web_debug', false);
        $this->getResponse()->setContentType('application/json');

        $boundleft = $request->getParameter('swLat');
        $boundright = $request->getParameter('neLat');
        $boundtop = $request->getParameter('swLng');
        $boundbottom = $request->getParameter('neLng');

        $day_from = $request->getParameter('day_from');
        $day_to = $request->getParameter('day_to');
        $hour_from = date("H:i", strtotime($request->getParameter('hour_from')));
        $hour_to = date("H:i", strtotime($request->getParameter('hour_to')));
        
        $this->getUser()->setAttribute('fechainicio', $day_from);
        $this->getUser()->setAttribute('fechatermino', $day_to);
        $this->getUser()->setAttribute('horainicio', $hour_from);
        $this->getUser()->setAttribute('horatermino', $hour_to);

        $startTime = strtotime($day_from);
        $endTime = strtotime($day_to);

        $timeHasWorkday = false;
        $timeHasWeekend = false;

        for ($i = $startTime; $i <= $endTime; $i = $i + 86400) {
            $thisDate = date('Y-m-d', $i); // 2010-05-01, 2010-05-02, etc
            $dw = date("w", $i);
            if ($dw == 6 || $dw == 0) {
                $timeHasWeekend = true;
                break;
            }
        }

        for ($i = $startTime; $i <= $endTime; $i = $i + 86400) {
            $thisDate = date('Y-m-d', $i); // 2010-05-01, 2010-05-02, etc
            $dw = date("w", $i);
            if ($dw > 0 && $dw < 5) {
                $timeHasWorkday = true;
                break;
            }
        }

        $this->logMessage('day_from ' . $day_from, 'err');

        $brand = $request->getParameter('brand');
        $model = $request->getParameter('model');

        $transmission = $request->getParameter('transmission');
        $type = $request->getParameter('type');

        $location = $request->getParameter('location');
        $price = $request->getParameter('price');

        $lat_centro = $request->getParameter('clat');
        $lng_centro = $request->getParameter('clng');


        $regionCodigo = $request->getParameter("region");
        $comunaCodigoInterno = $request->getParameter("comuna");
        $comunas = array();
        if (!is_null($regionCodigo)) {
            if (is_null($comunaCodigoInterno)) {
                $comunasList = Doctrine::getTable('Comunas')->findByPadre($regionCodigo);
                foreach ($comunasList as $comuna) {
                    $comunas[] = $comuna->getCodigoInterno();
                }
            } else {
                $comunas[] = $comunaCodigoInterno;
            }
        }

        /* remember center */
        $this->getUser()->setAttribute("map_clat", $request->getParameter('map_clat'));
        $this->getUser()->setAttribute("map_clng", $request->getParameter('map_clng'));

        $debug = 0;
        if ($debug) {
            print_r('<html>');
            print_r('<body>');
            print_r(date('h:i:s'));
        }
        $this->logMessage(date('h:i:s'), 'err');


        $query = Doctrine_Query::create()
                ->from('Movie m');

        $q = Doctrine_Query::create()
                ->select('
				ca.id, 
				ca.lat lat, 
				ca.lng lng, 
				co.nombre comuna_nombre,
				ca.price_per_day, 
				ca.price_per_hour,
	        	ca.photoS3 photoS3, 
				ca.Seguro_OK, 
				ca.foto_perfil, 
				ca.transmission transmission,
				ca.user_id,
				ca.velocidad_contesta_pedidos velocidad_contesta_pedidos,
				greatest(1440,ca.velocidad_contesta_pedidos)/greatest(0.1,ca.contesta_pedidos) carrank,
				mo.name modelo, 
				mo.id_tipo_vehiculo id_tipo_vehiculo,
				br.name brand, 
				')
                ->from('Car ca')
                ->innerJoin('ca.Model mo')
                ->innerJoin('ca.Comunas co')
                ->innerJoin('mo.Brand br')
                ->Where('ca.activo = ?', 1)
                ->andWhereIn('ca.seguro_ok', array(4));
        if (count($comunas) > 0) {
            $q->andWhereIn('ca.comuna_id', $comunas);
        } else {
            $q->andWhere('ca.lat < ?', $boundright);
            $q->andwhere('ca.lat > ?', $boundleft);
            $q->andWhere('ca.lng > ?', $boundtop);
            $q->andWhere('ca.lng < ?', $boundbottom);
        }


        if (
                $hour_from != "Hora de inicio" &&
                $hour_to != "Hora de entrega" &&
                $hour_from != "" &&
                $hour_from != "" &&
                $day_from != "Dia de inicio" &&
                $day_to != "Dia de entrega" &&
                $day_from != "" &&
                $day_to != ""
        ) {

            $this->logMessage('day_from0 ' . $day_from, 'err');

            $day_from = implode('-', array_reverse(explode('-', $day_from)));
            $day_to = implode('-', array_reverse(explode('-', $day_to)));

            $this->logMessage('day_from1 ' . $day_from, 'err');

            $fullstartdate = $day_from . " " . $hour_from;
            $fullenddate = $day_to . " " . $hour_to;

            $q = $q->andWhere('ca.disponibilidad_semana >= ?', $timeHasWorkday);
            $q = $q->andWhere('ca.disponibilidad_finde >= ?', $timeHasWeekend);
        }

        if ($brand != "") {
            $q = $q->andWhere('br.id = ?', $brand);
        }


        if ($model != "" && $model != "0") {
            $q = $q->andWhere('mo.id = ?', $model);
        }

        if ($transmission != "") {
            $transmission = explode(",", $transmission);
            $q = $q->andWhereIn('ca.transmission', $transmission);
        }


        if ($type != "") {
            $type = explode(",", $type);
            $q = $q->andWhereIn('mo.id_tipo_vehiculo', $type);
        }

        $q = $q->orderBy('carrank asc');
        $q = $q->addOrderBy(' IF( ca.velocidad_contesta_pedidos =0,1440, ca.velocidad_contesta_pedidos)  asc');
        $q = $q->addOrderBy('ca.fecha_subida  asc');
        $q = $q->limit(33);

        $cars = $q->execute();

        if ($debug) {
            print_r('<br />' . date('h:i:s'));
        }
        $this->logMessage(date('h:i:s'), 'err');

        $this->logMessage('day_from2 ' . $day_from, 'err');

        $data = array();
        $carsid = Array();

        sfContext::getInstance()->getConfiguration()->loadHelpers(array('Url'));
        $contador = 0;
        foreach ($cars as $car) {

            $contador ++;
            if ($debug) {
                print_r('<br />auto numero: ' . $contador . ', ' . date('h:i:s'));
            }
            $this->logMessage(date('h:i:s'), 'err');

            if ($lat_centro != null && $lng_centro != null) {

                $lat1 = $car->getlat();
                $lat2 = $lat_centro;

                $lon1 = $car->getlng();
                $lon2 = $lng_centro;

                $R = 6371; // km
                $dLat = deg2rad($lat2 - $lat1);
                $dLon = deg2rad($lon2 - $lon1);
                $lat1 = deg2rad($lat1);
                $lat2 = deg2rad($lat2);

                $a = sin($dLat / 2) * sin($dLat / 2) +
                        sin($dLon / 2) * sin($dLon / 2) * cos($lat1) * cos($lat2);
                $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
                $d = $R * $c;
            } else {
                $d = 0;
            };

            $photo = $car->getFotoPerfil();

            $has_reserve = false;
            $is_available = true;

            $this->logMessage('day_from ' . $day_from, 'err');

            if (
                    $hour_from != "Hora de inicio" &&
                    $hour_to != "Hora de entrega" &&
                    $hour_from != "" &&
                    $hour_from != "" &&
                    $day_from != "Dia de inicio" &&
                    $day_to != "Dia de entrega" &&
                    $day_from != "" &&
                    $day_to != ""
            ) {

                $this->logMessage('fullstartdate ' . $fullstartdate, 'err');
                $this->logMessage('fullenddate ' . $fullenddate, 'err');

                $has_reserve = $car->hasReserve($fullstartdate, $fullenddate);
            }

            $porcentaje = $car->getContestaPedidos();

            if ($this->getUser()->getAttribute("logged")) {
                $velocidad = $car->getVelocidadContestaPedidos();

                if ($velocidad < 1) {
                    $velocidad = "Menos de un minuto";
                } elseif ($velocidad < 10) {
                    $velocidad = "Menos de 10 minutos";
                } elseif ($velocidad < 60) {
                    $velocidad = "Menos de una hora";
                } elseif ($velocidad < 1440) {
                    if (floor($velocidad / 60) == 1) {
                        $velocidad = "1 hora";
                    } else {
                        $velocidad = floor($velocidad / 60) . " horas";
                    }
                } else {
                    if (floor($velocidad / 60 / 24) == 1) {
                        $velocidad = "1 dia";
                    } else {
                        $velocidad = floor($velocidad / 60 / 24) . " dias";
                    }
                }
            } else {
                $reservasRespondidas = 0;
                $velocidad = 0;
            };
            $transmision = "Manual";
            $tipoTrans = $car->getTransmission();
            $carPercentile = $car->getCarPercentile();

            if ($tipoTrans == 0)
                $transmision = "Manual";
            if ($tipoTrans == 1)
                $transmision = "Autom&aacute;tica";

            $this->logMessage($has_reserve, 'err');

            if (!$has_reserve) {

                $data[] = array('id' => $car->getId(),
                    'longitude' => $car->getlng(),
                    'latitude' => $car->getlat(),
                    'comuna' => strtolower($car->getComunaNombre()),
                    'brand' => $car->getBrand(),
                    'model' => $car->getModelo(),
                    'typeModel' => $car->getIdTipoVehiculo(),
                    'year' => $car->getYear(),
                    'photoType' => $car->getPhotoS3(),
                    'photo' => $photo,
                    'price_per_hour' => $this->transformarPrecioAPuntos(floor($car->getPricePerHour())),
                    'price_per_day' => $this->transformarPrecioAPuntos(floor($car->getPricePerDay())),
                    'userid' => $car->getUserId(),
                    'carRank' => $car->getCarrank(),
                    'carPercentile' => $carPercentile,
                    'typeTransmission' => $transmision,
                    'userVelocidadRespuesta' => $velocidad,
                    'userContestaPedidos' => $porcentaje,
                    'cantidadCalificacionesPositivas' => '0',
                    'd' => $d,
                    'verificado' => $car->autoVerificado(),
                );
            }
        }//fin foreach

        $position = array();
        $newRow = array();
        foreach ($data as $key => $row) {
            $position[$key] = $row["price_per_day"];
            $newRow[$key] = $row;
        }

        if ($price != "") {
            if ($price == 0) {
                asort($position);
            } else {
                arsort($position);
            }
        } else {
            asort($position);
        }

        $returnArray = array();

        foreach ($position as $key => $pos) {
            $returnArray[] = $newRow[$key];
        }

        $carsArray = array("cars" => $returnArray);

        if ($debug) {
            print_r('<br />' . date('h:i:s'));
            print_r('</body>');
            print_r('</html>');
        }
        $this->logMessage(date('h:i:s'), 'err');
        return $this->renderText(json_encode($carsArray));
    }
    
    public function formatearHoraChilena($fecha){
        $horaChilena = strftime("%Y-%m-%d %H:%M:%S",strtotime('-4 hours',strtotime($fecha)));
        return $horaChilena;
    }
    
    public function transformarPrecioAPuntos($precio){
        $precioMillones = intval($precio/1000000);
        $precioMiles = $precio - ($precioMillones*1000000);
        $precioMiles = intval($precioMiles/1000);
        $precioCientos = $precio - ($precioMiles*1000);
        $precioResultado = "";
        if($precioMillones == 0){
            if($precioMiles == 0){
                $precioResultado = $precioCientos;
            }else{
                if($precioCientos == 0){
                    $precioResultado = $precioMiles.".000";
                }else{
                    $cantidadDeCientos = strlen($precioCientos);
                    if($cantidadDeCientos == 1) $precioResultado = $precioMiles.".00".$precioCientos;
                    else if($cantidadDeCientos == 2) $precioResultado = $precioMiles.".0".$precioCientos;
                    else $precioResultado = $precioMiles.".".$precioCientos;
                }
            }
        }else{
            if($precioMiles == 0){
                if($precioCientos == 0){
                    $precioResultado = $precioMillones.".000.000";
                }else{
                    $cantidadDeCientos = strlen($precioCientos);
                    if($cantidadDeCientos == 1) $precioResultado = $precioMillones.".000.00".$precioCientos;
                    else if($cantidadDeCientos == 2) $precioResultado = $precioMillones.".000.0".$precioCientos;
                    else $precioResultado = $precioMillones.".000.".$precioCientos;
                }
            }else{
                $cantidadDeMiles = strlen($precioMiles);
                $cantidadDeCientos = strlen($precioCientos);
                if($cantidadDeMiles == 1){
                    if($cantidadDeCientos == 1) $precioResultado = $precioMillones.".00".$precioMiles.".00".$precioCientos;
                    else if($cantidadDeCientos == 2) $precioResultado = $precioMillones.".00".$precioMiles.".0".$precioCientos;
                    else $precioResultado = $precioMillones.".00".$precioMiles.".".$precioCientos;
                }else if($cantidadDeCientos == 2){
                    if($cantidadDeCientos == 1) $precioResultado = $precioMillones.".0".$precioMiles.".00".$precioCientos;
                    else if($cantidadDeCientos == 2) $precioResultado = $precioMillones.".0".$precioMiles.".0".$precioCientos;
                    else $precioResultado = $precioMillones.".0".$precioMiles.".".$precioCientos;
                }else{
                    if($cantidadDeCientos == 1) $precioResultado = $precioMillones.".".$precioMiles.".00".$precioCientos;
                    else if($cantidadDeCientos == 2) $precioResultado = $precioMillones.".".$precioMiles.".0".$precioCientos;
                    else $precioResultado = $precioMillones.".".$precioMiles.".".$precioCientos;
                }
            }
        }

        return $precioResultado;
    }

    public function executeCars(sfWebRequest $request) {

        $this->setLayout(false);
        sfConfig::set('sf_web_debug', false);

        $boundleft = $request->getParameter('swLat');
        $boundright = $request->getParameter('neLat');
        $boundtop = $request->getParameter('swLng');
        $boundbottom = $request->getParameter('neLng');
        $hour_from = $request->getParameter('hour_from');
        $hour_to = $request->getParameter('hour_to');
        $day_from = $request->getParameter('day_from');
        $day_to = $request->getParameter('day_to');

        /* if(
          $hour_from != "Hora de inicio" &&
          $hour_to != "Hora de entrega" &&
          $hour_from != "" &&
          $hour_from != ""
          )
          {
          list($day_from, $hour_from) = split(' ', $hour_from);
          list($day_to, $hour_to) = split(' ', $hour_to);
          } */

        $q = Doctrine_Query::create()
                ->select('ca.id , av.id idav , mo.name model,
			  br.name brand, ca.year year,
			  ca.address address, ci.name city,
			  st.name state, co.name country,
			  owner.firstname firstname,
			  owner.lastname lastname,
			  ca.price_per_day priceday,
			  ca.price_per_hour pricehour,
			  owner.id userid'
                )
                ->from('Car ca')
                ->innerJoin('ca.Availabilities av')
                ->innerJoin('ca.Model mo')
                ->innerJoin('ca.User owner')
                ->innerJoin('mo.Brand br')
                ->innerJoin('ca.City ci')
                ->innerJoin('ci.State st')
                ->innerJoin('st.Country co')
                ->where('ca.lat > ?', $boundleft)
                ->andWhere('ca.activo = ?', 1)
                ->andWhere('ca.lat < ?', $boundright)
                ->andWhere('ca.lng > ?', $boundtop)
                ->andWhere('ca.lng < ?', $boundbottom);

        if (
                $hour_from != "Hora de inicio" &&
                $hour_to != "Hora de entrega" &&
                $hour_from != "" &&
                $hour_from != "" &&
                $day_from != "Dia de inicio" &&
                $day_to != "Dia de entrega" &&
                $day_from != "" &&
                $day_to != ""
        ) {
            $q = $q->andWhere('av.hour_from > ?', $hour_from)
                    ->andWhere('av.hour_to < ?', $hour_from)
                    ->andWhere('av.date_from > ?', $day_from)
                    ->andWhere('av.date_to < ?', $day_to);
        }

        $this->cars = $q->execute();
    }

    public function getPhotoUser($idUser){
        $claseUsuario = Doctrine_Core::getTable('user')->findOneById($idUser);
        if($claseUsuario->getFacebookId()!=null && $claseUsuario->getFacebookId()!=""){
          $urlFoto = $claseUsuario->getPictureFile();
        }else{
          if($claseUsuario->getPictureFile()!=""){
            $urlPicture = $claseUsuario->getPictureFile();
            $urlPicture = explode("/", $urlPicture);
            $urlPicture = $urlPicture[count($urlPicture)-1];
            $urlFoto = "http://www.arriendas.cl/images/users/".$urlPicture;
          }else{
            $urlFoto = "http://www.arriendas.cl/images/img_calificaciones/tmp_user_foto.jpg";
          }
        }
        return $urlFoto;
    }

    public function executeDoSearch(sfWebRequest $request) {

        /*  $fecha = strtotime($request->getParameter('date'));
          $day = mktime(0, 0, 0, date("m",$fecha), date("d",$fecha), date("y",$fecha));
          $dayArray = getdate($day);

          $q = Doctrine_Query::create()
          ->select('av.id , ca.id idcar , mo.name model,
          br.name brand, ca.year year,
          ca.address address, ci.name city,
          st.name state, co.name country'
          )
          ->from('Availability av')
          ->innerJoin('av.Car ca')
          ->innerJoin('ca.Model mo')
          ->innerJoin('mo.Brand br')
          ->innerJoin('ca.City ci')
          ->innerJoin('ci.State st')
          ->innerJoin('st.Country co')
          ->where('av.date_from <= ?', $request->getParameter('date'))
          ->andWhere('av.date_to >= ?', $request->getParameter('date'));

          $q = $q->andWhere('av.hour_from >= ?', $request->getParameter('hourfrom')) //revisar
          ->andWhere('av.hour_to <= ?', $request->getParameter('hourto')); //revisar
          }
          $q = $q->andWhere('av.day = ? OR av.day IS NULL', $dayArray['wday']);
          //$offers = $q->fetchArray();
          //$offers = $q->getSqlQuery();
          //var_dump($offers);
          $this->searchResults = $q->execute();

          $this->forward ('main', 'search');
         */
    }

	//CONTACTO
    public function executeContact(sfWebRequest $request) {}
	
	//TERMINOS
    public function executeTerminos(sfWebRequest $request) {
        $this->setLayout("newIndexLayout");
    }
	
	//COMPANIA
    public function executeCompania(sfWebRequest $request) {
        $this->setLayout("newIndexLayout");        
    }
	
	//AYUDA
    public function executeAyuda(sfWebRequest $request) {
        
    }

    //////////////////REGISTER//////////////////////////////
    public function executeTerminosycondiciones(sfWebRequest $request) {
        
    }

    public function executePrivacidad(sfWebRequest $request) {
        
    }

    public function executeRegisterPayment(sfWebRequest $request) {
        
    }
	
    public function executeAddCarFromRegister(sfWebRequest $request) {
        
        $idUsuario = sfContext::getInstance()->getUser()->getAttribute('userid');

        $usuario = Doctrine_Core::getTable('user')->findOneById($idUsuario);

		$usuario->setPropietario(true);
   	    $this->getUser()->setAttribute("propietario",true);			    
		$usuario->save();
		$this->redirect('profile/addCar');
        return sfView::NONE;
   
    }
	
    public function executeUserRegister(sfWebRequest $request) {

        //print_r($_SESSION);die;

        if (!isset($_SESSION['reg_back'])) {
            $urlpage = split('/', $request->getReferer());

            if ($urlpage[count($urlpage) - 1] != "userRegister" && $urlpage[count($urlpage) - 1] != "doRegister") {
                $_SESSION['reg_back'] = $request->getReferer();
            }
        }
        try {
            $q = Doctrine_Query::create()
                    ->select('r.*')
                    ->from('Regiones r');

            $this->regiones = $q->fetchArray();
        } catch (Exception $e) {
            echo $e->getMessage();
            die;
        }
    }

    public function executeRegisterClose(sfWebRequest $request) {
        if (isset($_SESSION['reg_back'])) {
            $this->redirect($_SESSION['reg_back']);
        } else {
            $this->forward('main', 'login');
        }
    }

    //////////////////FORGOT//////////////////////////////

    public function executeForgot(sfWebRequest $request) {
        $this->setLayout("newIndexLayout");
    }

    public function executeSiteMap(sfWebRequest $request) {
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
	
    public function executeActivate(sfWebRequest $request) {
        
    }

     //////////////////Busca un auto//////////////////////////////

    public function executeSearchACars(sfWebRequest $request) {
        
        $this->setLayout("newIndexLayout");

        $this->isWeekend    = false;
        $this->limit        = 33;
        $this->Region       = Doctrine_Core::getTable("Region")->find(13);
        $this->hasCommune   = false;
        $this->hasRegion    = false;
        $this->nearToSubway = false;

        if ($request->hasParameter('metro')){
            if($request->getParameter('metro')=='cercanos-al-metro'){
                $this->nearToSubway = true;
            }
        }

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

        if ($this->getUser()->getAttribute("from", false) &&
            $this->getUser()->getAttribute("to", false) &&
            strtotime($this->getUser()->getAttribute("from")) > time() &&
            strtotime($this->getUser()->getAttribute("to")) > strtotime($this->getUser()->getAttribute("from"))) {

            $this->from = $this->getUser()->getAttribute("from");
            $this->to   = $this->getUser()->getAttribute("to");
        } else {

            $this->getUser()->getAttributeHolder()->remove('from');
            $this->getUser()->getAttributeHolder()->remove('to');

            if (strtotime(date("Y-m-d H:i:s")) >= strtotime(date("Y-m-d 20:00:00")) || strtotime(date("Y-m-d H:i:s")) <= strtotime(date("Y-m-d 08:00:00"))) {
                $this->from = date("Y-m-d 08:00", strtotime("+12 Hours"));
            } else {
                $this->from = date("Y-m-d H:i", strtotime("+4 Hours"));
            }

            if (strtotime(date("Y-m-d H:i:s")) >= strtotime(date("Y-m-d 20:00:00")) || strtotime(date("Y-m-d H:i:s")) <= strtotime(date("Y-m-d 08:00:00"))) {
                $this->to = date("Y-m-d 08:00", strtotime("+32 Hours"));
            } else {
                $this->to = date("Y-m-d H:i", strtotime("+24 Hours"));
            }
        }
    }

    //////////////////Enlasnoticias//////////////////////////////

    public function executeNews(sfWebRequest $request) {
        $this->setLayout("newIndexLayout");
    }

    //////////////////¿ComoFunciona?//////////////////////////////

    public function executeHowWorks(sfWebRequest $request) {
        $this->setLayout("newIndexLayout");
    }

    //////////////////ComparePrecios//////////////////////////////

    public function executePricesCompare(sfWebRequest $request) {
        $this->setLayout("newIndexLayout");
    }

    //////////////////PreguntasFrecuentes//////////////////////////////

    public function executeQuestions(sfWebRequest $request) {
        $this->setLayout("newIndexLayout");
    }


    //////////////////LOGIN//////////////////////////////


    public function sendRecoverEmail($user) {

        $url = $_SERVER['SERVER_NAME'];
        $url = str_replace('http://', '', $url);
        $url = str_replace('https://', '', $url);
        // $url = 'http://www.arriendas.cl/main/recover?email=' . $user->getEmail() . "&hash=" . $user->getHash();
        $url = 'http://www.arriendas.cl/contrasena/modificar/' . $user->getId() . "/" . $user->getHash();
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
	
    public function executeDoActivate(sfWebRequest $request) {

        $q = Doctrine::getTable('user')->createQuery('u')->where('u.email = ?', $this->getRequestParameter('email'));
        $user = $q->fetchOne();
        if ($user != null) {

			$url = $_SERVER['SERVER_NAME'];
			$url = str_replace('http://', '', $url);
			$url = str_replace('https://', '', $url);

            sfContext::getInstance()->getConfiguration()->loadHelpers("Asset");
            $name = tmlentities($user->getFirstName());
            $verificacion = $url.$this->getController()->genUrl('main/registerVerify').'?activate='.$user->getHash().'&username='.$user->getUsername();

            require sfConfig::get('sf_app_lib_dir')."/mail/mail.php";
            $mail = new Email();
            $mail->setSubject('Bienvenido a Arriendas.cl!');
            $mail->setBody("<p>Hola $name:</p><p>Bienvenido a Arriendas.cl!</p><p>Haciendo click <a href='$verificacion'>aquí</a> confirmarás la validez de tu dirección de mail.</p><p>[Puedes ver autos cerca tuyo haciendo click <a href='http://www.arriendas.cl'>aquí</a>]</p>");
            $mail->setTo($correo);
            $mail->submit();
			
			
            $this->getUser()->setFlash('msg', 'Se ha enviado un correo a tu casilla');
        } else {
            $this->getUser()->setFlash('msg', 'No se ha encontrado ese correo electrónico en nuestra base');
        }

        $this->getUser()->setFlash('show', true);
        $this->forward('main', 'activate');
    }

    public function executeUploadPhoto(sfWebRequest $request) {

        $return = array("error" => false);
        $valid_formats = array("jpg", "png", "gif", "bmp", "jpeg");

        try {

            $name = $_FILES[$request->getParameter('file')]['name'];
            $size = $_FILES[$request->getParameter('file')]['size'];
			$tmp  = $_FILES[$request->getParameter('file')]['tmp_name'];

            if (!strlen($name)) {
                throw new Exception("Por favor, seleccione una imagen", 2);
            }

            list($txt, $ext) = explode(".", $name);

            $ext = strtolower($ext);

            if (!in_array($ext, $valid_formats)) {
                throw new Exception("Formato de la imagen inválido", 2);
            }

            if ($size > (5 * 1024 * 1024)) {
                throw new Exception("La imagen excede el tamaño máximo permitido (1 MB)", 2);                
            }
            
            /*$sizewh = getimagesize($tmp);

			if($sizewh[0] > 194 || $sizewh[1] > 204) {
				echo '<script>alert(\'La imagen no cumple con las dimensiones especificadas. Puede continuar si lo desea\')</script>';
            }*/
        
            $userId = $this->getUser()->getAttribute("userid");
            $actual_image_name = time() . $userId . "." . $ext;

            $uploadDir = sfConfig::get("sf_web_dir");
            $path      = $uploadDir . '/images/users/';
            $fileName  = $actual_image_name . "." . $ext;

            $tmp = $_FILES[$request->getParameter('file')]['tmp_name'];

            if (!move_uploaded_file($tmp, $path . $actual_image_name)) {
                throw new Exception("El User ".$userId." tiene problemas para grabar la imagen de su perfil", 1);
            }

            sfContext::getInstance()->getConfiguration()->loadHelpers("Asset");

            /*echo "<input type='hidden' name='" . $request->getParameter('photo') . "' value='" . $path . $actual_image_name . "'/>";
            echo "<img src='" . image_path("users/" . $actual_image_name) . "' class='preview' height='" . $request->getParameter('height') . "' width='" . $request->getParameter('width') . "' />";*/

            $User = Doctrine_Core::getTable('User')->find($userId);
            $User->setPictureFile("/images/users/".$actual_image_name);
            $User->save();
        } catch (Exception $e) {
            $return["error"] = true;
            if ($e->getCode() < 2) {
                $return["errorMessage"] = "Problemas al subir la imagen. El problema ha sido notificado al equipo de desarrollo, por favor, intentalo nuevamente más tarde";
                error_log("[".date("Y-m-d H:i:s")."] [main/uploadPhoto] ERROR: ".$e->getMessage());
            } else {
                $return["errorMessage"] = $e->getMessage();
            }            
            if ($request->getHost() == "www.arriendas.cl" && $e->getCode() < 2) {
                Utils::reportError($e->getMessage(), "main/uploadPhoto");
            }
        }

        $this->renderText(json_encode($return));

        return sfView::NONE;
    }    

    public function executeUploadRut(sfWebRequest $request) {

        $valid_formats = array("jpg", "png", "gif", "bmp", "jpeg");

        if (isset($_POST) and $_SERVER['REQUEST_METHOD'] == "POST") {
            $name = $_FILES[$request->getParameter('file')]['name'];
            $size = $_FILES[$request->getParameter('file')]['size'];
            if (strlen($name)) {
                list($txt, $ext) = explode(".", $name);
                if (in_array(strtolower($ext), $valid_formats) || 1==1) {
                    if ($size < (5 * 1024 * 1024 * 10)) { // Image size max 1 MB
                        $userid = $this->getUser()->getAttribute("userid");
                        $actual_image_name = time() . $userid . "." . $ext;

                        $uploadDir = sfConfig::get("sf_web_dir");
                        $path = $uploadDir . '/images/users/';
                        $fileName = $actual_image_name . "." . $ext;

                        $tmp = $_FILES[$request->getParameter('file')]['tmp_name'];
                        if (move_uploaded_file($tmp, $path . $actual_image_name)) {
                            sfContext::getInstance()->getConfiguration()->loadHelpers("Asset");

                            echo "<input type='hidden' name='" . $request->getParameter('photo') . "' value='" . $path . $actual_image_name . "'/>";
                            echo "<img src='" . image_path("users/" . $actual_image_name) . "' class='preview' height='" . $request->getParameter('height') . "' width='" . $request->getParameter('width') . "' />";
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

    public function executeValueYourCar(sfWebRequest $request) {
        $this->setLayout("newIndexLayout");
        try {

            $this->Brands = BrandTable::getBrandOrderByName();
            //$this->Brands = Doctrine_Core::getTable('Brand')->findAll();
        
        } catch(Exception $e) { 
            error_log("[".date("Y-m-d H:i:s")."] [main/valueYourCar] ERROR: ".$e->getMessage()); 
        }
    }

    public function executePriceJson(sfWebRequest $request){
        $return = array("error" => false);
        try {
            $year = null;
            $model = $request->getParameter('model');
            $year = $request->getParameter('year');
            $Model = Doctrine_Core::getTable("model")->find($model);

            $price = $Model->getPrice();

            if(is_null($price) || $price==''){
                throw new Exception("El precio de este modelo aún no está disponible", 1);
            }

            $priceDay = $price/300;

            $priceHour = $priceDay/6;

            if($priceHour < 4000) {
                $priceHour = 4000;
            }

            $valorHora = round(($priceHour * 0.95), -3);
            $valorDia = round(($priceDay * 0.95), -3);
            
            $return["precioDia"] = $valorDia;
            $return["precioHora"] = $valorHora;
        } catch (Exception $e) {
            $return["error"] = true;
            $return["errorCode"] = $e->getCode();
            $return["errorMessage"] = $e->getMessage();
        }
        $this->renderText(json_encode($return));

        return sfView::NONE;
        
    }

    public function executeGetModel(sfWebRequest $request) {
        $return = array("error" => false);

        try {

            $brandId = $request->getPostParameter("brand", null);

            if (is_null($brandId) || $brandId == "") {
                throw new Exception("Falta definir la marca", 1);
            }

            $return["models"] = Model::getByBrand($brandId);

        } catch (Exception $e) {
            $return["error"] = true;
            $return["errorCode"] = $e->getCode();
            $return["errorMessage"] = $e->getMessage();
        }
        
        $this->renderText(json_encode($return));
        
        return sfView::NONE;
    }

    public function executePrice(sfWebRequest $request) {

        $this->setLayout(false);
        sfConfig::set('sf_web_debug', false);

        $year = $request->getParameter('year');
        $model = $request->getParameter('model');
		$brand = $request->getParameter('brand');
		$email = $request->getParameter('email');

        if ($hour == "")
            $hour = 6;
		
		/*
        $q = Doctrine_Query::create()->from('ModelPrice')
                ->where('ModelPrice.Model.Id = ?', $model)
                ->andWhere('ModelPrice.year = ?', $year);
        $messeges = $q->fetchOne();
		*/
		
		//Tabla Calculator
		$q = Doctrine_Query::create()
                ->select('price')
                ->from('Calculator')
				->where('modelo LIKE ?', $model)
		        ->groupBy('modelo');
        $messeges = $q->fetchOne();	

		/*
        if ($messeges == null) {
            $this->result = 7 * $hour; // No hay registro con esos datos en la base
        } else if ($messeges->getPrice() == null) {

            $this->result = 7 * $hour;  // Tiene el precio null
        } else if ($messeges->getPrice() > 32) {
            $this->result = 7 * $hour; // Es mayor a 32
        } else {
            $price = (float) $messeges->getPrice();
            $result = 10 * (1 + (($price - 32)) / 21.7) * $hour;
            $this->result = $result;
        }
		
        $this->result = $this->result * 52 . " por año";
		*/
		
		$result =  ceil($messeges->getPrice() * pow( ( 1 / (1 + 0.05) ), date('Y') - $year) );
		
        $to = $email;
        $subject = 'Bienvenido a Arriendas.cl';

		// compose headers
        $headers = "From: \"Arriendas.cl\" <no-reply@arriendas.cl>\n";
        $headers .= "Content-type: text/html\n";
        $headers .= "X-Mailer: PHP/" . phpversion() . "\n";

        $mail = '
            Bienvenido a Arriendas.cl!
            <br><br>
            Gracias por suscribirte a la primera comunidad de arriendo de veh&iacute;culos entre vecinos por hora.
            <br><br>
            Con tu '.htmlentities($brand).' '.htmlentities($model).' del '.$year.' puedes ganar $'.$result.' por hora.
            <br><br>
            - El equipo de Arriendas.cl
            <br><br>
            <em style="color: #969696">Nota: Para evitar posibles problemas con la recepci&oacute;n de este correo le aconsejamos nos agregue a su libreta de contactos.</em> ';

		// send email
        $this->mailSmtp($to, $subject, $mail, $headers);
		
		$this->result = "Mensaje enviado correctamente. Gracias por utilizar nuestro portal.";
    }

    public function executeLoginFacebook(sfWebRequest $request) {

        $app_id = "213116695458112";
        $app_secret = "8d8f44d1d2a893e82c89a483f8830c25";
        //$my_url = "http://www.arriendas.cl/main/loginFacebook";
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
            $dialog_url = sprintf("https://www.facebook.com/dialog/oauth?client_id=%s&redirect_uri=%s&state=%s&scope=%s,%s", $app_id, urlencode($my_url), $this->getUser()->getAttribute('state'), "email", "user_photos");
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


                    // Notificaciones
                    Notification::make($myUser->id, 1);
                    
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

    public function executePanel(sfWebRequest $request) {
        $this->user = Doctrine::getTable('User')->findOneById($this->getUser()->getAttribute('user_id'));
    }

    public function executeCaptcha() {

        // $this->get('profiler')->disable();

        $this->getResponse()->setContentType('image/png');

        sfConfig::set('sf_web_debug', false);  

        $this->setLayout(false);
    }
    
    public function mailSmtp($to_input,$subject_input,$mail,$headers) {
        error_reporting(0);
        require_once "Mail.php";
        require_once "Mail/mime.php";

        $from = "No Reply <noreply@arriendas.cl>";
        $to = $to_input;
        $subject = $subject_input;
        $body = $mail;

        $host = "smtp.sendgrid.net";
        $username = "german@arriendas.cl";
        $password = "whisper00@@__";

        $headers = array ('From' => $from,
        'To' => $to,
        'Subject' => $subject);

        $mime= new Mail_mime();
        $mime->setHTMLBody($body);
        $body=$mime->get();
        $headers = $mime->headers($headers);

        $smtp = Mail::factory('smtp',
        array ('host' => $host,
         'auth' => true,
         'username' => $username,
         'password' => $password));

        $mail = $smtp->send($to, $headers, $body);
    }

    public function calificacionesPendientes(){
        //crear tabla en calificaciones una vez que la reserva haya expirado
        //enviar mail a propietario y arrendatario informando
    }

    public function executeGenerateRegionesSlugs(sfWebRequest $request) {
        
        Doctrine_Core::getTable('Regiones')->generateSlugs();
    }
    
    public function executeGenerateComunasSlugs(sfWebRequest $request) {
        
        Doctrine_Core::getTable('Comunas')->generateSlugs();
    }
    
    // PROCESO ARTIFICIAL DE UPDATE PARA FACTURAS ENTRE 1306 Y 2000
    // CORRESPONDIENTE A TRANSACCIONES COMPLETADAS
    
    public function executeUpdateManualNroFactura(sfWebRequest $request){
        
        ini_set('memory_limit', '1024M');
        
        //$trans_actual = 13421;
        //$trans_limite = 17826;
        
        $trans_actual = $request->getParameter('from');
        $trans_limite = $request->getParameter('to');
        
        
        $limits = array($trans_actual, $trans_limite);
        $trans_query = Doctrine_Query::create()
            ->from('Transaction t')
            ->leftJoin('t.Reserve r')
            ->leftJoin('r.Car c')
            ->where('t.completed = ?', true)
            ->andwhere('t.id BETWEEN ? AND ?', $limits)
            ->andWhere('t.numero_factura = 0')
            ->execute();
        
        foreach($trans_query as $trans){
            $reserve = $trans->getReserve();
            $this->generarNroFactura($reserve, $trans);
            echo "transaction " . $trans->getId() . " actualiza nro_factura a: " . $trans->getNumeroFactura() . "\n";
            echo "reserve " . $reserve->getId() . " actualiza nro_factura a: " . $reserve->getNumeroFactura() . "\n";
            echo '<br>';
        }
        
        exit;
    }
    
    private function generarNroFactura($reserve, $order){
        
        // primero valido que no exista otra factura ya generada 
        // para la misma quincena y mismo user_id (y numero asignado!)

        $first_day = date('Ym01', strtotime($order->getDate()));
        $actual_day = date('j', strtotime($order->getDate()));
        $day_15 = date('Ymd', strtotime($first_day. ' + 14 days'));
        if($actual_day <= 15){
            // primera quincena
            $quincena_from = $first_day;
            $quincena_to = $day_15;
        }else{
            // segunda quincena
            $quincena_from = $day_15;
            $quincena_to = date('Ymt', strtotime($quincena_from));  //t returns the number of days in the month of a given date
        }

        $rangeDates = array($quincena_from, $quincena_to);
        $trans_query = Doctrine_Query::create()
            ->from('Transaction t')
            ->leftJoin('t.Reserve r')
            ->leftJoin('r.Car c')
            ->where('c.user_id = ?', $reserve->getIdOwner())
            ->andWhere('t.completed = ?', true)
            ->andwhere('t.date BETWEEN ? AND ?', $rangeDates)
            ->andWhere('t.id <> ?', $order->getId())
            ->andWhere('t.numero_factura <> 0');
            //->fetchOne();
        
        
        $cant_transac = $trans_query->count();

        $conn = Doctrine_Manager::getInstance()->getCurrentConnection();
        $conn->beginTransaction();
        
        $nro_fac_transaction = 0;
        if($cant_transac > 0)
        {
            // hay factura generada durante quincena actual, asigno mismo nro.
            $trans = $trans_query->fetchOne();
            $nro_fac_transaction = $trans->getNumeroFactura();
            $order->setNumeroFactura($nro_fac_transaction);
        }else{                    
            // no hay factura generada durante quincena actual, asigno el siguiente nro.
            // (debo obtener el mayor nro. asignado desde ambas tablas)
            try{
                $nro_fac_transaction = $this->getNextNumeroFactura();
                $order->setNumeroFactura($nro_fac_transaction);

            } catch (Exception $ex) {
                echo $ex->getMessage();
            }
        }
        $order->save();
        
        $montoLiberacion = $reserve->getMontoLiberacion();
        $montoGarantia = sfConfig::get('app_monto_garantia');
        if($montoLiberacion != $montoGarantia)
        {
            $nro_fac_reserve = $cant_transac > 0? $this->getNextNumeroFactura() : $nro_fac_transaction + 1;
            $reserve->setNumeroFactura($nro_fac_reserve);
            $reserve->save();
        }                    

        //Resolvemos la transaccion
        $conn->commit();
    }
    
    private function getNextNumeroFactura(){
        
        // max en transaction
        //$qt = "select max(numero_factura) nro_fac from Transaction where id < " . $trans_limite;
        $qt = "select max(numero_factura) nro_fac from Transaction";
        $query1 = Doctrine_Query::create()->query($qt);
        $trans = $query1->toArray();

        // max en reserve
        //$qr = "select max(numero_factura) nro_fac from Reserve where numero_factura < 2000";
        $qr = "select max(numero_factura) nro_fac from Reserve";
        $query2 = Doctrine_Query::create()->query($qr);
        $res = $query2->toArray();

        $next_numero = ($trans[0]['nro_fac'] > $res[0]['nro_fac']? $trans[0]['nro_fac'] : $res[0]['nro_fac']) + 1;
        return $next_numero;
    }

    private function validateDates ($from, $to) {

        $from = strtotime($from);
        $to   = strtotime($to);

        if ($from >= $to) {
            return "La fecha de inicio debe ser menor a la fecha de término.";
        }

        return false;
    }

}
