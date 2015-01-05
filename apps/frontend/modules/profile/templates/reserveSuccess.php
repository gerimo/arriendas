<link href="/css/newDesign/reserve.css" rel="stylesheet" type="text/css">

<div class="space-30 hidden-xs"></div>
<div class="space-50 visible-xs"></div>

<?php if (is_null($license)): ?>
    <div id="license-container">
        <h1 class="text-center">¡Falta tu licencia de conducir!</h1>
        <h2 class="text-center">Es necesario que la subas para poder realizar arriendos.</h2>
        <button class="btn btn-a-white" id="license-bottom">Seleccionar</button>
    </div>
    <div class="space-60 hidden-xs"></div>
    <div class="space-30 visible-xs"></div>
<?php else: ?>
    <div class="space-60 hidden-xs"></div>
    <div class="space-30 visible-xs"></div>
<?php endif ?>

<div class="container">

    <div class="row">
        <div class="col-md-5">

            <h1 class="hidden-xs reserve-title"><?php echo $Car->getModel()->getBrand()->getName()." ".$Car->getModel()->getName().", <small>".$Car->getYear()."</small>" ?></h1>

            <!-- Carousel -->
            <div id="car-images-carousel">
                <?php //foreach ($Car->getPhotoes() as $Photo): ?>
                    <div><img src="/images/default.png" width="100%"></div>
                <?php //endforeach ?>
                <div><img src="/images/default.png" width="100%"></div>
            </div>
        </div>

        <div class="hidden-xs col-md-3">

            <div class="space-50"></div>
            <h2 class="reserve-subtitle">Características:</h2>

            <ul id="features">
                <?php if ($passengers): ?>
                    <li><span><i class="fa fa-user"></i></span> 5 o más pasajeros</li>
                <?php else: ?>
                    <li><span><i class="fa fa-user"></i></span> 4 pasajeros</li>
                <?php endif ?>

                <?php if ($diesel): ?>
                    <li><span><i class="fa fa-tint"></i></span> Petrolero</li>
                <?php else: ?>
                    <li><span><i class="fa fa-tint"></i></span> Bencinero</li>
                <?php endif ?>

                <?php if ($airCondition): ?>
                    <li><span><i class="fa fa-sun-o"></i></span> Aire acondicionado</li>
                <?php endif ?>

                <?php if ($transmission): ?>
                    <li><span><i class="fa fa-gear"></i></span> Automático</li>
                <?php else: ?>
                    <li><span><i class="fa fa-gear"></i></span> Manual</li>
                <?php endif ?>
            </ul>
        </div>

        <div class="hidden-xs col-md-4">

            <div class="space-50 hidden-xs"></div>

            <span class="saved"><strong>40%</strong> AHORRO</span>

            <div class="reserve-box">

                <span class="title-price"><?php echo '$'.number_format($price, 0, ',', '.') ?></span>

                <ul class="reserve-box-summary">
                    <li> <i class='fa fa-check'></i> <strong>Calidad Arriendas:</strong> el auto se encuentra verificado por Arriendas.</li>
                    <li> <i class='fa fa-check'></i> <strong>Garantía Arriendas:</strong> 2 opciones de reemplazo o te devolvemos el dinero.</li>
                    <li> <i class='fa fa-check'></i> <strong>Ofertas:</strong> recibirás ofertas de otros dueños manteniendo este precio.</li>
                </ul>

                <!-- <a class="btn-a-primary btn-block" href="">Reservar</a> -->
            </div>
        </div>
    </div>

    <div class="visible-xs row">
        <div class="col-xs-12">
            <h1 class="reserve-title"><?php echo $Car->getModel()->getBrand()->getName()." ".$Car->getModel()->getName().", <small>".$Car->getYear()."</small>" ?></h1>
        </div>
    </div>

    <div class="visible-xs space-30"></div>

    <div class="visible-xs row">
        <div class="col-xs-12">
            <span class="title-price" id="price" data-price="<?php echo $price ?>"><?php echo '$'.number_format($price, 0, ',', '.') ?></span>
            <span class="saved"><strong>40%</strong> AHORRO</span>
        </div>
    </div>

    <div class="visible-xs space-30"></div>

    <div class="visible-xs row">
        
        <div class="col-xs-12">

            <h2 class="reserve-subtitle">Características:</h2>

            <div class="row" id="features">
                <?php if ($passengers): ?>
                    <div class="col-xs-6"><i class="fa fa-user"></i> 5 o más pasajeros</div>
                <?php else: ?>
                    <div class="col-xs-6"><i class="fa fa-user"></i> 4 pasajeros</div>
                <?php endif ?>

                <?php if ($diesel): ?>
                    <div class="col-xs-6"><i class="fa fa-tint"></i> Petrolero</div>
                <?php else: ?>
                    <div class="col-xs-6"><i class="fa fa-tint"></i> Bencinero</div>
                <?php endif ?>

                <?php if ($airCondition): ?>
                    <div class="col-xs-6"><i class="fa fa-sun-o"></i> A/C</div>
                <?php endif ?>

                <?php if ($transmission): ?>
                    <div class="col-xs-6"><i class="fa fa-gear"></i> Automático</div>
                <?php else: ?>
                    <div class="col-xs-6"><i class="fa fa-gear"></i> Manual</div>
                <?php endif ?>
            </div>
        </div>
        <div class="col-xs-12">
            <p><strong>Calidad Arriendas:</strong> el auto se encuentra verificado por Arriendas.</p>
            <p><strong>Garantía Arriendas:</strong> 2 opciones de reemplazo o te devolvemos el dinero.</p>
            <p><strong>Ofertas:</strong> recibirás ofertas de otros dueños manteniendo este precio.</p>
        </div>
    </div>

    <div class="space-30"></div>

    <!-- Reviews -->
    <?php if (count($reviews) > 0): ?>

        <div class="panel-group" id="reviews" role="tablist" aria-multiselectable="true">
            <div class="panel panel-default">
                <div class="panel-heading" role="tab" id="headingOne">
                    <a data-toggle="collapse" data-parent="#reviews" href="#reviewsCollapseOne" aria-expanded="true" aria-controls="reviewsCollapseOne">
                        Reviews (<?php echo count($reviews) ?>) 
                        <span id="car-rating"></span>
                    </a>
                </div>
        
                <div id="reviewsCollapseOne" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">
                    <div class="panel-body">
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
                                </div>
                            </div>
                        <?php endforeach ?>
                    </div>
                </div>
            </div>
        </div>
    <?php endif ?>

    <form action="<?php echo url_for('profile/pay') ?>" id="reserve-form" method="post">

        <!-- Resumen y garantía -->
        <div class="row">
            <div class="col-md-offset-2 col-md-8">
                <ul id="fromTo">
                    <li></i>Desde: <?php echo $fromHuman ?></li>
                    <li></i>Hasta: <?php echo $toHuman ?></li>
                </ul>

                <div id="warranty-container">

                        <div id="sub-price-container">
                            <span class="price pull-right" id="subtotal" data-price="<?php echo $price ?>"><?php echo '$'.number_format($price, 0, ',', '.') ?></span>SUB TOTAL
                        </div>
                    
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
                    <span class="pull-right">TOTAL <span class="price" data-price="<?php echo $price ?>" id="total"><?php echo '$'.number_format($price, 0, ',', '.') ?></span></span>
                </div>
            </div>
        </div>

        <!-- Medio de pago -->
        <div class="space-50 hidden-xs"></div>
        <div class="space-20 visible-xs"></div>
        <h1 class="reserve-title">Medio de pago</h1>

        <div class="row" id="payments">
            <div class="col-xs-6 col-sm-4 col-md-3 radio">
                <label>
                    <input id="payment7" name="payment-group" type="radio" value="Banco Falabella">
                    <img src="/images/newDesign/payments/svg_07.svg" alt="">
                </label>
            </div>
            <div class="col-xs-6 col-sm-4 col-md-3 radio">
                <label>
                    <input id="payment8" name="payment-group" type="radio" value="Banco BBVA">
                    <img src="/images/newDesign/payments/svg_08.svg" alt="">
                </label>
            </div>
            <div class="col-xs-6 col-sm-4 col-md-3 radio">
                <label>
                    <input id="payment9" name="payment-group" type="radio" value="Banco BICE">
                    <img src="/images/newDesign/payments/svg_09.svg" alt="">
                </label>
            </div>
            <div class="col-xs-6 col-sm-4 col-md-3 radio">
                <label>
                    <input id="payment10" name="payment-group" type="radio" value="Banco Internacional">
                    <img src="/images/newDesign/payments/svg_10.svg" alt="">
                </label>
            </div>
            <div class="col-xs-6 col-sm-4 col-md-3 radio">
                <label>
                    <input id="payment11" name="payment-group" type="radio" value="Banco Credito e Inversiones">
                    <img src="/images/newDesign/payments/svg_11.svg" alt="">
                </label>
            </div>
            <div class="col-xs-6 col-sm-4 col-md-3 radio">
                <label>
                    <input id="payment12" name="payment-group" type="radio" value="Banco Estado">
                    <img src="/images/newDesign/payments/svg_12.svg" alt="">
                </label>
            </div>
            <div class="col-xs-6 col-sm-4 col-md-3 radio">
                <label>
                    <input id="payment13" name="payment-group" type="radio" value="Banco Scotiabank">
                    <img src="/images/newDesign/payments/svg_13.svg" alt="">
                </label>
            </div>
            <div class="col-xs-6 col-sm-4 col-md-3 radio">
                <label>
                    <input id="payment15" name="payment-group" type="radio" value="Banco Itau">
                    <img src="/images/newDesign/payments/Itau.png" alt="">
                </label>
            </div>
            <div class="col-xs-6 col-sm-4 col-md-3 radio">
                <label>
                    <input id="payment14" name="payment-group" type="radio" value="Banco Corpbanca">
                    <img src="/images/newDesign/payments/svg_14.svg" alt="">
                </label>
            </div>
            <div class="col-xs-6 col-sm-4 col-md-3 radio">
                <label>
                    <input id="payment15" name="payment-group" type="radio" value="Banco de Chile">
                    <img src="/images/newDesign/payments/svg_15.svg" alt="">
                </label>
            </div>
            <div class="col-xs-6 col-sm-4 col-md-3 radio">
                <label>
                    <input id="payment15" name="payment-group" type="radio" value="Banco Santander">
                    <img src="/images/newDesign/payments/Santander.png" alt="">
                </label>
            </div>
        </div>

        <div class="row">
            <div class="col-md-offset-4 col-md-4">
                <button class="btn-block" id="btn-pay" type="button">PAGAR <i class="fa fa-chevron-right"></i></button>
            </div>
        </div>

        <input id="car" name="car" type="hidden" value="<?php echo $Car->getId() ?>">
        <input id="from" name="from" type="hidden" value="<?php echo $from ?>">
        <input id="to" name="to" type="hidden" value="<?php echo $to ?>">
    </form>

    <!-- Alert -->
    <div style="display:none">
        <div id="dialog-alert" title="">
            <p></p>
        </div>

        <?php include_partial('contratosArrendatario') ?>
    </div>
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
        $('#car-rating').raty({
            /*readOnly: true,*/
            score: 3,
            size: 12
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

        var payment = $('input:radio[name=payment-group]:checked').val();

        if (payment === undefined) {

            $("#dialog-alert p").html("Necesitas seleccionar tu medio de pago");
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
</script>