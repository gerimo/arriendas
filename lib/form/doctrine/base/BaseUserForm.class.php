<?php

/**
 * User form base class.
 *
 * @method User getObject() Returns the current form's model object
 *
 * @package    CarSharing
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseUserForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                    => new sfWidgetFormInputHidden(),
      'username'              => new sfWidgetFormInputText(),
      'password'              => new sfWidgetFormInputText(),
      'facebook_id'           => new sfWidgetFormInputText(),
      'url'                   => new sfWidgetFormInputText(),
      'email'                 => new sfWidgetFormInputText(),
      'firstname'             => new sfWidgetFormInputText(),
      'lastname'              => new sfWidgetFormInputText(),
      'paypal_id'             => new sfWidgetFormInputText(),
      'driver_license_number' => new sfWidgetFormInputText(),
      'driver_license_file'   => new sfWidgetFormInputText(),
      'picture_file'          => new sfWidgetFormInputText(),
      'telephone'             => new sfWidgetFormInputText(),
      'identification'        => new sfWidgetFormInputText(),
      'birthdate'             => new sfWidgetFormInputText(),
      'country'               => new sfWidgetFormInputText(),
      'city'                  => new sfWidgetFormInputText(),
      'hash'                  => new sfWidgetFormInputText(),
      'confirmed'             => new sfWidgetFormInputCheckbox(),
      'autoconfirm'           => new sfWidgetFormInputCheckbox(),
      'deleted'               => new sfWidgetFormInputCheckbox(),
      'confirmed_fb'          => new sfWidgetFormInputCheckbox(),
      'confirmed_sms'         => new sfWidgetFormInputCheckbox(),
      'friend_invite'         => new sfWidgetFormInputCheckbox(),
    ));

    $this->setValidators(array(
      'id'                    => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'username'              => new sfValidatorString(array('max_length' => 45, 'required' => false)),
      'password'              => new sfValidatorString(array('max_length' => 45, 'required' => false)),
      'facebook_id'           => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'url'                   => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'email'                 => new sfValidatorString(array('max_length' => 45, 'required' => false)),
      'firstname'             => new sfValidatorString(array('max_length' => 45, 'required' => false)),
      'lastname'              => new sfValidatorString(array('max_length' => 45, 'required' => false)),
      'paypal_id'             => new sfValidatorString(array('max_length' => 45, 'required' => false)),
      'driver_license_number' => new sfValidatorString(array('max_length' => 45, 'required' => false)),
      'driver_license_file'   => new sfValidatorString(array('max_length' => 90, 'required' => false)),
      'picture_file'          => new sfValidatorString(array('max_length' => 90, 'required' => false)),
      'telephone'             => new sfValidatorString(array('max_length' => 45, 'required' => false)),
      'identification'        => new sfValidatorString(array('max_length' => 45, 'required' => false)),
      'birthdate'             => new sfValidatorString(array('max_length' => 45, 'required' => false)),
      'country'               => new sfValidatorString(array('max_length' => 45, 'required' => false)),
      'city'                  => new sfValidatorString(array('max_length' => 45, 'required' => false)),
      'hash'                  => new sfValidatorString(array('max_length' => 45, 'required' => false)),
      'confirmed'             => new sfValidatorBoolean(array('required' => false)),
      'autoconfirm'           => new sfValidatorBoolean(array('required' => false)),
      'deleted'               => new sfValidatorBoolean(array('required' => false)),
      'confirmed_fb'          => new sfValidatorBoolean(array('required' => false)),
      'confirmed_sms'         => new sfValidatorBoolean(array('required' => false)),
      'friend_invite'         => new sfValidatorBoolean(array('required' => false)),
    ));

    $this->validatorSchema->setPostValidator(
      new sfValidatorDoctrineUnique(array('model' => 'User', 'column' => array('id')))
    );

    $this->widgetSchema->setNameFormat('user[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'User';
  }

}
