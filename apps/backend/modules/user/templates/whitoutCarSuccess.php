    
<div class="hidden-xs space-100"></div>
<div class="visible-xs space-50"></div>

<div class="row">
    <div class="col-md-offset-2 col-md-8"> 
        <div class="col-md-12">
            <div class="col-md-4">
                <input class="datepicker form-control text-center" id="from" placeholder="from" type="text" value="<?php echo date("Y-m-d")?>"  >
            </div>
            <div class="col-md-4">
                <input  class="datepicker form-control text-center" id="to" placeholder="to" type="text" value="<?php echo date("Y-m-d")?>"  >
                
            </div>

            <div class="col-md-4">
                <button class="buscar btn btn-block btn-primary" onclick="getUserWhitoutCar()">Buscar</button>
            </div>
        </div>

        <img class="load" src="/images/ajax-loader.gif">

        <div class="space-100"></div>

        <table  class="display responsive no-wrap " id="userWhitoutCarTable" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Teléfono</th>
                    <th>email</th>
                    <th>dirección</th>
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

        $.post("<?php echo url_for('user_without_car_get') ?>", {"from": from, "to": to}, function(r){

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
                $('#userWhitoutPayTable').DataTable().rows().remove().draw();
                $(".load").hide();
            } else {
                
                $('#userWhitoutCarTable').DataTable().rows().remove().draw();
                $.each(r.data, function(k, v){
                    var button = "<a class='btn btn-block btn-primary comment' href='"+urlComment.replace("userIdPattern", v.user_id)+"'>Comentarios</a>";
                    $('#userWhitoutCarTable').DataTable().row.add([ v.user_id, v.user_fullname, v.user_telephone, v.user_email, v.user_address, button ]).draw();
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

</script>