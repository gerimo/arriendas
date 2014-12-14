<?php use_stylesheet('subi_tu_auto.css') ?>
<?php use_stylesheet('registro.css') ?>
<?php use_stylesheet('calendario.css') ?>
<?php use_stylesheet('jquery.lightbox-0.5.css') ?>
<?php use_javascript('jquery.lightbox-0.5.js') ?>
<?php use_javascript('jquery.lightbox-0.5.min.js') ?>
<?php use_javascript('jquery.lightbox-0.5.pack.js') ?>
<?php use_stylesheet('mis_arriendos.css') ?>

<?php use_javascript('pedidos.js?v=13112014') ?>

<?php use_stylesheet('ReserveSuccess?v=2.css') ?>
<?php use_javascript('ReserveSuccess?v=2.js') ?>

<script type="text/javascript">
    var urlEliminarPedidosAjax = <?php echo "'".url_for("profile/eliminarPedidosAjax")."';" ?>
    var urlCambiarEstadoPedido = <?php echo "'".url_for("profile/cambiarEstadoPedidoAjax")."';" ?>
    var urlEditarFecha = <?php echo "'".url_for("profile/editarFechaPedidosAjax")."';" ?>
    var urlPedidos = <?php echo "'".url_for("profile/pedidos")."';" ?>
    var urlPago = <?php echo "'".url_for("profile/fbDiscount")."';" ?>
    var urlPagoValidar = <?php echo "'".url_for("profile/checkCanPay")."';" ?>
    var urlExtenderReserva = <?php echo "'".url_for("profile/extenderReservaAjax")."';" ?>
    var urlRefreshPedidos = <?php echo "'".url_for("profile/pedidosAjax")."';" ?>
    var urlUpdateProfile = <?php echo "'".url_for("profile/edit")."';" ?>
    var urlFBconnect = <?php echo "'".url_for("main/loginFacebook")."';" ?>

    var urlCarProfile = <?php echo "'".url_for("cars/car")."';" ?>
</script>

<div style="display:none">
    <?php include_partial('contratosArrendatario') ?>
</div>

