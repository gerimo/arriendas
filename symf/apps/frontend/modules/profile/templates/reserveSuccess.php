<?php use_stylesheet('subi_tu_auto.css') ?>
<?php use_stylesheet('registro.css') ?>
<?php use_stylesheet('calendario.css') ?>
<style>
    input[type=text], input[type=password]{
        border: 1px solid #CECECE;
        background-color: white;
        width: 256px;
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
    #centralcontent {
        margin:0px;
        margin-top:80px;
        display:block;
        width:100%;
    }

    #centralcontent h3 {
        margin-top:20px;
        margin-bottom:10px;
    }
    #linkmain {
        /*position: absolute;*/
        display:table;
        padding-top:10px;
        bottom: 20px;
        left: 54px;
    }
    .btnAmarillo{
        padding: 5px;
        margin-left: 308px;
    }
    .mensaje_alerta {
    background: none repeat scroll 0 0 #F8F8F8;
    border: 1px solid #CCCCCC;
    color: #FF0000;
    display: block;
    height: 25px;
    line-height: 30px;
    margin-left: 20px;
    padding: 9px;
    width: 441px;
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

		$("form#frm1").submit(function() {
			
			if( !$("#chkcontrato").is(':checked') ) {
				
				alert("Debes aceptar los terminos del contrato para continuar");
				return false;
			}
			
			//valido que reserva sea de mínimo 1 hora
			if( isValidDate( $('#datefrom').val() ) && isValidDate( $('#dateto').val() ) && isValidTime( $('#hour_from').val() ) && isValidTime( $('#hour_to').val() ) ) {
				
				if( $('#datefrom').val() == $('#dateto').val() ) {
					
					var dif = restarHoras($('#hour_from').val(), $('#hour_to').val())
					
					if( dif < 1 ) { alert('La reserva no puede ser menor a 1 hora'); return false; }
				}
			}
			
		})

		$("#generatepdf").live("click", function(event) {

			event.preventDefault();
			
			if( isValidDate($('#datefrom').val()) && isValidDate($('#dateto').val()) && isValidTime($('#hour_from').val()) && isValidTime($('#hour_to').val()) ) {
				
				var arr = $('#datefrom').val().split('-');
				var horas = $('#hour_from').val().split(':');
				var inicio = arr[2] + '-' + arr[1] + '-' + arr[0] + ' ' + $('#hour_from').val();
				arr = $('#dateto').val().split('-');
				var termino = arr[2] + '-' + arr[1] + '-' + arr[0] + ' ' + $('#hour_to').val();

				window.open('<?php echo url_for('profile/agreePdf')?>?id=<?php echo  $car->getId() ?>&inicio=' + inicio + '&termino=' + termino);
			}
			else
				alert('Para poder visualizar el contrato es necesario llenar los datos de la reserva');

		})
	
        $.datepicker.regional['es'] = {
            closeText: 'Cerrar',
            prevText: '&#x3c;Ant',
            nextText: 'Sig&#x3e;',
            currentText: 'Hoy',
            monthNames: ['Enero','Febrero','Marzo','Abril','Mayo','Junio',
                'Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'],
            monthNamesShort: ['Ene','Feb','Mar','Abr','May','Jun',
                'Jul','Ago','Sep','Oct','Nov','Dic'],
            dayNames: ['Domingo','Lunes','Martes','Mi&eacute;rcoles','Jueves','Viernes','S&aacute;bado'],
            dayNamesShort: ['Dom','Lun','Mar','Mi&eacute;','Juv','Vie','S&aacute;b'],
            dayNamesMin: ['Do','Lu','Ma','Mi','Ju','Vi','S&aacute;'],
            weekHeader: 'Sm',
            dateFormat: 'yy-mm-dd',
            firstDay: 1,
            isRTL: false,
            showMonthAfterYear: false,
            yearSuffix: ''};
        $.datepicker.setDefaults($.datepicker.regional['es']);


        $.timepicker.regional['es'] = {
            timeOnlyTitle: 'Seleccione la hora',
            timeText: 'Tiempo',
            hourText: 'Hora',
            minuteText: 'Minutos',
            secondText: 'Segundos',
            millisecText: 'Milisegundos',
            currentText: 'Hoy',
            closeText: 'Cerrar',
            timeFormat: 'hh:mm:ss',
            ampm: false
        };
        $.timepicker.setDefaults($.timepicker.regional['es']);

        $('#datefrom,#dateto').datepicker({
            dateFormat: 'dd-mm-yy',
            buttonImageOnly: true,
            minDate:'-0d'
        });
        
        $("#hour_from , #hour_to").timePicker();

        
    	$("#hour_to").change(function () {
	    var fecha_inicial= $("#datefrom").datepicker('getDate');
	    var fecha_final= $("#dateto").datepicker('getDate');
	    var hora_inicial= $("#hour_from").timePicker('getTime').val();
	    var hora_final= $("#hour_to").timePicker('getTime').val();
	    var inicio= fecha_inicial.getTime()+ (parseInt(hora_inicial.substring(0,2)) * 60 * 60) + (parseInt(hora_inicial.substring(3,5)) * 60);
	    var fin= fecha_final.getTime()+ parseInt(hora_final.substring(0,2)) * 60 *60 + parseInt(hora_final.substring(3,5)) * 60;
	    var horas= (Math.ceil((fin - inicio)/(60*60)));
	    if (horas <= 6) {
		//Calculo de la tarifa por horas
		var tarifa=horas*<?php echo $car->getPricePerHour(); ?>;
		$("#valor_reserva").text(tarifa);
	    } else {
		//Calculo de la tarifa por dias
		var dias= (Math.ceil(horas/24000));
		$("#valor_reserva").text(dias*<?php echo $car->getPricePerDay() ; ?>);
	    }
	    });
	
		if(isValidDate('<?php echo $fndreserve['fechainicio']?>')) $('#datefrom').val('<?php echo $fndreserve['fechainicio']?>');
		if(isValidTime('<?php echo $fndreserve['horainicio']?>')) $('#hour_from').val('<?php echo $fndreserve['horainicio']?>');
		if(isValidDate('<?php echo $fndreserve['fechatermino']?>')) $('#dateto').val('<?php echo $fndreserve['fechatermino']?>');
		if(isValidTime('<?php echo $fndreserve['horatermino']?>')) $('#hour_to').val('<?php echo $fndreserve['horatermino']?>');
    });

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
	
	function restarHoras(hora_desde, hora_hasta) {
		
		var hora, min, seg;
		var hora2, min2, seg2;
		var arr = hora_desde.split(':');
		var arr2 = hora_hasta.split(':');
		hora = parseInt(arr[0]); min = parseInt(arr[1]); seg = parseInt(arr[2]);
		hora2 = parseInt(arr2[0]); min2 = parseInt(arr2[1]); seg2 = parseInt(arr2[2]);
		var aux = (hora * 3600) + (min * 60); var aux2 = (hora2 * 3600) + (min2 * 60);
		var dif = parseFloat( (aux2 - aux) / 3600 );
		
		return dif; 
	}
