<link href="/css/newDesign/create.css" rel="stylesheet" type="text/css">


<div class="space-100"></div>

<div class="container">
    <div class="col-xs-12 col-md-offset-2 col-md-8 BCW">
        <div class="row">

                <fieldset id="paso3">

                <h1>Foto Del Vehículo</h1>
                <div class="space-50"></div> 
                    <!-- foto perfil -->
                    <div id="previewPhotoCar" class="photo text-center col-xs-12 col-sm-6 col-md-6" style="margin-bottom:10%">
                    <label class="col-md-12">Foto Perfil</label>
                        <?php if ($Car->getFotoPerfil() == null): ?>
                            <?php echo image_tag('img_asegura_tu_auto/AutoVistaAerea.png') ?>
                        <?php else: ?>
                            <?php echo image_tag($Car->getFotoPerfil()) ?>
                        <?php endif ?>
                        <a id="linkPhotoCar" href=""><i class="fa fa-edit"></i> subir</a>
                    </div>

                    <!-- foto Seguro accesorios -->
                    <div id="previewPhotoCarAccessory" class="photo text-center col-xs-12 col-sm-6 col-md-6" style="margin-bottom:10%">
                        <label class="col-md-12">Foto Accesorios</label>
                            <?php if ($Car->getAccesoriosSeguro() == null): ?>
                                <?php echo image_tag('img_asegura_tu_auto/accesorio2.png') ?>
                            <?php else: ?>
                                <?php echo image_tag($Car->getAccesoriosSeguro()) ?>
                            <?php endif ?>
                        <a id="linkPhotoCarAccessory" href=""><i class="fa fa-edit"></i> subir</a>
                    </div>

                    <!-- foto Seguro frente -->
                    <div id="previewPhotoCarFront" class="photo text-center col-xs-12 col-sm-6 col-md-6" style="margin-bottom:10%">
                    <label class="col-md-12">Foto Frente</label>
                        <?php if ($Car->getSeguroFotoFrente() == null): ?>
                            <?php echo image_tag('img_asegura_tu_auto/AutoFrente.png') ?>
                        <?php else: ?>
                            <?php echo image_tag($Car->getSeguroFotoFrente()) ?>
                        <?php endif ?>
                    <a id="linkPhotoCarFront" href=""><i class="fa fa-edit"></i> subir</a>
                    </div>

                <div class="space-30"></div> 
                
                    <!-- foto Seguro Costado Derecho -->
                    <div id="previewPhotoCarSideRight" class="photo text-center col-xs-12 col-sm-6 col-md-6" style="margin-bottom:10%">
                    <label class="col-md-12">Foto Costado Derecho</label>
                        <?php if ($Car->getSeguroFotoCostadoDerecho() == null): ?>
                            <?php echo image_tag('img_asegura_tu_auto/AutoCostadoDerch.png') ?>
                        <?php else: ?>
                            <?php echo image_tag($Car->getSeguroFotoCostadoDerecho()) ?>
                        <?php endif ?>
                    <a id="linkPhotoCarSideRight" href=""><i class="fa fa-edit"></i> subir</a>
                    </div>

                    <!-- foto Seguro Costado Izquierdo -->
                    <div id="previewPhotoCarSideLeft" class="photo text-center col-xs-12 col-sm-6 col-md-6" style="margin-bottom:10%">
                    <label class="col-md-12">Foto Costado Izquierdo</label>
                        <?php if ($Car->getSeguroFotoCostadoIzquierdo() == null): ?>
                            <?php echo image_tag('img_asegura_tu_auto/AutoCostadoIzq.png') ?>
                        <?php else: ?>
                            <?php echo image_tag($Car->getSeguroFotoCostadoIzquierdo()) ?>
                        <?php endif ?>
                    <a id="linkPhotoCarSideLeft" href=""><i class="fa fa-edit"></i> subir</a>
                    </div>

                    <!-- foto Seguro Trasero Derecho -->
                    <div id="previewPhotoCarBackRight" class="photo text-center col-xs-12 col-sm-6 col-md-6" style="margin-bottom:10%">
                    <label class="col-md-12">Foto Trasero Derecho</label>
                        <?php if ($Car->getSeguroFotoTraseroDerecho() == null): ?>
                            <?php echo image_tag('img_asegura_tu_auto/AutoDerechTra.png') ?>
                        <?php else: ?>
                            <?php echo image_tag($Car->getSeguroFotoTraseroDerecho()) ?>
                        <?php endif ?>
                    <a id="linkPhotoCarBackRight" href=""><i class="fa fa-edit"></i> subir</a>
                    </div>


                    <!-- foto Seguro Trasero Izquierdo -->
                    <div id="previewPhotoCarBackLeft" class="photo text-center col-xs-12 col-sm-6 col-md-6" style="margin-bottom:10%">
                    <label class="col-md-12">Foto Trasero Izquierdo</label>
                        <?php if ($Car->getSeguroFotoTraseroIzquierdo() == null): ?>
                            <?php echo image_tag('img_asegura_tu_auto/AutoIzqFrent.png') ?>
                        <?php else: ?>
                            <?php echo image_tag($Car->getSeguroFotoTraseroIzquierdo()) ?>
                        <?php endif ?>
                    <a id="linkPhotoCarBackLeft" href=""><i class="fa fa-edit"></i> subir</a>
                    </div>

                </fieldset>

                       

            <div class="space-20"></div>
            <div class="col-xs-12 col-sm-offset-8 col-sm-4 col-md-offset-8 col-md-4">
                <button class="btn-a-primary btn-block" name="save" onclick="location.href='<?php echo url_for('cars') ?>'">Finalizar</button>
                <p class="alert"></p> 
            </div>
        </div>
    </div>
