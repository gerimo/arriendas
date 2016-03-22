<link href="/css/newDesign/searchACars.css?v=2" rel="stylesheet" type="text/css">
<script src="/js/newDesign/dates.js?v=2" type="text/javascript"></script>

<div class="space-100"></div>

<!-- Buscador -->
<div class="row">
    <div class="col-md-12">
        <div class="BCW">

            <h1 class="text-center">¡Arrienda un Auto Ahora!</h1>

            <!-- Formulario de búsqueda -->
            <div class="row ">
                <div class="col-xs-6 col-sm-3 col-md-3" id="region-container">
                    <select class="region form-control" id="region">
                        <?php if ($hasRegion && $Region->id == $hasRegion): ?>
                            <option selected value="<?php echo $Region->id ?>"><?php echo ucwords(strtolower($Region->name)) ?></option> 
                        <?php else: ?>
                            <option disabled selected value="<?php echo $Region->id ?>"><?php echo $Region->name ?></option>
                        <?php endif ?>
                    </select>
                </div>
                <div class="col-xs-6 col-sm-3 col-md-3" id="commune-container">
                    <select class="commune form-control" id="commune">
                        <option value="0">Todas Las Comunas</option>
                        <?php foreach ($Region->getCommunes() as $Commune): ?>
                               <?php if ($hasCommune && $Commune->id == $hasCommune): ?>
                                    <option selected value="<?php echo $Commune->id ?>"><?php echo ucwords(strtolower($Commune->name)) ?></option> 
                                <?php else: ?>
                                    <option value="<?php echo $Commune->id ?>"><?php echo ucwords(strtolower($Commune->name)) ?></option>
                                <?php endif ?>
                        <?php endforeach ?>
                    </select>
                </div>

                <div class="col-xs-6 col-sm-2 col-md-2" id="from-container">
                    <input class="datetimepicker form-control text-left" id="fromH" placeholder="Desde" type="button">
                </div>
                <div class="col-xs-6 col-sm-2 col-md-2" id="to-container">
                    <input class="datetimepicker form-control text-left" id="toH" placeholder="Hasta" type="button">
                </div>

                 <!-- Search -->
                <div class="col-xs-12 col-sm-2 col-md-2 text-center">
                    <button class="btn btn-a-action btn-block" id="search">Buscar</button>
                </div>
            </div>

            <!-- Filtros -->
            <div class="row filters">
                <div class="col-md-12">
                    <strong class="heading">Filtros:</strong>
                    <ul>
                        <li><input type="checkbox" name="filter" class="isAutomatic"> Automático</li>
                        <li><input type="checkbox" name="filter" class="isLowConsumption"> Bajo consumo</li>
                        <li><input type="checkbox" name="filter" class="isMorePassengers"> Más de 5 pasajeros</li>
                        <li><?php if($nearToSubway): ?>
                                <input type="checkbox" checked="checked" name="filter" class="nearToSubway"> Cercano al metro
                            <?php else: ?>
                                <input type="checkbox" name="filter" class="nearToSubway"> Cercano al metro
                            <?php endif; ?>
                        </li>
                        <li><input type="checkbox" name="filter" class="haveChair"> Silla Bebé</li>
                        <li><input type="checkbox" name="filter" class="isAirportDelivery"> Auto en aeropuerto</li>
                    </ul>
                </div>
            </div>

            <!-- Contenedor -->
            <div class="row">
                <div class="row" id="list-container"></div>
                <div id="list-loading" class="loading" style="text-align: center; margin: 4% 0 4% 0"><?php echo image_tag('ajax-loader.gif', array("width" => "80px", "height" => "80px")) ?></div>                
                <button class="see-more btn-block" data-offset="0" data-limit="<?php echo $limit ?>" type="button">Ver más</button>
            </div>

            <!--Mobile-->
            <!-- <section id="section-map">
                <nav class="visible-xs navbar navbar-default" id="filters-navbar" role="navigation">
                    <div class="container">

                        <div class="navbar-filters">
                            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#filters">
                                <span class="sr-only">Toggle navigation</span>
                                <span class="icon-bar"></span>
                                <span class="icon-bar"></span>
                                <span class="icon-bar"></span>
                            </button>
                            <h3 class="filter"style="margin-top: 5px; padding-top: 12px">Filtros:</h3>
                        </div>

                        <div class="collapse navbar-collapse" id="filters">
                            <div class="container">
                                <ul class="nav navbar-nav">
                                    <li><input type="checkbox" name="filter" class="isAutomatic"> Automático</li>
                                    <li><input type="checkbox" name="filter" class="isLowConsumption"> Petrolero</li>
                                    <li><input type="checkbox" name="filrer" class="isMorePassengers"> Más de 5 pasajeros</li>
                                    <li><input type="checkbox" name="filrer" class="nearToSubway"> Cercano al metro (máximo 15 minutos)</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </nav>
                <br><br>
                <div id="section-map-body">
                    <div class="tab-container" id="tab-list">
                        <div class="row" id="list">
                            <div id="list-loading" class="loading" style="text-align: center; margin: 4% 0 4% 0"><?php echo image_tag('ajax-loader.gif', array("width" => "80px", "height" => "80px")) ?></div>
                            <div class="row" id="list-container"></div>
                        </div>
                    </div>
                </div>
            </section> -->         
        </div>
    </div>
