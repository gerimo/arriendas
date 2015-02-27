<link href="/css/newDesign/mobile/recover.css" rel="stylesheet" type="text/css">

<div class="visible-xs space-50"></div>
<div class="row">
    <div class="col-md-offset-4 col-md-4">

        <div class="BCW" id="frm">
            <h1>Ingresa tu nueva contraseña</h1>

            <?php echo form_tag('main/doChangePSW', array('method'=>'post', 'id'=>'frm1')); ?>

                <div class="c1">
                    <label>Ingrese contraseña</label>
                    <input type="password" class="form-control" id="password" name="password" value="" onfocus="checkclear(this)"/>
                </div>

                <div class="c1">
                    <label>Ingrese nuevamente su contraseña</label>
                    <input type="Password" class="form-control" id="passwordAgain" name="passwordAgain" value="" onfocus="checkclear(this)"/>
                </div>

                <input type="hidden" id="hash" name="hash" value="<?=$hash?>" />
                <input type="hidden" id="id" name="id" value="<?=$id?>" />

            </form>
            <div class="visible-xs space-20"></div> 
            <div class="row">
                <div class="alert"></div>
                <div class="col-md-offset-7 col-md-5" style="padding: 0">
                    <button class="btn btn-a-primary btn-block" id="save" onclick="submitFrom()">Aceptar</button>
                </div>
            </div>

        </div>
        
        <div class="BCW" id="message" style="display: none">
            <p class="text-center"></p>
            <p class="text-center"><?php echo link_to("Volver al inicio","main/index"); ?> </p>
        </div>
    </div>
</div> 


<script>


function checkclear(what) {
    if(!what._haschanged) {
        what.value=''
    };
    what._haschanged=true;
}


function submitFrom() {
    var password      = $("#password").val();
    var passwordAgain = $("#passwordAgain").val();

    $(".alert").removeClass("alert-a-danger");
    $(".alert").removeClass("alert-a-success");

    if((password != passwordAgain) || (password == '' || passwordAgain == '')) {
        $(".alert").addClass("alert-a-danger");
        $(".alert").html("las password deben coincidir y no pueden ser campos vacíos");
    } else {
        $(".alert").addClass("alert-a-success");
        $(".alert").html("la password fué cambiada exitosamente");
        document.forms["frm1"].submit();
    }
}

function DoPassValidation() {
  var frm = document.forms["frm1"];
  if(frm.password.value != frm.passwordAgain.value) {
    sfm_show_error_msg('El password no coincide.',frm.password);
    return false;
  } else {
    return true;
  }
}



/*var frmvalidator = new Validator("frm1");
frmvalidator.EnableMsgsTogether();

frmvalidator.addValidation("password","req", "Ingrese su contraseña");
frmvalidator.addValidation("passwordAgain","req", "Vuelva a ingresar su contraseña");

frmvalidator.setAddnlValidationFunction(DoPassValidation);*/

 
</script>
