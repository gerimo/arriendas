<?php

class NotificationCarsWithoutVerificationTask extends sfBaseTask {

    protected function configure() {

        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'frontend'),
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'local'),
            new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'doctrine')
        ));

        $this->namespace = 'notification';
        $this->name = 'CarsWithoutVerification';
        $this->briefDescription = 'genera las notificaciones a los usuarios que posean autos sin verificacion por mas de 10 dias';
        $this->detailedDescription = <<<EOF
The [CarsWithoutVerification|INFO] task does things.
Call it with:

  [php symfony notification:CarsWithoutVerification|INFO]
EOF;
    }



    protected function execute($arguments = array(), $options = array()) {

        $config = ProjectConfiguration::getApplicationConfiguration("frontend", "prod", TRUE);
        sfContext::createInstance($config);
        $context = sfContext::createInstance($this->configuration);
        $context->getConfiguration()->loadHelpers('Partial');

        // initialize the database connection
        $databaseManager = new sfDatabaseManager($this->configuration);
        $connection = $databaseManager->getDatabase($options['connection'])->getConnection();

        $Cars = Doctrine_core::getTable("Car")->findByNotSeguroOk();
        $dateNow = date("Y-m-d H:i:s");

        foreach ($Cars as $Car) {
            $carDate = date("Y-m-d H:i:s", strtotime($Car->fecha_subida));
            
            $diff = abs(strtotime($dateNow) - strtotime($carDate));

                $years   = floor($diff / (365*60*60*24)); 
                $months  = floor(($diff - $years * 365*60*60*24) / (30*60*60*24)); 
                $days    = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24)/ (60*60*24));

            if($days == 10){
                Notification::make($Car->getUser()->id, 12);
            }
        }
        
    }
}