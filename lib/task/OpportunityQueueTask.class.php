<?php

class OpportunityQueueTask extends sfBaseTask {

    protected function configure() {

        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name'),
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
            new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'doctrine'),
        ));

        $this->namespace = 'arriendas';
        $this->name = 'opportunityQueueTask';
        $this->briefDescription = '';
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

        // Este task debiera correr cada 10 minutos app
        // Este task toma todas las reservas pagas que se hayan hecho y que esten pendientes para generar
        //  la lista de usuarios a los cuales se les debe enviar correos de "oportunidades"

        try {

            $maxIterations = 5;
            $kmPerIteration = 1;
            $exclusivityTime = 60; // En minutos // Exclusividad que se le entrega al dueño original antes de enviar oportunidades
            //$baseKpi = 20; //en porcentaje

            // Se obtienen todas las reservas para procesar
            $q = Doctrine_Core::getTable("OpportunityQueue")
                ->createQuery('OQ')
                ->innerJoin('OQ.Reserve R')
                ->where('OQ.is_active IS TRUE')
                ->andWhere('R.confirmed = 0')
                ->andWhere("DATE_ADD(OQ.paid_at, INTERVAL {$exclusivityTime} MINUTE) < NOW()")
                ->andWhere('OQ.iteration <= ?', $maxIterations);
                /*->andWhere('R.date < ?', date("Y-m-d H:i:s"));*/

            $OpportunitiesQueue = $q->execute();

            if (count($OpportunitiesQueue) == 0) {
                $this->log("[".date("Y-m-d H:i:s")."] No se encontraron oportunidades");
            } else {

                foreach ($OpportunitiesQueue as $OpportunityQueue) {

                    $Reserve = $OpportunityQueue->getReserve();

                    // Si la reserva ya posee mas de 5 opciones de cambio, no se generan oportunidades y se cancela
                    if (count($Reserve->getChangeOptions()) > 5) {
                        $OpportunityQueue->setIsActive(false);
                        $OpportunityQueue->save();
                        continue;
                    }

                    // Si el usuario que realizó la reserva o el pago se encuentra bloqueado, no se generan oportunidades
                    if ($Reserve->getUser()->getBlocked()) {
                        continue;
                    }

                    $q = Doctrine_Core::getTable('OpportunityEmailQueue')
                        ->createQuery('OEQ')
                        ->select('OEQ.*, count(OEQ.id) AS total')
                        ->where('OEQ.reserve_id = ?', $Reserve->getId())
                        ->andWhere('OEQ.sended_at IS NULL')
                        ->groupBy('OEQ.reserve_id');

                    $result = $q->execute();

                    // Esto es para evitar generar los registros para una reserva en la que aun no se envian todos los correos
                    //  generados para la iteracion anterior
                    if (isset($result[0]) && $result[0]->getTotal() > 0) {
                        continue;
                    }

                    $desde = $OpportunityQueue->getIteration() - 1;
                    $hasta = $OpportunityQueue->getIteration();

                    $this->log("[".date("Y-m-d H:i:s")."] Buscando autos para reserva {$Reserve->getId()} entre {$desde} Km y {$hasta} Km");
                    
                    $q = Doctrine_Core::getTable('Car')
                        ->createQuery('C')
                        /*->innerJoin('C.Reserves R')*/
                        ->innerJoin('C.Model M')
                        ->where('distancia(?, ?, C.lat, C.lng) > ?', array($Reserve->getCar()->getLat(), $Reserve->getCar()->getLng(), $desde))
                        ->andWhere('distancia(?, ?, C.lat, C.lng) <= ?',  array($Reserve->getCar()->getLat(), $Reserve->getCar()->getLng(), $hasta))
                        ->andWhere('C.seguro_ok = 4')
                        ->andWhere('C.activo = 1')
                        ->andWhere('C.transmission = ?', $Reserve->getCar()->getTransmission());
                        /*->andWhere('R.comentario = "null"')*/
                        /*->andWhere('(day(R.date) - day(R.fecha_reserva)) > 0')*/
                        /*->andWhere("hour(R.fecha_reserva) < 22 AND hour(R.fecha_reserva) > 5")*/                    
                        /*->groupBy('C.user_id')*/
                        /*->orderBy('C.ratio_aprobacion DESC');*/

                    if ($Reserve->getCar()->getModel()->getIdOtroTipoVehiculo() == 2) {
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
            if ($options['env'] == 'prod') {
                Utils::reportError($e->getMessage(), "OpportunityQueueTask");
            } else {
                $this->log("[".date("Y-m-d H:i:s")."] ERROR: {$e->getMessage()}");
            }    
        }
    }
}