<link href="/css/newDesign/opportunities.css" rel="stylesheet" type="text/css">

<div class="hidden-xs space-100"></div>
<div class="visible-xs space-50"></div>

<div class="row">
    <div class="col-md-offset-2 col-md-8">

        <div class="BCW">

            <h1>Oportunidades</h1>

            <table class="display responsive no-wrap" id="opportunities" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <th>Desde</th>
                        <th>Hasta</th>
                        <th>Monto</th>
                        <th>Arrendatario</th>
                        <th>Acción</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($Opportunities as $Opp): ?>
                        <tr data-reserve-id="<?php echo $Opp["Reserve"]->getId() ?>">
                            <td><?php echo date("Y-m-d H:i", strtotime($Opp["Reserve"]->getFechaInicio2())) ?></td>
                            <td><?php echo date("Y-m-d H:i", strtotime($Opp["Reserve"]->getFechaTermino2())) ?></td>
                            <td><?php echo '$'.number_format(CarTable::getPrice($Opp["Reserve"]->getFechaInicio2(), $Opp["Reserve"]->getFechaTermino2(), $Opp["Reserve"]->getCar()->getPricePerHour(), $Opp["Reserve"]->getCar()->getPricePerDay(), $Opp["Reserve"]->getCar()->getPricePerWeek(), $Opp["Reserve"]->getCar()->getPricePerMonth()), 0, ',', '.') ?></td>
                            <td><?php echo $Opp["Reserve"]->getUser()->getFirstname() ." ". $Opp["Reserve"]->getUser()->getLastname() ?></td>
                            <td>
                                <button class="approve btn btn-a-primary">Postular</button>
                                <img class="loading" src="/images/ajax-loader.gif">
                            </td>
                        </tr>
                    <?php endforeach ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="hidden-xs space-100"></div>

<!-- Dialogs -->
<div style="display:none">

    <div id="dialog-cars" data-number-of-cars="<?php echo count($Cars) ?>">
        <h3 class="text-center">Escoge el auto con el qué deseas postular</h3>
        <div class="row">
            <?php foreach($Cars as $k => $Car): ?>

                <?php $photo = image_path('../uploads/cars/'.$Car->foto_perfil) ?>
                <?php if ($Car->photoS3 == 1): ?>
                    <?php $photo = image_path($Car->foto_perfil) ?>
                <?php endif ?>
                
                <div class="col-md-offset-1 col-md-5">
                    <div class="radio">
                        <label>
                            <input id="car<?php echo $k ?>" name="optionsCars" type="radio" value="<?php echo $Car->getId() ?>" <?php if (count($Cars) == 1) echo 'checked' ?>>
                            <img class='car img-responsive' src='http://res.cloudinary.com/arriendas-cl/image/fetch/w_134,h_99,c_fill,g_center/http://www.arriendas.cl<?php echo $photo ?>' height='99' width='134' alt='<?php echo $Car->getModel()->getBrand()->name." ".$Car->getModel()->name ?>'>                            
                            <?php echo $Car->getModel()->getBrand()->getName() ." ". $Car->getModel()->getName() ?>
                        </label><br>
                    </div>
                </div>
            <?php endforeach ?>
        </div>
    </div>

    <div id="dialog-alert" title="">
        <p></p>
    </div>
</div>

<script>

    $("a[data-target='opportunities']").parent().addClass("active");

    $(document).ready(function(){
    
        $('#opportunities').DataTable({
            info: false,
            paging: false,
            responsive: true
        });
    });

    $(".approve").click(function(){

        var numberOfCars = $("#dialog-cars").data("number-of-cars");
        var reserveId    = $(this).parent().parent().data("reserve-id");

        if (numberOfCars > 1) {
            $("#dialog-cars").dialog({
                closeOnText: true,
                modal: true,
                resizable: true,
                title: "Mis autos activos",
                width: "50%",
                buttons: [
                    {
                        text: "Seleccionar",
                        click: function() {
                            if ($("input[name='optionsCars']").is(":checked")) {
                                $( this ).dialog( "close" );
                                approve(reserveId);
                            }
                        }
                    }
                ]
            });
        } else {
            approve(reserveId);
        }        
    });

    function approve(reserveId) {

        $("[data-reserve-id="+reserveId+"]").find("button").attr("disabled", true);
        $("[data-reserve-id="+reserveId+"]").find("img").show();

        var carId = $("input[name='optionsCars']:checked").val();

        $.post("<?php echo url_for('opportunities_approve') ?>", {"reserveId": reserveId, "carId": carId}, function(r){

            if (r.error) {
                $("#dialog-alert p").html(r.errorMessage);
                $("#dialog-alert").attr("title", "Problemas con la aprobación");
                $("#dialog-alert").dialog({
                    buttons: [{
                        text: "Aceptar",
                        click: function() {
                            $( this ).dialog( "close" );
                        }
                    }]
                });
                $("[data-reserve-id="+reserveId+"]").find("button").removeAttr("disabled");
                $("[data-reserve-id="+reserveId+"]").find(".loading").hide();
            } else {
                location.reload();
            }
        }, "json");
    }
</script>