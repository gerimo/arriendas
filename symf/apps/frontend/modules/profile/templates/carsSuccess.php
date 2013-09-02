
<?php use_stylesheet('calendario.css') ?>
<?php use_stylesheet('mis_autos.css') ?>
<?php use_stylesheet('registro.css') ?>




<div class="main_box_1">
<div class="main_box_2">
    
<div class="main_col_izq">

<?php include_component('profile', 'userinfo') ?>

</div><!-- main_col_izq -->

    
<!--  contenido de la seccion -->
<div class="main_contenido">

<h1 class="misautos_titulo_seccion">Mis autos</h1>     

<div class="clear"></div>
<div style="margin-left:30px; margin-bottom:30px;">
	<a href="<?php echo url_for('profile/addCar') ?>">Sube tu auto</a>
</div>


<div class="misautos_box">
<div class="misautos_box_header"><h2>Mis autos publicados</h2></div>

<?php foreach ($cars as $c): ?>
	
	<!-- auto_publicado -->
	<div class="misautos_user_item">
	    
	<div class="misautos_user_post">
	<div class="misautos_marca">
		<a href="<?php echo url_for('profile/car?id=' . $c->getId() )?>" ><span><?=$c->getModel()->getBrand()->getName()?> <?=$c->getModel()->getName()?></span></a>
	</div><!-- misautos_marca -->
	
	
	     

	<p class="misautos_info">Kilometraje: <b><?=$c->getKm()?> km</b></p>
	
	<div class="misautos_btn_sector">
	   <!-- <a href="#" class="misautos_btn_disp"></a>
	   -->
	    <a href="<?php echo url_for('profile/rental') ?>" class="misautos_btn_alqui">Mis Arriendos</a>
	    <!--
	    <a href="#" class="misautos_btn_denun"></a>
	      -->
	</div><!-- misautos_btn_sector -->
	
	
	<div class="misautos_user_item_flecha"></div>
	</div><!-- misautos_user_post -->
	
	<div class="misautos_user_frame">
	
	<a href="<?php echo url_for('profile/car?id=' . $c->getId() )?>" >
	<?php if ($c->getPhotoFile('main') != NULL): ?> 
            <?= image_tag("cars/".$c->getPhotoFile('main')->getFileName(), 'size=84x84') ?>
    <?php else: ?>
            <?= image_tag('default.png', 'size=84x84') ?>  
     <?php endif; ?>
	</a>

	</div>
	
	<div class="clear"></div>
	</div><!-- auto_publicado -->


<?php endforeach; ?>  
    
</div><!-- misautos_box -->

</div><!-- main_contenido -->



<?php /*

<div class="main_col_dere">



<!--  ticker actividades____________________ -->
<div class="activ_box">
    
<ul class="activ_menu">
    <li class="activ_seleccionado">+</li>
    <li><a href="#">Mensajes</a></li>
    <li><a href="#">Calificaciones</a></li>    
</ul>   

<!-- items sector -->   
<div class="activ_frame">    

<!-- item -->      
<div class="activ_item">
<p>La reserva que concluy� a las 12pm, se finaliz� correctamente - <a href="#">Si</a> / <a href="#">No</a></p>
</div><!-- activ_item -->

<!-- item -->      
<div class="activ_item">
<p>La reserva que concluy� a las 12pm, se finaliz� correctamente - <a href="#">Si</a> / <a href="#">No</a></p>
</div><!-- activ_item -->

<!-- item -->      
<div class="activ_item">
<p>La reserva que concluy� a las 12pm, se finaliz� correctamente - <a href="#">Si</a> / <a href="#">No</a></p>
</div><!-- activ_item -->


<!-- item -->      
<div class="activ_item">
<p>La reserva que concluy� a las 12pm, se finaliz� correctamente - <a href="#">Si</a> / <a href="#">No</a></p>
</div><!-- activ_item -->

<!-- item -->      
<div class="activ_item">
<p>La reserva que concluy� a las 12pm, se finaliz� correctamente - <a href="#">Si</a> / <a href="#">No</a></p>
</div><!-- activ_item -->

<!-- item -->      
<div class="activ_item">
<p>La reserva que concluy� a las 12pm, se finaliz� correctamente - <a href="#">Si</a> / <a href="#">No</a></p>
</div><!-- activ_item -->

<!-- item -->      
<div class="activ_item">
<p>La reserva que concluy� a las 12pm, se finaliz� correctamente - <a href="#">Si</a> / <a href="#">No</a></p>
</div><!-- activ_item -->
        
</div><!-- activ_frame -->
</div><!-- activ_box -->
<!--  ticker actividades____________________ -->
    
</div><!-- main_col_dere -->
*/ ?>

    
</div><!-- main_box_2 -->
<div class="clear"></div>
</div><!-- main_box_1 -->

