<?php use_stylesheet('comunes.css') ?>
<?php use_stylesheet('subi_tu_auto.css') ?>
<?php use_stylesheet('registro.css') ?>
<?php use_stylesheet('calificaciones_profile.css') ?>
<?php use_stylesheet('jquery.lightbox-0.5.css') ?>
<?php use_stylesheet('royalslider.css') ?>
<?php use_stylesheet('rs-universal.css') ?>
<?php
$useragent = $_SERVER['HTTP_USER_AGENT'];
if (preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i', $useragent) || preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i', substr($useragent, 0, 4))) {
    use_stylesheet('moviles.css');
}
?>
<?php use_javascript('jquery.lightbox-0.5-v2.js') ?>
<?php use_javascript('jquery.lightbox-0.5.min.js') ?>
<?php use_javascript('jquery.lightbox-0.5.pack.js') ?>
<?//php use_javascript('jquery-1.8.3.min.js') ?>
<?php use_javascript('jquery.royalslider.min.js') ?>
<script type="text/javascript">
    $(function() {
        //$('#gallery a').lightBox();
        $('#galleryDanios a').lightBox();
    });

    $(document).ready(function() {
        //document.title = $("#Pagetitle").val();
        $('#gallery-2').royalSlider({
            fullscreen: {
              enabled: true,
              nativeFS: true
            },
            controlNavigation: 'thumbnails',
            thumbs: {
              orientation: 'vertical',
              paddingBottom: 4,
              appendSpan: true
            },
            transitionType:'fade',
            autoScaleSlider: true, 
            autoScaleSliderWidth: 900,     
            autoScaleSliderHeight: 440,
            loop: true,
            arrowsNav: false,
            keyboardNavEnabled: true
        });
    });

</script>


