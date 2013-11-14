<?php

/**
 * rutinas actions.
 *
 * @package    CarSharing
 * @subpackage rutinas
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class rutinasActions extends sfActions
{
 /**
  * Executes index action
  *
  * @param sfRequest $request A request object
  */
  public function executeIndex(sfWebRequest $request)
  {
    $this->forward('default', 'module');
  }

  
    public function executeReserve(sfWebRequest $request)
  {
  
		$this->setLayout(false);

                $users = Doctrine_Core::getTable('Reserve')
                        ->createQuery('s')
                        ->select('*')
                        ->execute();

                foreach($users as $user) {
					$user->save();
                }			
 }

  
  }
