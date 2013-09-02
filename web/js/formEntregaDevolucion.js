$(document).on('ready',function(){

	if(cargarHora && isPropietario){
		mostrarHoraActual();		
	}

	$('#horaLlegadaDevolucion').on('focusout',function(){

		if($('#horaLlegadaDevolucion').attr('readonly')){
			return;
		}

		if(horaCorrecta()){
			var horaDevolucion = $('#horaLlegadaDevolucion').val();

			if(confirm('Es correcta la hora de devolución del vehículo: '+horaDevolucion+' (no se puede volver a ingresar)')){
				
				//almacenar en la base de datos
				$.ajax({
					type:'post',
					url: urlFormularioEntregaAjax,
					data:{
						opcion:'horaLlegadaPropietario',
						horaLlegada: horaDevolucion,
						idReserve: idReserve
					}
				}).done(function(data){
					alert('La hora de llegada ha sido registrada');
					$('.horaLlegadaDevolucion').removeClass('textoRemarcado');
					$('#horaLlegadaDevolucion').attr('readonly','true');
					verificarTermino();
				}).fail(function(){
					alert('Ha ocurrido un error al registrar la hora de llegada, inténtelo nuevamente');
				});
			}
		}
	});

	$('#botonPropietarioDocumentos').on('click',function(){
		if(confirm('¿La documentación presentada por el arrendatario es correcta?. De ser así presione "Aceptar" (no se puede volver a verificar)')){
			
			//ingresar a la base de datos
			$.ajax({
				type:'post',
				url: urlFormularioEntregaAjax,
				data:{
					opcion:'declaracionDocumentosPropietario',
						idReserve: idReserve
				}
			}).done(function(data){
				alert('Su declaración ha sido registrada');
				$('#nombrePropietarioDocumentos').removeClass('gris');
				$('#propietarioDocumentos').attr("disabled", true);
				$('#botonPropietarioDocumentos').attr("disabled", true);
				$('.documentosPropietario').addClass('bordeNegro');
				verificarTermino();
			}).fail(function(){
				alert('Ha ocurrido un error al registrar su declaración, inténtelo nuevamente');
			});

		}
	});

	$('#botonArrendatarioDocumentos').on('click',function(){
		if(confirm('¿La documentación presentada por el propietario es completa?. De ser así presione "Aceptar" (no se puede volver a verificar)')){
			
			//ingresar a la base de datos
			$.ajax({
				type:'post',
				url: urlFormularioEntregaAjax,
				data:{
					opcion:'declaracionDocumentosArrendatario',
						idReserve: idReserve
				}
			}).done(function(data){
				alert('Su declaración ha sido registrada');
				$('#nombreArrendatarioDocumentos').removeClass('gris');
				$('#arrendatarioDocumentos').attr("disabled", true);
				$('#botonArrendatarioDocumentos').attr("disabled", true);
				$('.documentosArrendatario').addClass('bordeNegro');
				verificarTermino();
			}).fail(function(){
				alert('Ha ocurrido un error al registrar su declaración, inténtelo nuevamente');
			});
		}
	});

	$('#botonArrendatarioDanios').on('click',function(){
		if(confirm('¿El vehículo no posee daños adicionales a los declarados?. De ser así presione "Aceptar" (no se puede volver a verificar)')){
			
			//ingresar a la base de datos
			$.ajax({
				type:'post',
				url: urlFormularioEntregaAjax,
				data:{
					opcion:'declaracionDaniosArrendatario',
						idReserve: idReserve
				}
			}).done(function(data){
				alert('Su declaración ha sido registrada');
				$('#nombreArrendatarioDanios').removeClass('gris');
				$('#arrendatarioDanios').attr("disabled", true);
				$('#botonArrendatarioDanios').attr("disabled", true);
				$('#declaracion1').addClass('bordeGris');
				verificarTermino();
			}).fail(function(){
				alert('Ha ocurrido un error al registrar su declaración, inténtelo nuevamente');
			});
		}
	});

	$('#botonPropietarioDevolucion').on('click',function(){
		if(confirm('¿El vehículo no posee daños adicionales a como fue entregado?. De ser así presione "Aceptar" (no se puede volver a verificar)')){
			
			//ingresar a la base de datos
			$.ajax({
				type:'post',
				url: urlFormularioEntregaAjax,
				data:{
					opcion:'declaracionDevolucionPropietario',
						idReserve: idReserve
				}
			}).done(function(data){
				alert('Su declaración ha sido registrada');
				$('#nombrePropietarioDevolucion').removeClass('gris');
				$('#propietarioDevolucion').attr("disabled", true);
				$('#botonPropietarioDevolucion').attr("disabled", true);
				$('#declaracion2').addClass('bordeGris');
				verificarTermino();
			}).fail(function(){
				alert('Ha ocurrido un error al registrar su declaración, inténtelo nuevamente');
			});
		}
	});

	$('#kmEntrega').on('focusout',function(){

		if($('#kmEntrega').attr('readonly')){
			return;
		}

		var kmEntrega = $('#kmEntrega').val();

		//quita puntos al valor ingresado
		while(kmEntrega.toString().indexOf('.') != -1){
			kmEntrega = kmEntrega.toString().replace('.','');
		}

		if(kmEntrega!=""&& !isNaN(kmEntrega)){
			if(confirm('Es correcto el kilometraje inicial: '+kmEntrega+' (no se puede volver a ingresar)')){
				
				//ingresar a la base de datos
				$.ajax({
					type:'post',
					url: urlFormularioEntregaAjax,
					data:{
						opcion:'kilometrajeInicialArrendatario',
						kmInicial: kmEntrega,
						idReserve: idReserve
					}
				}).done(function(data){
					alert('El kilometraje ha sido registrado');
					$('.kilometros').removeClass('textoRemarcado');
					$('#kmEntrega').val(kmEntrega);
					$('#kmEntrega').attr('readonly','true');
					verificarTermino();
				}).fail(function(){
					alert('Ha ocurrido un error al registrar el kilometraje, inténtelo nuevamente');
				});

			}
		}else{
			alert('Debe ingresar un valor válido para el kilometraje');
			$('#kmEntrega').val('');
		}

		
	});

	$('#kmEntregaDevolucion').on('focusout',function(){

		if($('#kmEntregaDevolucion').attr('readonly')){
			return;
		}

		var kmEntrega = $('#kmEntregaDevolucion').val();

		//quita puntos al valor ingresado
		while(kmEntrega.toString().indexOf('.') != -1){
			kmEntrega = kmEntrega.toString().replace('.','');
		}

		if(kmEntrega!=""&& !isNaN(kmEntrega)){
			if(confirm('Es correcto el kilometraje final: '+kmEntrega+' (no se puede volver a ingresar)')){
				
				//ingresar a la base de datos
				$.ajax({
					type:'post',
					url: urlFormularioEntregaAjax,
					data:{
						opcion:'kilometrajeFinalPropietario',
						kmFinal: kmEntrega,
						idReserve: idReserve
					}
				}).done(function(data){
					alert('El kilometraje ha sido registrado');
					$('.kilometrosDevolucion').removeClass('textoRemarcado');
					$('#kmEntregaDevolucion').val(kmEntrega);
					$('#kmEntregaDevolucion').attr('readonly','true');
					verificarTermino();
				}).fail(function(){
					alert('Ha ocurrido un error al registrar el kilometraje, inténtelo nuevamente');
				});

			}
		}else{
			alert('Debe ingresar un valor válido para el kilometraje');
			$('#kmEntregaDevolucion').val('');
		}

		
	});

	function verificarTermino(){
		var termino = true;

		if(isPropietario){
			if(!$('#botonPropietarioDocumentos').attr("disabled")){
				termino = false;
			}
			if(!$('#kmEntregaDevolucion').attr('readonly')){
				termino = false;
			}
			if(!$('#horaLlegadaDevolucion').attr('readonly')){
				termino = false;
			}
			if(!$('#botonPropietarioDevolucion').attr("disabled")){
				termino = false;
			}
			
		}else{
			if(!$('#botonArrendatarioDocumentos').attr("disabled")){
				termino = false;
			}
			if(!$('#kmEntrega').attr('readonly')){
				termino = false;
			}
			if(!$('#botonArrendatarioDanios').attr("disabled")){
				termino = false;
			}			
		}

		if(termino){
			alert('Gracias por realizar el formulario de entrega y devolución');
			window.location.href=redireccion;
		}

	}

});

