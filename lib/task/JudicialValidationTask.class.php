<?php

class JudicialValidationTask extends sfBaseTask {

    protected function configure() {

        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name'),
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
            new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'doctrine'),
            new sfCommandOption('user', null, sfCommandOption::PARAMETER_REQUIRED, 'The user id', ''),
            new sfCommandOption('rut', null, sfCommandOption::PARAMETER_REQUIRED, 'The user rut', ''),
        ));

        $this->namespace = 'arriendas';
        $this->name = 'JudicialValidation';
        $this->briefDescription = '';
        $this->detailedDescription = <<<EOF
The [JudicialValidation|INFO] task does things.
Call it with:

  [php symfony JudicialValidation|INFO]
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
        // $rut = substr($run, 0, -1)."-".substr($run, -1);
        $userid = $options["user"];

        echo "verificando...\n";

        $mail    = new Email();
        $mailer  = $mail->getMailer();
        $message = $mail->getMessage();            

        $profile = Doctrine_Core::getTable('User')->find($userid);
        $scraperSrv = new ScraperService();
        $statusJudicial = $scraperSrv->getCausasJudicialesStatus($run);
        switch ($statusJudicial) {
            case 0:
                /* problemas de conexion */
                $profile->setChequeoJudicial(false);
                $profile->save();

                /* notificaciones */
                /*$messageBody = "<p>Usuario: " . $profile->getFirstname() . ":</p>";
                $messageBody .= "<p>mail: " . $profile->getEmail() . ":</p>";
                $messageBody .= "<p>licencia: " . $run . ":</p>";
                $messageBody .= "<p>No se pudo verificar si contaba con causas judiciales por problemas de conexion con la web.</p>";
                $message = $this->getMailer()->compose();
                $message->setSubject("No se pudo verificar causas judiciales");
                $message->setFrom('notificaciones@arriendas.cl', 'Notificaciones Arriendas');
                $message->setTo('soporte@arriendas.cl');
                $message->setBody($messageBody, "text/html");
                $this->getMailer()->send($message); */

                $subject = "¡No se pudo verificar si contaba con causas judiciales!";
                $body    = $this->getPartial('emails/notificationOfJudicialVerification', array('User' => $profile));
                $from    = array("no-reply@arriendas.cl" => "Notificaciones Arriendas.cl");
                $to      = array("soporte@arriendas.cl");

                $message->setSubject($subject);
                $message->setBody($body, 'text/html');
                $message->setFrom($from);
                $message->setTo($to);
                //$message->setBcc(array("cristobal@arriendas.cl" => "Cristóbal Medina Moenne"));
                
                $mailer->send($message);

                break;
            case 1:
                /* se chequeo y no ha sido bloqueada */
                $profile->setChequeoJudicial(true);
                $profile->save(); 

                break;
            case 2:
                /* bloqueado */
                $profile->setBlocked(true);
                $profile->setChequeoJudicial(true);
                $profile->save();

                /* notificaciones */
                /*$messageBody = "<p>Usuario: " . $profile->getFirstname() . ":</p>";
                $messageBody .= "<p>mail: " . $profile->getEmail() . ":</p>";
                $messageBody .= "<p>licencia: " . $run . ":</p>";
                $messageBody .= "<p>Tiene causas judiciales y  por eso fue blockeado en el sistema.</p>";
                $message = $this->getMailer()->compose();
                $message->setSubject("Usuario con causas judiciales");
                $message->setFrom('notificaciones@arriendas.cl', 'Notificaciones Arriendas');
                $message->setTo('soporte@arriendas.cl');
                $message->setBody($messageBody, "text/html");
                $this->getMailer()->send($message);*/

                $subject = "¡El usuario fué bloqueado por poseer causas judiciales!";
                $body    = $this->getPartial('emails/notificationOfJudicialVerification', array('User' => $profile));
                $from    = array("no-reply@arriendas.cl" => "Notificaciones Arriendas.cl");
                $to      = array("soporte@arriendas.cl");

                $message->setSubject($subject);
                $message->setBody($body, 'text/html');
                $message->setFrom($from);
                $message->setTo($to);
                //$message->setBcc(array("cristobal@arriendas.cl" => "Cristóbal Medina Moenne"));
                
                $mailer->send($message);

                break;
        }

        echo " \n";
        $this->log('done.');
    }

}
