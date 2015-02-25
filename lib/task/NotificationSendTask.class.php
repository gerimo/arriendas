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
        $context = sfContext::createInstance($this->configuration);
        $context->getConfiguration()->loadHelpers('Partial');

        // contadores 
        $countAction2               = 0;
        $countAction3               = 0;
        $countAction4               = 0;
        $countDefaults              = 0;
        $countoTotalNotification    = 0;
        $countSents                 = 0;
        $countNotActive             = 0;

        try {
            $this->log("[".date("Y-m-d H:i:s")."] Buscando notificaciones por enviar");
            $startTime = microtime(true);

            $UsersNotifications = Doctrine_core::getTable("UserNotification")->findBySentAtAsNull();

            $this->log("[".date("Y-m-d H:i:s")."] ".count($UsersNotifications). " notificaciones encontradas");

            if (count($UsersNotifications) > 0) {
                foreach ($UsersNotifications as $UserNotification) {

                    $alreadySent = true;

                    $User         = $UserNotification->getUser();
                    $Notification = $UserNotification->getNotification();   
                    $Action       = $Notification->getAction();
                    $Type         = $Notification->getNotificationType();

                    $title   = $Notification->message_title;
                    $message = $Notification->message;

                    if($Notification->is_active && $Action->is_active && $Type->is_active) {
                        switch ($Type->id) {
                            case 1:
                                // se ejecuta a traves de un filtro
                                break;

                            case 2:
                                $SMS = new SMS("Arriendas");

                                // si el titulo es diferente de null o vacío, le añade un salto de linea.
                                if ($title) {
                                    $title = $title.chr(0x0D).chr(0x0A);
                                } else {
                                    $title = "";
                                }

                                $SMS->send($title.$message, $User->telephone);

                                $countAction2 ++;
                                break;

                            case 3:
                                $mail    = new Email();
                                $mailer  = $mail->getMailer();
                                $message = $mail->getMessage();     

                                $subject = $Notification->message_title;
                                $body    = $Notification->message;
                                $from    = array("no-reply@arriendas.cl" => "Notificaciones Arriendas.cl");
                                $to      = array($User->email => $User->firstname." ".$User->lastname);

                                $message->setSubject($subject);
                                $message->setBody($body, 'text/html');
                                $message->setFrom($from);
                                $message->setTo($to);
                                $message->setReplyTo(array("ayuda@arriendas.cl" => "Ayuda Arriendas.cl"));
                                //$message->setBcc(array("cristobal@arriendas.cl" => "Cristóbal Medina Moenne"));
                                
                                $mailer->send($message);

                                $countAction3 ++;
                                break;

                            case 4:
                                $mail    = new Email();
                                $mailer  = $mail->getMailer();
                                $message = $mail->getMessage();     

                                $subject = $Notification->message_title;
                                $body    = $Notification->message;
                                $from    = array("no-reply@arriendas.cl" => "Notificaciones Arriendas.cl");
                                $to      = array("soporte@arriendas.cl" => "Soporte Arriendas.cl");

                                $message->setSubject($subject);
                                $message->setBody($body, 'text/html');
                                $message->setFrom($from);
                                $message->setTo($to);
                                //$message->setBcc(array("cristobal@arriendas.cl" => "Cristóbal Medina Moenne"));
                                
                                $mailer->send($message);
                                $countAction4++;
                                break;
                              
                            default:
                                $alreadySent = false;
                                $countDefaults++;
                                break;
                        }

                        if ($alreadySent) {
                            $UserNotification->setSentAt(date("Y-m-d H:i:s"));
                            $UserNotification->save();
                            $countSents++;
                        }
                    } else {
                        $countNotActive++;
                    }
                     // Mensaje Log
                    $txtNotificationId  = str_pad("Notificacion ID: ".$Notification->id, 20);
                    $txtAction          = str_pad("Accion: ".$Action->name, 15);
                    $txtType            = str_pad("Tipo: ".$Type->name, 15);
                    $txtUser            = str_pad("Usuario ID: ".$User->id. "(".$User->firstname." ".$User->lastname.")",10);
                    $this->log($txtNotificationId.$txtAction.$txtType.$txtUser);      
                }
            } else {
                $this->log("[".date("Y-m-d H:i:s")."] No hay notificaciones por enviar");
            }

        } catch (Exeception $e) {
            $this->log("[".date("Y-m-d H:i:s")."] [NotificationSendTask] ERROR: ".$e->getMessage());
        }

        $endTime = microtime(true);

        $this->log("[".date("Y-m-d H:i:s")."] ---------------------------------------------------");
        $this->log("[".date("Y-m-d H:i:s")."] Total Notificaciones pendientes:          ".$countoTotalNotification);
        $this->log("[".date("Y-m-d H:i:s")."] ---------------------------------------------------");
        $this->log("[".date("Y-m-d H:i:s")."] Total Notificaciones de tipo desconocido: ".$countDefaults);
        $this->log("[".date("Y-m-d H:i:s")."] ");
        $this->log("[".date("Y-m-d H:i:s")."] ");
        $this->log("[".date("Y-m-d H:i:s")."] ---------------------------------------------------");
        $this->log("[".date("Y-m-d H:i:s")."] Total Notificaciones enviadas:            ".$countSents);
        $this->log("[".date("Y-m-d H:i:s")."] ---------------------------------------------------");
        $this->log("[".date("Y-m-d H:i:s")."] Total Notificaciones tipo SMS:            ".$countAction2);
        $this->log("[".date("Y-m-d H:i:s")."] Total Notificaciones tipo Email:          ".$countAction3);
        $this->log("[".date("Y-m-d H:i:s")."] Total Notificaciones tipo Soporte:        ".$countAction4);
        $this->log("[".date("Y-m-d H:i:s")."] ");
        $this->log("[".date("Y-m-d H:i:s")."] ");
        $this->log("[".date("Y-m-d H:i:s")."] Total Notificaciones no activadas:        ".$countNotActive);

        $this->log("[".date("Y-m-d H:i:s")."] Tiempo total de procesamiento     ".round($endTime-$startTime, 2)." segundos");
    }
}