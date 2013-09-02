<?php

/**
 * Car filter form base class.
 *
 * @package    CarSharing
 * @subpackage filter
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseCarFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'User_id'        => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('User'), 'add_empty' => true)),
      'km'             => new sfWidgetFormFilterInput(),
      'City_id'        => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('City'), 'add_empty' => true)),
      'address'        => new sfWidgetFormFilterInput(),
      'lat'            => new sfWidgetFormFilterInput(),
      'lng'            => new sfWidgetFormFilterInput(),
      'offer'          => new sfWidgetFormFilterInput(),
      'observations'   => new sfWidgetFormFilterInput(),
      'price_per_hour' => new sfWidgetFormFilterInput(),
      'price_per_day'  => new sfWidgetFormFilterInput(),
      'Model_id'       => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Model'), 'add_empty' => true)),
      'year'           => new sfWidgetFormFilterInput(),
      'description'    => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'User_id'        => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('User'), 'column' => 'id')),
      'km'             => new sfValidatorPass(array('required' => false)),
      'City_id'        => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('City'), 'column' => 'id')),
      'address'        => new sfValidatorPass(array('required' => false)),
      'lat'            => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'lng'            => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'offer'          => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'observations'   => new sfValidatorPass(array('required' => false)),
      'price_per_hour' => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'price_per_day'  => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'Model_id'       => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('Model'), 'column' => 'id')),
      'year'           => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'description'    => new sfValidatorPass(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('car_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Car';
  }

  public function getFields()
  {
    return array(
      'id'             => 'Number',
      'User_id'        => 'ForeignKey',
      'km'             => 'Text',
      'City_id'        => 'ForeignKey',
      'address'        => 'Text',
      'lat'            => 'Number',
      'lng'            => 'Number',
      'offer'          => 'Number',
      'observations'   => 'Text',
      'price_per_hour' => 'Number',
      'price_per_day'  => 'Number',
      'Model_id'       => 'ForeignKey',
      'year'           => 'Number',
      'description'    => 'Text',
    );
  }
}
