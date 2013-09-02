<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
  <head>
    <?php include_http_metas() ?>
    <?php include_metas() ?>
    <?php include_title() ?>
    <link rel="shortcut icon" href="/favicon.ico" />
    <?php include_stylesheets() ?>
    <?php include_javascripts() ?>

<script src="http://localhost/arriendas_backend/web/chosen/chosen.jquery.js" type="text/javascript"></script>

<link rel="stylesheet" href="http://localhost/arriendas_backend/web/chosen/chosen.css"/>
    
    <script>
      
      function subir_padron(idPadron,accion) {
        window.open("car/subirPadron/?id=" + idPadron + "&accion=" + accion ,"ventana","width=400,height=150,toolbars=no,scrollbars=no");
      }
      
      function refrescar() {
        location.reload();
      }
      
    </script>
    
    <style>
      
      #menu_principal {
        font-family: Verdana;
        font-size: 14px;
        color: black;
        margin-left: 50px;
      }
      
      #menu_principal a {
        text-decoration: none;
      }
      
      #menu_principal a:hover {
        text-decoration: underline;
      }
      
    </style>
    
  </head>
  <body>
    <div id="titulo_principal" style="width:100%; text-align: center;">
      <h3 style="font-family: Verdana; font-size: 24px; color: #2E6E9E;">Backend Prueba</h3>
    <div style="width:100%; text-align: center;">
      <nav id="menu_principal">
      <span><?php echo link_to('Reserve', 'reserve') ?></span>
      <span><?php echo link_to('User', 'user') ?></span>
      <span><?php echo link_to('Car', 'car') ?></span>
      <span><?php echo link_to('Dashboard', 'dashboard') ?></span>
    </nav>
    </div>
    <div style="width:100%; text-align: center;">
    <nav id="menu_principal">
      <span><?php echo link_to('Marcas', 'brand') ?></span>
      <span><?php echo link_to('Modelo', 'model') ?></span>
      <span><?php echo link_to('Users2Excel','users2Excel/export') ?></span>
    </nav>
    </div>
    <?php echo $sf_content ?>
    
    <script type="text/javascript"> $(".chzn-select").chosen(); $(".chzn-select-deselect").chosen({allow_single_deselect:true}); </script>
  </body>
</html>
