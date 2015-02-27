<div class="hidden-xs space-40"></div>
<div class="visible-xs space-50"></div>

<div class="row">
    <div class="col-md-offset-2 col-md-8">
        <h1>Pagos Quincenales</h1> 
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
                    <th>Precio Reserva (70%)</th>
                    <th>Quincena de la Reserva</th>
                    <th>N° de factura</th>
                    <th>¿Se realizo pago?</th>
				</tr>
			</thead>

			<tbody>
			</tbody>
		</table>  
	</div>

    <!-- Modal -->
    <div class="modal fade" id="photoModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Datos de Cuenta de usuario</h4>
                </div>
                <div class="modal-body col-md-12">
                    <input class="form-control" name="rutAccount" id="rutAccount" placeholder="Rut de cuenta"  type="text"> 
                    <div class="space-10"></div>
                    <input class="form-control" name="numberAccount" id="numberAccount" placeholder="N° de cuenta" type="text">   
                    <div class="space-10"></div>
                    <select class="form-control" id="bank" name="bank">
                        <option value="0">Seleccione su banco</option>
                        <?php foreach ($Banks as $Bank): ?>
                            <option value="<?php echo $Bank->id ?>"><?php echo $Bank->name ?></option>
                        <?php endforeach ?>
                    </select>
                    <div class="space-10"></div>
                    <select class="form-control" id="account" name="account">
                        <option value="0">Seleccione el tipo de cuenta</option>
                        <?php foreach ($BankAccountTypes as $BankAccountType): ?>
                            <option value="<?php echo $BankAccountType->id ?>"><?php echo $BankAccountType->name ?></option>
                        <?php endforeach ?>
                    </select>
                    <div class="space-30"></div>
                </div>
                <div class="modal-footer">
                    <button data-dismiss="modal" datatype="button" class="btn btn-danger">Cerrar</button>
                    <button type="button" class="btn btn-success" onclick="saveAccount()">Guardar</button>
                </div>
            </div>
        </div>
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
            "amount" : 0
        }
		$.post("<?php echo url_for('reserve_find_expired_reserves') ?>", parameters, function(r){
			if (r.error) {
				$('#expiredReservesTable').DataTable().rows().remove().draw();
				$(".load").hide();
				console.log(r.errorMessage);
			} else {	
				$('#expiredReservesTable').DataTable().rows().remove().draw();
				$.each(r.data, function(k, v){  
                    var paidUser  = optionSelected(v.t_is_paid_user, v.t_id, 1);
                    var button = "<a class='btn btn-block btn-primary datos' data-user-id='"+v.uc_id+"' >Datos de cuenta</a>";
					$('#expiredReservesTable').DataTable().row.add([v.uc_id, v.uc_fullname, v.uc_telephone, v.uc_email, button, v.r_id, v.r_date, v.r_duration, v.r_price, v.r_fortnightly ,v.t_number, paidUser]).draw();
				});
				$(".load").hide();
			}
		}, 'json');
	}

    function optionSelected(option, transactionId, type) {

        if (option == 0) {
            var select ="<label class='radio-inline'> <input class='radio' value='1' type='radio' name='inlineRadioOptions"+cont+"'  data-type='"+type+"' data-transaction-id='"+transactionId+"' data-cont='"+cont+"'> SI </label>"+ 
                        "<label class='radio-inline'> <input class='radio' value='0' type='radio' checked name='inlineRadioOptions"+cont+"' data-type='"+type+"' data-transaction-id='"+transactionId+"' data-cont='"+cont+"'> NO</label>";
        } else {
            var select ="<label class='radio-inline'> <input class='radio' value='1' type='radio' checked name='inlineRadioOptions"+cont+"' data-type='"+type+"' data-transaction-id='"+transactionId+"' data-cont='"+cont+"'> SI </label>"+ 
                        "<label class='radio-inline'> <input class='radio' value='0' type='radio' name='inlineRadioOptions"+cont+"'  data-type='"+type+"' data-transaction-id='"+transactionId+"' data-cont='"+cont+"'> NO</label>";
        }

        cont+=1;
        return select;
    }
    
    $('body').on("click", ".radio", function(e){

        var transactionId   = $(this).data("transaction-id");
        var type            = $(this).data("type");
        var cont            = $(this).data("cont");

        var name = "inlineRadioOptions"+cont;

        var option = $("input[name='"+name+"']:checked").val();

        var parameters = {
            "transactionId"  : transactionId,
            "option"         : option,
            "type"           : type
        };

        $.post("<?php echo url_for('transaction_edit') ?>", parameters, function(r){
            if (r.error) {
                console.log(r.errorMessage);
            } else {
            }
        }, 'json')
    });

    $('body').on("click", ".datos", function(e){

        $("#userId").val($(this).data("user-id"));
        $("#photoModal").modal('show');
    });

    function saveAccount() {

        var userId = $("#userId").val();
        var rutAccount     = $("#rutAccount").val();
        var numberAccount  = $("#numberAccount").val();
        var bankId         = $("#bank option:selected").val();
        var accountId      = $("#account option:selected").val();

        var parameters = {
            "userId" : userId,
            "rutAccount": rutAccount,
            "numberAccount": numberAccount,
            "bankId": bankId,
            "accountId": accountId
        }

        $.post("<?php echo url_for('user_edit_account') ?>", parameters, function(r){
            if (r.error) {
                alert(r.errorMessage);
            } else {
                $("#photoModal").modal('hide');
            }
        }, 'json')

    }

</script>