<?php

/**
 * AvailabilityHasCar form base class.
 *
 * @method AvailabilityHasCar getObject() Returns the current form's model object
 *
 * @package    CarSharing
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseAvailabilityHasCarForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'Availability_id' => new sfWidgetFormInputHidden(),
      'Car_id'          => new sfWidgetFormInputHidden(),
    ));

    $this->setValidators(array(
      'Availability_id' => new sfValidatorChoice(array('choices' => array($this->getObject()->get('Availability_id')), 'empty_value' => $this->getObject()->get('Availability_id'), 'required' => false)),
      'Car_id'          => new sfValidatorChoice(array('choices' => array($this->getObject()->get('Car_id')), 'empty_value' => $this->getObject()->get('Car_id'), 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('availability_has_car[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'AvailabilityHasCar';
  }

}
