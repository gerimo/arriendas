
<?php use_stylesheet('calendario.css') ?>


<div class="main_box_1">
<div class="main_box_2">

<?php include_component('profile', 'profile') ?>
    
<!--  contenido de la seccion -->
<div class="main_contenido">

<div class="barraSuperior">
<p>MIS AUTOS</p>
</div>    


<!-- proximas reservas confirmadas -->
<div class="calen_box_3">
<div class="calen_box_3_header"><h2>Mis autos</h2>
<div class="calen_box_header_nro_2"><span><?=$cars->Count()?></span></div>
</div><!-- /calen_box_2_header -->



<table style="padding:10px; margin:10px;width:100%; font-size:12px;">
	
		<?php foreach ($cars as $c): ?>
		<tr>
		      <td><?=$c->getId()?></td>
		      <td><?=$c->getModel()?></td>
		      <td><?=$c->getBrand()?></td>
		      <td><?=$c->getYear()?></td>
		      <td><?=$c->getAddress()?></td>
		      <td><?=$c->getCity()?></td>
		      <td><?=$c->getState()?></td>
		      <td><?=$c->getCountry()?></td>
		      <td><a href="<?php echo url_for('cars/car?id=' . $c->getIdcar() )?>" />Ver</a></td>
		</tr>
		<?php endforeach; ?>
	
	</table>




</div><!-- calen_box_3 -->




</div><!-- main_contenido -->

<?php include_component('profile', 'colDer') ?>
    
</div><!-- main_box_2 -->
<div class="clear"></div>
</div><!-- main_box_1 -->


