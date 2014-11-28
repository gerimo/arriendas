<?php

class OportunityQueueTask extends sfBaseTask {

    protected function configure() {

        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name'),
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
            new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'doctrine'),
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
            $baseKpi = 20; //en porcentaje

            // Se obtienen todas las reservas para procesar
            $table = Doctrine_Core::getTable('OportunityQueue');
            $q = $table
                ->createQuery('OQ')
                ->innerJoin('OQ.Reserve R')
                ->where('OQ.is_active IS TRUE')
                ->andWhere("DATE_ADD(OQ.paid_at, INTERVAL {$exclusivityTime} MINUTE) < NOW()")
                ->andWhere('OQ.iteration <= ?', $maxIterations)
                ->andWhere('R.date < ?', date("Y-m-d H:i:s"))
                ;

            $OpportunitiesQueue = $q->execute();

            foreach ($OpportunitiesQueue as $OpportunityQueue) {

                // Si el usuario que realizó la reserva o el pago se encuentra bloqueado, no se generan oportunidades
                if ($OpportunityQueue->getReserve()->getUser()->getBlocked()) {
                    continue;
                }

                $table = Doctrine_Core::getTable('OportunityEmailQueue');
                $q = $table
                    ->createQuery('o')
                    ->select('o.*, count(o.id) AS total')
                    ->where('o.reserve_id = ?', $OpportunityQueue->getReserve()->getId())
                    ->andWhere('o.sended_at IS NULL')
                    ->groupBy('o.reserve_id')
                    ;

                $result = $q->execute();

                // Esto es para evitar generar los registros para una reserva en la que aun no se envian todos los correos
                //  generados para la iteracion anterior
                if (isset($result[0]) && $result[0]->getTotal() > 0) {
                    continue;
                }

                $desde = $OpportunityQueue->getIteration() - 1;
                $hasta = $OpportunityQueue->getIteration();

                $this->log("Calculando Usuarios para reserva {$OpportunityQueue->getReserve()->getId()} entre {$desde} Km y {$hasta} Km");
                
                $table = Doctrine_Core::getTable('Car');
                $q = $table
                    ->createQuery('c')
                    ->innerJoin('c.Reserves r')
                    ->innerJoin('c.Model m')
                    ->where('distancia(?, ?, c.lat, c.lng) > ?', array($OpportunityQueue->getReserve()->getCar()->getLat(), $OpportunityQueue->getReserve()->getCar()->getLng(), $desde))
                    ->andWhere('distancia(?, ?, c.lat, c.lng) <= ?',  array($OpportunityQueue->getReserve()->getCar()->getLat(), $OpportunityQueue->getReserve()->getCar()->getLng(), $hasta))
                    ->andWhere('c.seguro_ok = ?', 4)
                    ->andWhere('c.activo IS TRUE')
                    ->andWhere('c.transmission = ?', $OpportunityQueue->getReserve()->getCar()->getTransmission())
                    ->andWhere('r.comentario = "null"')
                    ->andWhere('(day(r.date) - day(r.fecha_reserva)) > 0')
                    /*->andWhere("hour(r.fecha_reserva) < 22 AND hour(r.fecha_reserva) > 5")*/
                    
                    ->groupBy('c.user_id')
                    ->orderBy('c.ratio_aprobacion DESC')
                    ;

                if ($OpportunityQueue->getReserve()->getCar()->getModel()->getIdOtroTipoVehiculo() == 1) {
                    $q->andWhere('m.id_otro_tipo_vehiculo IN (1,2)');
                } else {
                    $q->andWhere('m.id_otro_tipo_vehiculo = ?', $OpportunityQueue->getReserve()->getCar()->getModel()->getIdOtroTipoVehiculo());
                }

                $cars = $q->execute();

                foreach($cars as $car) {

                    if ($car->getRatioAprobacion() > $baseKpi) {

                        $this->log("User: {$car->getUser()} Id: {$car->getUser()->getId()} RatioAprobacion: {$car->getRatioAprobacion()}");

                        $oportunityEmail = new OportunityEmailQueue();
                        $oportunityEmail->setOwner($car->getUser()); // Dueño del auto
                        $oportunityEmail->setRenter($OpportunityQueue->getReserve()->getUser()); //Arendatario
                        $oportunityEmail->setReserve($OpportunityQueue->getReserve());
                        $oportunityEmail->save();
                    }
                }

                $OpportunityQueue->setIteration($OpportunityQueue->getIteration() + 1);
                $OpportunityQueue->save();
            }
        } catch (Exception $e) {

            Utils::reportError($e->getMessage(), "profile/OportunityQueueTask");
        }
    }
}