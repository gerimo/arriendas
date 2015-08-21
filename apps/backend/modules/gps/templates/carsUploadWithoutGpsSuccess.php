<div class="hidden-xs space-40"></div>
<div class="visible-xs space-50"></div>

<div class="row">
    <div class="col-md-offset-2 col-md-8">
        <h1> Control de Autos no subidos por cobro de Gps</h1> 
        <div class="space-30"> </div>
        <div class="col-md-offset-4 col-md-4">
            <label>Cantidad de autos a mostrar</label>
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
                    <th>ID</th>
                    <th>Comuna</th>
                    <th>Marca</th>
                    <th>Modelo</th>
                    <th>Año</th>
                    <th>Transmisión</th>
                    <th>ID Usuario</th>
                    <th>Pago validado?</th>
                </tr>
            </thead>

            <tbody>
            </tbody>
        </table>  
    </div>

    <!-- Modal -->
    <div class="modal fade" id="Modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel">¿Está seguro?</h4>
                </div>
                <div class="modal-body">
                    <div class="text-center thumbnail">
                        <p>
                        Una vez validado el pago del gps para el vehículo, éste quedará guardado en la sección de Autos.
                        Y se le notificará al usuario de la creación del vehículo y pago del gps.
                        </p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button data-dismiss="modal" datatype="button" class="btn btn-danger">No</button>
                    <button data-dismiss="modal" type="button" class="btn btn-success btnTmpCar" data-car-tmp-id="" onclick="checkGps(this)">SI</button>
                </div>
            </div>
        </div>
    </div>

    <div style="display:none">
        <div id="dialog-alert" title="">
            <p></p>
        </div>
    </div>
</div>

<div class="hidden-xs space-10f0"></div>

    

<script>

    $(document).ready(function() {

        findCarControl();
     
        $('#reserveTable').DataTable({
            info: false,
            paging: true,
            responsive: true
        });    
    }); 

    function findCarControl() {

        var limit     = $("#limit option:selected").val();    
        $(".load").show();

        $.post("<?php echo url_for('gps_find_all') ?>", {"limit": limit}, function(r){
            if (r.error) {
                $('#reserveTable').DataTable().rows().remove().draw();
                $(".load").hide();
                console.log(r.errorMessage);
            } else {    
                $('#reserveTable').DataTable().rows().remove().draw();
                $('#reserveTable').DataTable().column(0).order( 'desc' );
                var button = '';
                $.each(r.data, function(k, v){
                    if(v.car_id == '1'){
                        button = "<a type='button' class='btn btn-primary create' data-car-tmp-id='"+v.id+"'>Validar Pago de Gps</a>";
                        $(".btnTmpCar").data("car-tmp-id", v.id);
                    } else {
                        button = 'Pago validado. <br> ID auto: '+v.car_id;
                    }
                    $('#reserveTable').DataTable().row.add([v.id, v.comunne, v.brand, v.model, v.year, v.transmission, v.user_id, button]).draw();
                });
                $(".load").hide();
            }
        }, 'json');
    }

    $('body').on("click", ".create", function(e){

        var carTmpId  = $(this).data("car-tmp-id");
        $("#Modal").modal('show');
    });

    $('#limit').change(function(e){
        findCarControl();
    });

    function checkGps(e){
        var carTmpId = $(e).data("car-tmp-id");
        $.post("<?php echo url_for('gps_generate_pay') ?>", {"carTmpId": carTmpId} , function(r){
            if(r.error){
                $("#dialog-alert p").html(r.errorMessage);
                $("#dialog-alert").attr("title", "Problema al generar el pago");
                $("#dialog-alert").dialog({
                    buttons: [{
                        text: "Aceptar",
                        click: function() {
                            $( this ).dialog( "close" );
                        }
                    }]
                });
            } else {
                location.reload();
            }
        }, 'json');
    }


</script>