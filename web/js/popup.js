$(document).ready(function() {

	var alto = document.body.offsetHeight;
	$('.overlay-container').css({height:alto});
	
	$('.detallereserva').click(function(event) {
		event.preventDefault();
		
		type = $(this).attr('data-type');
		
		$('.overlay-container').fadeIn(function() {
			
			window.setTimeout(function(){
				$('.window-container.'+type).addClass('window-container-visible');
			}, 100);
			
		});
	});
	
	$('.close').click(function() {
		$('.overlay-container').fadeOut().end().find('.window-container').removeClass('window-container-visible');
	});
	
});