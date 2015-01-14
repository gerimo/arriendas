<link href="/css/newDesign/index.css" rel="stylesheet" type="text/css">

<div class="hidden-xs space-100"></div>
<div class="visible-xs space-50"></div>

<div class="row">
    <div class="col-md-offset-2 col-md-8">

        <div class="BCW">

            <h1>Reservas (<?php echo count($Reserves) ?>)</h1>

            <div class="panel-group" id="reserves" role="tablist" aria-multiselectable="true">
                <?php foreach ($Reserves as $key => $Reserve ): ?>
                    <div class="panel panel-default">
                        <div class="panel-heading" role="tab" id="reserve-<?php echo $Reserve->getId() ?>">
                            <div class="row">
                                <div class="col-md-1">ID</div>
                                <div class="col-md-3">Desde</div>
                                <div class="col-md-3">Hasta</div>
                            </div>
                            <div class="row">
                                <div class="col-md-1"><?php echo $Reserve->getId() ?></div>
                                <div class="col-md-3"><?php echo date("d-m-Y H:i", strtotime($Reserve->getDate())) ?></div>
                                <div class="col-md-3"><?php echo date("d-m-Y H:i", strtotime("+".$Reserve->getDuration()." hour", strtotime($Reserve->getDate()))) ?></div>
                                <div class="col-md-3"><a href="#">Descargar contratos</a></div>
                                <div class="col-md-2"><button class="btn-a-action" data-reserve-id="<?php echo $Reserve->getId() ?>">Extender</button></div>
                            </div>
                            <a class="btn-block text-center" data-toggle="collapse" data-parent="#reserves" href="#collapse-<?php echo $Reserve->getId() ?>" aria-expanded="true" aria-controls="collapse-<?php echo $Reserve->getId() ?>">
                                <i class="fa fa-chevron-down"></i>
                            </a>
                        </div>
                        <div id="collapse-<?php echo $Reserve->getId() ?>" data-reserve-id="<?php echo $Reserve->getId() ?>" class="panel-collapse collapse" role="tabpanel" aria-labelledby="reserve-<?php echo $Reserve->getId() ?>">
                            <div class="panel-body">

                                <div class="row">
                                    <div class="col-md-3"><strong>Auto</strong></div>
                                    <div class="col-md-3"><strong>Dirección</strong></div>
                                    <div class="col-md-3"><strong>Dueño</strong></div>
                                </div>

                                <?php foreach ($Opportunities[$Reserve->getId()] as $O): ?>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <?php echo $O->getCar()->getModel()->getBrand()->getName() ." ". $O->getCar()->getModel()->getName() ?>
                                        </div>
                                        <div class="col-md-3">
                                            <?php echo $O->getCar()->getAddress() .", ". $O->getCar()->getCommune() ?>
                                        </div>
                                        <div class="col-md-3">
                                            <?php echo $O->getCar()->getUser()->getFirstname() ." ". $O->getCar()->getUser()->getLastname() ?>
                                        </div>
                                        <div class="col-md-3 text-center">
                                            <?php if ($O->getTransaction()->getCompleted()): ?>
                                                <i class="fa fa-check"></i>
                                            <?php else: ?>
                                                <button class="change btn-a-primary" data-reserve-id="<?php echo $O->getId() ?>">Cambiar</button>
                                            <?php endif ?>
                                        </div>
                                    </div>
                                <?php endforeach ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach ?>
            </div>
        </div>
    </div>
</div>

<div class="hidden-xs space-100"></div>

<script>

    $('#reserves').on('shown.bs.collapse', function () {
        console.log("down to up");
    });
    $('#reserves').on('hidden.bs.collapse', function () {
        console.log("up to down");
    });

    $(".extend").click(function(){

        var reserveId = $(this).data("reserve-id");

        $.post("<?php echo url_for('reserve_extend') ?>", {"reserveId": reserveId}, function(r){
    
            if (r.error) {
            }
        });
    });

    $(".change").click(function(){

        var reserveId     = $(this).parent().parent().parent().parent().data("reserve-id");
        var opportunityId = $(this).data("reserve-id");

        $.post("<?php echo url_for('reserve_change') ?>", {"reserveId": reserveId, "opportunityId": opportunityId}, function(r){
    
            if (r.error) {
            }
        });
    });
</script>