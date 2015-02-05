<?php use_helper('FrontendRouting') ?>

<div class="hidden-xs space-100"></div>
<div class="visible-xs space-50"></div>

<div class="row">
    <div class="col-md-12">
        	<div class="col-md-offset-4 col-md-4">
            <input class="form-control" id="idReservaOriginal" placeholder="ID reserva original" type="text" onblur="isOriginalReserve()">
            <i class="find fa fa-check" id="reserve-check"></i>
            <i class="find fa fa-remove"id="reserve-remove"></i>
            <div class="space-70"></div>
        </div>

        <div>
            <img class="loading" src="/images/ajax-loader.gif">
            <table class="display responsive no-wrap" id="carsActivesTable" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Comuna</th>
                        <th>Marca</th>
                        <th>Modelo</th>
                        <th>Año</th>
                        <th>Transmisión</th>
                        <th>Tipo Bencina</th>
                        <th>Tipo Vehículo</th>
                        <th>Metro Cercano</th>
                        <th>Cant. reserva ult. 3 meses</th>
                        <th>Nombre usuario</th>
                        <th>Telefono usuario</th>
                        <th>Crear Oportunidad</th>
                    </tr>
                </thead>
            </table>
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

    /*var urlCreate = "<?php echo url_for_frontend('opportunities_approve', array('carId' => 'carIdPattern', 'reserveId' => 'reserveIdPattern')) ?>";*/
    
	$(document).ready(function() {

        $(".found").hide();  
        $(".find").hide();    
 		$("#create").prop("disabled", true);

        $('#carsActivesTable').hide();
        $('.loading').hide();  
    });

    $(document).on("click", ".opp-approve", function(){

        var reserveId = $(this).data("reserve-id");
        var carId = $(this).data("car-id");
    
        var parameters = {
            "reserveId" : reserveId,
            "carId" : carId
        };

        $.post("<?php echo url_for_frontend('opportunities_approve') ?>", parameters, function(r){
            console.log("SUCCESS");
            if (r.error) {
                console.log(r.errorMessage);
            } else {
                console.log("ok");
            }

        }, 'json')
        .fail(function(r) {
            console.log("FAIL");
            console.log(r);
        });
    });

    function isOriginalReserve() {

        var idReservaOriginal = $("#idReservaOriginal").val();
		$("#reserve-check").hide();
 		$("#reserve-remove").hide();

        $.post("<?php echo url_for('reserve_is_original_reserve') ?>", {"idReservaOriginal": idReservaOriginal}, function(r){

        	if (r.error) {
           		console.log(r.errorMessage);
            } else {
            	if (r.original != null) {
	            	if (r.original) {

                        getActivesCars(idReservaOriginal);
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

    function getActivesCars(reserveId) {

        $(".loading").show();

        $.post("<?php echo url_for('car_get_actives_cars') ?>", {"reserveId": reserveId} , function(r){

            if (r.error) {
                console.log(r.errorMessage);
            } else {
                
                $('#carsActivesTable').DataTable({
                    info: false,
                    paging: true,
                    responsive: true
                });
 
                $('#carsActivesTable').DataTable().column(9).order( 'desc' );

                $('#carsActivesTable').show();
                $('#carsActivesTable').DataTable().rows().remove().draw();
                
                $.each(r.data, function(k, v){
                    var button = "<button class='btn btn-block btn-primary opp-approve' data-reserve-id='"+reserveId+"' data-car-id='"+v.car_id+"'>Crear</button>";
                    $('#carsActivesTable').DataTable().row.add([ v.car_id, v.car_commune, v.car_brand, v.car_model, v.car_year, v.car_transmission, v.car_benzine, v.car_type, v.car_subway, v.car_cant, v.user_fullname , v.user_telephone, button ]).draw();
                });
                
                $(".loading").hide();
            }

        }, 'json');
    }
    
</script>