<?php

/**
 * Report filter form base class.
 *
 * @package    CarSharing
 * @subpackage filter
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseReportFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'renter_comment' => new sfWidgetFormFilterInput(),
      'owner_comment'  => new sfWidgetFormFilterInput(),
      'Reserve_id'     => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Reserve'), 'add_empty' => true)),
      'ReportType_id'  => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('ReportType'), 'add_empty' => true)),
    ));

    $this->setValidators(array(
      'renter_comment' => new sfValidatorPass(array('required' => false)),
      'owner_comment'  => new sfValidatorPass(array('required' => false)),
      'Reserve_id'     => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('Reserve'), 'column' => 'id')),
      'ReportType_id'  => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('ReportType'), 'column' => 'id')),
    ));

    $this->widgetSchema->setNameFormat('report_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Report';
  }

  public function getFields()
  {
    return array(
      'id'             => 'Number',
      'renter_comment' => 'Text',
      'owner_comment'  => 'Text',
      'Reserve_id'     => 'ForeignKey',
      'ReportType_id'  => 'ForeignKey',
    );
  }
}
