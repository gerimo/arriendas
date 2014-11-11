

function bindEventsAll(){
    $("#confirmarEditarHora").dialog({
        resizable: false,
        width: 550,
        modal: false,
        autoOpen: false,
        closeOnEscape: false,
        title: 'Editar pedidos de misma fecha',
        position: {my: "center", at: "center"},
        buttons: {
            Si: function() {
                editarTodos();
                $(this).dialog("close");
                $('#editarHora').dialog('open');
            },
            No: function() {
                $(this).dialog("close");
                $('#editarHora').dialog('open');
            }
        }
    });

    $("#editarHora").dialog({
        resizable: false,
        width: 550,
        modal: false,
        autoOpen: false,
        closeOnEscape: false,
        title: 'Editar hora',
        position: {my: "center", at: "center"},
        buttons: {
            "Aceptar": function() {
                //confirmar datos
                if (confirmarFecha()) {
                    //ingresar datos a la bd
                    ingresarNuevasFechas();
                    $(this).dialog("close");
                }
            },
            Cancelar: function() {
                //limpiar campos de fecha
                limpiarFecha();
                $(this).dialog("close");
            }
        }
    });

    /*
     $("#mapa").dialog({
     resizable: false,
     width: 350,
     modal: false,
     autoOpen: false,
     closeOnEscape: false,
     title: 'Ubicaci&oacute;n',
     position: { my: "center top", at: "center top"},
     buttons: {
     "Cerrar": function() {
     $( this ).dialog( "close" );
     $('#googleMaps').attr('src','');
     }
     }
     });
     */

    $('.botonPagar').on('click', function(event) {

        event.preventDefault();

        var newFlow = 0;
        var idUsuario = $(this).data('userid');
        var idCar = $(this).data('carid');        

        if (idUsuario) {
            var newFlow = 1;
        } else {
            var idReserve = $(this).attr('id');
            idReserve = obtenerId(idReserve);
            var duracion = $(this).attr('class').split(' ');
        }

        /* valido que pueda pagar */
        $.ajax({
            type: 'post',
            url: urlPagoValidar,
            data: {idReserve: idReserve, idUsuario: idUsuario}
        }).done(function(data) {
            if (data.length > 0) {
                switch (data) {
                    case 'error:rutnulo':
                        alert("No has ingresado tu RUT, Por favor ingresa tu RUT en tu Perfil para poder realizar el pago.");
                        window.location = urlUpdateProfile + "?redirect=pedidos";
                        break;
                    case 'error:extranjero-sin-facebook':
                        alert("Siendo extranjero, obligatoriamente tienes que ingresar con facebook para poder pagar.");
                        window.location = urlFBconnect + "?return=pedidos";
                        break;
                    case 'error:usermenor':
                        alert("Para arrendar debes tener más de 23 años.");
                        break;
                    case 'error:nobirthdate':
                        alert("Para arrendar debes completar tu fecha de nacimiento.");
                        window.location = urlUpdateProfile + "?redirect=pedidos";
                        break;
                    default:
                        alert('Disculpe, ha ocurrido un error. Por favor inténtelo nuevamente mas tarde.');
                        break;
                }
            } else {
                /* se validó que puede pagar */

                if (newFlow) {
                    /* Se asigna 24 porque pq en la página de realizar reserva si es menor a un día se sigue flujo antiguo */
                    duracion = 24;
                } else {
                    duracion = obtenerId(duracion[1]);
                }                
                
                if (duracion > 23) {
                    $('#textoBencinaArrendatario').text('Devolveré el vehículo con el marcador de bencina en el mismo nivel con el que me fue entregado.');
                } else {
                    $('#textoBencinaArrendatario').text('Pagaré por la bencina utilizada en efectivo según los kilómetros manejados.');
                }

                /*informe de daños*/
                if (!newFlow) { 
                    var classCar = $('#bloque_' + idReserve).attr('class');
                    
                    classCar = classCar.split(' ');
                    for (var i = 0; i < classCar.length; i++) {
                        if (classCar[i].indexOf != -1) {
                            idCar = obtenerId(classCar[i]);
                        }
                    }
                }
                
                $('.informeDanios').attr('href', 'http://arriendas.cl/frontend_dev.php/main/generarReporteDanios/idAuto/' + idCar);

                $("#confirmarContratosArrendatario").dialog({
                    resizable: false,
                    width: 550,
                    modal: false,
                    autoOpen: false,
                    closeOnEscape: false,
                    title: 'Confirmar contratos',
                    position: {my: "center", at: "center"},
                    dialogClass: 'no-close',
                    buttons: {
                        "Aceptar": function() {

                            /*confirmar que se aprueben los contratos*/
                            if (contratosSeleccionados('Arrendatario')) {

                                if (newFlow) {
                                    durationCheck();
                                    $("form#frm1").submit();
                                } else {
                                    window.location.href = urlPago + '/id/' + idReserve;                                    
                                }

                                limpiarContratos('Arrendatario');
                                $(this).dialog("close");
                            }
                        },
                        Cancelar: function() {
                            /*limpiar checkbox*/
                            limpiarContratos('Arrendatario');
                            $(this).dialog("close");
                        }
                    }
                });

                $("#confirmarContratosArrendatario").dialog('open');
            }
        }).fail(function() {
            alert('Disculpe, ha ocurrido un error. Por favor inténtelo nuevamente mas tarde.');
        });
    });

    //evento de select (en espera-propietario)
    $('.select').on('change', function() {

        var isImpulsive = $(this).data('impulsive');
        var signature = $(this).data('signature');

        var select_id = $(this).attr('id');
        var id = obtenerId(select_id);

        var accion = $('#' + select_id + ' option:selected').attr('name');

        if (isImpulsive == 1 && accion == "oportunidad") {

            url = urlNuevoFlujoAceptarOportunidad.replace("reservePattern", id).replace("signaturePattern", signature);
            window.location = url;
            
        } else {

            var duracion = $(this).attr('class').split(' ');

            if (duracion[0].indexOf('_') != -1) {
                duracion = duracion[0];
            } else if (duracion[1].indexOf('_') != -1) {
                duracion = duracion[1];
            } else if (duracion[2].indexOf('_') != -1) {
                duracion = duracion[2];
            }

            duracion = obtenerId(duracion);

            if (duracion > 23) {
                $('#textoBencinaPropietario').text('El arrendatario me devolverá el vehículo con el marcador de bencina en el mismo nivel con el que lo se lo entregué.');
            } else {
                $('#textoBencinaPropietario').text('El arrendatario me pagará en efectivo la bencina utilizada según los kilómetros manejados.');
            }

            //informe de daños
            var classCar = $('#bloque_' + id).attr('class');
            
            classCar = classCar.split(' ');
            for (var i = 0; i < classCar.length; i++) {
                if (classCar[i].indexOf != -1) {
                    idCar = obtenerId(classCar[i]);
                }
            }

            $('.informeDanios').attr('href', 'http://arriendas.cl/main/generarReporteDanios/idAuto/' + idCar);

            if (accion != 'none') {

                if (accion == 'preaprobar' || accion.indexOf('oportunidad') !== -1) { //debe aprobar los contratos

                    if(accion === 'oportunidad'){
                        buildCarSelect(id);
                    }

                    $("#confirmarContratosPropietario").dialog({
                        resizable: false,
                        width: 550,
                        modal: false,
                        autoOpen: false,
                        closeOnEscape: false,
                        title: 'Confirmar contratos',
                        position: {my: "center", at: "center"},
                        dialogClass: 'no-close',
                        buttons: {
                            "Aceptar": function() {
                                if (contratosSeleccionados('Propietario')) {
                                    if (accion === 'oportunidad') {
                                        accion = accion + '-' + $('#carsSelect option:selected').attr('name');
                                    }
                                    cambiarEstado(id, accion);

                                    //limpiar checkbox
                                    limpiarContratos('Propietario');
                                    $(this).dialog("close");
                                }

                            },
                            Cancelar: function() {
                                $('#select_' + id).val('none');
                                //limpiar checkbox
                                limpiarContratos('Propietario');
                                $(this).dialog("close");
                            }
                        }
                    });

                    $("#confirmarContratosPropietario").dialog('open');

                } else {

                    if (confirm('¿Desea rechazar esta reserva?. No podrá ser recuperada')) {
                        cambiarEstado(id, accion);
                    } else {
                        var clases = $(this).attr('class');
                        clases = clases.split(' ');
                        if (clases.length == 2) {
                            $(this).val('none');
                        } else {
                            $(this).val('preaprobar');
                        }
                    }
                }
            }
        }

    });

    $('#datefrom,#dateto').datepicker({
        dateFormat: 'dd-mm-yy',
        buttonImageOnly: true,
        minDate: '-0d'
    });

    $("#hour_from , #hour_to").timePicker();

    $('#dateto_extender').datepicker({
        dateFormat: 'dd-mm-yy',
        buttonImageOnly: true,
        minDate: '-0d'
    });

    $('#hour_to_extender').timePicker();


    var ejecutarAcordeon = true;

    //acordeon
    $('.pedidosEnEspera .cabecera').click(function() {
        var $nextDiv = $(this).next();

        if (ejecutarAcordeon) {
            $nextDiv.slideToggle('fast');
            ejecutarAcordeon = false;
        } else {
            var $visibleSiblings = $nextDiv.siblings('.block:visible');

            if ($visibleSiblings.length) {
                //alert('1');
                $visibleSiblings.slideUp('fast', function() {
                    $nextDiv.slideToggle('fast');
                });
            } else {
                //alert('2');
                //$nextDiv.slideToggle('fast');
            }
        }

    });

    //cambia color a imagenes
    /*
     $('.botonHerramientas').on('hover',function(){
     var src = $(this).attr('src');
     src = src.split('/');
     var ultimaPosicion = src[src.length-1];
     
     if(ultimaPosicion == "BotonCancelar.png"){
     src[src.length-1] = "BotonCancelarClaro.png"
     }else if(ultimaPosicion == "BotonCancelarClaro.png"){
     src[src.length-1] = "BotonCancelar.png"
     }else if(ultimaPosicion == "BotonEditar.png"){
     src[src.length-1] = "BotonEditarClaro.png"
     }else{
     src[src.length-1] = "BotonEditar.png"
     }
     
     src = src.join('/');
     $(this).attr('src',src);
     
     });
     */

    $('#eliminarResultadosRealizados').on('click', function(event) {
        event.preventDefault();
        //alert('eliminar resultados seleccionados');
        var noSeleccionados = true;
        var eliminar = new Array();
        var i = 0;
        $('.checkboxResultadosRealizados').each(function() {
            if ($(this).is(':checked')) {
                //alert('eliminar '+$(this).attr('id'));
                eliminar[i] = obtenerId($(this).attr('id'));
                i++;
                noSeleccionados = false;
            }
        });
        if (noSeleccionados) {
            alert('Debe seleccionar algún pedido de "RESULTADOS" para ser eliminado');
        } else {
            var opcion = confirm('Los pedidos eliminados no podrán ser recuperados');
            if (opcion) {

                for (var i = 0; i < eliminar.length; i++) {
                    $('#bloque_' + eliminar[i] + ' .eventoReserva .der .cargando').show();
                    $.ajax({
                        type: 'post',
                        url: urlEliminarPedidosAjax,
                        data: {
                            idReserve: eliminar[i]
                        }
                    }).done(function() {
                        var del = this.data.split('=')[1];
                        $('#bloque_' + del + ' .eventoReserva .der .cargando').hide();
//						ocultarBloque(id);
                        cambiarImgEstado(del, 'rechazar');
                    }).fail(function() {
                        var del = this.data.split('=')[1];
                        $('#bloque_' + del + ' .eventoReserva .der .cargando').hide();
                        alert('Ha ocurrido un error al eliminar algunos pedidos, inténtelo nuevamente');
                    });
                }
                ;
            } else {
                quitarChecked('.checkboxResultadosRealizados');
            }
        }

    });

    $('#eliminarEnEsperaRealizados').on('click', function(event) {
        event.preventDefault();
        //alert('eliminar resultados seleccionados');
        var noSeleccionados = true;
        var eliminar = new Array();
        var i = 0;
        $('.checkboxEnEsperaRealizados').each(function() {
            if ($(this).is(':checked')) {
                //alert('eliminar '+$(this).attr('id'));
                eliminar[i] = obtenerId($(this).attr('id'));
                i++;
                noSeleccionados = false;
            }
        });
        if (noSeleccionados) {
            alert('Debe seleccionar algún pedido de "EN ESPERA" para ser eliminado');
        } else {
            var opcion = confirm('Los pedidos eliminados no podrán ser recuperados');
            if (opcion) {
                for (var i = 0; i < eliminar.length; i++) {
                    $('#bloque_' + eliminar[i] + ' .eventoReserva .der .cargando').show();
                    $.ajax({
                        type: 'post',
                        url: urlEliminarPedidosAjax,
                        data: {
                            idReserve: eliminar[i]
                        }
                    }).done(function() {
                        var del = this.data.split('=')[1];
                        $('#bloque_' + del + ' .eventoReserva .der .cargando').hide();
//						ocultarBloque(id);
                        cambiarImgEstado(del, 'rechazar');
                    }).fail(function() {
                        var del = this.data.split('=')[1];
                        $('#bloque_' + del + ' .eventoReserva .der .cargando').hide();
                        alert('Ha ocurrido un error al eliminar algunos pedidos, inténtelo nuevamente');
                    });
                }
                ;
            } else {
                quitarChecked('.checkboxEnEsperaRealizados');
            }
        }
    });

    $('#eliminarPreaprobadosRecibidos').on('click', function(event) {
        event.preventDefault();
        //alert('eliminar resultados seleccionados');
        var noSeleccionados = true;
        var eliminar = new Array();
        var i = 0;
        $('.checkboxEnEsperaRecibidos').each(function() {
            if ($(this).is(':checked')) {
                //alert('eliminar '+$(this).attr('id'));
                eliminar[i] = obtenerId($(this).attr('id'));
                i++;
                noSeleccionados = false;
            }
        });
        if (noSeleccionados) {
            alert('Debe seleccionar algún pedido de "PRE APROBADOS" para ser eliminado');
        } else {
            var opcion = confirm('Los pedidos eliminados no podrán ser recuperados');
            if (opcion) {

                for (var i = 0; i < eliminar.length; i++) {
                    $('#bloque_' + eliminar[i] + ' .eventoReserva .der .cargando').show();
                    $.ajax({
                        type: 'post',
                        url: urlEliminarPedidosAjax,
                        data: {
                            idReserve: eliminar[i]
                        }
                    }).done(function() {
                        var del = this.data.split('=')[1];
                        $('#bloque_' + del + ' .eventoReserva .der .cargando').hide();
//						ocultarBloque(id);
                        cambiarImgEstado(del, 'rechazar');
                    }).fail(function() {
                        var del = this.data.split('=')[1];
                        $('#bloque_' + del + ' .eventoReserva .der .cargando').hide();
                        alert('Ha ocurrido un error al eliminar algunos pedidos, inténtelo nuevamente');
                    });
                }
                ;
            } else {
                quitarChecked('.checkboxEnEsperaRecibidos');
            }
        }
    });

    $('#editarEnEsperaRealizados').on('click', function(event) {
        event.preventDefault();

        if (!comprobarSeleccionEditar()) {
            alert('Seleccione reservas de una misma fecha para editar');
            return;
        }

        if (!comprobarBotonEditar()) {
            alert('Solo puede editar reservas de una misma fecha');
            return;
        }

        var editar = new Array();
        var i = 0;
        $('.checkboxCuerpo').each(function() {
            if ($(this).is(':checked')) {
                //alert('editar '+$(this).attr('id'));
                var id = $(this).attr('id');
                editar[i] = obtenerId(id);
                i++;

            }
        });

        var idPadre = obtenerIdPadre(editar[0]);

        cargarFecha(fechasEnEsperaRealizados[idPadre]);

        if (seleccionoBloqueCompleto()) {
            //carga fecha y hora al dialog
            $('#editarHora').dialog('open');
        } else {
            //alert(fechasEnEsperaRealizados[idPadre]);
            $('#textoFecha').text(fechasEnEsperaRealizados[idPadre]);
            $("#confirmarEditarHora").dialog("open");
        }

        for (var i = 0; i < editar.length; i++) {
            //alert('editar '+editar[i]);
        }

    });


    $('#eliminarEnEsperaRecibidos').on('click', function(event) {
        event.preventDefault();
        var noSeleccionados = true;
        var eliminar = new Array();
        var i = 0;
        $('.checkboxEnEsperaRecibidos').each(function() {
            if ($(this).is(':checked')) {
                //alert('eliminar '+$(this).attr('id'));
                eliminar[i] = obtenerId($(this).attr('id'));
                i++;
                noSeleccionados = false;
            }
        });
        if (noSeleccionados) {
            alert('Debe seleccionar algún pedido de "EN ESPERA" para ser eliminado');
        } else {
            var opcion = confirm('Los pedidos eliminados no podrán ser recuperados');
            if (opcion) {
                for (var i = 0; i < eliminar.length; i++) {
                    $('#bloque_' + eliminar[i] + ' .eventoReserva .der .cargando').show();
                    $.ajax({
                        type: 'post',
                        url: urlEliminarPedidosAjax,
                        data: {
                            idReserve: eliminar[i]
                        }
                    }).done(function() {
                        var del = this.data.split('=')[1];
                        $('#bloque_' + del + ' .eventoReserva .der .cargando').hide();
//						ocultarBloque(id);
                        cambiarImgEstado(del, 'rechazar');
                    }).fail(function() {
                        var del = this.data.split('=')[1];
                        $('#bloque_' + del + ' .eventoReserva .der .cargando').hide();
                        alert('Ha ocurrido un error al eliminar algunos pedidos, inténtelo nuevamente');
                    });
                }
                ;
            } else {
                quitarChecked('.checkboxEnEsperaRecibidos');
            }
        }
    });

    function quitarChecked(clase) {
        $(clase).each(function() {
            if ($(this).is(':checked')) {
                $(this).attr('checked', false);
            }
        });
    }

    $('.checkboxCabecera').on('click', function() {
        var idCompleta = $(this).attr('id');
        var id = obtenerId(idCompleta);
        if ($(this).is(':checked')) {
            //quitar los checkboxEnEsperaRealizados
            //quitarChecked('.checkboxEnEsperaRealizados');
            //selecciono $(this)
            //$(this).attr("checked","checked");

            //seleccionar todos los hijos de $(this)
            $('.cuerpo_' + id).each(function() {
                $(this).attr("checked", "checked");
            });

        } else {
            //evitar que se oculte el acordeón
            //quitar todos los checkbox de checkboxEnEsperaRealizados
            $('.cuerpo_' + id).each(function() {
                $(this).attr("checked", false);
            });

            //ejecutarAcordeon = false;
        }

        actualizarBotonEditar();
    });

    $('.checkboxCuerpo').on('click', function() {

        var idCompleta = $(this).attr('class');
        idCompleta = idCompleta.split('_');
        var id = idCompleta[1].charAt(0);

        //se está quitando
        if (!$(this).is(':checked')) {

            //alert(idCompleta);
            //alert(id);
            $('#cabecera_' + id).attr('checked', false);

        } else {
            //se está seleccionando
            //si todos los checkbox están seleccionados, selecciona al padre
            var todosSeleccionados = true;
            $(".cuerpo_" + id).each(function() {
                if (!$(this).is(':checked')) {
                    todosSeleccionados = false;
                }
            });

            if (todosSeleccionados) {
                $('#cabecera_' + id).attr('checked', true);
            }

        }

        actualizarBotonEditar();
    });

    $('.mapa').on('click', function(event) {
        event.preventDefault();

        var ubicacion = $(this).attr('id');
        $('#ubicacion').text(ubicacion);

        ubicacion = ubicacion.split('_');
        var lat = ubicacion[0];
        var lng = ubicacion[1];

        $('#googleMaps').attr('src', 'http://maps.googleapis.com/maps/api/staticmap?center=' + lat + ',' + lng + '&zoom=17&size=300x300&sensor=false');

        $('#mapa').dialog('open');
    });

    $('.descargarContrato').on('click', function(event) {
        event.preventDefault();

        var contrato_id = $(this).attr('id');

        contrato_id = contrato_id.split('_');

        idReserve = contrato_id[1];
        idCar = contrato_id[2];
        tokenReserve = contrato_id[3];

        var urlContrato1 = 'http://www.arriendas.cl/api.php/contrato/generarContrato/tokenReserva/';
        var urlContrato2 = 'http://www.arriendas.cl/main/generarFormularioEntregaDevolucion/tokenReserve/';
        var urlContrato3 = 'http://www.arriendas.cl/main/generarReporte/idAuto/';
        window.open(urlContrato1 + tokenReserve);
        window.open(urlContrato2 + tokenReserve);
        window.open(urlContrato3 + idCar);
    });

    ////////////////////////
    // BARRA NAVEGADORA
    var estado1 = '';
    var estado2 = '';

    $('#boton1').on('hover', function() {
        //si tiene la clase clara, la quita y pone una oscura

        if (estado1 != '') {

            if (estado1 == 'claro') {
                $(this).removeClass('oscuro');
                $(this).addClass('claro');

                $('#intermedio').removeClass('intOscOsc');
                $('#intermedio').addClass('intClaOsc');
            }

            estado1 = '';
        } else {

            if ($(this).hasClass('claro')) {
                estado1 = 'claro';
                $(this).removeClass('claro');
                $(this).addClass('oscuro');

                $('#intermedio').removeClass('intClaOsc');
                $('#intermedio').addClass('intOscOsc');
            } else {
                estado1 = 'oscuro';
            }

        }
    });

    $('#boton2').on('hover', function() {
        //si tiene la clase clara, la quita y pone una oscura

        if (estado2 != '') {

            if (estado2 == 'claro') {
                $(this).removeClass('oscuro').addClass('claro');
                $('#intermedio').removeClass('intOscOsc').addClass('intOscCla');
            }

            estado2 = '';
        } else {

            if ($(this).hasClass('claro')) {
                estado2 = 'claro';
                $(this).removeClass('claro').addClass('oscuro');
                $('#intermedio').removeClass('intOscCla').addClass('intOscOsc');
            } else {
                estado2 = 'oscuro';
            }

        }
    });

    $('#boton1').on('click', function() {
        if (estado1 == 'claro') {

            $(this).removeClass('claro').addClass('oscuro');
            $('#boton2').removeClass('oscuro').addClass('claro');
            $('#intermedio').removeClass('intOscOsc').addClass('intOscCla');

            estado1 = 'oscuro';

            $('#conten_opcion2').fadeOut('fast');
            $('#conten_opcion1').fadeIn('fast');
        }

    });

    $('#boton2').on('click', function() {
        if (estado2 == 'claro') {

            $(this).removeClass('claro').addClass('oscuro');
            $('#boton1').removeClass('oscuro').addClass('claro');
            $('#intermedio').removeClass('intOscOsc').addClass('intClaOsc');

            estado2 = 'oscuro';

            $('#conten_opcion1').fadeOut('fast');
            $('#conten_opcion2').fadeIn('fast');
        }
    });

    $('.boton_extender').on('click', function(event) {
        event.preventDefault();
        var extender_id = $(this).attr('id');
        var id = obtenerId(extender_id);

        var fecha = $(this).attr('class');
        fecha = fecha.split(' ');
        fecha = fecha[1];
        fecha = fecha.split('_');

        var fechaDesde = fecha[0];
        fechaDesde = fechaDesde.split('/');
        fechaDesde[2] = '20' + fechaDesde[2];
        fechaDesde = fechaDesde.join('-');

        var horaDesde = fecha[1] + ':00';

        //Considerar 30 minutos o 00 minutos
        var tipoHora = fecha[1];
        tipoHora = tipoHora.split(':');
        var minutos = tipoHora[1];
        if (minutos == 00) {
            $('div.time-picker ul li.horaImpar').css('display', 'none');
        }
        if (minutos == 30) {
            $('div.time-picker ul li.horaPar').css('display', 'none');
        }
        //Fin

        var fechaHasta = fecha[2];
        fechaHasta = fechaHasta.split('/');
        fechaHasta[2] = '20' + fechaHasta[2];
        fechaHasta = fechaHasta.join('-');

        var horaHasta = fecha[3] + ':00';

        $('#datefrom_extender').val(fechaDesde);
        $('#hour_from_extender').val(horaDesde);
        $('#dateto_extender').val(fechaHasta);
        $('#hour_to_extender').val(horaHasta);

        $("#extender").dialog({
            resizable: false,
            width: 550,
            modal: false,
            autoOpen: false,
            closeOnEscape: false,
            title: 'Extender reserva',
            position: {my: "center", at: "center"},
            buttons: {
                "Aceptar": function() {
                    //confirmar datos
                    if (confirmarExtension()) {
                        //ingresar datos a la bd
                        ingresarExtension(id);
                        $(this).dialog("close");
                    }
                },
                Cancelar: function() {
                    //limpiar campos de fecha
                    limpiarExtension();
                    $(this).dialog("close");
                }
            }
        });

        $('#extender').dialog('open');

    })


}

