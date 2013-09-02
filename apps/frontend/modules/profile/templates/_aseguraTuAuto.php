<div id="encabezado">

	<div class="barraSuperior">
		<p>¡ASEGURA TU AUTO!</p>
	</div>
	
	<div class="subtitulo"><div class="aux">en </div> <?php echo image_tag('img_asegura_tu_auto/4_Grande.png','class=imagen4') ?> <div class="aux"> pasos</div></div>
</div>	
<div id="contenidoAseguraTuAuto">
<div id="paso1">
	<div class="ubicacionPasos"><a href='#' title="Paso 1" class="siguiente0"><?php echo image_tag('img_asegura_tu_auto/1.png') ?></a> <a href='#' title="Paso 2" class='siguiente1'><?php echo image_tag('img_asegura_tu_auto/2claro.png') ?></a> <a href='#' title="Paso 3" class='siguiente2'><?php echo image_tag('img_asegura_tu_auto/3claro.png') ?></a> <a href='#' title="Paso 4" class='siguiente3'><?php echo image_tag('img_asegura_tu_auto/4claro.png')?></a></div>
	<div class="contenido">
		<div class="etiquetaPrincipal">FOTOS DEL VEHÍCULO</div>
		<div id="contenidoFotoFrente">
<?php
	for($i=0;$i<count($partes);$i++){
?>
<div style="visibility:hidden;overflow:hidden;height:0px;">
    <form id="form_<?=$partes[$i]?>" method="post" enctype="multipart/form-data" action='<?php echo url_for('profile/uploadPhotoVerification?photo='.$partes[$i].'&width=140&height=140&file=file_'.$partes[$i].'&idCar='.$id) ?>'>
        <input type="file" name="file_<?=$partes[$i]?>" id="file_<?=$partes[$i]?>" />
        <input type="submit" />
    </form>
</div>
<div class="contenedorFoto">
	<div class="regisFoto">
	    <div id="preview_<?=$partes[$i]?>">
         	<?php if ($fotosPartes[$partes[$i]]== null): ?>
         		<?php echo image_tag('img_asegura_tu_auto/'.$partes[$i].'.png', 'size=140x140') ?>
         	<?php else: ?>
         	 	<?php echo image_tag('../uploads/verificaciones/'.$fotosPartes[$partes[$i]], 'size=140x140') ?>
         	<?php endif; ?>
	    </div>
	</div>
	<div class="regisOp">
		<div class="tituloReg">
			<p><?=$nombresPartes[$i]?></p>
		</div>
		<a href="#" id="link_<?=$partes[$i]?>" title="Agregar una foto"><?php echo image_tag('img_subi_tu_auto/upcar_ico_agrega_foto.png') ?></a>
		<span style="font-size: 12px; margin-left:10px;">Tamaño máximo foto: 1 MB</span>
	</div>
</div>
<?php
	}//fin for
?>

		</div>
	</div>
	<div class="botonesVolverSiguiente">
		<a href="#" class="siguiente1"><?php echo image_tag('img_asegura_tu_auto/Siguiente_SubeTuAuto.png') ?></a>
	</div>
</div>
<div id="paso2">
	<div class="ubicacionPasos"><a href='#' title="Paso 1" class="siguiente0"><?php echo image_tag('img_asegura_tu_auto/1claro.png') ?></a> <a href='#' title="Paso 2" class='siguiente1'><?php echo image_tag('img_asegura_tu_auto/2.png') ?></a> <a href='#' title="Paso 3" class='siguiente2'><?php echo image_tag('img_asegura_tu_auto/3claro.png') ?></a> <a href='#' title="Paso 4" class='siguiente3'><?php echo image_tag('img_asegura_tu_auto/4claro.png')?></a></div>
	<div class="contenido">
		<div class="etiquetaPrincipal">DESCRIPCIÓN DEL DAÑO</div>

		<div class="mensajeIntruccionMapaAuto">> Indica la zona del daño haciendo click sobre la imagen</div>

		<div id="map_canvas"></div>

	    <div id="ingresoDatos">
	      <p class="titulo">Descripción del daño <span id="numeroDanio"></span></p>

	    <?php echo form_tag('profile/daniosMapaAuto', array('method' => 'post', 'enctype' => 'multipart/form-data', 'id' => 'frmMapaAuto')); ?>

		      <div id="danioFoto">
		      	<h3>> Imagen del daño</h3>
		      	<div id="imagenDanio">
		      		<?php echo image_tag('img_asegura_tu_auto/Marco.png','id=marcoImg') ?>
		      		<?php echo image_tag('img_asegura_tu_auto/AutoVistaAerea.png','id=fotoDanio') ?>
		      	</div>
		      	<div id="subirDanio">
		      		<input type="file" name="foto" id="foto"/>
		      	</div>
		      </div>
		      <div id="danioDescripcion">
		      	<h3>> Descripción del daño</h3>
		      	<p><textarea name="descripcion" id="descripcion"></textarea></p>
		      </div>

		      <input type="hidden" value="" name="id" id="id">
		      <input type="hidden" value="<?php echo $id; ?>" name="idCar" id="idCar">
			  <input type="hidden" value="" name="lat" id="lat">
		      <input type="hidden" value="" name="lng" id="lng">
		      <input type="hidden" value="" name="posicionX" id="posicionX">
		      <input type="hidden" value="" name="posicionY" id="posicionY">
		      <input type="hidden" value="" name="urlfoto" id="urlfoto">
		      <input type="hidden" value="" name="opcion" id="opcion">

		      <div class="botonesMapaAuto">
			      <input type="submit" value="Confirmar" id="confirmar">
			      <input type="submit" value="Eliminar" id="eliminar">
		      </div>
		</form>

		</div>
	</div>
	<div class="botonesVolverSiguiente">
		<a href="#" class="siguiente0"><?php echo image_tag('img_asegura_tu_auto/BotonVolver.png') ?></a>
		<div class="extremoDerecho"><a href="#" class="siguiente2"><?php echo image_tag('img_asegura_tu_auto/Siguiente_SubeTuAuto.png') ?></a></div>
	</div>
</div>

<?php
	$OK = 'ok';
?>						
<?php echo form_tag('profile/aseguraTuAuto?id='.$id.'&form='.$OK, array('method' => 'post', 'enctype' => 'multipart/form-data', 'id' => 'frml')); ?>

<div id="paso3">
	<div class="ubicacionPasos"><a href='#' title="Paso 1" class="siguiente0"><?php echo image_tag('img_asegura_tu_auto/1claro.png') ?></a> <a href='#' title="Paso 2" class='siguiente1'><?php echo image_tag('img_asegura_tu_auto/2claro.png') ?></a> <a href='#' title="Paso 3" class='siguiente2'><?php echo image_tag('img_asegura_tu_auto/3.png') ?></a> <a href='#' title="Paso 4" class='siguiente3'><?php echo image_tag('img_asegura_tu_auto/4claro.png')?></a></div>
	<div class="contenido">
		<div class="etiquetaPrincipal">AUDIO</div>
		<div id="preguntaPaso3">
			<div class="pregunta">¿TIENE EL EQUIPO DE AUDIO ORIGINAL DE SU VEHÍCULO?</div>
			<div class="botonesPregunta">
				<a href="" id="a_botonSi"><div class="botonSi"></div></a>
				<a href="" id="a_botonNo"><div class="botonNo"></div></a>
			</div>
		</div>
		<div id="accesoriosDelVehiculo">
			<div class="etiquetaPrincipal">DECLARA LOS ACCESORIOS AUDIO DE TU VEHÍCULO</div>
				<div class="horizontalGris">
					<div class="tituloAccesorio">
						(1) RADIO
					</div>
					<div class="izquierdaAccesorio">
						<label>Marca</label>
						<?php if($seguroFotos[0]['radioMarca'] != null){ ?>
								<input type="text" name="marca_Radio" value="<?php echo $seguroFotos[0]['radioMarca'] ?>" style="width:160px; font-size:14px ;height:20px; background-color:#E6E7E8; border: 1px solid gray;">
						<?php }else{ ?>
								<input type="text" name="marca_Radio" style="width:160px; font-size:14px ;height:20px; background-color:#E6E7E8; border: 1px solid gray;">
						<?php }	?>
						<label>Tipo</label>
							<select name="tipo_Radio" style="width:160px; font-size:14px ;height:20px; background-color:#E6E7E8; border: 1px solid gray;">
								<?php 
									if($seguroFotos[0]['radioTipo'] != null){ 
										$opcionTipoRadio = $seguroFotos[0]['radioTipo'];
										if($opcionTipoRadio == "0"){
								?>
												<option value="0" selected>--</option><option value="radioCassette">Radio Cassette</option><option value="radioCD">Radio CD</option><option value="soloRadio">Sólo Radio</option><option value="panelDesmontable">Panel Desmontable</option>
								<?php
										}else if($opcionTipoRadio == "radioCassette"){
								?>
												<option value="0">--</option><option value="radioCassette" selected>Radio Cassette</option><option value="radioCD">Radio CD</option><option value="soloRadio">Sólo Radio</option><option value="panelDesmontable">Panel Desmontable</option>
								<?php
										}else if($opcionTipoRadio == "radioCD"){
								?>
												<option value="0">--</option><option value="radioCassette">Radio Cassette</option><option value="radioCD" selected>Radio CD</option><option value="soloRadio">Sólo Radio</option><option value="panelDesmontable">Panel Desmontable</option>
								<?php
										}else if($opcionTipoRadio == "soloRadio"){
								?>
												<option value="0">--</option><option value="radioCassette">Radio Cassette</option><option value="radioCD">Radio CD</option><option value="soloRadio" selected>Sólo Radio</option><option value="panelDesmontable">Panel Desmontable</option>
								<?php
										}else if($opcionTipoRadio == "panelDesmontable"){
								?>
												<option value="0">--</option><option value="radioCassette">Radio Cassette</option><option value="radioCD">Radio CD</option><option value="soloRadio">Sólo Radio</option><option value="panelDesmontable" selected>Panel Desmontable</option>
								<?php
										}
									}else{
								?>
										<option value="0">--</option>
										<option value="radioCassette">Radio Cassette</option>
										<option value="radioCD">Radio CD</option>
										<option value="soloRadio">Sólo Radio</option>
										<option value="panelDesmontable">Panel Desmontable</option>
								<?php 
									}	
								?>
							</select>
					</div>
					<div class="derechaAccesorio">
						<label>Modelo</label>
						<?php if($seguroFotos[0]['radioModelo'] != null){ ?>
								<input type="text" name="modelo_Radio" value="<?php echo $seguroFotos[0]['radioModelo'] ?>" style="width:160px; font-size:14px ;height:20px; background-color:#E6E7E8; border: 1px solid gray;">
						<?php }else{ ?>
								<input type="text" name="modelo_Radio" style="width:160px; font-size:14px ;height:20px; background-color:#E6E7E8; border: 1px solid gray;">
						<?php }	?>
					</div>
				</div>
				<div class="horizontalBlanca">
					<div class="tituloAccesorio">
						(2) PARLANTES
					</div>
					<div class="izquierdaAccesorio">
						<label>Marca</label>
						<?php if($seguroFotos[0]['parlantesMarca'] != null){ ?>
								<input type="text" name="marca_Parlantes" value="<?php echo $seguroFotos[0]['parlantesMarca'] ?>" style="width:160px; font-size:14px ;height:20px; background-color: white; border: 1px solid gray;">
						<?php }else{ ?>
								<input type="text" name="marca_Parlantes" style="width:160px; font-size:14px ;height:20px; background-color: white; border: 1px solid gray;">
						<?php }	?>
					</div>
					<div class="derechaAccesorio">
						<label>Modelo</label>
						<?php if($seguroFotos[0]['parlantesModelo'] != null){ ?>
								<input type="text" name="modelo_Parlantes" value="<?php echo $seguroFotos[0]['parlantesModelo'] ?>" style="width:160px; font-size:14px ;height:20px; background-color: white; border: 1px solid gray;">
						<?php }else{ ?>
								<input type="text" name="modelo_Parlantes" style="width:160px; font-size:14px ;height:20px; background-color: white; border: 1px solid gray;">
						<?php }	?>
					</div>
				</div>
				<div class="horizontalGris">
					<div class="tituloAccesorio">
						(3) TWEETERS
					</div>
					<div class="izquierdaAccesorio">
						<label>Marca</label>
						<?php if($seguroFotos[0]['tweeMarca'] != null){ ?>
								<input type="text" name="marca_Tweeters" value="<?php echo $seguroFotos[0]['tweeMarca'] ?>" style="width:160px; font-size:14px ;height:20px; background-color: #E6E7E8; border: 1px solid gray;">
						<?php }else{ ?>
								<input type="text" name="marca_Tweeters" style="width:160px; font-size:14px ;height:20px; background-color: #E6E7E8; border: 1px solid gray;">
						<?php }	?>
					</div>
					<div class="derechaAccesorio">
						<label>Modelo</label>
						<?php if($seguroFotos[0]['tweeModelo'] != null){ ?>
								<input type="text" name="modelo_Tweeters" value="<?php echo $seguroFotos[0]['tweeModelo'] ?>" style="width:160px; font-size:14px ;height:20px; background-color: #E6E7E8; border: 1px solid gray;">
						<?php }else{ ?>
								<input type="text" name="modelo_Tweeters" style="width:160px; font-size:14px ;height:20px; background-color: #E6E7E8; border: 1px solid gray;">
						<?php }	?>
					</div>
				</div>
				<div class="horizontalBlanca">
					<div class="tituloAccesorio">
						(4) ECUALIZADOR
					</div>
					<div class="izquierdaAccesorio">
						<label>Marca</label>
						<?php if($seguroFotos[0]['ecuaMarca'] != null){ ?>
								<input type="text" name="marca_Ecualizador" value="<?php echo $seguroFotos[0]['ecuaMarca'] ?>" style="width:160px; font-size:14px ;height:20px; background-color: white; border: 1px solid gray;">
						<?php }else{ ?>
								<input type="text" name="marca_Ecualizador" style="width:160px; font-size:14px ;height:20px; background-color: white; border: 1px solid gray;">
						<?php }	?>
					</div>
					<div class="derechaAccesorio">
						<label>Modelo</label>
						<?php if($seguroFotos[0]['ecuaModelo'] != null){ ?>
								<input type="text" name="modelo_Ecualizador" value="<?php echo $seguroFotos[0]['ecuaModelo'] ?>" style="width:160px; font-size:14px ;height:20px; background-color: white; border: 1px solid gray;">
						<?php }else{ ?>
								<input type="text" name="modelo_Ecualizador" style="width:160px; font-size:14px ;height:20px; background-color: white; border: 1px solid gray;">
						<?php }	?>
					</div>
				</div>
				<div class="horizontalGris">
					<div class="tituloAccesorio">
						(5) AMPLIFICADOR
					</div>
					<div class="izquierdaAccesorio">
						<label>Marca</label>
						<?php if($seguroFotos[0]['ampliMarca'] != null){ ?>
								<input type="text" name="marca_Amplificador" value="<?php echo $seguroFotos[0]['ampliMarca'] ?>" style="width:160px; font-size:14px ;height:20px; background-color: #E6E7E8; border: 1px solid gray;">
						<?php }else{ ?>
								<input type="text" name="marca_Amplificador" style="width:160px; font-size:14px ;height:20px; background-color: #E6E7E8; border: 1px solid gray;">
						<?php }	?>
					</div>
					<div class="derechaAccesorio">
						<label>Modelo</label>
						<?php if($seguroFotos[0]['ampliModelo'] != null){ ?>
								<input type="text" name="modelo_Amplificador" value="<?php echo $seguroFotos[0]['ampliModelo'] ?>" style="width:160px; font-size:14px ;height:20px; background-color: #E6E7E8; border: 1px solid gray;">
						<?php }else{ ?>
								<input type="text" name="modelo_Amplificador" style="width:160px; font-size:14px ;height:20px; background-color: #E6E7E8; border: 1px solid gray;">
						<?php }	?>
					</div>
				</div>
				<div class="horizontalBlanca">
					<div class="tituloAccesorio">
						(6) COMPACT CD
					</div>
					<div class="izquierdaAccesorio">
						<label>Marca</label>
						<?php if($seguroFotos[0]['compMarca'] != null){ ?>
								<input type="text" name="marca_CompactCD" value="<?php echo $seguroFotos[0]['compMarca'] ?>" style="width:160px; font-size:14px ;height:20px; background-color: white; border: 1px solid gray;">
						<?php }else{ ?>
								<input type="text" name="marca_CompactCD" style="width:160px; font-size:14px ;height:20px; background-color: white; border: 1px solid gray;">
						<?php }	?>
					</div>
					<div class="derechaAccesorio">
						<label>Modelo</label>
						<?php if($seguroFotos[0]['compModelo'] != null){ ?>
								<input type="text" name="modelo_CompactCD" value="<?php echo $seguroFotos[0]['compModelo'] ?>" style="width:160px; font-size:14px ;height:20px; background-color: white; border: 1px solid gray;">
						<?php }else{ ?>
								<input type="text" name="modelo_CompactCD" style="width:160px; font-size:14px ;height:20px; background-color: white; border: 1px solid gray;">
						<?php }	?>
					</div>
				</div>
				<div class="horizontalGris">
					<div class="tituloAccesorio">
						(7) SUBWOOFER
					</div>
					<div class="izquierdaAccesorio">
						<label>Marca</label>
						<?php if($seguroFotos[0]['subwMarca'] != null){ ?>
								<input type="text" name="marca_Subwoofer" value="<?php echo $seguroFotos[0]['subwMarca'] ?>" style="width:160px; font-size:14px ;height:20px; background-color: #E6E7E8; border: 1px solid gray;">
						<?php }else{ ?>
								<input type="text" name="marca_Subwoofer" style="width:160px; font-size:14px ;height:20px; background-color: #E6E7E8; border: 1px solid gray;">
						<?php }	?>
					</div>
					<div class="derechaAccesorio">
						<label>Modelo</label>
						<?php if($seguroFotos[0]['subwModelo'] != null){ ?>
								<input type="text" name="modelo_Subwoofer" value="<?php echo $seguroFotos[0]['subwModelo'] ?>" style="width:160px; font-size:14px ;height:20px; background-color: #E6E7E8; border: 1px solid gray;">
						<?php }else{ ?>
								<input type="text" name="modelo_Subwoofer" style="width:160px; font-size:14px ;height:20px; background-color: #E6E7E8; border: 1px solid gray;">
						<?php }	?>
					</div>
				</div>
				<div class="horizontalBlanca">
					<div class="tituloAccesorio">
						(8) SISTEMA DVD
					</div>
					<div class="izquierdaAccesorio">
						<label>Marca</label>
						<?php if($seguroFotos[0]['sistMarca'] != null){ ?>
								<input type="text" name="marca_SistemaDVD" value="<?php echo $seguroFotos[0]['sistMarca'] ?>" style="width:160px; font-size:14px ;height:20px; background-color: white; border: 1px solid gray;">
						<?php }else{ ?>
								<input type="text" name="marca_SistemaDVD" style="width:160px; font-size:14px ;height:20px; background-color: white; border: 1px solid gray;">
						<?php }	?>
					</div>
					<div class="derechaAccesorio">
						<label>Modelo</label>
						<?php if($seguroFotos[0]['sistModelo'] != null){ ?>
								<input type="text" name="modelo_SistemaDVD" value="<?php echo $seguroFotos[0]['sistModelo'] ?>" style="width:160px; font-size:14px ;height:20px; background-color: white; border: 1px solid gray;">
						<?php }else{ ?>
								<input type="text" name="modelo_SistemaDVD" style="width:160px; font-size:14px ;height:20px; background-color: white; border: 1px solid gray;">
						<?php }	?>
					</div>
				</div>
				<div class="ultimaCaja">
					<div class="horizontalBlanca">
						<div class="tituloOtrosAccesorios">
							OTROS ACCESORIOS
						</div>
						<div class="textoOtrosAccesorios">
							<?php if($seguroFotos[0]['otros'] != null){ ?>
									<textarea rows='8' name="otrosAccesorios"><?php echo $seguroFotos[0]['otros'] ?></textarea>
							<?php }else{ ?>
									<textarea rows='8' name="otrosAccesorios"></textarea>
							<?php }	?>
						</div>
					</div>
				</div>
	<div class="botonesVolverSiguiente">
		<a href="#" class="siguiente1"><?php echo image_tag('img_asegura_tu_auto/BotonVolver.png') ?></a>
		<div class="extremoDerecho"><a href="#" class="siguiente3"><?php echo image_tag('img_asegura_tu_auto/Siguiente_SubeTuAuto.png') ?></a></div>
	</div>
		</div>
	</div>
