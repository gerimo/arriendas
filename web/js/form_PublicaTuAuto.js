
$(document).on('ready',function(){

	$('#precioHora').on('focusout',function(){
		var precioHora = $('#precioHora').val();

		//quita puntos y comas
		precioHora = limpiarPrecio(precioHora);
		$('#precioHora').val(precioHora);

		if(precioHora!="" && precioHora<4000){
			alert("El valor mínimo por hora es $4.000.-");
			$('#precioHora').val('');
			$('#precioHora').focus();
		}
	});

	$('#precioDia').on('focusout',function(){
		var precioDia = $('#precioDia').val();
		precioDia = limpiarPrecio(precioDia);
		$('#precioDia').val(precioDia);
                /* por ahora no se valida mas el precio diario */
//		if(precioDia!="" && precioDia<18000){
//			alert("El valor mínimo por día es $18.000.-");
//			$('#precioDia').val('');
//			$('#precioDia').focus();
//		}
	});



	function limpiarPrecio(precioHora){
		precioHora = precioHora.replace('.','');
		precioHora = precioHora.replace('.','');
		precioHora = precioHora.replace(',','');
		precioHora = precioHora.replace('-','');
		precioHora = precioHora.replace('$','');
		return precioHora;
	}

	/* previsualizar foto */
	  function archivo1(evt) {
	      var files = evt.target.files; // FileList object
	 
	      // Obtenemos la imagen del campo "file".
	      for (var i = 0, f; f = files[i]; i++) {
	        //Solo admitimos imágenes.
	        if (!f.type.match('image.*')) {
	            continue;
	        }
	 
	        var reader = new FileReader();
	 
	        reader.onload = (function(theFile) {
	            return function(e) {
	              // Insertamos la imagen
	             $('#fotoPerfilAutoDestino').attr('src',e.target.result);
	             $('#fotoPerfilAutoDestino').addClass('imgCargada');
	             $('#fotoPerfilAutoDestino').removeClass('imgNoCargada');
	             $('#mejorFoto').fadeOut('fast');
	            };
	        })(f);
	 
	        reader.readAsDataURL(f);
	      }
	  }
	  function archivo2(evt) {
	      var files = evt.target.files; // FileList object
	 
	      // Obtenemos la imagen del campo "file".
	      for (var i = 0, f; f = files[i]; i++) {
	        //Solo admitimos imágenes.
	        if (!f.type.match('image.*')) {
	            continue;
	        }
	 
	        var reader = new FileReader();
	 
	        reader.onload = (function(theFile) {
	            return function(e) {
	              // Insertamos la imagen
	             $('#fotoPadronFrenteDestino').attr('src',e.target.result);
	             $('#fotoPadronFrenteDestino').removeClass('imgNoCargada');
	             $('#fotoPadronFrenteDestino').addClass('imgCargada');
	            };
	        })(f);
	 
	        reader.readAsDataURL(f);
	      }
	  }
	  function archivo3(evt) {
	      var files = evt.target.files; // FileList object
	 
	      // Obtenemos la imagen del campo "file".
	      for (var i = 0, f; f = files[i]; i++) {
	        //Solo admitimos imágenes.
	        if (!f.type.match('image.*')) {
	            continue;
	        }
	 
	        var reader = new FileReader();
	 
	        reader.onload = (function(theFile) {
	            return function(e) {
	              // Insertamos la imagen
	             $('#fotoPadronReversoDestino').attr('src',e.target.result);
	             $('#fotoPadronReversoDestino').removeClass('imgNoCargada');
	             $('#fotoPadronReversoDestino').addClass('imgCargada');
	            };
	        })(f);
	 
	        reader.readAsDataURL(f);
	      }
	  }
	
	/* al cambio de marca muestra distintas opciones de modelo */
	/*$("#brand").change(function(){
		getModel();
	});*/

	//alert(getModel($("#brand")));
	//anadirPunto('9030');



//$('#tengoDisponibilidadSemanal').change(function () {
//    if ($(this).attr("checked")) {
//		$("#siTengoDisponibilidadSemanal").show();
//        return;
//    }
//		$("#siTengoDisponibilidadSemanal").hide();
//		$("#precioSemana").value('');
//});


//$('#tengoDisponibilidadMensual').change(function () {
//    if ($(this).attr("checked")) {
//		$("#siTengoDisponibilidadMensual").show();
//        return;
//    }
//		$("#siTengoDisponibilidadMensual").hide();
//		$("#precioMes").value('');
//});

	$('#boton1').on('click',function(event){

		//alert($('#comuna').val());
		event.preventDefault();
		console.log('paso1');
		console.log($('#ubicacion').val());
		//verificar los datos ingresados
		if(verificaPaso1()){

			$('#paso1').fadeOut('fast',function(){

				var marca = $('#brand option:selected').html();
				var modelo = $('#model option:selected').html();
				var anio = $('#anio option:selected').html();

				//alert(modelo);

				$('.msjModelo').text(marca+", "+modelo+", "+anio);

				//calcular precio del vehículo
				ingresaPrecios(modelo, anio);

				$('#paso2').fadeIn('fast');	
			});
		}
	});

	$('#boton2').on('click',function(event){

		event.preventDefault();

		//verificar los datos ingresados
		if(verificaPaso2()){
			$('#paso2').fadeOut('fast',function(){
				$('#paso3').fadeIn('fast');	
			});
		}
	});

		$('#boton3').on('click',function(event){

		event.preventDefault();

		//verificar los datos ingresados
		if(verificaPaso3()){
			$('#paso3').fadeOut('fast',function(){
				$('#paso4').fadeIn('fast');	
			});
		}
	});

	
	
	$('#boton4').on('click',function(event){

		event.preventDefault();

		//verificar los datos ingresados
		if(verificaPaso4()){
			//envía el formulario
			//alert('entro');
			var url= $("#frm1").attr('action');
			url=url + "?lat=" + $('#lat').val() + "&lng=" + $('#lng').val() + "&urlFotoPerfil=" + urlFotoPerfil;
			$("#frm1").attr('action',url);
			$("#frm1").submit();
		}
		
	});

	//obtiene las coordenadas dada la dirección, a través de geocoder
	$('#ubicacion').focusout(function(){
		obtenerCoordenadas();
	});

	$('#comuna').focusout(function(){
		obtenerCoordenadas();
	});

	function obtenerCoordenadas(){
		//return true;
		var direccion = $('#ubicacion').val();
		var comuna = $('#comuna').val();

		if(direccion!="" && comuna!=""){

			var urlDireccion = 'http://maps.googleapis.com/maps/api/geocode/json?address='+direccion+' '+comuna+' chile&sensor=false';

			$.ajax({
	            dataType: 'json',  
	            url: urlDireccion,
		    	//timeout : 1000,
	            cache: false,
	        }).success(function(data){
	        	//alert(data);
            	if(data.status=='OK'){

            		var lat = data.results[0].geometry.location.lat;
            		var lng = data.results[0].geometry.location.lng;

	                //asigna el valor a input ocultos
	                $('#lat').val(lat);
	                $('#lng').val(lng);

	                //asigna la latitud y longitud a google maps
	                var src = 'http://maps.google.com/maps/api/staticmap?zoom=16&size=512x312&maptype=roadmap&markers=color:red|label:.|'+lat+','+lng+'&sensor=false';
	                $('#mapa').attr('src',src);
	                $("#confirmacionUbicacion").dialog("open");

            	}else{

            		//alert('error');
	                $("#errorUbicacion").dialog("open");

	                $('#ubicacion').val('');

            		//$('#lat').val('');
	                //$('#lng').val('');
            	}
	        }).fail(function(jqXHR, textStatus){
			//PArche para levantar el tema
			//CORREGIR
					$('#lat').val(-33);
	                $('#lng').val(-70);
	        });

		}
		console.log(direccion);
		console.log(comuna);
		console.log($('#lng').val());
		console.log($('#lat').val());
	}

	//http://maps.google.com/maps/api/staticmap?zoom=16&size=512x512&maptype=roadmap&markers=color:red|label:C|-33.4874866,-70.7368552&sensor=false

	$(function() {
        $( "#confirmacionUbicacion" ).dialog({
            resizable: false,
            width: 550,
            modal: true,
            autoOpen: false,
            closeOnEscape: false,
            position: { my: "center top", at: "center top"},
            buttons: {
                "Si": function() {
                    $( this ).dialog( "close" );
                },
                No: function() {
                	//borrar la dirección ingresada
                	$('#ubicacion').val('');
                	//borra el valor de los input ocultos
                	$('#lat').val('');
		            $('#lng').val('');
		            //borra la url del mapa de google
		            $('#mapa').attr('src','');
                	$('#ubicacion').focus();
                    $( this ).dialog( "close" );
                }
            }
        });
    });

    $(function() {
        $( "#errorUbicacion" ).dialog({
            resizable: false,
            width: 350,
            modal: true,
            autoOpen: false,
            closeOnEscape: true,
            buttons: {
                "Aceptar": function() {
                	$('#ubicacion').focus();
                    $( this ).dialog( "close" );
                }
            }
        });
    });

});

