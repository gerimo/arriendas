
<?php use_stylesheet('registro.css') ?>
<?php use_stylesheet('my_reserves.css')   ?>
<?php use_stylesheet('comunes.css') ?>
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
       
       <?php if($reserva->getConfirmed() == 0) :?>
        $('#f_date' + idReserva + ',#t_date' + idReserva).datetimepicker({
            addSliderAccess: true,
            sliderAccessArgs: { touchonly: false },
            minuteGrid: 15
        }); 
        $('#f_date' + idReserva).removeAttr('disabled');
        $('#t_date' + idReserva).removeAttr('disabled');
        $('#modo_vista_' + idReserva).css('display','none');
        $('#modo_edicion_' + idReserva).removeClass('modo_edicion');
      <?php else :?>
      	alert("No es posible modificar el arriendo ya que se encuentra en estado: confirmado");
      <?php endif;?>
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
        
        <?php if($reserva->getConfirmed() == 0) :?>
        if(confirm('\xBF Est\xE1 seguro ?')){
        
            var url = <?php echo "'" . url_for('profile/deleteReserve?idReserva=') . "'"; ?> + idReserva;
            
            location.href=url;
        
        }
		<?php else :?>
		alert("No es posible eliminar el arriendo ya que se encuentra en estado: confirmado");
		<?php endif;?>        
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

        <?php include_component('profile', 'profile') ?>

        <!--  contenido de la seccion -->
        <div class="main_contenido">

            <div class="barraSuperior">
            <p>MODIFICAR ARRIENDO</p>
            </div>

            <?php if ($reserva): ?>
                <!-- coche item-->
                <div class="auto" id="auto<?php echo $reserva->getId() ?>">
                    <div class="foto_auto">
                        <?php if ($reserva->getCar()->getPhotoFile() != NULL): ?> 
                            <?= image_tag("cars/" . $reserva->getCar()->getPhotoFile()->getFileName(), 'width=159') ?>
                        <?php else: ?>
                            <?= image_tag('default.png', 'size=64x54') ?>  
                        <?php endif; ?>
                    </div><!-- land_arecomend_frame -->
                    <div class="info_auto">
                        <ul>
                            <li><?= $reserva->getBrand(); ?> <?= $reserva->getModel(); ?></li>
                            <li>A&ntilde;o: <?= $reserva->getYear() ?></li>
                            <li>D&iacute;a: $<?= $reserva->getPriceday() ?> - Hora: $<?= $reserva->getPricehour() ?></li>
                            <li>Direcci&oacute;n: <?= $reserva->getAddress() ?></li>
                            <li><label>Fecha de Retiro: </label>
                                <input size="20" type="text" id="f_date<?php echo $reserva->getId(); ?>" value="<?= $reserva->getDate() ?>" disabled="disabled" readonly="true" />
                                <input size="20" type="hidden" id="f_date_orig<?php echo $reserva->getId(); ?>" value="<?= $reserva->getDate() ?>" disabled="disabled" />
                            </li>
                            <li><label>Fecha de Entrega: </label>
                                <input size="20" type="text" id="t_date<?php echo $reserva->getId(); ?>" value="<?php echo $reserva->getFechafin(); ?>" disabled="disabled" readonly="true" />
                                <input size="20" type="hidden" id="t_date_orig<?php echo $reserva->getId(); ?>" value="<?php echo $reserva->getFechafin(); ?>" disabled="disabled" />
                            </li>
                        </ul>
                    </div>
                    <div id="modo_vista_<?php echo $reserva->getId(); ?>" class="botones_edit">
                        <a onclick="editar(<?php echo $reserva->getId(); ?>)" title="Modificar"><?= image_tag('img_mis_reservas/edit.png', 'size=32x32') ?></a><span>Modificar</span>
                        <a onclick="eliminar(<?php echo $reserva->getId(); ?>)" title="Eliminar"><?= image_tag('img_mis_reservas/delete.png', 'size=32x32') ?></a><span>Eliminar</span>
                    </div>
                    <div id="modo_edicion_<?php echo $reserva->getId(); ?>" class="modo_edicion botones_edit">
                        <a onclick="guardar(<?php echo $reserva->getId(); ?>,<?php echo $reserva->getIdcar(); ?>)" title="Guardar"><?= image_tag('img_mis_reservas/edit.png', 'size=32x32') ?></a><span>Guardar</span>
                        <a onclick="cancelar(<?php echo $reserva->getId(); ?>)" title="Cancelar"><?= image_tag('img_mis_reservas/delete.png', 'size=32x32') ?></a><span>Cancelar</span>
                    </div>
                </div><!-- auto -->
                <div class="vertodos">
                    <a href="<?= url_for("profile/pedidos") ?>">Ver todos</a>
                </div>
            <?php else: ?>
                <?php echo '<p class="mensaje_alerta">No tienes arriendos</p>'; ?>
            <?php endif; ?>
            <div class="clear"></div>
        </div><!-- main_contenido -->

        <?php include_component('profile', 'colDer') ?>

    </div><!-- main_box_2 -->
    <div class="clear"></div>
</div><!-- main_box_1 -->

