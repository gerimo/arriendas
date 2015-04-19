<?php

abstract class BaseNotification extends sfDoctrineRecord {

    public function setTableDefinition() {

        $this->setTableName('Notification');
        
        $this->hasColumn('id', array(
            'type' => 'integer',
            'length' => 4,
            'primary' => true,
            'autoincrement' => true            
        ));

        $this->hasColumn('action_id', array(
            'type' => 'integer',
            'notnull' => true,
            'length' => 4,
        ));

        $this->hasColumn('created_at', array(
            'type' => 'datetime',
            'notnull' => true
        ));

        $this->hasColumn('is_active', array(
            'type' => 'boolean',
            'default' => false
        ));
        
        $this->hasColumn('message', array(
            'type' => 'text',
            'notnull' => true
        ));

        $this->hasColumn('message_title', array(
            'type' => 'string',
            'length' => 255
        ));

        $this->hasColumn('notification_type_id', array(
            'type' => 'integer',
            'notnull' => true,
            'length' => 4
        ));

        // Indices
        $this->index('fk_Action_Notification', array(
            'fields' => array(0 => 'action_id')
        ));

        $this->index('fk_NotificationType_Notificaction', array(
            'fields' => array(0 => 'notification_type_id')
        ));

        $this->option('charset', 'utf8');
        $this->option('type', 'InnoDB');
    }

    public function setUp() {

        parent::setUp();
        
        $this->hasMany('UserNotifications as UserNotification', array(
            'local' => 'id',
            'foreign' => 'notification_id'
        ));

        $this->hasOne('Action', array(
            'local' => 'action_id',
            'foreign' => 'id',
            'onDelete' => 'no action',
            'onUpdate' => 'no action'
        ));

        $this->hasOne('NotificationType', array(
            'local' => 'notification_type_id',
            'foreign' => 'id',
            'onDelete' => 'no action',
            'onUpdate' => 'no action'
        ));

    }
}