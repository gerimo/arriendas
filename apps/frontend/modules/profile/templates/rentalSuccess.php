<?php use_stylesheet('calendario.css') ?>
<?php use_stylesheet('registro.css') ?>
<?php use_stylesheet('mis_arriendos.css') ?>
<script>
    function eliminar(idReserva){
        
        if(confirm('\xBF Est\xE1 seguro ?')){
        
            var url = <?php echo "'" . url_for('profile/deleteReserve?idReserva=') . "'"; ?> + idReserva;
            location.href = url;
        
        }
    }
    
    function guardarkm(id,tipo){
        var kms = $('#km' + tipo + id).val();
        $.ajax({
            type: 'POST',
            data: 'id=' + id + '&tipo=' + tipo + '&kms=' + kms,
            url: '<?php echo url_for('profile/guardarKms'); ?>',
            success: function(data){
                if(data == 'ok'){
                    location.href='<?php echo url_for('profile/rental'); ?>'
                } else {
                    alert(data);
                }
            }
        });
    }
    function aprobarkm(id,tipo){

        $.ajax({
            type: 'POST',
            data: 'id=' + id + '&tipo=' + tipo,
            url: '<?php echo url_for('profile/aprobarKm'); ?>',
            success: function(data){
                if(data == 'OK'){
                    alert('Kms ' + tipo + ' confirmado exitosamente');
                    location.href = '<?php echo url_for('profile/rental'); ?>'
                } else {
                    alert('Error');
                }
            }
        });
    }
    
    function desaprobarkm(id,tipo){

        $.ajax({
            type: 'POST',
            data: 'id=' + id + '&tipo=' + tipo,
            url: '<?php echo url_for('profile/desaprobarKm'); ?>',
            success: function(data){
                if(data == 'OK'){
                    alert('Kms ' + tipo + ' ha sido desaprobado exitosamente');
                    location.href = '<?php echo url_for('profile/rental'); ?>'
                } else {
                    alert('Error');
                }
            }
        });
    }
    
    $(document).ready(function(){
        $('input:text').focus(function(){
            if($(this).val() == 'Km Inicial'){
                $(this).val('');
            } else {
                if($(this).val() == 'Km Final'){
                    $(this).val('');
                }
            }
        });
        $('input:text').blur(function(){
            if($(this).attr('id').substring(0, 3) == 'kmi'){
                if($(this).val().length == 0){
                    $(this).val('Km Inicial');
                }
            } else {
                
                if($(this).val().length == 0){
                    $(this).val('Km Final');
                }
            }
        });
        
		$("a.vercontrato").live("click", function(event) {

			event.preventDefault();
			
			var inicio = $('#desde_' + $(this).attr('id') ).text();
			var termino = $('#hasta_' + $(this).attr('id') ).text();
			window.open( $(this).attr("href") + '&inicio=' + inicio + '&termino=' + termino );

		})
    });
</script>

