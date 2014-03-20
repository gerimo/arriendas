<?php use_stylesheet('calendario.css') ?>
<?php use_stylesheet('registro.css') ?>
<?php use_stylesheet('mis_arriendos.css') ?>
<?php use_stylesheet('comunes.css') ?>
<?php use_stylesheet('popup.css') ?>
<?php use_javascript('popup.js') ?>
<?php use_stylesheet('cupertino/jquery-ui.css') ?>
<?php use_stylesheet('popup.css') ?>
<?php use_javascript('popup.js') ?>

<div class="main_box_1">
    <div class="main_box_2">


        <?php include_component('profile', 'profile') ?>

        <!--  contenido de la seccion -->
        <div class="main_contenido">
			
			<a href="#" class='detallereserva' data-type="zoomin">[Detalle Reserva]</a>

		</div><!-- main_contenido -->

        <?php include_component('profile', 'colDer') ?>  

        <div class="clear"></div>
    </div><!-- main_box_2 -->
</div>

<div class='overlay-containerLogin'>
	<span title='Cerrar Ventana' class='close'>x</span>
    <div class='window-containerLogin zoomin'>
    	<div class="title">INGRESAR</div>
	    <div class='contenedorPopupLogin'>
	    	<div class="loginFB">
	    		<div class="tagTitle">Iniciar sesión con Facebook</div>
	    	</div>
	    	<div class="separadorLogin"></div>
	    	<div class="loginCuenta">
	    		<div class="tagTitle">Iniciar sesión con tu Usuario</div>
	    		<input type="text" placeholder="USUARIO" name="inputUserName" />
	    		<input type="password" placeholder="CONTRASEÑA" name="inputPass" />
	    	</div>
	    </div>
	    <div class="pieDePopup"></div>
    </div>
</div>