function obtenerId(idCompleta) {
    idCompleta = idCompleta.split('_');
    return idCompleta[1];
}

function ocultarBloque(bloqueId) {
    bloqueId = "bloque_" + bloqueId;
    //alert(bloqueId);
    $('#' + bloqueId).fadeOut('fast');
}

function cambiarEstado(id, accion) {
    //accion: preaprobar - rechazar
    $('#bloque_' + id + ' .eventoReserva .der .cargando').show();
    $.ajax({
        type: 'post',
        url: urlCambiarEstadoPedido,
        data: {
            idReserve: id,
            accion: accion
        }
    }).done(function(data) {
        //alert(data)

        $('#bloque_' + id + ' .eventoReserva .der .cargando').hide();
        if (data === 'error:seguro4') {
            alert("Tu auto se encuentra en proceso de verificación para recibir un seguro. Si todavía no has recibido al inspector, contáctate cuanto antes a soporte@arriendas.cl para solicitar su visita.");
        }
        else if (data === 'error:rutdueñonulo') {
            alert("No has ingresado tu RUT. Te redireccionaremos para que puedas continuar con la aprobación");
            setTimeout("redireccion()", 2);
        } else {
            cambiarImgEstado(id, accion);
        }
        ;
        //recarga la página
        if (accion == 'preaprobar') {
            //	window.location.href = urlPedidos;
        }

    }).fail(function() {
        $('#bloque_' + id + ' .eventoReserva .der .cargando').hide();
        alert('Ha ocurrido un error al modificar su pedido, inténtelo nuevamente');
    });
}

