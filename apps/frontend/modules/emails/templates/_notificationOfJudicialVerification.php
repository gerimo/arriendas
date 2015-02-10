<?php
    $User = $User;
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