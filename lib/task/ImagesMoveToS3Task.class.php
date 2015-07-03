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
        require sfConfig::get('sf_lib_dir')."/vendor/s3upload/s3_config.php";

        // se obtienen los registros de las imagenes almacenadas en la base de datos
        $Images = Doctrine_core::getTable("image")->findByIsOnS3(0);

        // Indicadores
        $cantidadImagenesSubidasAlLocal = 0;
        $cantidadImagenesEliminadasEnLocal = 0;
        $cantidadImagenesSubidasAlS3 = 0;

        // se iteran lás imágenes
        foreach ($Images as $Image) {
            $cantidadImagenesSubidasAlLocal = 1 + $cantidadImagenesSubidasAlLocal;
            // se definen los atributos de la imagen
            $info_imagen = getimagesize($uploadDir . $Image->path_original);
            $imagen_ancho = $info_imagen[0];
            $imagen_alto = $info_imagen[1];
            $imagen_tipo = $info_imagen['mime'];

            // se detecta el tipo de imágen del archivo original
            switch ( $imagen_tipo ){
                case "image/jpg":
                    $nueva_imagen_sin_path = $Image->getId() . "-original" . ".jpg";
                    break;
                case "image/jpeg":
                    $nueva_imagen_sin_path = $Image->getId() . "-original" . ".jpeg";
                    break;
                case "image/png":
                    $nueva_imagen_sin_path = $Image->getId() . "-original" . ".png";
                    break;
                case "image/gif":
                    $nueva_imagen_sin_path = $Image->getId() . "-original" . ".gif";
                    break;
            }
            
            // se sube la imagen original al s3
            if($s3->putObjectFile($uploadDir.$Image->path_original, $bucket , $nueva_imagen_sin_path, S3::ACL_PUBLIC_READ) ) {
                $s3file_original ='http://'.$bucket.'.s3.amazonaws.com/'.$nueva_imagen_sin_path;
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
                $nueva_imagen_sin_path = $Image->getId() . "-" . ($medida) . ".jpg";
                $nueva_imagen = $path . $nueva_imagen_sin_path;

                $lienzo = imagecreatetruecolor( $miniatura_ancho, $miniatura_alto );
                imagecopyresampled($lienzo, $imagen, 0, 0, 0, 0, $miniatura_ancho, $miniatura_alto, $imagen_ancho, $imagen_alto);

                if(imagejpeg($lienzo, $nueva_imagen, 100)) {

                    // se sube la imágen al s3
                    if($s3->putObjectFile($nueva_imagen, $bucket , $nueva_imagen_sin_path, S3::ACL_PUBLIC_READ) ) {
                        $cantidadImagenesSubidasAlS3 = 1 + $cantidadImagenesSubidasAlS3;

                        $s3file ='http://'.$bucket.'.s3.amazonaws.com/'.$nueva_imagen_sin_path;
                        if(unlink($nueva_imagen)){
                            //$cantidadImagenesEliminadasEnLocal = 1 + $cantidadImagenesEliminadasEnLocal;
                        }

                    }
                    
                    switch ($medida) {
                        case "xs":
                            $Image->setPathXs($s3file);
                            break;

                        case "sm":
                            $Image->setPathSm($s3file);
                            break;

                        case "md":
                            $Image->setPathMd($s3file);
                            break;

                        case "lg":
                            $Image->setPathLg($s3file);
                            break;
                        
                        default:
                            $Image->setPathLg("N/A");
                            break;
                    }

                    
                }
            }
            if(unlink($uploadDir . $Image->path_original)){
                $cantidadImagenesEliminadasEnLocal = 1 + $cantidadImagenesEliminadasEnLocal;
            }

            $Image->setPathOriginal($s3file_original);
            $Image->setIsOnS3(true);

            $Image->save();
        }

        $this->log("Total de imágenes a procesar: " . $cantidadImagenesSubidasAlLocal);
        $this->log("Total de imágenes procesadas: " . $cantidadImagenesEliminadasEnLocal);
        $this->log("Total de imágenes subidas a s3: " . $cantidadImagenesSubidasAlS3);
        

    }

}