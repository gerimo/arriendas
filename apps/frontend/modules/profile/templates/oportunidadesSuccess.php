<?php use_stylesheet('calendario.css') ?>
<?php use_stylesheet('registro.css') ?>
<?php use_stylesheet('mis_arriendos.css') ?>
<?php use_stylesheet('comunes.css?v=02052014') ?>
<?php use_javascript('pedidos.js?v=13112014') ?>
<?php use_stylesheet('cupertino/jquery-ui.css') ?>
<?php
$useragent=$_SERVER['HTTP_USER_AGENT'];
if(preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i',$useragent)||preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i',substr($useragent,0,4))){
  use_stylesheet('moviles.css');
}
?>

<script type="text/javascript">
    var urlEliminarPedidosAjax = <?php echo "'".url_for("profile/eliminarPedidosAjax")."';" ?>
    var urlCambiarEstadoPedido = <?php echo "'".url_for("profile/cambiarEstadoPedidoAjax")."';" ?>
    var urlEditarFecha = <?php echo "'".url_for("profile/editarFechaPedidosAjax")."';" ?>
    var urlPedidos = <?php echo "'".url_for("profile/pedidos")."';" ?>
    var urlPago = <?php echo "'".url_for("profile/fbDiscount")."';" ?>
    var urlExtenderReserva = <?php echo "'".url_for("profile/extenderReservaAjax")."';" ?>
    var urlCars = <?php echo "'".url_for("cars/GetByReserserVehicleType")."';" ?>
    var urlNuevoFlujoAceptarOportunidad = <?php echo "'".url_for("opportunityAccept", array("reserve_id" => "reservePattern", "signature" => "signaturePattern"))."';" ?>
</script>