<div class="main_box_1">
    <div class="main_box_2">


        <?php include_component('profile', 'profile') ?>

        <!--  contenido de la seccion -->
        <div class="main_contenido">

            <div class="barraSuperior">
                <p>TUS RESERVAS DE AUTOS</p>
            </div>

            <?php if (count($ownerinprogress) || count($cars)): ?>
                <table class="misArriendos">
                    <tr>
                        <th>Estado</th>
                        <th>Foto</th>
                        <th>Desde</th>
                        <th>Hasta</th>
                        <th>Env&iacute;a un mensaje</th>
                        <th colspan="2">Acciones</th>
                    </tr>
                    <?php foreach ($ownerinprogress as $reserve): ?>
                        <tr>
                            <td>En curso</td>
                            <td>
                                <?
                                if ($reserve->getCar()->getPhotoFile('main')) {
                                    echo image_tag("cars/" . $reserve->getCar()->getPhotoFile('main')->getFileName(), 'size=68x68');
                                } else {
                                    echo image_tag('default.png', 'size=68x68');
                                }
                                ?>
                            </td>
                            <td><?= $reserve->getDate() ?></td>
                            <td><?= $reserve->getFechafin() ?></td>
                            <td class="enviarmsj">
                                <?php include_component("profile", 'pictureFile', array("user" => $reserve->getUser(), "params" => "width=23px height=23px")) ?><br/>
                                <?php include_component("messages", "linkMessage", array("user" => $reserve->getUser())) ?> <br/>
                                (<?php include_component("messages", "positiveRatings", array("user" => $reserve->getUser())) ?>)
                            </td>
                            <!--td class="enviarmsj">
                                <?php if (strtotime("now") >= strtotime($reserve->getDate() . " -30 minutes")): ?>
                                    <input type="text" id="kminicial<?php echo $reserve->getId(); ?>" class="km" value="<?php if ($reserve->getKminicial()) echo $reserve->getKminicial(); ?>"/> 
                                    <?php if ($reserve->getKminicial() == 0): ?>
                                        <?=
                                        image_tag('img_mis_reservas/save_.png', array('onclick' => 'guardarkm(' . $reserve->getId() . ',"inicial")',
                                            'width' => '16px',
                                            'height' => '16px',
                                            'class' => 'btnOk'));
                                        ?>
                                    <?php else: ?>
                                        <?=
                                        image_tag('img_mis_reservas/ok.png', array('onclick' => 'aprobarkm(' . $reserve->getId() . ',"inicial")',
                                            'width' => '16px',
                                            'height' => '16px',
                                            'class' => 'btnOk'));
                                        ?>
                                        <?=
                                        image_tag('img_mis_reservas/delete.png', array('onclick' => 'desaprobarkm(' . $reserve->getId() . ',"inicial")',
                                            'width' => '16px',
                                            'height' => '16px',
                                            'class' => 'btnOk'));
                                        ?>
                                    <?php endif; ?>
                                    <br/>
                                    <input type="text" id="kmfinal<?php echo $reserve->getId(); ?>" class="km" value="<?php if ($reserve->getKmfinal()) echo $reserve->getKmfinal(); ?>"/> 
                                    <?php if ($reserve->getKmfinal() == 0): ?>
                                        <?=
                                        image_tag('img_mis_reservas/save_.png', array('onclick' => 'guardarkm(' . $reserve->getId() . ',"final")',
                                            'width' => '16px',
                                            'height' => '16px',
                                            'class' => 'btnOk'));
                                        ?>
                                    <?php else: ?>
                                        <?=
                                        image_tag('img_mis_reservas/ok.png', array('onclick' => 'aprobarkm(' . $reserve->getId() . ',"final")',
                                            'width' => '16px',
                                            'height' => '16px',
                                            'class' => 'btnOk'));
                                        ?>
                                        <?=
                                        image_tag('img_mis_reservas/delete.png', array('onclick' => 'desaprobarkm(' . $reserve->getId() . ',"final")',
                                            'width' => '16px',
                                            'height' => '16px',
                                            'class' => 'btnOk'));
                                        ?>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </td -->
                            <td><?= link_to('Modificar', 'profile/modificarArriendo?id=' . $reserve->getId(), array('title' => 'Modificar')) ?><br/>
                            <?php if($reserve->getConfirmed() == 0) :?>
                            <a class="link_sin_ruta" onclick="eliminar(<?php echo $reserve->getId(); ?>)" title="Eliminar">Eliminar</a>
                            <?php endif;?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if (count($cars)): ?>
                        <?php foreach ($cars as $reserve): ?>
                            <tr>
                                <td>Confirmadas</td>
                                <td>
                                    <?
                                    if ($reserve->getCar()->getPhotoFile('main')) {
                                        echo image_tag("cars/" . $reserve->getCar()->getPhotoFile('main')->getFileName(), 'size=68x68');
                                    } else {
                                        echo image_tag('default.png', 'size=68x68');
                                    }
                                    ?>
                                </td>
                                <td><?= $reserve->getDate() ?></td>
                                <td><?= $reserve->getFechafin() ?></td>
                                <td class="enviarmsj">
                                    <?php include_component("profile", 'pictureFile', array("user" => $reserve->getUser(), "params" => "width=23px height=23px")) ?><br/>
                                    <?php include_component("messages", "linkMessage", array("user" => $reserve->getUser())) ?> <br/>
                                    (<?php include_component("messages", "positiveRatings", array("user" => $reserve->getUser())) ?>)
                                </td>
                                <!-- td class="enviarmsj">
                                    <?php if (strtotime("now") >= strtotime($reserve->getDate() . " -30 minutes")): ?>
                                        <input type="text" id="kminicial<?php echo $reserve->getId(); ?>" class="km" value="<?php if ($reserve->getKminicial()) echo $reserve->getKminicial(); ?>"/> 
                                        <?php if ($reserve->getKminicial() == 0): ?>
                                            <?=
                                            image_tag('img_mis_reservas/save_.png', array('onclick' => 'guardarkm(' . $reserve->getId() . ',"inicial")',
                                                'width' => '16px',
                                                'height' => '16px',
                                                'class' => 'btnOk'));
                                            ?>
                                        <?php else: ?>
                                            <?=
                                            image_tag('img_mis_reservas/ok.png', array('onclick' => 'aprobarkm(' . $reserve->getId() . ',"inicial")',
                                                'width' => '16px',
                                                'height' => '16px',
                                                'class' => 'btnOk'));
                                            ?>
                                            <?=
                                            image_tag('img_mis_reservas/delete.png', array('onclick' => 'desaprobarkm(' . $reserve->getId() . ',"inicial")',
                                                'width' => '16px',
                                                'height' => '16px',
                                                'class' => 'btnOk'));
                                            ?>
                                        <?php endif; ?>
                                        <br/>
                                        <input type="text" id="kmfinal<?php echo $reserve->getId(); ?>" class="km" value="<?php if ($reserve->getKmfinal()) echo $reserve->getKmfinal(); ?>"/> 
                                        <?php if ($reserve->getKmfinal() <= 0): ?>
                                            <?=
                                            image_tag('img_mis_reservas/save_.png', array('onclick' => 'guardarkm(' . $reserve->getId() . ',"final")',
                                                'width' => '16px',
                                                'height' => '16px',
                                                'class' => 'btnOk'));
                                            ?>
                                        <?php else: ?>
                                            <?=
                                            image_tag('img_mis_reservas/ok.png', array('onclick' => 'aprobarkm(' . $reserve->getId() . ',"final")',
                                                'width' => '16px',
                                                'height' => '16px',
                                                'class' => 'btnOk'));
                                            ?>
                                            <?=
                                            image_tag('img_mis_reservas/delete.png', array('onclick' => 'desaprobarkm(' . $reserve->getId() . ',"final")',
                                                'width' => '16px',
                                                'height' => '16px',
                                                'class' => 'btnOk'));
                                            ?>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </td -->
                                <td><?= link_to('Modificar', 'profile/modificarArriendo?id=' . $reserve->getId(), array('title' => 'Modificar')) ?><br/>
                                	
                                <?php if($reserve->getConfirmed() == 0) :?>
                                <a class="link_sin_ruta" onclick="eliminar(<?php echo $reserve->getId(); ?>)" title="Eliminar">Eliminar</a>
                                <?php endif;?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </table>
            <?php else: ?>
                <p class="mensaje_alerta">No tienes arriendos en curso o confirmado</p>
            <?php endif; ?>

            <?php if (count($toconfirm)): ?>
            	<h1 class="calen_titulo_seccion">Reservas por Confirmar</span></h1> 
                <table class="misArriendos">
                    <tr>
                        <th>Desde - Hasta</th>
                        <th>Estado</th>
                        <th>Env&iacute;a un mensaje</th>
                        <th>Calificaciones positivas</th>
                        <th>Puntaje de limpieza</th>
                        <th colspan="2">Acciones</th>
                    </tr>
                    <?php foreach ($toconfirm as $reserve): ?>
                        <tr>
                            <td id="desde_<?=$reserve->getId()?>"><?
                                $desde = strtotime($reserve->getDate());
                                echo date("d/m/y G:i",$desde);

                                echo " - ";

                                $hasta = strtotime($reserve->getFechafin());
                                echo date("d/m/y G:i",$hasta);
                                //echo $reserve->getFechafin();
                            ?></td>
                            <td>Por confirmar</td>
                            <td class="enviarmsj">
                                <?php include_component("profile", 'pictureFile', array("user" => $reserve->getUser(), "params" => "width=23px height=23px")) ?><br/>
                                <?php include_component("messages", "linkMessage", array("user" => $reserve->getUser())) ?>
                            </td>
                            <td><?php include_component("messages", "positiveRatings", array("user" => $reserve->getUser())) ?></td>
                            <td><?php include_component("messages", "cleanRatings", array("user" => $reserve->getUser())) ?></td>
                            <td>
                            	<input type="checkbox" id="chkcontrato_<?php echo $reserve->getId()?>" value="1"><a target="_blank" href="<?php echo url_for('http://www.arriendas.cl/api.php/contrato/generarContrato/tokenReserva/'.$reserve->getToken())?>" class="vercontrato" id="<?php echo $reserve->getId()?>">Contrato</a><br/>
                            	<?= link_to('Confirmar', 'profile/confirmReserve?id=' . $reserve->getId(), array('title' => 'Confirmar', 'id' => $reserve->getId() , 'class' => 'Confirmar')) ?><br/>
                                <?php
                                echo link_to('Cancelar', 'profile/cancelReserveConfirmation?id=' . $reserve->getId(), array(
                                    'onclick' => 'return validar()', 'title' => 'Cancelar'
                                ))
                                ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            <?php else: ?>
                <p class="mensaje_alerta">No tienes arriendos pendientes de confirmar</p>
            <?php endif; ?>

        </div><!-- main_contenido -->

        <?php include_component('profile', 'colDer') ?>

        <div class="clear"></div>
    </div><!-- main_box_2 -->
</div><!-- main_box_1 -->