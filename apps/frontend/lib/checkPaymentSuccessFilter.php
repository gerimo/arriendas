<?php

/* 
 * Filtro para validación de pantalla de pago exitoso
 */

class CheckPaymentSuccessFilter extends sfFilter
{
    public function execute($filterChain)
    {
        $request = $this->getContext()->getRequest();
        $user  = $this->getContext()->getUser();
        $action = $this->context->getActionName();
            
        // Ejecutar este filtro solo una vez, y si el action
        // no es el de login
        if ($this->isFirstCall()
                && $user 
                && $user->isAuthenticated()
                && $action != 'logout')
        {
            $idUsuario = sfContext::getInstance()->getUser()->getAttribute('userid');
            $toShow = Doctrine_Core::getTable("Transaction")->countPendingToShowByUser($idUsuario);
            if($toShow > 0){
                
                $this->getContext()->getController()->forward('bcpuntopagos', 'showExito');
                throw new sfStopException();

            }
        }

        // Ejecutar el proximo filtro
        $filterChain->execute();
    }

}