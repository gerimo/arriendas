<?php

abstract class BaseBankAccount extends sfDoctrineRecord {

    public function setTableDefinition() {

        $this->setTableName('BankAccount');
        
        $this->hasColumn('id', array(
            'type' => 'integer',
            'length' => 4,
            'primary' => true,
            'autoincrement' => true
        ));

        $this->hasColumn('user_id', array(
            'type' => 'integer',
            'notnull' => true,
            'length' => 4,
        ));

        $this->hasColumn('bank_id', array(
            'type' => 'integer',
            'notnull' => true,
            'length' => 4,
        ));

        $this->hasColumn('bank_account_type_id', array(
            'type' => 'integer',
            'notnull' => true,
            'length' => 4,
        ));

        $this->hasColumn('number', array(
            'type' => 'integer',
            'notnull' => true,
            'length' => 4,
        ));

        $this->hasColumn('rut_bank', 'integer', 20, array(
            'type' => 'integer',
            'length' => 20,
        ));

        $this->hasColumn('rut_dv_bank', 'string', 1, array(
            'type' => 'string',
            'length' => 1,
        ));

        $this->hasColumn('created_at', array(
            'type' => 'datetime',
            'notnull' => true
        ));

        $this->hasColumn('is_active', array(
            'type' => 'boolean',
            'default' => false
        ));

        // Indices
        $this->index('fk_User_BankAccount', array(
            'fields' => array(0 => 'user_id')
        ));

        $this->index('fk_Bank_BankAccount', array(
            'fields' => array(0 => 'bank_id')
        ));

        $this->index('fk_BankAccountType_BankAccount', array(
            'fields' => array(0 => 'bank_account_type_id')
        ));

        $this->option('charset', 'utf8');
        $this->option('type', 'InnoDB');
    }

    public function setUp() {

        parent::setUp();
        
        $this->hasOne('User', array(
            'local' => 'user_id',
            'foreign' => 'id',
            'onDelete' => 'no action',
            'onUpdate' => 'no action'
        ));

        $this->hasOne('Bank', array(
            'local' => 'bank_id',
            'foreign' => 'id',
            'onDelete' => 'no action',
            'onUpdate' => 'no action'
        ));

        $this->hasOne('BankAccountType', array(
            'local' => 'bank_account_type_id',
            'foreign' => 'id',
            'onDelete' => 'no action',
            'onUpdate' => 'no action'
        ));
    }
}