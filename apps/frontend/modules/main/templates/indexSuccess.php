<!-- Google Maps -->
<script src="http://maps.googleapis.com/maps/api/js?libraries=places&amp;sensor=false" type="text/javascript"></script>
<script src="js/newDesign/markerclusterer.js" type="text/javascript"></script>

<!-- Varios -->
<script type="text/javascript">

    /*google.maps.event.addDomListener(window, 'load', initialize);*/

    var usuarioLogeado = "<?php echo $usuarioLog; ?>";

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
        <?php endif; ?>
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

        if (geolocalizacion) {
            center = new google.maps.LatLng(latitud, longitud);
        }

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
        <?php endif; ?>

        map = new google.maps.Map(document.getElementById('map-container'), {
            zoom: 14,
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
            console.log("listener idle");
            searchCars();
        });

        google.maps.event.addListener(map, 'dragend', function() {
            
            if (strictBounds === null || strictBounds.contains(map.getCenter())) {
                lastValidCenter = map.getCenter();
            } else {
                map.panTo(lastValidCenter);
            }
            console.log("listener dragend");
            searchCars();
        });

        google.maps.event.addListener(map, 'zoom_changed', function() {

            if (strictBounds === null || strictBounds.contains(map.getCenter())) {
                lastValidCenter = map.getCenter();
            } else {
                map.panTo(lastValidCenter);
            }
            console.log("listener zoom");
            searchCars();
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
            console.log("listener place change");
            searchCars();
        });
    }
        
    function localizame() {
        if (navigator.geolocation) { // Si el navegador tiene geolocalizacion
            navigator.geolocation.getCurrentPosition(coordenadas, errores);
        } else {
            alert('¡Oops! Tu navegador no soporta geolocalización. Bájate Chrome, que es gratis!');
        }
    }

    function searchCars() {

        $('#map-list-container, #list-container').hide();
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
        var map_center_lat = center.lat();
        var map_center_lng = center.lng();

        // Inputs and Selects
        var from = null;
        if ($("#from").val() != "") {
            from = $("#from").val();
        }

        var to = null;
        if ($("#to").val() != "") {
            to = $("#to").val();
        }

        var automatic = null;
        if ($('#automatic').is(':checked')) {
            automatic = true;
        }
        
        var low = null;
        if ($('#low').is(':checked')) {
            low = true;
        }

        var pasenger = null;
        if ($('#pasenger').is(':checked')) {
            pasenger = true;
        }

        var commune = null;
        if ($("#commune option:selected").val() > 0) {
            commune = $("#commune option:selected").val();
        }

        var error = false;
        var errorMap = "<p style='padding: 5% 5% 0 5%'>Para buscar, debes:<ul>";
        var errorList = "<p style='padding: 5% 5% 0 5%'>Para buscar, debes:<ul>";

        // List
        if (!region || region == 0) {
            errorList += "<li>Seleccionar una región</li>";
        }

        if (!commune || commune == 0) {
            errorList += "<li>Seleccionar una comuna</li>";
        }

        if ($("#tab-list").is(":visible") && errorList != "<p style='padding: 5% 5% 0 5%'>Para buscar, debes:<ul>") {
            error = true;
        }

        // All
        if (!from || from == "") {
            e = "<li>Seleccionar una fecha de inicio</li>";
            errorMap += e;
            errorList += e;
            error = true;
        }

        if (!to || to == "") {
            e = "<li>Seleccionar una fecha de término</li>";
            errorMap += e;
            errorList += e;
            error = true;
        }

        errorMap += "</ul></p>";
        errorList += "</ul></p>";

        if (error) {

            $("#map-list-container").html(errorMap);
            $("#list-container").html(errorList);

            $('.loading').hide();
            $("#map-list-container, #list-container").show();

            console.log("errooooor");
            return false;
        }

        var parameters = {
            swLat: swLat,
            swLng: swLng,
            neLat: neLat,
            neLng: neLng,
            map_center_lat: map_center_lat,
            map_center_lng: map_center_lng,
            from : from,
            to: to,
            automatic: automatic,
            low: low,
            pasenger: pasenger,
            commune: commune
        }

        console.log(parameters);

        $.post("<?php echo url_for('main/getCars') ?>", parameters, function(response){

            var contador = 0;
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

            if (response.cars.length > 0) {
            
                for (var i = 0; i < response.cars.length; i++) {
                    
                    var dataCar = response.cars[i];
                    var latLng = new google.maps.LatLng(dataCar.latitude, dataCar.longitude);
                    
                    if (dataCar.verificado) {
                        var verificado = "<img src='http://www.arriendas.cl/images/verificado.png' class='img_verificado' title='Auto Asegurado'/>";
                    } else {
                        var verificado = "";
                    }

                    if (dataCar.cantidadCalificacionesPositivas == 0) {
                        var calificacionesPositivas = "";
                    } else {
                        var calificacionesPositivas = "- " + dataCar.cantidadCalificacionesPositivas + "<img src='http://www.arriendas.cl/images/Medalla.png' class='medallita'/>";
                    }

                    if (dataCar.userVelocidadRespuesta == "") {
                        velocidadDeRespuesta = "<span style='font-style: italic; color:#BCBEB0;font-weight: normal;'>No tiene mensajes</span>";
                    } else {
                        velocidadDeRespuesta = dataCar.userVelocidadRespuesta;
                    }

                    var opcionLogeado = "";
                    if (usuarioLogeado == 1) { // Informacion cuando se esta logeado
                        opcionLogeado = '<div class="detalles_user"><a target="_blank" href="http://www.arriendas.cl/profile/publicprofile/id/' + dataCar.userid + '" class="mapcar_user_titulo" title="Ir al perfil del dueño">Ver perfil del dueño</a><a target="_blank" href="http://www.arriendas.cl/messages/new/id/' + dataCar.userid + '" class="mapcar_user_message" title="Env&iacute;ale un mensaje al dueño"></a></div><div class="datos_user"><div class="img_vel_resp"></div><p>Velocidad de Respuesta: <br><b>' + velocidadDeRespuesta + '</b></p></div>';
                    } else { //Informacion cuando NO se está logeado
                        opcionLogeado = "";
                    }

                    var urlFotoTipo = "";
                    var urlFotoThumbTipo = "";
                    if (dataCar.photoType == 1) {
                        urlFotoTipo = dataCar.photo;
                        urlFotoThumbTipo = dataCar.photo;
                    } else {
                        urlFotoTipo = "<?php echo image_path('../uploads/cars/" + dataCar.photo + "'); ?>";
                        urlFotoThumbTipo = "http://arriendas.cl/uploads/cars/" + dataCar.photo;
                    }

                    var contentString = "";
                    contentString += "<div class='infowindow row' id='" + dataCar.id + "'>";

                    contentString += "<div class='col-md-4 text-center'>";
                    contentString += "<a href='" + urlFotoTipo + "' class='thickbox'>";
                    contentString += "<img src='http://res.cloudinary.com/arriendas-cl/image/fetch/w_112,h_84,c_fill,g_center/" + urlFotoThumbTipo + "'/>";
                    contentString += "</a>";
                    contentString += "</div>";

                    contentString += "<div class='col-md-8' style='padding-left: 15px'>";
                    contentString += "<h2><a href='<?php echo url_for('arriendo-de-autos/rent-a-car') ?>/" + dataCar.brand + dataCar.model + "/" + dataCar.comuna + "/" + dataCar.id + "' target='_blank' title='Ir al perfil del auto'>" + dataCar.brand + " " + dataCar.model + "</a></h2>";
                    /*contentString += "<div class='images'>"+ verificado +"</div>";*/                    
                    contentString += "<p class='paragraph' style='margin-top: 5px'>Dia: <b>$" + dataCar.price_per_day + " CLP</b></p>";
                    contentString += "<p class='paragraph'>Hora: <b>$" + dataCar.price_per_hour + " CLP</b></p>";
                    contentString += "<p class='paragraph'>Transmisión: <b>" + dataCar.typeTransmission + "</b></p>";
                    contentString += opcionLogeado;
                    contentString += "</div>";                    
                    
                    contentString += "</div>";

                    contentString += "<p class='text-right'><a class='btn-a-action' href='<?php echo url_for('profile/reserve?id=') ?>" + dataCar.id + "' target='_blank'>RESERVAR</a></p>";

                    if (infowindow)
                        infowindow.close();
                    
                    var infowindow = new google.maps.InfoWindow({
                        content: contentString
                    });

                    if (dataCar.verificado) {
                        contador = contador + 1;
                        var image = 'http://chart.apis.google.com/chart?chst=d_map_pin_letter&chld=' + contador + '|05a4e7|ffffff';
                    } else {
                        var image = "<?php echo 'http://www.arriendas.cl/images/Home/circle.png'; ?>";
                    }

                    var marker = new google.maps.Marker({
                        id: dataCar.id,
                        position: latLng,
                        icon: image,
                        contentString: contentString
                    });

                    google.maps.event.addListener(marker, 'click', function() {

                        infowindow.setContent(this.contentString);
                        infowindow.open(map, this);

                        $('a.thickbox').click(function() {
                            var t = this.title || this.name || null;
                            var a = this.href || this.alt;
                            var g = this.rel || false;
                            tb_show(t, a, g);
                            this.blur();
                            return false;
                        });
                    });

                    markers.push(marker);

                    switch (dataCar.typeModel) {
                        case "1":
                            typeModel = 'Automóvil';
                            break;
                        case "2":
                            typeModel = 'Pick-Up';
                            break;
                        case "3":
                            typeModel = 'Station Wagon';
                            break;
                        case "39":
                            typeModel = 'SUV';
                            break;
                        case "4":
                            typeModel = 'Furgón';
                            break;
                    }

                    article = "<article class='box'>";
                    article += "<div class='row'>";
                    article += "<div class='col-md-4 image'>";
                    article += "<img class='car' src='http://res.cloudinary.com/arriendas-cl/image/fetch/w_134,h_99,c_fill,g_center/" + urlFotoThumbTipo + "' height='99' width='134' alt=''>";
                    article += "<img class='marker' src='http://chart.apis.google.com/chart?chst=d_map_pin_letter&chld=" + contador + "|05a4e7|ffffff'>"
                    article += "</div>";
                    article += "<div class='col-md-8 text'>";
                    article += "<h2><a href='<?php echo url_for("arriendo-de-autos/rent-a-car") ?>/" + dataCar.brand + dataCar.model + "/" + dataCar.comuna + "/" + dataCar.id + "'>"+ dataCar.brand +" "+ dataCar.model +"<small>, "+dataCar.year+"</small></a></h2>";
                    /*article += "<span class='sub-heading'>A 2 km Metro <strong>Tobalaba</strong></span>";*/
                    article += "<p class='price'>$"+ dataCar.price_per_day +"</p>";
                    article += "<p class='text-right'><a class='btn-a-action' href='<?php echo url_for("profile/reserve?c=") ?>"+ dataCar.id + "' class='reserve'>RESERVAR</a></p>";
                    /*article += "<img src='http://chart.apis.google.com/chart?chst=d_map_pin_letter&chld=" + contador + "|05a4e7|ffffff' />";*/
                    article += "</div>";
                    article += "</div>";
                    article += "</article>";

                    listContent += "<div class='col-md-4'>";
                    listContent += article;
                    listContent += "</div>";
                    
                    mapListContent += article;

                    /*listContent += "<article class='box'>";
                    listContent += "<div class='img-holder'><img src='http://res.cloudinary.com/arriendas-cl/image/fetch/w_134,h_99,c_fill,g_center/" + urlFotoThumbTipo + "' height='99' width='134' alt=''></div>";
                    listContent += "<div class='text-area'>";
                    listContent += "<h2><a href='<?php echo url_for("arriendo-de-autos/rent-a-car") ?>/" + dataCar.brand + dataCar.model + "/" + dataCar.comuna + "/" + dataCar.id + "'>"+ dataCar.brand +" "+ dataCar.model +"<span>, "+dataCar.year+"</span></a></h2>";
                    listContent += "<span class='sub'>Providencia </span>";
                    listContent += "<span class='sub-heading'>A 2 km Metro <strong>Tobalaba</strong></span>";
                    listContent += "<span class='price'>$"+ dataCar.price_per_day +"</span>";
                    listContent += "<a href='<?php echo url_for("profile/reserve?id=") ?>"+ dataCar.id + "' class='reserve'>RESERVAR</a>";
                    listContent += "</div>";
                    listContent += "</article>";*/
                }
            } else {
                /*mapListContent += "<article class='box'>";*/
                mapListContent += "<h2 style='text-align: center'>No hemos encontrado vehículos</h2>";
                /*mapListContent += "</article>";*/

                listContent += "<h2 style='text-align: center'>No hemos encontrado vehículos</h2>";
            }

            $("#map-list-container").html(mapListContent);
            $("#list-container").html(listContent);

            $('.loading').hide();
            $("#map-list-container, #list-container").show();

            var mcOptions = {
                maxZoom: 10
            };

            markerCluster = new MarkerClusterer(map, markers, mcOptions);
        }, "json");
    }

    // Video
    function playPause() { 

        var V = document.getElementById("video");

        if (V.paused) {
            V.play(); 
        } else {
            V.pause(); 
        }
    }
