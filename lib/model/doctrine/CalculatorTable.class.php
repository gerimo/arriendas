<?php

class CalculatorTable extends Doctrine_Table
{
    /**
     * Returns an instance of this class.
     *
     * @return object CalculatorTable
     */
    public static function getInstance()
    {
        return Doctrine_Core::getTable('calculator');
    }

}
