<link href="/css/newDesign/messageRegister.css" rel="stylesheet" type="text/css">

<div class="hidden-xs space-100"></div>
<div class="visible-xs space-50"></div>    

<div class="row">
    <div class="col-md-offset-3 col-md-6">
      	<div class="BCW">
	    	<h1>¡Felicitaciones!</h1>
	    	<p class="text-center">Tu cuenta a sido activada, ya puedes ingresar con tu nombre de usuario y contraseña. </p>
	    	<br>
	    	<p class="hacer text-center">
              <b>¿Qué quieres hacer ahora?</b>
	    	</p>
	    	<br>
	    	<div class="row hidden-xs" >
		    	<button class="col-md-5 btn btn-a-action" onclick="location.href='<?php echo url_for('homepage') ?>'">Quiero Arrendar un Auto</button>
		        <button class="col-md-offset-3 col-md-4 btn btn-a-action" onclick="location.href='<?php echo url_for('car_create') ?>'">Quero Subir mi Auto</button>
      		</div>
      		<div class="row visible-xs">
      			<button class="col-md-5 btn btn-block btn-a-action" onclick="location.href='<?php echo url_for('homepage') ?>'">Quiero Arrendar un Auto</button>
      		</div>
      		<br>
      		<div class="row visible-xs">
      			<button class="col-md-offset-3 col-md-4 btn-block btn btn-a-action" onclick="location.href='<?php echo url_for('car_create') ?>'">Quero Subir mi Auto</button>
      		</div>
      	</div>
    </div>
</div>
<div class="hidden-xs space-100"></div>

