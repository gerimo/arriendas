<?php use_stylesheet('search.css') ?>
<?php use_stylesheet('landing.css') ?>
<?php use_stylesheet('registro.css') ?>
<?php use_stylesheet('comparaprecios.css') ?>

<?php
$useragent=$_SERVER['HTTP_USER_AGENT'];
if(preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i',$useragent)||preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i',substr($useragent,0,4))){
  use_stylesheet('moviles.css');
}
?>
<style>
    .marcadores{
        width: 30px;
        float: left;
        padding: 14px 0;
    }
    .marcadores img{

    }
    li.sep{
        margin-top: 7px;
        font-size: 11px;
    }
    a.link_user{
        color: #05a4e7;
        text-decoration: none;
        border-bottom: 1px solid #05a4e7;
    }
    .img_verificado{
        width: 14px;
        height: 15px;
    }
    .land_ultauto_item {
        margin-right:20px;
    }
    /* css for timepicker */
    .ui-timepicker-div .ui-widget-header { margin-bottom: 8px; }
    .ui-timepicker-div dl { text-align: left; }
    .ui-timepicker-div dl dt { height: 25px; margin-bottom: -25px; }
    .ui-timepicker-div dl dd { margin: 0 10px 10px 65px; }
    .ui-timepicker-div td { font-size: 90%; }
    .ui-tpicker-grid-label { background: none; border: none; margin: 0; padding: 0; }


    .ui-widget { font-family: Lucida Grande, Lucida Sans, Arial, sans-serif; font-size: 0.8em; }


    

    .time-picker { width:121px;}

    .comparaPrecio{
        height: 30px;
        margin-top: 10px;
        text-align: center;
        text-decoration: none;
        font-weight: bold;
        color: #ec00b8;
    }

    .img_instrucciones_2{
        cursor: pointer;
    }
 	
</style>

<?php
$usuarioLog = 0;
if (sfContext::getInstance()->getUser()->getAttribute("logged")){
    $usuarioLog = 1;
}else{
    $usuarioLog = 2;
}

?>

