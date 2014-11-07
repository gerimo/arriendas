<?php

class Functions
{
    public function generarContrato($tokenReserva) {
    //Obtenemos el ID de la reserva
//    $idReserva=  $request->getParameter("idReserva");
//    $tokenReserva=  $request->getParameter("tokenReserva");
    
    if (!$tokenReserva){
		$reserva= Doctrine_Core::getTable("reserve")->findOneById($idReserva);
		$transaction= Doctrine_Core::getTable("transaction")->findOneByReserveId($idReserva);
    }else{
		$reserva= Doctrine_Core::getTable("reserve")->findOneByToken($tokenReserva);
		$transaction= Doctrine_Core::getTable("transaction")->findOneByReserveId($reserva->getId());
    };
	
    $datosContrato= new stdClass();
    //var_dump($reserva);
	$confirmed=$transaction->getCompleted();
		
    $dateTimeDevolucion = date("Y-m-d H:i:s", strtotime("+".$reserva->getDuration()." hours", strtotime($reserva->getDate())));
	
    $datosContrato->fecha_arriendo=substr($reserva->getDate(),8,2)."/".substr($reserva->getDate(),5,2)."/".substr($reserva->getDate(),0,4);
    $datosContrato->hora_arriendo=substr($reserva->getDate(),11,5);
    $datosContrato->fecha_devolucion=substr($dateTimeDevolucion,8,2)."/".substr($dateTimeDevolucion,5,2)."/".substr($dateTimeDevolucion,0,4);
    $datosContrato->hora_devolucion=substr($dateTimeDevolucion,11,5);
    $datosContrato->duracion_arriendo=$reserva->getDuration()." horas";
    $datosContrato->precio_arriendo="$".number_format($reserva->getPrice(),0,",",".");
    
    //datos del auto
    $auto= CarTable::getInstance()->findOneById($reserva->getCarId());
    $datosContrato->patente_auto=$auto->getPatente();
    $datosContrato->anio_auto=$auto->getYear();
    
    
    //Modelo
    $modelo= ModelTable::getInstance()->findOneById($auto->getModelId());
    $datosContrato->modelo_auto=$modelo->getName();
  
    //Marca
    $marca= BrandTable::getInstance()->findOneById($modelo->getBrandId());
    $datosContrato->marca_auto=$marca->getName();
    
    //Arrendatario
    $arrendatario= UserTable::getInstance()->findOneById($reserva->getUserId());
    $datosContrato->nombre_arrendatario=$arrendatario->getFirstname()." ".$arrendatario->getLastname();
	
	if (!$confirmed){
		$datosContrato->rut_arrendatario=preg_replace("/[\w\.]/","X",$arrendatario->getRut());
		$datosContrato->domicilio_arrendatario=preg_replace("/[\w\.]/","X",$arrendatario->getAddress());
    }else{
		$datosContrato->rut_arrendatario=$arrendatario->getRut();
		$datosContrato->domicilio_arrendatario=$arrendatario->getAddress();
    };
	
    //Comuna Arrendatario
    $comuna_arr= ComunasTable::getInstance()->findOneByCodigoInterno($arrendatario->getComuna());
    $datosContrato->comuna_arrendatario=$comuna_arr->getNombre();
    
    //Dueno
    $duenio= UserTable::getInstance()->findOneById($auto->getUserId());
    $datosContrato->nombre_duenio=$duenio->getFirstname()." ".$duenio->getLastname();

	if (!$confirmed){
		$datosContrato->rut_duenio=preg_replace("/[\w\.]/","X",$duenio->getRut());
		$datosContrato->domicilio_duenio=preg_replace("/[\w\.]/","X",$duenio->getAddress());
	}else{
		$datosContrato->rut_duenio=$duenio->getRut();
		$datosContrato->domicilio_duenio=$duenio->getAddress();
	};

    //Comuna Duenio
    $comuna_due= ComunasTable::getInstance()->findOneByCodigoInterno($duenio->getComuna());
    $datosContrato->comuna_duenio= $comuna_due->getNombre();
    
    
  
    //Cargamos el texto del contrato desde el archivo fuente
    $contrato= file_get_contents(sfConfig::get("sf_app_template_dir")."/contrato_arriendas.html");
    
    //Reemplazamos los datos por la informacion del contrato
    $contrato= str_replace("{fecha_arriendo}",utf8_decode($datosContrato->fecha_arriendo),$contrato);
    $contrato= str_replace("{hora_ariendo}",$datosContrato->hora_arriendo,$contrato);
    $contrato= str_replace("{fecha_devolucion}",utf8_decode($datosContrato->fecha_devolucion),$contrato);
    $contrato= str_replace("{hora_devolucion}",$datosContrato->hora_devolucion,$contrato);
    $contrato= str_replace("{nombre_completo_duenio}",utf8_decode($datosContrato->nombre_duenio),$contrato);
    $contrato= str_replace("{rut_duenio}",$datosContrato->rut_duenio,$contrato);
    $contrato= str_replace("{comuna_duenio}",utf8_decode($datosContrato->comuna_duenio),$contrato);
    $contrato= str_replace("{domicilio_duenio}",utf8_decode($datosContrato->domicilio_duenio),$contrato);
    $contrato= str_replace("{nombre_usuario}",utf8_decode($datosContrato->nombre_arrendatario),$contrato);
    $contrato= str_replace("{rut_arrendatario}",utf8_decode($datosContrato->rut_arrendatario),$contrato);
    $contrato= str_replace("{domicilio_arrendatario}",utf8_decode($datosContrato->domicilio_arrendatario),$contrato);
    $contrato= str_replace("{comuna_arrendatario}",utf8_decode($datosContrato->comuna_arrendatario),$contrato);
    $contrato= str_replace("{marca_auto}",utf8_decode($datosContrato->marca_auto),$contrato);
    $contrato= str_replace("{modelo_auto}",utf8_decode($datosContrato->modelo_auto),$contrato);
    $contrato= str_replace("{anio_auto}",utf8_decode($datosContrato->anio_auto),$contrato);
    $contrato= str_replace("{patente_auto}",utf8_decode($datosContrato->patente_auto),$contrato);
    $contrato= str_replace("{duracion_arriendo}",utf8_decode($datosContrato->duracion_arriendo),$contrato);
    $contrato= str_replace("{inicio}",$datosContrato->fecha_arriendo." a las ".$datosContrato->hora_arriendo,$contrato);
    $contrato= str_replace("{precio}",$datosContrato->precio_arriendo,$contrato);
    
    //Creamos la instancia de la libreria mPDF
    //require sfConfig::get("sf_app_lib_dir")."/mpdf53/mpdf.php";
    
//    $this->getResponse()->setContentType("application/pdf");
    $pdf= new \mPDF();
    $pdf->WriteHTML(utf8_encode($contrato));
    return $pdf->Output("contrato.pdf" ,"S");
  }
  
