<?php

class opportunityActions extends sfActions {

    public function executeIndex(sfWebRequest $request) {

        $this->oOC = Doctrine_Core::getTable("OpportunityConfig")
            ->createQuery('OC')->fetchOne();

        $this->log = shell_exec("head -n 500 ../log/opportunityGenerate.log | sort -r");
    }

    public function executeCreate(sfWebRequest $request) {
    }

    public function executeKpiRefresh (sfWebRequest $request) {
        
        $return = array("error" => false);

        $from = $request->getPostParameter("from", null);
        $to   = $request->getPostParameter("to", null);

        try {

            // KP1 = Arriendos por OPP / Total arriendos
            $return["kpi1"] = BI::OppKPI1($from, $to);
            $return["kpi2"] = BI::OppKPI2($from, $to);
            $return["kpi3"] = BI::OppKPI3($from, $to);
            $return["kpi4"] = BI::OppKPI4($from, $to);
            $return["kpi5"] = BI::OppKPI5($from, $to);
            $return["kpi6"] = BI::OppKPI6($from, $to);

        } catch (Exception $e) {
            $return["error"] = true;
            $return["errorMessage"] = $e->getMessage();
        }

        $this->renderText(json_encode($return));

        return sfView::NONE;
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
