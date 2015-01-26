<link href="/css/newDesign/create.css" rel="stylesheet" type="text/css">
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
                    <!-- comuna -->
                    <div class="col-sm-4 col-md-4">   
                        <label>Comuna (*)</label>
                        <select class="form-control" id="commune" name="commune" placeholder="" type="text">
                                <option selected value="<?php if($Car->commune_id) echo $Car->commune_id ?>"><?php if($Car->getCommune()): echo $Car->getCommune(); else: echo "---"; endif; ?></option>
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
                        <input class="form-control" id="address" name="address" value="<?php if($Car->address) echo $Car->address ?>" placeholder="Dirección #111" type="text"> 
                    </div>


                    </div>

                    <div class="linea espacio hidden-xs col-md-11"></div>

                    <div class="espacio col-sm-12 col-md-12">
                        <!-- marca auto -->
                        <div class="col-sm-4 col-md-4">
                            <label>Marca (*)</label><br>
                            <select class="form-control" id="brand" name="brand" placeholder="" type="text">
                                <option value="<?php if($Car->getModel()->getBrandId()) echo $Car->getModel()->getBrandId()?>"><?php if($Car->getModel()->getBrand()): echo $Car->getModel()->getBrand(); else: echo "---"; endif; ?></option>
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
                            <select class="form-control" id="model" name="model" placeholder="" type="text">
                                <option value="<?php if($Car->getModelId()) echo $Car->getModelId() ?>"><?php if($Car->getModel()): echo $Car->getModel(); else: echo "---"; endif; ?></option>
                            </select>
                        </div>

                        <div class="visible-xs space-20"></div>


                        <!--Año auto-->
                        <div class="col-sm-4 col-md-4">
                            <label>Año (*)</label>
                            <select class="form-control" id="ano" name="ano" placeholder="" type="text">
                                <option value="<?php if($Car->year) echo $Car->year ?>"><?php if($Car->year): echo $Car->year; else: echo "---"; endif; ?></option>
                            <?php
                                for($i=2014; $i>=1996; $i--):
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
                                <option value="<?php if($Car->doors) echo $Car->doors ?>"><?php if($Car->doors): echo $Car->doors; else: echo "---"; endif; ?></option>
                                <option value="3">3</option>
                                <option value="5">5</option>
                            </select>
                        </div>

                        <div class="visible-xs space-20"></div>


                        <!--Transmisión-->
                        <div class="col-sm-4 col-md-4">
                            <label>Transmisión (*)</label>
                            <select class="form-control" id="transmission" name="transmission" placeholder="" type="text">
                                <option value="<?php if($Car->transmission == 0){ echo 0;} elseif($Car->transmission == 1){ echo 1;} ?>"><?php if($Car->transmission == 0): echo "Manual"; elseif($Car->transmission == 1): echo "Automática"; else: echo "---"; endif; ?></option>
                                <option value="0">Manual</option>
                                <option value="1">Automática</option>
                            </select>
                        </div>

                        <div class="visible-xs space-20"></div>


                        <!--Tipo de bencina -->
                        <div class="col-sm-4 col-md-4">
                            <label>Tipo de bencina (*)</label>
                            <select class="form-control " id="benzine" name="benzine" placeholder="" type="text">
                                <option value="<?php if($Car->tipobencina) echo $Car->tipobencina ?>"><?php if($Car->tipobencina): echo $Car->tipobencina; else: echo "---"; endif; ?></option>
                                <option value="93">93</option>
                                <option value="95">95</option>
                                <option value="97">97</option>
                                <option value="Diesel">Diesel</option>
                                <option value="Híbrido">Híbrido</option>
                            </select>
                        </div>
                    </div>

                    <div class="espacio col-sm-12 col-md-12">
                        <!-- Tipo de Vehículo -->
                        <div class="col-sm-4 col-md-4">
                            <label>Tipo de Vehículo (*)</label><br>
                            <select class="form-control" id="typeCar" name="typeCar" placeholder="" type="text">
                                    <option value="<?php if($Car->getModel()->getCarType()->getId()) echo $Car->getModel()->getCarType()->getId() ?>"><?php if($Car->getModel()->getCarType()->getName()): echo $Car->getModel()->getCarType()->getName(); else: echo "---"; endif; ?></option>
                                <?php
                                    foreach ($CarTypes as $CarType):
                                ?>
                                    <option value="<?php echo $CarType['id']?>"><?php echo $CarType['name']?></option>
                                <?php
                                    endforeach;
                                ?>
                            </select>
                        </div>

                        <div class="visible-xs space-20"></div>

                        <!--Patente-->
                        <div class="col-sm-4 col-md-4">
                            <label>Patente (*)</label>
                            <input class="form-control" id="patent" name="patent" value="<?php if($Car->patente) echo $Car->patente ?>" placeholder="Patente" type="text" onblur="validatePatent()">
                        </div>

                        <div class="visible-xs space-20"></div>

                        <!--Color-->
                        <div class="col-sm-4 col-md-4">
                            <label>Color (*)</label>
                            <input class="form-control" id="color" value="<?php if($Car->color) echo $Car->color ?>" name="color" placeholder="color" type="text">
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

                <input id="lat" name="lat" type="hidden" value="<?php if($Car->lat) echo $Car->lat ?>">
                <input id="lng" name="lng" type="hidden" value="<?php if($Car->lng) echo $Car->lng ?>">
                <input id="carId" name="carId" type="hidden" value="<?php if($Car->id) echo $Car->id ?>">

            </form>
            <div class="col-md-offset-8 col-md-4">
                <button class="btn-a-primary btn-block" name="save" onclick="validateFormCreate()">Guardar</button>
                <p class="alert"></p> 
            </div>

            <div class="hidden-xs space-100"></div> 

            <form id="priceCar">
                <fieldset id="paso2">
                    <h1>Precios del vehículo</h1>

                    <!--precios obligatorios-->
                    <div class="hidden-xs space-30"></div>    
                    <div class="col-md-12">
                        <div class="col-sm-6 col-md-6"> 

                            <label>Precio por hora (*)</label>
                            <input class="form-control" id="priceHour" name="priceHour" value="<?php if($Car->price_per_hour) echo round($Car->price_per_hour) ?>" placeholder="Precio referencia $<?php if($hour<4000): echo "4.000"; else: echo number_format(round($hour), 0, '', '.'); endif;    ?>" type="text">
                        </div>
                        <div class="col-sm-6 col-md-6">
                            <label>Precio por día (*)</label>
                            <input class="form-control" id="priceDay" name="priceDay" placeholder="Precio referencia $<?php echo number_format(round($day), 0, '', '.') ?>"  value="<?php if($Car->price_per_day) echo round($Car->price_per_day) ?>" type="text">
                        </div>
                        </div>
                    </div>
                    <div class="space-30"></div> 

                    <!--precios Opcionales-->
                    <div class="col-md-12">
                        <div class="col-sm-6 col-md-6">
                            <label>Precio por semana (Opcional)</label>
                            <input class="form-control" id="priceWeek" name="priceWeek" placeholder="Precio referencia $<?php echo number_format(round($week), 0, '', '.') ?>" value="<?php if($Car->price_per_week) echo round($Car->price_per_week) ?>"type="text">
                        </div>
                        <div class="col-sm-6 col-md-6">
                            <label>Precio por mes (Opcional)</label>
                            <input class="form-control" id="priceMonth" name="priceMonth" placeholder="Precio referencia $<?php echo number_format(round($month), 0, '', '.') ?>" value="<?php if($Car->price_per_month) echo round($Car->price_per_month) ?>" type="text">
                        </div>
                    </div>

                    <div class="hidden-xs space-100"></div>
                    <div class="visible-xs space-50"></div>
                </fieldset>
            </form>
            <div class="col-md-offset-8 col-md-4">
                <button class="btn-a-primary btn-block" id="save" onclick="validateFormPrice()">Guardar</button>
                <p class="alertPrice"></p> 
            </div>

            <div class="hidden-xs space-100"></div> 

            <!-- <form id="availabilityCar">

                <fieldset id="paso3">
                    <h1>Disponibilidad del vehículo </h1>
                    <div class="hidden-xs space-0"></div>
                    <div class="visible-xs space-10"></div>
                    <div class = "col-md-12 text-center">
                        <p style="font-weight: 300; font-size:20px;">¿Qué días de la semana puedes recibir clientes?</p>
                    </div>

                    <div class="hidden-xs space-50"></div>
                    <div class="visible-xs space-50"></div>  

                    <div id="availability-container">
                        <div class="col-md-10">
                        </div>
                        <div class="radio">
                            <label>
                                <input class="check" name="option1" type="checkbox" value="1" <?php if($Car->getOptions() & 1){?>checked <?php } ?>>

                            
                                Puedo recibir clientes en la semana.
                            </label>
                        </div>
                        <div class="radio">
                            <label>
                                <input class="check" name="option2" type="checkbox" value="2" <?php if($Car->getOptions() & 2){?>checked <?php } ?>>
                                 Puedo recibir clientes los fines de semana.   
                            </label>
                        </div> 
                        <div class="col-md-12">
                            <p>Si tienes disponibilidad todos los días, selecciona fines de semana y semana.</p>
                        </div>

                   </div>
                    <div class="hidden-xs space-50"></div>
                    <div class="visible-xs space-50"></div>
                    
                    <div class="hidden-xs space-10"></div>
                    <div class="visible-xs space-50"></div>

                    <div class="col-md-12 text-center">
                        <p style="font-weight: 300; font-size:20px;">Otros filtros de busqueda</p>
                    </div>
                    <div class="hidden-xs space-50"></div>
                    <div class="visible-xs space-50"></div>  
                    <div class="radio">
                            <label>
                                <input class="check" name="option3" type="checkbox" value="4" <?php if($Car->getOptions() & 4){?>checked <?php } ?>>
                                Puedo recibir clientes que pagan el mismo día.
                            </label>
                    </div>
                    <div class="radio">
                        <label>
                            <input class="check" name="option4" type="checkbox" value="8" <?php if($Car->getOptions() & 8){?>checked <?php } ?>>
                            Solo arriendos mayores a 24 horas.
                        </label>
                    </div>

                    <div class="hidden-xs space-50"></div>
                    <div class="visible-xs space-50"></div>

                    <div class="col-md-offset-1 col-md-10 text-center">
                        <p style="color:#00aced; font-size: 25px; font-weight: 300;">Aparicion en busqueda: <span id="percent">0</span>% de reservas<span id="more"><span></p>
                    </div>
                    <div class="hidden-xs space-100"></div>
                    <div class="visible-xs space-50"></div>
                </fieldset>
            </form>
            <div class="col-md-offset-8 col-md-4">
                <button class="btn-a-primary btn-block" id="save" onclick="validateFormAvailability()">Guardar</button>
                <p class="alertAva"></p> 
            </div> -->

            <div class="hidden-xs space-100"></div> 

            <form id="photoCar">
                <fieldset id="paso4">
                        <h1>Foto Del Vehículo</h1>
                        <div class="space-50"></div> 
                            <!-- foto perfil -->
                            <div id="previewPhotoCar" class="photo text-center col-xs-12 col-sm-6 col-md-6" style="margin-bottom:10%">
                            <label class="col-md-12">Foto Perfil</label>
                                <?php if ($Car->getFotoPerfil() == null): ?>
                                    <?php echo image_tag('img_asegura_tu_auto/AutoVistaAerea.png') ?>
                                <?php else: ?>
                                    <?php echo image_tag($Car->getFotoPerfil()) ?>
                                <?php endif ?>
                                <a id="linkPhotoCar" href=""><i class="fa fa-edit"></i> subir</a>
                            </div>

                            <!-- foto Seguro accesorios -->
                            <div id="previewPhotoCarAccessory" class="photo text-center col-xs-12 col-sm-6 col-md-6" style="margin-bottom:10%">
                                <label class="col-md-12">Foto Accesorios</label>
                                    <?php if ($Car->getAccesoriosSeguro() == null): ?>
                                        <?php echo image_tag('img_asegura_tu_auto/accesorio2.png') ?>
                                    <?php else: ?>
                                        <?php echo image_tag($Car->getAccesoriosSeguro()) ?>
                                    <?php endif ?>
                                <a id="linkPhotoCarAccessory" href=""><i class="fa fa-edit"></i> subir</a>
                            </div>

                            <!-- foto Seguro frente -->
                            <div id="previewPhotoCarFront" class="photo text-center col-xs-12 col-sm-6 col-md-6" style="margin-bottom:10%">
                            <label class="col-md-12">Foto Frente</label>
                                <?php if ($Car->getSeguroFotoFrente() == null): ?>
                                    <?php echo image_tag('img_asegura_tu_auto/AutoFrente.png') ?>
                                <?php else: ?>
                                    <?php echo image_tag($Car->getSeguroFotoFrente()) ?>
                                <?php endif ?>
                            <a id="linkPhotoCarFront" href=""><i class="fa fa-edit"></i> subir</a>
                            </div>

                        <div class="space-30"></div> 
                        
                            <!-- foto Seguro Costado Derecho -->
                            <div id="previewPhotoCarSideRight" class="photo text-center col-xs-12 col-sm-6 col-md-6" style="margin-bottom:10%">
                            <label class="col-md-12">Foto Costado Derecho</label>
                                <?php if ($Car->getSeguroFotoCostadoDerecho() == null): ?>
                                    <?php echo image_tag('img_asegura_tu_auto/AutoCostadoDerch.png') ?>
                                <?php else: ?>
                                    <?php echo image_tag($Car->getSeguroFotoCostadoDerecho()) ?>
                                <?php endif ?>
                            <a id="linkPhotoCarSideRight" href=""><i class="fa fa-edit"></i> subir</a>
                            </div>

                            <!-- foto Seguro Costado Izquierdo -->
                            <div id="previewPhotoCarSideLeft" class="photo text-center col-xs-12 col-sm-6 col-md-6" style="margin-bottom:10%">
                            <label class="col-md-12">Foto Costado Izquierdo</label>
                                <?php if ($Car->getSeguroFotoCostadoIzquierdo() == null): ?>
                                    <?php echo image_tag('img_asegura_tu_auto/AutoCostadoIzq.png') ?>
                                <?php else: ?>
                                    <?php echo image_tag($Car->getSeguroFotoCostadoIzquierdo()) ?>
                                <?php endif ?>
                            <a id="linkPhotoCarSideLeft" href=""><i class="fa fa-edit"></i> subir</a>
                            </div>

                            <!-- foto Seguro Trasero Derecho -->
                            <div id="previewPhotoCarBackRight" class="photo text-center col-xs-12 col-sm-6 col-md-6" style="margin-bottom:10%">
                            <label class="col-md-12">Foto Trasero Derecho</label>
                                <?php if ($Car->getSeguroFotoTraseroDerecho() == null): ?>
                                    <?php echo image_tag('img_asegura_tu_auto/AutoDerechTra.png') ?>
                                <?php else: ?>
                                    <?php echo image_tag($Car->getSeguroFotoTraseroDerecho()) ?>
                                <?php endif ?>
                            <a id="linkPhotoCarBackRight" href=""><i class="fa fa-edit"></i> subir</a>
                            </div>


                            <!-- foto Seguro Trasero Izquierdo -->
                            <div id="previewPhotoCarBackLeft" class="photo text-center col-xs-12 col-sm-6 col-md-6" style="margin-bottom:10%">
                            <label class="col-md-12">Foto Trasero Izquierdo</label>
                                <?php if ($Car->getSeguroFotoTraseroIzquierdo() == null): ?>
                                    <?php echo image_tag('img_asegura_tu_auto/AutoIzqFrent.png') ?>
                                <?php else: ?>
                                    <?php echo image_tag($Car->getSeguroFotoTraseroIzquierdo()) ?>
                                <?php endif ?>
                            <a id="linkPhotoCarBackLeft" href=""><i class="fa fa-edit"></i> subir</a>
                            </div>
                </fieldset>
                <div class="space-20"></div>
            </form><!-- 
            <div class="col-md-offset-8 col-md-4">
                <button class="btn-a-primary btn-block" name="save" onclick="location.href='<?php echo url_for('cars') ?>'">Guardar</button>
            </div> -->

           
            <div style="display:none">

            <form action="<?php echo url_for('cars/uploadPhoto?photo=cars&width=194&height=204&file=filePhotoCar') ?>" enctype="multipart/form-data" id="formPhotoCar" method="post">
                <input id="filePhotoCar" name="filePhotoCar" type="file">
                <input type="submit">
            </form>

            <form action="<?php echo url_for('cars/uploadPhotoAccessory?photo=cars&width=194&height=204&file=filePhotoCarAccessory') ?>" enctype="multipart/form-data" id="formPhotoCarAccessory" method="post">
                <input id="filePhotoCarAccessory" name="filePhotoCarAccessory" type="file">
                <input type="submit">
            </form>

            <form action="<?php echo url_for('cars/uploadPhotoFront?photo=cars&width=194&height=204&file=filePhotoCarFront') ?>" enctype="multipart/form-data" id="formPhotoCarFront" method="post">
                <input id="filePhotoCarFront" name="filePhotoCarFront" type="file">
                <input type="submit">
            </form>

            <form action="<?php echo url_for('cars/uploadPhotoSideRight?photo=cars&width=194&height=204&file=filePhotoCarSideRight') ?>" enctype="multipart/form-data" id="formPhotoCarSideRight" method="post">
                <input id="filePhotoCarSideRight" name="filePhotoCarSideRight" type="file">
                <input type="submit">
            </form>

            <form action="<?php echo url_for('cars/uploadPhotoSideLeft?photo=cars&width=194&height=204&file=filePhotoCarSideLeft') ?>" enctype="multipart/form-data" id="formPhotoCarSideLeft" method="post">
                <input id="filePhotoCarSideLeft" name="filePhotoCarSideLeft" type="file">
                <input type="submit">
            </form>

            <form action="<?php echo url_for('cars/uploadPhotoBackRight?photo=cars&width=194&height=204&file=filePhotoCarBackRight') ?>" enctype="multipart/form-data" id="formPhotoCarBackRight" method="post">
                <input id="filePhotoCarBackRight" name="filePhotoCarBackRight" type="file">
                <input type="submit">
            </form>

            <form action="<?php echo url_for('cars/uploadPhotoBackLeft?photo=cars&width=194&height=204&file=filePhotoCarBackLeft') ?>" enctype="multipart/form-data" id="formPhotoCarBackLeft" method="post">
                <input id="filePhotoCarBackLeft" name="filePhotoCarBackLeft" type="file">
                <input type="submit">
            </form>
                <div id="dialog-alert" title="">
                    <p></p>
                </div>

            </div>

        </div>
    </div>
