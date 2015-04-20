<?php

class NotificationCarsWithoutReservesForTwoMonthsTask extends sfBaseTask {

    protected function configure() {

        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'frontend'),
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'local'),
            new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'doctrine')
        ));

        $this->namespace = 'notification';
        $this->name = 'CarsWithoutReservesForTwoMonths';
        $this->briefDescription = 'genera las notificaciones a los propietarios que no hayan recibido arriendos en los ultimos 2 meses';
        $this->detailedDescription = <<<EOF
The [CarsWithoutReservesForTwoMonths|INFO] task does things.
Call it with:

  [php symfony notification:CarsWithoutReservesForTwoMonths|INFO]
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

        $Cars = Doctrine_core::getTable("Car")->findCarsWithoutReserves();
        $dateNow = date("Y-m-d H:i:s");

        foreach ($Cars as $Car) {
            $carDate = date("Y-m-d H:i:s", strtotime($Car->fecha_subida));
            
            $diff = (strtotime($dateNow) - strtotime($carDate));

            if($diff > 0) {
                $years   = floor($diff / (365*60*60*24)); 
                $months  = floor(($diff - $years * 365*60*60*24) / (30*60*60*24)); 
                $days    = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24)/ (60*60*24));
                $hours   = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24 - $days*60*60*24)/ (60*60)); 
                $minuts  = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24 - $days*60*60*24 - $hours*60*60)/ 60); 
            }

            if($months == 2){
                Notification::make($Reserve->getUser()->id, 11, $Reserve->id);
            }
        }
        
    }
}