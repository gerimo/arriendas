<?php
    $Owner  = $Reserve->getCar()->getUser();
    $Renter = $Reserve->getUser();
?>

<p>Hola <?php echo $Owner->firstname ?>,</p>

<p>Han aceptado tu postulación por un total de <strong> $<?php echo number_format($Reserve->price, 0, ',', '.') ?></strong>, desde el <strong><?php echo date("d-m-Y H:i", strtotime($Reserve->getFechaInicio2())) ?></strong> hasta el <strong><?php echo date("d-m-Y H:i", strtotime($Reserve->getFechaTermino2())) ?></strong>. Te recomendamos que llames al arrendatario cuanto antes. Sus datos son los siguientes:</p>

<table>
    <tr>
        <th style='text-align: left'>Nombre</th>
        <td><?php echo $Renter->firstname." ".$Renter->lastname ?></td>
    </tr>
    <tr>
        <th style='text-align: left'>Teléfono</th>
        <td><?php echo $Renter->telephone ?></td>
    </tr>
    <tr>
        <th style='text-align: left'>Correo electrónico</th>
        <td><?php echo $Renter->email ?></td>
    </tr>
</table>

<?php include_partial("emails/footer") ?>