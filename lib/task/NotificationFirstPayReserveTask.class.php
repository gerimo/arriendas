<?php

class NotificationFirstPayReserveTask extends sfBaseTask {

    protected function configure() {

        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'frontend'),
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'local'),
            new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'doctrine')
        ));

        $this->namespace = 'notification';
        $this->name = 'FirstPayReserve';
        $this->briefDescription = 'Busca a los usuario que hayan registrado una reserva sin haberla pagado';
        $this->detailedDescription = <<<EOF
        The [FirstPayReserve|INFO] task does things.
        Call it with:

        [php symfony notification:FirstPayReserve|INFO]
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

        $Reserves = Doctrine_core::getTable("Reserve")->findReserves(50);
        $date = date("Y-m-d H:i:s", strtotime("2014-01-01"));


        foreach ($Reserves as $Reserve) {
            $User = $Reserve->getUser()->getId();
            $Car = $Reserve->getCar();
            $UserCar = $Car->getUser()->getId();   

            //Primera Reserva 
            if (Reserve::IsFirstReserve($User)) {

                $Notification = Doctrine_core::getTable("UserNotification")->findByUserIdAndNotificationId($User, 85);

                if (count($Notification) == 0) { 
                    Notification::make($User, 2, $Reserve->id);
                }   
            }

            //Primer pago quinsenal 
            
            if ($Car->getQuantityOfLatestRents($date) == 1) {

                $Notification = Doctrine_core::getTable("UserNotification")->findByUserIdAndNotificationId($UserCar, 118);

                if (count($Notification) == 0)   { 
                    Notification::make($UserCar, 13, $Reserve->id);
                }   
            }
        }
    }
}