<script type="text/javascript">

    var usuarioLogeado =  "<?php echo $usuarioLog; ?>";

    var map;
    var markerCluster;
    var markers = [];
    var swLat;
    var swLng;
    var neLat;
    var neLng;
    var latitud;
    var longitud;
    var geolocalizacion;   
    var _data; 
    
    function localizame() {
        if (navigator.geolocation) { /* Si el navegador tiene geolocalizacion */
            navigator.geolocation.getCurrentPosition(coordenadas, errores);
        }else{
            alert('Oops! Tu navegador no soporta geolocalización. Bájate Chrome, que es gratis!');
        }
    }
    
	function crearMarca(position){
			
		marker = new google.maps.Marker({
			position: position,
			map: map,
			draggable: true,
		});
		return marker;
	}
        
    function coordenadas(position) {
        latitud = position.coords.latitude; /*Guardamos nuestra latitud*/
        longitud = position.coords.longitude; /*Guardamos nuestra longitud*/
        <?php if($sf_user->getAttribute('geolocalizacion')==true):?>
            geolocalizacion = true;
        <?php else: ?>
            geolocalizacion = false;
        <?php endif; ?>
        //initialize();
    }
        
    function errores(err) {
        /*Controlamos los posibles errores */
        if (err.code == 0) {
            alert("Oops! Algo ha salido mal");
        }
        if (err.code == 1) {
            alert("Oops! No has aceptado compartir tu posición");
        }
        if (err.code == 2) {
            alert("Oops! No se puede obtener la posición actual");
        }
        if (err.code == 3) {
            alert("Oops! Hemos superado el tiempo de espera");
        }
    }
	
    google.maps.event.addDomListener(window, 'load', initialize);

    function initialize() {

	console.log('initialize gmaps')
	

//	alert('stop');
	initialize2();

		<?php
		if (stripos($_SERVER['SERVER_NAME'], "arrendas") !== FALSE)
		    echo "var center = new google.maps.LatLng(-34.59, -58.401604);";
		else
		    echo "var center = new google.maps.LatLng(-33.427224, -70.605558);";
		?> 
    	if(geolocalizacion){    
            center = new google.maps.LatLng(latitud,longitud);

        }

<?php if(isset($_GET['ciudad']) && $_GET['ciudad']=="arica"): ?>
center = new google.maps.LatLng(-18.32,-70.20);
<?php endif; ?>
<?php if(isset($_GET['ciudad']) && $_GET['ciudad']=="concepcion"): ?>
center = new google.maps.LatLng(-37.00,-72.30);
<?php endif; ?>
<?php if(isset($_GET['ciudad']) && $_GET['ciudad']=="laserena"): ?>
center = new google.maps.LatLng(-29.75,-71.10);
<?php endif; ?>
<?php if(isset($_GET['ciudad']) && $_GET['ciudad']=="temuco"): ?>
center = new google.maps.LatLng(-38.45,-72.40);
<?php endif; ?>
<?php if(isset($_GET['ciudad']) && $_GET['ciudad']=="valparaiso"): ?>
center = new google.maps.LatLng(-33.2,-71.4);
<?php endif; ?>
<?php if(isset($_GET['ciudad']) && $_GET['ciudad']=="viña"): ?>
center = new google.maps.LatLng(-33.0,-71.3);
<?php endif; ?>
 
        map = new google.maps.Map(document.getElementById('map'), {
            zoom: 12,
            center: center,
            mapTypeId: google.maps.MapTypeId.ROADMAP,
            scrollwheel: false
        });

		if(geolocalizacion){
			
		  marker = new google.maps.Marker({
		      position: center,
		      map: map,
		      draggable: true,
		  });
		}

        google.maps.event.addListener(map, 'idle', function () {
            google.maps.event.clearListeners(map, 'idle');
            searchMarkers();  
            doReload();        
        });

        google.maps.event.addListener(map, 'dragend', function () {
            searchMarkers();
            doReload();          
        });
        google.maps.event.addListener(map, 'zoom_changed', function () {	
            searchMarkers();
            doReload();	
        });
		
		//Autocomplete
        var input = document.getElementById('searchTextField');
        var autocomplete = new google.maps.places.Autocomplete(input);

        autocomplete.bindTo('bounds', map);

        var infowindow = new google.maps.InfoWindow();
        var marker = new google.maps.Marker({
          map: map
        });
        
        google.maps.event.addListener(autocomplete, 'place_changed', function() {
          infowindow.close();
          marker.setVisible(false);
          input.className = '';
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
              new google.maps.Size(35, 35));
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
        });

		//Fin Autocomplete
    }

    function doReload(){
	
//        $("#loading").css("display","inline");
		$("#loadingSearch").css("display","table");

        $('.search_arecomend_window').fadeOut('fast',function() {
			$("#loader").fadeIn("fast");		   
  	    });
          
    }

    function mycarousel_initCallback(carousel)
    {
        // Disable autoscrolling if the user clicks the prev or next button.
        carousel.buttonNext.bind('click', function() {
            carousel.startAuto(0);
        });
	
        carousel.buttonPrev.bind('click', function() {
            carousel.startAuto(0);
        });
	
        // Pause autoscrolling if the user moves with the cursor over the clip.
        carousel.clip.hover(function() {
            carousel.stopAuto();
        }, function() {
            carousel.startAuto();
        });
	
        if( carousel.size() < 5){
            carousel.wrarp("first");
            carousel.scroll(5);
            carousel.stopAuto();
        }
    };

    function doSearch()
    {
		console.log('doSearch')
	
        $('.search_arecomend_window').fadeOut('fast');
      	$("#loader").fadeIn("fast");
  	
  	
        document.getElementById("hour_from_hidden").value = document.getElementById("hour_from").value;
        document.getElementById("hour_to_hidden").value = document.getElementById("hour_to").value;
        document.getElementById("price_hidden").value = document.getElementById("price").value;
        document.getElementById("model_hidden").value = document.getElementById("model").value;
        document.getElementById("location_hidden").value = ""; //document.getElementById("location").value;
        document.getElementById("brand_hidden").value = document.getElementById("brand").value;
        document.getElementById("day_from_hidden").value = document.getElementById("day_from").value;
        document.getElementById("day_to_hidden").value = document.getElementById("day_to").value; 

		//valido que hora desde sea mayor a la actual
		var current = new Date();
		if( isValidDate( $('#day_from').val() ) ) {
			
			if( $('#day_from').val() == '<?php echo date('d-m-Y')?>' && isValidTime( $('#hour_from').val() ) ) {
			
				var dif = restarHoras(current.getHours() + ':' + current.getMinutes() + ':' + current.getSeconds(), $('#hour_from').val())
				if( dif < 0 ) { alert('La hora no puede ser menor a la actual'); $('#hour_from').val('Hora de inicio'); }
			}
		}

		if( isValidDate( $('#day_to').val() ) ) {
			
			if( $('#day_to').val() == '<?php echo date('d-m-Y')?>' && isValidTime( $('#hour_to').val() ) ) {
			
				var dif = restarHoras(current.getHours() + ':' + current.getMinutes() + ':' + current.getSeconds(), $('#hour_to').val())
				if( dif < 0 ) { alert('La hora no puede ser menor a la actual'); $('#hour_to').val('Hora de entrega'); }
			}
		}

		//valido que reserva sea de mínimo 1 hora
		if( isValidDate( $('#day_from').val() ) && isValidDate( $('#day_to').val() ) && isValidTime( $('#hour_from').val() ) && isValidTime( $('#hour_to').val() ) ) {

			if( $('#day_from').val() == $('#day_to').val() ) {
				
				var dif = restarHoras($('#hour_from').val(), $('#hour_to').val())
				if( dif < 1 ) { alert('La reserva no puede ser menor a 1 hora'); $('#hour_from').val('Hora de inicio'); $('#hour_to').val('Hora de entrega'); }
			}
		}

		

        searchMarkers();  
        doReload();
        //createClickToMarker();
  	        	
  	
    }

	//Valida formato fecha
	function isValidDate(date){
	  if (date.match(/^(?:(0[1-9]|[12][0-9]|3[01])[\- \/.](0[1-9]|1[012])[\- \/.](19|20)[0-9]{2})$/)){
	    return true;
	  }else{
	    return false;
	  }
	}
	
	function isValidTime(time){
		var objRegExp  = /(^\d{2}:\d{2}:\d{2}$)/;
		
		if(time.match(objRegExp)) {
	      return true;
	    }else{
	      return false;
	    }
	}
	function restarHoras(hora_desde, hora_hasta) {
		
		var hora, min, seg;
		var hora2, min2, seg2;
		var arr = hora_desde.split(':');
		var arr2 = hora_hasta.split(':');
		hora = parseInt(arr[0]); min = parseInt(arr[1]); seg = parseInt(arr[2]);
		hora2 = parseInt(arr2[0]); min2 = parseInt(arr2[1]); seg2 = parseInt(arr2[2]);
		var aux = (hora * 3600) + (min * 60); var aux2 = (hora2 * 3600) + (min2 * 60);
		var dif = parseFloat( (aux2 - aux) / 3600 );
		
		return dif; 
	}


    function searchMarkers() {

        
		//alert('load markers');
		//$('.search_arecomend_window').css("visibility", "hidden");
        //$("#loader").fadeIn("slow");
	

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
	
        var hour_from = document.getElementById("hour_from_hidden").value;
        var hour_to = document.getElementById("hour_to_hidden").value;
        var brand_hidden = encodeURIComponent(document.getElementById("brand_hidden").value);
        var location_hidden = encodeURIComponent(document.getElementById("location_hidden").value);
        var price_hidden = encodeURIComponent(document.getElementById("price_hidden").value);
        var model_hidden = encodeURIComponent(document.getElementById("model_hidden").value);
        var day_from_hidden = encodeURIComponent(document.getElementById("day_from_hidden").value);
        var day_to_hidden = encodeURIComponent(document.getElementById("day_to_hidden").value);    

        if(price_hidden != '-'){
            precio = "&price="+price_hidden;
        } else {
            var center = map.getCenter();
            var lat = center.lat();
            var lon = center.lng();
            var start_price = "0";
            precio = "&clat=" + lat + "&clng=" + lon + "&price=" + start_price;
        }
	    
		
		
			
        var url = "<?php echo url_for('main/map') ?>?day_from=" + day_from_hidden + "&day_to=" + day_to_hidden + "&model=" + model_hidden + "&hour_from=" + hour_from + "&hour_to=" + hour_to + "&brand=" + brand_hidden  + "&location=" + location_hidden  + precio  + "&swLat="+swLat+"&swLng="+swLng+"&neLat="+neLat+"&neLng="+neLng+"";
		 	    
        //document.write(var_dump(url,'html'));

        $.getJSON(url, function(data){
	    	
            if (markers) {
                for (i in markers) {
                    markers[i].setMap(null);
                }
                markers =  new Array();
                markers.length = 0;
            }
            
	    	var contador = 0;
            var nodes = '';
            for (var i = 0; i < data.cars.length; i++) {
                var dataCar = data.cars[i];

				
                var latLng = new google.maps.LatLng(dataCar.latitude,dataCar.longitude);
                if (dataCar.verificado) {
                    var verificado="<img src='http://www.arriendas.cl/images/verificado.png' class='img_verificado' title='Auto Asegurado'/>";
                } else {
                    var verificado="";
                }

                if(dataCar.cantidadCalificacionesPositivas == 0){
                    var calificacionesPositivas = "";
                }else{
                    var calificacionesPositivas = "- " + dataCar.cantidadCalificacionesPositivas + "<img src='http://www.arriendas.cl/images/Medalla.png' class='medallita'/>";
                }

                if(dataCar.userVelocidadRespuesta == ""){
                    velocidadDeRespuesta = "<span style='font-style: italic; color:#BCBEB0;font-weight: normal;'>No tiene mensajes</span>";
                }else{
                    velocidadDeRespuesta = dataCar.userVelocidadRespuesta;
                }

                var opcionLogeado = "";
                if(usuarioLogeado == 1){//Informacion cuando se esta logeado
//                    opcionLogeado = '<div class="detalles_user"><a target="_blank" href="http://www.arriendas.cl/profile/publicprofile/id/' + dataCar.userid + '" class="mapcar_user_titulo" title="Ir al perfil de '+dataCar.firstname +'">' + dataCar.firstname + " " + dataCar.lastname + '</a><a target="_blank" href="http://www.arriendas.cl/messages/new/id/' +dataCar.userid + '" class="mapcar_user_message" title="Env&iacute;ale un mensaje a '+ dataCar.firstname +'"></a></div><div class="datos_user"><div class="img_vel_resp"></div><p>Velocidad de Respuesta: <br><b>'+velocidadDeRespuesta+'</b></p><div class="img_reservas_resp"></div><p>Reservas Respondidas: <b>' + dataCar.reservasRespondidas + '</b></p></div>';
                    opcionLogeado = '<div class="detalles_user"><a target="_blank" href="http://www.arriendas.cl/profile/publicprofile/id/' + dataCar.userid + '" class="mapcar_user_titulo" title="Ir al perfil del dueño">Ver perfil del dueño</a><a target="_blank" href="http://www.arriendas.cl/messages/new/id/' +dataCar.userid + '" class="mapcar_user_message" title="Env&iacute;ale un mensaje al dueño"></a></div><div class="datos_user"><div class="img_vel_resp"></div><p>Velocidad de Respuesta: <br><b>'+velocidadDeRespuesta+'</b></p></div>';
                }else{//Informacion cuando NO se está logeado
                    if (dataCar.verificado) {
                        opcionLogeado = "<div class='info_car_asegurado'><p>Asegurado <span style='font-weight:normal'>(Deducible 5 UF)</span></p><div class='imagenesSeguro'><a title='Seguro contra daños'><div class='img_s1_home'></div></a><p class='sepa'>|</p><a title='Seguro destrucción total'><div class='img_s2_home'></div></a><p class='sepa'>|</p><a title='Seguro de terceros'><div class='img_s3_home'></div></a><p class='sepa'>|</p><a title='Seguro de conductor y acompañante'><div class='img_s4_home'></div></a><p class='sepa'>|</p><a title='Seguro contra robo'><div class='img_s5_home'></div></a></div></div>";
                    }else{
                    opcionLogeado = "";
                    }
                }
                var urlFotoTipo = "";
                var urlFotoThumbTipo = "";
                if(dataCar.photoType == 1){
                    urlFotoTipo = dataCar.photo;
                    urlFotoThumbTipo = "<?php echo url_for('main/s3thumb'); ?>?alto=84&ancho=112&urlFoto=" + dataCar.photo;
                }else{
                    urlFotoTipo = "<?php echo image_path('../uploads/cars/" + dataCar.photo + "'); ?>";
                    urlFotoThumbTipo = "<?php echo image_path('../uploads/cars/thumbs/" + dataCar.photo + "'); ?>";
                }
                var contentString = '<div style="width:380px; height:165px;" class="mapcar_box" id="' + dataCar.id + '">' +
                    '<div class="mapcar_frame">' +
                    '<a href="' + urlFotoTipo + '"  class="thickbox"  ><img class="imagemark" width="112" height="84" src="' + urlFotoThumbTipo + '"/></a>' +                  
                    '</div><div class="detalles_car"><div class="titulo"><a target="_blank" title="Ir al perfil del auto" href="<?php echo url_for('auto/economico?chile=') ?>'+dataCar.comuna + '/id/' + dataCar.id +'">' + dataCar.brand + ' ' + dataCar.model + " " + verificado + calificacionesPositivas +'</a></div><div class="datos_car"><p>Dia: <b>$' + dataCar.price_per_day + ' CLP</b></p><p>Hora: <b>$'+ dataCar.price_per_hour +' CLP</b></p><p>Transmisión: <b>'+ dataCar.typeTransmission +'</b></p></div>'+ opcionLogeado +'</div><a target="_blank" href="<?php echo url_for('profile/reserve?id=') ?>' + dataCar.id + '" class="mapcar_btn_detalle"></a></div>';
					
			
                    if (infowindow) infowindow.close();
                        var infowindow = new google.maps.InfoWindow({
                        content: contentString
                    });
			
                    //var image = 'images/beachflag.png';

                    //var num = i+1;

                    //var image = "<?php echo 'http://www.arriendas.cl/images/Home/IndicadorMapa.png';?>";
                    
                    if(dataCar.verificado){
                        contador = contador + 1;
                        var image = 'http://chart.apis.google.com/chart?chst=d_map_pin_letter&chld=' + contador + '|05a4e7|ffffff';
                    }else{
                        var image = "<?php echo 'http://www.arriendas.cl/images/Home/circle.png';?>";
                        //var image = "<?php echo 'http://devel.arriendas.cl/frontend/web/images/Home/circle.png';?>";
                    }

			        var marker = new google.maps.Marker({
                        id: dataCar.id,
                        position: latLng,
                        icon: image,
                        contentString: contentString
                    });
			

                    google.maps.event.addListener(marker, 'click', function() {
                        infowindow.setContent(this.contentString);
                        infowindow.open(map,this);
    				
                        $('a.thickbox').click(function(){
                            var t = this.title || this.name || null;
                            var a = this.href || this.alt;
                            var g = this.rel || false;
                            tb_show(t,a,g);
                            this.blur();
                            return false;
                        });
    				
                    });

			
                    markers.push(marker);

                    //search box
                    nodes += '<div class="search_arecomend_item" id="'+dataCar.id+'">';
                    nodes +=    '<div class="marcadores">';
                    nodes +=        '<img src="http://chart.apis.google.com/chart?chst=d_map_pin_letter&chld='+contador+'|05a4e7|ffffff"/></div>';
                    nodes +=        '<div class="search_arecomend_frame">';
                    nodes +=            '<img width="64px" height="64px" src="'+urlFotoThumbTipo+'" />';
                    nodes +=        '</div>';
                    nodes +=        '<ul class="search_arecomend_info">';
                    nodes +=            '<input type="hidden" class="link" value="<?php echo url_for('auto/economico?chile=') ?>'+dataCar.comuna + '/id/' + dataCar.id +'"/>';
                    nodes +=            '<li class="search_arecomend_marca">';
                    nodes +=                '<a target="_blank" title="Ir al perfil del auto" href="<?php echo url_for('auto/economico?chile=') ?>'+dataCar.comuna + '/id/' + dataCar.id +'">';
                    nodes +=                    dataCar.brand + ' ' +dataCar.model;
                    nodes +=                '</a>';
                    nodes +=                verificado;
                    nodes +=            '</li>';
                    nodes +=            '<li>Día: <b>$'+dataCar.price_per_day+'</b></li>';
                    nodes +=            '<li>Hora: <b>$'+dataCar.price_per_hour+'</b></li>';
                    <?php if (sfContext::getInstance()->getUser()->getAttribute("logged")){?>
                    //nodes +=            '<li class="sep">Usuario: <a target="_blank" title="Ir al perfil de '+dataCar.firstname+'" class="link_user" href="http://www.arriendas.cl/profile/publicprofile/id/'+dataCar.userid+'">'+dataCar.firstname+' '+dataCar.lastname+'</a></li>';
                    <?php } ?>
                    nodes+=            '</ul>';
                    nodes+=        '</div>';

            }

            $("#loader").hide();
            $(".search_arecomend_window").html(nodes);            
            $('.search_arecomend_window').fadeIn("fast");
            
				
            //createClickToMarker();
		
            var mcOptions = {maxZoom: 10};
		
		
            markerCluster = new MarkerClusterer(map, markers, mcOptions);
            //alert(markers);
		
            $("#loading").css("display","none");
        
		

		
            //tb_show("Thickbox","cartaRec.html?placeValuesBefor eTB_=savedValues&TB_iframe=true&height=500&width=6 00&modal=true","media/loadingAnimation.gif");
		
        });

    }
    /*
    function formatNumber(precio){
        var precioMillones = Math.floor(precio/1000000);
        var precioMiles = parseInt(precio) - (precioMillones*1000000);
        precioMiles = Math.floor(precioMiles/1000);
        var precioCientos = parseInt(precio) - (precioMiles*1000);
        var precioResultado = "";
        if(precioMillones == 0){
            if(precioMiles == 0){
                precioResultado = precioCientos;
            }else{
                if(precioCientos == 0){
                    precioResultado = precioMiles + ".000";
                }else{
                    cantidadDeCientos = ((precioCientos).toString()).length;
                    if(cantidadDeCientos == 1) precioResultado = precioMiles + ".00" + precioCientos;
                    else if(cantidadDeCientos == 2) precioResultado = precioMiles + ".0" + precioCientos;
                    else precioResultado = precioMiles + "." + precioCientos;
                }
            }
        }else{
            if(precioMiles == 0){
                if(precioCientos == 0){
                    precioResultado = precioMillones + ".000.000";
                }else{
                    cantidadDeCientos = ((precioCientos).toString()).length;
                    if(cantidadDeCientos == 1) precioResultado = precioMillones + ".000.00" + precioCientos;
                    else if(cantidadDeCientos == 2) precioResultado = precioMillones + ".000.0" + precioCientos;
                    else precioResultado = precioMillones + ".000." + precioCientos;
                }
            }else{
                cantidadDeMiles = ((precioMiles).toString()).length;
                cantidadDeCientos = ((precioCientos).toString()).length;
                if(cantidadDeMiles == 1){
                    if(cantidadDeCientos == 1) precioResultado = precioMillones + ".00" + precioMiles + ".00" + precioCientos;
                    else if(cantidadDeCientos == 2) precioResultado = precioMillones + ".00" + precioMiles + ".0" + precioCientos;
                    else precioResultado = precioMillones + ".00" + precioMiles + "." + precioCientos;
                }else if(cantidadDeCientos == 2){
                    if(cantidadDeCientos == 1) precioResultado = precioMillones + ".0" + precioMiles + ".00" + precioCientos;
                    else if(cantidadDeCientos == 2) precioResultado = precioMillones + ".0" + precioMiles + ".0" + precioCientos;
                    else precioResultado = precioMillones + ".0" + precioMiles + "." + precioCientos;
                }else{
                    if(cantidadDeCientos == 1) precioResultado = precioMillones + "." + precioMiles + ".00" + precioCientos;
                    else if(cantidadDeCientos == 2) precioResultado = precioMillones + "." + precioMiles + ".0" + precioCientos;
                    else precioResultado = precioMillones + "." + precioMiles + "." + precioCientos;
                }
            }
        }
        return precioResultado;
    }
    */

    function createClickToMarker(){
        /*
        $(".search_arecomend_item").hover(function () {
            var currentId = $(this).attr('id');
			
            if (markers) {
                for (i in markers) {
				
                    if(currentId == markers[i].id){
					
                        google.maps.event.trigger(markers[i], 'click');
                    }
                }
				
            }
		
		
        });
        */
    }



    function var_dump(data,addwhitespace,safety,level) {
        var rtrn = '';
        var dt,it,spaces = '';
        if(!level) {level = 1;}
        for(var i=0; i<level; i++) {
            spaces += '   ';
        }//end for i<level
        if(typeof(data) != 'object') {
            dt = data;
            if(typeof(data) == 'string') {
                if(addwhitespace == 'html') {
                    dt = dt.replace(/&/g,'&amp;');
                    dt = dt.replace(/>/g,'&gt;');
                    dt = dt.replace(/</g,'&lt;');
                }//end if addwhitespace == html
                dt = dt.replace(/\"/g,'\"');
                dt = '"' + dt + '"';
            }//end if typeof == string
            if(typeof(data) == 'function' && addwhitespace) {
                dt = new String(dt).replace(/\n/g,"\n"+spaces);
                if(addwhitespace == 'html') {
                    dt = dt.replace(/&/g,'&amp;');
                    dt = dt.replace(/>/g,'&gt;');
                    dt = dt.replace(/</g,'&lt;');
                }//end if addwhitespace == html
            }//end if typeof == function
            if(typeof(data) == 'undefined') {
                dt = 'undefined';
            }//end if typeof == undefined
            if(addwhitespace == 'html') {
                if(typeof(dt) != 'string') {
                    dt = new String(dt);
                }//end typeof != string
                dt = dt.replace(/ /g,"&nbsp;").replace(/\n/g,"<br>");
            }//end if addwhitespace == html
            return dt;
        }//end if typeof != object && != array
        for (var x in data) {
            if(safety && (level > safety)) {
                dt = '*RECURSION*';
            } else {
                try {
                    dt = var_dump(data[x],addwhitespace,safety,level+1);
                } catch (e) {continue;}
            }//end if-else level > safety
            it = var_dump(x,addwhitespace,safety,level+1);
            rtrn += it + ':' + dt + ',';
            if(addwhitespace) {
                rtrn += '\n'+spaces;
            }//end if addwhitespace
        }//end for...in
        if(addwhitespace) {
            rtrn = '{\n' + spaces + rtrn.substr(0,rtrn.length-(2+(level*3))) + '\n' + spaces.substr(0,spaces.length-3) + '}';
        } else {
            rtrn = '{' + rtrn.substr(0,rtrn.length-1) + '}';
        }//end if-else addwhitespace
        if(addwhitespace == 'html') {
            rtrn = rtrn.replace(/ /g,"&nbsp;").replace(/\n/g,"<br>");
        }//end if addwhitespace == html
        return rtrn;
    }//end function var_dump

	 
	 
    //$(document).ready(
	function initialize2() {
	
	
	 $('#video').html('	<iframe src="http://player.vimeo.com/video/45668172?title=0&byline=0&portrait=0ll" width="940" height="500" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe>')

        //timerSet("#hour_from", "#hour_to");
        $("#hour_from , #hour_to").timePicker();	
			
        getModel($("#brand"),false);
		 
        $("#brand").change(function(){
            getModel($(this),true); 
        });
	
        $('#model').change(function() {
            doSearch();
        });	
	
        $('#hour_from').change(function() {
            doSearch();
        });
	
        $('#hour_to').change(function() {
            doSearch();
        });

		var date = new Date();

		if( date.getMinutes() < 30 ){
            var hora = date.getHours()+1;
            $('#hour_from').val(hora + ':00:00');
        }else{
            var hora = date.getHours()+1;
            $('#hour_from').val(hora+':30:00');
        }

        //fecha actual
        var dia = date.getDate();
        if(dia<10){
            dia = '0'+dia;
        }
        var mes = date.getMonth()+1;
        if(mes<10){
            mes = '0'+mes;
        }
        var anio = date.getFullYear();

        $('#day_from').val(dia+'-'+mes+'-'+anio);
	
        $('#day_from').change(function() {
        	
        	if( $('#day_to').val() == 'Dia de entrega' || $('#day_to').val() == '' )
        		$('#day_to').val( $(this).val() );
		});
	/*	
$('#day_to').change(function() {
        doSearch();
});
         */	
        $('#brand').change(function() {
            doSearch();
        });
	
        $('#price').change(function() {
            doSearch();
        });

	
	


        $( "#day_from" ).datepicker({
            dateFormat: 'dd-mm-yy',
            buttonImageOnly: true,
            minDate:'-0d'
        });

		

        $( "#day_to" ).datepicker({
            dateFormat: 'dd-mm-yy',
            buttonImageOnly: true,
            minDate:'-0d'
        });

	

	



        /*$('#hour_from').datetimepicker({
                addSliderAccess: true,
                sliderAccessArgs: { touchonly: false },
                minuteGrid: 15
    // timeFormat: 'HH:mm',
    // minHour: null,
    // minMinutes: null,
    // minTime: null,
    // maxHour: null,
    // maxMinutes: null,
    // maxTime: null,
    // startHour: 0,
    // startMinutes: 0,
    // startTime: null,
    // interval: 30,
    // callbacks
    change: function(time) {}
});
        
$('#hour_to').datetimepicker({
                addSliderAccess: true,
                sliderAccessArgs: { touchonly: false },
                minuteGrid: 15
    // timeFormat: 'HH:mm',
    // minHour: null,
    // minMinutes: null,
    // minTime: null,
    // maxHour: null,
    // maxMinutes: null,
    // maxTime: null,
    // startHour: 0,
    // startMinutes: 0,
    // startTime: null,
    // interval: 30,
    // callbacks
    change: function(time) {}
});
		
         */
        
//        $('#mycarousel').jcarousel({
  //          wrap: 'circular',
    //        auto: 4,
      //      scroll: 5,
        //    initCallback: mycarousel_initCallback
      //  });
    	
    	
    	$('a.mapcar_btn_detalle').live('click',function(event) {
    		
    		event.preventDefault();
    		obj = $(this);
    		
	        $.ajax({
	        	
	        	type: 'post',
	        	url: '<?php echo url_for('main/filtrosBusqueda') ?>',
	        	data: { fechainicio: $('#day_from').val(), horainicio: $('#hour_from').val(), fechatermino: $('#day_to').val(), horatermino: $('#hour_to').val() },
	        	success: function() {
	        		
	        		top.location = obj.attr('href');
	        	}
	        })
    	})
    	/*
       	$('.search_arecomend_marca a').live('click',function(event) {
    		
    		//event.preventDefault();
    		obj = $(this);
    		
	        $.ajax({
	        	
	        	type: 'post',
	        	url: '<?php echo url_for('main/filtrosBusqueda') ?>',
	        	data: { fechainicio: $('#day_from').val(), horainicio: $('#hour_from').val(), fechatermino: $('#day_to').val(), horatermino: $('#hour_to').val() },
	        	success: function() {
	        		
	        		top.location = obj.attr('href');
	        	}
	        })
    	});
        */

        /*código german*/

        $("#sliderComoUsar").click(function(){

            var url1 = $(".img_instrucciones_1").attr('src');
            url1 = url1.split('/');

            var url2 = $(".img_instrucciones_2").attr('src');
            url2 = url2.split('/');

            //alert(url1[url1.length-1]);

            if(url1[url1.length-1] == 'dueno.png'){
                url1[url1.length-1] = 'arrendatario.png';
                url2[url2.length-1] = 'slider1.png';
                $(".boton_ponerAutoArriendo").removeClass("botonSeleccionado");
                $(".boton_arriendaUnAuto").addClass("botonSeleccionado");
                //$("#tagLineArrendador").css("display","none");
                //$("#tagLineArrendatario").css("display","block");
                //$("#preguntasContenedor2").css("display","none");
                //$("#preguntasContenedor").css("display","block");
            }else{
                url1[url1.length-1] = 'dueno.png';
                url2[url2.length-1] = 'slider2.png';
                $(".boton_arriendaUnAuto").removeClass("botonSeleccionado");
                $(".boton_ponerAutoArriendo").addClass("botonSeleccionado");
                //$("#tagLineArrendatario").css("display","none");
                //$("#tagLineArrendador").css("display","block");
                //$("#preguntasContenedor").css("display","none");
                //$("#preguntasContenedor2").css("display","block"); 
            }

            url1 = url1.join('/');
            $(".img_instrucciones_1").attr('src',url1);

            url2 = url2.join('/');
            $(".img_instrucciones_2").attr('src',url2);
            //$('#sliderComoUsarImg').attr('src','Bimagenes1/slider2.png');

        });

        /*fin código germán*/

        /*Código Miguel*/
        $(".boton_arriendaUnAuto").click(function(){
            $(".boton_ponerAutoArriendo").removeClass("botonSeleccionado");
            $(".boton_arriendaUnAuto").addClass("botonSeleccionado");

            var url1 = $(".img_instrucciones_1").attr('src');
            url1 = url1.split('/');

            var url2 = $(".img_instrucciones_2").attr('src');
            url2 = url2.split('/');

            url1[url1.length-1] = 'arrendatario.png';
            url2[url2.length-1] = 'slider1.png';

            url1 = url1.join('/');
            $(".img_instrucciones_1").attr('src',url1);

            url2 = url2.join('/');
            $(".img_instrucciones_2").attr('src',url2);

            //$("#tagLineArrendador").css("display","none");
            //$("#tagLineArrendatario").css("display","block");

            //$("#preguntasContenedor2").css("display","none");
            //$("#preguntasContenedor").css("display","block");
                        
        });
        $(".boton_ponerAutoArriendo").click(function(){
            $(".boton_arriendaUnAuto").removeClass("botonSeleccionado");
            $(".boton_ponerAutoArriendo").addClass("botonSeleccionado");

            var url1 = $(".img_instrucciones_1").attr('src');
            url1 = url1.split('/');

            var url2 = $(".img_instrucciones_2").attr('src');
            url2 = url2.split('/');
            
            url1[url1.length-1] = 'dueno.png';
            url2[url2.length-1] = 'slider2.png';

            url1 = url1.join('/');
            $(".img_instrucciones_1").attr('src',url1);

            url2 = url2.join('/');
            $(".img_instrucciones_2").attr('src',url2);

            //$("#tagLineArrendatario").css("display","none");
            //$("#tagLineArrendador").css("display","block");

            //$("#preguntasContenedor").css("display","none");
            //$("#preguntasContenedor2").css("display","block");           

        });


        /*Fin Código Miguel */

		
		
		
		  // async load of Zendesk
      var c = document.createElement('link');
     c.rel = "stylesheet";
     c.type = "text/css";
     c.href = "//asset.zendesk.com/external/zenbox/v2.6/zenbox.css";
     var h = document.getElementsByTagName('head')[0];
     h.appendChild(c);
     $.ajax({
       url: "//asset.zendesk.com/external/zenbox/v2.6/zenbox.js",
       dataType: "script",
       cache: true,
       success: function() {
         Zenbox.init({
      dropboxID:   "20193521",
      url:         "https://arriendascl.zendesk.com",
      tabTooltip:  "Chat",
      tabImageURL: "https://assets.zendesk.com/external/zenbox/images/tab_es_support.png",
      tabColor:    "black",
      tabPosition: "Right"
	  });
       }
    })
  
    	
    };
	

	
    //function timerSet( input1, input2 ){	


    ///$(input1, input2).timePicker();	

    // Store time used by duration.

    /*	var oldTime = $.timePicker(input1).getTime();

        alert("aaa");
        // Keep the duration between the two inputs.
        $(input1).change(function() {
          if ($(input2).val()) { // Only update when second input has a value.
                // Calculate duration.
                var duration = ($.timePicker(input2).getTime() - oldTime);
                var time = $.timePicker(input1).getTime();
                // Calculate and update the time in the second input.
                $.timePicker(input2).setTime(new Date(new Date(time.getTime() + duration)));
                oldTime = time;
          }
        });
        // Validate.
        $(input2).change(function() {
          if($.timePicker(input1).getTime() > $.timePicker(this).getTime()) {
                $(this).addClass("error");
          }
          else {
                $(this).removeClass("error");
          }
        });*/

    ///}
	
    function getModel(currentElement,reloadCars){
		
		console.log('getModel');
	
        $("#model").html("");
	
        $.getJSON("<?php echo url_for('main/getModelSearch') ?>",{id:currentElement.val(), ajax: 'true'}, function(j){
            var options = '';
      
            for (var i = 0; i < j.length; i++) {
	  
                options += '<option value="' + j[i].optionValue + '" >' + j[i].optionDisplay + '</option>';
		
            }
            $("#model").html(options);
	  
	  
            if (reloadCars){
				doSearch();
			};
	  
        })
    }


	
	
        
</script>


<style type="text/css">



    #loading {
        z-index:10;
        display: none;
		position:absolute;
        width: 630px;
        height: 520px;
        background: white url(<?php echo image_path("ajax-loader.gif") ?>) no-repeat center center;
        zoom: 1;
        filter: alpha(opacity=50);
        opacity: 0.5; 
    }	  

    #loadingSearch {
        /* z-index:10;
       position:absolute;*/
		display:table;
        width: 950px;
        height: 215px;
        background: white url(<?php echo image_path("ajax-loader.gif") ?>) no-repeat center center;
        zoom: 1;
        filter: alpha(opacity=50);
        opacity: 0.5; 
    }	  
	
	
	
    #loading #icon {
        position:absolute;
        top: 240px;
        left: 300px;

    }

    #offers {
        width: 300px;
        height: 400px;
        float:right;
    }

    #InfoCar{
        font-size:12px;
        width:300px;
        height:150px;


    }

    #InfoCar h1{
        font-size:14px;
    }

    .imagemark
    {
        margin-bottom:10px;
        float:left;
    }



    .land_sector3{

        display:none;
    }

    #search{

    }



