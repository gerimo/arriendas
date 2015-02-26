<?php

class Notification extends BaseNotification {


    public static function make($userId, $actionId, $reserveId = null) {

        $Notifications = Doctrine_Core::getTable("Notification")->findByActionId($actionId);

        foreach ($Notifications as $Notification) {

        	$Action = $Notification->getAction();
            $Type = $Notification->getNotificationType();

        	if($Notification->is_active && $Action->is_active && $Type->is_active) {

                $UserNotification = new UserNotification();
                $UserNotification->setCreatedAt(date("Y-m-d H:i:s"));
                $UserNotification->setNotificationId($Notification->id);
                $UserNotification->setUserId($userId);
                $UserNotification->setReserveId($reserveId);
                $UserNotification->save();
            }
        }

    }

    public static function translator($userId, $message, $reserveId = null){
        $User = Doctrine_Core::getTable("user")->find($userId);
        if($User) {

            $message = str_replace("{User.id}", $User->id, $message);
            $message = str_replace("{User.firstname}", $User->firstname, $message);
            $message = str_replace("{User.lastname}", $User->lastname, $message);
            $message = str_replace("{User.birthdate}", $User->birthdate, $message);
            $message = str_replace("{User.rut}", $User->getRutFormatted(), $message);
            $message = str_replace("{User.email}", $User->email, $message);
            $message = str_replace("{User.telephone}", $User->telephone, $message);
            $message = str_replace("{User.address}", $User->address, $message);
            $message = str_replace("{User.commune}", $User->getCommune()->name, $message);
            $message = str_replace("{User.region}", $User->getCommune()->getRegion()->name, $message);
        }
        if($reserveId) {
            $Reserve = Doctrine_Core::getTable("Reserve")->find($$reserveId);
            if($Reserve) {
                $message = str_replace("{Reserve.id}", $Reserve->id, $message);
                $message = str_replace("{Reserve.date}", $Reserve->date, $message);
                $message = str_replace("{Reserve.duration}", $Reserve->duration, $message);
                $message = str_replace("{Reserve.price}", $Reserve->price, $message);
                $message = str_replace("{Reserve.numeroFactura}", $Reserve->numero_factura, $message);
                $Car = $Reserve->getCar();
                if($Car) {
                    $message = str_replace("{Car.id}", $Car->id, $message);
                    $message = str_replace("{Car.address}", $Car->address, $message);
                    $message = str_replace("{Car.model}", $Car->getModel()->name, $message);
                    $message = str_replace("{Car.brand}", $Car->getModel()->getBrand()->name, $message);
                    $message = str_replace("{Car.commune}", $Car->getCommune()->name, $message);
                    $message = str_replace("{Car.region}", $Car->getCommune()->getRegion()->name, $message);
                }
            }
        }
        return $message;
    }

}