</script>

<section id="section-home">

    <!-- <video id="video" width="420">
        <source src="/videos/test.mp4" type="video/mp4">
        <source src="mov_bbb.ogg" type="video/ogg">
        Your browser does not support HTML5 video.
    </video> -->

    <div id="section-home-background">
        <img id="section-home-background-img" src="/images/newDesign/background-home.jpg">
    </div>

    <div class="col-sm-offset-1 col-sm-10 col-md-offset-2 col-md-8">
        
        <!-- Carousel -->
        <div id="section-home-carousel">
            <div>
                <h1>ARRIENDA EL AUTO DE UN VECINO DESDE $17.000 FINALES</h1>
                <h2>en precios finales, todo incluído.</h2>
            </div>
            <div>
                <h1>RENT A CAR SIN TARJETA DE CREDITO. MAYOR AHORRO GARANTIZADO</h1>
                <h2>deposito en garantía opcional. </h2>
            </div>
            <div>
                <h1>PRIMER SISTEMA DE ARRIENDO DE AUTOS ENTRE PERSONAS</h1>
                <h2>hay un auto en tu comuna</h2>
            </div>
        </div>
    </div>
</section>

<section id="section-map">

    <div class="row" id="section-map-form-search">

        <span class="ico-search hidden-xs"><img src="/images/newDesign/ico-search.svg"></span>

        <!-- List -->
        <div class="col-xs-6 col-sm-3 col-md-3" id="region-container">
            <select class="region form-control" id="region">
                <option disabled value="<?php echo $Region->getCodigo() ?>"><?php echo $Region->getNombre() ?></option>
            </select>
        </div>
        <div class="col-xs-6 col-sm-3 col-md-3" id="commune-container">
            <select class="commune form-control" id="commune">
                <option value="0">Comuna</option>
                <?php foreach ($Comunas as $Comuna): ?>
                    <option value="<?php echo $Comuna->getCodigoInterno() ?>"><?php echo ucwords(strtolower($Comuna->getNombre())) ?></option>
                <?php endforeach ?>
            </select>
        </div>
        
        <!-- Map -->
        <div class="hidden-xs col-sm-6 col-md-6" id="direction-container">
            <input class="direction form-control" id="direction" placeholder="Dirección" type="text">
        </div>
        <div class="col-xs-6 col-sm-2 col-md-2" id="from-container">
            <input class="from datetimepicker form-control" id="from" placeholder="Desde" type="text">
        </div>
        <div class="col-xs-6 col-sm-2 col-md-2" id="to-container">
            <input class="to datetimepicker form-control" id="to" placeholder="Hasta" type="text">
        </div>

        <!-- Search -->
        <div class="col-xs-12 col-sm-2 col-md-2 text-center">
            <a class="btn-a-action btn-block" href id="search">Buscar</a>
        </div>
    </div>

    <div class="hidden-xs row" id="section-map-filters">
        <div class="col-md-2 text-center">
            <span class="hidden-xs glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
            <strong>Filtros</strong>
        </div>
        <div class="col-md-7">
            <ul>
                <li><input type="checkbox" name="filter" id="automatic"> Automático</li>
                <li><input type="checkbox" name="filter" id="diesel"> Petrolero</li>
                <li><input type="checkbox" name="filrer" id="pasenger"> Más de 5 pasajeros</li>
            </ul>
        </div>
        <div class="col-md-3 hidden-xs tabset">
            <div class="col-md-6 text-center tab active" data-target="#tab-map"><strong><span class="glyphicon glyphicon-map-marker" aria-hidden="true"></span> Mapa</strong></div>
            <div class="col-md-6 text-center tab" data-target="#tab-list"><strong><span class="glyphicon glyphicon-list" aria-hidden="true"></span> Lista</strong></div>
        </div>
    </div>

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
                <h3 class="text-center" style="margin-top: 5px; padding-top: 10px">Filtros</h3>
            </div>

            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="filters">
                
                <ul class="nav navbar-nav">
                    <li><input type="checkbox" name="filter" id="automatic"> Automático</li>
                    <li><input type="checkbox" name="filter" id="diesel"> Petrolero</li>
                    <li><input type="checkbox" name="filrer" id="pasenger"> Más de 5 pasajeros</li>
                </ul>
            </div><!-- /.navbar-collapse -->
        </div><!-- /.container -->
    </nav>

    <div id="section-map-body">

        <div class="tab-container hidden-xs" id="tab-map">
            <div class="row">
                <div class="col-md-9" id="map">
                    <div id="map-container"></div>
                </div>

                <div class="col-md-3" id="map-list">
                    <div id="map-list-loading" class="loading" style="text-align: center; margin-top: 30%"><?php echo image_tag('ajax-loader.gif', array("width" => "80px", "height" => "80px")) ?></div>
                    <div id="map-list-container"></div>
                </div>
            </div>
        </div>

        <div class="tab-container" id="tab-list">
            <div class="row" id="list">
                <div id="list-loading" class="loading" style="text-align: center; margin-top: 10%"><?php echo image_tag('ajax-loader.gif', array("width" => "80px", "height" => "80px")) ?></div>
                <div class="row" id="list-container"></div>
            </div>
        </div>
    </div>
