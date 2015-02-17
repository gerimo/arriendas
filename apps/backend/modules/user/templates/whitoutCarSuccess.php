	
<div class="hidden-xs space-40"></div>
<div class="visible-xs space-50"></div>

<div class="row">
	<div class="col-md-offset-2 col-md-8">
		<h1> Usuarios que no registraron auto</h1> 
		<div class="space-30"> </div>
		<div class="col-md-12">
			<div class="col-md-4">
				<input class="datepicker form-control" id="from" placeholder="Desde" type="text" value="<?php echo date("Y-m-d")?>"  >
			</div>
			<div class="col-md-4">
				<input  class="datepicker form-control" id="to" placeholder="Hasta" type="text" value="<?php echo date("Y-m-d")?>"  >
				
			</div>

			<div class="col-md-4">
				<button class="buscar btn btn-block btn-primary" onclick="getUserWhitoutCar()">Buscar</button>
			</div>
		</div>

		<img class="load" src="/images/ajax-loader.gif">
		<div class="space-100"></div>
	</div>

		
	<div class="col-md-12">
		<table  class="display responsive no-wrap" id="userWhitoutCarTable" cellspacing="0" width="100%">
			<thead>
				<tr>
					<th>ID</th>
					<th>Nombre</th>
					<th>Teléfono</th>
					<th>Email</th>
					<th>Dirección</th>
					<th>Llamado</th>
					<th>Comentario</th>
				</tr>
			</thead>

			<tbody>
			</tbody>
		</table>
	</div>
	<div style="display:none">
		<div id="dialog-alert" title="">
			<p></p>
		</div>
		<input id="userId"></input>
	</div>
</div>

<div class="hidden-xs space-10f0"></div>

	

<script>

	var urlComment = "<?php echo url_for('comment', array('userId' => 'userIdPattern', 'managementId' => '2')) ?>";

	$(document).ready(function() {

		getUserWhitoutCar();
	 
		$('#userWhitoutCarTable').DataTable({
			info: false,
			paging: true,
			responsive: true
		});    
	}); 

	$('body').on("click", ".comment", function(e){

		$(this).colorbox({
			height: "75%",
			width: "85%"
		});

		e.preventDefault();
	});

	function getUserWhitoutCar() {

		var from = $("#from").val();
		var to   = $("#to").val();
		$(".load").show();

		$.post("<?php echo url_for('user_whitout_car_get') ?>", {"from": from, "to": to}, function(r){
			if (r.error) {
			   /* $("#dialog-alert p").html("No se encontraron usuarios");
				$("#dialog-alert").attr('title','Error!');
				$("#dialog-alert").dialog({
					buttons: [{
					text: "Aceptar",
					click: function() {
						$('#userWhitoutCarTable').DataTable().rows().remove().draw();
						$( this ).dialog( "close" );

					}
					}]
				});*/
				console.log(r.errorMessage);
				$('#userWhitoutPayTable').DataTable().rows().remove().draw();
				$(".load").hide();
			} else {
				
				$('#userWhitoutCarTable').DataTable().rows().remove().draw();
				$.each(r.data, function(k, v){
					var button = "<a class='btn btn-block btn-primary comment' href='"+urlComment.replace("userIdPattern", v.user_id)+"'>Comentarios</a>";
					var option = optionSelected(v.user_call,v.user_id);
					$('#userWhitoutCarTable').DataTable().row.add([ v.user_id, v.user_fullname, v.user_telephone, v.user_email, v.user_address, option,button ]).draw();
				});
				$(".load").hide();

			}

		}, 'json');
	}

	$('#from').datetimepicker({

		dayOfWeekStart: 1,
		format:'Y-m-d',
		lang:'es',
		timepicker: false,
		maxDate: "<?php echo date('Y-m-d') ?>"
	});

	$('#to').datetimepicker({

		dayOfWeekStart: 1,
		format:'Y-m-d',
		lang:'es',
		timepicker: false,
		maxDate: "<?php echo date('Y-m-d') ?>"
	});

	function optionSelected(option, userId) {

		if (option == 0) {

			var select ="<select class='form-control call' data-user-id='"+userId+"' type='text'>"+
							"<option value='0' selected >No llamado</option>"+
							"<option value='1'>LLamado</option>"+
							"<option value='2'>No contesto</option>"+
						"</select>";
		} else if (option == 1) {

			var select ="<select class='form-control call' data-user-id='"+userId+"' type='text'>"+
							"<option value='0'>No llamado</option>"+
							"<option value='1' selected >LLamado</option>"+
							"<option value='2'>No contesto</option>"+
						"</select>";
		} else {

			var select ="<select class='form-control call' data-user-id='"+userId+"' type='text'>"+
							"<option value='0'>No llamado</option>"+
							"<option value='1'>LLamado</option>"+
							"<option value='2' selected >No contesto</option>"+
						"</select>";
		}

		return select;
	}

	$(document).on("change", ".call", function(){

		var userId = $(this).data("user-id");
		var option = $(".call option:selected").val();

		var parameters = {
			"userId" : userId,
			"option" : option
		};

		$.post("<?php echo url_for('user_change_call') ?>", parameters, function(r){
			if (r.error) {
				console.log(r.errorMessage);
			} else {
				
			}
		}, 'json')
	});

</script>