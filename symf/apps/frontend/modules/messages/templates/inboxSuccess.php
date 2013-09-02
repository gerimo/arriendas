<div id="conversation">
<?php echo $sf_user->getFlash("message")?>
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

<h1 class="msg_titulo_seccion">Centro de mensajes</h1>


<div class="msg_box">
<div class="msg_box_header"><h2>Mensajes sin leer</h2></div>


<?php foreach ($messages as $mess) :?>
<!-- post de usuario -->
<div class="msg_user_item">

<div class="msg_user_post" onclick="gotoConversation('<?php echo $mess->getConversationId()?>','<?php echo $mess->getId()?>')">
<?php echo $mess->getBody()?>
<div class="msg_user_item_flecha"></div>
<div class="msg_user_fecha"><?php echo $mess->getDateEs()?></div>
</div><!-- msg_user_post -->

<div class="msg_user_frame">
<?php include_component("profile","pictureFile",array("user"=>$mess->getUser(),"params"=>"width='84px' height='84px'"));?>
</div>

<div class="msg_user_nombre">
<?php echo $mess->getUser()?>
</div>
<div class="clear"></div>
</div><!-- msg_item -->

<?php endforeach;?>

</div><!-- msg_box -->



<div class="msg_box">
<div class="msg_box_header"><h2>Mensajes Leidos</h2></div>


<?php foreach ($old_messages as $mess) :?>
<?php $direccion = ($mess->getUserId() == $user_id) ? "_dere" : ""?>
<div class="msg_user_item">

<div class="msg_user_post" onclick="gotoConversation('<?php echo $mess->getConversationId()?>','<?php echo $mess->getId()?>')">
<?php echo $mess->getBody()?>
<div class="msg_user_item_flecha"></div>
<div class="msg_user_fecha"><?php echo $mess->getDateEs()?></div>
</div><!-- msg_user_post -->
<div class="msg_user_frame">
<?php include_component("profile","pictureFile",array("user"=>$mess->getUser(),"params"=>"width='44px' height='44px'"));?>
</div>
<div class="msg_user_nombre">
<?php echo $mess->getUser()->getFirstName()?> <?php echo   substr($mess->getUser()->getLastName(), 0, 1)?>.
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



<script language="javascript">
function gotoConversation(id,message){
var datos = "id="+id+"&message_id="+message;
<?php echo jq_remote_function(array(
  "update"=>"conversation",
  "url"=>"messages/conversation",
  "with"=>"datos",
))?>
}
function deleteMessage(id){
  var datos = "id="+id;
<?php echo jq_remote_function(array(
  "update"=>"conversation",
  "url"=>"messages/delete",
  "with"=>"datos",
))?>
}

function gotoMensajes(){
<?php echo jq_remote_function(array(
  "update"=>"conversation",
  "url"=>"messages/inbox",
))?>
}
</script>

</div>
