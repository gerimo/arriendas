<link href="/css/newDesign/reserve.css" rel="stylesheet" type="text/css">

<div class="space-30 hidden-xs"></div>
<div class="space-50 visible-xs"></div>

<?php if (is_null($license)): ?>
    <div class="row" id="license-container">
        <div class="col-xs-12 col-md-offset-4 col-md-4">
            <h1>¡FALTA TU CÉDULA DE CONDUCIR!</h1>
            <h2>Es necesario que la subas una vez para seguirrealizando arriendos.</h2>
            <a href="#" class="upload-link" id="license-bottom">
                <span class="icon-svg_17"></span>
                <strong class="text">(la puedes sacar con tu celular)</strong>
            </a>
        </div>
    </div>
    <div class="space-60 hidden-xs"></div>
<?php else: ?>
    <div class="space-60 hidden-xs"></div>
<?php endif ?>

<div class="container">

    <div class="col-xs-offset-0 col-xs-12 col-sn-12 col-md-offset-0 col-md-5 carousal-area">
        <h1 class="hidden-xs text-capitalize">
            <?php echo $Car->getModel()->getBrand()->name." ".$Car->getModel()->name ?>, <small><?php echo $Car->year ?></small><br>
            <span><?php echo $Car->getCommune()->name ?></span>
        </h1>
        <div id="carousel-example-generic" class="carousel slide" data-ride="carousel">
            <div class="carousel-inner" role="listbox">
                <div class="item active">
                    <?php
                    $base_url = $sf_request->getUriPrefix().$sf_request->getRelativeUrlRoot();
                    if ($arrayFotos) {
                        if ($opcionFotosEnS3 == 1) {
                            ?>
                            <div id="gallery">
                                <ul>
                                    <?php
                                    $cantidadFotos = count($arrayFotos);
                                    for ($i = 0; $i < $cantidadFotos; $i++) {
                                        ?>
                                        <li>
                                            <a title="<?= $arrayDescripcionFotos[$i]; ?>" href="http://www.arriendas.cl/main/s3thumb?alto=600&ancho=600&urlFoto=<?= $arrayFotos[$i]; ?>">
                                                <img title="<?= $arrayDescripcionFotos[$i]; ?>" src="http://www.arriendas.cl/main/s3thumb?alto=40&ancho=40&urlFoto=<?= $arrayFotos[$i]; ?>" width="40" height="40" alt="<?=$image_alt?>">
                                            </a>
                                        </li>
                                        <?php
                                    }
                                    ?>
                                </ul>
                            </div>
                            <?php
                        } else {
                            ?>

                            <div id="gallery">
                                <ul>
                                    <?php
                                    $cantidadFotos = count($arrayFotos);
                                    for ($i = 0; $i < $cantidadFotos; $i++) {
                                        ?>
                                        <li>
                                            <a title="<?= $arrayDescripcionFotos[$i]; ?>" href="<?= image_path('../uploads/verificaciones/' . $arrayFotos[$i]); ?>">
                                                <img title="<?= $arrayDescripcionFotos[$i]; ?>" src="http://res.cloudinary.com/arriendas-cl/image/fetch/w_40,h_40,c_fill,g_center/<?= $base_url ?>/uploads/verificaciones/thumbs/<?= $arrayFotos[$i]?>" width="40" height="40" alt="<?=$image_alt?>" >
                                            </a>
                                        </li>
                                        <?php
                                    }
                                    ?>
                                </ul>
                            </div>
                            <?php
                        }
                    }
                    ?>
                </div>
            </div>
            <a class="left carousel-control" href="#carousel-example-generic" role="button" data-slide="prev"></a>
            <a class="right carousel-control" href="#carousel-example-generic" role="button" data-slide="next"></a>
        </div>
    </div> 

    <div class="hidden-xs hidden-sm col-md-3 car-sep">

        <!-- Se quitó disponibilidad y ubucación
        <div class="availability">
            <h2>Disponibilidad:</h2>
            <span class="timing">L - V: Completa<br>S - D: Completa</span>
            <h2>Ubicación:</h2>
            <span class="km-area"><em>A <a href="#">1.5 km</a></em> del Metro</span>
        </div>
        -->
        <div class="space-50 hidden-xs"></div>

        <ul id="features">
            <h2>Características:</h2>
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
                <li><span class="icon-svg"></span> Automático</li>
            <?php else: ?>
                <li><span class="icon-svg"></span> Manual</li>
            <?php endif ?>
        </ul>
    </div>

    <div class="hidden-xs col-sm-12 col-md-4 reserve-area">
        <div class="block">
            <div class="saved">
                <strong>40%</strong>AHORRO
            </div>
            <div class="price-area">
                <?php if ($Car->getPricePerDay() != 0):?>
                   <span class="price">$<?php echo number_format(round($Car->getPricePerDay()), 0, '', '.')?> <span>/ día</span></span>
                <?php endif ?>
                <?php if ($Car->getPricePerWeek() != 0):?>
                   <span class="text"><span><?php echo number_format(round(($Car->getPricePerWeek()/7)), 0, '', '.') ?></span> / día la SEMANA</span>
                <?php endif ?>
                <?php if ($Car->getPricePerMonth() != 0):?>
                    <span class="text"><span><?php echo number_format(round(($Car->getPricePerMonth()/30)), 0, '', '.') ?></span> / día el MES</span>
                <?php endif ?> 
            </div>
                <ul class="list list-unstyled">
                    <li><span class="icon-svg_16"></span>Seguro</li>
                    <li><span class="icon-svg_16"></span>Tags</li>
                    <li><span class="icon-svg_16"></span>Conductor Adicional</li>
                    <li><span class="icon-svg_16"></span>Kilometraje Libre</li>
                    <li><span class="icon-svg_16"></span>Asistencia en ruta 24/7</li>
                </ul>
                <a href="#" class="reservar btn btn-warning text-uppercase" data-target=".duracion">RESERVAR</a>                
            <div class="space-20"></div>
        </div>
    </div>  

    <div class="mobile visible-xs">
        <h1 class="text-capitalize"><?php echo $Car->getModel()->getBrand()->getName()." ".$Car->getModel()->getName().", <span>".$Car->getCommune().","."</span>"." "."<span>".$Car->getYear()."</span>" ?></h1>
        <div class="row">
            <div class="auto col-xs-6"> 
                <div>
                    <h2>Características:</h2>
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
                        <li><span class="icon-svg"></span> Automático</li>
                    <?php else: ?>
                        <li><span class="icon-svg"></span> Manual</li>
                    <?php endif ?>
                </div>
            </div>
            <div class="col-xs-6">
                <div class="disponibilidad">
                    <h2>Disponibilidad:</h2>
                    <span class="timing">L - V: Completa<br>S - D: Completa</span>
                    <h2>Ubicación:</h2>
                    <span class="km-area"><em>A <a href="#">1.5 km</a></em> del Metro</span>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12">
                <div>
                    <div class="block-mobile">
                        <div class="saved">
                            <strong>40%</strong>AHORRO
                        </div>
                        <div class="area-precio">
                            <?php if ($Car->getPricePerDay() != 0):?>
                                <span class="text">$<?php echo number_format(round($Car->getPricePerDay()), 0, '', '.')?> <span class="sub">/ día</span></span></br>
                            <?php endif ?>
                            <?php if ($Car->getPricePerWeek() != 0):?>
                                <span class="text"><span>$<?php echo number_format(round(($Car->getPricePerWeek()/7)), 0, '', '.') ?></span> <span class="sub"> / día la SEMANA</span></span></br>
                            <?php endif ?>
                            <?php if ($Car->getPricePerMonth() != 0):?>
                                <span class="text"><span>$<?php echo number_format(round(($Car->getPricePerMonth()/30)), 0, '', '.') ?></span> <span class="sub"> / día el MES</span></span>
                            <?php endif ?> 
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Reviews 
    <?php if (count($reviews) > 0): ?>
        <div class="row">
            <div class="panel-group col-md-offset-1 col-md-10" id="reviews">
                <div class="panel-heading">
                    <h1>Reviews</h1>
                </div>
        
                <div id="reviewsCollapseOne" class="panel-collapse col-md-offset-1 col-md-11" >
                    <div class="border">
                        <?php count($reviews) ?> 
                        <?php foreach ($reviews as $review): ?>
                            <div class="row review">
                                <div class="col-md-1">
                                    <?php if ($review['picture']): ?>
                                        <img src="<?php echo $review['picture'] ?>">
                                    <?php else: ?>
                                        <i class="fa fa-user" style="font-size: 38px"></i>
                                    <?php endif ?>
                                </div>

                                <div class="col-md-11">
                                    <p><?php echo ucfirst($review['opinion']) ?></p>
                                    <span class="car-rating pull-right" data-number="<?php echo $review['star'] ?>" ></span>
                                </div>
                            </div>
                        <?php endforeach ?>
                    </div>
                </div>
            </div>
        </div>
    <?php endif ?>
    -->

    <div class="space-100 hidden-xs"></div>

    <div class="col-sm-12 col-xs-12 content">
        <h1 class="text-uppercase mob-none text-center">¡ARRIENDA UN AUTO EN 3 PASOS!</h1>
        <ul class="step-list list-unstyled">
            <li class="gray clearfix">
                <span class="num">1</span>
                <div class="info">
                    <div>
                    <h2><span class="dis">1.- </span>GARANTÍA ARRIENDAS</h2>
                    <ul class="list list-unstyled">
                        <li><span class="icon-svg_16"></span>Calidad Arriendas: el auto se encuentra verificado por Arriendas.</li>
                        <li><span class="icon-svg_16"></span>Garantía Arriendas: 2 opciones de reemplazo o te devolvemos el dinero.</li>
                        <li><span class="icon-svg_16"></span>Ofertas: Recibirás ofertas de otros dueños manteniendo este precio.</li>
                    </ul>
                    </div>
                </div>
            </li>
        </ul>
    </div>

    <form action="<?php echo url_for('reserve_pay') ?>" id="reserve-form" method="post">

        <!-- Resumen y garantía -->
        <div class="row">
            <div class="step-list col-md-offset-1 col-md-10">
                <span class="num duracion">2</span>   
                <h2><span class="dis">2.- </span>DURACION Y PRECIO</h2>
                <span class="text">Arriendo de Auto <?php echo $Car->getModel()->getBrand()->getName()." ".$Car->getModel()->getName() ?> a <?php echo number_format(round($Car->getPricePerDay()), 0, '', '.') ?> el Día por 48 horas en <?php echo $Car->getCommune();?>, Santiago.</span>
                <input class="datetimepicker btn-block" id="fromH" type="button" value="Desde: <?php echo $fromHuman ?>"></input>
                <input class="datetimepicker btn-block" id="toH" type="button" value="Hasta: <?php echo $toHuman ?>"></input>

                <div id="warranty-container">

                        <div id="sub-price-container">
                            <span class="price pull-right" id="subtotal" data-price="<?php echo $price ?>"><?php echo '$'.number_format($price, 0, ',', '.') ?></span>SUB TOTAL
                        </div>
                        <div class="price-count">
                        <div class="radio">
                            <label>
                                <input data-price="<?php echo $amountWarranty ?>" name="warranty" type="radio" value="1">
                                $180.000 Depósito en Garantía (se devuelve al finalizar el Arriendo)
                            </label>
                        </div>
                        <?php if (!$isDebtor): ?>
                            <div class="radio">
                                <label>
                                    <input data-price="<?php echo $amountWarrantyFree ?>" name="warranty" type="radio" value="0">
                                    $8.700 por Eliminar el Depósito en Garantía
                                </label>
                            </div>
                        <?php endif ?>
                        </div>
                    <span class="pull-right">TOTAL <span class="price" data-price="<?php echo $price ?>" id="total"><?php echo '$'.number_format($price, 0, ',', '.') ?></span></span>
                </div>
            </div>
        </div>

        <!-- Medio de pago -->
        <div class="space-50 hidden-xs"></div>
        <div class="space-20 visible-xs"></div>

        <div class="row" id="payments">
            <div class="col-xs-12 col-md-offset-1 col-md-11">
                <span class="nums">3</span>
                <h1 class="tres"><span class="dis">3.- </span>MEDIO DE PAGO</h1>

                <div class="hidden-xs space-30"></div>
                <div class="visible-xs space-50"></div>

                <div class="row">
                    <div class="col-lg-4 col-sm-6 col-xs-4">
                        <label>
                            <img class="img-responsive" src="/images/newDesign/payments/svg_07.svg" alt="">
                        </label>
                    </div>

                    <div class="col-lg-4 col-sm-6 col-xs-4">
                        <label>
                            <div class="visible-sm space-40"></div>
                            <img class="img-responsive" src="/images/newDesign/payments/svg_08.svg" alt="">
                        </label>
                    </div>
                    <div class="col-lg-4 col-sm-6 col-xs-4">
                        <label>
                            <img class="img-responsive" src="/images/newDesign/payments/svg_09.svg" alt="">
                        </label>
                    </div>
                </div>

                <div class="hidden-xs space-50"></div>

                <div class="row">
                    <div class="col-lg-4 col-sm-6 col-xs-4">
                        <label>
                            <div class="visible-xs space-30"></div>
                            <img class="img-responsive" src="/images/newDesign/payments/svg_10.svg" alt="">
                        </label>
                    </div> 

                    <div class="col-lg-4 col-sm-6 col-xs-4">
                        <label>
                            <div class="visible-xs space-20"></div>
                            <img class="img-responsive" src="/images/newDesign/payments/svg_11.svg" alt="">
                        </label>
                    </div>

                    <div class="col-lg-4 col-sm-6 col-xs-4">
                        <label>
                            <div class="visible-xs space-40"></div>
                            <img class="img-responsive" src="/images/newDesign/payments/svg_12.svg" alt="">
                        </label>
                    </div>
                </div>

                <div class="hidden-xs space-50"></div>

                <div class="row">
                    <div class="col-lg-4 col-sm-6 col-xs-4">
                        <label>
                            <div class="visible-xs space-30"></div>
                            <div class="visible-sm space-50"></div>
                            <img class="img-responsive" src="/images/newDesign/payments/svg_13.svg" alt="">
                        </label>
                    </div>
                        
                    <div class="col-lg-4 col-sm-6 col-xs-4">
                        <label>
                            <div class="visible-xs space-30"></div>
                            <img class="img-responsive" src="/images/newDesign/payments/svg_14.svg" alt="">
                        </label>
                    </div>

                    <div class="col-lg-4 col-sm-6 col-xs-4">
                        <label>
                            <div class="visible-xs space-30"></div>
                            <div class="visible-sm space-50"></div>
                            <img class="img-responsive" src="/images/newDesign/payments/svg_15.svg" alt="">
                        </label>
                    </div>
                </div>

                <div class="hidden-xs space-50"></div>

                <div class="row">
                    <div class="col-lg-4 col-sm-6 col-xs-4">
                        <label>
                            <div class="visible-xs space-30"></div>
                            <img class="img-responsive" src="/images/newDesign/payments/Santander.png" alt="">
                        </label>
                    </div>
                </div>
    
            </div>    
        </div>
        <div class="space-100 hidden-xs"></div>
        <div class="row">
            <div class="col-sm-offset-0  col-md-offset-7 ">
               <span class=" pull-right secure-payment"><span class="icon-svg_21"></span> Pago seguro</span> 
            </div>
            <div class="col-md-3">
                <?php if (!$User->getBlocked()): ?>
                    <button class="btn-block" id="btn-pay" type="button">PAGAR <i class="fa fa-chevron-right"></i></button>
                <?php endif ?>
            </div>
        </div>

        <input id="car" name="car" type="hidden" value="<?php echo $Car->getId() ?>">
        <input id="from" name="from" type="hidden" value="<?php echo $from ?>">
        <input id="to" name="to" type="hidden" value="<?php echo $to ?>">
    </form>