  public function generarReporte($idCar, $iDamages = false)
  {
      //require sfConfig::get("sf_app_lib_dir")."/mpdf53/mpdf.php";
    $pdf= new \mPDF();

    //Generamos el HTML correspondiente
    //$request->setParameter("idAuto",$request->getParameter("idAuto"));
    //$html=$this->getController()->getPresentationFor("main","informeDanios");
    $car = Doctrine_Core::getTable("car")->findOneById($idCar);    
    $user= Doctrine_Core::getTable("user")->findOneByID($car->getUserId());
    $damages= Doctrine_Core::getTable("damage")->findByCarId($car->getId());
    $paginas= ceil(count($damages)/2);
    $paginasDanios= $paginas;
    $propietario= $user->getFirstname()." ".$user->getLastName();
    $telefono= $user->getTelephone();
    $direccion=$car->getAddress();
    $foto="http://cdn1.arriendas.cl/uploads/cars/".$car->getFoto();
    $agno=$car->getYear();
    $precioHora=$car->getPricePerHour();
    $precioDia=$car->getPricePerDay();
    $marcaAuto= $car->getMarcaModelo();
//    $fotoFrontal= "../uploads/vistas_seguro/".$car->getSeguroFotoFrente();
//    $fotoCostadoIzquierdo= "../uploads/vistas_seguro/".$car->getSeguroFotoCostadoIzquierdo();
//    $fotoCostadoDerecho= "../uploads/vistas_seguro/".$car->getSeguroFotoCostadoDerecho();
//    $fotoTrasera= "../uploads/vistas_seguro/".$car->getSeguroFotoTraseroDerecho();
//    $fotoPanel= "../uploads/vistas_seguro/".$car->getSeguroFotoTraseroIzquierdo();    
    $danios= $damages;

    $html = '<html>
<head>
	
<style>
	
.contenidoInformeDanios{
    background-color: #00AEEF;
    margin: 0px auto;
    width: 1020px; /*tamanio A4*/
    border-color: black;
    border-width: 1px;
    border-style: solid;
}
.contenidoInformeDanios2{
    background-color: white;
    text-align: center;
    margin: 0px auto;
    width: 1020px;
    margin-top: 10px;
}

h1 i{
    font-size: 22px;
    color: white;
    background-color: #EC008C;
    padding-top: 4px;
    padding-bottom: 4px;
    padding-right: 2px;
    padding-left: 2px;
}

p{
    font-size: 18px;
    line-height: 1.5em;
}

/************************************** SEGUNDA PÁGINA **********************************/

.logo_arriendas {
    margin: auto;
    margin-top: 100px;
    width: 487px;
    height: 178px;
    
}

.logo_informe {
    margin: auto;
    margin-top: 240px;
    width: 487px;
    height: 94px;
}
.logo_informe .contenido{
    font-size: 23px;
    color: white;
    padding-top: 20px;
    padding-left: 10px;
    font-weight: bold;
    margin: auto;
}
/************************************** SEGUNDA PÁGINA **********************************/

.logo_importante {
    margin: auto;
    margin-top: 20px;
    width: 326px;
    height: 122px;
}
/*
.logo_importante .contenido{
    font-size: 30px;
    color: #00AEEF;
    padding-top: 25px;
    font-weight: bold;
    margin: auto;
}
*/
/************************************** TERCERA PÁGINA **********************************/
.logo_FichaAuto {
    margin: auto;
    margin-top: 20px;
    width: 316px;
    height: 116px;
}
.parrafo{
    line-height: 0.1em;
    color: gray;
}

/*
.logo_FichaAuto .contenido{
    font-size: 30px;
    color: #00AEEF;
    padding-top: 20px;
    font-weight: bold;
    margin: auto;
}
*/
.auto{
    float: left;
    margin-left: 60px;
    margin-top: 20px;
    width: 279px;
    height: 279px;
    border-style: solid;
    border-width: 2px;
    border-color: black;
}

.contenidoFichaAuto{
    float: right;
    margin-right: 30px;
    margin-top: 20px;
    width: 600px;
    height: 800px;
}
.contenidoFichaAuto h1{
    font-size: 24px;
    color: #00AEEF;
    text-align: left;
}
.contenidoFichaAuto p{
    font-weight: bold;
}

.etiquetaFichaDuenio{
    float: left;
    margin-top: 10px;
    background-color: #E6E7E8;
    height: 35px;
    width: 30%;
}
.etiquetaFichaDuenio h1{
    text-align: left;
    font-size: 20px;
    color: #EC008C;
}
.triangulo {
    margin-top: 10px;
    float: left;
    width: 0;
    height: 0;
    border-bottom: 35px solid #E6E7E8; 
    border-right: 35px solid transparent;          
}

.fichaDuenio{
    float: left;
    padding-left: 8px;
    text-align: left;
    margin-top: 2px;
    background-color: #E6E7E8;
    height: 200px;
    width: 100%;
}
.fichaDuenio span{
    font-weight: bold;
    font-size: 20px;
    color: #00AEEF;
    padding-top: 10px;
}
.fichaDuenio p{
    padding-top: 20px;
}

.detalles{
    float: left;
    margin-top: 20px;
    text-align: left;
    padding: 10px;
}
.detalles span.celeste{
    font-weight: bold;
    font-size: 20px;
    color: #00AEEF;
    margin-left: 12px;
}
.detalles span.rosado{
    font-weight: bold;
    font-size: 20px;
    color: #EC008C;
    margin-left: 12px;
}
/************************************** CUARTA PÁGINA **********************************/
.encabezadoPagina{
    padding-top: 10px;
    width: 100%;
    height: 60px;
    color: white;
    background-color: #00AEEF;
}
.encabezadoPagina span{
    font-size: 30px;
}

#contenidoDaniosAuto{
    text-align: left;
}
#contenidoDaniosAuto h2{
    font-weight: bold;
    font-size: 20px;
    color: #00AEEF;
    margin-left: 12px;
}

