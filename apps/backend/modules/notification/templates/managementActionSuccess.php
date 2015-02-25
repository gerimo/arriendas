<div class="hidden-xs space-40"></div>
<div class="visible-xs space-50"></div>

<div class="row">
	<div class="col-md-12">
        <h1 class="text-center">Manejadora de acciones</h1> 
        <div class="space-100"></div>
        <div class="col-md-2 pull-right "><a class='btn btn-block btn-primary action'>Crear</a></div>
        <div class="space-100"></div>
		<table  class="display responsive no-wrap" id="managementActionTable" cellspacing="0" width="100%">
			<thead>
				<tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Descripción</th>
                    <th>Editar</th>
    				<th>Desactivar</th>
				</tr>
			</thead>   
            <tbody>
                <?php foreach ($Actions as $Action):?>
                    <tr>
                        <th><?php echo $Action->id ?></th>
                        <th><?php echo $Action->name?></th>
                        <th><?php echo $Action->description?></th>
                        <th><a class='btn btn-block btn-primary action' data-action-id='<?php echo $Action->id ?>' data-name='<?php echo $Action->name ?>' data-description='<?php echo $Action->description ?>' >Editar</a></th>
                        <th>   
                            <form>
                            <label class='radio-inline'> <input class='radio' value='1' type='radio' name='radio<?php echo $Action->id?>' data-action-id='<?php echo $Action->id?>' <?php if($Action->is_active) echo 'checked'?>> SI </label>
                            <label class='radio-inline'> <input class='radio' value='0' type='radio' name='radio<?php echo $Action->id?>' data-action-id='<?php echo $Action->id?>' <?php if(!$Action->is_active) echo 'checked'?>> NO</label> 
                            </form>
                        </th>
                    </tr>
                <?php endforeach ?>
			</tbody>
		</table>  
	</div>

    <!-- Modal -->
    <div class="modal fade" id="actionModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Acción de notificación</h4>
                </div>
                <div class="modal-body">
                    <div id="carDamages">
                        <label for="recipient-name" class="control-label">Titulo:</label>
                        <input type="text" class="form-control" id="name" placeholder="Titulo">
                         <div class="space-30"></div>
                        <label for="recipient-name" class="control-label">Descripción:</label>
                        <textarea type="text" class="form-control" id="description" placeholder="Escriba Descripicion aquí.." rows="5"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button data-dismiss="modal" datatype="button" class="btn btn-danger">Cerrar</button>
                    <button data-dismiss="modal" type="button" class="btn btn-success" onclick="findNotificacionAction()">Crear</button>
                </div>
            </div>
        </div>
    </div>

	<div style="display:none">
        <input id="actionId">
		<div id="dialog-alert" title="">
			<p></p>
		</div>
	</div>
</div>

<div class="hidden-xs space-10f0"></div>

<script>

	$(document).ready(function() {
	 
		$('#managementActionTable').DataTable({
			info: false,
			paging: true,
			responsive: true
		});    
	}); 

	function findNotificacionAction(actionId, option) {

        if (!actionId) {
            var actionId = $("#actionId").val();
        }

        var name        = $("#name").val();
        var description = $("#description").val();

        var parameters = {
            "actionId"      : actionId,
            "name"          : name,
            "description"   : description,
            "option"        : option
        }

        console.log(parameters);

		$.post("<?php echo url_for('notification_management_action_crud') ?>", parameters, function(r){
            if (r.error) {
                console.log(r.errorMessage);
            } else {    
                location.reload();
            }
        }, 'json');
	}
    
    $('body').on("click", ".radio", function(e){

        var actionId   = $(this).data("action-id");

        var option     = $("input[name='radio"+actionId+"']:checked").val();

        findNotificacionAction(actionId, option);
    });

    $('body').on("click", ".action", function(e){

        $("#actionId").val($(this).data("action-id"));
        $("#name").val($(this).data("name"));
        $("#description").val($(this).data("description"));
        $('#actionModal').modal('show') 
    }); 
</script>