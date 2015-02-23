<?php

class transactionActions extends sfActions {

	public function executeIndex(sfWebRequest $request) {
	}

	public function executeEditTransaction(sfWebRequest $request) {

		$return = array("error" => false);

        try {

            $transactionId    	= $request->getPostParameter("transactionId", null);
            $type         		= $request->getPostParameter("type", null);
            $option       		= $request->getPostParameter("option", null);

            $Transaction = Doctrine_Core::getTable('Transaction')->find($transactionId);
            if ($type == 1) {
                $Transaction->setIsPaidUser($option);
            } 

            $Transaction->save();

        } catch (Exception $e) {
            $return["error"]        = true;
            $return["errorCode"]    = $e->getCode();
            $return["errorMessage"] = $e->getMessage();
        }

        $this->renderText(json_encode($return));
        
        return sfView::NONE;
	}
}