var urlFotoPerfil= "";

function uploaderReady(element, url) {
	//Dado que se recibe solo un upload por esta vía, lo escribimos en una variable global de Javascript
	urlFotoPerfil= url;
}

function verificaPaso1(){
	//var ubicacion = $('#ubicacion').val();
	var ubicacion="prueba";
	var comuna = $('#comuna').val();
	var marca = $('#brand').val();
	var modelo = $('#model').val();
	var anio = $('#anio').val();
	var puertas = $('#puertas').val();
	var transmision = $('#transmision').val();
	var tipoBencina = $('#tipoBencina').val();
	var usosVehiculo = $('#usosVehiculo').val();
	var lat = $('#lat').val();
	var lng = $('#lng').val();
	var patente = $('#patente').val();
	var color = $('#color').val();

	var camposIngresados = true;

	if(ubicacion == ""){
		//$('#ubicacionLabel').text('Ubicación del Vehículo(*)');
		$('#ubicacionLabel').addClass('faltaValor');
		camposIngresados = false;
	}else{
		//$('#ubicacionLabel').text('Ubicación del Vehículo');
		$('#ubicacionLabel').removeClass('faltaValor');
	}

	if(patente == ""){
		//$('#ubicacionLabel').text('Ubicación del Vehículo(*)');
		$('#patenteLabel').addClass('faltaValor');
		camposIngresados = false;
	}else{
		$('#patenteLabel').removeClass('faltaValor');
                $.ajax({
                    "url": urlCheckPatente + "?patente=" + patente + "&carid="+$("#idCar").val(),
                    "async" : false,
                    "success": function(data){
                        if(data.length <= 0 && data != "1" ){
                            camposIngresados = false;
                            alert("Ya ha subido este auto. Recién lo podrá ver publicado su auto una vez que lo haya visitado un inspector. Un inspector te llamará esta semana.");
                            window.location = urlListadoAutos;
                        }
                        
                    }
                });
	}

	if(color == ""){
		//$('#ubicacionLabel').text('Ubicación del Vehículo(*)');
		$('#colorLabel').addClass('faltaValor');
		var camposIngresados = false;
	}else{
		//$('#ubicacionLabel').text('Ubicación del Vehículo');
		$('#colorLabel').removeClass('faltaValor');
	}
	
	if(comuna == ""){
		//$('#comunaLabel').text('Comuna(*)')
		$('#comunaLabel').addClass('faltaValor');
		var camposIngresados = false;
	}else{
		//$('#comunaLabel').text('Comuna')
		$('#comunaLabel').removeClass('faltaValor');
	}
/*
	if(lat == "" || lng == ""){
		//$('#ubicacionLabel').text('Ubicación del Vehículo(*)');		
		$('#ubicacionLabel').addClass('faltaValor');
		var camposIngresados = false;
	}else{
		//$('#ubicacionLabel').text('Ubicación del Vehículo');
		$('#ubicacionLabel').removeClass('faltaValor');
	}
*/	
	if(marca == ""){
		//$('#marcaLabel').text('Marca(*)');
		$('#marcaLabel').addClass('faltaValor');
		var camposIngresados = false;
	}else{
		//$('#marcaLabel').text('Marca');
		$('#marcaLabel').removeClass('faltaValor');
	}

	if(modelo == null){
		//$('#modeloLabel').text('Modelo(*)');
		$('#modeloLabel').addClass('faltaValor');
		var camposIngresados = false;
	}else{
		//$('#modeloLabel').text('Modelo');
		$('#modeloLabel').removeClass('faltaValor');
	}

	if(anio == ""){
		//$('#anioLabel').text('Año(*)');
		$('#anioLabel').addClass('faltaValor');
		var camposIngresados = false;
	}else{
		//$('#anioLabel').text('Año');
		$('#anioLabel').removeClass('faltaValor');
	}

	if(puertas == ""){
		//$('#puertasLabel').text('Puertas(*)');
		$('#puertasLabel').addClass('faltaValor');
		var camposIngresados = false;
	}else{
		//$('#puertasLabel').text('Puertas');
		$('#puertasLabel').removeClass('faltaValor');
	}

	if(transmision == ""){
		//$('#transmisionLabel').text('Transmisión');
		$('#transmisionLabel').addClass('faltaValor');
		var camposIngresados = false;
	}else{
		//$('#transmisionLabel').text('Transmision(*)');
		$('#transmisionLabel').removeClass('faltaValor');
	}

	if(tipoBencina == ""){
		//$('#tipoBencinaLabel').text('Tipo de Bencina(*)');
		$('#tipoBencinaLabel').addClass('faltaValor');
		var camposIngresados = false;
	}else{
		//$('#tipoBencinaLabel').text('Tipo de Bencina');
		$('#tipoBencinaLabel').removeClass('faltaValor');
	}

	if(usosVehiculo == ""){
		//$('#usosVehiculoLabel').text('Usos del Vehículo(*)');
		$('#usosVehiculoLabel').addClass('faltaValor');
		var camposIngresados = false;
	}else{
		//$('#usosVehiculoLabel').text('Usos del Vehículo');
		$('#usosVehiculoLabel').removeClass('faltaValor');
	}

	if(camposIngresados){ //muestra mensaje de ingresar todos los datos
		$('.mensajeIngresoDatos1').fadeOut('fast');
	}else{
		$('.mensajeIngresoDatos1').fadeIn('fast');
	}

	return camposIngresados;

}

