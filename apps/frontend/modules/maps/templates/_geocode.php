<script src="https://maps.google.com/maps/api/js?v=3&client=AIzaSyDlyhAd4qtsSQfnIl0LPByHb4yQRnS5-u8&sensor=false"
type="text/javascript"></script>

<script type="text/javascript">
//<![CDATA[
// globals
  var map;
  var marker;
  var places = [ ];
// geocoder variables and constants
  var geo = new google.maps.Geocoder();
  var reasons = [ ] ;
    reasons[G_GEO_SUCCESS] = "Success" ;
    reasons[G_GEO_MISSING_ADDRESS] = "Missing Address: The address was either missing or had no value." ;
    reasons[G_GEO_UNKNOWN_ADDRESS] = "Unknown Address:  No corresponding geographic location could be found for the specified address." ;
    reasons[G_GEO_UNAVAILABLE_ADDRESS] = "Unavailable Address:  The geocode for the given address cannot be returned due to legal or contractual reasons." ;
    reasons[G_GEO_BAD_KEY] = "Bad Key: The API key is either invalid or does not match the domain for which it was given" ;
    reasons[G_GEO_TOO_MANY_QUERIES] = "Too Many Queries: The daily geocoding quota for this site has been exceeded." ;
    reasons[G_GEO_SERVER_ERROR] = "Server error: The geocoding request could not be successfully processed." ;
    reasons[G_GEO_BAD_REQUEST] = "A directions request could not be successfully parsed.";
    reasons[G_GEO_MISSING_QUERY] = "No query was specified in the input.";
    reasons[G_GEO_UNKNOWN_DIRECTIONS] = "The GDirections object could not compute directions between the points.";
    var accuracy = [ ] ;
    accuracy[0] = 'Unknown' ;
    accuracy[1] = 'Country' ;
    accuracy[2] = 'Region' ;
    accuracy[3] = 'Sub-region' ;
    accuracy[4] = 'Town' ;
    accuracy[5] = 'Post code' ;
    accuracy[6] = 'Street' ;
    accuracy[7] = 'Intersection' ;
    accuracy[8] = 'Address' ;
    accuracy[9] = 'Premise' ;
// fix precision of reported Lat/Lng
    function fix6ToString(n) {
    return n.toFixed(6).toString();
  }
// get window width independent of browser
  function getWWidth() {
    var myWidth = 0;
    if( typeof( window.innerWidth ) == 'number' ) {
    //Non-IE
      myWidth = window.innerWidth;
    } else if( document.documentElement && document.documentElement.clientWidth) {
    //IE 6+ in 'standards compliant mode'
      myWidth = document.documentElement.clientWidth;
    } else if( document.body && document.body.clientWidth ) {
    //IE 4 compatible
      myWidth = document.body.clientWidth;
    }
    return myWidth;
  }
// get window height independent of browser
  function getWHeight() {
    var myHeight = 0;
    if( typeof( window.innerHeight ) == 'number' ) {
      myHeight = window.innerHeight;
    } else if( document.documentElement && document.documentElement.clientHeight) {
      myHeight = document.documentElement.clientHeight;
    } else if( document.body && document.body.clientHeight ) {
      myHeight = document.body.clientHeight;
    }
    return myHeight;
  }
// set div size
  function setDivSize() {
    if (getWWidth()) {  // if it works!
      document.getElementById("map").style.width = getWWidth() > 758 ? Math.ceil(getWWidth()*0.95) +'px' : '720px' ;
    }
    if (getWHeight()) {
      document.getElementById("map").style.height = Math.ceil(getWHeight()*0.925) +'px';
    }
  }
// resize map keeping same centre
  function resizeAndCenterMap() {
    var mapcenter = map.getCenter();
    setDivSize();
    map.checkResize();
    map.setCenter(mapcenter);
  }
// nudges
  function nudgeW() {
    var ll = marker.getPoint();
    var newll = new GLatLng(ll.lat(), (ll.lng() - nudgeFactor().lng()));
    marker.setPoint(newll);
    marker.redraw();
    GEvent.trigger(marker,'click');
    }
  function nudgeE() {
    var ll = marker.getPoint();
    var newll = new GLatLng(ll.lat(), (ll.lng() + nudgeFactor().lng()));
    marker.setPoint(newll);
    marker.redraw();
    GEvent.trigger(marker,'click');
    }
  function nudgeN() {
    var ll = marker.getPoint();
    var newll = new GLatLng(ll.lat() + nudgeFactor().lat(), ll.lng());
    marker.setPoint(newll);
    marker.redraw();
    GEvent.trigger(marker,'click');
    }
  function nudgeS() {
    var ll = marker.getPoint();
    var newll = new GLatLng(ll.lat() - nudgeFactor().lat(), ll.lng());
    marker.setPoint(newll);
    marker.redraw();
    GEvent.trigger(marker,'click');
    }
// nudgeFactor returns GLatLng with Lat & Lng per pixel
  function nudgeFactor() {
    var b = map.getBounds().toSpan(); // LatLng as size of the map
    var s = map.getSize(); // width and height of map in pixels
    return new GLatLng(b.lat()/s.height , b.lng()/s.width );
  }
