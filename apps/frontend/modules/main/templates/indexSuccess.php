<link href="/css/newDesign/index.css?v=8" rel="stylesheet" type="text/css">

<!-- Google Maps -->
<script src="http://maps.googleapis.com/maps/api/js?libraries=places&amp;sensor=false" type="text/javascript"></script>
<script src="/js/newDesign/markerclusterer.js" type="text/javascript"></script>

<!-- Varios -->
<script type="text/javascript">

    var reserveUrl = "<?php echo url_for('reserve', array('carId' => 'carId'), true) ?>";

    var usuarioLogeado = "<?php echo $usuarioLog; ?>";
    var geolocalizacion; // coordenadas
    var latitud; // coordenadas
    var lastValidCenter; // initialize
    var longitud; // coordenadas
    var map; // initialize, searchCars
    var markerCluster; // searchCars
    var markers = []; // searchCars
    var strictBounds = null; // initialize

    var swLat;
    var swLng;
    var neLat;
    var neLng;

    function coordenadas(position) {

        latitud  = position.coords.latitude; // Guardamos nuestra latitud
        longitud = position.coords.longitude; // Guardamos nuestra longitud
        
        <?php if ($sf_user->getAttribute('geolocalizacion') == true): ?>
            geolocalizacion = true;
        <?php else: ?>
            geolocalizacion = false;
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

    function initialize() {

        var center = null;        

        <?php if (stripos($_SERVER['SERVER_NAME'], "arrendas") !== FALSE): ?>
            center = new google.maps.LatLng(-34.59, -58.401604);
        <?php else: ?>
            center = new google.maps.LatLng(-33.436024, -70.632858);
        <?php endif ?>            

        <?php if (isset($_GET['ciudad']) && $_GET['ciudad'] == "arica"): ?>
            center = new google.maps.LatLng(-18.32, -70.20);
        <?php elseif (isset($_GET['ciudad']) && $_GET['ciudad'] == "concepcion"): ?>
            center = new google.maps.LatLng(-37.00, -72.30);
        <?php elseif (isset($_GET['ciudad']) && $_GET['ciudad'] == "laserena"): ?>
            center = new google.maps.LatLng(-29.75, -71.10);
        <?php elseif (isset($_GET['ciudad']) && $_GET['ciudad'] == "temuco"): ?>
            center = new google.maps.LatLng(-38.45, -72.40);
        <?php elseif (isset($_GET['ciudad']) && $_GET['ciudad'] == "valparaiso"): ?>
            center = new google.maps.LatLng(-33.2, -71.4);
        <?php elseif (isset($_GET['ciudad']) && $_GET['ciudad'] == "viña"): ?>
            center = new google.maps.LatLng(-33.0, -71.3);
        <?php elseif (isset($map_clat) && isset($map_clng)): ?>
            center = new google.maps.LatLng(<?= $map_clat ?>, <?= $map_clng ?>);
        <?php endif ?>

        if (geolocalizacion) {
            center = new google.maps.LatLng(latitud, longitud);
        }

        map = new google.maps.Map(document.getElementById('map-container'), {
            zoom: 11,
            center: center,
            mapTypeId: google.maps.MapTypeId.ROADMAP,
            scrollwheel: false
        });

        var market = null;

        if (geolocalizacion) {

            marker = new google.maps.Marker({
                position: center,
                map: map,
                draggable: true,
            });
        }

        lastValidCenter = center;

        google.maps.event.addListener(map, 'idle', function() {

            google.maps.event.clearListeners(map, 'idle');
            searchCars(0, $("button.see-more").data("limit"));
        });

        google.maps.event.addListener(map, 'dragend', function() {

            if (strictBounds === null || strictBounds.contains(map.getCenter())) {
                lastValidCenter = map.getCenter();
            } else {
                map.panTo(lastValidCenter);
            }
            searchCars(0, $("button.see-more").data("limit"));
        });

        google.maps.event.addListener(map, 'zoom_changed', function() {

            if (strictBounds === null || strictBounds.contains(map.getCenter())) {
                lastValidCenter = map.getCenter();
            } else {
                map.panTo(lastValidCenter);
            }
            searchCars(0, $("button.see-more").data("limit"));
        });

        //Autocomplete
        var input = document.getElementById('direction');
        var autocomplete = new google.maps.places.Autocomplete(input);

        autocomplete.bindTo('bounds', map);

        var infowindow = new google.maps.InfoWindow();
        var marker = new google.maps.Marker({
            map: map
        });

        google.maps.event.addListener(autocomplete, 'place_changed', function() {

            infowindow.close();
            marker.setVisible(false);
            /*input.className = '';*/

            var place = autocomplete.getPlace();

            if (!place.geometry) {
                // Inform the user that the place was not found and return.
                input.className = 'notfound';
                return;
            }

            // If the place has a geometry, then present it on a map.
            if (place.geometry.viewport) {
                map.fitBounds(place.geometry.viewport);
            } else {
                map.setCenter(place.geometry.location);
                map.setZoom(17);  // Why 17? Because it looks good.
            }

            var image = new google.maps.MarkerImage(
                place.icon,
                new google.maps.Size(71, 71),
                new google.maps.Point(0, 0),
                new google.maps.Point(17, 34),
                new google.maps.Size(35, 35)
                );

            marker.setIcon(image);
            marker.setPosition(place.geometry.location);

            var address = '';
            if (place.address_components) {
                address = [
                (place.address_components[0] && place.address_components[0].short_name || ''),
                (place.address_components[1] && place.address_components[1].short_name || ''),
                (place.address_components[2] && place.address_components[2].short_name || '')
                ].join(' ');
            }

            infowindow.setContent('<div><strong>' + place.name + '</strong><br>' + address);
            infowindow.open(map, marker);
            searchCars(0, $("button.see-more").data("limit"));
        });
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

    function searchCars(offset, limit) {

        if (offset == 0) {
            $('#map-list-container, #list-container').hide();
        }
        $(".loading").show();

        // First, determine the map bounds
        var bounds = map.getBounds();

        // Then the points
        var swPoint = bounds.getSouthWest();
        var nePoint = bounds.getNorthEast();

        // Now, each individual coordinate
        swLat = swPoint.lat();
        swLng = swPoint.lng();
        neLat = nePoint.lat();
        neLng = nePoint.lng();

        var center = map.getCenter();
        var mapCenterLat = center.lat();
        var mapCenterLng = center.lng();

        var from      = $("#from").val();
        var to        = $("#to").val();
        var regionId  = parseInt($("#region option:selected").val());
        var communeId = parseInt($("#commune option:selected").val());

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

        var haveChair = false;
        $(".haveChair").each(function(){
            if ($(this).is(':checked')) {
                haveChair = true;
            }
        });

        var nearToSubway = false;
        $(".nearToSubway").each(function(){
            if ($(this).is(':checked')) {
                nearToSubway = true;
            }
        });

        var isAirportDelivery = false;
        $(".isAirportDelivery").each(function(){
            if ($(this).is(':checked')) {
                isAirportDelivery = true;
                if (reserveUrl.indexOf("?a") == -1) {
                    reserveUrl = reserveUrl+"?a";
                }
            }
        });

        var isMap = false;
        if ($("#tab-map").is(":visible")) {
            isMap = true;
        }

        var parameters = {
            offset: offset,
            limit: limit,
            isMap: isMap,//$('div[data-target="#tab-map"]').hasClass("activo"),
            SWLat: swLat,
            SWLng: swLng,
            NELat: neLat,
            NELng: neLng,
            mapCenterLat: mapCenterLat,
            mapCenterLng: mapCenterLng,
            from : from,
            to: to,
            regionId: regionId,
            communeId: communeId,
            isAutomatic: isAutomatic,
            isLowConsumption: isLowConsumption,
            isMorePassengers: isMorePassengers,
            haveChair: haveChair,
            isAirportDelivery: isAirportDelivery,
            nearToSubway: nearToSubway        
        };

        /*console.log(parameters);*/

        $.post("<?php echo url_for('car_search') ?>", parameters, function(r){

            var listContent = "";
            var mapListContent = "";
            var markersLength = markers.length;

            if (markers) {

                var i = 0;
                
                for (i ; i < markers.length ; i++) {
                    markers[i].setMap(null)
                }

                markers = [];
            }

            if (markersLength > 0) {
                markerCluster.clearMarkers();
            }

            if (r.cars.length > 0) {
                for (var i = 0 ; i < r.cars.length ; i++) {

                    var Car = r.cars[i];
                    
                    var latLng = new google.maps.LatLng(Car.latitude, Car.longitude);

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

                    var windowMarker = "";
                    windowMarker += "<div class='infowindow row' id='" + Car.id + "'>";

                    windowMarker += "<div class='col-md-4 text-center'>";
                    if(Car.photoS3){
                        windowMarker += "<img class='img-responsive' src='" + Car.photoS3 + "' alt='rent a car  "+ Car.brand +" "+ Car.model +"'/>";
                    } else {
                        if(str > 0) {
                            windowMarker += "<img class='img-responsive' src='http://www.arriendas.cl" + urlFotoThumbTipo + "' alt='rent a car  "+ Car.brand +" "+ Car.model +"'/>";
                        } else {
                            windowMarker += "<img class='img-responsive' src='http://res.cloudinary.com/arriendas-cl/image/fetch/w_112,h_84,c_fill,g_center/http://www.arriendas.cl" + urlFotoThumbTipo + "' alt='rent a car "+ Car.brand +" "+ Car.model +"'/>";
                        }
                    }
                    windowMarker += "</div>";

                    windowMarker += "<div class='col-md-8' style='padding-left: 15px'>";
                    windowMarker += "<h2>" + Car.brand + " " + Car.model + "</a></h2>";
                    windowMarker += "<p class='paragraph' style='margin-top: 5px'>Hora: <b>$" + Car.price_per_hour + " </b></p>";
                    windowMarker += "<p class='paragraph' style='margin-top: 5px'>Dia: <b>$" + Car.price_per_day + " </b></p>";
                    windowMarker += "<p class='paragraph' style='margin-top: 5px'>Transmisión: <b>" + Car.transmission + "</b></p>";
                    windowMarker += "<div class='metro'><p><img class='km-area' src='/images/newDesign/ico.png' alt='metro'> A <b><em>"+Car.nearestMetroDistance+"</em> km</b> del Metro "+Car.nearestMetroName+"</p></div>"

                    windowMarker += "</div>";                    
                    
                    windowMarker += "</div>";

                    windowMarker += "<p class='text-right'><a class='btn btn-a-action btn-sm' href='"+reserveUrl.replace("carId", Car.id)+"' target='_blank'>RESERVAR</a></p>";

                    if (infowindow) {
                        infowindow.close();
                    }
                    
                    var infowindow = new google.maps.InfoWindow({
                        content: windowMarker
                    });

                    var image = 'http://chart.apis.google.com/chart?chst=d_map_pin_letter&chld=' + (i+1) + '|05a4e7|ffffff';

                    var marker = new google.maps.Marker({
                        id: Car.id,
                        position: latLng,
                        icon: image,
                        contentString: windowMarker
                    });

                    google.maps.event.addListener(marker, 'click', function() {
                        infowindow.setContent(this.contentString);
                        infowindow.open(map, this);
                    });

                    markers.push(marker);

                    article = "<article class='box'>";
                    article += "<div class='row'>";
                    article += "<div class='col-xs-4 col-md-4 image'>";
                    if(Car.photoS3){
                            article += "<img class='img-responsive' src='" + Car.photoS3+ "' height='99' width='134' alt='rent a car "+ Car.brand +" "+ Car.model +"'/>";
                    } else {
                        if(str > 0) {
                            article += "<img class='img-responsive' src='http://www.arriendas.cl" + urlFotoThumbTipo + "' height='99' width='134' alt='rent a car "+ Car.brand +" "+ Car.model +"'/>";
                        }else   {
                            article += "<img class='img-responsive' src='http://res.cloudinary.com/arriendas-cl/image/fetch/w_112,h_84,c_fill,g_center/http://www.arriendas.cl" + urlFotoThumbTipo + "' height='99' width='134' alt='rent a car " + Car.brand +" "+ Car.model +"'/>";
                        }
                    }
                    /*article += "<img class='car img-responsive' src='http://res.cloudinary.com/arriendas-cl/image/fetch/w_134,h_99,c_fill,g_center/http://www.arriendas.cl" + urlFotoThumbTipo + "' height='99' width='134' alt='"+ Car.brand +" "+ Car.model +"'>";*/
                    article += "<img class='marker' src='http://chart.apis.google.com/chart?chst=d_map_pin_letter&chld=" + (i+1) + "|05a4e7|ffffff'>";
                    article += "</div>";
                    article += "<div class='col-xs-8 col-md-8 text'>";
                    article += "<h2>"+ Car.brand +" "+ Car.model +"<small>, "+Car.year+"</small></h2>";
                    /*article += "<span class='sub-heading'>A 2 km Metro <strong>Tobalaba</strong></span>";*/
                    article += "<p class='price'>$"+ Car.price +" <small style='color: black; font-weight: 300'>TOTAL FINAL</small></p>";
                    article += "<div class='metro'><p><img class='km-area' src='/images/newDesign/ico.png' alt='metro'> A <b><em>"+Car.nearestMetroDistance+"</em> km</b> del Metro "+Car.nearestMetroName+"</p></div>"
                    article += "<p class='text-right'><a class='btn btn-a-action btn-sm' href='"+reserveUrl.replace("carId", Car.id)+"' class='reserve' target='_blank'>RESERVAR</a></p>";
                    /*article += "<img src='http://chart.apis.google.com/chart?chst=d_map_pin_letter&chld=" + contador + "|05a4e7|ffffff' />";*/
                    article += "</div>";
                    article += "</div>";
                    article += "</article>";

                    listContent += "<div class='col-md-4'>";
                    listContent += article;
                    listContent += "</div>";
                    
                    mapListContent += article;
                }
            } else {
                if (offset) {
                    mapListContent += "<div class='col-md-12'><p class='msg-search' style='padding-top: 3%'>Ya se están mostrando todos los autos disponibles para las fechas indicadas</p></div>";
                    listContent += "<div class='col-md-12'><p class='msg-search' style='padding-top: 3%'>Ya se están mostrando todos los autos disponibles para las fechas indicadas</p></div>";
                } else {
                    mapListContent += "<div class='col-md-12'><p class='msg-search'>No hemos encontrado autos</p></div>";
                    listContent += "<div class='col-md-12'><p class='msg-search'>No hemos encontrado autos</p></div>";
                }
            }

            if (offset) {
                $("#map-list-container").append(mapListContent);
                $("#list-container").append(listContent);
            } else {
                $("#map-list-container").html(mapListContent);
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
            if (!$("#map-list-container, #list-container").is(":visible")) {
                $("#map-list-container, #list-container").show();
            }

            markerCluster = new MarkerClusterer(map, markers, {maxZoom: 10});            
        }, "json");
    }
</script>

<section id="section-home">

    <div class="hidden-xs hidden-sm col-md-12">
        <video id="video" autoplay loop >
            <source src="/videos/video.mp4" type="video/mp4">
                Your browser does not support HTML5 video.
        </video> 
    </div>

    <div class="col-sm-offset-1 col-sm-10 col-md-offset-2 col-md-8 text-center">

        <!-- Carousel -->
        <div id="section-home-carousel">
            <div>
                <h1>ARRIENDA EL AUTO DE UN VECINO CON SEGURO, ASISTENCIA EN RUTA Y TAG</h1>
                <h2>Precios finales, sin letra chica.</h2>
            </div>
            <div>
                <h1>DEPOSITO EN GARANTIA OPCIONAL </h1>
                <h2>Pagos con o sin Tarjeta de Crédito. </h2>
            </div>
            <div>
                <h1>PRIMER SISTEMA DE ARRIENDO DE AUTOS ENTRE PERSONAS</h1>
                <h2>Hay un auto en tu comuna o en un metro cercano.</h2>
            </div>            
        </div>
        <button class="hidden-xs" id="btn-leese" data-target="#section-map-form-search">Arrienda ahora</button>
    </div>

    <div class="row" id="section-map-form-search">

        <span class="ico-search hidden-xs" data-target="#section-map-form-search"><img src="/images/newDesign/ico-search.svg"></span>
        <p class="available-cars" >Mostrando autos con disponibilidad confirmada</p>
        <p class="search-by">Buscar por: <a href="<?php echo url_for('rent_a_car')?>">Comuna</a> / <a href="<?php echo url_for('rent_a_car_near_to_subway', array('metro' => 'cercanos-al-metro'))?>">Cercanos al metro</a> </p>

        <!-- List -->
        <div class="col-xs-6 col-sm-3 col-md-3" id="region-container">
            <select class="region form-control" id="region">
                <option disabled selected value="<?php echo $Region->id ?>"><?php echo $Region->name ?></option>
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

        <!-- Map -->
        <div class="hidden-xs col-sm-6 col-md-6" id="direction-container">
            <input class="direction form-control" id="direction" placeholder="Dirección" type="text">
        </div>

        <!-- From To -->
        <div class="col-xs-6 col-sm-2 col-md-2" id="from-container">
            <input class="datetimepicker form-control text-left" id="fromH" placeholder="Desde" type="button">
        </div>
        <div class="col-xs-6 col-sm-2 col-md-2" id="to-container">
            <input class="datetimepicker form-control text-left" id="toH" placeholder="Hasta" type="button">
        </div>

        <!-- Search -->
        <div class="col-xs-12 col-sm-2 col-md-2 text-center">
            <button class="btn btn-a-action btn-block" id="search" type="button">Buscar</button>
        </div>
<hr>
        <div class="filters">
            <input type="checkbox" name="filter" class="isAutomatic"> Automático
            <input type="checkbox" name="filter" class="isLowConsumption"> Bajo Consumo
            <input type="checkbox" name="filter" class="isMorePassengers"> Más de 5 pasajeros
            <input type="checkbox" name="filter" class="haveChair"> Silla Bebé
            <input type="checkbox" name="filter" class="isAirportDelivery"> Auto en aeropuerto
            <input type="checkbox" name="filter" class="nearToSubway"> Cercano al metro(máximo a 15 minutos)
        </div>
    </div>
</section>

<section id="section-map">

    <div id="section-map-body">

        <div class="tab-container hidden-xs" id="tab-map">
            <div class="row">
                <div class="col-sm-8 col-md-8" id="map">
                    <div id="map-container"></div>
                </div>

                <div class="col-sm-4 col-md-4" id="map-list">
                    <div class='hidden-xs space-50'></div>
                    <div id="map-list-loading" class="loading" style="text-align: center; margin-top: 30%"><?php echo image_tag('ajax-loader.gif', array("width" => "80px", "height" => "80px")) ?></div>
                    <div id="map-list-container"></div>
                </div>
            </div>
        </div>

        <div class="tab-container" id="tab-list">
            <div class="row" id="list">
                <div class="row" id="list-container"></div>
                <div id="list-loading" class="loading" style="text-align: center; margin: 4% 0 4% 0">
                    <?php if ($isMobile): ?>
                        <?php echo image_tag('ajax-loader.gif', array("width" => "40px", "height" => "40px")) ?>
                    <?php else: ?>
                        <?php echo image_tag('ajax-loader.gif', array("width" => "80px", "height" => "80px")) ?>
                    <?php endif ?>
                </div>
                <button class="see-more btn-block" data-offset="0" data-limit="<?php echo $limit ?>" type="button">Ver más</button>
            </div>
        </div>
    </div>
</section>

<section id="section-how-works">
    <div class="row">
        <div class="col-sm-offset-1 col-sm-10 col-md-offset-1 col-md-10">
            <h2 class="title"><span>¿Cómo Funciona?</span></h2>
            <iframe class="iframe" src="//player.vimeo.com/video/45668172?title=0&amp;byline=0&amp;portrait=0&amp;color=ffffff"  frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>    
        </div>
    </div>
</section>

<section id="section-compare-prices">

    <div class="row">
        <div class="col-sm-offset-1 col-sm-10 col-md-offset-1 col-md-10 text-center">
            <h2 class="title">Compare precios</h2>
       </div>
    </div>
   
    <div class="visible-xs space-20"></div>

    <div class="row">
        <div class="col-sm-offset-1 col-sm-10 col-md-offset-2 col-md-8 table-responsive text-center">
            <table id="compare-prices-table">
                <thead>
                    <tr>
                        <th class="table-transparent text-"></th>
                        <th><img src="/images/newDesign/logo-avis.svg"></th>
                        <th><img src="/images/newDesign/logo-hertz.svg"></th>
                        <th><img src="/images/newDesign/logo-europcar.svg"></th>
                        <th class="table-active"><img src="/images/newDesign/logo.svg"></th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><span class = "td">City Car</span></td>
                        <td><span class = "td">$ 40.877</span></td>
                        <td><span class = "td">$ 33.858</span></td>
                        <td><span class = "td">$ 34.580</span></td>
                        <td class = "last"><span class = "td">$ 17.000</span></td>
                    </tr>
                    <tr>
                        <td><span class = "td">Mediano</span></td>
                        <td><span class = "td">$ 49.207</span></td>
                        <td><span class = "td">$ 51.946</span></td>
                        <td><span class = "td">$ 54.081</span></td>
                        <td class = "last"><span class = "td">$ 25.000</span></td>
                    </tr>
                    <tr>
                        <td><span class = "td">Camioneta SUV</span></td>
                        <td><span class = "td">$ 89.667</span></td>
                        <td><span class = "td">$ 73.337</span></td>
                        <td><span class = "td">$ 74.413</span></td>
                        <td class = "last" ><span class = "td">$ 35.000</span></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-offset-1 col-sm-10 col-md-offset-1 col-md-10">
            <br>
            <p class="hidden-xs text-center">Precios con IVA, aplicando descuento por reservas en internet, con seguro de daños, robo y accidentes personales. Muestra tomada 1/1/2015 en sus páginas de internet</p>
       </div>
    </div>
</section>

<section class="visible-xs hidden-sm hidden-md" id="section-condition">
    
    <div class="row">
        <div class="col-xs-offset-1 col-xs-10">
            <h2 class="title"><span>Condiciones de Arriendo</span></h2>
            <p>Debes tener al menos 24 años para poder arrendar.</p>
            <p>Tienes que contar con una licencia de conducir valida.</p>
            <p>Debes pagar con una cuenta bancaria a tu nombre (no puede ser de un tercero).</p>
        </div>
    </div>
    <div class="visible-xs hidden-sm hidden-md space-40"></div>
</section>

<section class="hidden-xs" id="section-on-news">

    <div class="row">
        <div class="col-sm-offset-1 col-sm-10 col-md-offset-1 col-md-10">
            <h2 class="title">Arriendas en las noticias</h2>
        </div>
    </div>

    <div class="row" id="noticias">
        <div class="col-sm-offset-2 col-sm-8 col-md-offset-2 col-md-8">
            <div id="section-on-news-carousel">
                <div class="normal"><a href="http://www.t13.cl/videos/actualidad/arrienda-tu-auto-es-la-nueva-tendencia-entre-los-chilenos" target="_blank"><img src="/images/newDesign/logos_canais/13.png" alt="Canal 13"></a></div>
                <div><a href="http://www.cnnchile.com/noticia/2014/01/10/arriendas-el-emprendimiento-que-permite-arrendar-tu-propio-auto" target="_blank"><img src="/images/newDesign/logos_canais/LogoCNN.png" alt="CNN Chile"></a></div>
                <div class="normal"><a href="http://www.24horas.cl/nacional/rent-a-car-vecino-la-nueva-forma-de-viajar-906946" target="_blank"><img src="/images/newDesign/logos_canais/logotvn2.png" alt="TVN"></a></div>
                <div><a href="http://www.emol.com/noticias/economia/2012/07/27/552815/emprendedor-estrenara-primer-sistema-de-arriendo-de-vehiculos-por-hora-de-chile.html" target="_blank"><img src="/images/newDesign/logos_canais/LogoEmol.png" alt="EMOL"></a></div>
                <div><a href="http://www.lun.com/lunmobile//pages/NewsDetailMobile.aspx?IsNPHR=1&dt=2012-10-23&NewsID=0&BodyId=0&PaginaID=6&Name=6&PagNum=0&SupplementId=0&Anchor=20121023_6_0_0" target="_blank"><img src="/images/newDesign/logos_canais/LogoLUN.png" alt="Las Últimas Noticias"></a></div>
                <div><a href="http://www.tacometro.cl/prontus_tacometro/site/artic/20121030/pags/20121030152946.html" target="_blank"><img src="/images/newDesign/logos_canais/LogoPublimetro.png" alt="Publimetro" ></a></div>
                <div><a href="http://www.lasegunda.com/Noticias/CienciaTecnologia/2012/08/774751/arriendascl-sistema-de-alquiler-de-autos-por-horas-debuta-en-septiembre" target="_blank"><img src="/images/newDesign/logos_canais/LogoLaSegunda.png" alt="La Segunda"></a></div>
                <div><a href="http://www.lacuarta.com/noticias/cronica/2013/08/63-157571-9-ahora-puedes-arrendar-el-automovil-de-tu-vecino.shtml" target="_blank"><img src="/images/newDesign/logos_canais/LogoLaCuarta.png" alt="La Cuarta"></a></div>
                <div><a href="http://www.diariopyme.cl/arrienda-tu-auto-y-gana-dinero-extra-a-fin-de-mes/prontus_diariopyme/2013-06-23/212000.html" target="_blank"><img src="/images/newDesign/logos_canais/LogoDiarioPyme.png" alt="Diario PYME"></a></div>
            </div>
        </div>
    </div>
</section>

<section class="hidden-xs" id="section-testimonials">

    <div class="hidden-xs space-40"></div>

    <div id="testimonials-container">
        <h1>Testimonios</h1>
        <div id="section-testimonials-carousel">
            <div>
                <p class="testimonial"><i class="fa fa-quote-left"></i> Puedo arrendar desde mi casa, en cualquier horario, sin tarjeta de crédito. Es el mismo auto que en un rent a car pero 30% más barato. <i class="fa fa-quote-right"></i></p>
                <p class="user">Javiera Cruzar,</p>
                <p class="user-type">Usuario Arriendas</p>
            </div>
            <div>
                <p class="testimonial"><i class="fa fa-quote-left"></i> La experiencia con el arrendador fue excelente. <i class="fa fa-quote-right"></i></p>
                <p class="user">Juan Diaz Medel,</p>
                <p class="user-type">Usuario Arriendas</p>
            </div>
            <div>
                <p class="testimonial"><i class="fa fa-quote-left"></i>  Lo mejor de todo es que no piden tarjetas de credito, para los que no las usamos … ¡Sigan así!.  <i class="fa fa-quote-right"></i></p>
                <p class="user">Anitha Cordova Cifuentes,</p>
                <p class="user-type">Usuario Arriendas</p>
            </div>
            <div>
                <p class="testimonial"><i class="fa fa-quote-left"></i> La disposicion del dueño fue muy buena y no hubo ningun problema. Absolutamente recomendable. Ahora estoy a la espera de mi segundo arriendo para este fin de semana. Ojala se expandan a todas las regiones . <i class="fa fa-quote-right"></i></p>
                <p class="user">Francisco Riquelme,</p>
                <p class="user-type">Usuario Arriendas</p>
            </div>

            <div>
                <p class="testimonial"><i class="fa fa-quote-left"></i> Gracias por toda su ayuda y felicitarles por este emprendimiento que ayudas a tantos a contar, no solo con un auto, sino con un servicio responsable, personalizado y de gran calidad. <i class="fa fa-quote-right"></i></p>
                <p class="user">Eduardo Cortes,</p>
                <p class="user-type">Usuario Arriendas</p>
            </div>
            <div>
                <p class="testimonial"><i class="fa fa-quote-left"></i> Mi experiencia ha sido muy buena. La persona que me arrendó el auto fue atento. Soy brasileño y estuve en Chile solo para estudiar, y mismo así yo pude arrendar un auto con toda la seguridad que solo Arriendas puede dar a sus clientes.<i class="fa fa-quote-right"></i></p>
                <p class="user">Samir,</p>
                <p class="user-type">Usuario Arriendas</p>
            </div>
            <div>
                <p class="testimonial"><i class="fa fa-quote-left"></i> Mi testimonio es un 90% positivo, el sistema es bastante fácil de utilizar y la gestión para realizar el arriendo es también buena, salvo que falta incluir mas datos del vehículo. <i class="fa fa-quote-right"></i></p>
                <p class="user">Manuel Nuñez Casanga,</p>
                <p class="user-type">Usuario Arriendas</p>
            </div>
            <div>
                <p class="testimonial"><i class="fa fa-quote-left"></i> Arriendas me permitió resevar un auto sin tarjeta de crédito y sin depósito en garantía. Lo recomiendo! <i class="fa fa-quote-right"></i></p>
                <p class="user">Rosa Velásquez Rojas,</p>
                <p class="user-type">Usuario Arriendas</p>
            </div>
            <div>
                <p class="testimonial"><i class="fa fa-quote-left"></i> Su personal fue muy atento y eso genero la confianza suficiente para inscribirme en su página y concretar el arriendo. <i class="fa fa-quote-right"></i></p>
                <p class="user">Felipe Salinas,</p>
                <p class="user-type">Usuario Arriendas</p>
            </div>
            <div>
                <p class="testimonial"><i class="fa fa-quote-left"></i> Mi experiencia con Arriendas fue excelente ademas de una excelente experiencia en la atencion del dueño del vehículo: amable, puntual y el auto, excelente. 100% confiable. <i class="fa fa-quote-right"></i></p>
                <p class="user">Sergio Quezada,</p>
                <p class="user-type">Usuario Arriendas</p>
            </div>
             <div>
                <p class="testimonial"><i class="fa fa-quote-left"></i> Algunos pequeños ripios en la coordinación inicial, pero el auto arrendado estaba en perfecto estado y la persona que lo arrendó fue muy agradable. En general, una buena experiencia, la repetiré cuando lo necesite. <i class="fa fa-quote-right"></i></p>
                <p class="user">Gustavo Villagran,</p>
                <p class="user-type">Usuario Arriendas</p>
            </div>
        </div>
    </div>

    <div class="hidden-xs space-100"></div>
</section>

<div style="display:none">
    <div id="dialog-alert" title="">
        <p></p>
    </div>
</div>

<input id="from" type="hidden">
<input id="to" type="hidden">

<script>

    $(document).ready(function(){

        localizame();
        initialize();

        // Carousel
        $('#section-home-carousel').slick({
            autoplay: true,
            arrows: false,
            speed: 450,
            infinite: true
        });

        $('#section-on-news-carousel').slick({
            arrows: true,
            slidesToShow: 4,
            slidesToScroll: 4,
            lazyLoad: 'ondemand'
        });

        $('#section-testimonials-carousel').slick({
            autoplay: true,
            arrows: false,
            speed: 450
        });

        initializeDate("from", new Date(<?php echo strtotime($from) * 1000 ?>), true, false);
        initializeDate("to", new Date(<?php echo strtotime($to) * 1000 ?>), true, false);

        // Cuando es fin de semana
        <?php if ($isWeekend): ?>
            $('div[data-target="#tab-list"]').click();
            $('.map').html("");
            $('.map').removeClass("tab");
            $('.tabset').css("cursor", "default");
            $('.tabset').css("background-color", "#00aced");
        <?php endif ?>

        // Cuando se carga desde un rent-a-car-especifico (footer)
        <?php if ($hasCommune): ?>
            $('div[data-target="#tab-list"]').click();
        <?php endif ?>
    });

    <?php if (!$isMobile): ?>
        $('#section-home').css({
            'height': $(window).height()
        });

        $("#map, #map-list").css({
            height: $(window).height() - $("#section-map-form-search").outerHeight() - 50
        });
    <?php endif ?>

    $("input[type='checkbox']").change(function(){
        searchCars(0, $("button.see-more").data("limit"));
    });

    $("#search").click(function(e){
        searchCars(0, $("button.see-more").data("limit"));
        $(".ico-search").click();
    });

    $("#commune").change(function(){
        searchCars(0, $("button.see-more").data("limit")); 
    });

    $(".tab").click(function(){

        var target = $(this).data("target");

        if (target == "#tab-map") {
            $("#region-container").hide();
            $("#commune-container").hide();
            $("#direction-container").show();
            $(".list").removeClass('activo');
            $(".map").addClass('activo');
        }

        if (target == "#tab-list") {
            $("#direction-container").hide();
            $("#region-container").show();
            $("#commune-container").show();
            $(".map").removeClass('activo');
            $(".list").addClass('activo');
        }
    });

    $('#header .animate').each(function(){

        var target  = $(this).data('target');

        $(this).on('click', function(e) {

            e.preventDefault();
            var position = $(target).offset().top - 50;
            $('html, body').animate({
                scrollTop: position
            }, 1250);
        });
    });

    $(".ico-search").on('click', function(e) {
        
        e.preventDefault();

        var target  = $(this).data('target');
        var position = $(target).offset().top - 50;

        $('html, body').animate({
            scrollTop: position
        }, 1250);
    });

    $("#btn-leese").on('click', function(e) {
        
        e.preventDefault();

        var target  = $(this).data('target');
        var position = $(target).offset().top - 50;
        $('html, body').animate({
            scrollTop: position
        }, 1250);
    });

    $(".tab").on('click', function(){

        var target = $(this).data("target");

        $(".tab").removeClass("active");
        $(this).addClass("active");

        $(".tab-container").hide();
        $(target).show();
    });

    $(document).on("click", ".see-more", function(){
        searchCars($("button.see-more").data("offset"), $("button.see-more").data("limit"));
    });

    function afterDateRefresh() {
        searchCars(0, $("button.see-more").data("limit"));
    }
</script>
<script src="/js/newDesign/dates.js?v=5" type="text/javascript"></script>
