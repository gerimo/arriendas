<?php

/**
 * dashboard actions.
 *
 * @package    backend
 * @subpackage dashboard
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class dashboardActions extends sfActions
{
 /**
  * Executes index action
  *
  * @param sfRequest $request A request object
  */
  public function executeIndex(sfWebRequest $request)
  {
    $query = Doctrine_Query::create()
                ->select("DATE_FORMAT(date,'%d-%m-%Y') as fecha, COUNT(id) as solicitudes, SUM(confirmed) as confirmaciones, SUM(canceled) as cancelaciones, SUM(complete) as completadas, SUM(price*complete) as monto")
                ->from("Reserve")
                ->groupBy("DATE(date)")
                ->orderBy("fecha DESC");
    $this->dashboard= $query->execute();
    
  }
}
