<link href="/css/newDesign/conversaciones.css" rel="stylesheet" type="text/css">

<div class="hidden-xs space-100"></div>
<div class="visible-xs space-50"></div>

<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-offset-2 col-md-8">

        <div class="BCW" id="frm">

            <h1>Mensajes</h1>

            <?php
                $cantidadMensajes = count($conversacion)-3;
                $idUsuarioTo = $objetoConversacion[0]->getUserToId();
                $idUsuarioFrom = $objetoConversacion[0]->getUserFromId();
                $myId = $user_id;
                $yoSoyElFrom = false;
                $yoSoyElTo = false;
                $idOtroUsuario = null;
                if($myId == $idUsuarioFrom){ //Yo soy el que envia mensajes
                    $yoSoyElFrom = true;
                    $idOtroUsuario = $idUsuarioTo;
                }else if($myId == $idUsuarioTo){//Yo soy el que recibe mensajes
                    $yoSoyElTo = true;
                    $idOtroUsuario = $idUsuarioFrom;
                }
            ?>

            <div class="row topPart">                   
                <span><a href="#"><i class="fa fa-arrow-left"></i> Bandeja de entrada</a></span>
            </div>

            <div class="hidden-xs space-50"></div>
            <div class="visible-xs space-50"></div>

            <div class="row newMessage">

                <div class="imgProfile col-sm-2 col-md-3 hidden-xs text-center">
                    <span class="img-thumbnail">
                        <?php if($yoSoyElFrom): ?>
                            <?php include_component("profile","pictureFile", array("user" => $objetoConversacion[0]->getUserFrom(), "params" => "width=74px height=74px")) ?>
                        <?php elseif($yoSoyElTo): ?>
                            <?php include_component("profile","pictureFile", array("user" => $objetoConversacion[0]->getUserTo(), "params" => "width=74px height=74px")) ?>
                        <?php endif ?>
                    </span>
                </div>

                <div class="col-sm-10 col-md-8">
                    <textarea  class="form-control" name="nuevoMensaje" placeholder="<?php if($yoSoyElTo){ echo 'Escríbele un nuevo mensaje a '.$objetoConversacion[0]->getUserFrom()->getFirstName();}else if($yoSoyElFrom){ echo 'Escríbele un nuevo mensaje a '.$objetoConversacion[0]->getUserTo()->getFirstName();} ?>"><?php echo $comentarios;?></textarea>
                </div>

                <div class="sendButton col-sm-offset-10 col-sm-2 col-md-offset-9 col-md-2">
                    <br>
                    <div id="ajax_loader"><?php echo image_tag('ajax-loader.gif', 'class=img_loader');?></div>
                    <input type="button" class="enviarMensaje btn btn-a-action btn-block" value="Enviar"/>
                </div>
            </div>

            <div class="cargaDeNuevosMensajes"></div>

            <?php for ($i = 0 ; $i < $cantidadMensajes ; $i++): ?>
                <?php if ($conversacion[$i]['userMensaje'] == $idUsuarioFrom): ?> <!-- Si es el emisor -->
                    <?php if ($idUsuarioFrom == $myId): ?>

                        <div class='row'>

                            <div class="col-md-3">
                                <?php include_component("profile","pictureFile",array("user"=>$objetoConversacion[0]->getUserFrom(),"params"=>"width='74px' height='74px'"));?>
                                <div class="row nameProfile">
                                    <?php 
                                        if($yoSoyElFrom) echo "Tú";
                                        else echo "<a target='_blank' href='http://www.arriendas.cl/profile/publitarget='_blank' profile/id/".$conversacion[$i]['userMensaje']."'>".$objetoConversacion[0]->getUserFrom()->getFirstName()."</a>";
                                    ?>
                                </div>
                            </div>

                            <div class="puntaCeleste col-md-1"></div> 

                            <div class="row marcoTextoIzq col-md-7">
                                <div class="texto">
                                    <div class="msg"><p><?=$conversacion[$i]['bodyMensaje'];?></p></div>
                                </div>
                                <div class="horaFecha"><?=$conversacion[$i]['dateMensaje'];?></div>
                            </div>
                        </div>

                    <?php else: ?>

                        <div class='row newMensaje'>
                            <div class="row rightMessage">

                                <div class="row imgProfile">
                                    <?php include_component("profile","pictureFile",array("user"=>$objetoConversacion[0]->getUserFrom(),"params"=>"width='74px' height='74px'"));?>
                                </div>

                                <div class="row nameProfile">
                                    <?php if($yoSoyElFrom) echo "Tú";
                                        else echo "<a target='_blank' href='http://www.arriendas.cl/profile/publicprofile/id/".$conversacion[$i]['userMensaje']."'>".$objetoConversacion[0]->getUserFrom()->getFirstName()."</a>";
                                    ?>
                                </div>

                            </div>

                            <div class="puntaCeleste"></div> 

                            <div class="row marcoTextoDer col-xs-9 col-sm-9 col-md-9">
                                <div class="texto">
                                    <div class="msg"><p><?=$conversacion[$i]['bodyMensaje'];?></p></div>
                                </div>
                                <div class="horaFecha"><?=$conversacion[$i]['dateMensaje'];?></div>
                            </div>
                        </div>

                    <?php endif ?>
                <?php endif ?>

                <?php if ($conversacion[$i]['userMensaje'] == $idUsuarioTo): ?> <!-- Si es el emisor -->
                    <?php if ($idUsuarioTo == $myId): ?>

                        <div class='row newMensaje'>
                            <div class="row leftMessage col-md-1">

                                <div class="row imgProfile">
                                    <?php include_component("profile","pictureFile",array("user"=>$objetoConversacion[0]->getUserTo(),"params"=>"width='74px' height='74px'"));?>
                                </div>

                                <div class="row nameProfile">
                                    <?php 
                                        if($yoSoyElTo) echo "Tú";
                                        else echo "<a target='_blank' href='http://www.arriendas.cl/profile/publicprofile/id/".$conversacion[$i]['userMensaje']."'>".$objetoConversacion[0]->getUserTo()->getFirstName()."</a>";
                                    ?>
                                </div>

                            </div>

                            <div class="puntaBlanca col-md-offset-1 col-md-1"></div>

                            <div class="row marcoTextoIzq col-md-offset-2 col-md-8">
                                <div class="row texto">
                                    <div class="msg"><p><?=$conversacion[$i]['bodyMensaje'];?></p></div>
                                </div>
                                <div class= "horaFecha"><?=$conversacion[$i]['dateMensaje'];?></div>
                            </div>
                        </div>

                    <?php else: ?> <!-- Si no soy yo -->

                        <div class='row newMensaje'>
                            <div class="row rightMessage">

                                <div class="row imgProfile">
                                    <?php include_component("profile","pictureFile",array("user"=>$objetoConversacion[0]->getUserTo(),"params"=>"width='74px' height='74px'"));?>
                                </div>

                                <div class="row nameProfile">
                                    <?php 
                                        if($yoSoyElTo) echo "Tú";
                                        else echo "<a target='_blank' href='http://www.arriendas.cl/profile/publicprofile/id/".$conversacion[$i]['userMensaje']."'>".$objetoConversacion[0]->getUserTo()->getFirstName()."</a>";
                                    ?>
                                </div>
                            </div>

                            <div class="puntaCeleste"></div>

                            <div class="row marcoTextoDer col-xs-9 col-sm-9 col-md-9">
                                <div class="texto">
                                    <div class="msg"><p><?=$conversacion[$i]['bodyMensaje'];?></p></div>
                                </div>
                                <div class="horaFecha"><?=$conversacion[$i]['dateMensaje'];?></div>
                            </div>
                        </div>
            
                    <?php endif ?>
                <?php endif ?>
            <?php endfor ?>

        </div>
    </div>
