

<link href="/css/newDesign/warningUploadLicense.css" rel="stylesheet" type="text/css">

<div class="hidden-xs space-100"></div>
<div class="visible-xs space-50"></div>
<div class="row">
		<div class="col-md-offset-3 col-md-6">
			<div class="BCW">
			
					<h1>¡PAGO EXITOSO!</h1>
					<div class="contenido text-center">

						<div class="warning col-md-offset-1 col-md-10 thumbnail">
							<h2 class="warning-title">¡Advertencia!</h2>
							<i class="fa fa-exclamation-triangle fa-2"></i>
							<p>
								Para ver los datos del dueño y del arriendo debes subir la foto de tu licencia de conducir.</br><br>
								Puedes subir la foto directamente desde </br>
								<a id="linklicense" href="">  Aquí  </a>
							</p>
							<div class="text-center" id="previewlicense"></div>
						</div>

						<br>

						<div class="col-md-offset-1 col-md-10">
							<p class="Owner text-center">
								<b>Datos del dueño del auto</b>
							</p>
							<p>
								<b>Nombre:</b>    XXXXX XXXXX
								<br>
								<b>Direccion:</b> XXXXXX XXXX, XXXXXX
								<br>
								<b>Teléfono:</b>  XXXXXXXX
								<br>
								<b>Correo:</b>    XXXXXXXX@XXXX.XXX            
							</p>
							<hr>
							<p class="Owner text-center">
								<b>Datos del arriendo</b>
							</p>
							<p>  
								<b>Fecha Inicio:</b>  XX-XX-XXX XX:XX
								<br>
								<b>Fecha Termino:</b>  XX-XX-XXX XX:XX
							</p>
						</div>

					</div>
				<div class="row hidden-xs" style="margin-top: 30px">
					<div class="hidden-xs space-40"></div>
				</div>
			</div>
		</div>
</div>
<div class="hidden-xs space-100"></div>

<div style="display:none">
	<form action="<?php echo url_for('main/uploadLicense?photo=license&width=194&height=204&file=filelicense') ?>" enctype="multipart/form-data" id="formlicense" method="post">
			<input id="filelicense" name="filelicense" type="file">
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
		$('#previewlicense').hide();	
		imageUpload('#formlicense', '#filelicense', '#previewlicense','#linklicense');
	});

	function imageUpload (form, formfile, preview, link) {

		$(formfile).change(function(e) {

			$(preview).html('<?php echo image_tag('loader.gif') ?>');
			$(preview).show();
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
									$('#previewlicense').hide();
									$( this ).dialog( "close" );
								}
							}]
						});
					} else {
						location.reload();
					}
				}
			}).submit();
			$(preview).hide();
		});
		
		$(link).click(function(e) {
			e.preventDefault();
			$(formfile).click();
		});
	}

</script>