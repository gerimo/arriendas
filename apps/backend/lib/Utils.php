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

                $Holiday = Doctrine_Core::getTable("Holiday")->findOneByDate(date("N", strtotime("+".$i." day")));
            }
        }

        if (count($days) > 0) {
            return $days;
        }

        return false;
    }
}