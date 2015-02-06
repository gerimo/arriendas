<link href="/css/newDesign/cars.css" rel="stylesheet" type="text/css">

<div class="hidden-xs space-100"></div>
<div class="visible-xs space-50"></div>

<div class="row">
    <div class="col-md-offset-2 col-md-8">
        <div class="BCW">
            
            <div class="col-md-offset-8 col-md-4">
                <button class="col-md-12 btn btn-a-primary btn-block" onclick="location.href='<?php echo url_for('cars/create') ?>'">¡Sube un auto!</button>
            </div>

            <h1 style="text-indent: 30px">Mis Autos</h1>
                       
            <div class="row">

                <?php foreach ($cars as $c): ?>
                    <div class="misautos_user_item grey" id="container-car-<?php echo $c->getId() ?>">
                                            
                        <div class="row">
                            <div class="col-offset-2 col-md-3 text-center">
                                <a href="<?php echo url_for('car_edit', array('id' => $c->id)) ?>" >
                                    <?php if ($c->getFotoPerfil() == null): ?>
                                        <?php echo image_tag('img_asegura_tu_auto/AutoVistaAerea.png', array("width"=>"84px","height"=>"84px")) ?>
                                    <?php else: ?>
                                        <?php echo image_tag($c->getFotoPerfil(), array("width"=>"84px","height"=>"84px")) ?>
                                    <?php endif ?>
                                </a>
                            </div>
                            
                            <div class="col-md-4">
                                <h2 class="text-left" style="margin: 0">
                                    <?php echo $c->getModel()->getBrand()->name ?> <?php echo $c->getModel()->name ?>&nbsp;&nbsp;&nbsp;&nbsp;
                                    <small><a href="<?php echo url_for('car_edit', array('id' => $c->id)) ?>"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></a></small>
                                </h2>

                                <div class="row">
                                    <div class="col-xs-2 col-sm-2 col-md-1">
                                        <div class="imgActivo text-center" style="margin-top:12px">
                                            <div class="circuloActivo" style="display: <?php echo ($c->getActivo())?"block":"none" ?>"></div>
                                            <div class="circuloInactivo" style="display: <?php echo (!$c->getActivo())?"block":"none" ?>"></div>
                                        </div>
                                    </div>
                                    <div class="col-xs-10 col-sm-10 col-md-8">
                                        <select class="form-control selectorActivo" data-car-id="<?php echo $c->id ?>">
                                            <option value="1" <?php if ($c->activo) echo "selected" ?>>Activo</option>
                                            <option value="0" <?php if (!$c->activo) echo "selected" ?>>Inactivo</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <?php if ($c->getActivo() == 0): ?>
                                <div class="col-md-3">
                                    <p style="margin-top: 10px">Inactivo Hasta:</p>
                                    <input class="disabled-until form-control" data-car-id="<?php echo $c->id ?>" type="text" value="<?php echo $c->getDisabledUntil() ? date('d-m-Y',strtotime($c->getDisabledUntil())): "";?>">
                                </div>
                            <?php endif ?>
                        </div>

                        <?php if ($c->getActivo() == 1): ?>
                            <div class="visible-xs space-10"></div>
                            
                            <?php if (isset($availabilityOfCars[$c->getId()])): ?>
                                <?php error_log($availabilityOfCars[$c->getId()]) ?>
                                <div class="row">
                                    <div class="col-md-offset-1 col-md-11">

                                        <h3 class="text-left">Horario para recibir un cliente los siguientes días:</h3>

                                        <?php foreach ($availabilityOfCars[$c->getId()] as $AOC): ?>
                                            <h4><?php echo $AOC["dayName"] ." ". date("j", strtotime($AOC["day"])) ?></h4>
                                            <div class="row" data-car-id="<?php echo $c->getId() ?>" data-day="<?php echo $AOC['day'] ?>">
                                                <div class="col-md-3">
                                                    <p>Desde:</p>
                                                    <input class="from datetimepicker form-control" type="text" <?php if (isset($AOC['from'])) echo "value='".date("H:i", strtotime($AOC['from']))."'"; ?>>
                                                </div>
                                                <div class="col-md-3">
                                                    <p>Hasta:</p>
                                                    <input class="to datetimepicker form-control" type="text" <?php if (isset($AOC['to'])) echo "value='".date("H:i", strtotime($AOC['to']))."'"; ?>>
                                                </div>
                                                <div class="col-md-6">
                                                    <p>&nbsp;</p>
                                                    <div class="row">
                                                        <div class="col-xs-6 col-sm-6 col-md-6">
                                                            <button class="btn-disponibility-remove btn btn-block">Eliminar</button>
                                                        </div>
                                                        <div class="col-xs-6 col-sm-6 col-md-6">
                                                            <button class="btn-disponibility-save btn btn-primary btn-block">Guardar</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endforeach ?>
                                    </div>
                                </div>
                            <?php endif ?>

                            <div class="row">
                                <div class="col-md-offset-1 col-md-11">
                                    <h3 class="text-left">¿Qué días de la semana puedes recibir clientes?</h3>

                                    <div class="radio">
                                        <label>
                                            <input class="car-option" data-car-id="<?php echo $c->id ?>" name="option1" type="checkbox" value="1" <?php if ($c->getOptions() & 1) echo 'checked' ?>>
                                            Puedo recibir clientes en la semana.
                                        </label>
                                    </div>
                                    <div class="radio">
                                        <label>
                                            <input class="car-option" data-car-id="<?php echo $c->id ?>" name="option2" type="checkbox" value="2" <?php if($c->getOptions() & 2) echo 'checked' ?>>
                                             Puedo recibir clientes los fines de semana.   
                                        </label>
                                    </div> 

                                    <p>Si tienes disponibilidad todos los días, selecciona fines de semana y semana.</p>
                                
                                    <h3 class="text-left">Otros filtros de busqueda</h3>

                                    <div class="radio">
                                        <label>
                                            <input class="car-option" data-car-id="<?php echo $c->id ?>" name="option3" type="checkbox" value="4" <?php if($c->getOptions() & 4) echo 'checked' ?>>
                                            Puedo recibir clientes que pagan el mismo día.
                                        </label>
                                    </div>
                                    <div class="radio">
                                        <label>
                                            <input class="car-option" data-car-id="<?php echo $c->id ?>" name="option4" type="checkbox" value="8" <?php if($c->getOptions() & 8) echo 'checked' ?>>
                                            Solo arriendos mayores a 24 horas.
                                        </label>
                                    </div>
                                </div>
                            </div>                            
                        <?php endif ?>
                    </div>
                    <div class="hidden-xs space-30"></div>
                    <hr>
                    <div class="hidden-xs space-30"></div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>

