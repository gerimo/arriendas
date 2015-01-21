<link href="/css/newDesign/create.css" rel="stylesheet" type="text/css">


<div class="space-100"></div>

<div class="container">
    <div class="col-md-offset-2 col-md-8 BCW">
        <div class="row">
            <form id="priceCar">

                <fieldset id="paso2">
                    <legend>Precios del vehículo</legend>
                    <div class = "col-sm-12 col-md-12 text-center">
                        <p>Fija el precio de tu auto > <?php echo $Car->getModel()->getBrand()->getName().", "." ".$Car->getModel()->getName().", ".   " ".$Car->getYear() ?></p>
                    </div>

                    <div class="space-50"></div>


                    <!--precios obligatorios-->
                    <div class="col-md-12">
                        <div class="col-sm-6 col-md-6">
                            <label>Precio por hora (*)</label>
                            <input class="form-control" id="priceHour" name="priceHour" placeholder="" type="text">
                        </div>
                        <div class="col-sm-6 col-md-6">
                            <label>Precio por día (*)</label>
                            <input class="form-control" id="priceDay" name="priceDay" placeholder="" type="text">
                        </div>
                        </div>
                    </div>

                    <!--precios Opcionales-->
                    <div class="col-md-12">
                        <div class="col-sm-6 col-md-6">
                            <label>Precio por semana (Opcional)</label>
                            <input class="form-control" id="priceWeek" name="priceWeek" placeholder="" type="text">
                        </div>
                        <div class="col-sm-6 col-md-6">
                            <label>Precio por mes (Opcional)</label>
                            <input class="form-control" id="priceMonth" name="priceMonth" placeholder="" type="text">
                        </div>
                    </div>
                    
                </fieldset>
            </form>

            <div class="hidden-xs space-100"></div>
            <div class="visible-xs space-50"></div>

            <div class="col-md-offset-8 col-md-4">
                <button class="btn-a-primary btn-block" id="save" data-id="<?php echo $Car->getId(); ?>" onclick="validateForm()">siguiente</button>
                <p class="alert"></p> 
            </div>
        </div>
    </div>
</div>

<div class="space-100 hidden-xs"></div>



<script>

function validateForm() {

        var priceHour        = $("#priceHour").val();
        var priceDay         = $("#priceDay").val();
        var priceWeek        = $("#priceWeek").val();
        var priceMonth       = $("#priceMonth").val();
        var carId            = $("#save").data("id"); 


        $.post("<?php echo url_for('cars/getValidatePrice') ?>", {"priceHour": priceHour, "priceDay": priceDay, "priceWeek": priceWeek, "priceMonth": priceMonth, "carId": carId}, function(r){

            
            $(".alert").removeClass("alert-a-danger");
            $(".alert").removeClass("alert-a-success");

            $("#priceHour").removeClass("alert-danger");
            $("#priceDay").removeClass("alert-danger");


            if (r.error) {
                $(".alert").addClass("alert-a-danger");
                $(".alert").html(r.errorMessage);

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
                window.location.href = r.url_complete;
            }

        }, 'json');
    }

    


</script>