</style>

<input type="hidden" id="hour_from_hidden" />
<input type="hidden" id="hour_to_hidden" />
<input type="hidden" id="price_hidden" />
<input type="hidden" id="model_hidden" value="0"  />
<input type="hidden" id="brand_hidden" />
<input type="hidden" id="location_hidden" />
<input type="hidden" id="day_from_hidden" value="12-11-2013" />
<input type="hidden" id="day_to_hidden" />

<script>
    
    
    
    $(document).ready(function() {
        $("#more").click(function () {

            $(".search_ext_box").slideToggle("slow");
        });
        <?php if($sf_user->getAttribute('geolocalizacion')==true):?>
        localizame();
        <?php endif; ?>

        $(".buton_more_options").click(function(){
             $(".search_ext_box").each(function() {
                displaying = $(this).css("display");
                if(displaying == "block") {
                  $(this).fadeOut('hide',function() {
                   $(this).css("display","none");
                  });
                } else {
                  $(this).fadeIn('hide',function() {
                    $(this).css("display","block");
                  });
                }
              });
        });
    });
	
	
</script>

<!--
<div style="position: absolute; z-index: 9;">
    <a href="#" onclick="mostrar_ayuda();" id="btn_ayuda">Instrucciones</a>
</div>
<script>
    
    function mostrar_ayuda() {
	alert("dro");
	document.getElementById("instrucciones_paso1").style.display="visible";
    }
    
