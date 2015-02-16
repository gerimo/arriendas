<?php

class UserCheckBlockedLicenseToNotCheckedUsersTask extends sfBaseTask {

    protected function configure() {

        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name'),
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
            new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'doctrine'),
            new sfCommandOption('user', null, sfCommandOption::PARAMETER_REQUIRED, 'The user id', ''),
            new sfCommandOption('rut', null, sfCommandOption::PARAMETER_REQUIRED, 'The user rut', ''),
        ));

        $this->namespace = 'user';
        $this->name = 'CheckBlockedLicenseToNotCheckedUsers';
        $this->briefDescription = 'Verifica el estado de la licencia de conducir de los usuarios que no han sido chequeados';
        $this->detailedDescription = <<<EOF
The [CheckBlockedLicenseToNotCheckedUsers|INFO] task does things.
Call it with:

  [php symfony arriendas:CheckBlockedLicenseToNotCheckedUsers|INFO]
EOF;
    }

    protected function execute($arguments = array(), $options = array()) {

        $config = ProjectConfiguration::getApplicationConfiguration("frontend", "prod", TRUE);
        sfContext::createInstance($config);
        
        // initialize the database connection
        $databaseManager = new sfDatabaseManager($this->configuration);
        $connection = $databaseManager->getDatabase($options['connection'])->getConnection();

        $run = $options["rut"];
        $userid = $options["user"];

        $this->log("[".date("Y-m-d H:i:s")."] Procesando...");
        $startTime = microtime(true);

        $Users = Doctrine_Core::getTable('User')->findByChequeoLicencia(0);
        $dateNow = date('Y-m-d H:i:s');
        $scraperSrv = new ScraperService();


        foreach ($Users as $User) {
            
            $status = 0;
            $causa = '';

            if(!$User->getExtranjero()) {
                if(!empty($User->rut) && !empty($User->rut_dv)) {

                    $statusLicense = $scraperSrv->getLicenceStatus($User->getRutComplete());

                    $data = $statusLicense[1];
                    $title = $statusLicense[0];

                    if(count($title)>0 && count($data)) {
                        if(count($title) == count($data)){
                            if(count($title) == 3){
                                if(strlen($data[1])<5) {
                                    $status = 2;
                                } else {
                                    $status = 1;
                                }
                            } else {
                                if(strlen($data[2])>3) {
                                    $fecha = split(' ', substr($data[2], 2));
                                    $plitDate = split('/', $fecha[0]);
                                    $year = $plitDate[2];
                                    $month = $plitDate[1];
                                    $day = $plitDate[0];

                                    $date = date('Y-m-d G:i:s',strtotime($year."/".$month."/".$day." 00:00:00"));

                                    if((round(abs(strtotime($date)-strtotime($dateNow)))/86400) < 180) {
                                        $status = 3;
                                    } else {
                                        $status = 1;
                                    }
                                }
                            }
                        }
                    }

                }
            }
            /**
            * return: 
            *      0 - connection lost.
            *      1 - license OK.
            *      2 - without license.
            *      3 - blocked license.
            */
            try {
                switch ($status) {
                    case 1:
                        $User->setChequeoLicencia(true);
                        $User->save();
                        $causa = 'License OK';
                        break;
                    case 2:
                        $User->setChequeoLicencia(true);
                        $User->setBlockedLicense($dateNow);
                        $User->save();
                        $causa = 'Without License';
                        break;
                    case 3:
                        $User->setChequeoLicencia(true);
                        $User->setBlockedLicense($date);
                        $User->save();
                        $causa = 'Blocked License '.$date;
                        break;
                    
                    default:
                        $User->setChequeoLicencia(false);
                        $User->setBlockedLicense(null);  
                        $User->save();
                        $causa = 'Connection Lost';
                        break;
                }

            } catch(Exception $e) {
                $this->log($e->getMessage());
            }

            $usuario = str_pad(("ID: ".$User->getId()." (".$User->getFirstname()." ".$User->getLastname().")"), 50);
            $rut     = str_pad(("RUT: ".$User->getRutComplete()), 18);
            $this->log($usuario."".$rut."".$causa);
        }
        
    }

}
