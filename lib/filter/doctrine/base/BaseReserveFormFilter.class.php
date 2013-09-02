<?php

/**
 * Reserve filter form base class.
 *
 * @package    CarSharing
 * @subpackage filter
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseReserveFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'date'      => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate())),
      'duration'  => new sfWidgetFormFilterInput(),
      'confirmed' => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'User_id'   => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('User'), 'add_empty' => true)),
      'Car_id'    => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Car'), 'add_empty' => true)),
      'Rating_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Rating'), 'add_empty' => true)),
      'complete'  => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
    ));

    $this->setValidators(array(
      'date'      => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'duration'  => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'confirmed' => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'User_id'   => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('User'), 'column' => 'id')),
      'Car_id'    => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('Car'), 'column' => 'id')),
      'Rating_id' => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('Rating'), 'column' => 'id')),
      'complete'  => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
    ));

    $this->widgetSchema->setNameFormat('reserve_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Reserve';
  }

  public function getFields()
  {
    return array(
      'id'        => 'Number',
      'date'      => 'Date',
      'duration'  => 'Number',
      'confirmed' => 'Boolean',
      'User_id'   => 'ForeignKey',
      'Car_id'    => 'ForeignKey',
      'Rating_id' => 'ForeignKey',
      'complete'  => 'Boolean',
    );
  }
}