function cambiarImgEstado(id, accion) {
    //accion: preaprobar - rechazar

    var url = $('#bloque_' + id + ' .eventoReserva .der .img img').attr('src');
    if (url != '') {
        url = url.split('/');
        var texto = '';
        //alert(url[url.length-1]);
        //IconoEnEspera.png
        //IconoPreAprobado.png
        //IconoRechazado.png

        if (accion == 'preaprobar' || accion.indexOf('oportunidad') !== -1) {
            url[url.length - 1] = 'IconoPreAprobado.png';
            texto = 'Pre aprobado<br>(Falta pago)';
        } else if (accion == 'rechazar') {
            url[url.length - 1] = 'IconoRechazado.png';
            texto = 'Anulado';
        }

        url = url.join('/');
        $('#bloque_' + id + ' .eventoReserva .der .img img').attr('src', url);
        $('#bloque_' + id + ' .eventoReserva .der .texto').html(texto);
        console.log(texto);
        console.log($('#bloque_' + id + ' .eventoReserva .der .texto'));
    }

}

function actualizarBotonEditar() {
    //muestra u oculta editar

    var src = $('#editarEnEsperaRealizados').attr('src');
    src = src.split('/');
    var ultimaPosicion = src[src.length - 1];

    if (comprobarBotonEditar()) {
        src[src.length - 1] = "BotonEditar.png";
    } else {
        src[src.length - 1] = "BotonEditarClaro.png";
    }

    src = src.join('/');
    $('#editarEnEsperaRealizados').attr('src', src);
}

