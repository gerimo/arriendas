<link href="/css/newDesign/reserves.css?v=2" rel="stylesheet" type="text/css">

<div class="hidden-xs space-100"></div>
<div class="visible-xs space-50"></div>

<div class="row">
    <div class="col-md-offset-2 col-md-8">

        <div class="BCW">

            <?php if (count($PaidReserves) > 0): ?>

                <h1>Reservas recibidas</h1>

                <?php foreach ($PaidReserves as $PaidReserve): ?>
                    <div class="row paid-reserve-container">
                        <div class="col-md-8">
                            <div class="row" style="margin-top: 10px">
                                <div class="col-md-4">
                                    <p class="text-center"><strong>Desde: </strong><?php echo date("d-m-Y H:i", strtotime($PaidReserve->getDate())) ?></p>
                                    <p class="text-center"><strong>Hasta: </strong><?php echo date("d-m-Y H:i", strtotime("+".$PaidReserve->getDuration()." hour", strtotime($PaidReserve->getDate()))) ?></p>
                                </div>
                                <div class="col-md-4">
                                    <p class="price text-center"><?php echo '$'.number_format(CarTable::getPrice($PaidReserve->getFechaInicio2(), $PaidReserve->getFechaTermino2(), $PaidReserve->getCar()->getPricePerHour(), $PaidReserve->getCar()->getPricePerDay(), $PaidReserve->getCar()->getPricePerWeek(), $PaidReserve->getCar()->getPricePerMonth()), 0, ',', '.') ?></p>
                                    <p class="text-center"><?php echo $PaidReserve->getCar()->getModel()->getBrand()->getName()." ".$PaidReserve->getCar()->getModel()->getName() ?></p>
                                </div>
                                <div class="col-md-4">
                                    <p class="text-center"><?php echo $PaidReserve->getUser()->getFirstname()." ".$PaidReserve->getUser()->getLastname() ?></p>
                                    <p class="text-center">
                                        <span class="glyphicon glyphicon-earphone"></span> <a href="tel:<?php echo $PaidReserve->getUser()->getTelephone() ?>"><?php echo $PaidReserve->getUser()->getTelephone() ?></a>
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 text-center">
                            <?php if ($PaidReserve->confirmed): ?>
                                <button class="approved btn btn-a-primary btn-block" disabled>Aprobada</button>
                                <a class="download-contracts" data-car-id="<?php echo $PaidReserve->getCar()->id ?>" data-reserve-token="<?php echo $PaidReserve->token ?>" href="#">Descargar contratos</a>
                            <?php else: ?>
                                <button class="approve btn btn-a-primary btn-block" data-reserve-id="<?php echo $PaidReserve->getId() ?>">Aprobar</button>
                                <a class="reject" data-reserve-id="<?php echo $PaidReserve->getId() ?>">Rechazar</a>
                            <?php endif ?>
                        </div>
                        <div class="col-md-1 text-center">
                            <img class="loading" src="/images/ajax-loader.gif">
                        </div>
                    </div>
                <?php endforeach ?>
            <?php endif ?>

            <?php if (count($Reserves) > 0): ?>
                
                <h1>Reservas</h1>

                <div class="panel-group" id="reserves" role="tablist" aria-multiselectable="true">
                    <?php foreach ($Reserves as $key => $Reserve ): ?>
                        <div class="panel panel-default">
                            <div class="panel-heading" role="tab" id="reserve-<?php echo $Reserve->getId() ?>">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="row">
                                            <div class="col-xs-6 col-sm-6 col-md-6 text-center">
                                                <p><strong>Desde</strong></p>
                                                <p><?php echo date("d-m-Y H:i", strtotime($Reserve->getDate())) ?></p>
                                            </div>
                                            <div class="col-xs-6 col-sm-6 col-md-6 text-center">
                                                <p><strong>Hasta</strong></p>
                                                <p><?php echo date("d-m-Y H:i", strtotime("+".$Reserve->getDuration()." hour", strtotime($Reserve->getDate()))) ?></p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3 text-center">
                                        <p class="price"><strong><?php echo '$'.number_format($Reserve->getPrice(), 0, ',', '.') ?></strong></p>
                                        <a class="download-contracts" data-car-id="<?php echo $Reserve->getCar()->id ?>" data-reserve-token="<?php echo $Reserve->token ?>" href="#">Descargar contratos</a>
                                    </div>
                                    <div class="col-md-3 text-center">
                                        <?php if ($Reserve->getConfirmed()): ?>
                                            <button class="extend btn btn-a-primary btn-block" data-reserve-id="<?php echo $Reserve->getId() ?>" data-reserve-to="<?php echo date("d-m-Y H:i", strtotime($Reserve->getFechaTermino2())) ?>">Extender</button>
                                        <?php else: ?>
                                            <p>&nbsp;</p>
                                            <p>En espera de confirmación</p>
                                        <?php endif ?>
                                    </div>
                                </div>
                                <a class="btn-block text-center" data-toggle="collapse" data-parent="#reserves" href="#collapse-<?php echo $Reserve->getId() ?>" aria-expanded="true" aria-controls="collapse-<?php echo $Reserve->getId() ?>" data-number-of-opportunities="<?php echo count($ChangeOptions[$Reserve->getId()]) ?>">
                                    <?php if ($key >= 0): ?>
                                        <i class="fa fa-chevron-up"></i> Ocultar auto<?php if (count($ChangeOptions[$Reserve->getId()]) > 1) echo "s" ?>
                                    <?php else: ?>
                                        <i class="fa fa-chevron-down"></i> Mostrar auto<?php if (count($ChangeOptions[$Reserve->getId()]) > 1) echo "s" ?>
                                    <?php endif ?>
                                </a>
                            </div>
                            <div id="collapse-<?php echo $Reserve->getId() ?>" data-reserve-id="<?php echo $Reserve->getId() ?>" class="panel-collapse collapse <?php if ($key >= 0) echo 'in' ?>" role="tabpanel" aria-labelledby="reserve-<?php echo $Reserve->getId() ?>">
                                <div class="panel-body">

                                    <?php foreach ($ChangeOptions[$Reserve->getId()] as $i => $CO): ?>
                                        <div class="row">
                                            <div class="col-md-8">
                                                <div class="col-md-3 text-center">
                                                    <?php if ($CO->getCar()->getFotoPerfilS3('xs')): ?>
                                                        <img alt="Foto_perfil" src="<?php echo $CO->getCar()->getFotoPerfilS3('xs')?>" width="84px" height="84px">
                                                    <?php else: ?>
                                                        <?php if ($CO->getCar()->getFotoPerfil() == null): ?>
                                                            <?php echo image_tag('img_asegura_tu_auto/AutoVistaAerea.png', array("width"=>"84px","height"=>"84px")) ?>
                                                        <?php else: ?>
                                                            <?php echo image_tag($CO->getCar()->getFotoPerfil(), array("width"=>"84px","height"=>"84px")) ?>
                                                        <?php endif ?>
                                                    <?php endif ?>
                                                    <!--<img src="/uploads/cars/thumbs/<?php echo $CO->getCar()->getFotoPerfil() ?>" width="80%"> -->
                                                </div>
                                                <div class="col-md-9">
                                                    <br class="visible-xs">
                                                    <p><b>Auto: </b><?php echo $CO->getCar()->getModel()->getBrand()->getName() ." ". $CO->getCar()->getModel()->getName() ?>, <i class="fa fa-cog"></i> <?php if ($CO->getCar()->getTransmission()) echo "Automático"; else echo "Mecánico"; ?></p>
                                                    <p><b>Dirección: </b><?php echo $CO->getCar()->getAddress() .", ". $CO->getCar()->getCommune()->name ?></p>
                                                    <p><b>Dueño: </b><?php echo $CO->getCar()->getUser()->firstname ." ". substr($CO->getCar()->getUser()->lastname, 0, 1) ?></p>
                                                    <?php if ($CO->getTransaction()->completed): ?>
                                                        <p><b>Contacto:</b>
                                                            <span class="glyphicon glyphicon-earphone"></span> <a href="tel:<?php echo $CO->getCar()->getUser()->getTelephone() ?>"><?php echo $CO->getCar()->getUser()->getTelephone() ?></a>
                                                            <span class="glyphicon glyphicon-envelope"></span> <a href="mailto:<?php echo $CO->getCar()->getUser()->getEmail() ?>"><?php echo $CO->getCar()->getUser()->getEmail() ?></a>
                                                        </p>
                                                    <?php endif ?>
                                                    </p>
                                                    <p><img class="metro" src='/images/newDesign/ico.png' alt='metro'> A <b><?php echo round($CO->getCar()->getNearestMetro()->distance, 1) ?> km</b> del Metro <?php echo $CO->getCar()->getNearestMetro()->getMetro()->name ?></p>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <?php if ($CO->getTransaction()->getCompleted()): ?>
                                                    <button class="reserved btn btn-a-primary btn-block" disabled>Reservado</button>
                                                <?php else: ?>
                                                    <button class="change btn btn-a-action btn-block" data-reserve-id="<?php echo $CO->getId() ?>">Cambiar</button>
                                                <?php endif ?>
                                            </div>
                                            <div class="col-md-1 text-center">
                                                <img class="loading" src="/images/ajax-loader.gif">
                                            </div>
                                        </div>
                                        <?php if ($i < count($ChangeOptions[$Reserve->getId()])-1): ?>
                                            <hr>
                                        <?php elseif (count($CarsWithAvailability[$Reserve->getId()]) > 0): ?>
                                            <hr>
                                        <?php endif ?>
                                    <?php endforeach ?>

                                    <?php foreach ($CarsWithAvailability[$Reserve->getId()] as $i => $C): ?>
                                        <div class="row">
                                            <div class="col-md-8">
                                                <div class="col-md-3 text-center">
                                                    <?php if ($C->getFotoPerfilS3('xs')): ?>
                                                        <img alt="Foto_perfil" src="<?php echo $C->getFotoPerfilS3('xs')?>" width="84px" height="84px">
                                                    <?php else: ?>
                                                        <?php if ($C->getFotoPerfil() == null): ?>
                                                            <?php echo image_tag('img_asegura_tu_auto/AutoVistaAerea.png', array("width"=>"84px","height"=>"84px")) ?>
                                                        <?php else: ?>
                                                            <?php echo image_tag($C->getFotoPerfil(), array("width"=>"84px","height"=>"84px")) ?>
                                                        <?php endif ?>
                                                    <?php endif ?>
                                                    <!-- <img src="/uploads/cars/thumbs/<?php echo $C->getFotoPerfil() ?>" width="80%"> -->
                                                </div>
                                                <div class="col-md-9">
                                                    <br class="visible-xs">
                                                    <p><b>Auto: </b><?php echo $C->getModel()->getBrand()->getName() ." ". $C->getModel()->getName() ?>, <i class="fa fa-cog"></i> <?php if ($C->getTransmission()) echo "Automático"; else echo "Mecánico"; ?></p>
                                                    <p><b>Dirección: </b><?php echo $C->getAddress() .", ". $C->getCommune()->name ?></p>
                                                    <p><b>Dueño: </b><?php echo $C->getUser()->firstname ." ". substr($C->getUser()->lastname, 0, 1) ?></p>
                                                    <p><img class="metro" src='/images/newDesign/ico.png' alt='metro'> A <b><?php echo round($C->getNearestMetro()->distance, 1) ?> km</b> del Metro <?php echo $C->getNearestMetro()->getMetro()->name ?></p>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <button class="change-with-availability btn btn-a-action btn-block" data-car-id="<?php echo $C->getId() ?>" data-reserve-id="<?php echo $Reserve->getId() ?>">Cambiar</button>
                                            </div>
                                            <div class="col-md-1 text-center">
                                                <img class="loading" src="/images/ajax-loader.gif">
                                            </div>
                                        </div>
                                        <?php if ($i < count($CarsWithAvailability[$Reserve->getId()])-1): ?>
                                            <hr>
                                        <?php endif ?>
                                    <?php endforeach ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach ?>
                </div>
            <?php else: ?>
                <?php if (count($PaidReserves) == 0): ?>
                    <h2 class="text-center">Actualmente no hay reservas en curso</h2>
                <?php endif ?>
            <?php endif ?>
        </div>
    </div>
