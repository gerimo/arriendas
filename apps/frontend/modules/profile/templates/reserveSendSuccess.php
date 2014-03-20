<?php use_stylesheet('comunes.css') ?>
<?php use_stylesheet('registro.css') ?>
<style>
.d1 {
width:100%;
margin:20px;
}
</style>
<script type="text/javascript">
	function generarBarraEstrellas(num){
        var gris = "<?php echo image_path('img_search/EstrellaGris.png'); ?>";
        var rosa = "<?php echo image_path('img_search/EstrellaRosada'); ?>";

        var cadena = "";
        for(var i=0;i<5;i++){
            if(num>0) cadena = cadena +'<img width="10px" height="9px" style="margin-right: 4px;" src="'+rosa+'" />';
            else cadena = cadena +'<img width="10px" height="9px" style="margin-right: 4px;" src="'+gris+'" />';

            num--;
        }
        return cadena;
    }
</script>
<div class="main_box_1">
<div class="main_box_2">

<?php include_component('profile', 'profile') ?>
    
<!--  contenido de la seccion -->
<div class="main_contenido">

<div class="barraSuperior">
	<p>RESERVA ENVIADA</p>
</div>

<div id="contenedorMsjReserveSend">
	<?php echo html_entity_decode($sf_user->getFlash('msg')); ?>
</div>

<div id="contenedorTitleReservas">
	<div id="titleMasReservas">RECOMENDACIONES PARA MÚLTIPLES RESERVAS</div>
	<div id="periodoReserva"><?php echo $reserveDateFrom." a ".$reserveDateTo;?></div>
</div>

<div id="contenedorRecomenderCarsR">
	<form>
	<?php $i = 1; ?>
	<?php foreach ($cars as $c): ?>
	    <div class="div_car_recomenderR" id="<?=$c["id"]; ?>">
	        <div class="marcador_car_recomenderR">
	        	<input class= "marcadorR" type="checkbox" name="carRecomender" value="car_<?=$c["id"]; ?>" />
	        </div>
	        <div class="photo_car_recomenderR">
	            <?php   if ($c["photo"] != NULL){ 
	                        if($c["photoType"] == 1) {
	            ?> 
	                            <img class="photoR" width="84px" height="64px" src="<?php echo url_for('main/s3thumb').'?alto=64&ancho=64&urlFoto='.urlencode($c['photo']) ?>"/>
	                        <?php }else{ ?>
	                            <img class="photoR" width="84px" height="64px" src="<?php echo image_path('../uploads/cars/thumbs/'.urlencode($c['photo'])); ?>"/>

	            <?php       }
	                    }else{ ?>
	                <?= image_tag('default.png', 'size=84x64', 'class=photoR') ?>  
	            <?php } ?>

	        </div><!-- search_arecomend_frame -->

	        <ul class="info_car_recomenderR">
	            <input type="hidden" class="linkR" value="<?php echo url_for('auto/economico?chile='.$c["comuna"] .'?id=' . $c["id"]) ?>"/>
	            <li class="marca_modeloR"><?= '<a target="_blank" title="Ir al perfil del auto" href="'.url_for('auto/economico?chile=' . $c["comuna"].'&id='. $c["id"]).'">';?><?= $c["brand"] ?>, <?= $c["model"] ?> </a>
	            </li>
	            <li class="starsR">
	            <script type="text/javascript">
	            var numStars = Math.floor((Math.random()*5)+1); //función temporal
	            document.write(generarBarraEstrellas(numStars));
	            </script>
	            </li>
	            <li class="direccionR"><?= ucwords(strtolower($c["comuna"])).", Santiago"; ?></li>
	        </ul>
	        <ul class="valor_car_recomenderR">
	        	<li class="valorR">$30.000</li>
	        	<li class="tiempoAseguradoR">(1 día y 5 horas asegurado)</li>
	        </ul>
	    </div>
	    <?php $i++; ?>
	<?php endforeach; ?>
</form><!--fin formulario-->
<button id="btnVerMasAutosR" title="Ver más autos disponibles"></button>
<button id="btnReservarTodosR" title="Reservar todos los autos"></button>
</div><!-- fin contenedorRecomenderCars -->


</div><!-- main_contenido -->

<?php include_component('profile', 'colDer') ?>
    
</div><!-- main_box_2 -->

<!-- Google Code for Realizar pedido de reserva Conversion Page -->
<script type="text/javascript">
/* <![CDATA[ */
var google_conversion_id = 996876210;
var google_conversion_language = "en";
var google_conversion_format = "2";
var google_conversion_color = "ffffff";
var google_conversion_label = "AEJyCNbyswQQsr-s2wM";
var google_conversion_value = 0;
/* ]]> */
</script>
<script type="text/javascript" src="//www.googleadservices.com/pagead/conversion.js">
</script>
<noscript>
<div style="display:inline;">
<img height="1" width="1" style="border-style:none;" alt="" src="//www.googleadservices.com/pagead/conversion/996876210/?value=0&amp;label=AEJyCNbyswQQsr-s2wM&amp;guid=ON&amp;script=0"/>
</div>
</noscript>

<script>
    $(function(){
        console.log('');
        jQuery.get('<?php echo url_for('profile/reserveCallback?id=' . $idReserve) ?>')
    });
</script>


<div class="clear"></div>
</div><!-- main_box_1 -->

<script type="text/javascript">
	
function generarBarraEstrellas(num){
    var gris = "<?php echo image_path('img_search/EstrellaGris.png'); ?>";
    var rosa = "<?php echo image_path('img_search/EstrellaRosada'); ?>";

    var cadena = "";
    for(var i=0;i<5;i++){
        if(num>0) cadena = cadena +'<img width="10px" height="9px" src="'+rosa+'" />';
        else cadena = cadena +'<img width="10px" height="9px" src="'+gris+'" />';

        num--;
    }
    return cadena;
}


</script>

