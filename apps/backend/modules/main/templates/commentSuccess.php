
<div class="row" style="margin: 0">
    <div class="col-md-offset-2 col-md-8">

        <h1>Comentarios</h1>

        <div class="space-20"></div>

        <textarea class="form-control" id="comentario" placeholder="Escribe un comentario aquí..."></textarea>

        <div class="space-20"></div>

        <div style="padding:0" class ="col-md-4 pull-right">
        	<button class="btn btn-block btn-primary"  onclick="addComment()">Guardar</button>
        </div>

        <div class ="space-70"></div>

        <div id="comments">
            <?php foreach($UserManagements as $UserManagement): ?>
                <div id="<?php echo $UserManagement->id?>">
                   	<label><?php echo date("Y-m-d H:i", strtotime($UserManagement->created_at))?> <i class="fa fa-remove" data-comment-id="<?php echo $UserManagement->id?>"></i></label>
                   	<p style="text-align:justify; margin: 0 0 30px 0"><?php echo $UserManagement->comment ?></p>
                    <hr>
                </div>
            <?php endforeach ?>
    	</div>
	</div>

	<div class="hidden-xs space-100"></div>

	<!-- Dialogs -->
	<div style="display:none">
        <input id="idUser" type="hidden" value="<?php echo $User ?>">
        <input id="idManagement" type="hidden" value="<?php echo $Management ?>">

	    <div id="dialog-alert" title="">
	        <p></p>
	    </div>
	</div>
</div>

<script>

    function addComment() {

        var userId          = $("#idUser").val();
        var managementId    = $("#idManagement").val();
        var comment         = $("#comentario").val();

        var parameters = {
            "userId": userId,
            "managementId": managementId,
            "comment": comment
        };

        $.post("<?php echo url_for('commentDo') ?>", parameters , function(r){

            if (r.error) {
                alert(r.errorMessage);
            } else {
                html = "<label><?php echo date('Y-m-d H:i') ?> <i class='fa fa-remove' data-comment-id='<?php echo $UserManagement->id?>'></i></label>"+
                        "<p style='text-align:justify; margin: 0 0 30px 0'>"+comment+"</p>";

                $( "#comments" ).prepend(html);

                $("#comentario").val("");
            }

        }, 'json');
    }

    $(document).on("click", ".fa-remove", function() {

        var commentId = $(this).data("comment-id");
        console.log(commentId);
        var parameters = {
            "commentId" : commentId
        };

        $.post("<?php echo url_for('commentDelete') ?>", parameters, function(r){
            if (r.error) {
                console.log(r.errorMessage);
            } else {

                $("#"+r.comentId).remove();
            }

        }, 'json')
    });

</script>