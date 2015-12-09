<?php
    $Owner  = $Reserve->getCar()->getUser();
    $Renter = $Reserve->getUser();
    $Car    = $Reserve->getCar();
?>

<p><?php echo $Renter->firstname ?>, has pagado la reserva.</p>

<p>Ahora debes ponerte en contacto con el dueño del auto para coordinar la entrega, imprimir los contratos de arriendo (contrato, formulario y pagare) y presentarlos el día del retiro del auto.</p>

<p>En caso de que el auto pagado no este disponible por favor escríbenos a <a href="mailto:soporte@arriendas.cl">soporte@arriendas.cl</a>.</p>

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