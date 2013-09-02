<?php

/**
 * cars actions.
 *
 * @package    CarSharing
 * @subpackage cars
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class carsActions extends sfActions {

    /**
     * Executes index action
     *
     * @param sfRequest $request A request object
     */
    public function executeIndex(sfWebRequest $request) {


        $q = Doctrine_Query::create()
                ->select('ca.id , av.id idav , mo.name model, 
		  br.name brand, ca.year year, 
		  ca.address address, ci.name city, 
		  st.name state, co.name country'
                )
                ->from('Car ca')
                ->innerJoin('ca.Availabilities av')
                ->innerJoin('ca.Model mo')
                ->innerJoin('mo.Brand br')
                ->innerJoin('ca.City ci')
                ->innerJoin('ci.State st')
                ->innerJoin('st.Country co');
        $this->cars = $q->execute();
    }

    public function executeCar(sfWebRequest $request) {
        $this->getUser()->setAttribute('lastview', $request->getReferer());
        $this->car = Doctrine_Core::getTable('car')->find(array($request->getParameter('id')));

		$this->df = ''; if($request->getParameter('df')) $this->df = $request->getParameter('df');
		$this->hf = ''; if($request->getParameter('hf')) $this->df = $request->getParameter('hf');
		$this->dt = ''; if($request->getParameter('dt')) $this->df = $request->getParameter('dt');
		$this->ht = ''; if($request->getParameter('ht')) $this->df = $request->getParameter('ht');

        if ($this->car->getUser()->getId() == $this->getUser()->getAttribute("userid")) {
            $this->forward('profile', 'car');
        }
        $this->user = $this->car->getUser();
	
	//Modificaci—n para llevar el conteo de la cantidad de consulas que recibe el perfil del auto
	$q= Doctrine_Query::create()
	    ->update("car")
	    ->set("consultas","consultas + 1")
	    ->where("id = ?",$request->getParameter('id'));
	$q->execute();
    }

}
