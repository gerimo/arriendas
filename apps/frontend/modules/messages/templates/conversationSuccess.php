<div id="conversation">
<?php use_helper("jQuery")?>
<?php use_stylesheet('mensajes.css') ?>
<?php use_stylesheet('messages.css') ?>
<?php use_stylesheet('registro.css') ?>
<?php use_stylesheet('conversaciones.css') ?>

<div class="main_box_1">
<div class="main_box_2">


<?php include_component('profile', 'profile') ?>

<!--  contenido de la seccion -->
<div class="main_contenido">
<?php
	$cantidadMensajes = count($conversacion)-3;
	$idUsuarioTo = $objetoConversacion[0]->getUserToId();
	$idUsuarioFrom = $objetoConversacion[0]->getUserFromId();
	$myId = $user_id;
	$yoSoyElFrom = false;
	$yoSoyElTo = false;
	$idOtroUsuario = null;
	if($myId == $idUsuarioFrom){ //Yo soy el que envia mensajes
		$yoSoyElFrom = true;
		$idOtroUsuario = $idUsuarioTo;
	}else if($myId == $idUsuarioTo){//Yo soy el que recibe mensajes
		$yoSoyElTo = true;
		$idOtroUsuario = $idUsuarioFrom;
	}
?>

<div class="barraSuperior">
	<p>MENSAJES</p>
</div>
<input type="button" class="volverAtras" value="Volver a bandeja de entrada"/>
<div class="nuevoMensaje">
	<div class="msg_user_frame">
    	<?php 
    		if($yoSoyElFrom) include_component("profile","pictureFile",array("user"=>$objetoConversacion[0]->getUserFrom(),"params"=>"width=74px height=74px"));
    		else if($yoSoyElTo) include_component("profile","pictureFile",array("user"=>$objetoConversacion[0]->getUserTo(),"params"=>"width=74px height=74px"));
    	?>
  	</div>
	<textarea class="textoMensaje" name="nuevoMensaje" placeholder="<?php if($yoSoyElTo){ echo 'Escríbele un nuevo mensaje a '.$objetoConversacion[0]->getUserFrom()->getFirstName();}else if($yoSoyElFrom){ echo 'Escríbele un nuevo mensaje a '.$objetoConversacion[0]->getUserTo()->getFirstName();} ?>"></textarea>
	<div id="ajax_loader"><?php echo image_tag('ajax-loader.gif', 'class=img_loader');?></div><input type="button" class="enviarMensaje" value=""/>
