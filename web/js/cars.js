$(document).on('ready',function(){


	$('.eliminar').on('click',function(e){
		e.preventDefault();

		if(confirm('¿Desea eliminar el vehículo seleccionado?')){
			var id = $(this).attr('id');
			eliminarAuto(id);
		}
		
	});

});

function eliminarAuto(id){

	$('#item_'+id+' .cargando').show();

	$.ajax({
		type:'post',
		url: urlEliminarCarAjax,
		data:{
			idCar:id
		}
	}).done(function(){
		$('#item_'+id).hide();
		$('#item_'+id+' .cargando').hide();
	}).fail(function(){
		alert('Ha ocurrido un error al eliminar el vehículo, inténtelo nuevamente');
		$('#item_'+id+' .cargando').hide();
	});

}