<link href="/css/newDesign/create.css" rel="stylesheet" type="text/css">


<div class="space-100"></div>

<div class="container">
    <div class="col-md-offset-2 col-md-8 BCW">
        <div class="row">
            <form id="priceCar">

                <fieldset id="paso3">
                    <legend>Disponibilidad del vehículo</legend>
                    <div class="hidden-xs space-50"></div>
                    <div class="visible-xs space-10"></div>
                    <div class = "col-md-12 text-center">
                        <p>¿Cúal es la disponibilidad de tu <?php echo $Car->getModel()->getBrand()->getName().", "." ".$Car->getModel()->getName().", ".   " ".$Car->getYear() ?>?</p>
                    </div>

                    <div class="hidden-xs space-70"></div>
                    <div class="visible-xs space-50"></div>  

                    <div class="col-md-offset-2 col-md-8 text-center">
                        <label>¿Cuando puedes recibir pedidos de reserva?</label>
                        <select class="form-control" id="availability" name="availability" placeholder="" type="text">
                            <option value="">--</option>
                            <option value='1'>Los días de semana</option>;
                            <option value='2'>Algunos o todos los fines de semana</option>;
                            <option value='3'>Todos los días</option>;
                        </select>
                    </div>
                    
                    <div class="hidden-xs space-100"></div>
                    <div class="visible-xs space-50"></div>

                    <div class="col-md-offset-2 col-md-8">
                        <p>Te recomendamos marcar "Disponibilidad Completa" para que el auto siempre figure en los resultados de búsqueda. Recuerdas que siempre es posible rechazar un pedido de reserva si tu auto no esta disponible.</p>
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

<div class="space-100 hidden-xs"></div>



<script>

function validateForm() {

        var availability     = $("#availability option:selected").val();
        var carId            = $("#save").data("id"); 


        $.post("<?php echo url_for('cars/getValidateAvailability') ?>", {"availability": availability, "carId": carId}, function(r){

            
            $(".alert").removeClass("alert-a-danger");
            $(".alert").removeClass("alert-a-success");

            $("#availability").removeClass("alert-danger");



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

    


</script>