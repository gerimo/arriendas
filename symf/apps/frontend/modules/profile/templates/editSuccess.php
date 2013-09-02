<?php use_stylesheet('registro.css') ?>
<?php use_stylesheet('comunes.css') ?>
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

</style>

<script type="text/javascript">

    $(document).ready(function() 
    { 
	
	$('#tandc').attr('checked',true);
                    
        $( ".datepicker" ).datepicker({
            dateFormat: 'yy-mm-dd', 
            buttonImageOnly: true,
            minDate:'-0d',
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
            $(preview).html('<?php echo image_tag('loader.gif') ?>');
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

});


</script>


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


<?php echo form_tag('profile/doUpdateProfile', array('method' => 'post', 'id' => 'frm1')); ?> 

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
    	if( $("#run").val() == "" ) { $("#run").parent("label").find("span").text("* Falta ingresar Rut"); }
    	if( $("#address").val() == "" ) { $("#address").parent("label").find("span").text("* Falta ingresar Dirección"); }
    	
        if(document.myform.onsubmit())
        {
            document.forms["frm1"].submit();
        }
    }
    
    $(function() {
    	
    	$("#frm1").submit(function() {
    		
			if( $("#password").val() != '' ) {
				
				if( $("#passwordAgain").val() == '' ) {
					
					alert("Debe confirmar la nueva contraseña");
					return false;
				}
				else if( $("#password").val() != $("#passwordAgain").val() ) {
					
					alert("La contraseña no coincide. Ingresela nuevamente");
					return false;					
				}
				else { return true; }
			}
			else return true;
    	}) 
    })

    function checkclear(what){
        if(!what._haschanged){
            what.value=''
        };
        what._haschanged=true;
    }

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

<div class="main_box_1">
<div class="main_box_2">
    <h1>Editar perfil</h1>

    <!-- sector usuario -->

    <div class="regis_box_usuario">

        <div class="titulolteral">Foto perfil</div>

        <div class="regis_foto_frame">

            <div id="previewmain">


                <?php if ($user->getPictureFile() == null): ?>

                    <?php echo image_tag('img_registro/tmp_user_foto.png', 'size=194x204') ?>  

                <?php else: ?>
                
                    <?php if($user->getFacebookId()!=null):?>
                        <?php echo image_tag($user->getPictureFile().'?type=large', 'size=194x204') ?>  
                    <?php else: ?>
                        <?php echo image_tag('users/'.$user->getFileName(), 'size=194x204') ?>  
                    <?php endif;?>

                <?php endif; ?>
            </div> 

            <a href="#" id="linkmain"><?php echo image_tag('img_subi_tu_auto/upcar_ico_agrega_foto.png') ?></a>
			<br />
			<span style="font-size: 12px">Dimensiones imagen 194x204 pix.</span>


        </div>
        <br/>
        <div class="titulolteral">Licencia de conducir</div>

        <div class="regis_foto_frame">

            <div id="previewlicence">

                <?php
                if ($user->getDriverLicenseFile() == null):
                    echo image_tag('img_registro/tmp_user_foto.png', 'size=194x204');
                else:
                    echo image_tag('licence/'.$user->getLicenceFileName(), 'size=194x204');
                endif;
                ?>

            </div> 

            <a href="#" id="linklicence"><?php echo image_tag('img_subi_tu_auto/upcar_ico_agrega_foto.png') ?></a>

			<br />
			<span style="font-size: 12px">Dimensiones imagen 194x204 pix.</span>

        </div>


       <br/>
        <div class="titulolteral">Foto de Carnet</div>

        <div class="regis_foto_frame">

            <div id="previewrut">

                <?php 
                if ($user->getRutFileName() == null):
                    image_tag('img_registro/tmp_user_foto.png', 'size=194x204');
                else:
                    echo image_tag('users/'.$user->getRutFileName(), 'size=194x204');
                endif;
                ?>
 

            </div> 

            <a href="#" id="linkrut"><?= image_tag('img_subi_tu_auto/upcar_ico_agrega_foto.png') ?></a>

			<br />
			<span style="font-size: 12px">Dimensiones imagen 194x204 pix.</span>


        </div>

        <!--  <h2 class="regis_usuario">Katia Dolizniak</h2>-->

        <!-- <ul class="regis_validar">
             <li>Validar direccion</li>
             <li>Validar direccion</li>
             <li>Validar direccion</li>
             <li>Validar direccion</li>        
         </ul>
         
        -->

    </div><!-- regis_box_usuario -->


    <!-- sector datos de usuario -->
    <div class="regis_box_info">     

        <div class="regis_titulo_1">Datos de usuario</div>



        <div class="regis_formulario">  

            <?php if ($sf_user->getFlash('show')): ?>

                <div style="border:1px solid #FF0000; background: #fcdfff; 	width:360px; display:table;margin-bottom:20px; padding:20px;font-size:14px;">
                    <?php echo $sf_user->getFlash('msg'); ?>
                </div>
            <?php endif; ?>

            <div class="c1" style="display:none;">
                <label class="input">
                    <span>Nombre de usuario</span>
                    <input type="text" id="username" name="username" value="<?php echo $user->getUsername(); ?>" >
                </label>
            </div><!-- /c1 -->

            <div class="c1">
                <label class="input">
                    <span>Nombre</span>
                    <input id="firstname" name="firstname" type="text" value="<?php if($user->getFirstName()) echo $user->getFirstName(); ?>" >
                </label>
            </div><!-- /c1 -->    

            <div class="c1">
                <label class="input">
                    <span>Apellido</span>
                    <input id="lastname" name="lastname" type="text" value="<?php if($user->getLastName()) echo $user->getLastName(); ?>" >
                </label>
            </div><!-- /c1 -->    
            
            <?php if ($user->getFacebookId() == null): ?>

                <div class="c1">
                    <label class="input">
                        <span>Nueva Contrase&ntilde;a</span>
                        <input id="password" name="password" type="password" />
                    </label>
                    <br/>
                </div><!-- /c1 -->

                <div class="c1">
                    <label class="input">
                        <span>Confirma tu contrase&ntilde;a</span>
                        <input id="passwordAgain" name="passwordAgain" type="password" />
                    </label>
                    <br/>
                </div><!-- /c1 -->

            <?php endif; ?>
            
            <?php if ($user->getFacebookId() == null): ?>  
                <div class="c1">
                    <label class="input">
                        <span>Email</span>
                        <input type="text" id="email" name="email" value="<?php if($user->getEmail()) echo $user->getEmail();?>" />
                    </label>
                </div><!-- /c1 -->    

				<!--
                <div class="c1">
                    <label class="input">
                        <span>Confirma tu Email</span>
                        <input type="text" id="emailAgain" name="emailAgain" >
                    </label>
                </div><!-- /c1 -->

            <?php else:?>
                <input type="hidden" id="email" name="email" value="<?php if($user->getEmail()) echo $user->getEmail();?>" />
                <input type="hidden" id="emailAgain" name="emailAgain" value="<?php if($user->getEmail()) echo $user->getEmail();?>"/>
                <input type="hidden" name="telephone" id="telephone" value="<?php if ($user->getTelephone() != null) echo $user->getTelephone(); ?>" />
            <?php endif; ?>

                <div class="c1">
                <label class="input">
                    <span>Rut</span>
                    <input type="text" name="run" id="run" value="<?php if ($user->getRut() != null) echo $user->getRut(); ?>" />
                </label>
            	</div><!-- /c1 -->
                  
                <div class="c1">
                <label class="input">
                    <span>Tel&eacute;fono</span>
                    <input type="text" name="telephone" id="telephone" value="<?php if ($user->getTelephone() != null) echo $user->getTelephone();?>" />
                </label>
            	</div><!-- /c1 -->

            <?php if ($user->getFacebookId() == null): ?>  
            <div class="c1">
                <label class="input">
                    <span>Fecha de nacimiento</span>
                    <input type="text" id="birth" name="birth" class="datepicker" value="<?php if ($user->getBirthdate() != null)
                            echo $user->getBirthdate(); ?>" >
                </label>
            </div><!-- /c1 -->
            <?php else:?>
                <input type="hidden" id="birth" name="birth" class="datepicker" value="<?php if ($user->getBirthdate() != null)
                            echo $user->getBirthdate(); ?>" />
            <?php endif;?>

            <div class="c1">
                <label class="input">
                    <span>Direcci&oacute;n #1111</span>
                    <input type="text" name="address" id="address" value="<?php if ($user->getAddress() != null) echo $user->getAddress();?>" style="width:566px;" >
                </label>
            </div><!-- /c1 -->

            <div class="c1">
                <label>
                    <select name="region" id="region" onChange="cargarComunas(this.value)">
                        <?php
                        foreach ($regiones as $r) {
                            if ($r["codigo"] == $userRegion) {
                                echo '<option value="' . $r["codigo"] . '" selected>' . $r["nombre"] . '</option>';
                            } else {
                                echo '<option value="' . $r["codigo"] . '">' . $r["nombre"] . '</option>';
                            }
                        }
                        ?>
                    </select>
                </label>
            </div><!-- /c1 -->

            <div class="c1">
                <label class="input">
                    <select name="comunas" id="comunas">
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

            <div class="regis_term" style="display: none;">
                <p>Terminos y Condiciones:</p>
                <div style="width:400px; height:200px; overflow:auto; border: 1px solid #CCC;"><?php include_component('main', 'terminosycondiciones') ?></div>

                <div style="font-size:11px; float: left; margin-top: 10px;">
                    <input type="checkbox" id="tandc" name="tandc" style="width:12px;height:12px; vertical-align: middle; " /> Acepto terminos y condiciones
                </div>
            </div><!-- /c1 -->



        </div><!-- regis_formulario -->

        <div class="regis_botones posit">     

            <button class="regis_btn_formulario" name="save" onclick="submitFrom()"> Siguiente </button>        

        </div><!-- regis_botones -->


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

    var frmvalidator = new Validator("frm1");
 
    frmvalidator.EnableMsgsTogether();
 
    //frmvalidator.addValidation("username","req","Ingrese un nombre de usuario");
    frmvalidator.addValidation("firstname","req","Ingrese su nombre");
    frmvalidator.addValidation("lastname","req","Ingrese su apellido");
    frmvalidator.addValidation("telephone","numeric","El Telefono Debe ser numerico");
    
    //frmvalidator.addValidation("password","req", "Ingrese su contraseña");
    //frmvalidator.addValidation("passwordAgain","req", "Vuelva a ingresar su contraseña");
 
    frmvalidator.addValidation("email","maxlen=50");
    frmvalidator.addValidation("email","req", "Ingrese un correo electronico");
    frmvalidator.addValidation("email","email", "Ingresa una direccion de correo valida");
 
    //frmvalidator.addValidation("emailAgain","maxlen=50");
    //frmvalidator.addValidation("emailAgain","req", "Vuelva a ingresar el correo electronico");
    //frmvalidator.addValidation("emailAgain","email", "Ingresa una direccion de correo valida");
 
 	frmvalidator.addValidation("run","req","Ingrese Rut");
    frmvalidator.addValidation("run","rut","Ingrese un Rut valido");
    
    frmvalidator.addValidation("region","req", "Ingrese Region");
    frmvalidator.addValidation("comunas","req", "Ingrese Comuna");
    
    frmvalidator.addValidation("address","req", "Ingrese Direccion");
 
    frmvalidator.addValidation("tandc","shouldselchk=on", "Acepte los terminos y condiciones");

    frmvalidator.setAddnlValidationFunction(DoPassValidation);
    //frmvalidator.setAddnlValidationFunction(DoEmailValidation);
 

 
</script>