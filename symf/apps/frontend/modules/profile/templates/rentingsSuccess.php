<?php use_stylesheet('calendario.css') ?>
<?php use_stylesheet('registro.css') ?>

<div class="main_box_1">
<div class="main_box_2">

<?php include_partial('tabsmenu') ?>


<div class="main_col_izq">

<?php include_component('profile', 'userinfo') ?>

</div><!-- main_col_izq -->
    
<!--  contenido de la seccion -->
<div class="main_contenido">

<h1 class="calen_titulo_seccion">Los que Alquile</span></h1>     









<!-- proximas reservas confirmadas -->
<div class="calen_box_3">
<div class="calen_box_3_header"><h2>Pr&oacute;ximas reservas ya confirmadas</h2>
<div class="calen_box_header_nro_2"><span><?=$cars->Count()?></span></div>
</div><!-- /calen_box_2_header -->



<?php foreach ($cars as $car): ?>


<!-- usuario item -->
<div class="calen_user_box">
    
    <div class="calen_user_foto_frame">
        <img src="images/img_calendario/<?=$car->getIdcar()?>.jpg" width="84" height="84" />
    </div>
    
    <ul class="calen_user_info">
        <li class="calen_user_nombre"><?=$car->getFirstname()?> <?=$car->getLastname()?></li>
        <!--<li class="calen_user_calif">25 calificaciones positivas</li>-->
        <li><?=$car->getDate()?></li>
    </ul>
    
    <div class="calen_user_coment">
    	<b>Auto:</b> <?=$car->getBrand()?> <?=$car->getModel()?> (<?=$car->getYear()?>)<br/>
    </div>   
      
</div><!-- calen_user_box -->

<?php endforeach; ?>


</div><!-- calen_box_3 -->




</div><!-- main_contenido -->

<div class="main_col_dere">
<?php include_component('profile', 'rightsidebar') ?>
</div><!-- main_col_dere -->
    
</div><!-- main_box_2 -->
<div class="clear"></div>
</div><!-- main_box_1 -->
