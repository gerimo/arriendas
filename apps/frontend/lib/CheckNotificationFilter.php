<?php

class checkNotificationFilter extends sfFilter {

    public function execute($filterChain) {

        $request = $this->getContext()->getRequest();
        $user  = $this->getContext()->getUser();
        $action = $this->context->getActionName();

        if ($this->isFirstCall() && $user) {

            $userId  = sfContext::getInstance()->getUser()->getAttribute('userid');
            $User    = Doctrine_core::getTable("user")->find($userId);
            $UserNotification = Doctrine_Core::getTable('UserNotification')->findOneByUserIdAndViewedAt($userId, null);

            if ($UserNotification) {
                $this->getContext()->getUser()->setAttribute("notificationMessage", $UserNotification->getNotification()->message);
                $this->getContext()->getUser()->setAttribute("notificationId", $UserNotification->getNotification()->id);
                if (is_null($UserNotification->getViewedAt())) {
                    $UserNotification->setViewedAt(date("Y-m-d H:i:s"));
                    $UserNotification->save();
                }                
            }
        }

        // Ejecutar el proximo filtro
        $filterChain->execute();
    }
}