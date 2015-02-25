<?php

class UserNotificationTable extends Doctrine_Table {

    public function findLastNotification($userId = null, $notificationId = null) {

        if (is_null($userId) || is_null($notificationId)) {
            return null;
        }

        $q = Doctrine_Core::getTable("UserNotification")
            ->createQuery('UN')
            ->where('UN.user_id = ?', $userId)
            ->andWhere('UN.notification_id = ?', $notificationId)
            ->orderBy('UN.created_at DESC');

        return $q->fetchOne();
    }

    public function findBySentAtAsNull() {

        $q = Doctrine_Core::getTable("UserNotification")
            ->createQuery('UN')
            ->where('UN.sent_at is null');

        return $q->execute();
    }
}