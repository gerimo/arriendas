
<script type="text/javascript">
    var urlCheckPatente = '<?php echo $urlCheckPatente ?>';
    var urlListadoAutos = '<?php echo $urlListadoAutos ?>';
function ingresaPrecios(modelo, anio){
	//alert('hola1');
	/*
	$.getJSON('../main/priceJson/modelo/'+modelo,function(data){
		alert('hola2');
		//alert(data.valorHora);
	});*/
	//alert(modelo);
	var urlAbsoluta = '<?php echo $urlAbsoluta ?>'+'/modelo/'+modelo+'/year/'+anio;
	//alert(urlAbsoluta);

	$.ajax({
		url: urlAbsoluta,
		dataType:'json'

	}).done(function(datos) {
	// Se ejecuta antes de terminar, si todo esta OK
		//$('div').html(datos.nombre);
		var valorHora = datos.valorHora;
		var valorDia = valorHora*6;
		var valorTotal = valorDia*4*2;

		if(valorHora<4000){
			valorHora = 4000;
		}

		//establece el valor de los inputs valor dia y valor hora
		$('#precioHora').attr('placeholder','$ '+anadirPunto(valorHora)+'.- (sugerido)');
		$('#precioDia').attr('placeholder','$ '+anadirPunto(valorDia)+'.- (sugerido)');
		$('#precioSemana').attr('placeholder','$ '+anadirPunto(valorDia*0.9*7)+'.- (sugerido)');
		$('#precioMes').attr('placeholder','$ '+anadirPunto(valorDia*0.62*30)+'.- (sugerido)');

		$('#precioTotal').text('$ '+anadirPunto(valorTotal));


	}).fail(function(jqXHR, textStatus) {
	// Se ejecuta antes de terminar, si ocurre un error
		alert(textStatus);
	});

}

function getModel(currentElement){
	
	//alert("<?php echo $car[0]['Model_id']; ?>");

     $("#model").html("");
	
     $.getJSON("<?php echo url_for('profile/getModel')?>",{id:currentElement.val(), ajax: 'true'}, function(j){
      var options = '';

      for (var i = 0; i < j.length; i++) {

      	var selected = "";
    	<?php if((isset($car)) && ($car[0]['Model_id'] != "")){ ?>
			if(j[i].optionValue == <?php echo $car[0]['Model_id']?>)
				selected = 'selected="selected"';
	    <?php } ?>
        options += '<option value="' + j[i].optionValue + '" ' + selected + ' >' + j[i].optionDisplay + '</option>';

      }
      $("#model").html(options);
    })
}

$(document).on('ready',function(){
	
	var urlUploadFile = <?php echo "'".url_for("profile/uploadImage")."';" ?>
	$('#fileInput').uploadify({
		'uploader'  : 'http://localhost/repo_arriendas/web/uploadify.swf',
		'script'    : urlUploadFile,
		'cancelImg' : 'http://localhost/repo_arriendas/web/cancel.png',
		'auto'      : true,
		'folder'    : 'uploads',
		'scriptData' : {'texto': $("#mitexto").val()},
		'onComplete': function(event, queueID, fileObj, response, data) {
 		    $('#fotosWrapper').append(response);
		}
	});
	$("#uploadImage").click(function(){

		var urlUploadFile = <?php echo "'".url_for("profile/uploadImage")."';" ?>
		//var nombreFile = $("#nameFile").val();
		var inputFileImage = document.getElementById("nameFile");
		var file = inputFileImage.files[0];
		var data = new FormData();
		data.append('archivo',file);
		alert(data);
		$.ajax({
			type: 'POST',
			url: urlUploadFile,
			data: data
		}).done(function(texto){
			alert(texto);
			//$("#resultado").prepend(texto);
		}).fail(function(){
			alert('Error al subir foto perfil');
		});

	});
	
	//getModel('12');
	//alert('entro');

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

	imageUpload('#formcar', '#filecar', '#previewcar','#linkcar');
	<?php
	    for ($i=0;$i<count($partes);$i++){
	    	echo "imageUpload('#form_".$partes[$i]."', '#file_".$partes[$i]."', '#preview_".$partes[$i]."','#link_".$partes[$i]."'); \n";
	    }
	?>

	$("#brand").change(function(){
		getModel($(this));
	});

	//$("#brand").val(12);
	getModel($('#brand'));

	//inicializa la comuna

	<?php if(isset($car[0]['comuna_id']) && isset($car[0]['nombreComuna'])): ?>
		$('#valor option[value=' + '<?php echo $car[0]["comuna_id"] ?>' + ']').attr('selected', true);
		$('#comuna').val( '<?php echo $car[0]["nombreComuna"] ?>' );
		$('#comunaId').val( '<?php echo $car[0]["comuna_id"] ?>' );
	<?php endif; ?>
});

