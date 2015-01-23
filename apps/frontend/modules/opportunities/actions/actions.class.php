<?php

class opportunitiesActions extends sfActions {

    public function executeIndex(sfWebRequest $request) {
        
        $this->setLayout("newIndexLayout");

        $userId = $this->getUser()->getAttribute("userid");
        $this->Opportunities = array();

        $User = Doctrine_Core::getTable('User')->find($userId);

        $this->Cars = Doctrine_Core::getTable('Car')->findByUserIdAndActivo($userId, true);

        foreach ($this->Cars as $Car) {

            foreach ($Car->getOpportunities() as $Op) {
                
                if(!isset($this->Opportunities[$Op->getId()])) {
                    $this->Opportunities[$Op->getId()] = array("Reserve" => $Op);
                }

                if (!in_array($this->Opportunities[$Op->getId()]["Cars"], $Car)) {
                    $this->Opportunities[$Op->getId()]["Cars"][] = $Car;
                }
            }
        }
    }

    public function executeApprove(sfWebRequest $request) {

        $return = array("error" => false);

        $reserveId = $request->getPostParameter("reserveId", null);
        $carId     = $request->getPostParameter("carId", null);

        try {

            if (is_null($reserveId) || $reserveId == 0) {
                throw new Exception("No se encontró la reserva", 1);
            }

            if (is_null($carId) || $carId == 0) {
                throw new Exception("No se encontró el auto", 1);
            }

            $Car = Doctrine_Core::getTable('Car')->find($carId);
            $OriginalReserve = Doctrine_Core::getTable('Reserve')->find($reserveId);

            if ($Car->hasReserve($OriginalReserve->getFechaInicio2(), $OriginalReserve->getFechaTermino2())) {
                throw new Exception("El auto seleccionado para postular ya posee una reserva en las fechas de la postulación", 1);                
            }

            if ($Car->getUserId() != $this->getUser()->getAttribute("userid")) {
                throw new Exception("No! No! No!", 1);
            }            

            $O = $OriginalReserve->copy(true);
            $O->setCar($Car);
            $O->setFechaReserva(date("Y-m-d H:i:s"));
            $O->setConfirmed(true);
            $O->setImpulsive(true);
            $O->setComentario('Reserva oportunidad');
            $O->setReservaOriginal($OriginalReserve->getId());
            $O->save();
            
            $O->setUniqueToken(true);
            $O->save();

            $OT = $OriginalReserve->getTransaction()->copy(true);
            $OT->setCar($Car->getModel()->getBrand()->getName() ." ". $Car->getModel()->getName());
            $OT->setReserve($O);
            $OT->setDate(date("Y-m-d H:i:s"));
            $OT->setCompleted(false);
            $OT->setImpulsive(true);
            $OT->setTransaccionOriginal($OriginalReserve->getTransaction()->getId());
            $OT->save();

        } catch(Exception $e) {
            $return["error"] = true;
            $return["errorMessage"] = $e->getMessage();

            if ($request->getHost() == "www.arriendas.cl") {
                Utils::reportError($e->getMessage(), "opportunities/approve");
            }
        }

        $this->renderText(json_encode($return));

        return sfView::NONE;
    }
}