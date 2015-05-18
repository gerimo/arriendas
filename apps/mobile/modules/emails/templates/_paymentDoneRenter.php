<?php
    $Owner  = $Reserve->getCar()->getUser();
    $Renter = $Reserve->getUser();
    $Car    = $Reserve->getCar();
?>

<p><?php echo $Renter->firstname ?>, has pagado la reserva.</p>

<p>El dueño tiene 4 horas para aprobar la reserva. Si no lo hace, el sistema te ofrecerá opciones de reemplazo.</p> 

<p>En caso de que el precio del auto de reemplazo sea mayor, arriendas pondrá un 15% del valor del auto ya pagado.</p> 

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
</table>

<p>Los datos del arriendo y la versión escrita del formulario de entrega, se encuentran adjuntos en formato PDF.</p>

<?php include_partial("emails/footer") ?>