<?php

class CarProximityMetro extends BaseCarProximityMetro {


	// Funcion estática que recibe un auto e inserta un registro en CarProximityMetro, definiendo la distancia al metro mas cercano
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

	// Funcion estática que recibe un auto y modifica un registro en CarProximityMetro, definiendo la distancia a su metro mas cercano
	static function setCarProximityMetro($Car) {
		
		$metroArray = $Car->getDistancesToMetros(); // obtiene el id del metro
		
		$CarProximityMetros = Doctrine_core::getTable("CarProximityMetro")->findByCarId($Car->getId())->toArray();
		try{

			asort($metroArray);
			
			$distanceToMetroArrayFirstsElements = array_slice($metroArray, 0, count($CarProximityMetros), true);

			foreach ($distanceToMetroArrayFirstsElements as $metroId => $distance) {

	        	$CarProximityMetroArray = array_shift($CarProximityMetros);

	        	$CarProximityMetroId = $CarProximityMetroArray["id"];

	        	$CarProximityMetro = Doctrine_core::getTable("CarProximityMetro")->findOneById($CarProximityMetroId);

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