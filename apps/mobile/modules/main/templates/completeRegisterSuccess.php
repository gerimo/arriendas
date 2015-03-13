<link href="/css/newDesign/mobile/completeRegisterMobile.css" rel="stylesheet" type="text/css">

<div class="visible-xs space-50"></div>    

<div class="row">
    <div class="col-md-offset-4 col-md-4">
        <div class="BCW" id="frm">
            <h1 class="completeRegister">Completa tus datos</h1>

            <?php if ($sf_user->getFlash('show')): ?>
                <div style="border:1px solid #FF0000; background: #fcdfff;  width:360px; display:table;margin-bottom:20px; padding:20px;font-size:14px;">
                    <?php echo $sf_user->getFlash('msg'); ?>
                </div>
            <?php endif ?>

            <select class="form-control" id="foreign" name="foreign" >
                <option value="0">Tengo RUT Chileno - I have chilean RUT number</option>
                <option value="1">No tengo RUT - I don't have chilean RUT number</option>
            </select>

            <input class="form-control" id="rut" name="rut" type="text" placeholder="RUT">
            <input class="form-control" name="telephone" id="telephone" placeholder="Teléfono"  type="text">
            <input class="datetimepicker form-control" readonly="readonly" id="birth" name="birth" placeholder="Fecha de nacimiento" >
            <div class="row">
                <i class="fa fa-exclamation-triangle"></i><span class="note"> Debes tener desde 24 años para utilizar arriendas.cl .</span>
            </div>
            <input class="form-control" id="address" name="address" placeholder="Dirección #111" >
            
            <select class="form-control" id="region" name="region">
            <option selected value="0">Selecciona tu región</option>
            <?php foreach ($Regions as $r): ?>
                <option value="<?php echo $r->getId() ?>"><?php echo $r->getName() ?></option>
            <?php endforeach ?>
            </select>
                
            <select class="form-control" id="commune" name="commune" disabled>
                <option value="0">Selecciona tu comuna</option>
            </select><br>

            <div class="col-md-12 btn-group">
                <label class="form-control">¿Como nos conociste?</label>
                <div class="col-xs-offset-2 col-md-offset-1 como-radio">
                    <label class="radio">
                        <input type="radio" name="como" value="noticias" />En las noticias
                    </label>

                    <label class="radio">
                        <input type="radio" name="como" value="google" />Por Google
                    </label>

                    <label class="radio">
                        <input type="radio" name="como" value="buses" />Publicidad en buses
                    </label>

                    <label class="radio">
                        <input type="radio" name="como" value="radio" />En la radio
                    </label>

                    <label class="radio">
                        <input type="radio" name="como" value="facebook" />Por Facebook
                    </label>

                    <label class="radio">
                        <input type="radio" name="como" value="google" />Por un amigo
                    </label>

                    <label class="radio">
                        <input type="radio" id="another-radio" name="como" value="otro" />Otro
                    </label>
                    <input class="form-control another" id="another-text" placeholder="especifica">
                </div>
            </div>

            <div class="row">
                <div class="col-md-offset-7 col-md-5" style="padding: 0">
                    <button class="btn btn-a-primary btn-block" id="save"  onclick="validateForm()">Finalizar</button>
                </div>
            </div>
            <p class="alert pull-right"></p>
        </div>
    </div>
</div>
<div style="display:none">
    <div id="dialog-alert" class="text-center" title="">
        <p></p>
    </div>
</div>

<script type="text/javascript">

    var referer = "<?php echo $referer ?>";
    $("#foreign").change(function(){
        var foreign = $(this).val();
        $('#rut').val('');
        if(foreign > 0){  
            $('#rut').val('');
            $('#rut').attr('disabled', true);
            $("#rut").parent("label").find("span").text('');
        } else {
            $('#rut').attr('disabled', false);
            $("#rut").parent("label").find("span").text('Rut');
        }
    });

    $(document).ready(function(){  
        $("#another-text").attr('disabled', true);
        $("input:radio[name=como]").click(function() {  
            if($('input:radio[name=como]:checked').val() == 'otro'){
                $('#another-text').attr('disabled', false);
                $("#another-text").parent("label").find("span").text('Rut');
            } else {
                $('#another-text').val('');
                $('#another-text').attr('disabled', true);
                $("#another-text").parent("label").find("span").text('');
            }
        });  
      
        $("#radio_desactivar").click(function() {  
            $("#radio").attr('checked', false);  
        });  
  
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

    function validateForm() {
        var como           = $('input:radio[name=como]:checked').val();
        var anotherText   = $("#another-text").val();
        var rut            = $("#rut").val();
        var foreign        = $("#foreign option:selected").val();
        var telephone      = $("#telephone").val();
        var birth          = $("#birth").val();
        var address        = $("#address").val();
        var commune        = $("#commune option:selected").val();
        var region         = $("#region option:selected").val();

        $.post("<?php echo url_for('main/doCompleteRegister') ?>", {"como": como, "anotherText": anotherText, "rut": rut, "foreign": foreign, "telephone": telephone, "birth": birth, "address": address, "commune": commune, "region": region}, function(r){

            $(".alert").removeClass("alert-a-danger");
            $(".alert").removeClass("alert-a-success");

            if (r.error) {
                $("#dialog-alert").html(r.errorMessage).addClass("text-center");
                $("#dialog-alert").dialog({
                    closeOnText: true,
                    modal: true,
                    resizable: true,
                    title: "Completar registro",
                    buttons: [
                        {
                            text: "Aceptar",
                            click: function() {     
                                $( this ).dialog( "close" );               
                            }
                        }
                    ]
                });
            } else {

                location.href="<?php echo url_for('message_register_success') ?>"
                
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