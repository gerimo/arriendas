

<link href="/css/newDesign/warningUploadLicense.css" rel="stylesheet" type="text/css">

<div class="hidden-xs space-100"></div>
<div class="visible-xs space-50"></div>
<div class="row">
		<div class="col-md-offset-3 col-md-6">
			<div class="BCW">
			
					<h1>¡PAGO EXITOSO!</h1>
					<div class="contenido text-center">

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
	<form action="<?php echo url_for('main/uploadLicenseWarning?photo=license&width=194&height=204&file=filelicense') ?>" enctype="multipart/form-data" id="formlicense" method="post">
			<input id="filelicense" name="filelicense" type="file">
			<input type="submit">
	</form>
</div>

<div style="display:none">
	<div id="dialog-alert" class="text-center" title="">
		<p></p>
	</div>
</div>

<div style="display:none">
	<div id="dialog-alert" class="text-center" title="">
		<p></p>
	</div>
</div>

<script type="text/javascript">

	$(document).ready(function() {
		$("#dialog-alert p").html("Para continuar primero debes subir la foto de tu licencia de conducir.");
		$("#dialog-alert").attr("title", "¡Advertencia!");
		$("#dialog-alert").dialog({
			dialogClass: "no-close",
			modal: true,
			buttons: [{
				text: "Subir foto",
				'id': 'linklicense',
				click: function() {
				}
			}]
		});
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
					console.error("asd");
					if (r.error) {

						$("#dialog-alert p").html(r.errorMessage);
						$("#dialog-alert").attr("title", "¡Advertencia!");
						$("#dialog-alert").dialog({
							modal: true,
							dialogClass: "no-close",
							buttons: [{
								text: "aceptar",
								click: function() {
                                    location.reload();
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