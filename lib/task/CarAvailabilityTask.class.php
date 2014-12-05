<?php

// Este task debería correr todos los días
// Este task revisa si mañana es feriado o fin de semana y envía un correo a los dueños
//  de autos disponibles, preguntando si su auto realmente estrará disponible el fin de semana.

// FALTA VER UNA FORMA DE NO ENVIAR DENUEVO LOS CORREOS SI EL TASK SE LLEGASE A CORRER AGAIN

class CarAvailabilityTask extends sfBaseTask {

    protected function configure() {

        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'frontend'),
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
            new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'doctrine')
        ));

        $this->namespace = 'arriendas';
        $this->name = 'carAvailability';
        $this->briefDescription = '';
        $this->detailedDescription = <<<EOF
The [CarAvailability|INFO] task does things.
Call it with:

  [php symfony arriendas:carAvailability|INFO]
EOF;
    }

    protected function execute($arguments = array(), $options = array()) {

        $config = ProjectConfiguration::getApplicationConfiguration("frontend", "prod", TRUE);
        sfContext::createInstance($config);

        // initialize the database connection
        $databaseManager = new sfDatabaseManager($this->configuration);
        $conn = $databaseManager->getDatabase($options['connection'])->getConnection();

        try {

            $this->log("Chequeando...");

            $Holiday = Doctrine_Core::getTable("Holiday")->findOneByDate(date("Y-m-d"));
            if (date("N") == 6 || date("N") == 7 || $Holiday) {
                $this->log("Es fin de semana o festivo. Terminado");
                exit;
            }

            $Holiday = Doctrine_Core::getTable("Holiday")->findOneByDate(date("Y-m-d", strtotime("+1 day")));
            if ($Holiday || date("N", strtotime("+1 day")) == 6) {

                $day  = date("Y-m-d");
                $i    = 0;
                $from = $day;
                
                do {

                    $to = $day;

                    $i++;

                    $day = date("Y-m-d", strtotime("+".$i." day"));

                    $Holiday = Doctrine_Core::getTable("Holiday")->findOneByDate(date("Y-m-d", strtotime($day)));
                } while($Holiday || date("N", strtotime($day)) == 6 || date("N", strtotime($day)) == 7);
            } else {

                $this->log("Mañana no es sabado ni festivo. Terminado");
                exit;
            }

            $week = array(
                1 => "Lunes",
                2 => "Martes",
                3 => "Miércoles",
                4 => "Jueves",
                5 => "Viernes",
                6 => "Sábado",
                7 => "Domingo"
            );

            if ($options['env'] == 'dev') {
                $host = 'http://test.arriendas.cl';
            } else {
                $host = 'http://www.arriendas.cl';
            }

            $routing = $this->getRouting();
            $imageUrl = $host . $routing->generate('availabilityEmailOpen');

            $from .= " 12:00:00";
            $to   .= " 23:59:59";

            $q = Doctrine_Core::getTable("Car")
                ->createQuery('C')
                ->where('C.activo = 1')
                ->andWhere('C.seguro_ok = 4')
                ->andWhere('C.user_id = 6768');

            $Cars = $q->execute();

            if (!$Cars) {
                $this->log("No hay autos disponibles");
                exit;
            }

            foreach ($Cars as $Car) {

                if (!$Car->hasReserve($from, $to)) {

                    $CarAvailabilityEmail = new CarAvailabilityEmail();

                    $CarAvailabilityEmail->setCar($Car);
                    $CarAvailabilityEmail->setSentAt(date("Y-m-d H:i:s"));
                    $CarAvailabilityEmail->save();

                    $url_all_ava  = $host . $routing->generate('availability', array(
                        'id' => $CarAvailabilityEmail->getId(),
                        'o' => 1,
                        'signature' => $CarAvailabilityEmail->getSignature()
                    ));

                    $url_cus_ava  = $host . $routing->generate('availability', array(
                        'id' => $CarAvailabilityEmail->getId(),
                        'o' => 0,
                        'signature' => $CarAvailabilityEmail->getSignature()
                    ));

                    $this->log("Enviando consulta a auto ID: ".$Car->getId());

                    $User = $Car->getUser();

                    $subject = "¡Se vienen días movidos! Y nos gustaría saber sobre tu auto patente ".strtoupper($Car->getPatente());

                    $body = "<p>".$User->getFirstname().",</p>";
                    $body .= "<p>Generalmente, los fines de semana y festivos tenemos un aumento considerable de personas que buscan arrendar uno o más autos, pero la disponibilidad de estos no siempre se encuentra actualizada. Con el fin de entregar un mejor servicio tanto a ti como a los arrendatarios, necesitamos que nos cuentes de la disponibilidad de tu auto desde hoy al medio día hasta el ".$week[date("N", strtotime($to))] ." ". date("j", strtotime($to)).".</p>";
                    $body .= "<p>Si tu auto estará disponible por los siguientes días, por favor, indícanoslo haciendo <a href='{$url_all_ava}'>click aquí</a>.</p>";
                    $body .= "<p>Si tu auto estará disponible sólo en algún(os) periodo(s) de los siguientes días, puedes indicarnos en <a href='{$url_cus_ava}'>la siguientes sección</a>.</p>";
                    $body .= "<br>";
                    $body .= "<p>Indicándonos la disponibilidad de tu auto para los siguientes días aumentas las posibilidades de arrendarlo.</p>";                    
                    $body .= "<br>";
                    $body .= "<p style='color: #aaa; font-size:14px; margin: 0; padding: 3px 0 0 0'>Atentamente</p>";
                    $body .= "<p style='color: #aaa; font-size:14px; margin: 0; padding: 3px 0 0 0'>Equipo Arriendas.cl</p>";
                    $body .= "<img src='{$imageUrl}?id={$CarAvailabilityEmail->getId()}'>";

                    $message = $this->getMailer()->compose();
                    $message->setSubject($subject);
                    $message->setBody($body, "text/html");
                    $message->setFrom('soporte@arriendas.cl', 'Soporte Arriendas.cl');
                    /*$message->setTo(array($User->getEmail() => $User->getFirstname()." ".$User->getLastname()));*/
                    $message->setBcc(array(
                            "cristobal@arriendas.cl" => "Cristóbal Medina Moenne"
                        ));
                    
                    $this->getMailer()->send($message);
                } else {

                    $this->log("Auto ID: ".$Car->getId()." ya posee una reserva");
                }
            }
        } catch (Exeception $e) {

            Utils::reportError($e->getMessage(), "profile/CarAvailabilityTask");
        }
    }
}