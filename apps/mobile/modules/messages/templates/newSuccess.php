<?php use_stylesheet('subi_tu_auto.css') ?>
<?php use_stylesheet('mensajes.css') ?>
<?php use_stylesheet('messages.css') ?>
<?php use_stylesheet('registro.css') ?>
<?php $car = new Car();?>

<div class="main_box_1">
<div class="main_box_2">

<?php include_component('profile', 'profile') ?>

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



<div class="barraSuperior">
<p>CENTRO DE MENSAJES</p>
</div>

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
       <?php include_component('profile', 'colDer') ?>
</div><!-- main_box_2 -->
<div class="clear"></div>
</div><!-- main_box_1 -->
