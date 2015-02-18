<?php

/* 
 * Filtro para validación de pantalla de pago exitoso
 */

class CheckNotificationFilter extends sfFilter
{
    public function execute($filterChain)
    {
        $request = $this->getContext()->getRequest();
        $user  = $this->getContext()->getUser();
        $action = $this->context->getActionName();
            
        // Ejecutar este filtro solo una vez, y si el action
        // no es el de login
        if ($this->isFirstCall() && $user) 
        {
            $userId  = sfContext::getInstance()->getUser()->getAttribute('userid');
            $User    = Doctrine_core::getTable("user")->find($userId);
            $UserNotification = Doctrine_Core::getTable('UserNotification')->findOneByUserIdAndViewedAt($userId, null);

            if ($UserNotification) {
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