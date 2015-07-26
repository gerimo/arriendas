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

        foreach ($Cars as $Car) {

            $dateCar = date("Y-m-d", strtotime('+10 days', strtotime($Car->fecha_subida)));
            $dateNow = date('Y-m-d');

            if($dateNow == $dateCar){
                Notification::make($Car->getUser()->id, 12);
            }
        }
    }
}