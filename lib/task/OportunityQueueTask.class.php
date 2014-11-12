<?php

class OportunityQueueTask extends sfBaseTask {

    protected function configure() {

        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name'),
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
            new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'doctrine'),
            new sfCommandOption('numberOfMails', null, sfCommandOption::PARAMETER_REQUIRED, 'Cantidad de correos', 16),
        ));

        $this->namespace = 'arriendas';
        $this->name = 'oportunityQueueTask';
        $this->briefDescription = '';
        $this->detailedDescription = <<<EOF
The [OportunityQueueTask|INFO] task does things.
Call it with:

  [php symfony arriendas:OportunityQueueTask|INFO]
EOF;
    }

    protected function execute($arguments = array(), $options = array()) {

        $config = ProjectConfiguration::getApplicationConfiguration("frontend", "prod", TRUE);
        sfContext::createInstance($config);

        // Este task debiera correr cada 10 minutos app

        // initialize the database connection
        $databaseManager = new sfDatabaseManager($this->configuration);
        $conn = $databaseManager->getDatabase($options['connection'])->getConnection();

        // Este task toma todas las reservas pagas que se hayan hecho y que esten pendientes para generar
        //  la lista de usuarios a los cuales se les debe enviar correos de "oportunidades"

        $maxIterations  = 5;
        $kmPerIteration = 1;
        $exclusivityTime = 20; // En minutos
        $baseKpi = 20; //en porcentaje

        // Se obtienen todas las reservas para procesar
        $table = Doctrine_Core::getTable('OportunityQueue');
        $q = $table
            ->createQuery('o')
            ->innerJoin('o.Reserve r')
            ->where('o.is_active IS TRUE')
            ->andWhere("DATE_ADD(o.paid_at, INTERVAL {$exclusivityTime} MINUTE) < NOW()")
            ->andWhere('o.iteration <= ?', $maxIterations)
            ;

        $elements = $q->execute();

        foreach ($elements as $element) {

            $table = Doctrine_Core::getTable('OportunityEmailQueue');
            $q = $table
                ->createQuery('o')
                ->select('o.*, count(o.id) AS total')
                ->where('o.reserve_id = ?', $element->getReserve()->getId())
                ->andWhere('o.sended_at IS NULL')
                ->groupBy('o.reserve_id')
                ;

            $result = $q->execute();

            // Esto es para evitar generar los registros para una reserva en la que aun no se envian todos los correos
            //  generados para la iteracion anterior
            if (isset($result[0]) && $result[0]->getTotal() > 0) {
                continue;
            }

            $desde = $element->getIteration() - 1;
            $hasta = $element->getIteration();

            $this->log("Calculando Usuarios para reserva {$element->getReserve()->getId()} entre {$desde} Km y {$hasta} Km");
            
            $table = Doctrine_Core::getTable('Car');
            $q = $table
                ->createQuery('c')
                ->innerJoin('c.Reserves r')
                ->where('distancia(?, ?, c.lat, c.lng) > ?', array($element->getReserve()->getCar()->getLat(), $element->getReserve()->getCar()->getLng(), $desde))
                ->andWhere('distancia(?, ?, c.lat, c.lng) <= ?',  array($element->getReserve()->getCar()->getLat(), $element->getReserve()->getCar()->getLng(), $hasta))
                ->groupBy('c.user_id')
                ->orderBy('c.ratio_aprobacion DESC')
                ;

            $cars = $q->execute();

            foreach($cars as $car) {

                if ($car->getKpi() > $baseKpi) {

                    $this->log("User: {$car->getUser()} Id: {$car->getUser()->getId()} KPI: {$car->getKpi()}");

                    $oportunityEmail = new OportunityEmailQueue();
                    $oportunityEmail->setOwner($car->getUser()); // Dueño del auto
                    $oportunityEmail->setRenter($element->getReserve()->getUser()); //Arendatario
                    $oportunityEmail->setReserve($element->getReserve());
                    $oportunityEmail->save();
                }
            }

            $element->setIteration($element->getIteration() + 1);
            $element->save();
        }
    }
}