function verificaPaso2(){
	var precioDia = $('#precioDia').val();
	var precioHora = $('#precioHora').val();

	var camposIngresados = true;

	if(precioDia == "" || !isNum(precioDia)){
		$('#precioDiaLabel').addClass('faltaValor');
		var camposIngresados = false;
	}else{
		$('#precioDiaLabel').removeClass('faltaValor');
	}

	
	if(precioHora == "" || !isNum(precioHora)){
		$('#precioHoraLabel').addClass('faltaValor');
		var camposIngresados = false;
	}else{
		$('#precioHoraLabel').removeClass('faltaValor');
	}

	if(camposIngresados){ //muestra mensaje de ingresar todos los datos
		$('.mensajeIngresoDatos2').fadeOut('fast');
	}else{
		$('.mensajeIngresoDatos2').fadeIn('fast');
	}
	
	return camposIngresados;

}


function verificaPaso3(){
	var disponibilidad = $('#disponibilidad').val();

	var camposIngresados = true;

	if(disponibilidad == "" ){
		$('#disponibilidad').addClass('faltaValor');
		var camposIngresados = false;
	}else{
		$('#disponibilidad').removeClass('faltaValor');
	}

	

	if(camposIngresados){ //muestra mensaje de ingresar todos los datos
		$('.mensajeIngresoDatos3').fadeOut('fast');
	}else{
		$('.mensajeIngresoDatos3').fadeIn('fast');
	}
	
	return camposIngresados;

}



