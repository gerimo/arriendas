<?php

/**
 * CarTable
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class CarTable extends Doctrine_Table
{
    /**
     * Returns an instance of this class.
     *
     * @return object CarTable
     */
    public static function getInstance()
    {
        return Doctrine_Core::getTable('Car');
    }
}