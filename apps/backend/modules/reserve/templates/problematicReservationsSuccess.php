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
					<th>ID Reserva</th>
					<th>Fecha Inicio Reserva</th>
					<th>Fecha devolución Reserva</th>
					<th>ID Usuario Pagador</th>
					<th>Nombre Usuario Pagador</th>
					<th>Teléfono Usuario Pagador</th>
					<th>Licencia Usuario Pagador</th>
					<th>Chequeo Judicial</th>
					<th>ID Usuario Beneficiario</th>
					<th>ID Car</th>
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
					<div id="photoL">
						<label>Foto Licencia</label>
						<input name="photo" type="file" class="file-loading" id="photoLicense" accept="image/*" data-id="10">
					</div>
				</div>
				<div class="modal-footer">
					<button data-dismiss="modal" datatype="button" class="btn btn-danger botones">No</button>
					<button data-dismiss="modal" type="button" class="btn btn-success botones" onclick="isValidLicense()">SI</button>
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

		$("#photoLicense").fileinput({

			allowedFileExtensions: ["jpg", "gif", "png", "bmp","jpeg"],
			uploadUrl: '<?php echo url_for("user_upload_photo")?>',
			dropZoneEnabled: false,
			showRemove: false,  
			elErrorContainer: false,
			showPreview: false,
			uploadExtraData:function() { return {userId: $("#userId").val()}; },
			/*showCaption: false,*/
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
					var checkLicense    = photoLicense(v.up_license, v.up_id); 
					var chequeo         = optionSelected(v.up_check, v.up_id, 5);
					$('#reserveTable').DataTable().row.add([v.r_id, v.r_date, v.r_date_end, v.up_id, v.up_fullname, v.up_telephone, checkLicense, chequeo, v.uo_id, v.c_id]).draw();
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

	function photoLicense(license, userId) {

		if (license) {
			
			var select ="<a type='button' class='btn btn-primary photo' data-license='"+license+"' data-user-id='"+userId+"'>Licencia</a>";
		   
		} else {

			var select ="<a type='button' class='btn btn-primary photo1' data-user-id='"+userId+"'>Subir Licencia</a>";
		}

		return select;
	}

	$('body').on("click", ".photo", function(e){
		$("#myModalLabel").html("¿Es valida la licencia?");
		$("#photoL").hide();
		$("#carDamages").show();
		$(".modal-footer").show();
		var userId  = $(this).data("user-id");
		var license = $(this).data("license");
		if(license.search("licence") < 5){
			$("#photo").attr("src", license);
		} else {
			var n = license.lastIndexOf("/");
			var res = license.slice(n+1);
			$("#photo").attr("src", "https://www.arriendas.cl/images/licence/"+res);
		}
		$("#photoModal").modal('show');
		$("#userId").val(userId);
	});

	$('body').on("click", ".photo1", function(e){
		$("#myModalLabel").html("Subir Foto Licencia");
		$(".modal-footer").hide();
		$("#carDamages").hide();
		$("#photoL").show();
		var userId  = $(this).data("user-id");
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

	$('#photoLicense').on('filebatchuploadsuccess', function(event, data, previewId, index) {
        findReserves();	
        $("#photoModal").modal('hide');
    });


</script>