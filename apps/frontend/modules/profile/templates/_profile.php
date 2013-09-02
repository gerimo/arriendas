<!-- informacion de usuario -->
<div class="main_col_izq">

	<div class="barraSuperior"><p>TU</p></div>

	<div class="usuario_foto_frame">
	    <?php include_component("profile", "pictureFile", array("user" => $user, "params" => "width=145px height=147px")); ?>

	</div><!-- usuario_foto_frame -->

	<div class="box_menu_izq">
	<ul class="menu_izq">

		<li <?php if(strpos(sfContext::getInstance()->getRouting()->getCurrentInternalUri(), 'pedidos') !== FALSE ) echo 'class="selected"';?>><a class="bt_menu_izq" href="<?php echo url_for('profile/pedidos') ?>" title="Reservas">Pedidos de Reserva</a>
			<?php
				if($cantPedidos>0){
					echo "<span class='textoResaltado'> ($cantPedidos)</span>";
				}
			?>
		</li>

		<li <?php if(strpos(sfContext::getInstance()->getRouting()->getCurrentInternalUri(), 'messages') !== FALSE ) echo 'class="selected"';?>><a class="bt_menu_izq" href="<?php echo url_for('messages/inbox') ?>" title="Mensajes">Mensajes</a>
			<?php
				if($cantMensajes>0){
					echo "<span class='textoResaltado'> ($cantMensajes)</span>";
				}
			?>
		</li>
		<li <?php if(strpos(sfContext::getInstance()->getRouting()->getCurrentInternalUri(), 'calificaciones') !== FALSE ) echo 'class="selected"';?>><a class="bt_menu_izq" href="<?php echo url_for('profile/calificaciones') ?>" title="Calificaciones">Calificaciones</a>
			<?php
				if($cantCalificaciones>0){
					echo "<span class='textoResaltado'> ($cantCalificaciones)</span>";
				}
			?>
		</li>

		<li <?php if(strpos(sfContext::getInstance()->getRouting()->getCurrentInternalUri(), 'transactions') !== FALSE ) echo 'class="selected"';?>><a class="bt_menu_izq" href="<?php echo url_for('profile/transactions') ?>" title="Transacciones">Transacciones</a></li>

	</ul>
	</div>

	<div id="verificaciones">
		<p class="subtitulo">VERIFICACIONES</p>
		<ul>
			<?php
			if($verificacionAutos!=null){
				for($i=0;$i<count($verificacionAutos);$i++){
		    		//echo "id:".$verificacionAutos[$i]['idCar']." estado:".$verificacionAutos[$i]['estadoVerificacion']." marca:".$verificacionAutos[$i]['marca']." modelo:".$verificacionAutos[$i]['modelo'];
					echo "<li>";
		    		if($verificacionAutos[$i]['estadoVerificacion']!=4){ //estado 4, el auto ha sido verificado por arriendas.cl
					if ($verificacionAutos[$i]['estadoVerificacion']==2) {
						$texto= "Estamos validando los datos de tu ";
					} else {
						$texto=	"Asegura tu auto";
					}
		    			echo image_tag('img_verificacion/IconoAutoSinConfirmar.png','class=imgConfirmado');
						//echo "<p class='confirmado' style='margin-top:0'><a href=".url_for('profile/aseguraTuAuto?id='.$verificacionAutos[$i]['idCar'].'&paso=1')." title='Asegura tu auto'>$texto</br><span class='valorCampoVerificado'>".$verificacionAutos[$i]['marca']." ".$verificacionAutos[$i]['modelo']."</span></a></p>";
						echo "<p class='confirmado' style='margin-top:0'><a href='mailto:soporte@arriendas.cl?subject=Reserva%20un%20horario%20para%20que%20verifiquemos%20tu%20auto' title='Asegura tu auto'>$texto</br><span class='valorCampoVerificado'>".$verificacionAutos[$i]['marca']." ".$verificacionAutos[$i]['modelo']."</span></a></p>";
		    		}else{
		    			echo image_tag('img_verificacion/IconosAutoConfirmado.png','class=imgConfirmado');
						//echo "<p class='confirmado' style='margin-top:0'>Auto asegurado</br><span class='valorCampoVerificado'><a href=".url_for('cars/car?id='.$verificacionAutos[$i]['idCar'])." title='Ver ficha de auto'>".$verificacionAutos[$i]['marca']." ".$verificacionAutos[$i]['modelo']."</a></span></p>";
						echo "<p class='confirmado' style='margin-top:0'>Auto asegurado</br><span class='valorCampoVerificado'><a href='#' title='Ver ficha de auto'>".$verificacionAutos[$i]['marca']." ".$verificacionAutos[$i]['modelo']."</a></span></p>";
		    		}
		    		echo "</li>";
		    	}
		    }
			?>
			<li>
				<?php
					//confirma telefono
					if(!$telefonoConfirmado){
						echo image_tag('img_verificacion/IconoTelefSinConfirmar.png');
						echo "<p class='noConfirmado'>Tel&eacute;fono por confirmar</p>";
					}else{
						echo image_tag('img_verificacion/IconoTelefonConfirmado.png','class=imgConfirmado');
						echo "<p class='confirmado'><span class='valorCampoVerificado'>".$telefono."</span></p>";
					}
				?>
			</li>
			<li>
				<?php
					//confirma mail
					if(!$emailConfirmado){
						echo image_tag('img_verificacion/IconoEmailSinConfirmar.png');
						echo "<p class='noConfirmado'>Confirma tu email</p>";
					}else{

						//si el email tiene 21 caracteres, se acorta a 17... por motivos de espacio
						if(strlen($email)>21){
							$email = substr($email, 0,17);
							$email = $email."...";
						}
						echo image_tag('img_verificacion/IconoMailConfirmado.png','class=imgConfirmado');
						echo "<p class='confirmado'>".$email."</p>";
					}
				?>
			</li>
			<li class="sinBorde">
				<?php
					//confirma facebook
					if(!$facebookConfirmado){
						echo image_tag('img_verificacion/IconoFacebookSinConfirmar.png');
						echo "<p class='noConfirmado'>Conectarse con Facebook</p>";
					}else{
						echo image_tag('img_verificacion/IconoFacebookAzul.png','class=imgConfirmado');
						echo "<p class='confirmado'>Facebook verificado</p>";
					}
				?>
			</li>

		</ul>

	</div>
	
</div><!-- end columna_izq -->