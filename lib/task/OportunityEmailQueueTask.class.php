<?php

class OportunityEmailQueueTask extends sfBaseTask {

    protected function configure() {

        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name'),
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
        sfContext::createInstance($config);

        // Este task debiera correr cada 1 minuto app

        // initialize the database connection
        $databaseManager = new sfDatabaseManager($this->configuration);
        $conn = $databaseManager->getDatabase($options['connection'])->getConnection();

        // Este task toma un registro por cada reserva y envia un correo de oportunidad al dueño

        $table = Doctrine_Core::getTable('OportunityEmailQueue');
        $q = $table
            ->createQuery('o')
            ->where('o.sended_at IS NULL')
            ->groupBy('o.reserve_id')
            ;

        $oportunityEmails = $q->execute();

        foreach($oportunityEmails as $oportunityEmail) {

            $renter = $oportunityEmail->getRenter();

            $this->log($renter);

            $messageBody = "<p>Usuario: " . "yo" . ":</p>";
            $messageBody = "<img src=''>";
            $messageBody .= "<p>mail: " . "mi@email.com" . ":</p>";
            $messageBody .= "<p>licencia: " . "14083627-3" . ":</p>";
            $messageBody .= "<p>No se pudo chequear la licencia por problemas de conexion con la web.</p>";
            $message = $this->getMailer()->compose();
            $message->setSubject("No se pudo chequear la licencia");
            $message->setFrom('notificaciones@arriendas.cl', 'Notificaciones Arriendas');
            $message->setTo('marco.pincheira.s@gmail.com');
            $message->setBody($messageBody, "text/html");
            //$this->getMailer()->send($message);

            echo $messageBody;
        }

    }
}