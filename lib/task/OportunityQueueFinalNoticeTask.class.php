<?php

class OportunityQueueFinalNoticeTask extends sfBaseTask {

    protected function configure() {

        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name'),
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
            new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'doctrine'),
            new sfCommandOption('hoursToNotice', null, sfCommandOption::PARAMETER_REQUIRED, 'Cantidad de horas previas a la reserva', 24),
        ));

        $this->namespace = 'arriendas';
        $this->name = 'oportunityQueueFinalNoticeTask';
        $this->briefDescription = '';
        $this->detailedDescription = <<<EOF
The [OportunityQueueFinalNoticeTask|INFO] task does things.
Call it with:

  [php symfony arriendas:OportunityQueueFinalNoticeTask|INFO]
EOF;
    }

    protected function execute($arguments = array(), $options = array()) {

        $config = ProjectConfiguration::getApplicationConfiguration("frontend", "prod", TRUE);
        sfContext::createInstance($config);

        // initialize the database connection
        $databaseManager = new sfDatabaseManager($this->configuration);
        $conn = $databaseManager->getDatabase($options['connection'])->getConnection();

        $hoursToNotice = $options["hoursToNotice"];

        $this->log("HORAS: ".$hoursToNotice);

        $r = Doctrine_Core::getTable("Reserve")->find(18261);
        $this->log("Fecha inicio: ".$r->getDate());
        $this->log("Confirmado: ".$r->getConfirmed());
        $this->log("Reserva Original: ".$r->getReservaOriginal());
        $this->log("Completada: ".$r->getTransaction()->getCompleted());
        $this->log("Notificado: ".$r->getOportunityQueue()->getFinalNotice());

        // Se obtienen todas las reservas dentro del periodo de notificación que no hayan sido ya notificadas
        $q = Doctrine_Core::getTable('Reserve')
            ->createQuery('R')
            ->innerJoin('R.Transaction T')
            ->innerJoin('R.OportunityQueue OQ')
            ->where('R.confirmed = 0')
            ->andWhere('R.impulsive = 1')
            ->andWhere('R.reserva_original = 0 OR R.reserva_original = NULL')
            ->andWhere("R.date > DATE_SUB(NOW(), INTERVAL {$hoursToNotice} HOUR)", $hoursToNotice)
            ->andWhere("R.date <= NOW()")
            ->andWhere('T.completed = 1')
            ->andWhere("OQ.final_notice = 0");

        $Reservations = $q->execute();

        $this->log("[".date("Y-m-d H:i:s")."] Reservas a revisar: ".count($Reservations));

        foreach ($Reservations as $OriginalReserve) {

            $this->log("Chequeando reserva ".$OriginalReserve->getId()."...");
            $OpportunityReservations = Doctrine_Core::getTable("Reserve")->findByReservaOriginal($OriginalReserve->getId());
            
            if (count($OpportunityReservations) == 0) {

                $this->log("No tiene oportunidades. Notificando a soporte...");

                $this->notificarASoporte($OriginalReserve);

                $OpportunityQueue = Doctrine_Core::getTable("OportunityQueue")->findOneByReserveId($OriginalReserve->getId());
                $OpportunityQueue->setFinalNotice(true);
                $OpportunityQueue->save();
            } else {

                $notify = true;

                // Se revisa que no tenga ya una oportunidad seleccionada
                foreach ($OpportunityReservations as $OpportunityReserve) {

                    if ($OpportunityReserve->getTransaction()->getCompleted()) {
                        $notify = false;
                    }
                }

                if ($notify) {

                    $this->log("Revisando diponibilidad de las oportunidades...");

                    $moreSaving = array();
                    $nearest = array();

                    foreach ($OpportunityReservations as $OpportunityReserve) {

                        // Se revisa que el auto esté disponible
                        $q = Doctrine_Core::getTable('Reserve')
                            ->createQuery('R')
                            ->innerJoin('R.Transaction T')
                            ->where('R.confirmed = 1')
                            ->andWhere('R.car_id = ?', $OpportunityReserve->getCarId())
                            ->andWhere('
                                (R.date >= ? AND R.date < DATE_ADD(?, INTERVAL ? HOUR)) OR
                                (? >= R.date AND DATE_ADD(?, INTERVAL ? HOUR) < DATE_ADD(R.date, INTERVAL R.duration HOUR)) OR
                                (? <= DATE_ADD(R.date, INTERVAL R.duration HOUR) AND DATE_ADD(?, INTERVAL ? HOUR) >= DATE_ADD(R.date, INTERVAL R.duration HOUR))
                            ', array(
                                    $OpportunityReserve->getDate(), $OpportunityReserve->getDate(), $OpportunityReserve->getDuration(),
                                    $OpportunityReserve->getDate(), $OpportunityReserve->getDate(), $OpportunityReserve->getDuration(),
                                    $OpportunityReserve->getDate(), $OpportunityReserve->getDate(), $OpportunityReserve->getDuration()
                                )
                            )
                            ->andWhere("T.completed = 1");

                        $OverlappedReservations = $q->execute();

                        // El auto se considera sólo si no está ya tomado por otra reserva
                        if (count($OverlappedReservations) == 0) {

                            // OJO. se considera sólo el precio por hora. averiguar como considerar el precio por día
                            $moreSaving[$OpportunityReserve->getId()] = ($OpportunityReserve->getCar()->getPricePerHour() * $OpportunityReserve->getDuration()) - $OpportunityReserve->getPrice();
                            $nearest[$OpportunityReserve->getId()] = (6371 * acos( cos( radians($OriginalReserve->getLat()) ) * cos( radians( $OpportunityReserve->getLat() ) ) * cos( radians($OpportunityReserve->getLng()) - radians($OriginalReserve->getLng()) ) + sin( radians($OriginalReserve->getLat()) ) * sin( radians( $OpportunityReserve->getLat() ) ) ) );
                        }
                    }

                    if (count($moreSaving) > 0) {

                        $this->log("Cambiando a la oportunidad que genera más ahorro...");

                        // Se ordenan los autos del mayor ahorro al menor
                        arsort($moreSaving);
                        // Se ordenan los autos de menor distancia a mayor
                        asort($nearest);

                        // Reseteamos todas las reservas, tanto original como oportunidades
                        $OriginalReserve->getTransaction()->setCompleted(false);
                        $OriginalReserve->save();

                        foreach ($OpportunityReservations as $OpportunityReserve) {

                            if ($OpportunityReserve->getTransaction()->getCompleted()) {
                                $OpportunityReserve->getTransaction()->setCompleted(false);
                                $OpportunityReserve->save();
                            }
                        }

                        // Finalmente, se deja como completa la reserva que genera más ahorro (OJO. falta incluir también la distancia)
                        $NewReserve = Doctrine_Core::getTable("Reserve")->find(key(array_shift($moreSaving)));
                        $NewReserve->getTransaction()->setCompleted(true);
                        $NewReserve->save();

                        // Notificamos al usuario
                        $this->log("Notificando al usuario...");

                        /*$message = $this->getMailer()->compose();
                        $message->setSubject("[NF] Reserva ".$OriginalReserve->getId()." sin oportunidades");
                        $message->setFrom('no-reply@arriendas.cl', 'Notificaciones Arriendas');
                        $message->setTo(array("Germán Rimoldi" => "german@arriendas.cl"));
                        $message->setBody($body, "text/html");
                        $this->getMailer()->send($message);*/
                    } else {

                        $this->log("No hay oportunidad(es) disponible(s). Notificando a soporte...");
                        $this->notificarASoporte($OriginalReserve);
                    }

                    $OpportunityQueue = Doctrine_Core::getTable("OportunityQueue")->findOneByReserveId($OriginalReserve->getId());
                    $OpportunityQueue->setFinalNotice(true);
                    $OpportunityQueue->save();
                } else {
                    $this->log("Ya poseía oportunidad seleccionada [si sucede esto revisar poque es raro]");
                }
            }
        }
    }

    private function notificarASoporte($OriginalReserve) {

        // Se notifica a soporte que el dueño no ha confirmado y el arrendatario no tiene opciones

        $User = $OriginalReserve->getUser();

        $body = "<p>Faltan aproximadamente $hoursToNotice horas y la reserva ".$OriginalReserve->getId()." no se sido confirmada por el dueño y el arrendatario no tiene otras opciones.</p>";
        $body .= "<br>";
        $body .= "<h3>Información de contacto del arrendatario</h3>";
        $body .= "<table>";
        $body .= "<tr><th>Nombre</th><td>".$User->getFirstname()." ".$User->getLastname()."</td></tr>";
        $body .= "<tr><th>Teléfono</th><td>".$User->getTelephone()."</td></tr>";
        $body .= "<tr><th>Email</th><td>".$User->getEmail()."</td></tr>";
        $body .= "</table>";

        $message = $this->getMailer()->compose();
        $message->setSubject("Notificación reserva sin oportunidades [".$OriginalReserve->getId()."]");
        $message->setFrom('no-reply@arriendas.cl', 'Notificaciones Arriendas.cl');
        $message->setTo(array("Soporte Arriendas.cl" => "soporte@arriendas.cl"));
        $message->setBody($body, "text/html");
        $this->getMailer()->send($message);
    }
}