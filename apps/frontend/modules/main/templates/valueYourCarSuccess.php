<link href="/css/newDesign/valueYourCar.css" rel="stylesheet" type="text/css">

<div class="hidden-xs space-100"></div>
<div class="visible-xs space-50"></div>
<div class="container">
	<div class="row">
	    <div class="col-md-offset-1 col-xs-12 col-md-10">
	        <div class="BCW">
	        	<!--<?php echo form_tag('main/doRegister', array('method'=>'post', 'id'=>'frm1'));?> -->
				<h1 class="valueYourCarTitle">Monetiza tu auto</h1>

		   		<div class="row">
			   		<div class="col-md-offset-1 col-md-3">
		        		<h4>Marca del auto:</h4>
						<select name="brand" class="form-control" id="brand">
							<?php foreach ($Brands as $Brand): ?>
								<option value="<?php echo $Brand->id ?>" ><?php echo $Brand->getName()?></option> 		
							<?php endforeach; ?>
						</select>
			   		</div>	

			   		<div class="col-md-3">
		        		<h4>Modelo del auto:</h4>
						<select name="model" class="form-control" id="model">
							<option selected value="0">Selecciona tu modelo</option>
						</select>
			   		</div>

			   		<div class="col-md-2">
			        	<h4>A&ntilde;o</h4>
						<select name="year" class="form-control" id="year">
							<?php for($i = 2014; $i >= 1996 ;$i--): ?>
								<option value="<?php echo $i?>" ><?php echo $i?></option> 		
							<?php endfor;?>
						</select>
			   		</div>

			   		<div class="visible-xs space-10"></div>

			   		<div class="col-md-2">
			   			<div class="space-30"></div>
			    		<input class="btn btn-a-primary" id="botonCalcular" type="button" value="Calcular"/>
			   	 	</div>
				</div>

				<div class="resultados" style="display: none;">
			   	 	<div class="row text-center">
						<div class="col-md-offset-4 col-md-4 col-xs-12">

							<img class="loading" src="/images/ajax-loader.gif">
							<div class="visible-xs space-40"></div>
							<div class="hidden-xs space-50"></div>

							<h2>Precios Sugeridos:</h2>

							<div class="visible-xs space-10"></div>
							<div class="hidden-xs space-30"></div>

							<p class="text-info">Por hora: <span class="precioPorHora"></span></p>

							<div class="visible-xs space-10"></div>
							<div class="hidden-xs space-30"></div>

							<p class="text-info">Por día: <span class="precioPorDia"></span></p>

				    	</div>	
			    	</div>
					<div class="hidden-xs space-10"></div>
			    	<div class="row text-center">
			    		<div class="visible-xs space-20"></div>
			    		<div class="hidden-xs space-30"></div>
				    	<div class="clearfix col-md-offset-4 col-md-4 col-xs-12">
			    			<a href="<?php echo url_for('car_create') ?>" class="btn btn-block regular-link btn-a-primary">Sube un auto</a>
			    		</div>
			    	</div>				    
			    </div>

			    <div class="resultadoError" style="display: none;">
			    	<div class="hidden-xs space-20"></div>
            		<p class="alert text-center"></p>
            		<div class="hidden-xs space-20"></div>
            		<div class="visible-xs space-20"></div>
			    </div>

			</div>
		</div>
	</div> 
</div>
<div class="hidden-xs space-100"></div>
<script>

