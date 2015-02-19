<link href="/css/newDesign/searchACars.css" rel="stylesheet" type="text/css">
<script src="/js/newDesign/markerclusterer.js" type="text/javascript"></script>



<script>
    var reserveUrl = "<?php echo url_for('reserve', array('carId' => 'carId'), true) ?>";
    var usuarioLogeado = "<?php echo $usuarioLog; ?>";

    var map; // initialize, searchCars
    var geolocalizacion; // coordenadas
    var latitud; // coordenadas
    var lastValidCenter; // initialize
    var longitud; // coordenadas
    var map; // initialize, searchCars
    var markerCluster; // searchCars
    var markers = []; // searchCars
    var strictBounds = null; // initialize

    var swLat; // searchCars
    var swLng; // searchCars
    var neLat; // searchCars
    var neLng; // searchCars

    function coordenadas(position) {

        latitud  = position.coords.latitude; // Guardamos nuestra latitud
        longitud = position.coords.longitude; // Guardamos nuestra longitud
        
        <?php if ($sf_user->getAttribute('geolocalizacion') == true): ?>
            geolocalizacion = true;
        <?php else: ?>
            geolocalizacion = false;
        <?php endif ?>
    }
    function localizame() {
        <?php if (!$isMobile): ?>
            if (navigator.geolocation) { // Si el navegador tiene geolocalizacion
                navigator.geolocation.getCurrentPosition(coordenadas, errores);
            } else {
                alert('¡Oops! Tu navegador no soporta geolocalización. Bájate Chrome, que es gratis!');
            }
        <?php endif ?>
    }

    function errores(err) {

        // Controlamos los posibles errores
        if (err.code == 0) {
            alert("¡Oops! Algo ha salido mal");
        }
        if (err.code == 1) {
            alert("¡Oops! No has aceptado compartir tu posición");
        }
        if (err.code == 2) {
            alert("¡Oops! No se puede obtener la posición actual");
        }
        if (err.code == 3) {
            alert("¡Oops! Hemos superado el tiempo de espera");
        }
    }

    function searchCars() {

        if (validateTime()) {
            
            $("#dialog-alert p").html('Fecha "Hasta" debe ser posterior a la fecha "Desde"');
            $("#dialog-alert").attr('title','Fecha "Hasta" mal ingresada');
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

        if(validateMin()){
            $("#dialog-alert p").html('No se puede ingresar "Fechas" con duraciones de media hora.');
            $("#dialog-alert").attr('title','Fecha "Hasta" mal ingresada');
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

        $('#list-container').hide();
        $(".loading").show();

        var from      = $("#from").val();
        var to        = $("#to").val();
        var regionId  = parseInt($("#region option:selected").val());
        var communeId = parseInt($("#commune option:selected").val());

        //Filtro busqueda
        var isAutomatic = false;
        $(".isAutomatic").each(function(){
            if ($(this).is(':checked')) {
                isAutomatic = true;
            }
        });
        
        var isLowConsumption = false;
        $(".isLowConsumption").each(function(){
            if ($(this).is(':checked')) {
                isLowConsumption = true;
            }
        });

        var isMorePassengers = false;
        $(".isMorePassengers").each(function(){
            if ($(this).is(':checked')) {
                isMorePassengers = true;
            }
        });

        var nearToSubway = false;
        $(".nearToSubway").each(function(){
            if ($(this).is(':checked')) {
                nearToSubway = true;
            }
        });

        // Validación de la búsqueda
        var error = false;
        var errorMessage = "<p style='padding: 5% 5% 0 5%'>Para buscar, debes:<ul>";

        if (from == "") {
            errorMessage += "<li>Seleccionar una fecha de inicio</li>";
            error = true;
        }

        if (to == "") {
            errorMessage += "<li>Seleccionar una fecha de término</li>";
            error = true;
        }
        errorMessage += "</ul></p>";

        if (error) {

            $("#list-container").html(errorMessage);
            $('.loading').hide();
            $("#list-container").show();

            return false;
        }

        var parameters = {
            //Parameters List
            from : from,
            to: to,
            regionId: regionId,
            communeId: communeId,
            isAutomatic: isAutomatic,
            isLowConsumption: isLowConsumption,
            isMorePassengers: isMorePassengers,
            nearToSubway: nearToSubway
        }

        $.post("<?php echo url_for('car_search') ?>", parameters, function(r){

            var listContent = "";
            var markersLength = markers.length;

            if (markersLength > 0) {
                markerCluster.clearMarkers();
            }

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
                   if(str > 0) {
                        article += "<img class='img-responsive' src='http://www.arriendas.cl" + urlFotoThumbTipo + "' height='99' width='134' alt='rent a car "+ Car.brand +" "+ Car.model +"'/>";
                    }else   {
                        article += "<img class='img-responsive' src='http://res.cloudinary.com/arriendas-cl/image/fetch/w_112,h_84,c_fill,g_center/http://www.arriendas.cl" + urlFotoThumbTipo + "' height='99' width='134' alt='rent a car " + Car.brand +" "+ Car.model +"'/>";
                    }
                    article += "</div>";
                    article += "<div class='col-xs-8 col-md-8 text'>";
                    article += "<h2>"+ Car.brand +" "+ Car.model +"<small>, "+Car.year+"</small></h2>";
                    /*article += "<span class='sub-heading'>A 2 km Metro <strong>Tobalaba</strong></span>";*/
                    article += "<p class='price'>$"+ Car.price +" <small style='color: black; font-weight: 300; font-size: 9px;'>TOTAL</small></p>";
                    article += "<div class='metro'><p><img class='km-area' src='/images/newDesign/ico.png' alt='metro'> A <b><em>"+Car.nearestMetroDistance+"</em> km</b> del Metro "+Car.nearestMetroName+"</p></div>";
                    article += "<p class='text-right'><a class='btn btn-a-action btn-sm' href='"+reserveUrl.replace("carId", Car.id)+"' class='reserve' target='_blank'>RESERVAR</a></p>";
                    /*article += "<img src='http://chart.apis.google.com/chart?chst=d_map_pin_letter&chld=" + contador + "|05a4e7|ffffff' />";*/
                    article += "</div>";
                    article += "</div>";
                    article += "</article>";

                    listContent += "<div class='col-md-4'>";
                    listContent += article;
                    listContent += "</div>";  
                   

                    /*listContent += "<article class='box'>";
                    listContent += "<div class='img-holder'><img src='http://res.cloudinary.com/arriendas-cl/image/fetch/w_134,h_99,c_fill,g_center/" + urlFotoThumbTipo + "' height='99' width='134' alt=''></div>";
                    listContent += "<div class='text-area'>";
                    listContent += "<h2><a href='<?php echo url_for("arriendo-de-autos/rent-a-car") ?>/" + Car.brand + Car.model + "/" + Car.comuna + "/" + Car.id + "'>"+ Car.brand +" "+ Car.model +"<span>, "+Car.year+"</span></a></h2>";
                    listContent += "<span class='sub'>Providencia </span>";
                    listContent += "<span class='sub-heading'>A 2 km Metro <strong>Tobalaba</strong></span>";
                    listContent += "<span class='price'>$"+ Car.price_per_day +"</span>";
                    listContent += "<a href='<?php echo url_for("profile/reserve?id=") ?>"+ Car.id + "' class='reserve'>RESERVAR</a>";
                    listContent += "</div>";
                    listContent += "</article>";*/
                }
            } else {
                //mapListContent += "<h2 style='text-align: center'>No hemos encontrado vehículos</h2>";
                listContent += "<h2 style='text-align: center'>No hemos encontrado vehículos</h2>";
            }

            //$("#map-list-container").html(mapListContent);
            $("#list-container").html(listContent);

            $('.loading').hide();
            $("#list-container").show();

            var mcOptions = {
                maxZoom: 10
            };

            //markerCluster = new MarkerClusterer(map, markers, mcOptions);
        }, "json");
    }

