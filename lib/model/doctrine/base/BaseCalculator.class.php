<?php

/**
 * BaseModel
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $id
 * @property string $marca
 * @property string $modelo
 * @property integer $year
 * @property integer $price
 *
 * 
 * @method integer             getId()          Returns the current record's "id" value
 * @method string             getMarca()          Returns the current record's "marca" value
 * @method string             getModelo()          Returns the current record's "modelo" value
 * @method integer             getYear()          Returns the current record's "year" value
 * @method integer             getPrice()          Returns the current record's "price" value
 * @method Calculator               setId()          Sets the current record's "id" value
 * @method Calculator               setMarca()          Sets the current record's "marca" value
 * @method Calculator               setModelo()          Sets the current record's "modelo" value
 * @method Calculator               setYear()          Sets the current record's "year" value
 * @method Calculator               setPrice()          Sets the current record's "price" value
 */ 
 
abstract class BaseCalculator extends sfDoctrineRecord
{
    public function setTableDefinition()
    {
    	$this->setTableName('Calculator');
	}
}
    	