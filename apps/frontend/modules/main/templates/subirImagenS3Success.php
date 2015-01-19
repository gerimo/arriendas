<script type="text/javascript">
	//urlAjax = '<?php echo url_for("main/subirImagenS3Ajax")?>';
	//imgCargando = '<?php echo image_tag("ajax-loader.gif", "width=100", "height=100") ?>';
</script>

<?php use_javascript('uploadImage.js') ?>

<style type="text/css">
	.imagenSubida{
		width: 200px;
		height: 150px;		
	}
</style>

<?php //echo form_tag('main/subirImagenS3Ajax', array('method'=>'post', 'enctype'=>'multipart/form-data','id'=>'frm1')); ?>
<!-- <form method='post' action='http://devel.arriendas.cl/frontend/web/frontend_dev.php/main/subirImagenS3Ajax'> -->
<div class="content">
	<input type="file" id="images" name="images[]" />
    <button id="btnSubmit">Subir archivo</button>
    <div id="imagen"></div>
    <div id="response"></div>
    <button type='submit'>Enviar</button>
</div>

<!-- </form> -->