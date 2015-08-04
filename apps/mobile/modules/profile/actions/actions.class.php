<?php

/**
 * profile actions.
 *
 * @package    CarSharing
 * @subpackage profile
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class profileActions extends sfActions
{
 /**
  * Executes index action
  *
  * @param sfRequest $request A request object
  */
 	public function executeCars(sfWebRequest $request) {
		$this->setLayout("newIndexLayout");

        $this->CAESuccess = $request->getGetParameter("CAE", null);

        $Cars = Doctrine_Query::create()
            ->from('Car C')
            ->where('C.user_id = ?', $this->getUser()->getAttribute("userid"))
            ->orderBy('C.activo DESC')
            ->execute();

        $this->cars = $Cars;

        $week = array(
            1 => "Lunes",
            2 => "Martes",
            3 => "Miércoles",
            4 => "Jueves",
            5 => "Viernes",
            6 => "Sábado",
            7 => "Domingo"
        );

        $this->availabilityOfCars = array();

        foreach ($Cars as $Car) {
            if ($Car->getActivo()) {

                $days = null;                

                if (date("N") == 6 || date("N") == 7 || Doctrine_Core::getTable("Holiday")->findOneByDate(date("Y-m-d"))) {
                    $days = Utils::isWeekend(true, false);
                } elseif (date("N", strtotime("+1 day")) == 6 || date("N", strtotime("+1 day")) == 7 || Doctrine_Core::getTable("Holiday")->findOneByDate(date("Y-m-d", strtotime("+1 day")))) {
                    $days = Utils::isWeekend(true, true);
                }

                if ($days) {
                    foreach ($days as $day) {

                        $data = array();
                        $data["day"] = $day;
                        $data["dayName"] = $week[date("N", strtotime($day))];

                        $CarAvailability = Doctrine_Core::getTable("CarAvailability")->findOneByDayAndCarIdAndIsDeleted($day, $Car->getId(), false);
                        if ($CarAvailability) {
                            $data["from"] = $CarAvailability->getStartedAt();
                            $data["to"] = $CarAvailability->getEndedAt();
                        }

                        $this->availabilityOfCars[$Car->getId()][] = $data;
                    }
                }
            }
        }
    }

	public function executeCarAvailabilityDeleteChangeStatus(sfWebRequest $request){
        $return = array("error" => false);

        $carId = $request->getPostParameter('car');
        $day   = $request->getPostParameter('day');
        
        $week = array(
            1 => "Lunes",
            2 => "Martes",
            3 => "Miércoles",
            4 => "Jueves",
            5 => "Viernes",
            6 => "Sábado",
            7 => "Domingo"
        );

        try {
            $Holiday = Doctrine_Core::getTable("Holiday")->findOneByDate(date("Y-m-d", strtotime("+1 day")));
            
            if (date("N", strtotime("+1 day")) == 6 || date("N", strtotime("+1 day")) == 7 || $Holiday) {
                $i = 0;

                do {
                    $CarAvailability = Doctrine_Core::getTable("CarAvailability")->findOneByDayAndCarIdAndIsDeleted($day, $carId, false);
                    if ($CarAvailability) {

                        $CarAvailability->setIsDeleted(true);
                        $CarAvailability->save();
                    }

                    $i++;
                    $day = date("Y-m-d", strtotime("+".$i." day"));

                    $Holiday = Doctrine_Core::getTable("Holiday")->findOneByDate(date("Y-m-d", strtotime($day)));
                }while (date("N", strtotime($day)) == 7 || date("N", strtotime($day)) == 6 || $Holiday);
            }   
            
        }catch (Exception $e) {
            error_log("No hay nada para borrar");
            $return["error"] = true;
            Utils::reportError($e->getMessage(), "Version Mobile Version Mobile executeCarAvailabilityDeleteChangeStatus");
        }

        $this->renderText(json_encode($return));

        return sfView::NONE;
    }

 	public function executeChangePassword (sfWebRequest $request) {

        $this->setLayout("newIndexLayout");

        $userId = $this->getUser()->getAttribute("userid");

        $this->User = Doctrine_Core::getTable('user')->find($userId);
    }

    public function executeCarDisabledUntilDelete(sfWebRequest $request) {
        
        $return = array("error" => false);

        $carId = $request->getPostParameter('car');
        $to   = $request->getPostParameter('to');

        try {

            $DisabledCar = Doctrine_Core::getTable("Car")->find($carId);
            if(!$DisabledCar){
                throw new Exception("No se encuentra el auto. ¿Trampa?", 1);
            }

            $DisabledCar->setDisabledUntil(null);
            $DisabledCar->save();
        } catch (Exception $e) {

            $return["error"] = true;
            Utils::reportError($e->getMessage(), "Version Mobile executeCarDisabledUntilDelete");
        }

        $this->renderText(json_encode($return));

        return sfView::NONE;
    }

    public function executeDoChangePassword (sfWebRequest $request) {

        $return = array("error" => false);

        $pass1 = $request->getPostParameter('pass1', null);
        $pass2 = $request->getPostParameter('pass2', null);

        try {

            if (is_null($pass1) || $pass1 == "") {
                throw new Exception("Debes ingresar tu nueva contraseña", 2);
            }

            if (is_null($pass2) || $pass2 == "") {
                throw new Exception("Debes repetir tu nueva contraseña", 2);
            }

            if ($pass1 != $pass2) {
                throw new Exception("Las contraseñas no coinciden", 2);
            }

            $userId = $this->getUser()->getAttribute('userid');

            $User = Doctrine_Core::getTable('User')->find($userId);
            $User->setPassword(md5($pass1));

            $User->save();
        } catch (Exception $e) {

            $return["error"] = true;
            $return["errorMessage"] = $e->getMessage();

            if ($e->getCode() != 2) {
                Utils::reportError($e->getMessage(), "Version Mobile profile/doChangePassword");
            }
        }

        $this->renderText(json_encode($return));

        return sfView::NONE;
    }

    public function executeDoEdit (sfWebRequest $request) {

        $return = array("error" => false);
    
        try {
            $firstname      = $request->getPostParameter("firstname", null);
            $lastname       = $request->getPostParameter("lastname", null);
            $motherLastname = $request->getPostParameter("motherLastname", null);
            $email          = $request->getPostParameter("email", null);
            $emailAgain     = $request->getPostParameter("emailAgain", null);
            $rut            = $request->getPostParameter("rut", null);
            $foreign        = $request->getPostParameter("foreign", null);
            $telephone      = $request->getPostParameter("telephone", null);
            $birth          = $request->getPostParameter("birth", null);
            $address        = $request->getPostParameter("address", null);
            $commune        = $request->getPostParameter("commune", null);

            $userId = $this->getUser()->getAttribute("userid");

            $User = Doctrine_Core::getTable('User')->find($userId);
            
            if (is_null($foreign) || $foreign == "") {
                throw new Exception("Debes indicar tu nacionalidad", 1);
            }

            // si el usuario es extranjero y selecciona "soy chileno", se hace la verificacion del rut y se setea
            if($User->getExtranjero() && !$foreign) {
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

                    $User->setRut($number);
                    $User->setRutDv(strtoupper($dv));                    
                }
            }

            if (is_null($firstname) || $firstname == "") {
                throw new Exception("Debes indicar tu nombre", 1);
            }

            if (is_null($lastname) || $lastname == "") {
                throw new Exception("Debes indicar tu apllellido paterno", 1);
            }

            /*if (is_null($motherLastname) || $motherLastname == "") {
                throw new Exception("Debes indicar tu apellido materno", 1);
            }*/

            if (is_null($email) || $email == "") {
                throw new Exception("Debes indicar tu correo electrónico", 1);
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

            if (is_null($commune) || $commune == "0") {
                throw new Exception("Debes indicar tu comuna", 1);
            }

            $User->setFirstname($firstname);
            $User->setLastname($lastname);
            $User->setApellidoMaterno($motherLastname ? $motherLastname : "");
            $User->setFirstname($firstname);
            $User->setEmail($email);
            $User->setExtranjero($foreign);
            $User->setTelephone($telephone);
            $User->setBirthdate($birth);
            $User->setAddress($address);

            $Commune = Doctrine_Core::getTable('Commune')->find($commune);
            $User->setCommune($Commune);

            
            if(!$foreign){
    
                $rut = $User->getRutComplete();
                $basePath = sfConfig::get('sf_root_dir');

                // check Judicial
                if(!$User->getChequeoJudicial()){
                    $comando = "nohup " . 'php '.$basePath.'/symfony arriendas:JudicialValidation --rut="'.strtoupper($rut).'" --user="'.$userId.'"' . " > /dev/null 2>&1 &";
                    exec($comando);
                }

                // check License
                /*if(!$User->getChequeoLicencia()) {
                    $comando = "nohup " . 'php '.$basePath.'/symfony  user:CheckDriversLicense --rut="'.strtoupper($rut).'" --user="'.$userId.'"' . " > /dev/null 2>&1 &";
                    exec($comando);
                }*/

            } else {
                $User->setChequeoJudicial(false);
            }

            // si no estaba confirmado, lo confirma.
            if(!$User->getConfirmed()) {
                $User->setConfirmed(true);
            }



            $User->save();
    
        } catch (Exception $e) {
            $return["error"] = true;
            $return["errorCode"] = $e->getCode();
            $return["errorMessage"] = $e->getMessage();
        }
    
        $this->renderText(json_encode($return));

        return sfView::NONE;
    }

 	public function executeEdit (sfWebRequest $request) {

        $this->setLayout("newIndexLayout");

        $userId = $this->getUser()->getAttribute("userid");

        $this->User = Doctrine_Core::getTable('User')->find($userId);

        // Si el usuario posee "extranjero" = false y "rut" = null entonces se setea "extranjero" a true
        // en el caso contrario ("extranjero" = true y "rut" != null) se setea a "extranjero" a false
        // esto valida en caso que el usuario se haya registrado en la version antigua de arriendas.
        if(!$this->User->getExtranjero() && is_null($this->User->rut)){
            $this->User->setExtranjero(true);
            $this->User->save();
        } elseif($this->User->getExtranjero() && !is_null($this->User->rut)) {
            $this->User->setExtranjero(false);
            $this->User->save();
        }

        $array = $this->User->getArrayImages();
        $this->imagen_perfil = $array["fotoPerfil"];
        $this->imagen_licencia = $array["fotoLicencia"];

        $this->Regions = Doctrine_Core::getTable('Region')->findAll();
    }

    public function executeTransactions(sfWebRequest $request) {

        $this->setLayout("newIndexLayout");

        $userId = sfContext::getInstance()->getUser()->getAttribute('userid');

        $this->TransactionsRenter = null;
        $this->TransactionsOwner  = null;

        $TransactionsRenter = Doctrine_Core::getTable("Transaction")->findByUserId($userId);        

        foreach ($TransactionsRenter as $i => $T) {
            //selecciona solo las Tsacciones pagadas (completed=1)
            if ($T['completed']) {

                $Reserve = Doctrine_Core::getTable("Reserve")->findOneById($T['reserve_id']);

                $this->TransactionsRenter[$i]['fechaInicio']  = $Reserve->getFechaInicio2();
                $this->TransactionsRenter[$i]['fechaTermino'] = $Reserve->getFechaTermino2();

                $precio = $Reserve->getPrice();

                $this->TransactionsRenter[$i]['monto']             = number_format($precio, 0, ',', '.');
                $this->TransactionsRenter[$i]['comisionArriendas'] = number_format($precio * 0.15, 0, ',', '.');
                $this->TransactionsRenter[$i]['precioSeguro']      = number_format($precio * 0.15, 0, ',', '.');
                $this->TransactionsRenter[$i]['neto']              = number_format($precio * 0.7, 0, ',', '.');
                $this->TransactionsRenter[$i]['depositoGarantia']  = number_format($Reserve->getMontoLiberacion(), 0, ',', '.');
            }
        }
        
        $User = Doctrine_Core::getTable('User')->find($userId);
        
        foreach ($User->getTransaccionesWithOwner() as $i => $T) {
            //selecciona solo las transacciones pagadas (completed=1)
            if ($T['completed']) {
                
                $Reserve = Doctrine_Core::getTable("Reserve")->findOneById($T['reserve_id']);

                $this->TransactionsOwner[$i]['fechaInicio']  = $Reserve->getFechaInicio2();
                $this->TransactionsOwner[$i]['fechaTermino'] = $Reserve->getFechaTermino2();

                $precio = $Reserve->getPrice();

                $this->TransactionsOwner[$i]['monto']             = number_format($precio, 0, ',', '.');
                $this->TransactionsOwner[$i]['comisionArriendas'] = number_format($precio * 0.15, 0, ',', '.');
                $this->TransactionsOwner[$i]['precioSeguro']      = number_format($precio * 0.15, 0, ',', '.');
                $this->TransactionsOwner[$i]['neto']              = number_format($precio * 0.7, 0, ',', '.');
                /*$transaccionesRenter[$i]['depositoGarantia']      = number_format($Reserve->getMontoLiberacion(), 0, ',', '.');*/
            }
        }
    }

    public function executeUploadPhoto(sfWebRequest $request) {
        $return = array("error" => false);

        if (!empty($_FILES)) {
            
            $userId = $this->getUser()->getAttribute("userid");

            $tempFile = $_FILES['filemain']['tmp_name'];
            $name = $_FILES['filemain']['name'];
            $size = $_FILES['filemain']['size'];

            // se sube la foto al local, se especifica qué tipo de foto es.
            $message = Image::UploadImageToTempFolder($tempFile, $size, $name, 1, $userId);

            if(strpos($message, "Mensaje:")){
                $return["error"] = true;
                $return["errorMessage"] = $message;
            }

        }
        $this->renderText(json_encode($return));

        return sfView::NONE;        
    }

    public function executeUploadLicense(sfWebRequest $request) {
        $return = array("error" => false);

        if (!empty($_FILES)) {

            $userId = $this->getUser()->getAttribute("userid");
            $User = Doctrine_Core::getTable("user")->find($userId);

            $tempFile = $_FILES['filelicense']['tmp_name'];
            $name = $_FILES['filelicense']['name'];
            $size = $_FILES['filelicense']['size'];
            // se sube la foto al local, se especifica qué tipo de foto es.
            $message = Image::UploadImageToTempFolder($tempFile, $size, $name, 2, $userId);

            if(strpos($message, "Mensaje:")){
                $return["error"] = true;
                $return["errorMessage"] = $message;
            }else{
                $Image = Doctrine_Core::getTable("image")->find($message);
                $User->setDriverLicenseFile($Image->getImageSize("md"));
                $User->save();
            }

        }
        $this->renderText(json_encode($return));

        return sfView::NONE;        
    }
}
