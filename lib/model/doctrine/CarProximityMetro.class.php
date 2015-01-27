<?php

class CarProximityMetro extends BaseCarProximityMetro {


	// Funcion estática que recibe un auto y arreglo de metros por parámetros e inserta un registro en CarProximityMetro.
	// Devuelve true si se logró.
	static function setNewCarProximityMetro($Car) {
		$metroArray = $Car->getDistancesToMetros();
		try{
			asort($metroArray);
	        foreach (array_slice($metroArray, 0, 2, true) as $metroId => $distance) {
	            
	            $CarProximityMetro = new CarProximityMetro();
	            $CarProximityMetro->setMetroId($metroId);
	            $CarProximityMetro->setCarId($Car->id);
	            $CarProximityMetro->setDistance($distance);
	            $CarProximityMetro->save();
	        }
	    } catch(Exception $e) {
	    	return false;
	    }
	    return true;
	}
}