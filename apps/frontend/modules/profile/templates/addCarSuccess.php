
<?php use_stylesheet('registro.css') ?>
<?php use_stylesheet('comunes.css') ?>

<!-- css para estilo de autocompletar input-->
<?php use_stylesheet('cupertino/jquery-ui.css') ?>

<!-- vínculos para el formulario de "publica tu auto" -->
<?php use_stylesheet('form_PublicaTuAuto.css?v=09122013') ?>
<?php use_javascript('form_PublicaTuAuto.js?v=09122013') ?>

<!-- js para subir imagen perfil -->
<?php use_javascript('jquery.uploadify.v2.1.0.min.js') ?>
<?php use_javascript('swfobject.js') ?>


<div class="main_box_1">
<div class="main_box_2">

<?php include_component('profile', 'profile') ?>


<!--  contenido de la seccion -->
<div class="main_contenido">


<?php include_partial('profile/car2', array('car' => $car,'brand' => $brand, 'comunas'=>$comunas, 'urlAbsoluta'=>$urlAbsoluta,'rutaArchivo'=>$rutaArchivo, "partes"=>$partes,"nombresPartes"=>$nombresPartes, 'auto' => $auto, "id" => $idAuto, "fotosPartes"=>$fotosPartes)) ?>


</div><!-- main_contenido -->

<?php include_component('profile', 'colDer') ?>

</div><!-- main_box_2 -->
</div><!-- main_box_1 -->

