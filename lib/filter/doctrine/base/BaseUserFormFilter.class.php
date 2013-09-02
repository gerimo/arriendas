<?php

/**
 * User filter form base class.
 *
 * @package    CarSharing
 * @subpackage filter
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseUserFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'username'              => new sfWidgetFormFilterInput(),
      'password'              => new sfWidgetFormFilterInput(),
      'facebook_id'           => new sfWidgetFormFilterInput(),
      'url'                   => new sfWidgetFormFilterInput(),
      'email'                 => new sfWidgetFormFilterInput(),
      'firstname'             => new sfWidgetFormFilterInput(),
      'lastname'              => new sfWidgetFormFilterInput(),
      'paypal_id'             => new sfWidgetFormFilterInput(),
      'driver_license_number' => new sfWidgetFormFilterInput(),
      'driver_license_file'   => new sfWidgetFormFilterInput(),
      'picture_file'          => new sfWidgetFormFilterInput(),
      'telephone'             => new sfWidgetFormFilterInput(),
      'identification'        => new sfWidgetFormFilterInput(),
      'birthdate'             => new sfWidgetFormFilterInput(),
      'country'               => new sfWidgetFormFilterInput(),
      'city'                  => new sfWidgetFormFilterInput(),
      'hash'                  => new sfWidgetFormFilterInput(),
      'confirmed'             => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'autoconfirm'           => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'deleted'               => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'confirmed_fb'          => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'confirmed_sms'         => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'friend_invite'         => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
    ));

    $this->setValidators(array(
      'username'              => new sfValidatorPass(array('required' => false)),
      'password'              => new sfValidatorPass(array('required' => false)),
      'facebook_id'           => new sfValidatorPass(array('required' => false)),
      'url'                   => new sfValidatorPass(array('required' => false)),
      'email'                 => new sfValidatorPass(array('required' => false)),
      'firstname'             => new sfValidatorPass(array('required' => false)),
      'lastname'              => new sfValidatorPass(array('required' => false)),
      'paypal_id'             => new sfValidatorPass(array('required' => false)),
      'driver_license_number' => new sfValidatorPass(array('required' => false)),
      'driver_license_file'   => new sfValidatorPass(array('required' => false)),
      'picture_file'          => new sfValidatorPass(array('required' => false)),
      'telephone'             => new sfValidatorPass(array('required' => false)),
      'identification'        => new sfValidatorPass(array('required' => false)),
      'birthdate'             => new sfValidatorPass(array('required' => false)),
      'country'               => new sfValidatorPass(array('required' => false)),
      'city'                  => new sfValidatorPass(array('required' => false)),
      'hash'                  => new sfValidatorPass(array('required' => false)),
      'confirmed'             => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'autoconfirm'           => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'deleted'               => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'confirmed_fb'          => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'confirmed_sms'         => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'friend_invite'         => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
    ));

    $this->widgetSchema->setNameFormat('user_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'User';
  }

  public function getFields()
  {
    return array(
      'id'                    => 'Number',
      'username'              => 'Text',
      'password'              => 'Text',
      'facebook_id'           => 'Text',
      'url'                   => 'Text',
      'email'                 => 'Text',
      'firstname'             => 'Text',
      'lastname'              => 'Text',
      'paypal_id'             => 'Text',
      'driver_license_number' => 'Text',
      'driver_license_file'   => 'Text',
      'picture_file'          => 'Text',
      'telephone'             => 'Text',
      'identification'        => 'Text',
      'birthdate'             => 'Text',
      'country'               => 'Text',
      'city'                  => 'Text',
      'hash'                  => 'Text',
      'confirmed'             => 'Boolean',
      'autoconfirm'           => 'Boolean',
      'deleted'               => 'Boolean',
      'confirmed_fb'          => 'Boolean',
      'confirmed_sms'         => 'Boolean',
      'friend_invite'         => 'Boolean',
    );
  }
}
