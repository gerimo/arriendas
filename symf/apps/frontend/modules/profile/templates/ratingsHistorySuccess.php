<?php use_stylesheet('calificaciones.css') ?>
<?php use_stylesheet('calendario.css') ?>
<?php use_stylesheet('registro.css') ?>

<div class="main_box_1">
<div class="main_box_2">

<script>
function 	submitFrom(nameform)
{
	//alert(nameform);
	document.forms[nameform].submit();
}
</script>

<div class="main_col_izq">

<div class="usuario_foto_frame">


<?php include_component("profile","pictureFile",array("user"=>$user,"params"=>"width=129px height=147px"));?>

</div><!-- usuario_foto_frame -->

<div class="usuario_info">
    <span class="usuario_nombre">
	<span >Usuario:</span><?php include_component("messages","linkMessage",array("user"=>$user))?>
	</span>
</div><!-- usuario_info -->


</div><!-- main_col_izq -->
    
<!--  contenido de la seccion -->
<div class="main_contenido">


<h1 class="calen_titulo_seccion">Historial de Calificaciones</span></h1>     

<?php foreach ($ratingsCompleted as $r): ?>

<?php 
	$reserves = $r->getReserve();
	$reserve = $reserves[0];
	$car = $reserve->getCar();
?>

<style>
.calif_preguntas
{
	float: left;
	margin-left: 10px;
	margin-bottom: 20px;
	width: 370px;
	padding: 10px;
	color: #979797;
	font-size: 11px;
	line-height: 14px;
}
</style>

	<!-- calificacion de usuario____________________ -->
	<div class="calif_user_box">
		
	<div class="calif_user_info">


	<!-- preguntas -->
<ul class="calif_general">    
			<li class="calif_auto">Auto: <span><?= $car->getModel(); ?> <?= $car->getModel()->getBrand(); ?></span></li>
			<li><b>Fecha:</b> <?= date("Y-m-d",strtotime ($reserve->getDate())); ?></li>
			<li><b>Duraci&oacute;n:</b> <?= $reserve->getDuration() ?> horas</li>
			<li><b>Califficci&oacute;n:</b> <?
			
			$i =$r->getQualified();
			
			switch ($i) {
				case 0:
					echo "Negativa";
					break;
				case 1:
					echo "Positiva";
					break;
			}
			
			?> </li>
			</ul><!-- calif_general -->

	<!--  resumen experiencia -->
	<p>Resumen de tu experiencia con el usuario:</p>
	<div class="calif_preguntas">  
	<?= $r->getUserRenterOpnion() ?>
	</div>
	
	
	<!-- calig_preguntas -->

	<!-- contraparte -->
	<p>Comentario contraparte:</p>
	<div class="calif_contraparte" >
	<?= $r->getUserOwnerOpnion() ?>
	</div>
	
	<!-- boton calificar -->
	
	<input type="hidden" id="id" name="id" value="<?= $r->getId()?>">
	
	<a href="#" onclick="submitFrom('my<?= $r->getId() ?>')" class="calif_btn_calificar"></a>

	</div><!-- calif_user_info -->

	<div class="calif_user_frame">
	
		<?php if ($reserve->getUser()->getPictureFile() != NULL): ?>
            <?= image_tag("users/".$reserve->getUser()->getFileName(), 'size=84x84') ?>
    <?php else: ?>
            <?= image_tag('img_registro/tmp_user_foto.jpg', 'size=84x84') ?>
     <?php endif; ?>
	
	
	</div>

	<div class="calif_user_nombre">
	<a href="<?php echo url_for('profile/ratingsHistory?id='. $reserve->getUser()->getId()) ?>">
	<?= $reserve->getUser()->getFirstname(); ?> <?= substr($reserve->getUser()->getLastname(), 0, 1); ?>.
	</a>
	</div>
	<div class="clear"></div>
	</div><!-- calif_user_box -->

	<!-- /calificacion de usuario____________________ -->

	
<?php endforeach; ?>


</div><!-- main_contenido -->


        <div class="main_col_right">
			<?php include_component('profile', 'newsfeed') ?>
        </div>
    
</div><!-- main_box_2 -->
<div class="clear"></div>
</div><!-- main_box_1 -->