</script>

<div class="hidden-xs space-100"></div>
<div class="visible-xs space-50"></div>

<div class="row">
    <div class="col-md-12">
        <div class="BCW">
            <h1>¡Arrienda un Auto!</h1>

            <!--Solo debe aparecer la Lista-->
            <div class="lista grey">
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
                        <input class="from daepicker form-control" id="from" placeholder="Desde" type="text" value="<?php if(date("H:i") >= "20:00" || date("H:i") <= "08:00"):echo date("d-m-Y 08:00",(strtotime ("+12 Hours"))); else:echo date("d-m-Y H:i", (strtotime ("+4 Hours"))); endif; ?>" >
                    </div>
                    <div class="col-xs-6 col-sm-2 col-md-2" id="to-container">
                        <input class="to datetimepicker form-control" id="to" placeholder="Hasta" type="text" value="<?php if(date("H:i") >= "20:00" || date("H:i") <= "08:00"):echo date("d-m-Y 08:00",(strtotime ("+32 Hours"))); else:echo date("d-m-Y H:i", (strtotime ("+24 Hours"))); endif; ?>" >
                    </div>

                     <!-- Search -->
                    <div class="col-xs-12 col-sm-2 col-md-2 text-center">
                        <a class="btn btn-a-action btn-block" href id="search">Buscar</a>
                    </div>
                </div>

                <div class="hidden-xs row" id="section-map-filters">
                    <div class=" col-md-offset-1 col-md-6 col-sm-2 col-md-2 text-center">
                        <strong class="heading">Filtros:</strong>
                    </div>
                    <div class="col-sm-8 col-md-8">
                        <ul>
                            <li><input type="checkbox" name="filter" class="isAutomatic"> Automático</li>
                            <li><input type="checkbox" name="filter" class="isLowConsumption"> Petrolero</li>
                            <li><input type="checkbox" name="filter" class="isMorePassengers"> Más de 5 pasajeros</li>
                            <li><input type="checkbox" name="filter" class="nearToSubway"> Cercano al metro</li>
                        </ul>
                    </div>
                </div>
            </div>

            <!--Mobile-->
            <section id="section-map">
                <nav class="visible-xs navbar navbar-default" id="filters-navbar" role="navigation">
                    <div class="container">

                        <!-- Brand and toggle get grouped for better mobile display -->
                        <div class="navbar-filters">
                            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#filters">
                                <span class="sr-only">Toggle navigation</span>
                                <span class="icon-bar"></span>
                                <span class="icon-bar"></span>
                                <span class="icon-bar"></span>
                            </button>
                            <h3 class="filter"style="margin-top: 5px; padding-top: 12px">Filtros:</h3>
                        </div>

                        <!-- Collect the nav links, forms, and other content for toggling -->
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
            </section>

            <!--Alert-->
            <div style="display:none">
                <div id="dialog-alert" title="">
                    <p></p>
                </div>
            </div>
            <br>

            <!--Anuncios-->
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

