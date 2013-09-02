<script>
    
    function enviarSMS() {
        alert("Se enviara SMS al numero " + $("#fono_ingresado").html());
        $("#ingresoCodigo").show();
        $.ajax({
        type: "GET",
        url: "<?php echo url_for("profile/enviarConfirmacionSMS")."?numero="; ?>" + $("#fono_ingresado").html(),
        });
    }
        
    function editarFono() {
        $("#fono_editable").show();
        $("#fono").hide();
        
    }
    
    function fonoEditado() {
        $("#fono_ingresado").html($("#ingreso_fono").val());
        $("#fono_editable").hide();
        $("#fono").show();
    }
    
    function confirmarCodigo() {
        $.ajax({
        type: "GET",
        url: "<?php echo url_for("profile/validarSMS?token="); ?>" + $("#codigoIngresado").val(),
        success: function(data) {
            if(data == 1) {
                alert("numero validado");
            } else {
                alert("codigo incorrecto");
            }
        }
        });
    }
    
</script>
<div>De acuerdo a nuestros registros, tu tel&eacute;fono es el
<span id="fono"><span id="fono_ingresado">96996794</span> <a href="#" onclick="editarFono();">cambiar</a></span>
<span id="fono_editable" style="display: none;">
    <input type="text" id="ingreso_fono" value="96996794"/><input type="button" value="Listo" onclick="fonoEditado();"/></span>
</div>
<div>Para confirmarlo, presiona el bot&oacute;n confirmar e ingresa el c&oacute;digo de 4 d&iacute;gitos que enviaremos a
tu tel&eacute;fono</div>
<div><input type="button" value="Confirmar" onclick="enviarSMS();"/></div>
<div id="ingresoCodigo" style="display:none;">
Hemos enviado un SMS a tu celular. En cuanto lo recibas, ingresa el c&oacute;digo recibido en el cuadro de abajo<br />    
C&oacute;digo: <input id="codigoIngresado" maxlength="4" type="text" placeholder="ej: 1234"/> <input type="button" value="Ingresar" onclick="confirmarCodigo()"/></div>