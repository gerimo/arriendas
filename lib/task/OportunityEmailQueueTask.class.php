<?php

class OportunityEmailQueueTask extends sfBaseTask {

    protected function configure() {

        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'frontend'),
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
            new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'doctrine'),
            new sfCommandOption('numberOfMails', null, sfCommandOption::PARAMETER_REQUIRED, 'Cantidad de correos', 16),
        ));

        $this->namespace = 'arriendas';
        $this->name = 'oportunityEmailQueueTask';
        $this->briefDescription = '';
        $this->detailedDescription = <<<EOF
The [OportunityEmailQueueTask|INFO] task does things.
Call it with:

  [php symfony arriendas:oportunityEmailQueueTask|INFO]
EOF;
    }

    protected function execute($arguments = array(), $options = array()) {

        $config = ProjectConfiguration::getApplicationConfiguration("frontend", "prod", TRUE);
        $context = sfContext::createInstance($this->configuration);

        // initialize the database connection
        $databaseManager = new sfDatabaseManager($this->configuration);
        $conn = $databaseManager->getDatabase($options['connection'])->getConnection();

        // Este task debiera correr cada 1 minuto app
        // Este task toma un registro por cada reserva y envia un correo de oportunidad al dueño

        try {
            if ($options['env'] == 'dev') {
                $host = 'http://test.arriendas.cl';
            } else {
                $host = 'http://www.arriendas.cl';
            }

            $routing    = $this->getRouting();
            $imageUrl   = $host . $routing->generate('checkOpenedEmail');

            // Se obtiene una oportunidad por cada reserva diferente
            $table = Doctrine_Core::getTable('OportunityEmailQueue');
            $q = $table
                ->createQuery('o')
                ->where('o.sended_at IS NULL')
                ->groupBy('o.reserve_id')
                ;

            $oportunityEmails = $q->execute();

            foreach($oportunityEmails as $oportunityEmail) {

                $owner = $oportunityEmail->getOwner();
                $reserve = $oportunityEmail->getReserve();

                $acceptUrl  = $host . $routing->generate('opportunityAccept', array(
                    'reserve_id' => $oportunityEmail->getReserve()->getId(),
                    'signature' => $oportunityEmail->getReserve()->getSignature(),
                ));

                $this->log("Envio de correo de oportunidad a " . $owner->getFirstname() . " Reserva {$reserve->getId()}");

                $desde = $reserve->getDate();
                $hasta = date('Y-m-d H:i:s', strtotime($desde. " + {$reserve->getDuration()} hours"));
                $price = number_format($reserve->getPrice(), 0, ',', '.');

                $body  = "<p>Hola {$owner->getFirstname()},</p>";
                $body .= "<p>La oportunidad es por \${$price} desde {$desde} hasta {$hasta}.</p>";
                $body .= "<p>Aprueba la oportunidad haciendo <strong><a href='{$acceptUrl}'>click aquí</a></strong>.</p>";
                $body .= "<br>";
                $body .= "<p style='color: #aaa; font-size:14px; margin: 0; padding: 3px 0 0 0'>Atentamente</p>";
                $body .= "<p style='color: #aaa; font-size:14px; margin: 0; padding: 3px 0 0 0'>Equipo Arriendas.cl</p>";
                $body .= "<img src='{$imageUrl}?id={$oportunityEmail->getId()}'>";

                $message = $this->getMailer()->compose();
                $message->setSubject("Oportunidad especial para arrendar tu auto");
                $message->setBody($body, "text/html");
                $message->setFrom('soporte@arriendas.cl', 'Notificaciones Arriendas.cl');
                $message->setTo(array($oportunityEmail->getOwner()->getEmail() => $oportunityEmail->getOwner()->getFirstname()." ".$oportunityEmail->getOwner()->getLastname()));
                

                $this->getMailer()->send($message);

                $oportunityEmail->setSendedAt(date('Y-m-d H:i:s'));
                $oportunityEmail->save();
            }
        } catch (Execption $e) {

            Utils::reportError($e->getMessage(), "profile/OportunityEmailQueueTask");
        }
    }
}