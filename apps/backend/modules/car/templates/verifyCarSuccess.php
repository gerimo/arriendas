
<script type="text/javascript" src="https://maps.google.com/maps/api/js?sensor=false"></script>
<div class="container">

    <div class="hidde-xs space-100"></div>

    <div class="col-md-offset-4 col-md-4" id="buscar">
        <label>Busqueda</label> 
        <input class="form-control" id="carId" placeholder="Id auto" type="text">
        <div class="hidde-xs space-20"></div>
        <button class="buscar btn btn-block btn-primary" onclick="findCar()">Buscar</button>
    </div>

    <div id="formulario">
        <h1 class="text-center ">Verificar auto</h1>
        <div class="space-20"></div>
        <div id="hk-container" class="carousel slide" data-ride="carousel">
            <div class="carousel-inner" role="listbox">
                <div class="item active">
                    <div class="container hk-container">
                        <h3 class="col-md-12">Fotos del auto</h3>
                        <div class="col-md-12">
                            <div class="col-md-4">
                                <input name="photo" type="file" id="photo" class="file-loading photo" accept="image/*">
                            </div>
                            <img id="imagePhoto"></img>
                        </div>
                    </div>
                    <div class="space-100"></div>
                    <div class="row">
                        <div class="col-md-offset-6 col-md-6"><button class="btn btn-block btn-primary" id="buttonOne" data-target="#hk-container" data-slide-to="1" onclick="initializeCarImage()">Siguiente</button></div>
                        <div class="col-md-offset-6 col-md-6"><button class="btn btn-block btn-primary" id="buttonTwo" data-target="#hk-container" data-slide-to="1">Siguiente</button></div>
                    </div>
                    <div class="space-100"></div>
                </div>
                    
                <div class="item" >
                    <h2>Daños del auto</h2>                
                     <div class="container hk-container">
                        <div class="col-md-offset-3 col-md-6">
                            <div id="map_car" style="width:550;height:400px;"></div>
                        </div>
                    </div>
                    <div class="space-100"></div>
                    <div class="row">
                        <div class="col-xs-6 col-sm-6 col-md-6"><button class="btn btn-block btn-primary" data-target="#hk-container" data-slide-to="0">Anterior</button></div>
                        <div class="col-xs-6 col-sm-6 col-md-6"><button class="btn btn-block btn-primary" data-target="#hk-container" data-slide-to="2">Siguiente</button></div>
                    </div>
                    <div class="space-100"></div>
                </div> 

                <div class="item">
                    <h2>Audio del auto</h2>
                    <div class="col-md-offset-2 col-md-8"id="soundOption">
                        <div class="col-md-12"><label>¿Tiene el equipo de audio original de se vehículo?</label></div>
                        <div class="col-md-6"><button class="btn btn-block btn-primary soundOptionYes">SI</button></div>
                        <div class="col-md-6"><button class="btn btn-block btn-primary" data-target="#hk-container" data-slide-to="3">NO</button></div>
                    </div>
                    <div class="container hk-container" id="audio">                      
                        <div class="col-md-12"> 
                            <div class="col-md-4">
                                <label>1.-Radio</label>
                                <input  class="form-control"  id="radioMarca"  placeholder="Marca"  type="text">
                                <input  class="form-control"  id="radioModelo" placeholder="Modelo" type="text">
                                <select class="form-control"  id="radioTipo"   type="text">
                                    <option value="">Tipo</option>
                                    <option value="radioCassette">Radio Cassette</option>
                                    <option value="radioCD">Radio CD</option>
                                    <option value="soloRadio">Sólo Radio</option>
                                    <option value="panelDesmontable">Panel Desmontable</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label>2.-Parlantes</label>
                                <input  class="form-control"  id="parlantesMarca"  placeholder="Marca"  type="text">
                                <input  class="form-control"  id="parlantesModelo" placeholder="Modelo" type="text">
                            </div>
                            <div class="col-md-4">
                                <label>3.-Tweeters</label>
                                <input  class="form-control"  id="tweetersMarca"  placeholder="Marca"  type="text">
                                <input  class="form-control"  id="tweetersModelo" placeholder="Modelo" type="text">
                            </div>
                        </div>
                        <div class="col-md-12"> 
                            <div class="space-30"></div>    
                            <div class="col-md-4">
                                <label>4.-Ecualizador</label>
                                <input  class="form-control"  id="ecualizadorMarca"  placeholder="Marca"  type="text">
                                <input  class="form-control"  id="ecualizadorModelo" placeholder="Modelo" type="text">
                            </div>
                            <div class="col-md-4">
                                <label>5.-Amplificador</label>
                                <input  class="form-control"  id="amplificadorMarca"  placeholder="Marca"  type="text">
                                <input  class="form-control"  id="amplificadorModelo" placeholder="Modelo" type="text">
                            </div>
                            <div class="col-md-4">
                                <label>6.-Compact CD</label>
                                <input  class="form-control"  id="compactCdMarca"  placeholder="Marca"  type="text">
                                <input  class="form-control"  id="compactCdModelo" placeholder="Modelo" type="text">
                            </div>
                        </div>
                        <div class="col-md-12"> 
                            <div class="space-30"></div> 
                            <div class="col-md-4">
                                <label>7.-Subwoofer</label>
                                <input  class="form-control"  id="subwooferMarca"  placeholder="Marca"  type="text">
                                <input  class="form-control"  id="subwooferModelo" placeholder="Modelo" type="text">
                            </div>
                            <div class="col-md-4">
                                <label>8.-Sistema DVD</label>
                                <input  class="form-control"  id="sistemaDvdMarca"  placeholder="Marca"  type="text">
                                <input  class="form-control"  id="sistemaDvdModelo" placeholder="Modelo" type="text">
                            </div>
                            <div class="col-md-4">
                                <label>9.-Otros Accesorios</label>
                                <textarea class="form-control"  id="otrosAccesorios"  placeholder="Escriba aquí..."  type="text"></textarea>
                            </div>
                        </div>
                        <div class="col-md-offset-8 col-md-4">
                            <button class="audioAccessorieButton btn btn-block btn-success" onclick="saveAudioAccessories()">Guardar</button>
                        </div>
                        <div class="col-md-12">
                            <div class="space-60"></div> 
                            <div class="col-xs-6 col-sm-6 col-md-6"><button class="btn btn-block btn-primary" data-target="#hk-container" data-slide-to="1">Anterior</button></div>
                            <div class="col-xs-6 col-sm-6 col-md-6"><button class="btn btn-block btn-primary" data-target="#hk-container" data-slide-to="3">Siguiente</button></div>
                        </div>
                        <div class="space-100"></div>
                    </div>  
                </div>  

                <div class="item">
                    <h2>Accesorios del auto</h2>
                    <div class="container hk-container">                      
                         <div class="col-md-4">
                            <div class="radio"><label><input class="checkbox" type="checkbox" id="aireAcondicionado" value="aireAcondicionado">Aire Acondicionado</label></div>
                            <div class="radio"><label><input class="checkbox" type="checkbox" id="parabrisasOriginal" value="parabrisasOriginal">Parabrisas Original</label></div>
                            <div class="radio"><label><input class="checkbox" type="checkbox" id="vidriosPolarizados" value="vidriosPolarizados">Vidrios Polarizados</label></div>
                        </div>
                        <div class="col-md-4">
                            <div class="radio"><label><input class="checkbox" type="checkbox" id="espejos" value="espejos">Espejos</label></div>
                            <div class="radio"><label><input class="checkbox" type="checkbox" id="ruedaRepuesto" value="ruedaRepuesto">Rueda Repuesto</label></div>
                            <div class="radio"><label><input class="checkbox" type="checkbox" id="cilindrosDeSeguridad" value="cilindrosDeSeguridad">Cilindros De Seguridad</label></div>
                        </div>
                        <div class="col-md-4">
                            <div class="radio"><label><input class="checkbox" type="checkbox" id="electricos" value="electricos">Eléctricos</label></div>
                            <div class="radio"><label><input class="checkbox" type="checkbox" id="llantaAleacion" value="llantaAleacion">Llanta Aleación</label></div>
                            <div class="radio"><label><input class="checkbox" type="checkbox" id="techoLona" value="techoLona">Techo Lona</label></div>
                        </div>
                        <div class="col-md-4">
                            <div class="radio"><label><input class="checkbox" type="checkbox" id="manuales" value="manuales">Manuales</label></div>
                            <div class="radio"><label><input class="checkbox" type="checkbox" id="llantaFierro" value="llantaFierro">Llanta Fierro</label></div>
                            <div class="radio"><label><input class="checkbox" type="checkbox" id="techoFibra" value="techoFibra">Techo Fibra</label></div>
                        </div>
                        <div class="col-md-4">
                            <div class="radio"><label><input class="checkbox" type="checkbox" id="vidrios" value="vidrios">Vidrios</label></div>
                            <div class="radio"><label><input class="checkbox" type="checkbox" id="huinche" value="huinche">Huinche</label></div>
                            <div class="radio"><label><input class="checkbox" type="checkbox" id="laminasDeSeguridad" value="laminasDeSeguridad">Laminas De Seguridad</label></div>
                        </div>
                        <div class="col-md-4">
                            <div class="radio"><label><input class="checkbox" type="checkbox" id="electricos" value="electricos">Eléctricos</label></div>
                            <div class="radio"><label><input class="checkbox" type="checkbox" id="equipoDeFrio" value="equipoDeFrio">Equipo De Frío</label></div>
                            <div class="radio"><label><input class="checkbox" type="checkbox" id="parrillaAmericana" value="parrillaAmericana">Parrilla Americana</label></div>
                        </div>
                        <div class="col-md-4">
                            <div class="radio"><label><input class="checkbox" type="checkbox" id="manuales" value="manuales">Manuales</label></div>
                            <div class="radio"><label><input class="checkbox" type="checkbox" id="cupula" value="cupula">Cúpula</label></div>
                            <div class="radio"><label><input class="checkbox" type="checkbox" id="cubrePickUp" value="cubrePickUp">Cubre Pick Up</label></div>
                        </div>
                        <div class="col-md-4">
                            <div class="radio"><label><input class="checkbox" type="checkbox" id="cierreCentralizado" value="cierreCentralizado">Cierre Centralizado</label></div>
                            <div class="radio"><label><input class="checkbox" type="checkbox" id="controlCrucero" value="controlCrucero">Control Crucero</label></div>
                            <div class="radio"><label><input class="checkbox" type="checkbox" id="cocoDeArrastre" value="cocoDeArrastre">Coco De Arrastre</label></div>
                        </div>
                        <div class="col-md-4">
                            <div class="radio"><label><input class="checkbox" type="checkbox" id="alarma" value="alarma">Alarma</label></div>
                            <div class="radio"><label><input class="checkbox" type="checkbox" id="vidriosGrabados" value="vidriosGrabados">Vidrios Grabados</label></div>
                            <div class="radio"><label><input class="checkbox" type="checkbox" id="aleron" value="aleron">Alerón</label></div>
                        </div>
                        <div class="col-md-4">
                            <div class="radio"><label><input class="checkbox" type="checkbox" id="sunRoof" value="sunRoof">SunRoof</label></div>
                            <div class="radio"><label><input class="checkbox" type="checkbox" id="bluetooth" value="bluetooth">Bluetooth</label></div>
                            <div class="radio"><label><input class="checkbox" type="checkbox" id="antena" value="antena">Antena</label></div>
                        </div>
                        <div class="col-md-4">
                            <div class="radio"><label><input class="checkbox" type="checkbox" id="electricos" value="electricos">Eléctricos</label></div>
                            <div class="radio"><label><input class="checkbox" type="checkbox" id="sistemaABS" value="sistemaABS">Sistema ABS</label></div>
                            <div class="radio"><label><input class="checkbox" type="checkbox" id="manual" value="manual">Manual</label></div>
                        </div>
                        <div class="col-md-4">
                            <div class="radio"><label><input class="checkbox" type="checkbox" id="manuales" value="manuales">Manuales</label></div>
                            <div class="radio"><label><input class="checkbox" type="checkbox" id="cortinaRetractil" value="cortinaRetractil">Cortina Retráctil</label></div>
                            <div class="radio"><label><input class="checkbox" type="checkbox" id="electrica" value="electrica">Eléctrica</label></div>
                        </div>
                        <div class="col-md-4">
                            <div class="radio"><label><input class="checkbox" type="checkbox" id="climatizador" value="climatizador">Climatizador</label></div>
                            <div class="radio"><label><input class="checkbox" type="checkbox" id="airBag" value="airBag">Air Bag</label></div>
                            <div class="radio"><label><input class="checkbox" type="checkbox" id="tapiceria" value="tapiceria">Tapicería</label></div>
                        </div>
                        <div class="col-md-4">
                            <div class="radio"><label><input class="checkbox" type="checkbox" id="sensorAcercamiento" value="sensorAcercamiento">Sensor Acercamiento</label></div>
                            <div class="radio"><label><input class="checkbox" type="checkbox" id="moldurasPasoRueda" value="moldurasPasoRueda">Molduras Paso Rueda</label></div>
                            <div class="radio"><label><input class="checkbox" type="checkbox" id="cuero" value="cuero">Cuero</label></div>
                        </div>
                        <div class="col-md-4">
                            <div class="radio"><label><input class="checkbox" type="checkbox" id="sensorRetroceso" value="sensorRetroceso">Sensor Retroceso</label></div>
                            <div class="radio"><label><input class="checkbox" type="checkbox" id="conosRueda" value="conosRueda">Conos Rueda</label></div>
                            <div class="radio"><label><input class="checkbox" type="checkbox" id="normal" value="normal">Normal</label></div>
                        </div>
                        <div class="col-md-4">
                            <div class="radio"><label><input class="checkbox" type="checkbox" id="ganchosAmarre" value="ganchosAmarre">Ganchos Amarre</label></div>
                            <div class="radio"><label><input class="checkbox" type="checkbox" id="neblineros" value="neblineros">Neblineros</label></div>
                            <div class="radio"><label><input class="checkbox" type="checkbox" id="neumaticos" value="neumaticos">Neumáticos</label></div>
                        </div>
                        <div class="col-md-4">
                            <div class="radio"><label><input class="checkbox" type="checkbox" id="llantasAleacion" value="llantasAleacion">Llantas Aleación</label></div>
                            <div class="radio"><label><input class="checkbox" type="checkbox" id="tapasDeRueda" value="tapasDeRueda">Tapas De Rueda</label></div>
                            <div class="radio"><label><input class="checkbox" type="checkbox" id="nuevos" value="nuevos">Nuevos</label></div>
                        </div>
                        <div class="col-md-4">
                            <div class="radio"><label><input class="checkbox" type="checkbox" id="llantasFierro" value="llantasFierro">Llantas Fierro</label></div>
                            <div class="radio"><label><input class="checkbox" type="checkbox" id="pisaderasLaterales" value="pisaderasLaterales">Pisaderas Laterales</label></div>
                            <div class="radio"><label><input class="checkbox" type="checkbox" id="medioUso" value="medioUso">Medio Uso</label></div>
                        </div>
                        <div class="col-md-4">
                            <div class="radio"><label><input class="checkbox" type="checkbox" id="parabrisasAlternativo" value="parabrisasAlternativo">Parabrisas Alternativo</label></div>
                            <div class="radio"><label><input class="checkbox" type="checkbox" id="barraAntivuelco" value="barraAntivuelco">Barra Antivuelco</label></div>
                            <div class="radio"><label><input class="checkbox" type="checkbox" id="gastados" value="gastados">Gastados</label></div>
                        </div>
                    </div>
                    <div class="space-50"></div>
                    <div class="col-md-12">
                        <div class=" col-xs-6 col-sm-6 col-md-6"><button class="btn btn-block btn-primary" data-target="#hk-container" data-slide-to="2">Anterior</button></div>
                        <div class=" col-xs-6 col-sm-6 col-md-6"><button class="btn btn-block btn-success">Finalizar</button></div>
                    </div>
                    <div class="space-50"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="damageModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Daños Auto</h4>
                </div>
                <div class="modal-body">
                    <div id="carDamages">
                        <div id="photoDamage">
                            <label for="recipient-name" class="control-label">Foto Daño:</label>
                            <input name="foto1" type="file" class="file-loading foto" accept="image/*">
                            <div class="space-40"></div>  
                        </div>
                        <div id="descriptionDiv">  
                            <label for="recipient-name" class="control-label">Descripcion:</label>
                            <textarea type="text" class="form-control" id="description" placeholder="Escriba Descripicion aquí.." rows="5"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button id="deleteButton" type="button" class="btn btn-primary" onclick="deleteDamage()">Eliminar</button>
                    <button id="saveButton" type="button" class="btn btn-primary" onclick="saveDamage()">Editar</button>
                </div>
            </div>
        </div>
    </div>

    <!--input de datos -->
    <div style="display:none">
            <input type="hidden" value="" id="lat">
            <input type="hidden" value="" id="lng">
            <input type="hidden" value="" id="coordX">
            <input type="hidden" value="" id="coordY">
            <input type="hidden" value="" id="marker">
            <input type="hidden" value="" id="map">

        <div id="dialog-alert" title="">
            <p></p>
        </div>
    </div>
