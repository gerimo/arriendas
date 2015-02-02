    
<div class="hidden-xs space-100"></div>
<div class="visible-xs space-50"></div>

<div class="row">
    <div class="col-md-offset-2 col-md-8">
        <img class="loading" src="/images/ajax-loader.gif">   
        <div style="padding:0px" class="col-md-offset-9 col-md-3">
            <label class="pull-right">Fecha:</label>
            <input style="margin-left: 0.5em;" class="datepicker form-control text-center" id="date" placeholder="Fecha" type="text" value="<?php echo date("Y-m-d", strtotime("-34 day"))?>" onblur="GetUserWhitoutPay()" >
            <div class="space-30"></div>
        </div>
        <table  class="display responsive no-wrap " id="userWhitoutPay" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th>ID Usuario</th>
                    <th>Nombre Usuario</th>
                    <th>Telefono Usuario</th>
                    <th>Id Reserva</th>
                    <th>Fecha Reserva</th>
                    <th>Comentario Reserva</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <th>0000</th>
                    <th>User</th>
                    <th>0000</th>
                    <th>0000</th>
                    <th>0000-00-00</th>
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

<div class="hidden-xs space-100"></div>

    

<script>

    $(document).ready(function() {

        GetUserWhitoutPay();
        
        $('#userWhitoutPay').DataTable({
            info: false,
            paging: false,
            responsive: true
        });    

        $(".loading").hide();
    }); 

    function GetUserWhitoutPay() {

        var fecha = $("#date").val();
        $(".loading").show();

        $.post("<?php echo url_for('user_without_pay_get') ?>", {"fecha": fecha}, function(r){

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
                
                $('#userWhitoutPay').DataTable().rows().remove().draw();
                $.each(r.data, function(k, v){
                    $('#userWhitoutPay').DataTable().row.add([ v.user_id, v.user_fullname, v.user_telephone, v.reserve_id, v.reserve_at, v.user_comment,  ]).draw();
                });
                    $(".loading").hide();

            }

        }, 'json');
    }

    $('#date').datetimepicker({

        dayOfWeekStart: 1,
        format:'Y-m-d',
        lang:'es',
        timepicker: false,
        maxDate: "<?php echo date('Y-m-d') ?>"
    });

    $('#userWhitoutPay tbody').on( 'click', 'td', function () {

        var dato = $('#userWhitoutPay').DataTable().cell( this ).index().row;
        console.log(this);
        var row  = $('#userWhitoutPay').DataTable().row(dato).data();

        if($('#userWhitoutPay').DataTable().cell( this ).index().column == 5) {

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
                GetUserWhitoutPay(); 
            }

        }, 'json');
    }

    
   
</script>