<link href="/css/newDesign/mobile/inbox.css" rel="stylesheet" type="text/css">
<?php use_helper("jQuery")?>

<?php
  $cantidadConversaciones = count($listaConversaciones);
  $cantidadConversacionesOrdenadas = count($conversacionesOrdenadas);
?>

<div class="hidden-xs space-100"></div>
<div class="visible-xs space-50"></div>

<div class="row">
    <div class="col-md-offset-2 col-md-8">
        <div class="BCW clearfix">
    
        <div id="confirmarEliminar" style="display:none;"><p>¿Está seguro de eliminar esta conversación?</p></div>
        <h1>Bandeja de entrada</h1>

        <?php echo form_tag('messages/eliminarConversation', array('method' => 'post', 'enctype' => 'multipart/form-data', 'id' => 'frmEliminarConversacion')); ?>
          
          <div class="conversaciones col-xs-12 col-sm-12 col-md-12">
            
            <?php if($cantidadConversacionesOrdenadas > 0): ?>

              <?php for($k=0;$k<$cantidadConversacionesOrdenadas;$k++): ?>
                <?php $myId = $user_id; ?>

                <?php for($i=0;$i<$cantidadConversaciones;$i++): ?>

                  <?php if($conversacionesOrdenadas[$k]['idConversacion'] == $listaConversaciones[$i]->getId()): ?>

                    <?php if($listaConversaciones[$i]->getUserFrom()->getId() != $myId): ?>

                      <?php if($conversacionesOrdenadas[$k]['tipoMensaje'] == "NoLeido"): ?>
                        <div class="encabezadoConversacionAmarillo col-xs-12 col-sm-12 col-md-12">

                      <?php else: ?>
                        <div class="encabezadoConversacion col-xs-12 col-sm-12 col-md-12">

                      <?php endif ?>

                        <?php echo "<a href='".url_for('messages', array('id' => $conversacionesOrdenadas[$k]['idConversacion']))."' title='Ir a la conversación'>"; ?>
                        
                          <div class="msg_user_frame hidden-xs col-sm-2 col-md-2">
                            <?php include_component("profile","pictureFile",array('inboxHeight'=> '100', 'inboxWidth'=> '100', "user"=>$listaConversaciones[$i]->getUserFrom(),"params"=>"width='74px' height='74px'"));?>
                          </div> 

                          <div class="costado_izquierdo col-xs-10 col-sm-8 col-md-8">
                            <div class = "row">

                              <div class="msg_user_nombre col-xs-3 col-sm-3 col-md-3">
                                <?php echo ucwords($listaConversaciones[$i]->getUserFrom()->getFirstName())." ".ucwords($listaConversaciones[$i]->getUserFrom()->getLastName());?>
                              </div>
                              
                              <div class="indicador col-xs-1 col-sm-1 col-md-1 pull-right">
                                <?php if($conversacionesOrdenadas[$k]['tipoMensaje'] == "NoLeido"): ?>
                                  <?php echo ""; ?>
                                <?php endif ?>

                                <?php if($conversacionesOrdenadas[$k]['tipoMensaje'] == "Leido"): ?>
                                  <?php echo ""; ?>
                                <?php endif ?>

                                <?php if($conversacionesOrdenadas[$k]['tipoMensaje'] == "Enviado"): ?>
                                  <?php echo image_tag('Flecha.png','class=flechaEnviado'); ?>
                                <?php endif ?>
                              </div>

                              <div class="body_msgInbox col-xs-5 col-sm-5 col-md-5"><p><?=$conversacionesOrdenadas[$k]['bodyMensaje'];?></p></div>
                              <div class="fecha_msg col-xs-3 col-sm-3 col-md-3"><?=$conversacionesOrdenadas[$k]['dateMensaje'];?></div>
                            
                            </div>
                          </div>

                        </a>

                        <div class="eliminar_msgInbox col-xs-2 col-sm-2 col-md-2">
                            <input class="botonEliminarConversacion" name="<?=$conversacionesOrdenadas[$k]['idConversacion'];?>" value="Eliminar" type="button"></input>
                        </div>

                      </div>

                    <?php else: ?>

                      <?php if($conversacionesOrdenadas[$k]['tipoMensaje'] == "NoLeido"): ?>
                        <div class="encabezadoConversacionAmarillo col-xs-12 col-sm-12 col-md-12">

                      <?php else: ?>
                        <div class="encabezadoConversacion col-xs-12 col-sm-12 col-md-12">

                      <?php endif ?>

                        <?php echo "<a href='".url_for('messages', array('id' => $conversacionesOrdenadas[$k]['idConversacion']))."' title='Ir a la conversación'>"; ?>
                        
                          <div class="msg_user_frame hidden-xs col-sm-2 col-md-2">
                            <?php include_component("profile","pictureFile",array('inboxHeight'=> '100', 'inboxWidth'=> '100', "user"=>$listaConversaciones[$i]->getUserTo(),"params"=>"width='74px' height='74px'"));?>
                          </div>

                          <div class="costado_izquierdo col-xs-10 col-sm-8 col-md-8">

                            <div class="msg_user_nombre col-xs-3 col-sm-3 col-md-3">
                              <?php echo ucwords($listaConversaciones[$i]->getUserTo()->getFirstName())." ".ucwords($listaConversaciones[$i]->getUserTo()->getLastName());?>
                            </div>

                            <div class="indicador col-sm-1 col-sm-1 col-md-1 pull-right">
                              <?php if($conversacionesOrdenadas[$k]['tipoMensaje'] == "NoLeido"): ?>
                                <?php echo ""; ?>
                              <?php endif ?>

                              <?php if($conversacionesOrdenadas[$k]['tipoMensaje'] == "Leido"): ?>
                                <?php echo ""; ?>
                              <?php endif ?>

                              <?php if($conversacionesOrdenadas[$k]['tipoMensaje'] == "Enviado"): ?>
                                <?php echo image_tag('Flecha.png','class=flechaEnviado'); ?>
                              <?php endif ?>
                            </div>

                            <div class="body_msgInbox col-xs-5 col-sm-5 col-md-5"><p><?=$conversacionesOrdenadas[$k]['bodyMensaje'];?></p></div>
                            <div class="fecha_msg col-xs-3 col-sm-3 col-md-3"><?=$conversacionesOrdenadas[$k]['dateMensaje'];?></div>
                          </div>

                        </a>

                          <div class="eliminar_msgInbox col-xs-2 col-sm-2 col-md-2">
                            <input class="botonEliminarConversacion" name="<?=$conversacionesOrdenadas[$k]['idConversacion'];?>" value="Eliminar" type="button"></input>
                          </div>

                      </div>

                    <?php endif ?>

                  <?php endif ?>

                <?php endfor ?>

              <?php endfor ?>

            <?php else: ?>
              <?php echo "<div class='noHay'><p>No existen conversaciones</p></div>"; ?>

            <?php endif ?>
          
          </div>
          <input id="opcionEliminar" type="hidden" name="opcionDelete" value="" />
        </form>
        </div>
    </div>
</div>

<div class="clear"></div>

<div class="hidden-xs space-100"></div>
<div class="visible-xs space-50"></div>


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