<!DOCTYPE html>
<html>

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <?php include_metas() ?>
        <?php include_title() ?>

        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
        <meta name="apple-mobile-web-app-capable" content="yes">    
        
        <link href="/css/newDesign/nblgr/bootstrap.min.css" rel="stylesheet">
        <link href="/css/newDesign/nblgr/bootstrap-responsive.min.css" rel="stylesheet">
        
        <link href="http://fonts.googleapis.com/css?family=Open+Sans:400italic,600italic,400,600" rel="stylesheet">
        <link href="/css/newDesign/nblgr/font-awesome.css" rel="stylesheet">        
        
        <link href="/css/newDesign/nblgr/ui-lightness/jquery-ui-1.10.0.custom.min.css" rel="stylesheet">
        
        <link href="/css/newDesign/nblgr/base-admin-3.css" rel="stylesheet">
        <link href="/css/newDesign/nblgr/base-admin-3-responsive.css" rel="stylesheet">
        
        <link href="/css/newDesign/nblgr/pages/dashboard.css" rel="stylesheet">   

        <link href="/css/newDesign/nblgr/custom.css" rel="stylesheet">

    </head>

    <body>

        <script src="/js/newDesign/nblgr/libs/jquery-1.9.1.min.js"></script>
        <script src="/js/newDesign/nblgr/libs/jquery-ui-1.10.0.custom.min.js"></script>
        <script src="/js/newDesign/nblgr/libs/bootstrap.min.js" type="text/javascript"></script>

        <script src="/js/newDesign/nblgr/plugins/flot/jquery.flot.js"></script>
        <script src="/js/newDesign/nblgr/plugins/flot/jquery.flot.pie.js"></script>
        <script src="/js/newDesign/nblgr/plugins/flot/jquery.flot.resize.js"></script>

        <script src="/js/newDesign/nblgr/Application.js"></script>

        <script src="/js/newDesign/nblgr/charts/area.js"></script>
        <script src="/js/newDesign/nblgr/charts/donut.js"></script>




        <?php include_component('main', 'header') ?>

        <?php echo $sf_content ?>                
    </body>
</html>