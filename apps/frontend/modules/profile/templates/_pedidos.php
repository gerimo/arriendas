<?php
$mostrarReservasRealizadas = false;
$mostrarReservasRecibidas = false;
$mostrarBarra = false;

if ($reservasRealizadas) {
    foreach ($reservasRealizadas as $reserva) {
        $mostrarReservasRealizadas = true;
    }
}

if ($reservasRecibidas) {
    foreach ($reservasRecibidas as $reserva) {
        $mostrarReservasRecibidas = true;
    }
}
if (!$mostrarReservasRealizadas && !$mostrarReservasRecibidas) {
    echo "<div class='barraSuperior'><p>PEDIDOS</p></div>";
} else if ($mostrarReservasRealizadas && !$mostrarReservasRecibidas) {
    echo "<div class='barraSuperior'><p>PEDIDOS REALIZADOS</p></div>";
} else if (!$mostrarReservasRealizadas && $mostrarReservasRecibidas) {
    echo "<div class='barraSuperior'><p>PEDIDOS RECIBIDOS</p></div>";
} else {
    echo "<div class='barraSuperior'><p>PEDIDOS</p></div>";
    $mostrarBarra = true;
}
?>
<?php if ($mostrarBarra) { ?>
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
        <input type="checkbox" name="contratoArrendatario0" id="contratoArrendatario1" class='checkboxContrato'><p> En caso de siniestro, dejaré constancia en carabineros INMEDIATAMENTE.</p>
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

<?php if ($mostrarBarra) { ?>
    <div id="conten_opcion1">
    <?php } ?>

    <?php
    $mostrar = false;
    $checkMostrar = 0;

    //ARRENDATARIO $reservasRealizadas
    //echo "<p class='tiutlo'>PEDIDOS REALIZADOS</p>";
//			if($cantidadConversacionesOrdenadas > 0){


    if ($reservasRealizadas) {
        foreach ($reservasRealizadas as $reserva) {
            //echo $reserva['estado']."<br>";
            if (isset($reserva['estado']) && $reserva['estado'] == 3) {
                $mostrar = true;
                $checkMostrar++;
            }
        }
    }

    if ($mostrar) {

        echo "<h3>APROBADOS (PAGADO) " . image_tag('img_pedidos/Check_PedidosDeReserva.png', 'class=imgCorrecto') . "</h3>";
        echo "<p class='textoAyuda'>Debes buscar el auto en la dirección indicada. Cualquier duda contactar al dueño</p>";

        foreach ($reservasRealizadas as $reserva) {
            //for ($i=0; $i < count($reservasRealizadas); $i++) {                

            if (isset($reserva['estado']) && $reserva['estado'] == 3) {
                echo "<div class='bloqueEstado' id='bloque_" . $reserva['idReserve'] . "'>";
                echo "<div class='fechaReserva'>";
                echo "<div class='izq'>";
                echo $reserva['fechaInicio'] . "<br>" . $reserva['fechaTermino'];
                echo "</div>";
                echo "<div class='der'>";
                echo $reserva['horaInicio'] . "<br>" . $reserva['horaTermino'];
                echo "</div>";
                echo "</div>";
                echo "<div class='infoUsuario ocultarWeb'>";
                echo "<div class='izq'>";
                echo "<a href='" . url_for('cars/car?id=' . $reserva['carId']) . "' title='Ver Auto'>";
                if ($reserva['photoType'] == 0)
                    echo image_tag("../uploads/cars/thumbs/" . $reserva['fotoCar'], 'class=img_usuario');
                else
                    echo image_tag($reserva['fotoCar'], 'class=img_usuario');
                echo "</a>";
                echo "</div>";
                echo "<div class='der'>";
                echo "<span class='textoMediano'>" . $reserva['marca'] . ", " . $reserva['modelo'] . "</span><br><a href='" . url_for('profile/publicprofile?id=' . $reserva['contraparteId']) . "'>" . $reserva['nombre'] . " " . $reserva['apellidoCorto'] . "</a>";
                echo "</div>";
                echo "</div>";
                echo "<div class='direccion'>";
                echo "<a class='nombreMovil' href='" . url_for('profile/publicprofile?id=' . $reserva['contraparteId']) . "'><span class='textoMediano'>" . $reserva['nombre'] . " " . $reserva['apellidoCorto'] . "</span></a>";
                echo image_tag('img_pedidos/IconoUbicacion.png');
                echo " " . $reserva['direccion'] . ", " . $reserva['comuna'] . "<!-- <a href='#' class='mapa' id='" . $reserva['lat'] . "_" . $reserva['lng'] . "'>(Ver mapa)</a>-->.<br>" . image_tag('img_pedidos/Telefono.png', 'class=telefono') . " +56 " . $reserva['telefono'];
                echo "</div>";
                echo "<div class='extender'>";
                echo "<div class='der'><div class='cargando'>" . image_tag('../images/ajax-loader.gif') . "</div>";
                echo "<div class='img'>" . image_tag('img_pedidos/IconoAutoAprobado.png') . "</div>";
                echo "<span class='textoColor'>¡APROBADO!</span>";
                echo "</div>";
                echo "<a href='#' id='contrato_" . $reserva['idReserve'] . "_" . $reserva['carId'] . "_" . $reserva['token'] . "' class='descargarContrato'>Descargar Contratos</a>";
                echo "</div>";
                echo "<div class='pago'>";
                echo "<a href='#' id='extender_" . $reserva['idReserve'] . "' class='boton_extender " . $reserva['fechaInicio'] . "_" . $reserva['horaInicio'] . "_" . $reserva['fechaTermino'] . "_" . $reserva['horaTermino'] . "'>" . image_tag('img_pedidos/BotonExtender.png') . "</a>";
                echo "</div>";
                echo "</div>";
            }
        }

        echo"<div class='bloqueEstado'>";
        echo"<div class='herramientas'>";
        echo "</div>";
        echo"</div>";
        echo "<div class='mensajeContacto'><p>Si necesita modificar su reserva pagada, escr&iacutebanos a <i>soporte@arriendas.cl</i></p></div>";
    }else {
        //echo "<h3>APROBADOS (PAGADO)</h3>";
        //echo "<p class='alerta'>No registra pedidos aprobados</p>";
    }




    $mostrar = false;

    if ($reservasRealizadas) {
        foreach ($reservasRealizadas as $reserva) {
            //if(isset($reserva['estado']) && ($reserva['estado']==1 || $reserva['estado']==2)){
            if (isset($reserva['estado']) && $reserva['estado'] == 2) {
                $mostrar = true;
                $checkMostrar++;
            }
        }
    }


    if ($mostrar) {

        echo "<h3>PRE APROBADOS (PENDIENTE DE PAGO)";
        echo "<a href='#'>" . image_tag('img_pedidos/BotonCancelar.png', 'id=eliminarResultadosRealizados class=botonHerramientas') . "</a>";
        echo "</h3>";
        echo "<p class='textoAyuda'>Hasta no realizar el pago no se confirmará la reserva, ni recibirás la cobertura de seguros durante el arriendo.</p>";

        foreach ($reservasRealizadas as $reserva) {
            if (isset($reserva['idReserve']) && $reserva['estado'] == 2) {

                echo"<div class='bloqueEstado idCar_" . $reserva['carId'] . "' id='bloque_" . $reserva['idReserve'] . "'>";
                echo "<div class='checkboxOpcion'>";
                echo "<input type='checkbox' class='checkbox checkboxResultadosRealizados' id='checkbox_" . $reserva['idReserve'] . "'>";
                echo "</div>";
                echo "<div class='fechaReserva'>";
                echo "<div class='izq'>";
                echo $reserva['fechaInicio'] . "<br>" . $reserva['fechaTermino'];
                echo "</div>";
                echo "<div class='der'>";
                echo $reserva['horaInicio'] . "<br>" . $reserva['horaTermino'];
                echo "</div>";
                echo "</div>";
                echo "<div class='infoUsuario ocultarWeb'>";
                echo "<div class='izq'>";
                echo "<a href='" . url_for('cars/car?id=' . $reserva['carId']) . "' title='Ver Auto'>";
                if ($reserva['photoType'] == 0)
                    echo image_tag("../uploads/cars/thumbs/" . $reserva['fotoCar'], 'class=img_usuario');
                else
                    echo image_tag($reserva['fotoCar'], 'class=img_usuario');
                echo "</a>";
                echo "</div>";
                echo "<div class='der'>";
                echo "<span class='textoMediano'>" . $reserva['marca'] . ", " . $reserva['modelo'] . "</span><br><a href='" . url_for('profile/publicprofile?id=' . $reserva['contraparteId']) . "'>" . $reserva['nombre'] . " " . $reserva['apellidoCorto'] . "</a>";
                echo "</div>";
                echo "</div>";
                echo "<div class='precio'>";
                echo "<span class='textoMediano nombreMovil'>" . $reserva['marca'] . ", " . $reserva['modelo'] . "<br></span><a class='nombreMovil' href='" . url_for('profile/publicprofile?id=' . $reserva['contraparteId']) . "'>" . $reserva['nombre'] . " " . $reserva['apellidoCorto'] . "</a>";
                echo "<span class='textoGrande2'>$" . $reserva['valor'] . "</span> CLP<br>(" . $reserva['tiempoArriendo'] . " - asegurado)";
                echo "</div>";
                echo"<div class='eventoReserva'>";
                echo "<div class='der'>";
                echo "<div class='cargando'>" . image_tag('../images/ajax-loader.gif') . "</div>";
                echo "<div class='img'>" . image_tag('img_pedidos/IconoPreAprobado.png') . "</div>";
                echo "Pre aprobado<br>(Falta pago)";
                echo "</div>";
                echo"</div>";
                echo"<div class='pagoBoton'>";
                echo "<a href='#'>" . image_tag('img_pedidos/BotonPagar.png', 'class=botonPagar duracion_' . $reserva['duracion'] . ' id=pagar_' . $reserva['idReserve']) . "</a>";
                echo "<a href='#'' class='detallereserva' data-type='zoomin'>[DETALLE RESERVA]</a>";
                echo "</div>";
                echo"</div>";
            }
        }

        echo"<div class='bloqueEstado'>";
        echo"<div class='herramientas'>";
        echo "</div>";
        echo"</div>";
    }else {
        //echo "<h3>PRE APROBADOS (PENDIENTE DE PAGO)</h3>";
        //echo "<p class='alerta'>No registra resultados";
    }

    /* oportunidades */
    $mostrarOportunidades = false;
    $cantOportunidadesVisibles = 0;
    if (count($reservasRecibidasOportunidades) > 0) {
        $mostrarOportunidades = true;
        /* compruebo que hayan oportunidades visibles */
        foreach ($reservasRecibidasOportunidades as $oportunidad) {
            if ($oportunidad['estado'] == 2) {
                $cantOportunidadesVisibles++;
            }
        }
    }
    if ($mostrarOportunidades && $cantOportunidadesVisibles > 0) {
        echo "<h3>OFERTAS DE OTROS DUEÑOS";
        echo "<a href='#'>" . image_tag('img_pedidos/BotonCancelar.png', 'id=eliminarResultadosRealizados class=botonHerramientas') . "</a>";
        echo "</h3>";
        echo "<p class='textoAyuda'>Hasta no realizar el pago no se confirmará la reserva, ni recibirás la cobertura de seguros durante el arriendo.</p>";

        foreach ($reservasRecibidasOportunidades as $reserva) {
            if (isset($reserva['idReserve']) && $reserva['estado'] == 2) {

                echo"<div class='bloqueEstado idCar_" . $reserva['carId'] . "' id='bloque_" . $reserva['idReserve'] . "'>";
                echo "<div class='checkboxOpcion'>";
                echo "<input type='checkbox' class='checkbox checkboxResultadosRealizados' id='checkbox_" . $reserva['idReserve'] . "'>";
                echo "</div>";
                echo "<div class='fechaReserva'>";
                echo "<div class='izq'>";
                echo $reserva['fechaInicio'] . "<br>" . $reserva['fechaTermino'];
                echo "</div>";
                echo "<div class='der'>";
                echo $reserva['horaInicio'] . "<br>" . $reserva['horaTermino'];
                echo "</div>";
                echo "</div>";
                echo "<div class='infoUsuario ocultarWeb'>";
                echo "<div class='izq'>";
                echo "<a href='" . url_for('cars/car?id=' . $reserva['carId']) . "' title='Ver Auto'>";
                if ($reserva['photoType'] == 0)
                    echo image_tag("../uploads/cars/thumbs/" . $reserva['fotoCar'], 'class=img_usuario');
                else
                    echo image_tag($reserva['fotoCar'], 'class=img_usuario');
                echo "</a>";
                echo "</div>";
                echo "<div class='der'>";
                echo "<span class='textoMediano'>" . $reserva['marca'] . ", " . $reserva['modelo'] . "</span><br><a href='" . url_for('profile/publicprofile?id=' . $reserva['contraparteId']) . "'>" . $reserva['nombre'] . " " . $reserva['apellidoCorto'] . "</a>";
                echo "</div>";
                echo "</div>";
                echo "<div class='precio'>";
                echo "<span class='textoMediano nombreMovil'>" . $reserva['marca'] . ", " . $reserva['modelo'] . "<br></span><a class='nombreMovil' href='" . url_for('profile/publicprofile?id=' . $reserva['contraparteId']) . "'>" . $reserva['nombre'] . " " . $reserva['apellidoCorto'] . "</a>";
                echo "<span class='textoGrande2'>$" . $reserva['valor'] . "</span> CLP<br>(" . $reserva['tiempoArriendo'] . " - asegurado)";
                echo "</div>";
                echo"<div class='eventoReserva'>";
                echo "<div class='der'>";
                echo "<div class='cargando'>" . image_tag('../images/ajax-loader.gif') . "</div>";
                echo "<div class='img'>" . image_tag('img_pedidos/IconoPreAprobado.png') . "</div>";
                echo "Pre aprobado<br>(Falta pago)";
                echo "</div>";
                echo"</div>";
                echo"<div class='pagoBoton'>";
                echo "<a href='#'>" . image_tag('img_pedidos/BotonPagar.png', 'class=botonPagar duracion_' . $reserva['duracion'] . ' id=pagar_' . $reserva['idReserve']) . "</a>";
                echo "<a href='#'' class='detallereserva' data-type='zoomin'>[DETALLE RESERVA]</a>";
                echo "</div>";
                echo"</div>";
            }
        }

        echo"<div class='bloqueEstado'>";
        echo"<div class='herramientas'>";
        echo "</div>";
        echo"</div>";
    }


    $mostrar = false;

    if ($reservasRealizadas) {
        foreach ($reservasRealizadas as $reserva) {
            if (isset($reserva['estado']) && $reserva['estado'] == 0) {
                $mostrar = true;
                $checkMostrar++;
            }
        }
    }

    if ($mostrar) {

        echo "<h3>EN ESPERA";
        echo "<a href='#'>" . image_tag('img_pedidos/BotonCancelar.png', 'id=eliminarEnEsperaRealizados class=botonHerramientas') . "</a>";
        echo "<a href='#'>" . image_tag('img_pedidos/BotonEditarClaro.png', 'id=editarEnEsperaRealizados class=botonHerramientas') . "</a>";
        echo "</h3>";

        echo "<p class='textoAyuda'>Realiza multiples reservas a otros autos hasta obtener la primera confirmación.</p>";
        ?>
        <div class='lineaSeparacion'></div>
        <div class="pedidosEnEspera">

            <?php
            $i = 0;

            echo "<script  type='text/javascript'>var fechasEnEsperaRealizados = new Array();</script>";
            foreach ($fechaReservasRealizadas as $fechaReserva) {
                if (isset($fechaReserva['fechaInicio'])) {
                    ?>
                    <div class="cabecera">
                        <div class="checkboxOpcion">
                            <input id="cabecera_<?php echo $i; ?>" class="checkbox checkboxCabecera checkboxEnEsperaRealizados" type="checkbox">
                        </div>
                        <div class="fechaReserva">
                            <p><?php echo "<span class='textoChico'>Desde</span> " . $fechaReserva['fechaInicio'] . " " . $fechaReserva['horaInicio'] . " - <span class='textoChico'>Hasta</span> " . $fechaReserva['fechaTermino'] . " " . $fechaReserva['horaTermino']; ?></p>
                            <p class='textoAuto'><?php
                                echo $fechaReserva['cantCar'];
                                if ($fechaReserva['cantCar'] == 1) {
                                    echo " auto";
                                } else {
                                    echo " autos";
                                }
                                ?></p>
                        </div>
                    </div>
                    <div class="block">
                        <!-- contenido del acordeón -->
                        <?php
                        foreach ($reservasRealizadas as $reserva) {
                            if ($reserva['estado'] == 0 && isset($fechaReserva['fechaInicio']) && isset($reserva['fechaInicio']) && $fechaReserva['fechaInicio'] == $reserva['fechaInicio'] && $fechaReserva['horaInicio'] == $reserva['horaInicio'] && $fechaReserva['fechaTermino'] == $reserva['fechaTermino'] && $fechaReserva['horaTermino'] == $reserva['horaTermino']) {

                                echo"<div class='bloqueEstado' id='bloque_" . $reserva['idReserve'] . "'>";
                                echo "<div class='checkboxOpcion'>";
                                echo "<input type='checkbox' class='checkbox cuerpo_" . $i . " checkboxEnEsperaRealizados checkboxCuerpo' id='checkbox_" . $reserva['idReserve'] . "'>";
                                echo "</div>";
                                echo "<div class='fechaReserva'>";
                                echo "<div class='izq'>";
                                echo $reserva['fechaInicio'] . "<br>" . $reserva['fechaTermino'];
                                echo "</div>";
                                echo "<div class='der'>";
                                echo $reserva['horaInicio'] . "<br>" . $reserva['horaTermino'];
                                echo "</div>";
                                echo "</div>";
                                echo "<div class='infoUsuario ocultarWeb'>";
                                echo "<div class='izq'>";
                                echo "<a href='" . url_for('cars/car?id=' . $reserva['carId']) . "' title='Ver Auto'>";
                                if ($reserva['photoType'] == 0)
                                    echo image_tag("../uploads/cars/thumbs/" . $reserva['fotoCar'], 'class=img_usuario');
                                else
                                    echo image_tag($reserva['fotoCar'], 'class=img_usuario');
                                echo "</a>";
                                echo "</div>";
                                echo "<div class='der'>";
                                echo "<span class='textoMediano'>" . $reserva['marca'] . ", " . $reserva['modelo'] . "</span><br><a href='" . url_for('profile/publicprofile?id=' . $reserva['contraparteId']) . "'>" . $reserva['nombre'] . " " . $reserva['apellidoCorto'] . "</a>";
                                echo "</div>";
                                echo "</div>";
                                echo "<div class='precio'>";
                                echo "<span class='textoMediano nombreMovil'>" . $reserva['marca'] . ", " . $reserva['modelo'] . "<br></span><a class='nombreMovil' href='" . url_for('profile/publicprofile?id=' . $reserva['contraparteId']) . "'>" . $reserva['nombre'] . " " . $reserva['apellidoCorto'] . "</a>";
                                echo "<span class='textoGrande2'>$" . $reserva['valor'] . "</span> CLP<br>(" . $reserva['tiempoArriendo'] . " - asegurado)";
                                echo "</div>";
                                echo"<div class='eventoReserva'>";
                                echo "<div class='der'>";
                                echo "<div class='cargando'>" . image_tag('../images/ajax-loader.gif') . "</div>";
                                echo "<div class='img'>" . image_tag('img_pedidos/IconoEnEspera.png') . "</div>";
                                echo "<div class='texto'>En espera de confirmaci&oacute;n</div>";
                                echo "</div>";
                                echo"</div>";
                                echo"</div>";
                            }
                        }
                        //echo "<div class='masArriendos'>" . image_tag('MasAgregar.png', 'class=img_masArriendos') . " Agrega m&aacute;s reservas</div>";
                        echo "<div class='masArriendos'>";
                        echo "<div class='titleMasAutos'>";
                            echo "<div class='subtitle1'><p>¿AÚN SIN RESPUESTAS?</p></div>";
                            echo "<div class='subtitle2'><p><span>TE RECOMENDAMOS AUTOS PARA LA</span> MISMA FECHA Y HORA</p></div>";
                        echo "</div>";

//Comienzo autos recomendados

    echo "<div id='contenedorRecomenderCars'>";
        echo "<form>";
        $i = 1;
        foreach ($cars as $c):
            
            echo "<div class='div_car_recomender' id='".$c["id"]."'>";
                echo "<div class='marcador_car_recomender'>";
                    echo "<input class= 'marcador' type='checkbox' name='carRecomender' value='car_".$c["id"]."'/>";
                echo "</div>";
                echo "<div class='photo_car_recomender'>";
                        if ($c["photo"] != NULL){ 
                            if($c["photoType"] == 1) {
                                echo "<img class='photo' width='74px' height='56px' src='".url_for('main/s3thumb')."'?alto=64&ancho=64&urlFoto='".urlencode($c['photo'])."'/>";
                            }else{
                                echo "<img class='photo' width='74px' height='56px' src='".image_path('../uploads/cars/thumbs/'.urlencode($c['photo']))."'/>";

                            }
                        }else{
                            echo image_tag('default.png', 'size=74x56', 'class=photo');
                        }
                echo "</div>";
                echo "<ul class='info_car_recomender'>";
                    echo "<input type='hidden' class='link' value='".url_for('auto/economico?chile='.$c["comuna"] .'?id=' . $c["id"])."'/>";
                    echo "<li class='marca_modelo'>".'<a target="_blank" title="Ir al perfil del auto" href="'.url_for('auto/economico?chile=' . $c["comuna"].'&id='. $c["id"]).'">'.$c["brand"].", ".$c["model"]."</a>";
                    echo "</li>";
                    echo "<li class='stars'>";
                    echo "<script type='text/javascript'>
                            var numStars = Math.floor((Math.random()*5)+1);
                            generarBarraEstrellas(numStars);
                          </script>";
                    echo "</li>";
                    echo "<li class='direccionCar'>".ucwords(strtolower($c["comuna"])).", Santiago</li>";
                echo "</ul>";
                echo "<ul class='valor_car_recomender'>";
                    echo "<li class='valor'>$30.000</li>";
                    echo "<li class='tiempoAsegurado'>(1 día y 5 horas asegurado)</li>";
                echo "</ul>";
            echo "</div>";
            $i++;
        endforeach;
        echo "</form>";
    echo "</div>";
    echo "<div id='contenedorBtnReservarTodos'><button id='btnReservarTodos' title='Reservar todos los autos'></button></div>";

//Fin autos recomendados
                        
                        ?>
                        <!-- fin contenido del acordeón -->
                    </div>

            <?php
            echo "<script  type='text/javascript'>fechasEnEsperaRealizados[" . $i . "] = '" . $fechaReserva['fechaInicio'] . " " . $fechaReserva['horaInicio'] . " a " . $fechaReserva['fechaTermino'] . " " . $fechaReserva['horaTermino'] . "';</script>";
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
        }else {
            //echo "<h3>EN ESPERA</h3>";
            //echo "<p class='alerta' style='margin-bottom: 30px;'>No registra pedidos en espera de confirmaci&oacute;n";
        }

        $mostrar = false;

        if ($reservasRealizadas) {
            foreach ($reservasRealizadas as $reserva) {
                if (isset($reserva['estado']) && $reserva['estado'] == 1) {
                    $mostrar = true;
                    $checkMostrar++;
                }
            }
        }

        if ($mostrar) {

            echo "<h3>CANCELADOS</h3>";

            foreach ($reservasRealizadas as $reserva) {
                if (isset($reserva['estado']) && $reserva['estado'] == 1) {

                    echo"<div class='bloqueEstado idCar_" . $reserva['carId'] . "' id='bloque_" . $reserva['idReserve'] . "'>";
                    echo "<div class='checkboxOpcion'>";
                    echo "</div>";
                    echo "<div class='fechaReserva'>";
                    echo "<div class='izq'>";
                    echo $reserva['fechaInicio'] . "<br>" . $reserva['fechaTermino'];
                    echo "</div>";
                    echo "<div class='der'>";
                    echo $reserva['horaInicio'] . "<br>" . $reserva['horaTermino'];
                    echo "</div>";
                    echo "</div>";
                    echo "<div class='infoUsuario ocultarWeb'>";
                    echo "<div class='izq'>";
                    echo "<a href='" . url_for('profile/publicprofile?id=' . $reserva['contraparteId']) . "' title='Ver perfil'>";
                    if ($reserva['facebook'] != null && $reserva['facebook'] != "") {
                        echo "<img src='" . $reserva['urlFoto'] . "' class='img_usuario'/>";
                    } else {
                        if ($reserva['urlFoto'] != "") {
                            $filename = explode("/", $reserva['urlFoto']);
                            echo image_tag("users/" . $filename[Count($filename) - 1], 'class=img_usuario');
                        } else {
                            echo image_tag('img_registro/tmp_user_foto.jpg', 'class=img_usuario');
                        }
                    }
                    echo "</a>";
                    echo "</div>";
                    echo "<div class='der'>";
                    echo "<a href='" . url_for('profile/publicprofile?id=' . $reserva['contraparteId']) . "'><span class='textoMediano'>" . $reserva['nombre'] . " " . $reserva['apellidoCorto'] . "</span></a>";
                    echo "</div>";
                    echo "</div>";
                    echo "<div class='precio'>";
                    echo "<a class='nombreMovil' href='" . url_for('profile/publicprofile?id=' . $reserva['contraparteId']) . "'><span class='textoMediano nombreMovil'>" . $reserva['nombre'] . " " . $reserva['apellidoCorto'] . "</span></a>";
                    echo "<span class='textoGrande2'>$" . $reserva['valor'] . "</span> CLP<br>(" . $reserva['tiempoArriendo'] . " - asegurado)";
                    echo "</div>";
                    echo"<div class='eventoReserva'>";
                    echo "<div class='der'>";
                    echo "<div class='img'>" . image_tag('img_pedidos/IconoRechazado.png') . "</div>";
                    echo "<div class='texto'>Cancelado</div>";
                    echo "</div>";
                    echo"</div>";
                    echo"<div class='pagoCheckbox'>";
                    echo "</div>";
                    echo"</div>";
                }
            }
        }
        if ($checkMostrar == 0) {
//					echo "<p class='alerta' style='margin-bottom: 30px;'>No existen pedidos de reserva.";
        };
        ?>

    <?php if ($mostrarBarra) { ?>
    </div>
    <div id="conten_opcion2">
    <?php } ?>

    <?php
    //PROPIETARIO
    //muestra la barra de pedidos recibidos solo si tiene algún pedido que mostrar


    $mostrar = false;
    //          $checkMostrar = 0;

    if ($reservasRecibidas) {
        foreach ($reservasRecibidas as $reserva) {
            if (isset($reserva['estado']) && $reserva['estado'] == 3) {
                $mostrar = true;
                $checkMostrar++;
            }
        }
    }

    if ($mostrar) {

        echo "<h3>APROBADOS (PAGADO) " . image_tag('img_pedidos/Check_PedidosDeReserva.png', 'class=imgCorrecto') . "</h3>";
        echo "<p class='textoAyuda'>La reserva ha sido confirmada.</p>";

        foreach ($reservasRecibidas as $reserva) {
            if (isset($reserva['estado']) && $reserva['estado'] == 3) {
                echo "<div class='bloqueEstado' id='bloque_" . $reserva['idReserve'] . "'>";
                echo "<div class='fechaReserva'>";
                echo "<div class='izq'>";
                echo $reserva['fechaInicio'] . "<br>" . $reserva['fechaTermino'];
                echo "</div>";
                echo "<div class='der'>";
                echo $reserva['horaInicio'] . "<br>" . $reserva['horaTermino'];
                echo "</div>";
                echo "</div>";
                echo "<div class='infoUsuarioPagados ocultarWeb'>";
                echo "<div class='izq'>";
                echo "<a href='" . url_for('profile/publicprofile?id=' . $reserva['contraparteId']) . "' title='Ver perfil'>";
                if ($reserva['facebook'] != null && $reserva['facebook'] != "") {
                    echo "<img src='" . $reserva['urlFoto'] . "' class='img_usuario'/>";
                } else {
                    if ($reserva['urlFoto'] != "") {
                        $filename = explode("/", $reserva['urlFoto']);
                        echo image_tag("users/" . $filename[Count($filename) - 1], 'class=img_usuario');
                    } else {
                        echo image_tag('img_registro/tmp_user_foto.jpg', 'class=img_usuario');
                    }
                }
                echo "</a>";
                echo "</div>";
                echo "<div class='der'>";
                echo "<a href='" . url_for('profile/publicprofile?id=' . $reserva['contraparteId']) . "'><span class='textoMediano'>" . $reserva['nombre'] . " " . $reserva['apellidoCorto'] . "</span></a>";
                echo "</div>";
                echo "</div>";
                echo "<div class='direccion'>";
                echo "<a class='nombreMovil' href='" . url_for('profile/publicprofile?id=' . $reserva['contraparteId']) . "'><span class='textoMediano'>" . $reserva['nombre'] . " " . $reserva['apellidoCorto'] . "</span></a>";
                echo image_tag('img_pedidos/IconoUbicacion.png');
                echo " " . $reserva['direccion'] . ", " . $reserva['comuna'] . "<!-- <a href='#' class='mapa' id='" . $reserva['direccion'] . "," . $reserva['comuna'] . "'>(Ver mapa)</a>-->.<br>" . image_tag('img_pedidos/Telefono.png', 'class=telefono') . " +56 " . $reserva['telefono'];
                echo "</div>";
                echo "<div class='extender'>";
                echo "<div class='der'><div class='cargando'>" . image_tag('../images/ajax-loader.gif') . "</div>";
                echo "<div class='img'>" . image_tag('img_pedidos/IconoAutoAprobado.png') . "</div>";
                echo "<span class='textoColor'>¡APROBADO!</span>";
                echo "</div>";
                echo "<a href='#' id='contrato_" . $reserva['idReserve'] . "_" . $reserva['carId'] . "_" . $reserva['token'] . "' class='descargarContrato'>Descargar Contratos</a>";
                echo "</div>";
                echo "<div class='precioPagados'>";
                echo "<span class='textoGrande2'>$" . $reserva['valor'] . "</span> CLP<br>(" . $reserva['tiempoArriendo'] . " - asegurado)";
                echo "</div>";
                echo "</div>";
            }
        }

        echo"<div class='bloqueEstado'>";
        echo"<div class='herramientas'>";
        echo "</div>";
        echo"</div>";
    } else {
        //echo "<h3>APROBADO (PAGADO)</h3>";
        //echo "<p class='alerta'>No registra pedidos pagados";
    }

    $mostrar = false;

    if ($reservasRecibidas) {
        foreach ($reservasRecibidas as $reserva) {
            if (isset($reserva['estado']) && $reserva['estado'] == 2) {
                $mostrar = true;
                $checkMostrar++;
            }
        }
    }

    if ($mostrar) {

        echo "<h3>PRE APROBADOS (PENDIENTE DE PAGO)";
        echo "<a href='#'>" . image_tag('img_pedidos/BotonCancelar.png', 'id=eliminarPreaprobadosRecibidos class=botonHerramientas') . "</a>";
        echo "</h3>";
        echo "<p class='textoAyuda'>Has aprobado la reserva, no dar inicio al arriendo hasta que el usuario pague a través de Arrienda.</p>";

        foreach ($reservasRecibidas as $reserva) {
            if (isset($reserva['estado']) && $reserva['estado'] == 2) {

                echo"<div class='bloqueEstado idCar_" . $reserva['carId'] . "' id='bloque_" . $reserva['idReserve'] . "'>";
                echo "<div class='checkboxOpcion'>";
                echo "<input type='checkbox' class='checkbox checkboxEnEsperaRecibidos' id='checkbox_" . $reserva['idReserve'] . "'>";
                echo "</div>";
                echo "<div class='fechaReserva'>";
                echo "<div class='izq'>";
                echo $reserva['fechaInicio'] . "<br>" . $reserva['fechaTermino'];
                echo "</div>";
                echo "<div class='der'>";
                echo $reserva['horaInicio'] . "<br>" . $reserva['horaTermino'];
                echo "</div>";
                echo "</div>";
                echo "<div class='infoUsuario ocultarWeb'>";
                echo "<div class='izq'>";
                echo "<a href='" . url_for('profile/publicprofile?id=' . $reserva['contraparteId']) . "' title='Ver perfil'>";
                if ($reserva['facebook'] != null && $reserva['facebook'] != "") {
                    echo "<img src='" . $reserva['urlFoto'] . "' class='img_usuario'/>";
                } else {
                    if ($reserva['urlFoto'] != "") {
                        $filename = explode("/", $reserva['urlFoto']);
                        echo image_tag("users/" . $filename[Count($filename) - 1], 'class=img_usuario');
                    } else {
                        echo image_tag('img_registro/tmp_user_foto.jpg', 'class=img_usuario');
                    }
                }
                echo "</a>";
                echo "</div>";
                echo "<div class='der'>";
                echo "<a href='" . url_for('profile/publicprofile?id=' . $reserva['contraparteId']) . "'><span class='textoMediano'>" . $reserva['nombre'] . " " . $reserva['apellidoCorto'] . "</span></a>";
                echo "</div>";
                echo "</div>";
                echo "<div class='precio'>";
                echo "<a class='nombreMovil' href='" . url_for('profile/publicprofile?id=' . $reserva['contraparteId']) . "'><span class='textoMediano nombreMovil'>" . $reserva['nombre'] . " " . $reserva['apellidoCorto'] . "</span></a>";
                echo "<span class='textoGrande2'>$" . $reserva['valor'] . "</span> CLP<br>(" . $reserva['tiempoArriendo'] . " - asegurado)";
                echo "</div>";
                echo"<div class='eventoReserva'>";
                echo "<div class='der'>";
                echo "<div class='cargando'>" . image_tag('../images/ajax-loader.gif') . "</div>";
                echo "<div class='img'>" . image_tag('img_pedidos/IconoPreAprobado.png') . "</div>";
                echo "<div class='texto'>Pre aprobado<br>(Falta pago)</div>";
                echo "</div>";
                echo"</div>";
                echo"<div class='pagoCheckbox'>";
                echo "<select class='select duracion_" . $reserva['duracion'] . "' id='select_" . $reserva['idReserve'] . "'><option name='preaprobar' selected>Pre aprobar</option><option name='rechazar'>Rechazar</option></select>";
                echo "</div>";
                echo"</div>";
            }
        }

        echo"<div class='bloqueEstado'>";
        echo"<div class='herramientas'>";
        echo "</div>";
        echo"</div>";
    } else {
        //echo "<h3>PRE APROBADOS (PENDIENTE DE PAGO)</h3>";
        //echo "<p class='alerta'>No registra pedidos pre aprobados";
    }

    //---------------------------------BLOQUE  EN ESPERA-----------

    $mostrar = false;

    if ($reservasRecibidas) {
        foreach ($reservasRecibidas as $reserva) {
            //if(isset($reserva['estado']) && ($reserva['estado']==0 || $reserva['estado']==1)){
            if (isset($reserva['estado']) && ($reserva['estado'] == 0)) {
                $mostrar = true;
                $checkMostrar++;
            }
        }
    }

    if ($mostrar) {

        echo "<h3>EN ESPERA";
        echo "<a href='#'>" . image_tag('img_pedidos/BotonCancelar.png', 'id=eliminarEnEsperaRecibidos class=botonHerramientas') . "</a>";
        echo "</h3>";
        echo "<p class='textoAyuda'>Has recibido un pedido de reserva. Perderás el arriendo si tardas más de 48 horas en contestar o si un competidor tuyo lo aprueba antes.</p>";

        foreach ($reservasRecibidas as $reserva) {
            if (isset($reserva['estado']) && $reserva['estado'] == 0) {

                echo"<div class='bloqueEstado idCar_" . $reserva['carId'] . "' id='bloque_" . $reserva['idReserve'] . "'>";
                echo "<div class='checkboxOpcion'>";
                echo "<input type='checkbox' class='checkbox checkboxEnEsperaRecibidos' id='checkbox_" . $reserva['idReserve'] . "'>";
                echo "</div>";
                echo "<div class='fechaReserva'>";
                echo "<div class='izq'>";
                echo $reserva['fechaInicio'] . "<br>" . $reserva['fechaTermino'];
                echo "</div>";
                echo "<div class='der'>";
                echo $reserva['horaInicio'] . "<br>" . $reserva['horaTermino'];
                echo "</div>";
                echo "</div>";
                echo "<div class='infoUsuario ocultarWeb'>";
                echo "<div class='izq'>";
                echo "<a href='" . url_for('profile/publicprofile?id=' . $reserva['contraparteId']) . "' title='Ver perfil'>";
                if ($reserva['facebook'] != null && $reserva['facebook'] != "") {
                    echo "<img src='" . $reserva['urlFoto'] . "' class='img_usuario'/>";
                } else {
                    if ($reserva['urlFoto'] != "") {
                        $filename = explode("/", $reserva['urlFoto']);
                        echo image_tag("users/" . $filename[Count($filename) - 1], 'class=img_usuario');
                    } else {
                        echo image_tag('img_registro/tmp_user_foto.jpg', 'class=img_usuario');
                    }
                }
                echo "</a>";
                echo "</div>";
                echo "<div class='der'>";
                echo "<a href='" . url_for('profile/publicprofile?id=' . $reserva['contraparteId']) . "'><span class='textoMediano'>" . $reserva['nombre'] . " " . $reserva['apellidoCorto'] . "</span></a>";
                echo "</div>";
                echo "</div>";
                echo "<div class='precio'>";
                echo "<a class='nombreMovil' href='" . url_for('profile/publicprofile?id=' . $reserva['contraparteId']) . "'><span class='textoMediano nombreMovil'>" . $reserva['nombre'] . " " . $reserva['apellidoCorto'] . "</span></a>";
                echo "<span class='textoGrande2'>$" . $reserva['valor'] . "</span> CLP<br>(" . $reserva['tiempoArriendo'] . " - asegurado)";
                echo "</div>";
                echo"<div class='eventoReserva'>";
                echo "<div class='der'>";
                echo "<div class='cargando'>" . image_tag('../images/ajax-loader.gif') . "</div>";
                echo "<div class='img'>" . image_tag('img_pedidos/IconoEnEspera.png') . "</div>";
                echo "<div class='texto'>En espera de confirmaci&oacute;n</div>";
                echo "</div>";
                echo"</div>";
                echo"<div class='pagoCheckbox'>";
                echo "<select class='select enEspera duracion_" . $reserva['duracion'] . "' id='select_" . $reserva['idReserve'] . "'><option name='none' selected>Acci&oacute;n</option><option name='preaprobar'>Pre aprobar</option><option name='rechazar'>Rechazar</option></select>";
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
    } else {
        //echo "<h3>EN ESPERA</h3>";
        //echo "<p class='alerta'>No registra pedidos en espera de confirmaci&oacute;n";
    }

    $mostrar = false;

    if ($reservasRecibidas) {
        foreach ($reservasRecibidas as $reserva) {
            if (isset($reserva['estado']) && $reserva['estado'] == 1) {
                $mostrar = true;
                $checkMostrar++;
            }
        }
    }

    if ($mostrar) {

        echo "<h3>CANCELADOS</h3>";

        foreach ($reservasRecibidas as $reserva) {
            if (isset($reserva['estado']) && $reserva['estado'] == 1) {

                echo"<div class='bloqueEstado idCar_" . $reserva['carId'] . "' id='bloque_" . $reserva['idReserve'] . "'>";
                echo "<div class='checkboxOpcion'>";
                echo "</div>";
                echo "<div class='fechaReserva'>";
                echo "<div class='izq'>";
                echo $reserva['fechaInicio'] . "<br>" . $reserva['fechaTermino'];
                echo "</div>";
                echo "<div class='der'>";
                echo $reserva['horaInicio'] . "<br>" . $reserva['horaTermino'];
                echo "</div>";
                echo "</div>";
                echo "<div class='infoUsuario ocultarWeb'>";
                echo "<div class='izq'>";
                echo "<a href='" . url_for('profile/publicprofile?id=' . $reserva['contraparteId']) . "' title='Ver perfil'>";
                if ($reserva['facebook'] != null && $reserva['facebook'] != "") {
                    echo "<img src='" . $reserva['urlFoto'] . "' class='img_usuario'/>";
                } else {
                    if ($reserva['urlFoto'] != "") {
                        $filename = explode("/", $reserva['urlFoto']);
                        echo image_tag("users/" . $filename[Count($filename) - 1], 'class=img_usuario');
                    } else {
                        echo image_tag('img_registro/tmp_user_foto.jpg', 'class=img_usuario');
                    }
                }
                echo "</a>";
                echo "</div>";
                echo "<div class='der'>";
                echo "<a href='" . url_for('profile/publicprofile?id=' . $reserva['contraparteId']) . "'><span class='textoMediano'>" . $reserva['nombre'] . " " . $reserva['apellidoCorto'] . "</span></a>";
                echo "</div>";
                echo "</div>";
                echo "<div class='precio'>";
                echo "<a class='nombreMovil' href='" . url_for('profile/publicprofile?id=' . $reserva['contraparteId']) . "'><span class='textoMediano nombreMovil'>" . $reserva['nombre'] . " " . $reserva['apellidoCorto'] . "</span></a>";
                echo "<span class='textoGrande2'>$" . $reserva['valor'] . "</span> CLP<br>(" . $reserva['tiempoArriendo'] . " - asegurado)";
                echo "</div>";
                echo"<div class='eventoReserva'>";
                echo "<div class='der'>";
                echo "<div class='img'>" . image_tag('img_pedidos/IconoRechazado.png') . "</div>";
                echo "<div class='texto'>Cancelado</div>";
                echo "</div>";
                echo"</div>";
                echo"<div class='pagoCheckbox'>";
                echo "</div>";
                echo"</div>";
            }
        }

        echo"<div class='bloqueEstado'>";
        echo"<div class='herramientas'>";
        echo "</div>";
        echo"</div>";
    }

    if ($checkMostrar == 0) {
        echo "<p class='alerta' style='margin-bottom: 30px;'>No existen pedidos de reserva.";
    };
    ?>

    <?php if ($mostrarBarra) { ?>
    </div>
    <?php } ?>

