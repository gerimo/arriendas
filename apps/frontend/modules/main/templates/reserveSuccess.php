<link href="/css/newDesign/reserve.css?v=1" rel="stylesheet" type="text/css">

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

<div class="space-90 hidden-xs"></div>
<div class="space-50 visible-xs"></div>

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
                            <img alt='rent a car "<?php echo $Car->getModel()->getBrand()->name." ".$Car->getModel()->name?>"' src="http://res.cloudinary.com/arriendas-cl/image/fetch/c_fill,g_center/http://www.arriendas.cl/uploads/verificaciones/<?= $arrayFotos[$i]?>" >
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
    <!-- 
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
    -->
    
    <div class="space-50 hidden-xs"></div>

    <form action="<?php echo url_for('reserve_pay') ?>" id="reserve-form" method="post">
        
        <h1 class="body-title" id="reserve">Arriendo de <?php echo $Car->getModel()->getBrand()->name ?> <?php echo $Car->getModel()->name ?> a $<span class="price"><?php echo number_format($price, 0, ',', '.') ?></span> en <?php echo $Car->getCommune()->name ?></h1>
        
        <div class="body row">
            <div class="col-sm-12 col-xs-12 col-md-offset-1 col-md-10">

                <!-- Duración -->
                <h2><span class="num">1</span> DURACIÓN</h2>
                <h3>DESDE:</h3>
                <input class="datetimepicker btn-block" id="fromH" type="button"></input>
                <h3>HASTA:</h3>
                <input class="datetimepicker btn-block" id="toH" type="button"></input>

                <?php if (isset($_GET['a'])): ?>
                    <div class="space-20 hidden-xs"></div>
                    <label class="isAirportDelivery"><input id="isAirportDelivery" name="isAirportDelivery" type="checkbox" checked value="1"> <span class="glyphicon glyphicon-plane" aria-hidden="true"></span> Deseo el auto en el aeropuerto</label>
                <?php else: ?>
                    <input id="isAirportDelivery" name="isAirportDelivery" type="hidden" value="0">
                <?php endif ?>

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
                       
                            <div class="radio">
                                <label>
                                    <input data-value="<?php echo $amountWarrantyFree ?>" name="warranty" type="radio" value="0">
                                    $<?php echo number_format($amountWarrantyFree, 0, ',', '.') ?> por Eliminar el Depósito en Garantía <small>(por día de arriendo)</small>
                                </label>
                            </div>
                        
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
                                    <div class="col-md-2 text-center" style="height: 60px"><img class="img-responsive" src="/images/newDesign/payments/banks/banco_estado.png" alt="Banco Estado"></div>
                                    <div class="col-md-2 text-center" style="height: 60px"><img class="img-responsive" src="/images/newDesign/payments/banks/consorcio.png" alt="Consorcio"></div>
                                    <div class="col-md-2 text-center" style="height: 60px"><img class="img-responsive" src="/images/newDesign/payments/banks/banco_chile.png" alt="Banco de Chile"></div>
                                    <div class="col-md-2 text-center" style="height: 60px"><img class="img-responsive" src="/images/newDesign/payments/banks/scotiabank.png" alt="Scotiabank"></div>
                                    <div class="col-md-2 text-center" style="height: 60px"><img class="img-responsive" src="/images/newDesign/payments/banks/bice.png" alt="Bancio Bice"></div>
                                    <div class="col-md-2 text-center" style="height: 60px"><img class="img-responsive" src="/images/newDesign/payments/banks/falabella.png" alt="Banco Falabella"></div>
                                    <div class="col-md-2 text-center" style="height: 60px"><img class="img-responsive" src="/images/newDesign/payments/banks/bbva.png" alt="Banco BBVA"></div>
                                    <div class="col-md-2 text-center" style="height: 60px"><img class="img-responsive" src="/images/newDesign/payments/banks/ripley.png" alt="Banco Ripley"></div>
                                    <div class="col-md-2 text-center" style="height: 60px"><img class="img-responsive" src="/images/newDesign/payments/banks/bcinova.png" alt="BCI Nova"></div>
                                    <div class="col-md-2 text-center" style="height: 60px"><img class="img-responsive" src="/images/newDesign/payments/banks/bci.png" alt="Banco Crédito e Inversiones"></div>
                                    <div class="col-md-2 text-center" style="height: 60px"><img class="img-responsive" src="/images/newDesign/payments/banks/itau.png" alt="Banco Itaú"></div>
                                    <div class="col-md-2 text-center" style="height: 60px"><img class="img-responsive" src="/images/newDesign/payments/banks/tbanc.png" alt="T Bank"></div>
                                    <div class="col-md-2 text-center" style="height: 60px"><img class="img-responsive" src="/images/newDesign/payments/banks/security.png" alt="Banco Security"></div>
                                    <div class="col-md-2 text-center" style="height: 60px"><img class="img-responsive" src="/images/newDesign/payments/banks/paris.png" alt="Banco Pais"></div>
                                    <div class="col-md-2 text-center" style="height: 60px"><img class="img-responsive" src="/images/newDesign/payments/banks/santander.png" alt="Banco Santander"></div>
                                    <div class="col-md-2 text-center" style="height: 60px"><img class="img-responsive" src="/images/newDesign/payments/banks/internacional.png" alt="Banco Internacional"></div>
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
                
                    <button class="btn-block" id="btn-pay" type="button">PAGAR</button>
                    <p class="secure-payment"><span class="icon-svg_21"></span> Pago seguro</p>
              
            </div>
        </div>
        <div class="space-100 hidden-xs"></div>

        <!-- FORMULARIO -->
        <input id="car" name="car" type="hidden" value="<?php echo $Car->getId() ?>">
        <input id="from" name="from" type="hidden">
        <input id="to" name="to" type="hidden">
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
</div>

