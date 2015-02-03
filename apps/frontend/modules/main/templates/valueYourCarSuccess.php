<link href="/css/newDesign/valueYourCar.css" rel="stylesheet" type="text/css">

<div class="hidden-xs space-100"></div>
<div class="visible-xs space-50"></div>
<div class="container">
	<div class="row">
	    <div class="col-md-offset-1 col-xs-12 col-md-10">
	        <div class="BCW">
				<?php echo form_tag('main/doRegister', array('method'=>'post', 'id'=>'frm1'));?>
				<h1>Monetiza tu auto</h1>

		   		<div class="row">

			   		<div class="col-md-offset-1 col-md-3">
		        		<h4>Marca del auto:</h4>
						<select name="brand" class="form-control" id="brand">
							<?php foreach ($marcas as $m): ?>
								<option value="<?php echo $m->getMarca() ?>" ><?php echo $m->getMarca() ?></option> 		
							<?php endforeach; ?>
						</select>
			   		</div>	

			   		<div class="col-md-3">
		        		<h4>Modelo del auto:</h4>
						<select name="model" class="form-control" id="model">
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
	        if(precioMillones == 0){
	            if(precioMiles == 0){
	                precioResultado = precioCientos;
	            }else{
	                if(precioCientos == 0){
	                    precioResultado = precioMiles + ".000";
	                }else{
	                    cantidadDeCientos = ((precioCientos).toString()).length;
	                    if(cantidadDeCientos == 1) precioResultado = precioMiles + ".00" + precioCientos;
	                    else if(cantidadDeCientos == 2) precioResultado = precioMiles + ".0" + precioCientos;
	                    else precioResultado = precioMiles + "." + precioCientos;
	                }
	            }
	        }else{
	            if(precioMiles == 0){
	                if(precioCientos == 0){
	                    precioResultado = precioMillones + ".000.000";
	                }else{
	                    cantidadDeCientos = ((precioCientos).toString()).length;
	                    if(cantidadDeCientos == 1) precioResultado = precioMillones + ".000.00" + precioCientos;
	                    else if(cantidadDeCientos == 2) precioResultado = precioMillones + ".000.0" + precioCientos;
	                    else precioResultado = precioMillones + ".000." + precioCientos;
	                }
	            }else{
	                cantidadDeMiles = ((precioMiles).toString()).length;
	                cantidadDeCientos = ((precioCientos).toString()).length;
	                if(cantidadDeMiles == 1){
	                    if(cantidadDeCientos == 1) precioResultado = precioMillones + ".00" + precioMiles + ".00" + precioCientos;
	                    else if(cantidadDeCientos == 2) precioResultado = precioMillones + ".00" + precioMiles + ".0" + precioCientos;
	                    else precioResultado = precioMillones + ".00" + precioMiles + "." + precioCientos;
	                }else if(cantidadDeCientos == 2){
	                    if(cantidadDeCientos == 1) precioResultado = precioMillones + ".0" + precioMiles + ".00" + precioCientos;
	                    else if(cantidadDeCientos == 2) precioResultado = precioMillones + ".0" + precioMiles + ".0" + precioCientos;
	                    else precioResultado = precioMillones + ".0" + precioMiles + "." + precioCientos;
	                }else{
	                    if(cantidadDeCientos == 1) precioResultado = precioMillones + "." + precioMiles + ".00" + precioCientos;
	                    else if(cantidadDeCientos == 2) precioResultado = precioMillones + "." + precioMiles + ".0" + precioCientos;
	                    else precioResultado = precioMillones + "." + precioMiles + "." + precioCientos;
	                }
	            }
	        }
	        return precioResultado;
	    }

	function ingresaPrecios(modelo, year){
		//var urlAbsoluta = "<?php echo url_for('main/priceJson')?>"+"?modelo=" + modelo;
		var urlAbsoluta = "<?php echo url_for('main/priceJson')?>"+"?modelo=" + modelo + "&year=" + year;

		$(".img_loader").fadeIn("hide");
		$('.precioPorHora').text("");
		$('.precioPorDia').text("");
		$('.precioPorHora').text("Calculando");
		$('.precioPorDia').text("Calculando");
		$.ajax({
			url: urlAbsoluta,
			dataType:'json'

		}).done(function(datos) {
		// Se ejecuta antes de terminar, si todo esta OK
			//$('div').html(datos.nombre);
			var valorHora = datos.valorHora;
			var valorDia = valorHora*6;

			if(valorHora<4000){
				valorHora = 4000;
			}
			$('.resultados').fadeIn("hide");
			$('.precioPorHora').text("");
			$('.precioPorDia').text("");
			//establece el valor de los inputs valor dia y valor hora
			var valorHoraEdit = "<span >$"+formatNumber(valorHora)+" CLP</span>";
			var valorDiaEdit = "<span >$"+formatNumber(valorDia)+" CLP</span>";
			$('.precioPorHora').append(valorHoraEdit);
			$('.precioPorDia').append(valorDiaEdit);


			$(".img_loader").fadeOut("hide");
		}).fail(function(jqXHR, textStatus) {
		// Se ejecuta antes de terminar, si ocurre un error
			$(".img_loader").fadeOut("hide");
			alert(textStatus);
		});

	}
	/*
	//da error
	(function( $ ){
		jQuery.fn.correo=function(){
			if(/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test($(this).val())){
				return true;
			}else{
				$(this).focus();
				return false;
			}
		};
	})( jQuery );
	*/
	 $(document).on('ready',function() {
			      
			getModel($("#brand"));
			 
			$("#brand").change(function(){
				getModel($(this)); 
			});
			
			
			
		$("#botonCalcular").click(function() {
			 var model = encodeURIComponent(document.getElementById("model").value);
			 var year = encodeURIComponent(document.getElementById("year").value);
			  	      
			 ingresaPrecios(model,year);

		});
			
	});


	function getModel(currentElement){
		
	     $("#model").html("");
		
	     $.getJSON("<?php echo url_for('main/getModel')?>",{ marca:currentElement.val(), ajax: 'true'}, function(j){
	      var options = '';
	      
	      for (var i = 0; i < j.length; i++) {
	      	var selected = ""; 
	        <?php if($selectedModel->getId()!=""){ ?>
			if(j[i].optionValue == <?php echo $selectedModel->getId()?>)
				selected = 'selected="selected"';
	      	<?php } ?>
	        options += '<option value="' + j[i].optionValue + '" ' + selected + ' >' + j[i].optionDisplay + '</option>';
	      }
	      $("#model").html(options);
	    })
	}

	</script>