</div>

<div class="hidden-xs space-100"></div>

<!-- Modal extensión reserva -->
<div class="modal fade" id="reserveExtendModal" tabindex="-1" role="dialog" aria-labelledby="reserveExtendModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="<?php echo url_for('reserve_extend') ?>" id="extendForm" method="post">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="reserveExtendModalLabel">Extender reserva</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">                        
                            <div class="form-group">
                                <label for="extendFrom">Desde</label>
                                <input disabled class="form-control" id="extendFrom" name="from" placeholder="Desde" type="text">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="extendTo">Hasta</label>
                                <input class="datetimepicker form-control" id="extendTo" name="to" placeholder="Hasta" type="text">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 text-center">
                            <img id="extendPriceLoading" src="/images/newDesign/loading.gif" width="33" height="33" style="display:none">
                            <p class="price" id="extendPrice" style="display: none"></p>
                            <p class="alert-danger" id="extendPriceAlert" style="display: none"></p>
                        </div>
                    </div>
                    <input id="extendReserve" name="reserveId" type="hidden" value="0">
                </div>
                <div class="modal-footer">
                    <button class="btn btn-a-primary pull-right" id="payExtend">Pagar</button>
                    <span id="extendAlert" style="display: none"></span>
                    <img id="extendLoading" src="/images/newDesign/loading.gif" style="display: none; margin-top: 4px; width: 28px">
                </div>
            </form>
        </div>
    </div>    
