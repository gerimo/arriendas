<?php

class CarAskAvailability2Task extends sfBaseTask {

    protected function configure() {

        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'frontend'),
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'local'),
            new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'doctrine')
        ));

        $this->namespace = 'car';
        $this->name = 'askAvailability2';
        $this->briefDescription = 'Envía un correo preguntando por la disponibilidad del auto para el fin de semana o festivo';
        $this->detailedDescription = <<<EOF
The [CarAskAvailability|INFO] task does things.
Call it with:

  [php symfony car:askAvailability2|INFO]
EOF;
    }

    protected function execute($arguments = array(), $options = array()) {

        $context = sfContext::createInstance($this->configuration);
        $context->getConfiguration()->loadHelpers('Partial');

        $routing = $this->getRouting();
        
        if ($options['env'] == 'dev') {
            $host = 'http://dev.arriendas.cl';
        } elseif ($options['env'] == 'prod') {
            $host = 'http://www.arriendas.cl';
        } else {
            $host = 'http://local.arriendas.cl';
        }

        // Configuración
        $numberOfDaysToAsk     = 7;
        $minimalAvailabilities = 10;

        // Definición de variables
        $days                = array();
        $isWeekToday         = false;
        $isWeekTomorrow      = false;
        $isWeekYesterday     = false;
        $isWeekendToday      = false;
        $isWeekendTomorrow   = false;
        $isWeekendYesterday  = false;
        $oMailing            = Doctrine_Core::getTable("Mailing")->find(2);
        $today               = time();        
        $tomorrow            = strtotime("+1 day");
        $totalActiveCars     = 0;
        $yesterday           = strtotime("-1 day");

        if (date("N", $yesterday) == 6 || date("N", $yesterday) == 7 || Doctrine_Core::getTable("Holiday")->findOneByDate(date("Y-m-d", $yesterday))) {
            $isWeekendYesterday = true;
        } else {
            $isWeekYesterday = true;
        }

        if (date("N", $today) == 6 || date("N", $today) == 7 || Doctrine_Core::getTable("Holiday")->findOneByDate(date("Y-m-d", $today))) {
            $isWeekendToday = true;
        } else {
            $isWeekToday = true;
        }

        if (date("N", $tomorrow) == 6 || date("N", $tomorrow) == 7 || Doctrine_Core::getTable("Holiday")->findOneByDate(date("Y-m-d", $tomorrow))) {
            $isWeekendTomorrow = true;
        } else {
            $isWeekTomorrow = true;
        }

        // Definición de variables de informe
        $iSentEmails = 0;
        $iUnsubscribedUsers = 0;
        $iCarsAlreadyReserved = 0;
        $iCarsAlreadyAvailables = 0;

        // Revisamos si es necesario enviar los emails
        $CA = Doctrine_Core::getTable("CarAvailability")->findByDayAndIsDeleted(date("Y-m-d"), false);

        $email = null;
        if ($isWeekendToday && $isWeekTomorrow) {
            $email = $this->getWeekEmail();
            $days = Utils::isWeek(true, true);
            $this->log("[".date("Y-m-d H:i:s")."] Manana comienza la semana.");
        } elseif ($isWeekToday && $isWeekendYesterday && count($CA) < $minimalAvailabilities) {
            $email = $this->getWeekEmail();
            $days = Utils::isWeek(true, false);
            $this->log("[".date("Y-m-d H:i:s")."] Comenzo la semana y no se tiene el minimo de ".$minimalAvailabilities." autos.");
        } elseif ($isWeekToday && $isWeekendTomorrow) {
            $email = $this->getWeekendEmail();
            $days = Utils::isWeekend(true, true);
            $this->log("[".date("Y-m-d H:i:s")."] Manana comienza el fin de semana.");
        } elseif ($isWeekendToday && $isWeekYesterday && count($CA) < $minimalAvailabilities) {
            $email = $this->getWeekendEmail();
            $days = Utils::isWeekend(true, false);
            $this->log("[".date("Y-m-d H:i:s")."] Comenzo el fin de semana y no se tiene el minimo de ".$minimalAvailabilities." autos.");
        } else {
            $this->log("[".date("Y-m-d H:i:s")."] Hoy no se envian correos.");
        }

        $from = $days[0]." 00:00:00";
        $to   = $days[count($days)-1]." 23:59:59";

        if ($email) {

            // Buscamos los autos activos
            $this->log("[".date("Y-m-d H:i:s")."] Buscando autos activos.");
            $oCars = Doctrine_Core::getTable("Car")->findCarsActives(false, false, false);
            if (count($oCars) > 0) {
                $totalActiveCars = count($oCars);
                $this->log("[".date("Y-m-d H:i:s")."] ".$totalActiveCars." autos encontrados.");
            } else {
                $this->log("[".date("Y-m-d H:i:s")."] No se encontraron autos activos.");
                exit;
            }

            foreach ($oCars as $oCar) {
                
                $oOwner = $oCar->getUser();

                // NO enviamos a los usuarios que ya no están suscritos
                $oUserMailingConfig = Doctrine_Core::getTable('UserMailingConfig')->findOneByUserIdAndMailingIdAndIsSubscribed($oOwner->id, $oMailing->id, false);
                if ($oUserMailingConfig) {
                    $this->log("[".date("Y-m-d H:i:s")."] User ".$oOwner->id." (".$oOwner->firstname." ".$oOwner->lastname.") propietario de Car ".$oCar->getId()." ya no esta suscrito. Se omite.");
                    $iUnsubscribedUsers++;
                    continue;
                }

                // NO enviamos a los autos que ya tienen una reserva
                if ($oCar->hasReserve($from, $to)) {
                    $this->log("[".date("Y-m-d H:i:s")."] Car ".$oCar->getId()." posee una reserva que abarca los siguientes ".$numberOfDaysToAsk." dias. Se omite."); 
                    $iCarsAlreadyReserved++;
                    continue;
                }

                // NO enviamos a los autos que ya nos dieron su disponibilidad
                if ($oCar->hasAvailability($days)) {
                    $this->log("[".date("Y-m-d H:i:s")."] Car ".$oCar->getId()." ya tiene la disponibilidad definida para los siguientes dias. Se omite.");
                    $iCarsAlreadyAvailables++;
                    continue;
                }

                $CarAvailabilityEmail = new CarAvailabilityEmail();
                $CarAvailabilityEmail->setCar($oCar);
                $CarAvailabilityEmail->setSentAt(date("Y-m-d H:i:s"));
                $CarAvailabilityEmail->save();

                $urlWeekend = $host . $routing->generate('car_availability_email', array(
                    'id' => $CarAvailabilityEmail->getId(),
                    'o' => 2,
                    'signature' => $CarAvailabilityEmail->getSignature()
                ));

                $urlWeek = $host . $routing->generate('car_availability_email', array(
                    'id' => $CarAvailabilityEmail->getId(),
                    'o' => 1,
                    'signature' => $CarAvailabilityEmail->getSignature()
                ));

                $subject = $email["subject"];
                $body    = get_partial($email["body"], array(
                                'Car' => $oCar,
                                'days' => $days,
                                'imageForTrackEmail' => $host . $routing->generate('car_availability_email_open', array("id" => $CarAvailabilityEmail->id)),
                                'urlMisAutos' => $host . $routing->generate('cars'),
                                'urlWeekend' => $urlWeekend,
                                'urlWeek' => $urlWeek
                            ));
                $from    = array("soporte@arriendas.cl" => "Soporte Arriendas.cl");
                $to      = array($oOwner->email => $oOwner->firstname." ".$oOwner->lastname);

                $message = $this->getMailer()->compose();
                $message->setSubject($subject);
                $message->setBody($body, 'text/html');
                $message->setFrom($from);
                //$message->setTo($to);
                $message->setTo(array("cristobal@arriendas.cl" => "Cristóbal Medina Moenne"));
                
                $this->log("[".date("Y-m-d H:i:s")."] Enviando consulta por Car ".$oCar->id." a User ".$oOwner->id." (".$oOwner->firstname." ".$oOwner->lastname.")");
                $this->getMailer()->send($message);

                $iSentEmails++;
                exit;
            }

            $this->log("[".date("Y-m-d H:i:s")."] ------------------------------");
            $this->log("[".date("Y-m-d H:i:s")."] Emails enviados: ".$iSentEmails);
            $this->log("[".date("Y-m-d H:i:s")."] Autos con reservas: ".$iCarsAlreadyReserved);
            $this->log("[".date("Y-m-d H:i:s")."] Autos ya disponibles: ".$iCarsAlreadyAvailables);
            $this->log("[".date("Y-m-d H:i:s")."] Usuarios desuscritos: ".$iUnsubscribedUsers);
            $this->log("[".date("Y-m-d H:i:s")."] Total autos activos: ".$totalActiveCars." (".($iSentEmails+$iCarsAlreadyReserved+$iCarsAlreadyAvailables+$iUnsubscribedUsers).")");
            $this->log("[".date("Y-m-d H:i:s")."] ------------------------------");
        }
    }

    private function getWeekEmail() {

        return array(
            "subject" => "Indica tu disponibilidad para esta semana",
            "body" => "emails/carAskAvailabilityMailingWeek"
        );
    }

    private function getWeekendEmail() {

        return array(
            "subject" => "Indica tu disponibilidad para este fin de semana",
            "body" => "emails/carAskAvailabilityMailingWeekend"
        );
    }
}