.columnaIzquierda{
    /*background-color: blue;*/
    height: 540px;
    width: 500px;
    float: left;
}

.columnaDerecha{
    /*background-color: red;*/
    height: 470px;
    width: 500px;
    float: left;

    padding-top: 70px;
}

.etiquetaDanio{
    color: #EC008C;
    font-weight: bold;
    font-size: 22px;
    width: 120px;
    height: 50px;
    margin-left: 50px;
    margin-top: 20px;
}

.texto{
    margin-top: 5px;
    float: right;
}

.zonaDelDanio{
    float: left;
    width: 300px;
    height: 190px;
    margin-top: 10px;
    margin-left: 20px;
}
.zonaDelDanio div{
    margin: 10px;
    width: 300px;
    height: 220px;
    border-style: solid;
    border-width: 2px;
    border-color: black;
    overflow: hidden;
    padding: 5px;
}

.imagenDelDanio{
    float: left;
    width: 400px;
    height: 300px;
    margin-top: 10px;
    margin-left: 60px;
}
.imagenDelDanio div{
    margin: 10px;
    width: 370px;
    height: 250px;
    overflow: hidden;
}

.descripcionDelDanio{
    float: left;
    width: 400px;
    height: 150px;
    margin-top: 20px;
    margin-left: 80px;
    font-family: Verdana;
}
.descripcionDelDanio textarea{
    font-family: Verdana;
    margin-top: 10px;
    margin-left: 4px;
    resize:none;
    font-size: 14px;
    padding: 5px;
}
.separacion{
    float: left;
    margin: auto;
    /*background-color: green;*/
    margin-left: 120px;
}
	
</style>
	
</head>
<body>
<div style="background-color: #00AEEF; text-align: center; width: 1020px; height: 100%; padding-top: 250px;">
	<div class="logo_arriendas"><img src="images/img_informeDanios/Logo.png" "width=486" "height=176"/></div>
	<div class="logo_informe"><img src="images/img_informeDanios/LinPortada.png" "width=486" "height=92"/>
		<div style="width:100%; color: white; font-family: "Arial"; font-size: 36px; margin-top: -70px;">INFORME DE TU ARRIENDO</div>
	</div>
</div>
<div style="background-color: white; text-align: center; margin: 0px auto; width: 1020px; margin-top: 10px;">
	<div style="margin: auto; margin-top: 20px; width: 326px; height: 122px;">
	<img src="images/img_informeDanios/LinImportante.png" "width=324" "height=120"/>
	<div style="color: #00AEEF; font-family: Arial;font-size: 50px; margin-top: -100px;">IMPORTANTE</div>
	</div>
	<br></br>
	<span style="font-weight: bold; font-size: 22px;color: white;background-color: #EC008C;padding-top: 4px;padding-bottom: 4px;padding-right: 2px;padding-left: 2px;">ACCESO AL AUTO</span>
	<p style="font-size: 18px;line-height: 1.5em;"><i>De existir una diferencia entre los da&ntilde;os aqu&iacute; listados y el estado del auto, <br><b>NO DE COMIENZO AL ARRIENDO</b>.</i></p>
	
	<br></br><br></br>
	<h1><b><i>ACCIDENTES O DA&Ntilde;OS DURANTE EL ARRIENDO<i></b></h1>
	<p>De ocurrir un accidente durante el arriendo,<br>debe dirigirse a la <b>oficina de carabineros</b> m&aacute;s cercana, en el acto.</p>

	<br></br><br></br>
	<h1><b><i>SU RESPONSABILIDAD COMO ARRENDATARIO<i></b></h1>
	<p>Los <b>daños por bajo del deducible</b> (5UF) son cubiertos por el conductor.</p>

	<br></br><br></br>
	<h1><b><i>DEVOLUCI&Oacute;N TARD&Iacute;A<i></b></h1>
	<p>Si Ud. se atrasa en la devoluci&oacute;n del veh&iacute;culo en un plazo igual o mayor a 30 minutos,<b>dispondr&aacute; de 24 horas para cancelar</b> el saldo generado.<br>En caso de excederse en el plazo,  Ud. deber&aacute; cancelar un valor equivalente al<br>triple del tiempo excedido de la reserva original.</p>

	<br></br>
	<!--
	<h1><b><i> PERÍODO DE ARRIENDO <i></b></h1>
	<p>En caso que por cualquier motivo el período de arriendo del vehículo exceda el plazo pactado<br>a través del sistema, debe <b>informar a Arriendas.cl llamandonos al 228974747</b> a la brevedad.<br>Recuerde que la póliza de seguro <b>sólo cubre accidentes durante el período de tiempo</b> declarado<br>como de uso por parte del arrendatario.</p>
	-->
