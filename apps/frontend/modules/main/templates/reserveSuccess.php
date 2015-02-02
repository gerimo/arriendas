<link href="/css/newDesign/reserve.css" rel="stylesheet" type="text/css">

<div class="space-30 hidden-xs"></div>
<div class="space-50 visible-xs"></div>

<?php if (is_null($license)): ?>
    <div class="row" id="license-container">
        <div class="col-xs-12 col-md-offset-4 col-md-4">
            <h1>¡FALTA TU CÉDULA DE CONDUCIR!</h1>
            <h2>Es necesario que la subas una vez para seguirrealizando arriendos.</h2>
            <div id="previewlicence">
            <a href="#" class="upload-link" id="linklicence">
                <span class="icon-svg_17"></span>
                <strong class="text">(la puedes sacar con tu celular)</strong>
            </a>
            </div>
        </div>
    </div>
<?php endif ?>

<div class="space-60 hidden-xs"></div>

<div class="container">

    <h1 class="hidden-xs text-capitalize reserve-title">
        <?php echo $Car->getModel()->getBrand()->name." ".$Car->getModel()->name.", <span>".$Car->year.", ".$Car->getCommune()->name."</span>" ?>
    </h1>

    <div class="col-md-5 carousal-area">
        <div id="carousel-example-generic" class="carousel slide" data-ride="carousel">
            <div class="carousel-inner" role="listbox">
                <?php if ($arrayFotos): ?>                        
                    <?php for ($i = 0; $i < count($arrayFotos); $i++): ?>
                        <div class="item <?php if ($i == 0) echo 'active' ?>">
                        <?php if (strpos($arrayFotos[$i], "cars")): ?>
                            <?php echo image_tag($arrayFotos[$i]) ?>
                        <?php else: ?>
                            <img src="http://res.cloudinary.com/arriendas-cl/image/fetch/c_fill,g_center/http://www.arriendas.cl/uploads/verificaciones/<?= $arrayFotos[$i]?>" >
                        <?php endif ?>
                        </div>
                    <?php endfor ?>
                <?php endif ?>
            </div>
            <a class="left carousel-control" href="#carousel-example-generic" role="button" data-slide="prev"></a>
            <a class="right carousel-control" href="#carousel-example-generic" role="button" data-slide="next"></a>
        </div>
    </div> 

    

    <div class="hidden-xs hidden-sm row">
        <div class="col-md-3 car-sep">
            <h2>Características:</h2>
            <ul id="features">
                <?php if ($passengers): ?>
                    <li><span class="icon-svg_01"></span> 5 o más pasajeros</li>
                <?php else: ?>
                    <li><span class="icon-svg_01"></span> 4 pasajeros</li>
                <?php endif ?>

                <?php if ($diesel): ?>
                    <li><span><i class="fa fa-tint"></i></span> Petrolero</li>
                <?php else: ?>
                    <li><span><i class="fa fa-tint"></i></span> Bencinero</li>
                <?php endif ?>

                <?php if ($airCondition): ?>
                    <li><span class="icon-svg_03"></span> Aire acondicionado</li>
                <?php endif ?>

                <?php if ($transmission): ?>
                    <li><span><i class="fa fa-cog"></i></span> Automático</li>
                <?php else: ?>
                    <li><span><i class="fa fa-cog"></i></span> Mecánico</li>
                <?php endif ?>

                <li><img class="metro" src='/images/newDesign/ico.png' alt='metro'> A <b><?php echo round($Car->getNearestMetro()->distance, 1) ?> km</b> del Metro <?php echo $Car->getNearestMetro()->getMetro()->name ?></li>
            </ul>
        </div>

        <div class="col-md-4 reserve-area">
            <div class="block">
                <div class="saved">
                    <strong>40%</strong>AHORRO
                </div>
                <div class="price-area">
                    <?php if ($Car->getPricePerHour() != 0):?>
                        <span class="text">$<?php echo number_format(round($Car->getPricePerHour()), 0, '', '.')?> / <small>HORA</small></span>
                    <?php endif ?>
                    <?php if ($Car->getPricePerDay() != 0):?>
                        <span class="text"><strong>$<?php echo number_format(round($Car->getPricePerDay()), 0, '', '.')?> / <small>DÍA</small></strong></span>
                    <?php endif ?>                    
                    <?php if ($Car->getPricePerWeek() != 0):?>
                        <span class="text">$<?php echo number_format(round(($Car->getPricePerWeek()/7)), 0, '', '.') ?> / <small>DÍA SEMANA</small></span>
                    <?php endif ?>
                    <?php if ($Car->getPricePerMonth() != 0):?>
                        <span class="text">$<?php echo number_format(round(($Car->getPricePerMonth()/30)), 0, '', '.') ?> / <small>DÍA MES</small></span>
                    <?php endif ?> 
                </div>
                    <ul class="list list-unstyled">
                        <li><span class="icon-svg_16"></span>Seguro</li>
                        <li><span class="icon-svg_16"></span>Tags</li>
                        <li><span class="icon-svg_16"></span>Conductor Adicional</li>
                        <li><span class="icon-svg_16"></span>Kilometraje Libre</li>
                        <li><span class="icon-svg_16"></span>Asistencia en ruta 24/7</li>
                    </ul>
                    <a href="#" class="reserve btn btn-warning text-uppercase" data-target="#reserve">RESERVAR</a>                
                <div class="space-20"></div>
            </div>
        </div>
    </div>

    <div class="visible-xs">

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

        <div class="space-20 visible-xs"></div>
        
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
    </div>

    <div class="space-50 hidden-xs"></div>

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

    <div class="space-50 hidden-xs"></div>

    <!-- Reviews -->  

    <?php if (count($reviews) > 0): ?>
        <div class="row">
            <div class="panel-group col-md-11" id="reviews">

                <div class="panel-heading">
                    <h2>Reviews <span class="car-rating pull-right" data-number="<?php echo $average ?>" ></span> <small>(<?php echo $quantity?>)</small></h2>
                </div>

                <div class="space-30 hidden-xs"></div>
                <div id="reviewsCollapseOne" class="panel-collapse col-md-offset-1 col-md-11" >
                    <div class="border">
                        <?php count($reviews) ?> 

                        <div class="row review">
                            <div class="col-md-1">
                                <?php if ( getimagesize($defaultReviews['picture'])): ?>
                                    <img class="img-responsive" src="<?php echo $defaultReviews['picture'] ?>">
                                <?php else: ?>
                                    <i class="fa fa-user" style="font-size: 38px"></i>
                                <?php endif ?>
                            </div>
                            <div class="col-md-11">
                                <p><?php echo ucfirst($defaultReviews['opinion']) ?></p>
                                <span class="car-rating pull-right" data-number="<?php echo $defaultReviews['star'] ?>" ></span>
                                <i class="pull-right"><?php echo $defaultReviews['date'] ?></i>
                            </div>
                        </div>

                        <div class="collapse" id="collapseReviews">

                            <?php foreach ($reviews as $review): ?>
                                <div class="row review">
                                    <div class="col-md-1">
                                        <?php if (getimagesize($review['picture'])): ?>
                                            <img src="<?php echo $review['picture'] ?>">
                                        <?php else: ?>
                                            <i class="fa fa-user" style="font-size: 38px"></i>
                                        <?php endif ?>
                                    </div>
                                    <div class="col-md-11">
                                        <p><?php echo ucfirst($review['opinion']) ?></p>
                                        <span class="car-rating pull-right" data-number="<?php echo $review['star'] ?>" ></span>
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
    
    <div class="space-50 hidden-xs"></div>

    <form action="<?php echo url_for('reserve_pay') ?>" id="reserve-form" method="post">
        
        <h1 class="body-title" id="reserve">Arriendo de <?php echo $Car->getModel()->getBrand()->name ?> <?php echo $Car->getModel()->name ?> a $<span class="price"><?php echo number_format($price, 0, ',', '.') ?></span> en <?php echo $Car->getCommune()->name ?></h1>
        
        <div class="body row">
            <div class="col-sm-12 col-xs-12 col-md-offset-1 col-md-10">

                <!-- Duración -->
                <h2><span class="num">1</span> DURACIÓN</h2>
                <input class="datetimepicker btn-block" id="fromH" type="button" value="Desde: <?php echo $fromHuman ?>"></input>
                <input class="datetimepicker btn-block" id="toH" type="button" value="Hasta: <?php echo $toHuman ?>"></input>

                <div class="space-50 hidden-xs"></div>

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
                        <?php if (!$isDebtor): ?>
                            <div class="radio">
                                <label>
                                    <input data-value="<?php echo $amountWarrantyFree ?>" name="warranty" type="radio" value="0">
                                    $<?php echo number_format($amountWarrantyFree, 0, ',', '.') ?> por Eliminar el Depósito en Garantía <small>(por día de arriendo)</small>
                                </label>
                            </div>
                        <?php endif ?>
                    </div>
                    <span class="pull-right total-box">TOTAL <strong>$<span class="total-price"><?php echo number_format($price, 0, ',', '.') ?></span></strong></span>
                </div>

                <div class="space-30 hidden-xs"></div>

                <!-- Medio de pago -->
                <h2><span class="num">3</span> MEDIO DE PAGO</h2>
                <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
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
                                <p>Paga a través de una Transferencia Bancaria, mediante Khipu. Podrás hacerlo a través de los diversos bancos nacionales.</p>
                                <div class="hidden-xs row">
                                    <div class="col-md-2" style="height: 80px"><img class="img-responsive" src="/images/newDesign/payments/svg_07.svg" alt=""></div>
                                    <div class="col-md-2" style="height: 80px"><img class="img-responsive" src="/images/newDesign/payments/svg_08.svg" alt=""></div>
                                    <div class="col-md-2" style="height: 80px"><img class="img-responsive" src="/images/newDesign/payments/svg_09.svg" alt=""></div>
                                    <div class="col-md-2" style="height: 80px"><img class="img-responsive" src="/images/newDesign/payments/svg_10.svg" alt=""></div>
                                    <div class="col-md-2" style="height: 80px"><img class="img-responsive" src="/images/newDesign/payments/svg_11.svg" alt=""></div>
                                    <div class="col-md-2" style="height: 80px"><img class="img-responsive" src="/images/newDesign/payments/svg_12.svg" alt=""></div>
                                    <div class="col-md-2" style="height: 80px"><img class="img-responsive" src="/images/newDesign/payments/svg_13.svg" alt=""></div>
                                    <div class="col-md-2" style="height: 80px"><img class="img-responsive" src="/images/newDesign/payments/svg_14.svg" alt=""></div>
                                    <div class="col-md-2" style="height: 80px"><img class="img-responsive" src="/images/newDesign/payments/svg_15.svg" alt=""></div>
                                    <div class="col-md-2" style="height: 80px"><img class="img-responsive" src="/images/newDesign/payments/Santander.png" alt=""></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Botón de pago -->
        <div class="space-100 hidden-xs"></div>
        <div class="row">
            <div class="col-md-offset-4 col-md-4">
                <?php if (!$User->getBlocked()): ?>
                    <button class="btn-block" id="btn-pay" type="button">PAGAR</button>
                    <p class="secure-payment"><span class="icon-svg_21"></span> Pago seguro</p>
                <?php endif ?>
            </div>
        </div>
        <div class="space-100 hidden-xs"></div>

        <!-- FORMULARIO -->
        <input id="car" name="car" type="hidden" value="<?php echo $Car->getId() ?>">
        <input id="from" name="from" type="hidden" value="<?php echo $from ?>">
        <input id="to" name="to" type="hidden" value="<?php echo $to ?>">        
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

    <form action="<?php echo url_for('main/uploadLicense?photo=licence&width=194&height=204&file=filelicence') ?>" enctype="multipart/form-data" id="formlicence" method="post">
        <input id="filelicence" name="filelicence" type="file">
        <input type="submit">
    </form>
</div>

<script>

    $(document).ready(function(){

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
                                $("#reserve-form").submit();
                            }
                        }
                    }
                ]
            });
        }
    });    

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
        refreshTotalPrice();
    });

    $(".payment").on('click', function(e) {

        $("input[name='payment']").prop("checked", false);

        $(this).find("input").prop("checked", true);
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

        var warrantyType  = $('input[type=radio][name=warranty]:checked').val();
        var warrantyPrice = parseInt($('input[type=radio][name=warranty]:checked').data("value"));

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
            totalPrice = price;
        } else {
            if (warrantyType == 1) {
                totalPrice = price + warrantyPrice;
            } else {
                if (hours >= 6) {
                    if (days) {
                        totalPrice = price + (days * warrantyPrice) + warrantyPrice;
                    } else {
                        totalPrice = price + warrantyPrice;
                    }            
                } else {
                    if (days) {
                        totalPrice = price + (days * warrantyPrice) + ((hours/6) * warrantyPrice);
                    } else {
                        totalPrice = price + (hours/6) * warrantyPrice;
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
</script>
