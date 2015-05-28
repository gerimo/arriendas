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

        $Cars = Doctrine_core::getTable("Car")->findBySeguroOkAndActivo(4,1);

        foreach ($Cars as $Car) {

            $Reserve = Doctrine_core::getTable("reserve")->findTheLastReserves($Car->id);

            $reserveDate = date("Y-m-d", strtotime('+2 month', strtotime($Reserve->fecha_reserva)));
            $dateNow = date('Y-m-d');

            if ($reserveDate == $dateNow) {
                $this->log($Car->id);
                $this->log($Reserve->id);
                Notification::make($Car->getUser()->id, 11);
            }
            
        }  
    }
}