<link href="/css/newDesign/mobile/forgot.css" rel="stylesheet" type="text/css">

<div class="visible-xs space-50"></div>

<div class="group_form" style="height: 340px;">
	<?php echo form_tag('forgot_password_send', array('method'=>'post', 'id'=>'frm1')); ?>
	<div class="row">
	    <div class="col-md-offset-4 col-md-4">
	        <div class="BCW">
	           	<h1>Restauración de contraseña</h1>
	           	<h2>¿Olvidaste tu contraseña?</h2>
	            <span>Ingresa tu correo electrónico. Te enviaremos un link de acceso.</span>
	           	<input class="form-control" type="email" placeholder="Ingrese Email" id="email" name="email" value="Ingrese Email" onfocus="checkclear(this)"/>
	           	
	           	<div class="alert"></div>

	           	<div class="row">
	                <div class="col-md-offset-7 col-md-5" style="padding: 0">
	                    <button class="btn btn-a-primary btn-block" onclick="submitFrom" id="change">Conectar</button>
	                </div>	                
	            </div>
	            <div>
	            	<div>
	                	<?php if($sf_user->getFlash('show')): ?>
							<div class="msg" >
								<?php echo $sf_user->getFlash('msg'); ?>
							</div>
						<?php endif; ?>
	                </div>
	            </div>

	        </div>
	   	</div>
	</div>
</div>
<div class="space-50"></div>


<script>
	function submitFrom()
	{
		var p = document.forms["frm1"].submit();
		alert(p);
	}

	function checkclear(what){
	if(!what._haschanged){
	  what.value=''
	};
	what._haschanged=true;
	}
</script>



