<?php

class NotificationStartDayReserveTask extends sfBaseTask {

    protected function configure() {

        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'frontend'),
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'local'),
            new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'doctrine')
        ));

        $this->namespace = 'notification';
        $this->name = 'StartDayReserve';
        $this->briefDescription = 'genera las notificaciones a los usuarios que comienzen con un arriendo hoy';
        $this->detailedDescription = <<<EOF
The [StartDayReserve|INFO] task does things.
Call it with:

  [php symfony notification:StartDayReserve|INFO]
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
        $dateNow = date("Y-m-d");

        foreach ($Reserves as $Reserve) {
            $reserveDate = date("Y-m-d", strtotime($Reserve->date));
            if($dateNow == $reserveDate){
                Notification::make($Reserve->getUser()->id, 5, $Reserve->id);
            }
        }
        
    }
}