</div>

<div class="hidden-xs space-100"></div>

<script type="text/javascript">

    var urlGuardarMensaje = <?php echo "'".url_for("messages/guardarMensajeNuevoAjax")."';" ?>
    var idCon = "<?php echo $conversacion['idConversacion']; ?>";
    var idFrom = "<?php echo $myId; ?>";
    var idTo = "<?php echo $idOtroUsuario; ?>";

    $(document).ready(function(){

        $("span.img-thumbnail").find( "img" ).addClass("img-responsive");
        $("div.imgProfile").find( "img" ).addClass("img-responsive");

        $(".enviarMensaje").click(function(){
            var contenido = $(".textoMensaje").val();
            if(contenido == ""){
                alert("Escriba su Mensaje");
            }else{
                //mostrar
                $(".img_loader").fadeIn("hide");
                $.ajax({
                    type: 'POST',
                    url: urlGuardarMensaje,
                    data: {
                        mensajeNuevo: contenido,
                        idConversacion: idCon,
                        idUserFrom: idFrom,
                        idUserTo: idTo
                    }
                }).done(function(texto){
                    $(".cargaDeNuevosMensajes").prepend(texto);
                    //ocultar
                    $(".img_loader").fadeOut("hide");
                    $(".textoMensaje").val('');
                }).fail(function(){
                    alert("Ha ocurrido un error al enviar mensaje");
                    //ocultar
                    $(".img_loader").fadeOut("hide");
                });
            }
        });

        $(".volverAtras").click(function(){
            //location.href="http://localhost/repo_arriendas/web/frontend_dev.php/messages/inbox";
            location.href="http://www.arriendas.cl/messages/inboxp";
        });
    });

</script>