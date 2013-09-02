<div class="main_col_right">
<div class="barraSuperior" style="margin-bottom:0;"><p>
	<?php
	$apellido = strtoupper($user->getLastname());
	$apellido = substr($apellido, 0,1);
	$apellido = $apellido.".";
	echo strtoupper("VERIFICACIONES ".$user->getFirstname()); 
	?>
</p></div>

<div id="verificaciones">
<ul>
	<?php
		if($verificacionAutos != null){
			for($i=0;$i<count($verificacionAutos);$i++){
	    		//echo "id:".$verificacionAutos[$i]['idCar']." estado:".$verificacionAutos[$i]['estadoVerificacion']." marca:".$verificacionAutos[$i]['marca']." modelo:".$verificacionAutos[$i]['modelo'];
				echo "<li>";
	    		if($verificacionAutos[$i]['estadoVerificacion']!=4){ //estado 4, el auto ha sido verificado por arriendas.cl
	    			echo image_tag('img_verificacion/IconoAutoSinConfirmar.png','class=imgConfirmado');
					echo "<p class='confirmado' style='margin-top:0'>Auto no verificado</br><span class='valorCampoVerificado'><a href=".url_for('cars/car?id='.$verificacionAutos[$i]['idCar'])." title='Ver ficha de auto'>".$verificacionAutos[$i]['marca']." ".$verificacionAutos[$i]['modelo']."</a></span></p>";
	    		}else{
	    			echo image_tag('img_verificacion/IconosAutoConfirmado.png','class=imgConfirmado');
					echo "<p class='confirmado' style='margin-top:0'>Auto verificado</br><span class='valorCampoVerificado'><a href=".url_for('cars/car?id='.$verificacionAutos[$i]['idCar'])." title='Ver ficha de auto'>".$verificacionAutos[$i]['marca']." ".$verificacionAutos[$i]['modelo']."</a></span></p>";
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
				echo "<p class='noConfirmado noVinculo'>Tel&eacute;fono no confirmado</p>";
			}else{
				echo image_tag('img_verificacion/IconoTelefonConfirmado.png','class=imgConfirmado');
				echo "<p class='confirmado'><span class='valorCampoVerificado'>Tel&eacute;fono confirmado</span></p>";
			}
		?>
	</li>
	<li>
		<?php
			//confirma mail
			if(!$emailConfirmado){
				echo image_tag('img_verificacion/IconoEmailSinConfirmar.png');
				echo "<p class='noConfirmado noVinculo'>Email no confirmado</p>";
			}else{
				echo image_tag('img_verificacion/IconoMailConfirmado.png','class=imgConfirmado');
				echo "<p class='confirmado'>Email confirmado</p>";
			}
		?>
	</li>
	<li class="sinBorde">
		<?php
			//confirma facebook
			if(!$facebookConfirmado){
				echo image_tag('img_verificacion/IconoFacebookSinConfirmar.png');
				echo "<p class='noConfirmado noVinculo'>No conectado a Facebook</p>";
			}else{
				echo image_tag('img_verificacion/IconoFacebookAzul.png','class=imgConfirmado');
				echo "<p class='confirmado'>Facebook verificado</p>";
			}
		?>
	</li>

</ul>
</div> <!-- fin verificaciones -->
</div><!-- fin main_col_right -->