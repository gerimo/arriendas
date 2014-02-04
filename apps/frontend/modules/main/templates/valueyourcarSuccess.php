<?php use_stylesheet('registro.css') ?>
<?php use_stylesheet('comunes.css') ?>

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
		$('#resultados').fadeIn("hide");
		$('.precioPorHora').text("");
		$('.precioPorDia').text("");
		//establece el valor de los inputs valor dia y valor hora
		var valorHoraEdit = "<span style='color:#EC008C;font-weight: bold;'>$"+formatNumber(valorHora)+" CLP</span>";
		var valorDiaEdit = "<span style='color:#EC008C;font-weight: bold;'>$"+formatNumber(valorDia)+" CLP</span>";
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
<style type="text/css">
#FondoReferidos{
    margin: 0 auto;
    width: 920px;
}
#subFondo{
    background-color: white;
    float: left;
    width: 920px;
    padding: 20px;
    height: auto ! important;
    min-height: 200px;
    -webkit-box-shadow: 1px 1px 10px rgba(50, 50, 50, 0.4);
    -moz-box-shadow: 1px 1px 10px rgba(50, 50, 50, 0.4);
    box-shadow: 1px 1px 10px rgba(50, 50, 50, 0.4);
    padding-bottom: 40px;
}
#Enunciado{
    float: left;
    width: 840px;
    text-align: center;
    font-size: 17px;
    margin-left: 40px;
    font-style: italic;
    line-height: 20px;
    margin-top: 16px;
}
#botonCargar{
    width: 166px;
    margin: 0 auto;
    height: 74px;
    margin-top: 134px;
}
input#botonVolver{
    margin: 0px;
    border: 0px;
    padding: 0px;
    cursor: pointer;
    background-image: url("http://www.arriendas.cl/images/Home/BotonIngresa.png");
    background-color: white;
    width: 164px;
    height: 72px;
}
p.textoPie{
    float: left;
    text-align: left;
}
h1{
	margin: 0px 0px 0px 0px;
	border-bottom: none;
	font-size: 20px;
}
.regis_box_info {
	width: 450px;
	float: left;
	margin-left: 20px;
	padding-bottom: 20px;
}

.c1{
	float: left;
	margin-bottom: 0px;
	margin-right: 0px;
	margin-left: 10px;
	width: 425px;
	height: 80px;
}
.c1 label{
	font-size: 14px;
	margin-bottom: 2px;
	text-align: left;
}
.c1 .c2{
	margin-right: 15px;
}
.regis_botones {
	float: left;
	margin-top: 0px;
	width: 170px;
	height: 78px;
	margin-left: -6px;
}
input#botonCalcular{
	margin: 0px;
	padding: 0px;
	border: 0px;
	cursor: pointer;
	background-image: url("../../images/Home/Calcular.png");
	background-color: white;
    width: 146px;
    height: 67px;
}
#ajax_loader{
	float: left;
	margin-left: 30px;
	margin-top: 18px;
	width: 40px;
	height: 40px;
}
.img_loader{
	display: none;
	width: 40px;
	height: 40px;
}
#resultados{
	float: left;
	text-align: left;
	padding-left: 10px;
	width: 220px;
	height: 170px;
	padding-top: 30px;
	margin-left: 30px;
}
/*
.img_subeunauto{
	float: left;
	margin-top: 15px;
	margin-left: -17px;
}
*/
#contenedorPrincipal{
	float: left;
	width: 100%;
	height: 90px;
	border-bottom: 1px solid #d1d1d1;
	font-size: 14px;
	margin-top: 15px;
}
	#contenedorPrincipal label.tituloValue{
		color: #05a4e7;
		text-align: center;
		font-style: italic;
		width: 100%;
	}
	#contenedorPrincipal input#inputPrecioAutoNuevo,
	#contenedorPrincipal select#selectRecibirReservas,
	#contenedorPrincipal select#selectDemoraEnContestar{
		margin: 0 auto;
		width: 115px;
		margin-top: 14px;
	}
		#contenedorPrincipal input#inputPrecioAutoNuevo{
			border: 1px solid rgb(146, 145, 145);
			padding: 2px;
		}
	#contenedorPrincipal #capaIzq{
		float: left;
		width: 34%;
		height: 86px;
	}
	#contenedorPrincipal #capaMedia{
		float: left;
		width: 33%;
		height: 86px;
	}
	#contenedorPrincipal #capaDer{
		float: left;
		width: 33%;
		height: 86px;
	}