<style type="text/css">
  #gallery-2 {
      width: 100%;
      height: 100%;
      -webkit-user-select: none;
      -moz-user-select: none;  
      user-select: none;
    }
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
    h2.subtitulos.punteado{
        border-bottom: 1px solid #BDBDBD;
        margin: 20px 20px 20px 20px;
        padding: 0px 0px 4px 0px;
        height: 15px;
        background-color: transparent;
        text-align: left;
        font-weight: normal;
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
        margin-left: 20px;
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
    h4.style2014{
        float: left;
        color: #00AEEF;
        font-family: Arial, sans-serif;
        font-size: 15px;
        font-weight: normal;
        margin-top: 3px;
        width: 100%;
        font-style: italic;
    }
    .calificacionEstrellas{
        float: left;
        padding-top: 10px;
        padding-bottom: 10px;
        border: 0px;
        margin-left: 0px;
        margin-top: 10px;
        margin-bottom: 20px;
        width: 480px,
    }
    .seccion{
        width: 230px;
        height: 190px;
        float: left;
        margin-top: 5px;
    }
    .cont_accesorios{
        width: 100%;
        float: left;
    }
    .secc_accesorio{
        width: 212px;
        height: 36px;
        float: left;
        margin-top: 5px;
        margin-left: 10px;
        /*background-color: yellow;*/
    }
    .label_acc{
        width: 120px;
        margin-right: 5px;
        float: left;
        margin-top: 10px;
        font-size: 11px;
    }
    .difpunta{
        margin-left: -600px;
    }
</style>
<?php if (sfContext::getInstance()->getUser()->getAttribute("logged")) { ?>
    <div class="main_box_1">
    <?php } else { ?>
        <div class="main_box_1" style="width:520px;">
        <?php } ?>
        <div class="main_box_2">


            <?php if (sfContext::getInstance()->getUser()->getAttribute("logged")) include_component('profile', 'profile') ?>


            <div class="main_contenido" style="width: 732px">

                <div class="barraSuperior" style="margin-bottom: 15px;">
                    <h2>INFORMACIÓN DEL AUTO EN ARRIENDO</h2>
                </div>
                <?php
                    if ($arrayFotos) {
                        if ($opcionFotosEnS3 == 1) {
                            ?>
                            <div style="width: 690px;margin-left: 20px;height: 348px;">
                                <!--<img src="destacado.png" style="z-index:100;position:absolute;">-->
                                <?php echo image_tag("img_autos/destacado.png",array("width"=>"239","height"=>"152","style"=>"z-index:100;position:absolute;top: 45px;"));?>
                                <div class="fwImage col span_4">
                                    <div id="gallery-2" class="royalSlider rsUni">
                                        <?php
                                        $cantidadFotos = count($arrayFotos);
                                        for ($i = 0; $i < $cantidadFotos; $i++) {
                                            ?>
                                                <!-- Imprimiendo foto -->
                                                <!--
                                                <a class="rsImg" data-rsBigImg="img/1.jpg" href="http://www.arriendas.cl/main/s3thumb?alto=600&ancho=600&urlFoto=<?= $arrayFotos[$i]; ?>" data-rsw="586" data-rsh="311">
                                                    Foto Auto
                                                    <img width="100" height="100" class="rsTmb" src="http://www.arriendas.cl/main/s3thumb?alto=40&ancho=40&urlFoto=<?= $arrayFotos[$i]; ?>" />
                                                </a>
                                                -->
                                                <!-- Fin impresión foto -->
                                            <?php
                                        }
                                        ?>
                                    </div>
                                </div>
                            </div>
                            <?php
                        } else {
                            ?>
                            <div style="width: 690px;margin-left: 20px;height: 348px;">
                                <!--<img src="destacado.png" style="z-index:100;position:absolute;">-->
                                <?php echo image_tag("img_autos/destacado.png",array("width"=>"239","height"=>"152","style"=>"z-index:100;position:absolute;top: 45px;"));?>
                                <div class="fwImage col span_4">
                                    <div id="gallery-2" class="royalSlider rsUni">
                                    <?php
                                    //Imprimiendo primera foto
                                    $image_alt = "arriendoautos/rentacar/" . $car->getModel()->getBrand() . $car->getModel() . "/" . $nombreComunaUrl . "/" . $car->getId();
                                    $image_title = "arriendoauto/" . $car->getId();
                                    if ($car->getPhotoS3() == 1) {

                                    ?>
                                    <!--
                                        <a class="rsImg" data-rsBigImg="img/1.jpg" href="<?="http://www.arriendas.cl/main/s3thumb?alto=185&ancho=185&urlFoto=" . $car->getFoto(); ?>" data-rsw="580" data-rsh="308">
                                            Foto Auto
                                            <img width="100" height="100" class="rsTmb" src="<?="http://www.arriendas.cl/main/s3thumb?alto=185&ancho=185&urlFoto=" . $car->getFoto(); ?>" />
                                        </a>
                                        -->
                                    <?php

                                    } else {

                                    ?>
                                        <a class="rsImg" data-rsBigImg="img/1.jpg" href="<?="http://res.cloudinary.com/arriendas-cl/image/fetch/w_185,h_185,c_fill,g_center/http://arriendas.cl/uploads/cars/" . $car->getFoto(); ?>" data-rsw="580" data-rsh="330">
                                            Foto de Perfil
                                            <img width="100" height="100" class="rsTmb" src="<?="http://res.cloudinary.com/arriendas-cl/image/fetch/w_185,h_185,c_fill,g_center/http://arriendas.cl/uploads/cars/" . $car->getFoto(); ?>" />
                                        </a>
                                    <?php
                                    }
                                    //Imprimiendo fotos restantes
                                    $cantidadFotos = count($arrayFotos);
                                    for ($i = 0; $i < $cantidadFotos; $i++) {
                                        ?>
                                            <!-- Imprimiendo foto -->
                                            <a class="rsImg" data-rsBigImg="img/1.jpg" href="<?= image_path('../uploads/verificaciones/' . $arrayFotos[$i]); ?>" data-rsw="580" data-rsh="330">
                                                <?= $arrayDescripcionFotos[$i]; ?>
                                                <img width="100" height="100" class="rsTmb" src="http://res.cloudinary.com/arriendas-cl/image/fetch/w_40,h_40,c_fill,g_center/http://arriendas.cl/<?= image_path('../uploads/verificaciones/thumbs/' . $arrayFotos[$i]); ?>" />
                                            </a>

                                           <!-- Fin impresión foto -->
                                    <?php
                                    }
                                    ?>
                                    </div>
                                </div>
                            </div>
                            <?php
                        }//end else if
                    }//end if
                    ?>
                <?php $ciudad="Santiago"; ?>
                <div style="width: 670px;margin-bottom: 10px;float: left;margin-left: 26px;padding-right: 10px;font-size: 12px;">
                        <a href="<?php echo url_for('profile/reserve?id=' . $car->getId()) ?>" style="float:right; margin-right: -10px;" title="Realizar una Reserva"><?php echo image_tag('BotonRealizarReserva.png', "class=boton"); ?></a>
                        <div style="width: 470px;height: 105px;">
                            <h1 style="float: left;margin-left: 0px;font-size: 21px;font-weight: bold;color: #00AEEF;width: 100%;border-bottom: dashed 0px #d1d1d1;margin: 0px 0px 0px 0px;height: auto;line-height: 26px;">Arriendo <?php echo $car->getModel() . ", " . $car->getModel()->getBrand() . " <br> " . str_replace(".","",str_replace(",","",$nombreComunaAuto)). ", ".$ciudad ; ?><?php if ($car->autoVerificado()): ?><?php echo image_tag("verificado.png", "class=img_verificado title='Auto Asegurado' size=18x18"); ?><?php endif; ?>
                            </h1>
                            <h4 class="style2014" style="margin-top: 5px;"><p style="float:left;">Due&ntilde;o - <a class="colorSub" href="<?php echo url_for('profile/publicprofile?id=' . $user->getId()) ?>" title="Ir al perfil de <?= $primerNombre ?>" style="text-decoration: none;"><?= $primerNombre . " " . $inicialApellido; ?></a></p>
                                <!-- Icono verificado correspondiente al del auto: cambiar cuando se implemente el php -->
                                <?php if ($car->autoVerificado()): ?><?php echo image_tag("verificado.png", "class=img_verificado title='Auto Asegurado' size=18x18 style='margin-top: -4px;'"); ?><?php endif; ?>
                            </h4>
                            <h4 class="style2014" style="border-bottom: 1px solid #bbbdbf;padding-bottom: 5px;">Ubicaci&oacute;n - <?php echo ucwords(strtolower($car->getAddressAprox())) ?>
                            </h4>
                            <div class="calificacionEstrellas" style="width:490px;">
                                <div class="puntualidad" style="width: 176px;">
                                        <?php echo image_tag('img_evaluaciones/IconoPuntualidad.png', 'class=img_IconoPuntualidad') ?>
                                    <p style="width: 72px;"><span style="font-size: 11px;">Puntualidad</span></p>
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
                                <div class="limpieza" style="float:left;width:170px;">
                                        <?php echo image_tag('img_evaluaciones/IconoLimpieza.png', 'class=img_IconoLimpieza') ?>
                                    <p style="width: 60px;"><span style="font-size: 11px;">Limpieza</span></p>
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

                                <div class="verComentarios" style="float:left;">
                                        <?php echo image_tag('img_evaluaciones/IconoVerComentarios.png',array("width"=>"16","height"=>"15","style"=>"float: left;margin-right: 4px;margin-top: 2px;")) ?>
                                    <p style="width: 132px;margin-top: 3px;"><span style="font-size: 11px;">Ver Comentarios</span> <span style="color:#00AEEF;font-weight:bold;font-style:italic;">(3)</span></p>
                                </div>


                            </div><!--fin div.calificacionEstrellas -->
                            <input type="hidden" id="Pagetitle" value="Arriendo <?php echo $car->getModel()->getBrand() . " " . $car->getModel() . $nombreComunaAuto; ?>"/>
                        </div><!--fin div -->
                    <div class="seccion">
                        <h2 class="subtitulos punteado" style="margin-top:0px;margin-left:0px;">Precio Final</h2>
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
                    </div><!--fin seccion -->
                    <div class="seccion" style="margin-left:5px;">
                        <h2 class="subtitulos punteado" style="margin-top:0px;margin-left:0px;">Precio sin Depósito en Garantía</h2>
                        <div class="texto_normal precios">Precio por Hora | <span class="texto_magenta"><strong><?php echo "$" . number_format(floor($car->getPricePerHour()) + 967, 0, ',', '.'); ?></strong></span></div>
                        <div class="texto_normal precios">Precio por D&iacute;a | <span class="texto_magenta"><strong><?php echo "$" . number_format(floor($car->getPricePerDay()) + 5800, 0, ',', '.'); ?></strong></span></div>
                    </div><!--fin seccion -->

                    <?php
                    if ($car->autoVerificado()) {
                        ?>
                        <div class="seccion" style="margin-left:5px;width: 200px;">
                            <h2 class="subtitulos punteado" style="margin-top:0px;margin-left:0px;width:105%;">Seguro<?php echo image_tag("verificado.png", "class=img_verificado title='Seguro Activo' size=14x14"); ?></h2>
                            <div class="texto_normal precios"><div class="img_s1"></div> <strong>Seguro contra da&ntilde;os</strong></div>
                            <div class="texto_normal precios"><div class="img_s2"></div> <strong>Seguro destrucci&oacute;n total</strong></div>
                            <div class="texto_normal precios"><div class="img_s3"></div> <strong>Seguro de terceros</strong></div>
                            <div class="texto_normal precios"><div class="img_s4"></div> <strong>Seguro de conductor y acompa&ntilde;ante</strong></div>
                            <div class="texto_normal precios"><div class="img_s5"></div> <strong>Deducible 5 UF</strong></div>
                        </div><!--fin seccion -->
                        <?php
                    }
                    ?>
                    <div class="seccion" style="margin-top:25px;height: auto;">
                        <h2 class="subtitulos punteado" style="margin-top:0px;margin-left:0px;">Datos del Auto</h2>
                        <div class="texto_normal precios">A&ntilde;o | <strong><?php echo $car->getYear(); ?></strong></div>
                        <div class="texto_normal precios">Tipo de Bencina | <strong><?php echo strtoupper($car->getTipoBencina()); ?></strong></div>
                        <div class="texto_normal precios">Transmisión | <strong><?php echo $sf_data->getRaw('transmision'); ?></strong></div>
                    </div><!--fin seccion -->

                    <div class="seccion" style="margin-left:5px;margin-top:25px;height: auto;">
                        <h2 class="subtitulos punteado" style="margin-top:0px;margin-left:0px;"><p style="float:left;">Due&ntilde;o - <a class="colorSub" href="<?php echo url_for('profile/publicprofile?id=' . $user->getId()) ?>" title="Ir al perfil de <?= $primerNombre ?>" style="text-decoration: none;"><?= $primerNombre . " " . $inicialApellido; ?></a></p></h2>
                            <div class="texto_normal precios">Calificaciones Positivas | <strong><?= $aprobacionDuenio['porcentaje']; ?>%</strong></div>
                            <div class="texto_normal precios">Tiempo respuesta | <strong><?php
                            if ($velocidadMensajes == "") {
                                echo "<span style='font-style: italic; color:#BCBEB0;'>Sin mensajes</span>";
                            } else {
                                echo $velocidadMensajes;
                            }
                            ?></strong></div>
                            <div class="texto_normal precios">Cantidad de arriendos | <strong>4</strong></div>
                    </div><!--fin seccion -->

                    <!--<div class="texto_normal precios"><a class="colorSub" href="<?php echo url_for('profile/publicprofile?id=' . $user->getId()) ?>" title="Ver calificaciones hechas a <?= $primerNombre ?>">Ver Calificaciones</a></div>-->
                    <?php if ($car->getDescription() != null) { ?>
                        <!--
                        <h2 class="subtitulos punteado espaciado">Breve Descripci&oacute;n del auto</h2>
                        <div class="texto_normal precios"><?php echo $car->getDescription(); ?></div>
                        -->
                    <?php } ?>

                    <div class="seccion" style="margin-left:5px;width: 200px;margin-top:25px;height: auto;">
                        <h2 class="subtitulos punteado espaciado" style="margin-top: 0px;margin-left: 0px;width:105%;">Da&ntilde;os del auto</h2>
                        <?php
                        if ($arrayFotosDanios) {
                            ?>
                            <div id="galleryDanios">
                                <ul>
                                    <?php
                                    $cantidadFotosDanios = count($arrayFotosDanios);
                                    for ($i = 0; $i < $cantidadFotosDanios; $i++) {
                                        if (($i+1) % 4 == 0){
                                            echo "<li style='margin-bottom:3px;float:left;'>";
                                        }else{
                                        ?>
                                        <li style="float: left;margin-right:4px;margin-bottom:3px">
                                        <?php
                                        }
                                        ?>

                                            <a title="<?= $arrayDescripcionesDanios[$i]; ?>" href="http://www.arriendas.cl/main/s3thumb?alto=600&ancho=600&urlFoto=http://www.arriendas.cl/uploads/damages/<?= $arrayFotosDanios[$i]; ?>">
                                                <img title="<?= $arrayDescripcionesDanios[$i]; ?>" src="http://res.cloudinary.com/arriendas-cl/image/fetch/w_40,h_40,c_fill,g_center/http://arriendas.cl/uploads/damages/<?= $arrayFotosDanios[$i]; ?>" width="40" height="40" alt="<?=$image_alt?>" >
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
                                echo "<span style='font-style: italic; color:#BCBEB0;margin-left: 20px;font-size: 13px;'>Auto no presenta Daños</span>";
                            } else {
                                echo "<span style='font-style: italic; color:#BCBEB0;margin-left: 20px;font-size: 13px;'>Auto no verificado</span>";
                            }
                        }
                        ?>
                    </div><!--fin seccion -->
                    <div class="seccion" style="margin-top:25px;height: 25px;">
                        <h2 class="subtitulos punteado" style="margin-top:0px;margin-left:0px;">Accesorios</h2>
                    </div><!--fin seccion -->
                    <?php
                        //declarando arreglo de accesorios activos ordenados según pdf
                        $arr_accesorios_activos = array(1,0, 1, 0, 1, 0, 1, 1,  0, 1, 1, 0); //para efectos de prueba se declara una costante, posteriormente se debe trabajar obteniendo la información desde la DB
                    ?>
                    <div class="cont_accesorios">
                        <?php
                            $cant_arr_accesorios_act = count($arr_accesorios_activos);
                            for ($acc=0;$acc<$cant_arr_accesorios_act;$acc++){
                                $urlCarpeta = "";
                                $title_acc = "";
                                if ($arr_accesorios_activos[$acc] == 0) {
                                    $urlCarpeta = "gris";
                                    $title_acc = "No disponible";
                                }else{
                                    $urlCarpeta = "color";
                                    $title_acc = "Disponible";
                                }
                                ?>
                                <div class="secc_accesorio">
                                    <?php
                                    $text_label = "";
                                    if ($acc == 0) $text_label = "Aire Acondicionado";
                                    elseif ($acc == 1) $text_label = "Silla de Bebé";
                                    elseif ($acc == 2) $text_label = "Espejo Eléctrico";
                                    elseif ($acc == 3) $text_label = "Espejo Manual";
                                    elseif ($acc == 4) $text_label = "Vidrios Eléctiros";
                                    elseif ($acc == 5) $text_label = "Vidrios Manuales";
                                    elseif ($acc == 6) $text_label = "Cierre Centralizado";
                                    elseif ($acc == 7) $text_label = "Alarma";
                                    elseif ($acc == 8) $text_label = "Sensor Acercamiento y Retroceso";
                                    elseif ($acc == 9) $text_label = "Sistema ABS";
                                    elseif ($acc == 10) $text_label = "Airbag";
                                    elseif ($acc == 11) $text_label = "Tapicería de Cuero";
                                    ?>
                                    <div class="texto_normal precios"><div class="label_acc" style="<?php if($urlCarpeta == 'gris'){echo 'color:#d1d1d1;';}?>"><strong><?=$text_label?></strong></div><?php echo image_tag("img_autos/accesorios/".$urlCarpeta."/".($acc+1).".png", "size=32x32 title='".$text_label." ".$title_acc."'"); ?></div>
                                </div>
                                <?php
                            }//fin for
                        ?>
                    </div>
                </div>

                <div id="evaluacionesPublicProfile" style="margin-left:26px;margin-top: 0px;">
                    <div class="evaluacion">
                        <?php
                        $cantidadDeComentariosSobreMi = count($comentarios);
                        if ($comentarios[0] == null) {
                            ?>
                            <div class="seccion" style="margin-top:25px;height: 25px;">
                                <h2 class="subtitulos punteado" style="margin-top:0px;margin-left:0px;">Calificaciones (0)</h2>
                            </div><!--fin seccion -->
                            <p style="float:left;margin-left:10px;font-size:14px;color:#BCBEB0;font-style:italic;"><?= $user->getFirstname(); ?> aún no tiene calificaciones</p>
                            <?php
                        } else {
                            ?>
                            <div class="seccion" style="margin-top:25px;height: 25px;">
                                <h2 class="subtitulos punteado" style="margin-top:0px;margin-left:0px;">Calificaciones (<?= $cantidadDeComentariosSobreMi; ?>)</h2>
                            </div><!--fin seccion -->

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
        <?php echo image_tag('img_evaluaciones/TrianguloComentarios.png', 'class=punta difpunta') ?>
                                        <div class="margenTexto" style="width:580px;">
                                            <div class="texto" style="width:568px;text-align: center;">
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

<?php //if (sfContext::getInstance()->getUser()->getAttribute("logged")) include_component('profile', 'colDer') ?>  

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