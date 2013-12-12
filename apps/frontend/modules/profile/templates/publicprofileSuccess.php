<?php use_stylesheet('comunes.css') ?>
<?php use_stylesheet('registro.css') ?>
<?php use_stylesheet('calificaciones_profile.css') ?>
<?php use_stylesheet('rating.css') ?>

<div class="main_box_1">
<div class="main_box_2">

<?php include_component('profile', 'profile') ?>

<div class="main_contenido">

    <div class="barraSuperior">
        <p>PERFIL P&Uacute;BLICO DE <?php echo strtoupper($user->getFirstname())." ".strtoupper($user->getLastname()); ?></p>
    </div>

    <div class="usuario_foto_frame">
        <?php include_component("profile", "pictureFile", array("user" => $user, "params" => "width=120px height=120px")); ?>

    </div><!-- usuario_foto_frame -->

    <div id="iconoEnviarMensaje"><a href="<?php echo url_for('messages/new?id='.$user->getId()) ?>" title="Enviar mensaje a <?php echo $user->getFirstname(); ?>"><?php echo image_tag('img_msj/EnviarMsjSombra.png','class=img_iconoEnviarMensaje') ?></a></div>

    <div id="datosPerfil">
        
        <div id="lineaMiembroDesde"><?php echo image_tag('img_evaluaciones/IconoMiembroDesde.png','class=miembroDesde') ?><p><span>Miembro desde | </span><?php echo $antiguedad; ?></p></div>
        
        <div id="lineaNTransacciones"><?php echo image_tag('img_evaluaciones/IconoNumeroTransacciones.png','class=nTransacciones') ?><p><span>N. Transacciones | </span><?php echo $cantidadTransacciones; ?></p></div>
        
        <div id="lineaTiempoDemora"><?php echo image_tag('img_evaluaciones/IconoTiempoDemora.png','class=velocidadRespuesta') ?><p><span>Velocidad de repuesta | </span><?=$velocidadRespuesta;?></p></div>

    </div>
    <div id="evaluacionesPublicProfile">
        <div class="evaluacion">
