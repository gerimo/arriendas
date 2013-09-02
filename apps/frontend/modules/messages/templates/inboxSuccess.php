<div id="conversation">
<?php use_helper("jQuery")?>
<?php use_stylesheet('mensajes.css') ?>
<?php use_stylesheet('messages.css') ?>
<?php use_stylesheet('registro.css') ?>
<?php use_stylesheet('conversaciones.css') ?>
<?php use_stylesheet('cupertino/jquery-ui.css') ?>
<?php
$useragent=$_SERVER['HTTP_USER_AGENT'];
if(preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i',$useragent)||preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i',substr($useragent,0,4))){
  use_stylesheet('moviles.css');
}
?>

<div class="main_box_1">
<div class="main_box_2">


<?php include_component('profile', 'profile') ?>

<!--  contenido de la seccion -->
<div class="main_contenido">
<div id="confirmarEliminar" style="display:none;"><p>¿Está seguro de eliminar esta conversación?<p></div>
<?php echo form_tag('messages/eliminarConversation', array('method' => 'post', 'enctype' => 'multipart/form-data', 'id' => 'frmEliminarConversacion')); ?>

<div class="barraSuperior">
  <p>BANDEJA DE ENTRADA</p>
</div>
<div class="conversaciones">
<?php
  $cantidadConversaciones = count($listaConversaciones);
  $cantidadConversacionesOrdenadas = count($conversacionesOrdenadas);
if($cantidadConversacionesOrdenadas > 0){
    for($k=0;$k<$cantidadConversacionesOrdenadas;$k++){
      $myId = $user_id;
      for($i=0;$i<$cantidadConversaciones;$i++){

        //si el id de la ultima conversacion es igual al id de la lista de conversaciones... entra
        if($conversacionesOrdenadas[$k]['idConversacion'] == $listaConversaciones[$i]->getId()){
          //si esa id no es la propia... entra
          if($listaConversaciones[$i]->getUserFrom()->getId() != $myId){
            if($conversacionesOrdenadas[$k]['tipoMensaje'] == "NoLeido") {
              ?>
              <div class="encabezadoConversacionAmarillo">
              <?php
            }else{
          ?>
              <!-- conversaciones en la que yo soy el user_from_id -->
              <div class="encabezadoConversacion">
          <?php
            }
          ?>
          <?php echo "<a href='".url_for('messages/conversation?id='.$conversacionesOrdenadas[$k]['idConversacion'])."' title='Ir a la conversación'>"; ?>
          <div class="msg_user_frame">
            <?php include_component("profile","pictureFile",array("user"=>$listaConversaciones[$i]->getUserFrom(),"params"=>"width='74px' height='74px'"));?>
          </div>
          <div class="costado_izquierdo">
            <div class="msg_user_nombre">
              <?php echo ucwords($listaConversaciones[$i]->getUserFrom()->getFirstName())." ".ucwords($listaConversaciones[$i]->getUserFrom()->getLastName());?>
            </div>
            <div class="indicador">
                <?php
                if($conversacionesOrdenadas[$k]['tipoMensaje'] == "NoLeido") echo "";
                if($conversacionesOrdenadas[$k]['tipoMensaje'] == "Leido") echo "";
                if($conversacionesOrdenadas[$k]['tipoMensaje'] == "Enviado") echo image_tag('Flecha.png','class=flechaEnviado');
                ?>
              </div>
            <div class="body_msgInbox"><p><?=$conversacionesOrdenadas[$k]['bodyMensaje'];?></p></div>
            <div class="fecha_msg"><?=$conversacionesOrdenadas[$k]['dateMensaje'];?></div>
          </div>
        </a>
          <div class="eliminar_msgInbox">
            <input class="botonEliminarConversacion" name="<?=$conversacionesOrdenadas[$k]['idConversacion'];?>" value="Eliminar" type="button"></input>
          </div>
        </div>

            <?php
          }else{
              if($conversacionesOrdenadas[$k]['tipoMensaje'] == "NoLeido") {
                  ?>
                  <div class="encabezadoConversacionAmarillo">
                  <?php
                }else{
              ?>
                  <!-- conversaciones en la que yo soy el user_to_id -->
                  <div class="encabezadoConversacion">
              <?php
                }
              ?>
          <?php echo "<a href='".url_for('messages/conversation?id='.$conversacionesOrdenadas[$k]['idConversacion'])."' title='Ir a la conversación'>"; ?>
            <div class="msg_user_frame">
              <?php include_component("profile","pictureFile",array("user"=>$listaConversaciones[$i]->getUserTo(),"params"=>"width='74px' height='74px'"));?>
            </div>
            <div class="costado_izquierdo">
              <div class="msg_user_nombre">
                <?php echo ucwords($listaConversaciones[$i]->getUserTo()->getFirstName())." ".ucwords($listaConversaciones[$i]->getUserTo()->getLastName());?>
              </div>
              <div class="indicador">
                <?php
                if($conversacionesOrdenadas[$k]['tipoMensaje'] == "NoLeido") echo "";
                if($conversacionesOrdenadas[$k]['tipoMensaje'] == "Leido") echo "";
                if($conversacionesOrdenadas[$k]['tipoMensaje'] == "Enviado") echo image_tag('Flecha.png','class=flechaEnviado');
                ?>
              </div>
              <div class="body_msgInbox"><p><?=$conversacionesOrdenadas[$k]['bodyMensaje'];?></p></div>
              <div class="fecha_msg"><?=$conversacionesOrdenadas[$k]['dateMensaje'];?></div>
            </div>
          </a>
          <div class="eliminar_msgInbox">
              <input class="botonEliminarConversacion" name="<?=$conversacionesOrdenadas[$k]['idConversacion'];?>" value="Eliminar" type="button"></input>
          </div>
        </div>
            <?php
          }//fin else
        }//fin si padre
      }//fin for hijo
  }//fin for padre
}else{
  echo "<div class='noHay'><p>No existen conversaciones</p></div>";
}
?>
</div>
<input id="opcionEliminar" type="hidden" name="opcionDelete" value="" />
</form>
</div><!-- main_contenido -->

 <?php include_component('profile', 'colDer') ?> 

</div><!-- main_box_2 -->
<div class="clear"></div>
</div><!-- main_box_1 -->
</div>


<script language="javascript">
function gotoConversation(id,message){
var datos = "id="+id+"&message_id="+message;
<?php echo jq_remote_function(array(
  "update"=>"conversation",
  "url"=>"messages/conversation",
  "with"=>"datos",
))?>
}
function deleteMessage(id){
  var datos = "id="+id;
<?php echo jq_remote_function(array(
  "update"=>"conversation",
  "url"=>"messages/delete",
  "with"=>"datos",
))?>
}

function gotoMensajes(){
<?php echo jq_remote_function(array(
  "update"=>"conversation",
  "url"=>"messages/inbox",
))?>
}
</script>
<script type="text/javascript">
$(document).ready(function(){
  $(".botonEliminarConversacion").click(function(){
    id = $(this).attr("name");
    $("#opcionEliminar").attr("value", id);
    $("#confirmarEliminar").dialog({
        resizable: false,
        width: 300,
        height: 120,
        modal: false,
        autoOpen: false,
        closeOnEscape: false,
        title: 'Pregunta',
        position: { my: "center", at: "center"},
        buttons: {
            Si: function() {
                $('#frmEliminarConversacion').submit();
                $(this).dialog( "close" );
            },
            No: function() {
                $(this).dialog( "close" );
            }
        }
    });
    $("#confirmarEliminar").dialog("open");
  });
});
</script>