</script>


<div class="barraSuperior">
<p>¡PUBLICA TU AUTO!</p>
</div>

<?php echo form_tag('profile/subirauto', array('method'=>'post', 'enctype'=>'multipart/form-data','id'=>'frm1')); ?>

<h3 class="subtitulo">en <div style="width: 16px;
background-color: rgb(221, 35, 117);
padding: 7px;
color: white;
-webkit-border-radius: 17px;
-moz-border-radius: 17px;
border-radius: 17px;
display: inline-block;
text-align: center;
padding-right: 8px;">4</div> pasos</h3>

<!-- inicio paso 1 -->
<div id="paso1">

	<div class="pasos"> 
		<?php
			echo image_tag('img_publica_auto/1.png');
			echo image_tag('img_publica_auto/2claro.png');
			echo image_tag('img_publica_auto/3claro.png'); 
			echo image_tag('img_publica_auto/4claro.png'); 
		?>
	</div>

	<div class="izqAncho">
		<label for="ubicacion" id="ubicacionLabel">Ubicaci&oacute;n del Veh&iacute;culo (*)</label><br>
		<?php
			if((isset($car)) && ($car[0]['address'] != "")){
				echo "<input type='text' id='ubicacion' name='ubicacion' value='".$car[0]['address']."'>";
			}else{
				echo "<input type='text' id='ubicacion' name='ubicacion' placeholder='Direcci&oacute;n'>";
			}
		?>
	</div>
	<div class="der">
		<label for="comuna" id="comunaLabel">Comuna (*)</label><br>

		<input type="text" id="comuna" name="comuna" placeholder="Comuna"/>                     
    	<select id="valor" style="display:none; width:20px;">
	        <option value="ninguna">---</option>
	        <?php
	        //count($comunas)
	        	for($i=0; $i<count($comunas); $i++){
	        		echo "<option value='".$comunas[$i]['codigoInterno']."'>".$comunas[$i]['nombre']."</option>";
	        	}
	        ?>
    	</select>

    	<!-- almacena el id de la comuna, ya que no la pude rescatar -->
    	<input type="hidden" name="comunaId" id="comunaId" value="">

	</div>


	<div class="separacionPunteada"></div>

	<div class="izq">
		<label for="brand" id="marcaLabel">Marca (*)</label><br>
		<select id="brand" name="brand">
			<option value="">--</option>
			<?php foreach ($brand as $b): ?>
				<option  value="<?php echo $b['id']; ?>"
				<?php
					if((isset($car)) && ($car[0]['marca'] != "")){
						if($b['id'] == $car[0]['marca']){
							echo " selected";
						}
					}
				?>
				><?php echo $b['name']; ?></option>
			<?php endforeach; ?>
		</select>
	</div>

	<div class="centro">
		<label for="model" id="modeloLabel">Modelo (*)</label><br>
		<select id="model" name="model">
			<option value="">--</option>
		</select>
	</div>
	<div class="der">
		<label for="anio" id="anioLabel">A&ntilde;o (*)</label><br>
		<select id="anio" name="anio">
			<option value="">--</option>
			<?php
			if((isset($car)) && ($car[0]['year'] != 0)){
				for($i=2014; $i>=1996; $i--){
					echo "<option value='".$i."'";
					if($car[0]['year'] == $i){
						echo " selected";
					}
					echo ">".$i."</option>";
				}
			}else{
				for($i=2014; $i>=1996; $i--){
					echo "<option value=".$i.">".$i."</option>";
				}
			}
			?>
		</select>
	</div>

	<div class="izq">
		<label for="puertas" id="puertasLabel">N° Puertas (*)</label><br>
		<select id="puertas" name="puertas">
			<option value="">--</option>
			<?php
			if((isset($car)) && ($car[0]['doors'] != 0)){
				if($car[0]['doors'] == 3){
					echo "<option value='3' selected>3</option>";
					echo "<option value='5'>5</option>";
				}
				if($car[0]['doors'] == 5){
					echo "<option value='3'>3</option>";
					echo "<option value='5' selected>5</option>";
				}
			}else{
				echo "<option value='3'>3</option>";
				echo "<option value='5'>5</option>";
			}
			?>
		</select>
	</div>
	<div class="centro">
		<label for="transmision" id="transmisionLabel">Transmisi&oacute;n (*)</label><br>
		<select id="transmision" name="transmision">
			<option value="">--</option>
			<?php
				if((isset($car)) && ($car[0]['transmission'] != "")){
					if($car[0]['transmission']==0){
						echo "<option value='0' selected>Manual</option>";
						echo "<option value='1'>Automática</option>";
					}else{
						echo "<option value='0'>Manual</option>";
						echo "<option value='1' selected>Automática</option>";
					}
				}else{
					echo "<option value='0'>Manual</option>";
					echo "<option value='1'>Automática</option>";
				}
			?>
		</select>
	</div>
	<div class="der">
		<label for="tipoBencina" id="tipoBencinaLabel">Tipo de Bencina (*)</label><br>
		<select id="tipoBencina" name="tipoBencina">
			<option value="">--</option>
			<?php
				if((isset($car)) && ($car[0]['tipobencina'] != "")){
					if($car[0]['tipobencina'] == "93"){
						echo "<option value='93' selected>93</option>";
					}else{
						echo "<option value='93'>93</option>";
					}
					if($car[0]['tipobencina'] == "95"){
						echo "<option value='95' selected>95</option>";
					}else{
						echo "<option value='95'>95</option>";
					}
					if($car[0]['tipobencina'] == "97"){
						echo "<option value='97' selected>97</option>";
					}else{
						echo "<option value='97'>97</option>";
					}
					if($car[0]['tipobencina'] == "Diesel"){
						echo "<option value='Diesel' selected>Diesel</option>";
					}else{
						echo "<option value='Diesel'>Diesel</option>";
					}
				}else{
					echo "<option value='93'>93</option>";
					echo "<option value='95'>95</option>";
					echo "<option value='97'>97</option>";
					echo "<option value='Diesel'>Diesel</option>";

				}
			?>
			
		</select>
	</div>

	<div class="izq">
		<label for="usosVehiculo" id="usosVehiculoLabel">Tipo de Veh&iacute;culo (*)</label><br>
		<select id="usosVehiculo" name="usosVehiculo">
			<option value="">--</option>
			<?php
				if((isset($car)) && ($car[0]['uso_vehiculo_id'] != "")){
					if($car[0]['uso_vehiculo_id'] == 1){
						echo "<option value='1' selected>Particular</option>";
						echo "<option value='2'>Utilitario</option>";
						echo "<option value='3'>Pick-up</option>";
						echo "<option value='4'>SUV</option>";
					}
					if($car[0]['uso_vehiculo_id'] == 2){
						echo "<option value='1'>Particular</option>";
						echo "<option value='2' selected>Utilitario</option>";
						echo "<option value='3'>Pick-up</option>";
						echo "<option value='4'>SUV</option>";
					}
					if($car[0]['uso_vehiculo_id'] == 3){
						echo "<option value='1'>Particular</option>";
						echo "<option value='2'>Utilitario</option>";
						echo "<option value='3' selected>Pick-up</option>";
						echo "<option value='4'>SUV</option>";
					}
					if($car[0]['uso_vehiculo_id'] == 4){
						echo "<option value='1'>Particular</option>";
						echo "<option value='2'>Utilitario</option>";
						echo "<option value='3'>Pick-up</option>";
						echo "<option value='4' selected>SUV</option>";
					}
				}else{
					echo "<option value='1'>Particular</option>";
					echo "<option value='2'>Utilitario</option>";
					echo "<option value='3'>Pick-up</option>";
					echo "<option value='4'>SUV</option>";
				}
			?>
		</select>
	</div>

	<div class="centro">
		<label for="patente" id="patenteLabel">Patente (*)</label><br>
		<?php
			if((isset($car)) && ($car[0]['patente'] != "")){
				echo "<input typer='text' id='patente' name='patente' placeholder='Patente' value='".$car[0]['patente']."'>";
			}else{
				echo "<input typer='text' id='patente' name='patente' placeholder='Patente'>";
			}
		?>

	</div>
	<div class="der">
		<label for="color" id="colorLabel">Color (*)</label><br>
				<?php
			if((isset($car)) && ($car[0]['color'] != "")){
				echo "<input typer='text' id='color' name='color' placeholder='Color' value='".$car[0]['color']."'>";
			}else{
				echo "<input typer='text' id='color' name='color' placeholder='Color'>";
			}
		?>
	</div>

	<?php
		if((isset($car))){
			echo "<input type='hidden' id='lat' value='".$car[0]['lat']."'>";
			echo "<input type='hidden' id='lng' value='".$car[0]['lng']."'>";
			echo "<input type='hidden' name='seguro_ok' id='seguro_ok' value='".$car[0]['seguro_ok']."'>";
		}else{
			echo "<input type='hidden' id='lat' value=''>";
			echo "<input type='hidden' id='lng' value=''>";
			echo "<input type='hidden' name='seguro_ok' id='seguro_ok' value=''>";
		}
	?>

	<div class="mensajeIngresoDatos1">
		<p>(*) Debe ingresar todos los campos</p>
	</div>

	<div class="boton1">
		<a href="">
			<?php echo image_tag('img_publica_auto/Siguiente_SubeTuAuto.png','id=boton1') ?>
		</a>
	</div>

