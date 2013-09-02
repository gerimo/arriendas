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

<div class="main_col_izq">

<?php include_component('profile', 'userinfo') ?>

</div><!-- main_col_izq -->
    
<!--  contenido de la seccion -->
<div class="main_contenido">

<h1 style="float: left;margin-left: 20px;margin-top: 20px;font-family: 'MuseoSlab500';font-size: 30px;font-weight: normal;letter-spacing: -1px;text-shadow: 0px 2px 0px #DDD;margin-bottom: 15px;">
Perfil
</h1>
<div class="clear"></div>
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

<div class="main_col_dere">
<?php include_component('profile', 'rightsidebar') ?>
</div><!-- main_col_dere -->
    
</div><!-- main_box_2 -->
<div class="clear"></div>
</div><!-- main_box_1 -->


