<link href="/css/newDesign/ratingOwnerForm.css" rel="stylesheet" type="text/css">

<div class="hidden-xs space-100"></div>
<div class="visible-xs space-50"></div>
<div class="row">
    <div class="col-md-offset-4 col-md-4">

        <div class="BCW" id="frm">
            <h1>Califica al arrendatario</h1>

            <div class="owner-form">

                <label>Escribe tu experiencia</label>
                <textarea class="form-control" id="form-opinion-about-renter" rows="3"></textarea>

                <div class="hidden-xs space-20"></div>
                <label>¿Fué puntual?</label>
                <div class="row">
                    <input type="radio" name="form-option-delay-about-renter" value="1" />Si
                    <input type="radio" name="form-option-delay-about-renter" value="0" />No
                </div>

                <div class="hidden-delay-options">
                    <div class="hidden-xs space-20"></div>
                    <div class="row">
                        <label>¿Cuanto tardó al retirar el vehículo?</label>
                        <div class="row">
                            <input type="radio" name="form-option-start-time-delay-about-renter" value="15" />15 min.
                            <input type="radio" name="form-option-start-time-delay-about-renter" value="30" />30 min.
                            <input type="radio" name="form-option-start-time-delay-about-renter" value="60" />60 min.
                            <input type="radio" name="form-option-start-time-delay-about-renter" value="90" />90 min.
                        </div>

                        <div class="hidden-xs space-20"></div>
                        <label>¿Cuanto tardó al entregar el vehículo?</label>
                        <div class="row">
                            <input type="radio" name="form-option-end-time-delay-about-renter" value="15" />15 min.
                            <input type="radio" name="form-option-end-time-delay-about-renter" value="30" />30 min.
                            <input type="radio" name="form-option-end-time-delay-about-renter" value="60" />60 min.
                            <input type="radio" name="form-option-end-time-delay-about-renter" value="90" />90 min.
                        </div>
                    </div>
                </div>

                <div class="hidden-xs space-20"></div>
                <label>¿El interior del auto estaba limpio?</label>
                <div class="row">
                    <input type="radio" name="form-option-cleaning-about-renter" value="1" />Si
                    <input type="radio" name="form-option-cleaning-about-renter" value="0" />No
                </div>

                <div class="hidden-xs space-20"></div>
                <label>¿Como lo calificarías?</label>
                <div class="row">
                    <span class="rating-cleaning" data-score="0"></span>
                </div>

                <div class="hidden-xs space-20"></div>
                <label>¿Recomendarías al usuario?</label>
                <div class="row">
                    <input type="radio" name="form-option-recommend-renter" value="1" />Si
                    <input type="radio" name="form-option-recommend-renter" value="0" />No
                </div>

                <div class="hidden-why-recommend">
                    <div class="hidden-xs space-20"></div>
                    <label>¿Porqué no lo recomendarías?</label>
                    <textarea class="form-control" id="form-comment-no-recommend-renter" value="usuario_recomendado" rows="3"></textarea>
                </div>

            </div>

            <div class="row">
                <div class="hidden-xs space-20"></div>
                <div class="alert"></div>

                <button class="btn btn-a-action btn-block" id="save" onclick="validateForm()">Calificar</button>

                <div class="text-center" id="load-image" style="display: none">
                    <div class="hidden-xs space-10"></div>
                    <img class="loading" src="/images/ajax-loader.gif">
                </div>

            </div>
        </div>

    </div>
</div>
<input hidden id="reserveId" value="<?php echo $reserveId?>">
<div class="hidden-xs space-100"></div>  

<script type="text/javascript">
    $(document).ready(function() { 
        $(".hidden-why-recommend").hide();
        $(".hidden-delay-options").hide();

        $('.rating-cleaning').raty({
            half: false,
            targetKeep: true,
            score: function()
            {
                return $(this).attr('data-score');
            }
        });
    });

    $("input:radio[name=form-option-delay-about-renter]").click(function() {  
        if($('input:radio[name=form-option-delay-about-renter]:checked').val() == 0){
            $(".hidden-delay-options").show("slow");
        } else {
            $('input:radio[name=form-option-start-time-delay-about-renter]').prop('checked', false);
            $('input:radio[name=form-option-end-time-delay-about-renter]').prop('checked', false);
            $(".hidden-delay-options").hide("slow");
        }
    });

    $("input:radio[name=form-option-recommend-renter]").click(function() {  
        if($('input:radio[name=form-option-recommend-renter]:checked').val() == 0){
            $(".hidden-why-recommend").show("slow");
        } else {
            $("#form-comment-no-recommend-renter").val("usuario_recomendado");
            $(".hidden-why-recommend").hide("slow");
        }
    });

    function validateForm() {
        

        var option_recommend_renter                 = $('input:radio[name=form-option-recommend-renter]:checked').val();    //opinion_about_r
        var comment_no_recommend_renter             = $("#form-comment-no-recommend-renter").val();                         //coment_no_recom

        var option_delay_about_renter               = $('input:radio[name=form-option-delay-about-renter]:checked').val();  //op_puntual_about
        var option_end_time_delay_about_renter      = $('input:radio[name=form-option-end-time-delay-about-renter]:checked').val(); // time
        var option_start_time_delay_about_renter    = $('input:radio[name=form-option-start-time-delay-about-renter]:checked').val(); // time
        
        var opinion_about_renter                    = $("#form-opinion-about-renter").val();                            //opinion_about_renter
        var rating                                  = $('.rating-cleaning').find("input[name='score']").val();  //op_cleaning_about_renter
        var id                                      = $('#reserveId').val();

        $("#load-image").show();
        $.post("<?php echo url_for('rating/makeARate') ?>", {"opinion_about_renter": opinion_about_renter, "op_recom_renter": option_recommend_renter, "coment_no_recom_renter": comment_no_recommend_renter, "op_cleaning_about_renter": rating, "op_puntual_about_renter": option_delay_about_renter, "time_delay_start_renter": option_start_time_delay_about_renter, "time_delay_end_renter": option_end_time_delay_about_renter, "reserveId": id}, function(r){

            $(".alert").removeClass("alert-a-danger");
            $(".alert").removeClass("alert-a-success");

            if (r.error) {
                $("#load-image").hide();
                $(".alert").css("color", "red");
                $(".alert").html(r.errorMessage);
            } else {
                $("#load-image").hide();
                location.href="<?php echo url_for('rating_show_reserves') ?>";
                
            }

        }, 'json');
    };
    
    
</script>