</div>

<!-- Alerta -->
<div style="display:none">
    <div id="dialog-alert" title="">
        <p></p>
    </div>
</div>

<script>

    $("a[data-target='reserves']").parent().addClass("active");

    $('#reserves').on('shown.bs.collapse', function () {
        checkCollapse();
    });

    $('#reserves').on('hidden.bs.collapse', function () {
        checkCollapse();
    });

    $(document).on("click", ".approve", function(){

        var button    = $(this);
        var grandpa = $(this).parent().parent();
        var reserveId = $(this).data("reserve-id");

        button.attr("disabled", true);
        grandpa.find(".loading").show();

        $.post("<?php echo url_for('reserve_approve') ?>", {"reserveId": reserveId}, function(r){
            if (r.error) {
                $("#dialog-alert p").html(r.errorMessage);
                $("#dialog-alert").attr("title", "Problemas al aprobar la reserva");
                $("#dialog-alert").dialog({
                    buttons: [{
                        text: "Aceptar",
                        click: function() {
                            $( this ).dialog( "close" );
                        }
                    }]
                });
                button.removeAttr("disabled");
                grandpa.find(".loading").hide();
            } else {
                location.reload();
            }
        }, "json");
    });

    $(document).on("click", ".change", function(){

        var button = $(this);
        var grandpa = $(this).parent().parent();
        var newReserveId = $(this).data("reserve-id");

        button.attr("disabled", true);
        grandpa.find(".loading").show();

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

                button.removeAttr("disabled");
                grandpa.find(".loading").hide();
            } else {

                location.reload();
            }            
        }, "json");
    });

    $(document).on("click", ".change-with-availability", function(){        

        var button    = $(this);
        var grandpa   = $(this).parent().parent();
        var carId     = $(this).data("car-id");
        var reserveId = $(this).data("reserve-id");        

        button.attr("disabled", true);
        grandpa.find(".loading").show();

        var parameters = {
            "carId": carId,
            "reserveId": reserveId
        };

        $.post("<?php echo url_for('reserve_change_with_availability') ?>", parameters, function(r){

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

                button.removeAttr("disabled");
                grandpa.find(".loading").hide();
            } else {
                location.reload();
            }            
        }, "json");
    });

    $(document).on("click", ".extend", function(){

        $("#extendReserve").val($(this).data("reserve-id"));
        $("#extendFrom").val($(this).data("reserve-to"));
        $("#extendTo").val("");

        $("#extendAlert").hide();
        
        $("#reserveExtendModal").modal('show');
    });

    $(document).on("click", ".reject", function(e){

        e.preventDefault;

        var grandpa = $(this).parent().parent();
        var reserveId = $(this).data("reserve-id");

        grandpa.find(".loading").show();

        $.post("<?php echo url_for('reserve_reject') ?>", {"reserveId": reserveId}, function(r){

            if (r.error) {

                $("#dialog-alert p").html(r.errorMessage);
                $("#dialog-alert").attr("title", "Problemas al rechazar la reserva");
                $("#dialog-alert").dialog({
                    buttons: [{
                        text: "Aceptar",
                        click: function() {
                            $( this ).dialog( "close" );
                        }
                    }]
                });
                grandpa.find(".loading").hide();
            } else {

                location.reload();
            }
        }, "json");
    })

    $(document).on("click", "#payExtend", function(){

        $("#extendLoading").show();
        $("#payExtend").attr("disabled", true);
        $("#extendFrom").removeAttr("disabled");

        $("#extendForm").submit();
    });

    $(document).on("click", ".download-contracts", function(e){

        e.preventDefault();

        var carId = $(this).data("car-id");
        var reserveToken = $(this).data("reserve-token");

        var urlContrato   = "http://<?php echo $sf_request->getHost() ?>/api.php/contrato/generarContrato/tokenReserva/";
        var urlFormulario = "http://<?php echo $sf_request->getHost() ?>/main/generarFormularioEntregaDevolucion/tokenReserve/";
        var urlPagare     = "http://<?php echo $sf_request->getHost() ?>/main/generarPagare/tokenReserve/";
        /*var urlContrato3 = "http://<?php echo $sf_request->getHost() ?>/main/generarReporte/idAuto/";*/

        window.open(urlContrato + reserveToken);
        window.open(urlFormulario + reserveToken);
        window.open(urlPagare + reserveToken);
        /*window.open(urlContrato3 + carId);*/
    });

    $("#extendTo").datetimepicker({
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
        minDate: "<?php echo date('d-m-Y') ?>",
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
        format:'d-m-Y H:i',
        onSelectDate: function() {
            getExtendPrice();
            $("#extendTo").val(roundMinutos($("#extendTo").val()));  
        },
        onSelectTime: function() {
            getExtendPrice();
        }
    });

    $("#extendFrom").datetimepicker({
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
        format:'d-m-Y H:i',
        onSelectDate: function() {
            getExtendPrice();
        },
        onSelectTime: function() {
            getExtendPrice();
        }
    });

    function checkCollapse() {

        $("a[data-toggle='collapse']").each(function(){

            var html = "";
            var isCollapse = $.parseJSON($(this).attr("aria-expanded"));
            var numberOfOpportunities = $(this).data("number-of-opportunities");

            if (isCollapse) {
                html += "<i class='fa fa-chevron-up'></i> Ocultar auto";
            } else {
                html += "<i class='fa fa-chevron-down'></i> Mostrar auto";
            }

            if (numberOfOpportunities > 1) {
                html += "s";
            }

            $(this).html(html);
        });
    }

    function getExtendPrice() {

        var reserveId = $("#extendReserve").val();
        var from      = $("#extendFrom").val();
        var to        = $("#extendTo").val();

        $("#extendPrice").hide();
        $("#extendPriceAlert").hide();
        $("#extendPriceLoading").show();

        $("#payExtend").attr("disabled", true);

        $.post("<?php echo url_for('reserve_extend_get_price') ?>", {"reserveId": reserveId, "from": from, "to": to}, function(r){

            $("#extendPriceLoading").hide();

            if (r.error) {
                $("#extendPriceAlert").html(r.errorMessage);
                $("#extendPriceAlert").show();
            } else {
                $("#extendPrice").html("$"+$.number(r.price, 0, ',', '.'));
                $("#extendPrice").show();
                $("#payExtend").removeAttr("disabled");
            }

        }, "json");
    }

    function roundMinutos(valor){
        var fechaH = valor

        var split = fechaH.split(" ");
        var f = split[0];
        var h = split[1];

        var split3 = h.split(":");
        var hora = split3[0];
        var min = split3[1];

        if(min > "14" && min < "45"){
            min = "30";
        }else if(min > "45"){
            min = "00";
            var a = parseInt(hora)+1;
            hora = a.toString();
        }else{
            min = "00";
        }

        fecha = f+" "+hora+":"+min;

        return fecha;
    }    
</script>