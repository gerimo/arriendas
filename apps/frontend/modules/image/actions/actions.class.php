<?php

class imageActions extends sfActions {

	public function executeIndex(sfWebRequest $request) {
		$this->setLayout("newIndexLayout");


	}

/*
	public function executeUpload(sfWebRequest $request) {
		require sfConfig::get('sf_app_lib_dir') . "/s3upload/image_check.php";
		$this->setLayout("newIndexLayout");

		if (!empty($_FILES)) {
			error_log("entra al proceso...");
            $tempFile = $_FILES['Filedata']['tmp_name'];
            $name = $_FILES['Filedata']['name'];
            $size = $_FILES['Filedata']['size'];

            // Extrae la extencion
            list($txt, $ext) = explode(".", $name);
            $ext = strtolower($ext);

            $bandera = FALSE;
            if (strlen($name) > 0) {
                if (in_array($ext, $valid_formats)) {
                    if ($size < (1024 * 1024 * 10)) {
                        
                        $check = getimagesize($_FILES['Filedata']['tmp_name']);

                        $uploadDir = sfConfig::get("sf_web_dir");
            			$path      = $uploadDir . '/images/s3_temp/';

            			//$actual_name = time() . "." . $ext;

            			// se crea un registro imagen con datos "tontos"
            			$image = new Image();
            			$image->save();

            			// se establece el nombre de la imagen subida
            			$actual_name = $image->id . "." . $ext;

            			// se guarda la foto en el directorio especificado.
                        if(move_uploaded_file($tempFile, $path . $actual_name)){

                        	// se obtiene el alto y largo de la imagen yá guardada
                        	$widthAndHeight = getimagesize($path . $actual_name);

                        	// se completan los datos del registro imagen en la DB
	                        $image->setPath($path . $actual_name);
	                        $image->setWidth($widthAndHeight[0]);
	                        $image->setHeight($widthAndHeight[1]);
	                        $image->setSize($size);
	                        $image->save();

	                        $bandera = TRUE;

                        } else {
                        	$image->delete();
                        	$msg = "Error, try again.";
                        }

                        
                    } else {
                        $msg = "Image size Max 10 MB";
                    }
                } else {
                    $msg = "Invalid file, please upload image file.";
                }
            } else {
                $msg = "Please select image file.";
            }

        }
        if($bandera){
        	error_log("si se pudo");
        	$this->imagen = $image->path;
        } else {
        	error_log("nope");
        	$this->imagen = "http://friendsoftype.com/wp-content/uploads/2011/03/FOT_EM_NOPE_05_LRG-1250x1250.jpg";
        }

	}
*/

    public function executeUpload(sfWebRequest $request) {
        $this->setLayout("newIndexLayout");
        if (!empty($_FILES)) {
            
            $userId = $this->getUser()->getAttribute("userid");

            $tempFile = $_FILES['Filedata']['tmp_name'];
            $name = $_FILES['Filedata']['name'];
            $size = $_FILES['Filedata']['size'];

            error_log("subiendo");
            $message = Image::UploadImageToTempFolder($tempFile, $size, $name, 1, $userId);

            error_log("RESULTADO: ". $message);
            if(strpos($message, "Mensaje:")){
                
            } else {
                $Image = Doctrine_core::getTable("image")->find($message);
                $this->imagen = $Image->path_original;
            }

        }
        

    }

}