<div class="main_box_1">
    <div class="main_box_2">
    
        <?php include_component('profile', 'profile') ?>

        <!--  contenido de la seccion -->
        <div class="main_contenido">

            <!-- btn agregar otro auto -->
            <!--<a href="#" class="upcar_btn_agregar_auto"></a>-->  
            <div class="barraSuperior">
                <p><?php if(count($reserve) > 0) echo "EXTENDER"; else echo "REALIZAR";?> RESERVA</p>
            </div>

            <div class="regis_formulario">  

                <div class="calen_box_3">
                    <div style="padding-top:20px; padding-left:20px;font-size:12px;width:505px;">

                        <?php echo form_tag('profile/doReserve', array('method' => 'post', 'id' => 'frm1')); ?> 

                        <?php if( $sf_user->getFlash('msg') ): ?>
                            <span class="mensaje_alerta"><?php echo html_entity_decode($sf_user->getFlash('msg')); ?></span>
                        <?php endif;?>
                        
                        <?php if(count($reserve) > 0) {?>
                            <?php foreach ($reserve as $r) { ?>
                                <div class="c1 height">
                                    <label>Desde</label>
                                    <input id="datefrom" name="datefrom" type="text" class="datepicker" readonly="readonly" value="<?php echo date('d-m-Y',strtotime($r['fechafin']))?>" /><br/><br/>
                                    <input readonly="readonly" type="text" id="hour_from" name="hour_from" readonly="readonly" value=""/>
                                </div><!-- /c1 -->
                            <?php } ?>
                        <?php } else {?>
                            <!-- muestra la última fecha ingresada vigente, si es que existe -->
                            <div class="c1 height">
                                <label>Desde</label>
                                <input id="datefrom" name="datefrom" type="text" class="datepicker" readonly="readonly" value="<?php if(isset($ultimaFechaValidaDesde)) echo $ultimaFechaValidaDesde; else echo "Día de inicio";?>" /><br/><br/>
                                <input readonly="readonly" type="text" id="hour_from" name="hour_from" readonly="readonly" value="<?php if(isset($ultimaHoraValidaDesde)) echo date("g:i A", strtotime($ultimaHoraValidaDesde)); else echo "Hora de inicio"; ?>"/>
                            </div><!-- /c1 -->
                        <?php } ?>

                        <div class="c1 height">
                            <label>Hasta</label>
                            <input id="dateto" name="dateto" type="text" class="datepicker" readonly="readonly" value="<?php if(isset($ultimaFechaValidaHasta)) echo $ultimaFechaValidaHasta; else echo "Día de entrega";?>"/><br/><br/>
                            <input readonly="readonly" type="text" id="hour_to" name="hour_to" value="<?php if(isset($ultimaHoraValidaHasta)) echo date("g:i A", strtotime($ultimaHoraValidaHasta)); else echo "Hora de entrega"; ?>" />
                        </div><!-- /c1 -->
            
                        <div class="c1 height" style="width:256px;">
                            <label>Valor de la Reserva</label>
                            <label id="valor_reserva">$0</label>
                        </div><!-- /c1 -->


                        <input type="hidden" name="duration" id="duration"/>


                        <input type="hidden" name="id" id="id" value="<?php echo  $car->getId() ?>"/>
                        <?php if(count($reserve) > 0) :?>
                            <?php foreach ($reserve as $r): ?>                        
                                <input type="hidden" name="reserve_id" id="reserve_id" value="<?php echo $r['id']?>"/>
                            <?php endforeach; ?>
                        <?php endif;?>

                        <div class="clear"></div>

                        <input type="hidden" name="newFlow" id="newFlow" value="<?php echo $newFlow ?>" />

                        <div style="max-width: 85%; text-align: right">
                            <button class="arriendas_pink_btn arriendas_big_btn right <?php if (!$newFlow) echo 'oldFlow' ?>" id="payBtn" style="position: initial" type="button">Reservar</button>
                            <input class="botonPagar" data-carid="<?php echo $car->getId() ?>" data-userid="<?php echo $idUsuario ?>" style="display:none">
                            <!-- <br>
                            <input type="submit" value="Consultar" class="btn-link"> -->
                        </div>

                        </form>  

                    </div>
                </div>
            </div>

            <!-- información del auto -->
            <div class="barraSuperior infoAuto" style='float:left; width:100%; margin-top:30px;'>
                <p>INFORMACIÓN DEL AUTO EN RENT A CAR</p>
            </div>
                <div class="ladoIzquierdo">
                    <div id="imagenPerfil">
                        <?php
                            if($car->getPhotoS3() == 1){
                                echo image_tag("http://www.arriendas.cl/main/s3thumb?alto=185&ancho=185&urlFoto=".$car->getFoto(),array("width"=>"185","height"=>"185"));
                            }else{
                                echo image_tag("../uploads/cars/".$car->getFoto(),array("width"=>"185","height"=>"185"));
                            }

                        ?>
                    </div>
                    <?php 
                        if($arrayFotos){
                            if($opcionFotosEnS3 == 1){
                    ?>
                        <div id="gallery">
                          <ul>
                            <?php

                            $cantidadFotos = count($arrayFotos);
                            for($i=0;$i<$cantidadFotos; $i++){
                                ?>
                                <li>
                                    <a title="<?=$arrayDescripcionFotos[$i];?>" href="http://www.arriendas.cl/main/s3thumb?alto=600&ancho=600&urlFoto=<?=$arrayFotos[$i];?>">
                                        <img title="<?=$arrayDescripcionFotos[$i];?>" src="http://www.arriendas.cl/main/s3thumb?alto=40&ancho=40&urlFoto=<?=$arrayFotos[$i];?>" width="40" height="40">
                                    </a>
                                </li>
                                <?php
                            }
                            ?>
                          </ul>
                      </div>
                      <?php
                            }else{
                        ?>
                        <div id="gallery">
                          <ul>
                            <?php

                            $cantidadFotos = count($arrayFotos);
                            for($i=0;$i<$cantidadFotos; $i++){
                                ?>
                                <li>
                                    <a title="<?=$arrayDescripcionFotos[$i];?>" href="<?=image_path('../uploads/verificaciones/'.$arrayFotos[$i]);?>">
                                        <img title="<?=$arrayDescripcionFotos[$i];?>" src="<?=image_path('../uploads/verificaciones/thumbs/'.$arrayFotos[$i]);?>" width="40" height="40">
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

                <div class="botones" style="display: none;">
                    <a href="<?php echo url_for('profile/reserve?id=' . $car->getId()) ?>" style="float:right; margin-right:25px;" title="Realizar una Reserva"><?php echo image_tag('BotonRealizarReserva.png',"class=boton"); ?></a>
                
                </div>
                <div class="titulo">Arriendo <?php echo $car->getModel()->getBrand()." ".$car->getModel().$nombreComunaAuto; ?><?php if($car->autoVerificado()): ?><?php echo image_tag("verificado.png", "class=img_verificado title='Auto Asegurado' size=18x18"); ?><?php endif; ?></div>

                <div class="subtitulos punteado">Precio Final</div>
                <div class="texto_normal precios">Precio por Hora | <span class="texto_magenta"><strong><?php echo "$".number_format(floor($car->getPricePerHour()),0,',','.'); ?></strong></span></div>
                <div class="texto_normal precios">Precio por D&iacute;a | <span class="texto_magenta"><strong><?php echo "$".number_format(floor($car->getPricePerDay()),0,',','.'); ?></strong></span></div>

        <?php if($car->getDisponibilidadSemana()==1 && $car->getDisponibilidadFinde()==1): ?>
                <div class="texto_normal precios">Precio por Semana | 
                <?php if($car->getPricePerWeek()>0): ?><span class="texto_magenta"><strong><?php echo "$".number_format(floor($car->getPricePerWeek()),0,',','.'); ?></strong></span>
                <?php else: ?><a href="mailto:soporte@arriendas.cl&subject=Arriendo Semanal <?php echo $car->getModel()->getBrand()." ".$car->getModel(); ?> (<?php echo $car->getId(); ?>)"><span class="texto_magenta"><strong>Consultar</strong></span></a>
                <?php endif; ?></div>
                <div class="texto_normal precios">Precio por Mes | 
                <?php if($car->getPricePerMonth()>0): ?><span class="texto_magenta"><strong><?php echo "$".number_format(floor($car->getPricePerMonth()),0,',','.'); ?></strong></span>
                <?php else: ?><a href="mailto:soporte@arriendas.cl&subject=Arriendo Mensual <?php echo $car->getModel()->getBrand()." ".$car->getModel(); ?> (<?php echo $car->getId(); ?>)"><span class="texto_magenta"><strong>Consultar</strong></span></a>
                <?php endif; ?></div>       
        <?php endif; ?>

                <div class="subtitulos punteado">Datos del Auto</div>
                <div class="texto_normal precios"><div class="interlineado">Ubicaci&oacute;n |</div><div class="interlineado2"><strong><?php echo ucwords(strtolower($car->getAddressAprox()))."" .$nombreComunaAuto ?></strong></div></div>
                <div class="texto_normal precios">A&ntilde;o | <strong><?php echo $car->getYear(); ?></strong></div>
                <div class="texto_normal precios">Tipo de Bencina | <strong><?php echo strtoupper($car->getTipoBencina()); ?></strong></div>
                
                <div class="subtitulos punteado espaciado"><p>Due&ntilde;o - <a class="colorSub" href="<?php echo url_for('profile/publicprofile?id=' . $user->getId()) ?>" title="Ir al perfil de <?=$primerNombre?>"><?=$primerNombre." ".$inicialApellido;?></a></p><a href="<?php echo url_for('messages/new?id='.$user->getId()) ?>" style="" title="Enviar e-mail a <?php echo ucwords($user->getFirstname()).' '.ucwords($user->getLastname()); ?>"><?php echo image_tag('img_msj/EnviarMsjSinSombraChico.png',"class=boton2"); ?></a></div>
                <div class="texto_normal precios">Calificaciones Positivas | <strong><?=$aprobacionDuenio['porcentaje'];?>%</strong></div>
                <div class="texto_normal precios">Velocidad de Respuesta | <strong><?php if($velocidadMensajes == ""){ echo "<span style='font-style: italic; color:#BCBEB0;'>No tiene mensajes</span>";}else{ echo $velocidadMensajes;}?></strong></div>
                <div class="texto_normal precios"><a class="colorSub" href="<?php echo url_for('profile/publicprofile?id=' . $user->getId()) ?>" title="Ver calificaciones hechas a <?=$primerNombre?>">Ver Calificaciones</a></div>
                <?php if($car->getDescription() != null){ ?>
                    <div class="subtitulos punteado espaciado">Breve Descripci&oacute;n del auto</div>
                    <div class="texto_normal precios"><?php echo $car->getDescription(); ?></div>
                <?php } ?>

                <?php 
                    if($car->autoVerificado()){
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
                        if($arrayFotosDanios){
                    ?>
                        <div id="galleryDanios">
                          <ul>
                            <?php

                            $cantidadFotosDanios = count($arrayFotosDanios);
                            for($i=0;$i<$cantidadFotosDanios; $i++){
                                ?>
                                <li>
                                    
                                    <a title="<?=$arrayDescripcionesDanios[$i];?>" href="http://www.arriendas.cl/main/s3thumb?alto=600&ancho=600&urlFoto=http://www.arriendas.cl/uploads/damages/<?=$arrayFotosDanios[$i];?>">
                                        <img title="<?=$arrayDescripcionesDanios[$i];?>" src="http://www.arriendas.cl/main/s3thumb?alto=40&ancho=40&urlFoto=http://www.arriendas.cl/uploads/damages/<?=$arrayFotosDanios[$i];?>" width="40" height="40">
                                    </a>
                                </li>
                                <?php
                            }
                            ?>
                          </ul>
                      </div>
                  <?php
                    }else{
                        if($car->autoVerificado()){
                            echo "<span style='font-style: italic; color:#BCBEB0;margin-left: 40px;font-size: 13px;'>Auto no presenta Daños</span>";
                        }else{
                            echo "<span style='font-style: italic; color:#BCBEB0;margin-left: 40px;font-size: 13px;'>Auto no verificado</span>";
                        }
                    }
                  ?>
            </div><!-- fin información del auto -->

        </div><!-- main_contenido -->

         <?php include_component('profile', 'colDer') ?>

    </div><!-- main_box_2 -->

<script type="text/javascript">
    function calcularPrecio(){
        var fecha_inicial= $("#datefrom").val();
        var fecha_final= $("#dateto").val();
        var hora_inicial= convertAmPmto24($("#hour_from").timePicker('getTime').val());
        var hora_final= convertAmPmto24($("#hour_to").timePicker('getTime').val());
        //var fechaInicio = fecha_inicial+' '+hora_inicial;

        if( isValidDate( $('#datefrom').val() ) &&
            isValidDate( $('#dateto').val() ) &&
            isValidTime( convertAmPmto24($('#hour_from').val()) ) &&
            isValidTime( convertAmPmto24($('#hour_to').val()) ) ) {

            if( $('#datefrom').val() == $('#dateto').val() ) {
                var dif = restarHoras(convertAmPmto24($('#hour_from').val()), convertAmPmto24($('#hour_to').val()));                    
                if( dif < 1 ) {
                    alert('La reserva no puede ser menor a 1 hora');
                    return false;
                }
            }
        }
                
        if(fecha_inicial!='' && fecha_final!='Día de entrega' && hora_inicial!='' && hora_final!='Hora de entrega'){
            //alert(fecha_inicial+' '+hora_inicInvalid Date ial+' '+fecha_final+' '+hora_final);        fecha_inicial = fecha_inicial.split('-');
            fecha_inicial = fecha_inicial.split('-');
            fecha_final = fecha_final.split('-');
            hora_inicial = (hora_inicial.split(':'));
            hora_final = (hora_final.split(':'));

            var dateInicio = new Date(fecha_inicial[2],fecha_inicial[1]-1,fecha_inicial[0],hora_inicial[0],hora_inicial[1],0);
            var dateTermino = new Date(fecha_final[2],fecha_final[1]-1,fecha_final[0],hora_final[0],hora_final[1],0);
            var diferencia = new Date(dateTermino.valueOf()-dateInicio.valueOf());
            diferencia = Math.round(diferencia/(1000*3600)); // cantidad de horas de diferencia

            var precioHora = parseFloat(<?php echo $car->getPricePerHour(); ?>);
            var precioDia = parseFloat(<?php echo $car->getPricePerDay() ; ?>);
           var precioSemana = parseFloat(<?php echo $car->getPricePerWeek() ; ?>);
           var precioMes = parseFloat(<?php echo $car->getPricePerMonth() ; ?>);

           var dia = Math.floor(diferencia/24);
            var hora = diferencia%24;
            if(hora>=6){
                hora = 0;
                dia++;
            }

            if (dia>=7 && precioSemana>0){
                precioDia=precioSemana/7
            };
            
            if (dia>=30 && precioMes>0){
                precioDia=precioMes/30
            };  

            var tarifa = precioHora*hora + precioDia*dia;
            $("#valor_reserva").text('$'+anadirPunto(tarifa));

        }
    };
</script>

<!-- Google Code for Pantalla de pedido de reserva Conversion Page -->
<script type="text/javascript">
    /* <![CDATA[ */
    var google_conversion_id = 996876210;
    var google_conversion_language = "en";
    var google_conversion_format = "2";
    var google_conversion_color = "ffffff";
    var google_conversion_label = "hDs_CM7itwQQsr-s2wM";
    var google_conversion_value = 22;
/* ]]> */
</script>

<script type="text/javascript" src="//www.googleadservices.com/pagead/conversion.js"></script>
<noscript>
    <div style="display:inline;">
        <img height="1" width="1" style="border-style:none;" alt="" src="//www.googleadservices.com/pagead/conversion/996876210/?value=22&amp;label=hDs_CM7itwQQsr-s2wM&amp;guid=ON&amp;script=0"/>
    </div>
</noscript>

    <div class="clear"></div>
</div><!-- main_box_1 -->