<?php
                $cantidadDeComentariosSobreMi = count($comentarios);
                if($comentarios[0] == null){
?> 
                        <h3>CALIFICACIONES (0)</h3>
                        <p style="float:left;margin-left:10px;font-size:14px;color:#BCBEB0;font-style:italic;"><?=$user->getFirstname();?> aún no tiene calificaciones</p>
<?php
                }else{
?>
            <h3>CALIFICACIONES (<?=$cantidadDeComentariosSobreMi;?>)</h3>   
            <div class="calificacionEstrellas">
                <div class="puntualidad">
                    <?php echo image_tag('img_evaluaciones/IconoPuntualidad.png','class=img_IconoPuntualidad') ?>
                    <p><span>Puntualidad</span></p>
                    <div class="estrellas">
                        <?php
                            for($i=0; $i<5; $i++){
                                if($i<$puntualidad){//estrella marcada
                                    echo image_tag('img_evaluaciones/IconoEstrellaCeleste.png');
                                }else{//estrella no marcada
                                    echo image_tag('img_evaluaciones/IconoEstrellaGris.png');
                                }
                            }
                        ?>
                    </div>
                </div>
                <div class="limpieza">
                    <?php echo image_tag('img_evaluaciones/IconoLimpieza.png','class=img_IconoLimpieza') ?>
                    <p><span>Limpieza</span></p>
                    <div class="estrellas">
                        <?php
                            for($i=0; $i<5; $i++){
                                if($i<$limpieza){//estrella marcada
                                    echo image_tag('img_evaluaciones/IconoEstrellaCeleste.png');
                                }else{//estrella no marcada
                                    echo image_tag('img_evaluaciones/IconoEstrellaGris.png');
                                }
                            }
                        ?>
                    </div>
                </div>
            </div>

            <?php
                for ($i=0; $i <count($comentarios) ; $i++) {
            ?>

            <div class="comentario">
                <div class="usuario">

                    <?php echo "<a href='".url_for('profile/publicprofile?id='.$comentarios[$i]['idEvaluador'])."'>"; ?>
                    <div class="fotoPosteador">
                        <?php 
                            
                            if($comentarios[$i]['facebookEvaluador']!=null && $comentarios[$i]['facebookEvaluador']!=""){
                                echo "<img src='".$comentarios[$i]['urlFotoEvaluador']."' class='img_usuario'/>";
                            }else{
                                if($comentarios[$i]['urlFotoEvaluador']!=""){
                                    $filename = explode("/", $comentarios[$i]['urlFotoEvaluador']);
                                    echo image_tag("users/".$filename[Count($filename) - 1], 'class=img_usuario');
                                }else{
                                    echo image_tag('img_registro/tmp_user_foto.jpg', 'class=img_usuario');
                                }
                            }
                            
                        ?>
                    </div>
                    <div class="nombrePosteador"><p><?php echo $comentarios[$i]['nombreEvaluador']." ".$comentarios[$i]['apellidoEvaluador']; ?></p></div>
                    </a>

                </div>
                <div class="campoTexto">
                    <?php echo image_tag('img_evaluaciones/TrianguloComentarios.png','class=punta') ?>
                    <div class="margenTexto">
                        <div class="texto">
                            <p><?php echo $comentarios[$i]['comentarioEvaluacion']; ?></p>
                            <div class="detallesComentario">
                                <div class="fecha"><span>|</span><p><?php echo $comentarios[$i]['fechaEvaluacion']; ?></p></div>
                                <div class="limp">
                                    <span>|</span><?php echo image_tag('img_evaluaciones/IconoLimpieza.png','class=img_det_limp') ?>
                                    <p>Limpieza</p>
                                    <p><?=$comentarios[$i]['limpio'];?>/5</p>
                                    <?php
                                        if($comentarios[$i]['limpio'] > 2){
                                    ?>
                                        <?php echo image_tag('img_evaluaciones/IconoDedoArriba.png','class=img_dedo_arriba') ?>
                                    <?php
                                        }else{
                                    ?>
                                        <?php echo image_tag('img_evaluaciones/IconoDedoAbajo.png','class=img_dedo_abajo') ?>
                                    <?php
                                        }
                                    ?>

                                </div>
                                <div class="punt">
                                    <span>|</span><?php echo image_tag('img_evaluaciones/IconoPuntualidad.png','class=img_det_punt') ?>
                                    <?php
                                        if($comentarios[$i]['puntual'] == 1){
                                    ?>
                                        <p>Puntual</p><?php echo image_tag('img_evaluaciones/IconoDedoArriba.png','class=img_dedo_arriba') ?>
                                    <?php
                                        }else{
                                    ?>
                                        <p>Impuntual [<?php echo $comentarios[$i]['impunt']; ?> min]</p><?php echo image_tag('img_evaluaciones/IconoDedoAbajo.png','class=img_dedo_abajo') ?>
                                    <?php
                                        }
                                    ?>
                                </div>
                                <div class="reco">
                                    <?php echo image_tag('img_evaluaciones/IconoUsuario.png','class=img_det_reco') ?>
                                    <?php
                                        if($comentarios[$i]['recomendado'] == 1){
                                    ?>
                                        <p>Recomendado</p><?php echo image_tag('img_evaluaciones/IconoDedoArriba.png','class=img_dedo_arriba') ?>
                                    <?php
                                        }else{
                                    ?>
                                        <p>No recomendado</p><?php echo image_tag('img_evaluaciones/IconoDedoAbajo.png','class=img_dedo_abajo') ?>
                                    <?php
                                        }
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <?php
            }
        }
            ?>
        </div>
    </div>

</div><!-- main_contenido -->

<div id="colDer">
<?php include_component('profile', 'publico', array('user' => $user, 'verificacionAutos' => $verificacionAutos, 'telefonoConfirmado' => $telefonoConfirmado, 'telefono' => $telefono, 'facebookConfirmado' => $facebookConfirmado, 'emailConfirmado' => $emailConfirmado, 'email' => $email));?>
<?php include_component('profile', 'autos', array('carsPublicProfile' => $carsPublicProfile, 'userPublicProfile' => $user)); ?>

<?php //include_component('profile', 'preguntasFrecuentes') ?>

</div>
</div><!-- main_box_2 -->
</div><!-- main_box_1 -->