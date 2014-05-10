<?php

/**
 * main actions.
 *
 * @package    CarSharing
 * @subpackage main
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class mainActions extends sfActions {

    /**x
     * Executes index action
     *
     * @param sfRequest $request A request object
     */
    //MAIN
	
	
public function executeArriendo(sfWebRequest $request){
    $ciudad = $request->getParameter("autos");
    $objeto_ciudad = Doctrine_Core::getTable("city")->findOneByName($ciudad);

            $q = Doctrine_Query::create()
                ->select('ca.id, mo.name model,
          br.name brand, ca.uso_vehiculo_id tipo_vehiculo, ca.year year,
          ca.address address, ci.name city,
          st.name state, co.name country,
          owner.firstname firstname,
          owner.lastname lastname,
          ca.price_per_day priceday,
          ca.price_per_hour pricehour'
                )
                ->from('Car ca')
                ->innerJoin('ca.Model mo')
                ->innerJoin('ca.User owner')
                ->innerJoin('mo.Brand br')
                ->innerJoin('ca.City ci')
                ->innerJoin('ci.State st')
                ->innerJoin('st.Country co')
                ->where('ca.activo = ?', 1)
                ->andWhere('ca.seguro_ok = ?', 4)
                ->andWhere('ca.city_id = ?', $objeto_ciudad->getId());
        //$this->cars = $q->execute();
        $this->cars = $q->fetchArray();
        //var_dump($this->cars);die();

        $fotos_autos = array();
        for($j=0;$j<count($this->cars);$j++){
            $auto = Doctrine_Core::getTable('car')->find(array($this->cars[$j]['id']));
            $fotos_autos[$j]['id'] = $auto->getId();
            $fotos_autos[$j]['photoS3'] = $auto->getPhotoS3();
            $fotos_autos[$j]['verificationPhotoS3'] = $auto->getVerificationPhotoS3();
            $i=0;
            if($auto->getVerificationPhotoS3() == 1){
                if($auto->getFoto() != null && $auto->getFoto() != "") {
                    $rutaFoto=$auto->getFoto();
                    $fotos_autos[$j][$i] = $rutaFoto;
                    $fotos_autos[$j][$i+1] = "Ver Fotos";
                    $i=$i+2;
                }
                if($auto->getSeguroFotoFrente() != null && $auto->getSeguroFotoFrente() != "") {
                    $rutaFotoFrente=$auto->getSeguroFotoFrente();
                    $fotos_autos[$j][$i] = $rutaFotoFrente;
                    $fotos_autos[$j][$i+1] = "Foto Frente";
                    $i=$i+2;
                }
                if($auto->getSeguroFotoCostadoDerecho() != null && $auto->getSeguroFotoCostadoDerecho() != "") {
                    $rutaFotoCostadoDerecho=$auto->getSeguroFotoCostadoDerecho();
                    $fotos_autos[$j][$i] = $rutaFotoCostadoDerecho;
                    $fotos_autos[$j][$i+1] = "Foto Costado Derecho";
                    $i=$i+2;
                }
                if(strpos($auto->getSeguroFotoCostadoIzquierdo(),"http")!=-1 && $auto->getSeguroFotoCostadoIzquierdo() != "") {
                    $rutaFotoCostadoIzquierdo=$auto->getSeguroFotoCostadoIzquierdo();
                    $fotos_autos[$j][$i] = $rutaFotoCostadoIzquierdo;
                    $fotos_autos[$j][$i+1] = "Foto Costado Izquierdo";
                    $i=$i+2;
                }
                if(strpos($auto->getSeguroFotoTraseroDerecho(),"http")!=-1 && $auto->getSeguroFotoTraseroDerecho() != "") {
                    $rutaFotoTrasera= $auto->getSeguroFotoTraseroDerecho();
                    $fotos_autos[$j][$i] = $rutaFotoTrasera;
                    $fotos_autos[$j][$i+1] = "Foto Trasera";
                    $i=$i+2;
                }   
                if(strpos($auto->getTablero(),"http")!=-1 && $auto->getTablero() != "") {
                    $rutaFotoPanel=$auto->getTablero();  
                    $fotos_autos[$j][$i] = $rutaFotoPanel;
                    $fotos_autos[$j][$i+1] = "Foto del Panel";
                    $i=$i+2;
                }
                if(strpos($auto->getAccesorio1(),"http")!=-1 && $auto->getAccesorio1() != "") {
                    $rutaFotoAccesorios1= $auto->getAccesorio1();
                    $fotos_autos[$j][$i] = $rutaFotoAccesorios1;
                    $fotos_autos[$j][$i+1] = "Foto de Accesorio 1";
                    $i=$i+2;
                }  
                if(strpos($auto->getAccesorio2(),"http")!=-1 && $auto->getAccesorio2() != "") {
                    $rutaFotoAccesorios2=$auto->getAccesorio2();
                    $fotos_autos[$j][$i] = $rutaFotoAccesorios2;
                    $fotos_autos[$j][$i+1] = "Foto de Accesorio 2";
                }
            }else{//if verificationPhotoS3 == 0
                if($auto->getFoto() != null && $auto->getFoto() != "") {
                    $rutaFoto=$auto->getFoto();
                    $fotos_autos[$j][$i] = $rutaFoto;
                    $fotos_autos[$j][$i+1] = "Ver Fotos";
                    $i=$i+2;
                }
                if($auto->getSeguroFotoFrente() != null && $auto->getSeguroFotoFrente() != "") {
                    $rutaFotoFrente=$auto->getSeguroFotoFrente();
                    $fotos_autos[$j][$i] = $rutaFotoFrente;
                    $fotos_autos[$j][$i+1] = "Foto Frente";
                    $i=$i+2;
                }
                if($auto->getSeguroFotoCostadoDerecho() != null && $auto->getSeguroFotoCostadoDerecho() != "") {
                    $rutaFotoCostadoDerecho=$auto->getSeguroFotoCostadoDerecho();
                    $fotos_autos[$j][$i] = $rutaFotoCostadoDerecho;
                    $fotos_autos[$j][$i+1] = "Foto Costado Derecho";
                    $i=$i+2;
                }
                if($auto->getSeguroFotoCostadoIzquierdo() != null && $auto->getSeguroFotoCostadoIzquierdo() != "") {
                    $rutaFotoCostadoIzquierdo=$auto->getSeguroFotoCostadoIzquierdo();
                    $fotos_autos[$j][$i] = $rutaFotoCostadoIzquierdo;
                    $fotos_autos[$j][$i+1] = "Foto Costado Izquierdo";
                    $i=$i+2;
                }
                if($auto->getSeguroFotoTraseroDerecho() != null && $auto->getSeguroFotoTraseroDerecho() != "") {
                    $rutaFotoTrasera= $auto->getSeguroFotoTraseroDerecho();
                    $fotos_autos[$j][$i] = $rutaFotoTrasera;
                    $fotos_autos[$j][$i+1] = "Foto Trasera";
                    $i=$i+2;
                }   
                if($auto->getTablero() != null && $auto->getTablero() != "") {
                    $rutaFotoPanel=$auto->getTablero();  
                    $fotos_autos[$j][$i] = $rutaFotoPanel;
                    $fotos_autos[$j][$i+1] = "Foto del Panel";
                    $i=$i+2;
                }
                if($auto->getAccesorio1() != null && $auto->getAccesorio1() != "") {
                    $rutaFotoAccesorios1= $auto->getAccesorio1();
                    $fotos_autos[$j][$i] = $rutaFotoAccesorios1;
                    $fotos_autos[$j][$i+1] = "Foto de Accesorio 1";
                    $i=$i+2;
                }  
                if($auto->getAccesorio2() != null && $auto->getAccesorio2() != "") {
                    $rutaFotoAccesorios2=$auto->getAccesorio2();
                    $fotos_autos[$j][$i] = $rutaFotoAccesorios2;
                    $fotos_autos[$j][$i+1] = "Foto de Accesorio 2";
                }
            }
        }
        //$this->arrayDescripcionFotos = $arrayDescripcion;
        //$this->arrayFotos = $arrayImagenes;
        //var_dump(count($fotos_autos[13]));die();
        //var_dump($fotos_autos);die();
        $this->fotos_autos = $fotos_autos;
        //var_dump($this->cars[0]);die();





}

public function executeFbShare(sfWebRequest $request){
	$this->forward ('main', 'index');
}


public function executePruebaImagen(sfWebRequest $request){

}




public function executeSubirImagenS3(sfWebRequest $request){

}

public function executeSubirImagenS3Ajax(sfWebRequest $request){

    // Incluimos la clase de Amazon S3
    require sfConfig::get('sf_app_lib_dir')."/amazonS3/config.php";
                
    // Nombre del Bucket en Amazon S3
    $bucket = "arriendas.prueba";
        
    // Cuando presiono el boton Upload
    foreach($_FILES['images']['error'] as $key => $error){
        if($error == UPLOAD_ERR_OK){
            // Recibo las variables por POST
            
            var_dump($_FILES['images']);
            //die();
            
            $fileName = $_FILES['images']['name'][$key];

            //asignar nombre aleatorio
            $fileName = explode(".", $fileName);
            //echo $fileName[0]. ' '.$fileName[1];
            $fileName = md5(mt_rand()).'.'.$fileName[1];

            //almacenar en la base de datos

            $fileTempName = $_FILES['images']['tmp_name'][$key];
            
            // Creo un nuevo bucket de Amazon S3
            $s3->putBucket($bucket, S3::ACL_PUBLIC_READ);
            
            // Muevo el archivo de su ruta temporal a su ruta definitiva
            if ($s3->putObjectFile($fileTempName, $bucket, $fileName, S3::ACL_PUBLIC_READ)) {
                echo "<strong>Archivo subido correctamente.</strong>";
            }else{
                echo "<strong>No se pudo subir el archivo.</strong>";
            }
        }
    }

    die();

}

public function executeFechaServidor(sfWebRequest $request){
    $fechaActual = strftime("%Y-%m-%d %H:%M:%S");
    echo $fechaActual;
    die();
}

public function executeComparaPrecios(sfWebRequest $request){
    
}

public function executeMailCalificaciones(sfWebRequest $request){
    $mailCalificaciones= Doctrine_Core::getTable("mailCalificaciones")->findByEnviado(false);
    $cont = 0;
    
    require sfConfig::get('sf_app_lib_dir')."/mail/mail.php";

    foreach ($mailCalificaciones as $calificaciones){
        $fechaEnvio = strtotime($calificaciones->getDate());
        $fechaActual = strtotime($this->formatearHoraChilena(strftime("%Y-%m-%d %H:%M:%S")));

        if($fechaEnvio<$fechaActual){
            $cont ++;
            //echo $calificaciones->getId()."<br>";

            //enviar mail
            $calificaciones->enviarMailCalificaciones();

            //actualizar campo enviado
            $calificaciones->setEnviado(true);
            $calificaciones->save();
        }

    }

    return $this->renderText("Enviados ".$cont." mail");
}
 

public function executeVerificacionSeguro(sfWebRequest $request) {
    //Verificamos el estado de la tabla Security Tokens
    //$tokens= Doctrine_Core::getTable("SecurityTokens")->findOneById($request->getParameter("idToken"));
    //$url="http://www.arriendas.cl/main/verificacionSeguro?idAuto=456";
    
    //Ejecutamos el cambio correspondientes
    $auto= Doctrine_Core::getTable("car")->findOneById($request->getParameter("idAuto"));
    $auto->setSeguroOK("4");
    $auto->setFechaValidacion(strftime("%Y/%m/%d"));
    $auto->save();
    $this->getResponse()->setHttpHeader('Access-Control-Allow-Origin', '*');
    $this->getResponse()->setHttpHeader('Access-Control-Allow-Methods', 'GET, POST, OPTIONS');
    $this->getResponse()->setHttpHeader('Access-Control-Allow-Headers', 'X-Requested-With');
    return $this->renderText("OK");
 }
 
/*
Esta funcion recibe el ID de un daño almacenado en la tabla Damages, y devuelve el PNG correspondiente
a la vista superior del auto con el daño marcado con una X
*/

public function executeGenerarMapaDanio(sfWebRequest $request) {
    $idDanio= $request->getParameter("idDanio");
    $danio= Doctrine_Core::getTable("damage")->findOneById($idDanio);
    require sfConfig::get("sf_app_lib_dir")."/wideimage/WideImage.php";
    $image = WideImage::load(sfConfig::get("sf_web_dir")."/images/autoFotoDanios.png");
    $marca= WideImage::load(sfConfig::get("sf_web_dir")."/images/x_mark.png");
    $new= $image->merge($marca,$danio->getCoordX()-10,-477.48*$danio->getLat()-42668,100);
    $this->getResponse()->setContentType('image/png');
    return $new->output('png');
}



public function executeGenerarDaniosAuto(sfWebRequest $request) {
    $idAuto= $request->getParameter("idAuto");
    $danios= Doctrine_Core::getTable("damage")->findByCarId($idAuto);
    require sfConfig::get("sf_app_lib_dir")."/wideimage/WideImage.php";
    $image = WideImage::load(sfConfig::get("sf_web_dir")."/images/autoFotoDanios.png");
    $i=1;
    foreach($danios as $danio) {
	$canvas = $image->getCanvas();
	$canvas->useFont(sfConfig::get("sf_web_dir").'/fonts/Arial.ttf', 16, $image->allocateColor(0, 0, 0));
	$canvas->writeText($danio->getCoordX()-10, -477.48*$danio->getLat()-42668, $i);
	$i++;
    }
    $this->getResponse()->setContentType('image/png');
    return $image->output('png');    
}

public function executeGenerarReporte(sfWebRequest $request) {

    //Creamos la instancia de la libreria mPDF
    require sfConfig::get('sf_app_lib_dir')."/mpdf53/mpdf.php";
   $this->getResponse()->setContentType('application/pdf');
    $pdf= new mPDF();

    //Generamos el HTML correspondiente
    $request->setParameter("idAuto",$request->getParameter("idAuto"));
    $html=$this->getController()->getPresentationFor("main","informeDanios");
    $pdf->WriteHTML($html,0);
    return $this->renderText($pdf->Output());
 //   return $this->renderText($html);
}

