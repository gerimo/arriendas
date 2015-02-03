    
<div class="hidden-xs space-100"></div>
<div class="visible-xs space-50"></div>

<div class="row">
    <div class="col-md-offset-2 col-md-8"> 
        <div class="col-md-12">
            <div class="col-md-4">
                <input class="datepicker form-control text-center" id="from" placeholder="from" type="text" value="<?php echo date("Y-m-d", strtotime("-35 day"))?>"  >
            </div>
            <div class="col-md-4">
                <input  class="datepicker form-control text-center" id="to" placeholder="to" type="text" value="<?php echo date("Y-m-d", strtotime("-34 day"))?>"  >
                
            </div>

            <div class="col-md-4">
                <button class="buscar btn btn-block btn-primary" onclick="getUserWhitoutCar()">Buscar</button>
            </div>
        </div>

        <img class="loading" src="/images/ajax-loader.gif">

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
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Teléfono</th>
                    <th>email</th>
                    <th>dirección</th>
                    <th>Comentario</th>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="modal fade bs-example-modal-sm" id="myModal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="input-group">
                <input type="text" class="form-control" id="comment" placeholder="Comentario...">
                <span class="input-group-btn">
                    <button class="btn btn-primary" type="button" onclick="editComment()">Update</button>
                </span>
            </div>
        </div>
      </div>
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

    $(document).ready(function() {

        getUserWhitoutCar();
     
        $('#userWhitoutCarTable').DataTable({
            info: false,
            paging: true,
            responsive: true
        });    
    }); 

    function getUserWhitoutCar() {

        var from = $("#from").val();
        var to   = $("#to").val();
        $(".loading").show();

        $.post("<?php echo url_for('user_without_car_get') ?>", {"from": from, "to": to}, function(r){

            if (r.error) {
                $("#dialog-alert p").html("No se encontraron usuarios");
                $("#dialog-alert").attr('title','Error!');
                $("#dialog-alert").dialog({
                    buttons: [{
                    text: "Aceptar",
                    click: function() {
                        $('#userWhitoutPay').DataTable().rows().remove().draw();
                        $( this ).dialog( "close" );

                    }
                    }]
                });
            } else {
                
                $('#userWhitoutCarTable').DataTable().rows().remove().draw();
                $.each(r.data, function(k, v){
                    $('#userWhitoutCarTable').DataTable().row.add([ v.user_id, v.user_fullname, v.user_telephone, v.user_email, v.user_address, v.user_commnet ]).draw();
                });
                    $(".loading").hide();

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

    $('#userWhitoutCarTable tbody').on( 'click', 'td', function () {

        var dato = $('#userWhitoutCarTable').DataTable().cell( this ).index().row;
        console.log(this);
        var row  = $('#userWhitoutCarTable').DataTable().row(dato).data();

        if($('#userWhitoutCarTable').DataTable().cell( this ).index().column == 5) {

            $("#userId").val(row[0]);
            $("#myModal").modal('show');


        }
    } );

    function editComment() {

        var userId  = $("#userId").val();
        var comment = $("#comment").val();

        console.log(userId);
        console.log(comment);
        $.post("<?php echo url_for('user_without_pay_comment') ?>", {"userId": userId, "comment": comment}, function(r){

            if (r.error) {
                console.log(r.errorMessage);
            } else {

                $("#myModal").modal('hide');
                GetUserWhitoutCar(); 
            }

        }, 'json');
    }

    
   
</script>