</div>

<div id="paso4">
	<div class="ubicacionPasos"><a href='#' title="Paso 1" class="siguiente0"><?php echo image_tag('img_asegura_tu_auto/1claro.png') ?></a> <a href='#' title="Paso 2" class='siguiente1'><?php echo image_tag('img_asegura_tu_auto/2claro.png') ?></a> <a href='#' title="Paso 3" class='siguiente2'><?php echo image_tag('img_asegura_tu_auto/3claro.png') ?></a> <a href='#' title="Paso 4" class='siguiente3'><?php echo image_tag('img_asegura_tu_auto/4.png')?></a></div>

	<div class="contenido">
		<div class="etiquetaPrincipal">DECLARACIÓN DE ACCESORIOS PARA EL SEGURO</div>
			
				<table id="hor-zebra" summary="Employee Pay Sheet">
					<tbody>
						<tr class="odd">
							<td><input type="checkbox" id="aireAcondicionado" name="accesorio[]" value="aireAcondicionado"><p>Aire Acondicionado</p></td>
							<td><input type="checkbox" id="parabrisasOriginal" name="accesorio[]" value="parabrisasOriginal"><p>Parabrisas Original</p></td>
							<td><input type="checkbox" id="vidriosPolarizados" name="accesorio[]" value="vidriosPolarizados"><p>Vidrios Polarizados</p></td>
						</tr>
						<tr>
							<td><input type="checkbox" id="espejos"><p>Espejos</p></td>
							<td><input type="checkbox" id="ruedaRepuesto"><p>Rueda Repuesto</p></td>
							<td><input type="checkbox" id="cilindrosDeSeguridad" name="accesorio[]" value="cilindrosDeSeguridad"><p>Cilindros De Seguridad</p></td>
						</tr>
						<tr class="odd">
							<td><div class="tabulacion"><input type="checkbox" id="esp_electricos" name="accesorio[]" value="esp_electricos"></div><p>Eléctricos</p></td>
							<td><div class="tabulacion"><input type="checkbox" id="llantaAleacion" name="accesorio[]" value="llantaAleacion"></div><p>Llanta Aleación</p></td>
							<td><input type="checkbox" id="techoLona" name="accesorio[]" value="techoLona"><p>Techo Lona</p></td>
						</tr>
						<tr>
							<td><div class="tabulacion"><input type="checkbox" id="esp_manuales" name="accesorio[]" value="esp_Manuales"></div><p>Manuales</p></td>
							<td><div class="tabulacion"><input type="checkbox" id="llantaFierro" name="accesorio[]" value="llantaFierro"></div><p>Llanta Fierro<p></td>
							<td><input type="checkbox" id="techoFibra" name="accesorio[]" value="techoFibra"><p>Techo Fibra<p></td>
						</tr>
						<tr class="odd">
							<td><input type="checkbox" id="vidrios"><p>Vidrios<p></td>
							<td><input type="checkbox" id="huinche" name="accesorio[]" value="huinche"><p>Huinche<p></td>
							<td><input type="checkbox" id="laminasDeSeguridad" name="accesorio[]" value="laminasDeSeguridad"><p>Láminas de Seguridad<p></td>
						</tr>
						<tr>
							<td><div class="tabulacion"><input type="checkbox" id="vid_electricos" name="accesorio[]" value="vid_electricos"></div><p>Electricos<p></td>
							<td><input type="checkbox" id="equipoDeFrio" name="accesorio[]" value="equipoDeFrio"><p>Equipo de Frío<p></td>
							<td><input type="checkbox" id="parrillaAmericana" name="accesorio[]" value="parrillaAmericana"><p>Parrilla Americana<p></td>
						</tr>
						<tr class="odd">
							<td><div class="tabulacion"><input type="checkbox" id="vid_manuales" name="accesorio[]" value="vid_manuales"></div><p>Manuales<p></td>
							<td><input type="checkbox" id="cupula" name="accesorio[]" value="cupula"><p>Cúpula<p></td>
							<td><input type="checkbox" id="cubrePickUp" name="accesorio[]" value="cubrePickUp"><p>Cubre Pick Up<p></td>
						</tr>
						<tr>
							<td><input type="checkbox" id="cierreCentralizado" name="accesorio[]" value="cierreCentralizado"><p>Cierre Centralizado<p></td>
							<td><input type="checkbox" id="controlCrucero" name="accesorio[]" value="controlCrucero"><p>Control Crucero<p></td>
							<td><input type="checkbox" id="cocoDeArrastre" name="accesorio[]" value="cocoDeArrastre"><p>Coco de Arrastre<p></td>
						</tr>
						<tr class="odd">
							<td><input type="checkbox" id="alarma" name="accesorio[]" value="alarma"><p>Alarma<p></td>
							<td><input type="checkbox" id="vidriosGrabados" name="accesorio[]" value="vidriosGrabados"><p>Vidrios Grabados<p></td>
							<td><input type="checkbox" id="aleron" name="accesorio[]" value="aleron"><p>Alerón<p></td>
						</tr>
						<tr>
							<td><input type="checkbox" id="sunRoof"><p>SunRoof<p></td>
							<td><input type="checkbox" id="bluetooth" name="accesorio[]" value="bluetooth"><p>Bluetooth<p></td>
							<td><input type="checkbox" id="antena"><p>Antena<p></td>
						</tr>
						<tr class="odd">
							<td><div class="tabulacion"><input type="checkbox" id="sun_electricos" name="accesorio[]" value="sun_electricos"></div><p>Electricos<p></td>
							<td><input type="checkbox" id="sistemaABS" name="accesorio[]" value="sistemaABS"><p>Sistema ABS<p></td>
							<td><div class="tabulacion"><input type="checkbox" id="ant_manual" name="accesorio[]" value="ant_manual"></div><p>Manual<p></td>
						</tr>
						<tr>
							<td><div class="tabulacion"><input type="checkbox" id="sun_manuales" name="accesorio[]" value="sun_manuales"></div><p>Manuales<p></td>
							<td><input type="checkbox" id="cortinaRetractil" name="accesorio[]" value="cortinaRetractil"><p>Cortina Retráctil<p></td>
							<td><div class="tabulacion"><input type="checkbox" id="ant_electrica" name="accesorio[]" value="ant_electrica"></div><p>Eléctrica<p></td>
						</tr>
						<tr class="odd">
							<td><input type="checkbox" id="climatizador" name="accesorio[]" value="climatizador"><p>Climatizador<p></td>
							<td><input type="checkbox" id="airBag" name="accesorio[]" value="airBag"><p>Air Bag<p></td>
							<td><input type="checkbox" id="tapiceria"><p>Tapicería<p></td>
						</tr>
						<tr>
							<td><input type="checkbox" id="sensorAcercamiento" name="accesorio[]" value="sensorAcercamiento"><p>Sensor Acercamiento<p></td>
							<td><input type="checkbox" id="moldurasPasoRueda" name="accesorio[]" value="moldurasPasoRueda"><p>Molduras Paso Rueda<p></td>
							<td><div class="tabulacion"><input type="checkbox" id="tap_cuero" name="accesorio[]" value="tap_cuero"></div><p>Cuero<p></td>
						</tr>
						<tr class="odd">
							<td><input type="checkbox" id="sensorRetroceso" name="accesorio[]" value="sensorRetroceso"><p>Sensor Retroceso</p></td>
							<td><input type="checkbox" id="conosRueda" name="accesorio[]" value="conosRueda"><p>Conos Rueda</p></td>
							<td><div class="tabulacion"><input type="checkbox" id="tap_normal" name="accesorio[]" value="tap_normal"></div><p>Normal</p></td>
						</tr>
						<tr>
							<td><input type="checkbox" id="ganchosAmarre" name="accesorio[]" value="ganchosAmarre"><p>Ganchos Amarre</p></td>
							<td><input type="checkbox" id="neblineros" name="accesorio[]" value="neblineros"><p>Neblineros</p></td>
							<td><input type="checkbox" id="neumaticos"><p>Neumáticos</p></td>
						</tr>
						<tr class="odd">
							<td><input type="checkbox" id="llantasAleacion" name="accesorio[]" value="llantasAleacion"><p>Llantas Aleacion</p></td>
							<td><input type="checkbox" id="tapasDeRueda" name="accesorio[]" value="tapasDeRueda"><p>Tapas De Rueda</p></td>
							<td><div class="tabulacion"><input type="checkbox" id="neu_nuevos" name="accesorio[]" value="neu_nuevos"></div><p>Nuevos</p></td>
						</tr>
						<tr>
							<td><input type="checkbox" id="llantasFierro" name="accesorio[]" value="llantasFierro"><p>Llantas Fierro</p></td>
							<td><input type="checkbox" id="pisaderasLaterales" name="accesorio[]" value="pisaderasLaterales"><p>Pisaderas Laterales</p></td>
							<td><div class="tabulacion"><input type="checkbox" id="neu_MedioUso" name="accesorio[]" value="neu_MedioUso"></div><p>Medio Uso</p></td>
						</tr>
						<tr class="odd">
							<td><input type="checkbox" id="parabrisasAlternativo" name="accesorio[]" value="parabrisasAlternativo"><p>Parabrisas Alternativo</p></td>
							<td><input type="checkbox" id="barraAntivuelco" name="accesorio[]" value="barraAntivuelco"><p>Barra Antivuelco</p></td>
							<td><div class="tabulacion"><input type="checkbox" id="neu_Gastados" name="accesorio[]" value="neu_Gastados"></div><p>Gastados</p></td>
						</tr>
					</tbody>
				</table>
	<div id="cajaContenedora"></div>
	<div id="confirmarListo" style="display:none;"><p>Tu verificación será aprobada o rechazada por nuestro equipo en 48 horas.<p></div>
	<div class="botonesVolverSiguiente">
		<a href="#" class="siguiente2"><?php echo image_tag('img_asegura_tu_auto/BotonVolver.png') ?></a>
		<div class="extremoDerecho"><a href="#" id="listo"><?php echo image_tag('img_asegura_tu_auto/Listo_SubeTuAuto.png') ?></a></div>
	</div>
	</div>
