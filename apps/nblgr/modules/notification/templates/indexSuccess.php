<link href="/css/newDesign/newView.css" rel="stylesheet" type="text/css">

<div class="hidden-xs space-40"></div>
<div class="visible-xs space-50"></div>

<div class="row">
	<div class="col-md-12">
        <h1 class="text-center">Notificaciones</h1> 
        <div class="space-100"></div>
        <div class="col-sm-4 col-md-4">   
            <label>Tipo de Usuario</label>
            <select class="form-control" id="userType" type="text">
                    <option value="">--</option>
                <?php
                    foreach ($UserTypes as $UserType):
                ?>
                    <option value="<?php echo $UserType->id?>"  <?php if ($userTypeId == $UserType->id ) echo "selected" ?>><?php echo $UserType->name?></option>
                <?php
                    endforeach;
                ?>
            </select>
        </div>

        <div class="col-md-2 pull-right "><a class='btn btn-block btn-primary action' data-name="1">Crear Acción  </a></div>
         <div class="col-md-2 pull-right "><a class='btn btn-block btn-primary action' data-name="2">Crear Tipo de notificacion</a></div>
        <div class="space-100"></div>
		<table  class="display responsive no-wrap" id="table" cellspacing="0" width="100%">
			<thead>
				<tr>
                    <th></th>
                    <?php foreach ($NTS as $NT):?>
                        <th class="text-center"><?php echo $NT->name?></th>
                    <?php endforeach ?>
				</tr>
			</thead>   
            <tbody>
            <tr>
            </tr>
			</tbody>
		</table> 
        <div class="hidden-xs space-100"></div>
        <div class="visible-xs space-100"></div>
	</div>
</div>

<!-- Modal -->
<div class="modal fade" id="notificationModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Notificación</h4>
            </div>
            <div class="modal-body">
                <div id="carDamages">
                    <label for="recipient-name" class="control-label">Titulo:</label>
                    <input type="text" class="form-control" id="title" placeholder="Titulo">
                    <div class="space-30"></div>
                    <div id="franco">
                        <label class="type">Tipo de Usuario</label>
                        <select class="form-control" id="type" type="text">
                            <option value="">--</option>
                            <?php foreach ($UserTypes as $UserType): ?>
                                <option value="<?php echo $UserType->id?>"><?php echo $UserType->name?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                     <div class="space-30"></div>
                    <label for="recipient-name" class="control-label">Descripción:</label>
                    <textarea type="text" class="form-control" id="message" placeholder="Escriba Descripicion aquí.." rows="5"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button data-dismiss="modal" datatype="button" class="btn btn-danger">Cerrar</button>
                <button data-dismiss="modal" type="button" class="btn1 btn btn-success" onclick="createNotificationOrAction()">Crear</button>
                <button data-dismiss="modal" type="button" class="btn2 btn btn-success" onclick="editNotificacion()">Editar</button>
            </div>
        </div>
    </div>
</div>

<div style="display:none">
    <input id="notificationId">
    <input id="option">
</div>

<script>

	$(document).ready(function() {
        
        $("#table").hide();
        findNotification($("#userType option:selected").val());
	}); 

    $('body').on("click", ".action", function(e){

        $(".btn2").hide();
        $(".btn1").show();
        $("#title").val("");
        $("#message").val("");
        $("#option").val($(this).data("name"));

        if($(this).data("name") == 1){
            $("#myModalLabel").html("Nueva Acción");
            $("#franco").show();
        } else {
            $("#myModalLabel").html("Nueva tipo de Notificación");
            $("#franco").hide();
        }
        $('#notificationModal').modal('show') 
    }); 

    $("#userType").change(function() {

        var userTypeId   = $("#userType option:selected").val();
        
        findNotification(userTypeId);
    });

    function findNotification(userTypeId){

        var userTypeId   = userTypeId;
        

        var parameters = {

            "userTypeId" : userTypeId
        };

        $.post("<?php echo url_for('notification_find') ?>", parameters, function(r){
                
            if (r.error) {
                console.log(r.errorMessage);
            } else {         

                $("#table tbody th").remove();
                $.each(r.data, function(k, v){ 
                    if(v.n_condition == 1) {
                        $("#table th:last").after('<th class="area" data-id="'+v.n_id+'" data-title="'+v.n_title+'" data-name="'+v.n_name+'" >'+caracteres(v.n_name)+'</th>');
                    } else {
                        $("#table tr:last").before('<th class="text-center">'+v.n_name+'</th>');
                        $("#table").append('<tr></tr>');
                    }
                }); 
                $("#table").show();
            }
        }, 'json');
    }

    function caracteres(texto){
        var caracteres = 100;
        var text = texto;
        if(texto.length > caracteres){

            console.log(texto.length);
            text = texto.substr(0, caracteres);
            text = text+"...";
        }

        return text;
    }

    function createNotificationOrAction() {

        var description     = $("#message").val();
        var name            = $("#title").val();
        var option          = $("#option").val();
        var userTypeId      = $("#type option:selected").val();


        var parameters = {
            "name"              : name,
            "description"       : description,
            "option"            : option,
            "userTypeId"        : userTypeId
        }

        $.post("<?php echo url_for('notification_create') ?>", parameters, function(r){
            if (r.error) {
                console.log(r.errorMessage);
            } else {         
                location.reload(); 
            }
        }, 'json');
    }

    $('body').on("click", ".area", function(e) {

        $("#notificationId").val($(this).data("id"));
        $("#title").val($(this).data("title"));
        $("#message").val($(this).data("name"));
        $("#franco").hide();
        $(".btn1").hide();
        $(".btn2").show();
        $('#notificationModal').modal('show') 
    });

    function editNotificacion() {

        var notificationId  = $("#notificationId").val();
        var message         = $("#message").val();
        var title           = $("#title").val();

        var parameters = {
            "title"             : title,
            "message"           : message,
            "notificationId"    : notificationId
        }

        $.post("<?php echo url_for('notification_edit') ?>", parameters, function(r){
            if (r.error) {
                console.log(r.errorMessage);
            } else {         
                location.reload(); 
            }
        }, 'json');
    }

</script>   