<?php use_stylesheet('comunes.css') ?>
<?php use_stylesheet('registro.css') ?>
<?php use_stylesheet('my_reserves.css') ?>
<script>
    
    $(document).ready(function(){
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

    });
    
    var cal;
    
    function editar(idReserva){
        
        $('#f_date' + idReserva + ',#t_date' + idReserva).datetimepicker({
            addSliderAccess: true,
            sliderAccessArgs: { touchonly: false },
            minuteGrid: 15
        }); 
        $('#f_date' + idReserva).removeAttr('disabled');
        $('#t_date' + idReserva).removeAttr('disabled');
        $('#modo_vista_' + idReserva).css('display','none');
        $('#modo_edicion_' + idReserva).removeClass('modo_edicion');
    }
    
    function cancelar(idReserva){
        $('#f_date' + idReserva).val($('#f_date_orig' + idReserva).val());
        $('#t_date' + idReserva).val($('#t_date_orig' + idReserva).val());
        $('#f_date' + idReserva).attr('disabled','disabled');
        $('#t_date' + idReserva).attr('disabled','disabled');
        $('#modo_vista_' + idReserva).css('display','block');
        $('#modo_edicion_' + idReserva).addClass('modo_edicion');
    }
    
    
    function eliminar(idReserva){
        
        if(confirm('\xBF Est\xE1 seguro ?')){
        
            var url = <?php echo "'" . url_for('profile/deleteReserve?idReserva=') . "'"; ?> + idReserva;
            
            location.href=url;
        
        }
    }
    
    function guardar(idReserva, idCar){
           
        $.ajax({
            type: "POST" ,
            url: <?php echo "'" . url_for('profile/editReserve') . "'"; ?>,
            data: 'idReserva=' + idReserva + '&carid=' + idCar + '&fechainicio=' + $('#f_date' + idReserva).val() + '&fechafin=' + $('#t_date' + idReserva).val(),
            success: function(data){
                if(data == '0'){
                    alert('Su reserva ha sido modificada exitosamente');
                    var url = <?php echo "'" . url_for('profile/pedidos') . "'"; ?>;
                    location.href = url;
                } else {
                    alert(data);
                }
            }
        });
    }
    
</script>

<div class="main_box_1">
    <div class="main_box_2">

        <div class="main_col_izq">
            <?php include_component('profile', 'userinfo') ?>
        </div><!-- main_col_izq -->

        <!--  contenido de la seccion -->
        <div class="main_contenido">

            <h1 class="calen_titulo_seccion">Mis Reservas</h1>
            <p>Para contactarte con nosotros haz click <a href="<?php echo url_for('help/index'); ?>">aqu&iacute;</a></p>



            <?php if (count($misreservas)): ?>
                <?php foreach ($misreservas as $r): ?>
                    <!-- coche item-->
                    <a id="<?php echo $r->getId() ?>"></a>
                    <div class="auto" id="auto<?php echo $r->getId() ?>">
                        <div class="foto_auto">
                            <?php if ($r->getCar()->getId() != NULL): ?> 
                                <?= image_tag("cars/" . $r->getCar()->getId() . ".jpg", 'width=159') ?>
                            <?php else: ?>
                                <?= image_tag('default.png', 'size=64x54') ?>  
                            <?php endif; ?>

                        </div><!-- land_arecomend_frame -->
                        <?php
                        //calcular fecha de entrega en funcion de la duracion y fecha de inicio

                        $inicioUnix = strtotime($r->getDate());

                        $duracion = $r->getDuration() * 60 * 60;

                        $finUnix = $inicioUnix + $duracion;

                        $entrega = date('Y-m-d H:i:s', $finUnix);
                        ?>
                        <div class="info_auto">
                            <ul>
                                <li><?= $r->getBrand(); ?> <?= $r->getModel() ?></li>
                                <li>A&ntilde;o: <?= $r->getYear() ?></li>
                                <li>D&iacute;a: $<?= $r->getPriceday() ?> - Hora: $<?= $r->getPricehour() ?></li>
                                <li>Direcci&oacute;n: <?= $r->getAddress() ?></li>
                                <li><label>Fecha de Retiro: </label>
                                    <input size="20" type="text" id="f_date<?php echo $r->getId(); ?>" value="<?= $r->getDate() ?>" disabled="disabled" readonly="true" />
                                    <input size="20" type="hidden" id="f_date_orig<?php echo $r->getId(); ?>" value="<?= $r->getDate() ?>" disabled="disabled" />
                                </li>
                                <li><label>Fecha de Entrega: </label>
                                    <input size="20" type="text" id="t_date<?php echo $r->getId(); ?>" value="<?php echo $entrega; ?>" disabled="disabled" readonly="true" />
                                    <input size="20" type="hidden" id="t_date_orig<?php echo $r->getId(); ?>" value="<?php echo $entrega; ?>" disabled="disabled" />
                                </li>
                            </ul>
                        </div>
                        <div id="modo_vista_<?php echo $r->getId(); ?>">
                            <a onclick="editar(<?php echo $r->getId(); ?>)" title="Modificar"><?=image_tag('img_mis_reservas/edit.png', 'size=32x32')?></a><span>Modificar</span>
                            <a onclick="eliminar(<?php echo $r->getId(); ?>)" title="Eliminar"><?=image_tag('img_mis_reservas/delete.png', 'size=32x32')?></a><span>Eliminar</span>
                        </div>
                        <div id="modo_edicion_<?php echo $r->getId(); ?>" class="modo_edicion">
                            <a onclick="guardar(<?php echo $r->getId(); ?>,<?php echo $r->getIdcar(); ?>)" title="Guardar"><?=image_tag('img_mis_reservas/edit.png', 'size=32x32')?></a><span>Guardar</span>
                            <a onclick="cancelar(<?php echo $r->getId(); ?>)" title="Cancelar"><?=image_tag('img_mis_reservas/delete.png', 'size=32x32')?></a><span>Cancelar</span>
                        </div>
                    </div><!-- auto -->
                    <div class="vertodos">
                        <a href="<?=url_for("profile/rental")?>">Ver todos</a>
                    </div>
                <?php endforeach; ?> 
            <?php else: ?>
                <?php echo '<p>No tienes reservas</p>'; ?>
            <?php endif; ?>
            <div class="clear"></div>
            
            			<!-- Se mueve include del div izq -->
            <?php include_component('profile', 'myreserves') ?>
            
        </div><!-- main_contenido -->
        <div class="main_col_right">
			<?php include_component('profile', 'newsfeed') ?>
        </div>    
    </div><!-- main_box_2 -->
    <div class="clear"></div>
</div><!-- main_box_1 -->

