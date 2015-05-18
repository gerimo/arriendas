<?php

    class checkHasNotPaidAGPSFilter extends sfFilter
    {
        public function execute($filterChain)
        {
            $request = $this->getContext()->getRequest();
            $user  = $this->getContext()->getUser();
            $action = $this->context->getActionName();

            if ($this->isFirstCall()
                    && $user 
                    && $user->isAuthenticated()
                    && $action != 'showMessage'
                    && $action != 'cancelUploadCar'
                    && $action != 'payGps'
                    && $action != 'processPaymentGPS'
                    && $action != 'processPaymentFinalGPS'
                    && $action != 'logout')
            {
                
                $idUsuario = sfContext::getInstance()->getUser()->getAttribute('userid');
                

                $Cars = Doctrine_core::getTable("car")->findByUserId($idUsuario);
                // Fecha que establece qué autos son "nuevos" y cualos no
                // el filtro hará efecto bajo los autos "nuevos" que no posean GPS
                $fecha = Date("Y-m-d H:i:s", strtotime("2015-05-09"));

                foreach ($Cars as $Car) {
                    if($Car->getFechaSubida() > $fecha){
                        if(!$Car->has_gps){
                            if($action != 'showMessage'){
                                $this->getContext()->getController()->redirect('gps/showMessage?car='.$Car->id); //definir una vista para el pago

                                throw new sfStopException();
                            }
                        }
                    }
                    
                }

            }

            $filterChain->execute();
        }

    }

?>