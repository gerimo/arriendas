<?php

class NotificationTable extends Doctrine_Table {

	public function findNotification($actionId, $notificationTypeId) {

        $q = Doctrine_Core::getTable("Notification")
            ->createQuery('N')
            ->innerJoin('N.Action A')
            ->Where('N.action_id = ?', $actionId)
            ->andWhere('N.notification_type_id = ?', $notificationTypeId);

        return $q->fetchOne();
    }
}