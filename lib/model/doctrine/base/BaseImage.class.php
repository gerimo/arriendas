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

        $this->hasColumn('path_original', array(
            'type' => 'string',
            'length' => 255
        ));

        $this->hasColumn('path_xs', array(
            'type' => 'string',
            'length' => 255
        ));

        $this->hasColumn('path_sm', array(
            'type' => 'string',
            'length' => 255
        ));

        $this->hasColumn('path_md', array(
            'type' => 'string',
            'length' => 255
        ));

        $this->hasColumn('path_lg', array(
            'type' => 'string',
            'length' => 255
        ));

        $this->hasColumn('car_id', array(
            'type' => 'integer',
            'notnull' => false,
            'length' => 11          
        ));

        $this->hasColumn('user_id', array(
            'type' => 'integer',
            'notnull' => false,
            'length' => 11          
        ));

        $this->hasColumn('image_type_id', array(
            'type' => 'integer',
            'length' => 11          
        ));

        $this->hasColumn('is_on_s3', 'boolean', null, array(
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

         