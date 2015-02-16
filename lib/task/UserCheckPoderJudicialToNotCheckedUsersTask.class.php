<?php
require_once sfConfig::get('sf_lib_dir') . '/vendor/fabpot/goutte.phar';

class UserCheckPoderJudicialToNotForeignTask extends sfBaseTask {

    protected function configure() {

        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'frontend'),
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'local'),
            new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'doctrine')
        ));

        $this->namespace = 'user';
        $this->name = 'checkPoderJudicialForNotForeignUsers';
        $this->briefDescription = 'Analiza los rut de los usuarios que no han sido chequeados';
        $this->detailedDescription = <<<EOF
The [checkCausasJudicialesToNotForeign|INFO] task does things.
Call it with:

  [php symfony user:userCheckPoderJudicialToNotForeign|INFO]
EOF;
    }

    protected function execute($arguments = array(), $options = array()) {

        $config = ProjectConfiguration::getApplicationConfiguration("frontend", "prod", TRUE);
        sfContext::createInstance($config);

        try {

            $client = new \Goutte\Client();
            // Objtiene la key
            $crawler = $client->request('GET', 'http://reformaprocesal.poderjudicial.cl/ConsultaCausasJsfWeb/page/panelConsultaCausas.jsf');
            $viewStateId = $crawler->filter('input[name="javax.faces.ViewState"]')->attr('value');



            $this->log("[".date("Y-m-d H:i:s")."] Procesando...");

            $Users = Doctrine_Core::getTable("User")->findByChequeoJudicial(0);

            $countTotalUsuariosAlPrincipio = count($Users);
            $countProblemasConexion=0;
            $countSinCausas=0;
            $countConCausas=0;
            $countRutInvalido=0;
            $countTotalUsuariosChequeados=0;
            
            $startTime = microtime(true);
            foreach ($Users as $User) {

                $nodeCount = 0;
                $problems = "";
                
                if(Utils::isValidRUT($User->getRutComplete())) {

                    if (strlen($viewStateId) > 0) {

                        /* verification call */
                        $params = array(
                            'formConsultaCausas:idFormRut' => $User->rut,
                            'formConsultaCausas:idFormRutDv' => strtoupper($User->rut_dv),
                            'formConsultaCausas:idSelectedCodeTribunalRut' => "0",
                            'formConsultaCausas:buscar1.x' => "66",
                            'formConsultaCausas:buscar1.y' => "19",
                            'formConsultaCausas' => "formConsultaCausas",
                            'javax.faces.ViewState' => $viewStateId,
                        );

                        $crawler = $client->request('POST', 'http://reformaprocesal.poderjudicial.cl/ConsultaCausasJsfWeb/page/panelConsultaCausas.jsf', $params);
                        $nodeCount = count($crawler->filter('.extdt-cell-div'));

                        if ($nodeCount > 1) {
                            $User->setBlocked(true);
                            $User->setChequeoJudicial(true);
                            $countTotalUsuariosChequeados++;
                            $countConCausas++;
                            $causa = "blocked by criminal records";
                        } else {
                            $User->setBlocked(false);
                            $User->setChequeoJudicial(true);
                            $countTotalUsuariosChequeados++;
                            $countSinCausas++;
                            $causa = "free of criminal records";
                        }
                                        
                    } else {
                        $User->setChequeoJudicial(false);
                        $countProblemasConexion++;
                        $causa = "connection fail";
                        $problems = "Connection lost";
                    }
                    $User->save();

                } else {
                    $User->setChequeoJudicial(false);
                    $problems = "Invalid RUT";
                    $countRutInvalido++;
                }
                $countTotal++;

                $usuario = str_pad(("ID: ".$User->getId()." (".$User->getFirstname()." ".$User->getLastname().")"), 50);
                $rut     = str_pad(("RUT: ".$User->getRutComplete()), 18);
                $causas   = str_pad(("Causas: ".($nodeCount/6)), 13);
                $this->log($usuario."".$rut."".$causas."".$problems);
                //$this->log("ID: ".$User->getId()."(".$User->getFirstname()." ".$User->getLastname().") RUT: ".$User->getRutComplete()."  causas: ".($nodeCount/6));

            }

            $endTime = microtime(true);

            $this->log("[".date("Y-m-d H:i:s")."] Total usuarios obtenidos sin chequeo: ".$countTotalUsuariosAlPrincipio);
            $this->log("[".date("Y-m-d H:i:s")."] ");
            $this->log("[".date("Y-m-d H:i:s")."] ");
            $this->log("[".date("Y-m-d H:i:s")."] Total usuarios Chequeados:            ".$countTotalUsuariosChequeados);
            $this->log("[".date("Y-m-d H:i:s")."] Usuarios con causas:      ".$countConCausas);
            $this->log("[".date("Y-m-d H:i:s")."] Usuarios sin Causas:      ".$countSinCausas);
            $this->log("[".date("Y-m-d H:i:s")."] ");
            $this->log("[".date("Y-m-d H:i:s")."] ");
            $this->log("[".date("Y-m-d H:i:s")."] Total no chequeados por rut invalido: ".$countRutInvalido);
            $this->log("[".date("Y-m-d H:i:s")."] ");
            $this->log("[".date("Y-m-d H:i:s")."] ");
            $this->log("[".date("Y-m-d H:i:s")."] Problemas de Conexión:                ".$countProblemasConexion);
            $this->log("[".date("Y-m-d H:i:s")."] ");
            $this->log("[".date("Y-m-d H:i:s")."] Tiempo total de procesamiento         ".round($endTime-$startTime, 2)." segundos");

        } catch (Exeception $e) {
            error_log("[".date("Y-m-d H:i:s")."] ERROR: ".$e->getMessage());
        }
    }
}