function verificaPaso4(){
	var fotoPerfilAuto = $('#fotoPerfilAuto').val();
	var fotoPadronFrente = $('#fotoPadronFrente').val();
	var fotoPadronReverso = $('#fotoPadronReverso').val();

	var urlPerfil = $('#urlPerfil').val();
	var urlPadronFrente = $('#urlPadronFrente').val();
	var urlPadronReverso = $('#urlPadronReverso').val();

	var camposIngresados = true;
	
	if(fotoPerfilAuto == "" && urlPerfil==""){
		$('#fotoPerfilLabel').addClass('faltaValor');
		var camposIngresados = false;
	}else{
		$('#fotoPerfilLabel').removeClass('faltaValor');
	}

	//foto de padron no son obligatorias
	/*
	if(fotoPadronFrente == "" && urlPadronFrente==""){
		$('#fotoPadronFrenteLabel').addClass('faltaValor');
		var camposIngresados = false;
	}else{
		$('#fotoPadronFrenteLabel').removeClass('faltaValor');
	}

	if(fotoPadronReverso == "" && urlPadronReverso==""){
		$('#fotoPadronReversoLabel').addClass('faltaValor');
		var camposIngresados = false;
	}else{
		$('#fotoPadronReversoLabel').removeClass('faltaValor');
	}
	*/
	if(camposIngresados){ //muestra mensaje de ingresar todos los datos
		$('.mensajeIngresoDatos4').fadeOut('fast');
	}else{
		$('.mensajeIngresoDatos4').fadeIn('fast');
	}

	return camposIngresados;

}

