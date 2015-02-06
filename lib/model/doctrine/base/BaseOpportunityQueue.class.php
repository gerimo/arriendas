<?php

/**
 * BaseOpportunityQueue
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $id
 * @property integer $iteration
 * @property timestamp $paid_at
 * @property timestamp $lastIterationAt
 * @property integer $reserve_id
 * @property boolean $is_active
 * @property Reserve $Reserve
 * @property Car $Car
 * @property boolean $final_notice
 * 
 * @method integer             getId()                Returns the current record's "id" value
 * @method integer             getIteration()         Returns the current record's "iteration" value
 * @method timestamp           getPaidAt()            Returns the current record's "paid_at" value
 * @method timestamp           getLastIterationAt()   Returns the current record's "last_interation_at" value
 * @method integer             getReserveId()         Returns the current record's "reserve_id" value
 * @method boolean             getIsActive()          Returns the current record's "is_active" value
 * @method Reserve             getReserve()           Returns the current record's "Reserve" value
 * @method boolean             getFinalNotice()       Returns the current record's "final_notice" value
 *
 * @method OpportunityQueue    setId()                Sets the current record's "id" value
 * @method OpportunityQueue    setIteration()         Sets the current record's "iteration" value
 * @method OpportunityQueue    setPaidAt()            Sets the current record's "paid_at" value
 * @method OpportunityQueue    setLastIterationAt()   Sets the current record's "paid_at" value
 * @method OpportunityQueue    setReserveId()         Sets the current record's "reserve_id" value
 * @method OpportunityQueue    setIsActive()          Sets the current record's "is_active" value
 * @method OpportunityQueue    setReserve()           Sets the current record's "Reserve" value
 * @method OpportunityQueue    setFinalNotice()       Sets the current record's "final_notice" value
 * 
 * @package    CarSharing
 * @subpackage model
 * @author     Your name here
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseOpportunityQueue extends sfDoctrineRecord {

    public function setTableDefinition() {

        $this->setTableName('OpportunityQueue');
        
        $this->hasColumn('id', 'integer', 4, array(
             'type' => 'integer',
             'primary' => true,
             'autoincrement' => true,
             'length' => 4,
             ));
        $this->hasColumn('iteration', 'integer', 4, array(
             'type' => 'integer',
             'notnull' => true,
             'default' => 1,
             'length' => 4,
             ));
        $this->hasColumn('paid_at', 'timestamp', null, array(
             'type' => 'timestamp',
             'notnull' => true,
             ));
        $this->hasColumn('last_iteration_at', 'timestamp', null, array(
             'type' => 'timestamp',
             'notnull' => false,
             ));
        $this->hasColumn('reserve_id', 'integer', 4, array(
             'type' => 'integer',
             'notnull' => true,
             'length' => 4,
             ));
        $this->hasColumn('is_active', 'boolean', null, array(
             'type' => 'boolean',
             'notnull' => true,
             'default' => 1,
             ));
        $this->hasColumn('final_notice', 'boolean', null, array(
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