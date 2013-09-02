<?php use_stylesheet('calendario.css') ?>
<?php use_stylesheet('registro.css') ?>
<style>
input[type=text], input[type=password]{
	border: 1px solid #CECECE;
	background-color: white;
	width: 350px;
	height: 30px;
	-webkit-border-radius: 10px;
	-moz-border-radius: 10px;
	border-radius: 10px;
	padding-left: 10px;
}
.c1 label { 
	display:table; 
	width:100%;
	margin-bottom:10px
}

.c1 {
	margin:20px; 
	margin-top:10px;
}

.c5 {
	margin:20px; 
	margin-top:10px;
}

/* css for timepicker */
.ui-timepicker-div .ui-widget-header { margin-bottom: 8px; }
.ui-timepicker-div dl { text-align: left; }
.ui-timepicker-div dl dt { height: 25px; margin-bottom: -25px; }
.ui-timepicker-div dl dd { margin: 0 10px 10px 65px; }
.ui-timepicker-div td { font-size: 90%; }
.ui-tpicker-grid-label { background: none; border: none; margin: 0; padding: 0; }

	
.ui-widget { font-family: Lucida Grande, Lucida Sans, Arial, sans-serif; font-size: 0.8em; }

</style>

<script>

 $(document).ready(function() {
	

        $('#hour_from').timepicker({
            /*timeFormat: 'hh:mm',
            minHour: null,
            minMinutes: null,
            minTime: null,
            maxHour: null,
            maxMinutes: null,
            maxTime: null,
            startHour: 0,
            startMinutes: 0,
            startTime: null,
            interval: 30,
            // callbacks
            change: function(time) {}*/
        });
        
        $('#hour_to').timepicker({/*
            timeFormat: 'hh:mm',
            minHour: null,
            minMinutes: null,
            minTime: null,
            maxHour: null,
            maxMinutes: null,
            maxTime: null,
            startHour: 0,
            startMinutes: 0,
            startTime: null,
            interval: 30,
            // callbacks
            change: function(time) {}*/
        });
        
  $( ".datepicker" ).datepicker({
        dateFormat: 'yy-mm-dd', 
	buttonImageOnly: true,
	minDate:'-0d'
  });
    	

    });

</script>

<div class="main_box_1">
<div class="main_box_2">
    
<div class="main_col_izq">

<?php include_component('profile', 'userinfo') ?>

</div><!-- main_col_izq -->

    
<!--  contenido de la seccion -->
<div class="main_contenido">

<h1 class="calen_titulo_seccion">Calendario</h1>     

<div class="clear"></div>

<?php echo form_tag('profile/doSaveAvailability', array('method'=>'post', 'id'=>'frm1')); ?> 

<div class="c1">
<h3>Fecha Desde</h3>
<input type="text" id="date_from" name="date_from" class="datepicker" value="">
</div>

<div class="c1">
<h3>Fecha Hasta</h3>
<input type="text" id="date_to" name="date_to"  class="datepicker" value="">
</div>

<div class="c1" style="width:100%">
<h3>Dia</h3>
<select id="day" name="day">
	<option value ="0">Domingo</option>
	<option value ="1">Lunes</option>
	<option value ="2">Martes</option>
	<option value ="3">Miercoles</option>
	<option value ="4">Jueves</option>
	<option value ="5">Viernes</option>
	<option value ="6">Sabado</option>
</select>	
</div>

<div class="c1">
<h3>Hora Desde</h3>
<input type="text" id="hour_from" name="hour_from" value="">
</div>

<div class="c1">
<h3>Hora Hasta</h3>
<input type="text" id="hour_to" name="hour_to" value="">
</div>

<div class="c1">
<h3>Auto</h3>
	<select id="car" name="car">
	<?php foreach ($cars as $c): ?>
	<option value="<?= $c->getId(); ?>"><?= $c->getModel()->getName(); ?> <?= $c->getModel()->getBrand()->getName();?></option>
	<?php endforeach; ?>
</select>
</div>

<div class="clear"></div>

<div class="c5">
         <input type="submit" value="Guardar" />
</div>
    
</form>    

</div><!-- main_contenido -->

<div class="main_col_dere"></div><!-- main_col_dere -->
    
</div><!-- main_box_2 -->
<div class="clear"></div>
</div><!-- main_box_1 -->