</section>

<script>

    $(document).ready(function(){

        /*// Si comuna es visible, se preselecciona comuna más hot
        if ($("#commune").is(':visible')) {            
            $("#commune option[value=93]").attr("selected", true);
        }*/

        localizame();
        initialize();

        // Video
        /*document.getElementById("video").play();*/

        // Carousel
        $('#section-home-carousel').slick({
            autoplay: true,
            arrows: false,
            speed: 450
        });
    });

    if ($(window).width() > 768) {

        $('#section-home').css({'height': $(window).height()});
        $('#section-map').css({'height': $(window).height()});

        $("#map, #map-list, #list").css({height: $(window).height() - $("#section-map-form-search").outerHeight() - $("#section-map-filters").outerHeight()});
    }

    $("input[type='checkbox']").change(function(){
        console.log("filter change");
        searchCars();
    });

    $("#search").click(function(e){
        e.preventDefault();
        console.log("search click");
        searchCars();        
    });

    $(".tab").click(function(){

        var target = $(this).data("target");

        if (target == "#tab-map") {
            $("#region-container").hide();
            $("#commune-container").hide();
            $("#direction-container").show();
        }

        if (target == "#tab-list") {
            $("#direction-container").hide();
            $("#region-container").show();
            $("#commune-container").show();
        }
    });

    $('#header .animate').each(function(){

        var target  = $(this).data('target');

        $(this).on('click', function(e) {

            e.preventDefault();

            $('html, body').animate({
                scrollTop: $(target).offset().top
            }, 1250);
        });
    });

    $(".ico-search").on('click', function(e) {

        e.preventDefault();

        $('html, body').animate({
            scrollTop: $("#section-map").offset().top
        }, 1250);
    });

    $(".tab").on('click', function(){

        var target = $(this).data("target");

        $(".tab").removeClass("active");
        $(this).addClass("active");

        $(".tab-container").hide();
        $(target).show();
    });

    $('.datetimepicker').datetimepicker({
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
                dayOfWeek:["Do", "Lu", "Ma", "Mi", "Ju", "Vi", "Sa"]
            }
        },
        format:'d-m-Y H:i'
    });
</script>
