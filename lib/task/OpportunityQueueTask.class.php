<?php

class OpportunityQueueTask extends sfBaseTask {

    protected function configure() {

        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name'),
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
            new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'doctrine'),
        ));

        $this->namespace = 'opportunities';
        $this->name = 'send';
        $this->briefDescription = 'Envío de oportunidades para reservas no confirmadas por su dueño original';
        $this->detailedDescription = <<<EOF
The [OpportunityQueueTask|INFO] task does things.
Call it with:

  [php symfony arriendas:OpportunityQueueTask|INFO]
EOF;
    }

    protected function execute($arguments = array(), $options = array()) {

        $config = ProjectConfiguration::getApplicationConfiguration("frontend", "prod", TRUE);
        sfContext::createInstance($config);

        // initialize the database connection
        $databaseManager = new sfDatabaseManager($this->configuration);
        $conn = $databaseManager->getDatabase($options['connection'])->getConnection();

        try {

            $maxIterations = 5; // Cantidad máxima de iteraciones
            $kmPerIteration = 1; // Radio en KM de cada iteración
            $exclusivityTime = 60; // En minutos // Exclusividad que se le entrega al dueño original antes de enviar oportunidades
            $timePerIteration = 20; // En minutos // Tiempo entre cada iteración

            // Se obtienen todas las reservas para procesar
            $q = Doctrine_Core::getTable("OpportunityQueue")
                ->createQuery('OQ')
                ->where('OQ.is_active IS TRUE')
                ->andWhere("DATE_ADD(OQ.paid_at, INTERVAL {$exclusivityTime} MINUTE) < NOW()")
                ->andWhere("OQ.last_iteration_at IS NULL OR DATE_ADD(OQ.last_iteration_at, INTERVAL {$timePerIteration} MINUTE) < NOW()")
                ->andWhere('OQ.iteration <= ?', $maxIterations);

            $OpportunitiesQueue = $q->execute();

            if (count($OpportunitiesQueue) == 0) {
                $this->log("[".date("Y-m-d H:i:s")."] No se encontraron oportunidades");
            } else {

                foreach ($OpportunitiesQueue as $OpportunityQueue) {

                    $Reserve = $OpportunityQueue->getReserve();

                    // Si la reserva ya se encuentra confirmada, desactivamos el envío de oportunidades
                    if ($Reserve->getConfirmed()) {
                        $this->log("[".date("Y-m-d H:i:s")."] La reserva {$Reserve->id} de oportunidad {$OpportunityQueue->id} ya fue confirmada por el dueño original. Se inactiva la oportundiad");
                        $OpportunityQueue->setIsActive(false);
                        $OpportunityQueue->save();
                        continue;
                    }

                    // Si la reserva ya posee mas de 5 opciones de cambio, no se generan oportunidades y se cancela
                    if (count($Reserve->getChangeOptions()) >= 5) {
                        $this->log("[".date("Y-m-d H:i:s")."] La oportunidad {$OpportunityQueue->id} ya posee mas de 5 opciones de cambios. Se inactiva la oportunidad");
                        $OpportunityQueue->setIsActive(false);
                        $OpportunityQueue->save();
                        continue;
                    }

                    // Si el usuario que realizó la reserva o el pago se encuentra bloqueado, no se generan oportunidades
                    if ($Reserve->getUser()->getBlocked()) {
                        $this->log("[".date("Y-m-d H:i:s")."] El usuario {$Reserve->getUser()->getBlocked()} que realizo la reserva {$Reserve->id} se encuentra bloqueado. Se inactiva la oportunidad");
                        $OpportunityQueue->setIsActive(false);
                        $OpportunityQueue->save();
                        continue;
                    }

                    // Esto es para evitar generar los registros para una reserva en la que aun no se envian todos los correos
                    // generados para la iteracion anterior
                    $q = Doctrine_Core::getTable('OpportunityEmailQueue')
                        ->createQuery('OEQ')
                        ->select('OEQ.*, count(OEQ.id) AS total')
                        ->where('OEQ.reserve_id = ?', $Reserve->getId())
                        ->andWhere('OEQ.sended_at IS NULL')
                        ->groupBy('OEQ.reserve_id');

                    $result = $q->execute();
                    
                    if (isset($result[0]) && $result[0]->getTotal() > 0) {
                        $this->log("[".date("Y-m-d H:i:s")."] Evitando generar registros para un reserva en la que aun no se envian todos los correos");
                        continue;
                    }

                    // Comenzamos
                    $desde = $OpportunityQueue->getIteration() - 1;
                    $hasta = $OpportunityQueue->getIteration();

                    $this->log("[".date("Y-m-d H:i:s")."] Buscando autos para reserva {$Reserve->getId()} entre {$desde} Km y {$hasta} Km");
                    
                    $q = Doctrine_Core::getTable('Car')
                        ->createQuery('C')
                        ->innerJoin('C.Model M')
                        ->where('distancia(?, ?, C.lat, C.lng) > ?', array($Reserve->getCar()->getLat(), $Reserve->getCar()->getLng(), $desde))
                        ->andWhere('distancia(?, ?, C.lat, C.lng) <= ?',  array($Reserve->getCar()->getLat(), $Reserve->getCar()->getLng(), $hasta))
                        ->andWhere('C.seguro_ok = 4')
                        ->andWhere('C.activo = 1')
                        ->andWhere('C.transmission = ?', $Reserve->getCar()->getTransmission());

                    if ($Reserve->getCar()->getModel()->getIdOtroTipoVehiculo() == 1) {
                        $q->andWhere('M.id_otro_tipo_vehiculo IN (1,2)');
                    } else {
                        $q->andWhere('M.id_otro_tipo_vehiculo = ?', $Reserve->getCar()->getModel()->getIdOtroTipoVehiculo());
                    }
                    
                    $Cars = $q->execute();

                    $this->log("[".date("Y-m-d H:i:s")."] ".count($Cars)." autos encontrados");

                    foreach($Cars as $Car) {

                        if (!$Car->hasReserve($Reserve->getFechaInicio2(), $Reserve->getFechaTermino2())) {

                            $this->log("[".date("Y-m-d H:i:s")."] User: {$Car->getUser()} Id: {$Car->getUser()->getId()}");

                            $OpportunityEmail = new OpportunityEmailQueue();
                            $OpportunityEmail->setReserve($Reserve); // Reserva original
                            $OpportunityEmail->setCar($Car); // Auto a postular
                            $OpportunityEmail->save();
                        }
                    }

                    $OpportunityQueue->setIteration($OpportunityQueue->getIteration() + 1);
                    $OpportunityQueue->save();
                }
            }
        } catch (Exception $e) {
            
            $this->log("[".date("Y-m-d H:i:s")."] ERROR: {$e->getMessage()}");    

            if ($options['env'] == 'prod') {
                Utils::reportError($e->getMessage(), "OpportunityQueueTask");
            }            
        }
    }
}