function comprobarBotonEditar() {
    //comprueba que los checkbox seleccionados correspondan a un solo padre
    var comprobarId = null;
    var valido = false;
    $('.checkboxCuerpo').each(function() {
        if ($(this).attr("checked")) {
            var idCompleta = $(this).attr('class');
            idCompleta = idCompleta.split('_');
            var id = idCompleta[1].charAt(0);

            if (!comprobarId) {
                comprobarId = id;
                valido = true;
            } else {
                if (comprobarId == id) {
                    valido = true;
                } else {
                    valido = false;
                }
            }
        }
    });

    return valido;

}

//verifica que al menos se seleccione una opción para ser editada
function comprobarSeleccionEditar() {
    var seleccion = false;

    $('.checkboxCuerpo').each(function() {
        if ($(this).attr("checked")) {
            seleccion = true;
        }
    });

    return seleccion;
}

//verifica que se haya seleccionado un bloque completo
function seleccionoBloqueCompleto() {
    var seleccion = false;

    $('.checkboxCabecera').each(function() {
        if ($(this).attr("checked")) {
            seleccion = true;
        }
    });

    return seleccion;
}

//selecciona el bloque completo de un id cabecera
function seleccionarBloqueCompleto(idPadre) {
    $('.checkboxCuerpo').each(function() {
        if (!$(this).attr("checked")) {

            var idCompleta = $(this).attr('class');
            idCompleta = idCompleta.split('_');
            var id = idCompleta[1].charAt(0);

            if (id == idPadre) {
                $('.cuerpo_' + id).attr('checked', true);
            }

        }
    });
}

