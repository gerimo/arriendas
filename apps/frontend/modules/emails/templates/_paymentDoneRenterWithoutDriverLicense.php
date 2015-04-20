<?php
    $Renter = $Renter;
?>

<p><?php echo $Renter->firstname ?>, has pagado la reserva.</p>

<p>Antes de acceder a los datos del dueño, debes subir la foto de tu licencia de conducir</p> 

<p>Inicia sesión en arriendas.cl y sube tu foto.</p> 

<h3>Datos del propietario</h3>

<table>
    <tr>
        <th style='text-align: left'>Nombre</th>
        <td>XXXXXXXX XXXXXXXX</td>
    </tr>
    <tr>
        <th style='text-align: left'>Teléfono</th>
        <td>XXXXXXX</td>
    </tr>
    <tr>
        <th style='text-align: left'>Correo electrónico</th>
        <td>XXXXXXXXX@XXXXX.XXX</td>
    </tr>
    <tr>
        <th style='text-align: left'>Dirección</th>
        <td>XXXXXXX XXXXX, XX XXXXXX</td>
    </tr>
</table>

<p>Los datos del arriendo y la versión escrita del formulario de entrega, se encuentran adjuntos en formato PDF.</p>

<?php include_partial("emails/footer") ?>