</div>
 
<script>
   
    $(document).ready(function() { 

        $("#buttonTwo").hide();
        $("#audio").hide();
        $("#formulario").hide();

        $(".photo").fileinput({
            allowedFileExtensions: ["jpg", "gif", "png", "bmp","jpeg"],
            uploadUrl: '<?php echo url_for("upload_photo")?>',
            dropZoneEnabled: false,
            showRemove: false,  
            elErrorContainer: false,
            showPreview: false,
            uploadExtraData:function() { return {carId: $("#carId").val()}; },
            /*showCaption: false,*/
        });

        $('.carousel').carousel({
            interval: 100000
        });
    });

    function findCar() {

        var carId = $("#carId").val();

        var parameters = {
            "carId" : carId
        };

        $.post("<?php echo url_for('car_find_car') ?>", parameters, function(r){
            if (r.error) {
                console.log(r.errorMessage);
            } else {
                $("#formulario").show();
                $("#buscar").hide();
            }
        }, 'json')
    }

    //Paso 2 descripción de daños
    function initializeCarImage() {

        $("#buttonOne").hide();
        $("#buttonTwo").show();


        var myLatlng = new google.maps.LatLng(-89.73797816536283, 1.0546875);

        var mapOptions = {
            center: myLatlng,
            zoom: 10,
            mapTypeControlOptions: {
                mapTypeIds: ['car']
            },
            zoomControl: false,
            streetViewControl: false,
            backgroundColor: 'white',
            disableDefaultUI: true,
            draggable:false,        
        };
        
        var imageMapOptions = {
            getTileUrl: function(coord, zoom) {
                return 'http://arriendas.assets.s3-website-sa-east-1.amazonaws.com/images/auto1024px.png';
            },
            tileSize: new google.maps.Size(1024, 1000),
            maxZoom: 2,
            minZoom: 2,
            name: 'Vehículo'
        };

        var map = new google.maps.Map(document.getElementById('map_car'), mapOptions);
        var imageMapType = new google.maps.ImageMapType(imageMapOptions);

        map.mapTypes.set('car', imageMapType);
        map.setMapTypeId('car');

        $("#map").data("map", map);

        google.maps.event.addListener(map, 'click', function(event) {
            placeMarker(event.latLng, map);
        });

        findDamage(map);
    };

    function placeMarker(location, map, zIndex) {

        if (!zIndex) {
            zIndex = 0;
        }
        var marker = new google.maps.Marker({
            position: location, 
            map: map,
            zIndex: zIndex
        });

        google.maps.event.addListener(marker, 'click', function() {
            $("#marker").data("marker", marker);
            findDamage();
            modalDamageShow();
        });
        
        $("#marker").data("marker", marker);
        $("#lat").val(location.lat());
        $("#lng").val(location.lng());

        if (marker.getZIndex() == 0) {
            modalDamageShow(1);
        } else {
            marker.setIcon('http://maps.google.com/mapfiles/marker_grey.png');
        }  
    }

    $('#map_car').click(function(ev) {

        if (ev.offsetX == undefined) { // para firefox
            x = ev.pageX - $(this).offset().left;
            y = ev.pageY - $(this).offset().top;
        }   
        else { // chrome
            x = ev.offsetX;
            y = ev.offsetY;
        }

        width = $(this).width();
        height = $(this).height();
        
        coordX = Math.round((x*100)/width);
        coordY = Math.round((y*100)/height);

        $("#coordX").val(coordX);
        $("#coordY").val(coordY);
    });
    
    function modalDamageShow(option) {
        
        if (option == 1) {
            $("#deleteButton").hide();
            $("#saveButton").show();
            $("#saveButton").html("Guardar");
            $("#descriptionDiv").show();
            $("#photoDamage").hide();
        } else if (option == 2){
            $("#saveButton").hide();
            $("#photoDamage").show();
            $("#descriptionDiv").hide();
        } else { 
            $("#deleteButton").show();
            $("#saveButton").show();
            $("#saveButton").html("Editar");
            $("#descriptionDiv").show();

        }
        $('#damageModal').modal('show');
    }

    $('#damageModal').on('hidden.bs.modal', function (e) {
        
        var marker = $("#marker").data("marker");

        if (marker.getZIndex() == 0) {
            marker.setMap(null);
        } else {
            marker.setIcon('http://maps.google.com/mapfiles/marker_grey.png');
        }

        $("#description").val(" ");
    });

    function saveDamage() {

        var marker = $("#marker").data("marker");

        if (marker.getZIndex() != 0) {
            var damageId    = marker.getZIndex();
        }

        var carId           = $("#carId").val();
        var lat             = $("#lat").val();
        var lng             = $("#lng").val();
        var coordX          = $("#coordX").val();
        var coordY          = $("#coordY").val();
        var description     = $("#description").val();

        var parameters = {
            "carId"         : carId,
            "lat"           : lat,
            "lng"           : lng,
            "coordX"        : coordX,
            "coordY"        : coordY,   
            "description"   : description,
            "damageId"      : damageId
        };

        $.post("<?php echo url_for('car_save_damage') ?>", parameters, function(r){
            if (r.error) {
                console.log(r.errorMessage);
            } else {
                var marker = $("#marker").data("marker");
                marker.setZIndex(parseInt(r.data));
                if(r.opcion) {
                    modalDamageShow(2);
                } else {
                    $('#damageModal').modal('hide');
                }
            }
        }, 'json')
    }

    function deleteDamage() {

        var marker = $("#marker").data("marker");
        var damageId = marker.getZIndex();

        var parameters = {
            "damageId" : damageId
        };

        $.post("<?php echo url_for('car_delete_damage') ?>", parameters, function(r){
            if (r.error) {
                console.log(r.errorMessage);
            } else {
                var marker = $("#marker").data("marker");
                marker.setZIndex(0);
                $('#damageModal').modal('hide');
            }
        }, 'json')
    }

    function findDamage(map) {

        var marker          = $("#marker").data("marker");
        var carId       = $("#carId").val();

        if (marker) {
            var damageId    = marker.getZIndex();
        }  

        var parameters = {
            "damageId" : damageId,
            "carId"    : carId
        };

        $.post("<?php echo url_for('car_find_damage') ?>", parameters, function(r){
            if (r.error) {
                console.log(r.errorMessage);
            } else {
                if (r.opcion) {
                    $("#description").val(r.description);
                    console.log("aca");
                } else {
                    $.each(r.data, function(k, v){
                        var myLatlng = new google.maps.LatLng(v.damage_lat, v.damage_lng);
                        placeMarker(myLatlng, map,parseInt(v.damage_id));
                    });
                }
            }
        }, 'json')
    }

    //paso 3

    $(".soundOptionYes").click(function(){
        $("#audio").show();
        $("#soundOption").hide(); 
    });

    function saveAudioAccessories() {

        var carId               = $("#carId").val();
        var radioMarca          = $("#radioMarca").val();
        var radioModelo         = $("#radioModelo").val();
        var radioTipo           = $("#radioTipo option:selected").val();
        var parlantesMarca      = $("#parlantesMarca").val();
        var parlantesModelo     = $("#parlantesModelo").val();
        var tweetersMarca       = $("#tweetersMarca").val();
        var tweetersModelo      = $("#tweetersModelo").val();
        var ecualizadorMarca    = $("#ecualizadorMarca").val();
        var ecualizadorModelo   = $("#ecualizadorModelo").val();
        var amplificadorMarca   = $("#amplificadorMarca").val();
        var amplificadorModelo  = $("#amplificadorModelo").val();
        var compactCdMarca      = $("#compactCdMarca").val();
        var compactCdModelo     = $("#compactCdModelo").val();
        var subwooferMarca      = $("#subwooferMarca").val();
        var subwooferModelo     = $("#subwooferModelo").val();
        var sistemaDvdMarca     = $("#sistemaDvdMarca").val();
        var sistemaDvdModelo    = $("#sistemaDvdModelo").val();
        var otrosAccesorios     = $("#otrosAccesorios").val();

        var parameters = {
            "carId"              :carId,                           
            "radioMarca"         :radioMarca,        
            "radioModelo"        :radioModelo,       
            "radioTipo"          :radioTipo,         
            "parlantesMarca"     :parlantesMarca,    
            "parlantesModelo"    :parlantesModelo,   
            "tweetersMarca"      :tweetersMarca,     
            "tweetersModelo"     :tweetersModelo,    
            "ecualizadorMarca"   :ecualizadorMarca,  
            "ecualizadorModelo"  :ecualizadorModelo, 
            "amplificadorMarca"  :amplificadorMarca, 
            "amplificadorModelo" :amplificadorModelo,
            "compactCdMarca"     :compactCdMarca,    
            "compactCdModelo"    :compactCdModelo,   
            "subwooferMarca"     :subwooferMarca,    
            "subwooferModelo"    :subwooferModelo,   
            "sistemaDvdMarca"    :sistemaDvdMarca,   
            "sistemaDvdModelo"   :sistemaDvdModelo,  
            "otrosAccesorios"    :otrosAccesorios
        };

        $.post("<?php echo url_for('car_save_audio_accessories') ?>", parameters, function(r){
            if (r.error) {
                console.log(r.errorMessage);
            } else {

                    console.log("aca");

            }
        }, 'json')
    }

    $('.checkbox').change(function() {

        var carId          = $("#carId").val();
        var accessory      = $(this).val();

        var parameters = {
            "carId"        :carId,                           
            "accessory"    :accessory
        };

        $.post("<?php echo url_for('car_save_accessories') ?>", parameters, function(r){
            if (r.error) {
                console.log(r.errorMessage);
            } else {
                console.log("aca");
            }
        }, 'json')
    });

    $('.photo').on('filebatchuploadsuccess', function(event, data, previewId, index) {
        console.log(data.response.urlPhoto);
        $("#imagePhoto").attr("src","../images/test/"+data.response.urlPhoto)

    });
</script>