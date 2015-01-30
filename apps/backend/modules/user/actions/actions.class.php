<?php

class userActions extends sfActions {
    
    public function executeIndex(sfWebRequest $request) {
    }

    public function executeWhitoutPay(sfWebRequest $request) {
        $this->holaMundo = "Hola Mundo!";
    }
}
