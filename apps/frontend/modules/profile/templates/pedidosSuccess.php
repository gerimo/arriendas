<?php use_stylesheet('calendario.css') ?>
<?php use_stylesheet('registro.css') ?>
<?php use_stylesheet('mis_arriendos.css') ?>
<?php use_stylesheet('comunes.css') ?>
<?php use_javascript('pedidos.js?v=08012013') ?>
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
</script>

<div class="main_box_1">
    <div class="main_box_2">


        <?php include_component('profile', 'profile') ?>

        <!--  contenido de la seccion -->
        <div class="main_contenido">

            <?php

            $mostrarReservasRealizadas = false;
            $mostrarReservasRecibidas = false;
            $mostrarBarra = false;

            if($reservasRealizadas){
                foreach ($reservasRealizadas as $reserva) {
                    $mostrarReservasRealizadas = true;
                }
            }

            if($reservasRecibidas){
                foreach ($reservasRecibidas as $reserva) {
                    $mostrarReservasRecibidas = true;
                }
            }

            if(!$mostrarReservasRealizadas && !$mostrarReservasRecibidas){
                echo "<div class='barraSuperior'><p>PEDIDOS</p></div>";
            }else if($mostrarReservasRealizadas && !$mostrarReservasRecibidas){
                echo "<div class='barraSuperior'><p>PEDIDOS REALIZADOS</p></div>";
            }else if(!$mostrarReservasRealizadas && $mostrarReservasRecibidas){
                echo "<div class='barraSuperior'><p>PEDIDOS RECIBIDOS</p></div>";
            }else{
                echo "<div class='barraSuperior'><p>PEDIDOS</p></div>";
                $mostrarBarra = true;
            }

            ?>
            <?php if($mostrarBarra){ ?>
            <div class="barraNavegadora">
                <div id="boton1" class='oscuro'>Pedidos Realizados</div>
                <div id="intermedio" class='intOscCla'></div>
                <div id="boton2" class='claro'>Pedidos Recibidos</div>
            </div>
            <?php } ?>
            
            <div style="display:none">
                <div id='confirmarEditarHora'>
                    <p>¿Desea editar la fecha y hora de todos los pedidos realizados para la fecha <span id='textoFecha'>seleccionada</span>?</p>
                </div>

                <div id='mapa'>
                    <p>mapa de ubicacion <span id='ubicacion'></span></p>
                    <img src="" id='googleMaps'>
                </div>

                <div id='editarHora'>
                    <div class='izq'>
                        <p class='titulo'>Desde</p>
                        <p><label>Fecha</label>
                        <input id="datefrom" name="datefrom" type="text" class="datepicker" readonly="readonly" value=""/></p>
                        <p><label>Hora</label>
                        <input readonly="readonly" type="text" id="hour_from" name="hour_from" readonly="readonly" value=""/></p>
                    </div>

                    <div class='der'>
                        <p class='titulo'>Hasta</p>
                        <p><label>Fecha</label>
                        <input id="dateto" name="dateto" type="text" class="datepicker" readonly="readonly" value="Día de entrega"/></p>
                        <p><label>Hora</label>
                        <input readonly="readonly" type="text" id="hour_to" name="hour_to" value="Hora de entrega"/></p>
                    </div>

                </div>

                <div id='confirmarContratosArrendatario'>
                    <input type="checkbox" name="contratoArrendatario1" id="contratoArrendatario1" class='checkboxContrato'><p> He visto el <a href='' class='informeDanios' target='_blank'>Informe de daños del veh&iacute;culo</a>.</p>
                    <input type="checkbox" name="contratoArrendatario2" id="contratoArrendatario2" class='checkboxContrato'><p> Entiendo ser responsable por daños producidos durante el arriendo por debajo del deducible de 5UF.</p>
                    <input type="checkbox" name="contratoArrendatario3" id="contratoArrendatario3" class='checkboxContrato'><p> <span id='textoBencinaArrendatario'>Si el arriendo es por menos de un día, pagaré por la bencina utilizada en efectivo según los kilómetros manejados. Si el arriendo es por un día o más, devolveré el veh&iacute;culo con el marcador de bencina en el mismo nivel con el que me fue entregado.</span></p>
                </div>

                <div id='confirmarContratosPropietario'>
                    <input type="checkbox" name="contratoPropietario1" id="contratoPropietario1" class='checkboxContrato'><p> El <a href='' class='informeDanios' target='_blank'>Informe de daños de mi veh&iacute;culo</a> se encuentra completo.</p>
                    <input type="checkbox" name="contratoPropietario2" id="contratoPropietario2" class='checkboxContrato'><p> El arrendatario se hará responsable por daños producidos por debajo del deducible de 5UF.</p>
                    <input type="checkbox" name="contratoPropietario3" id="contratoPropietario3" class='checkboxContrato'><p> <span id='textoBencinaPropietario'>Si el arriendo es por menos de un día, el arrendatario me pagará en efectivo la bencina utilizada según los kilómetros manejados. Si el arriendo es por un día o más, el arrendatario me devolverá el auto con el marcador de bencina en el mismo nivel con el que lo se lo entregué.</span></p>
                </div>

                <div id='extender'>
                    <p>La extensión de la reserva queda condicional a la aprobación del propietario del vehículo, en ningún caso reemplaza la reserva original ni permite la devolución del dinero pagado.</p>
                    <div class='izq'>
                        <p class='titulo'>Desde</p>
                        <p><label>Fecha</label>
                        <input id="datefrom_extender" name="datefrom_extender" type="text" readonly="readonly" value=""/></p>
                        <p><label>Hora</label>
                        <input readonly="readonly" type="text" id="hour_from_extender" name="hour_from_extender" readonly="readonly" value=""/></p>
                    </div>

                    <div class='der'>
                        <p class='titulo'>Hasta</p>
                        <p><label>Fecha</label>
                        <input id="dateto_extender" name="dateto_extender" type="text" class="datepicker" readonly="readonly" value="Día de entrega"/></p>
                        <p><label>Hora</label>
                        <input readonly="readonly" type="text" id="hour_to_extender" name="hour_to_extender" value="Hora de entrega"/></p>
                    </div>
                </div>

            </div>
    
            <?php if($mostrarBarra){ ?>
            <div id="conten_opcion1">
            <?php } ?>

            <?php
            $mostrar = false;
			$checkMostrar=0;

            //ARRENDATARIO $reservasRealizadas
            //echo "<p class='tiutlo'>PEDIDOS REALIZADOS</p>";

