<?php
    $Owner = $Car->getUser();

    $week = array(
        1 => "Lunes",
        2 => "Martes",
        3 => "Miércoles",
        4 => "Jueves",
        5 => "Viernes",
        6 => "Sábado",
        7 => "Domingo"
    );
?>

<p>Hola <?php echo $Owner->firstname ?>,</p>

<p><strong>Para recibir clientes este FIN DE SEMANA, debes indicar tu disponibilidad</strong>.</p>

<ul>
    <li>Si tienes disponibilidad de <strong><?php echo $week[date("N", strtotime($days[0]))] ?></strong> a <strong><?php echo $week[date("N", strtotime($days[count($days)-1]))] ?></strong> de 08:00 a 20:00, has <a href='<?php echo $urlWeekend ?>'>click aquí</a>.</li>
    <li>Si tienes disponibilidad solo algunos días y horarios, has <a href='<?php echo $urlMisAutos ?>'>click aquí</a>.</li>
</ul>

<p>Tu auto figurará como disponible para el pago, para reservas iniciadas en esos horarios.</p>

<?php include_partial("emails/footer", array("userId" => $Owner->id, "mailingId" => 2)) ?>

<img src='<?php echo $imageForTrackEmail ?>'>