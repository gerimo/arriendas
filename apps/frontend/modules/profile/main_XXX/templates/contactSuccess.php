<?php use_stylesheet('calendario.css') ?>
<?php use_stylesheet('registro.css') ?>
<?php use_stylesheet('mis_arriendos.css') ?>
<style>

    .input {
        margin: 5px 0;
        background: white;
        float: left;
        clear: both;
    }
    .input span {
        position: absolute;
        padding: 12px;
        margin-left: 3px;
        color: #999;
    }
    .input input, .input textarea, .input select {
        position: relative;
        margin: 0;
        border-width: 1px;

        background: transparent; 
        font: inherit;
    }
    .captcha{
        clear: both;
    }
    



    /* Hack to remove Safari's extra padding. Remove if you don't care about pixel-perfection. */
    @media screen and (-webkit-min-device-pixel-ratio:0) {
        .input input, .input textarea, .input select { padding: 4px; }
    }

    .input input 
    {
        padding-left: 12px;
    }

    #frm1 
    {
        margin:auto;
        display:table;

    }


    /* css for timepicker */
    .ui-timepicker-div .ui-widget-header { margin-bottom: 8px; }
    .ui-timepicker-div dl { text-align: left; }
    .ui-timepicker-div dl dt { height: 25px; margin-bottom: -25px; }
    .ui-timepicker-div dl dd { margin: 0 10px 10px 65px; }
    .ui-timepicker-div td { font-size: 90%; }
    .ui-tpicker-grid-label { background: none; border: none; margin: 0; padding: 0; }


    .ui-widget { font-family: Lucida Grande, Lucida Sans, Arial, sans-serif; font-size: 0.8em; }

    .titulolteral {
        font-size: 20px;
        color: #333;
        font-family: Arial, Helvetica, sans-serif;
        padding-bottom: 5px;
        float: left;
        border-bottom: 1px solid #E8E8E8;
        margin-top: 20px;
        margin-left: 20px;
        width:200px;
    }

</style>


<?php echo form_tag('main/doContacto', array('method' => 'post', 'id' => 'frm1')); ?>

<script>
    function submitFrom()
    {
		document.forms["frm1"].username.value = document.forms["frm1"].email.value;

        if(document.myform.onsubmit())
        {
        	
        	
        	document.forms["frm1"].submit();
        }
    }

    function checkclear(what){
        if(!what._haschanged){
            what.value=''
        };
        what._haschanged=true;
    }
</script>
<div class="main_box_1">
    <div class="main_box_2">


        <div class="main_col_izq">
            <?php include_component('profile', 'profile') ?>

        </div><!-- main_col_izq -->

        <!--  contenido de la seccion -->
        <div class="main_contenido">
            <h1 class="calen_titulo_seccion">Contacto</span></h1>     
            
                <p class="mensaje_alerta">Formulario de contacto en construcción, disculpe las molestias, por mietras nos puedes contactar al mail soporte@arriendas.cl</p>
                <p class="gracias">Gracias!</p>
            

        </div><!-- main_box_2 -->
        <div class="clear"></div>
    </div><!-- main_box_1 -->
</div>





<!-- <div class="regis_box">
    <div class="regis_box_somb_izq"></div>
    <div class="regis_box_somb_dere"></div>
    <h1>Contacto</h1>
	<div style="height: 500px; margin-left: auto; margin-right: auto; width: 80%; margin-top: 100px;">
		<p>Sitio en construcción temporalmente</p>
	</div>

    <div class="clear"></div>
</div>/regis_box -->


</form>

<script>

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

    function DoEmailValidation()
    {
        var frm = document.forms["frm1"];
        if(frm.email.value != frm.emailAgain.value)
        {
            sfm_show_error_msg('El correo electronico no coincide.',frm.email);
            return false;
        }
        else
        {
            return true;
        }
    }

    var frmvalidator = new Validator("frm1");
 
    frmvalidator.EnableMsgsTogether();
 
    //frmvalidator.addValidation("username","req","Ingrese un nombre de usuario");
    frmvalidator.addValidation("firstname","req","Ingrese su nombre");
    frmvalidator.addValidation("lastname","req","Ingrese su apellido");
    frmvalidator.addValidation("telephone","numeric","El Telefono Debe ser numerico");

    frmvalidator.addValidation("password","req", "Ingrese su contraseña");
    frmvalidator.addValidation("passwordAgain","req", "Vuelva a ingresar su contraseña");
 
    frmvalidator.addValidation("email","maxlen=50");
    frmvalidator.addValidation("email","req", "Ingrese un correo electronico");
    frmvalidator.addValidation("email","email", "Ingresa una direccion de correo valida");
 
    frmvalidator.addValidation("emailAgain","maxlen=50");
    frmvalidator.addValidation("emailAgain","req", "Vuelva a ingresar el correo electronico");
    frmvalidator.addValidation("emailAgain","email", "Ingresa una direccion de correo valida");
 
    frmvalidator.addValidation("tandc","shouldselchk=on", "Acepte los terminos y condiciones");

    frmvalidator.setAddnlValidationFunction(DoPassValidation);
    frmvalidator.setAddnlValidationFunction(DoEmailValidation);
 

 
</script>