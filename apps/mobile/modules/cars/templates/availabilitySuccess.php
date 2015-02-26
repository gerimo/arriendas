<link href="/css/newDesign/create.css" rel="stylesheet" type="text/css">


<div class="space-100"></div>

<div class="container">
    <div class="col-md-offset-2 col-md-8 BCW">
        <div class="row">
            <form id="priceCar">

                <fieldset id="paso3">
                    <h1>Disponibilidad del vehículo </h1>
                    <div class="visible-xs space-10"></div>
                    <div class = "col-md-12 text-center">
                        <p style="font-weight: 300; font-size:20px;">¿Qué días de la semana puedes recibir clientes?</p>
                    </div>

                    <div class="visible-xs space-50"></div>  

                    <div id="availability-container">
                        <div class="col-md-10">
                        </div>
                        <div class="radio">
                            <label>
                                <input class="check" name="option1" type="checkbox" value="1">
                                Puedo recibir clientes en la semana.
                            </label>
                        </div>
                        <div class="radio">
                            <label>
                                <input class="check" name="option2" type="checkbox" value="2">
                                 Puedo recibir clientes los fines de semana.   
                            </label>
                        </div> 
                        <div class="col-md-12">
                            <p>Si tienes disponibilidad todos los días, selecciona fines de semana y semana.</p>
                        </div>

                   </div>
                    <div class="visible-xs space-50"></div>
                    
                    <div class="visible-xs space-50"></div>

                    <div class="col-md-12 text-center">
                        <p style="font-weight: 300; font-size:20px;">Otros filtros de busqueda</p>
                    </div>
                    
                    <div class="visible-xs space-50"></div>  
                    <div class="radio">
                            <label>
                                <input class="check" name="option3" type="checkbox" value="4">
                                Puedo recibir clientes que pagan el mismo día.
                            </label>
                    </div>
                    <div class="radio">
                        <label>
                            <input class="check" name="option4" type="checkbox" value="8">
                            Solo arriendos mayores a 24 horas.
                        </label>
                    </div>

                    <div class="visible-xs space-50"></div>

                    <div class="col-md-offset-1 col-md-10 text-center">
                        <p style="color:#00aced; font-size: 25px; font-weight: 300;">Aparicion en busqueda: <span id="percent">0</span>% de reservas<span id="more"><span></p>
                    </div>
                    
                </fieldset>
            </form>
            <div class="space-50"></div>
            <div class="col-md-offset-8 col-md-4">
                <button class="btn-a-primary btn-block" id="save" data-id="<?php echo $Car->getId(); ?>" onclick="validateForm()">Siguiente</button>
                <p class="alert"></p> 
            </div>
        </div>
    </div>
</div>

<script>

    function validateForm() {

        var option1     = $('input:checkbox[name=option1]:checked').val();
        var option2     = $('input:checkbox[name=option2]:checked').val();
        var option3     = $('input:checkbox[name=option3]:checked').val();
        var option4     = $('input:checkbox[name=option4]:checked').val();
        var carId       = $("#save").data("id"); 


        $.post("<?php echo url_for('cars/getValidateAvailability') ?>", {"option1": option1, "option2": option2, "option3": option3, "option4": option4, "carId": carId}, function(r){

            
            $(".alert").removeClass("alert-a-danger");
            $(".alert").removeClass("alert-a-success");

            if (r.error) {
                $(".alert").addClass("alert-a-danger");
                $(".alert").html(r.errorMessage);

                 if(r.errorCode == 1){
                    $("#availability").addClass("alert-danger");
                    $('#availability').focus();

                }

            } else {
                window.location.href = r.url_complete;
            }

        }, 'json');
    }


    $('.check').change(function(){
        isChecked();
    });

    function isChecked(){

        var total = 0;
        $("#more").html("");

        if($('input:checkbox[name=option1]').is(':checked')) {
            total+= 40;
        }
        if($('input:checkbox[name=option2]').is(':checked')) {
            total+= 40;
        }
        if($('input:checkbox[name=option3]').is(':checked')) {
            total+= 20;
        }
        if($('input:checkbox[name=option4]').is(':checked')) {
            $("#more").html(" mayores a 24 horas");
        }

        $("#percent").html(total);
    }

</script>