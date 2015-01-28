<?php
    $Owner  = $Car->getUser();
?>

<p>Hola <?php echo $Owner->firstname ?></p>

<p>Has subido un auto!</p> 

<p>Para verlo publicado te contactará un inspector del seguro (BCI) en los próximos días para hacer la verificación del auto.</p> 

<p>También puedes dirigirte directamente a sus oficinas solicitando la póliza BCI de Arriendas.cl e indicando un código que te daremos respondiendo este mail.</p> 

<h3>CENTROS DE INPECCION</h3>
<p>1.TOMAS MORO 1371</p>
<p>Lunes a Sábado de 10:00 a 20:00 Horas</p>
<p>Domingos y Festivos Cerrado</p>

<br>
<p>2.BATTLE Y ORDOÑEZ 4830 ÑUÑOA</p>
<p>Lunes a Viernes de 9:00 a 20:00 Horas</p>
<p>Domingos y Festivos Cerrado</p>

<p>Ante cualquier duda, llámanos al 2 2640-2900.</p>


<?php include_partial("emails/footer") ?>