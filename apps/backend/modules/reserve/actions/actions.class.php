<?php

class reserveActions extends sfActions {

	public function executeIndex(sfWebRequest $request) {
	}

    public function executeFortnightlyPayments(sfWebRequest $request) {

        $this->Banks = Doctrine_Core::getTable('Bank')->findAll();
        $this->BankAccountTypes = Doctrine_Core::getTable('BankAccountType')->findAll();
    }


    public function executeEditReserve(sfWebRequest $request) {

        $return = array("error" => false);

        try {

            $reserveId    = $request->getPostParameter("reserveId", null);
            $type         = $request->getPostParameter("type", null);
            $option       = $request->getPostParameter("option", null);

            $Reserve = Doctrine_Core::getTable('Reserve')->find($reserveId);
            if ($type == 1) {
                $Reserve->setIsWarrantyBack($option);
            } elseif ($type == 2) {
                $Reserve->setIsSinister($option);
            }

            $Reserve->save();

        } catch (Exception $e) {
            $return["error"]        = true;
            $return["errorCode"]    = $e->getCode();
            $return["errorMessage"] = $e->getMessage();
        }

        $this->renderText(json_encode($return));
        
        return sfView::NONE;
    }

    public function executeExpiredReserves(sfWebRequest $request) {
    }

	public function executeIsOriginalReserve(sfWebRequest $request) {

		$return = array("error" => false);

	    try {

	        $idReservaOriginal = $request->getPostParameter("idReservaOriginal", null);

	        if ($idReservaOriginal != "") {

	            $reserveId = Doctrine_Core::getTable('Reserve')->findOriginalReserve($idReservaOriginal);
	          
	            if ($reserveId == nul) {
	            	throw new Exception("Reserva no encontrada", 1);
	            	
	            } else {
	            	$return["original"] = $reserveId;
	            }

	        } 

	    } catch (Exception $e) {
	        $return["error"] = true;
	        $return["errorCode"] = $e->getCode();
	        $return["errorMessage"] = $e->getMessage();
	    }

	    $this->renderText(json_encode($return));
	    
	    return sfView::NONE;
	}

	public function executeProblematicReservations(sfWebRequest $request) {
	}

    public function executeFindExpiredReserves(sfWebRequest $request) {

        $return = array("error" => false);

        try {

            $from     = $request->getPostParameter("from", null);
            $to       = $request->getPostParameter("to", null);
            $amount   = $request->getPostParameter("amount", null);

            $return["data"] = array();

            $Reserves = Doctrine_Core::getTable('Reserve')->findExpiredReserves($from, $to, $amount);

            if(count($Reserves) == 0){
                throw new Exception("No se encuentran reservas", 1);
            }

            foreach($Reserves as $i => $Reserve){

                $User = $Reserve->getUser();
                $UserCar = $Reserve->getCar()->getUser();
                $Transaction = $Reserve->getTransaction();

                $return["data"][$i] = array(
                    'u_id'            => $User->id,
                    'u_fullname'      => ucfirst(strtolower($User->firstname.' '.$User->lastname)),
                    'u_telephone'     => $User->telephone,
                    'u_email'         => $User->email,
                    'uc_id'           => $UserCar->id,
                    'uc_fullname'     => ucfirst(strtolower($UserCar->firstname.' '.$UserCar->lastname)),
                    'uc_telephone'    => $UserCar->telephone,
                    'uc_email'        => $UserCar->email,
                    'r_id'            => $Reserve->id,
                    'r_date'          => $Reserve->date,
                    'r_duration'      => $Reserve->getFechaTermino2(),
                    'r_fortnightly'   => $Reserve->getFortnightlyPayment(), 
                    'r_price'         => number_format(round(($Reserve->price * 0.7)), 0, '', '.'),
                    'r_warranty'      => $Reserve->is_warranty_back,    
                    'r_sinister'      => $Reserve->is_sinister,
                    't_id'            => $Transaction->id,
                    't_is_paid_user'  => $Transaction->is_paid_user,
                    't_number'        => $Transaction->numero_factura
                );  
            }

        } catch (Exception $e) {
            $return["error"]        = true;
            $return["errorCode"]    = $e->getCode();
            $return["errorMessage"] = $e->getMessage();
        }

        $this->renderText(json_encode($return));
        
        return sfView::NONE;
    }

	public function executeFindProblematicReserves(sfWebRequest $request) {

		$return = array("error" => false);

        try {

            $limit = $request->getPostParameter("limit", null);

            $return["data"] = array();

            $Reserves = Doctrine_Core::getTable('Reserve')->findProblematicsReserve($limit);

            if(count($Reserves) == 0){
                throw new Exception("No se encuentran reservas", 1);
            }

            foreach($Reserves as $i => $Reserve){

                $Car = $Reserve->getCar();
                $UserPay = $Reserve->getUser();
                $UserOwned = $Car->getUser();
                $return["data"][$i] = array(
                    'r_id'        => $Reserve->id,
                    'r_date'      => $Reserve->getFechaInicio2(),
                    'r_date_end'  => $Reserve->getFechaTermino2(),
                    'up_id'       => $UserPay->id,
                    'up_fullname' => ucfirst(strtolower($UserPay->firstname.' '.$UserPay->lastname)),
                    'up_telephone'=> $UserPay->telephone,
                    'up_license'  => $UserPay->getFotoPerfilS3() ? $UserPay->getFotoPerfilS3()->getImageSize('lg') : $UserPay->driver_license_file,
                    'up_check'    => $UserPay->getFotoLicenciaS3() ? $UserPay->getFotoLicenciaS3()->getImageSize('lg') : $UserPay->chequeo_judicial,
                    'uo_id'       => $UserOwned->id,
                    'c_id'        => $Car->id
                );  
            }

        } catch (Exception $e) {
            $return["error"]        = true;
            $return["errorCode"]    = $e->getCode();
            $return["errorMessage"] = $e->getMessage();
        }

        $this->renderText(json_encode($return));
        return sfView::NONE;
	}

}
