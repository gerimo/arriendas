<?php

class errorActions extends sfActions {

    public function executeIndex(sfWebRequest $request) {
        $this->forward404();
    }

    public function execute404 (sfWebRequest $request) {
        $this->setLayout("newIndexLayout");
    }
}
