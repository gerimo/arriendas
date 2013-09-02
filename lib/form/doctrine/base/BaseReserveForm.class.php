<?php

/**
 * Reserve form base class.
 *
 * @method Reserve getObject() Returns the current form's model object
 *
 * @package    CarSharing
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseReserveForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'        => new sfWidgetFormInputHidden(),
      'date'      => new sfWidgetFormDateTime(),
      'duration'  => new sfWidgetFormInputText(),
      'confirmed' => new sfWidgetFormInputCheckbox(),
      'User_id'   => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('User'), 'add_empty' => false)),
      'Car_id'    => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Car'), 'add_empty' => false)),
      'Rating_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Rating'), 'add_empty' => true)),
      'complete'  => new sfWidgetFormInputCheckbox(),
    ));

    $this->setValidators(array(
      'id'        => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'date'      => new sfValidatorDateTime(array('required' => false)),
      'duration'  => new sfValidatorInteger(array('required' => false)),
      'confirmed' => new sfValidatorBoolean(array('required' => false)),
      'User_id'   => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('User'))),
      'Car_id'    => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Car'))),
      'Rating_id' => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Rating'), 'required' => false)),
      'complete'  => new sfValidatorBoolean(array('required' => false)),
    ));

    $this->validatorSchema->setPostValidator(
      new sfValidatorDoctrineUnique(array('model' => 'Reserve', 'column' => array('id')))
    );

    $this->widgetSchema->setNameFormat('reserve[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Reserve';
  }

}
