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

        $this->error = false;

        $reserveId = $request->getParameter("reserve_id", null);
        $carId     = $request->getParameter("car_id", null);
        $signature = $request->getParameter("signature", null);

        try {

            $Reserve = Doctrine_Core::getTable('Reserve')->find($reserveId);

            if ($Reserve) {
                if ($Reserve->getSignature() == $signature) {

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
                                throw new Exception($error, 1);
                            }
                        } else {
                            throw new Exception("Oportunidad ya aceptada", 2);
                        }
                } else {
                    throw new Exception("Firma no coincide", 1);
                }
            } else {
                throw new Exception("No se econtró la reserva", 1);
            }

        } catch ( Exception $e ) {
            $this->error = "ERROR: ".$e->getMessage();

            if ($request->getHost() == "www.arriendas.cl") {
                Utils::reportError($e->getMessage(), "opportunities/mailingApprove");
            }
        }

        $this->redirect('reserves');
    }

    private function approve($reserveId, $carId, $isMailing = false) {

        error_log("Reserva: ".$reserveId);
        error_log("Car: ".$carId);
        error_log("isMailing: ".$isMailing);

        try {

            if (is_null($reserveId) || $reserveId == 0) {
                throw new Exception("No se encontró la reserva", 1);
            }

            if (is_null($carId) || $carId == 0) {
                throw new Exception("Falta el ID del auto", 1);
            }

            $Car = Doctrine_Core::getTable('Car')->find($carId);
            if (!$Car) {
                throw new Exception("No se encontró el auto", 1);                
            }

            $OriginalReserve = Doctrine_Core::getTable('Reserve')->find($reserveId);            

            if ($Car->hasReserve($OriginalReserve->getFechaInicio2(), $OriginalReserve->getFechaTermino2())) {
                throw new Exception("Este auto ya posee una reserva en las fechas de la oportundiad", 1);                
            }

            // Comentado porque cuando se aprueba por correo no necesariamente debería estar logueado
            /*if ($Car->getUserId() != $this->getUser()->getAttribute("userid")) {
                throw new Exception("No! No! No!", 1);
            }*/
            
            $O = $OriginalReserve->copy(true);
            $O->setCar($Car);
            $O->setFechaReserva(date("Y-m-d H:i:s"));
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
            

        } catch (Exception $e) {
            error_log("[ERROR] opportunities/(private)approve: ". $e->getMessage());
            return $e->getMessage();
        }

        return false;
    }
}
