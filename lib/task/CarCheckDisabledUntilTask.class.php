<?php

class CarCheckDisabledUntilTask extends sfBaseTask {

    protected function configure() {

        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'frontend'),
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
            new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'doctrine')
        ));

        $this->namespace = 'car';
        $this->name = 'checkDisabledUntil';
        $this->briefDescription = 'Activa los autos pasados en la fecha de inactividad';
        $this->detailedDescription = <<<EOF
The [CarAvailability|INFO] task does things.
Call it with:

  [php symfony car:checkDisabledUntil|INFO]
EOF;
    }

    protected function execute($arguments = array(), $options = array()) {

        $config = ProjectConfiguration::getApplicationConfiguration("frontend", "prod", TRUE);
        sfContext::createInstance($config);

        // initialize the database connection
        $databaseManager = new sfDatabaseManager($this->configuration);
        $conn = $databaseManager->getDatabase($options['connection'])->getConnection();

        try {

            $today = date("Y-m-d");

            $this->log("Buscando autos para activar con fecha ".$today);
            $oCars = Doctrine_Core::getTable("Car")->findDisabledCars($today);

            if (count($oCars) > 0) {

                $this->log("Autos para activar: ".count($oCars));

                foreach ($oCars as $oCar) {

                    $this->log("Activando Car ".$oCar->id." Fecha activacion: ".$oCar->disabled_until);
                    $oCar->setActivo(true);
                    $oCar->setDisabledUntil(null);
                    $oCar->save();
                }
            } else {
                $this->log("Con fecha ".$today." no hay Cars para activar");
            }


        } catch (Exeception $e) {

            Utils::reportError($e->getMessage(), "CarCheckDisabledUntilTask");
        }
    }
}