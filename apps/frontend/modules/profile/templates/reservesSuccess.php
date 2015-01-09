<link href="/css/newDesign/reserves.css" rel="stylesheet" type="text/css">

<div class="hidden-xs space-100"></div>
<div class="visible-xs space-50"></div>

<div class="row">
    <div class="col-md-offset-2 col-md-8">

        <div class="BCW">

            <h1>Reservas (<?php echo count($Reserves) ?>)</h1>

            <div class="panel-group" id="reserves" role="tablist" aria-multiselectable="true">
                <?php foreach ($Reserves as $key => $Reserve ): ?>
                    <div class="panel panel-default">
                        <div class="panel-heading" role="tab" id="reserve-<?php echo $Reserve->getId() ?>">
                            <div class="row">
                                <div class="col-md-1">ID</div>
                                <div class="col-md-3">Desde</div>
                                <div class="col-md-3">Hasta</div>
                            </div>
                            <div class="row">
                                <div class="col-md-1"><?php echo $Reserve->getId() ?></div>
                                <div class="col-md-3"><?php echo date("d-m-Y H:i", strtotime($Reserve->getDate())) ?></div>
                                <div class="col-md-3"><?php echo date("d-m-Y H:i", strtotime("+".$Reserve->getDuration()." hour", strtotime($Reserve->getDate()))) ?></div>
                                <div class="col-md-3"><a href="#">Descargar contratos</a></div>
                                <div class="col-md-2"><button class="extend btn-a-primary" data-reserve-id="<?php echo $Reserve->getId() ?>" data-reserve-to="<?php echo date("d-m-Y H:i", strtotime($Reserve->getFechaTermino2())) ?>">Extender</button></div>
                            </div>
                            <a class="btn-block text-center" data-toggle="collapse" data-parent="#reserves" href="#collapse-<?php echo $Reserve->getId() ?>" aria-expanded="true" aria-controls="collapse-<?php echo $Reserve->getId() ?>">
                                <i class="fa fa-chevron-down"></i>
                            </a>
                        </div>
                        <div id="collapse-<?php echo $Reserve->getId() ?>" data-reserve-id="<?php echo $Reserve->getId() ?>" class="panel-collapse collapse" role="tabpanel" aria-labelledby="reserve-<?php echo $Reserve->getId() ?>">
                            <div class="panel-body">

                                <div class="row">
                                    <div class="col-md-3"><strong>Auto</strong></div>
                                    <div class="col-md-3"><strong>Dirección</strong></div>
                                    <div class="col-md-3"><strong>Dueño</strong></div>
                                </div>

                                <?php foreach ($Opportunities[$Reserve->getId()] as $O): ?>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <?php echo $O->getCar()->getModel()->getBrand()->getName() ." ". $O->getCar()->getModel()->getName() ?>
                                        </div>
                                        <div class="col-md-3">
                                            <?php echo $O->getCar()->getAddress() .", ". $O->getCar()->getCommune() ?>
                                        </div>
                                        <div class="col-md-3">
                                            <?php echo $O->getCar()->getUser()->getFirstname() ." ". $O->getCar()->getUser()->getLastname() ?>
                                        </div>
                                        <div class="col-md-3 text-center">
                                            <?php if ($O->getTransaction()->getSelected()): ?>
                                                <i class="fa fa-check"></i>
                                            <?php else: ?>
                                                <button class="change btn-a-action" data-reserve-id="<?php echo $O->getId() ?>">Cambiar</button>
                                            <?php endif ?>
                                        </div>
                                    </div>
                                <?php endforeach ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach ?>
            </div>
        </div>
    </div>
</div>

<div class="hidden-xs space-100"></div>