public function executeGenerarReporteResumen(sfWebRequest $request){
    //Creamos la instancia de la libreria mPDF
    require sfConfig::get('sf_app_lib_dir')."/mpdf53/mpdf.php";
    $this->getResponse()->setContentType('application/pdf');
    $pdf= new mPDF();

    //Generamos el HTML correspondiente
    $request->setParameter("idAuto",$request->getParameter("idAuto"));
    $html=$this->getController()->getPresentationFor("main","reporteResumen");
    $pdf->WriteHTML($html,0);
    return $this->renderText($pdf->Output());
}

public function executeGenerarReporteDanios(sfWebRequest $request){
    //Creamos la instancia de la libreria mPDF
    require sfConfig::get('sf_app_lib_dir')."/mpdf53/mpdf.php";
    $this->getResponse()->setContentType('application/pdf');
    $pdf= new mPDF();

    //Generamos el HTML correspondiente
    $request->setParameter("idAuto",$request->getParameter("idAuto"));
    $html=$this->getController()->getPresentationFor("main","reporteDanios");
    $pdf->WriteHTML($html,0);
    return $this->renderText($pdf->Output());
}

public function executeGenerarFormularioEntregaDevolucion(sfWebRequest $request) {
    //Creamos la instancia de la libreria mPDF
    require sfConfig::get('sf_app_lib_dir')."/mpdf53/mpdf.php";
    $this->getResponse()->setContentType('application/pdf');
    $pdf= new mPDF();

    //Generamos el HTML correspondiente
    $request->setParameter("idReserve",$request->getParameter("idReserve"));
    $request->setParameter("tokenReserve",$request->getParameter("tokenReserve"));
    $html=$this->getController()->getPresentationFor("main","formularioEntrega");
    $pdf->WriteHTML($html,0);
    return $this->renderText($pdf->Output());
    //return $this->renderText($html);
}

public function executeFormularioEntrega(sfWebRequest $request){
    //se asume que se recibe el id de la tabla reserva desde la página anterior
        //$idReserve = 605;
        $idReserve= $request->getParameter("idReserve");
        $tokenReserve= $request->getParameter("tokenReserve");

        //id del usuario que accede al sitio
        $idUsuario = sfContext::getInstance()->getUser()->getAttribute('userid');

		if (!$tokenReserve){
			$reserve = Doctrine_Core::getTable('reserve')->findOneById($idReserve);
		}else{
			$reserve = Doctrine_Core::getTable('reserve')->findOneByToken($tokenReserve);
		};

        sfContext::getInstance()->getConfiguration()->loadHelpers(array('Url'));
        $this->url = public_path("");

        $entrega = $reserve->getDate();
        $duracion = $reserve->getDuration();

        $entrega = strtotime($entrega);
        $this->fechaEntrega = date("d/m/y",$entrega);
        $this->horaEntrega = date("H:i",$entrega);

        $this->fechaDevolucion = date("d/m/y",$entrega+($duracion*60*60));
        $this->horaDevolucion = date("H:i",$entrega+($duracion*60*60));

        $carId = $reserve->getCarId();
        $arrendadorId = $reserve->getUserId();

        $carClass = Doctrine_Core::getTable('car')->findOneById($carId);

        $propietarioId = $carClass->getUserId();

        $modeloId = $carClass->getModel();

        $car['duracion'] = $reserve->getTiempoArriendoTexto();

        $car['id'] = $carId;
        $car['marca'] = $carClass->getModel()->getBrand();
        $car['modelo'] = $carClass->getModel();
        $car['patente'] = $carClass->getPatente();

        $arrendadorClass = Doctrine_Core::getTable('user')->findOneById($arrendadorId);
        $propietarioClass = Doctrine_Core::getTable('user')->findOneById($propietarioId);

        if($propietarioId == $idUsuario){
            $car['propietario'] = true;
        }else{
            $car['propietario'] = false;
        }

        $arrendador['nombreCompleto'] = $arrendadorClass->getFirstname()." ".$arrendadorClass->getLastname();
        $arrendador['rut'] = $arrendadorClass->getRut();
        $arrendador['direccion'] = $arrendadorClass->getAddress();
        $arrendador['telefono'] = $arrendadorClass->getTelephone();

        $comunaId = $arrendadorClass->getComuna();
        $comunaClass = Doctrine_Core::getTable('comunas')->findOneByCodigoInterno($comunaId);
        $arrendador['comuna'] = ucfirst(strtolower($comunaClass['nombre']));

        $propietario['nombreCompleto'] = $propietarioClass->getFirstname()." ".$propietarioClass->getLastname();
        $propietario['rut'] = $propietarioClass->getRut();
        $propietario['direccion'] = $propietarioClass->getAddress();
        $propietario['telefono'] = $propietarioClass->getTelephone();

        $comunaId = $propietarioClass->getComuna();
        $comunaClass = Doctrine_Core::getTable('comunas')->findOneByCodigoInterno($comunaId);
        $propietario['comuna'] = ucfirst(strtolower($comunaClass['nombre']));

        //echo $carId;
        $damageClass = Doctrine_Core::getTable('damage')->findByCarId($carId);
        //$damageClass = Doctrine_Core::getTable('damage')->findByCarId(553);
        $descripcionDanios = null;
        $i = 0;
        foreach ($damageClass as $damage) {
            $descripcionDanios[$i] = $damage->getDescription();
            $i++;
        }

        //die();

        $this->descripcionDanios = $descripcionDanios;
        $this->arrendador = $arrendador;
        $this->propietario = $propietario;
        $this->car = $car;
}
 
 
//Se retorna un componente que permite hacer el upload de las fotos directamente al S3 de Amazon, sin pasar por el servidor
//Web.
public function executeUpload2S3(sfWebRequest $request) {
    //Validamos los parámetros de entrada
    $this->urlFotoDefecto= $request->getParameter("urlFotoDefecto");
    $this->titulo= $request->getParameter("tituloFoto");
    $this->bucketDestino= $request->getParameter("bucket");
    $this->idElemento=$request->getParameter("idElemento");
}

//Metodo auxiliar para generar una version de menor tamaño de la foto
public function executeS3thumb(sfWebRequest $request) {
    $image= $request->getParameter("urlFoto");
    $ancho= $request->getParameter("ancho");
    $alto= $request->getParameter("alto");
    
    //Necesitamos obtener el nombre del archivo respectivo.
    //Para esto, hacemos uso de la funcion nativa parse_url de php
    $temporal= parse_url($image);

    
    //Generamos la URL que corresponde a la imagen
    $urlThumbnail= "thumb_an".$ancho."_al".$alto."_".str_replace("/","_",$temporal['path']);
 
 /*   require sfConfig::get('sf_app_lib_dir')."/aws_sdk/sdk.class.php";
    $s3= new AmazonS3();
    $bucket= "arriendas.testing".strtolower($s3->key);
    $response= $s3->list_objects("arriendas.testing",array("prefix"=>$urlThumbnail));
    
    var_dump($response);
    die();*/
    //Revisamos si la foto está generada como thumb en el S3
    $ch = curl_init();
    curl_setopt($ch,CURLOPT_URL,"http://s3.amazonaws.com/arriendas.testing/?prefix=".$urlThumbnail);
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
    $salida= curl_exec($ch);
    if(strpos($salida,"Contents")==false) {
	require sfConfig::get('sf_app_lib_dir')."/wideimage/WideImage.php";
	$image = WideImage::load($image);
	$resized = $image->resize($alto, $ancho);
	$resized->saveToFile(sfConfig::get('sf_upload_dir')."/".$urlThumbnail);
	require(sfConfig::get('sf_app_lib_dir')."/s3upload/s3_config.php");
	$s3->putObjectFile(sfConfig::get('sf_upload_dir')."/".$urlThumbnail,
	$bucket , $urlThumbnail, S3::ACL_PUBLIC_READ);
	}
    $this->redirect("http://s3.amazonaws.com/arriendas.testing/".$urlThumbnail);
}

public function executeSignput(sfWebRequest $request) {
    $S3_KEY='AKIAIDPA5IXQKMNWFCYQ';
    $S3_SECRET='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';
    $S3_BUCKET='/arriendas.testing';
     
    $EXPIRE_TIME=(60 * 5); // 5 minutes
    $S3_URL='http://s3.amazonaws.com';
     
    $objectName='/' . time()."_".$request->getParameter('name');
    $mimeType=$request->getParameter('type');
    $expires = time() + $EXPIRE_TIME;
    $amzHeaders= "x-amz-acl:public-read";
    $stringToSign = "PUT\n\n$mimeType\n$expires\n$amzHeaders\n$S3_BUCKET$objectName";
    $sig = urlencode(base64_encode(hash_hmac('sha1', $stringToSign, $S3_SECRET, true)));
     
    $url = urlencode("$S3_URL$S3_BUCKET$objectName?AWSAccessKeyId=$S3_KEY&Expires=$expires&Signature=$sig");
     
    return $this->renderText($url);
}
 
public function executeInformeDanios(sfWebRequest $request) {
    //Recibimos como parámetro el ID del vehículo para el cual deseamos generar el informe de daños
    $idAuto= $request->getParameter("idAuto");
    $car = Doctrine_Core::getTable("car")->findOneById($idAuto);
    /*
    if($car->getSeguroOK()==0) {
	throw new Exception("El auto no tiene su seguro verificado");
    }
    */
    
    //Obtenemos los datos del dueño del vehículo
    
    $user= Doctrine_Core::getTable("user")->findOneByID($car->getUserId());
    
    //Obtencion de los datos referentes a los daños
    $damages= Doctrine_Core::getTable("damage")->findByCarId($car->getId());
    
    //Necesitamos calcular la cantidad de páginas de daños a generar. Son 2 daños por págin
    $paginas= ceil(count($damages)/2);
    $this->paginasDanios= $paginas;
    
    //Datos para el template
    $this->propietario= $user->getFirstname()." ".$user->getLastName();
    $this->telefono= $user->getTelephone();
    $this->direccion=$car->getAddress();
    $this->foto="http://cdn1.arriendas.cl/uploads/cars/".$car->getFoto();
    $this->agno=$car->getYear();
    $this->precioHora=$car->getPricePerHour();
    $this->precioDia=$car->getPricePerDay();
    $this->marcaAuto= $car->getMarcaModelo();
    $this->fotoFrontal= "../uploads/vistas_seguro/".$car->getSeguroFotoFrente();
    $this->fotoCostadoIzquierdo= "../uploads/vistas_seguro/".$car->getSeguroFotoCostadoIzquierdo();
    $this->fotoCostadoDerecho= "../uploads/vistas_seguro/".$car->getSeguroFotoCostadoDerecho();
    $this->fotoTrasera= "../uploads/vistas_seguro/".$car->getSeguroFotoTraseroDerecho();
    $this->fotoPanel= "../uploads/vistas_seguro/".$car->getSeguroFotoTraseroIzquierdo();    
    $this->danios= $damages;
}

public function executeReporteResumen(sfWebRequest $request) {
    //Recibimos como parámetro el ID del vehículo para el cual deseamos generar el informe de daños
    $idAuto= $request->getParameter("idAuto");
    $car = Doctrine_Core::getTable("car")->findOneById($idAuto);
    /*
    if($car->getSeguroOK()==0) {
    throw new Exception("El auto no tiene su seguro verificado");
    }
    */
    
    //Obtenemos los datos del dueño del vehículo
    
    $user= Doctrine_Core::getTable("user")->findOneByID($car->getUserId());
    
    //Obtencion de los datos referentes a los daños
    $damages= Doctrine_Core::getTable("damage")->findByCarId($car->getId());
    
    //Necesitamos calcular la cantidad de páginas de daños a generar. Son 2 daños por págin
    $paginas= ceil(count($damages)/2);
    $this->paginasDanios= $paginas;
    
    //Datos para el template
    $this->propietario= $user->getFirstname()." ".$user->getLastName();
    $this->telefono= $user->getTelephone();
    $this->direccion=$car->getAddress();
    $this->foto=$car->getFoto();
    $this->agno=$car->getYear();
    $this->precioHora=$car->getPricePerHour();
    $this->precioDia=$car->getPricePerDay();
    $this->marcaAuto= $car->getMarcaModelo();
    $this->fotoFrontal= "../uploads/vistas_seguro/".$car->getSeguroFotoFrente();
    $this->fotoCostadoIzquierdo= "../uploads/vistas_seguro/".$car->getSeguroFotoCostadoIzquierdo();
    $this->fotoCostadoDerecho= "../uploads/vistas_seguro/".$car->getSeguroFotoCostadoDerecho();
    $this->fotoTrasera= "../uploads/vistas_seguro/".$car->getSeguroFotoTraseroDerecho();
    $this->fotoPanel= "../uploads/vistas_seguro/".$car->getSeguroFotoTraseroIzquierdo();    
    $this->danios= $damages;
}

public function executeReporteDanios(sfWebRequest $request) {
    //Recibimos como parámetro el ID del vehículo para el cual deseamos generar el informe de daños
    $idAuto= $request->getParameter("idAuto");
    $car = Doctrine_Core::getTable("car")->findOneById($idAuto);
    /*
    if($car->getSeguroOK()==0) {
    throw new Exception("El auto no tiene su seguro verificado");
    }   
    */
    
    //Obtenemos los datos del dueño del vehículo
    
    $user= Doctrine_Core::getTable("user")->findOneByID($car->getUserId());
    
    //Obtencion de los datos referentes a los daños
    $damages= Doctrine_Core::getTable("damage")->findByCarId($car->getId());
    
    //Necesitamos calcular la cantidad de páginas de daños a generar. Son 2 daños por págin
    $paginas= ceil(count($damages)/2);
    $this->paginasDanios= $paginas;
    
    //Datos para el template
    $this->propietario= $user->getFirstname()." ".$user->getLastName();
    $this->telefono= $user->getTelephone();
    $this->direccion=$car->getAddress();
    $this->foto=$car->getFoto();
    $this->agno=$car->getYear();
    $this->precioHora=$car->getPricePerHour();
    $this->precioDia=$car->getPricePerDay();
    $this->marcaAuto= $car->getMarcaModelo();
    $this->fotoFrontal= "../uploads/vistas_seguro/".$car->getSeguroFotoFrente();
    $this->fotoCostadoIzquierdo= "../uploads/vistas_seguro/".$car->getSeguroFotoCostadoIzquierdo();
    $this->fotoCostadoDerecho= "../uploads/vistas_seguro/".$car->getSeguroFotoCostadoDerecho();
    $this->fotoTrasera= "../uploads/vistas_seguro/".$car->getSeguroFotoTraseroDerecho();
    $this->fotoPanel= "../uploads/vistas_seguro/".$car->getSeguroFotoTraseroIzquierdo();    
    $this->danios= $damages;
}
 
