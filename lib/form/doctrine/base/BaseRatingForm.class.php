<?php

/**
 * Rating form base class.
 *
 * @method Rating getObject() Returns the current form's model object
 *
 * @package    CarSharing
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseRatingForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                 => new sfWidgetFormInputHidden(),
      'description'        => new sfWidgetFormInputText(),
      'user_owner_opnion'  => new sfWidgetFormInputText(),
      'user_renter_opnion' => new sfWidgetFormInputText(),
      'complete'           => new sfWidgetFormInputCheckbox(),
      'qualified'          => new sfWidgetFormInputCheckbox(),
      'intime'             => new sfWidgetFormInputCheckbox(),
      'km'                 => new sfWidgetFormInputCheckbox(),
      'clean_satisfied'    => new sfWidgetFormInputText(),
    ));

    $this->setValidators(array(
      'id'                 => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'description'        => new sfValidatorString(array('max_length' => 45, 'required' => false)),
      'user_owner_opnion'  => new sfValidatorString(array('max_length' => 45, 'required' => false)),
      'user_renter_opnion' => new sfValidatorString(array('max_length' => 45, 'required' => false)),
      'complete'           => new sfValidatorBoolean(array('required' => false)),
      'qualified'          => new sfValidatorBoolean(array('required' => false)),
      'intime'             => new sfValidatorBoolean(array('required' => false)),
      'km'                 => new sfValidatorBoolean(array('required' => false)),
      'clean_satisfied'    => new sfValidatorString(array('max_length' => 45, 'required' => false)),
    ));

    $this->validatorSchema->setPostValidator(
      new sfValidatorDoctrineUnique(array('model' => 'Rating', 'column' => array('id')))
    );

    $this->widgetSchema->setNameFormat('rating[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Rating';
  }

}
