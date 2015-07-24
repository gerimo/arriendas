<?php




class ImagesMoveToS3Task extends sfBaseTask {
    
    protected function configure() {

        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'frontend'),
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'local'),
            new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'doctrine')
        ));

        $this->namespace = 'images';
        $this->name = 'moveToS3Task';
        $this->briefDescription = 'Verifica si hay imágenen en s3_temp y genera imágenes de 3 tamanos pequeño, mediano y grande';
        $this->detailedDescription = <<<EOF
        The [MoveToS3Task|INFO] task does things.
        Call it with:

        [php symfony images:MoveToS3Task|INFO]
EOF;
    }



    protected function execute($arguments = array(), $options = array()) {

        $config = ProjectConfiguration::getApplicationConfiguration("frontend", "prod", TRUE);
        sfContext::createInstance($config);

        // initialize the database connection
        $databaseManager = new sfDatabaseManager($this->configuration);
        $connection = $databaseManager->getDatabase($options['connection'])->getConnection();

        // se establecen las medidas de la imagenes de salida
        /*
            ** MEDIDAS **
            XS = 112 x 84 
            SM = 224 x 168
            MD = 448 x 336
            LG = 896 x 672
        */
        $medidas["xs"][0]=112;
        $medidas["xs"][1]=84;

        $medidas["sm"][0]=224;
        $medidas["sm"][1]=168;

        $medidas["md"][0]=448;
        $medidas["md"][1]=336;

        $medidas["lg"][0]=896;
        $medidas["lg"][1]=672;

        // se establece el directorio del proyecto en donde buscar las imágenes
        $uploadDir = sfConfig::get("sf_web_dir");
        $path      = $uploadDir . '/images/tmp_images/';

        // se carga la librería del aws
        require_once sfConfig::get('sf_lib_dir')."/vendor/s3upload/s3_config.php";

        $this->log("Ejecutando...");

        // se obtienen los registros de las imagenes almacenadas en la base de datos
        $Images = Doctrine_core::getTable("image")->findByIsOnS3(0);

        // Indicadores
        $cantidadImagenesSubidasAlLocal = 0;
        $cantidadImagenesProcesadasEnLocal = 0;
        $cantidadImagenesSubidasAlS3 = 0;
        $cantidadImagenesTemporalesSubidasEnLocal = 0;
        $cantidadImagenesTemporalesEliminadasEnLocal = 0;
        $cantidadImagenesConProblemasDeSubida = 0;

        // se iteran lás imágenes
        foreach ($Images as $Image) {
            $userId = $Image->user_id;
            $carId = $Image->car_id;

            $cantidadImagenesSubidasAlLocal = 1 + $cantidadImagenesSubidasAlLocal;
            // se definen los atributos de la imagen
            $info_imagen = getimagesize($uploadDir . $Image->path_original);
            $imagen_ancho = $info_imagen[0];
            $imagen_alto = $info_imagen[1];
            $imagen_tipo = $info_imagen['mime'];

            // se detecta el tipo de imágen del archivo original
            switch ( $imagen_tipo ){
                case "image/jpg":
                    $nueva_imagen_sin_path = $Image->getId() . ".jpg";
                    break;
                case "image/jpeg":
                    $nueva_imagen_sin_path = $Image->getId() . ".jpeg";
                    break;
                case "image/png":
                    $nueva_imagen_sin_path = $Image->getId() . ".png";
                    break;
                case "image/gif":
                    $nueva_imagen_sin_path = $Image->getId() . ".gif";
                    break;
            }
            
            // se sube la imagen original al s3
            if($s3->putObjectFile($uploadDir.$Image->path_original, $bucket, "original/".$nueva_imagen_sin_path, S3::ACL_PUBLIC_READ) ) {
                $s3file_original = "https://s3-sa-east-1.amazonaws.com/".$bucket."/original/".$nueva_imagen_sin_path;
                // $s3file_original ='http://'.$bucket.'.s3.amazonaws.com/'.$nueva_imagen_sin_path;
                $cantidadImagenesSubidasAlS3 = 1 + $cantidadImagenesSubidasAlS3;
            }

            // se crearán y se subirán las imágenes con las medidas previamente establecidas.
            foreach ($medidas as $medida => $value) {
                $miniatura_ancho_maximo = $value[0];
                $miniatura_alto_maximo = $value[1];

                // se establece un "marco" del tamaño especificado anteriormente, en donde de ajustará la imagen en cuestión
                $lienzo = imagecreatetruecolor( $miniatura_ancho_maximo, $miniatura_alto_maximo );
            
                // se identifican las proporciones
                $proporcion_imagen = $imagen_ancho / $imagen_alto;
                $proporcion_miniatura = $miniatura_ancho_maximo / $miniatura_alto_maximo;

                if ( $proporcion_imagen > $proporcion_miniatura ){
                    $miniatura_ancho = $miniatura_ancho_maximo;
                    $miniatura_alto = $miniatura_ancho_maximo / $proporcion_imagen;
                } else if ( $proporcion_imagen < $proporcion_miniatura ){
                    $miniatura_ancho = $miniatura_alto_maximo * $proporcion_imagen;
                    $miniatura_alto = $miniatura_alto_maximo;
                } else {
                    $miniatura_ancho = $miniatura_ancho_maximo;
                    $miniatura_alto = $miniatura_alto_maximo;
                }

                //se crea la imagen del tipo espefico
                switch ( $imagen_tipo ){
                    case "image/jpg":
                        $imagen = imagecreatefromjpeg($uploadDir . $Image->path_original );
                        break;
                    case "image/jpeg":
                        $imagen = imagecreatefromjpeg($uploadDir . $Image->path_original );
                        break;
                    case "image/png":
                        $imagen = imagecreatefrompng($uploadDir . $Image->path_original );
                        break;
                    case "image/gif":
                        $imagen = imagecreatefromgif($uploadDir . $Image->path_original );
                        break;
                }

                // se establece el nombre de la imagen final
                $nueva_imagen_sin_path = $Image->getId() . ".jpeg";
                $nueva_imagen = $path . $nueva_imagen_sin_path;

                $lienzo = imagecreatetruecolor( $miniatura_ancho, $miniatura_alto );
                imagecopyresampled($lienzo, $imagen, 0, 0, 0, 0, $miniatura_ancho, $miniatura_alto, $imagen_ancho, $imagen_alto);
                
                if(imagejpeg($lienzo, $nueva_imagen, 100)) {

                    $cantidadImagenesTemporalesSubidasEnLocal = $cantidadImagenesTemporalesSubidasEnLocal + 1;
                    // se sube la imágen al s3
                    if($s3->putObjectFile($nueva_imagen, $bucket, $medida."/".$nueva_imagen_sin_path, S3::ACL_PUBLIC_READ) ) {
                        $cantidadImagenesSubidasAlS3 = 1 + $cantidadImagenesSubidasAlS3;
                        $path_s3 = "https://s3-sa-east-1.amazonaws.com/".$bucket."/".$medida."/".$nueva_imagen_sin_path;

                        if(unlink($nueva_imagen)){
                            $cantidadImagenesTemporalesEliminadasEnLocal = 1 + $cantidadImagenesTemporalesEliminadasEnLocal;
                        }
                        
                    } else {
                        $cantidadImagenesConProblemasDeSubida++;
                    }                                
                     
                } else {
                    $cantidadImagenesConProblemasDeSubida++;
                }

            }
            if(unlink($uploadDir . $Image->path_original)){
                $cantidadImagenesProcesadasEnLocal = 1 + $cantidadImagenesProcesadasEnLocal;
            }

            $Image->setPathOriginal($s3file_original);
            $Image->setIsOnS3(true);

            $Image->save();
        }

        $this->log("--------------------------------------------------");
        $this->log("Total de imágenes a procesar: " . $cantidadImagenesSubidasAlLocal);
        $this->log("Total de imágenes procesadas: " . $cantidadImagenesProcesadasEnLocal);
        $this->log("--------------------------------------------------");
        $this->log("Total de imágenes temporales creadas: " . $cantidadImagenesTemporalesSubidasEnLocal);
        $this->log("Total de imágenes temporales Eliminadas: " . $cantidadImagenesTemporalesEliminadasEnLocal);
        $this->log("--------------------------------------------------");
        $this->log("Total de imágenes subidas a s3: " . $cantidadImagenesSubidasAlS3);
        $this->log("--------------------------------------------------");
        $this->log("Total de imágenes con error: " . $cantidadImagenesConProblemasDeSubida);
        

    }

}