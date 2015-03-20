<!DOCTYPE html>
<html>

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <?php //include_javascripts() ?>
        <?php //include_stylesheets() ?>
        <?php include_http_metas() ?>
        <?php include_metas() ?>
        <?php include_title() ?>

        <link href="http://arriendas.assets.s3.amazonaws.com/images/favicon.ico" rel="shortcut icon" type="image/x-icon">

        <!-- Metas -->
        <!-- Al publicar en una red social un link de la página el link se publicara de la siguiente forma -->
        <meta property="og:description" content="Arriendo de autos por hora con seguro, asistencia de viaje y TAGs incluídos. Busca un auto por ubicación o por precio. Rent a car Vecino.">
        <meta property="og:title" content="Rent a car vecino. Arriendo de autos, cerca de ti - Arriendas.cl">
        <meta property="og:type" content="website">
        <meta property="og:image" content="<?php echo image_path('newDesign/logo.jpg', 'absolute=true') ?>">
        <meta property="og:url" content="http://www.arriendas.cl<?php echo sfContext::getInstance()->getController()->genUrl(sfContext::getInstance()->getRouting()->getCurrentInternalUri());?>">
        <meta property="og:site_name" content="Rent a car vecino. Arriendo de autos, cerca de ti - Arriendas.cl">

        <!-- JQuery UI -->
        <link href="/css/newDesign/jquery-ui/jquery-ui.min.css" rel="stylesheet" type="text/css">
        <link href="/css/newDesign/jquery-ui/jquery-ui.structure.min.css" rel="stylesheet" type="text/css">
        <link href="/css/newDesign/jquery-ui/jquery-ui.theme.min.css" rel="stylesheet" type="text/css">
        
        <!-- Bootstrap -->
        <link href="/css/newDesign/bootstrap.min.css" rel="stylesheet" type="text/css">

        <!-- Font Awesome -->
        <link href="/css/newDesign/font-awesome/font-awesome.min.css" rel="stylesheet" type="text/css">

        <!-- Complementos -->
        <link href="/css/newDesign/datetimepicker/jquery.datetimepicker.css" rel="stylesheet" type="text/css">
        <link href="/css/newDesign/slick/slick.css" rel="stylesheet" type="text/css">
        <link href="/css/newDesign/raty/jquery.raty.css" rel="stylesheet" type="text/css">
        <link href="/css/newDesign/datatables/jquery.dataTables.min.css" rel="stylesheet" type="text/css">
        <!-- <link href="/css/newDesign/datatablesp/jquery.dataTables_themeroller.css" rel="stylesheet" type="text/css"> -->
        <link href="/css/newDesign/datatables/dataTables.responsive.css" rel="stylesheet" type="text/css">

        <!-- Estilos para todo el proyecto -->
        <link href="/css/newDesign/arriendas.css" rel="stylesheet" type="text/css">

        <!-- Fonts -->
        <link href="http://fonts.googleapis.com/css?family=Roboto:300,350,400,500,600,700,800" rel="stylesheet" type="text/css">
        <link href="http://fonts.googleapis.com/css?family=Open+Sans:400,500,600,700,800,400italic,700italic" rel="stylesheet" type="text/css">
    </head>

    <body>

        <!-- JQuery -->
        <script src="/js/newDesign/jquery-2.1.3.min.js" type="text/javascript"></script>

        <!-- JQuery UI -->
        <script src="/js/newDesign/jquery-ui/jquery-ui.min.js" type="text/javascript"></script>
        
        <!-- Bootstrap -->
        <script src="/js/newDesign/bootstrap.min.js" type="text/javascript"></script>

        <!-- Complementos -->
        <script src="/js/newDesign/datetimepicker/jquery.datetimepicker.js" type="text/javascript"></script>
        <script src="/js/newDesign/slick/slick.min.js" type="text/javascript"></script>
        <script src="/js/newDesign/raty/jquery.raty.js" type="text/javascript"></script>
        <script src="/js/newDesign/number/jquery.number.min.js" type="text/javascript"></script>
        <script src="/js/newDesign/jquery-form/jquery.form.min.js" type="text/javascript"></script>
        <script src="/js/newDesign/datatables/jquery.dataTables.min.js" type="text/javascript"></script>
        <script src="/js/newDesign/datatables/dataTables.responsive.min.js" type="text/javascript"></script>
        <script src="/js/newDesign/formToWizard/formToWizard.js" type="text/javascript"></script>

        <!-- Utils -->
        <script src="/js/newDesign/utils.js" type="text/javascript"></script>

        <!-- Arriendas -->
        <script src="/js/newDesign/arriendas.js" type="text/javascript"></script>

        <?php include_component('main', 'header') ?>

        <?php echo $sf_content ?>

        <?php if($this->context->getActionName()!="reserve"): ?>

            <?php include_component('main', 'footer') ?>

        <?php endif ?>

        <?php if ($_SERVER['HTTP_HOST'] == "m.arriendas.cl"): ?>
            
            <!-- Google Analytics -->
            <script>
                (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
                (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
                m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
                })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

                ga('create', 'UA-35908733-2', 'auto');
                ga('send', 'pageview');
            </script>

            <!-- SnapEngage -->
            <script type="text/javascript">           
                (function() {
                var se = document.createElement('script'); se.type = 'text/javascript'; se.async = true;
                se.src = '//storage.googleapis.com/code.snapengage.com/js/42f8a363-b7b1-47f8-8148-0175fa82193c.js';
                var done = false;
                se.onload = se.onreadystatechange = function() {
                if (!done&&(!this.readyState||this.readyState==='loaded'||this.readyState==='complete')) {
                done = true;
                /* Place your SnapEngage JS API code below */
                /* SnapEngage.allowChatSound(true); Example JS API: Enable sounds for Visitors. */
                }
                };
                var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(se, s);
                })();
            </script>
        <?php endif ?>        
    </body>
</html>