<?php use_stylesheet('subi_tu_auto.css') ?>
<?php use_stylesheet('mensajes.css') ?>
<?php use_stylesheet('messages.css') ?>
<?php use_stylesheet('registro.css') ?>
<?php $car = new Car();?>

<div class="main_box_1">
<div class="main_box_2">
<div class="main_col_izq">


<div class="usuario_foto_frame">
<?php include_component("profile","pictureFile",array("user"=>$user,"params"=>"width=129px height=147px"));?>
</div><!-- usuario_foto_frame -->

<div class="usuario_info">
    <span class="usuario_nombre">
    <span >Usuario:</span><?php include_component("messages","linkMessage",array("user"=>$user));?>
    </span>
</div><!-- usuario_info -->

<a href="<?php echo url_for('profile/publicprofile?id='.$user->getId()) ?>" class="usuario_btn_verperfil"></a>

</div><!-- main_col_izq -->


<!--  contenido de la seccion -->
<div class="main_contenido">

<style>

#linkmain {
	/*position: absolute;*/
	display:table;
	padding-top:10px;
	bottom: 20px;
	left: 54px;
}

</style>

<!-- btn agregar otro auto -->
<!--<a href="#" class="upcar_btn_agregar_auto"></a>-->



<h1 class="msg_titulo_seccion">Centro de mensajes</h1>

<!-- transacciones en curso -->
<div class="upcar_box_1">
<div class="upcar_box_1_header"><h2>Escribir Mensaje</h2></div>
<div class="upcar_sector_1">
    <div class="sector_1_form" style="width:450px">
</div><!-- upcar_sector_1 -->
<?=form_tag("messages/savenew",array("id"=>"form_new_message"))?>

<div class="upcar_sector_2">
    <textarea id="new_message" name="description"row="20" cols="20" ></textarea>
<!--
Envio automatico del mensaje agregar esto
onkeypress="return sendWithEnter('new_message',event,'form_new_message')"
-->
</div><!-- upcar_sector_2 -->

<input type="hidden" name="user_to" value="<?=$user->getId()?>"/>
<input type="hidden" name="user_from" value="<?=$user_from?>"/>
<div class="msg_send_answer">
  <input type="submit" value="Enviar Mensaje" style="width:100px">
</div>
</form>
</div><!-- upcar_box_1 -->
</div>
</div><!-- main_contenido -->
        <div class="main_col_right">
			<?php include_component('profile', 'newsfeed') ?>
        </div>    
</div><!-- main_box_2 -->
<div class="clear"></div>
</div><!-- main_box_1 -->