<div class="hidden-xs space-100"></div>

<script type="text/javascript">

    $("a[data-target='cars']").parent().addClass("active");

    $(document).on('ready',function(){

        /*isChecked();*/

        $(".disabled-until").datetimepicker({
            timepicker:false,
            dayOfWeekStart: 1,
            lang:'es',
            minDate: "<?php echo date('d-m-Y') ?>",
            closeOnDateSelect:true,
            format:'d-m-Y'
        });

        $('.datetimepicker').datetimepicker({
            dayOfWeekStart: 1,
            lang:'es',
            datepicker:false,
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
            format:'H:i'
        });
    });

    $(".btn-disponibility-save").click(function(){

        var p = $(this).parent().parent().parent().parent();

        var car    = p.data("car-id");
        var day    = p.data("day");
        var from   = p.find(".from").val();
        var to     = p.find(".to").val();

        var parameters = {
            "car": car,
            "day": day,
            "from": from,
            "to": to
        };

        $.post("<?php echo url_for('car_availability_save') ?>", parameters, function(r){
            
            if (r.error) {
                alert(r.errorMessage);
            }

        }, "json");
    });
   
    $(".btn-disponibility-remove").click(function(){

        var p = $(this).parent().parent().parent().parent();

        var car    = p.data("car-id");
        var day    = p.data("day");
        var from   = p.find(".from");
        var to     = p.find(".to");

        var parameters = {
            "car": car,
            "day": day
        };

        $.post("<?php echo url_for('car_availability_remove') ?>", parameters, function(r){

            if (r.error) {
            } else {
                from.val("");
                to.val("");
            }
        });
    });

    $(".selectorActivo").change(function() {

        var carId    = $(this).data("car-id");
        var isActive = $(this).val();

        $(this).attr("disabled", true);

        var parameters = {
            "carId": carId,
            "isActive": isActive
        }

        $.post("<?php echo url_for('car_set_active') ?>", parameters, function(r) {
            
            if (r.error) {

            } else {
                location.reload();
            }
        });
    });

    $(".disabled-until").change(function() {

        var carId      = $(this).data("car-id");
        var untilDate  = $(this).val();

        var parameters = {
            "carId": carId,
            "untilDate": untilDate
        };

        $.post("<?php echo url_for('car_set_disabled_until') ?>", parameters, function(r){
            if (r.error) {

            } else {
            }
        }, "json");
    });

    function deleteDisabled(idCar){
        
        var todisabled = $('#todisabled'+idCar).val();

        $.post("<?php echo url_for('profile/CarDisabledUntilDelete') ?>", {"car": idCar,"to": todisabled}, function(r){
            
            if (!r.error) {
                $('#todisabled'+idCar).val("");
            }    
        });
    }

    function deleteAvailability(idCar){
        var from = $('#from'+idCar).val();
        var to   = $('#to'+idCar).val();
        var day  = $(".AOC-container").attr("data-day");
        
        $.post("<?php echo url_for('profile/CarAvailabilityDeleteChangeStatus') ?>", {"car": idCar, "day": day});
    }

    $('.car-option').change(function() {

        var carId     = $(this).data("car-id");
        var option    = $(this).val();        
        var isChecked = $(this).is(":checked") ? 1 : 0;

        var parameters = {
            "option": option,
            "isChecked": isChecked,
            "carId": carId
        };

        $.post("<?php echo url_for('car_set_option') ?>", parameters, function(r){
            
            console.log(r);

            if (r.error) {
            } else {
            }

        }, 'json');
    });

    /*$('.check').change(function(){
        isChecked();
    });*/

    /*function isChecked(){

        var total = 0;
        $("#more").html("");

        if($('input:checkbox[name=option1]').is(':checked')) {
            total+= 40;
        }
        if($('input:checkbox[name=option2]').is(':checked')) {
            total+= 40;
        }
        if($('input:checkbox[name=option3]').is(':checked')) {
            total+= 20;
        }
        if($('input:checkbox[name=option4]').is(':checked')) {
            $("#more").html(" mayores a 24 horas");
        }

        $("#percent").html(total);
    }*/
</script>