</div>
<div style="background-color: white;text-align: center;margin: 0px auto;width: 1020px;margin-top: 10px;">
	<div style="margin: auto;margin-top: 20px;width: 316px;height: 116px;">
	<img src="images/img_informeDanios/LinFichaAuto.png" "width=314" "height=114"/>
	<div style="font-size:40px; color: #00AEEF; font-family: Arial; margin-top: -90px;">FICHA AUTO</div>
	</div>
	<br></br>
	';
		
		$zona = "aquí va la zona del daño";
		$imagen = "aquí va la imagen del daño";
		$descripcion = "Trizadura parachoque delantero";

		function iniciarCURL(){
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, "http://admin.arriendas.cl/api.php/autos/obtenerDatosAuto");
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			$output = curl_exec($ch);
			$auto = json_decode($output);
			curl_close($ch);
			return $auto;
		}
		$auto = iniciarCURL();
	$html .= '
	<div style="float: left;margin-left: 60px;margin-top: 20px;width: 239px;height: 239px;border-style: solid;border-width: 2px;border-color: black;"><img src="'.$foto.'" "width=220" "height=220"/>
	<div style="margin-top: 50px; text-align: left;">
		<!-- Espacio para las fotos del seguro (si aplica) -->
	</div>
	</div>
	<div style="float: right; margin-right: 30px; margin-top: 20px; width: 300px; height: 800px; font-size: 10px;">
		<h1 style="font-size: 24px;color: #00AEEF;text-align: left;">'.$marcaAuto.'</h1>
		<div style="float: left;margin-top: 10px;background-color: #E6E7E8;height: 35px;">
			<h1 style="text-align: left;font-size: 20px;color: #EC008C; padding-left: 20px;">
				FICHA DUEÑO</h1></div>
		<div class="fichaDuenio">
			<p style="font-size: 14px;"><span style="font-size: 14px;">Dueño(a) |</span> '.$propietario.'</p>
			<p style="font-size: 14px;"><span style="font-size: 14px;">Fono |</span> '.$telefono.'</p>
			<p style="font-size: 14px;"><span style="font-size: 14px;">Ubicación |</span> '.$direccion.'</p>
		</div>
		<div class="detalles">
			<span class="celeste" style="font-size: 14px;">Precio</span>
			<p class="parrafo" style="font-size: 10px;">- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -</p>
			<p style="font-size: 10px;">Precio por hora | <span class="rosado" style="font-size: 10px;">$ '.number_format($precioHora,0,",",".").'</span></p>
			<p style="font-size: 10px;">Precio por día | <span class="rosado" style="font-size: 10px;">$ '.number_format($precioDia,0,",",".").'</span></p>
		</div>
		<div class="detalles">
			<span class="celeste" style="font-size: 14px;">Descripción</span>
			<p class="parrafo" style="font-size: 10px;">- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -</p>
			<p style="font-size: 10px;">Año | '.$agno.'</p>
			<!--<p>Kilómetros | '.$auto->kilometraje.' km</p>-->
		</div>
		<div class="detalles">
			<span class="celeste" style="font-size: 14px;">Breve descripción del auto</span>
			<p style="font-size: 10px;" class="parrafo">- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -</p>
			<p style="font-size: 10px;"></p>
		</div>
	</div>
</div>';
if($iDamages)
{
    $html .= '<pagebreak />';
    for($j=0;$j<=$paginasDanios-1;$j++): 
    $html .= '
    <div>
            <div>
                    ';
    for($i=$j*2;$i<=min(count($danios)-1,$j*2+1);$i++):
            $html .= '
                    <div>
                            <div class="circulo">
                                    <p>'.($i+1).'</p>
                            </div>
                    <div style="text-align: left; font-size: 20px; color: #EC008C;"><strong>DAÑOS</strong></div>
                    </div>
                    <div>
                            <div style="float:left;" class="zonaDelDanio">
                                    <h2 style="color: #00AEEF;">> Zona del Daño</h2>
                                    <div>
                                            '.str_replace(".png","",image_tag(url_for("main/generarMapaDanio?idDanio=".$danios[$i]["id"],true),array("id"=>"fotoDanio","width"=>"300","height"=>"200"))).'

                                    </div>
                            </div>
                            <div class="imagenDelDanio" style="float:left; position: absolute; width: 300px; height: 300px;">
                                    <h2 style="color: #00AEEF;">> Imagen del Daño</h2>
                                    <div class="imagen">
                                    <img src="http://www.arriendas.cl/uploads/damages/'.$danios[$i]->getUrlFoto().'" width="300px"/>
                                    </div>
                            </div>
                    </div>
                    <div>
                            <h2 style="color: #00AEEF;">> Descripción del Daño</h2>
                            <textarea name="descripcion" id="descripcion" cols="40" rows="4">'.$danios[$i]->getDescription().'
                            </textarea>
                    </div>
                    <pagebreak />';
    endfor;
    $html .= '
            </div>


    </div>
    ';
    endfor; 
  }
