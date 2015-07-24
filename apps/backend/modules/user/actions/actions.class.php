<?php

class userActions extends sfActions {
    
    public function executeIndex(sfWebRequest $request) {
    }

    public function executeUserControl(sfWebRequest $request) {
    }

    public function executeEditUser(sfWebRequest $request) {

        $return = array("error" => false);

        try {

            $userId    = $request->getPostParameter("userId", null);
            $type      = $request->getPostParameter("type", null);
            $option    = $request->getPostParameter("option", null);

            error_log($userId);

            $User = Doctrine_Core::getTable('User')->find($userId);
            if ($type == 1) {
                $User->setBlocked($option);
            } elseif ($type == 2) {
                $User->setUsuarioValidado($option);
            } elseif ($type == 3) {
                $User->setExtranjero($option);
            } elseif ($type == 4) {
                $User->setIsValidLicense($option);
            } elseif ($type == 5) {
                $User->setChequeoJudicial($option);
            } elseif ($type == 6) {
                $bankAccount = $User->getBankAccount();
                if ($bankAccount) {
                    $bankAccount->setNumber($numberAccount);
                    $bankAccount->setRutBank($rutNumberAccount);
                    $bankAccount->setRutDvBank($dvAccount);
                    $bankAccount->setBankId($bankId);
                    $bankAccount->setBankAccountTypeId($setBankAccountTypeId);
                    $bankAccount->setCreatedAt(date("Y-m-d H:i:s"));
                    $bankAccount->save();
                }
            }

            $User->save();

        } catch (Exception $e) {
            $return["error"]        = true;
            $return["errorCode"]    = $e->getCode();
            $return["errorMessage"] = $e->getMessage();
        }

        $this->renderText(json_encode($return));
        
        return sfView::NONE;
    }

    public function executeEditUserAccount(sfWebRequest $request) {

        $return = array("error" => false);

        try {

            $userId         = $request->getPostParameter("userId", null);
            $rutAccount     = $request->getPostParameter("rutAccount", null);
            $numberAccount  = $request->getPostParameter("numberAccount", null);
            $bankId         = $request->getPostParameter("bankId", null);
            $accountId      = $request->getPostParameter("accountId", null);

            $User = Doctrine_Core::getTable('User')->find($userId);

            if (is_null($rutAccount) || $rutAccount == "") {
                    throw new Exception("Debes indicar el rut del banco", 1);
            } else {

                $rutAccount      = Utils::isValidRUT($rutAccount);
                $dvAccount       = substr($rutAccount, -1);
                $rutNumberAccount   = substr($rutAccount, 0, -1);

                if ($rutAccount == false || strlen($rutNumberAccount)>8) {
                    throw new Exception("el rut del banco ingresado es inválido", 1);
                }            
            }

            if (is_null($numberAccount) || $numberAccount == "") {
                throw new Exception("Debes indicar tu N° de cuenta", 1);
            }

            if (is_null($bankId) || $bankId == "0") {
                throw new Exception("Debes indicar tu banco", 1);
            }

            if (is_null($accountId) || $accountId == "0") {
                throw new Exception("Debes indicar el tipo de cuenta", 1);
            }

            $bankAccount = $User->getBankAccount();
            $bankAccount->setNumber($numberAccount);
            $bankAccount->setRutBank($rutNumberAccount);
            $bankAccount->setRutDvBank($dvAccount);
            $bankAccount->setBankId($bankId);
            $bankAccount->setBankAccountTypeId($accountId);
            $bankAccount->setCreatedAt(date("Y-m-d H:i:s"));
            $bankAccount->save();

        } catch (Exception $e) {
            $return["error"]        = true;
            $return["errorCode"]    = $e->getCode();
            $return["errorMessage"] = $e->getMessage();
        }

        $this->renderText(json_encode($return));
        
        return sfView::NONE;
    }

    public function executeUserFindAll(sfWebRequest $request) {
        $return = array("error" => false);

        $limit  = $request->getPostParameter("limit", null);

        try {

            $return["data"] = array();

            $Users = Doctrine_Core::getTable('User')->findUserControl($limit);

            if(count($Users) == 0){
                throw new Exception("No se encuentran usuarios", 1);
            }

            foreach($Users as $i => $User){
                $return["data"][$i] = array(
                    "user_id"        => $User->id,
                    "user_rut"       => $User->getRutFormatted(),
                    "user_fullname"  => ucfirst(strtolower($User->firstname." ".$User->lastname)),
                    "user_telephone" => $User->telephone,
                    "user_address"   => $User->address,
                    "user_email"     => $User->email,
                    "user_blocked"   => $User->blocked,
                    "user_valid"     => $User->usuario_validado,
                    "user_foreign"   => $User->extranjero,
                    "user_facebook"  => $User->facebook_id,
                    "user_license"   => $User->driver_license_file,
                    "user_comment"   => $User->getUserManagements()   
                );
            }
        } catch (Exception $e) {
            $return["error"]        = true;
            $return["errorCode"]    = $e->getCode();
            $return["errorMessage"] = $e->getMessage();
        }

        $this->renderText(json_encode($return));
        
        return sfView::NONE;
    }

