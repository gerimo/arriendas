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

            // añade el link de la imagen a cada entidad coorrespondiente
            switch ($Image->image_type_id) {

                // FOTO DE PERFIL
                case 1:
                    if($userId){
                        $User = Doctrine_core::getTable("user")->find($userId);
                        $User->setPictureFile($Image->path_md);
                        $User->save();
                    }
                    break;

                // FOTO DE LICENCIA 
                case 2:
                    if($userId){
                        $User = Doctrine_core::getTable("user")->find($userId);
                        $User->setPictureFile($Image->path_md);
                        $User->save();
                    }
                    break;

                // FOTO DE PERFIL DE AUTOMOVIL
                case 3:
                    if($carId){
                        $Car = Doctrine_core::getTable("car")->find($carId);
                        $Car->setFotoPerfil($Image->path_md);
                        $Car->save();
                    }
                    break;

                // FOTO PADRON REVERSO AUTOMOVIL
                case 4:
                    if($carId){
                        $Car = Doctrine_core::getTable("car")->find($carId);
                        $Car->setFotoPadronReverso($Image->path_md);
                        $Car->save();
                    }
                    break;

                // FOTO PADRON FRONTAL AUTOMOVIL
                case 5:
                    if($carId){
                        $Car = Doctrine_core::getTable("car")->find($carId);
                        $Car->setFotoPadron($Image->path_md);
                        $Car->save();
                    }
                    break;

                // FOTO COSTADO DERECHO AUTOMOVIL
                case 6:
                    if($carId){
                        $Car = Doctrine_core::getTable("car")->find($carId);
                        $Car->setSeguroFotoCostadoDerecho($Image->path_md);
                        $Car->save();
                    }
                    break;

                // FOTO COSTADO IZQUIERDO AUTOMOVIL
                case 7:
                    if($carId){
                        $Car = Doctrine_core::getTable("car")->find($carId);
                        $Car->setSeguroFotoCostadoIzquierdo($Image->path_md);
                        $Car->save();
                    }
                    break;

                // FOTO TRASERO DERECHO AUTOMOVIL
                case 8:
                    if($carId){
                        $Car = Doctrine_core::getTable("car")->find($carId);
                        $Car->setSeguroFotoTraseroDerecho($Image->path_md);
                        $Car->save();
                    }
                    break;

                // FOTO TRASERO IZQUIERDO AUTOMOVIL
                case 9:
                    if($carId){
                        $Car = Doctrine_core::getTable("car")->find($carId);
                        $Car->setSeguroFotoTraseroIzquierdo($Image->path_md);
                        $Car->save();
                    }
                    break;

                // LLANTA DELANTERA DERECHA
                case 10:
                    if($carId){
                        $Car = Doctrine_core::getTable("car")->find($carId);
                        $Car->setLlantaDelDer($Image->path_md);
                        $Car->save();
                    }
                    break;

                // LLANTA DELANTERA IZQUIERDA    
                case 11:
                    if($carId){
                        $Car = Doctrine_core::getTable("car")->find($carId);
                        $Car->setLlantaDelIzq($Image->path_md);
                        $Car->save();
                    }
                    break;

                // LLANTA TRASERA IZQUIERDA
                case 12:
                    if($carId){
                        $Car = Doctrine_core::getTable("car")->find($carId);
                        $Car->setLlantaTraIzq($Image->path_md);
                        $Car->save();
                    }
                    break;

                // LLANTA TRASERA DERECHA
                case 13:
                    if($carId){
                        $Car = Doctrine_core::getTable("car")->find($carId);
                        $Car->setLlantaTraDer($Image->path_md);
                        $Car->save();
                    }
                    break;

                // TABLERO AUTOMOVIL
                case 14:
                    if($carId){
                        $Car = Doctrine_core::getTable("car")->find($carId);
                        $Car->setTablero($Image->path_md);
                        $Car->save();
                    }
                    break;

                // EQUIPO AUDIO
                case 15:
                    if($carId){
                        $Car = Doctrine_core::getTable("car")->find($carId);
                        $Car->setEquipoAudio($Image->path_md);
                        $Car->save();
                    }
                    break;

                // RUEDA REPUESTO
                case 16:
                    if($carId){
                        $Car = Doctrine_core::getTable("car")->find($carId);
                        $Car->setRuedaRepuesto($Image->path_md);
                        $Car->save();
                    }
                    break;

                // ACCESORIO 1 AUTOMOVIL
                case 17:
                    if($carId){
                        $Car = Doctrine_core::getTable("car")->find($carId);
                        $Car->setAccesorio1($Image->path_md);
                        $Car->save();
                    }
                    break;

                // ACCESORIO 2 AUTOMOVIL
                case 18:
                    if($carId){
                        $Car = Doctrine_core::getTable("car")->find($carId);
                        $Car->setAccesorio2($Image->path_md);
                        $Car->save();
                    }
                    break;

                // FRONTAL DERECHO AUTOMOVIL
                case 19:
                    if($carId){
                        $Car = Doctrine_core::getTable("car")->find($carId);
                        $Car->setFrontalDer($Image->path_md);
                        $Car->save();
                    }
                    break;

                // FRONTAL IZQUIERDO AUTOMOVIL
                case 20:
                    if($carId){
                        $Car = Doctrine_core::getTable("car")->find($carId);
                        $Car->setFrontalIzq($Image->path_md);
                        $Car->save();
                    }
                    break;

                // FRONTAL CENTRO AUTOMOVIL
                case 21:
                    if($carId){
                        $Car = Doctrine_core::getTable("car")->find($carId);
                        $Car->setFrontalCen($Image->path_md);
                        $Car->save();
                    }
                    break;

                // TRASERO IZQUIERDO AUTOMOVIL
                case 22:
                    if($carId){
                        $Car = Doctrine_core::getTable("car")->find($carId);
                        $Car->setTraseroIzq($Image->path_md);
                        $Car->save();
                    }
                    break;

                // TRASERO DERECHO AUTOMOVIL
                case 23:
                    if($carId){
                        $Car = Doctrine_core::getTable("car")->find($carId);
                        $Car->setTraseroDer($Image->path_md);
                        $Car->save();
                    }
                    break;

                // TRASERO CENTRO AUTOMOVIL
                case 24:
                    if($carId){
                        $Car = Doctrine_core::getTable("car")->find($carId);
                        $Car->setTraseroCen($Image->path_md);
                        $Car->save();
                    }
                    break;

                // LATERAL DERECHO AUTOMOVIL
                case 25:
                    if($carId){
                        $Car = Doctrine_core::getTable("car")->find($carId);
                        $Car->setLateralDer($Image->path_md);
                        $Car->save();
                    }
                    break;

                // LATERAL IZQUIERDO AUTOMOVIL
                case 26:
                    if($carId){
                        $Car = Doctrine_core::getTable("car")->find($carId);
                        $Car->setLateralIzq($Image->path_md);
                        $Car->save();
                    }
                    break;

                // FOTO FRONTAL AUTOMOVIL
                case 27:
                    if($carId){
                        $Car = Doctrine_core::getTable("car")->find($carId);
                        $Car->setSeguroFotoFrente($Image->path_md);
                        $Car->save();
                    }
                    break;
                
                default:

                    break;
            }
        }

        $this->log("Total de imágenes a procesar: " . $cantidadImagenesSubidasAlLocal);
        $this->log("Total de imágenes procesadas: " . $cantidadImagenesEliminadasEnLocal);
        $this->log("Total de imágenes subidas a s3: " . $cantidadImagenesSubidasAlS3);
        

    }

}