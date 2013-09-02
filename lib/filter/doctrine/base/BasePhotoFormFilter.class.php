<?php

/**
 * Photo filter form base class.
 *
 * @package    CarSharing
 * @subpackage filter
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BasePhotoFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'path'   => new sfWidgetFormFilterInput(),
      'type'   => new sfWidgetFormFilterInput(),
      'Car_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Car'), 'add_empty' => true)),
    ));

    $this->setValidators(array(
      'path'   => new sfValidatorPass(array('required' => false)),
      'type'   => new sfValidatorPass(array('required' => false)),
      'Car_id' => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('Car'), 'column' => 'id')),
    ));

    $this->widgetSchema->setNameFormat('photo_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Photo';
  }

  public function getFields()
  {
    return array(
      'id'     => 'Number',
      'path'   => 'Text',
      'type'   => 'Text',
      'Car_id' => 'ForeignKey',
    );
  }
}
