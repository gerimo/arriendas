<?php

class Image extends BaseImage {

	// método que guarda una imagen en la carpeta temporal del proyecto. devuelve el id de la imagen si se completó con éxito.
	// De lo contrario devuelve el mensaje de error.
	public static function uploadImageToTempFolder($tempFile, $size, $name, $type, $userId = null, $carId = null) {
		require sfConfig::get('sf_app_lib_dir') . "/s3upload/image_check.php";
 		
 		$msg = null;

        // Extrae la extencion
        list($txt, $ext) = explode(".", $name);
        $ext = strtolower($ext);

        if (strlen($name) > 0) {
            if (in_array($ext, $valid_formats)) {
                if ($size < (1024 * 1024 * 10)) {

                    $uploadDir = sfConfig::get("sf_web_dir");
        			$path      = $uploadDir . '/images/tmp_images/';

        			// se crea un registro imagen sin y se instancia para adquirir un ID
        			$image = new Image();
        			$image->save();

        			// se establece el nombre de la imagen subida
        			$actual_name = $image->id . "." . $ext;

        			error_log("PATH: ". $path ."    TEMPFILE: ".$tempFile);
        			error_log("PATH: ". $path . $actual_name);

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

                        $image->save();

                    } else {
                    	// se elimina el registro de imagen, si no se pudo guardar
                    	$image->delete();
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
        	$msg = $image->id;
        	return $msg;
        }
        

	}

    public function getImageSize($size){
        $isOns3 = $this->is_on_s3;
        if($isOns3){
            $size = strtoupper($size);
            $sizes = array("XS", "SM", "MD", "LG");
            $path = $this->path_original;
            if(in_array($size, $sizes)){
                $resultado = str_replace("original", $size, $path);
                if($resultado){
                    return $resultado;
                }
            }
        }
        return null;
    }

}