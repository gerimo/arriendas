<?php

abstract class BaseBankAccountType extends sfDoctrineRecord {

    public function setTableDefinition() {

        $this->setTableName('BankAccountType');
        
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
        
        $this->hasMany('BankAccounts as BankAccount', array(
             'local' => 'id',
             'foreign' => 'bank_account_type_id'
        ));
    }
}