<?php

require_once sfConfig::get('sf_lib_dir') . '/vendor/mobile-detect/Mobile_Detect.php';

class carActions extends sfActions {
	
	public function executeIndex(sfWebRequest $request) {
	}

    public function executeCarControl(sfWebRequest $request) {
    }

    public function executeDeleteDamage(sfWebRequest $request) {

        $return = array("error" => false);

        try {   

            $damageId    = $request->getPostParameter("damageId", null);

            $Damage = Doctrine_Core::getTable('Damage')->find($damageId);

            if (!$Damage) {
                throw new Exception("Comentario no encontrado", 1);
            }

            
            $Damage->setIsDeleted(1);
            $Damage->save();

            $return["damageId"] = false;    

        } catch (Exception $e) {
            $return["error"] = true;
            $return["errorCode"] = $e->getCode();
            $return["errorMessage"] = $e->getMessage();
        }

        $this->renderText(json_encode($return));
        
        return sfView::NONE;
    }

    public function executeFindAll(sfWebRequest $request) {

        $return = array("error" => false);

        try {

            $limit = $request->getPostParameter("limit", null);

            $return["data"] = array();

            $Cars = Doctrine_Core::getTable('Car')->findAllCar($limit);

            if(count($Cars) == 0){
                throw new Exception("No se encuentran autos", 1);
            }

            foreach($Cars as $i => $Car){

                $CarProximityMetro = $Car->getNearestMetro();

                $return["data"][$i] = array(
                    'id' => $Car->id,
                    'brand' => $Car->getModel()->getBrand()->name,
                    'model' => $Car->getModel()->name,
                    'year' => $Car->year,
                    'transmission' => $Car->transmission == 1 ? 'Automática' : 'Manual',
                    'nearestMetroName' => $CarProximityMetro->getMetro()->name,
                    'type' => $Car->getModel()->getCarType()->name,
                    'comunne' =>$Car->getCommune()->name,
                    'QuantityOfLatestRents' =>$Car->getQuantityOfLatestRents(),
                    'user_name' =>$Car->getUser()->firstname." ".$Car->getUser()->lastname,
                    'user_telephone' => $Car->getUser()->telephone
                );
                  
            }

        } catch (Exception $e) {
            $return["error"]        = true;
            $return["errorCode"]    = $e->getCode();
            $return["errorMessage"] = $e->getMessage();
        }

        $this->renderText(json_encode($return));
        
        return sfView::NONE;
    }

	public function executeFindCar(sfWebRequest $request) {
        $return = array("error" => false);

        $carId   = $request->getPostParameter("carId", null);

        try {

            if (is_null($carId) || $carId == "") {
                throw new Exception("Debe ingresar una id correcta", 1);                
            }

            $Car = Doctrine_Core::getTable('Car')->findOneById($carId);

            if (!$Car) {
                 throw new Exception("Auto no encontrado", 1);                
            }

        } catch (Exception $e) {
            $return["error"] = true;
            $return["errorMessage"] = $e->getMessage();
        }

        $this->renderText(json_encode($return));

        return sfView::NONE;
	}

    public function executeFindDamage(sfWebRequest $request) {
        $return = array("error" => false);

        $damageId   = $request->getPostParameter("damageId", null);
        $carId      = $request->getPostParameter("carId", null);

        try {

            if ($damageId) { 
                $Damage     = Doctrine_Core::getTable('Damage')->find($damageId);

                $return["description"] = $Damage->description;
                $return["urlFoto"] = $Damage->urlFoto;
                $return["opcion"] = 1;
            } else {
                $Damages    = Doctrine_Core::getTable('Damage')->findByCarIdAndIsDeleted($carId,0);

                foreach($Damages as $i => $Damage){

                $return["data"][$i] = array(
                    "damage_id"     => $Damage->id,
                    "damage_lat"    => $Damage->lat,
                    "damage_lng"    => $Damage->lng
                );
            }

            
            }

        } catch (Exception $e) {
            $return["error"] = true;
            $return["errorMessage"] = $e->getMessage();
        }

        $this->renderText(json_encode($return));

        return sfView::NONE;
    }

	public function executeGetActivesCars(sfWebRequest $request) {

	    $return = array("error" => false);

	    try {

	        $reserveId 			= $request->getPostParameter("reserveId", null);
	        $limit              = $request->getPostParameter("limit", null);
            $isAutomatic        = $request->getPostParameter('isAutomatic', false) === 'true' ? true : false;
            $isLowConsumption   = $request->getPostParameter('isLowConsumption', false) === 'true' ? true : false;
            $isMorePassengers   = $request->getPostParameter('isMorePassengers', false) === 'true' ? true : false;
            $withAvailability   = $request->getPostParameter('withAvailability', false) === 'true' ? true : false;

	        $Reserve    = Doctrine_Core::getTable('Reserve')->find($reserveId);
	        $Cars       = Doctrine_Core::getTable('Car')->findCars(0, $limit, $Reserve->getFechaInicio2(), $Reserve->getFechaTermino2(), $withAvailability, false, false, false, false, false, 13, false, $isAutomatic, $isLowConsumption, $isMorePassengers, false, false);

	        $return["data"] = $Cars;

	    } catch (Exception $e) {
	        $return["error"] = true;
	        $return["errorCode"] = $e->getCode();
	        $return["errorMessage"] = $e->getMessage();
	    }

	    $this->renderText(json_encode($return));
	    
	    return sfView::NONE;
	}

