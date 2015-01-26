<?php

class mainActions extends sfActions {

    public function executeIndex (sfWebRequest $request) {
        $this->msg = "Hola mundo!";
    }
}
