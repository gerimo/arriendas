
<?php use_stylesheet('comunes.css') ?>
<?php use_stylesheet('form_PublicaTuAuto.css') ?>
<?php use_stylesheet('form_AseguraTuAuto.css') ?>
<?php use_javascript('https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false') ?>

<script>
	
	$(document).ready(function() {
		
		$('#country').val(2);
	})
</script>


<div class="main_box_1">
<div class="main_box_2">



<?php include_component('profile', 'profile') ?>


<!--  contenido de la seccion -->
<div class="main_contenido">


<div style="visibility:hidden;overflow:hidden;height:0px;">


</div>	
	
<!--
<div class="calen_textos_publica">En 3 Pasos</div>
<div class="calen_textos_publica">1 2 3</div>
-->

<?php include_partial('profile/aseguraTuAuto', array('id' => $idAuto, 'seguroFotos' => $result, 'pasoAcceso' => $pasoAcceso,"partes"=>$partes,"nombresPartes"=>$nombresPartes,"fotosPartes"=>$fotosPartes)) ?>

</div><!-- main_contenido -->

<?php include_component('profile', 'colDer') ?>

</div><!-- main_box_2 -->
</div><!-- main_box_1 -->
