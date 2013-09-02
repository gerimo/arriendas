<?php use_stylesheet('search.css') ?>
<?php use_stylesheet('landing.css') ?>
<?php use_stylesheet('registro.css') ?>
<?php
$useragent=$_SERVER['HTTP_USER_AGENT'];
if(preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i',$useragent)||preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i',substr($useragent,0,4))){
  use_stylesheet('moviles.css');
}
?>

<style>
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

 	
</style>


<script type="text/javascript">

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
        initialize();
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
        
		<?
		if (stripos($_SERVER['SERVER_NAME'], "arrendas") !== FALSE)
		    echo "var center = new google.maps.LatLng(-34.59, -58.401604);";
		else
		    echo "var center = new google.maps.LatLng(-33.427224, -70.605558);";
		?> 
    	if(geolocalizacion){    
            center = new google.maps.LatLng(latitud,longitud);

        }
        
        map = new google.maps.Map(document.getElementById('map'), {
            zoom: 15,
            center: center,
            mapTypeId: google.maps.MapTypeId.ROADMAP,
            scrollwheel: true
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
	
             $("#loading").css("display","inline");	
			 $("#loadingSearch").css("display","table");
		      $(".returncar_area").css("display","none");
		   
        if (markerCluster && markerCluster.clearMarkers)
            markerCluster.clearMarkers();
	
      	
        var hour_from_hidden = encodeURIComponent(document.getElementById("hour_from_hidden").value);
        var hour_to_hidden = encodeURIComponent(document.getElementById("hour_to_hidden").value);
        var brand_hidden = encodeURIComponent(document.getElementById("brand_hidden").value);
        var location_hidden = "";//encodeURIComponent(document.getElementById("location_hidden").value);
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
            precio = "&clat=" + lat + "&clng=" + lon;
        }       
      	     
		
        // var url = "<?php echo url_for('main/searchResult') ?>"+"?hour_from=" + hour_from_hidden  + "&hour_to=" + hour_to_hidden  + "&brand=" + brand_hidden  + "&location=" + location_hidden  + "&price=" + price_hidden  + "&swLat="+swLat+"&swLng="+swLng+"&neLat="+neLat+"&neLng="+neLng;
        var url = "<?php echo url_for('main/searchResult') ?>?day_from=" + day_from_hidden + "&day_to=" + day_to_hidden +"&model="+ model_hidden + "&hour_from=" + hour_from_hidden  + "&hour_to=" + hour_to_hidden  + "&brand=" + brand_hidden  + "&location=" + location_hidden  + precio  + "&swLat="+swLat+"&swLng="+swLng+"&neLat="+neLat+"&neLng="+neLng;
		
        //  document.write(var_dump(url,'html'));

        
        $(".search_arecomend_window").load(url,  function() {
        
            $('a.thickbox').click(function(){
                var t = this.title || this.name || null;
                var a = this.href || this.alt;
                var g = this.rel || false;
                tb_show(t,a,g);
                this.blur();
                return false;
            });
        
            var numItems = $('.search_arecomend_item').length;
        
            if(numItems == 0){
            
			  //  document.write(var_dump(url,'html'));
                $('.search_arecomend_window').css("visibility", "hidden");
            
            }
        
            else
            {
                $('.search_arecomend_window').css("visibility", "visible");
            }

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
            precio = "&clat=" + lat + "&clng=" + lon;
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
            
	    	
            for (var i = 0; i < data.cars.length; i++) {
                var dataCar = data.cars[i];
				
                var latLng = new google.maps.LatLng(dataCar.latitude,dataCar.longitude);
		
                var contentString = '<div class="mapcar_box" id="' + dataCar.id + '">' +
                    '<div class="mapcar_frame">' +
                    '<a href="' + dataCar.photo + '"  class="thickbox"  ><img class="imagemark" width="112" height="84" src="' + dataCar.photo  + '"/></a>' +					
                    '</div><div class="mapcar_titulo"><a href="<?php echo url_for('cars/car?id=') ?>'+dataCar.id + '">' + dataCar.brand + ' ' + dataCar.model +
                    '</a> </div><div class="mapcar_info"><p>Precio x hora: <b>$' + dataCar.price_per_hour + '</b> <br>Precio x d&iacute;a: $' + dataCar.price_per_day +
                    '<br></p></div> <p class="mapcar_user"><span class="txt_user">Usuario:</span><br /><a href="http://www.arriendas.cl/messages/new/id/' + dataCar.userid + '">' + dataCar.username + '</a>' +
                    //'<span><a href="#">Leer mensajes</a></span>' +
                '</p><a href="<?php echo url_for('cars/car?id=') ?>' + dataCar.id + '" class="mapcar_btn_detalle"><span>Reservar</span></a></div>';	
					
                /*var contentString = 
                        '<div id="InfoCar">'+
                        '<div style=""><img class="imagemark" weight="122" height="102" src="' + dataCar.photo  + '"/></div>' +
                        '<div style="float:right;"><h1><a href="<?= url_for('profile/publicprofile?id='); ?>'+ dataCar.userid + 
                        '" style="color: #d11978;text-decoration: none;">'+ dataCar.username + '</a></h1><br/>' +
                        '<b>' +  dataCar.brand + ' '+ dataCar.model   + '(' + dataCar.year + ')</b><br/><br/>' +
                        '<b>Precio por hora:</b> $ ' +  dataCar.price_per_hour + '<br/>' +
                        '<b>Precio por día:</b> $ ' +  dataCar.price_per_day + '<br/><br/><br/>' +
                        '<a href="<?php echo url_for('cars/car?id=') ?>' + dataCar.id + '">'+
                        '<img class="imagemark" src="<?= image_path("btn_reserva.png"); ?>" />' + 
                        '</a></div>' +
                        '</div>';
                 */
			
                if (infowindow) infowindow.close();
                var infowindow = new google.maps.InfoWindow({
                    content: contentString
                });
			
                //var image = 'images/beachflag.png';
                
                var num = i + 1;
                var image = 'http://chart.apis.google.com/chart?chst=d_map_pin_letter&chld=' + num + '|05a4e7|ffffff'
			    //var image = "<?php echo 'http://www.arriendas.cl/images/Home/IndicadorMapa.png';?>";

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
			
			
			
			
            }

				
            createClickToMarker();
		
            var mcOptions = {maxZoom: 12};
		
		
            markerCluster = new MarkerClusterer(map, markers, mcOptions);
            //alert(markers);
		
            $("#loading").css("display","none");
        
		

		
            //tb_show("Thickbox","cartaRec.html?placeValuesBefor eTB_=savedValues&TB_iframe=true&height=500&width=6 00&modal=true","media/loadingAnimation.gif");
		
        });

    }



    function createClickToMarker(){

	
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

	 
	 
    $(document).ready(function() {

        //timerSet("#hour_from", "#hour_to");
        $("#hour_from , #hour_to").timePicker();	
			
        getModel($("#brand"));
		 
        $("#brand").change(function(){
            getModel($(this)); 
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
		$('#hour_from').val(date.getHours() + ':00:00');
		if( date.getMinutes() > 0 ) $('#hour_from').val(date.getHours() + ':30:00');
	
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
            //doSearch();
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
        
        $('#mycarousel').jcarousel({
            wrap: 'circular',
            auto: 4,
            scroll: 5,
            initCallback: mycarousel_initCallback
        });
    	
    	
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
    	
       	$('.search_arecomend_marca a').live('click',function(event) {
    		
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
    	
    });
	

	
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
	
    function getModel(currentElement){
	
        $("#model").html("");
	
        $.getJSON("<?php echo url_for('main/getModelSearch') ?>",{id:currentElement.val(), ajax: 'true'}, function(j){
            var options = '';
      
            for (var i = 0; i < j.length; i++) {
	  
                options += '<option value="' + j[i].optionValue + '" >' + j[i].optionDisplay + '</option>';
		
            }
            $("#model").html(options);
	  
            doSearch();
	  
        })
    }


	
	
        
</script>


<style type="text/css">



    #loading {
        z-index:10;
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
<input type="hidden" id="day_from_hidden" />
<input type="hidden" id="day_to_hidden" />

<script>
    
    
    
    $(document).ready(function() {
        $("#more").click(function () {

            $(".search_ext_box").slideToggle("slow");
        });
        <?php if($sf_user->getAttribute('geolocalizacion')==true):?>
        localizame();
        <?php endif; ?>
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
    
</div>
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
            echo "<h2>Arrienda un auto desde <span class='dest'>4.000</span> pesos la hora o <span class='dest'>15.000</span> el día</h2>";
        ?>

    </div><!-- land_slogan_area -->


<div class="main_box_1">
<div class="main_box_2">
    <div class="search_sector1">      
        <!--  busqueda avanzada -->
        <div class="search_box_1">
            <div class="search_box_1_header">
                <!--<a href="#" class="search_form_calendar_1"><?php echo image_tag("img_search/land_form_ico_calendar.png") ?></a>-->
                <!--<a href="#" class="search_form_calendar_2"><?php echo image_tag("img_search/land_form_ico_calendar.png") ?></a>-->
                <p class="txt_buscador">Busca de automóviles cerca de tu ubicación <span>(búsqueda automática)</span></p>
                
                <div class="search_box_ubica">
                	<span class="group_desde">Ubicación</span>
                	<input id="searchTextField" type="text" size="50" placeholder="Introduce una ubicación" autocomplete="off"/>
                </div>
                
                <div class="search_box1_form">
                	<span class="group_desde">Disponible desde</span><span class="group_hasta">Hasta</span>
                    <input class="input_f1" readonly="readonly" type="text" id="day_from" value="Dia de inicio"/>
                    <input class="input_f1" readonly="readonly" type="text" id="hour_from" value=""/>
                    <input class="input_f1" readonly="readonly" type="text" id="day_to" value="Dia de entrega"/>
                    <input class="input_f1b" readonly="readonly" type="text" id="hour_to"  value="Hora de entrega" />
                    <a id="more" class="button2 altCta2 menor" href="#"><span id="spanprecio">+</span></a>
                </div>

                <!-- buscador avanzado -->
                <div class="search_ext_box">
                	<span class="group_filtar">Filtrar por</span>
                    <div class="search_ext_wrapper">
                        <select name="price" id="price" style="width:140px;margin-right:20px;" >
                            <option value="-" selected="selected">Precio</option>
                            <option value="1">Menor a mayor</option>
                            <option value="0">Mayor a menor</option>
                        </select> 

<!--<select>
    
    <option>Peugeot</option>
    <option>Fiat</option>     
    <option>Ford</option>     
    <option>Citroen</option>              
</select> 
                        -->
                        <select name="brand" id="brand" style="width:140px;margin-right:20px;" >
                            <option value="" selected="selected">Marca</option>	
                            <?php foreach ($brand as $b): ?>	
                                <option value="<?= $b->getId() ?>" ><?= $b->getName() ?></option> 		
                            <?php endforeach; ?>
                        </select>

                        <select name="model" id="model" style="width:140px;" >
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

                <h2>Resultado de b&uacute;squeda</h2>
                <!-- ?php echo image_tag("img_landing/land_box_header_flecha.png", array("class" => "search_box_header_flecha")) ? -->             

            </div><!-- land_box_2_header -->  

            <div class="search_arecomend_window">     

             <!--   <?php foreach ($offers as $c): ?>        

             
                    <div class="search_arecomend_item">
                        <div class="search_arecomend_frame">


                        </div>

                        <ul class="search_arecomend_info">
                           
                            <li>A&ntilde;o: <?= $c->getYear() ?></li>
                            <li>D&iacute;a: $<?= floor($c->getPriceday()) ?> - Hora: $<?= floor($c->getPricehour()) ?></li>
                        </ul>
                    </div>


                <?php endforeach; ?> 
	
	-->

            </div><!-- search_arecomend_window -->   



        </div><!-- search_box_2 -->    

        <div class="clear"></div>
    </div><!-- search_sector1 -->
</div>
</div>


</div><!-- search_container -->