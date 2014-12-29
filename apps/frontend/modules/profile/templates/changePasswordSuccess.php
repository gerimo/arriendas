<link href="/css/newDesign/changePassword.css" rel="stylesheet" type="text/css">

<div class="hidden-xs space-100"></div>
<div class="visible-xs space-50"></div>

<div class="row">
    <div class="col-md-offset-4 col-md-4 BCW">

        <h2>Cambiar contraseña</h2>

        <input class="form-control" id="password" name="password" type="password" placeholder="Nueva contraseña">
        <input class="form-control" id="passwordAgain" name="passwordAgain" type="password" placeholder="Repita nueva contraseña">

        <div class="alert"></div>

        <div class="row">
            <div class="col-md-offset-7 col-md-5" style="padding: 0">
                <button class="btn-a-primary btn-block" id="change">Cambiar</button>
            </div>
        </div>

        <hr>

        <p class="note"><strong>Nota:</strong> Al cambiar su contraseña, podrá iniciar sesión tanto con Facebook cómo con su correo asociado a la cuenta de Facebook.</p>
    </div>
</div>

<div class="hidden-xs space-100"></div>

<script>
    $(document).on("click", "#change", function(){

        var pass1 = $("#password").val();
        var pass2 = $("#passwordAgain").val();
    
        $.post("<?php echo url_for('profile/doChangePassword') ?>", {"pass1": pass1, "pass2": pass2}, function(r){

            $(".alert").removeClass("alert-a-success");
            $(".alert").removeClass("alert-a-danger");
    
            if (r.error) {
                $(".alert").addClass("alert-a-danger");
                $(".alert").html(r.errorMessage);
            } else {
                $(".alert").addClass("alert-a-success");
                $(".alert").html("Contraseña cambiada");

                $("#password").val("");
                $("#passwordAgain").val("");
            }
        }, "json");
    });
</script>