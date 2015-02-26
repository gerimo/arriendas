<link href="/css/newDesign/createMobile.css" rel="stylesheet" type="text/css">
<!-- Google Maps -->
<script type="text/javascript" src="https://maps.google.com/maps/api/js?sensor=false"></script>



<div class="space-100"></div>

<div class="container">
    <div class="col-xs-12 col-sm-offset-2 col-sm-8 col-md-offset-2 col-md-8 BCW">
        <div class="row">
            <form id="createCar">

                <fieldset id="paso1">
                    <h1>Datos del vehículo</h1>
                    <div class="espacio col-xs-12 col-sm-12 col-md-12">
                    </div>

                    <div class="espacio col-sm-12 col-md-12">
                        <!-- comuna -->
                    <div class="col-sm-4 col-md-4">   
                        <label>Comuna (*)</label>
                        <select class="form-control" id="commune" name="commune" placeholder="" type="text">
                                <option value="">--</option>
                            <?php
                                foreach ($Communes as $commun):
                            ?>
                                <option value="<?php echo $commun['id']?>"><?php echo $commun['name']?></option>
                            <?php
                                endforeach;
                            ?>
                        </select>
                    </div>
                    
                    <div class="visible-xs space-20"></div>

                    <!-- direccion -->
                    <div class="col-sm-8 col-md-8">
                        <label>Ubicación del vehículo  (*)</label>
                        <input class="form-control" id="address" name="address" placeholder="Dirección #111" type="text"> 
                    </div>
                        <!-- marca auto -->
                        <div class="col-sm-4 col-md-4">
                            <label>Marca (*)</label><br>
                            <select class="form-control" id="brand" name="brand" placeholder="" type="text">
                                    <option value="">--</option>
                                <?php
                                    foreach ($Brands as $brand):
                                ?>
                                    <option value="<?php echo $brand['id']?>"><?php echo $brand['name']?></option>
                                <?php
                                    endforeach;
                                ?>
                            </select>
                        </div>

                        <div class="visible-xs space-20"></div>

                        <!-- modelo auto -->
                        <div class="col-sm-4 col-md-4">
                            <label>Modelo (*)</label><br>
                            <select class="form-control" id="model" name="model" placeholder="" type="text" disabled>
                                    <option value="0">Selecciona el modelo</option>
                            </select>
                        </div>

                        <div class="visible-xs space-20"></div>


                        <!--Año auto-->
                        <div class="col-sm-4 col-md-4    ">
                            <label>Año (*)</label>
                            <select class="form-control" id="ano" name="ano" placeholder="" type="text">
                                <option value="">--</option>
                            <?php
                                for($i=2015; $i>=1996; $i--):
                            ?>
                                    <option value="<?php echo $i ?>"><?php echo $i ?></option>";
                            <?php
                                endfor;
                            ?>
                            </select>
                        </div>
                    </div>

                    <div class="espacio col-sm-12 col-md-12">
                        <!--N° puerta-->
                        <div class="col-sm-4 col-md-4">
                            <label>N° de puertas (*)</label>
                            <select class="form-control" id="door" name="door" placeholder="" type="text">
                                <option value="">--</option>
                                <option value="3">3</option>
                                <option value="5">5</option>
                            </select>
                        </div>

                        <div class="visible-xs space-20"></div>


                        <!--Transmisión-->
                        <div class="col-sm-4 col-md-4">
                            <label>Transmisión (*)</label>
                            <select class="form-control" id="transmission" name="transmission" placeholder="" type="text">
                                <option value="">--</option>
                                <option value="0">Manual</option>
                                <option value="1">Automática</option>
                            </select>
                        </div>

                        <div class="visible-xs space-20"></div>


                        <!--Tipo de bencina -->
                        <div class="col-sm-4 col-md-4">
                            <label>Tipo de bencina (*)</label>
                            <select class="form-control " id="benzine" name="benzine" placeholder="" type="text">
                                <option value="">--</option>
                                <option value="93">93</option>
                                <option value="95">95</option>
                                <option value="97">97</option>
                                <option value="Diesel">Diesel</option>
                                <option value="Híbrido">Híbrido</option>
                            </select>
                        </div>
                    </div>

                    <div class="espacio col-sm-12 col-md-12" >
                        <!-- Tipo de Vehículo -->
                        <div class="col-sm-4 col-md-4" id="cilindrada">
                            <label>Cilindrada (*)</label><br>
                            <select class="form-control col-xs-6 col-md-6" id="capacity1" name="" placeholder="" type="text">
                                <option value="1">1</option>
                                <?php
                                for($i=2; $i<7; $i++):
                                ?>
                                <option value="<?php echo $i ?>"><?php echo $i ?></option>";
                                <?php
                                endfor;
                                ?>
                            </select>
                            <select class="form-control col-xs-6 col-md-6" id="capacity2" name="" placeholder="" type="text">
                                <option value="0">0</option>
                                <?php
                                for($i=1; $i<10; $i++):
                                ?>
                                <option value="<?php echo $i ?>"><?php echo $i ?></option>";
                                <?php
                                endfor;
                                ?>
                            </select>
                        </div>

                        <div class="visible-xs space-20"></div>

                        <!--Patente-->
                        <div class="col-sm-4 col-md-4">
                            <label>Patente (*)</label>
                            <input class="form-control" id="patent" name="patent" placeholder="Patente" type="text" onblur="validatePatent()">
                        </div>

                        <div class="visible-xs space-20"></div>

                        <!--Color-->
                        <div class="col-sm-4 col-md-4">
                            <label>Color (*)</label>
                            <input class="form-control" id="color" name="color" placeholder="color" type="text">
                        </div>

                        <div class="espacio col-md-11"></div>
                        <!-- Modal -->
                        <div class="modal fade" id="myModal">
                          <div class="modal-dialog">
                            <div class="modal-content">
                              <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                <h4 class="modal-title" id="myModalLabel">¿Es esta su dirección?</h4>
                              </div>
                              <div id="mostrarMapa"> </div>
                              <div class="modal-footer">
                                <button id="noAddress"type="button" class="btn btn-default" data-dismiss="modal">No</button>
                                <button type="button" class="btn btn-primary" data-dismiss="modal">SI</button>
                              </div>
                            </div>
                          </div>
                        </div>  
                    </div>
                    <div class="espacio col-sm-12 col-md-12" > 
                        
                    

                        <div class="visible-xs space-50"></div>

                        <div class="col-sm-12 col-md-12" id="accessories">
                            <h1>Accesorios del vehículo</h1>
                            <div class="checkbox col-md-4">
                                <label>
                                    <input type="checkbox" id="sistemaABS" value="sistemaABS">
                                    ABS
                                </label>
                            </div>
                            <div class="checkbox col-md-4">
                                <label>
                                    <input type="checkbox" id="aireAcondicionado" value="aireAcondicionado">
                                    Aire acondicionado
                                </label>
                            </div>
                            <div class="checkbox col-md-4">
                                <label>
                                    <input type="checkbox" id="airBag" value="airBag">
                                    Air Bag
                                </label>
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-12" id="accessories">
                            <div class="checkbox col-md-4">
                                <label>
                                    <input type="checkbox" id="controlCrucero" value="controlCrucero">
                                    Control crucero
                                </label>
                            </div>
                            <div class="checkbox col-md-4">
                                <label>
                                    <input type="checkbox" id="sensor" value="sensor">
                                    Sensor acercamiento/retroseso
                                </label>
                            </div>
                            <div class="checkbox col-md-4">
                                <label>
                                    <input type="checkbox" id="babyChair" value="babyChair">
                                    Silla Bebé
                                </label>
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-12" id="accessories">
                            <div class="checkbox col-md-4">
                                <label>
                                    <input type="checkbox" id="vidriosElectricos" value="vidriosElectricos">
                                    Vidrios eléctricos
                                </label>
                            </div>
                        </div>

                        <div class="espacio col-md-11"></div>

                        <!-- Modal -->
                        <div class="modal fade" id="myModal">
                          <div class="modal-dialog">
                            <div class="modal-content">
                              <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                <h4 class="modal-title" id="myModalLabel">¿Es esta su dirección?</h4>
                              </div>
                              <div id="mostrarMapa"> </div>
                              <div class="modal-footer">
                                <button id="noAddress"type="button" class="btn btn-default" data-dismiss="modal">No</button>
                                <button type="button" class="btn btn-primary" data-dismiss="modal">SI</button>
                              </div>
                            </div>
                          </div>
                        </div>  
                    </div>
                </fieldset>

                <input id="lat" name="lat" type="hidden" value="">
                <input id="lng" name="lng" type="hidden" value="">
            </form>
            <div class="col-md-offset-8 col-md-4">
                
                <button class="btn btn-a-primary btn-block" id="next" name="save" onclick="validateForm()">Siguiente</button>
                <img class="load" src="/images/ajax-loader.gif">    
                <p class="alert"></p> 
            </div>
            
            <div style="display:none">
                <div id="dialog-alert" title="">
                    <p></p>
                </div>
            </div>
        </div>
    </div>
