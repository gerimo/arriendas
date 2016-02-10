<?php $User = $Reserve->getUser() ?>

<p>Hola <?php echo $User->firstname?>,</p>

<p>El dueño ha indicado que no tiene el auto disponible.</p>
<p>Puedes cambiar de auto haciendo click aqui.</p>
<p>Comunicate con nosotros al (02) 23333714.</p>

<?php include_partial("emails/footer") ?>