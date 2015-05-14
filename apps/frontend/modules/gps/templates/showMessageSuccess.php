
<link href="/css/newDesign/gpsMessage.css" rel="stylesheet" type="text/css">
<div class="hidden-xs space-100"></div>
<div class="visible-xs space-50"></div>    

<div class="row">
	<div class="content">
	    <div class="col-md-offset-2 col-md-8">
	        <div class="BCW clearfix">
	            <h1 class="text-center">¡ADVERTENCIA!</h1>
            	<div class="row">
	            	<p class="text-center thumbnail">
		            	Desde el 15 de Mayo del 2015, <a href="www.arriendas.cl">Arriendas</a> exije que adquieras un sistema de rastreo por GPS.<br>
		            	Esto, para garantizar una mayor seguridad en tus arriendos.<br><br>
					</p>
	            </div>

	            <div class="hidden-xs space-40"></div>

	            <div class="col-md-offset-1 col-md-10">
		            <div class="text-center">
		            	<h2>Detalles de la compra</h2>
		            </div>

					<div class="detail">
                    	<span class="pull-right">$<span class="price"><?php echo $gps_price ?></span></span><?php echo $gps_description ?>
                	</div>

                	<div>
                		<hr>
                	</div>

                	<div class="">
                    	<span class="pull-right">$<span class="price"><?php echo $gps_price ?></span></span>TOTAL
                	</div>

	            	<div class="hidden-xs space-20"></div>

                	<div class="">
		            	<div class="row">
	            			<a class="btn btn-block btn-a-action" id="pay">¡Comprar!</a>
	            		</div>
	            		<div class="row">
	            			<div class="foot-cancel row text-center">
				            	<p>Puedes canelar la compra haciendo click <a id="cancel">Aquí</a></p>
				            </div>
	            		</div>
		            </div>
                </div>
	        </div>
	    </div>
	</div>
</div>
<div class="hidden-xs space-100"></div>																			
<div class="visible-xs space-50"></div> 
<div id="id" data-id="<?php echo $carId ?>"></div>
<div id="description" data-description="<?php echo $gps_description ?>"></div>
<div id="price" data-price="<?php echo $gps_price ?>"></div>
<div id="dialog-alert" title="">
	<p></p>
</div>

<script>
	$("#cancel").click(function(){
		$("#dialog-alert p").html("¿Estás seguro?");
	    $("#dialog-alert").attr('title','¡ADVERTENCIA!');
	    $("#dialog-alert").dialog({
	        buttons: [
	        	{
		            text: "Aceptar",
		            click: function() {
	                	$( this ).dialog( "close" );
	                	cancel();
	            	}
	        	},
	        	{
	        		text: "cancelar",
		            click: function() {
	                	$( this ).dialog( "close" );
	            	}
	        	}
	        ],
	        minHeight: 200,
	        minWidth: 400
	    });
		
	});
	
    function cancel(){
    	var parameters = {
			"carId"         : $("#id").data("id")
		};
    	$.post("<?php echo url_for('gps_cancel') ?>", parameters, function(r){
			if (r.error) {
				$("#dialog-alert p").html(r.errorMessage);
			    $("#dialog-alert").attr('title','Error');
			    $("#dialog-alert").dialog({
			        buttons: [{
			            text: "Aceptar",
			            click: function() {
			                $( this ).dialog( "close" );
			            }
			        }],
			        minHeight: 200,
			        minWidth: 400
			    });
			} else {
				window.location.assign("<?php echo url_for('cars') ?>");
			}
		}, 'json');
    }
</script>