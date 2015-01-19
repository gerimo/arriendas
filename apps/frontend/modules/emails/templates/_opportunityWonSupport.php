<?php
    $Owner  = $Reserve->getCar()->getUser();
    $Renter = $Reserve->getUser();
    $Car    = $Reserve->getCar();
?>

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
        <td><?php echo $Owner->address ?></td>
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

<h3>Datos del arrendatario</h3>
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