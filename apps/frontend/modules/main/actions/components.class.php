<?php

    class mainComponents extends sfComponents {
        
        public function executeTerminosycondiciones() {}

        public function executeHeader(){
        	$carId= sfContext::getInstance()->getUser()->getAttribute('carId');
        	error_log($carId);

        }
    }
?>