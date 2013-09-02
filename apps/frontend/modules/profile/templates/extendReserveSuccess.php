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
        
    	

    });

</script>



<div class="main_box_1">
    <div class="main_box_2">

        <?php include_component('profile', 'profile') ?>


        <!--  contenido de la seccion -->
        <div class="main_contenido">

            <!-- btn agregar otro auto -->
            <!--<a href="#" class="upcar_btn_agregar_auto"></a>-->  

            <div class="barraSuperior">
            <p>EXTENDER RESERVA</p>
            </div>

            <div class="regis_formulario">  

                <div class="calen_box_3">
                    <div style="padding-top:20px; padding-left:20px;font-size:12px">

                        <?php echo form_tag('profile/doReserve', array('method' => 'post', 'id' => 'frm1')); ?>	

                        <span class="mensaje_alerta"><?php echo $sf_user->getFlash('msg'); ?></span>

                        <div class="c1 height">
                            <label>Desde</label>
                            <input id="datefrom" name="datefrom" type="text" class="datepicker" readonly="readonly" value="Día de inicio" /><br/><br/>
                            <input readonly="readonly" type="text" id="hour_from" name="hour_from" readonly="readonly" value="Hora de inicio"/>
                        </div><!-- /c1 -->


                        <div class="c1 height">
                            <label>Hasta</label>
                            <input id="dateto" name="dateto" type="text" class="datepicker" readonly="readonly" value="Día de entrega"/><br/><br/>
                            <input readonly="readonly" type="text" id="hour_to" name="hour_to" value="Hora de entrega" />
                        </div><!-- /c1 -->

                        <input type="hidden" name="duration" id="duration"/>


                        <input type="hidden" name="id" id="id" value="<?= $car->getId() ?>"/>
                        <input type="hidden" name="reserve_id" id="reserve_id" value="<?= $reserva->getId() ?>"/>
                        <input type="hidden" name="extend" id="extend" value="1"/>

                        <div class="clear"></div>

                        <input type="submit" value="Confirmar" class="btnAmarillo" style="margin-top:0;" />

                        </form>  

                    </div>
                </div>
            </div>

            <h1 class="trans_titulo_seccion">Informaci&oacute;n del auto</h1>   
            <!-- transacciones en curso -->
            <div class="upcar_box_1">
                <div class="upcar_box_1_header"><h2>Detalles del coche</h2></div>



                <div class="upcar_sector_1">
                    <div class="upcar_foto_frame">
                        <div id="previewmain">
                            <?php if ($car->getPhotoFile('main') != NULL): ?> 
                                <?= image_tag("cars/" . $car->getPhotoFile('main')->getFileName(), 'size=174x174') ?>
                            <?php else: ?>
                                <?= image_tag('default.png', 'size=174x174') ?>  
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
                                <input type="text" name="brand" id="brand" value="<?= $car->getModel()->getBrand() ?>" readonly="readonly" >
                            </div><!-- /c2 -->
                            <div class="c2">
                                <label>Modelo del auto:</label>
                                <input type="text" name="model" id="model" value="<?= $car->getModel() ?>"  readonly="readonly">
                            </div><!-- /c2 -->
                        </div><!-- /c1 --> 

                        <div class="c1 height">
                            <div class="c2">
                                <label>Precio por d&iacute;a:</label>
                                <input type="text" name="priceday" id="priceday" value="<?= $car->getPricePerDay() ?>" readonly="readonly" >
                            </div><!-- /c2 -->
                            <div class="c2">
                                <label>Precio por hora:</label>
                                <input type="text" name="pricehour" id="pricehour" value="<?= $car->getPricePerHour() ?>"  readonly="readonly">
                            </div><!-- /c2 -->
                        </div><!-- /c1 --> 


                        <div class="c1 height">
                            <label>A&ntilde;o</label>
                            <input type="text" id="year" name="year" value="<?= $car->getYear() ?>" readonly="readonly">
                        </div>

                        <div class="c1 height">
                            <label>Kilometros</label>
                            <input type="text" id="km" name="km" value="<?= $car->getKm() ?>" readonly="readonly">

                        </div>

                        <div class="c1 height">
                            <label>Direcci&oacute;n</label>
                            <input type="text" id="address" name="address" value="<?= $car->getAddress() ?>" readonly="readonly">
                        </div>

                        <div class="c1 height">
                            <div class="c2">
                                <label>Pa&iacute;s:</label>

                                <input type="text" style="width:120px" name="country" id="country" value="<?= $car->getCity()->getState()->getCountry() ?>"  readonly="readonly">

                            </div><!-- /c2 -->
                            <div class="c2">
                                <label>Región:</label>

                                <input type="text"  style="width:120px" name="state" id="state" value="<?= $car->getCity()->getState() ?>"  readonly="readonly">

                            </div><!-- /c2 -->
                            <div class="c2">
                                <label>Comuna:</label>

                                <input type="text" style="width:120px"  name="city" id="city" value="<?= $car->getCity() ?>"  readonly="readonly">

                            </div><!-- /c2 -->
                        </div><!-- /c1 -->

                        <input type="hidden" id="lat" name="lat" value="" />
                        <input type="hidden" id="lng" name="lng" value="" />
                        <input type="hidden" id="id" name="id" value="<?= $car->getId() ?>" />


                    </div><!-- upcar_sector_1 -->

                    <div class="upcar_sector_2">
                        <p>Breve descripci&oacute;n de tu auto:</p>
                        <textarea id="description" readonly="readonly" name="description"row="20" cols="20"><?= $car->getDescription() ?></textarea>     
                    </div><!-- upcar_sector_2 -->




                    <!-- fotografias adicionales -->


                    <div class="upcar_box_2">
                        <div class="upcar_box_2_header"><h2>Fotograf&iacute;as adicionales</h2></div>

                        <ul class="upcar_fotos">
                            <li>
                                <div id="previewphoto1"> 


                                    <?php if ($car->getPhotoFile('photo1')): ?>
                                        <?= image_tag("cars/" . $car->getPhotoFile('photo1')->getFileName(), 'size=74x74') ?>
                                    <?php else: ?>
                                        <?= image_tag('img_subi_tu_auto/upcar_sin_foto.png', 'size=74x74 class=imagen') ?>
                                    <?php endif; ?>

                                </div>

                            </li>

                            <li>
                                <div id="previewphoto2"> 

                                    <?php if ($car->getPhotoFile('photo2') != NULL): ?>
                                        <?= image_tag("cars/" . $car->getPhotoFile('photo2')->getFileName(), 'size=74x74') ?>
                                    <?php else: ?>
                                        <?= image_tag('img_subi_tu_auto/upcar_sin_foto.png', 'size=74x74 class=imagen') ?>
                                    <?php endif; ?>

                                </div>

                            </li>

                            <li>
                                <div id="previewphoto3"> 

                                    <?php if ($car->getPhotoFile('photo3') != NULL): ?>
                                        <?= image_tag("cars/" . $car->getPhotoFile('photo3')->getFileName(), 'size=74x74') ?>
                                    <?php else: ?>
                                        <?= image_tag('img_subi_tu_auto/upcar_sin_foto.png', 'size=74x74 class=imagen') ?>
                                    <?php endif; ?>

                                </div> 

                            </li>

                            <li>
                                <div id="previewphoto4"> 


                                    <?php if ($car->getPhotoFile('photo4') != NULL): ?>
                                        <?= image_tag("cars/" . $car->getPhotoFile('photo4')->getFileName(), 'size=74x74') ?>
                                    <?php else: ?>
                                        <?= image_tag('img_subi_tu_auto/upcar_sin_foto.png', 'size=74x74 class=imagen') ?>
                                    <?php endif; ?>


                                </div>

                            </li>

                            <li>
                                <div id="previewphoto5"> 
                                    <?php if ($car->getPhotoFile('photo5') != NULL): ?>
                                        <?= image_tag("cars/" . $car->getPhotoFile('photo4')->getFileName(), 'size=74x74') ?>
                                    <?php else: ?>
                                        <?= image_tag('img_subi_tu_auto/upcar_sin_foto.png', 'size=74x74 class=imagen') ?>
                                    <?php endif; ?>

                                </div>

                            </li>
                        </ul>
                    </div><!-- upcar_box_2 -->

                </div><!-- upcar_box_1 -->
            </div>


        </div><!-- main_contenido -->

        <?php include_component('profile', 'colDer') ?>

    </div><!-- main_box_2 -->
    <div class="clear"></div>
</div><!-- main_box_1 -->