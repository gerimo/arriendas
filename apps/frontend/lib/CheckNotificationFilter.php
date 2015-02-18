<?php

class checkNotificationFilter extends sfFilter {

    public function execute($filterChain) {

        $request = $this->getContext()->getRequest();
        $user  = $this->getContext()->getUser();
        $action = $this->context->getActionName();

        if ($this->isFirstCall() && $user) {

            $userId  = sfContext::getInstance()->getUser()->getAttribute('userid');
            $User    = Doctrine_core::getTable("user")->find($userId);
            $UserNotification = Doctrine_Core::getTable('UserNotification')->findOneByUserId($userId);

            error_log(gettype($UserNotification));

            if ($UserNotification) {
                error_log(2);
                $this->getContext()->getUser()->setAttribute("notificationMessage", $UserNotification->getNofication()->message);
                $this->getContext()->getUser()->setAttribute("notificationId", $UserNotification->getNofication()->id);
                $UserNotification->setViewedAt(date("Y-m-d H:i:s"));
                $UserNotification->save();
                throw new sfStopException;
            }
        }

        // Ejecutar el proximo filtro
        $filterChain->execute();
    }

}