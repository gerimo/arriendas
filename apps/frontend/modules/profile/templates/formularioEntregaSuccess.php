
<?php use_stylesheet('formEntrega.css') ?>
<?php use_javascript('formEntregaDevolucion.js') ?>

<script type="text/javascript">
	var urlFormularioEntregaAjax = <?php echo "'".url_for("profile/formularioEntregaAjax")."';" ?>
	var idReserve = <?php echo "'".$idReserve."';" ?>
	var isPropietario = <?php echo "'".$car['propietario']."';" ?>
	var redireccion = <?php echo "'".url_for('profile/cars')."';" ?>

	var cargarHora = <?php if($reserva['horaDevolucion']!=0){ echo "false;"; }else{ echo "true;";} ?>
</script>

<div id="contorno">
	<h1>FORMULARIO DE ENTREGA Y DEVOLUCI&Oacute;N</h1>
	<div id="datosPersonales">
		<p>Due&ntilde;o de auto: <?php echo $propietario['nombreCompleto'].", ".$propietario['rut'].", ".$propietario['direccion'].", ".$propietario['comuna'].", Fono: ".$propietario['telefono']; ?></p>
		<br>
		<p>Arrendatario: <?php echo $arrendador['nombreCompleto'].", ".$arrendador['rut'].", ".$arrendador['direccion'].", ".$arrendador['comuna'].", Fono: ".$arrendador['telefono']; ?></p>
	</div>
	<div id="contactoEmergencia">
		<p class="tituloEmergencia">Contactos de emergencia</p>
		<p class="textoEmergencia">Auxilia: +56 2 27976107</p>
		<p class="textoEmergencia">Arriendas.cl: +56 2 28794949</p>
	</div>

	<h2>Documentos necesarios para dar inicio al arriendo</h2>
	<div class="documentosPropietario <?php if($car['propietario'] && !$reserva['declaracionDocPropietario']){echo "remarcado";} ?>">

		<p class="declaracion"><?php if($car['propietario'] && !$reserva['declaracionDocPropietario']){echo "<span class='textoRemarcado'>(1) </span>";} ?>Declaro que se ha presentado <b><?php echo $arrendador['nombreCompleto']; ?></b> a dar inicio al arriendo con su licencia de conducir n&uacute;mero <b><?php echo $arrendador['rut']; ?></b> y c&eacute;dula de identidad n&uacute;mero <b><?php echo $arrendador['rut']; ?></b>.</p>
		<div class="firma">
			<p class="textoFirma"><span id="nombrePropietarioDocumentos" class="gris"><?php if($car['propietario']){echo $propietario['nombreCompleto'];} ?></span></p>
			<p class="lineaFirma"><!--<input type="checkbox" <?php if(!$car['propietario']){echo "disabled";} ?> name="propietarioDocumentos" class="firmaCheckbox duenio" id="propietarioDocumentos"> -->Encargado o Dueño</p>
		</div>

		<div class="firmaBotones">
			<input type="button" <?php if(!$car['propietario'] || $reserva['declaracionDocPropietario']){echo "disabled";} ?> value="Confirmar" name="botonPropietarioDocumentos" id="botonPropietarioDocumentos">
		</div>

	</div>
	<div class="documentosArrendatario <?php if(!$car['propietario'] && !$reserva['declaracionDocArrendatario']){echo "remarcado";} ?>">
		<p class="declaracion"><?php if(!$car['propietario'] && !$reserva['declaracionDocArrendatario']){echo "<span class='textoRemarcado'>(1) </span>";} ?>Declaro que el veh&iacute;culo es entregado con su <b>Permiso de circulaci&oacute;n</b>, <b>SOAP</b>, <b>Revisi&oacute;n t&eacute;cnica</b> y <b>Padr&oacute;n</b>.</p>
		<div class="firma">
			<p class="textoFirma"><span id="nombreArrendatarioDocumentos" class="gris"><?php if(!$car['propietario']){echo $arrendador['nombreCompleto'];} ?></span></p>
			<p class="lineaFirma"><!--<input type="checkbox" <?php if($car['propietario']){echo "disabled";} ?> name="arrendatarioDocumentos" class="firmaCheckbox arrendatario" id="arrendatarioDocumentos"> -->Arrendatario</p>
		</div>
		<div class="firmaBotones">
			<input type="button" <?php if($car['propietario'] || $reserva['declaracionDocArrendatario']){echo "disabled";} ?> value="Confirmar" name="botonArrendatarioDocumentos" id="botonArrendatarioDocumentos">
		</div>
	</div>

	<h2>Datos entrega del veh&iacute;culo <span class="textoSinEstilo">(<?php echo $car['marca']." ".$car['modelo'].", ".$car['patente'] ?>)</span></h2>
	<div class="kilometros <?php if(!$car['propietario'] && !$reserva['kmInicial']){echo "textoRemarcado";} ?>"><?php if(!$car['propietario'] && !$reserva['kmInicial']){echo "(2) ";} ?>Kilometraje <input type="text" <?php if($car['propietario'] || $reserva['kmInicial']){echo "readonly";} ?> name="kmEntrega" id="kmEntrega" <?php if($reserva['kmInicial']){ echo "placeholder='".$reserva['kmInicial']."'";}else{echo "placeholder='Kilometraje'";} ?> ></div>
	<div class="fecha">Fecha <span id="fecha"><?php echo $fechaEntrega; ?></span></div>
	<div class="horaEntrega">Hora de entrega <span id="horaEntrega"><?php echo $horaEntrega; ?></span></div>

	<h2 class="textoH2">Da&ntilde;os</h2>

	<div id="descripcionDanioDer">
		<div class="imgAuto">
			<img src="http://www.arriendas.cl/main/generarDaniosAuto?idAuto=<?php echo $car['id']; ?>" width="440px" height="330px">
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

	<div id="declaracion1" <?php if(!$car['propietario'] && !$reserva['declaracionDanios']){echo "class='remarcado'";} ?>>
		<p><?php if(!$car['propietario'] && !$reserva['declaracionDanios']){echo "<span class='textoRemarcado'>(3) </span>";} ?>Declaro que el auto ha sido entregado sin da&ntilde;os adicionales a los listados en este formulario y en el informe. De existir diferencias no de inicio al arriendo. (*)</p>
		<!--	
		<div class="firmaDuenio">
			<p><input type="checkbox" name="propietario" class="firmaCheckbox duenio"> Encargado o Dueño</p>
		</div>
		-->
		<div class="firma">
			<p class="textoFirma"><span id="nombreArrendatarioDanios" class="gris"><?php if(!$car['propietario']){echo $arrendador['nombreCompleto'];} ?></span></p>
			<p class="lineaFirma"><!--<input type="checkbox" <?php if($car['propietario']){echo "disabled";} ?> name="arrendatarioDanios" id="arrendatarioDanios" class="firmaCheckbox arrendatario"> -->Arrendatario</p>
		</div>
		<div class="firmaBotones">
			<input type="button" <?php if($car['propietario'] || $reserva['declaracionDanios']){echo "disabled";} ?> value="Confirmar" name="botonArrendatarioDanios" id="botonArrendatarioDanios">
		</div>
	</div>

	<h2>Datos de devoluci&oacute;n (llene estos datos cuando el veh&iacute;culo sea devuelto)</h2>

	<div class="kilometrosDevolucion <?php if($car['propietario'] && !$reserva['kmFinal']){echo "textoRemarcado";} ?>"><?php if($car['propietario'] && !$reserva['kmFinal']){echo "(2) ";} ?> Kilometraje <input type="text" <?php if(!$car['propietario'] || $reserva['kmFinal']){echo "readonly";} ?> name="kmEntrega" id="kmEntregaDevolucion" <?php if($reserva['kmFinal']){echo "placeholder='".$reserva['kmFinal']."'";}else{ echo "placeholder='Kilometraje'";} ?> ></div>

	<div class="fechaDevolucion">Fecha <span id="fechaDevolucion"><?php echo $fechaDevolucion; ?></span></div>
	<div class="horaEntregaDevolucion">Hora de entrega <span id="horaEntregaDevolucion"><?php echo $horaDevolucion; ?></span></div>

	<div class="horaLlegadaDevolucion <?php if($car['propietario'] && ($reserva['horaDevolucion']==0)){echo "textoRemarcado";} ?>"><?php if($car['propietario'] && ($reserva['horaDevolucion']==0)){echo "(3) ";} ?>Hora de llegada <input type="text" <?php if(!$car['propietario'] || ($reserva['horaDevolucion']!=0)){echo "readonly";} ?> name="horaEntregaDevolucion" id="horaLlegadaDevolucion" <?php if($reserva['horaDevolucion']!=0){ echo "placeholder='".$reserva['horaDevolucion']."'";}else{echo "placeholder='HH:MM'";} ?> ></div>

	<div id="declaracion2" <?php if($car['propietario'] && !$reserva['declaracionDevolucion']){echo "class='remarcado'";} ?>>
		<p><?php if($car['propietario'] && !$reserva['declaracionDevolucion']){echo "<span class='textoRemarcado'>(4) </span>";} ?>Declaro que el auto ha sido devuelto en las mismas condiciones en las que fue entregado. (*)</p>
		<div class="firma">
			<p class="textoFirma"><span id="nombrePropietarioDevolucion" class="gris"><?php if($car['propietario']){echo $propietario['nombreCompleto'];} ?></span></p>
			<p class="lineaFirma"><!--<input type="checkbox" <?php if(!$car['propietario']){echo "disabled";} ?> name="propietarioDevolucion" id="propietarioDevolucion" class="firmaCheckbox duenio"> -->Encargado o Dueño</p>
		</div>
		<!--
		<div class="firmaArrendatario">
			<p><input type="checkbox" name="arrendatario" class="firmaCheckbox arrendatario"> Arrendatario</p>
		</div>
		-->
		<div class="firmaBotones">
			<input type="button" <?php if(!$car['propietario'] || $reserva['declaracionDevolucion']){echo "disabled";} ?> value="Confirmar" name="botonPropietarioDevolucion" id="botonPropietarioDevolucion">
		</div>
	</div>

	<div id="contacto">
		<p>(*) Si el veh&iacute;culo no fue entregado o devuelto en las condiciones estipuladas<br>p&oacute;ngase en contacto con nosotros a <b>soporte@arriendas.cl</b> o <b>+56 2 28794949</b></p>
	</div>

</div>