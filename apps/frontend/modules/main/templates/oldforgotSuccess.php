<?php use_stylesheet('login.css') ?>
<script>
function submitFrom()
{
	document.forms["frm1"].submit();
}

function checkclear(what){
if(!what._haschanged){
  what.value=''
};
what._haschanged=true;
}
</script>
<div class="group_form" style="height: 340px;">
 <?php echo form_tag('main/doRecover', array('method'=>'post', 'id'=>'frm1')); ?>

	<div class="login_box" style="height:260px;">
		<div class="barraSuperior"><p>RESTAURACIÓN CONTRASEÑA</p></div>
	    <h2>¿Olvidaste tu contraseña?</h2>
	    <p style="text-align: center;font-size: 12px;">Ingresa tu correo electrónico. Te enviaremos un link de acceso.</p>
	   	<div class="login_formulario">
	    	<!-- form -->
		        <div class="c1">
		        	<input type="text" placeholder="Ingrese Email" id="email" name="email" value="Ingrese Email" onfocus="checkclear(this)"/>
		        </div>
		        	<button class="login_btn_conectar" onclick="submitFrom()" ></button>
	    	<!-- /form -->
	    	    <?php if($sf_user->getFlash('show')): ?>
					<div style="display:none;border: 1px solid #FF0000;background: #fcdfff;width: 240px;display: table;margin-bottom: 10px;padding: 10px;font-size: 12px;margin-top: 15px;float: left;margin-left: -12px;">
						<?php echo $sf_user->getFlash('msg'); ?>
					</div>
				<?php endif; ?>
	    </div>
	</div>

</form>
</div>