	public function executeIsActiveCar(sfWebRequest $request) {

		$return = array("error" => false);

	    try {

	        $idCar = $request->getPostParameter("idCar", null);

	        if ($idCar != "") {

	            $Car = Doctrine_Core::getTable('Car')->findByIdAndSeguroOkAndActivo($idCar,4,1);
	            
	            if (count($Car) == 0) {
	            	$return["original"] = false;
	            } else {
	            	$return["original"] = true;
	            }
	        } 
	    } catch (Exception $e) {
	        $return["error"] = true;
	        $return["errorCode"] = $e->getCode();
	        $return["errorMessage"] = $e->getMessage();
	    }

	    $this->renderText(json_encode($return));
	    
	    return sfView::NONE;
	}

	public function executeVerifyCar(sfWebRequest $request) {
	}

    public function executeSaveAccessories(sfWebRequest $request) { 


        $return = array("error" => false);

        $carId                 = $request->getPostParameter("carId", null);   
        $accessory             = $request->getPostParameter("accessory", null);

        try {

            $Car = Doctrine_Core::getTable('Car')->find($carId);
            if (!$Car) { 
                throw new Exception("Auto no encontrado", 1);
            }

            $accessories = $Car->accesoriosSeguro;
            $string = "";
            
            if (strrpos($accessories, "images") || $accessories == "") {
                $string = $accessory;
            } else if(strrpos($accessories, $accessory)) {
                $string = str_replace("_".$accessory, "",$accessories);
            } else {
                $string = $accessories."_".$accessory;
            }

            $Car->setAccesoriosSeguro($string);
            $Car->save();

            /*$CarAudioAccessories->setRadioMarca($radioMarca);
            $CarAudioAccessories->save();*/

        } catch (Exception $e) {
            $return["error"] = true;
            $return["errorMessage"] = $e->getMessage();
            error_log($e->getMessage());
        }

        $this->renderText(json_encode($return));

        return sfView::NONE;
    }

    public function executeSaveAudioAccessories(sfWebRequest $request) { 


        $return = array("error" => false);

        $carId                 = $request->getPostParameter("carId", null);   
        $radioMarca            = $request->getPostParameter("radioMarca", null);   
        $radioModelo           = $request->getPostParameter("radioModelo", null);
        $radioTipo             = $request->getPostParameter("radioTipo", null);
        $parlantesMarca        = $request->getPostParameter("parlantesMarca", null);
        $parlantesModelo       = $request->getPostParameter("parlantesModelo", null);
        $tweetersMarca         = $request->getPostParameter("tweetersMarca", null);
        $tweetersModelo        = $request->getPostParameter("tweetersModelo", null);
        $ecualizadorMarca      = $request->getPostParameter("ecualizadorMarca", null);
        $ecualizadorModelo     = $request->getPostParameter("ecualizadorModelo", null);
        $amplificadorMarca     = $request->getPostParameter("amplificadorMarca", null);
        $amplificadorModelo    = $request->getPostParameter("amplificadorModelo", null);
        $compactCdMarca        = $request->getPostParameter("compactCdMarca", null);
        $compactCdModelo       = $request->getPostParameter("compactCdModelo", null);
        $subwooferMarca        = $request->getPostParameter("subwooferMarca", null);
        $subwooferModelo       = $request->getPostParameter("subwooferModelo", null);
        $sistemaDvdMarca       = $request->getPostParameter("sistemaDvdMarca", null);
        $sistemaDvdModelo      = $request->getPostParameter("sistemaDvdModelo", null);
        $otrosAccesorios       = $request->getPostParameter("otrosAccesorios", null);

        try {

            $Car = Doctrine_Core::getTable('Car')->find($carId);
            if (!$Car) { 
                throw new Exception("Auto no encontrado", 1);
            }

            $CarAudioAccessories  = new CarAudioAccessories();

            $CarAudioAccessories->setRadioMarca($radioMarca);
            $CarAudioAccessories->setRadioModelo($radioModelo);
            $CarAudioAccessories->setRadioTipo($radioTipo);
            $CarAudioAccessories->setParlantesMarca($parlantesMarca);
            $CarAudioAccessories->setParlantesModelo($parlantesModelo);
            $CarAudioAccessories->setTweetersMarca($tweetersMarca);
            $CarAudioAccessories->setTweetersModelo($tweetersModelo);
            $CarAudioAccessories->setEcualizadorMarca($ecualizadorMarca);
            $CarAudioAccessories->setEcualizadorModelo($ecualizadorModelo);
            $CarAudioAccessories->setAmplificadorMarca($amplificadorMarca);
            $CarAudioAccessories->setAmplificadorModelo($amplificadorModelo);
            $CarAudioAccessories->setCompactCdMarca($compactCdMarca);
            $CarAudioAccessories->setCompactCdModelo($compactCdModelo);
            $CarAudioAccessories->setSubwooferMarca($subwooferMarca);
            $CarAudioAccessories->setSubwooferModelo($subwooferModelo);
            $CarAudioAccessories->setSistemaDvdMarca($sistemaDvdMarca);
            $CarAudioAccessories->setSistemaDvdModelo($sistemaDvdModelo);
            $CarAudioAccessories->setOtros($otrosAccesorios);
            $CarAudioAccessories->setCar($Car);
            $CarAudioAccessories->save();

        } catch (Exception $e) {
            $return["error"] = true;
            $return["errorMessage"] = $e->getMessage();
            error_log($e->getMessage());
        }

        $this->renderText(json_encode($return));

        return sfView::NONE;
    }

