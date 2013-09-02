<?php use_stylesheet('login.css') ?>
<style>
.login_box_somb_izq { height:245px;}
.login_box_somb_dere { height:245px;}
</style>
<script>


function checkclear(what){
if(!what._haschanged){
  what.value=''
};
what._haschanged=true;
}

</script>

<div class="login_box">
    <div class="login_box_somb_izq">
    <div class="login_box_somb_dere">
    <div class='barraSuperior'>
    <p>Ingresa tu nueva contraseña</p>
    </div>

   <div class="login_formulario">
    <?php echo form_tag('main/doChangePSW', array('method'=>'post', 'id'=>'frm1')); ?>
        <div class="c1">
		<label>Ingrese contraseña</label><br/><br/>
        <input type="password" id="password" name="password" value=""onfocus="checkclear(this)"/>
        </div>

        <div class="c1">
		<label>Ingrese nuevamente su contraseña</label><br/><br/>
        <input type="Password" id="passwordAgain" name="passwordAgain" value="" onfocus="checkclear(this)"/>
        </div>

		<input type="hidden" id="hash" name="hash" value="<?=$hash?>" />
		<input type="hidden" id="email" name="email" value="<?=$email?>" />
    <button class="login_btn_conectar" onclick="submitFrom()" ></button>

    </form>

  </div>
  </div>

    </div><!-- /login_formulario -->
</div><!-- /login_box -->


<script>
function submitFrom()
{
    if(document.myform.onsubmit())
    {
        document.forms["frm1"].submit();
    }
}

function DoPassValidation()
{
  var frm = document.forms["frm1"];
  if(frm.password.value != frm.passwordAgain.value)
  {
    sfm_show_error_msg('El password no coincide.',frm.password);
    return false;
  }
  else
  {
    return true;
  }
}



 var frmvalidator = new Validator("frm1");
 
 frmvalidator.EnableMsgsTogether();
 

 frmvalidator.addValidation("password","req", "Ingrese su contraseña");
 frmvalidator.addValidation("passwordAgain","req", "Vuelva a ingresar su contraseña");
 
 frmvalidator.setAddnlValidationFunction(DoPassValidation);

 
</script>
