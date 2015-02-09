<?php
    $User = $Transaction->getUser();
    $Reserve = $Transaction->getReserve();
?>
<h3>Datos del Usuario</h3>
<table>
    <tr>
        <th style='text-align: left'>ID</th>
        <td><?php echo $User->id ?></td>
    </tr>
    <tr>
        <th style='text-align: left'>Nombre</th>
        <td><?php echo $User->firstname." ".$User->lastname ?></td>
    </tr>
    <tr>
        <th style='text-align: left'>Teléfono</th>
        <td><?php echo $User->telephone ?></td>
    </tr>
    <tr>
        <th style='text-align: left'>Correo electrónico</th>
        <td><?php echo $User->email ?></td>
    </tr>
    <tr>
        <th style='text-align: left'>Dirección</th>
        <td><?php echo $User->address ?></td>
    </tr>
    <tr>
        <th style='text-align: left'>Comuna</th>
        <td><?php echo $User->getCommune()->name ?></td>
    </tr>
    <tr>
        <th style='text-align: left'>Rut</th>
        <td><?php echo $User->getRutFormatted() ?></td>
    </tr>
    
</table>

<h3>Datos del La Reserva</h3>
<table>
    <tr>
        <th style='text-align: left'>ID</th>
        <td><?php echo $Reserve->id ?></td>
    </tr>
    <tr>
        <th style='text-align: left'>Fecha inicio</th>
        <td><?php echo $Reserve->getFechaInicio2() ?></td>
    </tr>
    <tr>
        <th style='text-align: left'>Fecha de término</th>
        <td><?php echo $Reserve->getFechaTermino() ?></td>
    </tr>
    <tr>
        <th style='text-align: left'>Duración</th>
        <td><?php echo $Reserve->duration?></td>
    </tr>
    <tr>
        <th style='text-align: left'>Garantía</th>
        <td><?php echo $Reserve->getMontoLiberacion() ?></td>
    </tr>
    
</table>

<h3>Datos del La Transacción</h3>
<table>
    <tr>
        <th style='text-align: left'>ID</th>
        <td><?php echo $Transaction->id ?></td>
    </tr>
    <tr>
        <th style='text-align: left'>Vehículo</th>
        <td><?php echo $Transaction->getCar() ?></td>
    </tr>
    <tr>
        <th style='text-align: left'>Precio</th>
        <td><?php echo $Transaction->price ?></td>
    </tr>
    
</table>