<?php

class Utils {

    public static function calculateDuration($from, $to) {
        
        return floor((strtotime($to) - strtotime($from))/3600);
    }

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

    public static function validateRUT($rut) {

       $rut = str_replace(array('.', ',', '-', ' '), '', $rut);

       $dv     = substr($rut, -1);
       $number = substr($rut, 0, -1);

       $s = 1;
       for($m = 0; $number != 0; $number /= 10) {
           $s = ($s + $number % 10 * (9 - $m++ % 6)) % 11;
       }

       $newDv = chr($s ? $s + 47 : 75);

       return strtolower($newDv) == strtolower($dv);
   }
}