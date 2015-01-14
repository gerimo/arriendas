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

            if ($Car->getUserId() != $this->getUser()->getAttribute("userid")) {
                throw new Exception("No! No! No!", 1);
            }

            $OriginalReserve = Doctrine_Core::getTable('Reserve')->find($reserveId);            

            $O = $OriginalReserve->copy(true);
            $O->setCar($Car);
            $O->setUser($Car->getUser());
            $O->setFechaReserva(date("Y-m-d H:i:s"));
            $O->setConfirmed(false);
            $O->setImpulsive(true);
            $O->setComentario('Reserva oportunidad');
            $O->setReservaOriginal($OriginalReserve->getId());
            $O->setNumeroFactura(0);
            $O->save();

            $OT = $OriginalReserve->getTransaction()->copy(true);
            $OT->setCar($Car->getModel()->getBrand()->getName() ." ". $Car->getModel()->getName());
            $OT->setUser($Car->getUser());
            $OT->setReserve($O);
            $OT->setCompleted(false);
            $OT->setImpulsive(true);
            $OT->setTransaccionOriginal($OriginalReserve->getTransaction()->getId());
            $OT->setNumeroFactura(0);
            $OT->save();

        } catch(Exception $e) {
            $return["error"] = true;
            $return["errorMessage"] = $e->getMessage();

            /*Utils::reportError($e->getMessage(), "opportunities/approve");*/
        }

        $this->renderText(json_encode($return));

        return sfView::NONE;
    }
}
