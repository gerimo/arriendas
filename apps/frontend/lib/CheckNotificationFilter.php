<?php

class checkNotificationFilter extends sfFilter {

    public function execute($filterChain) {

        $ContextUser = $this->getContext()->getUser();

        if ($this->isFirstCall() && $ContextUser->isAuthenticated()) {

            $userId = $ContextUser->getAttribute('userid');
            $User   = Doctrine_core::getTable("User")->find($userId);
            $UserNotification = Doctrine_Core::getTable('UserNotification')->findOneByUserIdAndViewedAt($userId, null);

            if ($UserNotification) {
                $ContextUser->setAttribute("notificationMessage", $UserNotification->getNotification()->message);
                $ContextUser->setAttribute("notificationId", $UserNotification->getNotification()->id);
                if (is_null($UserNotification->getViewedAt())) {
                    $UserNotification->setViewedAt(date("Y-m-d H:i:s"));
                    $UserNotification->save();
                }                
            }
        }

        $filterChain->execute();
    }
}