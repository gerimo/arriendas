<?php

/**
 * Conversation form base class.
 *
 * @method Conversation getObject() Returns the current form's model object
 *
 * @package    CarSharing
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseConversationForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'           => new sfWidgetFormInputHidden(),
      'filed'        => new sfWidgetFormInputCheckbox(),
      'user_to_id'   => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('UserTo'), 'add_empty' => false)),
      'user_from_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('UserFrom'), 'add_empty' => false)),
      'start'        => new sfWidgetFormDateTime(),
      'end'          => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'           => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'filed'        => new sfValidatorBoolean(array('required' => false)),
      'user_to_id'   => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('UserTo'))),
      'user_from_id' => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('UserFrom'))),
      'start'        => new sfValidatorDateTime(),
      'end'          => new sfValidatorDateTime(array('required' => false)),
    ));

    $this->validatorSchema->setPostValidator(
      new sfValidatorDoctrineUnique(array('model' => 'Conversation', 'column' => array('id')))
    );

    $this->widgetSchema->setNameFormat('conversation[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Conversation';
  }

}
