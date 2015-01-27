<?php

class Utils {

    public static function calculateDuration($from, $to) {
        
        return floor((strtotime($to) - strtotime($from))/3600);
    }

    public static function distance($lat1, $lng1, $lat2, $lng2) {

        return ( 6371 * acos( cos( deg2rad($lat1) ) * cos( deg2rad( $lat2 ) ) * cos( deg2rad($lng2) - deg2rad($lng1) ) + sin( deg2rad($lat1) ) * sin( deg2rad( $lat2 ) ) ) );
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

    public static function isValidRUT($rut) {

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

    public static function isWeekend($getDays = false) {

        $days = array();

        $Holiday = Doctrine_Core::getTable("Holiday")->findOneByDate(date("Y-m-d"));
        if ($Holiday || date("N") == 6 || date("N") == 7) {

            if (!$getDays) {
                return true;
            }

            $days[0] = date("Y-m-d");
            $i = 1;

            $Holiday = Doctrine_Core::getTable("Holiday")->findOneByDate(date("Y-m-d", strtotime("+".$i." day")));            
            while ($Holiday || date("N", strtotime("+".$i." day")) == 6 || date("N", strtotime("+".$i." day")) == 7) {

                $days[$i] = date("Y-m-d", strtotime("+".$i." day"));
                $i++;

                $Holiday = Doctrine_Core::getTable("Holiday")->findOneByDate(date("N", strtotime("+".$i." day")));
            }

            $isWeekend = true;
        }

        if (count($days) > 0) {
            return $days;
        }

        return false;
    }
}