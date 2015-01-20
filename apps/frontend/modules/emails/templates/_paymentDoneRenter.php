<?php
    $Owner  = $Reserve->getCar()->getUser();
    $Renter = $Reserve->getUser();
    $Car    = $Reserve->getCar();
?>

<p>Hola <?php echo $Renter->firstname ?>,</p>

<p>Has pagado la reserva.</p>

<?php include_partial("emails/footer") ?>