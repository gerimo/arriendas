<?php use_stylesheet('comunes.css') ?>
<?php use_stylesheet('subi_tu_auto.css') ?>
<?php use_stylesheet('registro.css') ?>
<?php use_stylesheet('jquery.lightbox-0.5.css') ?>
<?php
$useragent = $_SERVER['HTTP_USER_AGENT'];
if (preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i', $useragent) || preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i', substr($useragent, 0, 4))) {
    use_stylesheet('moviles.css');
}
?>
<?php use_javascript('jquery.lightbox-0.5-v2.js') ?>
<?php use_javascript('jquery.lightbox-0.5.min.js') ?>
<?php use_javascript('jquery.lightbox-0.5.pack.js') ?>


<script type="text/javascript">
    $(function() {
        $('#gallery a').lightBox();
        $('#galleryDanios a').lightBox();
    });

    $(document).ready(function() {
        document.title = $("#Pagetitle").val();
    });

</script>


<style type="text/css">

    /* jQuery lightBox plugin - Gallery style */

    #gallery ul,#galleryDanios ul { list-style: none; }
    #gallery ul li,#galleryDanios ul li { display: inline; }
    #gallery ul img,#galleryDanios ul img {
        border: 2px solid #fff; /*A4A4A4*/
        border-width: 2px 2px 2px;
    }
    #gallery ul a:hover img,#galleryDanios ul a:hover img {
        border: 2px solid #EC008C;
        border-width: 2px 2px 2px;
        color: #fff;
    }
    #gallery ul a:hover,#galleryDanios ul a:hover { color: #fff; }
    a{text-decoration:none;}
</style>

<style>
    .texto_normal{
        font-size: 12px;
        margin-right: 20px;
    }
    .texto_normal_grande {
        color: #2D2D2D;
        font-size: 17px;
        font-family: Arial, sans-serif;
    }
    .texto_normal, .subtitulos{
        float: left;
        width: 95%;
    }
    .subtitulos{
        font-style: italic;
        font-size: 15px;
    }

    .texto_auto {
        color:#00AEEF;
        font-size: 24px;
    }

    .punteado {
        border-bottom: solid;
        border-bottom-width: 1px;
        border-bottom-color: #BDBDBD;
        margin-left: 20px;
        margin-bottom: 20px;
        padding-bottom: 4px;
        margin-top: 20px;
        float: left;
    }

    .precios{
        margin-left: 40px;
        margin-bottom: 15px;
    }

    .texto_magenta {
        color: #EC008C;
    }

    .espaciado {
        margin-top: 20px;
    }

    .galeria{
        margin-bottom: 10px;
        margin-left: 30px;
    }

    .botones{
        height: 70px;
        width: 325px;
    }

    .boton{
        margin-right: 10px;
    }
    .boton2{
        margin-left: 10px;
        float: left;
        margin-top: -4px;
        margin-bottom: -4px;
    }

    .titulo{
        float: left;
        margin-left: 18px;
        font-size: 21px;
        font-weight: bold;
        color: #00AEEF; /* celeste */
        width: 100%;
    }
    h1{
        float: left;
        margin-left: 18px;
        font-size: 21px;
        font-weight: bold;
        color: #00AEEF; /* celeste */
        width: 100%;
        border-bottom: dashed 0px #d1d1d1;
        margin: 0px 0px 0px 18px;
        height: auto;
    }
    .ladoIzquierdo{
        float: left;
        width: 220px;
    }
    .subtitulos p{
        float: left;
    }
    .colorSub{
        color:#00AEEF;
    }
    a.colorSub:hover{
        padding-bottom: 2px;
        text-decoration: underline;
    }
    .interlineado{
        float: left;
    }
    .interlineado2{
        float: left;
        width: 165px;
        margin-left: 5px;

    }
    .img_verificado{
        margin-left: 8px;
    }
.evaluaciones{
	float: left;
	width: 480px;
	margin-left: 4px;
	margin-top: 40px;
	margin-bottom: 30px;
}
#evaluacionesPublicProfile{
	float: left;
	width: 490px;
	margin-left: 9px;
	margin-top: 40px;
	margin-bottom: 30px;
}
.conten_opcion3 .evaluaciones, .conten_opcion2 .evaluaciones{
	margin-top: 10px;
}

