<?php use_stylesheet('login.css') ?>
<style>
.login_box_somb_izq { height:225px;}
.login_box_somb_dere { height:225px;}
</style>
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
<div class="group_form">
 <?php echo form_tag('main/doRecover', array('method'=>'post', 'id'=>'frm1')); ?>

<div class="login_box">
    <div class="login_box_somb_izq"></div>
    <div class="login_box_somb_dere"></div>
	<br/><br/>
    <h2>Se olvido su contraseña?</h2>
    <p>Ingrese su correo elecctronico y le enviaremos un link para que puede acceder.</p>
   	<div class="login_formulario">
    	<!-- form -->
	        <div class="c1">
	        	<input type="text" id="email" name="email" value="Ingrese email"onfocus="checkclear(this)"/>
	        </div>
	        	<button class="login_btn_conectar" onclick="submitFrom()" >Conectarse</button>
			<?php if($sf_user->getFlash('show')): ?>
				<div style="border:1px solid #FF0000; background: #fcdfff; 	width:260px; display:table;margin-bottom:20px; padding:20px;font-size:14px;">
					<?php echo $sf_user->getFlash('msg'); ?>
				</div>
			<?php endif; ?>
    	<!-- /form -->
    </div>
</div>

</form>
</div>