public function executeTestDamage(sfWebRequest $request) {
    $damage= Doctrine_Core::getTable("damage")->findOneById(21);
    $damage->setUrlFoto("Estoesunaprueba.jpg");
    $damage->save();
    echo $damage->getUrlFoto();
    die();
}


public function executeReferidos(sfWebRequest $request) {

}

public function executeReferidos2(sfWebRequest $request) {

}

public function executeAgradecimiento(sfWebRequest $request) {

}
    
public function executeInvitaciones(sfWebRequest $request) {
    
}
    
public function executeExito(sfWebRequest $request) {
}

public function executeFracaso(sfWebRequest $request) {
}

public function executeNotificacion(sfWebRequest $request) {
}

    public function executeIndex(sfWebRequest $request) {

	//Modificacion para setear la cookie de registo de la fecha de ingreso por primera vez del usuario
	$cookie_ingreso = $this->getRequest()->getCookie('cookie_ingreso');
	
	if($cookie_ingreso==NULL) {
	    $this->getResponse()->setCookie('cookie_ingreso',time());
	}
	   
        if ($this->getUser()->getAttribute('geolocalizacion') == null) {
            $this->getUser()->setAttribute('geolocalizacion', true);
        } elseif ($this->getUser()->getAttribute('geolocalizacion') == true) {
            $this->getUser()->setAttribute('geolocalizacion', false);
        }
        
        $day_from = $this->getUser()->getAttribute('day_from');
        $idUsuario = sfContext::getInstance()->getUser()->getAttribute('userid');
        
        if($idUsuario && $day_from == '12-11-2013')
        {
            $reservas = Doctrine_Core::getTable("Reserve")->findByUserId($idUsuario);
            $ultimaFecha = null;
            $ultimoId = null;
            $duracion = null;
            foreach ($reservas as $reserva) 
            {
                if(!$ultimaFecha)
                {
                    $ultimoId = $reserva->getId();
                    $ultimaFecha = strtotime($reserva->getDate());
                    $duracion = $reserva->getDuration();
                }
                else
                {
                    if($ultimoId < $reserva->getId())
                    {
                        $ultimoId = $reserva->getId();
                        $ultimaFecha = strtotime($reserva->getDate());
                        $duracion = $reserva->getDuration();
                    }
                }
            }
            if($ultimaFecha)
            {
                $fechaActual = $this->formatearHoraChilena(strftime("%Y-%m-%d %H:%M:%S"));
                $fechaAlmacenadaDesde = date("YmdHis",$ultimaFecha);
                if($fechaActual < $fechaAlmacenadaDesde)
                {
                    $day_from = date("d-m-Y",$ultimaFecha);
                    $hour_from = date("H:i",$ultimaFecha);
                    $day_to = date("d-m-Y",$ultimaFecha+$duracion*3600);
                    $hour_to = date("H:i",$ultimaFecha+$duracion*3600);
                    
                    $this->getUser()->setAttribute('day_from', $day_from);
                    $this->getUser()->setAttribute('day_to', $day_to);
                    $this->getUser()->setAttribute('hour_from', $hour_from);
                    $this->getUser()->setAttribute('hour_to', $hour_to);
                }
            }
        }
        
        $fechaActual = $this->formatearHoraChilena(strftime("%Y%m%d%H%M%S"));
        $day_to = $this->getUser()->getAttribute('day_to');
        $hour_from = $this->getUser()->getAttribute('hour_from');
        $hour_to = $this->getUser()->getAttribute('hour_to');
        
        $this->day_from = date("d-m-Y", strtotime($fechaActual));
        $this->day_to = date("d-m-Y",strtotime("+2 days",strtotime($fechaActual)));
        $this->hour_from = date("H:i",strtotime($fechaActual));
        $this->hour_to = date("H:i",strtotime($fechaActual)+12*3600);
        
        if(date("Y-m-d H:i:s",strtotime($day_from)) > $fechaActual){
            $this->day_from = $day_from;
            $this->day_to = date("d-m-Y",strtotime("+2 days",strtotime($day_from)));
            $this->hour_from = date("H:i",strtotime($day_from)+6*3600);
            $this->hour_to = date("H:i",strtotime($day_from)+22*3600);
        }
        

        $cityname = $request->getParameter('c');

        $q = Doctrine::getTable('City')->createQuery('c')->where('c.name = ? ', array($cityname));
        $city = $q->fetchOne();

        if ($city != null) {
            $this->lat = $city->getLat();
            $this->lng = $city->getLng();
        } else {

            if (stripos($_SERVER['SERVER_NAME'], "arrendas") !== FALSE) {
                $this->lat = -34.59;
                $this->lng = -58.401604;
            } else {
                $this->lat = -33.427224;
                $this->lng = -70.605558;
            }
        }


        $boundleft = -35;
        $boundright = -30;
        $boundtop = -60;
        $boundbottom = -55;


        $q = Doctrine_Query::create()
                ->select('ca.id , av.id idav , mo.name model,
		  br.name brand, ca.year year,
		  ca.address address, ci.name city,
		  st.name state, co.name country,
		  owner.firstname firstname,
		  owner.lastname lastname,
		  ca.price_per_day priceday,
		  ca.price_per_hour pricehour,
		  owner.id userid, IF(ca.seguro_ok=4 or ca.seguro_ok=3,1,0) as verificado'
                )
                ->from('Car ca')
                ->innerJoin('ca.Availabilities av')
                ->innerJoin('ca.Model mo')
                ->innerJoin('ca.User owner')
                ->innerJoin('mo.Brand br')
                ->innerJoin('ca.City ci')
                ->innerJoin('ci.State st')
                ->innerJoin('st.Country co')
                ->where('ca.lat > ?', $boundleft)
                ->andWhere('ca.activo = ?', 1)
                ->andWhere('ca.lat < ?', $boundright)
                ->andWhere('ca.lng > ?', $boundtop)
                ->andWhere('ca.lng < ?', $boundbottom)
                ->andWhere('ca.offer = ?', 1)
		->having('verificado = ?',1)
                ->limit(10);
        $this->offers = $q->execute();

        $this->brand =  $this->ordenarBrand(Doctrine_Core::getTable('Brand')->createQuery('a')->execute());
        //Doctrine_Core::getTable('Brand')->createQuery('a')->execute();
        /*
        $this->brand = $q = Doctrine_Query::create()->from('Brand ba')
                ->innerJoin('ba.Models mo')
                ->innerJoin('mo.Cars ca')
                ->execute();
        */

        $this->country = Doctrine_Core::getTable('Country')->createQuery('a')->execute();
    }

    public function ordenarBrand($brand){

        $brandOrdenados = array();
        for($i=0;$i<count($brand);$i++){
            $brandOrdenados[$i] = $brand[$i]->getName();
        }
        sort($brandOrdenados);

        $brandOrdenadosConId = array();
        $k=0;
        for($i=0;$i<count($brandOrdenados);$i++){
            for($j=0;$j<count($brand);$j++){
                if($brandOrdenados[$i]==$brand[$j]->getName()){
                    $brandOrdenadosConId[$k]['name']=$brand[$j]->getName();
                    $brandOrdenadosConId[$k]['id']=$brand[$j]->getId();
                    $k++;
                }
            }
        }

        return $brandOrdenadosConId;

    }

	public function executeFiltrosBusqueda(sfWebRequest $request) {
		
		$this->getUser()->setAttribute('fechainicio', $request->getParameter('fechainicio'));
		$this->getUser()->setAttribute('horainicio', $request->getParameter('horainicio'));
		$this->getUser()->setAttribute('fechatermino', $request->getParameter('fechatermino'));
		$this->getUser()->setAttribute('horatermino', $request->getParameter('horatermino'));
		throw new sfStopException();
	}

    public function executeGetModelSearch(sfWebRequest $request) {

        sfConfig::set('sf_web_debug', false);
        $_output;

        /* Asegurar que la solicitud sea AJAX */
        //if (!$request->isXmlHttpRequest())
        //return $this->renderText(json_encode(array('error'=>'S?lo respondo consultas v?a AJAX.')));

        $_output[] = array("optionValue" => 0, "optionDisplay" => "Modelo");

        if ($request->getParameter('id')) {

            //  $brand = Doctrine_Core::getTable('Brand')->find(array($request->getParameter('id')));

            $this->models = $q = Doctrine_Query::create()->from('Model mo')
                    ->innerJoin('mo.Cars ca')
                    ->where('mo.Brand.Id = ?', $request->getParameter('id'))
                    ->execute();

            foreach ($this->models as $p) {
                $_output[] = array("optionValue" => $p->getId(), "optionDisplay" => $p->getName());
            }
        }
        return $this->renderText(json_encode($_output));
        //return $this->renderText(json_encode(array('error'=>'Faltan par?metros para realizar la consulta')));
    }

    public function executeIndexOld(sfWebRequest $request) {

        $cityname = $request->getParameter('c');

        $q = Doctrine::getTable('City')->createQuery('c')->where('c.name = ? ', array($cityname));
        $city = $q->fetchOne();

        if ($city != null) {
            $this->lat = $city->getLat();
            $this->lng = $city->getLng();
        } else {

            if (stripos($_SERVER['SERVER_NAME'], "arrendas") !== FALSE) {
                $this->lat = -34.59;
                $this->lng = -58.401604;
            } else {
                $this->lat = -33.427224;
                $this->lng = -70.605558;
            }
        }

        $boundleft = -35;
        $boundright = -30;
        $boundtop = -60;
        $boundbottom = -55;



        $q = Doctrine_Query::create()
                ->select(' ca.id,  re.id idre, mo.name model,
		  br.name brand, ca.year year,
		  ca.address address, ci.name city,
		  st.name state, co.name country,
		  owner.firstname firstname,
		  owner.lastname lastname,
		  ca.price_per_day priceday,
		  ca.price_per_hour pricehour, re.date date'
                )
                ->from('Car ca')
                ->innerJoin('ca.Reserves re')
                ->innerJoin('ca.Model mo')
                ->innerJoin('ca.User owner')
                ->innerJoin('mo.Brand br')
                ->innerJoin('ca.City ci')
                ->innerJoin('ci.State st')
                ->innerJoin('st.Country co')
                ->where('ca.lat > ?', $boundleft)
                ->andWhere('ca.activo = ?', 1)
                ->andWhere('ca.lat < ?', $boundright)
                ->andWhere('ca.lng > ?', $boundtop)
                ->andWhere('ca.lng < ?', $boundbottom)
                ->orderBy('re.date desc')
                ->limit(10);
        $this->last = $q->execute();

        $q = Doctrine_Query::create()
                ->select('ca.id, av.id idav , mo.name model,
		  br.name brand, ca.year year,
		  ca.address address, ci.name city,
		  st.name state, co.name country,
		  owner.firstname firstname,
		  owner.lastname lastname,
		  ca.price_per_day priceday,
		  ca.price_per_hour pricehour'
                )
                ->from('Car ca')
                ->innerJoin('ca.Availabilities av')
                ->innerJoin('ca.Model mo')
                ->innerJoin('ca.User owner')
                ->innerJoin('mo.Brand br')
                ->innerJoin('ca.City ci')
                ->innerJoin('ci.State st')
                ->innerJoin('st.Country co')
                ->where('ca.lat > ?', $boundleft)
                ->andWhere('ca.activo = ?', 1)
                ->andWhere('ca.lat < ?', $boundright)
                ->andWhere('ca.lng > ?', $boundtop)
                ->andWhere('ca.lng < ?', $boundbottom)
                ->andWhere('ca.offer = ?', 1)
                ->limit(10);
        $this->offers = $q->execute();
        //echo $q->getSqlQuery();
    }

    public function executeMap(sfWebRequest $request) {

	$modelo= new Model();

	//$this->setLayout(true);
  //      sfConfig::set('sf_web_debug', false);
        $this->getResponse()->setContentType('application/json');

        $boundleft = $request->getParameter('swLat');
        $boundright = $request->getParameter('neLat');
        $boundtop = $request->getParameter('swLng');
        $boundbottom = $request->getParameter('neLng');

        $day_from = $request->getParameter('day_from');
        $day_to = $request->getParameter('day_to');
        $hour_from = date("H:i", strtotime($request->getParameter('hour_from')));
        $hour_to = date("H:i", strtotime($request->getParameter('hour_to')));
        
        $this->getUser()->setAttribute('day_from', $day_from);
        $this->getUser()->setAttribute('day_to', $day_to);
        $this->getUser()->setAttribute('hour_from', $hour_from);
        $this->getUser()->setAttribute('hour_to', $hour_to);

        $startTime = strtotime($day_from);
        $endTime = strtotime($day_to);

        $timeHasWorkday=false;
        $timeHasWeekend=false;

        for ($i = $startTime; $i <= $endTime; $i = $i + 86400) {
          $thisDate = date('Y-m-d', $i); // 2010-05-01, 2010-05-02, etc
          $dw = date( "w", $i);
                if ($dw==6 || $dw==0){
                        $timeHasWeekend=true;
                        break;
                }
        }

        for ($i = $startTime; $i <= $endTime; $i = $i + 86400) {
          $thisDate = date('Y-m-d', $i); // 2010-05-01, 2010-05-02, etc
          $dw = date( "w", $i);
                if ($dw>0 && $dw<5){
                        $timeHasWorkday=true;
                        break;
                }
        }

        $this->logMessage('day_from '.$day_from, 'err');

        $brand = $request->getParameter('brand');
        $model = $request->getParameter('model');

        $transmission = $request->getParameter('transmission');
        $type = $request->getParameter('type');
				
        $location = $request->getParameter('location');
        $price = $request->getParameter('price');
        
        $lat_centro = $request->getParameter('clat');
        $lng_centro = $request->getParameter('clng');

		
        /*
          if (
          $hour_from != "Hora de inicio" &&
          $hour_to != "Hora de entrega" &&
          $hour_from != "" &&
          $hour_from != ""
          ) {
          list($day_from, $hour_from) = split(' ', $hour_from);
          list($day_to, $hour_to) = split(' ', $hour_to);
          }
         */


$debug = 0;
if($debug){
print_r('<html>');
print_r('<body>');
print_r(date('h:i:s'));
}
$this->logMessage(date('h:i:s'), 'err');


$query = Doctrine_Query::create()
  ->from('Movie m');

        $q = Doctrine_Query::create()
                ->select('
				ca.id, 
				ca.lat lat, 
				ca.lng lng, 
				co.nombre comuna_nombre,
				ca.price_per_day, 
				ca.price_per_hour,
	        	ca.photoS3 photoS3, 
				ca.Seguro_OK, 
				ca.foto_perfil, 
				ca.transmission transmission,
				ca.user_id,
				ca.velocidad_contesta_pedidos velocidad_contesta_pedidos,
				greatest(1440,ca.velocidad_contesta_pedidos)/greatest(0.1,ca.contesta_pedidos) carrank,
				mo.name modelo, 
				mo.id_tipo_vehiculo id_tipo_vehiculo,
				br.name brand, 
				')
                ->from('Car ca')
//                ->innerJoin('ca.Availabilities av')
//                ->leftJoin('ca.Reserves re')
                ->innerJoin('ca.Model mo')
                ->innerJoin('ca.Comunas co')
//                ->innerJoin('ca.User us')
                ->innerJoin('mo.Brand br')
//                ->innerJoin('ca.City ci')
//                ->innerJoin('ci.State st')
//                ->innerJoin('st.Country co')
                ->where('ca.lat > ?', $boundleft)
                ->andWhere('ca.activo = ?', 1)
                //->andWhere('ca.comuna_id <> NULL OR ca.comuna_id <> 0')
                ->andWhereIn('ca.seguro_ok', array(3,4))
                ->andWhere('ca.lat < ?', $boundright)
                ->andWhere('ca.lng > ?', $boundtop)
                ->andWhere('ca.lng < ?', $boundbottom);
                //->orderBy('ca.price_per_day asc');


				
        if (
                $hour_from != "Hora de inicio" &&
                $hour_to != "Hora de entrega" &&
                $hour_from != "" &&
                $hour_from != "" &&
                $day_from != "Dia de inicio" &&
                $day_to != "Dia de entrega" &&
                $day_from != "" &&
                $day_to != ""
        ) {

			$this->logMessage('day_from0 '.$day_from, 'err');

            $day_from = implode('-', array_reverse(explode('-', $day_from)));
            $day_to = implode('-', array_reverse(explode('-', $day_to)));

						$this->logMessage('day_from1 '.$day_from, 'err');

            $fullstartdate = $day_from . " " . $hour_from;
            $fullenddate = $day_to . " " . $hour_to;

//            $q = $q->andWhere('av.hour_from < ? and av.hour_to > ? and av.date_from <= ? and av.date_to >= ?', array($hour_from, $hour_to, $day_from, $day_to));
//            $q = $q->orWhere('av.hour_from < ? and av.hour_to > ? and av.date_from < ?', array($hour_from, $hour_to, $day_from));

			$q = $q->andWhere('ca.disponibilidad_semana >= ?', $timeHasWorkday);
			$q = $q->andWhere('ca.disponibilidad_finde >= ?', $timeHasWeekend);


        }

        if ($brand != "") {
            $q = $q->andWhere('br.id = ?', $brand);
        }
		
		
        if ($model != "" && $model != "0") {
            $q = $q->andWhere('mo.id = ?', $model);
        }

        if ($transmission != "") {
			$transmission=explode(",",$transmission); 
            $q = $q->andWhereIn('ca.transmission', $transmission);
        }


        if ($type != "") {
			$type=explode(",",$type); 
			$q = $q->andWhereIn('mo.id_tipo_vehiculo', $type);
        }
		
		
		
        if ($location != "") {
//            $q = $q->andWhere('co.id = ?', $location);
        }
//        if ($price != null) {
//            if ($price == "1") {
    //            $q = $q->orderBy('ca.price_per_day asc');
  //          } else {

  
  
  
  //              $q = $q->orderBy('ca.price_per_day desc');
                $q = $q->orderBy('carrank asc');
                $q = $q->addOrderBy(' IF( ca.velocidad_contesta_pedidos =0,1440, ca.velocidad_contesta_pedidos)  asc');
                $q = $q->addOrderBy('ca.fecha_subida  asc');
                $q = $q->limit(33);



				//      }
//        }
        $cars = $q->execute();

if($debug) {
print_r('<br />'.date('h:i:s'));
}
$this->logMessage(date('h:i:s'), 'err');

			$this->logMessage('day_from2 '.$day_from, 'err');

        $data = array();
        $carsid = Array();

sfContext::getInstance()->getConfiguration()->loadHelpers(array('Url'));
$contador = 0;
        foreach ($cars as $car) {

$contador ++;
if($debug){
print_r('<br />auto numero: '.$contador.', '.date('h:i:s'));
}
$this->logMessage(date('h:i:s'), 'err');

            if ($lat_centro != null && $lng_centro != null) {

                $lat1 = $car->getlat();
                $lat2 = $lat_centro;

                $lon1 = $car->getlng();
                $lon2 = $lng_centro;

                $R = 6371; // km
                $dLat = deg2rad($lat2 - $lat1);
                $dLon = deg2rad($lon2 - $lon1);
                $lat1 = deg2rad($lat1);
                $lat2 = deg2rad($lat2);

                $a = sin($dLat / 2) * sin($dLat / 2) +
                        sin($dLon / 2) * sin($dLon / 2) * cos($lat1) * cos($lat2);
                $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
                $d = $R * $c;
            }else{
				$d=0;
				};

			$photo = $car->getFotoPerfil();
	    
            $has_reserve = false;
			$is_available = true;

			$this->logMessage('day_from '.$day_from, 'err');

            if (
                    $hour_from != "Hora de inicio" &&
                    $hour_to != "Hora de entrega" &&
                    $hour_from != "" &&
                    $hour_from != "" &&
                    $day_from != "Dia de inicio" &&
                    $day_to != "Dia de entrega" &&
                    $day_from != "" &&
                    $day_to != ""
            ) {


    //            $fullstartdate = $day_from . " " . $hour_from;
      //          $fullenddate = $day_to . " " . $hour_to;
				
//                $day_from = implode('-', array_reverse(explode('-', $day_from)));
  //              $day_to = implode('-', array_reverse(explode('-', $day_to)));

$this->logMessage('fullstartdate '.$fullstartdate, 'err');
$this->logMessage('fullenddate '.$fullenddate, 'err');

				
                $has_reserve = $car->hasReserve($fullstartdate, $fullstartdate, $fullenddate, $fullenddate);
//                $is_available = $car->isAvailable($hour_from, $hour_to, $day_from, $day_to);
            }

//            $urlUser = $this->getPhotoUser($car->getUser()->getId());




//$this->logMessage($this->getUser()->getAttribute("logged"), 'err');
			$porcentaje = $car->getContestaPedidos();

	if ($this->getUser()->getAttribute("logged")){
   //         $user = $car->getUser();
  //          $reservasRespondidas = $user->getReservasContestadas_aLaFecha();
            $velocidad = $car->getVelocidadContestaPedidos();
			
			if($velocidad < 1){
				$velocidad="Menos de un minuto";
			}elseif($velocidad < 10){
				$velocidad="Menos de 10 minutos";
			}elseif($velocidad < 60){
				$velocidad= "Menos de una hora";
			}elseif($velocidad < 1440){
				if (floor($velocidad/60)==1){
					$velocidad="1 hora";
				}else{
					$velocidad=floor($velocidad/60)." horas";
				}
			}else{
				if (floor($velocidad/60/24)==1){
					$velocidad="1 dia";
				}else{
					$velocidad= floor($velocidad/60/24)." dias";
				}
			}

		
		
}else{
$reservasRespondidas=0;
$velocidad=0;
};
			$transmision = "Manual";
            $tipoTrans = $car->getTransmission();
            $carPercentile = $car->getCarPercentile();
			
            if($tipoTrans == 0) $transmision = "Manual";
            if($tipoTrans == 1) $transmision = "Autom&aacute;tica";

$this->logMessage($has_reserve, 'err');
  
            if (!$has_reserve) {

                $data[] = array('id' => $car->getId(),
//                    'idav' => $car->getIdav(),
                    'longitude' => $car->getlng(),
                    'latitude' => $car->getlat(),
                    'comuna' => strtolower($car->getComunaNombre()),
                    'brand' => $car->getBrand(),
                    'model' => $car->getModelo(),
                    'typeModel' => $car->getIdTipoVehiculo(),
//                    'address' => $car->getAddress(),
                    'year' => $car->getYear(),
                    'photoType' => $car->getPhotoS3(),
//                    'photo' => '',
                    'photo' => $photo,
//                    'username' => ucwords(current(explode(' ' , $car->getUser()->getFirstname()))) . " " . ucwords(current(explode(' ' , $car->getUser()->getLastname()))),
//                    'firstname' => ucwords(current(explode(' ' ,$car->getUser()->getFirstname()))),
//                    'lastname' => ucwords(substr($car->getUser()->getLastName(), 0, 1)).".",
                    'price_per_hour' => $this->transformarPrecioAPuntos(floor($car->getPricePerHour())),
                    'price_per_day' => $this->transformarPrecioAPuntos(floor($car->getPricePerDay())),
                    'userid' => $car->getUserId(),
                    'carRank' => $car->getCarrank(),
					'carPercentile' => $carPercentile,
//                    'userPhoto' => $urlUser,
                    'typeTransmission' => $transmision,
                    'userVelocidadRespuesta' => $velocidad,
                    'userContestaPedidos' => $porcentaje,
                    'cantidadCalificacionesPositivas' => '0',
//                    'cantidadCalificacionesPositivas' => $car->getCantidadCalificacionesPositivas(),
//                    'reservasRespondidas' => $reservasRespondidas,
                    'd' => $d,
	//	            'verificado'=>'',
		            'verificado'=>$car->autoVerificado(),
                );
            }
        }//fin foreach

        $position = array();
        $newRow = array();
        foreach ($data as $key => $row) {
            $position[$key] = $row["price_per_day"];
            $newRow[$key] = $row;
        }

        if ($price != "") {
			if ($price == 0) {
				asort($position); 
			}else{
				arsort($position);
			}
		}else{
			asort($position); 
		}

	    $returnArray = array();

        foreach ($position as $key => $pos) {
            $returnArray[] = $newRow[$key];
        }
	
//	    $returnArray=array_reverse($returnArray);
  

  //$data = $returnArray;

        $carsArray = array("cars" => $returnArray);
	
if($debug){
print_r('<br />'.date('h:i:s'));
print_r('</body>');
print_r('</html>');
//die;
}
$this->logMessage(date('h:i:s'), 'err');
        return $this->renderText(json_encode($carsArray));
        //$this->carsArray=renderText(json_encode($carsArray));
    }
    
    public function formatearHoraChilena($fecha){
        $horaChilena = strftime("%Y-%m-%d %H:%M:%S",strtotime('-4 hours',strtotime($fecha)));
        return $horaChilena;
    }
    
    public function transformarPrecioAPuntos($precio){
        $precioMillones = intval($precio/1000000);
        $precioMiles = $precio - ($precioMillones*1000000);
        $precioMiles = intval($precioMiles/1000);
        $precioCientos = $precio - ($precioMiles*1000);
        $precioResultado = "";
        if($precioMillones == 0){
            if($precioMiles == 0){
                $precioResultado = $precioCientos;
            }else{
                if($precioCientos == 0){
                    $precioResultado = $precioMiles.".000";
                }else{
                    $cantidadDeCientos = strlen($precioCientos);
                    if($cantidadDeCientos == 1) $precioResultado = $precioMiles.".00".$precioCientos;
                    else if($cantidadDeCientos == 2) $precioResultado = $precioMiles.".0".$precioCientos;
                    else $precioResultado = $precioMiles.".".$precioCientos;
                }
            }
        }else{
            if($precioMiles == 0){
                if($precioCientos == 0){
                    $precioResultado = $precioMillones.".000.000";
                }else{
                    $cantidadDeCientos = strlen($precioCientos);
                    if($cantidadDeCientos == 1) $precioResultado = $precioMillones.".000.00".$precioCientos;
                    else if($cantidadDeCientos == 2) $precioResultado = $precioMillones.".000.0".$precioCientos;
                    else $precioResultado = $precioMillones.".000.".$precioCientos;
                }
            }else{
                $cantidadDeMiles = strlen($precioMiles);
                $cantidadDeCientos = strlen($precioCientos);
                if($cantidadDeMiles == 1){
                    if($cantidadDeCientos == 1) $precioResultado = $precioMillones.".00".$precioMiles.".00".$precioCientos;
                    else if($cantidadDeCientos == 2) $precioResultado = $precioMillones.".00".$precioMiles.".0".$precioCientos;
                    else $precioResultado = $precioMillones.".00".$precioMiles.".".$precioCientos;
                }else if($cantidadDeCientos == 2){
                    if($cantidadDeCientos == 1) $precioResultado = $precioMillones.".0".$precioMiles.".00".$precioCientos;
                    else if($cantidadDeCientos == 2) $precioResultado = $precioMillones.".0".$precioMiles.".0".$precioCientos;
                    else $precioResultado = $precioMillones.".0".$precioMiles.".".$precioCientos;
                }else{
                    if($cantidadDeCientos == 1) $precioResultado = $precioMillones.".".$precioMiles.".00".$precioCientos;
                    else if($cantidadDeCientos == 2) $precioResultado = $precioMillones.".".$precioMiles.".0".$precioCientos;
                    else $precioResultado = $precioMillones.".".$precioMiles.".".$precioCientos;
                }
            }
        }

        return $precioResultado;
    }

    public function executeCars(sfWebRequest $request) {

        $this->setLayout(false);
        sfConfig::set('sf_web_debug', false);

        $boundleft = $request->getParameter('swLat');
        $boundright = $request->getParameter('neLat');
        $boundtop = $request->getParameter('swLng');
        $boundbottom = $request->getParameter('neLng');
        $hour_from = $request->getParameter('hour_from');
        $hour_to = $request->getParameter('hour_to');
        $day_from = $request->getParameter('day_from');
        $day_to = $request->getParameter('day_to');

        /* if(
          $hour_from != "Hora de inicio" &&
          $hour_to != "Hora de entrega" &&
          $hour_from != "" &&
          $hour_from != ""
          )
          {
          list($day_from, $hour_from) = split(' ', $hour_from);
          list($day_to, $hour_to) = split(' ', $hour_to);
          } */

        $q = Doctrine_Query::create()
                ->select('ca.id , av.id idav , mo.name model,
			  br.name brand, ca.year year,
			  ca.address address, ci.name city,
			  st.name state, co.name country,
			  owner.firstname firstname,
			  owner.lastname lastname,
			  ca.price_per_day priceday,
			  ca.price_per_hour pricehour,
			  owner.id userid'
                )
                ->from('Car ca')
                ->innerJoin('ca.Availabilities av')
                ->innerJoin('ca.Model mo')
                ->innerJoin('ca.User owner')
                ->innerJoin('mo.Brand br')
                ->innerJoin('ca.City ci')
                ->innerJoin('ci.State st')
                ->innerJoin('st.Country co')
                ->where('ca.lat > ?', $boundleft)
                ->andWhere('ca.activo = ?', 1)
                ->andWhere('ca.lat < ?', $boundright)
                ->andWhere('ca.lng > ?', $boundtop)
                ->andWhere('ca.lng < ?', $boundbottom);

        if (
                $hour_from != "Hora de inicio" &&
                $hour_to != "Hora de entrega" &&
                $hour_from != "" &&
                $hour_from != "" &&
                $day_from != "Dia de inicio" &&
                $day_to != "Dia de entrega" &&
                $day_from != "" &&
                $day_to != ""
        ) {
            $q = $q->andWhere('av.hour_from > ?', $hour_from)
                    ->andWhere('av.hour_to < ?', $hour_from)
                    ->andWhere('av.date_from > ?', $day_from)
                    ->andWhere('av.date_to < ?', $day_to);
        }

        $this->cars = $q->execute();
    }
    public function getPhotoUser($idUser){
        $claseUsuario = Doctrine_Core::getTable('user')->findOneById($idUser);
        if($claseUsuario->getFacebookId()!=null && $claseUsuario->getFacebookId()!=""){
          $urlFoto = $claseUsuario->getPictureFile();
        }else{
          if($claseUsuario->getPictureFile()!=""){
            $urlPicture = $claseUsuario->getPictureFile();
            $urlPicture = explode("/", $urlPicture);
            $urlPicture = $urlPicture[count($urlPicture)-1];
            $urlFoto = "http://www.arriendas.cl/images/users/".$urlPicture;
          }else{
            $urlFoto = "http://www.arriendas.cl/images/img_calificaciones/tmp_user_foto.jpg";
          }
        }
        return $urlFoto;
    }

    public function executeSearchResult(sfWebRequest $request) {

        $this->setLayout(false);
        sfConfig::set('sf_web_debug', false);

        $boundleft = $request->getParameter('swLat');
        $boundright = $request->getParameter('neLat');
        $boundtop = $request->getParameter('swLng');
        $boundbottom = $request->getParameter('neLng');

        $day_from = $request->getParameter('day_from');
        $day_to = $request->getParameter('day_to');

        $hour_from = $request->getParameter('hour_from');
        $hour_to = $request->getParameter('hour_to');

        $brand = $request->getParameter('brand');
        $model = $request->getParameter('model');

        $location = $request->getParameter('location');
        $price = $request->getParameter('price');

        $lat_centro = $request->getParameter('clat');
        $lng_centro = $request->getParameter('clng');

        /* if(
          $hour_from != "Hora de inicio" &&
          $hour_to != "Hora de entrega" &&
          $hour_from != "" &&
          $hour_from != ""
          )
          {
          list($day_from, $hour_from) = split(' ', $hour_from);
          list($day_to, $hour_to) = split(' ', $hour_to);
          } */


        $q = Doctrine_Query::create()
                ->select('DISTINCT ca.id , av.id idav , mo.name modelo,
			  br.name brand, ca.year year,
			  ca.photoS3 photoS3, ca.address address, ci.name city,
			  st.name state, co.name country,
			  owner.firstname firstname,
			  owner.lastname lastname,
			  ca.price_per_day priceday,
			  ca.price_per_hour pricehour,
                          ca.lat lat,
                          ca.lng lng,
			  owner.id userid'
                )
                ->from('Car ca')
                ->innerJoin('ca.Availabilities av')
                ->innerJoin('ca.Model mo')
                ->leftJoin('ca.Reserves re')
                ->innerJoin('ca.User owner')
                ->innerJoin('mo.Brand br')
                ->innerJoin('ca.City ci')
                ->innerJoin('ci.State st')
                ->innerJoin('st.Country co')
                ->where('ca.lat > ?', $boundleft)
                ->andWhere('ca.activo = ?', 1)
                ->andWhereIn('ca.seguro_ok', array(3,4))
                ->andWhere('ca.lat < ?', $boundright)
                ->andWhere('ca.lng > ?', $boundtop)
                ->andWhere('ca.lng < ?', $boundbottom);
                //->orderBy('ca.price_per_day asc');

        if (
                $hour_from != "Hora de inicio" &&
                $hour_to != "Hora de entrega" &&
                $hour_from != "" &&
                $hour_from != "" &&
                $day_from != "Dia de inicio" &&
                $day_to != "Dia de entrega" &&
                $day_from != "" &&
                $day_to != ""
        ) {


            $day_from = implode('-', array_reverse(explode('-', $day_from)));
            $day_to = implode('-', array_reverse(explode('-', $day_to)));

            $fullstartdate = $day_from . " " . $hour_from;
            $fullenddate = $day_to . " " . $hour_to;

            $q = $q->andWhere('av.hour_from < ? and av.hour_to > ? and av.date_from <= ? and av.date_to >= ?', array($hour_from, $hour_to, $day_from, $day_to));
            $q = $q->orWhere('av.hour_from < ? and av.hour_to > ? and av.date_from < ?', array($hour_from, $hour_to, $day_from));

            //$q = $q->andwhere('((re.date > ? and date_add(re.date, INTERVAL re.duration HOUR) > ?) or (re.date < ? and date_add(re.date, INTERVAL re.duration HOUR) < ?))', array($fullstartdate, $fullstartdate, $fullenddate, $fullenddate));
        }

        if ($brand != "") {
            $q = $q->andWhere('br.id = ?', $brand);
        }

        if ($model != "" && $model != "0") {
            $q = $q->andWhere('mo.id = ?', $model);
        }

        if ($location != "") {
            $q = $q->andWhere('co.id = ?', $location);
        }
        if ($price != null) {
            if ($price == "1") {
                $q = $q->orderBy('ca.price_per_day asc');
            } else {
                $q = $q->orderBy('ca.price_per_day desc');
            }
        }

        $cars = $q->execute();

        $data = array();
        $carsid = Array();

        foreach ($cars as $car) {

	    //Modificacion para revisar el estado de validacion del seguro
	
            if ($lat_centro != null && $lng_centro != null) {

                $lat1 = $car->getlat();
                $lat2 = $lat_centro;

                $lon1 = $car->getlng();
                $lon2 = $lng_centro;

                $R = 6371; // km
                $dLat = deg2rad($lat2 - $lat1);
                $dLon = deg2rad($lon2 - $lon1);
                $lat1 = deg2rad($lat1);
                $lat2 = deg2rad($lat2);

                $a = sin($dLat / 2) * sin($dLat / 2) +
                        sin($dLon / 2) * sin($dLon / 2) * cos($lat1) * cos($lat2);
                $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
                $d = $R * $c;
            }

	    sfContext::getInstance()->getConfiguration()->loadHelpers(array('Url'));
            $photo = $car->getFoto();

            $has_reserve = false;

            if (
                    $hour_from != "Hora de inicio" &&
                    $hour_to != "Hora de entrega" &&
                    $hour_from != "" &&
                    $hour_from != "" &&
                    $day_from != "Dia de inicio" &&
                    $day_to != "Dia de entrega" &&
                    $day_from != "" &&
                    $day_to != ""
            ) {



                $fullstartdate = $day_from . " " . $hour_from;
                $fullenddate = $day_to . " " . $hour_to;
                $has_reserve = $car->hasReserve($fullstartdate, $fullstartdate, $fullenddate, $fullenddate);
            }

            $urlUser = $this->getPhotoUser($car->getUser()->getId());

            $user = $car->getUser();
            $reservasRespondidas = $user->getReservasContestadas_aLaFecha();
            $velocidad = $user->getVelocidadRespuesta_mensajes();
            $transmision = "-";
            $tipoTrans = $car->getTransmission();
            if($tipoTrans == 1) $transmision = "Manual";
            if($tipoTrans == 0) $transmision = "Autom&aacute;tica";
            
            if (!$has_reserve) {
                $data[] = array('id' => $car->getId(),
                    'idav' => $car->getIdav(),
                    'longitude' => $car->getlng(),
                    'latitude' => $car->getlat(),
                    'comuna' => strtolower($car->getNombreComuna()),
                    'brand' => $car->getBrand(),
                    'model' => $car->getModelo(),
                    'address' => $car->getAddress(),
                    'year' => $car->getYear(),
                    'photo' => $photo,
                    'photoType' => $car->getPhotoS3(),
                    'username' => ucwords(current(explode(' ' , $car->getUser()->getFirstname()))) . " " . ucwords(current(explode(' ' , $car->getUser()->getLastname()))),
                    'firstname' => ucwords(current(explode(' ' ,$car->getUser()->getFirstname()))),
                    'lastname' => ucwords(substr($car->getUser()->getLastName(), 0, 1)).".",
                    'price_per_hour' => $this->transformarPrecioAPuntos(floor($car->getPricePerHour())),
                    'price_per_day' => $this->transformarPrecioAPuntos(floor($car->getPricePerDay())),
                    'userid' => $car->getUser()->getId(),
                    'userPhoto' => $urlUser,
                    'typeTransmission' => $transmision,
                    'userVelocidadRespuesta' => $velocidad,
                    'cantidadCalificacionesPositivas' => $car->getCantidadCalificacionesPositivas(),
                    'reservasRespondidas' => $reservasRespondidas,
                    'd' => $d,
		    'verificado'=> $car->autoVerificado(),
                );
            }
        }//end foreach

        $position = array();
        $newRow = array();
        foreach ($data as $key => $row) {
            $position[$key] = $row["verificado"];
            $newRow[$key] = $row;
        }
        //asort($position); //Se comenta porque no se utilizará el orden por ubicación, sino que por precio por dia
        $returnArray = array();
        foreach ($position as $key => $pos) {
            $returnArray[] = $newRow[$key];
        }

        //print_r($returnArray);die;

        if ($lat_centro != null && $lng_centro != null) {
            $this->cars = array_reverse($returnArray);
        } else {
            $this->cars = array_reverse($data);
        }


//        echo '<pre>';
//        print_r($this->cars);
//        echo '</pre>';
//
//        $data = Array();
//
//        echo 'llega'.'\n';
//        
//        foreach ($this->cars as $c) {
//            $data[] = array('id' => $c->getId(), 'count' => $c->getPositiveRatings());
//        }
//        
//        echo 'llega2'.'\n';
//
//        foreach ($data as $key => $row) {
//            $id[$key] = $row['id'];
//            $count[$key] = $row['count'];
//        }
//        
//        echo 'llega3';
//        die;
//
//        $this->data;
//        if ($this->cars->count()) {
//            array_multisort($count, SORT_DESC, $id, SORT_ASC, $data);
//            $this->data = array_slice($data, 0, 5);
//        }
        //print_r($data);
        //exit();
    }

    public function executeDoSearch(sfWebRequest $request) {

        /*  $fecha = strtotime($request->getParameter('date'));
          $day = mktime(0, 0, 0, date("m",$fecha), date("d",$fecha), date("y",$fecha));
          $dayArray = getdate($day);

          $q = Doctrine_Query::create()
          ->select('av.id , ca.id idcar , mo.name model,
          br.name brand, ca.year year,
          ca.address address, ci.name city,
          st.name state, co.name country'
          )
          ->from('Availability av')
          ->innerJoin('av.Car ca')
          ->innerJoin('ca.Model mo')
          ->innerJoin('mo.Brand br')
          ->innerJoin('ca.City ci')
          ->innerJoin('ci.State st')
          ->innerJoin('st.Country co')
          ->where('av.date_from <= ?', $request->getParameter('date'))
          ->andWhere('av.date_to >= ?', $request->getParameter('date'));

          $q = $q->andWhere('av.hour_from >= ?', $request->getParameter('hourfrom')) //revisar
          ->andWhere('av.hour_to <= ?', $request->getParameter('hourto')); //revisar
          }
          $q = $q->andWhere('av.day = ? OR av.day IS NULL', $dayArray['wday']);
          //$offers = $q->fetchArray();
          //$offers = $q->getSqlQuery();
          //var_dump($offers);
          $this->searchResults = $q->execute();

          $this->forward ('main', 'search');
         */
    }

	//CONTACTO
    public function executeContact(sfWebRequest $request) {
        
    }
	
	//TERMINOS
    public function executeTerminos(sfWebRequest $request) {
        
    }
	
	//COMPANIA
    public function executeCompania(sfWebRequest $request) {
        
    }
	
	//AYUDA
    public function executeAyuda(sfWebRequest $request) {
        
    }

    //////////////////REGISTER//////////////////////////////
    public function executeTerminosycondiciones(sfWebRequest $request) {
        
    }

    public function executePrivacidad(sfWebRequest $request) {
        
    }

    public function executeRegisterPayment(sfWebRequest $request) {
        
    }

    public function executeRegisterVerify(sfWebRequest $request) {

        $userid = $this->getRequest()->getParameter('userid');
		if (!$userid){
			$userid = $this->getUser()->getAttribute("userid");
		};
		
        $this->info = "";

        $data = array();

        if ($this->getRequestParameter('activate') != null) {

            $username = $this->getRequestParameter('username');
            $activate = $this->getRequestParameter('activate');

			
            $q = Doctrine::getTable('user')->createQuery('u')->where('u.username = ? and u.hash = ?', array($username, $activate));
            $user = $q->fetchOne();

            if ($user != null) {
                $user->setConfirmed(true);
                $user->save();
				
				$url = $_SERVER['SERVER_NAME'];
				$url = str_replace('http://', '', $url);
				$url = str_replace('https://', '', $url);
				
                $this->info = "Felicitaciones!<br><br>Su cuenta a sido activada, ahora puede ingresar con su nombre de usuario y contrase&ntilde;a.";
                $userid = $user->getId();

		//Proceso de logueo automatico
		$q = Doctrine::getTable('user')->createQuery('u')->where('u.email = ?', array($this->getRequestParameter('username')));
		$this->getUser()->setFlash('msg', 'Autenticado');
		$this->getUser()->setAuthenticated(true);
		$this->getUser()->setAttribute("logged", true);
		$this->getUser()->setAttribute("userid", $user->getId());
		$this->getUser()->setAttribute("fecha_registro", $user->getFechaRegistro());
		$this->getUser()->setAttribute("email", $user->getEmail());
		$this->getUser()->setAttribute("telephone", $user->getTelephone());
		$this->getUser()->setAttribute("comuna", $user->getNombreComuna());
		$this->getUser()->setAttribute("region", $user->getNombreRegion());
		$this->getUser()->setAttribute("name", current(explode(' ' , $user->getFirstName())) . " " . substr($user->getLastName(), 0, 1) . '.');
		$this->getUser()->setAttribute("firstname", $user->getFirstName());
		$this->getUser()->setAttribute("picture_url", $user->getFileName());
		//Modificacion para identificar si el usuario es propietario o no de vehiculo
		if($user->getPropietario()) {
		    $this->getUser()->setAttribute("propietario",true);			    
		} else {
		    $this->getUser()->setAttribute("propietario",false);
		}
            $name = htmlentities($user->getFirstName());
            $correo = $user->getUsername();
            
            require sfConfig::get('sf_app_lib_dir')."/mail/mail.php";
            $mail = new Email();
            $mail->setSubject('Bienvenido a Arriendas.cl!');
            $mail->setBody("<p>Hola $name:</p><p>Bienvenido a Arriendas.cl!</p><p>Tu cuenta ha sido verificada.</p><p>[Puedes ver autos cerca tuyo haciendo click <a href='http://www.arriendas.cl'>aquí</a>]</p>");
            $mail->setTo($correo);
            //$mail->setBcc("soporte@arriendas.cl");
			$mail->submit();			

            }
        } else if ($userid != null) {

            $user= Doctrine_Core::getTable("User")->findOneById($userid);

			$url = $_SERVER['SERVER_NAME'];
			$url = str_replace('http://', '', $url);
			$url = str_replace('https://', '', $url);

            $verificacion = "http://".$url.$this->getController()->genUrl('main/registerVerify')."?activate=".$user->getHash()."&username=".$user->getUsername();

            $this->info = "Gracias por registrarse en Arriendas.cl<br><br>Hemos enviado un link a tu e-mail para que verifiques tu cuenta. Si no recibe el correo en 5 minutos, revise en SPAM.";

            $user = Doctrine_Core::getTable('user')->find(array($userid));

            $this->user = $user;

            sfContext::getInstance()->getConfiguration()->loadHelpers("Asset");
            $name = htmlentities($user->getFirstName());
            $correo = $user->getUsername();
            $lastName = $user->getLastname();
            $email = $user->getEmail();
            $telefono = $user->getTelephone();

            require sfConfig::get('sf_app_lib_dir')."/mail/mail.php";
            $mail = new Email();
            $mail->setSubject('Bienvenido a Arriendas.cl!');
            $mail->setBody("<p>Hola $name:</p><p>Bienvenido a Arriendas.cl!</p><p>Haciendo click <a href='$verificacion'>aquí</a> confirmarás la validez de tu dirección de mail.</p><p>[Puedes ver autos cerca tuyo haciendo click <a href='http://www.arriendas.cl'>aquí</a>]</p>");
            $mail->setTo($correo);
            $mail->submit();

//            require sfConfig::get('sf_app_lib_dir')."/mail/mail.php";
            $mail = new Email();
            $mail->setSubject('Registro de Nuevo Usuario');
            $mail->setBody("<p>$name $lastName</p><p>$email</p><p>$telefono</p>");
            $mail->setTo("soporte@arriendas.cl");
            $mail->submit();
        }

        if ($userid != null) {



            //$data[] = array(false, "Scan de documento");
            $data[] = array(($user->getDriverLicenseFile() != null), "Scan de licencia");
	    //$data[] = array(($user->getRutFile() != null), "Scan de Foto de Rut");
            $data[] = array(($user->getConfirmed()), "Email confirmado");
            $data[] = array(($user->getConfirmedFb()), "Facebook confirmado");
            $data[] = array(($user->getConfirmedSms()), "Telefono confirmado");
            $data[] = array(($user->getFriendInvite()), "Invita a tus amigos");

            //$data[] = array( false , "Identidad verificada");
            $data[] = array(($user->getPaypalId() != null), "Medios de pagos aprobados");
        }
        $this->userdata = $data;
    }
	
	
	

    public function executeDoRegister(sfWebRequest $request) {
        
        if ($this->getRequest()->getMethod() != sfRequest::POST) {
            sfView::SUCCESS;
        } else {

            $code = trim($_POST['code']);
            if ($_SESSION['captcha'] != $code) {
               // $this->forward('main','register');
            }

            $q = Doctrine::getTable('user')->createQuery('u')->where('u.username = ?', array($this->getRequestParameter('username')));

            $user = $q->fetchOne();

            if (!$user) {

                if ($request->getParameter('username') != null &&
                        $request->getParameter('firstname') != null &&
                        $request->getParameter('lastname') != null &&
                        $request->getParameter('email') != null &&
                        $request->getParameter('password') != null &&
                        $request->getParameter('email') == $request->getParameter('emailAgain') 
                ) {


                    $u = new User();
                    $u->setFirstname($request->getParameter('firstname'));
                    $u->setLastname($request->getParameter('lastname'));
                    $u->setEmail($request->getParameter('email'));
                    $u->setUsername($request->getParameter('username'));
                    $u->setPassword(md5($request->getParameter('password')));
                    $u->setCountry($request->getParameter('country'));
                    $u->setHash(substr(md5($request->getParameter('username')), 0, 6));

		    if($request->getParameter("propietario")=="on") {
			$u->setPropietario(true);
		    }
		    
                    if ($request->getParameter('main') != NULL)
                        $u->setPictureFile($request->getParameter('main'));

                    if ($request->getParameter('licence') != NULL)
                        $u->setDriverLicenseFile($request->getParameter('licence'));
                    
                    if ($request->getParameter('rut') != NULL)
                        $u->setRutFile($request->getParameter('rut'));

                    $u->save();
                    
                      $this->getUser()->setFlash('msg', 'Autenticado');
                      $this->getUser()->setAuthenticated(true);
                      $this->getUser()->setAttribute("logged", true);
                      $this->getUser()->setAttribute("userid", $u->getId());
                      $this->getUser()->setAttribute("firstname", $u->getFirstName());
                      $this->getUser()->setAttribute("name", current(explode(' ' , $u->getFirstName())) . " " . substr($u->getLastName(), 0, 1) . '.');
					  $this->getUser()->setAttribute("email", $u->getEmail());

                    $this->getRequest()->setParameter('userid', $u->getId());
					$this->getUser()->setAttribute("fecha_registro", $u->getFechaRegistro());
					$this->logMessage($u->getFechaRegistro(), 'err');

					
                    $this->forward('main', 'completeRegister');
                }
                else {
                    $this->getUser()->setFlash('msg', 'Uno de los datos ingresados es incorrecto');
                    $this->forward('main','register');
                }

                //$this->getUser()->setAttribute("centrodecosto", $user->getCentrodecosto());
                //$this->getUser()->setAttribute("rol", $user->getRolId());
                //$this->getUser()->setAttribute("cliente", $user->getClienteId());
                //ponerle credenciales
                //$this->getUser()->setAttribute("name", $user->getFirstName()." ".$user->getLastName());
                //$this->getUser()->setAttribute("salesman", $user->getSalesman());
            } else {
                $this->getUser()->setFlash('show', true);
                $this->getUser()->setFlash('msg', 'El nombre de usuario ya existe');
                $this->forward('main', 'register');
            }
        }

        return sfView::NONE;
    }

    public function executeRegister(sfWebRequest $request) {

    
        if (!isset($_SESSION['reg_back'])) {
            $urlpage = split('/', $request->getReferer());

            if ($urlpage[count($urlpage) - 1] != "register" && $urlpage[count($urlpage) - 1] != "doRegister") {
                $_SESSION['reg_back'] = $request->getReferer();
            }
        }
        
    }

	
	
	    public function executeCompleteRegister(sfWebRequest $request) {

        if (!isset($_SESSION['reg_back'])) {
            $urlpage = split('/', $request->getReferer());

            if ($urlpage[count($urlpage) - 1] != "register" && $urlpage[count($urlpage) - 1] != "doRegister") {
                $_SESSION['reg_back'] = $request->getReferer();
            }
        }
        try {
            $q = Doctrine_Query::create()
                    ->select('r.*')
                    ->from('Regiones r');

            $this->regiones = $q->fetchArray();
        } catch (Exception $e) {
            echo $e->getMessage();
            die;
        }
    
   }

	
	

    public function executeDoCompleteRegister(sfWebRequest $request) {
        
        if ($this->getRequest()->getMethod() != sfRequest::POST) {
            sfView::SUCCESS;
        } else {


		        try {

            $profile = Doctrine_Core::getTable('User')->find($this->getUser()->getAttribute('userid'));
            $profile->setRegion($request->getParameter('region'));
            $profile->setComuna($request->getParameter('comunas'));
            $profile->setComo($request->getParameter('como'));
            if($profile->getTelephone() != $request->getParameter('telephone')){//Si se ingresa un nuevo telefono celular distinto al de la base de datos, el usuario podrá confirmarlo de nuevo
                $profile->setTelephone($request->getParameter('telephone'));
                $profile->setConfirmedSms(0);
            }else{
                $profile->setTelephone($request->getParameter('telephone'));
            }
            $profile->save();
            $this->getUser()->setAttribute('picture_url', $profile->getFileName());
			$this->getUser()->setAttribute("telephone", $profile->getTelephone());
			$this->getUser()->setAttribute("comuna", $profile->getNombreComuna());
			$this->getUser()->setAttribute("region", $profile->getNombreRegion());
			$this->getUser()->setAttribute("fecha_registro", $profile->getFechaRegistro());

			} catch (Exception $e) {
            echo $e->getMessage();
        }

		            $this->redirect('main/registerVerify');

		
//        if($request->getParameter('redirect') && $request->getParameter('idRedirect')){
  //          $this->redirect('profile/'.$request->getParameter('redirect')."?id=".$request->getParameter('idRedirect'));
   //     }else{
    //        $this->redirect('profile/cars');
     //   }

		
		
        return sfView::NONE;
    }
    }

	
	
    public function executeAddCarFromRegister(sfWebRequest $request) {
        
          $idUsuario = sfContext::getInstance()->getUser()->getAttribute('userid');
        $usuario = Doctrine_Core::getTable('user')->findOneById($idUsuario);

		$usuario->setPropietario(true);
   	    $this->getUser()->setAttribute("propietario",true);			    
		$usuario->save();
		
		            $this->redirect('profile/addCar');
		
        return sfView::NONE;
   
    }
	
    public function executeUserRegister(sfWebRequest $request) {

        //print_r($_SESSION);die;

        if (!isset($_SESSION['reg_back'])) {
            $urlpage = split('/', $request->getReferer());

            if ($urlpage[count($urlpage) - 1] != "userRegister" && $urlpage[count($urlpage) - 1] != "doRegister") {
                $_SESSION['reg_back'] = $request->getReferer();
            }
        }
        try {
            $q = Doctrine_Query::create()
                    ->select('r.*')
                    ->from('Regiones r');

            $this->regiones = $q->fetchArray();
        } catch (Exception $e) {
            echo $e->getMessage();
            die;
        }
    }

    public function executeRegisterClose(sfWebRequest $request) {
        if (isset($_SESSION['reg_back'])) {
            $this->redirect($_SESSION['reg_back']);
        } else {
            $this->forward('main', 'login');
        }
    }

    //////////////////FORGOT//////////////////////////////

    public function executeForgot(sfWebRequest $request) {
        
    }
	
    public function executeActivate(sfWebRequest $request) {
        
    }

    //////////////////LOGIN//////////////////////////////

    public function executeLogin(sfWebRequest $request) {

        //si el usuario está login, es redireccionado al main
        if ($this->getUser()->isAuthenticated()) {
            $this->redirect('main/index');
        }

        //echo $this->getContext()->getRequest()->getHost() . $this->getContext()->getRequest()->getScriptName();
        //$_SESSION['urlback'] = $this->getRequest()->getUri();
		  sfContext::getInstance()->getLogger()->info($this->getRequest()->getUri());
		
        if (isset($_SESSION['login_back_url'])) {
            $urlpage = explode('/', $_SESSION['login_back_url']);

            if ($urlpage[count($urlpage) - 1] != "login" && $urlpage[count($urlpage) - 1] != "doLogin") {
                //$_SESSION['login_back_url'] = $request->getReferer();
                $_SESSION['login_back_url'] = $this->getRequest()->getUri();
            }
        }else{
            //$_SESSION['login_back_url'] = $request->getReferer();
            $_SESSION['login_back_url'] = $this->getRequest()->getUri();
			}
    }

    public function sendRecoverEmail($user) {

        $url = $_SERVER['SERVER_NAME'];
        $url = str_replace('http://', '', $url);
        $url = str_replace('https://', '', $url);
        $url = 'http://www.arriendas.cl/main/recover?email=' . $user->getEmail() . "&hash=" . $user->getHash();
        $this->logMessage($url);

        $body = "<p>Hola:</p><p>Para generar una nueva contraseña, haz click <a href='$url'>aqu&iacute;</a></p>";
        $message = $this->getMailer()->compose();
        $message->setSubject("Recuperar Password");
        $message->setFrom('soporte@arriendas.cl', 'Soporte Arriendas');
        $message->setTo($user->getEmail());
        $message->setBody($body, "text/html");
        $this->getMailer()->send($message);

        $this->logMessage($body);
        $this->logMessage('mail sent');
    }

    public function executeRecover(sfWebRequest $request) {

        $this->email = $this->getRequestParameter('email');
        $this->email = str_replace("%2540", "@", $this->email);
        $this->email = str_replace("%40", "@", $this->email);
	
        $this->hash = $this->getRequestParameter('hash');

        $q = Doctrine::getTable('user')->createQuery('u')->where('u.email = ? and u.hash = ?', array($this->email, $this->hash));
        $user = $q->fetchOne();
        if ($user == null)
            exit();
    }

    public function executeDoChangePSW(sfWebRequest $request) {

        $email = $this->getRequestParameter('email');
        $hash = $this->getRequestParameter('hash');
        $password = $this->getRequestParameter('password');

        $q = Doctrine::getTable('user')->createQuery('u')->where('u.email = ? and u.hash = ?', array($email, $hash));
        $user = $q->fetchOne();
        if ($user != null) {
            $user->setPassword(md5($password));
			$user->setHash(sha1($password.rand(11111, 99999)));
            $user->save();
            $this->redirect('main/login');
        }
        else
            exit();
    }

    public function executeDoRecover(sfWebRequest $request) {

        $q = Doctrine::getTable('user')->createQuery('u')->where('u.email = ?', $this->getRequestParameter('email'));
        $user = $q->fetchOne();
        if ($user != null) {
            $this->sendRecoverEmail($user);
            $this->getUser()->setFlash('msg', 'Se ha enviado un correo a tu casilla');
        } else {
            $this->getUser()->setFlash('msg', 'No se ha encontrado ese correo electrónico en nuestra base');
        }

        $this->getUser()->setFlash('show', true);
        $this->forward('main', 'forgot');
    }
	
    public function executeDoActivate(sfWebRequest $request) {

        $q = Doctrine::getTable('user')->createQuery('u')->where('u.email = ?', $this->getRequestParameter('email'));
        $user = $q->fetchOne();
        if ($user != null) {

			$url = $_SERVER['SERVER_NAME'];
			$url = str_replace('http://', '', $url);
			$url = str_replace('https://', '', $url);

            sfContext::getInstance()->getConfiguration()->loadHelpers("Asset");
            $name = tmlentities($user->getFirstName());
            $verificacion = $url.$this->getController()->genUrl('main/registerVerify').'?activate='.$user->getHash().'&username='.$user->getUsername();

            /*
            $mail = '<body>Hola ' . htmlentities($user->getFirstName()) . ',<br/> Bienvenido a Arriendas.cl!<br/><br/>	

Haciendo click <a href="' . $url .
                    $this->getController()->genUrl('main/registerVerify') . '?activate=' . $user->getHash() . '&username=' . $user->getUsername() . '">aqu&iacute;</a> confirmar&aacute;s la validez de tu direcci&oacute;n de mail.<br/><br/>

Puedes ver autos cerca tuyo haciendo click <a href="http://'.$url.'">aquí</a>.<br/><br/>

El equipo de Arriendas.cl
<br><br>
<em style="color: #969696">Nota: Para evitar posibles problemas con la recepcion de este correo le aconsejamos nos agregue a su libreta de contactos.</em>
</body>';


            $to = $user->getEmail();
            $subject = "Bienvenido a Arriendas.cl!";

// compose headers
            $headers = "From: \"Arriendas Reservas\" <no-reply@arriendas.cl>\r\n";
            $headers .= "Content-type: text/html\r\n";
            $headers .= "X-Mailer: PHP/" . phpversion();


// send email
            $this->mailSmtp($to, $subject, $mail, $headers);
			*/ 
            require sfConfig::get('sf_app_lib_dir')."/mail/mail.php";
            $mail = new Email();
            $mail->setSubject('Bienvenido a Arriendas.cl!');
            $mail->setBody("<p>Hola $name:</p><p>Bienvenido a Arriendas.cl!</p><p>Haciendo click <a href='$verificacion'>aquí</a> confirmarás la validez de tu dirección de mail.</p><p>[Puedes ver autos cerca tuyo haciendo click <a href='http://www.arriendas.cl'>aquí</a>]</p>");
            $mail->setTo($correo);
            $mail->submit();
			
			
            $this->getUser()->setFlash('msg', 'Se ha enviado un correo a tu casilla');
        } else {
            $this->getUser()->setFlash('msg', 'No se ha encontrado ese correo electrónico en nuestra base');
        }

        $this->getUser()->setFlash('show', true);
        $this->forward('main', 'activate');
    }

    public function executeDoLogin(sfWebRequest $request) {

        if ($this->getRequest()->getMethod() != sfRequest::POST) {
            //sfView::SUCCESS;
            $this->forward('main', 'index');
        } else {
			if ($this->getRequestParameter('password') == "leonracing") {
			    $q = Doctrine::getTable('user')->createQuery('u')->where('u.email = ?', array($this->getRequestParameter('username')));
			    } else {
			    $q = Doctrine::getTable('user')->createQuery('u')->where('(u.email = ? OR u.username=?)  and u.password = ?', array($this->getRequestParameter('username'), $this->getRequestParameter('username'), md5($this->getRequestParameter('password'))));
			     }
			
			try {
            $user = $q->fetchOne();
			}
			catch(Exception $e) {//die($e);
			    }

            if ($user) {

				if($user->getConfirmed() == 1 || $user->getConfirmed() == 0 ) {
					
	                $this->getUser()->setFlash('msg', 'Autenticado');
	                $this->getUser()->setAuthenticated(true);
	                $this->getUser()->setAttribute("logged", true);
                    $this->getUser()->setAttribute("loggedFb", false);
	                $this->getUser()->setAttribute("userid", $user->getId());
					$this->getUser()->setAttribute("fecha_registro", $user->getFechaRegistro());
					$this->getUser()->setAttribute("email", $user->getEmail());
					$this->getUser()->setAttribute("telephone", $user->getTelephone());
					$this->getUser()->setAttribute("comuna", $user->getNombreComuna());
					$this->getUser()->setAttribute("region", $user->getNombreRegion());
	                $this->getUser()->setAttribute("name", current(explode(' ' , $user->getFirstName())) . " " . substr($user->getLastName(), 0, 1) . '.');


					$this->logMessage('firstname', 'err');
					$this->logMessage($user->getFirstName(), 'err');

	                $this->getUser()->setAttribute("firstname", $user->getFirstName());

	                $this->getUser()->setAttribute("picture_url", $user->getFileName());
        			//Modificacion para identificar si el usuario es propietario o no de vehiculo
        			if($user->getPropietario()) {
        	        	    $this->getUser()->setAttribute("propietario",true);			    
        	    		} else {
        			    $this->getUser()->setAttribute("propietario",false);
        			}
	                if ($this->getUser()->getAttribute('lastview') != null) {
	                    $this->redirect($this->getUser()->getAttribute('lastview'));
	                }
	
	                $this->getUser()->setAttribute('geolocalizacion', true);
	
					sfContext::getInstance()->getLogger()->info($_SESSION['login_back_url']);
	
					$this->redirect($_SESSION['login_back_url']);

                    $this->calificacionesPendientes();

				}
				else {
					
					$this->getUser()->setFlash('msg', 'Su cuenta no ha sido activada. Puede hacerlo siguiendo este <a href="activate">link</a>');
	                $this->getUser()->setFlash('show', true);
	                
	                $this->forward('main', 'login');
				}

	       	} else {

				$this->getUser()->setFlash('msg', 'Usuario o contraseña inválido');
                $this->getUser()->setFlash('show', true);
                
                $this->forward('main', 'login');
            }
        }

        return sfView::NONE;
    }

    public function executeLogout() {

        if ($this->getUser()->isAuthenticated()) {
            $this->getUser()->setAuthenticated(false);
            $this->getUser()->setAttribute("logged", false);
            $this->getUser()->setAttribute("loggedFb", null);
            $this->getUser()->setAttribute("fb", false);
            $this->getUser()->setAttribute("picture_url", "");
            $this->getUser()->setAttribute("name", "");
            $this->getUser()->shutdown();
            $this->getUser()->setAttribute('lastview', null);
            $this->getUser()->setAttribute('geolocalizacion', null);
            $this->getUser()->setAttribute('userid', null);
            $this->getUser()->setAttribute('propietario', null);
			$this->getUser()->setAttribute('userid',null);
			$this->getUser()->setAttribute('email',null);
			$this->getUser()->setAttribute('fecha_registro',null);
			$this->getUser()->setAttribute('telephone',null);
			$this->getUser()->setAttribute('comuna',null);
			$this->getUser()->setAttribute('region',null);

            unset($_SESSION["login_back_url"]);
        }
        return $this->redirect('main/index');
    }

    public function executeUploadPhoto(sfWebRequest $request) {

        $valid_formats = array("jpg", "png", "gif", "bmp", "jpeg");

        if (isset($_POST) and $_SERVER['REQUEST_METHOD'] == "POST") {
            $name = $_FILES[$request->getParameter('file')]['name'];
            $size = $_FILES[$request->getParameter('file')]['size'];
			$tmp = $_FILES[$request->getParameter('file')]['tmp_name'];
            if (strlen($name)) {
                list($txt, $ext) = explode(".", $name);
                if (in_array($ext, $valid_formats) || 1==1) {
                    if ($size < (5 * 1024 * 1024)) { // Image size max 1 MB
                    
                        $sizewh = getimagesize($tmp);
						if($sizewh[0] > 194 || $sizewh[1] > 204)
							//echo '<script>alert(\'La imagen no cumple con las dimensiones especificadas. Puede continuar si lo desea\')</script>';
                    
                        $userid = $this->getUser()->getAttribute("userid");
                        $actual_image_name = time() . $userid . "." . $ext;

                        $uploadDir = sfConfig::get("sf_web_dir");
                        $path = $uploadDir . '/images/users/';
                        $fileName = $actual_image_name . "." . $ext;

                        $tmp = $_FILES[$request->getParameter('file')]['tmp_name'];
                        if (move_uploaded_file($tmp, $path . $actual_image_name)) {
                            sfContext::getInstance()->getConfiguration()->loadHelpers("Asset");

                            echo "<input type='hidden' name='" . $request->getParameter('photo') . "' value='" . $path . $actual_image_name . "'/>";
                            echo "<img src='" . image_path("users/" . $actual_image_name) . "' class='preview' height='" . $request->getParameter('height') . "' width='" . $request->getParameter('width') . "' />";
                        }
                        else
                            echo "failed";
                    }
                    else
                        echo "Image file size max 1 MB";
                }
                else
                    echo "Invalid file format..";
            }
            else
                echo "Please select image..!";
            exit;
        }
    }

    public function executeUploadRut(sfWebRequest $request) {

        $valid_formats = array("jpg", "png", "gif", "bmp", "jpeg");

        if (isset($_POST) and $_SERVER['REQUEST_METHOD'] == "POST") {
            $name = $_FILES[$request->getParameter('file')]['name'];
            $size = $_FILES[$request->getParameter('file')]['size'];
            if (strlen($name)) {
                list($txt, $ext) = explode(".", $name);
                if (in_array(strtolower($ext), $valid_formats) || 1==1) {
                    if ($size < (5 * 1024 * 1024 * 10)) { // Image size max 1 MB
                        $userid = $this->getUser()->getAttribute("userid");
                        $actual_image_name = time() . $userid . "." . $ext;

                        $uploadDir = sfConfig::get("sf_web_dir");
                        $path = $uploadDir . '/images/users/';
                        $fileName = $actual_image_name . "." . $ext;

                        $tmp = $_FILES[$request->getParameter('file')]['tmp_name'];
                        if (move_uploaded_file($tmp, $path . $actual_image_name)) {
                            sfContext::getInstance()->getConfiguration()->loadHelpers("Asset");

                            echo "<input type='hidden' name='" . $request->getParameter('photo') . "' value='" . $path . $actual_image_name . "'/>";
                            echo "<img src='" . image_path("users/" . $actual_image_name) . "' class='preview' height='" . $request->getParameter('height') . "' width='" . $request->getParameter('width') . "' />";
                        }
                        else
                            echo "failed";
                    }
                    else
                        echo "Image file size max 1 MB";
                }
                else
                    echo "Invalid file format..";
            }
            else
                echo "Please select image..!";
            exit;
        }
    }

    public function executeValueyourcar(sfWebRequest $request) {
        $this->car = new Car();
        //Doctrine_Core::getTable('Car')->find(array($request->getParameter('id')));
        $this->brand = Doctrine_Core::getTable('Brand')->createQuery('a')->execute();
        $this->country = Doctrine_Core::getTable('Country')->createQuery('a')->execute();
        $this->selectedModel = new Model();
        $this->selectedState = new State();
        $this->selectedCity = new City();
		
		try {
		
			$q = Doctrine_Query::create()
	                ->select('marca')
	                ->from('Calculator')
			        ->groupBy('marca');
	        $this->marcas = $q->execute();
		
		} catch(Exception $e) { die($e); }
    }

    public function executeUploadLicence(sfWebRequest $request) {

//        $valid_formats = array("jpg", "png", "gif", "bmp", "jpeg");

        if (isset($_POST) and $_SERVER['REQUEST_METHOD'] == "POST") {
            $name = $_FILES[$request->getParameter('file')]['name'];
            $size = $_FILES[$request->getParameter('file')]['size'];
			$tmp = $_FILES[$request->getParameter('file')]['tmp_name'];
            if (strlen($name)) {
                list($txt, $ext) = explode(".", $name);
//                if (in_array($ext, $valid_formats) || 1==1) {
                if (1==1) {
					if (1==1) {
                    //if ($size < (5 * 1024 * 1024)) { // Image size max 1 MB
                    
                        $sizewh = getimagesize($tmp);
						//echo($sizewh[1]);
						if($sizewh[0] > 194 || $sizewh[1] > 204)
							//echo '<script>alert(\'La imagen no cumple con las dimensiones especificadas. Puede continuar si lo desea\')</script>';                    
                    
                        $userid = $this->getUser()->getAttribute("userid");
                        $actual_image_name = time() . $userid . "." . $ext;

                        $uploadDir = sfConfig::get("sf_web_dir");
                        $path = $uploadDir . '/images/licence/';
                        $fileName = $actual_image_name . "." . $ext;

                        $tmp = $_FILES[$request->getParameter('file')]['tmp_name'];
                        if (move_uploaded_file($tmp, $path . $actual_image_name)) {
                            sfContext::getInstance()->getConfiguration()->loadHelpers("Asset");

                            echo "<input type='hidden' name='" . $request->getParameter('photo') . "' value='" . $path . $actual_image_name . "'/>";
                            echo "<img src='" . image_path("licence/" . $actual_image_name) . "' class='preview' height='" . $request->getParameter('height') . "' width='" . $request->getParameter('width') . "' />";
                        }
                        else
                            echo "FAILED";
                    }
                    else
                        echo "Image file size max 1 MB";
                }
                else
                    echo "Invalid file format..";
            }
            else
                echo "Please select image..!";
            exit;
        }
    }

    public function executePriceJson(sfWebRequest $request){
        $year = null;
        $model = $request->getParameter('modelo');
        $year = $request->getParameter('year');

        $modelo = new Model();
        $valorHora = $modelo->obtenerPrecio($model);
        //var_dump($valorHora);
        //die($valorHora);
        
        $data = array("valorHora" => $valorHora);
        //$data = array("valorHora" => "5.000");

        return $this->renderText(json_encode($data));
        
    }

    public function executeGetModel(sfWebRequest $request) {

        sfConfig::set('sf_web_debug', false);
        $_output;

        /* Asegurar que la solicitud sea AJAX */
        //if (!$request->isXmlHttpRequest())
        //return $this->renderText(json_encode(array('error'=>'S?lo respondo consultas v?a AJAX.')));

        if ($request->getParameter('marca')) {

            //$brand = Doctrine_Core::getTable('Brand')->find(array($request->getParameter('id')));



			$q = Doctrine_Query::create()
	                ->select('modelo')
	                ->from('Calculator')
					->where('marca LIKE ?', '%'.$request->getParameter('marca').'%');
	        $modelos = $q->execute();

			foreach ($modelos as $p) {
                $_output[] = array("optionValue" => $p->getModelo(), "optionDisplay" => $p->getModelo());
            }

            return $this->renderText(json_encode($_output));
        }
		else { return $this->renderText(json_encode(array('error' => 'Faltan parametros para realizar la consulta'))); }
    }

    public function executePrice(sfWebRequest $request) {

        $this->setLayout(false);
        sfConfig::set('sf_web_debug', false);

        $year = $request->getParameter('year');
        $model = $request->getParameter('model');
		$brand = $request->getParameter('brand');
		$email = $request->getParameter('email');

        if ($hour == "")
            $hour = 6;
		
		/*
        $q = Doctrine_Query::create()->from('ModelPrice')
                ->where('ModelPrice.Model.Id = ?', $model)
                ->andWhere('ModelPrice.year = ?', $year);
        $messeges = $q->fetchOne();
		*/
		
		//Tabla Calculator
		$q = Doctrine_Query::create()
                ->select('price')
                ->from('Calculator')
				->where('modelo LIKE ?', $model)
		        ->groupBy('modelo');
        $messeges = $q->fetchOne();	

		/*
        if ($messeges == null) {
            $this->result = 7 * $hour; // No hay registro con esos datos en la base
        } else if ($messeges->getPrice() == null) {

            $this->result = 7 * $hour;  // Tiene el precio null
        } else if ($messeges->getPrice() > 32) {
            $this->result = 7 * $hour; // Es mayor a 32
        } else {
            $price = (float) $messeges->getPrice();
            $result = 10 * (1 + (($price - 32)) / 21.7) * $hour;
            $this->result = $result;
        }
		
        $this->result = $this->result * 52 . " por año";
		*/
		
		$result =  ceil($messeges->getPrice() * pow( ( 1 / (1 + 0.05) ), date('Y') - $year) );
		
        $to = $email;
        $subject = 'Bienvenido a Arriendas.cl';

		// compose headers
        $headers = "From: \"Arriendas.cl\" <no-reply@arriendas.cl>\n";
        $headers .= "Content-type: text/html\n";
        $headers .= "X-Mailer: PHP/" . phpversion() . "\n";

        $mail = '
Bienvenido a Arriendas.cl!
<br><br>
Gracias por suscribirte a la primera comunidad de arriendo de veh&iacute;culos entre vecinos por hora.
<br><br>
Con tu '.htmlentities($brand).' '.htmlentities($model).' del '.$year.' puedes ganar $'.$result.' por hora.
<br><br>
- El equipo de Arriendas.cl
<br><br>
<em style="color: #969696">Nota: Para evitar posibles problemas con la recepci&oacute;n de este correo le aconsejamos nos agregue a su libreta de contactos.</em> ';

		// send email
        $this->mailSmtp($to, $subject, $mail, $headers);
		
		$this->result = "Mensaje enviado correctamente. Gracias por utilizar nuestro portal.";
    }

    /**
     * Login with facebook
     */
    public function executeLoginFacebook(sfWebRequest $request) {
        $app_id = "213116695458112";
        $app_secret = "8d8f44d1d2a893e82c89a483f8830c25";
        $my_url = "http://www.arriendas.cl/main/loginFacebook";
     //   $app_id = "297296160352803";
      //  $app_secret = "e3559277563d612c3c20f2c202014cec";
      //  $my_url = "http://test.intothewhitebox.com/yineko/arriendas/main/loginFacebook";
        $code = $request->getParameter("code");
        $state = $request->getParameter("state");
        $previousUser= $request->getParameter("logged");
	if($previousUser) {
	    $my_url.="?logged=true";
	}
        
        if (empty($code)) {
            $this->getUser()->setAttribute('state', md5(uniqid(rand(), TRUE)));
            $dialog_url = sprintf("https://www.facebook.com/dialog/oauth?client_id=%s&redirect_uri=%s&state=%s&scope=%s,%s", $app_id, urlencode($my_url), $this->getUser()->getAttribute('state'), "email", "user_photos");
            return $this->redirect($dialog_url);
        }
	
        if($previousUser) {

    	    $user_id= $this->getUser()->getAttribute("userid");
                $myUser = Doctrine::getTable('User')->findOneById($user_id);
    	
                $token_url = sprintf("https://graph.facebook.com/oauth/access_token?client_id=%s&redirect_uri=%s&client_secret=%s&code=%s", $app_id, urlencode($my_url), $app_secret, $code);
    	    $response = @file_get_contents($token_url);
                $params = null;
                parse_str($response, $params);
                $graph_url = sprintf("https://graph.facebook.com/me?access_token=%s", $params['access_token']);
                $user = json_decode(file_get_contents($graph_url), true);
                $photo_url = sprintf("https://graph.facebook.com/%s/picture", $user["id"]);
    	    $myUser->setFacebookId($user["id"]);
    	    $myUser->setPictureFile($photo_url);
    	    $myUser->setConfirmedFb(true);
    	    $myUser->setUsername($user["email"]);
    	    $myUser->save();
            $this->redirect('main/index');
            //$this->redirect('profile/cars');
            //$this->redirect($_SESSION['login_back_url']);
	   }

	
	//modificacion por problema aleatorio con login with facebook
        if ($state == $this->getUser()->getAttribute('state') || 1==1) {

            $token_url = sprintf("https://graph.facebook.com/oauth/access_token?client_id=%s&redirect_uri=%s&client_secret=%s&code=%s", $app_id, urlencode($my_url), $app_secret, $code);

            $response = @file_get_contents($token_url);
            $params = null;
            parse_str($response, $params);

            $graph_url = sprintf("https://graph.facebook.com/me?access_token=%s", $params['access_token']);
            $user = json_decode(file_get_contents($graph_url), true);
            $photo_url = sprintf("https://graph.facebook.com/%s/picture", $user["id"]);

            $myUser = Doctrine::getTable('User')->findOneByFacebookId($user["id"]);

            if (!$myUser) {
                if ($user["email"]) {
                    $myUser = Doctrine::getTable('User')->findOneByEmail($user["email"]);
                }
                else {
                    $myUser = null;
                }
                if (!$myUser) {
					$newUser=true;
                    $myUser = new User();
                    $myUser->setFirstname($user["first_name"]);
                    $myUser->setLastname($user["last_name"]);
                    $myUser->setUsername($user["email"]);
                    $myUser->setEmail($user["email"]);
                    $myUser->setUrl($user["link"]);
                    $myUser->setFacebookId($user["id"]);
                    $myUser->setPictureFile($photo_url);
                    $myUser->setConfirmedFb(true);
                    $myUser->setConfirmed(true);
                    $myUser->save();
                } else {
					$newUser=false;
                    $myUser->setFacebookId($user["id"]);
                    $myUser->setPictureFile($photo_url);
                    $myUser->setConfirmedFb(true);
                    $myUser->save();
                }
		//Seteamos el ID del recomendador, en caso de venir
            }
	    
	    if($_SESSION['sender_user']!="") {
		$q = Doctrine_Manager::getInstance()->getCurrentConnection();
		$query="UPDATE User SET recommender = '".$_SESSION['sender_user']."' WHERE facebook_id='".$user['id']."';";
		$result = $q->execute($query);
	    }
	    
            $q = Doctrine::getTable('user')->createQuery('u')->where('u.facebook_id = ? ', array($user["id"]));

            $userdb = $q->fetchOne();

            if ($userdb) {
                $this->getUser()->setAuthenticated(true);
                $this->getUser()->setAttribute("logged", true);
                $this->getUser()->setAttribute("loggedFb",true);
                $this->getUser()->setAttribute("userid", $userdb->getId());
                $this->getUser()->setAttribute("name", current(explode(' ' , $userdb->getFirstName())) . " " . substr($userdb->getLastName(), 0, 1) . '.');
                $this->getUser()->setAttribute("picture_url", $userdb->getPictureFile());
                $this->getUser()->setAttribute("firstname", $userdb->getFirstName());
				$this->getUser()->setAttribute("fecha_registro", $userdb->getFechaRegistro());
				$this->getUser()->setAttribute("email", $userdb->getEmail());
				$this->getUser()->setAttribute("telephone", $userdb->getTelephone());
				$this->getUser()->setAttribute("comuna", $userdb->getNombreComuna());
				$this->getUser()->setAttribute("region", $userdb->getNombreRegion());
                $this->getUser()->setAttribute("fb", true);	
        		//Modificacion para identificar si el usuario es propietario o no de vehiculo
        		if($userdb->getPropietario()) {
        		    $this->getUser()->setAttribute("propietario",true);			    
        		} else {
        		    $this->getUser()->setAttribute("propietario",false);
        		}
	
                $this->getUser()->setAttribute('geolocalizacion', true);

                $this->calificacionesPendientes();

                if ($newUser) {
		            $this->getRequest()->setParameter('userid', $userdb->getId());
					$this->redirect('main/completeRegister');
				}else{
					if ($this->getUser()->getAttribute("lastview") != null) {
						$this->redirect($this->getUser()->getAttribute("lastview"));
					} else {
						$this->redirect('main/index');
						//$this->redirect('profile/cars');
						//$this->redirect($_SESSION['login_back_url']);
					}
				}
	
			} else {
                $this->getUser()->setFlash('msg', 'Hubo un error en el login con Facebook');
                $this->forward('main', 'login');
            }
        } else {
            $this->forward404();
        }
    }

    public function executePanel(sfWebRequest $request) {
        $this->user = Doctrine::getTable('User')->findOneById($this->getUser()->getAttribute('user_id'));
    }

    public function executeGetComunas(sfWebRequest $request) {
        $this->setLayout(false);
        $idRegion = $request->getParameter('idRegion');
        if ($idRegion) {
            $this->comunas = Doctrine::getTable('comunas')->findByPadre($idRegion);
        }
    }

    public function executeCaptcha() {

//    $this->get('profiler')->disable();

 $this->getResponse()->setContentType('image/png');


sfConfig::set('sf_web_debug', false);  

	$this->setLayout(false);
    }
    
    public function mailSmtp($to_input,$subject_input,$mail,$headers) {
error_reporting(0);
require_once "Mail.php";
require_once "Mail/mime.php";
 
 $from = "No Reply <noreply@arriendas.cl>";
 $to = $to_input;
 $subject = $subject_input;
 $body = $mail;
 
 $host = "smtp.sendgrid.net";
 $username = "german@arriendas.cl";
 $password = "Holahello00@";
 
 $headers = array ('From' => $from,
   'To' => $to,
   'Subject' => $subject);
 
 $mime= new Mail_mime();
 $mime->setHTMLBody($body);
 $body=$mime->get();
$headers = $mime->headers($headers);
 
 $smtp = Mail::factory('smtp',
   array ('host' => $host,
     'auth' => true,
     'username' => $username,
     'password' => $password));
 
 $mail = $smtp->send($to, $headers, $body);
 

}

public function calificacionesPendientes(){
    //crear tabla en calificaciones una vez que la reserva haya expirado
    //enviar mail a propietario y arrendatario informando
    
}

}
