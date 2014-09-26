<?php use_stylesheet('registro.css') ?>
<?php use_stylesheet('comunes.css') ?>
<?php
$useragent=$_SERVER['HTTP_USER_AGENT'];
if(preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i',$useragent)||preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i',substr($useragent,0,4))){
  use_stylesheet('moviles.css');
}
?>
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
    .input p.fono {
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

    });
});



</script>



<script>
    
    function enviarSMS() {
        $("#ingreso_codigo").show();
		console.log($("#telephone").val());
		console.log($("#telephone").html());
        $.ajax({
        type: "GET",
        url: "<?php echo url_for("profile/enviarConfirmacionSMS")."?numero="; ?>" + $("#telephone").val(),
        });
	   $("#codigo").focus();
    }
    
    function confirmarCodigo() {
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
    }
    
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
    	if( $("#firstname").val() == "" ) { $("#firstname").parent("label").find("span").text("* Falta ingresar Nombres"); }
    	if( $("#lastname").val() == "" ) { $("#lastname").parent("label").find("span").text("* Falta ingresar Apellido Paterno"); }
    	if( $("#email").val() == "" ) { $("#email").parent("label").find("span").text("* Falta ingresar Email"); }
    	if( $("#run").val() == "" ) { $("#run").parent("label").find("span").text("* Falta ingresar Rut"); }
    	if( $("#birth").val() == "" ) { $("#birth").parent("label").find("span").text("* Falta ingresar Fecha de nacimiento"); }
    	if( $("#address").val() == "" ) { $("#address").parent("label").find("span").text("* Falta ingresar Dirección"); }
    	
	if( $("#apellidoMaterno").val() == "" ) { $("#apellidoMaterno").parent("label").find("span").text("* Falta ingresar Apellido Materno"); }
	
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

