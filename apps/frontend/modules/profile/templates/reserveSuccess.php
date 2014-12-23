<div class="space-100 hidden-xs"></div>

<div class="container">
    <div class="row">
        <div class="col-md-5">

            <p><strong><?php echo $Car->getModel()->getBrand()->getName()." ".$Car->getModel()->getName().", " ?></strong></p>

            <!-- Carousel -->
            <div id="car-images-carousel">
                <?php //foreach ($Car->getPhotoes() as $Photo): ?>
                    <div><img src="/images/default.png" width="100%"></div>
                <?php //endforeach ?>
                <div><img src="/images/default.png" width="100%"></div>
            </div>
        </div>

        <div class="col-md-3">
        </div>

        <div class="col-md-4">

            <div class="space-50 hidden-xs"></div>

            <span class="saved"><strong>40%</strong> AHORRO</span>

            <div class="reserve-box">

                <ul class="LWS">
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

    <h1>Reviews</h1>
    
    <?php foreach ($Ratings as $Rating): ?>
        <div class="row rating-container">
            <div class="col-md-1 text-center">
                <i class="fa fa-user"></i>
            </div>

            <div class="col-md-11">
                <p><?php //echo $Rating->getUserOwnerOpnion() ?></p>
            </div>
        </div>
    <?php endforeach ?>
</div>

<script>
    $(document).ready(function(){
    
        // Carousel
        $('#car-images-carousel').slick({
            dots: true,
            infinite: true
        });    
    });
</script>