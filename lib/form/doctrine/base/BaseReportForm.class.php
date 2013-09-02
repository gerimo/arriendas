<?php

/**
 * Report form base class.
 *
 * @method Report getObject() Returns the current form's model object
 *
 * @package    CarSharing
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseReportForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'             => new sfWidgetFormInputHidden(),
      'renter_comment' => new sfWidgetFormInputText(),
      'owner_comment'  => new sfWidgetFormInputText(),
      'Reserve_id'     => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Reserve'), 'add_empty' => false)),
      'ReportType_id'  => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('ReportType'), 'add_empty' => false)),
    ));

    $this->setValidators(array(
      'id'             => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'renter_comment' => new sfValidatorString(array('max_length' => 90, 'required' => false)),
      'owner_comment'  => new sfValidatorString(array('max_length' => 90, 'required' => false)),
      'Reserve_id'     => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Reserve'))),
      'ReportType_id'  => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('ReportType'))),
    ));

    $this->validatorSchema->setPostValidator(
      new sfValidatorDoctrineUnique(array('model' => 'Report', 'column' => array('id')))
    );

    $this->widgetSchema->setNameFormat('report[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Report';
  }

}
