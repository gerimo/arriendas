<?php

abstract class BaseGPS extends sfDoctrineRecord {

    public function setTableDefinition() {

        $this->setTableName('GPS');
        
        $this->hasColumn('id', array(
            'type' => 'integer',
            'length' => 4,
            'primary' => true,
            'autoincrement' => true            
        ));

        $this->hasColumn('description', array(
            'type' => 'string',
            'length' => 255
        ));

        $this->hasColumn('price', 'decimal', 10, array(
            'type' => 'decimal',
            'length' => 10,
            'scale' => '2',
        ));
        
        $this->option('charset', 'utf8');
        $this->option('type', 'InnoDB');
    }

    public function setUp() {

        parent::setUp();
        
    }
}

         