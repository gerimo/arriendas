<?php $User = $Reserve->getUser() ?>

<p>Hola <?php echo $User->firstname?>,</p>

<p>¡Se ha aprobado tu reserva!</p>
<p>Si tu arriendo no se concreta, Arriendas.cl no le pagará al dueño del auto y te daremos un auto a elección.</p>
<p>Los datos del arriendo y el contrato se encuentran adjuntos en formato PDF.</p>

<?php include_partial("emails/footer") ?>