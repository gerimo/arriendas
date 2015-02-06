<link href="/css/newDesign/edit.css" rel="stylesheet" type="text/css">

<div class="hidden-xs space-100"></div>
<div class="visible-xs space-50"></div>

<div class="row">
    <div class="col-md-offset-2 col-md-8">

        <div class="row BCW">

            <h1 style="text-indent: 30px">Mi perfil</h1>

            <div class="col-md-5 text-center">

                <div class="hidden-xs space-10"></div>
                <div class="regis_foto_frame">

                    <div id="previewmain" style="width: 100%">
                        <?php if ($User->getPictureFile() == null): ?>
                            <?php echo image_tag('img_registro/tmp_user_foto.png', 'size=194x204') ?>  
                        <?php else: ?>
                            <?php if($User->getFacebookId() != null):?>
                                <img src="http://res.cloudinary.com/arriendas-cl/image/facebook/w_194,h_204,c_fill,g_face/<?php echo $User->getFacebookId();?>.jpg">
                            <?php else: ?>
                                <!-- <img src="http://res.cloudinary.com/arriendas-cl/image/fetch/w_194,h_204,c_fill,g_face/http://www.arriendas.cl/images/users/<?php echo $User->getFileName() ?>">-->
                                <?php echo image_tag('users/'.$User->getFileName(), 'size=194x204') ?>
                            <?php endif ?>
                        <?php endif ?>
                    </div> 

                    <a id="linkmain" href=""><i class="fa fa-edit"></i> editar</a>
                </div>

                <div class="hidden-xs space-70"></div>
                <div class="visible-xs space-30"></div>

                <div class="regis_foto_frame">
                    <div id="previewlicense">
                        <?php if ($User->getDriverLicenseFile() == null): ?>
                            <?php echo image_tag('newDesign/foto_padron_reverso.png', 'size=194x204') ?>
                        <?php else: ?>
                            <?php echo image_tag('licence/'.$User->getLicenceFileName(), 'size=194x204') ?>
                        <?php endif ?>
                    </div> 

                    <a id="linklicense" href=""><i class="fa fa-edit"></i> editar</a>
                </div>
            </div>

            <div class="col-md-6">

                <?php if ($sf_user->getFlash('show')): ?>
                    <div style="border:1px solid #FF0000; background: #fcdfff;  width:360px; display:table;margin-bottom:20px; padding:20px;font-size:14px;">
                        <?php echo $sf_user->getFlash('msg'); ?>
                    </div>
                <?php endif ?>

                <input class="form-control" id="firstname" name="firstname" placeholder="Nombres" value="<?php if($User->getFirstName()) echo $User->getFirstName(); ?>" type="text">                    
            
                <input class="form-control" id="lastname" name="lastname" placeholder="Apellido paterno" value="<?php if($User->getLastName()) echo $User->getLastName(); ?>" type="text">                    
            
                <input class="form-control" id="motherLastname" name="motherLastname" placeholder="Apellido materno" value="<?php if($User->getApellidoMaterno()) echo $User->getApellidoMaterno(); ?>" type="text">                    

                <?php if ($User->getFacebookId() == null): ?>                          
                    <input class="form-control" id="email" name="email" placeholder="Correo electrónico" value="<?php if($User->getEmail()) echo $User->getEmail() ?>" type="text">                        
                <?php else: ?>
                    <input class="form-control" id="email" name="email" placeholder="Correo electrónico" value="<?php if($User->getEmail()) echo $User->getEmail() ?>" type="hidden">
                    <input class="form-control" id="emailAgain" name="emailAgain" placeholder="Repetir correo electrónico" value="<?php if($User->getEmail()) echo $User->getEmail() ?>" type="hidden">
                <?php endif ?>

                <div class="form-inline clearfix">
                    <select class="form-control" name="foreign" id="foreign">
                        <option value="0" <?php echo $User->getExtranjero() == 0 ? "selected" : ""; ?> >Tengo RUT Chileno - I have chilean RUT number</option>
                        <option value="1" <?php echo $User->getExtranjero() == 1 ? "selected" : ""; ?> >No tengo RUT - I don't have chilean RUT number</option>
                    </select>
                    <input class="form-control" id="run" name="run" placeholder="RUT" value="<?php if ($User->getRut()) echo $User->getRutFormatted() ?>" type="text">
                </div>

                <input class="form-control" name="telephone" id="telephone" placeholder="Teléfono" value="<?php if ($User->getTelephone()) echo $User->getTelephone() ?>" title="Celular" type="text">

                <input class="datetimepicker form-control" id="birth" name="birth" placeholder="Fecha de nacimiento" value="<?php if ($User->getBirthdate()) echo date('d-m-Y', strtotime($User->getBirthdate())) ?>" type="text">
                <span class="note">La fecha debe coincidir con la fecha de nacimiento de tu licencia.</span>
                
                <input class="form-control" id="address" name="address" placeholder="Dirección #111" value="<?php if ($User->getAddress()) echo $User->getAddress() ?>" type="text">
            
                <select class="form-control" id="region" name="region">
                    <?php foreach ($Regions as $R): ?>
                        <?php if ($R->id == $User->getCommune()->getRegion()->id): ?>
                            <option selected value="<?php echo $R->id ?>"><?php echo $R->name ?></option>
                        <?php else: ?>
                            <option value="<?php echo $R->id ?>"><?php echo $R->name ?></option>
                        <?php endif ?>
                    <?php endforeach ?>
                </select>

                <select class="form-control" id="commune" name="commune">
                    <?php foreach ($User->getCommune()->getRegion()->getCommunes() as $C): ?>
                        <?php if ($C->id == $User->getCommune()->id): ?>
                            <option selected value="<?php echo $C->id ?>"><?php echo $C->name ?></option>
                        <?php else: ?>
                            <option value="<?php echo $C->id ?>"><?php echo $C->name ?></option>
                        <?php endif ?>
                    <?php endforeach ?>
                </select>

                <!-- <div class="regis_term" style="display: none;">
                    <p>Terminos y Condiciones:</p>
                    <div style="width:400px; height:200px; overflow:auto; border: 1px solid #CCC;">
                        <?php //include_component('main', 'terminosycondiciones') ?>
                    </div>

                    <div style="font-size:11px; float: left; margin-top: 10px;">
                        <input type="checkbox" id="tandc" name="tandc" style="width:12px;height:12px; vertical-align: middle; " /> Acepto terminos y condiciones
                    </div>
                </div>-->

                <button class="btn btn-a-primary btn-block" name="save" onclick="validateForm()">Guardar</button>

                <p class="alert"></p>

                <div class="hidden-xs space-100"></div>
            </div>
        </div>

    </div>
