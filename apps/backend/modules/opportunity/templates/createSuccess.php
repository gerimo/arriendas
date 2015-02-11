<?php use_helper('FrontendRouting') ?>

<div class="hidden-xs space-100"></div>
<div class="visible-xs space-50"></div>

<div class="row">

    <div class="col-md-12">

        <div class="col-md-4">
            <label>Busqueda</label> 
            <input class="form-control" id="idReservaOriginal" placeholder="ID reserva" type="text">
            <div class="hidde-xs space-20"></div>
            <button class="buscar btn btn-block btn-primary" onclick="isOriginalReserve()">Buscar</button>
        </div>

        <div class="visible-xs space-50"></div>

        <div class="col-md-4">
            <label>Cantidad de autos a mostrar</label>
            <select class="form-control" id="limit" name="limit" placeholder="" type="text">
                <?php
                    for ($i = 50; $i <= 300; $i += 50):
                ?>
                    <option value="<?php echo $i ?>"><?php echo $i?></option>
                <?php
                    endfor;
                ?>
              	<option value="false">Todos</option>
            </select>
        </div>

        <div class="visible-xs space-50"></div>

        <div class="col-md-3" id ="filters">
        <label>Filtros de busqueda</label>
            <ul>
                <li><input type="checkbox" name="filter" class="isAutomatic"> Automático</li>
                <li><input type="checkbox" name="filter" class="isLowConsumption"> Petrolero</li>
                <li><input type="checkbox" name="filrer" class="isMorePassengers"> Más de 5 pasajeros</li>
                <li><input type="checkbox" name="filrer" class="withAvailability" <?php if (Utils::isWeekend()): ?> checked<?php endif; ?>> autos con disponibilidad</li>
            </ul>
        </div>
        <div class="col-md-1 text-center">
            <img class="loading" src="/images/ajax-loader.gif">
        </div>
    </div>


	<div class="col-md-12">

        <div class="hidden-xs space-100"></div>
        <div class="visible-xs space-50"></div>
		<table class="display responsive no-wrap" id="carsActivesTable" cellspacing="0" width="100%">
			<thead>
				<tr>
					<th>ID</th>
					<th>Comuna</th>
					<th>Marca</th>
					<th>Modelo</th>
					<th>Año</th>
					<th>Transmisión</th>
					<th>Precio</th>
					<th>Tipo Vehículo</th>
					<th>Metro Cercano</th>
					<th>Cant. reserva ult. 3 meses</th>
					<th>Nombre usuario</th>
					<th>Telefono usuario</th>
					<th>Crear Oportunidad</th>
				</tr>
			</thead>
			<tbody>            
			</tbody>
		</table>
	</div>
</div>
<div style="display:none">
	<div id="dialog-alert" title="">
		<p></p>
	</div>
	<input name="reserveFilter" id="reserveFilter">
</div>

</div>

<div class="hidden-xs space-100"></div>    

<script>
	
	$(document).ready(function() {
		$('#carsActivesTable').DataTable({
			info: false,
			paging: true,
			responsive: true
		});
		
		$('.loading').hide(); 
	});

	$(document).on("click", ".opp-approve", function(){

		var reserveId = $(this).data("reserve-id");
		var carId = $(this).data("car-id");
		$(this).prop("disabled", true);
		
		var parameters = {
			"reserveId" : reserveId,
			"carId" : carId
		};

		console.log(parameters);

		$.post("<?php echo url_for_frontend('opportunities_approve') ?>", parameters, function(r){
			if (r.error) {
				console.log(r.errorMessage);
			} else {

				$("#dialog-alert p").html("Rerserva creada exitosamente");
				$("#dialog-alert").attr('title','Reserva Creada!');
				$("#dialog-alert").dialog({
					buttons: [{
					text: "Aceptar",
					click: function() {
						$( this ).dialog( "close" );
					}
					}]
				});

				$(this).prop("disabled", false);
			}

		}, 'json')
		.fail(function(r) {
			console.log("FAIL");
			console.log(r);
		});
	});

	$("input[type='checkbox']").change(function(){
		getActivesCars($("#reserveFilter").val());
	});

	$("#limit").change(function(){
		getActivesCars($("#reserveFilter").val());
	});

	function isOriginalReserve() {

		var idReservaOriginal = $("#idReservaOriginal").val();

		$("#reserve-check").hide();
		$("#reserve-remove").hide();

		$.post("<?php echo url_for('reserve_is_original_reserve') ?>", {"idReservaOriginal": idReservaOriginal}, function(r){

			if (r.error) {
				console.log(r.errorMessage);
			} else {
				if (r.original != null) {
					if (r.original) {
						getActivesCars(r.original);
						$("#reserve-check").show();
						$("#reserveFilter").val(r.original); 

						if ($("#car-check").is(":visible")) {
							$("#create").prop("disabled", false);
						}

					}else {
						$("#reserve-remove").show(); 
					}
				}
			}

		}, 'json');
	}

	function getActivesCars(reserveId) {

		$(".loading").show();

		var limit     = $("#limit option:selected").val();

		var isAutomatic = false;
		$(".isAutomatic").each(function(){
			if ($(this).is(':checked')) {
				isAutomatic = true;
			}
		});
		
		var isLowConsumption = false;
		$(".isLowConsumption").each(function(){
			if ($(this).is(':checked')) {
				isLowConsumption = true;
			}
		});

		var isMorePassengers = false;
		$(".isMorePassengers").each(function(){
			if ($(this).is(':checked')) {
				isMorePassengers = true;
			}
		});

		var withAvailability = false;
		$(".withAvailability").each(function(){
			if ($(this).is(':checked')) {
				withAvailability = true;
			}
		});

		var parameters = {
			"reserveId": reserveId,
			"isAutomatic" : isAutomatic,
			"isLowConsumption": isLowConsumption,
			"isMorePassengers": isMorePassengers,
			"withAvailability" : withAvailability,
			"limit": limit

		}

		$.post("<?php echo url_for('car_get_actives_cars') ?>", parameters, function(r){

			if (r.error) {
				console.log(r.errorMessage);
			} else {

				$('#carsActivesTable').DataTable().column(9).order( 'desc' );

				$('#carsActivesTable').show();
				$('#carsActivesTable').DataTable().rows().remove().draw();
				
				$.each(r.data, function(k, v){
					var button = "<button class='btn btn-block btn-primary opp-approve' data-reserve-id='"+reserveId+"' data-car-id='"+v.id+"'>Crear</button>";
					$('#carsActivesTable').DataTable().row.add([ v.id, v.comunne, v.brand, v.model, v.year, v.transmission, v.price, v.type, v.nearestMetroName, v.QuantityOfLatestRents, v.user_name , v.user_telephone, button ]).draw();
				});
				
				$(".loading").hide();
			}

		}, 'json');
	}
	
</script>