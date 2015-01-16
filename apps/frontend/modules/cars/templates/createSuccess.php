<link href="/css/newDesign/create.css" rel="stylesheet" type="text/css">


<div class="space-100 hidden-xs"></div>

<div class="container">
    <div class="col-md-offset-2 col-md-8 BCW">
        <div class="row">
            <form id="createCar">

                <fieldset id="paso1">
                    <legend>Datos del vehículo</legend>
                    <div class="espacio col-md-12">
                        <!-- direccion -->
                        <div class="col-md-8">
                            <label>Ubicación del vehículo  (*)</label>
                            <input class="form-control" id="address" name="address" placeholder="Dirección #111" type="text" required>
                        </div>

                        <!-- comuna -->
                        <div class="col-md-4">   
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
                    </div>

                    <div class="linea espacio col-md-11"></div>

                    <div class="espacio col-md-12">
                        <!-- marca auto -->
                        <div class="col-md-4">
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

                        <!-- modelo auto -->
                        <div class="col-md-4">
                            <label>Modelo (*)</label><br>
                            <select class="form-control" id="model" name="model" placeholder="" type="text" disabled>
                                    <option value="0">Selecciona la marca</option>
                            </select>
                        </div>

                        <!--Año auto-->
                        <div class="col-md-4    ">
                            <label>Año (*)</label>
                            <select class="form-control" id="ano" name="ano" placeholder="" type="text">
                                <option value="">--</option>
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

                    <div class="espacio col-md-12">
                        <!--N° puerta-->
                        <div class="col-md-4">
                            <label>N° de puertas (*)</label>
                            <select class="form-control" id="door" name="door" placeholder="" type="text">
                                <option value="">--</option>
                                <option value="3">3</option>
                                <option value="5">5</option>
                            </select>
                        </div>

                        <!--Transmisión-->
                        <div class="col-md-4">
                            <label>Transmisión (*)</label>
                            <select class="form-control" id="transmission" name="transmission" placeholder="" type="text">
                                <option value="">--</option>
                                <option value="0">Manual</option>
                                <option value="1">Automática</option>
                            </select>
                        </div>

                        <!--Tipo de bencina -->
                        <div class="col-md-4">
                            <label>Tipo de bencina (*)</label>
                            <select class="form-control" id="benzine" name="benzine" placeholder="" type="text">
                                <option value="">--</option>
                                <option value="93">93</option>
                                <option value="95">95</option>
                                <option value="97">97</option>
                                <option value="Diesel">Diesel</option>
                                <option value="Híbrido">Híbrido</option>
                            </select>
                        </div>
                    </div>

                    <div class="espacio col-md-12">
                        <!--Tipo de Vehículo -->
                        <div class="col-md-4">
                            <label>Tipo de Vehículo (*)</label>
                            <select class="form-control" id="typeCar" name="typeCar" placeholder="" type="text">
                                <option value="">--</option>
                                <option value="1">Particular</option>
                                <option value="2">Utilitario</option>
                                <option value="3">Pick-up</option>
                                <option value="4">SUV</option>
                            </select>
                        </div>

                        <!--Patente-->
                        <div class="col-md-4">
                            <label>Patente (*)</label>
                            <input class="form-control" id="patent" name="patent" placeholder="Patente" type="text">
                        </div>

                        <!--Color-->
                        <div class="col-md-4">
                            <label>Color (*)</label>
                            <input class="form-control" id="color" name="color" placeholder="color" type="text">
                        </div>
                    </div>
                </fieldset>

                <fieldset id="paso2">
                    <legend>Precios del vehículo </legend>
                    <p class="msjPrecio">Fija el precio de tu auto > <span class="msjModelo"></span></p>
                </fieldset>

                <fieldset id="paso3">
                    <legend>Disponibilidad del vehículo</legend>
                    
                </fieldset>

                <fieldset id="paso4">
                    <legend>Fotos del Vehículo</legend>
                </fieldset>

            </form>

        </div>
    </div>
</div>

<div class="space-100 hidden-xs"></div>



<script>

    $(document).ready(function(){
            $("#createCar").formToWizard({ submitButton: 'SaveAccount' })
    });

    $("#brand").change(function(){

        var brandId = $(this).val();

        if (brandId == 0) {
            return false;
        }

        $("#model").attr("disabled", true);

        $.post("<?php echo url_for('cars/getModels') ?>", {"brandId": brandId}, function(r){
            console.log(r);
            if (r.error) {
                console.log(r.errorMessage);
            } else {

                var html = "<option selected value='0'>Selecciona la marca</option>";
                
                $.each(r.models, function(k, v){
                    html += "<option value='"+v.id+"'>"+v.name+"</option>";
                });

                $("#model").html(html);
            }

            $("#model").removeAttr("disabled");

        }, 'json');
    });

   /* function createNextButton(i) {
            var stepName = "step" + i;
            $("#" + stepName + "commands").append("<a href='#' id='" + stepName + "Next' class='next'>Siguiente ></a>");

            $("#" + stepName + "Next").bind("click", function(e) {
                $("#" + stepName).hide();
                $("#step" + (i + 1)).show();
                if (i + 2 == count)
                    $(submmitButtonName).show();
                selectStep(i + 1);
            });
        }*/

    function validateForm() {

        var address        = $("#address").val();
        var commune        = $("#commune").val();
        var brand          = $("#brand").val();
        var model          = $("#model").val();
        var ano            = $("#ano").val();
        var door           = $("#door").val();
        var transmission   = $("#transmission").val();
        var benzine        = $("#benzine").val();
        var typeCar        = $("#typeCar").val();
        var patent         = $("#patent").val();
        var color          = $("#color").val();


        $.post("<?php echo url_for('cars/doValidate1') ?>", {"address": address, "commune": commune, "brand": brand, "model": model, "ano": ano, "door": door, "ano": ano, "transmission": transmission}, function(r){

            $(".alert").removeClass("alert-a-danger");
            $(".alert").removeClass("alert-a-success");

            if (r.error) {
                $(".alert").addClass("alert-a-danger");
                $(".alert").html(r.errorMessage);
            } else {
                window.location.href = r.url_complete;
            }

        }, 'json');
    }    

    


</script>