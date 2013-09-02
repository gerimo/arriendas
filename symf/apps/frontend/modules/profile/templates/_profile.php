<!-- informacion de usuario -->
<div class="usuario_foto_frame">
    <?php include_component("profile", "pictureFile", array("user" => $user, "params" => "width=129px height=147px")); ?>

</div><!-- usuario_foto_frame -->


<!-- a href="<?php echo url_for('profile/publicprofile?id=' . $user->getId()) ?>" class="usuario_btn_verperfil"></a -->
<div class="box_menu_izq">
<ul class="menu_izq">
	<li <?php if(strpos(sfContext::getInstance()->getRouting()->getCurrentInternalUri(), 'pedidos') !== FALSE ) echo 'class="selected"';?>><a class="bt_menu_izq" href="<?php echo url_for('profile/pedidos') ?>" title="Reservas">Pedidos de Reserva</a></li>
	<li <?php if(strpos(sfContext::getInstance()->getRouting()->getCurrentInternalUri(), 'messages') !== FALSE ) echo 'class="selected"';?>><a class="bt_menu_izq" href="<?php echo url_for('messages/inbox') ?>" title="Mensajes">Mensajes</a></li>
	<li <?php if(strpos(sfContext::getInstance()->getRouting()->getCurrentInternalUri(), 'ratings') !== FALSE ) echo 'class="selected"';?>><a class="bt_menu_izq" href="<?php echo url_for('profile/ratings') ?>" title="Calificaciones">Calificaciones</a></li>
	<li <?php if(strpos(sfContext::getInstance()->getRouting()->getCurrentInternalUri(), 'transactions') !== FALSE ) echo 'class="selected"';?>><a class="bt_menu_izq" href="<?php echo url_for('profile/transactions') ?>" title="Transacciones">Transacciones</a></li>
</ul>
</div>