</div>
<!-- fin paso 1 -->

<!-- inicio paso 2 -->
<div id="paso2">

	<div class="pasos"> 
		<?php
			echo image_tag('img_publica_auto/1claro.png');
			echo image_tag('img_publica_auto/2.png');
			echo image_tag('img_publica_auto/3claro.png'); 
			echo image_tag('img_publica_auto/4claro.png'); 
		?>
	</div>

	<p class="msjPrecio">Fija el precio de tu auto > <span class="msjModelo"></span></p>

	<div class="izqMitad" style="height: 70px;">
		<label for="precioHora" id="precioHoraLabel">Precio por hora (*)</label><br>
		<?php
			if((isset($car)) && ($car[0]['price_per_hour'] != 0)){
				echo "<input type='text' id='precioHora' name='precioHora' value='".intval($car[0]['price_per_hour'])."'>";
			}else{
				echo "<input type='text' id='precioHora' name='precioHora'>";
			}
		?>
	</div>
	<div class="derMitad" style="height: 70px;">
		<label for="precioDia" id="precioDiaLabel">Precio por d&iacute;a (*)</label><br>
		<?php
			if((isset($car)) && ($car[0]['price_per_day'] != 0)){
				echo "<input type='text' id='precioDia' name='precioDia' value='".intval($car[0]['price_per_day'])."'>";
			}else{
				echo "<input type='text' id='precioDia' name='precioDia'>";
			}
		?>
	</div>
	<div class="izqMitad" style="height: 70px;">
		<label for="precioSemanal" id="precioSemanaLabel">Precio por semana (Opcional)</label><br>
		<?php
			if((isset($car)) && ($car[0]['price_per_week'] != 0)){
				echo "<input type='text' id='precioSemana' name='precioSemana' value='".intval($car[0]['price_per_week'])."'>";
			}else{
				echo "<input type='text' id='precioSemana' name='precioSemana'>";
			}
		?>
	</div>
	<div class="derMitad" style="height: 70px;">
		<label for="precioMensual" id="precioMesLabel">Precio por mes (Opcional)</label><br>
		<?php
			if((isset($car)) && ($car[0]['price_per_month'] != 0)){
				echo "<input type='text' id='precioMes' name='precioMes' value='".intval($car[0]['price_per_month'])."'>";
			}else{
				echo "<input type='text' id='precioMes' name='precioMes'>";
			}
		?>
	</div>

	
	<p class="msjPromocion1">GANA <span class="msjPromocion2" id="precioTotal"></span> ARRENDANDO ESTE AUTO</p>
	<p class="msjPromocion1 msjPromocion3">LOS FINES DE SEMANA, AL <span class="msjPromocion2" style="font-size:100%">MES</span></p>

	<div class="mensajeIngresoDatos2">
		<p>(*) Debe ingresar todos los campos</p>
	</div>

	<div class="boton2">
		<a href="">
			<?php echo image_tag('img_publica_auto/Siguiente_SubeTuAuto.png','id=boton2') ?>
		</a>
	</div>