</div>
</form>
</div><!-- fin contenidoAseguraTuAuto -->
<script type="text/javascript">
	
	$(document).ready(function(){

		function imageUpload(form, formfile, preview, link){

	        $(formfile).live('change', function()	
	        { 
	            $(preview).html('');
	            $(preview).html('<?php echo image_tag('loader.gif') ?>');
	            $(form).ajaxForm(
	            {
	                target: preview
	            }).submit();
	        });
			
	        $(link).click(function(event) {
	            $(formfile).click();
	        });

	    }
	    <?php
	    for ($i=0;$i<count($partes);$i++){
	    	echo "imageUpload('#form_".$partes[$i]."', '#file_".$partes[$i]."', '#preview_".$partes[$i]."','#link_".$partes[$i]."');";
	    }
	    ?>
		var paso = '<?php echo $pasoAcceso; ?>';
		//alert(redireccionar)
		if(paso == 2){
		 	//$("#paso1").fadeOut("fast");
		 	$("#paso2").fadeIn("fast");
			mapaAuto();
		}else{
			$("#paso1").fadeIn("fast");
		}
/*********************** botones *********************/
		  $(".siguiente0").on('click',function(e){
		 	e.preventDefault();
		 	$("#paso1").fadeIn("fast");
		 	$("#paso2").fadeOut("fast");
		 	$("#paso3").fadeOut("fast");
		 	$("#paso4").fadeOut("fast");
		 });
		 $(".siguiente1").on('click',function(e){
		 	e.preventDefault();
		 	$("#paso1").fadeOut("fast");
		 	$("#paso2").fadeIn("fast");
		 	mapaAuto();
		 	$("#paso3").fadeOut("fast");
		 	$("#paso4").fadeOut("fast");
		 });
		 $(".siguiente2").on('click',function(e){
		 	e.preventDefault();
		 	$("#paso1").fadeOut("fast");	
		 	$("#paso2").fadeOut("fast");
		 	$("#paso3").fadeIn("fast");
		 	$("#paso4").fadeOut("fast");
		 });
		 $(".siguiente3").on('click',function(e){
		 	e.preventDefault();
		 	$("#paso1").fadeOut("fast");
		 	$("#paso2").fadeOut("fast");
		 	$("#paso3").fadeOut("fast");
		 	$("#paso4").fadeIn("fast");
		 });
		 $("#listo").on('click',function(e){
		 	e.preventDefault();
		 	$("#confirmarListo").dialog({
		        resizable: false,
		        width: 500,
		        height: 160,
		        modal: false,
		        autoOpen: false,
		        closeOnEscape: false,
		        title: 'Aviso',
		        position: { my: "center", at: "center"},
		        buttons: {
		            Continuar: function() {
		                $("#frml").submit();
		                $(this).dialog( "close" );
		            },
		            Cancelar: function() {
		                $(this).dialog( "close" );
		            }
		        }
		    });
		    $("#confirmarListo").dialog("open");
		 });
		 $("#a_botonNo").on('click',function(e){
		 	e.preventDefault();
		 	$("#accesoriosDelVehiculo").fadeIn("fast");
		 });
		 $("#a_botonSi").on('click',function(e){
		 	e.preventDefault();
		 	$("#paso3").fadeOut("fast");
		 	$("#accesoriosDelVehiculo").fadeOut("fast");
		 	$("#paso4").fadeIn("fast");
		 });

/********************************** Botones Subir Foto ***********************************/
		 $("#botonSubirFrente").on('click',function(e){
		 	e.preventDefault();
		 	//alert($('#inputFotoFrente').val());
		 	if($('#inputFotoFrente').val() == ""){
		 		//alert("input vacio");
		 		alert("Ingrese alguna Imagen");
		 	}else{
		 		$("#fotoFrenteActual").fadeOut("fast");
		 		//alert("Subiendo una Imagen 1");
		 		//funcion(idFormulario,idInputTipoFile,idDivContenedorDeLaImagen)
		 		imageUpload('#formFotoFrente','#inputFotoFrente','#imagenFotoFrente');
		 		
		 	}
		 });
		 $("#botonSubirCostadoDerecho").on('click',function(e){
		 	e.preventDefault();
		 	//alert($('#inputFotoCostadoDerecho').val());
		 	if($('#inputFotoCostadoDerecho').val() == ""){
		 		//alert("input vacio");
		 		alert("Ingrese alguna Imagen");
		 	}else{
		 		$("#fotoCostadoDerechoActual").fadeOut("fast");
		 		//alert("Subiendo una Imagen 2");
		 		//funcion(idFormulario,idInputTipoFile,idDivContenedorDeLaImagen,idBotonParaSubirFoto)
		 		imageUpload('#formFotoCostadoDerecho','#inputFotoCostadoDerecho','#imagenFotoCostadoDerecho');
		 	}
		 });
		 $("#botonSubirCostadoIzquierdo").on('click',function(e){
		 	e.preventDefault();
		 	//alert($('#inputFotoCostadoIzquierdo').val());
		 	if($('#inputFotoCostadoIzquierdo').val() == ""){
		 		//alert("input vacio");
		 		alert("Ingrese alguna Imagen");
		 	}else{
		 		$("#fotoCostadoIzquierdoActual").fadeOut("fast");
		 		//alert("Subiendo una Imagen 3");
		 		//funcion(idFormulario,idInputTipoFile,idDivContenedorDeLaImagen)
		 		imageUpload('#formFotoCostadoIzquierdo','#inputFotoCostadoIzquierdo','#imagenFotoCostadoIzquierdo');
		 	}
		 });
		 $("#botonSubirTrasero").on('click',function(e){
		 	e.preventDefault();
		 	if($('#inputFotoTrasero').val() == ""){
		 		//alert("input vacio");
		 		alert("Ingrese alguna Imagen");
		 	}else{
		 		$("#fotoTraseroActual").fadeOut("fast");
		 		//alert("Subiendo una Imagen 4");
		 		//funcion(idFormulario,idInputTipoFile,idDivContenedorDeLaImagen)
		 		imageUpload('#formFotoTrasero','#inputFotoTrasero','#imagenFotoTrasero');
		 	}
		 });
		 $("#botonSubirPanel").on('click',function(e){
		 	e.preventDefault();
		 	//alert($('#inputFotoTraseroIzquierdo').val());
		 	if($('#inputFotoPanel').val() == ""){
		 		//alert("input vacio");
		 		alert("Ingrese alguna Imagen");
		 	}else{
		 		$("#fotoPanel").fadeOut("fast");
		 		//alert("Subiendo una Imagen 5");
		 		//funcion(idFormulario,idInputTipoFile,idDivContenedorDeLaImagen)
		 		imageUpload('#formFotoPanel','#inputFotoPanel','#imagenFotoPanel');
		 		//opcion = true;
		 	}
		 });
		 $("#botonSubirRueda1").on('click',function(e){
		 	e.preventDefault();
		 	//alert($('#inputFotoTraseroIzquierdo').val());
		 	if($('#inputFotoRueda1').val() == ""){
		 		//alert("input vacio");
		 		alert("Ingrese alguna Imagen");
		 	}else{
		 		$("#fotoRueda1").fadeOut("fast");
		 		//alert("Subiendo una Imagen 5");
		 		//funcion(idFormulario,idInputTipoFile,idDivContenedorDeLaImagen)
		 		imageUpload('#formFotoRueda1','#inputFotoRueda1','#imagenFotoRueda1');
		 		//opcion = true;
		 	}
		 });
		 $("#botonSubirRueda2").on('click',function(e){
		 	e.preventDefault();
		 	//alert($('#inputFotoTraseroIzquierdo').val());
		 	if($('#inputFotoRueda2').val() == ""){
		 		//alert("input vacio");
		 		alert("Ingrese alguna Imagen");
		 	}else{
		 		$("#fotoRueda2").fadeOut("fast");
		 		//alert("Subiendo una Imagen 5");
		 		//funcion(idFormulario,idInputTipoFile,idDivContenedorDeLaImagen)
		 		imageUpload('#formFotoRueda2','#inputFotoRueda2','#imagenFotoRueda2');
		 		//opcion = true;
		 	}
		 });
		 $("#botonSubirRueda3").on('click',function(e){
		 	e.preventDefault();
		 	//alert($('#inputFotoTraseroIzquierdo').val());
		 	if($('#inputFotoRueda3').val() == ""){
		 		//alert("input vacio");
		 		alert("Ingrese alguna Imagen");
		 	}else{
		 		$("#fotoRueda3").fadeOut("fast");
		 		//alert("Subiendo una Imagen 5");
		 		//funcion(idFormulario,idInputTipoFile,idDivContenedorDeLaImagen)
		 		imageUpload('#formFotoRueda3','#inputFotoRueda3','#imagenFotoRueda3');
		 		//opcion = true;
		 	}
		 });
		 $("#botonSubirRueda4").on('click',function(e){
		 	e.preventDefault();
		 	//alert($('#inputFotoTraseroIzquierdo').val());
		 	if($('#inputFotoRueda4').val() == ""){
		 		//alert("input vacio");
		 		alert("Ingrese alguna Imagen");
		 	}else{
		 		$("#fotoRueda4").fadeOut("fast");
		 		//alert("Subiendo una Imagen 5");
		 		//funcion(idFormulario,idInputTipoFile,idDivContenedorDeLaImagen)
		 		imageUpload('#formFotoRueda4','#inputFotoRueda4','#imagenFotoRueda4');
		 		//opcion = true;
		 	}
		 });
		 $("#botonSubirRuedaR").on('click',function(e){
		 	e.preventDefault();
		 	//alert($('#inputFotoTraseroIzquierdo').val());
		 	if($('#inputFotoRuedaR').val() == ""){
		 		//alert("input vacio");
		 		alert("Ingrese alguna Imagen");
		 	}else{
		 		$("#fotoRuedaR").fadeOut("fast");
		 		//alert("Subiendo una Imagen 5");
		 		//funcion(idFormulario,idInputTipoFile,idDivContenedorDeLaImagen)
		 		imageUpload('#formFotoRuedaR','#inputFotoRuedaR','#imagenFotoRuedaR');
		 		//opcion = true;
		 	}
		 });
		 $("#botonSubirAccesorio1").on('click',function(e){
		 	e.preventDefault();
		 	//alert($('#inputFotoTraseroIzquierdo').val());
		 	if($('#inputFotoAccesorio1').val() == ""){
		 		//alert("input vacio");
		 		alert("Ingrese alguna Imagen");
		 	}else{
		 		$("#fotoAccesorio1").fadeOut("fast");
		 		//alert("Subiendo una Imagen 5");
		 		//funcion(idFormulario,idInputTipoFile,idDivContenedorDeLaImagen)
		 		imageUpload('#formFotoAccesorio1','#inputFotoAccesorio1','#imagenFotoAccesorio1');
		 		//opcion = true;
		 	}
		 });
		 $("#botonSubirAccesorio2").on('click',function(e){
		 	e.preventDefault();
		 	//alert($('#inputFotoTraseroIzquierdo').val());
		 	if($('#inputFotoAccesorio2').val() == ""){
		 		//alert("input vacio");
		 		alert("Ingrese alguna Imagen");
		 	}else{
		 		$("#fotoAccesorio2").fadeOut("fast");
		 		//alert("Subiendo una Imagen 5");
		 		//funcion(idFormulario,idInputTipoFile,idDivContenedorDeLaImagen)
		 		imageUpload('#formFotoAccesorio2','#inputFotoAccesorio2','#imagenFotoAccesorio2');
		 		//opcion = true;
		 	}
		 });
		 
/************************************* Accesorios *************************************/
		<?php
			$arregloDeAccesoriosSeguro = $seguroFotos[0]['accesoriosSeguro'];
			$arregloDeAccesoriosSeguro_Separado = explode(',', $arregloDeAccesoriosSeguro);
			$cantidadDeAccesoriosAsegurados = count($arregloDeAccesoriosSeguro_Separado);
			$aireAcondicionadoOK = false;
			$parabrisasOriginalOK = false; 
			$vidriosPolarizadosOK = false; 
			$cilindrosDeSeguridadOK = false; 
			$esp_electricosOK = false; 
			$llantaAleacionOK = false; 
			$techoLonaOK = false; 
			$esp_manualesOK = false;  
			$llantaFierroOK = false; 
			$techoFibraOK = false; 
			$huincheOK = false;
			$vid_electricosOK = false; 
			$laminasDeSeguridadOK = false;
			$equipoDeFrioOK = false; 
			$parrillaAmericanaOK = false; 
			$vid_manualesOK = false;
			$cupulaOK = false; 
			$cubrePickUpOK = false; 
			$cierreCentralizadoOK = false; 
			$controlCruceroOK = false; 
			$cocoDeArrastreOK = false; 
			$alarmaOK = false; 
			$vidriosGrabadosOK = false; 
			$aleronOK = false; 
			$bluetoothOK = false;
			$sun_electricosOK = false;
			$sistemaABSOK = false;
			$ant_manualOK = false;
			$sun_manualesOK = false;
			$cortinaRetractilOK = false;
			$ant_electricaOK = false;
			$climatizadorOK = false;
			$airBagOK = false;
			$sensorAcercamientoOK = false;
			$moldurasPasoRuedaOK = false;
			$tap_cueroOK = false;
			$sensorRetrocesoOK = false;
			$conosRuedaOK = false;
			$tap_normalOK = false;
			$ganchosAmarreOK = false;
			$neblinerosOK = false;
			$llantasAleacionOK = false;
			$tapasDeRuedaOK = false;
			$neu_nuevosOK = false;
			$llantasFierroOK = false;
			$pisaderasLateralesOK = false;
			$neu_MedioUsoOK = false;
			$parabrisasAlternativoOK = false;
			$barraAntivuelcoOK = false;
			$neu_GastadosOK = false;
			
			for($i=0;$i<$cantidadDeAccesoriosAsegurados;$i++){ //Recorremos el arreglo rescatando los nombres de los accesorios
				if( $arregloDeAccesoriosSeguro_Separado[$i] == "aireAcondicionado") $aireAcondicionadoOK = true;
				if( $arregloDeAccesoriosSeguro_Separado[$i] == "parabrisasOriginal") $parabrisasOriginalOK = true;
				if( $arregloDeAccesoriosSeguro_Separado[$i] == "vidriosPolarizados") $vidriosPolarizadosOK = true;
				if( $arregloDeAccesoriosSeguro_Separado[$i] == "cilindrosDeSeguridad") $cilindrosDeSeguridadOK = true;
				if( $arregloDeAccesoriosSeguro_Separado[$i] == "esp_electricos") $esp_electricosOK = true;
				if( $arregloDeAccesoriosSeguro_Separado[$i] == "llantaAleacion") $llantaAleacionOK = true;
				if( $arregloDeAccesoriosSeguro_Separado[$i] == "techoLona") $techoLonaOK = true;
				if( $arregloDeAccesoriosSeguro_Separado[$i] == "esp_Manuales") $esp_manualesOK = true;
				if( $arregloDeAccesoriosSeguro_Separado[$i] == "llantaFierro") $llantaFierroOK = true;
				if( $arregloDeAccesoriosSeguro_Separado[$i] == "techoFibra") $techoFibraOK = true;
				if( $arregloDeAccesoriosSeguro_Separado[$i] == "huinche") $huincheOK = true;
				if( $arregloDeAccesoriosSeguro_Separado[$i] == "laminasDeSeguridad") $laminasDeSeguridadOK = true;
				if( $arregloDeAccesoriosSeguro_Separado[$i]== "vid_electricos") $vid_electricosOK = true;
				if($arregloDeAccesoriosSeguro_Separado[$i] == "equipoDeFrio") $equipoDeFrioOK = true;
				if( $arregloDeAccesoriosSeguro_Separado[$i]== "parrillaAmericana") $parrillaAmericanaOK = true;
				if( $arregloDeAccesoriosSeguro_Separado[$i]== "vid_manuales") $vid_manualesOK = true;
				if( $arregloDeAccesoriosSeguro_Separado[$i]== "cupula") $cupulaOK = true;
				if( $arregloDeAccesoriosSeguro_Separado[$i]== "cubrePickUp") $cubrePickUpOK = true;
				if( $arregloDeAccesoriosSeguro_Separado[$i]== "cierreCentralizado") $cierreCentralizadoOK = true;
				if( $arregloDeAccesoriosSeguro_Separado[$i]== "controlCrucero") $controlCruceroOK = true;
				if( $arregloDeAccesoriosSeguro_Separado[$i]== "cocoDeArrastre") $cocoDeArrastreOK = true;
				if( $arregloDeAccesoriosSeguro_Separado[$i]== "alarma") $alarmaOK = true;
				if( $arregloDeAccesoriosSeguro_Separado[$i]== "vidriosGrabados") $vidriosGrabadosOK = true;
				if( $arregloDeAccesoriosSeguro_Separado[$i]== "aleron") $aleronOK = true;
				if( $arregloDeAccesoriosSeguro_Separado[$i]== "bluetooth") $bluetoothOK = true;
				if( $arregloDeAccesoriosSeguro_Separado[$i]== "sun_electricos") $sun_electricosOK = true;
				if( $arregloDeAccesoriosSeguro_Separado[$i]== "sistemaABS") $sistemaABSOK = true;
				if( $arregloDeAccesoriosSeguro_Separado[$i]== "ant_manual") $ant_manualOK = true;
				if( $arregloDeAccesoriosSeguro_Separado[$i]== "sun_manuales") $sun_manualesOK = true;
				if( $arregloDeAccesoriosSeguro_Separado[$i]== "cortinaRetractil") $cortinaRetractilOK = true;
				if( $arregloDeAccesoriosSeguro_Separado[$i]== "ant_electrica") $ant_electricaOK = true;
				if( $arregloDeAccesoriosSeguro_Separado[$i]== "climatizador") $climatizadorOK = true;
				if( $arregloDeAccesoriosSeguro_Separado[$i]== "airBag") $airBagOK = true;
				if( $arregloDeAccesoriosSeguro_Separado[$i]== "sensorRetroceso") $sensorRetrocesoOK = true;
				if( $arregloDeAccesoriosSeguro_Separado[$i]== "sensorAcercamiento") $sensorAcercamientoOK = true;
				if( $arregloDeAccesoriosSeguro_Separado[$i]== "moldurasPasoRueda") $moldurasPasoRuedaOK = true;
				if( $arregloDeAccesoriosSeguro_Separado[$i]== "tap_cuero") $tap_cueroOK = true;
				if( $arregloDeAccesoriosSeguro_Separado[$i]== "conosRueda") $conosRuedaOK = true;
				if( $arregloDeAccesoriosSeguro_Separado[$i]== "tap_normal") $tap_normalOK = true;
				if( $arregloDeAccesoriosSeguro_Separado[$i]== "ganchosAmarre") $ganchosAmarreOK = true;
				if( $arregloDeAccesoriosSeguro_Separado[$i]== "neblineros") $neblinerosOK = true;
				if( $arregloDeAccesoriosSeguro_Separado[$i]== "llantasAleacion") $llantasAleacionOK = true;
				if( $arregloDeAccesoriosSeguro_Separado[$i]== "tapasDeRueda") $tapasDeRuedaOK = true;
				if( $arregloDeAccesoriosSeguro_Separado[$i]== "neu_nuevos") $neu_nuevosOK = true;
				if( $arregloDeAccesoriosSeguro_Separado[$i]== "llantasFierro") $llantasFierroOK = true;
				if( $arregloDeAccesoriosSeguro_Separado[$i]== "pisaderasLaterales") $pisaderasLateralesOK = true;
				if( $arregloDeAccesoriosSeguro_Separado[$i]== "neu_MedioUso") $neu_MedioUsoOK = true;
				if( $arregloDeAccesoriosSeguro_Separado[$i]== "parabrisasAlternativo") $parabrisasAlternativoOK = true;
				if( $arregloDeAccesoriosSeguro_Separado[$i]== "barraAntivuelco") $barraAntivuelcoOK = true;
				if( $arregloDeAccesoriosSeguro_Separado[$i]== "neu_Gastados") $neu_GastadosOK = true;
 
			}
		?>

		var varAireAcondicionado = '<?php echo $aireAcondicionadoOK; ?>';
		var varParabrisasOriginal = '<?php echo $parabrisasOriginalOK; ?>';
		var varVidriosPolarizados = '<?php echo $vidriosPolarizadosOK; ?>';
		var varCilindrosDeSeguridad = '<?php echo $cilindrosDeSeguridadOK; ?>';
		var varEsp_electricos = '<?php echo $esp_electricosOK; ?>';
		var varLlantaAleacion = '<?php echo $llantaAleacionOK; ?>';
		var varTechoLona = '<?php echo $techoLonaOK; ?>';
		var varEsp_manuales = '<?php echo $esp_manualesOK; ?>';
		var varLlantaFierro = '<?php echo $llantaFierroOK; ?>';
		var varTechoFibra = '<?php echo $techoFibraOK; ?>';
		var varHuinche = '<?php echo $huincheOK; ?>';
		var varLaminasDeSeguridad = '<?php echo $laminasDeSeguridadOK; ?>';
		var varVid_electricos = '<?php echo $vid_electricosOK; ?>';
		var varEquipoDeFrio = '<?php echo $equipoDeFrioOK; ?>';
		var varParrillaAmericana = '<?php echo $parrillaAmericanaOK; ?>';
		var varVid_manuales = '<?php echo $vid_manualesOK; ?>';
		var varCupula = '<?php echo $cupulaOK; ?>';
		var varCubrePickUp = '<?php echo $cubrePickUpOK; ?>';
		var varCierreCentralizado = '<?php echo $cierreCentralizadoOK; ?>';
		var varControlCrucero = '<?php echo $controlCruceroOK; ?>';
		var varCocoDeArrastre = '<?php echo $cocoDeArrastreOK; ?>';
		var varAlarma = '<?php echo $alarmaOK; ?>';
		var varVidriosGrabados = '<?php echo $vidriosGrabadosOK; ?>';
		var varAleron = '<?php echo $aleronOK; ?>';
		var varBluetooth = '<?php echo $bluetoothOK; ?>';
		var varSun_electricos = '<?php echo $sun_electricosOK; ?>';
		var varSistemaABS = '<?php echo $sistemaABSOK; ?>';
		var varAnt_manual = '<?php echo $ant_manualOK; ?>';
		var varSun_manuales = '<?php echo $sun_manualesOK; ?>';
		var varCortinaRetractil = '<?php echo $cortinaRetractilOK; ?>';
		var varAnt_electrica = '<?php echo $ant_electricaOK; ?>';
		var varClimatizador = '<?php echo $climatizadorOK; ?>';
		var varAirBag = '<?php echo $airBagOK; ?>';
		var varSensorAcercamiento = '<?php echo $sensorAcercamientoOK; ?>';
		var varMoldurasPasoRueda = '<?php echo $moldurasPasoRuedaOK; ?>';
		var varTap_cuero = '<?php echo $tap_cueroOK; ?>';
		var varSensorRetroceso = '<?php echo $sensorRetrocesoOK; ?>';
		var varConosRueda = '<?php echo $conosRuedaOK; ?>';
		var varTap_normal = '<?php echo $tap_normalOK; ?>';
		var varGanchosAmarre = '<?php echo $ganchosAmarreOK; ?>';
		var varNeblineros = '<?php echo $neblinerosOK; ?>';
		var varLlantasAleacion = '<?php echo $llantasAleacionOK; ?>';
		var varTapasDeRueda = '<?php echo $tapasDeRuedaOK; ?>';
		var varNeu_nuevos = '<?php echo $neu_nuevosOK; ?>';
		var varLlantasFierro = '<?php echo $llantasFierroOK; ?>';
		var varPisaderasLaterales = '<?php echo $pisaderasLateralesOK; ?>';
		var varNeu_MedioUso = '<?php echo $neu_MedioUsoOK; ?>';
		var varParabrisasAlternativo = '<?php echo $parabrisasAlternativoOK; ?>';
		var varBarraAntivuelco = '<?php echo $barraAntivuelcoOK; ?>';
		var varNeu_Gastados = '<?php echo $neu_GastadosOK; ?>';
		
		
		if(varAireAcondicionado){
			$('#aireAcondicionado').attr('checked',true);
			agregarElementoCajaContenedora("Aire Acondicionado", "aireAcondicionadoCC", "botonEliminar_aireAcondicionado");
		}
		if(varParabrisasOriginal){
			$('#parabrisasOriginal').attr('checked',true);
			agregarElementoCajaContenedora("Parabrisas Original", "parabrisasOriginalCC", "botonEliminar_parabrisasOriginal");
		}
		if(varVidriosPolarizados){
			$('#vidriosPolarizados').attr('checked',true);
		 	agregarElementoCajaContenedora("Vidrios Polarizados", "vidriosPolarizadosCC", "botonEliminar_vidriosPolarizados");
		}
		if(varCilindrosDeSeguridad){
			$('#cilindrosDeSeguridad').attr('checked',true);
			agregarElementoCajaContenedora("Cilindros de Seguridad", "cilindrosDeSeguridadCC", "botonEliminar_cilindrosDeSeguridad");
		}
		if(varEsp_electricos){
			$('#espejos').attr('checked',true);
		 	$('#esp_electricos').attr('checked',true);
		 	agregarElementoCajaContenedora("Espejos(Eléctricos)", "esp_ElectricosCC", "botonEliminar_espElectricos");
		}
		if(varLlantaAleacion){
		 	$('#ruedaRepuesto').attr('checked',true);
		 	$('#llantaAleacion').attr('checked',true);
		 	agregarElementoCajaContenedora("Rueda Repuesto(Aleación)", "rr_llantaAleacionCC", "botonEliminar_rrLlantaAleacion");
		}
		if(varTechoLona){
			$('#techoLona').attr('checked',true);
		 	agregarElementoCajaContenedora("Techo Lona", "techoLonaCC", "botonEliminar_techoLona");
		}
		if(varEsp_manuales){
			$('#espejos').attr('checked',true);
			$('#esp_manuales').attr('checked',true);
			agregarElementoCajaContenedora("Espejos(Manuales)", "esp_ManualesCC", "botonEliminar_espManuales");
		}
		if(varLlantaFierro){
			$('#ruedaRepuesto').attr('checked',true);
			$('#llantaFierro').attr('checked',true);
			agregarElementoCajaContenedora("Rueda Repuesto(Fierro)", "rr_llantaFierroCC", "botonEliminar_rrLlantaFierro");
		}
		if(varTechoFibra){
			$('#techoFibra').attr('checked',true);
			agregarElementoCajaContenedora("Techo Fibra", "techoFibraCC", "botonEliminar_techoFibra");
		}
		if(varHuinche){
			$('#huinche').attr('checked',true);
			agregarElementoCajaContenedora("Huinche", "huincheCC", "botonEliminar_huinche");
		}
		if(varLaminasDeSeguridad){
 			$('#laminasDeSeguridad').attr('checked',true);
		 	agregarElementoCajaContenedora("Láminas de Seguridad", "laminasDeSeguridadCC", "botonEliminar_laminasDeSeguridad");
		}
		if(varVid_electricos){
			$('#vidrios').attr('checked',true);
			$('#vid_electricos').attr('checked',true);
			agregarElementoCajaContenedora("Vidrios(Eléctricos)", "vid_ElectricoCC", "botonEliminar_vidElectrico");	
		}
		if(varEquipoDeFrio){
			$('#equipoDeFrio').attr('checked',true);
			agregarElementoCajaContenedora("Equipo de Frio", "equipoDeFrioCC", "botonEliminar_equipoDeFrio");
		}
		if(varParrillaAmericana){
			$('#parrillaAmericana').attr('checked',true);
			agregarElementoCajaContenedora("Parrilla Americana", "parrillaAmericanaCC", "botonEliminar_parrillaAmericana");
		}
		if(varVid_manuales){
			$('#vidrios').attr('checked',true);
			$('#vid_manuales').attr('checked',true);
			agregarElementoCajaContenedora("Vidrios(Manuales)", "vid_ManualesCC", "botonEliminar_vidManuales");
			
		}
		if(varCupula){
			$('#cupula').attr('checked',true);
			agregarElementoCajaContenedora("Cúpula", "cupulaCC", "botonEliminar_cupula");
		}
		if(varCubrePickUp){
			$('#cubrePickUp').attr('checked',true);
			agregarElementoCajaContenedora("Cubre PickUp", "cubrePickUpCC", "botonEliminar_cubrePickUp");
		}
		if(varCierreCentralizado){
			$('#cierreCentralizado').attr('checked',true);
			agregarElementoCajaContenedora("Cierre Centralizado", "cierreCentralizadoCC", "botonEliminar_cierreCentralizado");
		}
		if(varControlCrucero){
			$('#controlCrucero').attr('checked',true);
			agregarElementoCajaContenedora("Control Crucero", "controlCruceroCC", "botonEliminar_controlCrucero");
		}
		if(varCocoDeArrastre){
			$('#cocoDeArrastre').attr('checked',true);
			agregarElementoCajaContenedora("Coco De Arrastre", "cocoDeArrastreCC", "botonEliminar_cocoDeArrastre");
		}
		if(varAlarma){
			$('#alarma').attr('checked',true);
			agregarElementoCajaContenedora("Alarma", "alarmaCC", "botonEliminar_alarma");
		}
		if(varVidriosGrabados){
			$('#vidriosGrabados').attr('checked',true);
			agregarElementoCajaContenedora("Vidrios Grabados", "vidriosGrabadosCC", "botonEliminar_vidriosGrabados");
		}
		if(varAleron){
			$('#aleron').attr('checked',true);
		 	agregarElementoCajaContenedora("Alerón", "aleronCC", "botonEliminar_aleron");
		}
		if(varBluetooth){
			$('#bluetooth').attr('checked',true);
			agregarElementoCajaContenedora("Bluetooth", "bluetoothCC", "botonEliminar_bluetooth");
		}
		if(varSun_electricos){
			$('#sunRoof').attr('checked',true);
			$('#sun_electricos').attr('checked',true);
			agregarElementoCajaContenedora("Sun Roof(Elétrico)", "sun_ElectricoCC", "botonEliminar_sunElectrico");
		}
		if(varSistemaABS){
			$('#sistemaABS').attr('checked',true);
			agregarElementoCajaContenedora("Sistema ABS", "sistemaABSCC", "botonEliminar_sistemaABS");
		}
		if(varAnt_manual){
			$('#antena').attr('checked',true);
			$('#ant_manual').attr('checked',true);
			agregarElementoCajaContenedora("Antena(Manual)", "ant_ManualCC", "botonEliminar_antManual");		
		}
		if(varSun_manuales){
			$('#sunRoof').attr('checked',true);
			$('#sun_manuales').attr('checked',true);
			agregarElementoCajaContenedora("Sun Roof(Manual)", "sun_ManualCC", "botonEliminar_sunManual");
		}
		if(varCortinaRetractil){
			$('#cortinaRetractil').attr('checked',true);
			agregarElementoCajaContenedora("Cortina Retráctil", "cortinaRetractilCC", "botonEliminar_cortinaRetractil");
		}
		if(varAnt_electrica){
			$('#antena').attr('checked',true);
			$('#ant_electrica').attr('checked',true);
			agregarElementoCajaContenedora("Antena(Electrica)", "ant_ElectricaCC", "botonEliminar_antElectrica");
		}
		if(varClimatizador){
			$('#climatizador').attr('checked',true);
			agregarElementoCajaContenedora("Climatizador", "climatizadorCC", "botonEliminar_climatizador");
		}
		if(varAirBag){
			$('#airBag').attr('checked',true);
			agregarElementoCajaContenedora("Air Bag", "airBagCC", "botonEliminar_airBag");
		}
		if(varSensorAcercamiento){
			$('#sensorAcercamiento').attr('checked',true);
			agregarElementoCajaContenedora("Sensor Acercamiento", "sensorAcercamientoCC", "botonEliminar_sensorAcercamiento");
		}
		if(varMoldurasPasoRueda){
			$('#moldurasPasoRueda').attr('checked',true);
			agregarElementoCajaContenedora("Molduras Paso Rueda", "moldurasPasoRuedaCC", "botonEliminar_moldurasPasoRueda");
		}
		if(varTap_cuero){
			$('#tapiceria').attr('checked',true);
			$('#tap_cuero').attr('checked',true);
			agregarElementoCajaContenedora("Tapicería(Cuero)", "tap_CueroCC", "botonEliminar_tapCuero");
		}
		if(varSensorRetroceso){
			$('#sensorRetroceso').attr('checked',true);
			agregarElementoCajaContenedora("Sensor Retroceso", "sensorRetrocesoCC", "botonEliminar_sensorRetroceso");
		}
		if(varConosRueda){
			$('#conosRueda').attr('checked',true);
			agregarElementoCajaContenedora("Conos Rueda", "conosRuedaCC", "botonEliminar_conosRueda");
		}
		if(varTap_normal){
			$('#tapiceria').attr('checked',true);
			$('#tap_normal').attr('checked',true);
			agregarElementoCajaContenedora("Tapicería(Normal)", "tap_NormalCC", "botonEliminar_tapNormal");
		}
		if(varGanchosAmarre){
			$('#ganchosAmarre').attr('checked',true);
			agregarElementoCajaContenedora("Ganchos Amarre", "ganchosAmarreCC", "botonEliminar_ganchosAmarre");
		}
		if(varNeblineros){
			$('#neblineros').attr('checked',true);
			agregarElementoCajaContenedora("Neblineros", "neblinerosCC", "botonEliminar_neblineros");
		}

		if(varLlantasAleacion){
			$('#llantasAleacion').attr('checked',true);
			agregarElementoCajaContenedora("Llantas Aleación", "llantasAleacionCC", "botonEliminar_llantasAleacion");
		}

		if(varTapasDeRueda){
			$('#tapasDeRueda').attr('checked',true);
			agregarElementoCajaContenedora("Tapas de Rueda", "tapasDeRuedaCC", "botonEliminar_tapasDeRueda");	
		}

		if(varNeu_nuevos){
			$('#neumaticos').attr('checked',true);
			$('#neu_nuevos').attr('checked',true);
			agregarElementoCajaContenedora("Neumaticos(Nuevos)", "neu_NuevosCC", "botonEliminar_neuNuevos");		
		}

		if(varLlantasFierro){
			$('#llantasFierro').attr('checked',true);
			agregarElementoCajaContenedora("Llantas Fierro", "llantasFierroCC", "botonEliminar_llantasFierro");			
		}

		if(varPisaderasLaterales){
			$('#pisaderasLaterales').attr('checked',true);
			agregarElementoCajaContenedora("Pisaderas Laterales", "pisaderasLateralesCC", "botonEliminar_pisaderasLaterales");
		}

		if(varNeu_MedioUso){
			$('#neumaticos').attr('checked',true);
			$('#neu_MedioUso').attr('checked',true);
			agregarElementoCajaContenedora("Neumaticos(Medio Uso)", "neu_MedioUsoCC", "botonEliminar_neuMedioUso");	
		}

		if(varParabrisasAlternativo){
			$('#parabrisasAlternativo').attr('checked',true);
			agregarElementoCajaContenedora("Parabrisas Alternativo", "parabrisasAlternativoCC", "botonEliminar_parabrisasAlternativo");
		}

		if(varBarraAntivuelco){
			$('#barraAntivuelco').attr('checked',true);
			agregarElementoCajaContenedora("Barra Antivuelco", "barraAntivuelcoCC", "botonEliminar_barraAntivuelco");
		}

		if(varNeu_Gastados){
			$('#neumaticos').attr('checked',true);
			$('#neu_Gastados').attr('checked',true);
			agregarElementoCajaContenedora("Neumaticos(Gastados)", "neu_GastadosCC", "botonEliminar_neuGastados");
			
		}

		/*******************************************/
		 $('#aireAcondicionado').click(function(){
		 	if (this.checked){
		 		agregarElementoCajaContenedora("Aire Acondicionado", "aireAcondicionadoCC", "botonEliminar_aireAcondicionado");
		 	}else{
		 		borrarElementoCaja("aireAcondicionadoCC");
		 	}
		 });
		 $("#botonEliminar_aireAcondicionado").live('click',function(e){
			e.preventDefault();
			borrarElementoCaja('aireAcondicionadoCC');
			$('#aireAcondicionado').attr('checked',false);
		});
		 $('#parabrisasOriginal').click(function(){
		 	if (this.checked){
		 		agregarElementoCajaContenedora("Parabrisas Original", "parabrisasOriginalCC", "botonEliminar_parabrisasOriginal");
		 	}else{
		 		borrarElementoCaja("parabrisasOriginalCC");
		 	}
		 });
		 $("#botonEliminar_parabrisasOriginal").live('click',function(e){
			e.preventDefault();
			borrarElementoCaja('parabrisasOriginalCC');
			$('#parabrisasOriginal').attr('checked',false);
		});
		 $('#vidriosPolarizados').click(function(){
		 	if (this.checked){
		 		agregarElementoCajaContenedora("Vidrios Polarizados", "vidriosPolarizadosCC", "botonEliminar_vidriosPolarizados");
		 	}else{
		 		borrarElementoCaja("vidriosPolarizadosCC");
		 	}
		 });
		 $("#botonEliminar_vidriosPolarizados").live('click',function(e){
			e.preventDefault();
			borrarElementoCaja('vidriosPolarizadosCC');
			$('#vidriosPolarizados').attr('checked',false);
		});
		 $('#cilindrosDeSeguridad').click(function(){
		 	if (this.checked){
		 		agregarElementoCajaContenedora("Cilindros de Seguridad", "cilindrosDeSeguridadCC", "botonEliminar_cilindrosDeSeguridad");
		 	}else{
		 		borrarElementoCaja("cilindrosDeSeguridadCC");
		 	}
		 });
		 $("#botonEliminar_cilindrosDeSeguridad").live('click',function(e){
			e.preventDefault();
			borrarElementoCaja('cilindrosDeSeguridadCC');
			$('#cilindrosDeSeguridad').attr('checked',false);
		});
		 $('#techoLona').click(function(){
		 	if (this.checked){
		 		agregarElementoCajaContenedora("Techo Lona", "techoLonaCC", "botonEliminar_techoLona");
		 	}else{
		 		borrarElementoCaja("techoLonaCC");
		 	}
		 });
		 $("#botonEliminar_techoLona").live('click',function(e){
			e.preventDefault();
			borrarElementoCaja('techoLonaCC');
			$('#techoLona').attr('checked',false);
		});
		 $('#techoFibra').click(function(){
		 	if (this.checked){
		 		agregarElementoCajaContenedora("Techo Fibra", "techoFibraCC", "botonEliminar_techoFibra");
		 	}else{
		 		borrarElementoCaja("techoFibraCC");
		 	}
		 });
		 $("#botonEliminar_techoFibra").live('click',function(e){
			e.preventDefault();
			borrarElementoCaja('techoFibraCC');
			$('#techoFibra').attr('checked',false);
		});
		 $('#huinche').click(function(){
		 	if (this.checked){
		 		agregarElementoCajaContenedora("Huinche", "huincheCC", "botonEliminar_huinche");
		 	}else{
		 		borrarElementoCaja("huincheCC");
		 	}
		 });
		 $("#botonEliminar_huinche").live('click',function(e){
			e.preventDefault();
			borrarElementoCaja('huincheCC');
			$('#huinche').attr('checked',false);
		});
		 $('#laminasDeSeguridad').click(function(){
		 	if (this.checked){
		 		agregarElementoCajaContenedora("Láminas de Seguridad", "laminasDeSeguridadCC", "botonEliminar_laminasDeSeguridad");
		 	}else{
		 		borrarElementoCaja("laminasDeSeguridadCC");
		 	}
		 });
		 $("#botonEliminar_laminasDeSeguridad").live('click',function(e){
			e.preventDefault();
			borrarElementoCaja('laminasDeSeguridadCC');
			$('#laminasDeSeguridad').attr('checked',false);
		});
		 $('#equipoDeFrio').click(function(){
		 	if (this.checked){
		 		agregarElementoCajaContenedora("Equipo de Frio", "equipoDeFrioCC", "botonEliminar_equipoDeFrio");
		 	}else{
		 		borrarElementoCaja("equipoDeFrioCC");
		 	}
		 });
		 $("#botonEliminar_equipoDeFrio").live('click',function(e){
			e.preventDefault();
			borrarElementoCaja('equipoDeFrioCC');
			$('#equipoDeFrio').attr('checked',false);
		});
		 $('#parrillaAmericana').click(function(){
		 	if (this.checked){
		 		agregarElementoCajaContenedora("Parrilla Americana", "parrillaAmericanaCC", "botonEliminar_parrillaAmericana");
		 	}else{
		 		borrarElementoCaja("parrillaAmericanaCC");
		 	}
		 });
		 $("#botonEliminar_parrillaAmericana").live('click',function(e){
			e.preventDefault();
			borrarElementoCaja('parrillaAmericanaCC');
			$('#parrillaAmericana').attr('checked',false);
		});
		 $('#cupula').click(function(){
		 	if (this.checked){
		 		agregarElementoCajaContenedora("Cúpula", "cupulaCC", "botonEliminar_cupula");
		 	}else{
		 		borrarElementoCaja("cupulaCC");
		 	}
		 });
		 $("#botonEliminar_cupula").live('click',function(e){
			e.preventDefault();
			borrarElementoCaja('cupulaCC');
			$('#cupula').attr('checked',false);
		});
		 $('#cubrePickUp').click(function(){
		 	if (this.checked){
		 		agregarElementoCajaContenedora("Cubre PickUp", "cubrePickUpCC", "botonEliminar_cubrePickUp");
		 	}else{
		 		borrarElementoCaja("cubrePickUpCC");
		 	}
		 });
		 $("#botonEliminar_cubrePickUp").live('click',function(e){
			e.preventDefault();
			borrarElementoCaja('cubrePickUpCC');
			$('#cubrePickUp').attr('checked',false);
		});

		 $('#cierreCentralizado').click(function(){
		 	if (this.checked){
		 		agregarElementoCajaContenedora("Cierre Centralizado", "cierreCentralizadoCC", "botonEliminar_cierreCentralizado");
		 	}else{
		 		borrarElementoCaja("cierreCentralizadoCC");
		 	}
		 });
		 $("#botonEliminar_cierreCentralizado").live('click',function(e){
			e.preventDefault();
			borrarElementoCaja('cierreCentralizadoCC');
			$('#cierreCentralizado').attr('checked',false);
		});
		 $('#controlCrucero').click(function(){
		 	if (this.checked){
		 		agregarElementoCajaContenedora("Control Crucero", "controlCruceroCC", "botonEliminar_controlCrucero");
		 	}else{
		 		borrarElementoCaja("controlCruceroCC");
		 	}
		 });
		 $("#botonEliminar_controlCrucero").live('click',function(e){
			e.preventDefault();
			borrarElementoCaja('controlCruceroCC');
			$('#controlCrucero').attr('checked',false);
		});
		 $('#cocoDeArrastre').click(function(){
		 	if (this.checked){
		 		agregarElementoCajaContenedora("Coco De Arrastre", "cocoDeArrastreCC", "botonEliminar_cocoDeArrastre");
		 	}else{
		 		borrarElementoCaja("cocoDeArrastreCC");
		 	}
		 });
		 $("#botonEliminar_cocoDeArrastre").live('click',function(e){
			e.preventDefault();
			borrarElementoCaja('cocoDeArrastreCC');
			$('#cocoDeArrastre').attr('checked',false);
		});
		 $('#alarma').click(function(){
		 	if (this.checked){
		 		agregarElementoCajaContenedora("Alarma", "alarmaCC", "botonEliminar_alarma");
		 	}else{
		 		borrarElementoCaja("alarmaCC");
		 	}
		 });
		 $("#botonEliminar_alarma").live('click',function(e){
			e.preventDefault();
			borrarElementoCaja('alarmaCC');
			$('#alarma').attr('checked',false);
		});
		 $('#vidriosGrabados').click(function(){
		 	if (this.checked){
		 		agregarElementoCajaContenedora("Vidrios Grabados", "vidriosGrabadosCC", "botonEliminar_vidriosGrabados");
		 	}else{
		 		borrarElementoCaja("vidriosGrabadosCC");
		 	}
		 });
		 $("#botonEliminar_vidriosGrabados").live('click',function(e){
			e.preventDefault();
			borrarElementoCaja('vidriosGrabadosCC');
			$('#vidriosGrabados').attr('checked',false);
		});
		 $('#aleron').click(function(){
		 	if (this.checked){
		 		agregarElementoCajaContenedora("Alerón", "aleronCC", "botonEliminar_aleron");
		 	}else{
		 		borrarElementoCaja("aleronCC");
		 	}
		 });
		 $("#botonEliminar_aleron").live('click',function(e){
			e.preventDefault();
			borrarElementoCaja('aleronCC');
			$('#aleron').attr('checked',false);
		});
		 $('#bluetooth').click(function(){
		 	if (this.checked){
		 		agregarElementoCajaContenedora("Bluetooth", "bluetoothCC", "botonEliminar_bluetooth");
		 	}else{
		 		borrarElementoCaja("bluetoothCC");
		 	}
		 });
		 $("#botonEliminar_bluetooth").live('click',function(e){
			e.preventDefault();
			borrarElementoCaja('bluetoothCC');
			$('#bluetooth').attr('checked',false);
		});
		 $('#sistemaABS').click(function(){
		 	if (this.checked){
		 		agregarElementoCajaContenedora("Sistema ABS", "sistemaABSCC", "botonEliminar_sistemaABS");
		 	}else{
		 		borrarElementoCaja("sistemaABSCC");
		 	}
		 });
		 $("#botonEliminar_sistemaABS").live('click',function(e){
			e.preventDefault();
			borrarElementoCaja('sistemaABSCC');
			$('#sistemaABS').attr('checked',false);
		});
		 $('#cortinaRetractil').click(function(){
		 	if (this.checked){
		 		agregarElementoCajaContenedora("Cortina Retráctil", "cortinaRetractilCC", "botonEliminar_cortinaRetractil");
		 	}else{
		 		borrarElementoCaja("cortinaRetractilCC");
		 	}
		 });
		 $("#botonEliminar_cortinaRetractil").live('click',function(e){
			e.preventDefault();
			borrarElementoCaja('cortinaRetractilCC');
			$('#cortinaRetractil').attr('checked',false);
		});
		 $('#climatizador').click(function(){
		 	if (this.checked){
		 		agregarElementoCajaContenedora("Climatizador", "climatizadorCC", "botonEliminar_climatizador");
		 	}else{
		 		borrarElementoCaja("climatizadorCC");
		 	}
		 });
		 $("#botonEliminar_climatizador").live('click',function(e){
			e.preventDefault();
			borrarElementoCaja('climatizadorCC');
			$('#climatizador').attr('checked',false);
		});
		 $('#airBag').click(function(){
		 	if (this.checked){
		 		agregarElementoCajaContenedora("Air Bag", "airBagCC", "botonEliminar_airBag");
		 	}else{
		 		borrarElementoCaja("airBagCC");
		 	}
		 });
		 $("#botonEliminar_airBag").live('click',function(e){
			e.preventDefault();
			borrarElementoCaja('airBagCC');
			$('#airBag').attr('checked',false);
		});
		 $('#sensorAcercamiento').click(function(){
		 	if (this.checked){
		 		agregarElementoCajaContenedora("Sensor Acercamiento", "sensorAcercamientoCC", "botonEliminar_sensorAcercamiento");
		 	}else{
		 		borrarElementoCaja("sensorAcercamientoCC");
		 	}
		 });
		 $("#botonEliminar_sensorAcercamiento").live('click',function(e){
			e.preventDefault();
			borrarElementoCaja('sensorAcercamientoCC');
			$('#sensorAcercamiento').attr('checked',false);
		});
		 $('#moldurasPasoRueda').click(function(){
		 	if (this.checked){
		 		agregarElementoCajaContenedora("Molduras Paso Rueda", "moldurasPasoRuedaCC", "botonEliminar_moldurasPasoRueda");
		 	}else{
		 		borrarElementoCaja("moldurasPasoRuedaCC");
		 	}
		 });
		 $("#botonEliminar_moldurasPasoRueda").live('click',function(e){
			e.preventDefault();
			borrarElementoCaja('moldurasPasoRuedaCC');
			$('#moldurasPasoRueda').attr('checked',false);
		});
		 $('#sensorRetroceso').click(function(){
		 	if (this.checked){
		 		agregarElementoCajaContenedora("Sensor Retroceso", "sensorRetrocesoCC", "botonEliminar_sensorRetroceso");
		 	}else{
		 		borrarElementoCaja("sensorRetrocesoCC");
		 	}
		 });
		 $("#botonEliminar_sensorRetroceso").live('click',function(e){
			e.preventDefault();
			borrarElementoCaja('sensorRetrocesoCC');
			$('#sensorRetroceso').attr('checked',false);
		});
		 $('#conosRueda').click(function(){
		 	if (this.checked){
		 		agregarElementoCajaContenedora("Conos Rueda", "conosRuedaCC", "botonEliminar_conosRueda");
		 	}else{
		 		borrarElementoCaja("conosRuedaCC");
		 	}
		 });
		 $("#botonEliminar_conosRueda").live('click',function(e){
			e.preventDefault();
			borrarElementoCaja('conosRuedaCC');
			$('#conosRueda').attr('checked',false);
		});
		 $('#ganchosAmarre').click(function(){
		 	if (this.checked){
		 		agregarElementoCajaContenedora("Ganchos Amarre", "ganchosAmarreCC", "botonEliminar_ganchosAmarre");
		 	}else{
		 		borrarElementoCaja("ganchosAmarreCC");
		 	}
		 });
		 $("#botonEliminar_ganchosAmarre").live('click',function(e){
			e.preventDefault();
			borrarElementoCaja('ganchosAmarreCC');
			$('#ganchosAmarre').attr('checked',false);
		});
		 $('#neblineros').click(function(){
		 	if (this.checked){
		 		agregarElementoCajaContenedora("Neblineros", "neblinerosCC", "botonEliminar_neblineros");
		 	}else{
		 		borrarElementoCaja("neblinerosCC");
		 	}
		 });
		 $("#botonEliminar_neblineros").live('click',function(e){
			e.preventDefault();
			borrarElementoCaja('neblinerosCC');
			$('#neblineros').attr('checked',false);
		});
		 $('#tapasDeRueda').click(function(){
		 	if (this.checked){
		 		agregarElementoCajaContenedora("Tapas de Rueda", "tapasDeRuedaCC", "botonEliminar_tapasDeRueda");
		 	}else{
		 		borrarElementoCaja("tapasDeRuedaCC");
		 	}
		 });
		 $("#botonEliminar_tapasDeRueda").live('click',function(e){
			e.preventDefault();
			borrarElementoCaja('tapasDeRuedaCC');
			$('#tapasDeRueda').attr('checked',false);
		});
		 $('#pisaderasLaterales').click(function(){
		 	if (this.checked){
		 		agregarElementoCajaContenedora("Pisaderas Laterales", "pisaderasLateralesCC", "botonEliminar_pisaderasLaterales");
		 	}else{
		 		borrarElementoCaja("pisaderasLateralesCC");
		 	}
		 });
		 $("#botonEliminar_pisaderasLaterales").live('click',function(e){
			e.preventDefault();
			borrarElementoCaja('pisaderasLateralesCC');
			$('#pisaderasLaterales').attr('checked',false);
		});
		 $('#parabrisasAlternativo').click(function(){
		 	if (this.checked){
		 		agregarElementoCajaContenedora("Parabrisas Alternativo", "parabrisasAlternativoCC", "botonEliminar_parabrisasAlternativo");
		 	}else{
		 		borrarElementoCaja("parabrisasAlternativoCC");
		 	}
		 });
		 $("#botonEliminar_parabrisasAlternativo").live('click',function(e){
			e.preventDefault();
			borrarElementoCaja('parabrisasAlternativoCC');
			$('#parabrisasAlternativo').attr('checked',false);
		});
		 $('#barraAntivuelco').click(function(){
		 	if (this.checked){
		 		agregarElementoCajaContenedora("Barra Antivuelco", "barraAntivuelcoCC", "botonEliminar_barraAntivuelco");
		 	}else{
		 		borrarElementoCaja("barraAntivuelcoCC");
		 	}
		 });
		 $("#botonEliminar_barraAntivuelco").live('click',function(e){
			e.preventDefault();
			borrarElementoCaja('barraAntivuelcoCC');
			$('#barraAntivuelco').attr('checked',false);
		});
		 $('#llantasAleacion').click(function(){
		 	if (this.checked){
		 		agregarElementoCajaContenedora("Llantas Aleación", "llantasAleacionCC", "botonEliminar_llantasAleacion");
		 	}else{
		 		borrarElementoCaja("llantasAleacionCC");
		 	}
		 });
		 $("#botonEliminar_llantasAleacion").live('click',function(e){
			e.preventDefault();
			borrarElementoCaja('llantasAleacionCC');
			$('#llantasAleacion').attr('checked',false);
		});
		 $('#llantasFierro').click(function(){
		 	if (this.checked){
		 		agregarElementoCajaContenedora("Llantas Fierro", "llantasFierroCC", "botonEliminar_llantasFierro");
		 	}else{
		 		borrarElementoCaja("llantasFierroCC");
		 	}
		 });
		 $("#botonEliminar_llantasFierro").live('click',function(e){
			e.preventDefault();
			borrarElementoCaja('llantasFierroCC');
			$('#llantasFierro').attr('checked',false);
		});
/*
############################################################################################################################################################################################################################################################################################################################################################################################################################################################################################################################################################################################################################################################################################################################################################################################################################################################################################################################################################
*/
		 $('#espejos').click(function(){
		 	if (this.checked){
		 		$('#esp_electricos').attr('checked',true);
		 		agregarElementoCajaContenedora("Espejos(Eléctricos)", "esp_ElectricosCC", "botonEliminar_espElectricos");
		 		borrarElementoCaja("esp_ManualesCC");
		 	}else{
		 		$('#esp_electricos').attr('checked',false);
		 		$('#esp_manuales').attr('checked',false);
		 		borrarDosElementosCaja("esp_ElectricosCC", "esp_ManualesCC");
		 	}
		 });
		$('#esp_electricos').click(function(){
			 if (this.checked){
			 	$('#espejos').attr('checked',true);
			 	$('#esp_manuales').attr('checked',false);
			 	agregarElementoCajaContenedora("Espejos(Eléctricos)", "esp_ElectricosCC", "botonEliminar_espElectricos");
			 	borrarElementoCaja("esp_ManualesCC");
			 }else{
			 	$('#espejos').attr('checked',false);
			 	borrarDosElementosCaja("esp_ElectricosCC", "esp_ManualesCC");
			 }
		});
		$('#esp_manuales').click(function(){
			 if (this.checked){
			 	$('#espejos').attr('checked',true);
			 	$('#esp_electricos').attr('checked',false);
			 	agregarElementoCajaContenedora("Espejos(Manuales)", "esp_ManualesCC", "botonEliminar_espManuales");
			 	borrarElementoCaja("esp_ElectricosCC");
			 }else{
			 	$('#espejos').attr('checked',false);
			 	borrarDosElementosCaja("esp_ElectricosCC", "esp_ManualesCC");
			 }
		});
		$("#botonEliminar_espElectricos").live('click',function(e){
			e.preventDefault();
			borrarElementoCaja('esp_ElectricosCC');
			$('#espejos').attr('checked',false);
			$('#esp_electricos').attr('checked',false);
		});
		$("#botonEliminar_espManuales").live('click',function(e){
			e.preventDefault();
			borrarElementoCaja('esp_ManualesCC');
			$('#espejos').attr('checked',false);
			$('#esp_manuales').attr('checked',false);
		});
		$('#vidrios').click(function(){
		 	if (this.checked){
		 		$('#vid_electricos').attr('checked',true);
		 		agregarElementoCajaContenedora("Vidrios(Eléctricos)", "vid_ElectricoCC", "botonEliminar_vidElectrico");
		 		borrarElementoCaja("vid_ManualesCC");
		 	}else{
		 		$('#vid_electricos').attr('checked',false);
		 		$('#vid_manuales').attr('checked',false);
		 		borrarDosElementosCaja("vid_ElectricoCC", "vid_ManualesCC");
		 	}
		 });
		$('#vid_electricos').click(function(){
			 if (this.checked){
			 	$('#vidrios').attr('checked',true);
			 	$('#vid_manuales').attr('checked',false);
			 	agregarElementoCajaContenedora("Vidrios(Eléctricos)", "vid_ElectricoCC", "botonEliminar_vidElectrico");
			 	borrarElementoCaja("vid_ManualesCC");
			 }else{
			 	$('#vidrios').attr('checked',false);
			 	borrarDosElementosCaja("vid_ElectricoCC", "vid_ManualesCC");
			 }
		});
		$('#vid_manuales').click(function(){
			 if (this.checked){
			 	$('#vidrios').attr('checked',true);
			 	$('#vid_electricos').attr('checked',false);
			 	agregarElementoCajaContenedora("Vidrios(Manuales)", "vid_ManualesCC", "botonEliminar_vidManuales");
			 	borrarElementoCaja("vid_ElectricoCC");
			 }else{
			 	$('#vidrios').attr('checked',false);
			 	borrarDosElementosCaja("vid_ElectricoCC", "vid_ManualesCC");
			 }
		});
		$("#botonEliminar_vidElectrico").live('click',function(e){
			e.preventDefault();
			borrarElementoCaja('vid_ElectricoCC');
			$('#vidrios').attr('checked',false);
			$('#vid_electricos').attr('checked',false);
		});
		$("#botonEliminar_vidManuales").live('click',function(e){
			e.preventDefault();
			borrarElementoCaja('vid_ManualesCC');
			$('#vidrios').attr('checked',false);
			$('#vid_manuales').attr('checked',false);
		});
		$('#sunRoof').click(function(){
		 	if (this.checked){
		 		$('#sun_electricos').attr('checked',true);
		 		agregarElementoCajaContenedora("Sun Roof(Eléctrico)", "sun_ElectricoCC", "botonEliminar_sunElectrico");
		 		borrarElementoCaja("sun_ManualCC");
		 	}else{
		 		$('#sun_electricos').attr('checked',false);
		 		$('#sun_manuales').attr('checked',false);
		 		borrarDosElementosCaja("sun_ElectricoCC", "sun_ManualCC");
		 	}
		 });
		$('#sun_electricos').click(function(){
			 if (this.checked){
			 	$('#sunRoof').attr('checked',true);
			 	$('#sun_manuales').attr('checked',false);
			 	agregarElementoCajaContenedora("Sun Roof(Elétrico)", "sun_ElectricoCC", "botonEliminar_sunElectrico");
			 	borrarElementoCaja("sun_ManualCC");
			 }else{
			 	$('#sunRoof').attr('checked',false);
			 	borrarDosElementosCaja("sun_ElectricoCC", "sun_ManualCC");
			 }
		});
		$('#sun_manuales').click(function(){
			 if (this.checked){
			 	$('#sunRoof').attr('checked',true);
			 	$('#sun_electricos').attr('checked',false);
			 	agregarElementoCajaContenedora("Sun Roof(Manual)", "sun_ManualCC", "botonEliminar_sunManual");
			 	borrarElementoCaja("sun_ElectricoCC");
			 }else{
			 	$('#sunRoof').attr('checked',false);
			 	borrarDosElementosCaja("sun_ElectricoCC", "sun_ManualCC");
			 }
		});
		$("#botonEliminar_sunElectrico").live('click',function(e){
			e.preventDefault();
			borrarElementoCaja('sun_ElectricoCC');
			$('#sunRoof').attr('checked',false);
			$('#sun_electricos').attr('checked',false);
		});
		$("#botonEliminar_sunManual").live('click',function(e){
			e.preventDefault();
			borrarElementoCaja('sun_ManualCC');
			$('#sunRoof').attr('checked',false);
			$('#sun_manuales').attr('checked',false);
		});
		$('#ruedaRepuesto').click(function(){
		 	if (this.checked){
		 		$('#llantaAleacion').attr('checked',true);
		 		agregarElementoCajaContenedora("Rueda Repuesto(Aleación)", "rr_llantaAleacionCC", "botonEliminar_rrLlantaAleacion");
		 		borrarElementoCaja("rr_llantaFierroCC");
		 	}else{
		 		$('#llantaAleacion').attr('checked',false);
		 		$('#llantaFierro').attr('checked',false);
		 		borrarDosElementosCaja("rr_llantaAleacionCC", "rr_llantaFierroCC");
		 	}
		 });
		$('#llantaAleacion').click(function(){
			 if (this.checked){
			 	$('#ruedaRepuesto').attr('checked',true);
			 	$('#llantaFierro').attr('checked',false);
			 	agregarElementoCajaContenedora("Rueda Repuesto(Aleación)", "rr_llantaAleacionCC", "botonEliminar_rrLlantaAleacion");
			 	borrarElementoCaja("rr_llantaFierroCC");
			 }else{
			 	$('#ruedaRepuesto').attr('checked',false);
			 	borrarDosElementosCaja("rr_llantaAleacionCC", "rr_llantaFierroCC");
			 }
		});
		$('#llantaFierro').click(function(){
			 if (this.checked){
			 	$('#ruedaRepuesto').attr('checked',true);
			 	$('#llantaAleacion').attr('checked',false);
			 	agregarElementoCajaContenedora("Rueda Repuesto(Fierro)", "rr_llantaFierroCC", "botonEliminar_rrLlantaFierro");
			 	borrarElementoCaja("rr_llantaAleacionCC");
			 }else{
			 	$('#ruedaRepuesto').attr('checked',false);
			 	borrarDosElementosCaja("rr_llantaAleacionCC", "rr_llantaFierroCC");
			 }
		});
		$("#botonEliminar_rrLlantaAleacion").live('click',function(e){
			e.preventDefault();
			borrarElementoCaja('rr_llantaAleacionCC');
			$('#ruedaRepuesto').attr('checked',false)
			$('#llantaAleacion').attr('checked',false);
		});
		$("#botonEliminar_rrLlantaFierro").live('click',function(e){
			e.preventDefault();
			borrarElementoCaja('rr_llantaFierroCC');
			$('#ruedaRepuesto').attr('checked',false)
			$('#llantaFierro').attr('checked',false);
		});
		$('#antena').click(function(){
		 	if (this.checked){
		 		$('#ant_manual').attr('checked',true);
		 		agregarElementoCajaContenedora("Antena(Manual)", "ant_ManualCC", "botonEliminar_antManual");
		 		borrarElementoCaja("ant_ElectricaCC");
		 	}else{
		 		$('#ant_manual').attr('checked',false);
		 		$('#ant_electrica').attr('checked',false);
		 		borrarDosElementosCaja("ant_ManualCC", "ant_ElectricaCC");
		 	}
		 });
		$('#ant_manual').click(function(){
			 if (this.checked){
			 	$('#antena').attr('checked',true);
			 	$('#ant_electrica').attr('checked',false);
			 	agregarElementoCajaContenedora("Antena(Manual)", "ant_ManualCC", "botonEliminar_antManual");
			 	borrarElementoCaja("ant_ElectricaCC");
			 }else{
			 	$('#antena').attr('checked',false);
			 	borrarDosElementosCaja("ant_ManualCC", "ant_ElectricaCC");
			 }
		});
		$('#ant_electrica').click(function(){
			 if (this.checked){
			 	$('#antena').attr('checked',true);
			 	$('#ant_manual').attr('checked',false);
			 	agregarElementoCajaContenedora("Antena(Electrica)", "ant_ElectricaCC", "botonEliminar_antElectrica");
			 	borrarElementoCaja("ant_ManualCC");
			 }else{
			 	$('#antena').attr('checked',false);
			 	borrarDosElementosCaja("ant_ManualCC", "ant_ElectricaCC");
			 }
		});
		$("#botonEliminar_antManual").live('click',function(e){
			e.preventDefault();
			borrarElementoCaja('ant_ManualCC');
			$('#antena').attr('checked',false)
			$('#ant_manual').attr('checked',false);
		});
		$("#botonEliminar_antElectrica").live('click',function(e){
			e.preventDefault();
			borrarElementoCaja('ant_ElectricaCC');
			$('#antena').attr('checked',false)
			$('#ant_electrica').attr('checked',false);
		});
		$('#tapiceria').click(function(){
		 	if (this.checked){
		 		$('#tap_cuero').attr('checked',true);
		 		agregarElementoCajaContenedora("Tapicería(Cuero)", "tap_CueroCC", "botonEliminar_tapCuero");
		 		borrarElementoCaja("tap_NormalCC");
		 	}else{
		 		$('#tap_cuero').attr('checked',false);
		 		$('#tap_normal').attr('checked',false);
		 		borrarDosElementosCaja("tap_CueroCC", "tap_NormalCC");
		 	}
		 });
		$('#tap_cuero').click(function(){
			 if (this.checked){
			 	$('#tapiceria').attr('checked',true);
			 	$('#tap_normal').attr('checked',false);
			 	agregarElementoCajaContenedora("Tapicería(Cuero)", "tap_CueroCC", "botonEliminar_tapCuero");
			 	borrarElementoCaja("tap_NormalCC");
			 }else{
				$('#tapiceria').attr('checked',false);
				borrarDosElementosCaja("tap_CueroCC", "tap_NormalCC");
			 }
		});
		$('#tap_normal').click(function(){
			 if (this.checked){
			 	$('#tapiceria').attr('checked',true);
			 	$('#tap_cuero').attr('checked',false);
			 	agregarElementoCajaContenedora("Tapicería(Normal)", "tap_NormalCC", "botonEliminar_tapNormal");
			 	borrarElementoCaja("tap_CueroCC");
			 }else{
				$('#tapiceria').attr('checked',false);
				borrarDosElementosCaja("tap_CueroCC", "tap_NormalCC");
			 }
		});
		$("#botonEliminar_tapCuero").live('click',function(e){
			e.preventDefault();
			borrarElementoCaja('tap_CueroCC');
			$('#tapiceria').attr('checked',false)
			$('#tap_cuero').attr('checked',false);
		});

		$("#botonEliminar_tapNormal").live('click',function(e){
			e.preventDefault();
			borrarElementoCaja('tap_NormalCC');
			$('#tapiceria').attr('checked',false)
			$('#tap_normal').attr('checked',false);
		});
		$('#neumaticos').click(function(){
		 	if (this.checked){
		 		$('#neu_nuevos').attr('checked',true);
		 		agregarElementoCajaContenedora("Neumaticos(Nuevos)", "neu_NuevosCC", "botonEliminar_neuNuevos");
		 		borrarDosElementosCaja("neu_MedioUsoCC", "neu_GastadosCC");
		 	}else{
		 		$('#neu_nuevos').attr('checked',false);
		 		$('#neu_MedioUso').attr('checked',false);
		 		$('#neu_Gastados').attr('checked',false);
		 		borrarTresElementosCaja("neu_NuevosCC","neu_MedioUsoCC","neu_GastadosCC");
		 		//alert("Entra");
		 	}
		 });
		$('#neu_nuevos').click(function(){
			 if (this.checked){
			 	$('#neumaticos').attr('checked',true);
			 	$('#neu_MedioUso').attr('checked',false);
			 	$('#neu_Gastados').attr('checked',false);
			 	agregarElementoCajaContenedora("Neumaticos(Nuevos)", "neu_NuevosCC", "botonEliminar_neuNuevos");
			 	borrarDosElementosCaja("neu_MedioUsoCC", "neu_GastadosCC");
			 }else{
			 	$('#neumaticos').attr('checked',false);
			 	borrarTresElementosCaja("neu_NuevosCC","neu_MedioUsoCC","neu_GastadosCC");
			 }
		});
		$('#neu_MedioUso').click(function(){
			 if (this.checked){
			 	$('#neumaticos').attr('checked',true);
			 	$('#neu_nuevos').attr('checked',false);
			 	$('#neu_Gastados').attr('checked',false);
			 	agregarElementoCajaContenedora("Neumaticos(Medio Uso)", "neu_MedioUsoCC", "botonEliminar_neuMedioUso");
			 	borrarDosElementosCaja("neu_NuevosCC","neu_GastadosCC");
			 }else{
			 	$('#neumaticos').attr('checked',false);
			 	borrarTresElementosCaja("neu_NuevosCC","neu_MedioUsoCC","neu_GastadosCC");
			 }
		});
		$('#neu_Gastados').click(function(){
			 if (this.checked){
			 	$('#neumaticos').attr('checked',true);
			 	$('#neu_MedioUso').attr('checked',false);
			 	$('#neu_nuevos').attr('checked',false);
			 	agregarElementoCajaContenedora("Neumaticos(Gastados)", "neu_GastadosCC", "botonEliminar_neuGastados");
			 	borrarDosElementosCaja("neu_NuevosCC","neu_MedioUsoCC");
			 }else{
			 	$('#neumaticos').attr('checked',false);
			 	borrarTresElementosCaja("neu_NuevosCC","neu_MedioUsoCC","neu_GastadosCC");
			 }
		});

		$("#botonEliminar_neuNuevos").live('click',function(e){
			e.preventDefault();
			borrarElementoCaja('neu_NuevosCC');
			$('#neumaticos').attr('checked',false)
			$('#neu_nuevos').attr('checked',false);
		});

		$("#botonEliminar_neuMedioUso").live('click',function(e){
			e.preventDefault();
			borrarElementoCaja('neu_MedioUsoCC');
			$('#neumaticos').attr('checked',false)
			$('#neu_MedioUso').attr('checked',false);
		});
		$("#botonEliminar_neuGastados").live('click',function(e){
			e.preventDefault();
			borrarElementoCaja('neu_GastadosCC');
			$('#neumaticos').attr('checked',false)
			$('#neu_Gastados').attr('checked',false);
		});
	});
