<?php

class UserManagementTable extends Doctrine_Table {

    public function findCommentsUser($userId = false, $managementId = false) {
        $q = Doctrine_Core::getTable("UserManagement")
            ->createQuery('U')
            ->where('U.user_id = ?', $userId)
            ->andWhere('U.management_id= ?', $managementId)
            ->andWhere('U.is_deleted = 0')
            ->orderBy('U.created_at DESC');

        return $q->execute();
    }

}
