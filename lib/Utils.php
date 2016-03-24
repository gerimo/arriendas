<?php

class Utils {

    public static function calculateDuration($from, $to) {
        
        return floor((strtotime($to) - strtotime($from))/3600);
    }

    public static function distance($lat1, $lng1, $lat2, $lng2) {

        return ( 6371 * acos( cos( deg2rad($lat1) ) * cos( deg2rad( $lat2 ) ) * cos( deg2rad($lng2) - deg2rad($lng1) ) + sin( deg2rad($lat1) ) * sin( deg2rad( $lat2 ) ) ) );
    }

    public static function reportError($errorMessage, $place) {

        $mailer = sfContext::getInstance()->getMailer();

        $message = $mailer->compose()
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

        if (strtolower($newDv) == strtolower($dv)) {
            return $rut;
        }

        return false;
    }

    public static function isWeek($getDays = false, $tomorrow = false) {

        $days = array();

        $tomorrow ? $i = 1 : $i = 0;

        $Holiday = Doctrine_Core::getTable("Holiday")->findOneByDate(date("Y-m-d", strtotime("+".$i." day")));
        if (!$Holiday && date("N", strtotime("+".$i." day")) != 6 && date("N", strtotime("+".$i." day")) != 7) {

            if (!$getDays) {
                return true;
            }

            $days[] = date("Y-m-d", strtotime("+".$i." day"));
            $i++;

            $Holiday = Doctrine_Core::getTable("Holiday")->findOneByDate(date("Y-m-d", strtotime("+".$i." day")));
            while (!$Holiday && date("N", strtotime("+".$i." day")) != 6 && date("N", strtotime("+".$i." day")) != 7) {

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
         // variables a trabajar Formato DateTime
        $fecha = new DateTime();
        $fechaDesde = new DateTime($from);
        $fechaHasta = new DateTime($to);
        $fromPlus1H = strtotime("+1 Hours", strtotime($from));

        $from = strtotime($from);
        $to   = strtotime($to);

        if ($to > $from && $fromPlus1H > $to) {
            return "La fecha de término debe ser al menos 1 hora superior a la fecha de inicio.";
        }

        if ($from >= $to) {
            return "La fecha de inicio debe ser menor a la fecha de término.";
        }


        // diferencia entre fechas
        $dif = $fecha->diff($fechaDesde);
        $hours = $dif->h;
        $hours = $hours + $dif->days * 24;
        // si hay una diferencia de 2 o menos
        if($hours < 2 ){
            return "El horario de inicio de tu arriendo debe ser con un mínimo de 2 horas de anticipación";
        }
        
        // si la hora en mayor o igual a las 20 hrs
        if(intval($fecha->format("H")) >= 20 || intval($fecha->format("H")) < 7){
            $fechaControl = $fecha;

            if(intval($fecha->format("H")) >= 20){
                $fechaControl->add(new DateInterval('P1D'));
            }
            
            if($fechaDesde->format("d") == $fechaControl->format("d") && intval($fechaDesde->format("H")) < 9){
                return "Las reservas generadas en el sitio desde las 20.00 horas tendrán comienzo a partir de las 09:00 horas";
            }
        }

        return false;
    }
}