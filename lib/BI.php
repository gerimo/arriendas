<?php

class BI {

    // OPORTUNIDADES

    // KPI1 = Arriendos por OPP / Total arriendos
    public static function OppKPI1($from, $to) {

        $q = Doctrine_Core::getTable("Reserve")
            ->createQuery('R')
            ->innerJoin('R.Transaction T')
            ->where('T.completed = 1')
            ->andWhere('R.fecha_pago >= ?', $from)
            ->andWhere('R.fecha_pago <= ?', $to);

        $AllReserves = $q->execute();

        $q = Doctrine_Core::getTable("Reserve")
            ->createQuery('R')
            ->innerJoin('R.Transaction T')
            ->where('R.comentario LIKE "Reserva oportunidad%"')
            ->andWhere('T.completed = 1')
            ->andWhere('R.fecha_pago >= ?', $from)
            ->andWhere('R.fecha_pago <= ?', $to);

        $OppReserves = $q->execute();

        return round($OppReserves->count()/$AllReserves->count(), 2) * 100;
    }

    // KPI2 = Opp generadas / Opp pagadas
    public static function OppKPI2($from, $to) {

        $q = Doctrine_Core::getTable("Reserve")
            ->createQuery('R')
            ->where('R.comentario LIKE "Reserva oportunidad%"')
            ->andWhere('R.fecha_pago >= ?', $from)
            ->andWhere('R.fecha_pago <= ?', $to);

        $G = $q->execute();

        $q = Doctrine_Core::getTable("Reserve")
            ->createQuery('R')
            ->innerJoin('R.Transaction T')
            ->where('R.comentario LIKE "Reserva oportunidad%"')
            ->andWhere('T.completed = 1')
            ->andWhere('R.fecha_pago >= ?', $from)
            ->andWhere('R.fecha_pago <= ?', $to);

        $P = $q->execute();

        return round($G->count()/$P->count(), 2);
    }
    
    // KPI3 = Opp modulo página / Opp pagadas
    public static function OppKPI3($from, $to) {

        $q = Doctrine_Core::getTable("Reserve")
            ->createQuery('R')
            ->innerJoin('R.Transaction T')
            ->where('R.comentario = "Reserva oportunidad"')
            ->andWhere('T.completed = 1')
            ->andWhere('R.fecha_pago >= ?', $from)
            ->andWhere('R.fecha_pago <= ?', $to);

        $M = $q->execute();

        $q = Doctrine_Core::getTable("Reserve")
            ->createQuery('R')
            ->innerJoin('R.Transaction T')
            ->where('R.comentario LIKE "Reserva oportunidad%"')
            ->andWhere('T.completed = 1')
            ->andWhere('R.fecha_pago >= ?', $from)
            ->andWhere('R.fecha_pago <= ?', $to);

        $T = $q->execute();

        return round($M->count()/$T->count(), 2) * 100;
    }

    // KPI4 = Opp mailing / Opp pagadas
    public static function OppKPI4($from, $to) {

        $q = Doctrine_Core::getTable("Reserve")
            ->createQuery('R')
            ->innerJoin('R.Transaction T')
            ->where('R.comentario = "Reserva oportunidad - mailing"')
            ->andWhere('T.completed = 1')
            ->andWhere('R.fecha_pago >= ?', $from)
            ->andWhere('R.fecha_pago <= ?', $to);

        $M = $q->execute();

        $q = Doctrine_Core::getTable("Reserve")
            ->createQuery('R')
            ->innerJoin('R.Transaction T')
            ->where('R.comentario LIKE "Reserva oportunidad%"')
            ->andWhere('T.completed = 1')
            ->andWhere('R.fecha_pago >= ?', $from)
            ->andWhere('R.fecha_pago <= ?', $to);

        $T = $q->execute();

        return round($M->count()/$T->count(), 2) * 100;
    }

    // KPI5 = Opp backend / Opp pagadas
    public static function OppKPI5($from, $to) {

        $q = Doctrine_Core::getTable("Reserve")
            ->createQuery('R')
            ->innerJoin('R.Transaction T')
            ->where('R.comentario = "Reserva oportunidad - backend"')
            ->andWhere('T.completed = 1')
            ->andWhere('R.fecha_pago >= ?', $from)
            ->andWhere('R.fecha_pago <= ?', $to);

        $M = $q->execute();

        $q = Doctrine_Core::getTable("Reserve")
            ->createQuery('R')
            ->innerJoin('R.Transaction T')
            ->where('R.comentario LIKE "Reserva oportunidad%"')
            ->andWhere('T.completed = 1')
            ->andWhere('R.fecha_pago >= ?', $from)
            ->andWhere('R.fecha_pago <= ?', $to);

        $T = $q->execute();

        return round($M->count()/$T->count(), 2) * 100;
    }

    // KPI6 = Opp auto con disponibilidad / Opp pagadas
    public static function OppKPI6($from, $to) {

        $q = Doctrine_Core::getTable("Reserve")
            ->createQuery('R')
            ->innerJoin('R.Transaction T')
            ->where('R.comentario = "Reserva oportunidad - auto con disponibilidad"')
            ->andWhere('T.completed = 1')
            ->andWhere('R.fecha_pago >= ?', $from)
            ->andWhere('R.fecha_pago <= ?', $to);

        $M = $q->execute();

        $q = Doctrine_Core::getTable("Reserve")
            ->createQuery('R')
            ->innerJoin('R.Transaction T')
            ->where('R.comentario LIKE "Reserva oportunidad%"')
            ->andWhere('T.completed = 1')
            ->andWhere('R.fecha_pago >= ?', $from)
            ->andWhere('R.fecha_pago <= ?', $to);

        $T = $q->execute();

        return round($M->count()/$T->count(), 2) * 100;
    }
}