<?php use_helper('FrontendRouting'); ?>

<div class="hidden-xs space-100"></div>
<div class="visible-xs space-50"></div>

<div class="row">
    <div class="col-md-offset-2 col-md-8">
    	<div class="col-md-offset-4 col-md-4">
            <input class="form-control" id="idReservaOriginal" placeholder="ID reserva original" type="text" onblur="isOriginalReserve()">
            <i class="find fa fa-check" id="reserve-check"></i>
            <i class="find fa fa-remove"id="reserve-remove"></i>
            <div class="space-20"></div>
            <input class="form-control" id="idVehiculo" placeholder="ID vehículo" type="text" value="" onblur="isActiveCar()">
            <div class="space-20"></div>
            <i class="found fa fa-check" id="car-check"></i>
            <i class="found fa fa-remove" id="car-remove"></i>
            <button class="btn btn-primary btn-block" id="create"type="button" onclick="oppApprove()">Crear</button>
        </div>
    </div>

    </div>
    <div style="display:none">
        <div id="dialog-alert" title="">
            <p></p>
        </div>
    </div>

</div>

<div class="hidden-xs space-100"></div>

    

<script>

	$(document).ready(function() {
        $(".found").hide();  
        $(".find").hide();    
 		$("#create").prop("disabled", true);

    }); 

    function isOriginalReserve() {

        var idReservaOriginal = $("#idReservaOriginal").val();
		$("#reserve-check").hide();
 		$("#reserve-remove").hide();

        $.post("<?php echo url_for('opportunity_is_original_reserve') ?>", {"idReservaOriginal": idReservaOriginal}, function(r){

        	if (r.error) {
           		console.log(r.errorMessage);
            } else {
            	if (r.original != null) {
	            	if (r.original) {
	            		$("#reserve-check").show();
	            			if ($("#car-check").is(":visible")) {
	            				$("#create").prop("disabled", false);
	            			}
	            	}else {
	            		$("#reserve-remove").show();
	            	}
            	}
            }

        }, 'json');
    }

    function isActiveCar() {

        var idCar = $("#idVehiculo").val();
		$("#car-check").hide();
 		$("#car-remove").hide();

        $.post("<?php echo url_for('opportunity_is_active_car') ?>", {"idCar": idCar}, function(r){

        	if (r.error) {
           		console.log(r.errorMessage);
            } else {
            	if (r.original !=  null) {
	            	if (r.original) {
	            		$("#car-check").show();
	            		if ($("#reserve-check").is(":visible")) {
	            				$("#create").prop("disabled", false );
	            			}
	            	}else {
	            		$("#car-remove").show();
	            	} 
           		}
            }

        }, 'json');
    }

    function oppApprove() {

        var carId = $("#idVehiculo").val();
        var reserveId = $("#idReservaOriginal").val();

        var parameters = {
            "reserveId": reserveId,
            "carId": carId
        };

        console.log(parameters);

        $.post("<?php echo url_for_frontend('opportunities_approve') ?>", parameters, function(r){
            console.log("1");
            if (r.error) {
                console.log("2");
                console.log(r.errorMessage);
            } else {
                console.log("3");
            }
            console.log("4");
        }, "jsonp");
    }

  


</script>