<script src="http://maps.googleapis.com/maps/api/js?libraries=places&amp;sensor=false" type="text/javascript"></script>
<script src="js/designResponsive/markerclusterer.js" type="text/javascript"></script>
<script type="text/javascript">

    google.maps.event.addDomListener(window, 'load', initialize);

    var usuarioLogeado = "<?php echo $usuarioLog; ?>";

    var geolocalizacion; // coordenadas
    var latitud; // coordenadas
    var lastValidCenter; // initialize
    var longitud; // coordenadas
    var map; // initialize, searchMarkers
    var markerCluster; // searchMarkers
    var markers = []; // searchMarkers
    var strictBounds = null; // initialize

    var swLat; // searchMarkers
    var swLng; // searchMarkers
    var neLat; // searchMarkers
    var neLng; // searchMarkers

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

        map = new google.maps.Map(document.getElementById('map'), {
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
            searchMarkers();
        });

        google.maps.event.addListener(map, 'dragend', function() {
            
            if (strictBounds === null || strictBounds.contains(map.getCenter())) {
                lastValidCenter = map.getCenter();
            } else {
                map.panTo(lastValidCenter);
            }
            
            searchMarkers();
        });

        google.maps.event.addListener(map, 'zoom_changed', function() {

            if (strictBounds === null || strictBounds.contains(map.getCenter())) {
                lastValidCenter = map.getCenter();
            } else {
                map.panTo(lastValidCenter);
            }

            searchMarkers();
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

            searchMarkers();
            //doSearch();
        });
    }
        
    function localizame() {
        if (navigator.geolocation) { // Si el navegador tiene geolocalizacion
            navigator.geolocation.getCurrentPosition(coordenadas, errores);
        } else {
            alert('¡Oops! Tu navegador no soporta geolocalización. Bájate Chrome, que es gratis!');
        }
    }

    function searchMarkers() {

        $('#map-list-container').fadeOut('fast', function() {
            $("#map-list-loading").fadeIn("fast");
        });

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

        /*var hour_from = document.getElementById("hour_from_hidden").value;
        var hour_to = document.getElementById("hour_to_hidden").value;
        var brand_hidden = encodeURIComponent(document.getElementById("brand_hidden").value);
        var location_hidden = encodeURIComponent(document.getElementById("location_hidden").value);
        var price_hidden = encodeURIComponent(document.getElementById("price_hidden").value);
        var transmision_hidden = encodeURIComponent(document.getElementById("transmision_hidden").value);
        var tipo_hidden = encodeURIComponent(document.getElementById("tipo_hidden").value);
        var model_hidden = encodeURIComponent(document.getElementById("model_hidden").value);
        var day_from_hidden = encodeURIComponent(document.getElementById("day_from_hidden").value);
        var day_to_hidden = encodeURIComponent(document.getElementById("day_to_hidden").value);

        if (price_hidden != '-') {
            precio = "&price=" + price_hidden;
        } else {
            var center = map.getCenter();
            var lat = center.lat();
            var lon = center.lng();
            var start_price = "0";
            precio = "&clat=" + lat + "&clng=" + lon + "&price=" + start_price;
        }

        var center = map.getCenter();
        var map_lat = center.lat();
        var map_lng = center.lng();
        var region = "";
        var comuna = "";*/

        /*var url = "<?php echo url_for('main/map') ?>?day_from=" + day_from_hidden + "&day_to=" + day_to_hidden + "&model=" + model_hidden + "&hour_from=" + hour_from + "&hour_to=" + hour_to + "&brand=" + brand_hidden + "&transmission=" + transmision_hidden + "&type=" + tipo_hidden + "&location=" + location_hidden + precio + "&swLat=" + swLat + "&swLng=" + swLng + "&neLat=" + neLat + "&neLng=" + neLng + "&map_clat=" + map_lat + "&map_clng=" + map_lng + "";
        if (region && region.length > 0 && region != 0) {
            url += "&region=" + region;
        }
        if (comuna && comuna.length > 0 && comuna != 0) {
            url += "&comuna=" + comuna;
        }*/

        //document.write(var_dump(url,'html'));

        var center = map.getCenter();
        var map_center_lat = center.lat();
        var map_center_lng = center.lng();

        var parameters = {
            from: $("#from").val(),
            to: $("#to").val(),
            swLat: swLat,
            swLng: swLng,
            neLat: neLat,
            neLng: neLng,
            map_center_lat: map_center_lat,
            map_center_lng: map_center_lng
        }

        $.post("<?php echo url_for('main/getCars') ?>", parameters, function(response){

            var contador = 0;
            var nodes = '';
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
                contentString += "<div class='infowindow' id='" + dataCar.id + "'>";
                contentString += "<div class='image'>";
                contentString += "<a href='" + urlFotoTipo + "' class='thickbox'>";
                contentString += "<img src='http://res.cloudinary.com/arriendas-cl/image/fetch/w_112,h_84,c_fill,g_center/" + urlFotoThumbTipo + "'/>";
                contentString += "</a><br><br>";
                contentString += "<a target='_blank' href='<?php echo url_for('profile/reserve?id=') ?>" + dataCar.id + "' class='reserve'>RESERVAR</a>";
                contentString += "</div>";
                contentString += "<div class='detail'>";
                contentString += "<div class='detail-title'>";
                contentString += "<a target='_blank' title='Ir al perfil del auto' href='<?php echo url_for('arriendo-de-autos/rent-a-car') ?>/" + dataCar.brand + dataCar.model + "/" + dataCar.comuna + "/" + dataCar.id + "'>" + dataCar.brand + " " + dataCar.model + "</a>";
                contentString += "<div class='images'>"+ verificado +"</div>";
                contentString += "</div>";
                contentString += "<div class='detail-car-data'>";
                contentString += "<p>Dia: <b>$" + dataCar.price_per_day + " CLP</b></p>";
                contentString += "<p>Hora: <b>$" + dataCar.price_per_hour + " CLP</b></p>";
                contentString += "<p>Transmisión: <b>" + dataCar.typeTransmission + "</b></p>";
                contentString += "</div>";
                contentString += opcionLogeado;
                contentString += "</div>";
                
                contentString += "</div>";

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

                var odd = "";
                if (i % 2 != 0) {
                    odd = "odd";
                }

                nodes += "<article class='box "+odd+"'>";
                nodes += "<div class='img-holder'><img src='http://res.cloudinary.com/arriendas-cl/image/fetch/w_134,h_99,c_fill,g_center/" + urlFotoThumbTipo + "' height='99' width='134' alt='image description'></div>";
                nodes += "<div class='text-area'>";
                nodes += "<h2><a href='<?php echo url_for("arriendo-de-autos/rent-a-car") ?>/" + dataCar.brand + dataCar.model + "/" + dataCar.comuna + "/" + dataCar.id + "'>"+ dataCar.brand +" "+ dataCar.model +"<span>, "+dataCar.year+"</span></a></h2>";
                /*nodes += "<span class='sub-heading'>A 2 km Metro <strong>Tobalaba</strong></span>";*/
                nodes += "<span class='price'>$"+ dataCar.price_per_day +"</span>";
                nodes += "<a href='<?php echo url_for("profile/reserve?id=") ?>"+ dataCar.id + "' class='reserve'>RESERVAR</a>";
                nodes += "<img src='http://chart.apis.google.com/chart?chst=d_map_pin_letter&chld=" + contador + "|05a4e7|ffffff' />";
                nodes += "</div>";
                nodes += "</article>";
            }

            $('#map-list-container').stop(true, true);
            $("#map-list-loading").stop(true, true);

            $("#map-list-loading").hide();
            $("#map-list-container").html(nodes);
            $('#map-list-container').fadeIn("fast");

            var mcOptions = {
                maxZoom: 10
            };

            markerCluster = new MarkerClusterer(map, markers, mcOptions);
        }, "json");
    }
