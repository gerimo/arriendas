<?php

/* 
 * Filtro para validación de pantalla de pago exitoso
 */

class CheckCompleteRegisterFilter extends sfFilter
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
                && $action != 'logout'
                && $action != 'completeRegister'
                && $action != 'doCompleteRegister'
                && $action != 'getCommunes')
        {
            $idUsuario = sfContext::getInstance()->getUser()->getAttribute('userid');
            $User = Doctrine_core::getTable("user")->find($idUsuario);
                if(!$User->getExtranjero() && empty($User->getRut())){
                    $this->getContext()->getController()->forward('main', "completeRegister");
                    throw new sfStopException;
                }
        }

        // Ejecutar el proximo filtro
        $filterChain->execute();
    }

}