<?php

/* 
 * Filtro para validación de pantalla de pago exitoso
 */

    class CheckOptionsCarFilter extends sfFilter
    {
        public function execute($filterChain)
        {
            $request = $this->getContext()->getRequest();
            $user  = $this->getContext()->getUser();
            $action = $this->context->getActionName();

            // Ejecutar este filtro si el usuario esta logueado
            if ($this->isFirstCall() 
                && $user->isAuthenticated() && $action!='setOption'
                && $action!='logout' && $action!='CarDisabledUntilDelete' 
                && $action!='CarAvailabilityDeleteChangeStatus' && $action!='carAvailabilitySave'
                && $action!='carAvailabilityDelete' && $action!='CarDisabledUntilSave'
                && $action!='getChecked' && $action!='toggleActiveCarAjax' && $action!='setActive') 
            {
                $idUser = sfContext::getInstance()->getUser()->getAttribute('userid');
                $Cars = Doctrine_Core::getTable('Car')->findByUserIdAndActivoAndSeguroOkAndOptions($idUser, true, 4 ,0);
                
                if(count($Cars)>0){
                    $this->getContext()->getController()->forward('profile', 'cars');
                    $message = "Debes ingresar los filtros de Disponibilidad";
                    echo "<script type='text/javascript'>alert('$message');</script>";
                    throw new sfStopException();
               }
               
            }
            // Ejecutar el proximo filtro
            $filterChain->execute();
        }

    }

?>