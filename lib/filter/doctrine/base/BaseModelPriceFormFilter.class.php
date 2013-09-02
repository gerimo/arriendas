<?php

/**
 * ModelPrice filter form base class.
 *
 * @package    CarSharing
 * @subpackage filter
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseModelPriceFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'Model_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Model'), 'add_empty' => true)),
      'year'     => new sfWidgetFormFilterInput(),
      'price'    => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'Model_id' => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('Model'), 'column' => 'id')),
      'year'     => new sfValidatorPass(array('required' => false)),
      'price'    => new sfValidatorPass(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('model_price_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'ModelPrice';
  }

  public function getFields()
  {
    return array(
      'id'       => 'Number',
      'Model_id' => 'ForeignKey',
      'year'     => 'Text',
      'price'    => 'Text',
    );
  }
}
