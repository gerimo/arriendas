<?php use_stylesheet('search.css') ?>
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


.search_ext_box { z-index: 1;}

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
	
	google.maps.event.addDomListener(window, 'load', initialize);
    
    function initialize() {
      	      
        <?php if(stripos($_SERVER['SERVER_NAME'],"arrendas") !== FALSE) 
		echo "var center = new google.maps.LatLng(-34.59, -58.401604);";
	   else
		echo "var center = new google.maps.LatLng(-33.427224, -70.605558);";
	?> 

        map = new google.maps.Map(document.getElementById('map'), {
          zoom: 15,
          center: center,
          mapTypeId: google.maps.MapTypeId.ROADMAP,
          scrollwheel: true
        });
        
       
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
		
       
      }
      
      function doReload(){
	  
	        	
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
		  
  	       
      	     
		
  	     // var url = "<?php echo url_for('main/searchResult')?>"+"?hour_from=" + hour_from_hidden  + "&hour_to=" + hour_to_hidden  + "&brand=" + brand_hidden  + "&location=" + location_hidden  + "&price=" + price_hidden  + "&swLat="+swLat+"&swLng="+swLng+"&neLat="+neLat+"&neLng="+neLng;
  	      var url = "<?php echo url_for('main/searchResult')?>?day_from=" + day_from_hidden + "&day_to=" + day_to_hidden +"&model="+ model_hidden + "&hour_from=" + hour_from_hidden  + "&hour_to=" + hour_to_hidden  + "&brand=" + brand_hidden  + "&location=" + location_hidden  + "&price=" + price_hidden  + "&swLat="+swLat+"&swLng="+swLng+"&neLat="+neLat+"&neLng="+neLng;
		  
  	    //  document.write(var_dump(url,'html'));
  	      
  	      $("#searchContent").load(url,  function() {
  	     
		 
  	      		 //     var numItems = $('.countCarrousel').length;
  	      		      //alert(numItems);
  	      		      
  	      		     /* $('#mycarousel_search').jcarousel({
  	      		      		      wrap: 'circular',
  	      		      		      auto: 4,
  	      		      		      scroll: 5,
  	      		      		      size:numItems,
  	      		      		      initCallback: mycarousel_initCallback
  	      		      		      
  	      		      });*/
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


  	      searchMarkers();  
  	      doReload();
  	        	
  	      
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
	    
		
		
			
	     var url = "<?php echo url_for('main/map') ?>?day_from=" + day_from_hidden + "&day_to=" + day_to_hidden + "&model=" + model_hidden + "&hour_from=" + hour_from + "&hour_to=" + hour_to + "&brand=" + brand_hidden  + "&location=" + location_hidden  + "&price=" + price_hidden  + "&swLat="+swLat+"&swLng="+swLng+"&neLat="+neLat+"&neLng="+neLng+"";
		 	    
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
			'<img class="imagemark" width="112" height="84" src="' + dataCar.photo  + '"/>' +					
			'</div><div class="mapcar_titulo"><a href="<?php echo url_for('cars/car?id=')?>'+dataCar.id + '">' + dataCar.brand + ' ' + dataCar.model +
			'</a> </div><div class="mapcar_info"><p>Precio x hora: <b>$' + dataCar.price_per_hour + '</b> <br>Precio x d&iacute;a: $' + dataCar.price_per_day +
			'<br>10 reviews <br></p></div> <p class="mapcar_user"><span class="txt_user">Usuario:</span><br/><a href="#">' + dataCar.username + '</a> <span><a href="#">Leer mensajes</a></span>' +       
			'</p><a href="<?php echo url_for('cars/car?id=')?>' + dataCar.id + '" class="mapcar_btn_detalle"><span>Reservar</span></a></div>';	
					
				/*var contentString = 
				'<div id="InfoCar">'+
				'<div style=""><img class="imagemark" weight="122" height="102" src="' + dataCar.photo  + '"/></div>' +
				'<div style="float:right;"><h1><a href="<?=url_for('profile/publicprofile?id=');?>'+ dataCar.userid + 
				'" style="color: #d11978;text-decoration: none;">'+ dataCar.username + '</a></h1><br/>' +
				'<b>' +  dataCar.brand + ' '+ dataCar.model   + '(' + dataCar.year + ')</b><br/><br/>' +
				'<b>Precio por hora:</b> $ ' +  dataCar.price_per_hour + '<br/>' +
				'<b>Precio por día:</b> $ ' +  dataCar.price_per_day + '<br/><br/><br/>' +
				'<a href="<?php echo url_for('cars/car?id=')?>' + dataCar.id + '">'+
				'<img class="imagemark" src="<?=image_path("btn_reserva.png");?>" />' + 
				'</a></div>' +
				'</div>';
*/
			
			if (infowindow) infowindow.close(); 
			var infowindow = new google.maps.InfoWindow({
			    content: contentString
			});
			
			//var image = 'images/beachflag.png';
			
			var marker = new google.maps.Marker({
				id: dataCar.id,
				position: latLng,
				//icon: image,
				contentString: contentString
			});
			
			
			google.maps.event.addListener(marker, 'click', function() {
				infowindow.setContent(this.contentString);
				infowindow.open(map,this);
			});
			
			markers.push(marker);
			
			
			
			
		}
        
		createClickToMarker();
		
        markerCluster = new MarkerClusterer(map, markers);
	  	//alert(markers);
        
        });
            
}



