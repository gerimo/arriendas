<?php

/**
 * BaseCar
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $id
 * @property string $line
 * @property integer $station_number
 * @property string $name
 * @property float $lat
 * @property float $lng
 * @property Doctrine_Collection $CarProximityMetros
 * 
 * @method integer               getId()                   Returns the current record's "id" value
 * @method string                getLine()                 Returns the current record's "line" value
 * @method integer               getStationNumber()        Returns the current record's "station_number" value
 * @method string                getName()                 Returns the current record's "name" value
 * @method float                 getLat()                  Returns the current record's "lat" value
 * @method float                 getLng()                  Returns the current record's "lng" value
 * @method Doctrine_Collection   getCarProximityMetros()   Returns the current record's "CarProximityMetros" collection
 *
 * @method Metro                 setId()                   Sets the current record's "id" value
 * @method Metro                 setLinea()                Sets the current record's "line" value
 * @method Metro                 setStationNumber()        Sets the current record's "station_number" value
 * @method Metro                 setName()                 Sets the current record's "name" value
 * @method Metro                 setLat()                  Sets the current record's "lat" value
 * @method Metro                 setLng()                  Sets the current record's "lng" value
 * @method Metro                 setCarProximityMetros()   Sets the current record's "CarProximityMetros" collection
 *
 * @package    MetroSharing
 * @subpackage model
 * @author     Your name here
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseMetro extends sfDoctrineRecord {

    public function setTableDefinition() {

        $this->setTableName('Metro');
        
        $this->hasColumn('id', 'integer', 4, array(
             'type' => 'integer',
             'primary' => true,
             'autoincrement' => true,
             'length' => 4,
             ));
        $this->hasColumn('line', 'string', 8, array(
             'type' => 'string',
             'notnull' => true,
             'length' => 8,
             ));
        $this->hasColumn('station_number', 'integer', 8, array(
             'type' => 'integer',
             'notnull' => true,
             'length' => 8,
             ));
        $this->hasColumn('name', 'string', 32, array(
             'type' => 'integer',
             'notnull' => true,
             'length' => 32,
             ));
        $this->hasColumn('lat', 'float', 10, array(
             'type' => 'float',
             'length' => 10,
             'scale' => '6',
             ));
        $this->hasColumn('lng', 'float', 10, array(
             'type' => 'float',
             'length' => 10,
             'scale' => '6',
             ));

        $this->index('id_UNIQUE', array(
             'fields' => 
             array(
              0 => 'id',
             ),
             'type' => 'unique',
             ));
        $this->option('charset', 'utf8');
        $this->option('type', 'InnoDB');
    }

    public function setUp() {
        parent::setUp();

        $this->hasMany('CarProximityMetro as CarProximityMetros', array(
            'local' => 'id',
            'foreign' => 'metro_id'
        ));

    }
}