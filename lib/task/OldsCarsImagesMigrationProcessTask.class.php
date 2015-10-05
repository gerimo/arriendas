<?php




class OldsCarsImagesMigrationProcessTask extends sfBaseTask {
    
    protected function configure() {

        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'frontend'),
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'local'),
            new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'doctrine')
        ));

        $this->namespace = 'images';
        $this->name = 'OldsCarsImagesMigrationProcess';
        $this->briefDescription = 'Busca y procesa las imagenes de autos que no estén en s3';
        $this->detailedDescription = <<<EOF
        The [MoveToS3Task|INFO] task does things.
        Call it with:

        [php symfony images:MoveToS3Task|INFO]
EOF;
    }



   protected function execute($arguments = array(), $options = array()) {

        $config = ProjectConfiguration::getApplicationConfiguration("frontend", "prod", TRUE);
        sfContext::createInstance($config);
        $context = sfContext::createInstance($this->configuration);
        $context->getConfiguration()->loadHelpers('Partial');

        // initialize the database connection
        $databaseManager = new sfDatabaseManager($this->configuration);
        $connection = $databaseManager->getDatabase($options['connection'])->getConnection();

        $startTime = microtime(true);
        $this->log("Procesando...");

        $Cars = Doctrine_core::getTable("car")->findAll();

        $countTotalFotos = 0;
        $countTotalFotosRespaldadas = 0;
        $countTotalFotosConProblemas = 0;

        // fotos con error al ser guardadas
        $countErrorSubida = 0;

        // indica la cantidad de fotos almacenadas con la ruta en forma de link
        $countFotosLink = 0;

        // indica la cantidad de fotos almacendads que no se nocone su ruta
        $countFotosSinLink = 0;

        $uploadDir =  "/home/sergio/Proyectos/respaldo_imagenes_auto/";
        $uploadDirError =  "/home/sergio/Proyectos/respaldo_imagenes_auto_error/";

        foreach ($Cars as $Car) {

            $arrayImages = array("3" => $Car->foto_perfil,
                                     "4" => $Car->padron,
                                     "5" => $Car->seguroFotoFrente,
                                     "6" => $Car->seguroFotoCostadoDerecho,
                                     "7" => $Car->seguroFotoCostadoIzquierdo,
                                     "8" => $Car->seguroFotoTraseroDerecho,
                                     "9" => $Car->seguroFotoTraseroIzquierdo,
                                     "10" => $Car->llanta_del_der,
                                     "11" => $Car->llanta_del_izq,
                                     "12" => $Car->llanta_tra_izq,
                                     "13" => $Car->llanta_tra_der,
                                     "14" => $Car->tablero,
                                     "15" => $Car->equipo_audio,
                                     "16" => $Car->rueda_repuesto,
                                     "17" => $Car->accesorio1,
                                     "18" => $Car->accesorio2
                                     );
            
            // Estado de fotos 
            //$foto_perfil = $Car->foto_perfil;
        
            // imagen de perfil
            foreach ($arrayImages as $key => $imagePath) {
                // $this->log("Car ID: ".$Car->id." key: ".$key." value: ".$value);
                $value = $imagePath;
                if($value != ""){
                    $countTotalFotos++;

                    if(Image::isImage($value)){
                        
                        if (strpos($value,'cloudinary') !== false) {

                        } else {

                            if (strpos($value,'amazonaws') !== false) {

                                if (strpos($value,'arriendas.testing') !== false) {

                                }else{

                                    if(strpos($value,'arriendas.images') !== false){

                                    }
                                }

                            } else {
                                // foto que no encaja en ningún link
                                // $this->log("url unknoun: ".$value . "  car id:" . $Car->id);
                                // $value = "-".$value;
                            }   
                        }
                        
                    } else {
                        if(Image::isImage("http://www.arriendas.cl".$value)){
                            $value = "http://www.arriendas.cl".$value;
                            //$this->log("Valido: http://www.arriendas.cl".$value);
                        } else {
                            if(Image::isImage("http://www.arriendas.cl/images/cars/".$value)){
                                $value = "http://www.arriendas.cl/images/cars/".$value;
                                //$this->log("Valido: http://www.arriendas.cl/images/cars/".$value);
                            } else {
                                if(Image::isImage("http://www.arriendas.cl/uploads/cars/".$value)){
                                    $value = "http://www.arriendas.cl/uploads/cars/".$value;
                                    //$this->log("Valido: http://www.arriendas.cl/uploads/cars/".$value);
                                } else {
                                    if(Image::isImage("http://res.cloudinary.com/arriendas-cl/image/fetch/c_fill,g_center/http://www.arriendas.cl/uploads/verificaciones/".$value)){
                                        $value = "http://res.cloudinary.com/arriendas-cl/image/fetch/c_fill,g_center/http://www.arriendas.cl/uploads/verificaciones/".$value;
                                    }else{
                                        if(Image::isImage("http://res.cloudinary.com/arriendas-cl/image/fetch/c_fill,g_center/http://www.arriendas.cl/cars/verificaciones/".$value)){
                                            $value = "http://res.cloudinary.com/arriendas-cl/image/fetch/c_fill,g_center/http://www.arriendas.cl/cars/verificaciones/".$value;
                                        }else{
                                            $this->log("unknown source: ".$value . "  car id:" . $Car->id);
                                        }
                                    }
                                }
                            }
                        }
                    }

                    $path_info = pathinfo($value);
                    $ext = $path_info["extension"];

                    if(empty($ext)){
                        $ext = "sinExt";
                    }

                    $nombre_respaldo = $uploadDir . $key ."-".$Car->id.".".$ext;

                    if(strtoupper($ext) == 'JPG' || strtoupper($ext) == 'JPEG') {
                        $m_img = imagecreatefromjpeg($value);
                    } elseif(strtoupper($ext) == 'PNG') {
                        $m_img = imagecreatefrompng($value);
                        imagealphablending($m_img, false);
                        imagesavealpha($m_img, true);
                    } elseif(strtoupper($ext) == 'GIF') {
                        $m_img = imagecreatefromgif($value);
                    } else {
                        $m_img = FALSE;
                    }

                    if($m_img){

                        if(strtoupper($ext) == 'JPG' || strtoupper($ext) == 'JPEG') {
                            if(imagejpeg($m_img, $nombre_respaldo, 100)){
                                $countTotalFotosRespaldadas++;
                            } else {
                                copy($value, $uploadDirError  . $key ."-".$Car->id.".".$ext );
                                $this->log("error upload: ".$value . "  car id:" . $Car->id);
                                $countTotalFotosConProblemas++;
                            }
                        } elseif(strtoupper($ext)  == 'PNG') {
                            if(imagepng($m_img, $nombre_respaldo, 0)){
                                $countTotalFotosRespaldadas++;
                            } else {
                                copy($value, $uploadDirError  . $key ."-".$Car->id.".".$ext );
                                $this->log("error upload: ".$value . "  car id:" . $Car->id);
                                $countTotalFotosConProblemas++;
                            }
                        } elseif(strtoupper($ext)  == 'GIF') {
                            if(imagegif($m_img, $nombre_respaldo)){
                                $countTotalFotosRespaldadas++;
                            }else {
                                copy($value, $uploadDirError  . $key ."-".$Car->id.".".$ext );
                                $this->log("error upload: ".$value . "  car id:" . $Car->id);
                                $countTotalFotosConProblemas++;
                            }
                        } else{
                            copy($value, $uploadDirError  . $key ."-".$Car->id.".".$ext);
                            $this->log("error upload: ".$value . "  car id:" . $Car->id);
                            $countTotalFotosConProblemas++;
                        }
                    } else {
                        copy($value, $uploadDirError  . $key ."-".$Car->id.".".$ext);
                        $this->log("error upload: ".$value . "  car id:" . $Car->id);
                        // $this->log("n: ".($countTotalFotosConProblemas + 1)." ext: ".$ext." link: ".$value." CarID: ".$Car->id);
                        $countTotalFotosConProblemas++;
                    }
                }
            }
        }

        $endTime = microtime(true);
        $this->log("[".date("Y-m-d H:i:s")."] Tiempo total de procesamiento ".(round($endTime-$startTime, 2)/60) ." Minutos");
        $this->log("*****************Fotos validas***************");
        $this->log("Total fotos:                                     ".$countTotalFotos);
        $this->log("---------------------------------------------");
        $this->log("Total Respaldadas:                               ".$countTotalFotosRespaldadas);
        $this->log("---------------------------------------------");
        $this->log("Total con error:                                 ".$countTotalFotosConProblemas);
        $this->log("---------------------------------------------");
       
    }

}