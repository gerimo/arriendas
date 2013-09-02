<?php

/**
 * maps actions.
 *
 * @package    CarSharing
 * @subpackage maps
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class mapsActions extends sfActions
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
  public function executePicLocation(){
    	
	//Valores por defecto
	$lat = -33.427224;
	$lng = -70.605558;
    
    $this->setTemplate("map");
  
	if( $this->getRequestParameter("lat") != "" ) $lat = $this->getRequestParameter("lat");
	if( $this->getRequestParameter("lng") != "" ) $lng = $this->getRequestParameter("lng");
  
	$this->lat = $lat;
	$this->lng = $lng;
	
  }
  
  public function executeNuevasUbicaciones(){
	$direccion = $this->getRequestParameter("message");
	$direccion = split(",",$direccion);
	$this->address= $direccion[0];
	$ciudadn = $direccion[1];
	$provincian = $direccion[2];
	$paisn = $direccion[3];

	// buscamos el pais si no existe lo creamos
	$pais = Doctrine_Core::getTable("Country")->findOneByName($paisn);
	if (! $pais) {
		$this->pais = 0;
	$this->city = 0;
	$this->state = 0;
	 } else {
	// busacmos la provincia con el pais si no existe la creamos
	$provincia = Doctrine_Core::getTable("State")->getByCountryAndName($pais->getId(),$provincian);
	if (! $provincia) {
		$provincia = new State();
		$provincia->setCountryId($pais->getId());
		$provincia->setName($provincian);
		$provincia->save();
	}
	// ahora lo mismo con la ciudad
	$city = Doctrine_Core::getTable("City")->getByStateAndName($provincia->getId(),$ciudadn);
	if (! $city) {
		$city = new City();
		$city->setStateIdt($provincia->getId());
		$city->setName($ciudadn);
		$city->save();
	}
	$this->pais = $pais->getId();
	$this->city = $city->getId();
	$this->state = $provincia->getId();
	}
  }
}
