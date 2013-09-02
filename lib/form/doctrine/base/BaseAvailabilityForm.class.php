<?php

/**
 * Availability form base class.
 *
 * @method Availability getObject() Returns the current form's model object
 *
 * @package    CarSharing
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseAvailabilityForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'        => new sfWidgetFormInputHidden(),
      'date_from' => new sfWidgetFormDateTime(),
      'date_to'   => new sfWidgetFormDateTime(),
      'hour_from' => new sfWidgetFormTime(),
      'hour_to'   => new sfWidgetFormTime(),
      'day'       => new sfWidgetFormInputText(),
      'Car_id'    => new sfWidgetFormInputHidden(),
    ));

    $this->setValidators(array(
      'id'        => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'date_from' => new sfValidatorDateTime(array('required' => false)),
      'date_to'   => new sfValidatorDateTime(array('required' => false)),
      'hour_from' => new sfValidatorTime(array('required' => false)),
      'hour_to'   => new sfValidatorTime(array('required' => false)),
      'day'       => new sfValidatorInteger(array('required' => false)),
      'Car_id'    => new sfValidatorChoice(array('choices' => array($this->getObject()->get('Car_id')), 'empty_value' => $this->getObject()->get('Car_id'), 'required' => false)),
    ));

    $this->validatorSchema->setPostValidator(
      new sfValidatorDoctrineUnique(array('model' => 'Availability', 'column' => array('id')))
    );

    $this->widgetSchema->setNameFormat('availability[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Availability';
  }

}