<script>

    $(document).ready(function(){

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

        initializeDate("from", new Date(<?php echo strtotime($from) * 1000 ?>), true, true);
        initializeDate("to", new Date(<?php echo strtotime($to) * 1000 ?>), true, true);
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
                            }
                        }
                    }
                ]
            });
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

    function confirmAvailabilityOfTheCar() {

        var carId = $("#car").val();
        var from  = $("#from").val();
        var to    = $("#to").val();

        var parameters = {
            "carId": carId,
            "from": from,
            "to": to
        };

        $.post("<?php echo url_for('car_is_available') ?>", parameters, function(r){

            if (!r.isAvailable) {
                $("#dialog-alert p").html("Lo sentimos, pero el auto no se encuentra disponible para las fechas indicadas");
                $("#dialog-alert").attr("title", "El auto ya posee una reserva");
                $("#dialog-alert").dialog({
                    buttons: [{
                        text: "Aceptar",
                        click: function() {
                            $(this).dialog( "close" );
                        }
                    }]
                });
            }
        }, 'json');
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

    function refreshTotalPrice() {

        var warrantyType  = $('input[type=radio][name=warranty]:checked').val();
        var warrantyPrice = parseInt($('input[type=radio][name=warranty]:checked').data("value"));

        var price       = parseInt($("#price").val());
        var totalPrice  = 0;

        if (warrantyType === undefined) {
            totalPrice = price;
            $("#total-price").val(totalPrice);
            $(".total-price").html($.number(totalPrice, 0, ',', '.'));
        } else {

            if (warrantyType == 1) {
                totalPrice = price + warrantyPrice;
                $("#total-price").val(totalPrice);
                $(".total-price").html($.number(totalPrice, 0, ',', '.'));
            } else {

                var from  = $("#from").val();
                var to    = $("#to").val();

                var parameters = {
                    "from": from,
                    "to": to
                };

                $.post("<?php echo url_for('reserve_calculate_amount_warranty_free') ?>", parameters, function(r){
                    console.log(r);
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
                        totalPrice = price + r.amountWarrantyFree;
                        $("#total-price").val(totalPrice);
                        $(".total-price").html($.number(totalPrice, 0, ',', '.'));
                    }
                }, 'json');
            }
        }
    }

    function afterDateRefresh() {
        getRentalPrice();
        confirmAvailabilityOfTheCar();
    }
</script>
<script src="/js/newDesign/dates.js?v=1" type="text/javascript"></script>