</div>
<!-- fin paso 2 -->


<!-- inicio paso 3 -->
<div id="paso3">

	<div class="pasos"> 
		<?php
			echo image_tag('img_publica_auto/1claro.png');
			echo image_tag('img_publica_auto/2claro.png');
			echo image_tag('img_publica_auto/3.png'); 
			echo image_tag('img_publica_auto/4claro.png');
		?>
	</div>

	<p class="msjPrecio">¿Cúal es la disponibilidad de tu <span class="msjModelo"></span>?</p>

	<div class="izqMitad" style="width: 100%;text-align:center;margin-left:0px;">
		<label for="disponibilidad" id="disponibilidadLabel">¿Cuando puedes recibir pedidos de reserva?</label><br>
		<select id="disponibilidad" name="disponibilidad" style="margin-top: 10px;">
			<option value="">--</option>
			<?php
				if((isset($car))){
					if(($car[0]['disponibilidad_semana'] ==1 ) && ($car[0]['disponibilidad_finde'] == 0)){
						echo "<option value='1' selected>Los días de semana</option>";
					}else{
						echo "<option value='1'>Los días de semana</option>";
					}
					if(($car[0]['disponibilidad_semana'] == 0) && ($car[0]['disponibilidad_finde'] == 1)){
						echo "<option value='2' selected >Algunos o todos los fines de semana</option>";
					}else{
						echo "<option value='2'>Algunos o todos los fines de semana</option>";
					}
					if(($car[0]['disponibilidad_semana'] == 1) && ($car[0]['disponibilidad_finde'] == 1)){
						echo "<option value='3' selected>Todos los días</option>";
					}else{
						echo "<option value='3'>Todos los días</option>";
					}
				}else{
					echo "<option value='1'>Los días de semana</option>";
					echo "<option value='2'>Algunos o todos los fines de semana</option>";
					echo "<option value='3'>Todos los días</option>";

				}
			?>
			
		</select>
	</div>

	<p style="font-size: 14px;">Te recomendamos marcar "Disponibilidad Completa" para que el auto siempre figure en los resultados de búsqueda. Recuerdas que siempre es posible rechazar un pedido de reserva si tu auto no esta disponible.</p>

	<div class="mensajeIngresoDatos3">
		<p>(*) Debe ingresar todos los campos</p>
	</div>

	<div class="boton3" style="margin-top: 15px;">
		<a href="">
			<?php echo image_tag('img_publica_auto/Siguiente_SubeTuAuto.png','id=boton3') ?>
		</a>
	</div>

