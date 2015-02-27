<?php

class NotificationPayTwoHourWithoutConfirmationTask extends sfBaseTask {

    protected function configure() {

        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'frontend'),
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'local'),
            new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'doctrine')
        ));

        $this->namespace = 'notification';
        $this->name = 'PayTwoHourWithoutConfirmation';
        $this->briefDescription = 'genera las notificaciones a los propietarios que posean pagos son confirmar por 2 horas';
        $this->detailedDescription = <<<EOF
The [PayTwoHourWithoutConfirmation|INFO] task does things.
Call it with:

  [php symfony notification:PayTwoHourWithoutConfirmation|INFO]
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

        $Reserves = Doctrine_core::getTable("Reserve")->findByConfirmed(0);
        $dateNow = date("Y-m-d H:i:s");

        foreach ($Reserves as $Reserve) {
            if($Reserve->fecha_pago) {
                $reserveDate = date("Y-m-d H:i:s", strtotime($Reserve->fecha_pago));
                
                $diff = (strtotime($dateNow) - strtotime($reserveDate));

                    if($diff > 0) {
                        $years   = floor($diff / (365*60*60*24)); 
                        $months  = floor(($diff - $years * 365*60*60*24) / (30*60*60*24)); 
                        $days    = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24)/ (60*60*24));
                        $hours   = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24 - $days*60*60*24)/ (60*60)); 
                    }

                if($hours >= 2 && $hours < 3){
                    Notification::make($Car->getUser()->id, 12);
                }
            }
        }
        
    }
}