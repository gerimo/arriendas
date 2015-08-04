<?php

abstract class BaseImageType extends sfDoctrineRecord {

    public function setTableDefinition() {

        $this->setTableName('image_type');
        
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
    }

    public function setUp() {

        parent::setUp();
        
    }
}
