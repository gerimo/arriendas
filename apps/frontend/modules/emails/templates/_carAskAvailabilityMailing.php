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

    $dayNames = array();

    foreach ($days as $day)
        $dayNames[] = $week[date("N", strtotime($day))];

    if (count($dayNames) == 2) {
        $daysString = str_replace(",", " y", implode(", ", $dayNames));
    } elseif (count($dayNames) > 2) {
        $daysString = str_replace(", ".$dayNames[count($dayNames)-1], " y ".$dayNames[count($dayNames)-1], implode(", ", $dayNames));
    }
?>

<p>Hola <?php echo $Owner->firstname ?>,</p>

<p>Necesitaríamos que nos indiques en qué horarios podrías recibir clientes durante los próximos días.</p>

<ul>
    <li>Si puedes recibir clientes el <strong><?php echo $daysString ?></strong> entre 8am y 8pm, has <a href='<?php echo $urlAllAva ?>'>click aquí</a>.</li>
    <li>Si sólo puedes recibir clientes el <strong><?php echo $dayNames[count($dayNames)-1] ?></strong> entre 8am y 8pm, has <a href='<?php echo $urlOneAva ?>'>click aquí</a>.</li>
    <li>Si puedes recibir clientes en horarios específicos, has <a href='<?php echo $urlCusAva ?>'>click aquí</a>.</li>
</ul>

<p>Se te informará con un mínimo de 3 horas de anticipación para que puedas gestionar la entrega. Tu auto figurará como disponible para el pago, para reservas iniciadas en esos horarios.</p>

<?php include_partial("emails/footer", array("userId" => $Owner->id, "mailingId" => 2)) ?>

<img src='<?php echo $imageUrl ?>'>