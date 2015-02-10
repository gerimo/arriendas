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
        <td><?php echo $Reserve->getFechaTermino2() ?></td>
    </tr>
    <tr>
        <th style='text-align: left'>Duración</th>
        <td><?php echo $Reserve->duration?></td>
    </tr>
    <tr>
        <?php if ($Reserve->getLiberadoDeGarantia()): ?>
            <th style='text-align: left'>Liberación garantía</th>
            <td>$<?php echo number_format($Reserve->getMontoLiberacion(), 0, ',', '.') ?></td>
        <?php else: ?>
            <th style='text-align: left'>Depósito garantía</th>
            <td>$<?php echo number_format(180000, 0, ',', '.') ?></td>
        <?php endif ?>
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
        <td>$<?php echo number_format($Transaction->price, 0, ',', '.') ?></td>
    </tr>
    
</table>