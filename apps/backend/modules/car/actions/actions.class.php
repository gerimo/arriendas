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

            $damageId = $request->getPostParameter("damageId", null);

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
                    'user_telephone' => $Car->getUser()->telephone,
                    'user_email'  => $Car->getUser()->email
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
        $return["accessory"] = false;

        $carId   = $request->getPostParameter("carId", null);
        try {

            if (is_null($carId) || $carId == "") {
                throw new Exception("Debe ingresar una id correcta", 1);                
            }

            $Car = Doctrine_Core::getTable('Car')->findOneById($carId);

            if (!$Car) {
                throw new Exception("Auto no encontrado", 1);                
            }
            $arrayImagesS3 = $Car->getArrayImages();

            $CarAudioAccessories = Doctrine_Core::getTable('CarAudioAccessories')->findOneByCarId($Car->id);

            if (count($CarAudioAccessories) != 1 ) {
                $return["accessory"] = array(
                        'radioMarca'          => $CarAudioAccessories->radio_marca, 
                        'radioModelo'         => $CarAudioAccessories->radio_modelo,  
                        'radioTipo'           => $CarAudioAccessories->radio_tipo,
                        'parlantesMarca'      => $CarAudioAccessories->parlantes_marca,
                        'parlantesModelo'     => $CarAudioAccessories->parlantes_modelo,
                        'tweetersMarca'       => $CarAudioAccessories->tweeters_marca,
                        'tweetersModelo'      => $CarAudioAccessories->tweeters_modelo,
                        'ecualizadorMarca'    => $CarAudioAccessories->ecualizador_marca,
                        'ecualizadorModelo'   => $CarAudioAccessories->ecualizador_modelo,
                        'amplificadorMarca'   => $CarAudioAccessories->amplificador_marca, 
                        'amplificadorModelo'  => $CarAudioAccessories->ecualizador_modelo,  
                        'compactCdMarca'      => $CarAudioAccessories->compact_cd_marca,
                        'compactCdModelo'     => $CarAudioAccessories->compact_cd_modelo,
                        'subwooferMarca'      => $CarAudioAccessories->subwoofer_marca,  
                        'subwooferModelo'     => $CarAudioAccessories->subwoofer_modelo,
                        'sistemaDvdMarca'     => $CarAudioAccessories->sistema_dvd_marca,
                        'sistemaDvdModelo'    => $CarAudioAccessories->sistema_dvd_modelo,
                        'otrosAccesorios'     => $CarAudioAccessories->otros
                );

            }
            
            $return["data"] = array(
                "frente"      => ($arrayImagesS3["seguroFotoFrente"]) ? $arrayImagesS3["seguroFotoFrente"]->getImageSize("md") : $Car->seguroFotoFrente,
                "costadoD"    => ($arrayImagesS3["seguroFotoCostadoDerecho"]) ? $arrayImagesS3["seguroFotoCostadoDerecho"]->getImageSize("md") : $Car->seguroFotoCostadoDerecho,
                "costadoI"    => ($arrayImagesS3["seguroFotoCostadoIzquierdo"]) ? $arrayImagesS3["seguroFotoCostadoIzquierdo"]->getImageSize("md") : $Car->seguroFotoCostadoIzquierdo,
                "traseroD"    => ($arrayImagesS3["seguroFotoTraseroDerecho"]) ? $arrayImagesS3["seguroFotoTraseroDerecho"]->getImageSize("md") : $Car->seguroFotoTraseroDerecho,
                "traseroI"    => ($arrayImagesS3["seguroFotoTraseroIzquierdo"]) ? $arrayImagesS3["seguroFotoTraseroIzquierdo"]->getImageSize("md") : $Car->seguroFotoTraseroIzquierdo,
                "panel"       => ($arrayImagesS3["fotoTablero"]) ? $arrayImagesS3["fotoTablero"]->getImageSize("md") : $Car->tablero,
                "padron"      => ($arrayImagesS3["fotoPadron"]) ? $arrayImagesS3["fotoPadron"]->getImageSize("md") : $Car->padron,
                "accesorio1"  => ($arrayImagesS3["fotoAccesorio1"]) ? $arrayImagesS3["fotoAccesorio1"]->getImageSize("md") : $Car->accesorio1,
                "accesorio2"  => ($arrayImagesS3["fotoAccesorio2"]) ? $arrayImagesS3["fotoAccesorio2"]->getImageSize("md") : $Car->accesorio2,
                "accesoriosSeguro" => $Car->accesoriosSeguro
            );


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
                $return["urlFotoS3"] = $Damage->getDamageImageS3("md");
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
	        $Cars       = Doctrine_Core::getTable('Car')->findCars(0, $limit, $Reserve->getFechaInicio2(), $Reserve->getFechaTermino2(), $withAvailability, false, false, false, false, false, 13, false, $isAutomatic, $isLowConsumption, $isMorePassengers, false, false,false);

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
        $option                = $request->getPostParameter("option", null);

        try {

            $Car = Doctrine_Core::getTable('Car')->find($carId);
            if (!$Car) { 
                throw new Exception("Auto no encontrado", 1);
            }

            if ($option == 4) {
                $Car->setSeguroOk(4);
                // Notificaciones
                Notification::make($Car->getUser()->id, 6); 
                $Car->save();
                $return["option"] = 4; 
            }

            $CarAudioAccessories = Doctrine_Core::getTable('CarAudioAccessories')->findOneByCarId($Car->id);

            if (!$CarAudioAccessories) {
                $CarAudioAccessories  = new CarAudioAccessories();
            }
            
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

        try {

            if (!isset($_POST) || $_SERVER['REQUEST_METHOD'] != "POST") {
                throw new Exception("No! No! No!", 1);
            }

            $carId  = $request->getPostParameter("carId", null);
            $op     = $request->getPostParameter("op", null);
            $damageId     = $request->getPostParameter("damageId", null);

            $Car = Doctrine_Core::getTable('Car')->find($carId);

            if (!$Car) {
                throw new Exception("No se encontro el auto", 1);
            }

            $name = $_FILES['photo']['name'];
            $size = $_FILES['photo']['size'];
            $tempFile  = $_FILES['photo']['tmp_name'];


            if ($op == 1) {
                $message = Image::UploadImageToTempFolder($tempFile, $size, $name, 5, null, $carId);
            } elseif ($op == 2) {
                $message = Image::UploadImageToTempFolder($tempFile, $size, $name, 6, null, $carId);
            } elseif ($op == 3) {
                $message = Image::UploadImageToTempFolder($tempFile, $size, $name, 7, null, $carId);
            } elseif ($op == 4) {
                $message = Image::UploadImageToTempFolder($tempFile, $size, $name, 8, null, $carId);
            } elseif ($op == 5) {
                $message = Image::UploadImageToTempFolder($tempFile, $size, $name, 9, null, $carId);
            } elseif ($op == 6) {
                $message = Image::UploadImageToTempFolder($tempFile, $size, $name, 14, null, $carId);
            } elseif ($op == 7) {
                $message = Image::UploadImageToTempFolder($tempFile, $size, $name, 4, null, $carId);
            } elseif ($op == 8) {
                $message = Image::UploadImageToTempFolder($tempFile, $size, $name, 17, null, $carId);
            } elseif ($op == 9) {
                $message = Image::UploadImageToTempFolder($tempFile, $size, $name, 18, null, $carId);
            } elseif ($op == 10) {
                $Damage = Doctrine_Core::getTable('Damage')->find($damageId);
                if(!$Damage){
                    throw new Exception("No se encontró el registro de Damage", 1);
                }
                $message = Image::UploadImageToTempFolder($tempFile, $size, $name, 28, null, null, $Damage->id);
            }

            if(strpos($message, "Mensaje:")){
                throw new Exception($message, 2);
            }else{
                $Image = Doctrine_Core::getTable("image")->find($message);
                $return["urlPhoto"] = $Image->getImageSize("md");
            }

        } catch (Exception $e) {
            $return["error"] = true;
            $return["errorMessage"] = $e->getMessage();
            error_log($e->getMessage());
            if ($e->getCode() == 1) {
                $return["errorMessage"] = "Problemas al subir la imagen. El problema ha sido notificado al equipo de desarrollo, por favor, intentalo más tarde";
            }
        }

        $this->renderText(json_encode($return));
        return sfView::NONE;
    }

}