#contenedorSecundario{
	float: left;
	width: 100%;
	height: 90px;
	margin-top: 25px;
	font-style: normal;
}
	#contenedorSecundario #mensajeResultado{
		width: 80%;
		margin: 0 auto;
		height: 30px;
		background-color: white;
	}
		#contenedorSecundario #mensajeResultado p{
			color: #05a4e7;
			text-align: center;
			font-weight: bold;
		}
		#contenedorSecundario #mensajeResultado p span#resultadoValor{
			color: #ec008c;
		}
	#contenedorSecundario #botonSubirAuto{
		width: 20%;
		margin: 0 auto;
		height: 30px;
		margin-top: 16px;
	}
		#contenedorSecundario #botonSubirAuto .img_subeunauto{
			background-color: white;
		}	
</style>
<div id="FondoReferidos">
    <div id="subFondo">
        <div id="Enunciado">
		<?php echo form_tag('main/doRegister', array('method'=>'post', 'id'=>'frm1'));?>
		<h1>Monetiza tu auto</h1>
			<div id="contenedorPrincipal">
				<div id="capaIzq">
					<label class="tituloValue">¿Cuál es el precio de tu auto en 0 Kms?</label>
					<input type="text" name="precioAutoNuevo" id="inputPrecioAutoNuevo" />
				</div>
				<div id="capaMedia">
					<label class="tituloValue">¿Cuando puedes recibir reservas?</label>
					<select name="recibirReservas" id="selectRecibirReservas">
						<option value="1" selected>Todos los días</option>
						<option value="2">Fines de semana</option>
						<option value="3">Lunes a viernes</option>
					</select>
				</div>
				<div id="capaDer">
					<label class="tituloValue">¿Cuánto te demoras en contestar?</label>
					<select name="demoraEnContestar" id="selectDemoraEnContestar">
						<option value="1" selected>En el día</option>
						<option value="2">+ 1 día</option>
						<option value="3">+ 3 días</option>
					</select>
				</div>
			</div>
			<div id="contenedorSecundario">
				<div id="mensajeResultado"><p>GANA <span id="resultadoValor">$XXXXX</span> AL MES ARRENDANDO ESTE AUTO</p></div>
				<div id="botonSubirAuto">
					<a href="<?php echo url_for('profile/addCar') ?>" title="Sube un nuevo auto"><?php echo image_tag('img_mis_autos/Subeunauto.png', 'class=img_subeunauto');?></a>
				</div>
			</div>
		<!--
		<!-- sector datos de usuario --
		<div class="regis_box_info"> 
		    <div class="regis_formulario">  
		   	<div class="c1">
		   		<div class="c2">
		        	<label>Marca del auto:</label>
						<select name="brand" id="brand">
					<?php foreach ($marcas as $m): ?>
							<option value="<?php echo $m->getMarca() ?>" ><?php echo $m->getMarca() ?></option> 		
					<?php endforeach; ?>
						</select>
		   		</div><!-- /c2 --
		   		<div class="c2">
		        	<label>Modelo del auto:</label>
						<select name="model" id="model">
						</select>
		   		</div><!-- /c2 --
		   		<div class="c2">
		        	<label>A&ntilde;o</label>
						<select name="year" id="year">
					<?php for($i = 2014; $i >= 1996 ;$i--): ?>
						<option value="<?php echo $i?>" ><?php echo $i?></option> 		
					<?php endfor;?>
						</select>
		   		</div><!-- /c2 --
		    </div><!-- /c1 --
		    </div><!-- regis_formulario --

		    <div class="regis_botones">     
		    	<input  id="botonCalcular" type="button" value=""/>
		    </div><!-- regis_botones --
		    <div id="ajax_loader">
		    	<?php echo image_tag('ajax-loader.gif', 'class=img_loader');?>
		    </div>
		</div><!-- regis_box_info --
		</form>
		-->
		<!--<div id="resultados" style="display:none;">
			<p style="margin-bottom: 10px;font-size: 17px;padding-bottom: 2px;border-bottom: 1px solid #BCBEB0;">Precios Sugeridos:</p>
			<p style="font-size: 16px">Por hora: <span style="color:#BCBEB0;" class="precioPorHora"></span></p>
			<p style="font-size: 16px">Por día: <span style="color:#BCBEB0;" class="precioPorDia"></span></p>
			<a href="<?php echo url_for('profile/addCar') ?>" title="Sube un nuevo auto"><?php echo image_tag('img_mis_autos/Subeunauto.png', 'class=img_subeunauto');?></a>
		</div>-->
		<div class="clear"></div>
	</div>
    <br /><br />
    </div>
</div> 
