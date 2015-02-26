<div class="space-50 visible-xs"></div>

<div class="row">
    <div class="col-md-offset-3 col-md-6">

        <div class="BCW">

            <h2 class="text-center"><?php echo $paymentMsg ?></h2>

            <p>El pago puede demorarse unos instantes en asentarse, por favor espere que volveremos a comprobar su estado</p>

            <div class="bar-container">
                <span><small>Próxima verificación en...</small></span>
                <div id="progressbar" class="progress-striped"></div>
            </div>            
        </div>
    </div>		
</div>

<script>
    (function($) {
        $.fn.arriendasCounter = function() {
            var countdown = 0;
            var loopProcess = null;
            var progbarSelector = "#" + $(this).attr("id");
            var methods = {
                "updateProgressBar": function() {
                    $(progbarSelector).progressbar({
                        value: countdown
                    });
                },
                "start": function() {
                    $(progbarSelector).progressbar({
                        value: false
                    });
                    function loop() {
                        if (countdown < 100) {
                            countdown += 0.6;
                            methods.updateProgressBar();
                        } else {
                            clearInterval(loopProcess);
                            location.reload();
                        }
                    }
                    loopProcess = setInterval(loop, 50);
                }
            }
            methods.start();
        }
    })(jQuery);

    $(document).ready(function() {
        $("#progressbar").arriendasCounter();
    });
</script>