</script>
<div id="instrucciones_paso1" style="z-index:1; position: absolute; margin-top: -123px; margin-left: -3px; display: none;">
    <?php echo image_tag("instrucciones/InstruccionesWeb1.png"); ?>
    
</div>
<div id="instrucciones_paso2" style="z-index:1; position: absolute; margin-top: -123px; margin-left: -3px; display: none;">
    
    <?php echo image_tag("instrucciones/InstruccionesWeb2.png"); ?>
    
</div>x
<div id="instrucciones_paso3" style="z-index:1; position: absolute; margin-top: -123px; margin-left: -3px; display: none;">
    
    <?php echo image_tag("instrucciones/InstruccionesWeb3.png"); ?>
    
</div>

-->

<div class="search_container">


    <div class="land_slogan_area">
        <?php
        if (stripos($_SERVER['SERVER_NAME'], "arrendas") !== FALSE)
            echo "<h2>Autos en alquiler desde 5.000 pesos la hora, cerca tuyo</h2>";
        else
            echo "<div id='tagLineArrendador'>
                    <h2>Arriendas es tu <span class='dest'>propio negocio</span>, empieza a monetizar tu auto ";
                if(sfContext::getInstance()->getUser()->isAuthenticated()){
                echo "</h2>";
                }else{
                    echo "<span class='dest'><a href='".url_for('main/register')."' title='Ingresa aquí para registrarte' style='color:#00AEEF;'>aquí</a></span></h2>";
                }
            echo "</div>
                <div id='tagLineArrendatario'>
                    <h2>Arrienda un auto <span class='dest'>vecino</span> con seguro 1000UF, asistencia y TAGs en el <span class='dest'>precio final</span></h2>      
                </div>";
        ?>

    </div><!-- land_slogan_area -->


