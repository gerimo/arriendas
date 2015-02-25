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
}