    public function executeChangeCall(sfWebRequest $request) {

        $return = array("error" => false);

        try {

            $userId   = $request->getPostParameter("userId", null);
            $option   = $request->getPostParameter("option", null);

            $User = Doctrine_Core::getTable('User')->find($userId);

            $User->setLlamadoRegistro($option);
            $User->save();

        } catch (Exception $e) {
            $return["error"]        = true;
            $return["errorCode"]    = $e->getCode();
            $return["errorMessage"] = $e->getMessage();
        }

        $this->renderText(json_encode($return));
        
        return sfView::NONE;
    }

    public function executeWhitoutCar(sfWebRequest $request) {
    }

    public function executeWhitoutCarGet(sfWebRequest $request) {

        $return = array("error" => false);

        try {

            $from   = $request->getPostParameter("from", null);
            $to     = $request->getPostParameter("to", null);
            $isAutomatic        = $request->getPostParameter('isAutomatic', false) === 'true' ? true : false;
            $isLowConsumption   = $request->getPostParameter('isLowConsumption', false) === 'true' ? true : false;
            $isMorePassengers   = $request->getPostParameter('isMorePassengers', false) === 'true' ? true : false;

            $return["data"] = array();

            $Users = Doctrine_Core::getTable('User')->findUsersWithoutCar($from, $to);

            if(count($Users) == 0){
                throw new Exception("No se encuentran usuarios", 1);
            }

            foreach($Users as $i => $User){

                $return["data"][$i] = array(
                    "user_id"        => $User->id,
                    "user_fullname"  => ucfirst(strtolower($User->firstname." ".$User->lastname)),
                    "user_telephone" => $User->telephone,
                    "user_address"   => $User->address,
                    "user_email"     => $User->email,
                    "user_call"      => $User->llamado_registro,
                    "user_comment"   => $User->getUserManagements()   
                );
            }

        } catch (Exception $e) {
            $return["error"]        = true;
            $return["errorCode"]    = $e->getCode();
            $return["errorMessage"] = $e->getMessage();
        }

        $this->renderText(json_encode($return));
        
        return sfView::NONE;
    }
    
    public function executeWhitoutPay(sfWebRequest $request) {
    }

    public function executeWhitoutPayGet(sfWebRequest $request) {

        $return = array("error" => false);

        try {

            $from = $request->getPostParameter("from", null);
            $to   = $request->getPostParameter("to", null);

            $return["data"] = array();

            $Reserves = Doctrine_Core::getTable('User')->findUsersWithoutPay($from, $to);



            if(count($Reserves) == 0){
                throw new Exception("No se encuentran usuarios", 1);
            }

            foreach($Reserves as $i => $Reserve){

                $User = $Reserve->getUser();
                $return["data"][$i] = array(
                    "user_id"               => $User->id,
                    "user_fullname"         => ucfirst(strtolower($User->firstname." ".$User->lastname)),
                    "user_telephone"        => $User->telephone,
                    "user_address"          => $User->address,
                    "user_email"            => $User->email,
                    "user_call"             => $User->llamado_registro,
                    "user_comment"          => $User->getUserManagements(),
                    "reserva_fecha_inicio"  => $Reserve->fecha_inicio   
                );
                  
            }

        } catch (Exception $e) {
            $return["error"]        = true;
            $return["errorCode"]    = $e->getCode();
            $return["errorMessage"] = $e->getMessage();
        }

        $this->renderText(json_encode($return));
        
        return sfView::NONE;
    }

     public function executeUploadPhoto(sfWebRequest $request) {

        $return = array();

        try {

            if (!isset($_POST) || $_SERVER['REQUEST_METHOD'] != "POST") {
                throw new Exception("No! No! No!", 1);
            }

            $userId  = $request->getPostParameter("userId", null);

            $User = Doctrine_Core::getTable('User')->find($userId);

            if (!$User) {
                throw new Exception("No se encontro el usuario", 1);
            }

            $name = $_FILES['photo']['name'];
            $size = $_FILES['photo']['size'];
            $tempFile  = $_FILES['photo']['tmp_name'];
            
            $message = Image::UploadImageToTempFolder($tempFile, $size, $name, 2, $userId);


            if(strpos($message, "Mensaje:")){
                throw new Exception($message, 2);
            }else{
                $Image = Doctrine_Core::getTable("image")->find($message);
                $User->setDriverLicenseFile($Image->getImageSize("md"));
                $User->save();
                $return["urlPhoto"] = $Image->getImageSize("md");
            }

        } catch (Exception $e) {
            $return["error"] = true;
            $return["errorMessage"] = $e->getMessage();
            error_log($e->getMessage());
            if ($e->getCode() == 1) {
                $return["errorMessage"] = "Problemas al subir la imagen. El problema ha sido notificado al equipo de desarrollo, por favor, intentalo más tarde";
            }
        }

        $this->renderText(json_encode($return));
        return sfView::NONE;
    }
}
