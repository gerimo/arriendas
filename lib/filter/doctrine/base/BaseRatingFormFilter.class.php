<?php

/**
 * Rating filter form base class.
 *
 * @package    CarSharing
 * @subpackage filter
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseRatingFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'description'        => new sfWidgetFormFilterInput(),
      'user_owner_opnion'  => new sfWidgetFormFilterInput(),
      'user_renter_opnion' => new sfWidgetFormFilterInput(),
      'complete'           => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'qualified'          => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'intime'             => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'km'                 => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'clean_satisfied'    => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'description'        => new sfValidatorPass(array('required' => false)),
      'user_owner_opnion'  => new sfValidatorPass(array('required' => false)),
      'user_renter_opnion' => new sfValidatorPass(array('required' => false)),
      'complete'           => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'qualified'          => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'intime'             => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'km'                 => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'clean_satisfied'    => new sfValidatorPass(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('rating_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Rating';
  }

  public function getFields()
  {
    return array(
      'id'                 => 'Number',
      'description'        => 'Text',
      'user_owner_opnion'  => 'Text',
      'user_renter_opnion' => 'Text',
      'complete'           => 'Boolean',
      'qualified'          => 'Boolean',
      'intime'             => 'Boolean',
      'km'                 => 'Boolean',
      'clean_satisfied'    => 'Text',
    );
  }
}
