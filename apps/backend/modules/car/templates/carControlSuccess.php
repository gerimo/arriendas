	
<div class="hidden-xs space-40"></div>
<div class="visible-xs space-50"></div>

<div class="row">
	<div class="col-md-offset-2 col-md-8">
		<h1> Control Autos</h1> 
		<div class="space-30"> </div>
		<div class="col-md-offset-4 col-md-4">
            <label>Cantidad de autos a mostrar</label>
            <select class="form-control" id="limit" name="limit" placeholder="" type="text">
                <?php
                    for ($i = 50; $i <= 300; $i += 50):
                ?>
                    <option value="<?php echo $i ?>" <?php if ($i == 50) echo "selected" ?> ><?php echo $i?></option>
                <?php
                    endfor;
                ?>
                <option value="false">Todos</option>
            </select>
        </div>

		<img class="load" src="/images/ajax-loader.gif">
		<div class="space-100"></div>
	</div>

		
	<div class="col-md-12">
		<table  class="display responsive no-wrap" id="reserveTable" cellspacing="0" width="100%">
			<thead>
				<tr>
					<th>ID</th>
					<th>Comuna</th>
					<th>Marca</th>
					<th>Modelo</th>
					<th>Año</th>
					<th>Transmisión</th>
					<th>Tipo Vehículo</th>
					<th>Metro Cercano</th>
					<th>Cant. reserva ult. 3 meses</th>
					<th>Nombre usuario</th>
					<th>Telefono usuario</th>
					<th>email usuario</th>
				</tr>
			</thead>

			<tbody>
			</tbody>
		</table>  
	</div>

	<div style="display:none">
        <input id="userI">
		<div id="dialog-alert" title="">
			<p></p>
		</div>
	</div>
</div>

<div class="hidden-xs space-10f0"></div>

	

<script>

	$(document).ready(function() {

		findCarControl();
	 
		$('#reserveTable').DataTable({
			info: false,
			paging: true,
			responsive: true
		});    
	}); 

	function findCarControl() {

		var limit     = $("#limit option:selected").val();    
		$(".load").show();

		$.post("<?php echo url_for('car_find_all') ?>", {"limit": limit}, function(r){
			if (r.error) {
				$('#reserveTable').DataTable().rows().remove().draw();
				$(".load").hide();
				console.log(r.errorMessage);
			} else {	
				$('#reserveTable').DataTable().rows().remove().draw();
                $('#reserveTable').DataTable().column(0).order( 'desc' );
				$.each(r.data, function(k, v){
					$('#reserveTable').DataTable().row.add([v.id, v.comunne, v.brand, v.model, v.year, v.transmission, v.type, v.nearestMetroName, v.QuantityOfLatestRents, v.user_name , v.user_telephone, v.user_email]).draw();
				});
				$(".load").hide();
			}
		}, 'json');
	}

    $('#limit').change(function(e){
        findCarControl();
    });


</script>