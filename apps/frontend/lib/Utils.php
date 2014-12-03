<?php

class Utils {

    public static function reportError($errorMessage, $place) {

        $mail = new Email();
        $mailer = $mail->getMailer();

        $message = $mail->getMessage()
            ->setSubject("Error ".$place." ".date("Y-m-d H:i:s"))
            ->setBody("<p>".$errorMessage."</p>", "text/html")
            ->setFrom(array("no-reply@arriendas.cl" => "Errores Arriendas.cl"))
            ->setTo(array("cristobal@arriendas.cl" => "Cristóbal Medina Moenne"));
        
        $mailer->send($message);
    }
}