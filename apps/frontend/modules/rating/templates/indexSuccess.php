<link href="/css/newDesign/ratingIndex.css" rel="stylesheet" type="text/css">
<div class="hidden-xs space-100"></div>
<div class="row">
    <div class="col-md-offset-2 col-md-8">
        <div class="BCW clearfix">
        	<h1>Reservas</h1>
        	<div class="content">

				<div class="col-md-4 rating-option">
					<a class="btn btn-a-action btn-block" onclick="swich(this)" id="button1">Pendientes</a>
				</div>
				                                     
				<div class="col-md-4 rating-option">
					<a class="btn btn-a-action btn-block" onclick="swich(this)" id="button2">Realizadas</a>
				</div>

				<div class="col-md-4 rating-option">
					<a class="btn btn-a-action btn-block" onclick="swich(this)" id="button3">Recibidas</a>
				</div>

			</div>

			<div class="hidden-xs space-80"></div>
        	<div id="content"></div>
        </div>
    </div>
</div>
<div class="hidden-xs space-100"></div>
<script>
	$(document).ready(function(){

		$.post("<?php echo url_for('rating/getPendingsList')?>", function(r){
            if (r.error) {
            	
            } else {
            	if(r.renterList || r.ownerList){
            		$("#content").html(""); 
	                $("#content").append(r.renterList); 
	                $("#content").append(r.ownerList);
	                replaceImg();      
	                swich($("#button1")); 
            	} else {
            		$.post("<?php echo url_for('rating/getHistoryList')?>", function(r){
			            if (r.error) {
			            	console.error(r.errorMessage);
			            } else {
			            	$("#content").html(""); 
			                $("#content").append(r.renterList); 
			                $("#content").append(r.ownerList);
			                replaceImg(); 
			                swich($("#button2"));             
			            }
			        }, 'json');
            	}
            	     
            }
        }, 'json');
		
		

		$("#button1").click(function(){
	    	$.post("<?php echo url_for('rating/getPendingsList')?>", function(r){
	            if (r.error) {
	            	console.error(r.errorMessage);
	            } else {
	            	$("#content").html(""); 
	                $("#content").append(r.renterList); 
	                $("#content").append(r.ownerList);
	                replaceImg();            
	            }
	        }, 'json');
		});

		$("#button2").click(function(){
	    	$.post("<?php echo url_for('rating/getHistoryList')?>", function(r){
	            if (r.error) {
	            	console.error(r.errorMessage);
	            } else {
	            	$("#content").html(""); 
	                $("#content").append(r.renterList); 
	                $("#content").append(r.ownerList);
	                doRaty();
	                replaceImg();            
	            }
	        }, 'json');
		});

		$("#button3").click(function(){
	    	$.post("<?php echo url_for('rating/getMyRatesList')?>", function(r){
	            if (r.error) {
	            	console.error(r.errorMessage);
	            } else {
	            	$("#content").html(""); 
	                $("#content").append(r.renterList); 
	                $("#content").append(r.ownerList);
	                doRaty();
	                replaceImg();    
	            }
	        }, 'json');
		});

    });

    function swich(e){
    	$('#button1').removeClass('turn-touched');
    	$('#button2').removeClass('turn-touched');
    	$('#button3').removeClass('turn-touched');

    	$('#button1').addClass('btn-a-action');
    	$('#button2').addClass('btn-a-action');
    	$('#button3').addClass('btn-a-action');

    	$(e).addClass('turn-touched');
    	$(e).addClass('turn-touched');
    }

    function doRaty(){
    	$(".history-rate").raty({
       		readOnly: true,
            score: function() {
                return $(this).attr('data-score');
            }
       	});
    }

    function replaceImg(){
		$('.car-r-image').error(function(){
	        $(this).attr('src', 'http://www.arriendas.cl/images/default.png');
		});
    }
</script>