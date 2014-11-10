
<?php use_stylesheet('subi_tu_auto.css') ?>
<?php use_stylesheet('registro.css') ?>
<?php use_stylesheet('calendario.css') ?>
<?php use_stylesheet('jquery.lightbox-0.5.css') ?>
<?php use_javascript('jquery.lightbox-0.5.js') ?>
<?php use_javascript('jquery.lightbox-0.5.min.js') ?>
<?php use_javascript('jquery.lightbox-0.5.pack.js') ?>
<?php use_stylesheet('mis_arriendos.css') ?>


<script type="text/javascript">
    $(function() {
        $('#gallery a').lightBox();
        $('#galleryDanios a').lightBox();
    }); 
</script>


<style type="text/css">

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
        /*border-bottom: 1px solid #00AEEF;
        padding-bottom: 5px;*/
        font-size: 21px;
        font-weight: bold;
        color: #00AEEF; /* celeste */
        width: 100%;
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

    #imagenPerfil{
        padding-top: 6px;
        padding-left: 6px;
        width: 193px;
        height: 192px;
        float: left;
        margin-left: 20px;

        -webkit-box-shadow: 0px 0px 9px rgba(50, 50, 50, 0.32);
        -moz-box-shadow:    0px 0px 9px rgba(50, 50, 50, 0.32);
        box-shadow:         0px 0px 9px rgba(50, 50, 50, 0.32);
    }
    .infoAuto{
        border-top: 1px solid rgb(227, 228, 223);
    }
</style>

<style>
    input[type=text], input[type=password]{
        border: 1px solid #CECECE;
        background-color: white;
        width: 256px;
        height: 30px;
        -webkit-border-radius: 10px;
        -moz-border-radius: 10px;
        border-radius: 10px;
        padding-left: 10px;
    }
    .c1 label { 
        display:table; 
        width:100%;
        margin-bottom:10px
    }

    .c1 {
        margin:20px; 
        margin-top:10px;
        width: 450px;

    }
    #centralcontent {
        margin:0px;
        margin-top:80px;
        display:block;
        width:100%;
    }

    #centralcontent h3 {
        margin-top:20px;
        margin-bottom:10px;
    }
    #linkmain {
        /*position: absolute;*/
        display:table;
        padding-top:10px;
        bottom: 20px;
        left: 54px;
    }
    .btnAmarillo{
        cursor: pointer;
        padding: 1px;
        margin-right: 80px;
    }
    .mensaje_alerta {
        background: none repeat scroll 0 0 #F8F8F8;
        border: 1px solid #CCCCCC;
        color: #FF0000;
        display: block;
        height: auto;
        line-height: 30px;
        margin-left: 20px;
        padding: 9px;
        width: 360px;
    }

    /* css for timepicker */
    .ui-timepicker-div .ui-widget-header { margin-bottom: 8px; }
    .ui-timepicker-div dl { text-align: left; }
    .ui-timepicker-div dl dt { height: 25px; margin-bottom: -25px; }
    .ui-timepicker-div dl dd { margin: 0 10px 10px 65px; }
    .ui-timepicker-div td { font-size: 90%; }
    .ui-tpicker-grid-label { background: none; border: none; margin: 0; padding: 0; }
    .ui-widget { font-family: Lucida Grande, Lucida Sans, Arial, sans-serif; font-size: 0.8em; }
</style>

<style>
    /* Estilos varios */
    .btn {
        background-color: #ec008c;
        border: solid 2px white;
        border-radius: 8px;
        color: white;
        cursor: pointer;
        font-size: 14px;
        font-weight: normal;
        letter-spacing: 2px;
        padding: 8px 16px;
        text-decoration: none;
        text-transform: uppercase;
    }

    .btn:hover {
        background-color: #da007a;
    }

    .btn[disabled] {
        background-color: gray;
        cursor: auto;
    }

    .btn-link {
        background-color: transparent;
        border: solid 2px transparent;
        border-radius: 8px;
        color: blue;
        cursor: pointer;
        font-size: 12px;
        font-weight: normal;
        padding: 8px 16px;
        text-decoration: underline;
    }

    .btn-link:hover {
        color: #6633ff;
    }

    .btnbtn {
        background-image: url('/images/Home/BotonReservarHome.png');
        height: 38px;
        text-decoration: none;
        width: 111px;
    }
