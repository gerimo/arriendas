<link href="/css/newDesign/create.css" rel="stylesheet" type="text/css">


<div class="space-100 hidden-xs"></div>

<div class="container">
    <div class="col-md-offset-2 col-md-8 BCW">
        <div class="row">
            <form id="priceCar">

                <fieldset id="paso3">
                <div class="regis_foto_frame">

                    <div id="previewmain" style="width: 100%">
                        <?php echo image_tag('img_publica_auto/AutoVistaAerea.png', 'size=140x140') ?>
                    </div> 

                    <a id="linkmain" href=""><i class="fa fa-edit"></i> subir foto</a>
                </div>
                </fieldset>

                       

            </form>
            <button class="btn-a-primary btn-block" id="save"  onclick="validateForm()">Guardar</button>
            <p class="alert"></p> 
        </div>
    </div>
</div>

<!-- Formularios -->
<div style="display:none">

    <form action="<?php echo url_for('cars/UploadPhoto?photo=main&width=194&height=204&file=filemain') ?>" enctype="multipart/form-data" id="formmain" method="post">
        <input id="filemain" name="filemain" type="file">
        <input type="submit">
    </form>

</div>

<div class="space-100 hidden-xs"></div>



<script>

$(document).ready(function() {

        imageUpload('#formmain', '#filemain', '#previewmain','#linkmain');       
    });


function imageUpload (form, formfile, preview, link) {

        $(formfile).change(function(e) {

            $(preview).html('');
            $(preview).html('<?php echo image_tag('loader.gif') ?>');

            $(form).ajaxForm({
                target: preview
            }).submit();
        });
        
        $(link).click(function(e) {
            $(formfile).click();
        });
    }

function validateForm() {

        var availability     = $("#availability option:selected").val();
        var carId            = $("#save").data("id"); 


        $.post("<?php echo url_for('cars/getValidateAvailability') ?>", {"availability": availability, "carId": carId}, function(r){

            
            $(".alert").removeClass("alert-a-danger");
            $(".alert").removeClass("alert-a-success");

            if (r.error) {
                $(".alert").addClass("alert-a-danger");
                $(".alert").html(r.errorMessage);
            } else {
                window.location.href = r.url_complete;
            }

        }, 'json');
    }

    


</script>