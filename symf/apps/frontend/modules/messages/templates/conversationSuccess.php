<div id="conversation">
<?php use_helper("jQuery")?>
<?php use_stylesheet('mensajes.css') ?>
<?php use_stylesheet('messages.css') ?>
<?php use_stylesheet('registro.css') ?>

<div class="main_box_1">
<div class="main_box_2">


<div class="main_col_izq">

<?php include_component('profile', 'userinfo') ?>

</div><!-- main_col_izq -->

<!--  contenido de la seccion -->
<div class="main_contenido">

<h1 class="calen_titulo_seccion">Centro de mensajes</h1>

<div class="msg_box">
<div class="msg_box_header"><h2>Conversacion</h2></div>

<div class="msg_send_answer">
<?=jq_form_remote_tag(array(
  "url"=> "messages/saveconversation",
  "update"=> "conversation",
),array("id"=>"form_ans_message"))?>
<?php if ($sf_user->hasFlash("message_ok")):?>
<div class="msg_message_ok"><?=$sf_user->getFlash("message_ok")?></div>
<?php endif;?>
<?php if ($sf_user->hasFlash("message_error")):?>
<div class="msg_message_error"><?=$sf_user->getFlash("message_erro")?></div>
<?php endif;?>

<div class="upcar_sector_2">
  <textarea id="textarea_message" name="description" row="20" cols="20"
onFocus="$('#msg_send_intro').remove();" onkeypress="return sendWithEnter('textarea_message',event,'form_ans_message')"></textarea><span id="msg_send_intro">Escriba aqui su mensaje</span>
<input type="submit" value="Enviar Mensaje" style="width:100px"> <input type="button" value="Volver" onclick="window.location='<?=url_for("messages/inbox")?>'">
</div><!-- upcar_sector_2 -->

<input type="hidden" name="user_id" value="<?=$user->getId()?>"/>
<input type="hidden" name="conversation_id" value="<?=$conversation->getId()?>"/>


</form>
</Div>
<?php foreach ($messages as $mess) :?>
<?php $dere = ($mess->getUserId() == $user_id) ? "_dere" : "";?>
<!-- post de usuario -->
<div class="msg_user_item<?=$dere?>">

<div class="msg_user_post" >
<?=$mess->getBody()?>
<?php if ($mess->getUserId() == $user_id and $mess->getReceived() == 0 ) :?>
<input type="button" value="x" class="msg_delete_icon"
onclick="deleteMessage('<?=$mess->getId()?>')"/>
<?php endif;?>
<div class="msg_user_item_flecha"></div>
<div class="msg_user_fecha"><?=$mess->getDateEs()?></div>
</div><!-- msg_user_post -->

<div class="msg_user_frame">
<?php include_component("profile","pictureFile",array("user"=>$mess->getUser(),"params"=>"size=84x84"));?>
<?php //echo image_tag('img_mensajes/tmp_user_foto.jpg', "width='84px' height='84px'")?>
</div>

<div class="msg_user_nombre">
<?=$mess->getUser()?>
</div>
<div class="clear"></div>
</div><!-- msg_item -->

<?php endforeach;?>

</div><!-- msg_box -->

</div><!-- main_contenido -->

        <div class="main_col_right">
			<?php include_component('profile', 'newsfeed') ?>
        </div>    

</div><!-- main_box_2 -->
<div class="clear"></div>
</div><!-- main_box_1 -->
</div>