</div>

<!-- Formularios -->
<div style="display:none">

    <form action="<?php echo url_for('main/uploadPhoto?photo=main&width=194&height=204&file=filemain') ?>" enctype="multipart/form-data" id="formmain" method="post">
        <input id="filemain" name="filemain" type="file">
        <input type="submit">
    </form>

    <form action="<?php echo url_for('main/uploadLicense?photo=license&width=194&height=204&file=filelicense') ?>" enctype="multipart/form-data" id="formlicense" method="post">
        <input id="filelicense" name="filelicense" type="file">
        <input type="submit">
    </form>
    
    <form action="<?php echo url_for('main/uploadRut?photo=rut&width=194&height=204&file=filerut') ?>" enctype="multipart/form-data" id="formrut" method="post">
        <input id="filerut" name="filerut" type="file">
        <input type="submit">
    </form>

    <div id="dialog-alert" title="">
        <p></p>
    </div>
</div>

<div class="hidden-xs space-100"></div>

<script type="text/javascript">

    $(document).ready(function() {

        imageUpload('#formmain', '#filemain', '#previewmain','#linkmain');       
        imageUpload('#formlicense', '#filelicense', '#previewlicense','#linklicense');
    });

    $("#region").change(function(){

        var regionId = $(this).val();

        $("#commune").attr("disabled", true);

        if (regionId > 0) {
            $.post("<?php echo url_for('main/getCommunes') ?>", {"regionId": regionId}, function(r){

                if (r.error) {
                    console.log(r.errorMessage);
                } else {

                    var html = "<option selected value='0'>Selecciona tu comuna</option>";

                    $.each(r.communes, function(k, v){
                        html += "<option value='"+v.id+"'>"+v.name+"</option>";
                    });

                    $("#commune").html(html);
                }

                $("#commune").removeAttr("disabled");

            }, 'json');
        }
    });

    function imageUpload (form, formfile, preview, link) {

        $(formfile).change(function(e) {
            
            $(preview).html('<?php echo image_tag('loader.gif') ?>');

            $(form).ajaxForm({
                dataType: "json",
                target: preview,
                success: function(r){
                    if (r.error) {
                        $("#dialog-alert p").html(r.errorMessage);
                        $("#dialog-alert").attr("title", "Problemas al subir la imagen");
                        $("#dialog-alert").dialog({
                            buttons: [{
                                text: "Aceptar",
                                click: function() {
                                    $( this ).dialog( "close" );
                                }
                            }]
                        });
                    } else {
                        location.reload();
                    }
                }
            }).submit();
        });
        
        $(link).click(function(e) {
            e.preventDefault();
            $(formfile).click();
        });
    }

    $("#foreign").change(function(){
        var foreign = $(this).val();
        $('#run').val('');
        if(foreign > 0){  
            $('#run').val('');
            $('#run').attr('disabled', true);
            $("#run").parent("label").find("span").text('');
        } else {
            $('#run').attr('disabled', false);
            $("#run").parent("label").find("span").text('Rut');
        }
    });

    function validateForm() {

        var firstname      = $("#firstname").val();
        var lastname       = $("#lastname").val();
        var motherLastname = $("#motherLastname").val();
        var email          = $("#email").val();
        var emailAgain     = $("#emailAgain").val();
        var rut            = $("#run").val();
        var foreign        = $("#foreign option:selected").val();
        var telephone      = $("#telephone").val();
        var birth          = $("#birth").val();
        var address        = $("#address").val();
        var commune        = $("#commune option:selected").val();

        var parameters = {
            "firstname": firstname,
            "lastname": lastname,
            "motherLastname": motherLastname,
            "email": email,
            "emailAgain": emailAgain,
            "rut": rut,
            "foreign": foreign,
            "telephone": telephone,
            "birth": birth,
            "address": address,
            "commune": commune
        }

        $.post("<?php echo url_for('profile/doEdit') ?>", parameters, function(r){

            $(".alert").removeClass("alert-a-danger");
            $(".alert").removeClass("alert-a-success");

            if (r.error) {
                $(".alert").addClass("alert-a-danger");
                $(".alert").html(r.errorMessage);
            } else {
                $(".alert").addClass("alert-a-success");
                $(".alert").html("Cambios guardados satisfactoriamente");
            }

        }, 'json');
    }

    $('.datetimepicker').datetimepicker({
        dayOfWeekStart: 1,
        lang:'es',
        i18n:{
            es:{
                months:[
                    'Enero','Febrero','Marzo','Abril',
                    'Mayo','Junio','Julio','Agosto',
                    'Septiembre','Octubre','Noviembre','Diciembre'
                ],
                dayOfWeek:["Do", "Lu", "Ma", "Mi", "Ju", "Vi", "Sa"]
            }
        },
        timepicker: false,
        format:'d-m-Y'
    });
</script>