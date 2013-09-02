<h1>Subir Foto <?php echo $accion; ?></h1>
<form action="<?php echo $sf_request->getUriPrefix().$sf_request->getRelativeUrlRoot(); ?>/index.php/car/uploadFoto/?idAuto=<?php echo $idAuto; ?>&accion=<?php echo $accion; ?>" method="post" enctype="multipart/form-data">
    Seleccionar Imagen: <input type="file" name="archivo"/><br />
      <input type="submit" value="Subir"/>
</form>