<div class="hidden-xs space-100"></div>


<script>

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
            //var numberOfOpportunities = $(this).data("number-of-opportunities");

            if (isCollapse) {
                html += "Arriendo de autos en otras comunas de Santiago<i class='pull-right fa fa-chevron-up'></i>";
            } else {
                html += "Arriendo de autos en otras comunas de Santiago<i class='pull-right fa fa-chevron-down'></i>";
            }

            $(this).html(html);
        });
    }

    $(document).ready(function(){
        
        $('#from').datetimepicker({
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
            format:'d-m-Y H:i',
            minDate : "<?php echo date('d-m-Y') ?>",
            dayOfWeekStart: 1,
            lang:'es',
            onSelectTime: function() {
                    var to = $("#from").val();
                    times(to);
            },
            onSelectDate: function() {
                var to = $("#from").val();
                times(to);
            }
        });

        $("#search").click(function(e){
            e.preventDefault();
            searchCars();
        });

        $("#commune").change(function(){
            searchCars(); 
        });

        $("input[type='checkbox']").change(function(){
            searchCars();
        });

        $("#from").val(roundTime($("#from").val()));
        $("#to").val(roundTime($("#to").val()));

        localizame();

        <?php if (!($hasCommune)): ?>
            $("#commune").focus();

        <?php endif ?>

        $("#search").click();
        checkCollapse();
        
    });

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
    }

    function splitTime(time){
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
    }

    

</script>
