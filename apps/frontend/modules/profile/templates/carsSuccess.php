<link href="/css/newDesign/cars.css" rel="stylesheet" type="text/css">

<div class="hidden-xs space-100"></div>
<div class="visible-xs space-50"></div>

<div class="row">
    <div class="col-md-offset-3 col-md-6">
        <div class="BCW">
            
            <div class="col-md-offset-8 col-md-4">
                <button class="col-md-12  btn-block btn-a-primary" onclick="location.href='<?php echo url_for('cars/create') ?>'">¡Sube un auto!</button>
            </div>

            <h1>Mis Autos</h1>
                       
            <div class="row">
                <?php foreach ($cars as $c): ?>
                    <br/><br/>
                    
                    <div class="misautos_user_item grey" id="item_<?php echo $c->getId() ?>">
                                            
                        <div class="row grey">
                            <div class="col-md-3 ">
                                <a href="#" >
                                    <?php if($c->getPhotoS3() == 1): ?>
                                        <?php echo image_tag($c->getFoto(),array("width"=>"84px","height"=>"84px")) ?>
                                    <?php else: ?>
                                        <?php $base_url = $sf_request->getUriPrefix().$sf_request->getRelativeUrlRoot() ?>
                                        <?php echo "<img class='foto' src='http://res.cloudinary.com/arriendas-cl/image/fetch/w_84,h_84,c_fill,g_center/".$base_url."/uploads/cars/".$c->getFoto()."'/>" ?>
                                    <?php endif; ?>
                                </a>
                            </div>
                            
                            <div class="col-offset-md-2 col-md-5 ">
                                <a class= "misautos_marca" href="#" ><span><?=$c->getModel()->getBrand()->getName()?> <?=$c->getModel()->getName()?></span></a>
                           </div>
                            
                            
                            <div class="col-md-1" id="car_<?php echo $c->getId() ?>">
                                <div class="imgActivo col-md-offset-11 col-md-1" style="margin-top:12px">
                                        <div class="circuloActivo" style="display: <?= ($c->getActivo() == 1)?"block":"none" ?>"></div>
                                        <div class="circuloInactivo" style="display: <?= ($c->getActivo() == 0)?"block":"none" ?>" ></div>
                                </div>
                            </div>
                            <div class="col-md-3">    
                                <select class="form-control selectorActivo" >
                                    <option  value="1" <?= ($c->getActivo() == 1)?"selected":"" ?>>Activo</option>
                                    <option  value="0" <?= ($c->getActivo() == 0)?"selected":"" ?>>Inactivo</option>
                                </select>
                            </div>     
                        </div>
                        <!-- Esto es para mostrar el form desde - hasta-->
                        <div class="row">                                   
                            <div id="<?php echo $c->getId() ?>">
                                <?php if (isset($availabilityOfCars[$c->getId()])): ?>
                                    <div class="availabilityOfCars">
                                        <h2>Horario para recibir un cliente</h2>   
                                        <?php foreach ($availabilityOfCars[$c->getId()] as $AOC): ?>
                                            <div class="row">
                                                <h3 class="col-md-offset-1"><?php echo $AOC["dayName"] ." ". date("j", strtotime($AOC["day"])) ?></h3>
                                                <div class="AOC-container" data-car-id="<?php echo $c->getId() ?>" data-day="<?php echo $AOC['day'] ?>">
                                                    <div class='col-md-offset-3 col-md-4'>
                                                        <div class="date-group">
                                                            <span>Desde:</span>
                                                            <div class='input-group date'>
                                                                <input id="from<?php echo $c->getId() ?>" class="from datetimepicker form-control" type="text" <?php if (isset($AOC['from'])):echo "value='".date("H:i", strtotime($AOC['from']))."'";endif; ?>>
                                                                <!--span class="input-group-addon"><span class="glyphicon glyphicon-time"></span></span-->
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="date-group">
                                                            <span>Hasta:</span>
                                                            <div class='input-group date'>
                                                                <input id="to<?php echo $c->getId() ?>" class="to datetimepicker form-control" type="text" <?php if (isset($AOC['to'])): echo "value='".date("H:i", strtotime($AOC['to']))."'"; endif; ?>>
                                                                <!--span class="input-group-addon"><span class="glyphicon glyphicon-time"></span></span-->
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <br>
                                                    <div class="hidden-xs space-10">
                                                    <br><br>
                                                    <br><br>
                                                    </div>
                                                    <div class="botones col-md-12 hidden-xs" style="margin-top:22px">
                                                        <a class="btn-a-action col-md-offset-4 col-md-2 btnCars-delete-availability" href="">Eliminar</a>
                                                        <a style="margin-left: 87px" class="btn-a-action col-md-offset-1 col-md-2 btnCars-save-availability" href="">Guardar</a>
                                                        <br/>
                                                    </div>
                                                    <div class="visible-xs">
                                                        <a class="btn-a-action btn-block btnCars-delete-availability" href="">Eliminar</a>
                                                        <a class="btn-a-action btn-block btnCars-save-availability" href="">Guardar</a>
                                                        <br/>
                                                    </div>

                                                </div><!--fin AOC-container-->
                                            </div>
                                                <div class="hidden-xs space-10">    
                                                <br/>
                                                <br/>
                                                <br/>
                                            </div>
                                        <?php endforeach; ?>
                                    </div><!--fin availabilityOfCars-->
                                <?php else: ?>                                    
                                    <div class="disabledOfCars" id="dis_unt_<?php echo $c->getId() ?>" style="display: <?= ($c->getActivo() == 0)?"block":"none" ?>">
                                        <div clas="DOC-container" data-car-id="<?php echo $c->getId() ?>">
                                            <div class="col-md-offset-9 col-md-3">
                                                <div class='date-group'>
                                                    <span>Inactivo Hasta:</span>
                                                    <div class="input-group date ">
                                                        <input id="todisabled<?php echo $c->getId() ?>" class="todisabled form-control" type="text" value="<?php echo $c->getDisabledUntil() ? date('d-m-Y',strtotime($c->getDisabledUntil())): "";?>">
                                                        <!--span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span-->
                                                        </span>
                                                    </div><!-- fin input-group date-->
                                                    <br>
                                                </div>
                                            </div>
                                        </div><!--Fin DocContainer -->
                                    </div><!-- FIN disabledOfCars-->
                                <?php endif; ?>
                            </div><!-- fin id dinamico del desde-hasta -->
                        </div>
                    </div><!--fin misautos_user_item-->
                <?php endforeach; ?>
            </div><!--fin row-->
        </div><!--fin BCW-->
    </div><!--fin -->
