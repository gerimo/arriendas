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
        $this->name = 'oportunityQueueFinalNotice';
        $this->briefDescription = '';
        $this->detailedDescription = <<<EOF
The [OportunityQueueFinalNotice|INFO] task does things.
Call it with:

  [php symfony arriendas:oportunityQueueFinalNotice|INFO]
EOF;
    }

    protected function execute($arguments = array(), $options = array()) {

        $config = ProjectConfiguration::getApplicationConfiguration("frontend", "prod", TRUE);
        sfContext::createInstance($config);

        // initialize the database connection
        $databaseManager = new sfDatabaseManager($this->configuration);
        $conn = $databaseManager->getDatabase($options['connection'])->getConnection();

        $hoursToNotice = $options["hoursToNotice"];
        /*$hoursToNotice = 168;*/

        // Se obtienen todas las reservas dentro del periodo de notificación que no hayan sido ya notificadas
        $q = Doctrine_Core::getTable('Reserve')
            ->createQuery('R')
            ->innerJoin('R.Transaction T')
            ->innerJoin('R.OportunityQueue OQ')
            ->where('R.confirmed = 0 OR R.canceled = 1')
            ->andWhere('R.impulsive = 1')
            ->andWhere('R.reserva_original = 0 OR R.reserva_original = NULL')
            ->andWhere('NOW() < R.date')
            ->andWhere("NOW() >= DATE_SUB(R.date, INTERVAL {$hoursToNotice} HOUR)", $hoursToNotice)
            ->andWhere('T.completed = 1')
            ->andWhere("OQ.final_notice = 0");

        $Reservations = $q->execute();

        $this->log("[".date("Y-m-d H:i:s")."] Reservas a revisar: ".count($Reservations));

        foreach ($Reservations as $OriginalReserve) {

            $this->log("[".date("Y-m-d H:i:s")."] Chequeando reserva ".$OriginalReserve->getId());
            $OpportunityReservations = Doctrine_Core::getTable("Reserve")->findByReservaOriginal($OriginalReserve->getId());
            
            if (count($OpportunityReservations) == 0) {

                $this->log("[".date("Y-m-d H:i:s")."] No tiene oportunidades. Notificando a soporte...");

                $this->notificarASoporte($OriginalReserve, $hoursToNotice);

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

                    $this->log("[".date("Y-m-d H:i:s")."] Revisando diponibilidad de las oportunidades...");

                    $moreSaving = array();
                    $nearest = array();

                    foreach ($OpportunityReservations as $OpportunityReserve) {

                        $Car = $OpportunityReserve->getCar();

                        // El auto se considera sólo si no está ya tomado por otra reserva
                        if (!$Car->hasReserve($OpportunityReserve->getInicio(), $OpportunityReserve->getTermino())) {

                            // OJO. se considera sólo el precio por hora. averiguar como considerar el precio por día
                            $moreSaving[$OpportunityReserve->getId()] = ($Car->getPricePerHour() * $OpportunityReserve->getDuration()) - $OpportunityReserve->getPrice();
                            $nearest[$OpportunityReserve->getId()] = (6371 * acos( cos( deg2rad($OriginalReserve->getCar()->getLat()) ) * cos( deg2rad( $OpportunityReserve->getCar()->getLat() ) ) * cos( deg2rad($OpportunityReserve->getCar()->getLng()) - deg2rad($OriginalReserve->getCar()->getLng()) ) + sin( deg2rad($OriginalReserve->getCar()->getLat()) ) * sin( deg2rad( $OpportunityReserve->getCar()->getLat() ) ) ) );

                            error_log("[DEBUG] NO TIENE RESERVA");
                        } else {
                            error_log("[DEBUG] TIENE RESERVA");
                        }
                    }

                    if (count($moreSaving) > 0) {

                        $this->log("[".date("Y-m-d H:i:s")."] Cambiando a la oportunidad que genera más ahorro...");

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
                        $NewReserve = Doctrine_Core::getTable("Reserve")->find(array_shift(array_keys($moreSaving)));
                        $NewReserve->getTransaction()->setCompleted(true);
                        $NewReserve->save();

                        $Car = $NewReserve->getCar();
                        $Renter = $NewReserve->getRenter();
                        $Owner = $Car->getUser();

                        // Notificamos al arrendatario
                        $this->log("[".date("Y-m-d H:i:s")."] Notificando al arrendatario cambio de auto ".$OriginalReserve->getCar()->getId()." a ".$Car->getId());

                        $subject = "Hemos cambiado el auto de tu reserva por un auto mejor, manteniendo el precio";

                        $body = "<p>Hola ".$Renter->getFirstname().",</p>";
                        $body .= "<p>Hemos reemplazado el auto que pagaste por otro auto de mayor precio (y no debes pagar la diferencia!).</p>";
                        $body .= "<h3>Datos del propietario</h3>";
                        $body .= "<table>";
                        $body .= "<tr><th style='text-align: left'>Nombre</th><td>".$Owner->getFirstname()." ".$Owner->getLastname()."</td></tr>";
                        $body .= "<tr><th style='text-align: left'>Teléfono</th><td>".$Owner->getTelephone()."</td></tr>";
                        $body .= "<tr><th style='text-align: left'>Correo electrónico</th><td>".$Owner->getEmail()."</td></tr>";
                        $body .= "<tr><th style='text-align: left'>Dirección</th><td>".$Owner->getAddress()."</td></tr>";
                        $body .= "<tr><th style='text-align: left'>Marca</th><td>".$Car->getModel()->getBrand()->getName()."</td></tr>";
                        $body .= "<tr><th style='text-align: left'>Modelo</th><td>".$Car->getModel()->getName()."</td></tr>";
                        $body .= "<tr><th style='text-align: left'>Ahorro</th><td>".(round(($NewReserve->getRentalPrice() - $NewReserve->getPrice())/ $NewReserve->getPrice(), 0) * 100)." %</td></tr>";
                        $body .= "</table>";

                        $body .= "<br><br>";
                        $body .= "<p style='color: #aaa; font-size:14px; margin: 0; padding: 3px 0 0 0'>Atentamente</p>";
                        $body .= "<p style='color: #aaa; font-size:14px; margin: 0; padding: 3px 0 0 0'>Equipo Arriendas.cl</p>";

                        $message = $this->getMailer()->compose();
                        $message->setSubject($subject);
                        $message->setBody($body, "text/html");
                        $message->setFrom('soporte@arriendas.cl', 'Soporte Arriendas.cl');
                        /*$message->setTo(array($Renter->getEmail() => $Renter->getFirstName()." ".$Renter->getLastname()));*/
                        $message->setBcc(array("cristobal@arriendas.cl" => "Cristóbal Medina Moenne"));
                        
                        $this->getMailer()->send($message);
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

    private function notificarASoporte($OriginalReserve, $hoursToNotice) {

        // Se notifica a soporte que el dueño no ha confirmado y el arrendatario no tiene opciones

        $User = $OriginalReserve->getUser();

        $body = "<p style='background-color: #FA5858; padding: 10px 5px'>Faltan aproximadamente $hoursToNotice horas y la reserva <strong>".$OriginalReserve->getId()."</strong> no ha sido confirmada por el dueño y, el arrendatario no tiene otras opciones.</p>";
        $body .= "<h3>Información de contacto del arrendatario</h3>";
        $body .= "<table>";
        $body .= "<tr><th style='text-align: left'>Nombre</th><td>".$User->getFirstname()." ".$User->getLastname()."</td></tr>";
        $body .= "<tr><th style='text-align: left'>Teléfono</th><td>".$User->getTelephone()."</td></tr>";
        $body .= "<tr><th style='text-align: left'>Email</th><td>".$User->getEmail()."</td></tr>";
        $body .= "</table>";

        $message = $this->getMailer()->compose();
        $message->setSubject("Notificación reserva sin oportunidades [".$OriginalReserve->getId()."]");
        $message->setFrom('no-reply@arriendas.cl', 'Notificaciones Arriendas.cl');
        /*$message->setTo(array("soporte@arriendas.cl" => "Soporte Arriendas.cl"));*/
        $message->setBcc(array("cristobal@arriendas.cl" => "Cristóbal Medina Moenne"));
        $message->setBody($body, "text/html");
        $this->getMailer()->send($message);
    }
}