<div class="main_box_1">
    <div class="main_box_2">


        <?php include_component('profile', 'profile') ?>

        <!--  contenido de la seccion -->
        <div class="main_contenido">
            <div style="display:none">
                <div id='confirmarContratosPropietario'>
                    <div style="width:100%; float:left">
                        <select id="carsSelect" style="width: 50%;margin-left: auto;margin-right: auto;display: block;margin-bottom: 10px;"></select>
                    </div>
                    <input type="checkbox" name="contratoPropietario1" id="contratoPropietario1" class='checkboxContrato'><p> El <a href='' class='informeDanios' target='_blank'>Informe de daños de mi veh&iacute;culo</a> se encuentra completo.</p>
                    <input type="checkbox" name="contratoPropietario2" id="contratoPropietario2" class='checkboxContrato'><p> El arrendatario se hará responsable por daños producidos por debajo del deducible de 5UF.</p>
                    <input type="checkbox" name="contratoPropietario3" id="contratoPropietario3" class='checkboxContrato'><p> <span id='textoBencinaPropietario'>Si el arriendo es por menos de un día, el arrendatario me pagará en efectivo la bencina utilizada según los kilómetros manejados. Si el arriendo es por un día o más, el arrendatario me devolverá el auto con el marcador de bencina en el mismo nivel con el que lo se lo entregué.</span></p>
                </div>
            </div>

            <div class="barraSuperior">
              <p>OPORTUNIDADES</p>
            </div>

            <?php
              $mostrarReservasRecibidas = false;

              if($reservasRecibidas){
                  foreach ($reservasRecibidas as $reserva) {
                      $mostrarReservasRecibidas = true;
                  }
              }
            ?>
    
            <?php if($mostrarReservasRecibidas){ ?>
              <div id="conten_opcion1">
            <?php } ?>

            <?php
              $mostrar = false;
              $checkMostrar=0;

              if($reservasRecibidas){
                  foreach ($reservasRecibidas as $reserva) {
                      if(isset($reserva['estado']) && ($reserva['estado']==0 || $reserva['estado']==1)){
                          $mostrar = true;
  			                  $checkMostrar++;
                      }
                  }
              }
            ?>
            
            <?php if($mostrar): ?>

            <h3>OPORTUNIDADES</h3>
            <p class='textoAyuda'>Has recibido un pedido de reserva. Perderás el arriendo si tardas más de 48 horas en contestar o si un competidor tuyo lo aprueba antes.</p>
            <!-- contenido del acordeón -->
            <?php
            foreach ($reservasRecibidas as $reserva) {
                    if(isset($reserva['estado'])){
                        switch ($reserva['estado']) {
                            case 0:
                                   echo"<div class='bloqueEstado idCar_".$reserva['carId']."' id='bloque_".$reserva['idReserve']."'>";
                                   echo "<div class='checkboxOpcion'>";
                                       echo "<input type='checkbox' class='checkbox checkboxEnEsperaRecibidos' id='checkbox_".$reserva['idReserve']."'>";
                                   echo "</div>";
                                   echo "<div class='fechaReserva'>";
                                       echo "<div class='izq'>";
                                           echo $reserva['fechaInicio']."<br>".$reserva['fechaTermino'];
                                       echo "</div>";
                                       echo "<div class='der'>";
                                           echo $reserva['horaInicio']."<br>".$reserva['horaTermino'];
                                       echo "</div>";
                                   echo "</div>";
                                   echo "<div class='infoUsuario ocultarWeb'>";
                                       echo "<div class='izq'>";
                                           echo "<a href='".url_for('profile/publicprofile?id='.$reserva['contraparteId'])."' title='Ver perfil'>";
                                           if($reserva['facebook']!=null && $reserva['facebook']!=""){
                                               echo "<img src='".$reserva['urlFoto']."' class='img_usuario'/>";
                                           }else{
                                               if($reserva['urlFoto']!=""){
                                                   $filename = explode("/", $reserva['urlFoto']);
                                                   echo image_tag("users/".$filename[Count($filename) - 1], 'class=img_usuario');
                                               }else{
                                                   echo image_tag('img_registro/tmp_user_foto.jpg', 'class=img_usuario');
                                               }
                                           }
                                           echo "</a>";
                                       echo "</div>";
                                       echo "<div class='der'>";
                                           echo "<a href='".url_for('profile/publicprofile?id='.$reserva['contraparteId'])."'><span class='textoMediano'>".$reserva['nombre']." ".$reserva['apellidoCorto']."</span></a>";
                                       echo "<br>".$reserva['comuna'];
                                       echo "</div>";
                                   echo "</div>";
                                   echo "<div class='precio'>";
                                       echo "<a class='nombreMovil' href='".url_for('profile/publicprofile?id='.$reserva['contraparteId'])."'><span class='textoMediano nombreMovil'>".$reserva['nombre']." ".$reserva['apellidoCorto']."</span></a>";
                                       echo "<span class='textoGrande2'>$".$reserva['valor']."</span> CLP<br>(".$reserva['tiempoArriendo']." - asegurado)";
                                   echo "</div>";
                                   echo"<div class='eventoReserva'>";
                                       echo "<div class='der'>";
                                           echo "<div class='cargando'>".image_tag('../images/ajax-loader.gif')."</div>";
                                           echo "<div class='img'>".image_tag('img_pedidos/IconoEnEspera.png')."</div>";
                                           echo "<div class='texto'>En espera de confirmaci&oacute;n</div>";
                                       echo "</div>";
                                   echo"</div>";
                                   echo"<div class='pagoCheckbox'>";
                                           echo "<select class='select enEspera duracion_".$reserva['duracion']."' id='select_".$reserva['idReserve']."' data-impulsive='".$reserva['isImpulsive']."' data-signature='".$reserva['signature']."'>";
                                              echo "<option name='none' selected>Acci&oacute;n</option>";
                                              echo "<option name='oportunidad'>Pre aprobar</option>";
                                           echo "</select>";
                                   echo "</div>";
                                   echo"</div>";

                                break;

                            case 1:
                                echo"<div class='bloqueEstado idCar_".$reserva['carId']."' id='bloque_".$reserva['idReserve']."'>";
                                   echo "<div class='checkboxOpcion'>";
                                       echo "<input type='checkbox' class='checkbox checkboxEnEsperaRecibidos' id='checkbox_".$reserva['idReserve']."'>";
                                   echo "</div>";
                                   echo "<div class='fechaReserva'>";
                                       echo "<div class='izq'>";
                                           echo $reserva['fechaInicio']."<br>".$reserva['fechaTermino'];
                                       echo "</div>";
                                       echo "<div class='der'>";
                                           echo $reserva['horaInicio']."<br>".$reserva['horaTermino'];
                                       echo "</div>";
                                   echo "</div>";
                                   echo "<div class='infoUsuario ocultarWeb'>";
                                       echo "<div class='izq'>";
                                           echo "<a href='".url_for('profile/publicprofile?id='.$reserva['contraparteId'])."' title='Ver perfil'>";
                                           if($reserva['facebook']!=null && $reserva['facebook']!=""){
                                               echo "<img src='".$reserva['urlFoto']."' class='img_usuario'/>";
                                           }else{
                                               if($reserva['urlFoto']!=""){
                                                   $filename = explode("/", $reserva['urlFoto']);
                                                   echo image_tag("users/".$filename[Count($filename) - 1], 'class=img_usuario');
                                               }else{
                                                   echo image_tag('img_registro/tmp_user_foto.jpg', 'class=img_usuario');
                                               }
                                           }
                                           echo "</a>";
                                       echo "</div>";
                                       echo "<div class='der'>";
                                           echo "<a href='".url_for('profile/publicprofile?id='.$reserva['contraparteId'])."'><span class='textoMediano'>".$reserva['nombre']." ".$reserva['apellidoCorto']."</span></a>";
                                       echo "<br>".$reserva['comuna'];
                                       echo "</div>";
                                   echo "</div>";
                                   echo "<div class='precio'>";
                                       echo "<a class='nombreMovil' href='".url_for('profile/publicprofile?id='.$reserva['contraparteId'])."'><span class='textoMediano nombreMovil'>".$reserva['nombre']." ".$reserva['apellidoCorto']."</span></a>";
                                       echo "<span class='textoGrande2'>$".$reserva['valor']."</span> CLP<br>(".$reserva['tiempoArriendo']." - asegurado)";
                                   echo "</div>";
                                   echo"<div class='eventoReserva'>";
                                       echo "<div class='der'>";
                                           echo "<div class='cargando'>".image_tag('../images/ajax-loader.gif')."</div>";
                                           echo "<div class='img'>".image_tag('img_pedidos/IconoRechazado.png')."</div>";
                                           echo "<div class='texto'>Ganada por otro dueño</div>";
                                       echo "</div>";
                                   echo"</div>";
                                   echo"<div class='pagoCheckbox'>";
                                   echo "</div>";
                               echo"</div>";
                               break;

                            default:
                                break;
                        }
                    }
            }?>
        <?php endif; ?>
            <?php
            if ($checkMostrar==0){
                    echo "<p class='alerta' style='margin-bottom: 30px;'>No existen oportunidades.";
            }

            ?>

            <?php if($mostrarReservasRecibidas){ ?>
            </div>
            <?php } ?>


        </div><!-- main_contenido -->

        <?php include_component('profile', 'colDer') ?>  

        <div class="clear"></div>
    </div><!-- main_box_2 -->
</div>
<script>
function redireccion() {
    document.location.href="<?php echo url_for('profile/edit?redirect=oportunidades'); ?>";
}
</script>