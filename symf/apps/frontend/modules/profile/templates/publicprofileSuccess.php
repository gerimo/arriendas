<?php use_stylesheet('comunes.css') ?>
<?php use_stylesheet('registro.css') ?>
<style>
.d1 {
width:100%;
margin:20px;
font-size: 12px;
}


</style>


<div class="main_box_1">
<div class="main_box_2">

<div class="main_col_izq">

<!-- informacion de usuario -->
<div class="usuario_foto_frame">


<?php include_component("profile","pictureFile",array("user"=>$user,"params"=>"width=129px height=147px"));?>

</div><!-- usuario_foto_frame -->

<div class="usuario_info" style="display:none">
    <span class="usuario_nombre">
		<span >Usuario:</span><?php include_component("messages","linkMessage",array("user"=>$user))?>
	</span>
    <span class="usuario_lugar"><?=$user->getNombreComuna()?>, <?=$user->getNombreRegion()?></span><br/>
	 <span class="usuario_lugar">Califiacciones Positivas: <?= $user->getPositiveRatings();?></span>	
</div><!-- usuario_info -->

<div class="clear"></div>



</div><!-- main_col_izq -->

<!--  contenido de la seccion -->
<div class="main_contenido">

<h1 class="calen_titulo_seccion">Perfil P&uacute;blico</h1>
<div class="clear"></div>

<!-- Se comenta esta secci—n para evitar mostrar datos privados del usuario a los potenciales arrendatarios.

    <div class="d1">
        <b>Nombre de usuario:</b>
        <?=$user->getUsername()?>
    </div><!-- /d1 -->


    <div class="d1">
        <b>Nombre:</b>
        <?=$user->getFirstname()?>
    </div><!-- /d1 -->

    <div class="d1">
        <b>Apellido:</b>
        <?=$user->getLastname()?>
    </div><!-- /d1 -->
<!--
    <div class="d1">
        <b>Tel&eacute;fono:</b>
        <? # =$user->getTelephone() ?>
    </div><!-- /d1 -->

<div class="clear"></div><br/><br/>





</div><!-- main_contenido -->

<div class="main_col_right">
	<?php include_component('profile', 'newsfeed') ?>
</div> 

</div><!-- main_box_2 -->
<div class="clear"></div>
</div><!-- main_box_1 -->


