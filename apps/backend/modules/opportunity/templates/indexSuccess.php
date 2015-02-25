<div class="space-80 hidden-xs"></div>
<div class="space-60 visible-xs"></div>

<div class="container">

    <div class="row">
        <div class="col-md-7">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="fromH">Desde</label>
                        <input class="form-control text-left" id="fromH" type="button">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="toH">Hasta</label>
                        <input class="form-control text-left" id="toH" type="button">
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-5">

            <div class="row">
                <div class="col-md-offset-1 col-md-8">
                    <div class="form-group">
                        <label for="maxIterations">Cantidad máxima de iteraciones</label>
                        <input class="form-control" id="maxIterations" placeholder="Cantidad máxima de iteraciones" type="number" value="<?php if ($oOC->max_iterations) echo $oOC->max_iterations ?>">
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-offset-1 col-md-8">
                    <div class="form-group">
                        <label for="kmPerIteration">KM por iteración</label>
                        <input class="form-control" id="kmPerIteration" placeholder="KM por iteración" type="number" value="<?php if ($oOC->km_per_iteration) echo $oOC->km_per_iteration ?>">
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-offset-1 col-md-8">
                    <div class="form-group">
                        <label for="exclusivityTime">Tiempo de exclusividad (en minutos)</label>
                        <input class="form-control" id="exclusivityTime" placeholder="Tiempo de exclusividad (en minutos)" type="number" value="<?php if ($oOC->exclusivity_time) echo $oOC->exclusivity_time ?>">
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-offset-1 col-md-8">
                    <button class="btn btn-primary btn-block" id="save">Guardar</button>
                    <p class="alert text-center" style="margin: 5px 0">&nbsp;</p>
                </div>
            </div>
        </div>
    </div>
</div>

<input id="from" type="hidden">
<input id="to" type="hidden">

<script>

    $(document).ready(function(){
        initializeDate("from", new Date(<?php echo strtotime("now") * 1000 ?>), true, false);
        initializeDate("to", new Date(<?php echo strtotime("now") * 1000 ?>), true, false);
    });

    $(document).on("click", "#save", function(){
    
        $("#save").attr("disabled", true);

        var maxIterations   = $("#maxIterations").val();
        var kmPerIteration  = $("#kmPerIteration").val();
        var exclusivityTime = $("#exclusivityTime").val();

        var parameters = {
            "maxIterations": maxIterations,
            "kmPerIteration": kmPerIteration,
            "exclusivityTime": exclusivityTime
        };
    
        $.post("<?php echo url_for('opportunity_mailing_config_save') ?>", parameters, function(r){

            $(".alert").removeClass("text-danger");
            $(".alert").removeClass("text-success");
    
            if (r.error) {
                $(".alert").addClass("text-danger");
                $(".alert").html(r.errorMessage);
            } else {
                $(".alert").addClass("text-success");
                $(".alert").html("Datos guardados exitosamente");
            }
    
            $("#save").removeAttr("disabled");
        });
    });

    function afterDateRefresh() {

        var from = $("#from").val();
        var to   = $("#to").val();

        var parameters = {
            from: from,
            to: to
        };

        console.log(parameters);

        $.post("<?php echo url_for('opportunity_kpi_refresh') ?>", parameters, function(r){
console.log(r);
            if (r.error) {
            } else {
                
            }
        });
    }
</script>
<script src="/js/newDesign/dates.js" type="text/javascript"></script>