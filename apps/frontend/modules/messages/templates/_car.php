<?php
    $cont = 0;
    $aprobadas = $user->getCantReservasAprobadas();
    $preaprobadas = $user->getCantReservasPreaprobadas();
    $pendientes = $user->getCantReservasPendientes();
    $cont = $cont + $aprobadas + $preaprobadas + $pendientes;
?>
<ul class="notif_validado">
    <?php
    if($aprobadas>0){
        if($aprobadas == 1){
            echo "<li class='estadoReserva'><a href='".url_for('profile/pedidos')."'>Tienes $aprobadas pedido de reserva aprobado</a></li>";
        }else{
            echo "<li class='estadoReserva'><a href='".url_for('profile/pedidos')."'>Tienes $aprobadas pedidos de reserva aprobados</a></li>";
        }
    }
    if($preaprobadas>0){
        if($preaprobadas == 1){
            echo "<li class='estadoReserva'><a href='".url_for('profile/pedidos')."'>Tienes $preaprobadas pedido de reserva preaprobado</a></li>";
        }else{
            echo "<li class='estadoReserva'><a href='".url_for('profile/pedidos')."'>Tienes $preaprobadas pedidos de reserva preaprobados</a></li>";
        }
    }
    if($pendientes>0){
        if($pendientes == 1){
            echo "<li class='estadoReserva'><a href='".url_for('profile/pedidos')."'>Tienes $pendientes pedido de reserva pendiente</a></li>";
        }else{
            echo "<li class='estadoReserva'><a href='".url_for('profile/pedidos')."'>Tienes $pendientes pedidos de reserva pendientes</a></li>";
        }
    }
    ?>
    <?php if($user->getAutosSinVerificar() && $user->getPropietario()): ?><li><a href="mailto:soporte@arriendas.cl?subject=Reserva%20un%20horario%20para%20que%20verifiquemos%20tu%20auto">Tienes autos sin verificar</a></a></li><?php $cont++; endif; ?>
    <?php if($user->getEstadoVerificacion() && $user->getPropietario()): ?><li><a href="<?php echo url_for("profile/cars"); ?>">Verificaci&oacute;n incompleta</a></li><?php $cont++; endif; ?>
    <?php if($user->getAutosPendientesVerificar() && $user->getPropietario()): ?><li style='height: 37px;'><a href="#"><a href="<?php echo url_for("profile/cars"); ?>">Tienes autos en espera de verificacion</a></li><?php $cont++; endif; ?>
</ul>

<div id="dialog_car" style="display: none;"><?php echo $cont; ?></div>
<script>
    var cont = $('#dialog_car').html();
    if(cont > 0){
        $('.dialog#car').html(cont); 
        $('.dialog#car').css('display','block');
        if(cont>9){
            $('.dialog#car').addClass('dialogAncho');
        }
    } else {
        $('.dialog#car').css('display','none');
    }
</script>
<style type="text/css">
    .estadoReserva{
        height: 35px !important;
        background-image: none !important;
    }
    .dialogAncho{
        width: 15px !important;
    }
</style>