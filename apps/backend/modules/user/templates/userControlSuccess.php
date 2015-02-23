	
<div class="hidden-xs space-40"></div>
<div class="visible-xs space-50"></div>

<div class="row">
	<div class="col-md-offset-2 col-md-8">
		<h1>Control Usuario</h1> 
		<div class="space-30"> </div>
		<div class="col-md-offset-4 col-md-4">
            <label>Cantidad de usuarios a mostrar</label>
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
		<table  class="display responsive no-wrap" id="userControlTable" cellspacing="0" width="100%">
			<thead>
				<tr>
					<th>ID</th>
					<th>Nombre</th>
					<th>Teléfono</th>
					<th>Email</th>
					<th>Dirección</th>
                    <th>bloqueado</th>
                    <th>Antecedentes Validado</th>
                    <th>Extranjero</th>
                    <th>Facebook</th>
                    <th>Licencia</th>
					<th>Comentario</th>
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

	var urlComment = "<?php echo url_for('comment', array('userId' => 'userIdPattern', 'managementId' => '3')) ?>";

	$(document).ready(function() {

		findUserControl();
	 
		$('#userControlTable').DataTable({
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

	function findUserControl() {

		var limit     = $("#limit option:selected").val();    
		$(".load").show();

		$.post("<?php echo url_for('user_find_all') ?>", {"limit": limit}, function(r){
			if (r.error) {
				$('#userControlTable').DataTable().rows().remove().draw();
				$(".load").hide();
				console.log(r.errorMessage);
			} else {	
				$('#userControlTable').DataTable().rows().remove().draw();
                $('#userControlTable').DataTable().column(0).order( 'desc' );
				$.each(r.data, function(k, v){

                    var checkBloqued    = optionSelected(v.user_blocked, v.user_id, 1);
                    var checkValid      = optionSelected(v.user_valid, v.user_id, 2);
                    var checkForeign    = optionSelected(v.user_foreign, v.user_id, 3);
                    var facebook        = facebookUrl(v.user_facebook);
                    var photo           = photoLincense(v.user_license, v.user_id); 
					var button = "<a class='btn btn-block btn-primary comment' href='"+urlComment.replace("userIdPattern", v.user_id)+"'>Comentarios</a>";
					$('#userControlTable').DataTable().row.add([ v.user_id, v.user_fullname, v.user_telephone, v.user_email, v.user_address,checkBloqued, checkValid, checkForeign, facebook, photo, button ]).draw();
				});
				$(".load").hide();
			}
		}, 'json');
	}

    function optionSelected(option, userId, type) {

        var name = userId+type;
        if (option == 0) {
            var select ="<label class='radio-inline'> <input class='radio' value='1' type='radio' name='inlineRadioOptions"+name+"'  data-type='"+type+"' data-user-id='"+userId+"'> SI </label>"+ 
                        "<label class='radio-inline'> <input class='radio' value='0' type='radio' checked name='inlineRadioOptions"+name+"' data-type='"+type+"' data-user-id='"+userId+"'> NO</label>";
        } else {
            var select ="<label class='radio-inline'> <input class='radio' value='1' type='radio' checked name='inlineRadioOptions"+name+"' data-type='"+type+"' data-user-id='"+userId+"'> SI </label>"+ 
                        "<label class='radio-inline'> <input class='radio' value='0' type='radio' name='inlineRadioOptions"+name+"'  data-type='"+type+"' data-user-id='"+userId+"'> NO</label>";
        }

        return select;
    }

    function facebookUrl(facebookId) {

        if (facebookId) {
            var select ="<a href='http://www.facebook.com/"+facebookId+"' target='_blank'>Facebook</a>"
        } else {
            var select ="<label>no posee facebook</label>"
        }

        return select;
    }

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
    
    $('body').on("click", ".radio", function(e){

        var userId = $(this).data("userId");
        var type = $(this).data("type");

        var name = "inlineRadioOptions"+userId+type;

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

    $('#limit').change(function(e){
        findUserControl();
    });


    


</script>