<?php
    $Owner  = $Reserve->getCar()->getUser();
    $Renter = $Reserve->getUser();
    $Car    = $Reserve->getCar();
?>

<p><?php echo $Renter->firstname ?>, has pagado la reserva.</p>

<p>Debes ponerte en contacto con el dueño del auto para coordinar la entrega, imprimir los contratos de arriendo (contrato, formulario y pagaré) y presentarlos el día del retiro del auto.</p>

<p>En caso de que el auto pagado no este disponible por favor escríbenos a <a href="mailto:soporte@arriendas.cl">soporte@arriendas.cl</a>.</p>

<h3><strong>IMPORTANTE</strong></h3>
<ul>
    <li>Recuerda, en caso de siniestro dejar constancia inmediatamente en la comisaría más cercana.</li>
    <li>Revisar el auto al momento de la devolución junto con el dueño.</li>
    <li>Firmar los contratos al inicio y término de tu arriendo.</li>
</ul>

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

<?php include_partial("emails/footer") ?>