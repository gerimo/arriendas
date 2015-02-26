<link href="/css/newDesign/mobile/register.css" rel="stylesheet" type="text/css">

<div class="visible-xs space-50"></div>
<div class="row">
    <div class="col-md-offset-4 col-md-4">

        <div class="BCW" id="frm">
            <h1 class="title-register">Registro</h1>

            <input class="form-control" id="firstname" name="firstname" type="text" placeholder="Nombre">
            <input class="form-control" id="lastname" name="lastname" type="text" placeholder="Apellido paterno">
            <input class="form-control" id="email" name="email" type="text" placeholder="Email">
            <input class="form-control" id="emailAgain" name="emailAgain" type="text" placeholder="Confirma tu email">
            <input class="form-control" id="password" name="password" type="password" placeholder="Contraseña">

            <div class="alert"></div>

            <div class="row">

               <!--  <div class="col-md-6 hidden-xs text-center" style="padding: 0">
                    <div class="hidden-xs space-10"></div>
                    <img class="loading" src="/images/ajax-loader.gif">
                </div> -->
                
                <div class="col-md-6 col-xs-12" style="padding: 0">
                    <button class="btn btn-a-primary btn-block" id="save" onclick="validateForm()">Siguiente</button>
                </div>

            </div>
        </div>

        <!-- Mensaje desplegable -->
        <div class="BCW" id="message" style="display: none">
            <p class="text-center"></p>
            <p class="text-center"><?php echo link_to("Volver al inicio","main/index"); ?> </p>
        </div>
    </div>
</div> 

<script type="text/javascript">
    $(document).ready(function() { 
        $(".loading").hide();
        $('#foreign').click(function() {
            $('#rut').val('');
            if($('#rut').attr('disabled')){
                $('#rut').attr('disabled', false);
                $("#rut").parent("label").find("span").text('Rut');
            }else{
                $('#rut').attr('disabled', true);
                $("#rut").parent("label").find("span").text('');
                }
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

        var firstname      = $("#firstname").val();
        var lastname       = $("#lastname").val();
        var email          = $("#email").val();
        var emailAgain     = $("#emailAgain").val();
        var password       = $("#password").val();
        $(".loading").show();
        $.post("<?php echo url_for('main/doRegister') ?>", {"firstname": firstname, "lastname": lastname, "email": email, "emailAgain": emailAgain, "password": password}, function(r){
            
            $(".alert").removeClass("alert-a-danger");
            $(".alert").removeClass("alert-a-success");

            if (r.error) {
                $(".alert").addClass("alert-a-danger");
                $(".alert").html(r.errorMessage);
            } else {
                window.location.href = r.url_complete;
            }
            $(".loading").hide();
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

    function postAndRedirect(url, postData) {
        var postFormStr = "<form method='POST' action='" + url + "'>\n";

        for (var key in postData)
        {
            if (postData.hasOwnProperty(key))
            {
                postFormStr += "<input type='hidden' name='" + key + "' value='" + postData[key] + "'></input>";
            }
        }

        postFormStr += "</form>";

        var formElement = $(postFormStr);

        $('body').append(formElement);
        $(formElement).submit();
    }
</script>