<?php

class Utils {

    public static function calculateDuration($from, $to) {
        
        return floor((strtotime($to) - strtotime($from))/3600);
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
}