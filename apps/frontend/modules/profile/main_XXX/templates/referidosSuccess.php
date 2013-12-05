<script>

function cargarVentana() {
  document.getElementById("boton_condiciones").disabled=false;
}

function redirigir() {
  window.location="referidos2";
}

</script>
<div style="width: 600px;  margin-left:auto; margin-right: auto; height: 150px;">
Programa de Referidos<br /><br />
Gana 7% en comisiones sobre todo lo que ganen o gasten durante el 2013 tus amigos referidos a trav&eacute;s de
la aplicaci&oacute;n<br />
Adicionalmente, gana 1% sobre todo lo que ganen o gasten durante 2013 los amigos invitados por tus
referidos hasta la primera l&iacute;nea usando esta aplicaci&oacute;n<br />
Se repartir&aacute; y prorratear&aacute; entre los usuarios hasta el 8% en comisiones por transacci&oacute;n<br />

<br /><br />
<input type="checkbox" id="check_condiciones" onchange="cargarVentana();"/>Acepto las condiciones de la promoci&oacute;n <input id="boton_condiciones" type="submit" onclick="redirigir();" disabled/>
</div>






