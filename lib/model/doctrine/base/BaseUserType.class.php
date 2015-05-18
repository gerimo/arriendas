<?php

abstract class BaseUserType extends sfDoctrineRecord {

    public function setTableDefinition() {

        $this->setTableName('UserType');
        
        $this->hasColumn('id', array(
            'type' => 'integer',
            'length' => 4,
            'primary' => true,
            'autoincrement' => true
        ));

        $this->hasColumn('name', array(
            'type' => 'string',
            'length' => 255,
            'notnull' => true
        ));

        $this->option('charset', 'utf8');
        $this->option('type', 'InnoDB');
    }

    public function setUp() {

        parent::setUp();
        
    }
}