</script>


<script type="text/javascript">
/****************** SUBIDA DE IMAGENES ***********************/
//funcion(idFormulario,idInputTipoFile,idDivContenedorDeLaImagen)
	function imageUpload(form, inputfile, preview){

	    $(preview).html('<?php echo image_tag("loader.gif", "class=loader_gif") ?>');
        $(form).ajaxForm({
        	target: preview
        }).submit();
        
	}

/******************** caja contenedora ***************/
	function agregarElementoCajaContenedora(nombreOpcion, idElemento, idOpcionEliminar){
		 	elementoCerrar = document.createElement('a');
		 	elementoCerrar.appendChild(document.createTextNode('[X]'));
		 	elementoCerrar.id = idOpcionEliminar;
		 	elementoCerrar.href = "";

		 	elementoParrafo = document.createElement('span');
		 	elementoParrafo.appendChild(document.createTextNode(nombreOpcion));

		 	elementoPadre = document.createElement('p');
		elementoPadre.id = idElemento;

		elementoPadre.appendChild(elementoCerrar);
		elementoPadre.appendChild(elementoParrafo);

		$('#cajaContenedora').append(elementoPadre);
	}
	function eliminarElementoCajaContenedora(elemento){
		if(elemento){
			elemento.parentNode.removeChild(elemento);
		}
	}
	function borrarTresElementosCaja(elemento_1, elemento_2, elemento_3){
		elemento = document.getElementById(elemento_1);
		if(elemento) eliminarElementoCajaContenedora(elemento);
		elemento2 = document.getElementById(elemento_2);
	 	if(elemento2) eliminarElementoCajaContenedora(elemento2);
	 	elemento3 = document.getElementById(elemento_3);
	 	if(elemento3) eliminarElementoCajaContenedora(elemento3);
	}
	function borrarDosElementosCaja(elemento_1, elemento_2){
		elemento = document.getElementById(elemento_1);
		if(elemento) eliminarElementoCajaContenedora(elemento);
		elemento2 = document.getElementById(elemento_2);
	 	if(elemento2) eliminarElementoCajaContenedora(elemento2);
	}
	function borrarElementoCaja(elemento_1){
		elemento = document.getElementById(elemento_1);
		if(elemento){
			eliminarElementoCajaContenedora(elemento);
		}
	}

