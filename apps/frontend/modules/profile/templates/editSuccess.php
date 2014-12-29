<link href="/css/newDesign/edit.css" rel="stylesheet" type="text/css">

<div class="hidden-xs space-100"></div>
<div class="visible-xs space-50"></div>

<div class="row">
    <div class="col-md-offset-3 col-md-6 BCW">

        <div class="hidden-xs space-40"></div>
        <div class="row">

            <div class="col-md-offset-1 col-md-4">

                <h2>Foto perfil</h2>

                <div class="regis_foto_frame">
                    <div id="previewmain">
                        <?php if ($user->getPictureFile() == null): ?>
                            <?php echo image_tag('img_registro/tmp_user_foto.png', 'size=194x204') ?>  
                        <?php else: ?>
                            <?php if($user->getFacebookId()!=null):?>
                                <img src="http://res.cloudinary.com/arriendas-cl/image/facebook/w_194,h_204,c_fill,g_face/<?php echo $user->getFacebookId();?>.jpg"/>
                            <?php else: ?>
                                <img src="http://res.cloudinary.com/arriendas-cl/image/fetch/w_194,h_204,c_fill,g_face/http://arriendas.cl/<?php echo "images/users/".$user->getFileName() ?>" />
                            <?php endif ?>
                        <?php endif ?>
                    </div> 

                    <a class="pull-left" href="#" id="linkmain"><?php echo image_tag('img_subi_tu_auto/upcar_ico_agrega_foto.png') ?></a>
                    <span>Dimensiones imagen 194x204 pix.</span>
                </div>

                <br>
                
                <h2>Licencia de conducir</h2>

                <div class="regis_foto_frame">
                    <div id="previewlicence">
                        <?php if ($user->getDriverLicenseFile() == null): ?>
                            <?php echo image_tag('img_asegura_tu_auto/foto_padron_reverso.png', 'size=194x204') ?>
                        <?php else: ?>
                            <?php echo image_tag('licence/'.$user->getLicenceFileName(), 'size=194x204') ?>
                        <?php endif ?>
                    </div> 

                    <a class="pull-left" href="#" id="linklicence"><?php echo image_tag('img_subi_tu_auto/upcar_ico_agrega_foto.png') ?></a>
                    <span>Dimensiones imagen 194x204 pix.</span>
                </div>
            </div>

            <div class="col-md-6">

                <h2>Datos de usuario</h2>

                <?php if ($sf_user->getFlash('show')): ?>
                    <div style="border:1px solid #FF0000; background: #fcdfff;  width:360px; display:table;margin-bottom:20px; padding:20px;font-size:14px;">
                        <?php echo $sf_user->getFlash('msg'); ?>
                    </div>
                <?php endif ?>

                <input class="form-control" id="username" name="username" placeholder="Nombre de usuario" value="<?php echo $user->getUsername(); ?>" type="text" style="display:none">
            
                <input class="form-control" id="firstname" name="firstname" placeholder="Nombres" value="<?php if($user->getFirstName()) echo $user->getFirstName(); ?>" type="text">                    
            
                <input class="form-control" id="lastname" name="lastname" placeholder="Apellido paterno" value="<?php if($user->getLastName()) echo $user->getLastName(); ?>" type="text">                    
            
                <input class="form-control" id="apellidoMaterno" name="apellidoMaterno" placeholder="Apellido materno" value="<?php if($user->getApellidoMaterno()) echo $user->getApellidoMaterno(); ?>" type="text">                    

                <?php if ($user->getFacebookId() == null): ?>                          
                    <input class="form-control" id="email" name="email" placeholder="Correo electrónico" value="<?php if($user->getEmail()) echo $user->getEmail();?>" type="text">                        
                <?php else: ?>
                    <input class="form-control" id="email" name="email" placeholder="Correo electrónico" value="<?php if($user->getEmail()) echo $user->getEmail();?>" type="hidden">
                    <input class="form-control" id="emailAgain" name="emailAgain" placeholder="Repetir correo electrónico" value="<?php if($user->getEmail()) echo $user->getEmail();?>" type="hidden">
                <?php endif ?>

                <div class="form-inline">
                    <input class="form-control" id="run" name="run" placeholder="RUT" value="<?php if ($user->getRut() != null) echo $user->getRut(); ?>" type="text">
                    <select class="form-control pull-right" name="extranjero" id="extranjero">
                        <option value="0" <?php echo $user->getExtranjero() == 0 ? "selected" : ""; ?> >Chileno</option>
                        <option value="1" <?php echo $user->getExtranjero() == 1 ? "selected" : ""; ?> >Extranjero</option>
                    </select>
                </div>

                <?php if($user->getConfirmedSms() == 0): ?>
                    <div class="form-inline">
                        <input class="form-control" name="telephone" id="telephone" placeholder="Teléfono" value="<?php if ($user->getTelephone() != null) echo $user->getTelephone();?>" title="Celular" type="text">
                        <button class="btn-a-action pull-right" id="btn-confirm" onclick="enviarSMS()" title"Enviar SMS para Confirmar Teléfono" type="button">Confirmar</button>
                    </div>

                    <span>No es obligatorio confirmar tu teléfono.</span>
                    <?php echo image_tag("img_registro/BotonConfimarTelefGris.png",array("style"=>"display:none","title"=>"Teléfono Verificado","id"=>"img_tel_verificado")); ?>

                    <div class="c1 codigo_telephone" id="ingreso_codigo" style="display: none">
                        <input type="text" name="codigo" id="codigo"/>
                        <span>Ingresa el código que te enviamos por SMS</span>
                        <?php echo image_tag("http://arriendas.assets.s3.amazonaws.com/images/BotonConfimarTelef.png",array("style"=>"margin-top: -37px; margin-bottom: -30px; cursor: pointer;","onclick"=>"confirmarCodigo();","title"=>"Enviar Código")); ?>
                    </div>
                <?php else: ?>
                    <input class="form-control" id="telephone" name="telephone" placeholder="Teléfono" value="<?php if ($user->getTelephone() != null) echo $user->getTelephone();?>" title="Celular" type="text">
                    <?php echo image_tag("img_registro/BotonConfimarTelefGris.png",array("style"=>"margin-top: -37px; margin-bottom: -30px;","title"=>"Teléfono Verificado")); ?>
                    <span>No es obligatorio confirmar tu teléfono.</span>
                <?php endif ?>

                <?php if ($user->getFacebookId() == null): ?>
                    <input class="datepicker form-control" id="birth" name="birth" placeholder="Fecha de nacimiento" value="<?php if ($user->getBirthdate() != null) echo $user->getBirthdate(); ?>" type="text">
                    <span>La fecha debe coincidir con la fecha de nacimiento de tu licencia.</span>
                <?php else: ?>                        
                    <input class="datepicker form-control" id="birth" name="birth" placeholder="Fecha de nacimiento" value="<?php if ($user->getBirthdate() != null) echo $user->getBirthdate(); ?>" type="text">
                    <span>Tu fecha de nacimiento debe coincidir con la fecha de tu licencia.</span>
                <?php endif ?>
                
                <input class="form-control" id="address" name="address" placeholder="Dirección #111" value="<?php if ($user->getAddress() != null) echo $user->getAddress();?>" type="text">
            
                <select class="form-control" id="comunas" name="comunas">
                    <?php foreach ($comunas as $c): ?>
                        <?php if ($c["codigoInterno"] == $userComuna): ?>
                            <option selected value="<?php echo $c["codigoInterno"] ?>"><?php echo ucwords(strtolower($c["nombre"])) ?></option>
                        <?php else: ?>
                            <option value="<?php echo $c["codigoInterno"] ?>"><?php echo ucwords(strtolower($c["nombre"])) ?></option>
                        <?php endif ?>
                    <?php endforeach ?>
                </select>

                <select class="form-control" id="region" name="region" onChange="cargarComunas(this.value)">
                    <?php foreach ($regiones as $r): ?>
                        <?php if ($r["codigo"] == $userRegion): ?>
                            <option selected value="<?php echo $r["codigo"] ?>"><?php echo $r["nombre"] ?></option>
                        <?php else: ?>
                            <option value="<?php echo $r["codigo"] ?>"><?php echo $r["nombre"] ?></option>
                        <?php endif ?>
                    <?php endforeach ?>
                </select>
                
                <div class="regis_term" style="display: none;">
                    <p>Terminos y Condiciones:</p>
                    <div style="width:400px; height:200px; overflow:auto; border: 1px solid #CCC;">
                        <?php include_component('main', 'terminosycondiciones') ?>
                    </div>

                    <div style="font-size:11px; float: left; margin-top: 10px;">
                        <input type="checkbox" id="tandc" name="tandc" style="width:12px;height:12px; vertical-align: middle; " /> Acepto terminos y condiciones
                    </div>
                </div>

                <button class="btn-a-primary btn-block" name="save" onclick="submitFrom()">Guardar Datos</button>
            </div>
        </div>

        <div class="hidden-xs space-40"></div>
    </div>
