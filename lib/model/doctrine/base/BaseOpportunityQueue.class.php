<?php

abstract class BaseOpportunityQueue extends sfDoctrineRecord {

    public function setTableDefinition() {

        $this->setTableName('OpportunityQueue');
        
        $this->hasColumn('id', array(
             'type' => 'integer',
             'primary' => true,
             'autoincrement' => true,
             'length' => 4,
             ));
        $this->hasColumn('iteration', array(
             'type' => 'integer',
             'notnull' => true,
             'default' => 1,
             'length' => 4,
             ));
        $this->hasColumn('paid_at', array(
             'type' => 'datetime',
             'notnull' => true,
             ));
        $this->hasColumn('last_iteration_at', array(
             'type' => 'timestamp',
             'notnull' => false,
             ));
        $this->hasColumn('reserve_id', array(
             'type' => 'integer',
             'notnull' => true,
             'length' => 4,
             ));
        $this->hasColumn('is_active', array(
             'type' => 'boolean',
             'notnull' => true,
             'default' => 1,
             ));
        $this->hasColumn('final_notice', array(
             'type' => 'boolean',
             'notnull' => true,
             'default' => 0,
             ));

        $this->option('charset', 'utf8');
        $this->option('type', 'InnoDB');
    }

    public function setUp() {
        
        parent::setUp();
        
        $this->hasOne('Reserve', array(
             'local' => 'reserve_id',
             'foreign' => 'id',
             'onDelete' => 'no action',
             'onUpdate' => 'no action'));
    }
}