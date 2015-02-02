<?php

class userActions extends sfActions {
    
    public function executeIndex(sfWebRequest $request) {
    }

    public function executeWhitoutPay(sfWebRequest $request) {
        
    }

    public function executeWhitoutPayGet(sfWebRequest $request) {

        $return = array("error" => false);

        try {

            $fecha = $request->getPostParameter("fecha", null);

            $return["data"] = array();

            $Reserves = Doctrine_Core::getTable('User')->findUsersWithoutPay($fecha);

            if(count($Reserves) == 0){
            	throw new Exception("No se encuentran usuarios", 1);
            }

            foreach($Reserves as $i => $Reserve){

            	$User = $Reserve->getUser();

            	$return["data"][$i] = array(
            	 	"user_id" => $User->id,
            	 	"user_fullname" => ucfirst(strtolower($User->firstname." ".$User->lastname)),
            	 	"user_comment" => $User->comentarios_recordar,
            	 	"user_telephone" => $User->telephone,
            	 	"reserve_id" => $Reserve->id,
            	 	"reserve_at" => date("d-m-Y H:i", strtotime($Reserve->fecha_reserva)),
            	);
            }

            


        } catch (Exception $e) {
            $return["error"] = true;
            $return["errorCode"] = $e->getCode();
            $return["errorMessage"] = $e->getMessage();
        }

        $this->renderText(json_encode($return));
        
        return sfView::NONE;
    }

    public function executeWhitoutPayComment(sfWebRequest $request) {

         $return = array("error" => false);
         error_log("por aqui");

        try {

            $userId  = $request->getPostParameter("userId", null);
            $comment = $request->getPostParameter("comment", null);

            $User = Doctrine_Core::getTable('User')->find($userId);

            $User->setComentariosRecordar($comment);

            $User->save();


        } catch (Exception $e) {
            $return["error"] = true;
            $return["errorCode"] = $e->getCode();
            $return["errorMessage"] = $e->getMessage();
        }

        $this->renderText(json_encode($return));
        
        return sfView::NONE;
    }


}
