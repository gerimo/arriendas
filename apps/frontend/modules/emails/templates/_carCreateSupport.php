<?php
    $Owner  = $Car->getUser();
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
        <th style='text-align: left'>Comuna</th>
        <td><?php echo $Owner->getCommune() ?></td>
    </tr>
    <tr>
        <th style='text-align: left'>Rut</th>
        <td><?php echo $Owner->rut ?></td>
    </tr>
    
</table>

<h3>Datos del vehículo</h3>
<table>
    <tr>
        <th style='text-align: left'>Marca</th>
        <td><?php echo $Car->getModel()->getBrand()->name ?></td>
    </tr>
    <tr>
        <th style='text-align: left'>Modelo</th>
        <td><?php echo $Car->getModel() ?></td>
    </tr>
    <tr>
        <th style='text-align: left'>Dirección</th>
        <td><?php echo $Car->address ?></td>
    </tr>
    <tr>
        <th style='text-align: left'>Comuna</th>
        <td><?php echo $Car->getCommune() ?></td>
    </tr>
    <tr>
        <th style='text-align: left'>Patente</th>
        <td><?php echo $Car->patente ?></td>
    </tr>
    
</table>