/************************ PASO 2 *********************************/
	
	//alert(redireccionar);
	//alert(coordX);
  function mapaAuto(){
  	//alert('entro');
  	//cantidad de daños ingresados
    var cantDanios = 0;
    camposIncompletos = false;

    var mapOptions = {
      center: new google.maps.LatLng(-89.73797816536283, 1.0546875),
      zoom: 2,
      mapTypeControlOptions: {
        mapTypeIds: ['car']
      },
      zoomControl: false,
      streetViewControl: false,
      backgroundColor: 'white'
    };

    var map = new google.maps.Map(document.getElementById('map_canvas'), mapOptions);
    map.mapTypes.set('car', carMapType);
    map.setMapTypeId('car');

    /* crear un marcador al presionar sobre el mapa */
    google.maps.event.addListener(map, 'click', function(event) {
      //alert(event.latLng);
      limpiarDatos();
      if(!camposIncompletos){
        placeMarker(event.latLng, map,true);
      }

    });
    /*
    if(redireccionar){
		for(var i=0; i<coordX.length; i++){
			alert(lat[i]);
			alert(lng[i]);
			alert(coordX[i]);
			alert(coordY[i]);
			alert(descripcion[i]);
			alert(urlFoto[i]);
		}
	}
	*/
    //crea los marcadores que ya se han ingresado a la base de datos
    
    if(redireccionar){
    	for(var i=0; i<coordX.length; i++){
    		var position = new google.maps.LatLng(lat[i],lng[i]);
    		placeMarker(position,map,false);
		}
		//alert(lat[0]);
		camposIncompletos = false;
    }
	
    function placeMarker(position, map, ingresarDatos) {

      camposIncompletos = true;

      cantDanios++;
      var marker = new google.maps.Marker({
        position: position,
        map: map,
        title: ''+cantDanios,
        draggable: false
      });

      marker.setIcon('http://maps.google.com/mapfiles/marker_grey.png');
      map.panTo(position);

      google.maps.event.addListener(marker, 'click', function(){
      	if(!camposIncompletos){
	        cargarDatos(this);
	        ingresoDatos(this);
    	}
      });

      google.maps.event.addListener(marker, 'dragstart', function(){
        //posicion anterior como variable global
        posicionAnterior = obtenerPosicionPixel(this);
      });

      google.maps.event.addListener(marker, 'dragend', function(){
        actualizarPosicion(this);
      });

      if(ingresarDatos){
      	//alert("ingresar Datos");
      	ingresoDatos(marker);
      }
    }
    /* fin crear un marcador al presionar sobre el mapa */

  };


