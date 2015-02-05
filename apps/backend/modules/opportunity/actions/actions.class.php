<?php

class opportunityActions extends sfActions {

    public function executeIndex(sfWebRequest $request) {
    }

    public function executeCreate(sfWebRequest $request) {
	}

    public function executeMailing (sfWebRequest $request) {

        $this->oOC = Doctrine_Core::getTable("OpportunityConfig")
            ->createQuery('OC')->fetchOne();
    }

    public function executeMailingConfigSave (sfWebRequest $request) {
        
        $return = array("error" => false);

        $maxIterations   = $request->getPostParameter("maxIterations", null);
        $kmPerIteration  = $request->getPostParameter("kmPerIteration", null);
        $exclusivityTime = $request->getPostParameter("exclusivityTime", null);

        try {

            if (is_null($maxIterations)) {
                throw new Exception("Se necesita la cantidad máxima de iteraciones", 1);                
            }

            if (is_null($kmPerIteration)) {
                throw new Exception("Se necesita los KM por iteración", 1);
            }

            if (is_null($exclusivityTime)) {
                throw new Exception("Se necesita el tiempo de exclusividad para el dueño", 1);
            }

            $oOC = Doctrine_Core::getTable("OpportunityConfig")->find(1);

            $oOC->setMaxIterations($maxIterations);
            $oOC->setKmPerIteration($kmPerIteration);
            $oOC->setExclusivityTime($exclusivityTime);
            $oOC->save();
        } catch (Exception $e) {
            $return["error"] = true;
            $return["errorMessage"] = $e->getMessage();
        }

        $this->renderText(json_encode($return));

        return sfView::NONE;
    }
}
