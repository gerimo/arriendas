<?php

/**
 * BaseSecurityTokens
 * 
 * 
 * @property string $id
 * @property string $secret
 * @property boolean $valid
 * 
 * @method string       getId()      
 * @method string       getSecret()  
 * @method boolean      getValid()   
 * @method SecurityTokens setId()        
 * @method SecurityTokens setSecret()  
 * @method SecurityTokens setValid()
 * 
 * @package    CarSharing
 * @subpackage model
 * @author     Patricio Lopez
 * @version    1.0
 */
abstract class BaseSecurityTokens extends sfDoctrineRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('security_tokens');
        $this->hasColumn('id', 'string', 10, array(
             'type' => 'string',
             'primary' => true,
             'length' => 10,
             ));
        $this->hasColumn('secret', 'string', 30, array(
             'type' => 'string',
             'length'=> '30',
             ));
        $this->hasColumn('valid', 'boolean', 1, array(
             'type' => 'boolean',
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

    public function setUp()
    {
    }
}