var carTypeOptions = {
    getTileUrl: function(coord, zoom) {
        return 'http://arriendas.assets.s3-website-sa-east-1.amazonaws.com/images/auto1024px.png';
    },
    tileSize: new google.maps.Size(1024, 1000),
    maxZoom: 2,
    minZoom: 2,
    name: 'Vehículo'
};

  var carMapType = new google.maps.ImageMapType(carTypeOptions);


  // Normalizes the coords that tiles repeat across the x axis (horizontally)
  // like the standard Google map tiles.
  function getNormalizedCoord(coord, zoom) {
    var y = coord.y;
    var x = coord.x;

    return {
      x: x,
      y: y
    };
  }

  function ingresoDatos(marker){


    //hace variable global al marcador para que se pueda eliminar
    if(typeof(marcador)!="undefined"){
    	marcador.setIcon('http://maps.google.com/mapfiles/marker_grey.png');	
    }
    marcador = marker;

    //alert(marker.getPosition());
    //var posicion = obtenerPosicionPixel(marcador);
    //alert(posicion.pixelX+' '+posicion.pixelY);
    marker.setIcon('http://maps.google.com/mapfiles/marker.png');
    $('#numeroDanio').text(marker.getTitle());
    $('#ingresoDatos').fadeIn('fast');

    //baja el scroll
    $('#descripcion').focus();

  }


