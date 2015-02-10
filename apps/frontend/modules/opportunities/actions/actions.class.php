<?php

class opportunitiesActions extends sfActions {

    public function executeIndex(sfWebRequest $request) {
        
        $this->setLayout("newIndexLayout");

        $userId = $this->getUser()->getAttribute("userid");
        $this->Opportunities = array();

        $User = Doctrine_Core::getTable('User')->find($userId);

        $this->Cars = Doctrine_Core::getTable('Car')->findByUserIdAndActivoAndSeguroOk($userId, true, 4);

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

            $error = $this->approve($reserveId, $carId);

            if ($error) {
                throw new Exception($error, 1);
            }

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

    public function executeMailingApprove(sfWebRequest $request) {

        $this->setLayout("newIndexLayout");

        $this->isError = false;
        $this->message = null;

        $reserveId = $request->getParameter("reserve_id", null);
        $carId     = $request->getParameter("car_id", null);
        $signature = $request->getParameter("signature", null);

        try {

            $Reserve = Doctrine_Core::getTable('Reserve')->find($reserveId);
            if (!$Reserve) {
                throw new Exception("Reserve no encontrada");
            }

            if ($Reserve->getSignature() != $signature) {
                throw new Exception("Firma para aprobar Reserve ".$reserveId." no coincide");
            }

            $already = false;

            $ChangeOptions = $Reserve->getChangeOptions();

            foreach ($ChangeOptions as $CO) {
                if ($carId == $CO->getCar()->id) {
                    $already = true;
                    break;
                }
            }

            if (!$already) {
                $error = $this->approve($reserveId, $carId, true);
                if ($error) {
                    throw new Exception($error);
                }
            }

            $this->message = "Oportunidad aprobada";

        } catch (Exception $e) {

            $this->isError = true;
            $this->message = "Ha habido un problema aprobando la Oportunidad. El equipo ha sido notificado";

            error_log("[".date("Y-m-d H:i:s")."] [opportunities/mailingApprove] ERROR: ".$e->getMessage());

            if ($request->getHost() == "www.arriendas.cl") {
                Utils::reportError($e->getMessage(), "opportunities/mailingApprove");
            }
        }
    }

    public function executeMailingOpen(sfWebRequest $request) {

        $opportunityEmailQueueId        = $request->getParameter('id');
        $opportunityEmailQueueSignature = $request->getParameter('signature');

        try {

            $OpportunityEmailQueue = Doctrine_Core::getTable('OpportunityEmailQueue')->find($opportunityEmailQueueId);

            if (!$OpportunityEmailQueue) {
                throw new Exception("OpportunityEmailQueue no encontrada", 2);
            }

            if ($OpportunityEmailQueue->getSignature() != $opportunityEmailQueueSignature) {
                throw new Exception("La firma para OpportunityEmailQueue ".$opportunityEmailQueueId." no coincide", 2);
            }

            if (is_null($OpportunityEmailQueue->getOpenedAt())) {
                $OpportunityEmailQueue->setOpenedAt(date("Y-m-d H:i:s"));
                $OpportunityEmailQueue->save();
            }
        } catch (Exception $e) {
            error_log("[".date("Y-m-d H:i:s")."] [opportunity/mailingOpen] ERROR: ".$e->getMessage());
            if ($request->getHost() == "www.arriendas.cl" && $e->getCode() < 2) {
                Utils::reportError($e->getMessage(), "opportunity/mailingOpen");
            }
        }

        $this->getResponse()->setContentType('image/gif');

        echo base64_decode("R0lGODlhAQABAIAAAP///////yH+EUNyZWF0ZWQgd2l0aCBHSU1QACwAAAAAAQABAAACAkQBADs=");

        return sfView::NONE;
    }

    private function approve($reserveId, $carId, $isMailing = false) {

        error_log("reserveId: ".$reserveId);
        error_log("carId: ".$carId);

        if (is_null($reserveId) || $reserveId == 0) {
            return "No se encontró la reserva";
        }

        $OriginalReserve = Doctrine_Core::getTable('Reserve')->find($reserveId);

        if (is_null($carId) || $carId == 0) {
            return "Falta el ID del auto para aprobar oportunidad para Reserve ".$OriginalReserve->id;
        }

        $Car = Doctrine_Core::getTable('Car')->find($carId);
        if (!$Car) {
            return "No se encontró el auto para aprobar oportunidad para Reserve ".$OriginalReserve->id;
        }            

        if ($Car->hasReserve($OriginalReserve->getFechaInicio2(), $OriginalReserve->getFechaTermino2())) {
            return "Este Car ".$Car->id." ya posee una reserva en las fechas de la oportundiad";
        }

        $O = $OriginalReserve->copy(true);
        $O->setCar($Car);
        $O->setFechaReserva(date("Y-m-d H:i:s"));
        $O->setFechaConfirmacion(date("Y-m-d H:i:s"));
        $O->setConfirmed(true);
        $O->setImpulsive(true);
        $O->setReservaOriginal($OriginalReserve->getId());

        if ($isMailing) {
            $O->setComentario('Reserva oportunidad - mailing');
        } else {
            $O->setComentario('Reserva oportunidad');
        }
        
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

        return null;
    }
}
