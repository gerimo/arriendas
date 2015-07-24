<?php

abstract class BaseGPSTransaction extends sfDoctrineRecord {

    public function setTableDefinition() {

        $this->setTableName('gps_transaction');
        
        $this->hasColumn('id', array(
            'type' => 'integer',
            'length' => 4,
            'primary' => true,
            'autoincrement' => true            
        ));

        $this->hasColumn('car_id', array(
            'type' => 'integer',
            'notnull' => true,
            'length' => 4,
        ));

        $this->hasColumn('car_tmp_id', array(
            'type' => 'integer',
            'notnull' => true,
            'length' => 4,
        ));

        $this->hasColumn('gps_id', array(
            'type' => 'integer',
            'notnull' => true,
            'length' => 4,
        ));

        $this->hasColumn('completed', 'boolean', null, array(
             'type' => 'boolean',
             'notnull' => true,
             'default' => 0,
        ));

        $this->hasColumn('paid_on', array(
            'type' => 'datetime',
            'notnull' => true
        ));

        $this->hasColumn('amount', 'decimal', 10, array(
            'type' => 'decimal',
            'length' => 10,
            'scale' => '2',
        ));

        $this->hasColumn('viewed', 'boolean', null, array(
             'type' => 'boolean',
             'notnull' => true,
             'default' => 0,
        ));
        
        $this->option('charset', 'utf8');
        $this->option('type', 'InnoDB');
    }

    public function setUp() {

        parent::setUp();
        
        $this->hasOne('GPS', array(
            'local' => 'gps_id',
            'foreign' => 'id',
            'onDelete' => 'no action',
            'onUpdate' => 'no action'
        ));

        $this->hasOne('Car', array(
            'local' => 'car_id',
            'foreign' => 'id',
            'onDelete' => 'no action',
            'onUpdate' => 'no action'
        ));
    }
} 