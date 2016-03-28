<link href="/css/newDesign/mobile/reserve.css" rel="stylesheet" type="text/css">

<head>
    <script>(function() {
        var _fbq = window._fbq || (window._fbq = []);
        if (!_fbq.loaded) {
        var fbds = document.createElement('script');
        fbds.async = true;
        fbds.src = '//connect.facebook.net/en_US/fbds.js';
        var s = document.getElementsByTagName('script')[0];
        s.parentNode.insertBefore(fbds, s);
        _fbq.loaded = true;
        }
        _fbq.push(['addPixelId', '1519934024954094']);
        })();
        window._fbq = window._fbq || [];
        window._fbq.push(['track', 'PixelInitialized', {}]);
    </script>
    <noscript>
        <img height="1" width="1" alt="" style="display:none" src="https://www.facebook.com/tr?id=1519934024954094&amp;ev=PixelInitialized" />
    </noscript>
</head>

<!-- <div class="space-90 hidden-xs"></div> -->
<div class="space-50"></div>

<div class="container">

    <div class="col-md-5 carousal-area">
        <div id="carousel-example-generic" class="carousel slide" data-ride="carousel">
            <div class="carousel-inner" role="listbox">
                <?php if ($arrayFotos): ?>                        
                    <?php for ($i = 0; $i < count($arrayFotos); $i++): ?>
                        <div class="item <?php if ($i == 0) echo 'active' ?>">
                            <?php echo image_tag($arrayFotos[$i]) ?>
                        </div>
                    <?php endfor ?>
                <?php endif ?>
            </div>
            <a class="left carousel-control" href="#carousel-example-generic" role="button" data-slide="prev"></a>
            <a class="right carousel-control" href="#carousel-example-generic" role="button" data-slide="next"></a>
        </div>
    </div>

    <h1><?php echo $Car->getModel()->getBrand()->name." ".$Car->getModel()->name.", <small>".$Car->getYear().", ".$Car->getCommune()."</small>" ?></h1>

    <h2>Características:</h2>

    <ul class="features">
        <?php if ($metro): ?>
            <li><span class="metro-mobile"></span> A <strong>1.5 km</strong> del Metro Católica</li>
        <?php endif ?>
        <?php if ($passengers): ?>
            <li><span class="icon-svg_01"></span> 5 o más pasajeros</li>
        <?php else: ?>
            <li><span class="icon-svg_01"></span> 4 pasajeros</li>
        <?php endif ?>

        <?php if ($diesel): ?>
            <li><i class="fa fa-tint"></i> Petrolero</li>
        <?php else: ?>
            <li><i class="fa fa-tint"></i> Bencinero</li>
        <?php endif ?>

        <?php if ($airCondition): ?>
            <li><span class="icon-svg_03"></span> Aire acondicionado</li>
        <?php endif ?>

        <?php if ($transmission): ?>
            <li><i class="fa fa-cog"></i> Automático</li>
        <?php else: ?>
            <li><i class="fa fa-cog"></i> Mecánico</li>
        <?php endif ?>

        <li><img class="metro" src='/images/newDesign/ico.png' alt='metro'> A <b><?php echo round($Car->getNearestMetro()->distance, 1) ?> km</b> del Metro <?php echo $Car->getNearestMetro()->getMetro()->name ?></li>
    </ul>

    <div class="space-20"></div>
    
    <div class="block-mobile">
        <div class="saved pull-right"><strong>40%</strong>AHORRO</div>
        <div class="prices">
            <?php if ($Car->getPricePerHour()):?>
                <p>$<?php echo number_format(round($Car->getPricePerHour()), 0, '', '.')?> / <small>HORA</small></p>
            <?php endif ?>
            <?php if ($Car->getPricePerDay()):?>
                <p><strong>$<?php echo number_format(round($Car->getPricePerDay()), 0, '', '.')?> / <small>DÍA</small></strong></p>
            <?php endif ?>
            <?php if ($Car->getPricePerWeek()):?>
                <p>$<?php echo number_format(round(($Car->getPricePerWeek()/7)), 0, '', '.') ?> / <small>DÍA SEMANA</small></p>
            <?php endif ?>
            <?php if ($Car->getPricePerMonth()):?>
                <p>$<?php echo number_format(round(($Car->getPricePerMonth()/30)), 0, '', '.') ?> / <small>DÍA MES</small></p>
            <?php endif ?> 
        </div>
    </div>

    <h2 class="body-title">Garantía Arriendas.cl</h2>
    <div class="row">
        <div class="steps col-md-offset-1 col-md-10">            
            <ul class="list-warranty">
                <li><i class="fa fa-check"></i></span><strong>Calidad Arriendas:</strong> el auto se encuentra verificado por Arriendas.</li>
                <li><i class="fa fa-check"></i></span><strong>Garantía Arriendas:</strong> 2 opciones de reemplazo o te devolvemos el dinero.</li>
                <li><i class="fa fa-check"></i></span><strong>Ofertas:</strong> Recibirás ofertas de otros dueños manteniendo este precio.</li>
            </ul>
        </div>
    </div>
    
    <!-- Reviews -->
    <?php if (count($reviews) > 0): ?>
        <div class="row">
            <div class="panel-group col-md-11" id="reviews">

                <div class="panel-heading text-center">
                    <h1 class="body-title"><?php echo count($reviews) ?> Reviews</h1>
                    <span class="car-rating" data-number="<?php echo $reviews_avg ?>" ></span>
                </div>

                <div class="space-30 hidden-xs"></div>
                <div id="reviewsCollapseOne" class="panel-collapse col-md-offset-1 col-md-11" >
                    <div class="border">
                        <div class="collapse" id="collapseReviews">

                            <?php foreach ($reviews as $review): ?>
                                <div class="row review">
                                    <div class="col-xs-12 text-center">
                                        <?php if ($review['user_photo']): ?>
                                            <img src="<?php echo $review['user_photo'] ?>">
                                        <?php else: ?>
                                            <i class="fa fa-user" style="font-size: 38px"></i>
                                        <?php endif ?>
                                    </div>
                                    <div class="col-xs-12">
                                        <p><?php echo $review['opinion'] ?></p>
                                        <span class="car-rating pull-right" data-number="<?php echo $review['rating'] ?>" ></span>
                                        <i class="pull-right"><?php echo $review['date'] ?></i>
                                    </div>
                                </div>
                            <?php endforeach ?>

                        </div>

                    </div>

                    <a class="title-collapse text-center" data-toggle="collapse" href="#collapseReviews" aria-expanded="false" aria-controls="collapseReviews">
                        <h1><i id="reviewArrow" class="fa fa-angle-down" data-pos="0"></i></h1>
                    </a>

                </div>
            </div>
        </div>
    <?php endif ?>

    <form action="<?php echo url_for('reserve_pay') ?>" id="reserve-form" method="post">
        
        <h1 class="body-title" id="reserve">Arriendo de <?php echo $Car->getModel()->getBrand()->name ?> <?php echo $Car->getModel()->name ?> a $<span class="price"><?php echo number_format($price, 0, ',', '.') ?></span> en <?php echo $Car->getCommune()->name ?></h1>
        
        <div class="body row">
            <div class="col-sm-12 col-xs-12 col-md-offset-1 col-md-10">

                <!-- Duración -->
                <h2><span class="num">1</span> DURACIÓN</h2>
                <input class="datetimepicker btn-block" id="fromH" type="button" value="Desde: <?php echo $fromHuman ?>"></input>
                <input class="datetimepicker btn-block" id="toH" type="button" value="Hasta: <?php echo $toHuman ?>"></input>

                <!-- Garantía -->
                <h2 id="warranty-section"><span class="num">2</span> GARANTÍA</h2>
                <div class="clearfix" id="warranty-container">
                    <div id="sub-price-container">
                        <span class="pull-right">$<span class="price"><?php echo number_format($price, 0, ',', '.') ?></span></span>SUB TOTAL
                    </div>
                    <div class="price-count">
                        <div class="radio">
                            <label>
                                <input data-value="<?php echo $amountWarranty ?>" name="warranty" type="radio" value="1">
                                $<?php echo number_format($amountWarranty, 0, ',', '.') ?> por Depósito en Garantía <small>(se devuelve al finalizar el arriendo)</small>
                            </label>
                        </div>
                       
                            <div class="radio">
                                <label>
                                    <input data-value="<?php echo $amountWarrantyFree ?>" name="warranty" type="radio" value="0">
                                    $<?php echo number_format($amountWarrantyFree, 0, ',', '.') ?> por Eliminar el Depósito en Garantía <small>(por día de arriendo)</small>
                                </label>
                            </div>
                    </div>

                    <div class="price-count-commission">

                        <input data-value="<?php echo $baseCommission?>" name="comission" id="commission" checked type="hidden">
                        <span class="pull-right" id="baseCommissionSpan"> $<?php echo number_format($baseCommission, 0, ',', '.') ?></span>
                        <span class="comission-text">Comisión Arriendas (%<?php echo ROUND($baseCommissionValue,1) ?>)</span>

                    </div>

                    <div class="price-count-commission transbank-commission" style="display: none;" data-state="0">

                        <input data-value="<?php echo $transBankCommission ?>" name="comissionTbank" id="commission-Tbank" checked type="hidden">
                        <span class="pull-right" id="transbankCommissionSpan"> $<?php echo number_format($transBankCommission, 0, ',', '.') ?></span>
                        <span class="comission-text">Comisión TransBank (%<?php echo ROUND($transBankCommissionValue,1) ?>)</span>

                    </div>

                    <span class="pull-right total-box">TOTAL <strong>$<span class="total-price"><?php echo number_format($price, 0, ',', '.') ?></span></strong></span>
                </div>

                <!-- Medio de pago -->
                <h2 id="payment-section"><span class="num">3</span> MEDIO DE PAGO</h2>
                <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
                    <div class="panel panel-default">
                        <div class="panel-heading" role="tab" id="headingTwo">
                            <h4 class="panel-title">
                                <a class="payment btn-block" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" aria-expanded="true" aria-controls="collapseTwo">
                                    <img class="pull-right" src="/images/newDesign/payments/webpay.png">
                                    <input name="payment" type="radio" value="2"> Tarjeta de débito / crédito
                                </a>
                            </h4>
                        </div>
                        <div id="collapseTwo" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwo">
                            <div class="panel-body">
                                <p>Paga a través de <b>WebPay</b> con tu tarjeta de débito o crédito</p>
                            </div>
                        </div>
                    </div>
                    <div class="panel panel-default">
                        <div class="panel-heading" role="tab" id="headingOne">
                            <h4 class="panel-title">
                                <a class="payment btn-block" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                    <img class="pull-right" src="/images/newDesign/payments/khipu.png">
                                    <input name="payment" type="radio" value="1" checked> Transferencia Bancaria
                                </a>
                            </h4>
                        </div>
                        <div id="collapseOne" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
                            <div class="panel-body">
                                <p>Paga a través de una Transferencia Bancaria, mediante <b>Khipu</b>. Podrás hacerlo a través de los diversos bancos nacionales.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Botón de pago -->
        <div class="row">
            <div class="col-md-offset-4 col-md-4">
                
                    <button class="btn-block" id="btn-pay" type="button">PAGAR</button>
                    <p class="secure-payment"><span class="icon-svg_21"></span> Pago seguro</p>
              
            </div>
        </div>

        <!-- FORMULARIO -->
        <input id="car" name="car" type="hidden" value="<?php echo $Car->getId() ?>">
        <input id="from" name="from" type="hidden" value="<?php echo $from ?>">
        <input id="to" name="to" type="hidden" value="<?php echo $to ?>">        
        <input id="baseCommission" name="baseCommission" type="hidden" value="<?php echo $baseCommission ?>"></input>
        <input id="commissionTbank" name="commissionTbank" type="hidden" value="<?php echo $transBankCommission ?>"></input>
    </form>
    <input id="price" type="hidden" value="<?php echo $price ?>">
    <input id="total-price" type="hidden" value="<?php echo $price ?>">
