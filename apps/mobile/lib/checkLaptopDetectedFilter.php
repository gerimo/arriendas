<?php 

/* 
 * Filtro para utilizar version mobile en caso de que se utilice un celular para acceder a la pagina
 */
require_once sfConfig::get('sf_lib_dir') . '/vendor/mobile-detect/Mobile_Detect.php';

class checkLaptopDetectedFilter extends sfFilter {

    public function execute ($filterChain) {
    
        $request = $this->getContext()->getRequest();
        $user    = $this->getContext()->getUser();
        $action  = $this->context->getActionName();
    
        if ($this->isFirstCall()
            && $action != "getExtendPrice"
            && $action != "paymentInformation"
            && $action != "notifyPayment") {

            $MD = new Mobile_Detect;
            $referer = $request->getUri();

            if (!$MD->isMobile()) {
                error_log("from MOBILE to DESKTOP");
                error_log("action: ".$action);
                $host = str_replace("m.arriendas.cl", "www.arriendas.cl", $_SERVER ['HTTP_HOST']);
                $url  = str_replace("m.arriendas.cl", "www.arriendas.cl", $referer);
                
                if($this->getContext()->getActionStack()->getSize() != null){
                    $this->getContext()->getController()->redirect($url);
                } else {
                    $this->getContext()->getController()->redirect('http://'.$host);
                }
                throw new sfStopException();
            }
        }
        // execute next filter
        $filterChain->execute();
    }
}