</div>

<!-- Alert -->
<div style="display:none">
    <div id="dialog-alert" title="">
        <p></p>
    </div>

    <?php include_partial('contratosArrendatario') ?>
</div>

<div style="display:none">
    <form action="<?php echo url_for('main/uploadLicense?photo=license&width=194&height=204&file=license-file') ?>" enctype="multipart/form-data" id="license-form" method="post">
        <input id="license-file" name="license-file" type="file">
        <input type="submit">
    </form>
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
            score: function() {
                return $(this).attr('data-number');
            }
        });

        $('input[type=radio][name=warranty]').change(function() {

            var subtotal     = $("#subtotal").data("price");
            var warrantyType = $(this).val();
            var price        = $(this).data("price");
            var total        = 0;
            
            var from     = new Date($("#from").val());
            var to       = new Date($("#to").val());
            var duration = (to - from)/1000/60/60
            var days     = Math.floor(duration/24);
            var hours    = duration % 24;

            if (warrantyType == 1) {
                total = subtotal + price;
            } else {
                if (hours >= 6) {
                    if (days) {
                        total = subtotal + (days * price) + price;
                    } else {
                        total = subtotal + price;
                    }            
                } else {
                    if (days) {
                        total = subtotal + (days * price) + ((hours/6) * price);
                    } else {
                        total = subtotal + (hours/6) * price;
                    }
                }
            }

            $("#total").data("price", total);
            $("#total").html("$"+$.number(total, 0, ',', '.'));
        });

        $("#btn-pay").click(function(){

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

        $("#license-bottom").click(function(e){
            $("#license-file").click();
        });

        $("#license-file").change(function(e) {

            $("#license-form").ajaxForm({
                dataType: "json",
                success: function(r) {
                    
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
                        $("#license-container").remove();
                    }
                }
            }).submit();
        });
    });

    function isValidForm() {

        var warranty = $('input:radio[name=warranty]:checked').val();

        if (warranty === undefined) {

            $("#dialog-alert p").html("Necesitas definir el tipo de garatía");
            $("#dialog-alert").attr("title", "¡Alerta!");
            $("#dialog-alert").dialog({
                buttons: [{
                    text: "Aceptar",
                    click: function() {
                        $( this ).dialog( "close" );
                    }
                }]
            });

            return false;
        }

        return true;
    }

    $(".reservar").on('click', function(e) {
        var target  = $(this).data('target');
        e.preventDefault();
        var position = $(target).offset().top - 50;
        $('html, body').animate({
            scrollTop: position
        }, 1250);
    });

    $("#fromH").datetimepicker({ 
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
        minTime: "<?php echo date('H:i') ?>",
        i18n:{
            es:{
                months:[
                'Enero','Febrero','Marzo','Abril',
                'Mayo','Junio','Julio','Agosto',
                'Septiembre','Octubre','Noviembre','Diciembre'
                ],
                daysShort: ["Dom", "Lun", "Mar", "Mie", "Jue", "Vie", "Sab", "Dom"],
                dayOfWeek:["Dom", "Lun", "Mar", "Mie", "Jue", "Vie", "Sab"]
            }
        },
        lang:'es',
        format:'D d/m/Y H:i',
        timepicker: true,
        validateOnBlur: false,
        defaultDate: ' ',
        onSelectDate: function() {
            refreshDate(true);
        },

        onSelectTime: function() {
            refreshDate(true);
        }
    });

    $("#toH").datetimepicker({ 
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
                dayOfWeek:["Dom", "Lun", "Mar", "Mie", "Jue", "Vie", "Sab"]
            }
        },
        format:'D d/m/Y H:i',
        timepicker: true,
        validateOnBlur: false,
        defaultDate: ' ',
        onSelectDate: function() {
            refreshDate(false);
        },

        onSelectTime: function() {
            refreshDate(false);
        }
    });

    function getRentalPrice() {

        var carId = $("#car").val();
        var from  = $("#from").val();
        var to    = $("#to").val();

        $.post("<?php echo url_for('reserves/calculatePrice') ?>", {"carId": carId, "from": from, "to": to}, function(r){
            console.error(r);
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
                $("#subtotal").html("$"+$.number(r.price, 0, ',', '.'));
                $("#subtotal").data('price', r.price);
            }

        }, 'json');
    }

    function formatoFecha(fecha){
        
        var split = fecha.split(" ");
        var f = split[1];
        var h = split[2];

        var split2 = f.split("/");
        var dia = split2[0];
        var mes = split2[1]-1;
        var ano = split2[2];

        var split3 = h.split(":");
        var hora = split3[0];
        var min  = split3[1];

        var date = new Date(ano, mes, dia, hora, min, 0, 0);
        return date;
    }

    function refreshDate(isFrom) {

        if (isFrom) {
            var desde = "Desde: ";
            var fromh = $("#fromH").val();
            var nuevo = desde + fromh;
            $("#fromH").val(nuevo);
            $("#from").val(formatoFecha(fromh).format("Y-m-d H:i"));
        } else {            
            var hasta = "Hasta: ";
            var toh = $("#toH").val();
            var nuevo = hasta + toh;
            $("#toH").val(nuevo);
            $("#to").val(formatoFecha(toh).format("Y-m-d H:i"));
        }

        getRentalPrice();
    }

    function setDefaultDateH(){
        $("#toH").val('<?php echo $toHuman ?>');
        $("#fromH").val('<?php echo $fromHuman ?>');
    }
</script>