</div>

<div class="space-50"></div>

<!-- Anuncios -->
<div class="row">
    <div class="col-md-12">
        <div class="BCW">

            <div class="panel-group-spam" id="accordions" role="tablist" aria-multiselectable="true">
                <div class="panel panel-default hidden-xs">
                    <div class="panel-heading" role="tab" id="spam">
                          <h4 class="panel-title text-center">
                            <a class="collapsed" id="accordionsArrow" data-toggle="collapse" data-parent="#accordions" href="#spamFooter" aria-expanded="true" aria-controls="spamFooter">
                               Arriendo de autos en otras comunas de Santiago<i class="pull-right fa fa-chevron-down"></i>
                            </a>
                          </h4>
                    </div>
                    <div id="spamFooter" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="spamFooter">
                        <div class="panel-body text-center">
                            <a href="<?php echo url_for('rent_a_car_region_commune', array('region' => 'region-metropolitana', 'commune' => 'alhue'), true) ?>">Rent a Car Alhue</a> | <a href="<?php echo url_for('rent_a_car_region_commune', array('region' => 'region-metropolitana', 'commune' => 'buin'), true) ?>">Rent a Car Buin</a> |
                            <a href="<?php echo url_for('rent_a_car_region_commune', array('region' => 'region-metropolitana', 'commune' => 'calera-de-tango'), true) ?>">Rent a Car Calera de Tango</a> | <a href="<?php echo url_for('rent_a_car_region_commune', array('region' => 'region-metropolitana', 'commune' => 'cerrillos'), true) ?>">Rent a Car Cerrillos</a> |   
                            <a href="<?php echo url_for('rent_a_car_region_commune', array('region' => 'region-metropolitana', 'commune' => 'cerro-navia'), true) ?>">Rent a Car Cerro Navia</a> | <a href="<?php echo url_for('rent_a_car_region_commune', array('region' => 'region-metropolitana', 'commune' => 'colina'), true) ?>">Rent a Car Colina</a> |
                            <a href="<?php echo url_for('rent_a_car_region_commune', array('region' => 'region-metropolitana', 'commune' => 'conchali'), true) ?>">Rent a Car Conchalí</a> | 
                            <a href="<?php echo url_for('rent_a_car_region_commune', array('region' => 'region-metropolitana', 'commune' => 'curacavi'), true) ?>">Rent a Car Curacaví</a> | <a href="<?php echo url_for('rent_a_car_region_commune', array('region' => 'region-metropolitana', 'commune' => 'el-bosque'), true) ?>">Rent a Car El Bosque</a> |
                            <a href="<?php echo url_for('rent_a_car_region_commune', array('region' => 'region-metropolitana', 'commune' => 'el-monte'), true) ?>">Rent a Car El Monte</a> | <a href="<?php echo url_for('rent_a_car_region_commune', array('region' => 'region-metropolitana', 'commune' => 'estacion-central'), true) ?>">Rent a Car Estación Central</a> |   
                            <a href="<?php echo url_for('rent_a_car_region_commune', array('region' => 'region-metropolitana', 'commune' => 'huechuraba'), true) ?>">Rent a Car Huechuraba</a> | <a href="<?php echo url_for('rent_a_car_region_commune', array('region' => 'region-metropolitana', 'commune' => 'independencia'), true) ?>">Rent a Car Independencia</a> |
                            <a href="<?php echo url_for('rent_a_car_region_commune', array('region' => 'region-metropolitana', 'commune' => 'isla-de-maipo'), true) ?>">Rent a Car Isla de Maipo</a> | <a href="<?php echo url_for('rent_a_car_region_commune', array('region' => 'region-metropolitana', 'commune' => 'la-cisterna'), true) ?>">Rent a Car La Cisterna</a> |
                            <a href="<?php echo url_for('rent_a_car_region_commune', array('region' => 'region-metropolitana', 'commune' => 'la-florida'), true) ?>">Rent a Car La Florida</a> | <a href="<?php echo url_for('rent_a_car_region_commune', array('region' => 'region-metropolitana', 'commune' => 'la-granja'), true) ?>">Rent a Car La Granja</a> |
                            <a href="<?php echo url_for('rent_a_car_region_commune', array('region' => 'region-metropolitana', 'commune' => 'la-pintana'), true) ?>">Rent a Car La Pintana</a> | <a href="<?php echo url_for('rent_a_car_region_commune', array('region' => 'region-metropolitana', 'commune' => 'las-condes'), true) ?>">Rent a Car Las Condes</a> |   
                            <a href="<?php echo url_for('rent_a_car_region_commune', array('region' => 'region-metropolitana', 'commune' => 'lampa'), true) ?>">Rent a Car Lampa</a> | <a href="<?php echo url_for('rent_a_car_region_commune', array('region' => 'region-metropolitana', 'commune' => 'lo-espejo'), true) ?>">Rent a Car Lo Espejo</a> |
                            <a href="<?php echo url_for('rent_a_car_region_commune', array('region' => 'region-metropolitana', 'commune' => 'lo-barnechea'), true) ?>">Rent a Car Lo Barnechea</a> | <a href="<?php echo url_for('rent_a_car_region_commune', array('region' => 'region-metropolitana', 'commune' => 'macul'), true) ?>">Rent a Car Macul</a> |
                            <a href="<?php echo url_for('rent_a_car_region_commune', array('region' => 'region-metropolitana', 'commune' => 'lo-prado'), true) ?>">Rent a Car Lo Prado</a> | <a href="<?php echo url_for('rent_a_car_region_commune', array('region' => 'region-metropolitana', 'commune' => 'macul'), true) ?>">Rent a Car Macul</a> |
                            <a href="<?php echo url_for('rent_a_car_region_commune', array('region' => 'region-metropolitana', 'commune' => 'maipu'), true) ?>">Rent a Car Maipu</a> | <a href="<?php echo url_for('rent_a_car_region_commune', array('region' => 'region-metropolitana', 'commune' => 'maria-pinto'), true) ?>">Rent a Car María Pinto</a> |   
                            <a href="<?php echo url_for('rent_a_car_region_commune', array('region' => 'region-metropolitana', 'commune' => 'melipilla'), true) ?>">Rent a Car Melipilla</a> | <a href="<?php echo url_for('rent_a_car_region_commune', array('region' => 'region-metropolitana', 'commune' => 'ñuñoa'), true) ?>">Rent a Car Ñuñoa</a> |
                            <a href="<?php echo url_for('rent_a_car_region_commune', array('region' => 'region-metropolitana', 'commune' => 'padre-hurtado'), true) ?>">Rent a Car Padre Hurtado</a> | <a href="<?php echo url_for('rent_a_car_region_commune', array('region' => 'region-metropolitana', 'commune' => 'la-cisterna'), true) ?>">Rent a Car La Cisterna</a> |
                            <a href="<?php echo url_for('rent_a_car_region_commune', array('region' => 'region-metropolitana', 'commune' => 'paine'), true) ?>">Rent a Car Paine</a> | <a href="<?php echo url_for('rent_a_car_region_commune', array('region' => 'region-metropolitana', 'commune' => 'pedro-aguirre-cerda'), true) ?>">Rent a Car Pedro Aguirre Cerda</a> |
                            <a href="<?php echo url_for('rent_a_car_region_commune', array('region' => 'region-metropolitana', 'commune' => 'peñaflor'), true) ?>">Rent a Car Peñaflor</a> | <a href="<?php echo url_for('rent_a_car_region_commune', array('region' => 'region-metropolitana', 'commune' => 'peñalolen'), true) ?>">Rent a Car Peñalolen</a> |   
                            <a href="<?php echo url_for('rent_a_car_region_commune', array('region' => 'region-metropolitana', 'commune' => 'pirque'), true) ?>">Rent a Car Pirque</a> | <a href="<?php echo url_for('rent_a_car_region_commune', array('region' => 'region-metropolitana', 'commune' => 'providencia'), true) ?>">Rent a Car Providencia</a> |
                            <a href="<?php echo url_for('rent_a_car_region_commune', array('region' => 'region-metropolitana', 'commune' => 'pudahuel'), true) ?>">Rent a Car Pudahuel</a> | <a href="<?php echo url_for('rent_a_car_region_commune', array('region' => 'region-metropolitana', 'commune' => 'puente-alto'), true) ?>">Rent a Car Puente Alto</a> |  
                            <a href="<?php echo url_for('rent_a_car_region_commune', array('region' => 'region-metropolitana', 'commune' => 'quilicura'), true) ?>">Rent a Car Quilicura</a> | <a href="<?php echo url_for('rent_a_car_region_commune', array('region' => 'region-metropolitana', 'commune' => 'quinta-normal'), true) ?>">Rent a Car Quinta Normal</a> |
                            <a href="<?php echo url_for('rent_a_car_region_commune', array('region' => 'region-metropolitana', 'commune' => 'recoleta'), true) ?>">Rent a Car Recoleta</a> | <a href="<?php echo url_for('rent_a_car_region_commune', array('region' => 'region-metropolitana', 'commune' => 'renca'), true) ?>">Rent a Car Renca</a> |   
                            <a href="<?php echo url_for('rent_a_car_region_commune', array('region' => 'region-metropolitana', 'commune' => 'san-bernardo'), true) ?>">Rent a Car San Bernardo</a> | <a href="<?php echo url_for('rent_a_car_region_commune', array('region' => 'region-metropolitana', 'commune' => 'san-joaquin'), true) ?>">Rent a Car San Joaquin</a> |
                            <a href="<?php echo url_for('rent_a_car_region_commune', array('region' => 'region-metropolitana', 'commune' => 'san-jose-de-maipo'), true) ?>">Rent a Car San Jose de Maipo</a> | <a href="<?php echo url_for('rent_a_car_region_commune', array('region' => 'region-metropolitana', 'commune' => 'san-miguel'), true) ?>">Rent a Car San Miguel</a> |
                            <a href="<?php echo url_for('rent_a_car_region_commune', array('region' => 'region-metropolitana', 'commune' => 'san-pedro'), true) ?>">Rent a Car San Pedro</a> | <a href="<?php echo url_for('rent_a_car_region_commune', array('region' => 'region-metropolitana', 'commune' => 'san-ramon'), true) ?>">Rent a Car San Ramon</a> |
                            <a href="<?php echo url_for('rent_a_car_region_commune', array('region' => 'region-metropolitana', 'commune' => 'santiago-centro'), true) ?>">Rent a Car Santiago Centro</a> | <a href="<?php echo url_for('rent_a_car_region_commune', array('region' => 'region-metropolitana', 'commune' => 'las-condes'), true) ?>">Rent a Car Las Condes</a> | 
                            <a href="<?php echo url_for('rent_a_car_region_commune', array('region' => 'region-metropolitana', 'commune' => 'til-til'), true) ?>">Rent a Car Til-Til</a> | <a href="<?php echo url_for('rent_a_car_region_commune', array('region' => 'region-metropolitana', 'commune' => 'vitacura'), true) ?>">Rent a Car Vitacura</a>
                        </div>
                    </div>
                </div>
            </div>            
        </div>
    </div>
