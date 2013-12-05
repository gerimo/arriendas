<?php use_stylesheet('login.css') ?>
<script>
	function submitFrom() {
		document.forms["frm1"].submit();
	}

	function checkclear(what) {
		if (!what._haschanged) {
			what.value = ''
		};
		what._haschanged = true;
	}
</script>
<div class="group_form">
 <?php echo form_tag('main/doLogin', array('method' => 'post', 'id' => 'frm1')); ?>

<div class="login_box">
    <div class="login_box_somb_izq"></div>
    <div class="login_box_somb_dere"></div>
    <h1>Conectarse</h1>
	<?php if($sf_user->getFlash('show')): ?>
	<div class="error_log" >
		<?php echo html_entity_decode($sf_user -> getFlash('msg')); ?>
	</div>
	<?php endif; ?>
	
    <h2>Conectarse con Facebook</h2>
    <p>con un solo click</p>
    <div class="login_btn_fb">

<div id="social-register" class="registration_panel">
    <a href="<?php echo url_for("main/loginFacebook")?>" id="facebook-login"><?php echo image_tag('img_login/login_btn_fb') ?></a>
</div>

    </div>
    <h2 style="clear:both">Login con su cuenta de Arriendas.cl</h2>
    <p>si has creado una cuenta con nosotros</p>

   <div class="login_formulario">

        <div class="c1">
        <input type="text" id="username" name="username" value="E-mail" onfocus="checkclear(this)"/>
        </div>
        <div class="c1">
        <input type="Password" id="password" name="password" value="Password" onfocus="checkclear(this)"/>
        </div>
        <button class="login_btn_conectar" onclick="submitFrom()" >Conectarse</button>

        <div class="c2">
            <input type="checkbox" /> Recordarme
        </div>
        <div class="opt_reg">
	    <a href="<?php echo url_for('main/forgot')?>" class="login_olvidar">&iquest;Olvid&oacute; su clave?</a>
    	<a href="<?php echo url_for('main/register')?>" class="nouser">No eres usuario registrate</a>
		</div>
    </div>
</div>
</form>
</div>

