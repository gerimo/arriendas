<div class="main_box_1">
    <div class="main_box_2">
	
<?php if($ppId): ?>
	
	<form name="pp" action="<? echo url_for('bcpuntopagos/creacion?id='.$ppId)?>" method="post">
		
		<h1>Pagando con Punto Pagos Orden #<?php echo $ppId ?>. Monto: $ <?php echo number_format($ppMonto, 0, ',', '.') ?></h1>
		
		<?php if($sf_user->getFlash('notice')): ?>
		<div class="notice"><?php echo $sf_user->getFlash('notice')?></div>
		<?php endif ?>
		<?php if($sf_user->getFlash('error')): ?>
		<div class="error"><?php echo $sf_user->getFlash('error')?></div>
		<?php endif ?>

		<div>
		Selecciona tu medio de pago
		</div>
		
		<div>
			
			<table width="600" border="0" cellpadding="0" cellspacing="0">
				<tr>
					<td valign="middle"><input type="radio" name="pp_medio_pago" value="1"/> <?php echo image_tag('puntopagos/mp1.gif')?></td>
					<td valign="middle"><input type="radio" name="pp_medio_pago" value="2"/> <?php echo image_tag('puntopagos/mp2.gif')?></td>
					<td valign="middle"><input type="radio" name="pp_medio_pago" value="3"/> <?php echo image_tag('puntopagos/mp3.gif')?></td>
				</tr>
				<tr>
					<td valign="middle"><input type="radio" name="pp_medio_pago" value="4"/> <?php echo image_tag('puntopagos/mp4.gif')?></td>
					<td valign="middle"><input type="radio" name="pp_medio_pago" value="5"/> <?php echo image_tag('puntopagos/mp5.gif')?></td>
					<td valign="middle"><input type="radio" name="pp_medio_pago" value="6"/> <?php echo image_tag('puntopagos/mp6.gif')?></td>
				</tr>
				<tr>
					<td valign="middle"><input type="radio" name="pp_medio_pago" value="7"/> <?php echo image_tag('puntopagos/mp7.gif')?></td>
					<td valign="middle"><input type="radio" name="pp_medio_pago" value="10"/> <?php echo image_tag('puntopagos/mp10.gif')?></td>
					<td valign="middle"><input type="radio" name="pp_medio_pago" value="15"/> <?php echo image_tag('puntopagos/mp15.gif')?></td>
				</tr>
			</table>
			
		</div>
		<div>
			Presionando &quot;Pagar&quot; entrar&aacute;s a la plataforma de pagos en l&iacute;nea para generar la transferencia mediante una conexi&oacute;n segura.
			<br/><br/>
		</div>
		<div>
			<input type="hidden" name="idReserva" value="<?php echo $ppIdReserva?>" />
			<input type="submit" value=" Pagar "/>
		</div>
	
	</form>
	
<?php else: ?>
	<?php if($sf_user->getFlash('error') || $error): ?>
		<div class="error"><?php echo $sf_user->getFlash('error')?$sf_user->getFlash('error'):$error ?></div>
	<?php endif ?>
<?php endif ?>
    
    </div>
    <div class="clear"></div>
</div>

