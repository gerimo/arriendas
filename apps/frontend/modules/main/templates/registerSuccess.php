
<?php use_stylesheet('registro.css') ?>
<?php use_stylesheet('comunes.css') ?>
<?php
$useragent=$_SERVER['HTTP_USER_AGENT'];
if(preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i',$useragent)||preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i',substr($useragent,0,4))){
  use_stylesheet('moviles.css');
}
?>
<?php use_javascript('validaRut.js') ?>

<style>
.comboReg{

width:333px;height:40px;
-moz-border-radius-bottomleft: 5px;
-moz-border-radius-bottomright: 5px;
-webkit-border-bottom-left-radius: 5px;
-webkit-border-bottom-right-radius: 5px;
border-bottom-left-radius: 5px;
border-bottom-right-radius: 5px;
-moz-border-radius-topleft: 5px;
-moz-border-radius-topright: 5px;
-webkit-border-top-right-radius: 5px;
-webkit-border-top-left-radius: 5px;
border-top-left-radius: 5px;
border-top-right-radius: 5px;

}
    .input {
        /*margin: 5px 0;*/
        background: white;
        float: left;
        clear: both;
    }
    .input span {
        position: absolute;
        padding: 12px;
        margin-left: 3px;
        color: #999;
        z-index:1;
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
    .c1 { 
        margin-bottom: 7px;
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

    input.inputerror { border: 1px solid #FF8003; background-color:#FFFADC;}
    select.inputerror { border: 1px solid #FF8003; }

select
{
    color: #999;
}
option
{
    color: #000;
}
option:first-child
{
    color: #999;
}
</style>

<script type="text/javascript">
	 function changeMe(sel)
    {
      sel.style.color = "#000";              
    }

    $(document).ready(function() 
    { 

	
        $( ".datepicker" ).datepicker({
            dateFormat: 'dd-mm-yy',
            buttonImageOnly: true,
            changeMonth: true,
            changeYear: true,
            yearRange: ' 1920 : * ',
            onSelect: function(dateText, inst) { 
        
        
                var input = $(this);
                var def = input.attr('title');
                if (!input.val() || (input.val() == def)) {
                    input.prev('span').css('visibility', '');
                    if (def) {
                        var dummy = $('<label></label>').text(def).css('visibility','hidden').appendTo('body');
                        input.prev('span').css('margin-left', dummy.width() + 3 + 'px');
                        dummy.remove();
                    }
                } else {
                    input.prev('span').css('visibility', 'hidden');
                }
      
        
        
        
				}
        });
  

        function imageUpload(form, formfile, preview, link)
        {

            $(formfile).live('change', function()   
            { 
                $(preview).html('');
                $(preview).html('<?=image_tag("loader.gif")?>');
                $(form).ajaxForm(
                {
                    target: preview
                }).submit();
            });
        
            $(link).click(function(event) {
                $(formfile).click();
            });

        }       

        var i=1;
        imageUpload('#formmain', '#filemain', '#previewmain','#linkmain');

        imageUpload('#formlicence', '#filelicence', '#previewlicence','#linklicence');
        
        imageUpload('#formrut', '#filerut', '#previewrut','#linkrut');
        //capture selected filename
        //$('#logo').change(function(click) {
        //$('#text-logo').val(this.value);
        //});
    
    

        function toggleLabel() {
            var input = $(this);
            setTimeout(function() {
                var def = input.attr('title');
                if (!input.val() || (input.val() == def)) {
                    input.prev('span').css('visibility', '');
                    if (def) {
                        var dummy = $('<label></label>').text(def).css('visibility','hidden').appendTo('body');
                        input.prev('span').css('margin-left', dummy.width() + 3 + 'px');
                        dummy.remove();
                    }
                } else {
                    input.prev('span').css('visibility', 'hidden');
                }
            }, 0);
        };

        function resetField() {
            var def = $(this).attr('title');
            if (!$(this).val() || ($(this).val() == def)) {
                $(this).val(def);
                $(this).prev('span').css('visibility', '');
            }
        };

        $('input, textarea').live('change', toggleLabel);
        $('input, textarea').live('keydown', toggleLabel);
        $('input, textarea').live('paste', toggleLabel);
        $('select').live('change', toggleLabel);
        $('select').live('change', toggleLabel);

        $('input, textarea').live('focusin', function() {
            $(this).prev('span').css('color', '#ccc');
        });
    
    
        $('input, textarea').live('focusin', toggleLabel);
    
        $('input, textarea').live('focusout', function() {
            $(this).prev('span').css('color', '#999');
        });

        $(function() {
            $('input, textarea').each(function() { toggleLabel.call(this); });
        });

        /* carga las comunas de la región metropolitana por default */
        cargarComunas(13);


    });

    function cargarComunas(idRegion){
    
        $.ajax({
            method: 'POST' ,
            url: <?php echo "'" . url_for('main/getComunas') . "'"; ?>,
            data: 'idRegion=' + idRegion,
            success: function(data){
                $('#comunas').html(data);
            }
        });
    }
</script>

<!--
<div style="visibility:hidden;overflow:hidden;height:0px;">

    <form id="formlicence" method="post" enctype="multipart/form-data" action='<?php echo url_for('main/uploadLicence?photo=licence&width=194&height=204&file=filelicence') ?>'>
        <input type="file" name="filelicence" id="filelicence" />
        <input type="submit" />
    </form>


    <form id="formmain" method="post" enctype="multipart/form-data" action='<?php echo url_for('main/uploadPhoto?photo=main&width=194&height=204&file=filemain') ?>'>
        <input type="file" name="filemain" id="filemain" />
        <input type="submit" />
    </form>
    
    <form id="formrut" method="post" enctype="multipart/form-data" action='<?php echo url_for('main/uploadRut?photo=rut&width=194&height=204&file=filerut') ?>'>
        <input type="file" name="filerut" id="filerut" />
        <input type="submit" />
    </form>
    
</div>
-->

<?php echo form_tag('main/doRegister', array('method' => 'post', 'id' => 'frm1')); ?> 

<script>
    function submitFrom()
    {
        
        $('.inputerror').each(function() {
            
            $(this).removeClass('inputerror');
        })
        
        //Validaciones
        if( $("#firstname").val() == "" ) { $("#firstname").parent("label").find("span").text("* Falta ingresar Nombre"); }
        if( $("#lastname").val() == "" ) { $("#lastname").parent("label").find("span").text("* Falta ingresar Apellido"); }
        if( $("#email").val() == "" ) { $("#email").parent("label").find("span").text("* Falta ingresar Email"); }
        if( $("#emailAgain").val() == "" ) { $("#emailAgain").parent("label").find("span").text("* Falta Confirmar Email"); }
        if( $("#password").val() == "" ) { $("#password").parent("label").find("span").text("* Falta ingresar Contraseña"); }
 //       if( $("#passwordAgain").val() == "" ) { $("#passwordAgain").parent("label").find("span").text("* Falta Confirmar Contraseña"); }
//        if( $("#run").val() == "" ) { $("#run").parent("label").find("span").text("* Falta ingresar Rut"); }
//        if( $("#address").val() == "" ) { $("#address").parent("label").find("span").text("* Falta ingresar Dirección"); }
 //       if( $("#comunas").val() =='0') { $("#address").parent("label").find("span").text("* Falta ingresar Comuna"); }
        
        document.forms["frm1"].username.value = document.forms["frm1"].email.value;

        //alert("se va a enviar");
   //     DoRutValidation();

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

<div class="main_box_1_anterior" style="width:380px;margin-bottom: 30px;">
    <div class="main_box_2_anterior" style="width:380px;">
<div class="barraSuperior"><p>REGISTRO</p></div>
<div class="pasos" style="text-align: center;"> 
		<img src="/images/img_publica_auto/1.png"><img src="/images/img_publica_auto/2claro.png">	</div>

    <!-- sector usuario -->
	<!--
    <div class="regis_box_usuario">

        <div class="titulolteral">Foto perfil</div>

        <div class="regis_foto_frame">

            <div id="previewmain">


                <?= image_tag('img_registro/tmp_user_foto.png', 'size=194x204') ?>  

            </div> 

            <a href="#" id="linkmain"><?= image_tag('img_subi_tu_auto/upcar_ico_agrega_foto.png') ?></a>

        </div>
<!--
        <br/>
        <div class="titulolteral">Licencia de conducir</div>

        <div class="regis_foto_frame">

            <div id="previewlicence">


                <?= image_tag('img_registro/tmp_user_foto.png', 'size=194x204') ?>  

            </div> 

            <a href="#" id="linklicence"><?= image_tag('img_subi_tu_auto/upcar_ico_agrega_foto.png') ?></a>




        </div>

        <br/>
        <div class="titulolteral">Foto de Rut</div>

        <div class="regis_foto_frame">

            <div id="previewrut">


                <?= image_tag('img_registro/tmp_user_foto.png', 'size=194x204') ?>  

            </div> 

            <a href="#" id="linkrut"><?= image_tag('img_subi_tu_auto/upcar_ico_agrega_foto.png') ?></a>

        </div>
-->

        <!--  <h2 class="regis_usuario">Katia Dolizniak</h2>-->

        <!-- <ul class="regis_validar">
             <li>Validar direccion</li>
             <li>Validar direccion</li>
             <li>Validar direccion</li>
             <li>Validar direccion</li>        
         </ul>
         
        
    </div>
	-->

<!-- regis_box_usuario -->

    <!-- sector datos de usuario -->
    <div class="regis_box_info" style="width:380px;">     




        <div class="regis_formulario" style="width: 370px;">  

            <?php if ($sf_user->getFlash('show')): ?>

                <div style="border:1px solid #FF0000; background: #fcdfff;  width:360px; display:table;margin-bottom:20px; padding:20px;font-size:14px;">
                    <?php echo $sf_user->getFlash('msg'); ?>
                </div>
            <?php endif; ?>


            <div class="c1" style="display:none;">
                <label class="input">
                    <span>Nombre de usuario</span>
                    <input type="text" id="username" name="username"  onfocus="checkclear(this)" >
                </label>
            </div><!-- /c1 -->
            
            <div class="c1">
                <label class="input">
                    <span>Nombre</span>
                    <input id="firstname" name="firstname" type="text" onfocus="checkclear(this)" >
                </label>
            </div><!-- /c1 -->    

            <div class="c1">
                <label class="input">
                    <span>Apellido</span>
                    <input id="lastname" name="lastname" type="text"  onfocus="checkclear(this)" >
                </label>
            </div><!-- /c1 -->  

<!--            <div class="c1">
                <label class="input">
                    <span>Apellido Materno</span>
                    <input id="lastname_2" name="lastname_2" type="text"  onfocus="checkclear(this)" >
                </label>
            </div><!-- /c1 -->  
<!--           <div class="c1">
                <label class="input">
                    <span>Fecha de nacimiento</span>
                    <input type="text" name="" id="birth" name="birth" class="datepicker" onfocus="checkclear(this)" >
                </label> 
            </div><!-- /c1 -->
            
            <div class="c1">
                <label class="input">
                    <span>Email</span>
                    <input type="text" id="email" name="email" onfocus="checkclear(this)" >
                </label>
            </div><!-- /c1 -->    

            <div class="c1">
                <label class="input">
                    <span>Confirma tu Email</span>
                    <input type="text" id="emailAgain" name="emailAgain" onfocus="checkclear(this)" >
                </label>
            </div><!-- /c1 -->   
            
            <div class="c1">
                <label class="input">
                    <span>Contrase&ntilde;a</span>
                    <input id="password" name="password" type="password" onfocus="checkclear(this)" />
                </label>
                <br/>
            </div><!-- /c1 -->

<!--            <div class="c1">
                <label class="input">
                    <span>Confirma tu contrase&ntilde;a</span>
                    <input id="passwordAgain" name="passwordAgain" type="password" onfocus="checkclear(this)" />
                </label>
                <br/>
            </div><!-- /c1 -->

 <!--           <div class="c1">
                <label class="input">
                    <span>Rut</span>
                    <input type="text" id="run" name="run" onfocus="checkclear(this)" >
                </label>
            </div><!-- /c1 -->  

  <!--          <div class="c1">
                <label class="input">
                    <span>Serie Rut</span>
                    <input type="text" id="serie_run" name="serie_run" onfocus="checkclear(this)" >
                </label>
            </div><!-- /c1 -->  
            
     <!--       <div class="c1">
                <label class="input">
                    <span>Tel&eacute;fono</span>
                    <input type="text" name="telephone" id="telephone" onfocus="checkclear(this)" >
                </label>
            </div><!-- /c1 -->

       <!--     <div class="c1">
                <label class="input" style="width:282px;height:40px;">
                    <select onchange="changeMe(this)" name="como" id="como" class="comboReg">
                        <option value="0" selected disabled>¿Cómo escuchaste de Nosotros?</option>
						<option value="Google" >Google</option>
                    </select>
                </label>
            </div><!-- /c1 -->
            
    <!--        <div class="c1">
                <label class="input">
                    <span>Direcci&oacute;n #1111</span>
                    <input type="text" name="address" id="address" onfocus="checkclear(this)" style="width:566px;" >
                </label>
            </div><!-- /c1 -->

<!--            <div class="c1 combo">
                <label>
                    <select name="region" id="region" onChange="cargarComunas(this.value);changeMe(this)" class="comboReg">
                        <?php
                        foreach ($regiones as $r) {
                            if ($r["codigo"] == $userRegion) {
                                echo '<option value="' . $r["codigo"] . '" selected>' . $r["nombre"] . '</option>';
                            } else {
                                //marca por defaul la región metropolitana
                                if($r["codigo"] == 13){
                                    echo '<option value="' . $r["codigo"] . '" selected="selected">' . $r["nombre"] . '</option>';
                                }else{
                                    echo '<option value="' . $r["codigo"] . '">' . $r["nombre"] . '</option>';
                                }
                            }
                        }
                        ?>
                    </select>
                </label>
            </div><!-- /c1 -->
            
    <!--        <div class="c1 combo">
                <label class="input">
                    <select name="comunas" id="comunas" onchange="changeMe(this)" class="comboReg" >
                        <option value="0" selected disabled>---Comunas---</option>
                        <?php
                        foreach ($comunas as $c) {
                            if ($c["codigoInterno"] == $userComuna) {
                                echo '<option value="' . $c["codigoInterno"] . '" selected>' . $c["nombre"] . '</option>';
                            } else {
                                echo '<option value="' . $c["codigoInterno"] . '">' . $c["nombre"] . '</option>';
                            }
                        }
                        ?>
                    </select>
                </label>
            </div><!-- /c1 -->
            
		<!--	<div style="clear: left;"><input type="checkbox" style="width:25px;" id="propietario" name="propietario"/>
                <span style="font-family: 'Arial';font-size:11px;">Deseo ofrecer mi auto en arriendo</span></div>    
-->
            <div class="regis_term" style="padding-top: 10px;width: 90%;">
                <p style="margin-top: 0px;line-height: 15px;">
				Al registrarme, declaro<br>aceptar los <a href="http://www.arriendas.cl/main/terminosycondiciones" target="_blank">t&eacute;rminos y condiciones</a></p>
                
        <div class="regis_botones" style="float: right;margin-top: 0px;width: 80px;">    
             
            <button class="arriendas_pink_btn arriendas_big_btn right" name="save" onclick="submitFrom()"> Siguiente </button>        

        </div>

            </div>

        </div><!-- regis_formulario -->



    </div><!-- regis_box_info -->


    <div class="clear"></div>
</div><!-- /regis_box -->
</div>

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

    function DoRutValidation(){
        
        var frm = document.forms["frm1"];
        var rut = frm.run.value;
        //alert(rut);
        
        if($.validaRut(rut)){ //rut correcto
            $('#run').removeClass('inputerror');
        }else{ //rut incorrecto
            $('#run').addClass('inputerror');
        }
        
    }

    var frmvalidator = new Validator("frm1");
 
    frmvalidator.EnableMsgsTogether();
 
    //frmvalidator.addValidation("username","req","Ingrese un nombre de usuario");
    frmvalidator.addValidation("firstname","req","Ingrese su nombre");
    frmvalidator.addValidation("lastname","req","Ingrese su apellido");
//    frmvalidator.addValidation("telephone","numeric","El Telefono Debe ser numerico");

    frmvalidator.addValidation("password","req", "Ingrese su contraseña");
//    frmvalidator.addValidation("passwordAgain","req", "Vuelva a ingresar su contraseña");
 
    frmvalidator.addValidation("email","maxlen=50");
    frmvalidator.addValidation("email","req", "Ingrese un correo electronico");
    frmvalidator.addValidation("email","email", "Ingresa una direccion de correo valida");
 
    frmvalidator.addValidation("emailAgain","maxlen=50");
    frmvalidator.addValidation("emailAgain","req", "Vuelva a ingresar el correo electronico");
    frmvalidator.addValidation("emailAgain","email", "Ingresa una direccion de correo valida");
    
    //frmvalidator.addValidation("run","req", "Ingrese Rut");
    //frmvalidator.addValidation("run","rut", "Ingrese un Rut valido");
    
//    frmvalidator.addValidation("region","req", "Ingrese Region");
    //frmvalidator.addValidation("comunas","req", "Ingrese Comuna");
	
//    frmvalidator.addValidation("comunas","dontselect=0","Ingrese Comuna");	
	
  //  frmvalidator.addValidation("address","req", "Ingrese Direccion");
 
//    frmvalidator.addValidation("tandc","shouldselchk=on", "Acepte los terminos y condiciones");

//    frmvalidator.setAddnlValidationFunction(DoPassValidation);
    frmvalidator.setAddnlValidationFunction(DoEmailValidation);
    //frmvalidator.setAddnlValidationFunction(DoRutValidation);

 
</script>