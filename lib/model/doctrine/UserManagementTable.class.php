<?php

class UserManagementTable extends Doctrine_Table {

    public function findCommentsUser($userId = false, $managementId = false) {
        $q = Doctrine_Core::getTable("UserManagement")
            ->createQuery('U')
            ->where('U.user_id = ?', $userId)
            ->andWhere('U.management_id= ?', $managementId)
            ->orderBy('U.created_at DESC');

        return $q->execute();
    }

}
