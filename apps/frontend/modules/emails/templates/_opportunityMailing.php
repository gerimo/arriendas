<?php
    $Owner = $Car->getUser();
?>

<p>Hola <?php echo $Owner->firstname ?>,</p>

<p>Hemos recibido un pago por el arriendo de un auto con características muy similares a las de tu auto <?php echo strtoupper($Car->patente) ?>. Los datos de esta oportunidad son los siguientes: </p>

<table>
    <tr>
        <th style='text-align: left'>Fecha de inicio</th>
        <td><?php echo date("d-m-Y H:i", strtotime($Reserve->getFechaInicio2())) ?></td>
    </tr>
    <tr>
        <th style='text-align: left'>Fecha de termino</th>
        <td><?php echo date("d-m-Y H:i", strtotime($Reserve->getFechaTermino2())) ?></td>
    </tr>
    <tr>
        <th style='text-align: left'>Valor</th>
        <td>$<?php echo number_format($Reserve->price, 0, ',', '.') ?></td>
    </tr>
</table>

<p>Para postular a esta oportunidad, puedes hacerlo a través de nuestra <strong><a href='<?php echo $acceptUrl ?>'>sección de oportunidades</a></strong>.</p>

<?php include_partial("emails/footer") ?>

<img src='<?php echo $imageUrl ?>'>