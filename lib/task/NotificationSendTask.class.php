<?php

class NotificationSendTask extends sfBaseTask {

    protected function configure() {

        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'frontend'),
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'local'),
            new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'doctrine')
        ));

        $this->namespace = 'notification';
        $this->name = 'send';
        $this->briefDescription = 'Envía las notificaciones pendientes de la tabla UserNotification';
        $this->detailedDescription = <<<EOF
The [send|INFO] task does things.
Call it with:

  [php symfony notification:send|INFO]
EOF;
    }

    protected function execute($arguments = array(), $options = array()) {

        $config = ProjectConfiguration::getApplicationConfiguration("frontend", "prod", TRUE);
        sfContext::createInstance($config);

        try {

            $UsersNotifications = Doctrine_core::getTable("UserNotification")->findBySentAtAsNull();

            $alreadySent = true;

            foreach ($UsersNotifications as $UserNotification) {

                $User = $UserNotification->getUser();
                $Notification = $UserNotification->getNotification();   
                $Action = $Notification->getAction();
                $Type = $Notification->getNotificationType();

                if($Notification->is_active && $Action->is_active && $Type->is_active) {

                    switch ($Type->id) {
                        case 1:
                            // se ejecuta a traves de un filtro
                            break;

                        case 2:
                            $sms = new SMS("Arriendas.cl");
                            $sms->send($Notification->message_title.chr(0x0D).chr(0x0A).$Notification->message, $User->telephone);
                            break;

                        case 3:
                            $mail    = new Email();
                            $mailer  = $mail->getMailer();
                            $message = $mail->getMessage();     

                            $subject = $Notification->message_title;
                            $body    = $Notification->message;
                            $from    = array("no-reply@arriendas.cl" => "Notificaciones Arriendas.cl");
                            $to      = array($User->email);

                            $message->setSubject($subject);
                            $message->setBody($body, 'text/html');
                            $message->setFrom($from);
                            $message->setTo($to);
                            //$message->setBcc(array("cristobal@arriendas.cl" => "Cristóbal Medina Moenne"));
                            
                            $mailer->send($message);
                            break;

                        case 4:
                            $mail    = new Email();
                            $mailer  = $mail->getMailer();
                            $message = $mail->getMessage();     

                            $subject = $Notification->message_title;
                            $body    = $Notification->message;
                            $from    = array("no-reply@arriendas.cl" => "Notificaciones Arriendas.cl");
                            $to      = array("soporte@arriendas.cl");

                            $message->setSubject($subject);
                            $message->setBody($body, 'text/html');
                            $message->setFrom($from);
                            $message->setTo($to);
                            //$message->setBcc(array("cristobal@arriendas.cl" => "Cristóbal Medina Moenne"));
                            
                            $mailer->send($message);
                            break;
                          
                        default:
                            $alreadySent=false;
                            break;
                    }  
                    if($alreadySent){
                        $UserNotification->setSentAt(date("Y-m-d H:i:s"));
                        $UserNotification->save();
                    }      
                
                }      
            }

        } catch (Exeception $e) {
            error_log("[".date("Y-m-d H:i:s")."] [NotificationSendTask] ERROR: ".$e->getMessage());
        }
    }
}