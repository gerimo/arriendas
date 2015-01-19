<?php
    $Owner  = $Reserve->getCar()->getUser();
    $Renter = $Reserve->getUser();
    $Car    = $Reserve->getCar();
?>

<p>Hola <?php echo $Renter->firstname ?>,</p>

<p>Has cambiado el auto de tu reserva y esta ya está confirmada. Recuerda que debes llenar el <strong>Formulario de entrega</strong> Y <strong>Devolución</strong> del vehículo.</p>
<p>No des inicio al arriendo si el auto tiene más daños que los declarados.</p>

<h3>Datos del propietario</h3>

<table>
    <tr>
        <th style='text-align: left'>Nombre</th>
        <td><?php echo $Owner->firstname." ".$Owner->lastname ?></td>
    </tr>
    <tr>
        <th style='text-align: left'>Teléfono</th>
        <td><?php echo $Owner->telephone ?></td>
    </tr>
    <tr>
        <th style='text-align: left'>Correo electrónico</th>
        <td><?php echo $Owner->email ?></td>
    </tr>
    <tr>
        <th style='text-align: left'>Dirección</th>
        <td><?php echo $Owner->address .", ".$Car->getCommune()->name ?></td>
    </tr>
    <tr>
        <th style='text-align: left'>Marca</th>
        <td><?php echo $Car->getModel()->getBrand()->name ?></td>
    </tr>
    <tr>
        <th style='text-align: left'>Modelo</th>
        <td><?php echo $Car->getModel()->name ?></td>
    </tr>
</table>

<p>Los datos del arriendo y la versión escrita del formulario de entrega, se encuentran adjuntos en formato PDF.</p>

<?php include_partial("emails/footer") ?>