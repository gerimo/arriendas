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
 * 
 * @method integer             getId()             Returns the current record's "id" value
 * @method Car                 getCar()            Returns the current record's "car" value
 * @method timestamp           getCheckedAt()      Returns the current record's "checked_at" value
 * @method timestamp           getOpenedAt()       Returns the current record's "opened_at" value
 * @method timestamp           getSentAt()         Returns the current record's "sent_at" value
 * 
 * @method CarAvailabilityEmail       setId()             Sets the current record's "id" value
 * @method CarAvailabilityEmail       setCar()            Sets the current record's "car" value
 * @method CarAvailabilityEmail       setCheckedAt()      Sets the current record's "checked_at" value
 * @method CarAvailabilityEmail       setOpenedAt()       Sets the current record's "opened_at" value
 * @method CarAvailabilityEmail       setSentAt()         Sets the current record's "sent_at" value
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
            'primary' => true,
            'autoincrement' => true
        ));
        $this->hasColumn('car_id', 'integer', 11, array(
            'notnull' => true
        ));
        $this->hasColumn('checked_at', 'timestamp', null, array());
        $this->hasColumn('opened_at', 'timestamp', null, array());
        $this->hasColumn('sent_at', 'timestamp', null, array());

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