function mostrarHoraActual(){
	var fechaActual = new Date();
	var hora = fechaActual.getHours();
	var minutos = fechaActual.getMinutes();
	if((minutos).toString().length==1){
		minutos = "0"+minutos;
	}
	$('#horaLlegadaDevolucion').attr('placeholder',hora+':'+minutos);
}

function horaCorrecta(){
	var horaIngresada = $('#horaLlegadaDevolucion').val();
	if(horaIngresada != ""){

		//verifica el formato de entrada
		if(horaIngresada.indexOf(':')==-1){
			alert('Debe ingresar una hora correcta HH:MM');
			$('#horaLlegadaDevolucion').val('');
			return false;
		}

		horaIngresada = horaIngresada.split(':');
		var hora = horaIngresada[0];
		var minutos = horaIngresada[1];

		if(isNaN(hora) || isNaN(minutos)){
			alert('Debe ingresar una hora correcta HH:MM');
			$('#horaLlegadaDevolucion').val('');
			return false;
		}

		if(hora<0 || hora>23 || minutos<0 || minutos>59){
			alert('Debe ingresar una hora correcta HH:MM');
			$('#horaLlegadaDevolucion').val('');
			return false;
		}else{
			if((hora).toString().length==1){
				hora = "0"+hora;
			}
			if((minutos).toString().length==1){
				minutos = "0"+minutos;
			}
			$('#horaLlegadaDevolucion').val(hora+':'+minutos);
			return true;
		}
	}else{
		alert('Debe ingresar una hora correcta HH:MM');
		return false;
	}

}