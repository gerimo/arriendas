<?php
class notificationActions extends sfActions {

	public function executeIndex(sfWebRequest $request) {

        $this->Actions = Doctrine_Core::getTable('Action')->findAll();
	}

    public function executeEditNotification(sfWebRequest $request) {

        $return = array("error" => false);

        try {

            $notificationId      = $request->getPostParameter("notificationId", null);
            $title               = $request->getPostParameter("title", null);
            $message             = $request->getPostParameter("message", null);
            $option              = $request->getPostParameter("option", null);

            $Notification = Doctrine_Core::getTable('Notification')->find($notificationId);

            if ($Notification) {
                if ($title && $message) {
                    $Notification->setMessageTitle($title);
                    $Notification->setMessage($message);
                } else {
                    $Notification->setIsActive($option);
                } 
            }

            $Notification->save();

        } catch (Exception $e) {
            $return["error"]        = true;
            $return["errorCode"]    = $e->getCode();
            $return["errorMessage"] = $e->getMessage();
        }

        $this->renderText(json_encode($return));
        
        return sfView::NONE;
    }

    public function executeFindNotification(sfWebRequest $request) {

        $return = array("error" => false);

        try {

            $actionId       = $request->getPostParameter("actionId", null);

            $Notifications = Doctrine_Core::getTable('Notification')->findByActionId($actionId);

            if (count($Notifications) == 0 ) {
                throw new Exception("No hay notificaciones", 1);
            }      

            foreach($Notifications as $i => $Notification){

                $NT = $Notification-> getNotificationType();
                $return["data"][$i] = array(
                    "n_id"        => $Notification->id,
                    "n_title"     => $Notification->message_title,
                    "n_message"   => $Notification->message,
                    "nt_name"     => $NT->name,
                    "n_is_active" => $Notification->is_active
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
	
	public function executeManagementAction(sfWebRequest $request) {

		$this->Actions = Doctrine_Core::getTable('Action')->findAll();
	}

	public function executeManagementActionCrud(sfWebRequest $request) {

		$return = array("error" => false);

        try {

            $actionId   	= $request->getPostParameter("actionId", null);
            $name      		= $request->getPostParameter("name", null);
            $description    = $request->getPostParameter("description", null);
            $option		    = $request->getPostParameter("option", null);

            $Action = Doctrine_Core::getTable('Action')->find($actionId);

            if ($Action) {
                if ($name && $description) {
                	$Action->setName($name);
                	$Action->setDescription($description);
                } else {
                	$Action->setIsActive($option);
                } 

            } elseif ($name && $description) {
            	$Action = new Action();
                $fechaHoy = Date("Y-m-d H:i:s");
                $Action->setCreatedAt($fechaHoy); 
                $Action->setName($name); 
                $Action->setDescription($description); 
                $Action->setIsActive(true); 
            }

            $Action->save();

        } catch (Exception $e) {
            $return["error"]        = true;
            $return["errorCode"]    = $e->getCode();
            $return["errorMessage"] = $e->getMessage();
        }

        $this->renderText(json_encode($return));
        
        return sfView::NONE;
	}

	public function executeManagementNotificationType(sfWebRequest $request) {

        $this->NTS = Doctrine_Core::getTable('NotificationType')->findAll();
	}

    public function executeManagementNotificationTypeCrud(sfWebRequest $request) {

        $return = array("error" => false);

        try {

            $NTId           = $request->getPostParameter("NTId", null);
            $name           = $request->getPostParameter("name", null);
            $description    = $request->getPostParameter("description", null);
            $option         = $request->getPostParameter("option", null);

            $NT = Doctrine_Core::getTable('NotificationType')->find($NTId);

            if ($NT) {
                if ($name && $description) {
                    $NT->setName($name);
                    $NT->setDescription($description);
                } else {
                    $NT->setIsActive($option);
                } 

            } elseif ($name && $description) {
                $NT = new NotificationType();
                $fechaHoy = Date("Y-m-d H:i:s");
                $NT->setCreatedAt($fechaHoy); 
                $NT->setName($name); 
                $NT->setDescription($description); 
                $NT->setIsActive(true); 
            }

            $NT->save();

        } catch (Exception $e) {
            $return["error"]        = true;
            $return["errorCode"]    = $e->getCode();
            $return["errorMessage"] = $e->getMessage();
        }

        $this->renderText(json_encode($return));
        
        return sfView::NONE;
    }

}
