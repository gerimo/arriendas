<?php
require_once sfConfig::get('sf_lib_dir') . '/vendor/fabpot/goutte.phar';

class rutAnalyzerTask extends sfBaseTask {

    protected function configure() {

        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'frontend'),
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
            new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'doctrine')
        ));

        $this->namespace = 'arriendas';
        $this->name = 'rutAnalyzer';
        $this->briefDescription = 'Analiza los rut de los usuarios';
        $this->detailedDescription = <<<EOF
The [rutAnalyzer|INFO] task does things.
Call it with:

  [php symfony arriendas:rutAnalyzer|INFO]
EOF;
    }

    protected function execute($arguments = array(), $options = array()) {

        $config = ProjectConfiguration::getApplicationConfiguration("frontend", "prod", TRUE);
        sfContext::createInstance($config);

        try {

            $this->log("[".date("Y-m-d H:i:s")."] Procesando...");

            $Users = Doctrine_Core::getTable("User")->findAll();

            $countChilenosSinRut=0;
            $countChilenosConRutInvalido=0;
            $countChilenosConRutValido=0;
            $countExtranjerosSinRut=0;
            $countExtranjerosConRutInvalido=0;
            $countExtranjerosConRut=0;
            $countRutsTotales=0;
            $countTotalChilenos=0;
            $countTotalExtranjeros=0;
            $countChilenosConRut=0;
            $countExtranjerosConRutValido=0;
            $startTime = microtime(true);
            
            foreach ($Users as $User) {

                if(!$User->getExtranjero()) {

                    if(!$User->getRut()) {

                        $countChilenosSinRut++;

                    } else {
                        $countChilenosConRut++;
                        if(!Utils::isValidRUT($User->getRut())) {

                            $countChilenosConRutInvalido++;

                        } else {

                            $countChilenosConRutValido++;

                        }
                        
                    }
                    $countTotalChilenos++;

                } else {

                    if(!$User->getRut()) {

                        $countExtranjerosSinRut++;

                    } else {
                        if(!Utils::isValidRUT($User->getRut())) {

                            $countExtranjerosConRutInvalido++;

                        } else {
                            $countExtranjerosConRutValido++;
                        }

                        $countExtranjerosConRut++;
                    }

                    $countTotalExtranjeros++;

                }
                $countRutsTotales++;
            }

            $endTime = microtime(true);

            $this->log("[".date("Y-m-d H:i:s")."] ----------------------------------------");
            $this->log("[".date("Y-m-d H:i:s")."] ----------------------------------------");
            $this->log("[".date("Y-m-d H:i:s")."] Usuarios analizados:          |  ".$countRutsTotales);
            $this->log("[".date("Y-m-d H:i:s")."] ----------------------------------------");
            $this->log("[".date("Y-m-d H:i:s")."] ----------------------------------------");
            $this->log("[".date("Y-m-d H:i:s")."] ");
            $this->log("[".date("Y-m-d H:i:s")."] ----------------------------------------");
            $this->log("[".date("Y-m-d H:i:s")."] Total chilenos:               |  ".$countTotalChilenos);
            $this->log("[".date("Y-m-d H:i:s")."] ----------------------------------------");
            $this->log("[".date("Y-m-d H:i:s")."] ----------------------------------------");
            $this->log("[".date("Y-m-d H:i:s")."] chilenos sin rut:             |  ".$countChilenosSinRut);
            $this->log("[".date("Y-m-d H:i:s")."] ----------------------------------------");
            $this->log("[".date("Y-m-d H:i:s")."] ----------------------------------------");
            $this->log("[".date("Y-m-d H:i:s")."] chilenos con rut:             |  ".$countChilenosConRut);
            $this->log("[".date("Y-m-d H:i:s")."] ----------------------------------------");
            $this->log("[".date("Y-m-d H:i:s")."] chilenos con rut valido:      |  ".$countChilenosConRutValido);
            $this->log("[".date("Y-m-d H:i:s")."] chilenos con rut invalido:    |  ".$countChilenosConRutInvalido);
            $this->log("[".date("Y-m-d H:i:s")."] ----------------------------------------");
            $this->log("[".date("Y-m-d H:i:s")."] ");
            $this->log("[".date("Y-m-d H:i:s")."] ");
            $this->log("[".date("Y-m-d H:i:s")."] ----------------------------------------"); 
            $this->log("[".date("Y-m-d H:i:s")."] Total extranjeros:            |  ".$countTotalExtranjeros);
            $this->log("[".date("Y-m-d H:i:s")."] ----------------------------------------");
            $this->log("[".date("Y-m-d H:i:s")."] ----------------------------------------");
            $this->log("[".date("Y-m-d H:i:s")."] extranjeros sin rut:          |  ".$countExtranjerosSinRut);
            $this->log("[".date("Y-m-d H:i:s")."] ----------------------------------------");
            $this->log("[".date("Y-m-d H:i:s")."] ----------------------------------------");
            $this->log("[".date("Y-m-d H:i:s")."] extranjeros con rut:          |  ".$countExtranjerosConRut);
            $this->log("[".date("Y-m-d H:i:s")."] ----------------------------------------");
            $this->log("[".date("Y-m-d H:i:s")."] extranjeros con rut valido:   |  ".$countExtranjerosConRutValido);
            $this->log("[".date("Y-m-d H:i:s")."] extranjeros con rut invalido: |  ".$countExtranjerosConRutInvalido);
            $this->log("[".date("Y-m-d H:i:s")."] ----------------------------------------");

            $this->log("[".date("Y-m-d H:i:s")."] Tiempo total de procesamiento       ".round($endTime-$startTime, 2)." segundos");

        } catch (Exeception $e) {
            error_log("[".date("Y-m-d H:i:s")."] ERROR: ".$e->getMessage());
        }
    }
}