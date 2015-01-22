<?php 
    $Owner  = $Reserve->getCar()->getUser();
    $Renter = $Reserve->getUser();
?>

<p>Hola <?php echo $Owner->firstname ?>,</p>

<p>Has recibido un pago por <strong>$<?php echo number_format(($Reserve->getPrice()), 0, ',', '.') ?></strong>, desde el <strong><?php echo date("d-m-Y H:i", strtotime($Reserve->getFechaInicio2())) ?></strong> hasta el <strong><?php echo date("d-m-Y H:i", strtotime($Reserve->getFechaTermino2())) ?></strong>.</p>
<p>Si no apruebas la reserva cuanto antes otro dueño podría ganar la reserva. Para recibir tu pago debes aprobar la reserva haciendo <a href="<?php echo url_for('aprobarReserve', array('idReserve' => $Reserve->id, 'idUser' => $Owner->id), TRUE) ?>">click aquí</a>.</p>

<?php include_partial("emails/footer") ?>