</script>



<div class="main_box_1">
    <div class="main_box_2">



        <div class="main_col_izq">

            <div class="usuario_foto_frame">

                <?php if ($user->getFacebookId() != NULL): ?>
                    <?php echo  image_tag($user->getPictureFile() . '?type=large', 'size=129x147') ?>
                <?php elseif ($user->getPictureFile() != NULL): ?>
                    <?php echo  image_tag("users/" . $user->getFileName(), 'size=129x147') ?>
                <?php else: ?>
                    <?php echo  image_tag('img_registro/tmp_user_foto.jpg', 'size=129x147') ?>
                <?php endif; ?>


            </div><!-- usuario_foto_frame -->
            
			<!-- 
            <div class="usuario_info">
                <span >Usuario:</span><span class="usuario_nombre"><?php echo $user->getFirstname() . " " . $user->getLastname(); ?></span>
                <span class="usuario_lugar"><?php echo $user->getNombreComuna() ?>, <?php echo $user->getNombreRegion() ?></span>    
            </div>

            <a href="#<?php echo url_for('profile/profile') ?>" class="usuario_btn_verperfil"></a>
			-->


        </div><!-- main_col_izq -->


        <!--  contenido de la seccion -->
        <div class="main_contenido">

            <!-- btn agregar otro auto -->
            <!--<a href="#" class="upcar_btn_agregar_auto"></a>-->  

            <h1 ><?php if(count($reserve) > 0) echo "Extender"; else echo "Realizar";?> reserva</h1>

            <div class="regis_formulario">  

                <div class="calen_box_3">
                    <div style="padding-top:20px; padding-left:20px;font-size:12px;width:505px;">

                        <?php echo form_tag('profile/doReserve', array('method' => 'post', 'id' => 'frm1')); ?>	

						<?php if( $sf_user->getFlash('msg') ): ?>
                        <span class="mensaje_alerta"><?php echo html_entity_decode($sf_user->getFlash('msg')); ?></span>
						<?php endif;?>
						
						<?php if(count($reserve) > 0) {?>
						<?php foreach ($reserve as $r) { ?>
                        <div class="c1 height">
                            <label>Desde</label>
                            <input id="datefrom" name="datefrom" type="text" class="datepicker" readonly="readonly" value="<?php echo date('d-m-Y',strtotime($r['fechafin']))?>" /><br/><br/>
                            <input readonly="readonly" type="text" id="hour_from" name="hour_from" readonly="readonly" value="<?php echo date('H:i:s',strtotime($r['fechafin']))?>"/>
                        </div><!-- /c1 -->
                        <?php } ?>
						<?php } else {?>
                        <div class="c1 height">
                            <label>Desde</label>
                            <input id="datefrom" name="datefrom" type="text" class="datepicker" readonly="readonly" value="Día de inicio" /><br/><br/>
                            <input readonly="readonly" type="text" id="hour_from" name="hour_from" readonly="readonly" value="Hora de inicio"/>
                        </div><!-- /c1 -->
						<?php } ?>

                        <div class="c1 height">
                            <label>Hasta</label>
                            <input id="dateto" name="dateto" type="text" class="datepicker" readonly="readonly" value="Día de entrega"/><br/><br/>
                            <input readonly="readonly" type="text" id="hour_to" name="hour_to" value="Hora de entrega" />
                        </div><!-- /c1 -->
			
                        <div class="c1 height" style="width:256px;">
                            <label>Valor de la Reserva</label>
			    <label id="valor_reserva">$0</label>
                            <!--
			    <input id="dateto" name="dateto" type="text" class="datepicker" readonly="readonly" value="Día de entrega"/><br/><br/>
                            <input readonly="readonly" type="text" id="hour_to" name="hour_to" value="Hora de entrega" />
			    -->
			</div><!-- /c1 -->
						
						<div class="c1 height"><label><input type="checkbox" name="chkcontrato" id="chkcontrato" value="1" style="width: auto; height: auto;"> Declaro estar de acuerdo con los terminos del <a href="contratopdf" id="generatepdf">Contrato</a> asociado a los servicios de Arriendas.cl.</label></div>
							
                        <input type="hidden" name="duration" id="duration"/>


                        <input type="hidden" name="id" id="id" value="<?php echo  $car->getId() ?>"/>
						<?php if(count($reserve) > 0) :?>
						<?php foreach ($reserve as $r): ?>                        
                        <input type="hidden" name="reserve_id" id="reserve_id" value="<?php echo $r['id']?>"/>
                        <?php endforeach; ?>
						<?php endif;?>

                        <div class="clear"></div>

                        <input type="submit" value="Confirmar" class="btnAmarillo" style="margin-top:0;" />

                        </form>  

                    </div>
                </div>	




            </div>



            <h1 class="calen_titulo_seccion">Informaci&oacute;n del auto</h1>   


            <!-- transacciones en curso -->
            <div class="upcar_box_1">
                <div class="upcar_box_1_header"><h2>Detalles del coche</h2></div>



                <div class="upcar_sector_1">
                    <div class="upcar_foto_frame">
                        <div id="previewmain">
                            <?php if ($car->getPhotoFile('main') != NULL): ?> 
                                <?php echo  image_tag("cars/" . $car->getPhotoFile('main')->getFileName(), 'size=174x174') ?>
                            <?php else: ?>
                                <?php echo  image_tag('default.png', 'size=174x174') ?>  
                            <?php endif; ?>

                        </div> 



                    </div><!-- upcar_foto_frame -->




                    <div class="sector_1_form" style="width:450px">  
                        <!-- <div class="c1">
                             <label>Nombre del auto:</label>
                             <input type="text" name="" >
                         </div>-->  <!-- /c1 -->


                        <div class="c1 height">
                            <div class="c2">
                                <label>Marca del auto:</label>
                                <label style="width: 116px"><?php echo $car->getModel()->getBrand() ?></label>
                            </div><!-- /c2 -->
                            <div class="c2">
                                <label>Modelo del auto:</label>
                                <label style="width: 116px"><?php echo $car->getModel() ?></label>
                            </div><!-- /c2 -->
                        </div><!-- /c1 --> 

                        <div class="c1 height">
                            <div class="c2">
                                <label>Precio por d&iacute;a:</label>
                                <label style="width: 116px"><?php echo floor($car->getPricePerDay()) ?></label>
                            </div><!-- /c2 -->
                            <div class="c2">
                                <label>Precio por hora:</label>
                                <label style="width: 116px"><?php echo floor($car->getPricePerHour()) ?></label>
                            </div><!-- /c2 -->
                        </div><!-- /c1 --> 


                        <div class="c1 height">
                            <label>A&ntilde;o</label>
                            <label style="width: 264px"><?php echo $car->getYear() ?></label>
                        </div>

                        <div class="c1 height">
                            <label>Kilometros</label>
                            <label style="width: 264px"><?php echo $car->getKm() ?></label>
                        </div>

                        <div class="c1 height">
                            <label>Direcci&oacute;n</label>
                            <label style="width: 264px"><?php echo $car->getAddress() ?></label>
                        </div>

                        <div class="c1 height">
                            <div class="c2">
                                <label>Pa&iacute;s:</label>

                                <label style="width: 120px"><?php echo $car->getCity()->getState()->getCountry() ?></label>

                            </div><!-- /c2 -->
                            <div class="c2">
                                <label>Provincia:</label>
								<label style="width: 120px"><?php echo $car->getCity()->getState() ?></label>

                            </div><!-- /c2 -->
                            <div class="c2 clear">
                                <label>Localidad:</label>
								<label style="width: 120px"><?php echo $car->getCity() ?></label>

                            </div><!-- /c2 -->
                        </div><!-- /c1 -->

                        <input type="hidden" id="lat" name="lat" value="" />
                        <input type="hidden" id="lng" name="lng" value="" />
                        <input type="hidden" id="id" name="id" value="<?php echo  $car->getId() ?>" />


                    </div><!-- upcar_sector_1 -->

                    <div class="upcar_sector_2">
                        <p>Breve descripci&oacute;n de tu auto:</p>
                        <label style="width: 470px"><?php echo $car->getDescription() ?></label>     
                    </div><!-- upcar_sector_2 -->




                    <!-- fotografias adicionales -->

					<?php if($car->getPhotoFile('photo1') && $car->getPhotoFile('photo2') && $car->getPhotoFile('photo3') && $car->getPhotoFile('photo4') && $car->getPhotoFile('photo5')) :?>
                    <div class="upcar_box_2">
                        <div class="upcar_box_2_header"><h2>Fotograf&iacute;as adicionales</h2></div>

                        <ul class="upcar_fotos">
                            <li>
                                <div id="previewphoto1"> 


                                    <?php if ($car->getPhotoFile('photo1')): ?>
                                        <?php echo  image_tag("cars/" . $car->getPhotoFile('photo1')->getFileName(), 'size=74x74') ?>
                                    <?php else: ?>
                                        <?php echo  image_tag('img_subi_tu_auto/upcar_sin_foto.png', 'size=74x74 class=imagen') ?>
                                    <?php endif; ?>

                                </div>

                            </li>

                            <li>
                                <div id="previewphoto2"> 

                                    <?php if ($car->getPhotoFile('photo2') != NULL): ?>
                                        <?php echo  image_tag("cars/" . $car->getPhotoFile('photo2')->getFileName(), 'size=74x74') ?>
                                    <?php else: ?>
                                        <?php echo  image_tag('img_subi_tu_auto/upcar_sin_foto.png', 'size=74x74 class=imagen') ?>
                                    <?php endif; ?>

                                </div>

                            </li>

                            <li>
                                <div id="previewphoto3"> 

                                    <?php if ($car->getPhotoFile('photo3') != NULL): ?>
                                        <?php echo  image_tag("cars/" . $car->getPhotoFile('photo3')->getFileName(), 'size=74x74') ?>
                                    <?php else: ?>
                                        <?php echo  image_tag('img_subi_tu_auto/upcar_sin_foto.png', 'size=74x74 class=imagen') ?>
                                    <?php endif; ?>

                                </div> 

                            </li>

                            <li>
                                <div id="previewphoto4"> 


                                    <?php if ($car->getPhotoFile('photo4') != NULL): ?>
                                        <?php echo  image_tag("cars/" . $car->getPhotoFile('photo4')->getFileName(), 'size=74x74') ?>
                                    <?php else: ?>
                                        <?php echo  image_tag('img_subi_tu_auto/upcar_sin_foto.png', 'size=74x74 class=imagen') ?>
                                    <?php endif; ?>


                                </div>

                            </li>

                            <li>
                                <div id="previewphoto5"> 
                                    <?php if ($car->getPhotoFile('photo5') != NULL): ?>
                                        <?php echo  image_tag("cars/" . $car->getPhotoFile('photo4')->getFileName(), 'size=74x74') ?>
                                    <?php else: ?>
                                        <?php echo  image_tag('img_subi_tu_auto/upcar_sin_foto.png', 'size=74x74 class=imagen') ?>
                                    <?php endif; ?>

                                </div>

                            </li>
                        </ul>
                    </div><!-- upcar_box_2 -->
					<?php endif?>





                </div><!-- upcar_box_1 -->


            </div>





        </div><!-- main_contenido -->

          <div class="main_col_right">
			<?php include_component('profile', 'newsfeed') ?>
        </div> 

    </div><!-- main_box_2 -->
    <div class="clear"></div>
</div><!-- main_box_1 -->