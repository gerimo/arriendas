<link href="/css/newDesign/indexMobile.css" rel="stylesheet" type="text/css">

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
    
	function searchCars(offset, limit) {

        if (offset == 0) {
            $('#list-container').hide();
        }
        $(".loading").show();

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
        var haveChair = false;
        $(".haveChair").each(function(){
            if ($(this).is(':checked')) {
                haveChair = true;
            }
        });

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
            offset: offset,
            limit: limit,
            from : from,
            to: to,
            regionId: regionId,
            communeId: communeId,
            isAutomatic: isAutomatic,
            isLowConsumption: isLowConsumption,
            isMorePassengers: isMorePassengers,
            haveChair: haveChair,
            nearToSubway: nearToSubway
        };
        console.log(parameters);

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
                }
            } else {
                if (offset) {
                    listContent += "<div class='col-md-12'><p class='msg-search' style='padding-top: 3%'>Ya se están mostrando todos los autos disponibles para las fechas indicadas</p></div>";
                }else {
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
                console.log(r.cars.length+" < "+limit);
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
</script>

<section id="section-home">
    <div class="col-sm-offset-1 col-sm-10 col-md-offset-2 col-md-8">
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
            <!-- <button id="btn-leese" data-target="#section-map-form-search">¡ARRIENDA AHORA!</button> -->
        </div>
    </div>

	 <div class="row" id="section-map-form-search">

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
        <div class="col-xs-6 col-sm-2 col-md-2" id="from-container">
            <input class="from daepicker form-control" id="from" placeholder="Desde" readonly="readonly" type="text" value="<?php if(date("H:i") >= "20:00" || date("H:i") <= "08:00"):echo date("d-m-Y 08:00",(strtotime ("+12 Hours"))); else:echo date("d-m-Y H:i", (strtotime ("+4 Hours"))); endif; ?>" >
        </div>
        <div class="col-xs-6 col-sm-2 col-md-2" id="to-container">
            <input class="to datetimepicker form-control" id="to" placeholder="Hasta" readonly="readonly" type="text" value="<?php if(date("H:i") >= "20:00" || date("H:i") <= "08:00"):echo date("d-m-Y 08:00",(strtotime ("+32 Hours"))); else:echo date("d-m-Y H:i", (strtotime ("+24 Hours"))); endif; ?>" >
        </div>

        <!-- Search -->
        <div class="col-xs-12 col-sm-2 col-md-2 text-center">
            <button class="btn btn-a-action btn-block" id="search" type="button">Buscar</button>
        </div>
    </div>
</section>

<section id="section-map">
	<nav class="navbar navbar-default" id="filters-navbar" role="navigation">
        <div class="hidden-xs row" id="section-map-filters">
        </div>
        <div class="container">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-filters">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#filters">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <h3 style="margin-top: 5px; padding-top: 12px">Filtros</h3>
            </div>
            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="filters">
                <div class="container">
                    <ul class="nav navbar-nav">
                        <li><input type="checkbox" name="filter" class="isAutomatic"> Automático</li>
                        <li><input type="checkbox" name="filter" class="isLowConsumption"> Bajo Consumo</li>
                        <li><input type="checkbox" name="filter" class="isMorePassengers"> Más de 5 pasajeros</li>
                        <li><input type="checkbox" name="filter" class="nearToSubway"> Cercano al metro</li>
                        <li><input type="checkbox" name="filter" class="haveChair"> Silla de bebe</li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>

    <div id="section-map-body">
        <div class="tab-container" id="tab-list">
            <div class="row" id="list">
                <div class="row" id="list-container"></div>
                <div id="list-loading" class="loading" style="text-align: center; margin: 4% 0 4% 0">
                    <?php if ($isMobile): ?>
                        <?php echo image_tag('ajax-loader.gif', array("width" => "40px", "height" => "40px")) ?>
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
</section>

<section  id="section-condition">
    <div class="row">
        <div class="col-xs-offset-1 col-xs-11">
            <h2 class="title"><span>Condiciones de Arriendo</span></h2>
        </div>
        <div class="text-center">
            <p>Debes tener al menos 24 años para poder arrendar.</p>
            <p>Tienes que contar con una licencia de conducir valida.</p>
            <p>Debes pagar con una cuenta bancaria a tu nombre (no puede ser de un tercero).</p>
        </div>
    </div>
    <div class="space-40"></div>
</section>

<div style="display:none">
    <div id="dialog-alert" title="">
        <p></p>
    </div>
</div>
<script>
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
		// Carousel del Home 
        $('#section-home-carousel').slick({
            autoplay: true,
            arrows: false,
            speed: 450,
            infinite: true
        });

        $("button.see-more").click();
        $("#from").val(roundTime($("#from").val()));
        $("#to").val(roundTime($("#to").val()));
	})
    
    if(($(window).height())<"700"){
        console.log("hola");
        $('#section-home').css({
            'height': $(window).height() + $("#from-container").outerHeight()+ $("#from-container").outerHeight()
        });

    } else {
        $('#section-home').css({
            'background-size': "100% 100%"
        });
    }

    $("input[type='checkbox']").change(function(){
        searchCars(0, $("button.see-more").data("limit"));
    });

    $("#search").click(function(e){
        e.preventDefault();
        searchCars(0, $("button.see-more").data("limit"));
    });

    $("#commune").change(function(){
        searchCars(0, $("button.see-more").data("limit")); 
    });

    $(document).on("click", ".see-more", function(){
        searchCars($("button.see-more").data("offset"), $("button.see-more").data("limit"));
    });

	/*$("#btn-leese").on('click', function(e) {
        
        e.preventDefault();

        var target  = $(this).data('target');
        var position = $(target).offset().top - 50;
        $('html, body').animate({
            scrollTop: position
        }, 1250);
    });*/

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