$('#confirmar').on('click',function(e){
	e.preventDefault();
	//alert('entro');
	//guardar el daño en la base de datos

	var foto = $('#foto').val();
	var descripcion = $('#descripcion').val();
	var id = $('#id').val();

	//marcador es una variable global

	var lat = marcador.getPosition().lat();
    var lng = marcador.getPosition().lng();

	var posicion = obtenerPosicionPixel(marcador);
	//alert(posicion.pixelX+' '+posicion.pixelY);
	$('#posicionX').val(posicion.pixelX);
	$('#posicionY').val(posicion.pixelY);
	$('#lat').val(lat);
	$('#lng').val(lng);

	//validar que se ingresen todos los datos
	if(descripcion=='' || (foto=='' && id=='')){
	  alert('Debe ingresar todos los campos');
	}else{

	  //alert(posicion.pixelX+' '+posicion.pixelY);
	  //alert(foto);
	  //alert(descripcion);

	  camposIncompletos = false;

	  $("#opcion").val('ingresar');

	  $("#frmMapaAuto").submit();


	  //si se ingresa correctamente a la base de datos, ocultar capa
	  $('#ingresoDatos').fadeOut('fast');
	  limpiarDatos();
    }
});

$('#eliminar').on('click',function(e){
	e.preventDefault();
    //elimina el daño de la base de datos
    var posicion = obtenerPosicionPixel(marcador);

	$('#posicionX').val(posicion.pixelX);
	$('#posicionY').val(posicion.pixelY);
    
    $('#ingresoDatos').fadeOut('fast');

    //alert(marcador.getTitle());
    marcador.setVisible(false);

    camposIncompletos = false;

    $("#frmMapaAuto").submit();

    limpiarDatos();
});

  function cargarDatos(marcador){
    //carga los datos desde la base de datos
    //var posicion = obtenerPosicionPixel(marcador);
    //alert(posicion.pixelX+' '+posicion.pixelY);
    var title = marcador.getTitle();
    //alert(title);
    if(redireccionar){
    	//alert("entro1");
    	var pos = coordX.length -1;
    	if(title <= pos+1){
    		//alert("entro2");
    		//alert(descripcion[pos]);
    		$('#id').val(id[title-1]);
		    $('#fotoDanio').attr('src',urlAbsoluta+urlFoto[title-1]);
		 	$('#fotoDanio').addClass('fotoCargada');
		    $('#descripcion').val(descripcion[title-1]);
		    $('#lat').val(lat[title-1]);
		    $('#lng').val(lng[title-1]);
		    $('#posicionX').val(coordX[title-1]);
		    $('#posicionY').val(coordY[title-1]);
		    $('#urlfoto').val(urlFoto[title-1]);

		    //alert("entro3");

		    //ingresoDatos(marcador);
		    //alert("entro4");
    	}
    }

  }

  function actualizarPosicion(marcador){
    //actualiza la posición del marcador en la base de datos

    if(camposIncompletos == true){
      //alert('entro');
      ingresoDatos(marcador);
    }else{
      var posicion = obtenerPosicionPixel(marcador);

      alert(posicionAnterior.pixelX+' '+posicionAnterior.pixelY);
      alert(posicion.pixelX+' '+posicion.pixelY);

      limpiarDatos();
    }
  }

  function obtenerPosicionPixel(marker){
    var coordenadaY = marker.getPosition().lat();
    var coordenadaX = marker.getPosition().lng();

    var limiteIzq = -91;
    var limiteSup = -89.1695;

    var cantidadCoordenadaX = 182;
    var cantidadCoordenadaY = 0.7467;
    var cantidadPixelX = 522;
    var cantidadPixelY = 314;

    var pixelX = ((coordenadaX - limiteIzq) * cantidadPixelX) / cantidadCoordenadaX;
    var pixelY = -((coordenadaY - limiteSup) * cantidadPixelY) / cantidadCoordenadaY;

    //alert(coordenadaX+' '+coordenadaY);
    //alert(pixelX+' '+pixelY);
    return {
      pixelX: pixelX,
      pixelY: pixelY
    };
  }

  function limpiarDatos(){
  	//alert("limpiar datos");
    $('#id').val('');
    $('#fotoDanio').attr('src','/images/img_asegura_tu_auto/AutoVistaAerea.png');
    $('#fotoDanio').removeClass('fotoCargada');
    $('#descripcion').val('');
    $('#posicionX').val('');
    $('#posicionY').val('');
    $('#urlfoto').val('');
    $('#foto').val('');
    $("#opcion").val('');
  }

  //retorna true si todos los campos están en blanco
  function camposEnBlanco(){
  	var foto = $('#foto').val();
  	var urlFoto = $('#urlFoto').val();

  	var descripcion = $('#descripcion').val();
  }

  /* previsualizar foto */
  function archivo(evt) {
      var files = evt.target.files; // FileList object
 
      // Obtenemos la imagen del campo "file".
      for (var i = 0, f; f = files[i]; i++) {
        //Solo admitimos imágenes.
        if (!f.type.match('image.*')) {
            continue;
        }
 
        var reader = new FileReader();
 
        reader.onload = (function(theFile) {
            return function(e) {
              // Insertamos la imagen
             $('#fotoDanio').attr('src',e.target.result);
            };
        })(f);
 
        reader.readAsDataURL(f);
      }
  }
 
  document.getElementById('foto').addEventListener('change', archivo, false);

</script>