</div>

<!-- Alert -->
<div style="display:none">
    <div id="dialog-alert" title="">
        <p></p>
    </div>

    <?php include_partial('contratosArrendatario') ?>
    <!-- 
    <form action="<?php echo url_for('main/uploadLicense?photo=licence&width=194&height=204&file=filelicence') ?>" enctype="multipart/form-data" id="formlicence" method="post">
        <input id="filelicence" name="filelicence" type="file">
        <input type="submit">
    </form>
     -->
</div>

<script>

    $(document).ready(function(){

        TBankCommissionUpdater();
        refreshTotalPrice();

        imageUpload('#formlicence', '#filelicence', '#previewlicence','#linklicence');       

        // Carousel
        $('#car-images-carousel').slick({
            dots: false,
            infinite: true
        });

        // Rating
        $('.car-rating').raty({
            readOnly: true,
            score: function() {
                return $(this).attr('data-number');
            }
        });

        refreshDate(true, true);
        refreshDate(false, true);

    });

    $('#reviews').on('shown.bs.collapse', function () {
        $("#reviewArrow").removeClass("fa fa-angle-down");
        $("#reviewArrow").addClass("fa fa-angle-up");
    });

    $('#reviews').on('hidden.bs.collapse', function () {
        $("#reviewArrow").removeClass("fa fa-angle-up");
        $("#reviewArrow").addClass("fa fa-angle-down");
    });

    $("#btn-pay").on('click', function(e) {
        $("#dialog-alert p").html("Se está modificando el sistema.<br>No se están procesando reservas.");
        $("#dialog-alert").attr("title", "Estimado usuario:");
        $("#dialog-alert").dialog({
            buttons: [{
                text: "Aceptar",
                click: function() {
                    $(this).dialog( "close" );
                }
            }]
        });
    });

    // Mantención
    /*
    $("#btn-pay").on('click', function(e) {

        if (isValidForm()) {

            $("#confirmarContratosArrendatario").dialog({
                closeOnText: true,
                modal: true,
                resizable: true,
                title: "Contratos",
                width: "90%",
                buttons: [
                    {
                        text: "Cancelar",
                        click: function() {
                            $( this ).dialog( "close" );
                        }
                    },
                    {
                        text: "Aceptar",
                        click: function() {

                            var inputs = $("#confirmarContratosArrendatario input[type='checkbox']");
                            var userAccept = true;
                            
                            $.each(inputs, function(index, input){

                                if (!input.checked) {
                                    userAccept = false;
                                }
                            });

                            if (userAccept) {
                                var url = "<?php echo $redirect ?>";
                                if(url){
                                    window.location.href = url;
                                }
                                // llamada Post ajax que verifica que la hora de inicio de la reserva no sea dentro de 2 horas
                                $.post("<?php echo url_for('reserve_compare_time_from') ?>", {from:$("#from").val()}, function(r){
                                    if (r.error) {
                                        console.error(r.errorMessage);
                                    } else {
                                        if(!r.resultado){


                                            var carId = "<?php echo $Car->getId() ?>"
                                            var from  = $("#from").val();
                                            
                                            var to    = $("#to").val();
                                            
                                            var warranty = $('input[type=radio][name=warranty]:checked').val();
                                            var payment  =  $("input[name='payment']").val();

                                            parameters = {
                                                "carId": carId,
                                                "from": from,
                                                "to": to,
                                                "warranty": warranty,
                                                "payment": payment
                                            }
                                            
                                            $.post("<?php echo url_for('data_for_payment')?>", parameters, function(r){
                                                console.log(r);
                                                if (r.error) {

                                                } else {
                                                    $("#reserve-form").submit();
                                                }
                                            });
                                        }else{
                                            // si hay menos de 2 horas de margen, pra el inicio de la reserva, muestra el mensasje
                                            $("#dialog-alert p").html(r.mensaje);
                                            $("#dialog-alert").attr("title", "Problema al procesar la reserva");
                                            $("#dialog-alert").dialog({
                                                buttons: [{
                                                    text: "Aceptar",
                                                    click: function() {
                                                        $(this).dialog( "close" );
                                                    }
                                                }]
                                            });
                                        }
                                    }
                                    
                                }, 'json');
                            }
                        }
                    }
                ]
            });
        }
    });    
    */

    $("#fromH").datetimepicker({ 
        dayOfWeekStart: 1,
        lang:'es',
        format:'D d/m/Y H:i',
        minDate: "<?php echo date('Y-m-d') ?>",
        timepicker: true,
        validateOnBlur: false,
        value: "<?php echo date('D d/m/Y H:i', strtotime($from)) ?>",
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
        i18n:{
            es:{
                months:[
                'Enero','Febrero','Marzo','Abril','Mayo',
                'Junio','Julio','Agosto','Septiembre',
                'Octubre','Noviembre','Diciembre'
                ],
                dayOfWeek:["Do", "Lu", "Ma", "Mi", "Ju", "Vi", "Sa"]
            }
        },
        onSelectDate: function() {
            refreshDate(true, false);
        },
        onSelectTime: function() {
            refreshDate(true, false);
        }
    });

    $("#toH").datetimepicker({ 
        dayOfWeekStart: 1,
        lang:'es',
        format:'D d/m/Y H:i',
        minDate: "<?php echo date('Y-m-d') ?>",
        timepicker: true,
        validateOnBlur: false,
        value: "<?php echo date('D d/m/Y H:i', strtotime($to)) ?>",
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
        onSelectDate: function() {
            refreshDate(false, false);
        },
        onSelectTime: function() {
            refreshDate(false, false);
        }
    });

    $("input[type=radio][name=warranty]").on('change', function(e) {
        TBankCommissionUpdater();
        refreshTotalPrice();

        if ($(this).val() == 0) {
            $("#headingTwo").parent().show();
        } else {
            $("#headingTwo").parent().hide();
            $("#headingTwo").find("input").attr("checked", false);
        }
    });

    $(".payment").on('click', function(e) {

        $("input[name='payment']").prop("checked", false);

        $(this).find("input").prop("checked", true);

        TBankCommissionUpdater();
        refreshTotalPrice();
    });

    $(".reserve").on('click', function(e) {

        var target  = $(this).data('target');
        var position = $(target).offset().top - 100;

        $('html, body').animate({
            scrollTop: position
        }, 1250);
    });

    function dateFormat(fecha){

        var split = fecha.split(" ");
        var f = split[1];
        var h = split[2];

        var split2 = f.split("/");
        var dia = split2[0];
        var mes = split2[1];
        var ano = split2[2];

        var split3 = h.split(":");
        var hora = split3[0];
        var min  = split3[1];

        return ano+"-"+mes+"-"+dia+" "+hora+":"+min;
    }

    function explodeDate(date) {

        sDT = date.split(" ");
        sD = sDT[0].split("-");
        sT = sDT[1].split(":");

        return {
            "year": parseInt(sD[0]),
            "month": parseInt(sD[1]),
            "day": parseInt(sD[2]),
            "hours": parseInt(sT[0]),
            "minutes": parseInt(sT[1])
        }
    }

    function getRentalPrice() {

        var carId = $("#car").val();
        var from  = $("#from").val();
        var to    = $("#to").val();

        var parameters = {
            "carId": carId,
            "from": from,
            "to": to
        };

        $.post("<?php echo url_for('reserve_calculate_price') ?>", parameters, function(r){

            if (r.error) {
                $("#dialog-alert p").html(r.errorMessage);
                $("#dialog-alert").attr("title", "Error al calcular el precio");
                $("#dialog-alert").dialog({
                    buttons: [{
                        text: "Aceptar",
                        click: function() {
                            $(this).dialog( "close" );
                        }
                    }]
                });
            } else {
                $("#price").val(r.price);
                $(".price").html($.number(r.price, 0, ',', '.'));
                $("#baseCommissionSpan").html(r.baseCommission);
                $("#transbankCommissionSpan").html(r.transBankCommission);
                $("#baseCommission").val(r.baseCommissionValue);
                $("#commissionTbank").val(r.transBankCommissionValue);
                $('#commission').data('value',r.baseCommissionValue);
                $('#commission-Tbank').data('value',r.transBankCommissionValue);
                refreshTotalPrice();
            }
        }, 'json');
    }

    function getCarTime() {

        var from  = $("#from").val();
        var to    = $("#to").val();

        $.post("<?php echo url_for('reserves/calculateTime') ?>", {"from": from, "to": to}, function(r){
            
            if (r.error) {
                $("#dialog-alert p").html(r.errorMessage);
                $("#dialog-alert").attr("title", "¡Alerta!");
                $("#dialog-alert").dialog({
                    buttons: [{
                        text: "Aceptar",
                        click: function() {
                            $( this ).dialog( "close" );
                        }
                    }]
                });

                setDefaultDateH();

            } else {
                $("#time").html("("+r.tiempo+" Horas)");
            }

        }, 'json');
    }    

    function imageUpload (form, formfile, preview, link) {

        $(formfile).change(function(e) {
            
            $(preview).html('<?php echo image_tag('loader.gif') ?>');

            $(form).ajaxForm({
                dataType: "json",
                target: preview,
                success: function(r){
                    if (r.error) {
                        $("#dialog-alert p").html(r.errorMessage);
                        $("#dialog-alert").attr("title", "Problemas al subir la imagen");
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
                }
            }).submit();
        });
        
        $(link).click(function(e) {
            e.preventDefault();
            $(formfile).click();
        });
    }

    function isValidForm() {

        var warranty = $('input:radio[name=warranty]:checked').val();

        if (warranty === undefined) {

            $("#dialog-alert p").html("Necesitas definir el tipo de garatía");
            $("#dialog-alert").attr("title", "No se puede iniciar el pago");
            $("#dialog-alert").dialog({
                buttons: [{
                    text: "Aceptar",
                    click: function() {
                        $(this).dialog( "close" );

                        var position = $("#warranty-section").offset().top - 100;

                        $('html, body').animate({
                            scrollTop: position
                        }, 1250);
                    }
                }]
            });

            return false;
        }

        var payment = $('input:radio[name=payment]:checked').val();

        if (payment === undefined) {

            $("#dialog-alert p").html("Necesitas seleccionar el medio de pago");
            $("#dialog-alert").attr("title", "No se puede iniciar el pago");
            $("#dialog-alert").dialog({
                buttons: [{
                    text: "Aceptar",
                    click: function() {
                        $(this).dialog( "close" );

                        var position = $("#payment-section").offset().top - 100;

                        $('html, body').animate({
                            scrollTop: position
                        }, 1250);
                    }
                }]
            });

            return false;
        }

        return true;
    }

    function refreshDate(isFrom, isDocumentReady) {

        if (isFrom) {

            var fromH = $("#fromH").val();
            s = fromH.split(" ");
            $("#fromH").val("Desde: "+translateDay(s[0])+" "+s[1]+" a las "+s[2]);
            $("#from").val(dateFormat(fromH));

            if (!isDocumentReady) {

                var toDate = explodeDate($("#from").val());
                var to = new Date(toDate['year'], toDate['month']-1, toDate['day']+1, toDate['hours'], toDate['minutes'], 0, 0);

                $("#toH").val("Hasta: "+translateDay(to.format('D'))+" "+to.format('d/m/Y')+" a las "+pad(to.format('H'), 2)+":"+to.format('i'));
                $("#to").val(to.format("Y-m-d "+pad(to.format('H'), 2)+":i"));
            }
        } else {            
            var toH = $("#toH").val();
            s = toH.split(" ");
            $("#toH").val("Hasta: "+translateDay(s[0])+" "+s[1]+" a las "+s[2]);
            $("#to").val(dateFormat(toH));
        }

        getRentalPrice();
        /*getCarTime();*/
    }

    function refreshTotalPrice() {

        var paymentType  = parseInt($('input[type=radio][name=payment]:checked').val());
        var warrantyType  = $('input[type=radio][name=warranty]:checked').val();
        var warrantyPrice = parseInt($('input[type=radio][name=warranty]:checked').data("value"));

        var commission    = parseInt($('#commission').data('value'));
        var commissionTbank    = parseInt($('#commission-Tbank').data('value'));

        fromDate = explodeDate($("#from").val());
        toDate = explodeDate($("#to").val());

        var from     = new Date(fromDate['year'], fromDate['month'], fromDate['day'], fromDate['hours'], fromDate['minutes'], 0, 0);
        var to       = new Date(toDate['year'], toDate['month'], toDate['day'], toDate['hours'], toDate['minutes'], 0, 0);
        var duration = (to - from)/1000/60/60;
        var days     = Math.floor(duration/24);
        var hours    = duration % 24;

        var price        = parseInt($("#price").val());
        var totalPrice   = 0;

        if (warrantyType === undefined) {
            if(paymentType == 2){
               totalPrice = price + commission + commissionTbank; 
            } else {
                totalPrice = price + commission;
            }
        } else {
            if (warrantyType == 1) {
                if(paymentType == 2){
                   totalPrice = price + commission + commissionTbank + warrantyPrice 
                } else {
                    totalPrice = price + commission + warrantyPrice;
                }
            } else {
                if (hours >= 6) {
                    if(paymentType == 2){
                        if (days) {
                            totalPrice = price + (days * warrantyPrice) + warrantyPrice + commission + commissionTbank;
                        } else {
                            totalPrice = price + warrantyPrice + commission + commissionTbank;
                        }       
                    } else {
                        if (days) {
                            totalPrice = price + (days * warrantyPrice) + warrantyPrice + commission;
                        } else {
                            totalPrice = price + warrantyPrice + commission;
                        }       
                    }
                         
                } else {
                    if(paymentType == 2){
                        if (days) {
                            totalPrice = price + ((days * warrantyPrice) + ((hours/6) * warrantyPrice)) + commission + commissionTbank;
                        } else {
                            totalPrice = price + ((hours/6) * warrantyPrice) + commission + commissionTbank;
                        }
                    } else {
                        if (days) {
                            totalPrice = price + ((days * warrantyPrice) + ((hours/6) * warrantyPrice)) + commission;
                        } else {
                            totalPrice = price + (hours/6) * warrantyPrice + commission;
                        }
                    }
                    
                }
            }
        }

        $("#total-price").val(totalPrice);
        $(".total-price").html($.number(totalPrice, 0, ',', '.'));
    }


    function translateDay(day) {

        switch (day) {
            case "Mon": return "Lun";
            case "Tue": return "Mar";
            case "Wed": return "Mié";
            case "Thu": return "Jue";
            case "Thur": return "Jue";
            case "Fri": return "Vie";
            case "Sat": return "Sáb";
            case "Sun": return "Dom";
            default: return false;
        }
    }

    function TBankCommissionUpdater(){
        myRadio = $('input[name=payment]');
        pago = myRadio.filter(':checked').val();
        if(parseInt(pago) == 2 ){
            $(".transbank-commission").show();
        } else {
            $(".transbank-commission").hide();
        }
    };
</script>