</div>

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

<div class="hidden-xs space-100"></div>

<script type="text/javascript">

    $(document).ready(function() {

        /*$("#extranjero").css({"top": $("#run").position().top, "position": "absolute"});*/
        /*$("#btn-confirm").css({"top": $("#telephone").position().top, "position": "absolute"});*/
    
        /*$('#tandc').attr('checked',true);*/
                        
        /*$( ".datepicker" ).datepicker({
            dateFormat: 'yy-mm-dd',
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
        });*/        
  
        function imageUpload (form, formfile, preview, link) {

            $(formfile).live('change', function() {

                $(preview).html('');
                $(preview).html('<?php echo image_tag('loader.gif') ?>');
                $(form).ajaxForm({
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

        function toggleLabel2() {
            var input = $(this);
            setTimeout(function() {
                var def = input.attr('title');
                if (!input.val() || (input.val() == def)) {
                    input.prev('p.fono').css('visibility', '');
                    if (def) {
                        var dummy = $('<label></label>').text(def).css('visibility','hidden').appendTo('body');
                        dummy.remove();
                    }
                } else {
                    input.prev('p.fono').css('visibility', 'hidden');
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

        /*$('input, textarea').live('change', toggleLabel);
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

        $('input').live('change', toggleLabel2);
        $('input').live('keydown', toggleLabel2);
        $('input').live('paste', toggleLabel2);
        $('select').live('change', toggleLabel2);
        $('select').live('change', toggleLabel2);
        $('input').live('focusin', function() {
            $(this).prev('span').css('color', '#ccc');
        });
        $('input').live('focusin', toggleLabel2);
        $('input').live('focusout', function() {
            $(this).prev('p.fono').css('color', '#999');
        });
        $(function() {
            $('input').each(function() { toggleLabel2.call(this); });
        });

        $('input#run').live('focusout', function() {
            var run = $(this).val();
            var partes = run.split(" ");
            var run_sinEspacios = "";
            for(var i=0;i<partes.length;i++){
                run_sinEspacios = run_sinEspacios+partes[i];
            }
            var partes = run_sinEspacios.split(".");
            var run_sinPuntos_sinEspacios = "";
            for(var i=0;i<partes.length;i++){
                run_sinPuntos_sinEspacios = run_sinPuntos_sinEspacios+partes[i];
            }
            var partes = run_sinPuntos_sinEspacios.split("-");
            var run_sinGuion_sinPuntos_sinEspacios = "";
            for(var i=0;i<partes.length;i++){
                run_sinGuion_sinPuntos_sinEspacios = run_sinGuion_sinPuntos_sinEspacios+partes[i];
            }
            var ultimoCaracter = run_sinGuion_sinPuntos_sinEspacios.slice(-1);
            var restoCadena = run_sinGuion_sinPuntos_sinEspacios.slice(0,-1);
            
            var runCorregido = restoCadena+"-"+ultimoCaracter;
            $("input#run").attr("value",runCorregido);

        });*/
    });
    
    /*function enviarSMS() {
        $("#ingreso_codigo").show();
        console.log($("#telephone").val());
        console.log($("#telephone").html());
        $.ajax({
        type: "GET",
        url: "<?php echo url_for("profile/enviarConfirmacionSMS")."?numero="; ?>" + $("#telephone").val(),
        });
       $("#codigo").focus();
    }*/
    
    /*function confirmarCodigo() {
        $.ajax({
        type: "GET",
        url: "<?php echo url_for('profile/validarSMS?token='); ?>" + $("#codigo").val(),
        success: function(data) {
            if(data == 1) {
                alert("Número Validado");
                $(".codigo_telephone").fadeOut("fast");
                $("#img_tel_verificado").fadeIn("slow");
            } else {
                alert("Código Incorrecto");
                $("#ingreso_codigo").fadeOut("slow");
            }
        }
        });
    }*/

    /*function submitFrom() {
        
        $('.inputerror').each(function() {
            $(this).removeClass('inputerror');
        })
        
        //Validaciones
        if( $("#firstname").val() == "" ) { $("#firstname").parent("label").find("span").text("* Falta ingresar Nombres"); }
        if( $("#lastname").val() == "" ) { $("#lastname").parent("label").find("span").text("* Falta ingresar Apellido Paterno"); }
        if( $("#email").val() == "" ) { $("#email").parent("label").find("span").text("* Falta ingresar Email"); }
        if( $("#run").val() == "" ) { $("#run").parent("label").find("span").text("* Falta ingresar Rut"); }
        if( $("#birth").val() == "" ) { $("#birth").parent("label").find("span").text("* Falta ingresar Fecha de nacimiento"); }
        if( $("#address").val() == "" ) { $("#address").parent("label").find("span").text("* Falta ingresar Dirección"); }
        
        if( $("#apellidoMaterno").val() == "" ) {
            $("#apellidoMaterno").parent("label").find("span").text("* Falta ingresar Apellido Materno");
        }
    
        if(document.myform.onsubmit()) {
            document.forms["frm1"].submit();
        }
    }*/

    /*function checkclear(what){
        if(!what._haschanged){
            what.value=''
        };
        what._haschanged=true;
    }*/

    /*function cargarComunas(idRegion){
    
        $.ajax({
            method: 'POST' ,
            url: <?php echo "'" . url_for('main/getComunas') . "'"; ?>,
            data: 'idRegion=' + idRegion,
            success: function(data){
                $('#comunas').html(data);
            }
        });
    }*/

    /*function DoEmailValidation()
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
    }*/

    /*var frmvalidator = new Validator("frm1");
 
    frmvalidator.EnableMsgsTogether();
 
    frmvalidator.addValidation("firstname","req","Ingrese su nombre");
    frmvalidator.addValidation("lastname","req","Ingrese su apellido");
    frmvalidator.addValidation("telephone","numeric","El Telefono Debe ser numerico");
 
    frmvalidator.addValidation("email","maxlen=50");
    frmvalidator.addValidation("email","req", "Ingrese un correo electronico");
    frmvalidator.addValidation("email","email", "Ingresa una direccion de correo valida");
 
    frmvalidator.addValidation("run","req","Ingrese Rut");
    frmvalidator.addValidation("run","rut","Ingrese un Rut valido", "VWZ_IsListItemSelected(document.forms['frm1'].elements['extranjero'],'0')");
    
    frmvalidator.addValidation("region","req", "Ingrese Region");
    frmvalidator.addValidation("comunas","req", "Ingrese Comuna");
    
    frmvalidator.addValidation("address","req", "Ingrese Direccion");
 
    frmvalidator.addValidation("tandc","shouldselchk=on", "Acepte los terminos y condiciones");*/
</script>