<?php

class CalculateDistanceBetweenCarAndMetroTask extends sfBaseTask {

    protected function configure() {

        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'frontend'),
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
            new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'doctrine')
        ));

        $this->namespace = 'car';
        $this->name = 'calculateDistanceToMetro';
        $this->briefDescription = 'Calcula la distancia de cada auto al Metro';
        $this->detailedDescription = <<<EOF
The [CalculateDistanceBetweenCarAndMetro|INFO] task does things.
Call it with:

  [php symfony car:calculateDistanceToMetro|INFO]
EOF;
    }

    protected function execute($arguments = array(), $options = array()) {

        $config = ProjectConfiguration::getApplicationConfiguration("frontend", "prod", TRUE);
        sfContext::createInstance($config);

        // initialize the database connection
        $databaseManager = new sfDatabaseManager($this->configuration);
        $conn = $databaseManager->getDatabase($options['connection'])->getConnection();

        $conn->query("TRUNCATE TABLE CarProximityMetro");

        try {

            $this->log("[".date("Y-m-d H:i:s")."] Procesando...");

            $Cars = Doctrine_Core::getTable("Car")->findAll();

            $count = 0;
            $startTime = microtime(true);
            
            foreach ($Cars as $Car) {

                CarProximityMetro::setNewCarProximityMetro($Car);
                $count++;
            }

            $endTime = microtime(true);

            $this->log("[".date("Y-m-d H:i:s")."] Autos procesados: ".$count);
            $this->log("[".date("Y-m-d H:i:s")."] Tiempo total de procesamiento ".round($endTime-$startTime, 2)." segundos");

        } catch (Exeception $e) {
            error_log("[".date("Y-m-d H:i:s")."] ERROR: ".$e->getMessage());
            /*Utils::reportError($e->getMessage(), "CalculateDistanceBetweenCarAndMetroTask");*/
        }
    }
}