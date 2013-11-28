
<?php use_stylesheet('registro.css') ?>
<?php use_stylesheet('comunes.css') ?>

<!-- css para estilo de autocompletar input-->
<?php use_stylesheet('cupertino/jquery-ui.css') ?>

<!-- vínculos para el formulario de "publica tu auto" -->
<?php use_stylesheet('form_PublicaTuAuto.css') ?>
<?php use_javascript('form_PublicaTuAuto.js') ?>
<?php use_javascript('jquery.uploadify.v2.1.0.min.js') ?>
<?php use_javascript('swfobject.js') ?>
<script type="text/javascript">

	$(document).on('ready',function(){

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
		    	echo "imageUpload('#form_".$partes[$i]."', '#file_".$partes[$i]."', '#preview_".$partes[$i]."','#link_".$partes[$i]."'); \n";
		    }
		?>
	});

</script>
<div class="main_box_1">
<div class="main_box_2">

<?php include_component('profile', 'profile') ?>


<!--  contenido de la seccion -->
<div class="main_contenido">

	<div class="barraSuperior">
		<p>¡PUBLICA TU AUTO!</p>
	</div>

	
	<div id="sub_contenido" style="margin: 15px;margin-right: 25px;">
	
<p style="text-align: center;margin-top: 35px;font-style: italic;color:#EC008C;font-size:26px;">¡Su auto se ha guardado exitosamente!</p>

<p style="text-align: center;margin-top: 25px; margin-bottom:30px; ;font-size: 17px;">Un inspector te contactará para agendar la verificación de tu auto. Responde el correo enviado a <?=$emailUser;?> para inicial al proceso.</p>

<p style="text-align: center;margin-top: 25px; margin-bottom:30px; ;font-size: 17px;">Ahora debes subir al menos 4 fotos para que el auto se vea <b>publicado</b> en el sítio.</p>





<div class="etiquetaPrincipal">FOTOS DEL VEHÍCULO</div>
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
        	<?php echo image_tag('img_asegura_tu_auto/'.$partes[$i].'.png', 'size=140x140') ?>
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

<div class="boton3">
	<a href="<?php echo url_for('profile/addCarExitoFotos?id='.$id); ?>">
		<?php echo image_tag('img_publica_auto/Listo_SubeTuAuto.png', 'id=ok_photo') ?>
	</a>
</div>

	</div>
	
</div><!-- main_contenido -->

<?php include_component('profile', 'colDer') ?>

</div><!-- main_box_2 -->
</div><!-- main_box_1 -->


<!-- Google Code for Sube tu auto Conversion Page -->
<script type="text/javascript">
/* <![CDATA[ */
var google_conversion_id = 996876210;
var google_conversion_language = "en";
var google_conversion_format = "2";
var google_conversion_color = "ffffff";
var google_conversion_label = "P1bCCMb0swQQsr-s2wM";
var google_conversion_value = 0;

/* ]]> */
</script>
<script type="text/javascript" src="http://www.googleadservices.com/pagead/conversion.js">
</script>
<noscript>
<div style="display:inline;">
<img height="1" width="1" style="border-style:none;" alt="" src="http://www.googleadservices.com/pagead/conversion/996876210/?value=0&amp;label=P1bCCMb0swQQsr-s2wM&amp;guid=ON&amp;script=0"/>
</div>
</noscript>