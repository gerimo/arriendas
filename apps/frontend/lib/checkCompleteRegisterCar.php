<?php

/* 
 * Filtro para validación de pantalla de pago exitoso
 */

class CheckCompleteRegisterCar extends sfFilter
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
                && $action != 'getModels'
                && $action != 'getValidateCar'
                && $action != 'edit'
                && $action != 'getValidatePatent'
                && $action != 'getValidatePrice'
                && $action != 'uploadPhoto'
                && $action != 'uploadPhotoFront'
                && $action != 'uploadPhotoSideRight'
                && $action != 'uploadPhotoSideLeft'
                && $action != 'uploadPhotoBackRight'
                && $action != 'uploadPhotoBackLeft') 
        {
            $userId = sfContext::getInstance()->getUser()->getAttribute('userid');
            $User = Doctrine_core::getTable("user")->find($userId);
            $Car = Doctrine_Core::getTable('Car')->findOneByUserIdAndSeguroOkAndCapacity($userId, 4, 0);

            if ($Car) {
                $this->getContext()->getController()->redirect('cars/edit?id=' . $Car->id);
                throw new sfStopException;
            }
        }

        // Ejecutar el proximo filtro
        $filterChain->execute();
    }

}