<link href="/css/newDesign/completeRegister.css" rel="stylesheet" type="text/css">

<div class="hidden-xs space-100"></div>
<div class="visible-xs space-50"></div>    

<div class="row">
    <div class="col-md-offset-4 col-md-4">
        <div class="BCW" id="frm">
            <h1>Completa tus datos</h1>

            <?php if ($sf_user->getFlash('show')): ?>
                <div style="border:1px solid #FF0000; background: #fcdfff;  width:360px; display:table;margin-bottom:20px; padding:20px;font-size:14px;">
                    <?php echo $sf_user->getFlash('msg'); ?>
                </div>
            <?php endif ?>

            <select class="form-control" id="foreign" name="foreign" >
                <option value="0">Chileno - extranjero con rut</option>
                <option value="1">Extranjero - I dont have a RUT number</option>
            </select>

            <input class="form-control" id="rut" name="rut" type="text" placeholder="RUT">
            <input class="form-control" name="telephone" id="telephone" placeholder="Teléfono"  type="text">
            <input class="datetimepicker form-control" id="birth" name="birth" placeholder="Fecha de nacimiento" >
            <span class="note">La fecha debe coincidir con la fecha de nacimiento de tu licencia.</span>
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
                <label class="form-control">Como nos conociste?</label>
                <div class="col-md-offset-1">
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
                        <input type="radio" id="another" name="como" value="otro" />Otro
                    </label>
                </div>
            </div>

            <p class="alert"></p>
            <div class="row">
                <div class="col-md-offset-7 col-md-5" style="padding: 0">
                    <button class="btn-a-primary btn-block" id="save" data-id="<?php echo $User->getId(); ?>" onclick="validateForm()">Finalizar</button>
                </div>
            </div>
            <div class="hidden-xs space-100"></div>
        </div>
    </div>
</div>

<!-- Div mensaje desplegable -->
<div class="row">
    <div class="col-md-offset-1 col-md-10">
        <div class="BCW" id="message" style="display: none">
                <p class="text-center final-ask"></p>
                <div class="row">
                        <button class="col-md-4 btn-a-action" style="" onclick="location.href='<?php echo url_for('main/index') ?>'">Quiero Arrendar un Auto</button>
                        <button class="col-md-offset-4 col-md-4 btn-a-action" onclick="location.href='<?php echo url_for('main/AddCarFromRegister') ?>'">Quero Subir mi Auto</button>
                </div>
        </div>
    </div>
</div>

<div class="hidden-xs space-100"></div>

<script type="text/javascript">
    
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
        var userId         = $("#save").data("id"); 
        var rut            = $("#rut").val();
        var foreign        = $("#foreign option:selected").val();
        var telephone      = $("#telephone").val();
        var birth          = $("#birth").val();
        var address        = $("#address").val();
        var commune        = $("#commune option:selected").val();
        var region         = $("#region option:selected").val();

        $.post("<?php echo url_for('main/doCompleteRegister') ?>", {"como": como, "userId": userId, "rut": rut, "foreign": foreign, "telephone": telephone, "birth": birth, "address": address, "commune": commune, "region": region}, function(r){

            $(".alert").removeClass("alert-a-danger");
            $(".alert").removeClass("alert-a-success");

            if (r.error) {
                $(".alert").addClass("alert-a-danger");
                $(".alert").html(r.errorMessage);
            } else {
                $("#message p:first-child").html(r.message);
                $("#message").removeAttr("style");
                $("#frm").css("display", "none");
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