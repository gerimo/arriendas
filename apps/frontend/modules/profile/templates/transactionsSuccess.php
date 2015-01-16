<link href="/css/newDesign/transactions.css" rel="stylesheet" type="text/css">

<div class="hidden-xs space-100"></div>
<div class="visible-xs space-50"></div>

<div class="row">
    <div class="col-md-offset-1 col-md-10">

        <div class="BCW">

			<?php if(!$transaccionesRenter): ?>
				<h2>No tiene transacciones registradas como Arrendatario</h2>
			<?php else: ?>	
				<h2>Transacciones como Arrendatario</h2>
				<div class="table-responsive">
					<table class="table table-bordered">
						<tr>
							<td class='titulo'>FECHA</td>
							<td class='titulo'>MONTO</td>
							<td class='titulo'>COMISI&Oacute;N ARRIENDAS</td>
							<td class='titulo'>PRECIO SEGURO</td>
							<td class='titulo'>NETO</td>
							<td class='titulo'>DEP&Oacute;SITO EN GARANT&Iacute;A</td>
						</tr>

						<?php foreach ($transaccionesRenter as $transaccion) {

							echo "<tr>";
							echo "<td>".$transaccion['fechaInicio']."-".$transaccion['horaInicio']."<br>".$transaccion['fechaTermino']."-".$transaccion['horaTermino']."</td>";
							echo "<td>$".$transaccion['monto']." CLP</td>";
							echo "<td>$".$transaccion['comisionArriendas']." CLP</td>";
							echo "<td>$".$transaccion['precioSeguro']." CLP</td>";
							echo "<td><span class='destacado'>$".$transaccion['neto']." CLP</span><br><span class='textoChico'>Fecha de dep&oacute;sito ".$transaccion['fechaDeposito']."</span></td>";
							echo "<td>$".$transaccion['depositoGarantia']." CLP</td>";
							echo "</tr>";

						}?>
					</table>	
				</div>
			<?php endif ?>

			<?php if(!$transaccionesOwner): ?>
				<h2>No tiene transacciones registradas como Dueño</h2>
			<?php else: ?>
				<h2>Transacciones como dueño</h2>

				<div class="table-responsive">
					<table class="table table-bordered">
						<tr>
							<td class='titulo'>FECHA</td>
							<td class='titulo'>MONTO</td>
							<td class='titulo'>COMISI&Oacute;N ARRIENDAS</td>
							<td class='titulo'>PRECIO SEGURO</td>
							<td class='titulo'>NETO</td>
							<td class='titulo'>DEP&Oacute;SITO EN GARANT&Iacute;A</td>
						</tr>

						<?php foreach ($transaccionesOwner as $transacciones) {

							echo "<tr>";
								echo "<td>".$transacciones['fechaInicio']."-".$transacciones['horaInicio']."<br>".$transacciones['fechaTermino']."-".$transacciones['horaTermino']."</td>";
								echo "<td>$".$transacciones['monto']." CLP</td>";
								echo "<td>$".$transacciones['comisionArriendas']." CLP</td>";
								echo "<td>$".$transacciones['precioSeguro']." CLP</td>";
								echo "<td><span class='destacado'>$".$transacciones['neto']." CLP</span><br><span class='textoChico'>Fecha de dep&oacute;sito ".$transacciones['fechaDeposito']."</span></td>";
								echo "<td>$".$transaccion['depositoGarantia']." CLP</td>";
							echo "</tr>";
						} ?>
					</table>
				</div>

			<?php endif ?>

		</div>
    </div>
</div>

<div class="hidden-xs space-100"></div>
