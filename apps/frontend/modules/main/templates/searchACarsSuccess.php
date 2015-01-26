<link href="/css/newDesign/searchACars.css" rel="stylesheet" type="text/css">

<!-- Google Maps -->
<script src="http://maps.googleapis.com/maps/api/js?libraries=places&amp;sensor=false" type="text/javascript"></script>
<script src="/js/newDesign/markerclusterer.js" type="text/javascript"></script>

<!-- Varios -->
<script type="text/javascript">

    /*google.maps.event.addDomListener(window, 'load', initialize);*/

        var reserveUrl = "<?php echo url_for('reserve', array('c' => 'carId', 'f' => 'from', 't' => 'to'), true) ?>";

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
            searchCars();
        });

        google.maps.event.addListener(map, 'dragend', function() {

            if (strictBounds === null || strictBounds.contains(map.getCenter())) {
                lastValidCenter = map.getCenter();
            } else {
                map.panTo(lastValidCenter);
            }
            searchCars();
        });

        google.maps.event.addListener(map, 'zoom_changed', function() {

            if (strictBounds === null || strictBounds.contains(map.getCenter())) {
                lastValidCenter = map.getCenter();
            } else {
                map.panTo(lastValidCenter);
            }
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

    function validateTime(){

        var fechaF = splitTime($("#from").val());
        var fechaT = splitTime($("#to").val());

        console.log(fechaF);
        console.log(fechaT);

        if(fechaF > fechaT){
            return true;
        }
        return false;
    }

    function splitTime(time){
        var split = time.split(" ");
        var f = split[0];
        var h = split[1];

        var split = f.split("-");
        var dia = split[0];
        var mes = split[1];
        var ano = split[2];

        return (mes+dia+ano);
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

                    contentString += "<p class='text-right'><a class='btn-a-action' href='"+reserveUrl.replace("carId", dataCar.id).replace("from", dataCar.from).replace("to", dataCar.to)+"' target='_blank'>RESERVAR</a></p>";

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
                    article += "<div class='col-xs-4 col-md-4 image'>";
                    article += "<img class='car' src='http://res.cloudinary.com/arriendas-cl/image/fetch/w_134,h_99,c_fill,g_center/" + urlFotoThumbTipo + "' height='99' width='134' alt=''>";
                    article += "<img class='marker' src='http://chart.apis.google.com/chart?chst=d_map_pin_letter&chld=" + contador + "|05a4e7|ffffff'>"
                    article += "</div>";
                    article += "<div class='col-xs-8 col-md-8 text'>";
                    article += "<h2><a target='_blank' href='"+reserveUrl.replace("carId", dataCar.id).replace("from", dataCar.from).replace("to", dataCar.to)+"'>"+ dataCar.brand +" "+ dataCar.model +"<small>, "+dataCar.year+"</small></a></h2>";
                    /*article += "<span class='sub-heading'>A 2 km Metro <strong>Tobalaba</strong></span>";*/
                    article += "<p class='price'>$"+ dataCar.priceAPuntos +" <small>Total</small></p>";
                    article += "<p class='text-right'><a class='btn-a-action' href='"+reserveUrl.replace("carId", dataCar.id).replace("from", dataCar.from).replace("to", dataCar.to)+"' class='reserve' target='_blank'>RESERVAR</a></p>";
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
                mapListContent += "<h2 style='text-align: center'>No hemos encontrado vehículos</h2>";
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
</script>

<div class="hidden-xs space-100"></div>
<div class="visible-xs space-50"></div>

<div class="row">
    <div class="col-md-offset-1 col-md-10">
        <div class="BCW">
            <h1>¡Busca un Auto!</h1>


            <!-- List -->
        <div class="col-xs-6 col-sm-3 col-md-3" id="region-container">
            <select class="region form-control" id="region">
                <option disabled value="<?php echo $Region->id ?>"><?php echo $Region->name ?></option>
            </select>
        </div>
        <div class="col-xs-6 col-sm-3 col-md-3" id="commune-container">
            <select class="commune form-control" id="commune">
                <option value="0">Comuna</option>
                <?php foreach ($Region->getCommunes() as $Commune): ?>
                    <?php if ($hasCommune && $Commune->id == $hasCommune): ?>
                        <option selected value="<?php echo $Commune->id ?>"><?php echo ucwords(strtolower($Commune->name)) ?></option> 
                    <?php else: ?>
                        <option value="<?php echo $Commune->id ?>"><?php echo ucwords(strtolower($Commune->name)) ?></option>
                    <?php endif ?>
                <?php endforeach ?>
            </select>
        </div>

        </div>
    </div>
</div>

<div class="hidden-xs space-100"></div>