</div>

<!--Alert-->
<div style="display:none">
    <div id="dialog-alert" title="">
        <p></p>
    </div>
</div>

<input id="from" type="hidden">
<input id="to" type="hidden">

<div class="space-60"></div>

<script>

    var reserveUrl = "<?php echo url_for('reserve', array('carId' => 'carId'), true) ?>";
    var usuarioLogeado = "<?php echo $usuarioLog; ?>";

    $(document).ready(function(){

        $("#search").click(function(){
            searchCars(0, $("button.see-more").data("limit"));
        });

        $("#commune").change(function(){
            searchCars(0, $("button.see-more").data("limit"));
        });

        $("input[type='checkbox']").change(function(){
            searchCars(0, $("button.see-more").data("limit"));
        });

        <?php if (!($hasCommune)): ?>
            $("#commune").focus();
        <?php endif ?>                

        initializeDate("from", new Date(<?php echo strtotime($from) * 1000 ?>), true, false);
        initializeDate("to", new Date(<?php echo strtotime($to) * 1000 ?>), true, false);

        checkCollapse();

        $("#search").click();
    });

    $('#accordions').on('hidden.bs.collapse', function () {
        checkCollapse();
    });

    $('#accordions').on('shown.bs.collapse', function () {
        checkCollapse();
    });

    function checkCollapse() {

        $("a[data-toggle='collapse']").each(function(){

            var html = "";
            var isCollapse = $.parseJSON($(this).attr("aria-expanded"));

            if (isCollapse) {
                html += "Arriendo de autos en otras comunas de Santiago<i class='pull-right fa fa-chevron-up'></i>";
            } else {
                html += "Arriendo de autos en otras comunas de Santiago<i class='pull-right fa fa-chevron-down'></i>";
            }

            $(this).html(html);
        });
    }

    function searchCars(offset, limit) {

        if (offset == 0) {
            $('#list-container').hide();
        }
        $(".loading").show();

        var from      = $("#from").val();
        var to        = $("#to").val();
        var regionId  = parseInt($("#region option:selected").val());
        var communeId = parseInt($("#commune option:selected").val());

        //Filtro busqueda
        var isAutomatic = $(".isAutomatic").is(':checked');
        var isLowConsumption = $(".isLowConsumption").is(':checked');
        var isMorePassengers = $(".isMorePassengers").is(':checked');
        var nearToSubway = $(".nearToSubway").is(':checked');
        var haveChair = $(".haveChair").is(":checked");
        var isAirportDelivery = $(".isAirportDelivery").is(":checked");

        var parameters = {
            offset: offset,
            limit: limit,
            from : from,
            to: to,
            regionId: regionId,
            communeId: communeId,
            isAutomatic: isAutomatic,
            isLowConsumption: isLowConsumption,
            isMorePassengers: isMorePassengers,
            nearToSubway: nearToSubway,
            haveChair: haveChair,
            isAirportDelivery: isAirportDelivery
        }

        $.post("<?php echo url_for('car_search') ?>", parameters, function(r){

            var listContent = "";

            if (r.cars.length > 0) {
                for (var i = 0 ; i < r.cars.length ; i++) {

                    var Car = r.cars[i];

                    var urlFotoTipo      = "<?php echo image_path('../uploads/cars/" + Car.photo + "'); ?>";
                    var urlFotoThumbTipo = "<?php echo image_path('../uploads/cars/" + Car.photo + "'); ?>";
                    
                    if (Car.photoType == 1) {
                        urlFotoTipo = Car.photo;
                        urlFotoThumbTipo = Car.photo;
                    }

                    var str = 0;
                    if (Car.photo) {
                        str = Car.photo.indexOf("cars");
                    }

                    if(str > 0) {
                        urlFotoTipo = Car.photo;
                        urlFotoThumbTipo = Car.photo;
                    }
    
                    article = "<article class='box'>";
                    article += "<div class='row'>";
                    article += "<div class='col-xs-4 col-md-4 image'>";
                    if(Car.photoS3){
                        article += "<img class='img-responsive' src='" + Car.photoS3+ "' height='99' width='134' alt='rent a car "+ Car.brand +" "+ Car.model +"'/>";
                    }else{
                        if(str > 0) {
                            article += "<img class='img-responsive' src='http://www.arriendas.cl" + urlFotoThumbTipo + "' height='99' width='134' alt='rent a car "+ Car.brand +" "+ Car.model +"'/>";
                        }else   {
                            article += "<img class='img-responsive' src='http://res.cloudinary.com/arriendas-cl/image/fetch/w_112,h_84,c_fill,g_center/http://www.arriendas.cl" + urlFotoThumbTipo + "' height='99' width='134' alt='rent a car " + Car.brand +" "+ Car.model +"'/>";
                        }
                    }
                    article += "</div>";
                    article += "<div class='col-xs-8 col-md-8 text'>";
                    article += "<h2>"+ Car.brand +" "+ Car.model +"<small>, "+Car.year+"</small></h2>";
                    article += "<p class='price'>$"+ Car.price +" <small style='color: black; font-weight: 300; font-size: 9px;'>TOTAL</small></p>";
                    article += "<div class='metro'><p><img class='km-area' src='/images/newDesign/ico.png' alt='metro'> A <b><em>"+Car.nearestMetroDistance+"</em> km</b> del Metro "+Car.nearestMetroName+"</p></div>";
                    article += "<p class='text-right'><a class='btn btn-a-action btn-sm' href='"+reserveUrl.replace("carId", Car.id)+"' class='reserve' target='_blank'>VER MÁS</a></p>";
                    article += "</div>";
                    article += "</div>";
                    article += "</article>";

                    listContent += "<div class='col-md-4'>";
                    listContent += article;
                    listContent += "</div>";
                }
            } else {
                if (offset) {
                    listContent += "<div class='col-md-12'><p class='msg-search' style='padding-top: 3%'>Ya se están mostrando todos los autos disponibles para las fechas indicadas</p></div>";
                } else {
                    listContent += "<div class='col-md-12'><p class='msg-search'>No hemos encontrado autos</p></div>";
                }
            }

            if (offset) {
                $("#list-container").append(listContent);
            } else {
                $("#list-container").html(listContent);
            }

            if (r.cars.length) {
                $("button.see-more").data("offset", parseInt(offset)+parseInt(limit));
                
                if (r.cars.length < limit) {
                    /*$("button.see-more").hide();*/ // Al arreglar la query de búsqueda se descomenta esto
                } else {
                    $("button.see-more").show();
                }
            } else {
                $("button.see-more").hide();
            }

            $('.loading').hide();
            if (!$("#list-container").is(":visible")) {
                $("#list-container").show();
            }
        }, "json");
    }

    $(document).on("click", ".see-more", function(){
        searchCars($("button.see-more").data("offset"), $("button.see-more").data("limit"));
    });

    function afterDateRefresh() {
        searchCars(0, $("button.see-more").data("limit"));
    }

    /*

    function roundTime(valor){

        var fechaH = valor;

        var split = fechaH.split(" ");
        var f = split[0];
        var h = split[1];

        var split3 = h.split(":");
        var hora = parseInt(split3[0]);
        var min = parseInt(split3[1]);

        if (min > 14 && min <= 45){
            min = "30";
            $('#to').datetimepicker({
                allowTimes:[
                "00:30", "01:30", "02:30",
                "03:30", "04:30", "05:30",
                "06:30", "07:30", "08:30",
                "09:30", "10:30", "11:30",
                "12:30", "13:30", "14:30",
                "15:30", "16:30", "17:30",
                "18:30", "19:30", "20:30",
                "21:30", "22:30", "23:30",
                ],
                lang:'es',
                dayOfWeekStart: 1,
                minDate:get_date($('#from').val())?get_date($('#from').val()):false,
                format:'d-m-Y H:i'
            });

        } else if (min > 45){
            min = "00";
            hora = (hora+1).toString();
        
            $('#to').datetimepicker({
                allowTimes:[
                "00:00", "01:00", "02:00",
                "03:00", "04:00", "05:00",
                "06:00", "07:00", "08:00",
                "09:00", "10:00", "11:00",
                "12:00", "13:00", "14:00",
                "15:00", "16:00", "17:00",
                "18:00", "19:00", "20:00",
                "21:00", "22:00", "23:00",
                ],
                lang:'es',
                dayOfWeekStart: 1,
                minDate:get_date($('#from').val())?get_date($('#from').val()):false,
                format:'d-m-Y H:i'
            });

        } else {
            min = "00";
            $('#to').datetimepicker({
                allowTimes:[
                "00:00", "01:00", "02:00",
                "03:00", "04:00", "05:00",
                "06:00", "07:00", "08:00",
                "09:00", "10:00", "11:00",
                "12:00", "13:00", "14:00",
                "15:00", "16:00", "17:00",
                "18:00", "19:00", "20:00",
                "21:00", "22:00", "23:00",
                ],
                lang:'es',
                dayOfWeekStart: 1,
                minDate:get_date($('#from').val())?get_date($('#from').val()):false,
                format:'d-m-Y H:i'
            });
        }

        fecha = f+" "+hora+":"+min;

        return fecha;  
    }

    //Permite establecer un horario correcto.
    function times(valor){
        var fechaF = valor

        var split = fechaF.split(" ");
        var f = split[0];
        var h = split[1];

        var split3 = h.split(":");
        var hora = split3[0];
        var min = split3[1];

        if(min=="30"){
            $('#to').datetimepicker({
                allowTimes:[
                "00:30", "01:30", "02:30",
                "03:30", "04:30", "05:30",
                "06:30", "07:30", "08:30",
                "09:30", "10:30", "11:30",
                "12:30", "13:30", "14:30",
                "15:30", "16:30", "17:30",
                "18:30", "19:30", "20:30",
                "21:30", "22:30", "23:30",
                ],
                lang:'es',
                dayOfWeekStart: 1,
                minDate:get_date($('#from').val())?get_date($('#from').val()):false,
                format:'d-m-Y H:i'
            });
        }else{
            $('#to').datetimepicker({
                allowTimes:[
                "00:00", "01:00", "02:00",
                "03:00", "04:00", "05:00",
                "06:00", "07:00", "08:00",
                "09:00", "10:00", "11:00",
                "12:00", "13:00", "14:00",
                "15:00", "16:00", "17:00",
                "18:00", "19:00", "20:00",
                "21:00", "22:00", "23:00",
                ],
                lang:'es',
                dayOfWeekStart: 1,
                minDate:get_date($('#from').val())?get_date($('#from').val()):false,
                format:'d-m-Y H:i'
            });
        }

    }

    function get_date(input) {
        if(input == '') {
            return false;
        }else{
            var parts = input.match(/(\d+)/g);
            return parts[2]+'/'+parts[1]+'/'+parts[0];
        } 
    }

    function validateTime(){

        if($("#from").val() && $("#to").val()){
            var fechaF = splitTime($("#from").val());
            var fechaT = splitTime($("#to").val());
            if(fechaF >= fechaT){
              return true;
            }
            return false;
        }
    }*/

    /*function splitTime(time){
        var split = time.split(" ");
        var f = split[0];
        var h = split[1];

        var split = f.split("-");
        var dia = split[0];
        var mes = split[1];
        var ano = split[2];
        if(dia){
            var time = h.split(":");
            var hora = time[0];
            var min = time[1]
        }
        return (mes+dia+ano+hora+min);
    }

    //valida que los no existan lapsus menores a 1 hora  en los arriendos
    function validateMin(){
        if($("#from").val() && $("#to").val()){
            var minF = $("#from").val();
            var splitF = minF.split(" ");
            //separa fecha de hora
            var ff = splitF[0];
            var tf = splitF[1];
            //separa hora de min
            var timeF = tf.split(":");
            var hf = timeF[0]; 
            var mf = timeF[1];

            //Hasta 
            var minT = $("#to").val();        
            var splitT = minT.split(" ");
            var ft = splitT[0];
            var tt = splitT[1];
            var timeT = tt.split(":");
            var ht = timeT[0];
            var mt = timeT[1];
            
            if (mt != mf){
                return true;
            }else{
                return false;
            }
        }
    }*/
</script>
