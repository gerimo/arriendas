<?php use_stylesheet('mis_arriendos.css') ?>
<?php use_stylesheet('comunes.css') ?>
<?php use_stylesheet('pagoReserva.css') ?>
<?php
$useragent=$_SERVER['HTTP_USER_AGENT'];
if(preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i',$useragent)||preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i',substr($useragent,0,4))){
  use_stylesheet('moviles.css');
}
?>

<!-- Google Code for Pago de reserva Conversion Page -->

<script type="text/javascript">
/* <![CDATA[ */
var google_conversion_id = 996876210;
var google_conversion_language = "en";
var google_conversion_format = "2";
var google_conversion_color = "ffffff";
var google_conversion_label = "LLsrCL71swQQsr-s2wM";

var google_conversion_value = 0;
/* ]]> */
</script>
<script type="text/javascript" src="http://www.googleadservices.com/pagead/conversion.js">
</script>
<noscript>
<div style="display:inline;">
<img height="1" width="1" style="border-style:none;" alt="" src="http://www.googleadservices.com/pagead/conversion/996876210/?value=0&amp;label=LLsrCL71swQQsr-s2wM&amp;guid=ON&amp;script=0"/>
</div>
</noscript>

<style>
#contenido{
  margin-left: 10px;
  margin-right: 10px;
}
#contenido p{
  font-size: 13px;
  margin-top: 15px;
  float: left;
  width: 100%;
}
#contenido a{
  font-size: 15px;
}
</style>

<div class="main_box_1">
  <div class="main_box_2">

  	    <?php include_component('profile', 'profile') ?>

        <div class="main_contenido" style="height: 740px;">
           <div class="barraSuperior">
            <p>PAGO EXITOSO</p>
            </div>
            
            <div id='contenido'>
<center>
            <b style="color:#EC008C;font-size:18px;text-align:center;display: block;margin-top: 30px;">¡TU PAGO SE RECIBIÓ EXITOSAMENTE!</b></center>
            <div style="width:96%;height:2px;border-bottom:1px solid #EC008C;margin-left:10px;margin-top: 10px;"></div>
            <!--
            <br>
            <p>Antes y despu&eacute;s de realizar la reserva, debes firmar el <a href='http://www.arriendas.cl/main/generarFormularioEntregaDevolucion/tokenReserve/<?php echo $tokenReserve; ?>' target='_blank'>informe de da&ntilde;os impreso</a>, o <a href='http://www.arriendas.cl/profile/formularioEntrega/idReserve/<?php echo $idReserva; ?>' target='_blank'>completarlo desde tu celular</a>.</p>
            <br>
            -->
			<br>

      <div style="display:block;width: 100%;height: 210px;">
        <div style="display:block;float: left;margin-left: 40px;margin-top: 40px;">
          <!-- Imagen de auto sólo utilizada para el desarrollo -->
          <?php echo image_tag('http://res.cloudinary.com/arriendas-cl/image/fetch/w_185,h_185,c_fill,g_center/http://arriendas.cl/uploads/cars/13941179554095.jpg', array("width"=>"130","height"=>"130")) ?>
        </div>
        <div style="display:block;width: 300px;height: 180px;float: left;margin-left: 20px;margin-top: 20px;">
          <div style="display:block;width: 230px;border-bottom: 1px solid #B7B7B7;color:#27abe2;font-size: 16px;font-style: italic;padding-bottom: 5px;">Datos del dueño</div>
          <div style="font-size:13px;color:black;">
            <p>Nombre | <b><?=$nameOwner?> <?=$lastnameOwner?></b></p>
            <!-- Dirección sólo utilizada para el desarrollo -->
              <div style="float:left;margin-top: 15px;">Dirección |</div>
              <div style="float:left;width: 220px;margin-left: 4px;margin-top: 15px;"><b>Los Pescadores del Cardenal Raúl Silva Henríquez 2222,Providencia</b></div>
            <p>Fono | <b>(+569) <?=$telephoneOwner?></b></p>
            <p>Correo | <b><?=$emailOwner?></b></p>
          </div>
        </div>
      </div>

			<p style="font-size: 12px;font-weight:bold;color:#a2a2a2;text-align:center;line-height: 1.4em">* Te hemos enviado estos datos a tu correo junto con el contrato<br> y el formulario que deberás firmar para poder retirarlo.</p>

      <div style="display: block;margin: 0 auto;width: 400px;height: 290px;margin-top: 110px;">
        <?php echo image_tag('puntopagos/20140213 Graf_Pantalla_pago_-02.png', array("width"=>"27","height"=>"27","style"=>"float:left;")) ?>
        <div style="display:block;width: 90%;border-bottom:1px solid #EC008C;color:#EC008C;font-size: 17px;float: left;margin-top: 3px;font-weight: bold;margin-left: 10px;padding-bottom: 5px;">PARA ACCEDER A TU SEGURO</div>

        <div style="display:block;width: 362px;height: 250px;float:left;margin-top: 20px;margin-left: 10px;">

          <?php echo image_tag('puntopagos/20140213 Graf_Pantalla_pago_-04.png', array("width"=>"17","height"=>"16","style"=>"float:left;margin-top: 7px;")) ?>
          <div style="float:left;width: 336px;border-bottom:1px solid black;padding-bottom: 10px;margin-left: 8px;font-size: 12px;font-weight: bold;line-height: 1.2em;">En caso de siniestro debes realizar la constancia en Carabineros de inmediato.</div>

          <?php echo image_tag('puntopagos/20140213 Graf_Pantalla_pago_-04.png', array("width"=>"17","height"=>"16","style"=>"float:left;margin-top: 14px;")) ?>
          <div style="float:left;width: 336px;margin-left: 8px;font-size: 12px;font-weight: bold;margin-top: 10px;line-height: 1.2em;">Al retirar y devolver el auto debes firmar el <span style="color:#00adee">Formulario de Entrega y Devolución.</span></div>
          <?php echo image_tag('puntopagos/20140213 Graf_Pantalla_pago_-03.png', array("width"=>"295","height"=>"75","title"=>"Haz click para descargar formulario","style"=>"margin-top: 35px;margin-left: 35px;cursor:pointer;")) ?>
          <a href="<?php echo url_for("profile/pedidos")?>" title="Finalizar" style="color:#00adee;text-decoration:none;font-size: 18px;text-align:center;margin-top: 40px;margin-left: 144px;float: left;">Finalizar</a>
        </div>
      </div>
			</div>
      	</div> 

        <?php //include_component('profile', 'colDer') ?>
  </div>		
</div>