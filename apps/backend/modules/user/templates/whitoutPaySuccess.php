<div class="hidden-xs space-40"></div>
<div class="visible-xs space-50"></div>

<div class="row">
    <div class="col-md-offset-2 col-md-8">
        <h1>Usuarios que no terminaron pago</h1>
        <div class="col-md-12">
            <div class="col-md-4">
                <input class="datepicker form-control" id="from" placeholder="Desde" type="text" value="<?php echo date("Y-m-d")?>"  >
            </div>
            <div class="col-md-4">
                <input  class="datepicker form-control" id="to" placeholder="Hasta" type="text" value="<?php echo date("Y-m-d")?>"  >
                
            </div>

            <div class="col-md-4">
                <button class="buscar btn btn-block btn-primary" id="buscar" onclick="getUserWhitoutPay()">Buscar</button>
            </div>
        </div>

        <img class="load" src="/images/ajax-loader.gif">

        <div class="space-100"></div>

        <table  class="display responsive no-wrap " id="userWhitoutPayTable" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Teléfono</th>
                    <th>email</th>
                    <th>dirección</th>
                    <th>Fecha inicio</th>
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

    var urlComment = "<?php echo url_for('comment', array('userId' => 'userIdPattern', 'managementId' => '1')) ?>";

    $(document).ready(function() {

/*        getUserWhitoutPay();
*/     
        $('#userWhitoutPayTable').DataTable({
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

    function getUserWhitoutPay() {

        var from = $("#from").val();
        var to   = $("#to").val();
        $(".load").show();

        $.post("<?php echo url_for('user_whitout_pay_get') ?>", {"from": from, "to": to}, function(r){
            console.log(r);

            if (r.error) {
                $('#userWhitoutPayTable').DataTable().rows().remove().draw();
                $(".load").hide();
            } else {
                $('#userWhitoutPayTable').DataTable().rows().remove().draw();

                $.each(r.data, function(k, v) {
                    var button = "<a class='btn btn-block btn-primary comment' href='"+urlComment.replace("userIdPattern", v.user_id)+"'>Comentarios</a>";
                    $('#userWhitoutPayTable').DataTable().row.add([ v.user_id, v.user_fullname, v.user_telephone, v.user_email, v.user_address, v.reserva_fecha_inicio, button ]).draw();
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