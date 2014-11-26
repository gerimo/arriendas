<?php

// FALTA VER UNA FORMA DE NO ENVIAR DENUEVO LOS CORREOS SI EL TASK SE LLEGASE A CORRER AGAIN

class AutoMananaTask extends sfBaseTask {

    protected function configure() {

        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name'),
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
            new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'doctrine'),
            new sfCommandOption('reminder', null, sfCommandOption::PARAMETER_REQUIRED, 'Is a reminder', 0),
        ));

        $this->namespace = 'arriendas';
        $this->name = 'autoManana';
        $this->briefDescription = '';
        $this->detailedDescription = <<<EOF
The [AutoManana|INFO] task does things.
Call it with:

  [php symfony arriendas:autoManana|INFO]
EOF;
    }

    protected function execute($arguments = array(), $options = array()) {

        $config = ProjectConfiguration::getApplicationConfiguration("frontend", "prod", TRUE);
        sfContext::createInstance($config);

        // initialize the database connection
        /*$databaseManager = new sfDatabaseManager($this->configuration);
        $conn = $databaseManager->getDatabase($options['connection'])->getConnection();*/

        $isReminder = $options["reminder"];

        if ($isReminder) {

        } else {

            $from = null;
            $to = null;
            $i = 1;

            $this->log("Revisando si mañana es feriado o sábado...");

            $Holiday = Doctrine_Core::getTable("Holiday")->findOneByDate(date("Y-m-d", strtotime("+".$i." day")));
            if ($Holiday || date("N", strtotime("+".$i." day")) == 6) {

                $from = date("Y-m-d", strtotime("+".$i." day"));
                $to = date("Y-m-d", strtotime("+".$i." day"));
                
                do {

                    if ($i > 1) {
                        $to = date("Y-m-d", strtotime("+".$i." day"));
                    }

                    $i++;

                    $Holiday = Doctrine_Core::getTable("Holiday")->findOneByDate(date("Y-m-d", strtotime("+".$i." day")));
                } while($Holiday || date("N", strtotime("+".$i." day")) == 6 || date("N", strtotime("+".$i." day")) == 7);
            }

            if (!$from || !$to) {
                $this->log("Mañana no es feriado ni sábado...");
                exit;
            } else {
                $from .= " 00:00:00";
                $to .= " 23:59:59";
            }

            $q = Doctrine_Core::getTable("Car")
                ->createQuery('C')
                ->where('C.activo = 1')
                ->andWhere('C.seguro_ok = 4')
                ->andWhere('C.user_id = 6768');

            $Cars = $q->execute();

            if (count($Cars) == 0) {
                $this->log("No hay autos disponibles");
            }

            foreach ($Cars as $Car) {

                if (!$Car->hasReserve($from, $to)) {

                    $CarTodayEmail = new CarTodayEmail();

                    $CarTodayEmail->setCar($Car);
                    $CarTodayEmail->setSentAt(date("Y-m-d H:i:s"));
                    $CarTodayEmail->save();

                    $this->log("Enviando consulta a Auto ID: ".$Car->getId());

                    $User = $Car->getUser();

                    $subject = "¡Tenemos un aumento en la demanda! Cuéntanos sobre ".$Car->getPatente();

                    $body = "<p>".$User->getFirstname().",</p>";
                    $body .= "<p>Tenemos un aumento en la demanda de autos y nos gustaría saber si tu auto patente <strong>".$Car->getPatente()."</strong> se encuentra disponible. Para confirmar la disponibilidad haz <a href='http://www.arriendas.cl/profile/auto-hoy/".$CarTodayEmail->getSignature()."'>click aquí</a>.</p>";
                    $body .= "<br>";
                    $body .= "<p style='color: #aaa; font-size:14px; margin: 0; padding: 3px 0 0 0'>Atentamente</p>";
                    $body .= "<p style='color: #aaa; font-size:14px; margin: 0; padding: 3px 0 0 0'>Equipo Arriendas.cl</p>";

                    $message = $this->getMailer()->compose();
                    $message->setSubject($subject);
                    $message->setBody($body, "text/html");
                    $message->setFrom('soporte@arriendas.cl', 'Soporte Arriendas.cl');
                    /*$message->setTo(array($User->getEmail() => $User->getFirstname()." ".$User->getLastname()));*/
                    $message->setBcc(array("cristobal@arriendas.cl" => "Cristóbal Medina Moenne"));
                    
                    $this->getMailer()->send($message);
                } else {

                    $this->log("Auto ID: ".$Car->getId()." ya posee una reserva");
                }
            }
        }
    }
}