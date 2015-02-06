<?php

class backendConfiguration extends sfApplicationConfiguration {

    protected $frontendRouting = null;

    public function configure() {}

    public function generateFrontendUrl($name, $parameters = array()) {

        $host = "local.arriendas.cl";
        if ($_SERVER ['HTTP_HOST'] == "backend.arriendas.cl") {
            $host = "arriendas.cl";
        } elseif ($_SERVER ['HTTP_HOST'] == "dev.backend.arriendas.cl") {
            $host = "dev.arriendas.cl";
        }

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