$html  .='
</body>
</html>
';
//    $request->setParameter("idAuto",$idAuto);
//    $html=$controller->getPresentationFor("main","informeDanios");
    $pdf->WriteHTML($html,0);
    return $pdf->Output("reporte.pdf" ,"S");
  }

  public function generarFormulario($idReserve, $tokenReserve)
  {
      //require sfConfig::get("sf_app_lib_dir")."/mpdf53/mpdf.php";
    $pdf= new \mPDF();


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
    $fechaEntrega = date("d/m/y",$entrega);
    $horaEntrega = date("H:i",$entrega);

    $fechaDevolucion = date("d/m/y",$entrega+($duracion*60*60));
    $horaDevolucion = date("H:i",$entrega+($duracion*60*60));

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

    $html = '
<html>
<head>
<style type="text/css">

body{
	font-size: 10px;
	font-family: arial;
}

#contorno{
	width: 680px;
	height: 915px;
	border: 1px solid #585858;
	background-color: white;
}

h1 {
    float: left;
    background-color: black;
    color: white;
    font-size: 19px;
    margin: 0;
    padding: 2px;
    text-align: center;
    width: 676px;
    margin-bottom: 5px;
}

h2 {
    background-color: #D8D8D8;
    float: left;
    font-size: 13px;
    font-style: italic;
    margin-bottom: 5px;
    margin-left: 5px;
    margin-top: 5px;
    padding-bottom: 3px;
    padding-top: 3px;
    width: 670px;
}

p{
	font-size: 10px;
	margin: 0;
}


#datosPersonales {
    float: left;
    padding: 5px;
    width: 480px;
}

#contactoEmergencia {
    border-left: 1px solid black;
    float: right;
    padding: 5px;
    text-align: center;
    width: 175px;
}

.kilometros {
    float: left;
    font-weight: bold;
    padding: 10px;
    text-align: center;
    width: 190px;
}

.fecha {
    float: left;
    font-weight: bold;
    padding: 10px;
    text-align: center;
    width: 100px;
}

.horaEntrega {
    float: left;
    font-weight: bold;
    padding: 10px;
    text-align: center;
    width: 120px;
}

.duracion {
    float: right;
    font-weight: bold;
    padding: 10px;
    text-align: center;
    width: 185px;
}

.horaEntregaDevolucion {
    float: left;
    font-weight: bold;
    padding: 10px;
    text-align: center;
    width: 135px;
}

#imgIzq{
	/*background-color: green;*/
	float: left;
	padding: 20px;
	width: 480px;
}

#descripcionDanioDer {
    float: right;
    padding: 0 5px;
    width: 670px;
}

#descripcionDanio{
	background-color: silver;
	float: left;
	height: 75px;
	width: 980px;
	padding: 20px;
}

#declaracion1, #declaracion2 {
    background-color: #D8D8D8;
    float: left;
    padding: 5px;
    width: 670px;
    margin-top: 5px;
}

#horaLlegada, #horaLlegadaDevolucion{
	width: 100px;
}

.horaLlegada{
    float: right;
    font-weight: bold;
    padding: 10px;
    text-align: center;
    width: 140px;
}

.horaLlegadaDevolucion{
    float: right;
    font-weight: bold;
    padding: 10px;
    padding-right: 5px;
    text-align: center;
    width: 190px;
}

#contacto {
    border: 3px solid black;
    float: left;
    margin-left: 5px;
    margin-top: 5px;
    padding: 4px;
    text-align: center;
    width: 655px;
    margin-bottom: 5px;
}

#contacto p{
	font-size: 13px;
}

.tituloEmergencia{
	font-style: italic;
}

.textoEmergencia{
	font-weight: bold;
	margin-top: 5px;
}

.textoSinEstilo{
	font-family: arial;
	font-style: normal;
	font-weight: normal;
	color: black;
	font-size: 15px;
}

#kmEntrega{
	width: 150px;
}


.textoH2{
	font-weight: normal;
}

#declaracion1 p, #declaracion2 p {
    font-size: 13px;
    font-style: italic;
}

.firma{
    float: left;
    height: 30px;
    text-align: center;
    width: 210px;
    padding-top: 5px;
}

.imgAuto {
    float: left;
    height: 250px;
    margin-right: 5px;
    width: 295px;
    background-color: #FAFAFA;
}

.textoNormal{
	font-weight: normal;
	margin-left: 10px;
}

.tituloDanios {
    float: right;
    font-size: 11px;
    font-weight: bold;
    margin-bottom: 1px;
    text-align: center;
    width: 365px;
}

.numeroDanio{
	font-weight: bold;
}

.kilometrosDevolucion {
    float: left;
    font-weight: bold;
    padding: 10px;
    padding-left: 5px;
    text-align: center;
    width: 165px;
}

.fechaDevolucion {
    float: left;
    font-weight: bold;
    padding: 10px;
    text-align: center;
    width: 110px;
}

.documentosPropietario {
    border: 1px solid black;
    float: left;
    height: 95px;
    margin: 0 5px 5px;
    padding: 5px;
    width: 320px;
}

.documentosArrendatario {
    border: 1px solid black;
    float: right;
    height: 95px;
    margin: 0 5px 5px;
    padding: 5px;
    width: 316px;
}

