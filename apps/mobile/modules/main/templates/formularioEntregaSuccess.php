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
		<p>Due&ntilde;o de auto: <?php echo $propietario['nombreCompleto'].", ".$propietario['rut'].", ".$propietario['direccion'].", ".$propietario['comuna'].", Fono: ".$propietario['telefono']; ?></p>
		<br>
		<p>Arrendatario: <?php echo $arrendador['nombreCompleto'].", ".$arrendador['rut'].", ".$arrendador['direccion'].", ".$arrendador['comuna'].", Fono: ".$arrendador['telefono']; ?></p>
	</div>
	<div id="contactoEmergencia">
		<p class="tituloEmergencia">Contacto de emergencia</p>
		<!-- <p class="textoEmergencia">Auxilia: +56 2 27976107</p> -->
		<p class="textoEmergencia">Arriendas.cl: +56 02 2 333 3714</p>
	</div>

	<h2>Documentos necesarios para dar inicio al arriendo</h2>
	<div class="documentosPropietario">

		<p class="declaracion">Declaro que se ha presentado <b><?php echo $arrendador['nombreCompleto']; ?></b> a dar inicio al arriendo con su licencia de conducir n&uacute;mero <b><?php echo $arrendador['rut']; ?></b> y c&eacute;dula de identidad n&uacute;mero <b><?php echo $arrendador['rut']; ?></b>.</p>
		<div class="firma">
			<p class="textoFirma"><span id="nombrePropietarioDocumentos"><?php echo $propietario['nombreCompleto'] ?></span></p>
			<p class="lineaFirma"><?php echo $propietario['nombreCompleto'] ?> o Encargado</p>
		</div>

	</div>
	<div class="documentosArrendatario">
		<p class="declaracion">Declaro que el veh&iacute;culo es entregado con su <b>Permiso de circulaci&oacute;n</b>, <b>SOAP</b>, <b>Revisi&oacute;n t&eacute;cnica</b> y <b>Padr&oacute;n</b>.</p>
		<div class="firma">
			<p class="textoFirma"><span id="nombreArrendatarioDocumentos"><?php echo $arrendador['nombreCompleto'] ?></span></p>
			<p class="lineaFirma"><?php echo $arrendador['nombreCompleto'] ?></p>
		</div>
	</div>

	<h2>Datos entrega del veh&iacute;culo <span class="textoSinEstilo">(<?php echo $car['marca']." ".$car['modelo'].", ".$car['patente'] ?>)</span></h2>
	<div class="kilometros">Kil&oacute;metros <span class="textoNormal">___________________</span></div>
	<div class="fecha">Fecha <span class="textoNormal"><?php echo $fechaEntrega; ?></span></div>
	<div class="horaEntrega">Hora de entrega <span class="textoNormal"><?php echo $horaEntrega; ?></span></div>
    <div class="duracion">Duraci&oacute;n <span class="textoNormal"><?php echo $car['duracion']; ?></span></div>

	<h2 class="textoH2">Da&ntilde;os</h2>

	<div id="descripcionDanioDer">
		<div class="imgAuto">
			<img src="http://www.arriendas.cl/main/generarDaniosAuto?idAuto=<?php echo $car['id']; ?>" width="295px" height="250px">
		</div>
		<p class="tituloDanios">Descripci&oacute;n de da&ntilde;os:</p>
		<div class="textoDanios">
		<?php
			if($descripcionDanios){
				echo "<p>";
				foreach ($descripcionDanios as $i => $danio) {
					echo "<span class='numeroDanio'> ".($i+1)."</span>: ".$danio;
				}
			echo "</p>";
			}else{
				echo "<p>El veh&iacute;culo no presenta da&ntilde;os</p>";
			}
		?>
		</div>
	</div>

	<div id="declaracion1">
		<p>1.- Declaro que el auto ha sido entregado SIN da&ntilde;os adicionales en su exterior y tapiz, a los listados arriba.</p>
        <p>2.- Las luces, luces intermitentes, limpiaparabrisas y control crucero (si lo tuviese) funcionan correctamente.</p>
        <p>3.- El auto cuenta con rueda de repuesto, kit de seguridad y los siguientes accesorios: ______________________</p>
		<div class="firma">
			<p class="textoFirma"><span id="nombreArrendatarioDanios"><?php echo $arrendador['nombreCompleto'] ?></span></p>
			<p class="lineaFirma"><?php echo $arrendador['nombreCompleto'] ?></p>
		</div>
	</div>

	<h1>FORMULARIO DE DEVOLUCI&Oacute;N</h1>
	<div class="kilometrosDevolucion">Kil&oacute;metros <span class="textoNormal">_______________</span></div>
	<div class="fechaDevolucion">Fecha <span class="textoNormal"><?php echo $fechaDevolucion; ?></span></div>
	<div class="horaEntregaDevolucion">Hora de entrega <span class="textoNormal"><?php echo $horaDevolucion; ?></span></div>
	<div class="horaLlegadaDevolucion">Hora de llegada <span class="textoNormal">_______________</span></div>

	<div id="declaracion2">
		<p>Declaro que el auto ha sido devuelto en las mismas condiciones en las que fue entregado. De existir algún da&ntilde;o contáctese con nosotros dentro de las pr&oacute;ximas 24 hrs. (*)</p>
		<div class="firma">
			<p class="textoFirma"><span id="nombrePropietarioDevolucion"><?php echo $propietario['nombreCompleto'] ?></span></p>
			<p class="lineaFirma"><?php echo $propietario['nombreCompleto'] ?> o Encargado</p>
		</div>
	</div>

	<div id="contacto">
		<p>(*) Ante un siniestro env&iacute;a este formulario a Encomenderos 253, Oficina 12, 750166, Las Condes.</p>
	</div>

</div>