<div class="main_box_1_anterior">
<div class="main_box_2_anterior">
    <div class="search_sector1">      
        <!--  busqueda avanzada -->
        <div class="search_box_1">
            <input type="button" class="buton_more_options"/>
            <div class="search_box_1_title">
                <p class="txt_buscador">Busca de automóviles cerca de tu ubicación <span>(búsqueda automática)</span></p>
            </div>
            <div class="search_box_1_header">    
                <div class="search_box_ubica">
                	<input id="searchTextField" type="text" size="50" placeholder="Introduce una ubicación" autocomplete="off"/>
                </div>
                <div class="search_box1_form" style="display:none;">
                	<span class="group_desde">Disponible desde</span><span class="group_hasta">Hasta</span>
                    <input class="input_f1" readonly="readonly" type="text" id="day_from" value="Dia de inicio"/>
                    <input class="input_f1" readonly="readonly" type="text" id="hour_from" value=""/>
                    <input class="input_f1" readonly="readonly" type="text" id="day_to" value="Dia de entrega"/>
                    <input class="input_f1b" readonly="readonly" type="text" id="hour_to"  value="Hora de entrega" />
                    <a id="more" class="button2 altCta2 menor" href="#"><span id="spanprecio">+</span></a>
                </div>

                <!-- buscador avanzado -->
                <div class="search_ext_box" style="display:none;">
                	<span class="group_filtar">Filtrar por</span>
                    <div class="search_ext_wrapper">
                        <select name="price" id="price" style="margin-right:20px;" >
                            <option value="-" selected="selected">Precio</option>
                            <option value="1">Mayor a menor</option>
                            <option value="0">Menor a mayor</option>
                        </select> 

