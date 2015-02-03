<?php
    $Owner = $Car->getUser();
?>

<p>Hola <?php echo $Owner->firstname ?>,</p>

<p>Hemos recibido un pago por el arriendo de un auto con características muy similares a las de tu auto patente <b><?php echo strtoupper($Car->patente) ?></b>. Los datos de esta oportunidad son los siguientes: </p>

<table>
    <tr>
        <td style='text-align: left'>Fecha de inicio</td>
        <th style='text-align: left'><?php echo date("d-m-Y H:i", strtotime($Reserve->getFechaInicio2())) ?></th>
    </tr>
    <tr>
        <td style='text-align: left'>Fecha de termino</td>
        <th style='text-align: left'><?php echo date("d-m-Y H:i", strtotime($Reserve->getFechaTermino2())) ?></th>
    </tr>
    <tr>
        <td style='text-align: left'>Valor</td>
        <th style='text-align: left'>$<?php echo number_format($Reserve->price, 0, ',', '.') ?></th>
    </tr>
</table>

<p>Para postular a esta oportunidad, puedes hacerlo a través de nuestra <strong><a href='<?php echo $acceptUrl ?>'>sección de oportunidades</a></strong>.</p>

<?php include_partial("emails/footer", array("userId" => $Owner->id, "mailingId" => 1)) ?>

<img src='<?php echo $imageUrl ?>'>