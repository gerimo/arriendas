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

            /*$Holiday = Doctrine_Core::getTable("Holiday")->findOneByDate(date("Y-m-d"));
            if (date("N") == 6 || date("N") == 7 || $Holiday) {
                $this->log("Es fin de semana o festivo. Terminado");
                exit;
            }*/

            $week = array(
                1 => "Lunes",
                2 => "Martes",
                3 => "Miércoles",
                4 => "Jueves",
                5 => "Viernes",
                6 => "Sábado",
                7 => "Domingo"
            );

            /*$Holiday = Doctrine_Core::getTable("Holiday")->findOneByDate(date("Y-m-d", strtotime("+1 day")));
            if ($Holiday || date("N", strtotime("+1 day")) == 6) {*/

                $day  = date("Y-m-d");
                $i    = 0;
                $from = $day;

                $days = array();

                do {

                    $days[] = $week[date("N", strtotime($day))];

                    $to = $day;

                    $i++;

                    $day = date("Y-m-d", strtotime("+".$i." day"));

                    $Holiday = Doctrine_Core::getTable("Holiday")->findOneByDate(date("Y-m-d", strtotime($day)));
                } while($Holiday || date("N", strtotime($day)) == 6 || date("N", strtotime($day)) == 7);
            /*} else {

                $this->log("Mañana no es sabado ni festivo. Terminado");
                exit;
            }*/

            $daysCount = count($days);
            $daysPhrase = "";
            foreach 1($days as $i => $day){

                /*if ($i > 0) {*/
                    
                    $daysPhrase .= $day;
                    
                    if ($i < $daysCount - 2) {
                        $daysPhrase .= ", ";
                    } elseif ($i < $daysCount - 1) {
                        $daysPhrase .= " y ";
                    }
                /*}*/
            }

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
                ->andWhereNotIn('C.id', array(398123,398144,398145,397147,398173,398174,397413,397925,397949,398211,398213,397967,397968,398238,398261,398274,398275,397891,398215,397840,396878,398224,398066,398068,397547,398111,398198,398199,398234,396712,398241,398079,397063,397270,396714,396675,397569,397588,398101,397947,397948,397606,396741,354,398004,396742,396609,397441,398222,396820,396643,398113,398005,398110,397882,397916,396659,397460,397207,397483,396736,397765,397766,397268,397813,397817,398016,398130,397902,396880,397395,396940,396576,397396,397919,398037,396620,396471,397615,397995,397517,397267,396770,397918,397789,397950))
                ->orderBy('C.ratio_aprobacion DESC')
                ->limit(1);

            $Cars = $q->execute();

            if (count($Cars) == 0) {
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
                        'o' => 2,
                        'signature' => $CarAvailabilityEmail->getSignature()
                    ));

                    $url_one_ava  = $host . $routing->generate('availability', array(
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

                    $firstname = ucfirst(strtolower($User->getFirstname()));
                    $lastname = ucfirst(strtolower($User->getLastname()));

                    $subject = "¿Tienes disponibilidad para recibir clientes este fin de semana?";

                    $body = "<p>".$firstname.",</p>";
                    $body .= "<p>Necesitaríamos que nos indiques en qué horarios podrías recibir clientes, para que tu auto figure en las busquedas de mañana.</p>";
                    $body .= "<ul>";
                    $body .= "<li>Si puedes recibir clientes el ".$daysPhrase." entre 8am y 8pm, has <a href='{$url_all_ava}'>click aquí</a>.</li>";
                    $body .= "<li>Si sólo puedes recibir clientes el ".$days[0]." entre 8am y 8pm, has <a href='{$url_one_ava}'>click aquí</a>.</li>";
                    $body .= "<li>Si puedes recibir clientes en horarios específicos, has <a href='{$url_cus_ava}'>click aquí</a>.</li>";
                    $body .= "</ul>";
                    $body .= "<p>Se te informará con un mínimo de 3 horas de anticipación para que puedas gestionar la entrega. Tu auto figurará como disponible para el pago, para reservas iniciadas en esos horarios.</p>";
                    $body .= "<br>";
                    $body .= "<p style='color: #aaa; font-size:14px; margin: 0; padding: 3px 0 0 0'>Atentamente</p>";
                    $body .= "<p style='color: #aaa; font-size:14px; margin: 0; padding: 3px 0 0 0'>Equipo Arriendas.cl</p>";
                    $body .= "<img src='{$imageUrl}?id={$CarAvailabilityEmail->getId()}'>";

                    $message = $this->getMailer()->compose();
                    $message->setSubject($subject);
                    $message->setBody($body, "text/html");
                    $message->setFrom('soporte@arriendas.cl', 'Soporte Arriendas.cl');
                    $message->setTo(array($User->getEmail() => $firstname." ".$lastname));
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