</script>

<div class="side-slide">
    <div id="wrapper">
        <div class="container">
            <header id="header">
                <div class="logo">
                    <a href="<?php echo url_for('main/index') ?>" class="logo-desktop"><img src="images/designResponsive/logo.svg" height="19" width="123" alt="Arriendas.cl"></a>
                    <a href="<?php echo url_for('main/index') ?>" class="logo-mobile"><img src="images/designResponsive/logo-mobile.svg" height="16" width="22" alt="Arriendas.cl"></a>
                </div>
                <div class="nav-bar">
                    <nav id="nav">
                        <a href="#" class="nav-opener">Menu</a>
                        <ul class="nav-desktop">
                            <li><a href="<?php echo url_for('como_funciona/index') ?>">¿CÓMO FUNCIONA?</a></li>
                            <li><a href="<?php echo url_for('en_los_medios/index') ?>">EN LAS NOTICIAS</a></li>
                            <li><a href="https://arriendascl.zendesk.com/forums" target="_blank">AYUDA</a></li>
                        </ul>
                        <div class="nav-drop">
                            <ul class="nav-mobile">
                                <li><a href="#">RESERVAS</a></li>
                                <li><a href="#">MI PERFIL</a></li>
                                <li><a href="#">MENSAJES</a></li>
                                <li><a href="#">CALIFICACIONES</a></li>
                                <li><a href="#">SUBE UN AUTO</a></li>
                            </ul>
                        </div>
                    </nav>
                    <div class="right-area">
                        <a href="tel:0223333714" class="tel-link">(02) 2 333 37 14</a>
                        <a href="<?php echo url_for('main/login') ?>" class="login"><span>INGRESA</span></a>
                    </div>
                </div>
            </header>
            <div class="slideshow">
                <div class="slideset">
                    <div class="slide">
                        <img src="images/designResponsive/img1.jpg" height="586" width="1281" alt="image description" class="img-desktop">
                        <img src="images/designResponsive/img1-small.jpg" height="115" width="320" alt="image description" class="img-mobile">
                        <div class="outer">
                            <div class="text-holder">
                                <div class="text-area">
                                    <h1>ARRIENDA EL AUTO DE UN VECINO DESDE $17.000 FINALES</h1>
                                    <span class="sub-heading">en precios finales, todo incluído</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="slide">
                        <img src="images/designResponsive/img1.jpg" height="586" width="1281" alt="image description" class="img-desktop">
                        <img src="images/designResponsive/img1-small.jpg" height="115" width="320" alt="image description" class="img-mobile">
                        <div class="outer">
                            <div class="text-holder">
                                <div class="text-area">
                                    <h1>ARRIENDA EL AUTO DE UN VECINO DESDE $17.000 FINALES</h1>
                                    <span class="sub-heading">en precios finales, todo incluído</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-holder">
                <form accept-charset="#" class="from-search">
                    <span class="ico-search">search</span>
                    <input class="direction" id="direction" placeholder="Dirección" type="text">
                    <!-- <select class="region">
                        <option>Región</option>
                    </select>
                    <select class="comuna">
                        <option>Comuna</option>
                    </select> -->
                    <input class="from datetimepicker" id="from" placeholder="Desde" type="text">
                    <input class="destination datetimepicker" id="to" placeholder="Hasta" type="text">
                    <input type="submit" value="BUSCAR">
                </form>
            </div>
        </div>
        <main id="main" role="main">
            <section class="tab-holder">
                <div class="top-area">
                    <strong class="heading">Filtros</strong>
                    <form action="#" class="filters">
                        <div class="row"><input type="checkbox" name="filter" id="automatic">
                        <label for="automatic">Automático</label></div>
                        <div class="diesel row"><input type="checkbox" name="filter" id="diesel">
                        <label for="diesel">Diesel</label></div>
                        <div class="low-consumption row"><input type="checkbox" name="filter" id="low">
                        <label for="low">Bajo Consumo</label></div>
                        <div class="row"><input type="checkbox" name="filrer" id="pasenger">
                        <label for="pasenger">Más de 5 pasajeros</label></div>
                    </form>
                </div>
                <div class="tab-area">
                    <ul class="tabset">
                        <li class="active"><a href="#tab1"><span>MAPA</span></a></li>
                        <!-- <li><a href="#tab2"><span>LISTA</span></a></li> -->
                        <li>&nbsp;</li>
                    </ul>
                    <div class="tab-content">
                        <div id="tab1">
                            <div class="map-holder" id="map" style="height:575px">
                            </div>
                            <div class="list" id="map-list">
                                <div id="map-list-loading" style="text-align: center; margin-top: 100px"><?php echo image_tag('ajax-loader.gif', array("width" => "80px", "height" => "80px")) ?></div>
                                <div id="map-list-container">
                                    <!-- <article class="box">
                                        <div class="img-holder"><img src="images/designResponsive/img3.jpg" height="99" width="134" alt="image description"></div>
                                        <div class="text-area">
                                            <h2><a href="#">Kia Morning, <span>City Car</span></a></h2>
                                            <span class="sub-heading">A 2 km Metro <strong>Tobalaba</strong></span>
                                            <span class="price">$18.000</span>
                                            <a href="#" class="reserve">RESERVAR</a>
                                        </div>
                                    </article>
                                    <article class="box odd">
                                        <div class="img-holder"><img src="images/designResponsive/img3.jpg" height="99" width="134" alt="image description"></div>
                                        <div class="text-area">
                                            <h2><a href="#">Kia Morning, <span>City Car</span></a></h2>
                                            <span class="sub-heading">A 2 km Metro <strong>Tobalaba</strong></span>
                                            <span class="price">$18.000</span>
                                            <a href="#" class="reserve">RESERVAR</a>
                                        </div>
                                    </article> -->
                                </div>
                                <!-- <a class="more" href="#">ver más</a> -->
                            </div>
                        </div>
                        <!-- <div id="tab2">
                            <div class="data-holder">
                                <div class="row odd">
                                    <article class="box">
                                        <div class="img-holder"><img src="images/designResponsive/img3.jpg" height="99" width="134" alt="image description"></div>
                                        <div class="text-area">
                                            <h2><a href="#">Kia Morning, <span>City Car</span></a></h2>
                                            <span class="sub">Providencia </span>
                                            <span class="sub-heading">A 2 km Metro <strong>Tobalaba</strong></span>
                                            <span class="price">$18.000</span>
                                            <a href="#" class="reserve">RESERVAR</a>
                                        </div>
                                    </article>
                                    <article class="box">
                                        <div class="img-holder"><img src="images/designResponsive/img3.jpg" height="99" width="134" alt="image description"></div>
                                        <div class="text-area">
                                            <h2><a href="#">Kia Morning, <span>City Car</span></a></h2>
                                            <span class="sub">Providencia </span>
                                            <span class="sub-heading">A 2 km Metro <strong>Tobalaba</strong></span>
                                            <span class="price">$18.000</span>
                                            <a href="#" class="reserve">RESERVAR</a>
                                        </div>
                                    </article>
                                </div>
                            </div>
                            <a class="more" href="#">ver más</a>
                        </div> -->
                    </div>
                </div>
            </section>
            <section class="video-holder">
                <div class="video-box">
                    <h2 class="title"><span>¿Cómo Funciona?</span></h2>
                    <iframe src="//player.vimeo.com/video/45668172" width="500" height="281" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
                </div>
            </section>
            <section class="block">
                <div class="price-holder">
                    <h2 class="title"><span>Compara Precios</span></h2>
                    <div class="table-holder">
                        <table>
                            <tr>
                                <th></th>
                                <th><span class="th"><img src="images/designResponsive/logo-avis.svg" height="18" width="52" alt="AVIS"></span></th>
                                <th><span class="th"><img src="images/designResponsive/logo-hertz.svg" height="19" width="61" alt="Hertz"></span></th>
                                <th><span class="th"><img src="images/designResponsive/logo-eurorpcar.svg" height="21" width="112" alt="Europcar"></span></th>
                                <th class="last"><span class="th"><img src="images/designResponsive/logo.svg" height="19" width="123" alt="Arriendas.cl"></span></th>
                            </tr>
                            <tr>
                                <td><span class="td">Mediano</span></td>
                                <td><span class="td">$ 48.138 CLP <span>Hyundai i 10</span></span></td>
                                <td><span class="td">$ 48.138 CLP <span>Hyundai i 10</span></span></td>
                                <td><span class="td">$ 48.138 CLP <span>Hyundai i 10</span></span></td>
                                <td class="last"><span class="td">$ 48.138 CLP <span>Hyundai i 10</span></span></td>
                            </tr>
                            <tr>
                                <td><span class="td">CamionetaSUV</span></td>
                                <td><span class="td">$ 48.138 CLP <span>Hyundai i 10</span></span></td>
                                <td><span class="td">$ 48.138 CLP <span>Hyundai i 10</span></span></td>
                                <td><span class="td">$ 48.138 CLP <span>Hyundai i 10</span></span></td>
                                <td class="last"><span class="td">$ 48.138 CLP <span>Hyundai i 10</span></span></td>
                            </tr>
                            <tr>
                                <td><span class="td">Furgon Utilitario</span></td>
                                <td><span class="td">$ 48.138 CLP <span>Hyundai i 10</span></span></td>
                                <td><span class="td">$ 48.138 CLP <span>Hyundai i 10</span></span></td>
                                <td><span class="td">$ 48.138 CLP <span>Hyundai i 10</span></span></td>
                                <td class="last"><span class="td">$ 48.138 CLP <span>Hyundai i 10</span></span></td>
                            </tr>
                            <tr>
                                <td><span class="td">City Car Economico</span></td>
                                <td><span class="td">$ 48.138 CLP <span>Hyundai i 10</span></span></td>
                                <td><span class="td">$ 48.138 CLP <span>Hyundai i 10</span></span></td>
                                <td><span class="td">$ 48.138 CLP <span>Hyundai i 10</span></span></td>
                                <td class="last"><span class="td">$ 48.138 CLP <span>Hyundai i 10</span></span></td>
                            </tr>
                        </table>
                    </div>
                    <p>Precios con IVA, aplicando descuento por reservas en internet, con seguro de daños, robo y accidentes personales. Muestra tomada 4/4/2013 en sus páginas de internet</p>
                </div>
                <div class="logo-holder">
                    <h2 class="title"><span>Arriendas en las noticias</span></h2>
                    <div class="carousel">
                        <div class="mask">
                            <div class="slideset">
                                <div class="slide">
                                    <ul class="logo-list">
                                        <li><a href="#"><img src="images/designResponsive/logo2.png" height="59" width="183" alt="terra"></a></li>
                                        <li><a href="#"><img src="images/designResponsive/logo3.png" height="57" width="137" alt="pulso"></a></li>
                                        <li><a href="#"><img src="images/designResponsive/logo4.png" height="51" width="191" alt="publimetr"></a></li>
                                        <li><a href="#"><img src="images/designResponsive/logo5.png" height="79" width="79" alt="13"></a></li>
                                    </ul>
                                </div>
                                <div class="slide">
                                    <ul class="logo-list">
                                        <li><a href="#"><img src="images/designResponsive/logo2.png" height="59" width="183" alt="terra"></a></li>
                                        <li><a href="#"><img src="images/designResponsive/logo3.png" height="57" width="137" alt="pulso"></a></li>
                                        <li><a href="#"><img src="images/designResponsive/logo4.png" height="51" width="191" alt="publimetr"></a></li>
                                        <li><a href="#"><img src="images/designResponsive/logo5.png" height="79" width="79" alt="13"></a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <a class="btn-prev" href="#">Previous</a>
                        <a class="btn-next" href="#">Next</a>
                    </div>
                </div>
            </section>
            <section class="testimonials">
                <img src="images/designResponsive/img4.jpg" height="609" width="1281" alt="image description">
                <div class="outer">
                    <div class="holder">
                        <h2>TESTIMONIOS</h2>
                        <blockquote>
                            <q>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed sollicitudin nisl in rutrum dapibus.Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed sollicitudin nisl in rutrum dapibus. </q>
                            <cite><strong>Javiera Cruzat,</strong> <em>Usuario de Arriendas</em></cite>
                        </blockquote>
                    </div>
                </div>
            </section>
            <section class="terms-box">
                <h2 class="title"><span>Condiciones de Arriendo</span></h2>
                <div class="text-area">
                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed sollicitudin nisl in rutrum dapibus. </p>
                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed sollicitudin nisl in rutrum dapibus. </p>
                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed sollicitudin nisl in rutrum dapibus. </p>
                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed sollicitudin nisl in rutrum dapibus. </p>
                </div>
                <div class="link-holder">
                    <a href="#">INGRESAR</a>
                </div>
            </section>
        </main>
    </div>
</div>

<script>
    $(document).ready(function(){

        // redimensión del mapa según el ancho de la ventana
        $('#map').css({'width': ($(window).width()-$("#map-list").width())});
        /*google.maps.event.trigger(map, 'resize');*/

        localizame();    
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
