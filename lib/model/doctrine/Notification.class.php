<?php

class Notification extends BaseNotification {


    public static function make($userId, $actionId) {

        $Notifications = Doctrine_Core::getTable("Notification")->findByActionId($actionId);

        foreach ($Notifications as $Notification) {

        	$Action = $Notification->getAction();
            $Type = $Notification->getNotificationType();

        	if($Notification->is_active && $Action->is_active && $Type->is_active) {

                $UserNotification = new UserNotification();
                $UserNotification->setCreatedAt(date("Y-m-d H:i:s"));
                $UserNotification->setNotificationId($Notification->id);
                $UserNotification->setUserId($userId);
                $UserNotification->save();

            }
        }

    }

}
