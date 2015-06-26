<?php

abstract class BaseImage extends sfDoctrineRecord {

    public function setTableDefinition() {

        $this->setTableName('image');
        
        $this->hasColumn('id', array(
            'type' => 'integer',
            'length' => 4,
            'primary' => true,
            'autoincrement' => true            
        ));

        $this->hasColumn('path', array(
            'type' => 'string',
            'length' => 255
        ));

        $this->hasColumn('width', array(
            'type' => 'integer',
            'length' => 11        
        ));

        $this->hasColumn('Height', array(
            'type' => 'integer',
            'length' => 11          
        ));

        $this->hasColumn('size', array(
            'type' => 'integer',
            'length' => 11          
        ));

        $this->hasColumn('isOnS3', 'boolean', null, array(
         'type' => 'boolean',
         'notnull' => true,
         'default' => 0,
         ));
        
        $this->option('charset', 'utf8');
        $this->option('type', 'InnoDB');
    }

    public function setUp() {

        parent::setUp();
        
    }
}

         