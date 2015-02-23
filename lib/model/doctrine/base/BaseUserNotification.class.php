<?php

abstract class BaseUserNotification extends sfDoctrineRecord {

    public function setTableDefinition() {

        $this->setTableName('UserNotification');

        $this->hasColumn('id', array(
            'type' => 'integer',
            'length' => 4,
            'primary' => true,
            'autoincrement' => true
        ));
        
        $this->hasColumn('closed_at', array(
            'type' => 'datetime',
        ));

        $this->hasColumn('created_at', array(
            'type' => 'datetime',
            'notnull' => true
        ));

        $this->hasColumn('notification_id', array(
            'type' => 'integer',
            'notnull' => true,
            'length' => 4
        ));

        $this->hasColumn('sent_at', array(
            'type' => 'datetime',
        ));

        $this->hasColumn('user_id', array(
            'type' => 'integer',
            'notnull' => true,
            'length' => 4
        ));
        
        $this->hasColumn('viewed_at', array(
            'type' => 'datetime',
        ));

        // Indices
        $this->index('fk_UserNotification_Notification', array(
            'fields' => array(0 => 'notification_id')
        ));

        $this->index('fk_UserNotification_User', array(
            'fields' => array(0 => 'user_id')
        ));

        $this->option('charset', 'utf8');
        $this->option('type', 'InnoDB');
    }

    public function setUp() {

        parent::setUp();
        
        $this->hasOne('Notification', array(
             'local' => 'notification_id',
             'foreign' => 'id',
             'onDelete' => 'no action',
             'onUpdate' => 'no action'
        ));

        $this->hasOne('User', array(
             'local' => 'user_id',
             'foreign' => 'id',
             'onDelete' => 'no action',
             'onUpdate' => 'no action'
        ));
    }
}