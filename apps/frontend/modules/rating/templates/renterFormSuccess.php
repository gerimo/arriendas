<link href="/css/newDesign/ratingRenterForm.css" rel="stylesheet" type="text/css">

<div class="hidden-xs space-100"></div>
<div class="visible-xs space-50"></div>
<div class="row">
    <div class="col-md-offset-4 col-md-4">

        <div class="BCW" id="frm">
            <h1>Califica al dueño</h1>

            <div class="owner-form">

                <label>Escribe tu experiencia</label>
                <textarea class="form-control" id="form-opinion-about-owner" rows="3"></textarea>

                <div class="hidden-xs space-20"></div>
                <label>¿Fué puntual?</label>
                <div class="row">
                    <input type="radio" name="form-option-delay-about-owner" value="1" />Si
                    <input type="radio" name="form-option-delay-about-owner" value="0" />No
                </div>

                <div class="hidden-delay-options">
                    <div class="hidden-xs space-20"></div>
                    <div class="row">
                        <label>¿Cuanto tardó al momento de retirar el vehículo?</label>
                        <div class="row">
                            <input type="radio" name="form-option-start-time-delay-about-owner" value="15" />15 min.
                            <input type="radio" name="form-option-start-time-delay-about-owner" value="30" />30 min.
                            <input type="radio" name="form-option-start-time-delay-about-owner" value="60" />60 min.
                            <input type="radio" name="form-option-start-time-delay-about-owner" value="90" />90 min.
                        </div>

                        <div class="hidden-xs space-20"></div>
                        <label>¿Cuanto tardó al momento de entregar el vehículo?</label>
                        <div class="row">
                            <input type="radio" name="form-option-end-time-delay-about-owner" value="15" />15 min.
                            <input type="radio" name="form-option-end-time-delay-about-owner" value="30" />30 min.
                            <input type="radio" name="form-option-end-time-delay-about-owner" value="60" />60 min.
                            <input type="radio" name="form-option-end-time-delay-about-owner" value="90" />90 min.
                        </div>
                    </div>
                </div>

                <div class="hidden-xs space-20"></div>
                <label>¿El interior del auto estaba limpio?</label>
                <div class="row">
                    <input type="radio" name="form-option-cleaning-about-owner" value="1" />Si
                    <input type="radio" name="form-option-cleaning-about-owner" value="0" />No
                </div>

                <div class="hidden-xs space-20"></div>
                <label>¿El vehículo presentó algun desperfecto?</label>
                <div class="row">
                    <input type="radio" name="form-option-desp-car" value="1" />Si
                    <input type="radio" name="form-option-desp-car" value="0" />No
                </div>

                <div class="hidden-what-desp-car">
                    <div class="hidden-xs space-20"></div>
                    <label>¿Que desperfecto?</label>
                    <textarea class="form-control" id="form-comment-if-desp-car" value="auto_recomendado" rows="3"></textarea>
                </div>

                <div class="hidden-xs space-20"></div>
                <label>¿Recomendarías el vehículo?</label>
                <div class="row">
                    <input type="radio" name="form-option-recommend-car" value="1" />Si
                    <input type="radio" name="form-option-recommend-car" value="0" />No
                </div>

                <div class="hidden-why-recommend-car">
                    <div class="hidden-xs space-20"></div>
                    <label>¿Porqué no lo recomendarías?</label>
                    <textarea class="form-control" id="form-comment-no-recommend-car" value="auto_recomendado" rows="3"></textarea>
                </div>

                <div class="hidden-xs space-20"></div>
                <label>¿Como lo calificarías?</label>
                <div class="row">
                    <span class="rating-cleaning" data-score="0"></span>
                </div>

                <div class="hidden-xs space-20"></div>
                <label>¿Recomendarías al usuario?</label>
                <div class="row">
                    <input type="radio" name="form-option-recommend-owner" value="1" />Si
                    <input type="radio" name="form-option-recommend-owner" value="0" />No
                </div>

                <div class="hidden-why-recommend">
                    <div class="hidden-xs space-20"></div>
                    <label>¿Porqué no lo recomendarías?</label>
                    <textarea class="form-control" id="form-comment-no-recommend-owner" value="usuario_recomendado" rows="3"></textarea>
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
        $(".hidden-what-desp-car").hide();
        $(".hidden-why-recommend-car").hide();

        $('.rating-cleaning').raty({
            half: false,
            targetKeep: true,
            score: function()
            {
                return $(this).attr('data-score');
            }
        });
    });

    $("input:radio[name=form-option-delay-about-owner]").click(function() {  
        if($('input:radio[name=form-option-delay-about-owner]:checked').val() == 0){
            $(".hidden-delay-options").show("slow");
        } else {
            $('input:radio[name=form-option-start-time-delay-about-owner]').prop('checked', false);
            $('input:radio[name=form-option-end-time-delay-about-owner]').prop('checked', false);
            $(".hidden-delay-options").hide("slow");
        }
    });

    $("input:radio[name=form-option-recommend-owner]").click(function() {  
        if($('input:radio[name=form-option-recommend-owner]:checked').val() == 0){
            $(".hidden-why-recommend").show("slow");
            $("#form-comment-no-recommend-owner").val("");
        } else {
            $("#form-comment-no-recommend-owner").val("usuario_recomendado");
            $(".hidden-why-recommend").hide("slow");
        }
    });

    $("input:radio[name=form-option-desp-car]").click(function() {  
        if($('input:radio[name=form-option-desp-car]:checked').val() == 1){
            $(".hidden-what-desp-car").show("slow");
            $("#form-comment-if-desp-car").val("");
        } else {
            $("#form-comment-if-desp-car").val("vehículo_sin_desperfectos");
            $(".hidden-what-desp-car").hide("slow");
        }
    });

    $("input:radio[name=form-option-recommend-car]").click(function() {  
        if($('input:radio[name=form-option-recommend-car]:checked').val() == 0){
            $(".hidden-why-recommend-car").show("slow");
            $("#form-comment-no-recommend-car").val("");
        } else {
            $("#form-comment-no-recommend-car").val("vehículo_recomendado");
            $(".hidden-why-recommend-car").hide("slow");
        }
    });

    function validateForm() {
        
        var option_recommend_car                   = $('input:radio[name=form-option-recommend-car]:checked').val();
        var comment_no_recommend_car               = $('#form-comment-no-recommend-car').val();
        var option_desp_car                        = $('input:radio[name=form-option-desp-car]:checked').val();
        var coment_if_desp_car                     = $('#form-comment-if-desp-car').val();

        var option_recommend_owner                 = $('input:radio[name=form-option-recommend-owner]:checked').val();    //opinion_about_r
        var comment_no_recommend_owner             = $("#form-comment-no-recommend-owner").val();                         //coment_no_recom

        var option_delay_about_owner               = $('input:radio[name=form-option-delay-about-owner]:checked').val();  //op_puntual_about
        var option_end_time_delay_about_owner      = $('input:radio[name=form-option-end-time-delay-about-owner]:checked').val(); // time
        var option_start_time_delay_about_owner    = $('input:radio[name=form-option-start-time-delay-about-owner]:checked').val(); // time
        
        var opinion_about_owner                    = $("#form-opinion-about-owner").val();                            //opinion_about_owner
        var rating                                  = $('.rating-cleaning').find("input[name='score']").val();  //op_cleaning_about_owner
        var id                                      = $('#reserveId').val();

        $("#load-image").show();
        $.post("<?php echo url_for('rating/makeARate') ?>", {"opinion_about_owner": opinion_about_owner, "op_recom_owner": option_recommend_owner, "coment_no_recom_owner": comment_no_recommend_owner, "op_cleaning_about_owner": rating, "op_puntual_about_owner": option_delay_about_owner, "time_delay_start_owner": option_start_time_delay_about_owner, "time_delay_end_owner": option_end_time_delay_about_owner, "op_recom_car": option_recommend_car, "coment_no_recom_car": comment_no_recommend_car, "op_desp_car": option_desp_car, "coment_si_desp_car": coment_if_desp_car, "reserveId": id}, function(r){

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