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
//						->where('s.cant_reservas_aprobadas < 1')
                        ->execute();

                foreach($users as $user) {
					$user->save();
                }			
 }
 
 
  public function executeUser(sfWebRequest $request)
  {
  
		$this->setLayout(false);

                $users = Doctrine_Core::getTable('User')
                        ->createQuery('s')
                        ->select('*')
						->where('s.customerio < 1')
                        ->execute();

                foreach($users as $user) {
					$user->save();
                }			
 }
 

 
   public function executeCar(sfWebRequest $request)
  {
  
		$this->setLayout(false);

                $cars = Doctrine_Core::getTable('Car')
                        ->createQuery('s')
                        ->select('*')
						->where('s.cant_reservas_aprobadas < 1')
//s						->where('s.customerio < 1')
						->orderBy('s.id desc')
                        ->execute();

                foreach($cars as $car) {
					$car->save();
                }			
 }
  
 

   public function executeTransaction(sfWebRequest $request)
  {
  
		$this->setLayout(false);

                $transactions = Doctrine_Core::getTable('Transaction')
                        ->createQuery('s')
                        ->select('*')
						->where('s.customerio < 1')
                       ->execute();

                foreach($transactions as $transaction) {
					$transaction->save();
                }			
 }


 
  }
