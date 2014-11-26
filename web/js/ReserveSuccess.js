
$(function() {
    $('#gallery a').lightBox();
    $('#galleryDanios a').lightBox();
});

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

    if ($("#newFlow").val() == 1) {
        if ($("#datefrom").val() == today) {
            $("#newFlow").val(0);
            $("#payBtn").addClass("oldFlow");
        } else {
            $("#newFlow").val(1);
            $("#payBtn").removeClass("oldFlow");
        }
    }
}