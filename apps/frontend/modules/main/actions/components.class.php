<?php

    class mainComponents extends sfComponents {
        
        public function executeTerminosycondiciones() {}

        public function executeHeader(){
        	
        	$userId = sfContext::getInstance()->getUser()->getAttribute('userid');

        	$this->hasCars = false;
        	$this->hasActiveCars = false;
        	
        	$Cars = Doctrine_Core::getTable('Car')->findByUserId($userId);
        	if (count($Cars)) {

        		$this->hasCars = true;

        		foreach ($Cars as $Car) {
        			if ($Car->activo) {
        				$this->hasActiveCars = true;
        				break;
        			}
        		}
        	}

        	
        }

        public function executeFooter(){

        }
    }
?>