//			if($cantidadConversacionesOrdenadas > 0){

			
            if($reservasRealizadas){
                foreach ($reservasRealizadas as $reserva) {
                    //echo $reserva['estado']."<br>";
                    if(isset($reserva['estado']) && $reserva['estado']==3){
                        $mostrar = true;
			            $checkMostrar++;	
                    }
                }
            }
            
            if($mostrar){

            echo "<h3>APROBADOS (PAGADO) ".image_tag('img_pedidos/Check_PedidosDeReserva.png','class=imgCorrecto')."</h3>";
            echo "<p class='textoAyuda'>Debes buscar el auto en la dirección indicada. Cualquier duda contactar al dueño</p>";

            foreach ($reservasRealizadas as $reserva) {
            //for ($i=0; $i < count($reservasRealizadas); $i++) {                
                
                if(isset($reserva['estado']) && $reserva['estado']==3){
                    echo "<div class='bloqueEstado' id='bloque_".$reserva['idReserve']."'>";
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
                                echo "<a href='".url_for('cars/car?id='.$reserva['carId'])."' title='Ver Auto'>";
                                	if($reserva['photoType'] == 0) echo image_tag("../uploads/cars/thumbs/".$reserva['fotoCar'],'class=img_usuario');
                                	else echo image_tag($reserva['fotoCar'],'class=img_usuario');
                                echo "</a>";
                            echo "</div>";
                            echo "<div class='der'>";
                                echo "<span class='textoMediano'>".$reserva['marca'].", ".$reserva['modelo']."</span><br><a href='".url_for('profile/publicprofile?id='.$reserva['contraparteId'])."'>".$reserva['nombre']." ".$reserva['apellidoCorto']."</a>";
                            echo "</div>";
                        echo "</div>";
                        echo "<div class='direccion'>";
                            echo "<a class='nombreMovil' href='".url_for('profile/publicprofile?id='.$reserva['contraparteId'])."'><span class='textoMediano'>".$reserva['nombre']." ".$reserva['apellidoCorto']."</span></a>";
                            echo image_tag('img_pedidos/IconoUbicacion.png');
                            echo " ".$reserva['direccion'].", ".$reserva['comuna']."<!-- <a href='#' class='mapa' id='".$reserva['lat']."_".$reserva['lng']."'>(Ver mapa)</a>-->.<br>".image_tag('img_pedidos/Telefono.png','class=telefono')." +56 ".$reserva['telefono'];
                        echo "</div>";
                        echo "<div class='extender'>";
                            echo "<div class='der'><div class='cargando'>".image_tag('../images/ajax-loader.gif')."</div>";
                                echo "<div class='img'>".image_tag('img_pedidos/IconoAutoAprobado.png')."</div>";
                                echo "<span class='textoColor'>¡APROBADO!</span>";
                            echo "</div>";
                            echo "<a href='#' id='contrato_".$reserva['idReserve']."_".$reserva['carId']."_".$reserva['token']."' class='descargarContrato'>Descargar Contratos</a>";
                        echo "</div>";
                        echo "<div class='pago'>";
                            echo "<a href='#' id='extender_".$reserva['idReserve']."' class='boton_extender ".$reserva['fechaInicio']."_".$reserva['horaInicio']."_".$reserva['fechaTermino']."_".$reserva['horaTermino']."'>".image_tag('img_pedidos/BotonExtender.png')."</a>";
                        echo "</div>";
                    echo "</div>";
                }
            }

                echo"<div class='bloqueEstado'>";
                    echo"<div class='herramientas'>";
                    echo "</div>";
                echo"</div>";
                echo "<div class='mensajeContacto'><p>Si necesita modificar su reserva pagada, escr&iacutebanos a <i>soporte@arriendas.cl</i></p></div>";
            }else{
                //echo "<h3>APROBADOS (PAGADO)</h3>";
                //echo "<p class='alerta'>No registra pedidos aprobados</p>";
            }

            
            $mostrar = false;

            if($reservasRealizadas){
                foreach ($reservasRealizadas as $reserva) {
                    //if(isset($reserva['estado']) && ($reserva['estado']==1 || $reserva['estado']==2)){
                    if(isset($reserva['estado']) && $reserva['estado']==2){
                        $mostrar = true;
			            $checkMostrar++;	
                    }
                }
            }
            
            if($mostrar){

            echo "<h3>PRE APROBADOS (PENDIENTE DE PAGO)";
            echo "<a href='#'>".image_tag('img_pedidos/BotonCancelar.png','id=eliminarResultadosRealizados class=botonHerramientas')."</a>";
            echo "</h3>";
            echo "<p class='textoAyuda'>Hasta no realizar el pago no se confirmará la reserva, ni recibirás la cobertura de seguros durante el arriendo.</p>";

            foreach ($reservasRealizadas as $reserva) {
                if(isset($reserva['idReserve']) && $reserva['estado']==2){

                    echo"<div class='bloqueEstado idCar_".$reserva['carId']."' id='bloque_".$reserva['idReserve']."'>";
                        echo "<div class='checkboxOpcion'>";
                            echo "<input type='checkbox' class='checkbox checkboxResultadosRealizados' id='checkbox_".$reserva['idReserve']."'>";
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
                                echo "<a href='".url_for('cars/car?id='.$reserva['carId'])."' title='Ver Auto'>";
                                    if($reserva['photoType'] == 0) echo image_tag("../uploads/cars/thumbs/".$reserva['fotoCar'],'class=img_usuario');
                                	else echo image_tag($reserva['fotoCar'],'class=img_usuario');
                                echo "</a>";
                            echo "</div>";
                            echo "<div class='der'>";
                                echo "<span class='textoMediano'>".$reserva['marca'].", ".$reserva['modelo']."</span><br><a href='".url_for('profile/publicprofile?id='.$reserva['contraparteId'])."'>".$reserva['nombre']." ".$reserva['apellidoCorto']."</a>";
                            echo "</div>";
                        echo "</div>";
                        echo "<div class='precio'>";
                            echo "<span class='textoMediano nombreMovil'>".$reserva['marca'].", ".$reserva['modelo']."<br></span><a class='nombreMovil' href='".url_for('profile/publicprofile?id='.$reserva['contraparteId'])."'>".$reserva['nombre']." ".$reserva['apellidoCorto']."</a>";
                            echo "<span class='textoGrande2'>$".$reserva['valor']."</span> CLP<br>(".$reserva['tiempoArriendo']." - asegurado)";
                        echo "</div>";
                        echo"<div class='eventoReserva'>";
                            echo "<div class='der'>";
                                echo "<div class='cargando'>".image_tag('../images/ajax-loader.gif')."</div>";
                                echo "<div class='img'>".image_tag('img_pedidos/IconoPreAprobado.png')."</div>";
                                echo "Pre aprobado<br>(Falta pago)";
                            echo "</div>";
                        echo"</div>";
                        echo"<div class='pagoBoton'>";
                                echo "<a href='#'>".image_tag('img_pedidos/BotonPagar.png','class=botonPagar duracion_'.$reserva['duracion'].' id=pagar_'.$reserva['idReserve'])."</a>";
                        echo "</div>";
                    echo"</div>";

                }
            }

            echo"<div class='bloqueEstado'>";
                echo"<div class='herramientas'>";
                echo "</div>";
            echo"</div>";

            }else{
                //echo "<h3>PRE APROBADOS (PENDIENTE DE PAGO)</h3>";
                //echo "<p class='alerta'>No registra resultados";
            }
            


            $mostrar = false;

            if($reservasRealizadas){
                foreach ($reservasRealizadas as $reserva) {
                    if(isset($reserva['estado']) && $reserva['estado']==0){
                        $mostrar = true;
			            $checkMostrar++;	
                    }
                }
            }

            if($mostrar){

            echo "<h3>EN ESPERA";
            echo "<a href='#'>".image_tag('img_pedidos/BotonCancelar.png','id=eliminarEnEsperaRealizados class=botonHerramientas')."</a>";
            echo "<a href='#'>".image_tag('img_pedidos/BotonEditarClaro.png','id=editarEnEsperaRealizados class=botonHerramientas')."</a>";
            echo "</h3>";

            echo "<p class='textoAyuda'>Realiza multiples reservas a otros autos hasta obtener la primera confirmación.</p>";
            ?>
            <div class='lineaSeparacion'></div>
            <div class="pedidosEnEspera">

                <?php
                $i=0;

                echo "<script  type='text/javascript'>var fechasEnEsperaRealizados = new Array();</script>";
                foreach ($fechaReservasRealizadas as $fechaReserva) {
                    if(isset($fechaReserva['fechaInicio'])){
                ?>
                <div class="cabecera">
                    <div class="checkboxOpcion">
                        <input id="cabecera_<?php echo $i; ?>" class="checkbox checkboxCabecera checkboxEnEsperaRealizados" type="checkbox">
                    </div>
                    <div class="fechaReserva">
                        <p><?php echo "<span class='textoChico'>Desde</span> ".$fechaReserva['fechaInicio']." ".$fechaReserva['horaInicio']." - <span class='textoChico'>Hasta</span> ".$fechaReserva['fechaTermino']." ".$fechaReserva['horaTermino'];?></p>
                        <p class='textoAuto'><?php echo $fechaReserva['cantCar']; if($fechaReserva['cantCar']==1){ echo " auto"; }else{ echo " autos"; } ?></p>
                    </div>
                </div>
                <div class="block">
                    <!-- contenido del acordeón -->
                    <?php
                    foreach ($reservasRealizadas as $reserva) {
                        if(isset($fechaReserva['fechaInicio']) && isset($reserva['fechaInicio']) && $fechaReserva['fechaInicio']==$reserva['fechaInicio'] && $fechaReserva['horaInicio']==$reserva['horaInicio'] && $fechaReserva['fechaTermino']==$reserva['fechaTermino'] && $fechaReserva['horaTermino']==$reserva['horaTermino']){

                            echo"<div class='bloqueEstado' id='bloque_".$reserva['idReserve']."'>";
                            echo "<div class='checkboxOpcion'>";
                                echo "<input type='checkbox' class='checkbox cuerpo_".$i." checkboxEnEsperaRealizados checkboxCuerpo' id='checkbox_".$reserva['idReserve']."'>";
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
                                    echo "<a href='".url_for('cars/car?id='.$reserva['carId'])."' title='Ver Auto'>";
                                        if($reserva['photoType'] == 0) echo image_tag("../uploads/cars/thumbs/".$reserva['fotoCar'],'class=img_usuario');
                                		else echo image_tag($reserva['fotoCar'],'class=img_usuario');
                                    echo "</a>";
                                echo "</div>";
                                echo "<div class='der'>";
                                    echo "<span class='textoMediano'>".$reserva['marca'].", ".$reserva['modelo']."</span><br><a href='".url_for('profile/publicprofile?id='.$reserva['contraparteId'])."'>".$reserva['nombre']." ".$reserva['apellidoCorto']."</a>";
                                echo "</div>";
                            echo "</div>";
                            echo "<div class='precio'>";
                            echo "<span class='textoMediano nombreMovil'>".$reserva['marca'].", ".$reserva['modelo']."<br></span><a class='nombreMovil' href='".url_for('profile/publicprofile?id='.$reserva['contraparteId'])."'>".$reserva['nombre']." ".$reserva['apellidoCorto']."</a>";
                                echo "<span class='textoGrande2'>$".$reserva['valor']."</span> CLP<br>(".$reserva['tiempoArriendo']." - asegurado)";
                            echo "</div>";
                            echo"<div class='eventoReserva'>";
                                echo "<div class='der'>";
                                echo "<div class='cargando'>".image_tag('../images/ajax-loader.gif')."</div>";
                                    echo "<div class='img'>".image_tag('img_pedidos/IconoEnEspera.png')."</div>";
                                    echo "En espera de confirmaci&oacute;n";
                                echo "</div>";
                            echo"</div>";
                            echo"</div>";
                        }
                    }
                    echo "<div class='masArriendos'>".image_tag('MasAgregar.png','class=img_masArriendos')." Agrega m&aacute;s reservas</div>";
                    ?>
                    <!-- fin contenido del acordeón -->
                </div>
                
                <?php
                    echo "<script  type='text/javascript'>fechasEnEsperaRealizados[".$i."] = '". $fechaReserva['fechaInicio']." ".$fechaReserva['horaInicio']." a ".$fechaReserva['fechaTermino']." ".$fechaReserva['horaTermino'] ."';</script>";
                    $i++;
                    }
                }
                ?>
            </div>

            <?php
                /*
                echo"<div class='bloqueEstadoCabecera'>";
                    echo"<div class='herramientas'>";
                    echo "</div>";
                echo"</div>";
                */
            }else{
                //echo "<h3>EN ESPERA</h3>";
                //echo "<p class='alerta' style='margin-bottom: 30px;'>No registra pedidos en espera de confirmaci&oacute;n";
            }

				if ($checkMostrar==0){
//					echo "<p class='alerta' style='margin-bottom: 30px;'>No existen pedidos de reserva.";
				};
		
            
            ?>

            <?php if($mostrarBarra){ ?>
            </div>
            <div id="conten_opcion2">
            <?php } ?>

            <?php
            
            //PROPIETARIO
            //muestra la barra de pedidos recibidos solo si tiene algún pedido que mostrar
            

            $mostrar = false;
  //          $checkMostrar = 0;

            if($reservasRecibidas){
                foreach ($reservasRecibidas as $reserva) {
                    if(isset($reserva['estado']) && $reserva['estado']==3){
                        $mostrar = true;
			            $checkMostrar++;	
					}
                }
            }
            
            if($mostrar){

            echo "<h3>APROBADOS (PAGADO) ".image_tag('img_pedidos/Check_PedidosDeReserva.png','class=imgCorrecto')."</h3>";
            echo "<p class='textoAyuda'>La reserva ha sido confirmada.</p>";

            foreach ($reservasRecibidas as $reserva) {
                if(isset($reserva['estado']) && $reserva['estado']==3){
                    echo "<div class='bloqueEstado' id='bloque_".$reserva['idReserve']."'>";
                        echo "<div class='fechaReserva'>";
                            echo "<div class='izq'>";
                                echo $reserva['fechaInicio']."<br>".$reserva['fechaTermino'];
                            echo "</div>";
                            echo "<div class='der'>";
                                echo $reserva['horaInicio']."<br>".$reserva['horaTermino'];
                            echo "</div>";
                        echo "</div>";
                        echo "<div class='infoUsuarioPagados ocultarWeb'>";
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
                            echo "</div>";
                        echo "</div>";
                        echo "<div class='direccion'>";
                            echo "<a class='nombreMovil' href='".url_for('profile/publicprofile?id='.$reserva['contraparteId'])."'><span class='textoMediano'>".$reserva['nombre']." ".$reserva['apellidoCorto']."</span></a>";
                            echo image_tag('img_pedidos/IconoUbicacion.png');
                            echo " ".$reserva['direccion'].", ".$reserva['comuna']."<!-- <a href='#' class='mapa' id='".$reserva['direccion'].",".$reserva['comuna']."'>(Ver mapa)</a>-->.<br>".image_tag('img_pedidos/Telefono.png','class=telefono')." +56 ".$reserva['telefono'];
                        echo "</div>";
                        echo "<div class='extender'>";
                            echo "<div class='der'><div class='cargando'>".image_tag('../images/ajax-loader.gif')."</div>";
                                echo "<div class='img'>".image_tag('img_pedidos/IconoAutoAprobado.png')."</div>";
                                echo "<span class='textoColor'>¡APROBADO!</span>";
                            echo "</div>";
                            echo "<a href='#' id='contrato_".$reserva['idReserve']."_".$reserva['carId']."_".$reserva['token']."' class='descargarContrato'>Descargar Contratos</a>";
                        echo "</div>";
                        echo "<div class='precioPagados'>";
                            echo "<span class='textoGrande2'>$".$reserva['valor']."</span> CLP<br>(".$reserva['tiempoArriendo']." - asegurado)";
                        echo "</div>";
                    echo "</div>";
                }
            }

            echo"<div class='bloqueEstado'>";
                echo"<div class='herramientas'>";
                echo "</div>";
            echo"</div>";

            }else{
                //echo "<h3>APROBADO (PAGADO)</h3>";
                //echo "<p class='alerta'>No registra pedidos pagados";
            }
            
            $mostrar = false;

            if($reservasRecibidas){
                foreach ($reservasRecibidas as $reserva) {
                    if(isset($reserva['estado']) && $reserva['estado']==2){
                        $mostrar = true;
			            $checkMostrar++;	
                    }
                }
            }
            
            if($mostrar){

            echo "<h3>PRE APROBADOS (PENDIENTE DE PAGO)";
            echo "<a href='#'>".image_tag('img_pedidos/BotonCancelar.png','id=eliminarPreaprobadosRecibidos class=botonHerramientas')."</a>";
            echo "</h3>";
            echo "<p class='textoAyuda'>Has aprobado la reserva, no dar inicio al arriendo hasta que el usuario pague a través de Arrienda.</p>";

            foreach ($reservasRecibidas as $reserva) {
                if(isset($reserva['estado']) && $reserva['estado']==2){

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
                            echo "</div>";
                        echo "</div>";
                        echo "<div class='precio'>";
                            echo "<a class='nombreMovil' href='".url_for('profile/publicprofile?id='.$reserva['contraparteId'])."'><span class='textoMediano nombreMovil'>".$reserva['nombre']." ".$reserva['apellidoCorto']."</span></a>";
                            echo "<span class='textoGrande2'>$".$reserva['valor']."</span> CLP<br>(".$reserva['tiempoArriendo']." - asegurado)";
                        echo "</div>";
                        echo"<div class='eventoReserva'>";
                            echo "<div class='der'>";
                                echo "<div class='cargando'>".image_tag('../images/ajax-loader.gif')."</div>";
                                echo "<div class='img'>".image_tag('img_pedidos/IconoPreAprobado.png')."</div>";
                                echo "<div class='texto'>Pre aprobado<br>(Falta pago)</div>";
                            echo "</div>";
                        echo"</div>";
                        echo"<div class='pagoCheckbox'>";
                                echo "<select class='select duracion_".$reserva['duracion']."' id='select_".$reserva['idReserve']."'><option name='preaprobar' selected>Pre aprobar</option><option name='rechazar'>Rechazar</option></select>";
                        echo "</div>";
                    echo"</div>";

                }
            }

            echo"<div class='bloqueEstado'>";
                echo"<div class='herramientas'>";
                echo "</div>";
            echo"</div>";

            }else{
                //echo "<h3>PRE APROBADOS (PENDIENTE DE PAGO)</h3>";
                //echo "<p class='alerta'>No registra pedidos pre aprobados";
            }

            //---------------------------------BLOQUE  EN ESPERA-----------

            $mostrar = false;

            if($reservasRecibidas){
                foreach ($reservasRecibidas as $reserva) {
                    //if(isset($reserva['estado']) && ($reserva['estado']==0 || $reserva['estado']==1)){
                    if(isset($reserva['estado']) && ($reserva['estado']==0)){
                        $mostrar = true;
						$checkMostrar++;
                    }
                }
            }
            
            if($mostrar){

            echo "<h3>EN ESPERA";
            echo "<a href='#'>".image_tag('img_pedidos/BotonCancelar.png','id=eliminarEnEsperaRecibidos class=botonHerramientas')."</a>";
            echo "</h3>";
            echo "<p class='textoAyuda'>Has recibido un pedido de reserva. Perderás el arriendo si tardas más de 48 horas en contestar o si un competidor tuyo lo aprueba antes.</p>";

             foreach ($reservasRecibidas as $reserva) {
                if(isset($reserva['estado']) && $reserva['estado']==0){

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
                                echo "<select class='select enEspera duracion_".$reserva['duracion']."' id='select_".$reserva['idReserve']."'><option name='none' selected>Acci&oacute;n</option><option name='preaprobar'>Pre aprobar</option><option name='rechazar'>Rechazar</option></select>";
                        echo "</div>";
                    echo"</div>";

                }
            }
            /*
            foreach ($reservasRecibidas as $reserva) {
                if(isset($reserva['estado']) && $reserva['estado']==1){

                    echo"<div class='bloqueEstado' id='bloque_".$reserva['idReserve']."'>";
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
                        echo "<div class='infoUsuario'>";
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
                            echo "</div>";
                        echo "</div>";
                        echo "<div class='precio'>";
                            echo "<span class='textoGrande2'>$".$reserva['valor']."</span> CLP<br>(".$reserva['tiempoArriendo']." - asegurado)";
                        echo "</div>";
                        echo"<div class='eventoReserva'>";
                            echo "<div class='der'>";
                            echo "<div class='cargando'>".image_tag('../images/ajax-loader.gif')."</div>";
                                echo "<div class='img'>".image_tag('img_pedidos/IconoRechazado.png')."</div>";
                                echo "<div class='texto'>Rechazado</div>";
                            echo "</div>";
                        echo"</div>";
                        echo"<div class='pagoCheckbox'>";
                                echo "<select class='select' id='select_".$reserva['idReserve']."'><option name='preaprobar'>Pre aprobar</option><option name='rechazar' selected>Rechazar</option></select>";
                        echo "</div>";
                    echo"</div>";

                }
            }
            */

            echo"<div class='bloqueEstado'>";
                echo"<div class='herramientas'>";
                echo "</div>";
            echo"</div>";

            }else{
                //echo "<h3>EN ESPERA</h3>";
                //echo "<p class='alerta'>No registra pedidos en espera de confirmaci&oacute;n";
            }

			if ($checkMostrar==0){
				echo "<p class='alerta' style='margin-bottom: 30px;'>No existen pedidos de reserva.";
			};

            ?>

            <?php if($mostrarBarra){ ?>
            </div>
            <?php } ?>


        </div><!-- main_contenido -->

        <?php include_component('profile', 'colDer') ?>  

        <div class="clear"></div>
    </div><!-- main_box_2 -->
</div>
