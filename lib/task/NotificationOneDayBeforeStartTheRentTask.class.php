<?php

class NotificationOneDayBeforeStartTheRentTask extends sfBaseTask {

    protected function configure() {

        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'frontend'),
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'local'),
            new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'doctrine')
        ));

        $this->namespace = 'notification';
        $this->name = 'OneDayBeforeStartTheRent';
        $this->briefDescription = 'genera las notificaciones a los ausuarios un dia antes de comenzar el arriendo';
        $this->detailedDescription = <<<EOF
The [AtTheBeginningOfTheReserve|INFO] task does things.
Call it with:

  [php symfony notification:AtTheBeginningOfTheReserve|INFO]
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

        $Reserves = Doctrine_core::getTable("Reserve")->findPaidReserves();
        $dateNow = date("Y-m-d H:i:s");

        foreach ($Reserves as $Reserve) {
            $reserveDate = date("Y-m-d H:i:s", strtotime($Reserve->date));
            
            $diff = (strtotime($dateNow) - strtotime($reserveDate));

            if($diff < 0) {
                $years   = floor($diff / (365*60*60*24)); 
                $months  = floor(($diff - $years * 365*60*60*24) / (30*60*60*24)); 
                $days    = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24)/ (60*60*24));
            }

            if($days = 1){
                Notification::make($Reserve->getUser()->id, 16, $Reserve->id);
            }
        }
        
    }
}