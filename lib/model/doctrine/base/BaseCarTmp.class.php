<?php

abstract class BaseCarTmp extends sfDoctrineRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('Car_tmp');

        $this->hasColumn('id', 'integer', 4, array(
             'type' => 'integer',
             'primary' => true,
             'autoincrement' => true,
             'length' => 4,
             ));

        $this->hasColumn('user_id', 'integer', array(
             'type' => 'integer',
             'notnull' => true,
             ));

        $this->hasColumn('City_id', 'integer', 4, array(
             'type' => 'integer',
             'notnull' => true,
             'length' => 4,
             ));

        $this->hasColumn('address', 'string', 45, array(
             'type' => 'string',
             'length' => 45,
             ));

        $this->hasColumn('lat', 'float', 10, array(
             'type' => 'float',
             'length' => 10,
             'scale' => '6',
             ));

        $this->hasColumn('capacity', 'float', 10, array(
             'type' => 'float',
             'length' => 10,
             'scale' => '6',
             ));
        $this->hasColumn('lng', 'float', 10, array(
             'type' => 'float',
             'length' => 10,
             'scale' => '6',
             ));

        $this->hasColumn('model_id', 'integer', 4, array(
             'type' => 'integer',
             'notnull' => true,
             'length' => 4,
             ));

        $this->hasColumn('year', 'integer', 4, array(
             'type' => 'integer',
             'length' => 4,
             ));

        $this->hasColumn('patente', 'string', 10, array(
             'type' => 'string',
             'length' => 10,
             ));

        $this->hasColumn('color', 'string', 20, array(
             'type' => 'string',
             'length' => 20,
             ));

        $this->hasColumn('tipobencina', 'string', 50, array(
             'type' => 'string',
             'length' => 50,
             ));

        $this->hasColumn('doors', 'integer', 11, array(
             'type' => 'integer',
             'length' => 11,
             ));

       $this->hasColumn('transmission', 'integer', 4, array(
             'type' => 'integer',
             'length' => 4,
             ));

       $this->hasColumn('accesoriosSeguro', 'string', 255, array(
             'type' => 'string',
             'length' => 255,
             ));

        $this->hasColumn('fecha_subida', 'timestamp', null, array(
             'type' => 'timestamp',
             ));

        $this->hasColumn('baby_chair', 'boolean', null, array(
            'type' => 'boolean'
            ));

        $this->hasColumn('is_airport_delivery', 'boolean', null, array(
            'type' => 'boolean'
        ));

        $this->hasColumn('commune_id', 'integer', 11, array(
            'notnull' => true
        ));

        $this->hasColumn('canceled', 'boolean', null, array(
            'type' => 'boolean'
        ));

        $this->hasColumn('car_id', 'integer', 11, array(
        ));

        $this->option('charset', 'utf8');
        $this->option('type', 'InnoDB');
    }

    public function setUp() {

        parent::setUp();

    }
}