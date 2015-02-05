<?php
    $Owner = $Car->getUser();
?>

<p>Hola <?php echo $Owner->firstname ?>,</p>

<p>Necesitaríamos que nos indiques en qué horarios podrías recibir clientes durante los próximos días.</p>

<ul>
    <li>Si puedes recibir clientes el <?php ?> entre 8am y 8pm, has <a href='<?php echo $urlAllAva ?>'>click aquí</a>.</li>
    <li>Si sólo puedes recibir clientes el ".$days[1]." entre 8am y 8pm, has <a href='<?php echo $urlOneAva ?>'>click aquí</a>.</li>
    <li>Si puedes recibir clientes en horarios específicos, has <a href='<?php echo $urlCusAva ?>'>click aquí</a>.</li>
</ul>

<p>Se te informará con un mínimo de 3 horas de anticipación para que puedas gestionar la entrega. Tu auto figurará como disponible para el pago, para reservas iniciadas en esos horarios.</p>

<?php include_partial("emails/footer", array("userId" => $Owner->id, "mailingId" => 2)) ?>

<img src='<?php echo $imageUrl ?>'>