function formatNumber(precio){
    var precioMillones = Math.floor(precio/1000000);
    var precioMiles = parseInt(precio) - (precioMillones*1000000);
    precioMiles = Math.floor(precioMiles/1000);
    var precioCientos = parseInt(precio) - (precioMiles*1000);
    var precioResultado = "";
    if(precioMillones == 0) {
        if(precioMiles == 0) {
            precioResultado = precioCientos;
        } else {
            if(precioCientos == 0) {
                precioResultado = precioMiles + ".000";
            } else { 
                cantidadDeCientos = ((precioCientos).toString()).length;
                if(cantidadDeCientos == 1) {
                	precioResultado = precioMiles + ".00" + precioCientos;
                } else {
                	if(cantidadDeCientos == 2) {
                		precioResultado = precioMiles + ".0" + precioCientos;
                	} else {
                		precioResultado = precioMiles + "." + precioCientos;
                	}
                }
            }
        }
    } else {
        if(precioMiles == 0) {
            if(precioCientos == 0){
                precioResultado = precioMillones + ".000.000";
            } else { 
                cantidadDeCientos = ((precioCientos).toString()).length;
                if(cantidadDeCientos == 1) {
                	precioResultado = precioMillones + ".000.00" + precioCientos;
                } else {
                	if(cantidadDeCientos == 2) {
                		precioResultado = precioMillones + ".000.0" + precioCientos;
                	} else {
                		precioResultado = precioMillones + ".000." + precioCientos;
                	}
                }
            }
        } else {
            cantidadDeMiles = ((precioMiles).toString()).length;
            cantidadDeCientos = ((precioCientos).toString()).length;

            if(cantidadDeMiles == 1) {
                if(cantidadDeCientos == 1) {
                	precioResultado = precioMillones + ".00" + precioMiles + ".00" + precioCientos;
                } else {
                	if(cantidadDeCientos == 2) {
                		precioResultado = precioMillones + ".00" + precioMiles + ".0" + precioCientos;
                	} else {
                		precioResultado = precioMillones + ".00" + precioMiles + "." + precioCientos;
                	}
                }
            } else {
            	if(cantidadDeCientos == 2){
                	if(cantidadDeCientos == 1) {
                		precioResultado = precioMillones + ".0" + precioMiles + ".00" + precioCientos;
                	} else {
                		if(cantidadDeCientos == 2) {
                			precioResultado = precioMillones + ".0" + precioMiles + ".0" + precioCientos;
                		} else {
                			precioResultado = precioMillones + ".0" + precioMiles + "." + precioCientos;
                		}
                	}
                } else {
                    if(cantidadDeCientos == 1) { 
                    	precioResultado = precioMillones + "." + precioMiles + ".00" + precioCientos;
                    }else {
                    	if(cantidadDeCientos == 2) {
                    		precioResultado = precioMillones + "." + precioMiles + ".0" + precioCientos;
                    	}else {
                    		precioResultado = precioMillones + "." + precioMiles + "." + precioCientos;
                    	}
                    }
                }
            }
        }
	}
    return precioResultado;
}

	function ingresaPrecios(model, year){

		$(".loading").show();
		$('.precioPorHora').text("");
		$('.precioPorDia').text("");

		$('.precioPorHora').text("Calculando");
		$('.precioPorDia').text("Calculando");

		$(".alert").removeClass("alert-a-danger");
        $(".alert").removeClass("alert-a-success");

		$.post("<?php echo url_for('value_your_car_price') ?>", {"model": model, "year": year}, function(r){
			if(r.error){
				$('.resultados').hide();
				$('.resultadoError').show();
				$(".alert").addClass("alert-a-danger");
                $(".alert").html(r.errorMessage);
			} else {
				$('.resultadoError').hide();
				$(".loading").hide();
				var valorHora = r.precioHora;
				var valorDia = r.precioDia;

				$('.resultados').fadeIn("hide");
				$(".precioPorHora").text(formatNumber(valorHora));
				$(".precioPorDia").text(formatNumber(valorDia));

			}
		}, "json");

	}

	 $(document).on('ready',function() {
	 	getModel();

		$("#brand").change(function(){
			getModel();
		});
			
		$("#botonCalcular").click(function() {
			 /*var model = encodeURIComponent(document.getElementById("model").value);
			 var year = encodeURIComponent(document.getElementById("year").value);*/
			 var model = encodeURIComponent($("#model").val());
			 var year = encodeURIComponent($("#year").val());
			 if(model!='0'){
			 	ingresaPrecios(model,year);
			 }
			 

		});
			
	});


	function getModel(){
			var brandId = $("#brand").val();

	        $("#model").attr("disabled", true);

	        if (brandId > 0) {
	            $.post("<?php echo url_for('value_your_car_models') ?>", {"brand": brandId}, function(r){

	                if (r.error) {
	                    console.log(r.errorMessage);
	                } else {

	                    var html = "<option selected value='0'>Selecciona tu modelo</option>";

	                    $.each(r.models, function(k, v){
	                        html += "<option value='"+v.id+"'>"+v.name+"</option>";
	                    });

	                    $("#model").html(html);
	                }

	                $("#model").removeAttr("disabled");

	            }, 'json');
	        }
	    
	}

	</script>