function createClickToMarker(){

	
	$(".returncar_box").click(function () {
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
	
/*	$('#day_from').change(function() {
		doSearch();
	});
	
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
				dateFormat: 'yy-mm-dd', 
			buttonImageOnly: true,
			minDate:'-0d'
		  });
		  
		  $( "#day_to" ).datepicker({
				dateFormat: 'yy-mm-dd', 
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
	
     $.getJSON("<?php echo url_for('main/getModelSearch')?>",{id:currentElement.val(), ajax: 'true'}, function(j){
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

      #map {
        width: 630px;
        height: 520px;
   
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
 
 .header{
	margin-bottom:10px;
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


<div class="search_container">
    
<div class="search_sector1">      
    

<!--  busqueda avanzada -->
        <div class="search_box_1">
        <div class="search_box_1_header">
            <!--<a href="#" class="search_form_calendar_1"><?php echo image_tag("img_search/land_form_ico_calendar.png")?></a>-->
            <!--<a href="#" class="search_form_calendar_2"><?php echo image_tag("img_search/land_form_ico_calendar.png")?></a>-->
            <div class="search_box1_form">
                <input style="width:110px;margin-right:35px;" readonly="readonly" type="text" id="day_from" value="Dia de inicio"/>
				<input style="width:110px;margin-right:25px;" readonly="readonly" type="text" id="hour_from" value="Hora de inicio"/>
				<!--<input style="width:280px;margin-right:25px;" readonly="readonly" type="text" id="hour_from" value="Hora de inicio"/>-->
				<input style="width:110px;margin-right:35px;" readonly="readonly" type="text" id="day_to" value="Dia de entrega"/>
                <input style="width:110px;margin-right:0px;" readonly="readonly" type="text" id="hour_to"  value="Hora de entrega" />
				<!--<input style="width:280px;margin-right:0px;" readonly="readonly" type="text" id="hour_to"  value="Hora de entrega" />-->
                <!--<button onclick="doSearch()"></button>-->
            </div>
            
            <!-- buscador avanzado -->
            <div class="search_ext_box">
            <div class="search_ext_wrapper">
            <select name="price" id="price" >
                <option value="" selected="selected">Precio x hora</option>
                <option value="40-100">40 a 100 x hora</option>
                <option value="100-120">100 a 120 x hora</option>
                <option value="120-140">120 a 140 x hora</option>
                <option value="140-999">M&aacute;s de 140 x hora</option>
            </select> 
            
            <!--<select>
                
                <option>Peugeot</option>
                <option>Fiat</option>     
                <option>Ford</option>     
                <option>Citroen</option>              
            </select> 
            -->
            <select name="brand" id="brand">
            		<option value="" selected="selected">Marca</option>	
			<?php foreach ($brand as $b): ?>	
				<option value="<?=$b->getId() ?>" ><?=$b->getName() ?></option> 		
			<?php endforeach; ?>
	    </select>
		
		<select name="model" id="model">
		<option value="" selected="selected">Modelo</option>
	    </select>
		
           <!-- <select name="location" id="location">
            		<option value="" selected="selected">Pais</option>
			<?php foreach ($country as $c): ?>
				<option value="<?=$c->getId() ?>" ><?=$c->getName() ?></option> 		
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
            
               <div id="map"></div>
        </div><!-- search_box_1_maparea -->        
        </div><!-- search_box_1 -->     



<!--  autos recomendados -->
        <div class="search_box_2">
        <div class="search_box_2_header">
        <h2>Autos recomendados</h2>
        <?php echo image_tag("img_landing/land_box_header_flecha.png",array("class"=>"search_box_header_flecha"))?>             
        </div><!-- land_box_2_header -->  
        
        <div class="search_arecomend_window">     

<?php foreach ($offers as $c): ?>        
        
<!-- coche item-->
<div class="search_arecomend_item">
<div class="search_arecomend_frame">

				<?php if ($c->getPhotoFile('main') != NULL): ?> 
							<?= image_tag("cars/".$c->getPhotoFile('main')->getFileName(), 'size=64x44') ?>
				<?php else: ?>
							<?= image_tag('default.png', 'size=64x44') ?>  
				<?php endif; ?>

</div><!-- land_arecomend_frame -->
            
<ul class="search_arecomend_info">
<li class="search_arecomend_marca"><a target="_blank" href="<?php echo url_for('cars/car?id=' . $c->getId() )?>"><?=$c->getBrand()?> <?=$c->getModel()?> </a></li>
<li>A&ntilde;o: <?=$c->getYear()?></li>
<li>D&iacute;a: $<?=$c->getPriceday()?> - Hora: $<?=$c->getPricehour()?></li>
</ul>
</div><!-- search_arecomend_item -->


<?php endforeach; ?> 
  

</div><!-- search_arecomend_window -->   
        

        
        </div><!-- search_box_2 -->    
    
    <div class="clear"></div>
 </div><!-- search_sector1 -->
    

	
	<div class="search_sector2">

<?php /*	
<div class="nearcar">

	<!-- los coches mas cercanos -->
	<div class="search_box_3">
	<div class="search_box_3_header">
	<h2>Los 5 autos mas cercanos</h2>     
	</div><!-- search_box_3_header --> 
	
	<div class="nearcar_area">
	
	<!-- coche -->
	<div class="nearcar_box">
	    <div class="nearcar_frame">
		<img src="images/img_search/near_car_foto_tmp.jpg" width="132" height="92" />
	    </div><!-- nearcar_frame -->
	    
	    <div class="nearcar_info">
		<a href="#">Volkswagen Bora 1.9</a>
		<span>A�o: 2011<br>D�a: $150 - Hora: $40</span>        
	    </div><!-- nearcar_info -->
	    
	    <a href="#" class="nearcar_btn_detalle"><span>Detalle</span></a>
	</div><!-- nearcar_box -->
	
	
	<!-- coche -->
	<div class="nearcar_box">
	    <div class="nearcar_frame">
		<img src="images/img_search/near_car_foto_tmp.jpg" width="132" height="92" />
	    </div><!-- nearcar_frame -->
	    
	    <div class="nearcar_info">
		<a href="#">Volkswagen Bora 1.9</a>
		<span>A�o: 2011<br>D�a: $150 - Hora: $40</span>        
	    </div><!-- nearcar_info -->
	    
	    <a href="#" class="nearcar_btn_detalle"><span>Detalle</span></a>
	</div><!-- nearcar_box -->
	      
	      
	      
	<!-- coche -->
	<div class="nearcar_box">
	    <div class="nearcar_frame">
		<img src="images/img_search/near_car_foto_tmp.jpg" width="132" height="92" />
	    </div><!-- nearcar_frame -->
	    
	    <div class="nearcar_info">
		<a href="#">Volkswagen Bora 1.9</a>
		<span>A�o: 2011<br>D�a: $150 - Hora: $40</span>        
	    </div><!-- nearcar_info -->
	    
	    <a href="#" class="nearcar_btn_detalle"><span>Detalle</span></a>
	</div><!-- nearcar_box -->
	
	<!-- coche -->
	<div class="nearcar_box">
	    <div class="nearcar_frame">
		<img src="images/img_search/near_car_foto_tmp.jpg" width="132" height="92" />
	    </div><!-- nearcar_frame -->
	    
	    <div class="nearcar_info">
		<a href="#">Volkswagen Bora 1.9</a>
		<span>A�o: 2011<br>D�a: $150 - Hora: $40</span>        
	    </div><!-- nearcar_info -->
	    
	    <a href="#" class="nearcar_btn_detalle"><span>Detalle</span></a>
	</div><!-- nearcar_box -->
	
	
	<!-- coche -->
	<div class="nearcar_box">
	    <div class="nearcar_frame">
		<img src="images/img_search/near_car_foto_tmp.jpg" width="132" height="92" />
	    </div><!-- nearcar_frame -->
	    
	    <div class="nearcar_info">
		<a href="#">Volkswagen Bora 1.9</a>
		<span>A�o: 2011<br>D�a: $150 - Hora: $40</span>        
	    </div><!-- nearcar_info -->
	    
	    <a href="#" class="nearcar_btn_detalle"><span>Detalle</span></a>
	</div><!-- nearcar_box -->     
	      
	      
	</div><!-- nearcar_area -->
		
	</div><!-- search_box_3 -->

</div>

*/ ?>

<div class="returncar">

		<!-- coches resultados busqueda -->
		<div class="search_box_3">
		<div class="search_box_3_header">
		<h2>Resultados de la b&uacute;squeda</h2>     
		</div><!-- search_box_3_header --> 
		
		<div class="returncar_area">
		    
		<div id="searchContent"></div>
		      
		</div><!-- returncar_area -->
			
		</div><!-- search_box_3 -->

        
</div>



</div><!-- search_sector2 -->

</div><!-- search_container -->





