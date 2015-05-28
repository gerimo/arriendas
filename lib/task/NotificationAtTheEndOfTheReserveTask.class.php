<?php

class NotificationAtTheEndOfTheReserveTask extends sfBaseTask {

    protected function configure() {

        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'frontend'),
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'local'),
            new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'doctrine')
        ));

        $this->namespace = 'notification';
        $this->name = 'AtTheEndOfTheReserve';
        $this->briefDescription = 'genera las notificaciones a los propietarios que hayan finalizado su primer arriendo hace 30 min';
        $this->detailedDescription = <<<EOF
        
        The [AtTheEndOfTheReserve|INFO] task does things.
        Call it with:
        [php symfony notification:AtTheEndOfTheReserve|INFO]
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

        foreach ($Reserves as $Reserve) {

            $endReserveDate = date("Y-m-d H:i", strtotime('-30 minute', strtotime($Reserve->getFechaTermino2())));
            $startReserveDate = date("Y-m-d H:i", strtotime($Reserve->getFechaInicio2()));
            $beforeReserveDate = date("Y-m-d H:i", strtotime('-1 days', strtotime($Reserve->getFechaInicio2())));
            $twoHourAfterReserveDate = date("Y-m-d H:i", strtotime('+2 hour', strtotime($Reserve->getFechaInicio2())));
            $dateNow = date('Y-m-d H:i');

            if ($endReserveDate == $dateNow) {
                Notification::make($Reserve->getUser()->id, 10, $Reserve->id);
            }

            if($startReserveDate == $dateNow) { 
                Notification::make($Reserve->getUser()->id, 9,$Reserve->id);
            }

            if($beforeReserveDate == $dateNow) {
                Notification::make($Reserve->getUser()->id, 14,$Reserve->id);
            }

            if($twoHourAfterReserveDate == $dateNow) {
                Notification::make($Reserve->getUser()->id, 15,$Reserve->id);
            }
        }
        
    }
}