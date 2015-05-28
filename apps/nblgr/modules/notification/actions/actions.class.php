<?php
class notificationActions extends sfActions {

    public function executeIndex(sfWebRequest $request) {

        $this->NTS = Doctrine_Core::getTable('NotificationType')->findAll();
        $Actions = Doctrine_Core::getTable('Action')->findByUserTypeId(2);
        $this->UserTypes = Doctrine_Core::getTable('UserType')->findAll();

        foreach ($Actions as $Action) {
            $Notifications[] = array (
                    "id"        => $Action->id,
                    "name"      => $Action->name,
                    "title"     => "nada",
                    "condition" => 0
                    );
            foreach ($this->NTS as $NT) {
                $Notification = Doctrine_Core::getTable('Notification')->findNotification($Action->id, $NT->id);
                $name2 = substr($Notification->message,0,100)."...";
                $Notifications[] = array (
                    "id"        => $Notification->id,
                    "name"      => $Notification->message,
                    "name2"     => $name2,
                    "title"     => $Notification->message_title,
                    "condition" => 1
                    );
            }
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

  

}
            