</div>

<!-- Formularios -->
<div style="display:none">
    <form action="<?php echo url_for('cars/uploadPhoto?photo=cars&width=194&height=204&file=filePhotoCar') ?>" enctype="multipart/form-data" id="formPhotoCar" method="post">
        <input id="filePhotoCar" name="filePhotoCar" type="file">
        <input type="submit">
    </form>

    <form action="<?php echo url_for('cars/uploadPhotoAccessory?photo=cars&width=194&height=204&file=filePhotoCarAccessory') ?>" enctype="multipart/form-data" id="formPhotoCarAccessory" method="post">
        <input id="filePhotoCarAccessory" name="filePhotoCarAccessory" type="file">
        <input type="submit">
    </form>

    <form action="<?php echo url_for('cars/uploadPhotoFront?photo=cars&width=194&height=204&file=filePhotoCarFront') ?>" enctype="multipart/form-data" id="formPhotoCarFront" method="post">
        <input id="filePhotoCarFront" name="filePhotoCarFront" type="file">
        <input type="submit">
    </form>

    <form action="<?php echo url_for('cars/uploadPhotoSideRight?photo=cars&width=194&height=204&file=filePhotoCarSideRight') ?>" enctype="multipart/form-data" id="formPhotoCarSideRight" method="post">
        <input id="filePhotoCarSideRight" name="filePhotoCarSideRight" type="file">
        <input type="submit">
    </form>

    <form action="<?php echo url_for('cars/uploadPhotoSideLeft?photo=cars&width=194&height=204&file=filePhotoCarSideLeft') ?>" enctype="multipart/form-data" id="formPhotoCarSideLeft" method="post">
        <input id="filePhotoCarSideLeft" name="filePhotoCarSideLeft" type="file">
        <input type="submit">
    </form>

    <form action="<?php echo url_for('cars/uploadPhotoBackRight?photo=cars&width=194&height=204&file=filePhotoCarBackRight') ?>" enctype="multipart/form-data" id="formPhotoCarBackRight" method="post">
        <input id="filePhotoCarBackRight" name="filePhotoCarBackRight" type="file">
        <input type="submit">
    </form>

    <form action="<?php echo url_for('cars/uploadPhotoBackLeft?photo=cars&width=194&height=204&file=filePhotoCarBackLeft') ?>" enctype="multipart/form-data" id="formPhotoCarBackLeft" method="post">
        <input id="filePhotoCarBackLeft" name="filePhotoCarBackLeft" type="file">
        <input type="submit">
    </form>

    <div id="dialog-alert" title="">
        <p></p>
    </div>
</div>

<div class="space-100 hidden-xs"></div>


<script>

    $(document).ready(function() {

        imageUpload('#formPhotoCar', '#filePhotoCar', '#previewPhotoCar','#linkPhotoCar');
        imageUpload('#formPhotoCarAccessory', '#filePhotoCarAccessory', '#previewPhotoCarAccessory','#linkPhotoCarAccessory');
        imageUpload('#formPhotoCarFront', '#filePhotoCarFront', '#previewPhotoCarFront','#linkPhotoCarFront');
        imageUpload('#formPhotoCarSideRight', '#filePhotoCarSideRight', '#previewPhotoCarSideRight','#linkPhotoCarSideRight');
        imageUpload('#formPhotoCarSideLeft', '#filePhotoCarSideLeft', '#previewPhotoCarSideLeft','#linkPhotoCarSideLeft');
        imageUpload('#formPhotoCarBackRight', '#filePhotoCarBackRight', '#previewPhotoCarBackRight','#linkPhotoCarBackRight');
        imageUpload('#formPhotoCarBackLeft', '#filePhotoCarBackLeft', '#previewPhotoCarBackLeft','#linkPhotoCarBackLeft');

    });

    function imageUpload (form, formfile, preview, link) {

        $(formfile).change(function(e) {
            
            $(preview).html('<?php echo image_tag('loader.gif') ?>');

            $(form).ajaxForm({
                dataType: "json",
                target: preview,
                success: function(r){
                    if (r.error) {
                        $("#dialog-alert p").html(r.errorMessage);
                        $("#dialog-alert").attr("title", "Problemas al subir la imagen");
                        $("#dialog-alert").dialog({
                            buttons: [{
                                text: "Aceptar",
                                click: function() {
                                    $( this ).dialog( "close" );
                                }
                            }]
                        });
                    } else {
                        location.reload();
                    }
                }
            }).submit();
        });
        
        $(link).click(function(e) {
            e.preventDefault();
            $(formfile).click();
        });
    }


    


</script>