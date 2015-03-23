<?php

class mobileConfiguration extends sfApplicationConfiguration {

    protected $frontendRouting = null;

    public function configure() {}  
 
    public function generateFrontendUrl($name, $parameters = array()) {

        $host = "http://local.arriendas.cl";
        if ($_SERVER ['HTTP_HOST'] == "m.arriendas.cl") {
            $host = "http://www.arriendas.cl";
        } elseif ($_SERVER ['HTTP_HOST'] == "dev.m.arriendas.cl") {
            $host = "http://dev.arriendas.cl";
        }

        error_log("HOST: ".$host);

        return $host.$this->getFrontendRouting()->generate($name, $parameters);
    }

    public function getFrontendRouting() {

        if (!$this->frontendRouting) {
          $this->frontendRouting = new sfPatternRouting(new sfEventDispatcher());
     
          $config = new sfRoutingConfigHandler();
          $routes = $config->evaluate(array(sfConfig::get('sf_apps_dir').'/frontend/config/routing.yml'));
     
          $this->frontendRouting->setRoutes($routes);
        }
     
        return $this->frontendRouting;
    }
}
