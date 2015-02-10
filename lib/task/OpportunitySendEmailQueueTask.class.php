<?php

class OpportunitySendEmailQueueTask extends sfBaseTask {

    protected function configure() {

        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'frontend'),
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'local'),
            new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'doctrine')
        ));

        $this->namespace = 'opportunity';
        $this->name = 'send';
        $this->briefDescription = '';
        $this->detailedDescription = <<<EOF
The [OpportunitySendEmailQueueTask|INFO] task does things.
Call it with:

  [php symfony opportunity:send|INFO]
EOF;
    }

    protected function execute($arguments = array(), $options = array()) {

        $config = ProjectConfiguration::getApplicationConfiguration("frontend", "prod", TRUE);
        $context = sfContext::createInstance($this->configuration);
        $context->getConfiguration()->loadHelpers('Partial');

        // initialize the database connection
        $databaseManager = new sfDatabaseManager($this->configuration);
        $conn = $databaseManager->getDatabase($options['connection'])->getConnection();

        // Este task debiera correr cada 1 minuto app
        // Este task toma un registro por cada reserva y envia un correo de opportunidad al dueño

        try {

            $host = 'http://local.arriendas.cl';
            if ($options['env'] == 'dev') {
                $host = 'http://dev.arriendas.cl';
            } elseif ($options['env'] == 'prod') {
                $host = 'http://www.arriendas.cl';
            }

            $routing = $this->getRouting();

            // Se obtiene una oportunidad por cada reserva diferente
            $q = Doctrine_Core::getTable('OpportunityEmailQueue')
                ->createQuery('OEQ')
                ->where('OEQ.sended_at IS NULL');

            $OpportunityEmails = $q->execute();

            foreach($OpportunityEmails as $OpportunityEmail) {

                $Owner   = $OpportunityEmail->getCar()->getUser();
                $Reserve = $OpportunityEmail->getReserve();

                $acceptUrl  = $host . $routing->generate('opportunities_mailing_approve', array(
                    'reserve_id' => $OpportunityEmail->getReserve()->id,
                    'car_id'     => $OpportunityEmail->getCar()->id,
                    'signature'  => $OpportunityEmail->getReserve()->getSignature(),
                ));

                $imageUrl   = $host . $routing->generate('opportunities_mailing_open', array(
                    'id'         => $OpportunityEmail->id,
                    'signature'  => $OpportunityEmail->getSignature()
                ));

                $this->log("[".date("Y-m-d H:i:s")."] Envio de correo de opportunidad a {$Owner->firstname} {$Owner->lastname}, Reserva {$Reserve->id}");

                $subject = "Oportunidad especial para arrendar tu auto patente ".strtoupper($OpportunityEmail->getCar()->patente);
                $body    = get_partial('emails/opportunityMailing', array('Reserve' => $Reserve, 'Car' => $OpportunityEmail->getCar(), "acceptUrl" => $acceptUrl, "imageUrl" => $imageUrl));
                $from    = array("soporte@arriendas.cl" => "Oportunidades Arriendas.cl");
                $to      = array($Owner->email => $Owner->firstname." ".$Owner->lastname);

                /*$this->log("[".date("Y-m-d H:i:s")."] Enviando a ".$Owner->firstname." ".$Owner->lastname);*/
                /*$this->log("[".date("Y-m-d H:i:s")."] URL Apertura".$imageUrl);*/
                /*$this->log("[".date("Y-m-d H:i:s")."] URL Aprobacion".$acceptUrl);*/

                $message = $this->getMailer()->compose();
                $message->setSubject($subject);
                $message->setBody($body."<br><br>USER: ".$Owner->email, "text/html");
                $message->setFrom($from);
                /*$message->setTo($to);*/
                $message->setBcc(array(
                    "cristobal@arriendas.cl" => "Cristóbal Medina Moenne"
                    "francoinostrozah@gmail.com" => "Franco Inostroza Hinojosa"
                ));
                
                $this->getMailer()->send($message);

                $OpportunityEmail->setSendedAt(date('Y-m-d H:i:s'));
                $OpportunityEmail->save();
            }
        } catch (Execption $e) {

            $this->log("[".date("Y-m-d H:i:s")."] ERROR: ".$e->getMessage());

            if ($options['env'] == 'prod') {
                Utils::reportError($e->getMessage(), "OpportunitySendEmailQueueTask");
            }
        }
    }
}