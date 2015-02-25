<div class="hidden-xs space-40"></div>
<div class="visible-xs space-50"></div>

<div class="row">
    <h1 class="text-center">Administrador    de notificaciones</h1> 
    <div class="space-40"></div>
    <div class="col-md-offset-4 col-md-4">   
        <select class="form-control" id="action" name="action" type="text">
                <option selected value="">Selecciona una acción</option>
            <?php foreach ($Actions as $Action): ?>
                <option value="<?php echo $Action->id?>"><?php echo $Action->name?></option>
            <?php endforeach; ?>
        </select> 
    </div> 
	<div class="col-md-12" >
        <div class="space-100"></div>
		<table  class="display responsive no-wrap" id="notificationTable" cellspacing="0" width="100%">
			<thead>
				<tr>
                    <th>ID</th>
                    <th>Titulo</th>
                    <th>Mensaje</th>
                    <th>Tipo</th>
    				<th>¿Activo?</th>
                    <th>Editar</th>
				</tr>
			</thead>   
            <tbody>
			</tbody>
		</table>  
	</div>

    <!-- Modal -->
    <div class="modal fade" id="notificationModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Nueva notificación</h4>
                </div>
                <div class="modal-body">
                    <div id="carDamages">
                        <label for="recipient-name" class="control-label">Titulo:</label>
                        <input type="text" class="form-control" id="title" placeholder="Titulo">
                         <div class="space-30"></div>
                        <label for="recipient-name" class="control-label">Descripción:</label>
                        <div id="message" placeholder="Escriba Descripicion aquí.."></div>
                        <textarea type="text" class="form-control" id="description" placeholder="Escriba Descripicion aquí.." rows="5"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button data-dismiss="modal" datatype="button" class="btn btn-danger">Cerrar</button>
                    <button data-dismiss="modal" type="button" class="btn btn-success" onclick="editNotification()">Crear</button>
                </div>
            </div>
        </div>
    </div>

	<div style="display:none">
        <input id="notificationId">
        <input id="optionSelected">
		<div id="dialog-alert" title="">
			<p></p>
		</div>
	</div>
</div>      
<div class="hidden-xs space-10f0"></div>

<script>

	$(document).ready(function() {
	 


        $('#notificationTable').hide();

        $("#message").summernote({
            height: 200,
            minHeight: 100
        });
	}); 

	function findNotificacion() {

        var actionId        = $("#action option:selected").val();
        $("#optionSelected").val(actionId);
        var parameters = {
            "actionId"      : actionId
        }

		$.post("<?php echo url_for('notification_find_notification') ?>", parameters, function(r){
            if (r.error) {
                $('#notificationTable').DataTable().rows().remove().draw();
                console.log(r.errorMessage);
            } else { 

                $('#notificationTable').show();
                $('#notificationTable').DataTable().rows().remove().draw();
                $('#notificationTable').DataTable().column(0).order( 'desc' );
                $.each(r.data, function(k, v){

                    var isActive    = optionSelected(v.n_is_active, v.n_id, 1);
                    var button = "<a class='btn btn-block btn-primary editar' data-notification-id='"+v.n_id+"' data-title='"+v.n_title+"' data-message='"+v.n_message+"' data-name='"+v.nt_name+"' data-nt-id='"+v.nt_id+"' >Editar</a>";
                    $('#notificationTable').DataTable().row.add([ v.n_id, v.n_title, v.n_message, v.nt_name, isActive, button]).draw();
                });
            }
        }, 'json');
	}

    function editNotification(notificationId, option) {

        if (!notificationId) {
            var notificationId = $("#notificationId").val();
        }

        var title            = $("#title").val();
        var description      = $("#description").val();
        var description2     = $("#message").code();
        var message;

        if (description) {
            message = description;
        } else if(description2) {
            message = description2;
        }

        var parameters = {
            "notificationId"    : notificationId,
            "title"             : title,
            "message"           : message,
            "option"            : option
        }


        $.post("<?php echo url_for('notification_edit_notification') ?>", parameters, function(r){
            if (r.error) {
                console.log(r.errorMessage);
            } else {
                findNotificacion($("#optionSelected").val());
            }
        }, 'json');
    }

    function optionSelected(option, notificationId) {

        if (option == 0) {
            var select ="<label class='radio-inline'> <input class='radio' value='1' type='radio' name='radio"+notificationId+"'  data-notification-id='"+notificationId+"'> SI </label>"+ 
                        "<label class='radio-inline'> <input class='radio' value='0' type='radio' checked name='radio"+notificationId+"' data-notification-id='"+notificationId+"'> NO</label>";
        } else {
            var select ="<label class='radio-inline'> <input class='radio' value='1' type='radio' checked name='radio"+notificationId+"' data-notification-id='"+notificationId+"'> SI </label>"+ 
                        "<label class='radio-inline'> <input class='radio' value='0' type='radio' name='radio"+notificationId+"'  data-notification-id='"+notificationId+"'> NO</label>";
        }

        return select;
    }
    
    $('body').on("click", ".radio", function(e){

        var notificationId   = $(this).data("notification-id");
        $("#message").code("");
        $("#description").val("");
        var option     = $("input[name='radio"+notificationId+"']:checked").val();

        editNotification(notificationId, option);
    });

    $('body').on("click", ".editar", function(e){
        $("#myModalLabel").html($(this).data("name"));
        $("#notificationId").val($(this).data("notification-id"));
        $("#title").val($(this).data("title"));
        
        $('#notificationModal').modal('show');
        var ntId = $(this).data("nt-id");
        if( ntId != 2) {
            $("#message").summernote({
                height: 200,
                minHeight: 100
            });
            $("#description").hide();
            $("#message").code($(this).data("message"));
        }else {
            $("#message").destroy();
            $("#description").show();
            $("#description").val($(this).data("message"));
        }

    });

    $('#action').change(function(e){
        findNotificacion();
    });  
</script>