</div>
<!-- fin paso 3 -->

<!-- inicio paso 4 -->
<div id="paso4">

	<div class="pasos"> 
		<?php
			echo image_tag('img_publica_auto/1claro.png');
			echo image_tag('img_publica_auto/2claro.png');
			echo image_tag('img_publica_auto/3claro.png');
			echo image_tag('img_publica_auto/4.png'); 
		?>
	</div>

<div class="etiquetaPrincipal">FOTOS DEL VEHÍCULO</div>

<!-- almacena la url de la foto anterior si es que ya la ingresó -->
<input type="hidden" id="urlPerfil" name="urlPerfil" value="<?php echo $car[0]['foto_perfil']; ?>">
<input type="hidden" id="urlPadronFrente" name="urlPadronFrente" value="<?php echo $car[0]['padron']; ?>">
<input type="hidden" id="urlPadronReverso" name="urlPadronReverso" value="<?php echo $car[0]['foto_padron_reverso']; ?>">
<input type="hidden" id="idCar" name="idCar" value="<?php echo $id; ?>">

</form>

<!-- foto de perfil auto -->

<div style="visibility:hidden;overflow:hidden;height:0px;">

    <form id="formcar" method="post" enctype="multipart/form-data" action='<?php echo url_for('profile/uploadPhotoCar?photo=foto_perfil&width=140&height=140&file=filecar') ?>'>
        <input type="file" name="filecar" id="filecar" />
        <input type="submit" />
    </form>
