<div class="hidden-xs space-40"></div>
<div class="visible-xs space-50"></div>

<div class="row">
	<div class="col-md-offset-2 col-md-8">
		<h1> Reservas Problematicas</h1> 
		<div class="space-30"> </div>
		<div class="col-md-offset-4 col-md-4">
			<label>Reservas Problematicas</label>
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
					<th>ID Usuario</th>
					<th>Nombre Usuario</th>
					<th>Teléfono Usuario</th>
					<th>Email Usuario</th>
					<th>Usuario Extranjero</th>
					<th>Usuario Foto Licencia</th>
					<th>Usuario Chequeo Judicial</th>
					<th>ID Reserva</th>
					<th>Fecha Reserva</th>
					<th>Marca Auto</th>
					<th>Modelo Auto</th>
					<th>Tipo Auto</th>
				</tr>
			</thead>

			<tbody>
			</tbody>
		</table>  
	</div>

	<!-- Modal -->
	<div class="modal fade" id="photoModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title" id="myModalLabel">¿Es valida la licencia?</h4>
				</div>
				<div class="modal-body">
					<div id="carDamages">
						<div id="col-md-offset-3 col-md-6">  
							<img id="photo" style="width:100%; height:auto"></img>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button data-dismiss="modal" datatype="button" class="btn btn-danger">No</button>
					<button data-dismiss="modal" type="button" class="btn btn-success" onclick="isValidLicense()">SI</button>
				</div>
			</div>
		</div>
	</div>

	<div style="display:none">
		<input id="userI">
		<div id="dialog-alert" title="">
			<p></p>
		</div>
		<input type="hidden" value="" id="userId">
	</div>
</div>

<div class="hidden-xs space-10f0"></div>

<script>
	var cont = 0;

	$(document).ready(function() {

		findReserves();
	 
		$('#reserveTable').DataTable({
			info: false,
			paging: true,
			responsive: true
		});    
	}); 

	function findReserves() {

		var limit     = $("#limit option:selected").val();    
		$(".load").show();
		var cont = 0;

		$.post("<?php echo url_for('reserve_find_problematic_reserves') ?>", {"limit": limit}, function(r){
			if (r.error) {
				$('#reserveTable').DataTable().rows().remove().draw();
				$(".load").hide();
				console.log(r.errorMessage);
			} else {	
				$('#reserveTable').DataTable().rows().remove().draw();
				$.each(r.data, function(k, v){
					var checkForeign    = optionSelected(v.u_foreign, v.u_id, 3);
					var checkLicense    = photoLincense(v.u_license, v.u_id); 
					var checkJudicial   = optionSelected(v.u_check, v.u_id, 5); 
					$('#reserveTable').DataTable().row.add([v.u_id, v.u_fullname, v.u_telephone, v.u_email, checkForeign, checkLicense, checkJudicial,v.r_id, v.r_date, v.c_brand, v.c_model, v.c_type]).draw();
				});
				$(".load").hide();
			}
		}, 'json');
	}

	function optionSelected(option, userId, type) {

		if (option == 0) {
			var select ="<label class='radio-inline'> <input class='radio' value='1' type='radio' name='inlineRadioOptions"+cont+"'  data-type='"+type+"' data-user-id='"+userId+"' data-cont='"+cont+"'> SI </label>"+ 
						"<label class='radio-inline'> <input class='radio' value='0' type='radio' checked name='inlineRadioOptions"+cont+"' data-type='"+type+"' data-user-id='"+userId+"' data-cont='"+cont+"'> NO</label>";
		} else {
			var select ="<label class='radio-inline'> <input class='radio' value='1' type='radio' checked name='inlineRadioOptions"+cont+"' data-type='"+type+"' data-user-id='"+userId+"' data-cont='"+cont+"'> SI </label>"+ 
						"<label class='radio-inline'> <input class='radio' value='0' type='radio' name='inlineRadioOptions"+cont+"'  data-type='"+type+"' data-user-id='"+userId+"' data-cont='"+cont+"'> NO</label>";
		}

		cont+=1;
		return select;
	}
	
	$('body').on("click", ".radio", function(e){

		var userId = $(this).data("userId");
		var type = $(this).data("type");
		var cont = $(this).data("cont");

		var name = "inlineRadioOptions"+cont;

		var option = $("input[name='"+name+"']:checked").val();

		var parameters = {
			"userId" : userId,
			"option" : option,
			"type"   : type
		};

		$.post("<?php echo url_for('user_edit') ?>", parameters, function(r){
			if (r.error) {
				console.log(r.errorMessage);
			} else {
			}
		}, 'json')
	});

	function photoLincense(license, userId) {

		if (license) {
			
			var select ="<a type='button' class='btn btn-primary photo' data-license='"+license+"' data-user-id='"+userId+"' onClick'poto()'>Licencia</a>"
		   
		} else {
			var select ="<label>no posee licencia</label>"
		}

		return select;
	}

	$('body').on("click", ".photo", function(e){

		var userId  = $(this).data("user-id");
		var license = $(this).data("license");
		var n = license.lastIndexOf("/");
		var res = license.slice(n+1);
		$("#photo").attr("src", "https://www.arriendas.cl/images/licence/"+res);
		$("#photoModal").modal('show');
		$("#userId").val(userId);
	});

	function isValidLicense() {

		var userId = $("#userId").val();

		var parameters = {
			"userId" : userId,
			"option" : 1,
			"type"   : 4
		};

		$.post("<?php echo url_for('user_edit') ?>", parameters, function(r){
			if (r.error) {
				console.log(r.errorMessage);
			} else {
			}
		}, 'json')
	}

	$('#limit').change(function(e) {
		
		findReserves();
	});


</script>