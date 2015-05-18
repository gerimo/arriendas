<?php
    $GPSTransaction  = $GPSTransaction;
    $User = $GPSTransaction->getUser();
?>

<p><?php echo $User->firstname ?>, has pagado un gps.</p>

<p>El equipo será enviado a la brevedad a la dirección que especificaste en tu perfil.</p> 

<p>En caso de que la dirección no sea la correcta, porfavor cámbiala y contactate con nosotros a soporte@ariendas.cl .</p> 

<h3>Datos del GPS</h3>

<table>
    <tr>
        <th style='text-align: left'>Descripción</th>
        <td><?php echo $GPSTransaction->description ?></td>
    </tr>
    <tr>
        <th style='text-align: left'>Precio</th>
        <td><?php echo $GPSTransaction->amount ?></td>
    </tr>
    <tr>
        <th style='text-align: left'>Correo electrónico</th>
        <td><?php echo $User->email ?></td>
    </tr>
    <tr>
        <th style='text-align: left'>Dirección</th>
        <td><?php echo $User->address ?></td>
    </tr>
</table>

<?php include_partial("emails/footer") ?>