    public function executeSaveDamage(sfWebRequest $request) { 
        $return = array("error" => false);

        $carId          = $request->getPostParameter("carId", null);
        $lat            = $request->getPostParameter("lat", null);
        $lng            = $request->getPostParameter("lng", null);
        $coordX         = $request->getPostParameter("coordX", null);
        $coordY         = $request->getPostParameter("coordY", null);
        $description    = $request->getPostParameter("description", null);
        $damageId       = $request->getPostParameter("damageId", null);

        try {

            if ($damageId) {
                $Damage = Doctrine_Core::getTable('Damage')->find($damageId);
            } else {
                $Damage = new Damage();
                $Damage->setFecha(Date("Y-m-d H:i:s"));
                $return["opcion"] = 1;
            }

            $Damage->setCarId($carId);
            $Damage->setLat($lat);
            $Damage->setLng($lng);
            $Damage->setCoordX($coordX);
            $Damage->setCoordY($coordY);
            $Damage->setDescription($description);
            $Damage->save();

            $return["data"] = $Damage->id;

        } catch (Exception $e) {
            $return["error"] = true;
            $return["errorMessage"] = $e->getMessage();
        }

        $this->renderText(json_encode($return));

        return sfView::NONE;
    }

	public function executeUploadPhoto(sfWebRequest $request) {

        $return = array("error" => false);
        $valid_formats = array("jpg", "png", "gif", "bmp", "jpeg");

        try {

            if (!isget($_POST) || $_SERVER['REQUEST_METHOD'] != "POST") {
                throw new Exception("No! No! No!", 1);
            }

            $name = $_FILES[$request->getParameter('file')]['name'];
            $size = $_FILES[$request->getParameter('file')]['size'];
            $tmp  = $_FILES[$request->getParameter('file')]['tmp_name'];
            if (!strlen($name)) {
                throw new Exception("Por favor, seleccione una imagen", 2);
            }

            list($txt, $ext) = explode(".", $name);

            $ext = strtolower($ext);

            if (!in_array($ext, $valid_formats)) {
                throw new Exception("Formato de la imagen inválido", 2);
            }

            if ($size > (5 * 1024 * 1024)) {
                throw new Exception("La imagen excede el tamaño máximo permitido (1 MB)", 2);                
            }
            
            /*$sizewh = getimagesize($tmp);

            if($sizewh[0] > 194 || $sizewh[1] > 204) {
                echo '<script>alert(\'La imagen no cumple con las dimensiones especificadas. Puede continuar si lo desea\')</script>';
            }*/
            $carId= sfContext::getInstance()->getUser()->getAttribute('carId');
            $actual_image_name = time() . $carId . "." . $ext;

            $uploadDir = sfConfig::get("sf_web_dir");
            $path      = $uploadDir . '/images/cars/';
            $fileName  = $actual_image_name . "." . $ext;

            $tmp = $_FILES[$request->getParameter('file')]['tmp_name'];

            $uploaded = move_uploaded_file($tmp, $path . $actual_image_name);

            if (!$uploaded) {
                throw new Exception("No se pudo subir la imagen de perfil", 1);
            }

            sfContext::getInstance()->getConfiguration()->loadHelpers("Asset");

            /*echo "<input type='hidden' name='" . $request->getParameter('photo') . "' value='" . $path . $actual_image_name . "'/>";
            echo "<img src='" . image_path("users/" . $actual_image_name) . "' class='preview' height='" . $request->getParameter('height') . "' width='" . $request->getParameter('width') . "' />";*/

            $Car = Doctrine_Core::getTable('car')->find($carId);
            $Car->setFotoPerfil("/images/cars/".$actual_image_name);
            $Car->save();
        } catch (Exception $e) {
            $return["error"] = true;
            $return["errorMessage"] = $e->getMessage();
            if ($e->getCode() == 1) {
                $return["errorMessage"] = "Problemas al subir la imagen. El problema ha sido notificado al equipo de desarrollo, por favor, intentalo más tarde";
            }
            if ($request->getHost() == "www.arriendas.cl" && $e->getCode() == 1) {
                Utils::reportError($e->getMessage(), "main/uploadPhoto");
            }
        }

        $this->renderText(json_encode($return));

        return sfView::NONE;
    }

}