</div>

<div class="space-100 hidden-xs"></div>



<script>

    $(document).ready(function() {

        imageUpload('#formPhotoCar', '#filePhotoCar', '#previewPhotoCar','#linkPhotoCar');
        imageUpload('#formPhotoCarAccessory', '#filePhotoCarAccessory', '#previewPhotoCarAccessory','#linkPhotoCarAccessory');
        imageUpload('#formPhotoCarFront', '#filePhotoCarFront', '#previewPhotoCarFront','#linkPhotoCarFront');
        imageUpload('#formPhotoCarSideRight', '#filePhotoCarSideRight', '#previewPhotoCarSideRight','#linkPhotoCarSideRight');
        imageUpload('#formPhotoCarSideLeft', '#filePhotoCarSideLeft', '#previewPhotoCarSideLeft','#linkPhotoCarSideLeft');
        imageUpload('#formPhotoCarBackRight', '#filePhotoCarBackRight', '#previewPhotoCarBackRight','#linkPhotoCarBackRight');
        imageUpload('#formPhotoCarBackLeft', '#filePhotoCarBackLeft', '#previewPhotoCarBackLeft','#linkPhotoCarBackLeft');
   
    });


    /*js create*/

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

                var html;
                
                $.each(r.models, function(k, v){
                    html += "<option value='"+v.id+"'>"+v.name+"</option>";
                });

                $("#model").html(html);
            }

            $("#model").removeAttr("disabled");

        }, 'json');
    });

    function validateFormCreate() {

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
        var carId          = $("#carId").val();

        $.post("<?php echo url_for('cars/getValidateCar') ?>", {"address": address, "commune": commune, "brand": brand, "model": model, "ano": ano, "door": door, "transmission": transmission, "benzine": benzine, "typeCar": typeCar, "patent": patent, "color": color, "lat": lat, "lng": lng, "carId": carId}, function(r){

            
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
                $(".alert").addClass("alert-a-danger");
                $(".alert").html("Datos del vehículo editado con exito");
            }

        }, 'json');
    } 

    function validatePatent(){


        var x        = $("#patent").val();
        var patente  = $("#patent").val();
        var carId    = $("#carId").val();
        $("#patent").val(x.toUpperCase());
        console.log(carId);


        $.post("<?php echo url_for('cars/getValidatePatent') ?>", {"patente": patente, "carId": carId}, function(r){

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

    /*js Price*/

    function validateFormPrice() {

        var priceHour        = $("#priceHour").val();
        var priceDay         = $("#priceDay").val();
        var priceWeek        = $("#priceWeek").val();
        var priceMonth       = $("#priceMonth").val();
        var carId            = $("#carId").val(); 


        $.post("<?php echo url_for('cars/getValidatePrice') ?>", {"priceHour": priceHour, "priceDay": priceDay, "priceWeek": priceWeek, "priceMonth": priceMonth, "carId": carId}, function(r){

            
            $(".alertPrice").removeClass("alert-a-danger");
            $(".alertPrice").removeClass("alert-a-success");

            $("#priceHour").removeClass("alert-danger");
            $("#priceDay").removeClass("alert-danger");


            if (r.error) {
                $(".alertPrice").addClass("alert-a-danger");
                $(".alertPrice").html(r.errorMessage);

                if(r.errorCode == 1){
                    $("#priceHour").addClass("alert-danger");
                }else if(r.errorCode == 2){
                    $("#priceDay").addClass("alert-danger"); 
                }else if(r.errorCode == 3){
                    $("#priceHour").addClass("alert-danger");
                    $('#priceHour').val('');
                    $('#priceHour').focus(); 
                }

            } else {

                $(".alertPrice").addClass("alert-a-danger");
                $(".alertPrice").html("Datos del vehículo editado con exito");
            }

        }, 'json');
    }

    


    /*js photo */
    
    function imageUpload (form, formfile, preview, link) {

        $(formfile).change(function(e) {
            
            $(preview).html('<?php echo image_tag('loader.gif') ?>');

            $(form).ajaxForm({
                dataType: "json",
                target: preview,
                success: function(r){
                    if (r.error) {
                        $("#dialog-alert p").html(r.errorMessage);
                        $("#dialog-alert").attr("title", "Problemas al subir la imagen");
                        $("#dialog-alert").dialog({
                            buttons: [{
                                text: "Aceptar",
                                click: function() {
                                    $( this ).dialog( "close" );
                                }
                            }]
                        });
                    } else {
                        location.reload();
                    }
                }
            }).submit();
        });
        
        $(link).click(function(e) {
            e.preventDefault();
            $(formfile).click();
        });
    }





    

       

    


</script>