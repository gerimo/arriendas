<?php

/**
 * Transaction form base class.
 *
 * @method Transaction getObject() Returns the current form's model object
 *
 * @package    CarSharing
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseTransactionForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                 => new sfWidgetFormInputHidden(),
      'car'                => new sfWidgetFormInputText(),
      'date'               => new sfWidgetFormDateTime(),
      'price'              => new sfWidgetFormInputText(),
      'insurance'          => new sfWidgetFormInputText(),
      'commission'         => new sfWidgetFormInputText(),
      'fuel'               => new sfWidgetFormInputText(),
      'User_id'            => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('User'), 'add_empty' => false)),
      'TransactionType_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('TransactionType'), 'add_empty' => false)),
      'Reserve_id'         => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Reserve'), 'add_empty' => true)),
      'completed'          => new sfWidgetFormInputCheckbox(),
    ));

    $this->setValidators(array(
      'id'                 => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'car'                => new sfValidatorString(array('max_length' => 45, 'required' => false)),
      'date'               => new sfValidatorDateTime(array('required' => false)),
      'price'              => new sfValidatorNumber(array('required' => false)),
      'insurance'          => new sfValidatorNumber(array('required' => false)),
      'commission'         => new sfValidatorNumber(array('required' => false)),
      'fuel'               => new sfValidatorNumber(array('required' => false)),
      'User_id'            => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('User'))),
      'TransactionType_id' => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('TransactionType'))),
      'Reserve_id'         => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Reserve'), 'required' => false)),
      'completed'          => new sfValidatorBoolean(array('required' => false)),
    ));

    $this->validatorSchema->setPostValidator(
      new sfValidatorDoctrineUnique(array('model' => 'Transaction', 'column' => array('id')))
    );

    $this->widgetSchema->setNameFormat('transaction[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Transaction';
  }

}
