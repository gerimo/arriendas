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
        $miniatura_ancho_maximo = 800;
        $miniatura_alto_maximo = 600;

        // se establece el directorio del proyecto en donde buscar las imágenes
        $uploadDir = sfConfig::get("sf_web_dir");
        $path      = $uploadDir . '/images/s3_temp/';

        // se obtienen los registros de las imagenes almacenadas en la base de datos
        $Images = Doctrine_core::getTable("image")->findAll();

        // se iteran lás imágenes
        foreach ($Images as $Image) {

            // si el registro no fué procesado y subido a s3, lo hace
            if(!$Image->getIsOnS3()){

                // se definen los atributos de la imagen
                $info_imagen = getimagesize($Image->path);
                $imagen_ancho = $info_imagen[0];
                $imagen_alto = $info_imagen[1];
                $imagen_tipo = $info_imagen['mime'];

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
                        $imagen = imagecreatefromjpeg( $Image->path );
                        break;
                    case "image/png":
                        $imagen = imagecreatefrompng( $Image->path );
                        break;
                    case "image/gif":
                        $imagen = imagecreatefromgif( $Image->path );
                        break;
                }

                // se establece el nombre de la imagen final
                $nueva_imagen_sin_path = "h" . $miniatura_alto . "w" . $miniatura_ancho . "-" . time() . "-" . $Image->getId() . ".jpg";
                $nueva_imagen = $path . $nueva_imagen_sin_path;

                $lienzo = imagecreatetruecolor( $miniatura_ancho, $miniatura_alto );
                imagecopyresampled($lienzo, $imagen, 0, 0, 0, 0, $miniatura_ancho, $miniatura_alto, $imagen_ancho, $imagen_alto);

                if(imagejpeg($lienzo, $nueva_imagen, 100)) {

                    // se elimina la foto en s3_temp
                    if(unlink($Image->path)) {
                        $this->log($Image->path . " Deleted");
                    }

                     // se reestablecen los datos en la BD
                    $Image->setIsOnS3(true);
                    $Image->setPath($nueva_imagen);
                    $Image->save();
                }
                

            } else {
                $this->log($Image->id . " ya se procesó.");
            }

        }

    }

}