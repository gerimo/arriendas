<?php

class RutValidationTask extends sfBaseTask {

    protected function configure() {

        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name'),
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
            new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'doctrine'),
            new sfCommandOption('user', null, sfCommandOption::PARAMETER_REQUIRED, 'The user id', ''),
            new sfCommandOption('rut', null, sfCommandOption::PARAMETER_REQUIRED, 'The user rut', ''),
        ));

        $this->namespace = 'arriendas';
        $this->name = 'RutValidation';
        $this->briefDescription = '';
        $this->detailedDescription = <<<EOF
The [RutValidation|INFO] task does things.
Call it with:

  [php symfony RutValidation|INFO]
EOF;
    }

    protected function execute($arguments = array(), $options = array()) {

        $config = ProjectConfiguration::getApplicationConfiguration("frontend", "prod", TRUE);
        sfContext::createInstance($config);

        // initialize the database connection
        $databaseManager = new sfDatabaseManager($this->configuration);
        $connection = $databaseManager->getDatabase($options['connection'])->getConnection();

        /* arguments */
        $run = $options["rut"];
        $userid = $options["user"];

        echo "verificando...\n";

        $profile = Doctrine_Core::getTable('User')->find($userid);
        $scraperSrv = new ScraperService();
        $status = $scraperSrv->getLicenceStatus($run, $profile->getLastname());
        switch ($status) {
            case 0:
                /* problemas de conexion */
                $profile->setChequeoLicencia(false);
                /* notificaciones */
                $messageBody = "<p>Usuario: " . $profile->getFirstname() . ":</p>";
                $messageBody .= "<p>mail: " . $profile->getEmail() . ":</p>";
                $messageBody .= "<p>licencia: " . $run . ":</p>";
                $messageBody .= "<p>No se pudo chequear la licencia por problemas de conexion con la web.</p>";
                $message = $this->getMailer()->compose();
                $message->setSubject("No se pudo chequear la licencia");
                $message->setFrom('notificaciones@arriendas.cl', 'Notificaciones Arriendas');
                $message->setTo('soporte@arriendas.cl');
                $message->setBody($messageBody, "text/html");
                $this->getMailer()->send($message);

                break;
            case 1:
                /* se chequeo y no ha sido bloqueada */
                $profile->setChequeoLicencia(true);

                break;
            case 2:
                /* bloqueado */
                $profile->setBlocked(true);
                $profile->setChequeoLicencia(true);

                /* notificaciones */
                $messageBody = "<p>Usuario: " . $profile->getFirstname() . ":</p>";
                $messageBody .= "<p>mail: " . $profile->getEmail() . ":</p>";
                $messageBody .= "<p>licencia: " . $run . ":</p>";
                $messageBody .= "<p>Tiene licencia bloqueada y  por eso fue blockeado en el sistema.</p>";
                $message = $this->getMailer()->compose();
                $message->setSubject("Usuario con licencia bloqueada");
                $message->setFrom('notificaciones@arriendas.cl', 'Notificaciones Arriendas');
                $message->setTo('soporte@arriendas.cl');
                $message->setBody($messageBody, "text/html");
                $this->getMailer()->send($message);
                break;
            case 3:
                /* licencia falsa */
                $profile->setBlocked(true);
                $profile->setLicenciaFalsa(true);
                $profile->setChequeoLicencia(true);

                /* notificaciones */
                $messageBody = "<p>Usuario: " . $profile->getFirstname() . ":</p>";
                $messageBody .= "<p>mail: " . $profile->getEmail() . ":</p>";
                $messageBody .= "<p>licencia: " . $run . ":</p>";
                $messageBody .= "<p>Tiene licencia falsa y  por eso fue blockeado en el sistema.</p>";
                $message = $this->getMailer()->compose();
                $message->setSubject("Usuario con licencia falsa");
                $message->setFrom('notificaciones@arriendas.cl', 'Notificaciones Arriendas');
                $message->setTo('soporte@arriendas.cl');
                $message->setBody($messageBody, "text/html");
                $this->getMailer()->send($message);
                break;
        }

        echo " \n";
        $this->log('done.');
    }

}