</style>

<script>
$(document).ready(function() {

    //GUARDANDO EL FORMULARIO
    $("form#frm1").submit(function() {
        
        //valido que reserva sea de mínimo 1 hora
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
    });

    $('#payBtn').click(function() {

        durationCheck();

        if ($(this).hasClass('oldFlow')) {
            $("form#frm1").submit();
        } else {
            $('.botonPagar').click();
        }
    });
    
    $.datepicker.regional['es'] = {
        closeText: 'Cerrar',
        prevText: '&#x3c;Ant',
        nextText: 'Sig&#x3e;',
        currentText: 'Hoy',
        monthNames: ['Enero','Febrero','Marzo','Abril','Mayo','Junio',
            'Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'],
        monthNamesShort: ['Ene','Feb','Mar','Abr','May','Jun',
            'Jul','Ago','Sep','Oct','Nov','Dic'],
        dayNames: ['Domingo','Lunes','Martes','Mi&eacute;rcoles','Jueves','Viernes','S&aacute;bado'],
        dayNamesShort: ['Dom','Lun','Mar','Mi&eacute;','Juv','Vie','S&aacute;b'],
        dayNamesMin: ['Do','Lu','Ma','Mi','Ju','Vi','S&aacute;'],
        weekHeader: 'Sm',
        dateFormat: 'yy-mm-dd',
        firstDay: 1,
        isRTL: false,
        showMonthAfterYear: false,
        yearSuffix: ''};
    $.datepicker.setDefaults($.datepicker.regional['es']);

    $('#datefrom,#dateto').datepicker({
        dateFormat: 'dd-mm-yy',
        buttonImageOnly: true,
        minDate:'-0d'
    });
    
    $("#hour_from , #hour_to").timePicker({show24Hours:false});

    calcularPrecio();
    $('#datefrom').click(function(){

        $('div.time-picker').css('display', 'none');
    });

    $('#datefrom').on('change',function(){

        /*$('#dateto').attr("value", $('#datefrom').val());*/
        calcularPrecio();
    });

    $('#hour_from').click(function(){

        $('div.time-picker ul li.horaImpar').css('display', 'block');
        $('div.time-picker ul li.horaPar').css('display', 'block');
    });

    $('#hour_from').on('change',function(){

        var hora = $("#hour_from").timePicker('getTime').val();
        hora = hora.split(':');
        var tiempoHora = (parseInt(hora[0])+parseInt(1));
            if(parseInt(tiempoHora)==parseInt(24)){
            tiempoHora = "00";
            var fecha = $("#datefrom").timePicker('getTime').val();//obteniendo la fecha_from
            fecha = fecha.split('-');//sacandole los '-'
            mi_fecha = new Date(fecha[2],fecha[1]-1,fecha[0]);//creando un nuevo Date con los datos de la fecha obtenida de fecha_from
            mi_fecha.setDate(mi_fecha.getDate()+parseInt(1));//sumandole un dia a la fecha
            dia = obtenerNumeroCorrecto(mi_fecha.getDate());//obteniendo los dias
            mes = obtenerNumeroCorrecto(mi_fecha.getMonth()+parseInt(1));//obteniendo los meses(siempre se les suma 1)
            anio = mi_fecha.getFullYear();//obteniendo los años
            var nuevaFecha = dia+"-"+mes+"-"+anio;//creando un string de la nueva fecha
            $('#dateto').attr("value", nuevaFecha);//mostrar la nueva fecha en fecha_to
        }
        var tiempoTo = tiempoHora+":"+hora[1];//+":"+hora[2];
        $('#hour_to').attr("value", tiempoTo);

        calcularPrecio();
    });

    $('#dateto').on('change',function(){

        calcularPrecio();
    });

    $('#hour_to').click(function(){
        var hora_inicial= convertAmPmto24($("#hour_from").timePicker('getTime').val());
        hora_inicial = hora_inicial.split(':');
        var tipoMinutos = hora_inicial[1];
        if(tipoMinutos==00){
            $('div.time-picker ul li.horaImpar').css('display', 'none');
        }
        if(tipoMinutos==30){
            $('div.time-picker ul li.horaPar').css('display', 'none');
        }
    });

    $('#hour_to').on('change',function(){

        calcularPrecio();
    });

});

