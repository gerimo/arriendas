<link href="/css/newDesign/opportunities.css" rel="stylesheet" type="text/css">

<div class="hidden-xs space-100"></div>
<div class="visible-xs space-50"></div>

<div class="row">
    <div class="col-md-offset-2 col-md-8">

        <div class="BCW">

            <h1>Oportunidades (<?php echo count($Opportunities) ?>)</h1>

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
                <tfoot>
                    <tr>
                        <th>Desde</th>
                        <th>Hasta</th>
                        <th>Monto</th>
                        <th>Arrendatario</th>
                        <th>Acción</th>
                    </tr>
                </tfoot>
                <tbody>
                    <?php foreach($Opportunities as $Opp): ?>
                        <tr data-reserve-id="<?php echo $Opp["Reserve"]->getId() ?>">
                            <td><?php echo date("Y-m-d H:i", strtotime($Opp["Reserve"]->getFechaInicio2())) ?></td>
                            <td><?php echo date("Y-m-d H:i", strtotime($Opp["Reserve"]->getFechaTermino2())) ?></td>
                            <td><?php echo '$'.number_format(Car::getPrice($Opp["Reserve"]->getFechaInicio2(), $Opp["Reserve"]->getFechaTermino2(), $Opp["Reserve"]->getCar()->getPricePerHour(), $Opp["Reserve"]->getCar()->getPricePerDay(), $Opp["Reserve"]->getCar()->getPricePerWeek(), $Opp["Reserve"]->getCar()->getPricePerMonth()), 0, ',', '.') ?></td>
                            <td><?php echo $Opp["Reserve"]->getUser()->getFirstname() ." ". $Opp["Reserve"]->getUser()->getLastname() ?></td>
                            <td><button class="approve btn-a-primary">Aprobar</button></td>
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
        <h1>Escoge el auto con el qué deseas postular</h1>
        <?php foreach($Cars as $k => $Car): ?>
            <div class="radio">
                <label>
                    <input id="car<?php echo $k ?>" name="optionsCars" type="radio" value="<?php echo $Car->getId() ?>" <?php if (count($Cars) == 1) echo 'checked' ?>>
                    <?php echo $Car->getModel()->getBrand()->getName() ." ". $Car->getModel()->getName() ?>
                </label>
            </div>
        <?php endforeach ?>
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

        var numberOfCars = $("#dialog-cars").data("data-number-of-cars");

        if (numberOfCars > 1) {
            $("#dialog-cars").dialog({
                closeOnText: true,
                modal: true,
                resizable: true,
                title: "Mis autos activos",
                width: "75%",
                buttons: [
                    {
                        text: "Seleccionar",
                        click: function() {
                            if ($("input[name='optionsCars']").is(":checked")) {
                                $( this ).dialog( "close" );
                            }
                        }
                    }
                ]
            });
        }

        var carId     = $("input[name='optionsCars']:checked").val();
        var reserveId = $(this).parent().parent().data("reserve-id");

        $.post("<?php echo url_for('opportunities_approve') ?>", {"reserveId": reserveId, "carId": carId}, function(r){
console.log(r);

        }, "json");
    });
</script>