<!--<select>
    
    <option>Peugeot</option>
    <option>Fiat</option>     
    <option>Ford</option>     
    <option>Citroen</option>              
</select> 
                        -->
                        <select name="brand" id="brand" style="margin-right:20px;" >
                            <option value="" selected="selected">Marca</option>	
                            <?php foreach ($brand as $b): ?>	
                                <option value="<?= $b['id'] ?>" ><?= $b['name'] ?></option> 		
                            <?php endforeach; ?>
                        </select>

                        <select name="model" id="model" style="" >
                            <option value="" selected="selected">Modelo</option>
                        </select>

<!-- <select name="location" id="location">
        <option value="" selected="selected">Pais</option>
                        <?php foreach ($country as $c): ?>
                            <option value="<?= $c->getId() ?>" ><?= $c->getName() ?></option> 		
                        <?php endforeach; ?>
</select>-->

<!-- <select>
    <option  name="location" id="location" selected="selected">Ubicaci&oacute;n</option>
    <option>Almagro</option>
    <option>Caballito</option>     
    <option>Congreso</option>     
    <option>San Telmo</option>              
</select>
                        -->
                    </div><!-- search_ext_wrapper -->
                </div><!-- search_ext_box -->

            </div><!-- search_box_1_header -->

            <div class="search_box_1_maparea" style="background-color: #ccc;">    
                <div id="loading">
                </div>
                <div id="map"></div>
            </div><!-- search_box_1_maparea -->        
        </div><!-- search_box_1 -->     



        <!--  autos recomendados -->
        <div class="search_box_2">
            <div class="search_box_2_header">

                <!--<h2>Autos recomendados</h2>
				<?php echo image_tag("img_landing/land_box_header_flecha.png", array("class" => "search_box_header_flecha")) ?>             
				-->

                <h2>RESULTADOS - <a target="_blank" href="<?=url_for('main/arriendo?autos=santiago');?>" title="Ver todos los autos" style="font-size: 14px;color: #00AEEF;text-decoration: none;">Ver Todos</a></h2>
                <!-- ?php echo image_tag("img_landing/land_box_header_flecha.png", array("class" => "search_box_header_flecha")) ? -->             

            </div><!-- land_box_2_header -->  

            <div id="loader" style="display:none;margin-left: 105px;margin-top: 30px;"><?php echo image_tag("ajax-loader.gif", array("width" => "80px", "height" => "80px")); ?></div>  
            <div class="search_arecomend_window">  

            </div><!-- search_arecomend_window -->   



        </div><!-- search_box_2 -->    

        <div class="clear"></div>
    </div><!-- search_sector1 -->
    <!--<p class='comparaPrecio'><a href="<?php echo url_for('main/comparaPrecios') ?>" class='comparaPrecio'>Compara Precios</a></p>-->