function tConvert (time) {
  // Check correct time format and split into components
  time = time.toString ().match (/^([01]\d|2[0-3])(:)([0-5]\d)(:[0-5]\d)?$/) || [time];

  if (time.length > 1) { // If time format correct
    time = time.slice (1);  // Remove full string match value
    time[5] = +time[0] < 12 ? 'AM' : 'PM'; // Set AM/PM
    time[0] = +time[0] % 12 || 12; // Adjust hours
  }
  return time.join (''); // return adjusted time or original string
}

//FUNCIONES JAVASCRIPT
function obtenerNumeroCorrecto(numero){
    var numeroNuevo = "";
    cantidad = ((numero).toString()).length;
    if(cantidad == 1){
        numeroNuevo = "0" + numero;
    }else{
        numeroNuevo = numero;
    }
    return numeroNuevo;
}

function convertAmPmto24(time) {

//console.log(time);
//var time = $("#starttime").val();
var hours = Number(time.match(/^(\d+)/)[1]);
var minutes = Number(time.match(/:(\d+)/)[1]);
var AMPM = time.match(/\s(.*)$/)[1];
if(AMPM == "PM" && hours<12) hours = hours+12;
if(AMPM == "AM" && hours==12) hours = hours-12;
var sHours = hours.toString();
var sMinutes = minutes.toString();
if(hours<10) sHours = "0" + sHours;
if(minutes<10) sMinutes = "0" + sMinutes;

return sHours + ":" + sMinutes;

}

function anadirPunto(numero){ // v2007-08-06

var decimales=0;
var separador_decimal=',';
var separador_miles='.';

    numero=parseFloat(numero);
    if(isNaN(numero)){
        return "";
    }

    if(decimales!==undefined){
        // Redondeamos
        numero=numero.toFixed(decimales);
    }

    // Convertimos el punto en separador_decimal
    numero=numero.toString().replace(".", separador_decimal!==undefined ? separador_decimal : ",");

    if(separador_miles){
        // Añadimos los separadores de miles
        var miles=new RegExp("(-?[0-9]+)([0-9]{3})");
        while(miles.test(numero)) {
            numero=numero.replace(miles, "$1" + separador_miles + "$2");
        }
    }

    return numero;
}

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

        /*
        var inicio= fecha_inicial.getTime()+ (parseInt(hora_inicial.substring(0,2)) * 60 * 60) + (parseInt(hora_inicial.substring(3,5)) * 60);
        var fin= fecha_final.getTime()+ parseInt(hora_final.substring(0,2)) * 60 *60 + parseInt(hora_final.substring(3,5)) * 60;
        var horas= (Math.ceil((fin - inicio)/(60*60)));
        alert(horas);
        */
        /*
        if(horas>0){
            if (horas <= 6) {
                //Calculo de la tarifa por horas
                var tarifa=horas*<?php echo $car->getPricePerHour(); ?>;
                $("#valor_reserva").text('$'+tarifa);
            } else {
                //Calculo de la tarifa por dias
                var dias= (Math.ceil(horas/24000));
                $("#valor_reserva").text('$'+dias*<?php echo $car->getPricePerDay() ; ?>);
            }
        }else{
            $("#valor_reserva").text('$0');
        }
        */

    }
};

//Valida formato fecha
function isValidDate(date){
  if (date.match(/^(?:(0[1-9]|[12][0-9]|3[01])[\- \/.](0[1-9]|1[012])[\- \/.](19|20)[0-9]{2})$/)){
    return true;
  }else{
    return false;
  }
}

function isValidTime(time){
    var objRegExp  = /(^\d{2}:\d{2}$)/;
    
    if(time.match(objRegExp)) {
      return true;
    }else{
      return false;
    }
}

function restarHoras(hora_desde, hora_hasta) {
    
    var hora, min, seg;
    var hora2, min2, seg2;
    var arr = hora_desde.split(':');
    var arr2 = hora_hasta.split(':');
    hora = parseInt(arr[0]); min = parseInt(arr[1]); seg = parseInt(arr[2]);
    hora2 = parseInt(arr2[0]); min2 = parseInt(arr2[1]); seg2 = parseInt(arr2[2]);
    var aux = (hora * 3600) + (min * 60); var aux2 = (hora2 * 3600) + (min2 * 60);
    var dif = parseFloat( (aux2 - aux) / 3600 );
    
    return dif; 
}

