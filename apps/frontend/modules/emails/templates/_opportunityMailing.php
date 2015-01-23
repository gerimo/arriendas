<?php
    $Owner = $Car->getUser();
?>

<p>Hola <?php echo $Owner->firstname ?>,</p>
<p>La oportunidad es por <strong>$<?php echo number_format($Reserve->price, 0, ',', '.') ?></strong> desde <strong><?php echo date("d-m-Y H:i", strtotime($Reserve->getFechaInicio2())) ?></strong> hasta <strong><?php echo date("d-m-Y H:i", strtotime($Reserve->getFechaTermino2())) ?></strong>.</p>
<p>Aprueba la oportunidad haciendo <strong><a href='<?php echo $acceptUrl ?>'>click aquí</a></strong>.</p>

<?php include_partial("emails/footer") ?>

<img src='<?php echo $imageUrl ?>'>