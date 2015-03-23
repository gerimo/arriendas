<?php

class Utils {

    public static function calculateDuration($from, $to) {
        
        return floor((strtotime($to) - strtotime($from))/3600);
    }

    public static function distance($lat1, $lng1, $lat2, $lng2) {

        return ( 6371 * acos( cos( deg2rad($lat1) ) * cos( deg2rad( $lat2 ) ) * cos( deg2rad($lng2) - deg2rad($lng1) ) + sin( deg2rad($lat1) ) * sin( deg2rad( $lat2 ) ) ) );
    }

    public static function reportError($errorMessage, $place) {

        /*$mail = new Email();
        $mailer = $mail->getMailer();*/

        $mailer = sfContext::getInstance()->getMailer();

        $message = $mailer->compose()
        /*$message = $mail->getMessage()*/
            ->setSubject("Error ".$place." ".date("Y-m-d H:i:s"))
            ->setBody("<p>".$errorMessage."</p>", "text/html")
            ->setFrom(array("no-reply@arriendas.cl" => "Errores Arriendas.cl"))
            ->setTo(array("cristobal@arriendas.cl" => "Cristóbal Medina Moenne"));
        
        $mailer->send($message);
        /*$mailer->send($message);*/
    }

    public static function isValidRUT($rut) {

        $rut = str_replace(array('.', ',', '-', ' '), '', $rut);

        $dv     = substr($rut, -1);
        $number = substr($rut, 0, -1);

        $s = 1;
        for($m = 0; $number != 0; $number /= 10) {
            $s = ($s + $number % 10 * (9 - $m++ % 6)) % 11;
        }

        $newDv = chr($s ? $s + 47 : 75);

        if (strtolower($newDv) == strtolower($dv)) {
            return $rut;
        }

        return false;
    }

    public static function isWeekend($getDays = false, $tomorrow = false) {

        $days = array();

        $tomorrow ? $i = 1 : $i = 0;

        $Holiday = Doctrine_Core::getTable("Holiday")->findOneByDate(date("Y-m-d", strtotime("+".$i." day")));
        if ($Holiday || date("N", strtotime("+".$i." day")) == 6 || date("N", strtotime("+".$i." day")) == 7) {

            if (!$getDays) {
                return true;
            }

            $days[] = date("Y-m-d", strtotime("+".$i." day"));
            $i++;

            $Holiday = Doctrine_Core::getTable("Holiday")->findOneByDate(date("Y-m-d", strtotime("+".$i." day")));
            while ($Holiday || date("N", strtotime("+".$i." day")) == 6 || date("N", strtotime("+".$i." day")) == 7) {

                $days[] = date("Y-m-d", strtotime("+".$i." day"));
                $i++;

                $Holiday = Doctrine_Core::getTable("Holiday")->findOneByDate(date("Y-m-d", strtotime("+".$i." day")));
            }
        }

        if (count($days) > 0) {
            return $days;
        }

        return false;
    }

    public static function validateDates($from, $to) {

        $fromPlus1H = strtotime("+1 Hours", strtotime($from));

        $from = strtotime($from);
        $to   = strtotime($to);

        if ($to > $from && $fromPlus1H > $to) {
            return "La fecha de término debe ser al menos 1 hora superior a la fecha de inicio.";
        }

        if ($from >= $to) {
            return "La fecha de inicio debe ser menor a la fecha de término.";
        }

        return false;
    }
}