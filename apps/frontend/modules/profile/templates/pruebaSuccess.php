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

<div class='overlay-container'>
    <div class='window-container zoomin'>
	    <span title='Cerrar Ventana' class='close'>X</span>
	    <div class='contenedorPopup'>
	    	<div class='titlePopup'>DETALLES PETICIÓN DE RESERVA NºXXXXXXX</div>
	    	<div class='detallesPopup'>DETALLE AUTO |</div>
	    		<div class='datosDetalles'><div class='nombre'>Nombre: <span>Francisca Cofré</span></div><div class='rut'>RUT: 16367816-0</div><div class='comuna'>Comuna: Las Condes</div></div>
	    	<div class='kmsPopup'><p>KMS APRÓX |</p><div class='kilometros'><span>0</span><span>0</span><span>0</span><span>4</span><span>0</span></div></div>
	    	<div class='motivosPopup'><p><span>MOTIVO DE ARRIENDO |</span> Cadena de texto, Cadena de texto, Cadena de texto, Cadena de texto, Cadena de texto, Cadena de texto, Cadena de texto, Cadena de texto, Cadena de texto, Cadena de texto, Cadena de texto, Cadena de texto, Cadena de texto, Cadena de texto, Cadena de texto, Cadena de texto, Cadena de texto....<p></div>
	    	<div class='arriendoPopup'><p>ARRIENDO |</p>
	    		<div class='datosArriendo'>
	    			<div class='desde'>DESDE: 19/12/2013 - 10:00</div>
	    			<div class='hasta'>HASTA: 19/12/2013 - 21:00</div>
	    		</div>
	    	</div>
	    	<div class='valorPopup'><p>11<span class='pequeno'>HRS</span> = <span class='celeste'>VALOR $20.000</span></p></div>
	    	<div class='preaprobarPopup'><button class='btn_preAprobar'></button></div>
	    </div>
    </div>
</div>