#evaluacionesPublicProfile h3{
	margin-left: 10px;
	border-bottom: 1px solid #00AEEF;
	padding-bottom: 10px;
	font-size: 14px;
	color: #00AEEF; /* celeste */
	margin-bottom: 10px;
}
</style>
<?php if (sfContext::getInstance()->getUser()->getAttribute("logged")) { ?>
    <div class="main_box_1">
    <?php } else { ?>
        <div class="main_box_1" style="width:520px;">
        <?php } ?>
        <div class="main_box_2">


            <?php if (sfContext::getInstance()->getUser()->getAttribute("logged")) include_component('profile', 'profile') ?>


            <div class="main_contenido">

                <div class="barraSuperior">
                    <h2>INFORMACIÓN DEL AUTO EN ARRIENDO</h2>
                </div>
                <div class="ladoIzquierdo">
                    <div id="imagenPerfil">
                        <?php
                        if ($car->getPhotoS3() == 1) {
                            echo image_tag("http://www.arriendas.cl/main/s3thumb?alto=185&ancho=185&urlFoto=" . $car->getFoto(), array("width" => "185", "height" => "185"));
                        } else {
//						echo image_tag("../uploads/cars/".$car->getFoto(),array("width"=>"185","height"=>"185"));
                            echo "<img src='http://res.cloudinary.com/arriendas-cl/image/fetch/w_185,h_185,c_fill,g_center/http://arriendas.cl/uploads/cars/" . $car->getFoto() . "'/>";
                        }
                        ?>
                    </div>
                    <?php
                    if ($arrayFotos) {
                        if ($opcionFotosEnS3 == 1) {
                            ?>
                            <div id="gallery">
                                <ul>
                                    <?php
                                    $cantidadFotos = count($arrayFotos);
                                    for ($i = 0; $i < $cantidadFotos; $i++) {
                                        ?>
                                        <li>
                                            <a title="<?= $arrayDescripcionFotos[$i]; ?>" href="http://www.arriendas.cl/main/s3thumb?alto=600&ancho=600&urlFoto=<?= $arrayFotos[$i]; ?>">
                                                <img title="<?= $arrayDescripcionFotos[$i]; ?>" src="http://www.arriendas.cl/main/s3thumb?alto=40&ancho=40&urlFoto=<?= $arrayFotos[$i]; ?>" width="40" height="40">
                                            </a>
                                        </li>
            <?php
        }
        ?>
                                </ul>
                            </div>
        <?php
    } else {
        ?>
                            <div id="gallery">
                                <ul>
        <?php
        $cantidadFotos = count($arrayFotos);
        for ($i = 0; $i < $cantidadFotos; $i++) {
            ?>
                                        <li>
                                            <a title="<?= $arrayDescripcionFotos[$i]; ?>" href="<?= image_path('../uploads/verificaciones/' . $arrayFotos[$i]); ?>">
                                                <img title="<?= $arrayDescripcionFotos[$i]; ?>" src="http://res.cloudinary.com/arriendas-cl/image/fetch/w_40,h_40,c_fill,g_center/http://arriendas.cl/<?= image_path('../uploads/verificaciones/thumbs/' . $arrayFotos[$i]); ?>" width="40" height="40">
                                            </a>
                                        </li>
            <?php
        }
        ?>
                                </ul>
                            </div>

        <?php
    }
}
?>
                </div><!-- izquierda -->

                <div style="width: 256px;margin-bottom:50px; float: right;margin-left:0px;margin-right:30px;padding-right:10px;font-size:12px;">

                    <div class="botones">
                        <a href="<?php echo url_for('profile/reserve?id=' . $car->getId()) ?>" style="float:right; margin-right:25px;" title="Realizar una Reserva"><?php echo image_tag('BotonRealizarReserva.png', "class=boton"); ?></a>

                    </div>
                    <h1>Arriendo <?php echo $car->getModel()->getBrand() . " " . $car->getModel() . $nombreComunaAuto; ?><?php if ($car->autoVerificado()): ?><?php echo image_tag("verificado.png", "class=img_verificado title='Auto Asegurado' size=18x18"); ?><?php endif; ?>
                    </h1>
                    <input type="hidden" id="Pagetitle" value="Arriendo <?php echo $car->getModel()->getBrand() . " " . $car->getModel() . $nombreComunaAuto; ?>"/>

                    <div class="subtitulos punteado">Precio Final</div>
                    <div class="texto_normal precios">Precio por Hora | <span class="texto_magenta"><strong><?php echo "$" . number_format(floor($car->getPricePerHour()), 0, ',', '.'); ?></strong></span></div>
                    <div class="texto_normal precios">Precio por D&iacute;a | <span class="texto_magenta"><strong><?php echo "$" . number_format(floor($car->getPricePerDay()), 0, ',', '.'); ?></strong></span></div>

