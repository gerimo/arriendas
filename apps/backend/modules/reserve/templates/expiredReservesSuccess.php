<div class="hidden-xs space-40"></div>
<div class="visible-xs space-50"></div>

<div class="row">
    <div class="col-md-offset-2 col-md-8">
        <h1>Devolución Pago Garantía</h1> 
        <div class="space-30"> </div>
        <div class="col-md-12">
            <div class="col-md-4">
                <input class="datepicker form-control" id="from" placeholder="Desde" type="text" value="<?php echo date("Y-m-d")?>"  >
            </div>
            <div class="col-md-4">
                <input  class="datepicker form-control" id="to" placeholder="Hasta" type="text" value="<?php echo date("Y-m-d")?>"  >
                
            </div>

            <div class="col-md-4">
                <button class="buscar btn btn-block btn-primary" onclick="findExpiredReserves()">Buscar</button>
            </div>
        </div>

        <img class="load" src="/images/ajax-loader.gif">
        <div class="space-100"></div>
    </div>

	<div class="col-md-12">
		<table  class="display responsive no-wrap" id="expiredReservesTable" cellspacing="0" width="100%">
			<thead>
				<tr>
                    <th>ID Usuario</th>
                    <th>Nombre Usuario</th>
                    <th>Teléfono Usuario</th>
                    <th>Email Usuario</th>
    				<th>ID Reserva</th>
    				<th>Fecha Comienzo Reserva</th>
                    <th>Fecha final Reserva</th>
                    <th>¿Se devolvió el despósito de garantía?</th>
                    <th>¿Ocurrió algún siniestro?</th>
				</tr>
			</thead>

			<tbody>
			</tbody>
		</table>  
	</div>

	<div style="display:none">
        <input id="userI">
		<div id="dialog-alert" title="">
			<p></p>
		</div>
		<input type="hidden" value="" id="userId">
	</div>
</div>

<div class="hidden-xs space-10f0"></div>

<script>
    var cont = 0;

	$(document).ready(function() {

		findExpiredReserves();
	 
		$('#expiredReservesTable').DataTable({
			info: false,
			paging: true,
			responsive: true
		});    
	}); 

    $('#from').datetimepicker({

        dayOfWeekStart: 1,
        format:'Y-m-d',
        lang:'es',
        timepicker: false,
        maxDate: "<?php echo date('Y-m-d') ?>"
    });

    $('#to').datetimepicker({

        dayOfWeekStart: 1,
        format:'Y-m-d',
        lang:'es',
        timepicker: false,
        maxDate: "<?php echo date('Y-m-d') ?>"
    });

	function findExpiredReserves() {

		$(".load").show();

        var from = $("#from").val();
        var to   = $("#to").val();
        var cont = 0;

        var parameters = {
            "from"   : from,
            "to"     : to,
            "amount" : true
        }
		$.post("<?php echo url_for('reserve_find_expired_reserves') ?>", parameters, function(r){
			if (r.error) {
				$('#expiredReservesTable').DataTable().rows().remove().draw();
				$(".load").hide();
				console.log(r.errorMessage);
			} else {	
				$('#expiredReservesTable').DataTable().rows().remove().draw();
				$.each(r.data, function(k, v){

                    var warranty  = optionSelected(v.r_warranty, v.r_id, 1);
                    var sinister  = optionSelected(v.r_sinister, v.r_id, 2);
					$('#expiredReservesTable').DataTable().row.add([v.u_id, v.u_fullname, v.u_telephone, v.u_email, v.r_id, v.r_date, v.r_duration, warranty, sinister]).draw();
				});
				$(".load").hide();
			}
		}, 'json');
	}

    function optionSelected(option, reserveId, type) {

        if (option == 0) {
            var select ="<label class='radio-inline'> <input class='radio' value='1' type='radio' name='inlineRadioOptions"+cont+"'  data-type='"+type+"' data-reserve-id='"+reserveId+"' data-cont='"+cont+"'> SI </label>"+ 
                        "<label class='radio-inline'> <input class='radio' value='0' type='radio' checked name='inlineRadioOptions"+cont+"' data-type='"+type+"' data-reserve-id='"+reserveId+"' data-cont='"+cont+"'> NO</label>";
        } else {
            var select ="<label class='radio-inline'> <input class='radio' value='1' type='radio' checked name='inlineRadioOptions"+cont+"' data-type='"+type+"' data-reserve-id='"+reserveId+"' data-cont='"+cont+"'> SI </label>"+ 
                        "<label class='radio-inline'> <input class='radio' value='0' type='radio' name='inlineRadioOptions"+cont+"'  data-type='"+type+"' data-reserve-id='"+reserveId+"' data-cont='"+cont+"'> NO</label>";
        }

        cont+=1;
        return select;
    }
    
    $('body').on("click", ".radio", function(e){

        var reserveId   = $(this).data("reserve-id");
        var type        = $(this).data("type");
        var cont        = $(this).data("cont");

        var name = "inlineRadioOptions"+cont;

        var option = $("input[name='"+name+"']:checked").val();

        var parameters = {
            "reserveId"  : reserveId,
            "option"     : option,
            "type"       : type
        };

        $.post("<?php echo url_for('reserve_edit') ?>", parameters, function(r){
            if (r.error) {
                console.log(r.errorMessage);
            } else {
            }
        }, 'json')
    });



</script>