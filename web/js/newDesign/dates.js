function initializeDate(elem, date, withTimePicker, withHumanFormatExtended) {

    var format = getHumanFormat(date);
    if (withHumanFormatExtended) {
        format = getHumanFormatExtended(date);
    }

    $("#"+elem+"H").datetimepicker({ 
        dayOfWeekStart: 1,
        lang: 'es',
        minDate: new Date(),
        timepicker: withTimePicker,
        validateOnBlur: false,
        value: format,
        allowTimes:[
            "00:00", "00:30", "01:00", "01:30", "02:00", "02:30",
            "03:00", "03:30", "04:00", "04:30", "05:00", "05:30",
            "06:00", "06:30", "07:00", "07:30", "08:00", "08:30",
            "09:00", "09:30", "10:00", "10:30", "11:00", "11:30",
            "12:00", "12:30", "13:00", "13:30", "14:00", "14:30",
            "15:00", "15:30", "16:00", "16:30", "17:00", "17:30",
            "18:00", "18:30", "19:00", "19:30", "20:00", "20:30",
            "21:00", "21:30", "22:00", "22:30", "23:00", "23:30",
        ],
        onSelectDate: function() {
            refresh(elem, withHumanFormatExtended);
        },
        onSelectTime: function() {
            refresh(elem, withHumanFormatExtended);
        }
    });

    $("#"+elem).val(getFormat(date));
}

function getFormat(date) {

    var day = date.getDate();
    if (day < 10) {
        day = "0"+day;
    }

    var month = date.getMonth()+1;
    if (month < 10) {
        month = "0"+month;
    }

    return date.getFullYear()+"-"+month+"-"+day+" "+roundTime(date.getHours()+":"+date.getMinutes())+":00";
}

function getHumanFormat(date) {

    var day = date.getDate();
    if (day < 10) {
        day = "0"+day;
    }

    var month = date.getMonth()+1;
    if (month < 10) {
        month = "0"+month;
    }

    return date.getFullYear()+"-"+month+"-"+day+" "+roundTime(date.getHours()+":"+date.getMinutes());
    return day+"-"+month+"-"+date.getFullYear()+" "+roundTime(date.getHours()+":"+date.getMinutes());
}

function getHumanFormatExtended(date) {

    var d_names = new Array("Domingo", "Lunes", "Martes", "Miércoles",
        "Jueves", "Viernes", "Sábado");

    var m_names = new Array("Enero", "Febrero", "Marzo", 
        "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", 
        "Octubre", "Noviembre", "Diciembre");

    var dayOfWeek = d_names[date.getDay()];
    var day       = date.getDate();
    var month     = m_names[date.getMonth()];
    var year      = date.getFullYear();
    var time      = roundTime(date.getHours()+":"+date.getMinutes());

    return dayOfWeek+" "+day+" de "+month+" de "+year+" a las "+time;
}

function refresh(elem, withHumanFormatExtended) {

    var date = new Date($("#"+elem+"H").val());

    // Si es menos de 1 dia desde NOW se deja la misma fecha
    dateNow = new Date();
    dateNow.setDate(dateNow.getDate()+1);
    if (elem == "to" && date.getTime() < dateNow.getTime()) {
        date = new Date($("#"+elem).val());
    }

    // Se actualizan los valores
    if (withHumanFormatExtended) {
        $("#"+elem+"H").val(getHumanFormatExtended(date));
    } else {
        $("#"+elem+"H").val(getHumanFormat(date));
    }
    $("#"+elem).val(getFormat(date));

    // Revisamos que la fecha desde no sea mayor a la fecha hasta
    var dateFrom = new Date($("#from").val());
    var dateTo = new Date($("#to").val());

    if (dateTo.getTime() == dateFrom.getTime() || dateFrom.getTime() > dateTo.getTime()) {
        if (elem == "from") {
            dateTo = dateFrom;
            dateTo.setDate(dateFrom.getDate()+1);
            initializeDate("to", dateTo, true, withHumanFormatExtended);
        } else {
            dateFrom = dateTo;
            dateFrom.setDate(dateTo.getDate()-1);
            initializeDate("from", dateFrom, true, withHumanFormatExtended);
        }
    }

    afterDateRefresh();
}

function roundTime(time){

    var timeSplit = time.split(":");

    var h = parseInt(timeSplit[0]);
    var m = parseInt(timeSplit[1]);

    if (m > 14 && m < 45){
        m = "30";
    } else if (m > 45){
        m = "00";
        h = (h+1).toString();
    } else {
        m = "00";
    }

    if (h < 10) {
        h = "0"+h;
    }

    return h+":"+m;
}
