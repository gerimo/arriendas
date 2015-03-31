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

<p>Tenemos una alta demanda por estar próximos a al fin de semana largo de samana santa. Es por esto que necesitamos nos indiques tu disponibilidad para recibir clientes durante los próximos días:</p>

<ul>
    <?php foreach ($days as $day): ?>
        <li><?php echo $week[date("N", strtotime($day))]." ".date("d", strtotime($day)) ?></li>
    <?php endforeach ?>
</ul>

<p>Para indicar tu disponibilidad haz <a href='<?php echo $urlMisAutos ?>'><strong>click aquí</strong></a>.</p>

<!-- <p>Se te informará con un mínimo de 3 horas de anticipación para que puedas gestionar la entrega. Tu auto figurará como disponible para el pago, para reservas iniciadas en esos horarios.</p> -->

<?php include_partial("emails/footer", array("userId" => $Owner->id, "mailingId" => 2)) ?>

<img src='<?php echo $imageUrl ?>'>