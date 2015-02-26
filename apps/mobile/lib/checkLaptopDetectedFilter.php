<?php 

/* 
 * Filtro para utilizar version mobile en caso de que se utilice un celular para acceder a la pagina
 */

class checkLaptopDetectedFilter extends sfFilter {
    public function execute ($filterChain) {
    
        $request = $this->getContext()->getRequest();
        $user  = $this->getContext()->getUser();
        $action = $this->context->getActionName();

        if ($this->isFirstCall()) {
            $MD = new Mobile_Detect;
            if (!$MD->isMobile()) {
                error_log("[De mobile-> laptop]Me voy a http://".$host);
                $host = str_replace("m.arriendas.cl", "arriendas.cl", $_SERVER ['HTTP_HOST']);
                $this->getContext()->getController()->redirect('http://'.$host);
                /*$this->redirect('http://www.google.com/');*/
                throw new sfStopException();
            }
        }
        // execute next filter
        $filterChain->execute();
    }
}

?>