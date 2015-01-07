<link href="/css/newDesign/register.css" rel="stylesheet" type="text/css">

<div class="hidden-xs space-100"></div>
<div class="visible-xs space-50"></div>
<div class="row">
    <div class="col-md-offset-3 col-md-6">
        <div class="BCW" id="message" style="display: none">
            <p>Registro satisfactorio. Hemos enviado...</p>
        </div>
        <div class="BCW">
            <h1>Registro</h1>
            <h2>Datos Personales</h2>

            <input class="form-control" id="firstname" name="firstname" type="text" placeholder="Nombre">
            <input class="form-control" id="lastname" name="lastname" type="text" placeholder="Apellido paterno">
            <input class="form-control" id="motherLastname" name="motherLastname" type="text" placeholder="Apellido materno">
            <input class="form-control" id="email" name="email" type="text" placeholder="Email">
            <input class="form-control" id="emailAgain" name="emailAgain" type="text" placeholder="Confirma tu email">
            <input class="form-control" id="password" name="password" type="password" placeholder="Contraseña">

            <select class="form-control" id="foreign" name="foreign" >
                <option value="0">Chileno</option>
                <option value="1">Extranjero</option>
            </select>


            <input class="form-control" id="rut" name="rut" type="text" placeholder="RUT">
            <input class="form-control" id="telephone" name="telephone" placeholder="Teléfono" ype="text">
            <input class="datetimepicker form-control" id="birth" name="birth" placeholder="Fecha de nacimiento" type="text">
            <input class="form-control" id="address" name="address" placeholder="Dirección #111" type="text">

            <select class="form-control" id="region" name="region" onchange="cargarComunas(this.value)">
                <?php foreach ($Regions as $r): ?>
                    <option value="<?php echo $r["codigo"] ?>"><?php echo $r["nombre"] ?></option>
                <?php endforeach ?>
            </select>

            <select class="form-control" id="commune" name="commune">
                <option value="0">Selecciona tu comuna</option>
            </select>

            <div class="alert"></div>

            <div class="row">

                    <div class="col-md-offset-7 col-md-5" style="padding: 0">
                        <button class="btn-a-primary btn-block" id="save" onclick="validateForm()">Registrar</button>
                    </div>
            </div>

            <hr>

        </div>
    </div>
</div>
<div class="hidden-xs space-100"></div>  
<script type="text/javascript">
$(document).ready(function() { 

    /*$('#rut').live('focusout', function() {
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

        $("#rut").attr("value",runCorregido);

    });
*/

    // Funcion que deshabilita el input "rut", cuando es un extranjero.
    $('#foreign').click(function() {
        $('#rut').val('');
        if($('#rut').attr('disabled')){
            $('#rut').attr('disabled', false);
            $('#rut').css('background-color', '#FFFFFF');
            $("#rut").parent("label").find("span").text('Rut');
        }else{
            $('#rut').attr('disabled', true);
            $('#rut').css('background-color', '#FFFADC');
            $("#rut").parent("label").find("span").text('');
            }
    });
});
    function cargarComunas(idRegion){
    
        $.ajax({
            method: 'POST' ,
            url: <?php echo "'" . url_for('main/getComunas') . "'"; ?>,
            data: 'idRegion=' + idRegion,
            success: function(data){
                $('#commune').html(data);
            }
        });
    }

    function validateForm() {

        var firstname      = $("#firstname").val();
        var lastname       = $("#lastname").val();
        var motherLastname = $("#motherLastname").val();
        var email          = $("#email").val();
        var emailAgain     = $("#emailAgain").val();
        var rut            = $("#rut").val();
        var password       = $("#password").val();
        var foreign        = $("#foreign option:selected").val();
        var telephone      = $("#telephone").val();
        var birth          = $("#birth").val();
        var address        = $("#address").val();
        var commune        = $("#commune option:selected").val();
        var region         = $("#region option:selected").val();

        $.post("<?php echo url_for('main/doRegister') ?>", {"firstname": firstname, "lastname": lastname, "motherLastname": motherLastname, "email": email, "emailAgain": emailAgain, "rut": rut, "password": password, "foreign": foreign, "telephone": telephone, "birth": birth, "address": address, "commune": commune, "region": region}, function(r){

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