.documentosPropietario p, .documentosArrendatario p{
    font-style: italic;
}

.documentosPropietario .firma, .documentosArrendatario .firma{
    width: 317px;
}

.documentosPropietario .firmaBotones, .documentosArrendatario .firmaBotones{
    margin-right: 20px;
}

.lineaFirma{
	border-top: 1px solid black;
    margin: auto;
    padding-top: 5px;
    width: 200px;
}

.textoFirma{
	height: 20px;
}

.declaracion{
	height: 55px;
}

#declaracion1 {
    height: 95px;
}

#declaracion2{
	height: 90px;
}

#declaracion1 p, #declaracion2 p{
	color: black;
}

#nombrePropietarioDocumentos, #nombreArrendatarioDocumentos, #nombreArrendatarioDanios, #nombrePropietarioDevolucion{
	display: none;
}

.textoDanios {
    /*background-image: url("/frontend/web/images/lineaTexto.png");
    background-repeat: repeat;*/
    float: right;
    min-height: 235px;
    width: 365px;
}

#declaracion1 .firma, #declaracion2 .firma {
    width: 670px;
    margin-top: 15px;
}
</style>

</head>
<body>

<div id="contorno">
	<h1>FORMULARIO DE ENTREGA</h1>
	<div id="datosPersonales">
		<p>Due&ntilde;o de auto: '.$propietario["nombreCompleto"].', '.$propietario["rut"].', '.$propietario["direccion"].', '.$propietario["comuna"].', Fono: '.$propietario["telefono"].'</p>
		<br>
		<p>Arrendatario: '.$arrendador["nombreCompleto"].', '.$arrendador["rut"].', '.$arrendador["direccion"].', '.$arrendador["comuna"].', Fono: '.$arrendador["telefono"].'</p>
	</div>
	<div id="contactoEmergencia">
		<p class="tituloEmergencia">Contacto de emergencia</p>
		<!-- <p class="textoEmergencia">Auxilia: +56 2 27976107</p> -->
		<p class="textoEmergencia">Arriendas.cl: +56 02 2 333 3714</p>
	</div>

	<h2>Documentos necesarios para dar inicio al arriendo</h2>
	<div class="documentosPropietario">

		<p class="declaracion">Declaro que se ha presentado <b>'.$arrendador["nombreCompleto"].'</b> a dar inicio al arriendo con su licencia de conducir n&uacute;mero <b>'.$arrendador["rut"].'</b> y c&eacute;dula de identidad n&uacute;mero <b>'.$arrendador["rut"].'</b>.</p>
		<div class="firma">
			<p class="textoFirma"><span id="nombrePropietarioDocumentos">'.$propietario["nombreCompleto"].'</span></p>
			<p class="lineaFirma">'.$propietario["nombreCompleto"].' o Encargado</p>
		</div>

	</div>
	<div class="documentosArrendatario">
		<p class="declaracion">Declaro que el veh&iacute;culo es entregado con su <b>Permiso de circulaci&oacute;n</b>, <b>SOAP</b>, <b>Revisi&oacute;n t&eacute;cnica</b> y <b>Padr&oacute;n</b>.</p>
		<div class="firma">
			<p class="textoFirma"><span id="nombreArrendatarioDocumentos">'.$arrendador["nombreCompleto"].'</span></p>
			<p class="lineaFirma">'.$arrendador["nombreCompleto"].'</p>
		</div>
	</div>

	<h2>Datos entrega del veh&iacute;culo <span class="textoSinEstilo">('.$car["marca"].' '.$car["modelo"].', '.$car["patente"].')</span></h2>
	<div class="kilometros">Kil&oacute;metros <span class="textoNormal">___________________</span></div>
	<div class="fecha">Fecha <span class="textoNormal">'.$fechaEntrega.'</span></div>
	<div class="horaEntrega">Hora de entrega <span class="textoNormal">'.$horaEntrega.'</span></div>
    <div class="duracion">Duraci&oacute;n <span class="textoNormal">'.$car["duracion"].'</span></div>

	<h2 class="textoH2">Da&ntilde;os</h2>

	<div id="descripcionDanioDer">
		<div class="imgAuto">
			<img src="http://www.arriendas.cl/main/generarDaniosAuto?idAuto='.$car["id"].'" width="295px" height="250px">
		</div>
		<p class="tituloDanios">Descripci&oacute;n de da&ntilde;os:</p>
		<div class="textoDanios">';
		
			if($descripcionDanios){
				$html .= "<p>";
				foreach ($descripcionDanios as $i => $danio) {
					$html .= '<span class="numeroDanio"> '.($i+1).'</span>: '.$danio;
				}
			$html .= "</p>";
			}else{
				$html .= "<p>El veh&iacute;culo no presenta da&ntilde;os</p>";
			}
		
		$html .= '</div>
	</div>

	<div id="declaracion1">
		<p>1.- Declaro que el auto ha sido entregado SIN da&ntilde;os adicionales en su exterior y tapiz, a los listados arriba.</p>
        <p>2.- Las luces, luces intermitentes, limpiaparabrisas y control crucero (si lo tuviese) funcionan correctamente.</p>
        <p>3.- El auto cuenta con rueda de repuesto, kit de seguridad y los siguientes accesorios: ______________________</p>
		<div class="firma">
			<p class="textoFirma"><span id="nombreArrendatarioDanios">'.$arrendador["nombreCompleto"].'</span></p>
			<p class="lineaFirma">'.$arrendador["nombreCompleto"].'</p>
		</div>
	</div>

	<h1>FORMULARIO DE DEVOLUCI&Oacute;N</h1>
	<div class="kilometrosDevolucion">Kil&oacute;metros <span class="textoNormal">_______________</span></div>
	<div class="fechaDevolucion">Fecha <span class="textoNormal">'.$fechaDevolucion.'</span></div>
	<div class="horaEntregaDevolucion">Hora de entrega <span class="textoNormal">'.$horaDevolucion.'</span></div>
	<div class="horaLlegadaDevolucion">Hora de llegada <span class="textoNormal">_______________</span></div>

	<div id="declaracion2">
		<p>Declaro que el auto ha sido devuelto en las mismas condiciones en las que fue entregado. De existir algún da&ntilde;o contáctese con nosotros dentro de las pr&oacute;ximas 24 hrs. (*)</p>
		<div class="firma">
			<p class="textoFirma"><span id="nombrePropietarioDevolucion">'.$propietario["nombreCompleto"].'</span></p>
			<p class="lineaFirma">'.$propietario["nombreCompleto"].' o Encargado</p>
		</div>
	</div>

	<div id="contacto">
		<p>(*) Ante un siniestro env&iacute;a este formulario a Encomenderos 253, Oficina 12, 750166, Las Condes.</p>
	</div>

