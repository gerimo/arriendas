<?php
class changeRutToNewFormatTask extends sfBaseTask {

    protected function configure() {

        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'frontend'),
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
            new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'doctrine')
        ));

        $this->namespace = 'arriendas';
        $this->name = 'changeRutToNewFormat';
        $this->briefDescription = 'formatea el rut de los usuarios extraido de "rut_old" y los inserta el numero en "rut" como enteros y el digito verificador en "dv" como varchar';
        $this->detailedDescription = <<<EOF
The [changeRutToNewFormat|INFO] task does things.
Call it with:

  [php symfony arriendas:changeRutToNewFormat|INFO]
EOF;
    }

    protected function execute($arguments = array(), $options = array()) {

        $config = ProjectConfiguration::getApplicationConfiguration("frontend", "prod", TRUE);
        sfContext::createInstance($config);

        try {

            $this->log("[".date("Y-m-d H:i:s")."] Procesando...");

            $Users = Doctrine_Core::getTable("User")->findAll();
            $countCambiados=0;
            $countInvalidos=0;
            $countTotales=0;
            $startTime = microtime(true);
            
            foreach ($Users as $User) {$causa = 0;
                $countTotales++;
                $rut = Utils::isValidRUT($User->getRutOld());

                $dv     = substr($rut, -1);
                $number = substr($rut, 0, -1);

                if($rut) {

                    if(strlen($rut) < 8) {
                        $countInvalidos++;

                    } else {

                        if((!empty($dv) || !is_null($dv)) && (!is_null($number) || !empty($number))) {
                            $User->setRut($number);
                            $User->setRutDv($dv);
                            $User->save();  
                            $countCambiados++;
                        } else {

                            $countInvalidos++;

                        }      

                    }

                } else {
                    $countInvalidos++;          
                }
                $count++;
            }

            $endTime = microtime(true);
            $this->log("[".date("Y-m-d H:i:s")."] Total de rut's analizados:   ".$countTotales);
            $this->log("[".date("Y-m-d H:i:s")."] Total de rut's invalidos:    ".$countInvalidos);
            $this->log("[".date("Y-m-d H:i:s")."] Total de rut's modificados:  ".$countCambiados);
            $this->log("[".date("Y-m-d H:i:s")."] Tiempo total de procesamiento ".round($endTime-$startTime, 2)." segundos");

        } catch (Exeception $e) {
            error_log("[".date("Y-m-d H:i:s")."] ERROR: ".$e->getMessage());
        }
    }
}