</div>

<div class="hidden-xs space-100"></div>

<script type="text/javascript">

    var toggleActiveCarAjax = <?php echo "'".url_for("profile/toggleActiveCarAjax")."';" ?>

    function deleteDisabled(idCar){
        
        var todisabled = $('#todisabled'+idCar).val();
        $.post("<?php echo url_for('profile/CarDisabledUntilDelete') ?>", {"car": idCar,"to": todisabled}, function(r){
            console.log(r);
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
        
    $(document).on('ready',function(){
          
        $(".selectorActivo").change(function() {

            //prev()->busca el hermano anterior
            //next()->busca el hermano siguiente
            var id           = $(this).parent().prev().attr("id").replace("car_", "");
            var active       = $(this).val();
            var divImgActivo = $(this).parent().children(".imgActivo");
            if(active == 0){//esta desactivado
                divImgActivo.children(".circuloActivo").css("display","none");
                divImgActivo.children(".circuloInactivo").css("display","block");
                //setea en 1 is_delete of CarAvailability
                deleteAvailability(id);
            }else{
                divImgActivo.children(".circuloInactivo").css("display","none");
                divImgActivo.children(".circuloActivo").css("display","block");
                //setea en null disabled_until.
                deleteDisabled(id);
            }
                
            $('#item_'+id+' .cargando').show();
            $.ajax({
               type:'post',
                    url: toggleActiveCarAjax,
                    data:{
                        "idCar" : id,
                        "active" : active
                    }
            }).done(function(){
                    $('#item_'+id+' .cargando').hide();
                    location.reload();
            }).fail(function(){
                    alert('Ha ocurrido un error al inactivar el vehículo, inténtelo nuevamente');
                    $('#item_'+id+' .cargando').hide();
            });
        });

        $(".btnCars-save-availability").click(function(e){

            e.preventDefault();
            
            var p = $(this).parent().parent();

            var car    = p.data("car-id");
            var day    = p.data("day");
            var from   = p.find(".from").val();
            var to     = p.find(".to").val();
            var loader = p.find(".loader");

            loader.show();

            $.post("<?php echo url_for('profile/carAvailabilitySave') ?>", {"car": car, "day": day, "from": from, "to": to}, function(r){
                
                if (r.error) {
                    alert(r.errorMessage);
                }
                
                loader.hide();

            }, "json");

            
            if(to<from){
                p.find(".to").val("");
                
            }
        });
       
        $(".btnCars-delete-availability").click(function(e){

            e.preventDefault();
            var p = $(this).parent().parent();

            var car    = p.data("car-id");
            var day    = p.data("day");
            var from   = p.find(".from");
            var to     = p.find(".to");
            var loader = p.find(".loader");

            loader.show();

            $.post("<?php echo url_for('profile/carAvailabilityDelete') ?>", {"car": car, "day": day}, function(r){

                if (!r.error) {
                    from.val("");
                    to.val("");
                    p.find(".to").val("");
                }
                loader.hide();
            });
        });

        $(".todisabled").change(function(e){

            e.preventDefault();

            var p          = $(this).parent().parent().parent().parent();
            var car        = p.data("car-id");
            var todisabled = p.find(".todisabled").val();
            var loader     = p.find(".loader");
            loader.show();
            $.post("<?php echo url_for('profile/CarDisabledUntilSave') ?>", {"car": car,"to": todisabled}, function(r){
                console.log(r); // JS no esta interpretando el JSON
                loader.hide();

            },"json");
                       
        });

        $(".todisabled").datetimepicker({

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
</script>