</div>
    <div id="centro">


<div id="comoUsar">
<div class="titulos"> C&oacutemo usar Arriendas? </div>
<div id="instrucciones_1"><?php echo image_tag("comparaprecios/arrendatario.png", array("class" => "img_instrucciones_1")) ?></div>
<div id="sliderComoUsar"><?php echo image_tag("comparaprecios/slider1.png", array("class" => "img_instrucciones_2")) ?></div>

</div>

<div id="centrocentro"></div>

<div id="tabla"> 

<div class="titulos"> Es m&aacutes econ&oacutemico Arriendas.cl? </div>

<div id="contenedorTabla">
    <?php echo image_tag("Home/Tabla_Precios2.jpg", array("width" => "476px", "height" => "250px")) ?>
</div>     

</div>     

<div id="seguro"> 

<div class="titulos">Preguntas frecuentes (arrienda un auto)</div>

<!--<div id="seguroContenidos">-->

<div id="seguroContenido">

<div id="preguntasContenedor">
 <div class="preguntas"><a href="https://arriendascl.zendesk.com/entries/22326617--C%C3%B3mo-arriendo-un-auto-Qu%C3%A9-papeles-debo-presentar-" target="_blank"> <p>C&oacutemo arriendo un auto?</p></a></div>
 <div class="preguntas"><a href="https://arriendascl.zendesk.com/entries/23062448--Qu%C3%A9-es-lo-que-cubre-el-seguro-" target="_blank"> <p>Qu&eacute cubre el seguro?</p> </a></div>
 <div class="preguntas"><a href="https://arriendascl.zendesk.com/entries/23528442-Cu%C3%A1l-es-el-precio-final-del-arriendo-" target="_blank"> <p>Cu&aacutel es el precio final del arriendo?</p></a></div>
 <div class="preguntas"><a href="https://arriendascl.zendesk.com/entries/22573231--Qui%C3%A9n-paga-por-la-bencina-y-TAG-" target="_blank"> <p>Qui&eacuten paga la bencina y el TAG?</p></a></div>
 <div class="preguntas"><a href="https://arriendascl.zendesk.com/entries/23546076--Qu%C3%A9-tel%C3%A9fono-contactar-en-caso-de-accidente-o-urgencia-" target="_blank"> <p>Qu&eacute tel&eacutefono contactar en caso de urgencia o retraso?</p> </a></div> 