function editarTodos() {

    $('.checkboxCuerpo').each(function() {
        if ($(this).attr("checked")) {
            var idCompleta = $(this).attr('class');
            idCompleta = idCompleta.split('_');
            var id = idCompleta[1].charAt(0);

            $('#cabecera_' + id).attr('checked', true);
            seleccionarBloqueCompleto(id);
        }
    });

}

//obtiene el id del padre a partir del id de un hijo
function obtenerIdPadre(id) {

    var idCompleta = $('#checkbox_' + id).attr('class');
    idCompleta = idCompleta.split('_');
    var idPadre = idCompleta[1].charAt(0);

    return idPadre;
}

$.datepicker.regional['es'] = {
    closeText: 'Cerrar',
    prevText: '&#x3c;Ant',
    nextText: 'Sig&#x3e;',
    currentText: 'Hoy',
    monthNames: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio',
        'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
    monthNamesShort: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun',
        'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
    dayNames: ['Domingo', 'Lunes', 'Martes', 'Mi&eacute;rcoles', 'Jueves', 'Viernes', 'S&aacute;bado'],
    dayNamesShort: ['Dom', 'Lun', 'Mar', 'Mi&eacute;', 'Juv', 'Vie', 'S&aacute;b'],
    dayNamesMin: ['Do', 'Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'S&aacute;'],
    weekHeader: 'Sm',
    dateFormat: 'yy-mm-dd',
    firstDay: 1,
    isRTL: false,
    showMonthAfterYear: false,
    yearSuffix: ''
};

$.datepicker.setDefaults($.datepicker.regional['es']);

//$.timepicker.regional['es'] = {
//	timeOnlyTitle: 'Seleccione la hora',
//	timeText: 'Tiempo',
//	hourText: 'Hora',
//	minuteText: 'Minutos',
//	secondText: 'Segundos',
//	millisecText: 'Milisegundos',
//	currentText: 'Hoy',
//	closeText: 'Cerrar',
//	timeFormat: 'hh:mm:ss',
//	ampm: false
//};

//$.timepicker.setDefaults($.timepicker.regional['es']);

//ingresa valores de fecha y hora al dialog
function cargarFecha(fechas) {
    fechas = fechas.split(' ');

    var fechaDesde = fechas[0];
    fechaDesde = fechaDesde.split('/');
    fechaDesde[2] = '20' + fechaDesde[2];
    fechaDesde = fechaDesde.join('-');

    var horaDesde = fechas[1];
    horaDesde = horaDesde.split('/');
    horaDesde = horaDesde.join('-');

    var fechaHasta = fechas[3];
    fechaHasta = fechaHasta.split('/');
    fechaHasta[2] = '20' + fechaHasta[2];
    fechaHasta = fechaHasta.join('-');

    var horaHasta = fechas[4];
    horaHasta = horaHasta.split('/');
    horaHasta = horaHasta.join('-');

    $('#datefrom').val(fechaDesde);
    $('#hour_from').val(horaDesde);
    $('#dateto').val(fechaHasta);
    $('#hour_to').val(horaHasta);
}

function confirmarFecha() {
    //todos los campos ingresados
    var fechaDesde = $('#datefrom').val();
    var horaDesde = $('#hour_from').val();
    var fechaHasta = $('#dateto').val();
    var horaHasta = $('#hour_to').val();

    if (fechaDesde == '') {
        alert('ingresar fecha');
        return false;
    }
    if (horaDesde == '') {
        alert('ingresar fecha');
        return false;
    }
    if (fechaHasta == '') {
        alert('ingresar fecha');
        return false;
    }
    if (horaHasta == '') {
        alert('ingresar fecha');
        return false;
    }

    //fecha y hora hasta mayor que desde
    var fechaCompletaDesde = obtenerFechaString(fechaDesde, horaDesde);
    var fechaCompletaHasta = obtenerFechaString(fechaHasta, horaHasta);

    //alert(fechaCompletaDesde);
    //alert(fechaCompletaHasta);

    if (fechaCompletaHasta <= fechaCompletaDesde) {
        alert('Debe ingresar una fecha de entrega superior a la de pedido');
        return false;
    }

    return true;
}

//obtiene un string con la fecha: año|mes|dia|hora|minuto
function obtenerFechaString(fecha, hora) {
    fecha = fecha.split('-');
    var anio = fecha[2];
    var mes = fecha[1];
    var dia = fecha[0];
    hora = hora.split(':');
    var horas = hora[0];
    var minutos = hora[1];

    return anio + mes + dia + horas + minutos;
}

function limpiarFecha() {
    var fechaDesde = $('#datefrom').val('');
    var horaDesde = $('#hour_from').val('');
    var fechaHasta = $('#dateto').val('');
    var horaHasta = $('#hour_to').val('');
}

function ingresarNuevasFechas() {
    var fechaDesde = $('#datefrom').val();
    var horaDesde = $('#hour_from').val();
    var fechaHasta = $('#dateto').val();
    var horaHasta = $('#hour_to').val();

    var total = 0;
    var modificados = 0;

    $('.checkboxCuerpo').each(function() {
        if ($(this).is(':checked')) {
            total++;
        }
    });

    $('.checkboxCuerpo').each(function() {
        if ($(this).is(':checked')) {
            //alert('editar '+$(this).attr('id'));
            var id = $(this).attr('id');
            id = obtenerId(id);

            $('#bloque_' + id + ' .eventoReserva .der .cargando').show();
            $.ajax({
                type: 'post',
                url: urlEditarFecha,
                data: {
                    id: id,
                    fechaDesde: fechaDesde,
                    horaDesde: horaDesde,
                    fechaHasta: fechaHasta,
                    horaHasta: horaHasta
                }
            }).done(function() {
                $('#bloque_' + id + ' .eventoReserva .der .cargando').hide();
                modificados++;

                if (total == modificados) {
                    //	window.location.href = urlPedidos;
                }

            }).fail(function() {
                $('#bloque_' + id + ' .eventoReserva .der .cargando').hide();
                alert('Ha ocurrido un error al modificar las fechas, inténtelo nuevamente');
            });

        }

    });

}

//verifica que estén checkeados los contratos
function contratosSeleccionados(tipo) {

    if (!$('#contrato' + tipo + '1').is(":checked")) {
        $('#contrato' + tipo + '1').focus();
        return false;
    }

    if (!$('#contrato' + tipo + '2').is(":checked")) {
        $('#contrato' + tipo + '2').focus();
        return false;
    }

    if (!$('#contrato' + tipo + '3').is(":checked")) {
        $('#contrato' + tipo + '3').focus();
        return false;
    }

    return true;
}

//quita el checked a los contratos
function limpiarContratos(tipo) {
    $('#contrato' + tipo + '1').removeAttr("checked");
    $('#contrato' + tipo + '2').removeAttr("checked");
    $('#contrato' + tipo + '3').removeAttr("checked");
}

function limpiarExtension() {
    $('#datefrom_extender').val('');
    $('#hour_from_extender').val('');
    $('#dateto_extender').val('');
    $('#hour_to_extender').val('');
}

function confirmarExtension() {
    //todos los campos ingresados
    var fechaDesde = $('#datefrom_extender').val();
    var horaDesde = $('#hour_from_extender').val();
    var fechaHasta = $('#dateto_extender').val();
    var horaHasta = $('#hour_to_extender').val();

    if (fechaDesde == '') {
        alert('ingresar fecha');
        return false;
    }
    if (horaDesde == '') {
        alert('ingresar fecha');
        return false;
    }
    if (fechaHasta == '') {
        alert('ingresar fecha');
        return false;
    }
    if (horaHasta == '') {
        alert('ingresar fecha');
        return false;
    }

    //fecha y hora hasta mayor que desde
    var fechaCompletaDesde = obtenerFechaString(fechaDesde, horaDesde);
    var fechaCompletaHasta = obtenerFechaString(fechaHasta, horaHasta);

    if (fechaCompletaHasta <= fechaCompletaDesde) {
        alert('Debe ingresar una fecha de entrega superior a la de pedido');
        return false;
    }

    return true;
}

function ingresarExtension(id) {

    var fechaHasta = $('#dateto_extender').val();
    var horaHasta = $('#hour_to_extender').val();

    $.ajax({
        type: 'post',
        url: urlExtenderReserva,
        data: {
            id: id,
            fechaHasta: fechaHasta,
            horaHasta: horaHasta
        }
    }).success(function() {
        $.ajax({
            type: 'get',
            url: urlRefreshPedidos,
            success: function(data){
                $("#pedidos").html(data);
                bindEventsAll();
            }
        });
    }).fail(function() {
        alert('Ha ocurrido un error al extender la reserva, inténtelo nuevamente');
    });
}

function buildCarSelect(reserveId){
        $("#carsSelect").empty();
        $.ajax({
            "url": urlCars+"?reserve_id="+reserveId,
            "success":function(carsData){
                for(var i=0; i<carsData.length; i++){
                    var car = carsData[i];
                    var optionTmpl = "<option name='"+car.id+"'>"+car.brand+" "+car.model+", patente: "+car.patente+"</option>";
                    $("#carsSelect").append(optionTmpl);
                }
            },
        });
        
    }

function masDeUnDia() {

}

$(document).on('ready', function() {
    bindEventsAll();
});

function nuevoFlujoCambiar(idReserve) {

    $.ajax({
        type: 'post',
        url: urlNuevoFlujoCambiar,
        data: {idReserve: idReserve}
    }).done(function(data) {

        if(data.error) {
            alert('Disculpe, ha ocurrido un error. Por favor inténtelo nuevamente mas tarde.');
        } else {
            // do something
        }
    }).fail(function() {
        alert('Disculpe, ha ocurrido un error. Por favor inténtelo nuevamente mas tarde.');
    });
}

$('.nuevo_flujo_cambiar').on('click', function(event) {

    var idReserve = $(this).data('reserveid');

    $("#nuevo_flujo_cambiar_dialog").dialog({
        resizable: false,
        width: 550,
        modal: false,
        autoOpen: false,
        closeOnEscape: false,
        title: 'Cambiar auto',
        position: {my: "center", at: "center"},
        buttons: {
            "Aceptar": function() {
                nuevoFlujoCambiar(idReserve);
                $(this).dialog("close");
            },
            Cancelar: function() {
                $(this).dialog("close");
            }
        }
    });
});
