<?php

/**
 * Commune
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @package    CarSharing
 * @subpackage model
 * @author     Your name here
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
class Commune extends BaseCommune {

    public static function getByRegion($regionId = false) {

        $q = Doctrine_Core::getTable("Commune")
            ->createQuery('C')
            ->innerJoin('C.Region R')
            ->orderBy('C.name ASC');

        if ($regionId) {
            $q->where('R.id = ?', $regionId);
        }

        return $q->execute()->toArray();
    }
}