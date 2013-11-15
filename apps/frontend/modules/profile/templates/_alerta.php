<?php if(isset($reserva) && isset($reserva['id'])){ ?>
<div id='barraAlerta'>
	<p class="textVersionWeb">Próximo arriendo para el <?php echo $reserva['date']?>. Firma la <a href="http://www.arriendas.cl/main/generarFormularioEntregaDevolucion/tokenReserve/<?php echo $reserva['token'] ?>" target="_blank">versión impresa</a> o llena el <a href="http://www.arriendas.cl/profile/formularioEntrega/idReserve/<?php echo $reserva['id'] ?>" target="_blank">formulario online</a> al iniciar y finalizar el arriendo. <?php echo image_tag('BotonBorrar.png','class=img_cerrar'); ?></p>
	<p class="textVersionMovil">Firma el f&oacute;rmulario de arriendo <a href="http://www.arriendas.cl/profile/formularioEntrega/idReserve/<?php echo $reserva['id'] ?>" target="_blank">Aqu&iacute;</a>...</p>
</div>
<?php } ?>

<script type="text/javascript">
	$('#barraAlerta .img_cerrar').on('click',function(){
		$('#barraAlerta').fadeOut('fast');
	});
</script>