<?php if ($car->getDisponibilidadSemana() == 1 && $car->getDisponibilidadFinde() == 1): ?>
                        <div class="texto_normal precios">Precio por Semana | 
                        <?php if ($car->getPricePerWeek() > 0): ?><span class="texto_magenta"><strong><?php echo "$" . number_format(floor($car->getPricePerWeek()), 0, ',', '.'); ?></strong></span>
                        <?php else: ?>
                                <a href="<?php echo url_for('messages/new?id=' . $user->getId() . '&comentarios=Consulta arriendo Semanal ' . $car->getModel()->getBrand() . " " . $car->getModel()) ?>" title="Consultar arriendo Semanal de <?php echo $car->getModel()->getBrand() . " " . $car->getModel(); ?>"><span class="texto_magenta"><strong>Consultar</strong></span></a>
                            <?php endif; ?></div>
                        <div class="texto_normal precios">Precio por Mes | 
                            <?php if ($car->getPricePerMonth() > 0): ?><span class="texto_magenta"><strong><?php echo "$" . number_format(floor($car->getPricePerMonth()), 0, ',', '.'); ?></strong></span>
                            <?php else: ?>
                                <a href="<?php echo url_for('messages/new?id=' . $user->getId() . '&comentarios=Consulta arriendo Mensual ' . $car->getModel()->getBrand() . " " . $car->getModel()) ?>" title="Consultar arriendo Mensual de <?php echo $car->getModel()->getBrand() . " " . $car->getModel(); ?>"><span class="texto_magenta"><strong>Consultar</strong></span></a>
                            <?php endif; ?></div>		
                        <?php endif; ?>

                    <div class="subtitulos punteado">Precio sin Depósito en Garantía</div>
                    <div class="texto_normal precios">Precio por Hora | <span class="texto_magenta"><strong><?php echo "$" . number_format(floor($car->getPricePerHour()) + 967, 0, ',', '.'); ?></strong></span></div>
                    <div class="texto_normal precios">Precio por D&iacute;a | <span class="texto_magenta"><strong><?php echo "$" . number_format(floor($car->getPricePerDay()) + 5800, 0, ',', '.'); ?></strong></span></div>

                    <div class="subtitulos punteado">Datos del Auto</div>
                    <div class="texto_normal precios"><div class="interlineado">Ubicaci&oacute;n |</div><div class="interlineado2"><strong><?php echo ucwords(strtolower($car->getAddressAprox())) . "" . $nombreComunaAuto ?></strong></div></div>
                    <div class="texto_normal precios">A&ntilde;o | <strong><?php echo $car->getYear(); ?></strong></div>
                    <div class="texto_normal precios">Tipo de Bencina | <strong><?php echo strtoupper($car->getTipoBencina()); ?></strong></div>
                    <div class="texto_normal precios">Transmisión | <strong><?php echo $sf_data->getRaw('transmision'); ?></strong></div>

                    <div class="subtitulos punteado espaciado"><p>Due&ntilde;o - <a class="colorSub" href="<?php echo url_for('profile/publicprofile?id=' . $user->getId()) ?>" title="Ir al perfil de <?= $primerNombre ?>"><?= $primerNombre . " " . $inicialApellido; ?></a></p><a href="<?php echo url_for('messages/new?id=' . $user->getId()) ?>" style="" title="Enviar e-mail a <?php echo ucwords($user->getFirstname()) . ' ' . ucwords($user->getLastname()); ?>"><?php echo image_tag('img_msj/EnviarMsjSinSombraChico.png', "class=boton2"); ?></a></div>
                    <div class="texto_normal precios">Calificaciones Positivas | <strong><?= $aprobacionDuenio['porcentaje']; ?>%</strong></div>
                    <div class="texto_normal precios">Velocidad de Respuesta | <strong><?php if ($velocidadMensajes == "") {
                        echo "<span style='font-style: italic; color:#BCBEB0;'>No tiene mensajes</span>";
                    } else {
                        echo $velocidadMensajes;
                    } ?></strong></div>
                    <div class="texto_normal precios"><a class="colorSub" href="<?php echo url_for('profile/publicprofile?id=' . $user->getId()) ?>" title="Ver calificaciones hechas a <?= $primerNombre ?>">Ver Calificaciones</a></div>
