<?php

/**
 * BaseCarAvailabilityEmail
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $id
 * @property Car $car
 * @property timestamp $checked_at
 * @property timestamp $opened_at
 * @property timestamp $sent_at
 * @property datetime $started_at
 * @property datetime $ended_at
 * @property boolean $is_active
 * 
 * @method integer             getId()             Returns the current record's "id" value
 * @method Car                 getCar()            Returns the current record's "car" value
 * @method timestamp           getCheckedAt()      Returns the current record's "checked_at" value
 * @method timestamp           getOpenedAt()       Returns the current record's "opened_at" value
 * @method timestamp           getSentAt()         Returns the current record's "sent_at" value
 * @method datetime            getStartedAt()      Returns the current record's "started_at" value
 * @method datetime            getEndedAt()        Returns the current record's "ended_at" value
 * @method boolean             getIsActive()       Returns the current record's "is_active" value
 * 
 * @method CarAvailabilityEmail       setId()             Sets the current record's "id" value
 * @method CarAvailabilityEmail       setCar()            Sets the current record's "car" value
 * @method CarAvailabilityEmail       setCheckedAt()      Sets the current record's "checked_at" value
 * @method CarAvailabilityEmail       setOpenedAt()       Sets the current record's "opened_at" value
 * @method CarAvailabilityEmail       setSentAt()         Sets the current record's "sent_at" value
 * @method CarAvailabilityEmail       setStartedAt()      Sets the current record's "started_at" value
 * @method CarAvailabilityEmail       setEndedAt()        Sets the current record's "ended_at" value
 * @method CarAvailabilityEmail       setIsActive()       Sets the current record's "is_active" value
 * 
 * @package    CarAvailabilityEmailSharing
 * @subpackage model
 * @author     Cristóbal Medina Moenne
 * @version    SVN: $Id: Builder.php 7490 2014-11-25 10:07:27Z jwage $
 */
abstract class BaseCarAvailabilityEmail extends sfDoctrineRecord {

    public function setTableDefinition() {

        $this->setTableName('CarAvailabilityEmail');
      
        $this->hasColumn('id', 'integer', 11, array(
            'type' => 'integer',
            'primary' => true,
            'autoincrement' => true,
            'length' => 11,
        ));
        $this->hasColumn('car_id', 'integer', 11, array(
            'type' => 'integer',
            'notnull' => true
        ));
        $this->hasColumn('checked_at', 'timestamp', null, array(
            'type' => 'timestamp',
        ));
        $this->hasColumn('opened_at', 'timestamp', null, array(
            'type' => 'timestamp',
        ));
        $this->hasColumn('remembered_at', 'timestamp', null, array(
            'type' => 'timestamp',
        ));
        $this->hasColumn('sent_at', 'timestamp', null, array(
            'type' => 'timestamp',
        ));
        $this->hasColumn('started_at', 'datetime', null, array(
            "type" => "datetime",
            'notnull' => true
        ));
        $this->hasColumn('ended_at', 'datetime', null, array(
            "type" => "datetime",
            'notnull' => true
        ));
        $this->hasColumn('is_active', 'boolean', null, array(
            "type" => "boolean",
            "default" => true
        ));

        $this->index('id_UNIQUE', array(
            'fields' => array(
                0 => 'id',
            ),
            'type' => 'unique',
        ));
        $this->index('fk_CarAvailabilityEmail_Car', array(
            'fields' => array(
                0 => 'car_id',
            ),
        ));

        $this->option('charset', 'utf8');
        $this->option('type', 'InnoDB');
    }

    public function setUp() {

        parent::setUp();
        
        $this->hasOne('Car', array(
            'local' => 'car_id',
            'foreign' => 'id',
            'onDelete' => 'no action',
            'onUpdate' => 'no action'
        ));
    }
}