// zoom in
  function zoomIn() {
    var mag = map.getZoom();
    if (mag < 19) mag += 1;
    map.setCenter(map.getCenter(), mag);
  }
// zoom out
  function zoomOut() {
    var mag = map.getZoom();
    if (mag > 2) mag -= 1;
    map.setCenter(map.getCenter(), mag);
  }

// main map drawing function
  function load() {
    var nudgestring =  "<table cellpadding='0' cellspacing='0' border='0' class='blockcenter'><tr><td>&nbsp;</td><td><a href='javascript:nudgeN()' accesskey='n'><img src='images/btn_arrow_up.gif' alt='up' title='nudge north' width='20' height='20' border='0' /></a></td><td>&nbsp;</td></tr>" +
"<tr><td><a href='javascript:nudgeW()'><img src='images/btn_arrow_l.gif' alt='left' title='nudge west' width='20' height='20' border='0' /></a></td><td><img src='images/mark_mini.gif' alt='marker' title='nudge marker' width='20' height='20' /></td><td><a href='javascript:nudgeE()'><img src='images/btn_arrow_r.gif' alt='right' title='nudge east' width='20' height='20' border='0' /></a></td></tr>" +
"<tr><td>&nbsp;</td><td><a href='javascript:nudgeS()'><img src='images/btn_arrow_dn.gif' alt='down' title='nudge south' width='20' height='20' border='0' /></a></td><td>&nbsp;</td></tr></table>";
    if (GBrowserIsCompatible()) {
// Reset the initial size of the map div to fill screen
      setDivSize();
// Start processing the map
      map = new GMap2(document.getElementById("googleMap"));
      GEvent.addListener(map, "moveend", function() {
        var center = map.getCenter();
        document.getElementById("message").innerHTML = "Centre of map @ " + fix6ToString(center.lat()) + " , " + fix6ToString(center.lng());
      });
      GEvent.addListener(map, "dblclick", function(moverlay, mpoint) {
          marker.setPoint(mpoint);
        GEvent.trigger(marker,'click');
      });
// Lane Realty pos
      var LRPoint = new GLatLng(-27.928500,153.183897);
      var CentrePoint = new GLatLng(-27.94255,153.1839);
// Add controls
      map.addMapType(G_PHYSICAL_MAP);
      map.addControl(new GLargeMapControl());
      map.addControl(new GScaleControl());
      map.addControl(new GMapTypeControl());
      map.setCenter(CentrePoint,13);
// Now the marker and click label
      marker = new GMarker(LRPoint, {draggable: true});
      GEvent.addListener(marker, "click", function() {
        var point = marker.getPoint();
        marker.openInfoWindowHtml( fix6ToString(point.lat()) + " , " + fix6ToString(point.lng()) + nudgestring);
      });
      GEvent.addListener(marker, "dragstart", function() {
        map.closeInfoWindow();
      });
      GEvent.addListener(marker, "dragend", function() {
        var point = marker.getPoint();
        marker.openInfoWindowHtml( fix6ToString(point.lat()) + " , " + fix6ToString(point.lng()) + nudgestring);
      });
      map.addOverlay(marker);
// Monitor the window resize event and let the map know when it occurs
      if (window.attachEvent) { // IE
        window.attachEvent("onresize", function() {resizeAndCenterMap();} );
      } else {          // others
        window.addEventListener("resize", function() {resizeAndCenterMap();} , false);
      }
// Display marker info window
      GEvent.trigger(marker,'click');
// Add Lat Long to centre report at base of map
      GEvent.trigger(map,'moveend');
    }
  }
// Set marker for placemark
  function setPlaceMarker(n) {
        var p = places[n].Point.coordinates;
        map.setCenter(new GLatLng(p[1],p[0]), 8 + places[n].AddressDetails.Accuracy);
    marker.setPoint(new GLatLng(p[1],p[0]));
    marker.redraw();
    GEvent.trigger(marker,'click');
  }

// Find an Address...
  function findaddress( ){
    if (document.addressform.address.value) {
      var address = document.addressform.address.value;
      geo.getLocations(address, function (result) {
        if (result.Status.code == G_GEO_SUCCESS) {
              document.getElementById("geomessage").innerHTML = "Found " + result.Placemark.length +" result(s)";
              for (var i=0; i<result.Placemark.length; i++){
            places[i] = result.Placemark[i];
                var p = places[i].Point.coordinates;
                document.getElementById("geomessage").innerHTML += "<br /> <input type='button' value='Go To' onclick='setPlaceMarker(" + i + ")' name='goto" + i + "' /> "+(i+1)+": "+ places[i].address + " : " + p[1] + " , " + p[0] + "<br /> Accuracy: " + accuracy[places[i].AddressDetails.Accuracy] + ", Code: " + reasons[result.Status.code];
            }
          setPlaceMarker(0);
          } else {
              var reason="Code "+result.Status.code;
              if (reasons[result.Status.code]) {
                reason = reasons[result.Status.code]
              }
              alert('Could not find "' + address + '" ' + reason);
          }
        } ) ;
      } else {
      alert('Must have something in the address!')
    }
  }
</script>