function isNum(numero){

	if(/^[0-9|.]+$/.test(numero)){
		return true
	}else{
		return false;
	}

}


function anadirPunto(numero){ // v2007-08-06

var decimales=0;
var separador_decimal=',';
var separador_miles='.';

    numero=parseFloat(numero);
    if(isNaN(numero)){
        return "";
    }

    if(decimales!==undefined){
        // Redondeamos
        numero=numero.toFixed(decimales);
    }

    // Convertimos el punto en separador_decimal
    numero=numero.toString().replace(".", separador_decimal!==undefined ? separador_decimal : ",");

    if(separador_miles){
        // Añadimos los separadores de miles
        var miles=new RegExp("(-?[0-9]+)([0-9]{3})");
        while(miles.test(numero)) {
            numero=numero.replace(miles, "$1" + separador_miles + "$2");
        }
    }

    return numero;
}

function anadirPunto5(x){
    x = x.toString();
var num = x.replace(/\./g,'');
if(!isNaN(num)){
num = num.toString().split('').reverse().join('').replace(/(?=\d*\.?)(\d{3})/g,'$1.');
num = num.split('').reverse().join('').replace(/^[\.]/,'');
    return num;
}
};


function anadirPunto3(x) {
    x = x.toString();
    var pattern = /(-?\d+)(\d{3})/;
    while (pattern.test(x))
        x = x.replace(pattern, "$1.$2");
    return x;
}

//agrega . a variable numérica
function anadirPunto2(valor){
	var resultado = "";
	//alert(valor);
	//alert(valor.toString().length);

	while(valor.toString().length>3){
		var resto = valor%1000;
		//alert(resto.toString().length);
		if(resto.toString().length==2){
			resto = '0'+resto;
		}
		if(resto.toString().length==1){
			resto = '00'+resto;
		}
		resultado = '.'+resto+resultado;
		//alert(valor);
		valor = valor.toString().substring(0,valor.toString().length-3);
		//alert(valor);
		
		//alert(valor.toString().length);
	}
	//alert(valor);
	resultado = valor+resultado;
	//alert(resultado);
	return resultado;
}


$(function() {
	/*
	$( "#comuna" ).autocomplete({
	    source: availableTags
	});
	*/


	var valores = [];
	$("#valor option").each(function(i, value) {
	    if (i > 0) {
	        valores[i-1] = $(value).text();
	    }
	});
	$("#comuna").autocomplete({
	    source: valores,
	    minLength: 0,
	    select: function(event, ui) {
	        var opcion = $("#valor option").filter(function(index) {
	            return $(this).text() == ui.item.label;
	        }).val();

	        //alert(opcion);
	        $("#valor option[value="+opcion+"]").attr("selected",true);
	        $('#comunaId').val(opcion);
	    }
	});

	$("#comuna").click(function() {
	    $("#comuna").select();
	    $("#comuna").autocomplete("search", "");
	    $("#comuna").focus();
	});
	/*
	$('#boton1').on('click',function(){
		alert($('#valor').val());
	});
	*/
	//inicializa un valor por defecto
	/*
	$("#valor option[value=hola]").attr("selected",true);
	$('#comuna').val('aaa');
	*/

});
