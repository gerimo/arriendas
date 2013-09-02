<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="es" xml:lang="es">
    <head>
	<meta name="google-site-verification" content="HxfRs3eeO-VFQdT1_QX7j8hfHPNMh-EIxA7BOyNtHiU" />
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <link href="http://arriendas.assets.s3.amazonaws.com/images/favicon.ico" rel="shortcut icon" type="image/x-icon" />

            <?php /* include_http_metas() */ ?>
            <?php include_metas() ?>
            <?php
			if (stripos($_SERVER['SERVER_NAME'], "arrendas") !== FALSE)
				echo "<title>Arrendas</title>";
			else
				echo "<title>Arriendas</title>";
            ?>
            <?php include_stylesheets() ?>
            <script src="http://maps.google.com/maps/api/js?sensor=false"></script>
            <script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false&libraries=places"></script>
            
            <script src="//cdn.optimizely.com/js/241768225.js"></script>

            <?php include_javascripts() ?>
			<script type="text/javascript">
				$(document).ready(function() {
					$(".fancybox").fancybox();
				});
			</script>
			
			<style>
				
				.footer_social p { width: 22px; height: 22px; background-color: transparent; }
			</style>
    <script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-35908733-1']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>
			
    </head>
			<?php echo $sf_content ?>
    </body>
</html>