<!-- Modal extensión reserva -->
<div class="modal fade" id="reserveExtendModal" tabindex="-1" role="dialog" aria-labelledby="reserveExtendModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="reserveExtendModalLabel">Extender reserva</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">                        
                        <div class="form-group">
                            <label for="extend-from">Desde</label>
                            <input disabled class="form-control" id="extend-from" placeholder="Desde" type="text">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="extend-to">Hasta</label>
                            <input class="datetimepicker form-control" id="extend-to" placeholder="Hasta" type="text">
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn-a-primary pull-right" id="payExtend">Pagar</button>
                <span id="extend-alert" style="display: none"></span>
                <img id="extend-loading" src="/images/newDesign/loading.gif" style="display: none"> 
            </div>
        </div>
    </div>
    <input id="extend-reserve" type="hidden" value="0">
</div>

<!-- Alert -->
<div style="display:none">
    <div id="dialog-alert" title="">
        <p></p>
    </div>
</div>

<script>

    $('#reserves').on('shown.bs.collapse', function () {
        console.log("down to up");
    });
    $('#reserves').on('hidden.bs.collapse', function () {
        console.log("up to down");
    });

    $(document).on("click", ".extend", function(){

        $("#extend-reserve").val($(this).data("reserve-id"));
        $("#extend-from").val($(this).data("reserve-to"));
        $("#extend-to").val("");

        $("#extend-alert").hide();
        
        $("#reserveExtendModal").modal('show');
    });

    $(document).on("click", ".change", function(){

        var button = $(this);
        var panelBody = $(this).parent().parent().parent();
        var newReserveId = $(this).data("reserve-id");

        $.post("<?php echo url_for('reserve_change') ?>", {"newReserveId": newReserveId}, function(r){

            if (r.error) {

                $("#dialog-alert p").html(r.errorMessage);
                $("#dialog-alert").attr("title", "Problemas al cambiar de auto");
                $("#dialog-alert").dialog({
                    buttons: [{
                        text: "Aceptar",
                        click: function() {
                            $( this ).dialog( "close" );
                        }
                    }]
                });

            } else {

                var buttonCheck = "<i class='fa fa-check'></i>";
                var buttonChange = "<button class='change btn-a-action' data-reserve-id='<?php echo $O->getId() ?>'>Cambiar</button>";

                panelBody.find("i").replaceWith(buttonChange);
                button.replaceWith(buttonCheck);
            }
        }, "json");
    });

    $(document).on("click", "#payExtend", function(){

        var reserveId = $("#extend-reserve").val();
        var from      = $("#extend-from").val();
        var to        = $("#extend-to").val();

        var button    = $(this);

        $("#extend-loading").show();
        button.attr("disabled", true);

        $.post("<?php echo url_for('reserve_extend') ?>", {"reserveId": reserveId, "from": from, "to": to}, function(r){
console.log(r);
            if (r.error) {
                $("#extend-alert").html(r.errorMessage);
                $("#extend-alert").show();
            } else {
            }

            $("#extend-loading").hide();
            button.removeAttr("disabled");
        }, "json");
    });

    $('.datetimepicker').datetimepicker({
        allowTimes:[
        "00:00", "00:30", "01:00", "01:30", "02:00", "02:30",
        "03:00", "03:30", "04:00", "04:30", "05:00", "05:30",
        "06:00", "06:30", "07:00", "07:30", "08:00", "08:30",
        "09:00", "09:30", "10:00", "10:30", "11:00", "11:30",
        "12:00", "12:30", "13:00", "13:30", "14:00", "14:30",
        "15:00", "15:30", "16:00", "16:30", "17:00", "17:30",
        "18:00", "18:30", "19:00", "19:30", "20:00", "20:30",
        "21:00", "21:30", "22:00", "22:30", "23:00", "23:30",
        ],
        dayOfWeekStart: 1,
        lang:'es',
        i18n:{
            es:{
                months:[
                'Enero','Febrero','Marzo','Abril',
                'Mayo','Junio','Julio','Agosto',
                'Septiembre','Octubre','Noviembre','Diciembre'
                ],
                dayOfWeek:["Do", "Lu", "Ma", "Mi", "Ju", "Vi", "Sa"]
            }
        },
        format:'d-m-Y H:i'
    });
</script>