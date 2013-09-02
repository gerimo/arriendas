<?php

/**
 * Transaction filter form base class.
 *
 * @package    CarSharing
 * @subpackage filter
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseTransactionFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'car'                => new sfWidgetFormFilterInput(),
      'date'               => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate())),
      'price'              => new sfWidgetFormFilterInput(),
      'insurance'          => new sfWidgetFormFilterInput(),
      'commission'         => new sfWidgetFormFilterInput(),
      'fuel'               => new sfWidgetFormFilterInput(),
      'User_id'            => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('User'), 'add_empty' => true)),
      'TransactionType_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('TransactionType'), 'add_empty' => true)),
      'Reserve_id'         => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Reserve'), 'add_empty' => true)),
      'completed'          => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
    ));

    $this->setValidators(array(
      'car'                => new sfValidatorPass(array('required' => false)),
      'date'               => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'price'              => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'insurance'          => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'commission'         => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'fuel'               => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'User_id'            => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('User'), 'column' => 'id')),
      'TransactionType_id' => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('TransactionType'), 'column' => 'id')),
      'Reserve_id'         => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('Reserve'), 'column' => 'id')),
      'completed'          => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
    ));

    $this->widgetSchema->setNameFormat('transaction_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Transaction';
  }

  public function getFields()
  {
    return array(
      'id'                 => 'Number',
      'car'                => 'Text',
      'date'               => 'Date',
      'price'              => 'Number',
      'insurance'          => 'Number',
      'commission'         => 'Number',
      'fuel'               => 'Number',
      'User_id'            => 'ForeignKey',
      'TransactionType_id' => 'ForeignKey',
      'Reserve_id'         => 'ForeignKey',
      'completed'          => 'Boolean',
    );
  }
}
