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

        // Este task debiera correr cada 1 minuto app

        // initialize the database connection
        $databaseManager = new sfDatabaseManager($this->configuration);
        $conn = $databaseManager->getDatabase($options['connection'])->getConnection();

        // Este task toma un registro por cada reserva y envia un correo de oportunidad al dueño
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

            $renter = $oportunityEmail->getRenter();

            $acceptUrl  = $host . $routing->generate('opportunityAccept', array(
                'reserve_id' => $oportunityEmail->getReserve()->getId(),
                'signature' => $oportunityEmail->getReserve()->getSignature(),
            ));

            $this->log($renter);

            $body = '<p>Correo de prueba</p>';
            $body .= "<img src='{$imageUrl}?id={$oportunityEmail->getId()}'>";
            $body .= "Para aceptar la oprtunidad presiona <a href='{$acceptUrl}'>Aqui</a>";


            $message = $this->getMailer()->compose();
            $message->setSubject("No se pudo chequear la licencia");
            $message->setFrom('notificaciones@arriendas.cl', 'Notificaciones Arriendas');
            $message->setTo('marco.pincheira.s@gmail.com');
            $message->setBody($body, "text/html");

            $this->getMailer()->send($message);

            $oportunityEmail->setSendedAt(date('Y-m-d H:i:s'));
            $oportunityEmail->save();
        }
    }
}