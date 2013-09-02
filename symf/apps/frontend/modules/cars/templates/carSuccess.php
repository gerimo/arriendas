<?php use_stylesheet('comunes.css') ?>
<?php use_stylesheet('subi_tu_auto.css') ?>
<?php use_stylesheet('registro.css') ?>


<script>
	
	$(document).ready(function() {
		
		$('.bt_reserva').click(function(event) {
			
			event.preventDefault();

            var link = '';
            
            if( isValidDate(<?php echo $df;?>) ) link = link + '&df=' + <?php echo $df;?>;
            if( isValidTime(<?php echo $hf;?>) ) link = link + '&hf=' + <?php echo $hf;?>;
            if( isValidDate(<?php echo $dt;?>) ) link = link + '&dt=' + <?php echo $dt;?>;
            if( isValidTime(<?php echo $ht;?>) ) link = link + '&ht=' + <?php echo $ht;?>;
            
            top.location = $(this).attr('href') + link;

		})
	})
	
	//Valida formato fecha
	function isValidDate(date){
	  if (date.match(/^(?:(0[1-9]|[12][0-9]|3[01])[\- \/.](0[1-9]|1[012])[\- \/.](19|20)[0-9]{2})$/)){
	    return true;
	  }else{
	    return false;
	  }
	}
	
	function isValidTime(time){
		var objRegExp  = /(^\d{2}:\d{2}:\d{2}$)/;
		
		if(time.match(objRegExp)) {
	      return true;
	    }else{
	      return false;
	    }
	}
</script>

<style>
	
	.texto_normal_grande {
		color: #2D2D2D;
		font-size: 17px;
		font-family: Arial, sans-serif;
	}
	
	.texto_auto {
		color:#00AEEF;
		font-size: 24px;
	}
	
	.punteado {
		border-bottom: dashed;
		border-bottom-width: 1px;
		border-bottom-color: #2D2D2D;
		margin-left: 20px;
		margin-bottom: 20px;
	}
	
	.precios{
		margin-left: 40px;
		margin-bottom: 30px;
	}
	
	.texto_magenta {
		color: #EC008C;
	}
	
	.espaciado {
		margin-top: 20px;
	}
	
	.galeria{
		margin-bottom: 10px;
		margin-left: 30px;
	}
	
</style>



<div class="main_box_1">
    <div class="main_box_2">



<div class="main_col_izq" style="padding-left: 10px;">

	    <div class="texto_normal" style="margin-top: 40px; text-transform: uppercase;">
		Informaci&oacute;n del due&ntilde;o>
	    </div>
	
            <div class="usuario_foto_frame">
                <?php if ($user->getFacebookId() != NULL): ?>
                    <?php echo image_tag($user->getPictureFile().'?type=large', 'size=129x147') ?>
                <?php elseif ($user->getPictureFile() != NULL): ?>
                    <?php echo image_tag("users/" . $user->getFileName(), 'size=129x147') ?>
                <?php else: ?>
                    <?php echo image_tag('img_registro/tmp_user_foto.jpg', 'size=129x147') ?>
                <?php endif; ?>


            </div><!-- usuario_foto_frame -->

        <div class="usuario_info">
		<div class="subtitulos" style="margin-top: 5px;">Due&ntilde;o |</div>
		<div class="texto_normal" style="margin-top: 5px;"><?php include_component("messages", "linkMessage", array("user" => $user)); ?></div>
		<div class="subtitulos" style="margin-top:20px;">Ubicaci&oacute;n |</div>
		<div class="texto_normal" style="margin-top: 5px;"><?php echo $user->getNombreComuna() ?>, <?php echo $user->getNombreRegion() ?></div>
	</div><!-- usuario_info -->

            <a href="<?php echo url_for('profile/publicprofile?id=' . $user->getId()) ?>"><?php echo image_tag("BotonVerPerfil.png"); ?></a>



        </div><!-- main_col_izq -->


<div class="main_contenido" style="padding-left: 40px; float: left;">
	
	<div class="texto_normal_grande" style="margin-top: 20px; margin-bottom: 20px; text-align: center; vertical-align: middle;">
		<span>Informaci&oacute;n del Auto > </span>
		<span class="texto_auto"><?php echo $car->getModel()->getBrand() ?> <?php echo $car->getModel() ?></span>
	</div>
	
	
	
	<div style="width:160px;float:left; margin-right:40px;">
		<div class="upcar_foto_frame"">
			<?php if ($car->getPhotoFile('main') != NULL): ?>
			<a href="<?php echo image_path("cars/" . $car->getPhotoFile('main')->getFileName()); ?>" class="thickbox" ><?php echo image_tag("cars/" . $car->getPhotoFile('main')->getFileName(), 'size=174x174') ?></a>
                        <?php else: ?>
			<?php echo image_tag('default.png', 'size=174x174') ?>
                        <?php endif; ?>
		</div>
	</div>
	<div style="width: 250px; float: left;">
		<div style="text-align: right;">
			<a href="<?php echo url_for('profile/reserve?id=' . $car->getId()) ?>"/><?php echo image_tag('BotonRealizarReserva.png') ?></a>
		</div>
		<div class="subtitulos punteado">Precio</div>
		<div class="texto_normal precios">Precio por Hora | <span class="texto_magenta"><strong><?php echo "$".number_format(floor($car->getPricePerHour()),0,',','.'); ?></strong></span></div>
		<div class="texto_normal precios">Precio por D&iacute;a | <span class="texto_magenta"><strong><?php echo "$".number_format(floor($car->getPricePerDay()),0,',','.'); ?></strong></span></div>
		
		<div class="subtitulos punteado espaciado">Ubicaci&oacute;n</div>
		<div class="texto_normal precios"><?php echo $car->getAddress() ?></div>
		
		<div class="subtitulos punteado">Descripci&oacute;n</div>
		<div class="texto_normal precios">A&ntilde;o | <strong><?php echo $car->getYear(); ?></strong></div>
		<div class="texto_normal precios">Kil&oacute;metros | <strong><?php echo $car->getKm(); ?></strong></div>
		
		<div class="subtitulos punteado espaciado">Breve Descripci&oacute;n del auto</div>
		<div class="texto_normal precios"><?php echo $car->getDescription(); ?></div>
		
		<div class="subtitulos punteado">Da&ntilde;os del auto</div>
		<div class="subtitulos galeria">Ver Galer&iacute;a de da&ntilde;os</div>
		<div class="subtitulos galeria">Informe de da&ntilde;os</div>
		
	</div>

           
</div>

<div class="main_col_right">
	<?php include_component('profile', 'newsfeed'); ?>
</div>       

    </div><!-- main_box_2 -->
    <div class="clear"></div>
</div><!-- main_box_1 -->















