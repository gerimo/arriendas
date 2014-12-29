<div class="space-100 hidden-xs"></div>
<div class="space-50 visible-xs"></div>

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
                    <li><i class="fa fa-user"></i> 5 o más pasajeros</li>
                <?php else: ?>
                    <li><i class="fa fa-user"></i> 4 pasajeros</li>
                <?php endif ?>

                <?php if ($diesel): ?>
                    <li><i class="fa fa-tint"></i> Petrolero</li>
                <?php else: ?>
                    <li><i class="fa fa-tint"></i> Bencinero</li>
                <?php endif ?>

                <?php if ($airCondition): ?>
                    <li><i class="fa fa-sun-o"></i> Aire acondicionado</li>
                <?php endif ?>

                <?php if ($transmission): ?>
                    <li><i class="fa fa-gear"></i> Automático</li>
                <?php else: ?>
                    <li><i class="fa fa-gear"></i> Manual</li>
                <?php endif ?>
            </ul>
        </div>

        <div class="hidden-xs col-md-4">

            <div class="space-50 hidden-xs"></div>

            <span class="saved"><strong>40%</strong> AHORRO</span>

            <div class="reserve-box">

                <span class="title-price"><?php echo '$'.number_format($price, 0, ',', '.') ?></span>

                <ul class="reserve-box-summary">
                    <li><i class='fa fa-check'></i> Seguro</li>
                    <li><i class='fa fa-check'></i> Tags</li>
                    <li><i class='fa fa-check'></i> Conductor Adicional</li>
                    <li><i class='fa fa-check'></i> Kilometraje Libre</li>
                    <li><i class='fa fa-check'></i> Asistencia en ruta 24/7</li>
                </ul>

                <a class="btn-a-primary btn-block" href="">Reservar</a>
            </div>
        </div>
    </div>

    <div class="visible-xs row">
        <div class="col-xs-12">
            <h1 class="visible-xs reserve-title"><?php echo $Car->getModel()->getBrand()->getName()." ".$Car->getModel()->getName().", <small>".$Car->getYear()."</small>" ?></h1>
        </div>
    </div>

    <div class="visible-xs row">
        
        <div class="col-xs-6">
            <h2 class="reserve-subtitle">Características:</h2>

            <ul id="features">
                <?php if ($passengers): ?>
                    <li><i class="fa fa-user"></i> 5 o más pasajeros</li>
                <?php else: ?>
                    <li><i class="fa fa-user"></i> 4 pasajeros</li>
                <?php endif ?>

                <?php if ($diesel): ?>
                    <li><i class="fa fa-tint"></i> Petrolero</li>
                <?php else: ?>
                    <li><i class="fa fa-tint"></i> Bencinero</li>
                <?php endif ?>

                <?php if ($airCondition): ?>
                    <li><i class="fa fa-sun-o"></i> A/C</li>
                <?php endif ?>

                <?php if ($transmission): ?>
                    <li><i class="fa fa-gear"></i> Automático</li>
                <?php else: ?>
                    <li><i class="fa fa-gear"></i> Manual</li>
                <?php endif ?>
            </ul>
        </div>
        <div class="col-xs-6">
            <div class="text-center">
                <span class="price" id="price" data-price="<?php echo $price ?>"><?php echo '$'.number_format($price, 0, ',', '.') ?></span>
            </div>

            <div class="text-center">
                <span class="saved"><strong>40%</strong> AHORRO</span>
            </div>
        </div>
    </div>

    <div class="visible-xs space-30"></div>

    <!-- Reviews -->
    <?php if (count($reviews) > 0): ?>

        <div class="panel-group" id="reviews" role="tablist" aria-multiselectable="true">
            <div class="panel panel-default">
                <div class="panel-heading" role="tab" id="headingOne">
                    <!-- <div class="row">
                        <div class="col-xs-6 col-sm-6 col-md-6"> -->
                            <div id="car-rating"></div>
                            <a data-toggle="collapse" data-parent="#reviews" href="#reviewsCollapseOne" aria-expanded="true" aria-controls="reviewsCollapseOne">
                                <i class="fa fa-eye"></i> Ver Reviews
                            </a>
                        <!-- </div>
                        <div class="col-xs-6 col-sm-6 col-md-6 text-right"> -->
                            
                        <!-- </div>
                    </div> -->
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

    <!-- Resumen y garantía -->
    <div class="row">
        <div class="col-md-offset-2 col-md-8">
            <ul id="fromTo">
                <li><i class="fa fa-plus"></i>Desde: <?php echo $from ?></li>
                <li><i class="fa fa-plus"></i>Hasta: <?php echo $to ?></li>
            </ul>

            <div id="warranty-container">
                <div id="sub-price-container">
                    <span class="price pull-right" data-subtotal-price=""><?php echo '$'.number_format($price, 0, ',', '.') ?></span>SUB TOTAL
                </div>
                
                <form action="#" id="warranty-form">
                    <div class="radio">
                        <label>
                            <input id="radio-1" name="group1" type="radio">
                            $180.000 Depósito en Garantía (se devuelve al finalizar el Arriendo)
                        </label>
                    </div>
                    <div class="radio">
                        <label>
                            <input id="radio-2" name="group1" type="radio">
                            $8.700 por Eliminar el Depósito en Garantía
                        </label>                        
                    </div>
                </form>
                
                <span class="pull-right" id="total">TOTAL <span class="price" data-total-price=""><?php echo '$'.number_format($price, 0, ',', '.') ?></span></span>
            </div>
        </div>
    </div>

    <!-- Medio de pago -->
    <div class="space-50 hidden-xs"></div>
    <div class="space-20 visible-xs"></div>
    <h1 class="reserve-title">Medio de pago</h1>

    <form action="#" id="payments-form">

        <div class="row">
            <div class="col-xs-6 col-sm-4 col-md-3 radio">
                <label>
                    <input id="payment7" name="payment-group" type="radio">
                    <img src="/images/newDesign/payments/svg_07.svg" alt="">
                </label>
            </div>
            <div class="col-xs-6 col-sm-4 col-md-3 radio">
                <label>
                    <input id="payment8" name="payment-group" type="radio">
                    <img src="/images/newDesign/payments/svg_08.svg" alt="">
                </label>
            </div>
            <div class="col-xs-6 col-sm-4 col-md-3 radio">
                <label>
                    <input id="payment9" name="payment-group" type="radio">
                    <img src="/images/newDesign/payments/svg_09.svg" alt="">
                </label>
            </div>
            <div class="col-xs-6 col-sm-4 col-md-3 radio">
                <label>
                    <input id="payment10" name="payment-group" type="radio">
                    <img src="/images/newDesign/payments/svg_10.svg" alt="">
                </label>
            </div>
            <div class="col-xs-6 col-sm-4 col-md-3 radio">
                <label>
                    <input id="payment11" name="payment-group" type="radio">
                    <img src="/images/newDesign/payments/svg_11.svg" alt="">
                </label>
            </div>
            <div class="col-xs-6 col-sm-4 col-md-3 radio">
                <label>
                    <input id="payment12" name="payment-group" type="radio">
                    <img src="/images/newDesign/payments/svg_12.svg" alt="">
                </label>
            </div>
            <div class="col-xs-6 col-sm-4 col-md-3 radio">
                <label>
                    <input id="payment13" name="payment-group" type="radio">
                    <img src="/images/newDesign/payments/svg_13.svg" alt="">
                </label>
            </div>
            <div class="col-xs-6 col-sm-4 col-md-3 radio">
                <label>
                    <input id="payment14" name="payment-group" type="radio">
                    <img src="/images/newDesign/payments/svg_14.svg" alt="">
                </label>
            </div>
            <div class="col-xs-6 col-sm-4 col-md-3 radio">
                <label>
                    <input id="payment15" name="payment-group" type="radio">
                    <img src="/images/newDesign/payments/svg_15.svg" alt="">
                </label>
            </div>
        </div>
    </form>

    <div class="row">
        <div class="col-md-offset-4 col-md-4">
            <button class="btn-block" id="btn-pay" type="button">PAGAR <i class="fa fa-chevron-right"></i></button>
        </div>
    </div>
</div>

<script>
    $(document).ready(function(){
    
        // Carousel
        $('#car-images-carousel').slick({
            dots: true,
            infinite: true
        });

        // Rating
        $('#car-rating').raty({
            /*readOnly: true,*/
            score: 3,
            size: 12
        });
    });
</script>