<?php

/**
 * Car form base class.
 *
 * @method Car getObject() Returns the current form's model object
 *
 * @package    CarSharing
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseCarForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'             => new sfWidgetFormInputHidden(),
      'User_id'        => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('User'), 'add_empty' => false)),
      'km'             => new sfWidgetFormInputText(),
      'City_id'        => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('City'), 'add_empty' => false)),
      'address'        => new sfWidgetFormInputText(),
      'lat'            => new sfWidgetFormInputText(),
      'lng'            => new sfWidgetFormInputText(),
      'offer'          => new sfWidgetFormInputText(),
      'observations'   => new sfWidgetFormInputText(),
      'price_per_hour' => new sfWidgetFormInputText(),
      'price_per_day'  => new sfWidgetFormInputText(),
      'Model_id'       => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Model'), 'add_empty' => false)),
      'year'           => new sfWidgetFormInputText(),
      'description'    => new sfWidgetFormInputText(),
    ));

    $this->setValidators(array(
      'id'             => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'User_id'        => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('User'))),
      'km'             => new sfValidatorString(array('max_length' => 45, 'required' => false)),
      'City_id'        => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('City'))),
      'address'        => new sfValidatorString(array('max_length' => 45, 'required' => false)),
      'lat'            => new sfValidatorNumber(array('required' => false)),
      'lng'            => new sfValidatorNumber(array('required' => false)),
      'offer'          => new sfValidatorInteger(array('required' => false)),
      'observations'   => new sfValidatorString(array('max_length' => 45, 'required' => false)),
      'price_per_hour' => new sfValidatorNumber(array('required' => false)),
      'price_per_day'  => new sfValidatorNumber(array('required' => false)),
      'Model_id'       => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Model'))),
      'year'           => new sfValidatorInteger(array('required' => false)),
      'description'    => new sfValidatorString(array('max_length' => 45, 'required' => false)),
    ));

    $this->validatorSchema->setPostValidator(
      new sfValidatorDoctrineUnique(array('model' => 'Car', 'column' => array('id')))
    );

    $this->widgetSchema->setNameFormat('car[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Car';
  }

}