<div class="main_box_1_anterior">
<div class="main_box_2_anterior">
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
						<img src="http://res.cloudinary.com/arriendas-cl/image/facebook/w_194,h_204,c_fill,g_face/<?php echo $user->getFacebookId();?>.jpg"/>
                    <?php else: ?>
						<img src="http://res.cloudinary.com/arriendas-cl/image/fetch/w_194,h_204,c_fill,g_face/http://arriendas.cl/<?php echo "images/users/".$user->getFileName() ?>" />
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
                    echo image_tag('img_asegura_tu_auto/foto_padron_reverso.png', 'size=194x204');
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
        <div class="titulolteral">Foto de tu Cédula</div>

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
                    <span>Nombres</span>
                    <input id="firstname" name="firstname" type="text" value="<?php if($user->getFirstName()) echo $user->getFirstName(); ?>" style="width: 566px;" >
					</label>
            </div><!-- /c1 -->  

            <div class="c1">
                <label class="input">
                    <span>Apellido Paterno</span>
                    <input id="lastname" name="lastname" type="text" value="<?php if($user->getLastName()) echo $user->getLastName(); ?>" >
                </label>
            </div><!-- /c1 -->    
         
            <div class="c1">
                <label class="input">
                    <span>Apellido Materno</span>
                    <input id="apellidoMaterno" name="apellidoMaterno" type="text" value="<?php if($user->getApellidoMaterno()) echo $user->getApellidoMaterno(); ?>" >
                </label>
            </div><!-- /c1 --> 
            
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
            <?php endif; ?>

                <div class="c1">
                <label class="input">
                    <span>RUT</span>
                    <input type="text" name="run" id="run" value="<?php if ($user->getRut() != null) echo $user->getRut(); ?>" />
                </label>
            	</div><!-- /c1 -->
		
		        <div class="c1">
                    <label class="input">
                        <span>Nº Serie del RUT</span>
                        <input type="text" name="serie_run" id="serie_run" value="<?php if ($user->getSerieRut() != null) echo $user->getSerieRut(); ?>" style="width:234px;" />
                        <a href='http://www.dicom.cl/dcom/pag/p.com.000.pag-cedula.htm#uno' target='_blank' title="¿Qué es el nº de serie del rut?"><?php echo image_tag('img_registro/dudaNSerie1.png', array('width'=>'25px','height'=>'25px')) ?></a>
                    </label>
            	</div><!-- /c1 -->
                <div class="c1" style="width: 200px;">
                    <label class="input">
                        <select name="extranjero" id="extranjero">
                            <option value="0" <?php echo $user->getExtranjero() == 0 ? "selected" : ""; ?> >Chileno</option>
                            <option value="1" <?php echo $user->getExtranjero() == 1 ? "selected" : ""; ?> >Extranjero</option>
                        </select>
                    </label>
                </div><!-- /c1 -->
            <?php if($user->getConfirmedSms() == 0){ ?>    
                <div class="c1">
                <label class="input" id="espacio">
                    <p class="fono">98765432</p>
                    <input title="Celular" type="text" name="telephone" id="telephone" value="<?php if ($user->getTelephone() != null) echo $user->getTelephone();?>" />
		    <?php echo image_tag("http://arriendas.assets.s3.amazonaws.com/images/BotonConfimarTelef.png",array("style"=>"margin-top: -37px; margin-bottom: -30px; cursor: pointer;","onclick"=>"enviarSMS();","class"=>"codigo_telephone","title"=>"Enviar SMS para Confirmar Teléfono")); ?>
            <?php echo image_tag("img_registro/BotonConfimarTelefGris.png",array("style"=>"margin-top: -37px; margin-bottom: -30px; display:none","title"=>"Teléfono Verificado","id"=>"img_tel_verificado")); ?>
                </label>
            	</div><!-- /c1 -->

                <div class="c1 codigo_telephone" id="ingreso_codigo" style="border-style: solid; border-width: 1px; border-color: #FF0000; padding-left: 10px; margin-left: -10px; padding-top: 5px; display: none;">
                <label class="input">
                    <span>Ingresa el c&oacute;digo que te enviamos por SMS</span>
                    <input type="text" name="codigo" id="codigo"/>
		    <?php echo image_tag("http://arriendas.assets.s3.amazonaws.com/images/BotonConfimarTelef.png",array("style"=>"margin-top: -37px; margin-bottom: -30px; cursor: pointer;","onclick"=>"confirmarCodigo();","title"=>"Enviar Código")); ?>
                </label>
            	</div><!-- /c1 -->
            <?php }else{ ?>
            <div class="c1">
                <label class="input" style="margin-right:100px;">
                    <p class="fono">98765432</p>
                    <input title="Celular" type="text" name="telephone" id="telephone" value="<?php if ($user->getTelephone() != null) echo $user->getTelephone();?>" />
                    <?php echo image_tag("img_registro/BotonConfimarTelefGris.png",array("style"=>"margin-top: -37px; margin-bottom: -30px;","title"=>"Teléfono Verificado")); ?>
                </label>
            </div><!-- /c1 -->
            <?php } ?>
            <?php if ($user->getFacebookId() == null): ?>  
            <div class="c1">
                <label class="input">
                    <span>Fecha de nacimiento</span>
                    <input type="text" id="birth" name="birth" class="datepicker" value="<?php if ($user->getBirthdate() != null)
                            echo $user->getBirthdate(); ?>" >
                </label>
            </div><!-- /c1 -->
            <?php else:?>
            <label class="input">
                    <span>Fecha de nacimiento</span>
                <input type="text" id="birth" name="birth" class="datepicker" value="<?php if ($user->getBirthdate() != null)
                            echo $user->getBirthdate(); ?>" />
             </label>
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

            <button class="regis_btn_formulario" name="save" onclick="submitFrom()"> Guardar </button>        

        </div><!-- regis_botones -->


    </div><!-- regis_box_info -->

    <input type="hidden" name="redirect" value="<?=$redirect;?>">
    <input type="hidden" name="idRedirect" value="<?=$idRedirect;?>">
    <div class="clear"></div>
</div><!-- /regis_box -->
</div>

</form>

<script>

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
    frmvalidator.addValidation("run","rut","Ingrese un Rut valido", "VWZ_IsListItemSelected(document.forms['frm1'].elements['extranjero'],'0')");
    
    frmvalidator.addValidation("region","req", "Ingrese Region");
    frmvalidator.addValidation("comunas","req", "Ingrese Comuna");
    
    frmvalidator.addValidation("address","req", "Ingrese Direccion");
 
    frmvalidator.addValidation("tandc","shouldselchk=on", "Acepte los terminos y condiciones");

    //frmvalidator.setAddnlValidationFunction(DoPassValidation);
    //frmvalidator.setAddnlValidationFunction(DoEmailValidation);
 

</script>