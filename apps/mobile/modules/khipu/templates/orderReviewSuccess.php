<?php use_stylesheet('transacciones.css') ?>
<?php use_stylesheet('registro.css') ?>
<?php use_stylesheet('comunes.css') ?>

<style>
table td
{
padding:15px;
}
</style>

<div class="main_box_1">
<div class="main_box_2">
    

<div class="main_col_izq">

<?php include_component('profile', 'userinfo') ?>

</div><!-- main_col_izq -->
    
<!--  contenido de la seccion -->
<div class="main_contenido">
<h1 class="calen_titulo_seccion">Confirmar transacci&oacute;n</h1>


	
<div style="padding:20px; width:520px">

<?php if($sf_user->getFlash('show')): ?>
	
	<div style="border:1px solid #FF0000; background: #fcdfff; 	width:100%; display:table;margin-bottom:20px; padding:20px;font-size:14px;">
		<?php echo $sf_user->getFlash('msg'); ?>
	</div>

<?php else: ?>

	<table style="width:100%">
		<tr>
			<td><b>Email de PayPal:</b></td>
			<td><?= $email?></td>
		</tr>
		<tr>
			<td><b>ID PayPal:</b></td>
			<td><?=$payerId?></td>
		</tr>
		<?//$salutation?>
		<tr>
			<td><b>Nombre:</b></td>
			<td><?=$firstName?></td>
		</tr>
		<?//$middleName?></td></tr>
		<tr>
			<td><b>Apellido:</b></td>
			<td><?=$lastName?></td>
		</tr>
		<?//$suffix?></td></tr>
		<tr>
			<td><b>Pais:</b></td>
			<td><?=$cntryCode?></td>
		</tr>
		
		<tr>
			<td><b>Precio:</b></td>
			<td><?=$price?></td>
		</tr>
		
		<tr>
			<td><b>Auto:</b></td>
			<td><?=$car?></td>
		</tr>
		
		<?//$business?>
		
		
	</table>
	<br/><br/>
	<a href="<?php echo url_for('paypal/confirm')?>" /><?= image_tag('btn_pago.png') ?></a>
	
	<?php endif; ?>
	
</div>

</div><!-- main_contenido -->

<div class="main_col_dere">

<?php include_component('profile', 'rightsidebar') ?>

</div><!-- main_col_dere -->
    
</div><!-- main_box_2 -->
<div class="clear"></div>
</div><!-- main_box_1 -->








