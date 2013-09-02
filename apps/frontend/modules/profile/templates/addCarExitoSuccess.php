
<?php use_stylesheet('registro.css') ?>
<?php use_stylesheet('comunes.css') ?>

<!-- css para estilo de autocompletar input-->
<?php use_stylesheet('cupertino/jquery-ui.css') ?>

<!-- vínculos para el formulario de "publica tu auto" -->
<?php use_stylesheet('form_PublicaTuAuto.css') ?>
<?php use_javascript('form_PublicaTuAuto.js') ?>


<div class="main_box_1">
<div class="main_box_2">

<?php include_component('profile', 'profile') ?>


<!--  contenido de la seccion -->
<div class="main_contenido">

	<div class="barraSuperior">
		<p>¡PUBLICA TU AUTO!</p>
	</div>

<p style="text-align: center;margin-top: 35px;font-style: italic;color:#EC008C;font-size:26px;">¡Su auto se ha publicado exitosamente!</p>

<p style="text-align: center;margin-top: 25px;font-style: italic;font-size: 17px;">Te hemos enviado un correo a <?=$emailUser;?></p>

</div><!-- main_contenido -->

<?php include_component('profile', 'colDer') ?>

</div><!-- main_box_2 -->
</div><!-- main_box_1 -->

<script type="text/javascript">

$(document).ready(function(){
	/*
	setTimeout(function() {
  		window.location.href = "http://www.arriendas.cl/profile/cars";
	}, 4000);
	*/
});

</script>

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