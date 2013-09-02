<?php use_stylesheet('comunes.css') ?>
<?php use_stylesheet('registro.css') ?>
<style>
.d1 {
width:100%;
margin:20px;
}
</style>


<div class="main_box_1">
<div class="main_box_2">

<?php include_component('profile', 'profile') ?>
    
<!--  contenido de la seccion -->
<div class="main_contenido">

<div class="barraSuperior">
<p>PERF&Iacute;L</p>
</div>

<div style="margin:20px;width:100%;"> 
		<a href="<?php echo url_for('profile/edit')?>">Editar</a>
</div>    
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
    
    <div class="d1">
        <b>Email:</b>
        <?=$user->getEmail()?>
    </div><!-- /d1 -->    
    
    <div class="d1">
        <b>Tel&eacute;fono:</b>
        <?=$user->getTelephone()?>
    </div><!-- /d1 -->
    
    <div class="d1">
        <b>Pa&iacute;s:</b>
   
    </div><!-- /d1 -->
    
    <div class="d1">
        <b>Pa&iacute;s / Ciudad:</b>
       
    </div><!-- /d1 -->
    
    <div class="d1">
        <b>Fecha de nacimiento:</b>
   
    </div><!-- /d1 -->
       


<div class="clear"></div><br/><br/>


</div><!-- main_contenido -->

<?php include_component('profile', 'colDer') ?>
    
</div><!-- main_box_2 -->
<div class="clear"></div>
</div><!-- main_box_1 -->


