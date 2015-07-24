<?php

class Image extends BaseImage {

	// método que guarda una imagen en la carpeta temporal del proyecto. devuelve el id de la imagen si se completó con éxito.
	// De lo contrario devuelve el mensaje de error.
	public static function uploadImageToTempFolder($tempFile, $size, $name, $type, $userId = null, $carId = null, $damageId = null) {
		require sfConfig::get('sf_lib_dir') . "/vendor/s3upload/image_check.php";
 		
        // variable que almacenará el resultado de la funcion, si tuvo exito, devolverá el id de la foto modificada. Si no, el respectivo mensaje
 		$msg = null; 
        $hasChange = false;
        // Extrae la extencion
        list($txt, $ext) = explode(".", $name);
        $ext = strtolower($ext);

        if (strlen($name) > 0) {
            if (in_array($ext, $valid_formats)) {
                if ($size < (1024 * 1024 * 10)) {

                    $uploadDir = sfConfig::get("sf_web_dir");
        			$path      = $uploadDir . '/images/tmp_images/';

        			// Verifica si esisten registros de una foto yá subida, si exite se modifica, si no, se crea una instancia.
                    if($userId){
                        $image = Doctrine_core::getTable("image")->findOneByImageTypeIdAndUserId($type, $userId);
                        $hasChange = true;
                    }elseif ($carId) {
                        $image = Doctrine_core::getTable("image")->findOneByImageTypeIdAndCarId($type, $carId);
                        $hasChange = true;
                    }elseif ($damageId) {
                        $image = Doctrine_core::getTable("image")->findOneByImageTypeIdAndDamageId($type, $damageId);
                        $hasChange = true;
                    }

                    if(!$image){
                        $image = new Image();
                        $image->save();
                    }

        			// se establece el nombre de la imagen subida
        			$actual_name = $image->id . "." . $ext;

        			// se guarda la foto en el directorio especificado.
                    if(move_uploaded_file($tempFile, $path . $actual_name)){
                        
                    	// se completan los datos del registro imagen en la DB
                        $image->setPathOriginal('/images/tmp_images/' . $actual_name);
                        $image->setImageTypeId($type);

                        // si no se envía un User Id no se setea.
                        if($userId){
                        	$image->setUserId($userId);
                        }

                        // si no se envía un Car Id no se setea.
                        if($carId){
                        	$image->setCarId($carId);
                        }

                        if($damageId){
                            $image->setDamageId($damageId);
                        }

                        $image->save();

                    } else {
                        // si la imagen fué modificada, no la elimina cuando no pueda guardarla en local
                        if(!$hasChange){
                        	// se elimina el registro de imagen, si no se pudo guardar
                        	$image->delete();
                        }
                        $msg = "Mensaje: Error al guardar la imagen, porfavor intente otra vez.";
                    }

                    
                } else {
                    $msg = "Mensaje: Tamaño máximo 10 mb.";
                }
            } else {
                $msg = "Mensaje: Archivo inválido, porfavor seleccione una imágen.";
            }
        } else {
            $msg = "Mensaje: seleccione una imágen.";
        }

        if($msg){
        	return $msg;
        }else{
            if($hasChange){
                $image->setIsOnS3(false);   
                $image->save();
            }
        	$msg = $image->id;
        	return $msg;
        }
        

	}

    public function getImageSize($size){
        $isOns3 = $this->is_on_s3;
        if($isOns3 || strtoupper($size) == "ORIGINAL"){
            $size = strtoupper($size);
            $sizes = array("XS", "SM", "MD", "LG");
            $path = $this->path_original;
            if(in_array($size, $sizes)){
                $resultado = str_replace("original", strtolower($size), $path);
                $resultado = str_replace(array("png", "gif", "jpeg"), "jpeg", $resultado);
                if($resultado){
                    return $resultado;
                }
            }
        } else {
            return $this->path_original;

        }
        return null;
    }

}