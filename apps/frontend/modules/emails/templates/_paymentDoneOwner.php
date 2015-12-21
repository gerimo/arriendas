<?php 
    $Owner  = $Reserve->getCar()->getUser();
    $Renter = $Reserve->getUser();
?>

<p>Hola <?php echo $Owner->firstname ?>,</p>

<p>Has recibido un pago por <strong>$<?php echo number_format(($Reserve->getPrice()), 0, ',', '.') ?></strong>, desde el <strong><?php echo date("d-m-Y H:i", strtotime($Reserve->getFechaInicio2())) ?></strong> hasta el <strong><?php echo date("d-m-Y H:i", strtotime($Reserve->getFechaTermino2())) ?></strong>.</p>

<p>Para aprobar debes ingresar a tu perfil de Arriendas.cl en la sección reservas.</p>

<h3><strong>IMPORTANTE</strong></h3>
<ul>
    <li>Recuerda, en caso de siniestro dejar constancia inmediatamente en la comisaría más cercana.</li>
    <li>Revisar el auto al momento de la devolución junto con el arrendatario.</li>
    <li>Firmar los contratos al inicio y término de tu arriendo.</li>
</ul>

<?php include_partial("emails/footer") ?>