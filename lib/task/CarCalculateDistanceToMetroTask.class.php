<?php

class CarCalculateDistanceToMetroTask extends sfBaseTask {

    protected function configure() {

        $this->addArguments(array(
            new sfCommandArgument('carId', sfCommandArgument::REQUIRED, 'ID del auto a calcular distancia', null),
        ));

        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'frontend'),
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
            new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'doctrine'),
            new sfCommandOption('all', null, sfCommandOption::PARAMETER_REQUIRED, 'Calculate for all the cars', 'no'),
        ));

        $this->namespace = 'car';
        $this->name = 'calculateDistanceToMetro';
        $this->briefDescription = 'Calcula la distancia de cada auto al Metro';
        $this->detailedDescription = <<<EOF
The [CarCalculateDistanceToMetro|INFO] task does things.
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

        

        try {

            $this->log("[".date("Y-m-d H:i:s")."] Procesando...");
            
            $count     = 0;
            $startTime = microtime(true);

            if ($options["all"] == "yes") {

                $Cars = Doctrine_Core::getTable("Car")->findAll();

                $conn->query("TRUNCATE TABLE CarProximityMetro");

                foreach ($Cars as $Car) {

                    CarProximityMetro::setNewCarProximityMetro($Car);
                    $count++;
                }

            } else {

                $Car = Doctrine_Core::getTable("Car")->find($arguments["carId"]);
                
                CarProximityMetro::setNewCarProximityMetro($Car);
                
                $count++;
            }

            $endTime = microtime(true);

            $this->log("[".date("Y-m-d H:i:s")."] Autos procesados: ".$count);
            $this->log("[".date("Y-m-d H:i:s")."] Tiempo total de procesamiento ".round($endTime-$startTime, 2)." segundos");

        } catch (Exeception $e) {
            error_log("[".date("Y-m-d H:i:s")."] ERROR: ".$e->getMessage());
        }
    }
}