<?php if ($car->getDescription() != null) { ?>
                        <div class="subtitulos punteado espaciado">Breve Descripci&oacute;n del auto</div>
                        <div class="texto_normal precios"><?php echo $car->getDescription(); ?></div>
                    <?php } ?>

                    <?php
                    if ($car->autoVerificado()) {
                        ?>
                        <div class="subtitulos punteado">Seguro<?php echo image_tag("verificado.png", "class=img_verificado title='Seguro Activo' size=14x14"); ?></div>
                        <div class="texto_normal precios"><div class="img_s1"></div> <strong>Seguro contra da&ntilde;os</strong></div>
                        <div class="texto_normal precios"><div class="img_s2"></div> <strong>Seguro destrucci&oacute;n total</strong></div>
                        <div class="texto_normal precios"><div class="img_s3"></div> <strong>Seguro de terceros</strong></div>
                        <div class="texto_normal precios"><div class="img_s4"></div> <strong>Seguro de conductor y acompa&ntilde;ante</strong></div>
                        <div class="texto_normal precios"><div class="img_s5"></div> <strong>Deducible 5 UF</strong></div>
                        <?php
                    }
                    ?>

                    <div class="subtitulos punteado espaciado">Da&ntilde;os del auto</div>
                    <?php
                    if ($arrayFotosDanios) {
                        ?>
                        <div id="galleryDanios">
                            <ul>
                                <?php
                                $cantidadFotosDanios = count($arrayFotosDanios);
                                for ($i = 0; $i < $cantidadFotosDanios; $i++) {
                                    ?>
                                    <li>

                                        <a title="<?= $arrayDescripcionesDanios[$i]; ?>" href="http://www.arriendas.cl/main/s3thumb?alto=600&ancho=600&urlFoto=http://www.arriendas.cl/uploads/damages/<?= $arrayFotosDanios[$i]; ?>">
                                            <img title="<?= $arrayDescripcionesDanios[$i]; ?>" src="http://res.cloudinary.com/arriendas-cl/image/fetch/w_40,h_40,c_fill,g_center/http://arriendas.cl/uploads/damages/<?= $arrayFotosDanios[$i]; ?>" width="40" height="40">
                                        </a>
                                    </li>
                                    <?php
                                }
                                ?>
                            </ul>
                        </div>
                        <?php
                    } else {
                        if ($car->autoVerificado()) {
                            echo "<span style='font-style: italic; color:#BCBEB0;margin-left: 40px;font-size: 13px;'>Auto no presenta Daños</span>";
                        } else {
                            echo "<span style='font-style: italic; color:#BCBEB0;margin-left: 40px;font-size: 13px;'>Auto no verificado</span>";
                        }
                    }
                    ?>
                </div>

                <div id="evaluacionesPublicProfile">
                    <div class="evaluacion">
                        <?php
                        $cantidadDeComentariosSobreMi = count($comentarios);
                        if ($comentarios[0] == null) {
                            ?> 
                            <h3>CALIFICACIONES (0)</h3>
                            <p style="float:left;margin-left:10px;font-size:14px;color:#BCBEB0;font-style:italic;"><?= $user->getFirstname(); ?> aún no tiene calificaciones</p>
                            <?php
                        } else {
                            ?>
                            <h3>CALIFICACIONES (<?= $cantidadDeComentariosSobreMi; ?>)</h3>   
                            <div class="calificacionEstrellas">
                                <div class="puntualidad">
                                    <?php echo image_tag('img_evaluaciones/IconoPuntualidad.png', 'class=img_IconoPuntualidad') ?>
                                    <p><span>Puntualidad</span></p>
                                    <div class="estrellas">
                                        <?php
                                        for ($i = 0; $i < 5; $i++) {
                                            if ($i < $puntualidad) {//estrella marcada
                                                echo image_tag('img_evaluaciones/IconoEstrellaCeleste.png');
                                            } else {//estrella no marcada
                                                echo image_tag('img_evaluaciones/IconoEstrellaGris.png');
                                            }
                                        }
                                        ?>
                                    </div>
                                </div>
                                <div class="limpieza">
                                    <?php echo image_tag('img_evaluaciones/IconoLimpieza.png', 'class=img_IconoLimpieza') ?>
                                    <p><span>Limpieza</span></p>
                                    <div class="estrellas">
                                        <?php
                                        for ($i = 0; $i < 5; $i++) {
                                            if ($i < $limpieza) {//estrella marcada
                                                echo image_tag('img_evaluaciones/IconoEstrellaCeleste.png');
                                            } else {//estrella no marcada
                                                echo image_tag('img_evaluaciones/IconoEstrellaGris.png');
                                            }
                                        }
                                        ?>
                                    </div>
                                </div>
                            </div>

                            <?php
                            for ($i = 0; $i < count($comentarios); $i++) {
                                ?>

                                <div class="comentario">
                                    <div class="usuario">

                                        <?php echo "<a href='" . url_for('profile/publicprofile?id=' . $comentarios[$i]['idEvaluador']) . "'>"; ?>
                                        <div class="fotoPosteador">
                                            <?php
                                            if ($comentarios[$i]['facebookEvaluador'] != null && $comentarios[$i]['facebookEvaluador'] != "") {
                                                echo "<img src='" . $comentarios[$i]['urlFotoEvaluador'] . "' class='img_usuario'/>";
                                            } else {
                                                if ($comentarios[$i]['urlFotoEvaluador'] != "") {
                                                    $filename = explode("/", $comentarios[$i]['urlFotoEvaluador']);
                                                    echo image_tag("users/" . $filename[Count($filename) - 1], 'class=img_usuario');
                                                } else {
                                                    echo image_tag('img_registro/tmp_user_foto.jpg', 'class=img_usuario');
                                                }
                                            }
                                            ?>
                                        </div>
                                        <div class="nombrePosteador"><p><?php echo $comentarios[$i]['nombreEvaluador'] . " " . $comentarios[$i]['apellidoEvaluador']; ?></p></div>
                                        </a>

                                    </div>
                                    <div class="campoTexto">
        <?php echo image_tag('img_evaluaciones/TrianguloComentarios.png', 'class=punta') ?>
                                        <div class="margenTexto">
                                            <div class="texto">
                                                <p><?php echo $comentarios[$i]['comentarioEvaluacion']; ?></p>
                                                <div class="detallesComentario">
                                                    <div class="fecha"><span>|</span><p><?php echo $comentarios[$i]['fechaEvaluacion']; ?></p></div>
                                                    <div class="limp">
                                                        <span>|</span><?php echo image_tag('img_evaluaciones/IconoLimpieza.png', 'class=img_det_limp') ?>
                                                        <p>Limpieza</p>
                                                        <p><?= $comentarios[$i]['limpio']; ?>/5</p>
        <?php
        if ($comentarios[$i]['limpio'] > 2) {
            ?>
                                                            <?php echo image_tag('img_evaluaciones/IconoDedoArriba.png', 'class=img_dedo_arriba') ?>
                                                            <?php
                                                        } else {
                                                            ?>
                                                            <?php echo image_tag('img_evaluaciones/IconoDedoAbajo.png', 'class=img_dedo_abajo') ?>
                                                            <?php
                                                        }
                                                        ?>

                                                    </div>
                                                    <div class="punt">
                                                        <span>|</span><?php echo image_tag('img_evaluaciones/IconoPuntualidad.png', 'class=img_det_punt') ?>
        <?php
        if ($comentarios[$i]['puntual'] == 1) {
            ?>
                                                            <p>Puntual</p><?php echo image_tag('img_evaluaciones/IconoDedoArriba.png', 'class=img_dedo_arriba') ?>
                                                            <?php
                                                        } else {
                                                            ?>
                                                            <p>Impuntual [<?php echo $comentarios[$i]['impunt']; ?> min]</p><?php echo image_tag('img_evaluaciones/IconoDedoAbajo.png', 'class=img_dedo_abajo') ?>
                                                            <?php
                                                        }
                                                        ?>
                                                    </div>
                                                    <div class="reco">
        <?php echo image_tag('img_evaluaciones/IconoUsuario.png', 'class=img_det_reco') ?>
                                                        <?php
                                                        if ($comentarios[$i]['recomendado'] == 1) {
                                                            ?>
                                                            <p>Recomendado</p><?php echo image_tag('img_evaluaciones/IconoDedoArriba.png', 'class=img_dedo_arriba') ?>
                                                            <?php
                                                        } else {
                                                            ?>
                                                            <p>No recomendado</p><?php echo image_tag('img_evaluaciones/IconoDedoAbajo.png', 'class=img_dedo_abajo') ?>
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

            </div>

<?php if (sfContext::getInstance()->getUser()->getAttribute("logged")) include_component('profile', 'colDer') ?>  

        </div><!-- main_box_2 -->
        <div class="clear"></div>
    </div><!-- main_box_1 -->

    <style type="text/css">
        #imagenPerfil{
            padding-top: 6px;
            padding-left: 6px;
            width: 193px;
            height: 192px;
            float: left;
            margin-left: 20px;

            -webkit-box-shadow: 0px 0px 9px rgba(50, 50, 50, 0.32);
            -moz-box-shadow:    0px 0px 9px rgba(50, 50, 50, 0.32);
            box-shadow:         0px 0px 9px rgba(50, 50, 50, 0.32;
        }

    </style>