</div>
<div class="contenedorFotoCar">
	<div class="regis_foto_car">
	    <div id="previewcar">
	        <?php if ($auto == null): ?>
	            <?php echo image_tag('img_publica_auto/AutoVistaAerea.png', 'size=140x140') ?>
	         <?php else: ?>
	         	<?php if ($auto->getFoto_perfil() == null): ?>
	         		<?php echo image_tag('img_publica_auto/AutoVistaAerea.png', 'size=140x140') ?>
	         	<?php else: ?>
	         	 	<?php echo image_tag('../uploads/cars/'.$auto->getFoto_perfil(), 'size=140x140') ?>
	         	<?php endif; ?>
	        <?php endif; ?>
	    </div>
	</div>
	<div class="regis_op_car">
		<div class="subtituloDer titulo_reg_car">
			<p>Foto Perfil (*)</p>
		</div>
		<a href="#" id="linkcar" title="Agregar una foto"><?php echo image_tag('img_subi_tu_auto/upcar_ico_agrega_foto.png') ?></a>
		<span style="font-size: 12px; margin-left:10px;">Tamaño máximo foto: 1 MB</span>
	</div>
</div>
<div class="contenido">
<div id="contenidoFotoFrente">
<?php
	if($id != ""){
		for($i=0;$i<count($partes);$i++){
?>

<div style="visibility:hidden;overflow:hidden;height:0px;">
    <form id="form_<?=$partes[$i]?>" method="post" enctype="multipart/form-data" action='<?php echo url_for('profile/uploadPhotoVerification?photo='.$partes[$i].'&width=140&height=140&file=file_'.$partes[$i].'&idCar='.$id) ?>'>
        <input type="file" name="file_<?=$partes[$i]?>" id="file_<?=$partes[$i]?>" />
        <input type="submit" />
    </form>
</div>
<div class="contenedorFotoCar">
	<div class="regis_foto_car">
	    <div id="preview_<?=$partes[$i]?>">
	        <?php if ($fotosPartes[$partes[$i]]== null): ?>
         		<?php echo image_tag('img_asegura_tu_auto/'.$partes[$i].'.png', 'size=140x140') ?>
         	<?php else: ?>
         	 	<?php echo image_tag('../uploads/verificaciones/'.$fotosPartes[$partes[$i]], 'size=140x140') ?>
         	<?php endif; ?>
	    </div>
	</div>
	<div class="regis_op_car">
		<div class="subtituloDer titulo_reg_car">
			<p><?=$nombresPartes[$i]?></p>
		</div>
		<a href="#" id="link_<?=$partes[$i]?>" title="Agregar una foto"><?php echo image_tag('img_subi_tu_auto/upcar_ico_agrega_foto.png') ?></a>
		<span style="font-size: 12px; margin-left:10px;">Tamaño máximo foto: 1 MB</span>
	</div>
</div>

<?php
		}
	}
?>

</div>
</div>

<!-- fin foto de perfil auto -->

<div class="mensajeIngresoDatos4">
	<p>(*) Debe ingresar todos los campos</p>
</div>

<div class="boton4">
	<a href="">
		<?php echo image_tag('img_publica_auto/Listo_SubeTuAuto.png','id=boton4') ?>
	</a>
</div>

<!--
<iframe id="widgetFotoPadron" style="width:450px; height: 186px;"
 src="<?php echo url_for("main/upload2S3")."?tituloFoto=Foto Perfil (*)&urlFotoDefecto=".$rutaArchivo."&idElemento=perfil"; ?>">
</iframe>

<h3>Upload image file here</h3><br/>
<div style='margin:10px'><input id="nameFile" type='file' name='fotoPerfil'/> <input id='uploadImage' type='button' value='Upload Image'/></div>
<div id="resultado"></div>

<input type="text" size="25" name="mensaje" id="mitexto" />
<input type="file" name="fileInput" id="fileInput" />
<input type="button" id="fileInputUploader" value="Subir"/>
<div id="fotosWrapper"></div>
-->


</div>
<!-- fin paso 4 -->


<!-- inicio ventana emergente mapa ubicación -->
<div id="confirmacionUbicacion" title="Confirme su ubicaci&oacute;n">
    <p>¿Es correcta su ubicaci&oacute;n?</p>
	<img id="mapa" src="" alt="Cargando Mapa">
</div>

<div id="errorUbicacion" title="Error en su ubicaci&oacute;n">
    <p>La direcci&oacute;n ingresada no es correcta.</p>
</div>
<!-- fin ventana emergente mapa ubicación -->