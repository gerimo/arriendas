<?php

class CarAskAvailabilityNewTask extends sfBaseTask {

    protected function configure() {

        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'frontend'),
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'local'),
            new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'doctrine')
        ));

        $this->namespace = 'car';
        $this->name = 'askAvailabilityNew';
        $this->briefDescription = 'Envía un correo preguntando por la disponibilidad del auto para el fin de semana o festivo';
        $this->detailedDescription = <<<EOF
The [CarAskAvailability|INFO] task does things.
Call it with:

  [php symfony car:askAvailability|INFO]
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
        $numberOfDaysToAsk = 7;

        // Definición de variables
        $days              = array();
        $isSunday          = false;
        $oMailing          = Doctrine_Core::getTable("Mailing")->find(2);
        //$today             = time();
        //$tomorrow          = strtotime("+1 day");
        $totalActiveCars   = 0;

        // Definimos los días a preguntar
        for ($i = 0 ; $i < $numberOfDaysToAsk ; $i++) {
            $days[$i] = date("Y-m-d", strtotime("+".$i." day"));
        }

        $from = $days[0] .= " 00:00:00";
        $to   = $days[count($days)-1] .= " 23:59:59";

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
                continue;
            }

            // NO enviamos a los autos que ya tienen una reserva
            if ($oCar->hasReserve($from, $to)) {
                $this->log("[".date("Y-m-d H:i:s")."] Car ".$oCar->getId()." posee una reserva que abarca los siguientes ".$numberOfDaysToAsk." dias. Se omite."); 
                continue;
            }

            // NO enviamos a los autos que ya nos dieron su disponibilidad
            if ($oCar->hasAvailability($days)) {
                $this->log("[".date("Y-m-d H:i:s")."] Car ".$oCar->getId()." ya tiene la disponibilidad definida para los siguientes dias. Se omite.");
                continue;
            }

            $CarAvailabilityEmail = new CarAvailabilityEmail();
            $CarAvailabilityEmail->setCar($oCar);
            $CarAvailabilityEmail->setSentAt(date("Y-m-d H:i:s"));
            $CarAvailabilityEmail->save();

            if (date("N") == 7 || date("N") == 1) {
                $subject = "¿Tienes disponibilidad para recibir clientes esta semana? [E".$CarAvailabilityEmail->getId()."]";
                $body    = get_partial('emails/carAskAvailabilityMailingLongWeekend', array( // Fin de semana largo
                    'Car' => $oCar,
                    'days' => $days,
                    'imageForTrackEmail' => $host . $routing->generate('car_availability_email_open', array("id" => $CarAvailabilityEmail->id)),
                    'urlMisAutos' => $host . $routing->generate('cars')
                ));
            } else {

            }

            $from  = array("soporte@arriendas.cl" => "Soporte Arriendas.cl");
            $to    = array($oOwner->email => $oOwner->firstname." ".$oOwner->lastname);

            $message = $this->getMailer()->compose();
            $message->setSubject($subject);
            $message->setBody($body, 'text/html');
            $message->setFrom($from);
            $message->setTo($to);
            $message->setPriority(3);
            
            $this->log("[".date("Y-m-d H:i:s")."] Enviando consulta por Car ".$oCar->id." a User ".$oOwner->firstname." ".$oOwner->lastname." Car ");
            $this->getMailer()->send($message);
        }
    }
}