</div>
<div class="cargaDeNuevosMensajes"></div>
<div class="mensajesAntiguos">
<?php
	for($i=0;$i<$cantidadMensajes;$i++){
			if($conversacion[$i]['userMensaje'] == $idUsuarioFrom){//Si es el emisor
				if($idUsuarioFrom == $myId){
			?>
			<div class='newMensaje'>
				<div class="usuarioIzq">
			      <div class="msg_user_frame">
			        <?php include_component("profile","pictureFile",array("user"=>$objetoConversacion[0]->getUserFrom(),"params"=>"width='74px' height='74px'"));?>
			      </div>
			      <div class="msg_user_nombre">
			          <?php if($yoSoyElFrom) echo "Tú";
			          		else echo "<a target='_blank' href='http://www.arriendas.cl/profile/publitarget='_blank' profile/id/".$conversacion[$i]['userMensaje']."'>".$objetoConversacion[0]->getUserFrom()->getFirstName()."</a>";
			          ?>
		          </div>
			    </div>
			  <div class="puntaBlanca"></div>  
		      <div class="marcoTextoIzq">
		  	  	<div class="texto">
			      <div class="msg"><p><?=$conversacion[$i]['bodyMensaje'];?></p></div>
			      <div class="horaFecha"><?=$conversacion[$i]['dateMensaje'];?></div>
			    </div>
			  </div>
		    </div>
		    <?php
				}else{
			?>
			<div class='newMensaje'>
				<div class="usuarioDer">
			      <div class="msg_user_frame">
			        <?php include_component("profile","pictureFile",array("user"=>$objetoConversacion[0]->getUserFrom(),"params"=>"width='74px' height='74px'"));?>
			      </div>
			      <div class="msg_user_nombre">
			          <?php if($yoSoyElFrom) echo "Tú";
			          		else echo "<a target='_blank' href='http://www.arriendas.cl/profile/publicprofile/id/".$conversacion[$i]['userMensaje']."'>".$objetoConversacion[0]->getUserFrom()->getFirstName()."</a>";
			          ?>
		          </div>
			    </div>
			  <div class="puntaCeleste"></div>  
		      <div class="marcoTextoDer">
		  	  	<div class="texto">
			      <div class="msg"><p><?=$conversacion[$i]['bodyMensaje'];?></p></div>
			      <div class="horaFecha"><?=$conversacion[$i]['dateMensaje'];?></div>
			    </div>
			  </div>
		    </div>

			<?php
				}//fin else-if anidado
			}//fin if padre
			if($conversacion[$i]['userMensaje'] == $idUsuarioTo){//Si es el emisor
				if($idUsuarioTo == $myId){
			?>
			<div class='newMensaje'>
			  <div class="usuarioIzq">
			      <div class="msg_user_frame">
			        <?php include_component("profile","pictureFile",array("user"=>$objetoConversacion[0]->getUserTo(),"params"=>"width='74px' height='74px'"));?>
			      </div>
			      <div class="msg_user_nombre">
			          <?php 
			          	if($yoSoyElTo) echo "Tú";
			          	else echo "<a target='_blank' href='http://www.arriendas.cl/profile/publicprofile/id/".$conversacion[$i]['userMensaje']."'>".$objetoConversacion[0]->getUserTo()->getFirstName()."</a>";
			          ?>
		        </div>
		  	  </div>
		  	  <div class="puntaBlanca"></div>
		  	  <div class="marcoTextoIzq">
		  	  	<div class="texto">
			      <div class="msg"><p><?=$conversacion[$i]['bodyMensaje'];?></p></div>
			      <div class="horaFecha"><?=$conversacion[$i]['dateMensaje'];?></div>
			    </div>
			  </div>
		    </div>
			<?php
				}else{//Si no soy yo
			?>
			<div class='newMensaje'>
			  <div class="usuarioDer">
			      <div class="msg_user_frame">
			        <?php include_component("profile","pictureFile",array("user"=>$objetoConversacion[0]->getUserTo(),"params"=>"width='74px' height='74px'"));?>
			      </div>
			      <div class="msg_user_nombre">
			          <?php 
			          	if($yoSoyElTo) echo "Tú";
			          	else echo "<a target='_blank' href='http://www.arriendas.cl/profile/publicprofile/id/".$conversacion[$i]['userMensaje']."'>".$objetoConversacion[0]->getUserTo()->getFirstName()."</a>";
			          ?>
		        </div>
		  	  </div>
		  	  <div class="puntaCeleste"></div>
		  	  <div class="marcoTextoDer">
		  	  	<div class="texto">
			      <div class="msg"><p><?=$conversacion[$i]['bodyMensaje'];?></p></div>
			      <div class="horaFecha"><?=$conversacion[$i]['dateMensaje'];?></div>
			    </div>
			  </div>
		    </div>

			<?php
				}
			}
	}//fin for
?>
</div>
</div><!-- main_contenido -->
        
    <?php include_component('profile', 'colDer') ?> 

</div><!-- main_box_2 -->
<div class="clear"></div>
</div><!-- main_box_1 -->
</div>
<script type="text/javascript">
	var urlGuardarMensaje = <?php echo "'".url_for("messages/guardarMensajeNuevoAjax")."';" ?>
	var idCon = "<?php echo $conversacion['idConversacion']; ?>";
	var idFrom = "<?php echo $myId; ?>";
	var idTo = "<?php echo $idOtroUsuario; ?>";
$(document).ready(function(){
	$(".enviarMensaje").click(function(){
		var contenido = $(".textoMensaje").val();
		if(contenido == ""){
			alert("Escriba su Mensaje");
		}else{
			//mostrar
			$(".img_loader").fadeIn("hide");
			$.ajax({
				type: 'POST',
				url: urlGuardarMensaje,
				data: {
					mensajeNuevo: contenido,
					idConversacion: idCon,
					idUserFrom: idFrom,
					idUserTo: idTo
				}
			}).done(function(texto){
				$(".cargaDeNuevosMensajes").prepend(texto);
				//ocultar
				$(".img_loader").fadeOut("hide");
				$(".textoMensaje").val('');
			}).fail(function(){
				alert("Ha ocurrido un error al enviar mensaje");
				//ocultar
				$(".img_loader").fadeOut("hide");
			});
		}
	});
	$(".volverAtras").click(function(){
		//location.href="http://localhost/repo_arriendas/web/frontend_dev.php/messages/inbox";
		location.href="http://www.arriendas.cl/messages/inbox";
	});
});


</script>