function durationCheck() {
    var d = new Date();

    var dd = d.getDate();
    var mm = d.getMonth()+1;
    var yy = d.getFullYear();

    if (dd<10) { dd='0'+dd }
    if (mm<10) { mm='0'+mm }

    var today = dd+"-"+mm+"-"+yy;

    if ($("#datefrom").val() == today) {
        $("#newFlow").val(0);
        $("#payBtn").addClass("oldFlow");
    } else {
        $("#newFlow").val(1);
        $("#payBtn").removeClass("oldFlow");
    }
}
</script>

<?php use_javascript('pedidos.js?v=29072014') ?>

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
</script>

<div style="display:none">
    <?php include_partial('contratosArrendatario') ?>
</div>

<div class="main_box_1">
    <div class="main_box_2">
    
        <?php include_component('profile', 'profile') ?>
            
            <!-- 
            <div class="usuario_info">
                <span >Usuario:</span><span class="usuario_nombre"><?php echo $user->getFirstname() . " " . $user->getLastname(); ?></span>
                <span class="usuario_lugar"><?php echo $user->getNombreComuna() ?>, <?php echo $user->getNombreRegion() ?></span>    
            </div>

            <a href="#<?php echo url_for('profile/profile') ?>" class="usuario_btn_verperfil"></a>
            -->


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
                                            <!--
                                <input id="dateto" name="dateto" type="text" class="datepicker" readonly="readonly" value="Día de entrega"/><br/><br/>
                                            <input readonly="readonly" type="text" id="hour_to" name="hour_to" value="Hora de entrega" />
                                 -->
                         </div><!-- /c1 -->
                        
                        <!--
                        <div class="c1 height"><label><input type="checkbox" name="chkcontrato" id="chkcontrato" value="1" style="width: auto; height: auto;"> Declaro estar de acuerdo con los terminos del <a href="contratopdf" id="generatepdf">Contrato</a> asociado a los servicios de Arriendas.cl.</label></div>
                        -->
                            
                        <input type="hidden" name="duration" id="duration"/>


                        <input type="hidden" name="id" id="id" value="<?php echo  $car->getId() ?>"/>
                        <?php if(count($reserve) > 0) :?>
                            <?php foreach ($reserve as $r): ?>                        
                                <input type="hidden" name="reserve_id" id="reserve_id" value="<?php echo $r['id']?>"/>
                            <?php endforeach; ?>
                        <?php endif;?>

                        <div class="clear"></div>

                        <input type="hidden" name="newFlow" id="newFlow" value="0" />

                        <!-- <h3>Opciones de reemplazo</h3>
                        <p><input type="checkbox"> Auto de mayor precio manteniendo tu precio pagado.</p>
                        <p><input type="checkbox"> Auto de las mismas características (te devolvemos el dinero a tu favor, si lo hubiera).</p>
                        <p><input type="checkbox"> Arriendas conseguirá un auto muy cerca de tu reserva original.</p>
                        <p><input type="checkbox"> A menos de 1 km del metro.</p> -->

                        <div style="max-width: 85%; text-align: right">
                            <button class="arriendas_pink_btn arriendas_big_btn right" id="payBtn" style="position: initial" type="button">Reservar</button>
                            <input class="botonPagar" data-carid="<?php echo $car->getId() ?>" data-userid="<?php echo $idUsuario ?>" style="display:none">
                            <br>
                            <input type="submit" value="Consultar" class="btn-link">
                        </div>

                        </form>  

                    </div>
                </div>
            </div>

            <!-- información del auto -->
            <div class="barraSuperior infoAuto" style='float:left; width:100%; margin-top:30px;'>
                <p>INFORMACIÓN DEL AUTO EN ARRIENDO</p>
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
<script type="text/javascript" src="//www.googleadservices.com/pagead/conversion.js">
</script>
<noscript>
<div style="display:inline;">
<img height="1" width="1" style="border-style:none;" alt="" src="//www.googleadservices.com/pagead/conversion/996876210/?value=22&amp;label=hDs_CM7itwQQsr-s2wM&amp;guid=ON&amp;script=0"/>
</div>
</noscript>

    
    <div class="clear"></div>
</div><!-- main_box_1 -->



