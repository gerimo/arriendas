<?php use_stylesheet('calendario.css') ?>
<?php use_stylesheet('registro.css') ?>
<?php use_stylesheet('mis_arriendos.css') ?>
<?php use_stylesheet('comunes.css') ?>
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
    });
</script>

<div class="main_box_1">
    <div class="main_box_2">


        <div class="main_col_izq">
            <?php include_component('profile', 'profile') ?>
            <?php include_component('profile', 'userdocsvalidation') ?>

        </div><!-- main_col_izq -->

        <!--  contenido de la seccion -->
        <div class="main_contenido">
 
            <?php if (count($misreservas) || count($misreservasconfirmadas) || count($inprogress)): ?>
                <h1 class="calen_titulo_seccion">Pedidos de Reserva</span></h1>
                <table class="misArriendos">
                    <?php if (count($misreservas)): ?>
                        <tr>
                            <th>Estado</th>
                            <th>Foto</th>
                            <th>Desde</th>
                            <th>Hasta</th>
                            <th>Env&iacute;a un mensaje</th>
                            <!--<th>Puntaje de limpieza</th>-->
                            <th colspan="2">Acciones</th>
                        </tr>
                    <?php endif; ?>
                    <?php foreach ($misreservas as $reserve): ?>
                        <tr>
                            <td>En espera de confirmaci&oacute;n</td>
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
                                <?php include_component("profile", 'pictureFile', array("user" => $reserve->getCar()->getUser(), "params" => "width=23px height=23px")) ?><br/>
                                <?php include_component("messages", "linkMessage", array("user" => $reserve->getCar()->getUser())) ?><br/>
                                (<?php include_component("messages", "positiveRatings", array("user" => $reserve->getCar()->getUser())) ?>)
                            </td>
                            <!--<td><?php include_component("messages", "cleanRatings", array("user" => $reserve->getCar()->getUser())) ?></td>-->
                            <td><?= link_to('Modificar', 'profile/detalleReserva?id=' . $reserve->getId(), array('title' => 'Modificar')) ?><br/>
                            <?= link_to('Extender', 'profile/reserve?idreserve=' . $reserve->getId().'&id='.$reserve->getCar()->getId(), array('title' => 'Extender Reserva')) ?><br/>
                            <a class="link_sin_ruta" onclick="eliminar(<?php echo $reserve->getId(); ?>)" title="Eliminar">Eliminar</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if (count($misreservasconfirmadas)): ?>
                        <tr>
                            <th>Estado</th>
                            <th>Foto</th>
                            <th>Desde</th>
                            <th>Hasta</th>
                            <th>Env&iacute;a un mensaje</th>
                            <!-- th>Kms</th -->
                            <th colspan="2">Acciones</th>
                        </tr>
                        <?php foreach ($misreservasconfirmadas as $reserve): ?>
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
                                    <?php include_component("profile", 'pictureFile', array("user" => $reserve->getCar()->getUser(), "params" => "width=23px height=23px")) ?><br/>
                                    <?php include_component("messages", "linkMessage", array("user" => $reserve->getCar()->getUser())) ?><br/>
                                    (<?php include_component("messages", "positiveRatings", array("user" => $reserve->getCar()->getUser())) ?>)
                                </td>
                                <!--
                                <td class="enviarmsj">
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
                                </td>
                                -->
                                <td>

                                <?= link_to('Extender', 'profile/reserve?idreserve=' . $reserve->getId().'&id='.$reserve->getCar()->getId(), array('title' => 'Extender Reserva')) ?><br/>
                                <?=link_to('Pagar', 'profile/payReserve?id=' . $reserve->getId(), array('title' => 'Pagar Reserva'))?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                    <?php if (count($inprogress)): ?>
                        <tr>
                            <th>Estado</th>
                            <th>Foto</th>
                            <th>Desde</th>
                            <th>Hasta</th>
                            <th>Env&iacute;a un mensaje</th>
                            <!-- th>Kms</th -->
                            <th colspan="2">Acciones</th>
                        </tr>
                        <?php foreach ($inprogress as $reserve): ?>
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
                                    <?php include_component("profile", 'pictureFile', array("user" => $reserve->getCar()->getUser(), "params" => "width=23px height=23px")) ?><br/>
                                    <?php include_component("messages", "linkMessage", array("user" => $reserve->getCar()->getUser())) ?><br/>
                                    (<?php include_component("messages", "positiveRatings", array("user" => $reserve->getCar()->getUser())) ?>)
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
                                <td><?= link_to('Modificar', 'profile/detalleReserva?id=' . $reserve->getId(), array('title' => 'Modificar')) ?><br/>
                                <?= link_to('Extender', 'profile/reserve?idreserve=' . $reserve->getId().'&id='.$reserve->getCar()->getId(), array('title' => 'Extender Reserva')) ?><br/>
                                <a class="link_sin_ruta" onclick="eliminar(<?php echo $reserve->getId(); ?>)" title="Eliminar">Eliminar</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </table>
            <?php else: ?>
                <p class="mensaje_alerta">No tienes pedidos confirmados o en espera de confirmaci&oacute;n</p>
            <?php endif; ?>
        </div><!-- main_box_2 -->
        <div class="main_col_right">
			<?php include_component('profile', 'newsfeed') ?>
        </div>    
        <div class="clear"></div>
    </div><!-- main_box_1 -->
</div>