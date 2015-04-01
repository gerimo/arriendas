function initializeDate(elem, date, withTimePicker, withHumanFormatExtended) {

    var format = "d-m-Y H:i";
    var value  = getHumanFormat(date);

    if (withHumanFormatExtended) {
        value  = getHumanFormatExtended(date);
    }

    if (elem == "from") {
        $("#fromH").datetimepicker({ 
            dayOfWeekStart: 1,
            lang: 'es',
            timepicker: withTimePicker,
            validateOnBlur: false,
            value: value,
            format: format,
            minDate: new Date(),
            maxDate: false,
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
            },
            onShow: function(){
                /*this.setOptions({
                    maxDate: $('#to').val() ? $('#to').val().split(" ")[0] : false
                });*/
            }
        });
    } else {
        $("#toH").datetimepicker({ 
            dayOfWeekStart: 1,
            lang: 'es',
            timepicker: withTimePicker,
            validateOnBlur: false,
            value: value,
            format: format,
            minDate: $('#from').val().split(" ")[0],
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
            },
            onShow: function(){
                this.setOptions({
                    minDate: $('#from').val() ? $('#from').val().split(" ")[0] : false
                });
            },
        });
    }

    $("#"+elem).val(getFormat(date));
}

function chileanFormat2Date(CF) {

    s = CF.split(" ");
    d = s[0].split("-");
    t = s[1].split(":");

    return new Date(d[2], d[1], d[0], roundTime(t[0]+":"+t[1]).split(":")[0], roundTime(t[0]+":"+t[1]).split(":")[1], 0, 0);
}

function getDayFormat(day) {

    if (day < 10) {
        day = "0"+day;
    }

    return day;
}

function getMonthFormat(month) {

    month = month + 1;
    if (month < 10) {
        month = "0"+month;
    }

    return month;
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

    return date.getFullYear()+"/"+month+"/"+day+" "+roundTime(date.getHours()+":"+date.getMinutes())+":00";
}

function getHumanFormat(date) {

    var day = getDayFormat(date.getDate());
    var month = getMonthFormat(date.getMonth());

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
    
    var date = chileanFormat2Date($("#"+elem+"H").val());

    if (withHumanFormatExtended) {
        value = getHumanFormatExtended(date);
    } else {
        value = getHumanFormat(date);
    }

    $("#"+elem+"H").val(value);
    $("#"+elem).val(getFormat(date));

    if (elem == "from") {
        
        date.setDate(date.getDate()+1);
        to = new Date($("#to").val());

        if (date >= to) {

            if (withHumanFormatExtended) {
                value = getHumanFormatExtended(date);
            } else {
                value = getHumanFormat(date);
            }

            $("#toH").val(value);
            $("#to").val(getFormat(date));
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