</div>





<script>
    
    $(document).ready(function() {  

        $(".load").hide();  
    }); 

    $("#brand").change(function(){

        var brandId = $("#brand option:selected").val();

        if (brandId == 0) {
            return false;
        }

        $("#model").attr("disabled", true);

        $.post("<?php echo url_for('cars/getModels') ?>", {"brandId": brandId}, function(r){

            if (r.error) {
                console.log(r.errorMessage);
            } else {

                var html = "<option selected value=''>Selecciona el modelo</option>";
                
                $.each(r.models, function(k, v){
                    html += "<option value='"+v.id+"'>"+v.name+"</option>";
                });

                $("#model").html(html);
            }

            $("#model").removeAttr("disabled");

        }, 'json');
    });

    function validateForm() {

        var address        = $("#address").val();
        var commune        = $("#commune option:selected").val();
        var brand          = $("#brand option:selected").val();
        var model          = $("#model option:selected").val();
        var ano            = $("#ano option:selected").val();
        var door           = $("#door option:selected").val();
        var transmission   = $("#transmission option:selected").val();
        var benzine        = $("#benzine option:selected").val();
        var typeCar        = $("#typeCar option:selected").val();
        var patent         = $("#patent").val();
        var color          = $("#color").val();
        var lat            = $("#lat").val();
        var lng            = $("#lng").val();


        if ($("#sistemaABS").is(':checked')) {
            var sistemaABS = $("#sistemaABS").val();
        }

        if ($("#aireAcondicionado").is(':checked')) {
            var aireAcondicionado = $("#aireAcondicionado").val();
        }

        if ($("#airBag").is(':checked')) {
            var airBag = $("#airBag").val();
        }

        if ($("#controlCrucero").is(':checked')) {
            var controlCrucero = $("#controlCrucero").val();
        }

        if ($("#sensor").is(':checked')) {
            var sensor = $("#sensor").val();
        }

        if ($("#vidriosElectricos").is(':checked')) {
            var vidriosElectricos = $("#vidriosElectricos").val();
        }

        var babyChair = false;
        if ($("#babyChair").is(':checked')) {
            babyChair = true;
        }

        var capacity1 = $("#capacity1 option:selected").val();
        var capacity2 = $("#capacity2 option:selected").val();
        var capacity = capacity1+"."+capacity2;

        var parameters = {
            "address": address,
            "commune": commune,
            "brand": brand,
            "model": model,
            "ano": ano,
            "door": door,
            "transmission": transmission,
            "benzine": benzine,
            "patent": patent,
            "color": color,
            "lat": lat,
            "lng": lng,
            "sistemaABS": sistemaABS,
            "aireAcondicionado": aireAcondicionado,
            "airBag": airBag, 
            "controlCrucero": controlCrucero,   
            "sensor": sensor,
            "babyChair": babyChair,
            "vidriosElectricos": vidriosElectricos,
            "capacity":  capacity
        };

        $.post("<?php echo url_for('cars/getValidateCar') ?>", parameters, function(r){

            
            $(".alert").removeClass("alert-a-danger");
            $(".alert").removeClass("alert-a-success");
            
            $("#address").removeClass("alert-danger");
            $("#commune").removeClass("alert-danger");
            $("#brand").removeClass("alert-danger");
            $("#model").removeClass("alert-danger");
            $("#ano").removeClass("alert-danger");
            $("#door").removeClass("alert-danger");
            $("#transmission").removeClass("alert-danger");
            $("#benzine").removeClass("alert-danger");
            $("#typeCar").removeClass("alert-danger");
            $("#patent").removeClass("alert-danger");
            $("#benzine").removeClass("alert-danger");
            $(".load").show();
            $("#next").attr("disabled", true);


            if (r.error) {
                $(".alert").addClass("alert-a-danger");
                $(".alert").html(r.errorMessage);

                if(r.errorCode == 1){
                    $("#address").addClass("alert-danger");
                    $('#address').focus();
                }else if(r.errorCode == 2){
                    $("#commune").addClass("alert-danger");
                    $('#commune').focus(); 
                }else if(r.errorCode == 3){
                    $("#brand").addClass("alert-danger"); 
                    $('#brand').focus();
                }else if(r.errorCode == 4){
                    $("#model").addClass("alert-danger"); 
                    $('#model').focus();
                }else if(r.errorCode == 5){
                    $("#ano").addClass("alert-danger"); 
                    $('#ano').focus();
                }else if(r.errorCode == 6){
                    $("#door").addClass("alert-danger"); 
                    $('#door').focus();
                }else if(r.errorCode == 7){
                    $("#transmission").addClass("alert-danger"); 
                    $('#transmission').focus();
                }else if(r.errorCode == 8){
                    $("#benzine").addClass("alert-danger"); 
                    $('#benzine').focus();
                }else if(r.errorCode == 9){
                    $("#typeCar").addClass("alert-danger"); 
                    $('#typeCar').focus();
                }else if(r.errorCode == 11){
                    $("#color").addClass("alert-danger"); 
                    $('#color').focus();
                }

            } else {
                window.location.href = r.url_complete;
            }

        }, 'json');
    } 

    function validatePatent(){


        var x = $("#patent").val();
        $("#patent").val(x.toUpperCase());
        var patente = $("#patent").val();

        $.post("<?php echo url_for('cars/getValidatePatent') ?>", {"patente": patente}, function(r){

            if (r.errorCode == 2) {

                $("#dialog-alert p").html("Ya ha subido este auto.</br> Recién podrá ver "+ 
                    "publicado su auto una vez que lo haya visitado"+
                    " un inspector. Un inspector te llamará esta"+
                    " semana.");
                $("#dialog-alert").attr('title','Patente ya existe!');
                $("#dialog-alert").dialog({
                    buttons: [{
                    text: "Aceptar",
                    click: function() {
                        $( this ).dialog( "close" );
                    }
                    }]
                });

                $("#patent").val("");

            }
            else if(r.errorCode == 1){
                    $(".alert").html(r.errorMessage);
                    $("#patent").addClass("alert-danger");
                    $('#patent').val('');

            }

        }, 'json');

        $(".alert").html("");
        $("#patent").removeClass("alert-danger");
    }

    $('#noAddress').blur(function(){
        $('#address').val("");
        $('#commune').val("");
        $('#lat').val("");
        $('#log').val("");
    });
  
  
    $('#address').blur(function(){
        if(($("#address").val() != null) && ($("#address").val() != "") && ($("#commune option:selected").val() != "") && ($("#commune option:selected").val() != "")){
            modalShow();
        }
    });

    $('#commune').blur(function(){
        if(($("#address").val() != null) && ($("#address").val() != "") && ($("#commune option:selected").val() != "") && ($("#commune option:selected").val() != "")){
            modalShow();
        }
    });

    function modalShow(){
        $("#myModal").modal('show');
        $('#myModal').on('shown.bs.modal', function() {

            var direccion = $("#commune option:selected").text()+" "+$("#address").val();
            initialize(direccion);
        
        });
    }

    function initialize(address) {

        var geoCoder = new google.maps.Geocoder(address)
        var request = {address:address};
        geoCoder.geocode(request, function(result, status){

            var latlng = new google.maps.LatLng(result[0].geometry.location.lat(), result[0].geometry.location.lng());       
            var myOptions = {
                zoom: 17,
                center: latlng,
                mapTypeId: google.maps.MapTypeId.ROADMAP

            };

            $("#lat").val(result[0].geometry.location.lat());
            $("#lng").val(result[0].geometry.location.lng());
    
    
            var map = new google.maps.Map(document.getElementById("mostrarMapa"),myOptions);
     
            var marker = new google.maps.Marker({position:latlng,map:map,title:'title'});

 
        })
    }




    

       

    


</script>