<?php

/* 
 * Filtro para validación de pantalla de pago exitoso
 */

class CheckGPSPaymentMessageFilter extends sfFilter
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
                && $action != 'showPayedMessageGPS'
                && $action != 'showMessage'
            ) 
        {
        	$userId = sfContext::getInstance()->getUser()->getAttribute('userid');
            $GPSTransactions = Doctrine_core::getTable("GPSTransaction")->findByCompletedAndViewed(1,0);

            foreach ($GPSTransactions as $GPSTransaction) {
            	$carId = $GPSTransaction->car_id;
            	$Car = Doctrine_core::getTable("car")->find($carId);
            	
            	if($Car->getUser()->id == $userId){
        			$this->getContext()->getController()->redirect('gps/showPayedMessageGPS?transactionId='. $GPSTransaction->id);
            		throw new sfStopException();

            	}

            }
        }

        // Ejecutar el proximo filtro
        $filterChain->execute();
    }

}