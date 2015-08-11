<?php
    $GPSTransaction  = $GPSTransaction;
    $GPS = Doctrine_Core::getTable("gps")->find($GPSTransaction->gps_id);
    $Car = Doctrine_Core::getTable("car")->find($GPSTransaction->car_id);
    $User = $Car->getUser();;
?>

<p>Hola <?php echo $User->firstname ?>,</p>

<p>Tu pago fue recibido con éxito nos encargaremos! <br>

Se enviará el equipo y una tarjeta SIM al domicilio ingresado en la página. <br>

Recuerda que con la devolución del GPS puedes solicitar la devolución del dinero. <br>

Si tienes dudas por favor contactamos al 02-23333714 o a soporte@arriendas.cl.</p> 

<h3>Datos del GPS</h3>

<table>
    <tr>
        <th style='text-align: left'>Descripción</th>
        <td><?php echo $GPS->description ?></td>
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
        <th style='text-align: left'>Dirección de despacho</th>
        <td><?php echo $User->address ?></td>
    </tr>
</table>

<?php include_partial("emails/footer") ?>