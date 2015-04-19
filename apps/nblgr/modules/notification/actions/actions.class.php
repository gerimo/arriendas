<?php
class notificationActions extends sfActions {

    public function executeIndex(sfWebRequest $request) {

        $this->NTS = Doctrine_Core::getTable('NotificationType')->findAll();
        $this->Actions = Doctrine_Core::getTable('Action')->findAll();
        $this->UserTypes = Doctrine_Core::getTable('UserType')->findAll();

        foreach ($this->Actions as $Action) {
            $Notifications[] = array (
                    "id"        => $Action->id,
                    "name"      => $Action->name,
                    "title"     => "nada",
                    "condition" => 0
                    );
            foreach ($this->NTS as $NT) {
                $Notification = Doctrine_Core::getTable('Notification')->findNotification($Action->id, $NT->id);
                $Notifications[] = array (
                    "id"        => $Notification->id,
                    "name"      => $Notification->message,
                    "title"     => $Notification->message_title,
                    "condition" => 1
                    );
            }
        }

        $userTypeId = $this->getUser()->getAttribute("userTypeId"); 

        if ($userTypeId) {

            $this->userTypeId = $this->getUser()->getAttribute("userTypeId");   
        } else {

            $this->userTypeId = $this->getUser()->getAttribute("");   
        }


        $this->Notifications = $Notifications;
    }

    public function executeCreate(sfWebRequest $request) {

        $return = array("error" => false);

        try {

            $name           = $request->getPostParameter("name", null);
            $description    = $request->getPostParameter("description", null);
            $userTypeId     = $request->getPostParameter("userTypeId", null);
            $option         = $request->getPostParameter("option", null);

            if($option == 1){

                $Action = new Action();
                $Action->setCreatedAt(Date("Y-m-d H:i:s")); 
                $Action->setName($name); 
                $Action->setDescription($description); 
                $Action->setIsActive(true); 
                $Action->setUserTypeId($userTypeId);
                $Action->save();
                $NTS = Doctrine_Core::getTable('NotificationType')->findAll();

                foreach ($NTS as $NT) {
                    $Notification = new Notification();
                    $Notification->setActionId($Action->id);
                    $Notification->setCreatedAt(Date("Y-m-d H:i:s"));
                    $Notification->setNotificationTypeId($NT->id);
                    $Notification->save();
                }

            } elseif ($option == 2) {

                $NT = new NotificationType();
                $fechaHoy = Date("Y-m-d H:i:s");
                $NT->setCreatedAt($fechaHoy); 
                $NT->setName($name); 
                $NT->setDescription($description); 
                $NT->setIsActive(true); 
                $NT->save();

                $Actions = Doctrine_Core::getTable('Action')->findAll();

                foreach ($Actions as $Action) {
                    $Notification = new Notification();
                    $Notification->setActionId($Action->id);
                    $Notification->setCreatedAt(Date("Y-m-d H:i:s"));
                    $Notification->setNotificationTypeId($NT->id);
                    $Notification->save();
                }
            }

        } catch (Exception $e) {
            $return["error"]        = true;
            $return["errorCode"]    = $e->getCode();
            $return["errorMessage"] = $e->getMessage();
        }

        $this->renderText(json_encode($return));
        
        return sfView::NONE;
    }

    public function executeEdit(sfWebRequest $request) {

        $return = array("error" => false);

        try {

            $notificationId      = $request->getPostParameter("notificationId", null);
            $message             = $request->getPostParameter("message", null);
            $title               = $request->getPostParameter("title",null);

            $Notification = Doctrine_Core::getTable('Notification')->find($notificationId);


            if ($Notification) {
                if ($message != "") {
                    $Notification->setMessage($message);
                    $Notification->setMessageTitle($title);
                    $Notification->setIsActive(1);
                } else {
                    $Notification->setMessage("");
                    $Notification->setMessageTitle("");
                    $Notification->setIsActive(0);
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

    public function executeFind(sfWebRequest $request) {

        $return = array("error" => false);

        try {

            $userTypeId       = $request->getPostParameter("userTypeId", null);

            $Actions = Doctrine_Core::getTable('Action')->findByUserTypeId($userTypeId);
            $NTS = Doctrine_Core::getTable('NotificationType')->findAll(); 
            $this->getUser()->setAttribute("userTypeId", $userTypeId);   

            foreach ($Actions as $Action) {

                $Notifications[] = array(
                    "n_id"        => $Action->id,
                    "n_name"      => $Action->name,
                    "n_title"     => "nada",
                    "n_condition" => 0
                );
                foreach ($NTS as $NT) {

                    $Notification = Doctrine_Core::getTable('Notification')->findNotification($Action->id, $NT->id, $userTypeId);

                    $Notifications[] = array(
                        "n_id"        => $Notification->id,
                        "n_name"      => $Notification->message,
                        "n_title"     => $Notification->message_title,
                        "n_condition" => 1
                    );
                }
            }

            $return["data"] = $Notifications;

        } catch (Exception $e) {
            $return["error"]        = true;
            $return["errorCode"]    = $e->getCode();
            $return["errorMessage"] = $e->getMessage();
        }

        $this->renderText(json_encode($return));
          
          return sfView::NONE;
      }

}
            