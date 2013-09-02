<?php

/**
 * BaseModel
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $id
 * @property string $name
 * @property integer $Brand_id
 * @property Brand $Brand
 * @property Doctrine_Collection $Cars
 * @property Doctrine_Collection $ModelPrices
 * 
 * @method integer             getId()          Returns the current record's "id" value
 * @method string              getName()        Returns the current record's "name" value
 * @method integer             getBrandId()     Returns the current record's "Brand_id" value
 * @method Brand               getBrand()       Returns the current record's "Brand" value
 * @method Doctrine_Collection getCars()        Returns the current record's "Cars" collection
 * @method Doctrine_Collection getModelPrices() Returns the current record's "ModelPrices" collection
 * @method Model               setId()          Sets the current record's "id" value
 * @method Model               setName()        Sets the current record's "name" value
 * @method Model               setBrandId()     Sets the current record's "Brand_id" value
 * @method Model               setBrand()       Sets the current record's "Brand" value
 * @method Model               setCars()        Sets the current record's "Cars" collection
 * @method Model               setModelPrices() Sets the current record's "ModelPrices" collection
 * 
 * @package    CarSharing
 * @subpackage model
 * @author     Your name here
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseModel extends sfDoctrineRecord
{
 
    public function setTableDefinition()
    {
        $this->setTableName('Model');
        $this->hasColumn('id', 'integer', 4, array(
             'type' => 'integer',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => true,
             'autoincrement' => true,
             'length' => 4,
             ));
        $this->hasColumn('name', 'string', 45, array(
             'type' => 'string',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => false,
             'notnull' => false,
             'autoincrement' => false,
             'length' => 45,
             ));
        $this->hasColumn('brand_id', 'integer', 4, array(
             'type' => 'integer',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => false,
             'notnull' => true,
             'autoincrement' => false,
             'length' => 4,
             ));
        $this->hasColumn('price', 'integer', 4, array(
             'type' => 'integer',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => false,
             'notnull' => false,
             'autoincrement' => false,
             'length' => 4,
             ));
        $this->hasColumn('rendimiento', 'decimal', 10, array(
             'type' => 'decimal',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => false,
             'notnull' => false,
             'autoincrement' => false,
             'length' => 10,
             ));
        $this->hasColumn('tipo_bencina', 'integer', 4, array(
             'type' => 'integer',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => false,
             'notnull' => false,
             'autoincrement' => false,
             'length' => 4,
             ));
        $this->hasColumn('foto_defecto', 'string', 256, array(
             'type' => 'string',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => false,
             'notnull' => false,
             'autoincrement' => false,
             'length' => 256,
             ));
    }
 
    public function setUp()
    {
        parent::setUp();
        $this->hasOne('Brand', array(
             'local' => 'Brand_id',
             'foreign' => 'id',
             'onDelete' => 'no action',
             'onUpdate' => 'no action'));

        $this->hasMany('Car as Cars', array(
             'local' => 'id',
             'foreign' => 'Model_id'));

        $this->hasMany('ModelPrice as ModelPrices', array(
             'local' => 'id',
             'foreign' => 'Model_id'));
    }
}