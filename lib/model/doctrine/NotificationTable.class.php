<?php

class NotificationTable extends Doctrine_Table {

	public function findNotification($actionId, $notificationTypeId, $usertypeId) {

        $q = Doctrine_Core::getTable("Notification")
            ->createQuery('N')
            ->innerJoin('N.Action A')
            ->Where('N.action_id = ?', $actionId)
            ->andWhere('N.notification_type_id = ?', $notificationTypeId)
            ->andWhere('A.user_type_id = ?', $usertypeId);

        return $q->fetchOne();
    }
}