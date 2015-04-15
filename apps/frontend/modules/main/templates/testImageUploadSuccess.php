<div class="row">
    <div class="col-md-offset-2 col-md-8">

    	<div class="hidden-xs space-100"></div>
    	<div class="row BCW">
    		<div class="text-center">
    			<a id="linkmain" href=""><i class="fa fa-edit"></i> Subir foto</a>
    		</div>
    		<div class="hidden-xs space-30"></div>
    		<div class="text-center" id="previewmain"></div>
    	</div>
    	<div class="hidden-xs space-20"></div>

    </div>
</div>

<div style="display:none">

    <form action="<?php echo url_for('main/uploadImages?photo=main&file=filemain') ?>" enctype="multipart/form-data" id="formmain" method="post">
        <input id="filemain" name="filemain" type="file">
        <input type="submit">
    </form>
</div>

<div style="display:none">
    <div id="dialog-alert" class="text-center" title="">
        <p></p>
    </div>
</div>

<script type="text/javascript">

    $(document).ready(function() {

        imageUpload('#formmain', '#filemain', '#previewmain','#linkmain');

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