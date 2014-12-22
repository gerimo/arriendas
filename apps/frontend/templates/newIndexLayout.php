<!doctype html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <?php //include_metas() ?>
        <?php //include_title() ?>
        <?php //include_stylesheets() ?>
        
        <link href="css/newDesign/bootstrap.min.css" rel="stylesheet" type="text/css">
        <link href="css/newDesign/font-awesome/font-awesome.min.css" rel="stylesheet" type="text/css">
        <link href="css/newDesign/datetimepicker/jquery.datetimepicker.css" rel="stylesheet" type="text/css">
        <link href="css/newDesign/slick/slick.css" rel="stylesheet" type="text/css">
        <link href="css/newDesign/index.css" rel="stylesheet" media="all">
        <link href='http://fonts.googleapis.com/css?family=Roboto:400,300,700' rel='stylesheet' type='text/css'>
        <link href='http://fonts.googleapis.com/css?family=Open+Sans:400,600,700,700italic,800,400italic' rel='stylesheet' type='text/css'>
    </head>

    <body>

        <!-- JQuery -->
        <script src="js/newDesign/jquery-2.1.3.min.js" type="text/javascript"></script>

        <!-- Bootstrap -->
        <script src="js/newDesign/bootstrap.min.js" type="text/javascript"></script>

        <!-- Complementos -->
        <script src="js/newDesign/datetimepicker/jquery.datetimepicker.js" type="text/javascript"></script>
        <script src="js/newDesign/slick/slick.min.js" type="text/javascript"></script>
        <script src="js/newDesign/index.js" type="text/javascript"></script>

        <!-- Google Maps -->
        <script src="http://maps.googleapis.com/maps/api/js?libraries=places&amp;sensor=false" type="text/javascript"></script>
        <script src="js/newDesign/markerclusterer.js" type="text/javascript"></script>

        <?php echo $sf_content ?>

        <?php include_partial('global/footer') ?>
    </body>
</html>