</div>
<!--
<div id="preguntasContenedor2">
<div class="preguntas"><a href="https://arriendascl.zendesk.com/entries/23566616-Resumen-de-los-t%C3%A9rminos-y-condiciones-para-due%C3%B1os-de-autos-" target="_blank"> <p>Qué debo hacer para poner mi auto en arriendo?</p></a></div>
 <div class="preguntas"><a href="https://arriendascl.zendesk.com/entries/23572141-Ver-el-formulario-de-entrega-y-devoluci%C3%B3n-del-auto" target="_blank"> <p>Cómo gestiono las reservas?</p> </a></div>
 <div class="preguntas"><a href="https://arriendascl.zendesk.com/entries/23585618--Cu%C3%A1ndo-se-me-paga-por-mis-arriendos-C%C3%B3mo-se-paga-el-seguro-y-las-comision-de-Arriendas-" target="_blank"> <p>Qué cubre el seguro?</p> </a></div>
 <div class="preguntas"><a href="https://arriendascl.zendesk.com/entries/23757242--Cu%C3%A1les-son-mis-responsabilidades-como-contribuyente- " target="_blank"> <p>Cuál es mi responsabilidad como contribuyente?</p></a></div>
 <div class="preguntas"><a href="https://arriendascl.zendesk.com/entries/23585618--Cu%C3%A1ndo-se-me-paga-por-mis-arriendos-C%C3%B3mo-se-paga-el-seguro-y-las-comision-de-Arriendas- " target="_blank"> <p>Cuándo se me pagan los arriendos?</p></a></div>
</div>
-->

</div>
<!--</div>-->
</div>

<!-- 
<div id="contenedorPreguntasFrecuentes2">
<div class="titulos"> Preguntas frecuentes (si quieres arrendar un auto) <div id="sliderComoUsar" float:right;> <img id="sliderComoUsarImg" src="/Users/germanrimoldi/Desktop/slider1.png"/> </div>
</div>
</div>
-->



<div class="titulos" style="margin-left: 10px;
margin-top: 5px;
padding: 10px 0;float: left;width: 940px;"> Ver video explicativo </div>
	<div id="video">

	</div>

<br><br>
<div id="medios" style="display:none;">
    <div id="emol"><a href="http://www.emol.com/noticias/economia/2012/07/27/552815/emprendedor-estrenara-primer-sistema-de-arriendo-de-vehiculos-por-hora-de-chile.html" target="_blank"><?php echo image_tag("comparaprecios/mercurio.png") ?></a></div>
    <div id="LUN"><a href="http://images.lun.com/lunservercontents/NewsPaperPages/2012/oct/23/LUCPR06LU2310_800.swf" target="_blank"><?php echo image_tag("comparaprecios/LUN.png") ?></a></div>
    <div id="publimetro"><a href="http://www.tacometro.cl/prontus_tacometro/site/artic/20121030/pags/20121030152946.html" target="_blank"><?php echo image_tag("comparaprecios/publimetro.png") ?></a></div>
    <div id="latercera"><a href="http://www.lasegunda.com/Noticias/CienciaTecnologia/2012/08/774751/arriendascl-sistema-de-alquiler-de-autos-por-horas-debuta-en-septiembre" target="_blank"><?php echo image_tag("comparaprecios/latercera.png") ?></a></div>
    <!-- href="http://www.lasegunda.com/noticias/economia/2012/07/767675/emprendedor-estrenara-primer-sistema-de-arriendo-de-vehiculos-por-hora-en-chile" -->
</div>
</div>

</div>
</div>

</div><!-- search_container -->