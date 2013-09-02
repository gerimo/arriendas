<?php use_helper("jQuery")?>
<div id="show-maps">
  <div id="head-maps">Marque la ubicaci&oacute;n del veh&iacute;culo</div>
  <div id="maps" class="maps_popup"></div>
  <div id="tool-maps">
    <div id="message"></div>
    <input type="button" value="Seleccionar" onclick="confirmarCerrar()"/>
  </div>
</div>
<script type="text/javascript">
var geocoder;
var map;
var infowindow = new google.maps.InfoWindow();
var slat;
var slng;
$(document).ready(function(){
  initialize();
});
function initialize() {
  var center = new google.maps.LatLng(<?=$lat?>,<?=$lng?>);
  map = new google.maps.Map(document.getElementById('maps'), {
    zoom: 15,
    center: center,
    mapTypeId: google.maps.MapTypeId.ROADMAP,
    scrollwheel: true
  });
  crearMarca(center);
  geocoder = new google.maps.Geocoder();
  google.maps.event.addListener(map,"click",function(event){
    var position = getPosition(event.latLng);
    crearMarca(position);
    showAddress(position);
    
google.maps.event.addListener(marker, "dragend", function(event) {
      showAddress(event.latLng);
    });
    google.maps.event.addListener(marker, "click", function(event) {
      showAddress(event.latLng);
    });

  });
}
function getPosition(position){
    var input = position+"";
    var latlngStr = input.split(",",2);
    var lat = parseFloat(latlngStr[0].substr(1));
    var lng = parseFloat(latlngStr[1].substr(1));
    var latlng = new google.maps.LatLng(lat, lng);
    return latlng;
}
function showAddress(position){
  var input = position+"";
  var latlngStr = input.split(",",2);
  var lat = slat = parseFloat(latlngStr[0].substr(1));
  var lng = slng = parseFloat(latlngStr[1].substr(1));
  var latlng = new google.maps.LatLng(lat, lng);
  geocoder.geocode({'latLng': latlng}, function(results, status) {
    if (status == google.maps.GeocoderStatus.OK) {
      if (results[1]) {
        map.setZoom(15);
        markerData(results,marker);
      } else {
        alert("No results found");
      }
    } else {
      alert("Geocoder failed due to: " + status);
    }
  });
}
function crearMarca(position){
  marker = new google.maps.Marker({
      position: position,
      map: map,
      draggable: true,
  });
  return marker;
}

function markerData(results,marker){
  $('#message').html(results[0].formatted_address);
}

function procesarDatos(){
  var pais = $("#sscountry").val();
  if (pais != 0 ) {
    $("#country").val(pais);
    getState($("#country"));
    $("#state").val($("#ssstate").val());
    $("#city").val($("#sscity").val());
  //  $("#address").val($("#ssaddress").val());	
    $("#address").prev('span').css("visibility","hide");	
	
  }
	enviarDatos();
}
function enviarDatos(){
  $("#frommap").val(1);
  $("#TB_window").remove();
  $("#TB_overlay").remove();
}
</script>
<?php
echo "<script language='javascript'>
function confirmarCerrar(){
  var datos = 'message='+$('#message').html()+'&lat='+slat+'&lng='+slng;
  ".jq_remote_function(array(
    "update"=>"acava",
    "url"=>"maps/nuevasUbicaciones",
    "with"=>"datos",
	"complete"=>"procesarDatos()",
	"script"=>true
  )).";
}

</script>";
?>