</div></body></html>';
    
    $pdf->WriteHTML($html,0);
    return $pdf->Output("formulario.pdf" ,"S");
  }
  
  
      public function generarPagare($tokenReserva) {

        $reserva = Doctrine_Core::getTable("reserve")->findOneByToken($tokenReserva);
        $transaction = Doctrine_Core::getTable("transaction")->findOneByReserveId($reserva->getId());
        
        $datosContrato = new stdClass();
        $confirmed = $transaction->getCompleted();

        $dateTimeDevolucion = date("Y-m-d H:i:s", strtotime("+" . $reserva->getDuration() . " hours", strtotime($reserva->getDate())));

        $datosContrato->fecha_arriendo = substr($reserva->getDate(), 8, 2) . "/" . substr($reserva->getDate(), 5, 2) . "/" . substr($reserva->getDate(), 0, 4);
        $datosContrato->fecha_arriendo_dia = substr($reserva->getDate(), 8, 2);
        $datosContrato->fecha_arriendo_mes = substr($reserva->getDate(), 5, 2);
        $datosContrato->fecha_arriendo_anio = substr($reserva->getDate(), 0, 4);
        $datosContrato->hora_arriendo = substr($reserva->getDate(), 11, 5);
        $datosContrato->fecha_devolucion = substr($dateTimeDevolucion, 8, 2) . "/" . substr($dateTimeDevolucion, 5, 2) . "/" . substr($dateTimeDevolucion, 0, 4);
        $datosContrato->hora_devolucion = substr($dateTimeDevolucion, 11, 5);
        $datosContrato->duracion_arriendo = $reserva->getDuration() . " horas";
        $datosContrato->precio_arriendo = "$" . number_format($reserva->getPrice(), 0, ",", ".");

        //datos del auto
        $auto = CarTable::getInstance()->findOneById($reserva->getCarId());
        $datosContrato->patente_auto = $auto->getPatente();
        $datosContrato->anio_auto = $auto->getYear();


        //Modelo
        $modelo = ModelTable::getInstance()->findOneById($auto->getModelId());
        $datosContrato->modelo_auto = $modelo->getName();

        //Marca
        $marca = BrandTable::getInstance()->findOneById($modelo->getBrandId());
        $datosContrato->marca_auto = $marca->getName();

        //Arrendatario
        $arrendatario = UserTable::getInstance()->findOneById($reserva->getUserId());
        $datosContrato->nombre_arrendatario = $arrendatario->getFirstname() . " " . $arrendatario->getLastname();

        if (!$confirmed) {
            $datosContrato->rut_arrendatario = preg_replace("/[\w\.]/", "X", $arrendatario->getRut());
            $datosContrato->domicilio_arrendatario = preg_replace("/[\w\.]/", "X", $arrendatario->getAddress());
        } else {
            $datosContrato->rut_arrendatario = $arrendatario->getRut();
            $datosContrato->domicilio_arrendatario = $arrendatario->getAddress();
        };

        //Comuna Arrendatario
        $comuna_arr = ComunasTable::getInstance()->findOneByCodigoInterno($arrendatario->getComuna());
        $datosContrato->comuna_arrendatario = $comuna_arr->getNombre();

        
        
        //Dueno
        $duenio = UserTable::getInstance()->findOneById($auto->getUserId());
        $datosContrato->nombre_duenio = $duenio->getFirstname() . " " . $duenio->getLastname();
        
        // Region
        $datosContrato->name_region_duenio = $duenio->getRegion();

        if (!$confirmed) {
            $datosContrato->rut_duenio = preg_replace("/[\w\.]/", "X", $duenio->getRut());
            $datosContrato->domicilio_duenio = preg_replace("/[\w\.]/", "X", $duenio->getAddress());
        } else {
            $datosContrato->rut_duenio = $duenio->getRut();
            $datosContrato->domicilio_duenio = $duenio->getAddress();
        };

        //Comuna Duenio
        $comuna_due = ComunasTable::getInstance()->findOneByCodigoInterno($duenio->getComuna());
        $datosContrato->comuna_duenio = $comuna_due->getNombre();



        //Cargamos el texto del contrato desde el archivo fuente
        $contrato = file_get_contents(sfConfig::get("sf_app_template_dir") . "/pagare.html");


        //Reemplazamos los datos por la informacion del contrato
        $contrato = str_replace("{name_region_duenio}", $datosContrato->name_region_duenio, $contrato);
        $contrato = str_replace("{nombre_completo_duenio}", utf8_decode($datosContrato->nombre_duenio), $contrato);
        $contrato = str_replace("{rut_duenio}", $datosContrato->rut_duenio, $contrato);
        $contrato = str_replace("{comuna_duenio}", utf8_decode($datosContrato->comuna_duenio), $contrato);
        $contrato = str_replace("{domicilio_duenio}", utf8_decode($datosContrato->domicilio_duenio), $contrato);
        $contrato = str_replace("{nombre_usuario}", utf8_decode($datosContrato->nombre_arrendatario), $contrato);
        $contrato = str_replace("{rut_arrendatario}", utf8_decode($datosContrato->rut_arrendatario), $contrato);
        $contrato = str_replace("{domicilio_arrendatario}", utf8_decode($datosContrato->domicilio_arrendatario), $contrato);
        $contrato = str_replace("{comuna_arrendatario}", utf8_decode($datosContrato->comuna_arrendatario), $contrato);
        $contrato = str_replace("{marca_auto}", utf8_decode($datosContrato->marca_auto), $contrato);
        $contrato = str_replace("{modelo_auto}", utf8_decode($datosContrato->modelo_auto), $contrato);
        $contrato = str_replace("{anio_auto}", utf8_decode($datosContrato->anio_auto), $contrato);
        $contrato = str_replace("{patente_auto}", utf8_decode($datosContrato->patente_auto), $contrato);
        $contrato = str_replace("{fecha_arriendo_dia}", utf8_decode($datosContrato->fecha_arriendo_dia), $contrato);
        $contrato = str_replace("{fecha_arriendo_mes}", utf8_decode($datosContrato->fecha_arriendo_mes), $contrato);
        $contrato = str_replace("{fecha_arriendo_anio}", utf8_decode($datosContrato->fecha_arriendo_anio), $contrato);
        $contrato = str_replace("{duracion_arriendo}", utf8_decode($datosContrato->duracion_arriendo), $contrato);
        $contrato = str_replace("{inicio}", $datosContrato->fecha_arriendo . " a las " . $datosContrato->hora_arriendo, $contrato);
        $contrato = str_replace("{precio}", $datosContrato->precio_arriendo, $contrato);

        $pdf = new \mPDF();
        $pdf->WriteHTML(utf8_encode($contrato));
        return $pdf->Output("contrato.pdf", "S");
    }
    
    /** 
     * generación de nro. de factura
     * en tablas Reserve y Transaction
     * @param type $reserve
     * @param type $order
     */
    public function generarNroFactura($reserve, $order){
        
        // primero valido que no exista otra factura ya generada 
        // para la misma quincena y mismo user_id

        $first_day = date('Ym01');
        $actual_day = date('j');
        $day_15 = date('Ymd', strtotime($first_day. ' + 14 days'));
        if($actual_day <= 15){
            // primera quincena
            $quincena_from = $first_day;
            $quincena_to = $day_15;
        }else{
            // segunda quincena
            $quincena_from = $day_15;
            $quincena_to = date('Ymt', strtotime($quincena_from));  //t returns the number of days in the month of a given date
        }

        $rangeDates = array($quincena_from, $quincena_to);
        $trans_query = Doctrine_Query::create()
            ->from('Transaction t')
            ->leftJoin('t.Reserve r')
            ->leftJoin('r.Car c')
            ->where('c.user_id = ?', $reserve->getIdOwner())
            ->andWhere('t.completed = ?', true)
            ->andwhere('t.date BETWEEN ? AND ?', $rangeDates);
            //->fetchOne();
        
        $cant_transac = $trans_query->count();

        $conn = Doctrine_Manager::getInstance()->getCurrentConnection();
        $conn->beginTransaction();
        
        $nro_fac_transaction = 0;
        if($cant_transac > 0)
        {
            // hay factura generada durante quincena actual, asigno mismo nro.
            $trans = $trans_query->fetchOne();
            $nro_fac_transaction = $trans->getNumeroFactura();
            $order->setNumeroFactura($nro_fac_transaction);
        }else{                    
            // no hay factura generada durante quincena actual, asigno el siguiente nro.
            // (debo obtener el mayor nro. asignado desde ambas tablas)
            try{
                // max en transaction
                $qt = "select max(numero_factura) nro_fac from Transaction";
                $query1 = Doctrine_Query::create()->query($qt);
                $trans = $query1->toArray();
                
                // max en reserve
                $qr = "select max(numero_factura) nro_fac from Reserve";
                $query2 = Doctrine_Query::create()->query($qr);
                $res = $query2->toArray();
                
                $nro_fac_transaction = ($trans[0]['nro_fac'] > $res[0]['nro_fac']? $trans[0]['nro_fac'] : $res[0]['nro_fac']) + 1;
                $order->setNumeroFactura($nro_fac_transaction);

            } catch (Exception $ex) {
                echo $ex->getMessage();
            }
        }
        $order->save();
        
        $montoLiberacion = $reserve->getMontoLiberacion();
        $montoGarantia = sfConfig::get('deposito_garantia');
        if($montoLiberacion != $montoGarantia)
        {
            $nro_fac_reserve = $cant_transac > 0? $cant_transac + 2: $nro_fac_transaction + 1;
            $reserve->setNumeroFactura($nro_fac_reserve);
            $reserve->save();
        }                    

        //Resolvemos la transaccoin
        $conn->commit();
    }

}
?>
