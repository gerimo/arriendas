<div class="space-40 hidden-xs"></div>
<div class="space-20 visible-xs"></div>

<div class="container">

    <h1>Dashboard</h1>
    <div class="space-30 hidden-xs"></div>

    <div class="row">
        <div class="col-md-8">
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

            <div class="row">
                <div class="col-md-6">
                    <p class="kpi-primary text-center" id="kpi1"></p>
                    <p class="text-center">Porcetanje de las reservas pagadas</p>
                </div>
                <div class="col-md-6">
                    <p class="kpi-primary text-center" id="kpi2"></p>
                    <p class="text-center">Oportunidades generadas sobre las pagadas</p>
                </div>
            </div>

            <div class="row">
                <div class="col-md-3">
                    <p class="kpi-secondary text-center" id="kpi3"></p>
                    <p class="text-center">Sitio</p>
                </div>
                <div class="col-md-3">
                    <p class="kpi-secondary text-center" id="kpi4"></p>
                    <p class="text-center">Mailing</p>
                </div>
                <div class="col-md-3">
                    <p class="kpi-secondary text-center" id="kpi5"></p>
                    <p class="text-center">Backend</p>
                </div>
                <div class="col-md-3">
                    <p class="kpi-secondary text-center" id="kpi6"></p>
                    <p class="text-center">Autos confirmados</p>
                </div>
            </div>
        </div>
        <div class="space-20 visible-xs"></div>
        <div class="col-md-4">

            <div class="row">
                <div class="col-md-offset-1 col-md-11">
                    <div class="form-group">
                        <label for="maxIterations">Cantidad máxima de iteraciones</label>
                        <input class="form-control" id="maxIterations" placeholder="Cantidad máxima de iteraciones" type="number" value="<?php if ($oOC->max_iterations) echo $oOC->max_iterations ?>">
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-offset-1 col-md-11">
                    <div class="form-group">
                        <label for="kmPerIteration">KM por iteración</label>
                        <input class="form-control" id="kmPerIteration" placeholder="KM por iteración" type="number" value="<?php if ($oOC->km_per_iteration) echo $oOC->km_per_iteration ?>">
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-offset-1 col-md-11">
                    <div class="form-group">
                        <label for="exclusivityTime">Tiempo de exclusividad (en minutos)</label>
                        <input class="form-control" id="exclusivityTime" placeholder="Tiempo de exclusividad (en minutos)" type="number" value="<?php if ($oOC->exclusivity_time) echo $oOC->exclusivity_time ?>">
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-offset-1 col-md-11">
                    <button class="btn btn-primary btn-block" id="save">Guardar</button>
                    <p class="alert text-center" style="margin: 5px 0">&nbsp;</p>
                </div>
            </div>
        </div>
    </div>

    <div class="space-30 hidden-xs"></div>
    <pre><?php echo $log ?></pre>
</div>

<input id="from" type="hidden">
<input id="to" type="hidden">

<script>

    $("li[data-section='Oportunidades']").addClass("active");

    $(document).ready(function(){
        initializeDate("from", new Date(<?php echo strtotime(date("Y-m-d 00:00:00")) * 1000 ?>), true, false);
        initializeDate("to", new Date(<?php echo strtotime("now") * 1000 ?>), true, false);
        afterDateRefresh();
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

        $.post("<?php echo url_for('opportunity_kpi_refresh') ?>", parameters, function(r){

            if (r.error) {
            } else {
                $("#kpi1").html(r.kpi1+"%");
                $("#kpi2").html(r.kpi2);
                $("#kpi3").html(r.kpi3+"%");
                $("#kpi4").html(r.kpi4+"%");
                $("#kpi5").html(r.kpi5+"%");
                $("#kpi6").html(r.kpi6+"%");
            }
        }, "json");
    }
</script>
<script src="/js/newDesign/dates.js" type="text/javascript"></script>