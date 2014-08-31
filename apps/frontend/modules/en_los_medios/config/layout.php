<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="es" xml:lang="es">
    <head>
	<meta name="google-site-verification" content="HxfRs3eeO-VFQdT1_QX7j8hfHPNMh-EIxA7BOyNtHiU" />
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
            <?php /* include_http_metas() */ ?>
            <?php include_metas() ?>
            <?php
			if (stripos($_SERVER['SERVER_NAME'], "arrendas") !== FALSE)
				echo "<title>Arrendas</title>";
			else
				echo "<title>Arriendas</title>";
            ?>
            <link rel="shortcut icon" href="/favicon.ico" />
            <?php include_stylesheets() ?>
            <script src="http://maps.google.com/maps/api/js?sensor=false"></script>
            <script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false&libraries=places"></script>
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

    <body>
    	<!-- BANNER TOP 
        <?php //if (sfContext::getInstance()->getUser()->isAuthenticated() && inModuleAndAction("main", "index")): ?>
            <div class="slideshow_invitation" style="display: none">
                <a href="<?php echo url_for('invitation/index'); ?>">Gana 10.000 pesos en cr&eacute;dito invitando tus amigos en </a>
                <?php echo image_tag("/images/img_ui/footer_ico_fb.png") ?>
            </div>
        <?php //endif; ?>
        -->
        <!-- login facebook -->
        <div id="fb-root"></div>
        <script>
			window.fbAsyncInit = function() {
				FB.init({
					appId : '213116695458112/8d8f44d1d2a893e82c89a483f8830c25',
					status : true,
					cookie : true,
					xfbml : true
				});
			}; ( function() {
					var e = document.createElement('script');
					e.async = true;
					e.src = document.location.protocol + '//connect.facebook.net/en_US/all.js';
					document.getElementById('fb-root').appendChild(e);
				}());

			function timeMsg() {
				var t = setTimeout("slide()", 5000);
			}

			function slide() {
				$(".slideshow_invitation").slideDown('slow').delay(7000).slideUp(400);
			}

			var mouse_is_inside = false;
			$(document).ready(function() {
				timeMsg();

				$('.link_logout').click(function() {
					$('.submenu_logout').css('display', 'block');
				});
				$('.submenu_logout').mouseleave(function() {
					$(this).css('display', 'none');
				});

				$('.submenu_logout').hover(function() {
					mouse_is_inside = true;
				}, function() {
					mouse_is_inside = false;
				});

				$("body").mouseup(function() {
					if (!mouse_is_inside)
						$('.submenu_logout').hide();
				});
				$('.submenu_logout a').click(function() {
					$('.submenu_logout').css('display', 'none');
				});
			});

        </script>

        <!-- login facebook -->
        <div class="body_bg">
            <?php

			function inModuleAndAction($module, $action) {
				return (sfContext::getInstance() -> getActionName() == $action && sfContext::getInstance() -> getModuleName() == $module);
			}

			$colors = array();
			$colors[] = array("00aff0", "header_menu_1.png");
			//$colors[] = array("83c028", "header_menu_2.png");
			//$colors[] = array("9334b1", "header_menu_3.png");

			$number = 0;
			//            if (inModuleAndAction("main", "index")) {
			//                $number = 0;
			//            } else {
			//                $number = rand(0, 2);
			//            }
            ?>

            
           
            <div class="header">
                <div class="header_contenido">
                
                    <div class="header_logo">
                        <?php echo link_to(image_tag("arriendas_c.png", 'size=120x18'), 'main/index') ?>
                    </div>
                    <ul class="footer_social">
                        <li class="footer_fb" title="Siguenos en Facebook">
                            <?php if (stripos($_SERVER['SERVER_NAME'], "arrendas") !== FALSE): ?>
                                <a href="http://www.facebook.com/Arrendas" TARGET="_blank" class="fb" title="Siguenos en Facebook"><p></p>
                                <?php else: ?>
                                <a href="http://www.facebook.com/arriendaschile" TARGET="_blank" class="fb" title="Siguenos en Facebook"><p></p><?php endif; ?></a>
                        </li>
                        <li class="footer_tw" title="Siguenos en Twitter">
                            <?php if (stripos($_SERVER['SERVER_NAME'], "arrendas") !== FALSE): ?>
                                <a href="http://www.twitter.com/arrendas" TARGET="_blank" class="tw" title="Siguenos en Twitter"><p></p>
                                <?php else: ?>
                                <a href="http://www.twitter.com/arriendas" TARGET="_blank" class="tw" title="Siguenos en Twitter"><p></p>
                                <?php endif; ?></a>
                        </li>
                    </ul>
                    
                    <div class="top_header_menu">
                        <a class="item_thm" href="<?php echo url_for('main/valueyourcar') ?>">&iquest;Cu&aacute;nto puedo ganar con mi auto?</a>
                        <a class="item_thm fancybox" data-fancybox-type="iframe" href="http://player.vimeo.com/video/45668172?title=0&amp;byline=0&amp;portrait=0&amp;color=ffffff">Ver Video explicativo</a>
			<a class="item_thm fancybox" href="https://www.facebook.com/dialog/apprequests?app_id=213116695458112&message=Invita amigos tuyos a sumarse a Arriendas.cl y gana una comisión por las ventas quer ellos hagan&redirect_uri=https://www.arriendas.cl/main/invitaciones"
			onclick="return confirm('Programa de Referidos 2013\n\nGana 7% en comisiones sobre todo lo que ganen o gasten durante el 2013 tus amigos referidos a través de la aplicación esta promoción\nAdicionalmente, gana 1% sobre todo lo que ganen o gasten durante 2013 los amigos invitados por' +
					 'tus referidos hasta la primera línea usando esta aplicación\n\nSe repartirá y prorrateará entre los usuarios hasta el 8% en comisiones por transacción');">Invita Amigos</a> 
                    </div>
                    <?php if (!sfContext::getInstance()->getUser()->isAuthenticated()): ?>
                     <a href="<?php echo url_for('main/login') ?>" id="logout" name="logout"  class="menu_btn_login">Login</a>
					 <a href="<?php echo url_for('main/register') ?>" class="menu_btn_registro">Registro</a>
					
					<!--  inicio botones login / registro  -->
                	<div class="loadimages"><span class="img1"></span><span class="img2"></span></div>

                        

                        <?php else: ?>
                            <div class="cuadro_logueado">
                                <?php
                                if ($sf_user->getAttribute('picture_url') != null) {
                                    if ($sf_user->getAttribute('fb')) {
                                        echo image_tag($sf_user->getAttribute('picture_url'));
                                    } else {
                                        echo image_tag('users/' . $sf_user->getAttribute('picture_url'));
                                    }
                                } else
                                    echo image_tag('img_registro/tmp_user_foto.png', 'size=23x23')
                                ?>
                                <div class="group_name">                               
                                	<span class="bienvenido">Bienvenido</span><br/>
                                <span class="name_user"><?php echo $sf_user->getAttribute('name') ?></span>
                                <a class="link_logout">
                                    <?php echo image_tag('bt_user_opt_v2.jpg', 'id=logout') ?>
                                </a>
                                </div> 
                                <div class="submenu_logout">
                                    <dl>
                                    	<dt><a href="<?php echo url_for('profile/edit') ?>">Mi Perfil</a></dt>
                                        <dt><a href="<?php echo url_for('main/logout') ?>">Logout</a></dt>
                                    </dl>
                                </div>
                            </div>
                            
                            <ul class="menu_social" >
                                <li><?php include_component("messages", "alerts", array("color" => $colors[$number][0])); ?></li>
    						<!-- <li><a href="<?php echo url_for('main/logout') ?>" style="color: white;font-weight: bold;text-decoration: none;text-shadow: 0px 1px 0px #0873CE;font-size: 14px;"  id="logout" name="logout" >Logout</a></li>-->
                            </ul>
                        <?php endif; ?>
                <!--  fin botones login / registro -->
					
					
                </div><!-- header_contenido -->
                
               <div class="separador"></div>
                <!-- inicio header_menu -->
                    <div class="header_menu"> 
                        <ul class="menu_1">
                            <li><a href="http://player.vimeo.com/video/45668172?title=0&byline=0&portrait=0&color=ffffff" target="_blank" class="item_1<?php
							if (inModuleAndAction("main", "index")) {
								echo "_in";
							}
                        ?>" title="Inicio - Cómo funciona Arriendas.cl">
                            <span>Cómo funciona Arriendas.cl</span></a></li>
                            <li><a href="<?php echo url_for('profile/index') ?>" class="item_2<?php
							if (inModuleAndAction("profile", "index")) {
								echo "_in";
							}
                        ?>" title="Sube tu Auto">
                                    <span>Sube tu Auto</span></a></li>
                                    
							<li><a href="<?php echo url_for('profile/pedidos') ?>" class="item_5<?php
							if (inModuleAndAction("profile", "pedidos")) {
								echo "_in";
							}
 							?>" title="Reservas de mis Autos"><span>Reservas de mis Autos</span></a></li>                                    

                            <li><a href="https://arriendascl.zendesk.com/anonymous_requests/new" class="item_2<?php
							if (inModuleAndAction("main", "contact")) {
								echo "_in";
							}
                        ?>" target="_blank" title="Contacto"><span>Contacto</span></a></li>
							<li><a href="https://arriendascl.zendesk.com/forums" target="_blank" class="item_2"><span>Ayuda</span></a></li>
                            
                        </ul><!-- menu_1 -->

                    </div><!-- Fin header_menu -->
                <div class="separador"></div>
            </div><!-- header -->

			<?php echo $sf_content ?>

            <!-- footer -->
            <div class="clear"></div>
            <div class="footer">
                <div class="footer_contenido">

                    <div class="footer_enlaces">
                        <ul class="enlaces_box">
                            <li class="enlaces_titulo">Usuarios</li>
                            <li><a href="<?php echo url_for('main/login') ?>" title="Registrate">Registrate</a></li>
                            <li><a href="<?php echo url_for('main/register') ?>" title="Login">Login</a></i>
                            <li><a href="<?php echo url_for('profile/edit') ?>" title="Tu Perfil">Tu Perfil</a></i>
                        </ul>
                        <ul class="enlaces_box">
                            <li class="enlaces_titulo">Acerca de Arriendas</li>
                            <li><a href="<?php echo url_for('main/compania') ?>" title="La compa&ntilde;&iacute;a">La compa&ntilde;&iacute;a</a></li>
                            <li><a href="<?php echo url_for('http://www.arriendas.cl/docs/poliza_bci.pdf') ?>" target="_blank" title="P&oacute;liza de Seguro">P&oacute;liza de Seguro</a></li>
			    <li><a href="<?php echo url_for('main/terminos') ?>" title="Terminos y Condiciones">T&eacute;rminos y Condiciones</a></i>
                            <li><a href="https://arriendascl.zendesk.com/home" target="_blank" title="Contacto">Contacto</a></i>
                        </ul>
                        
                        <ul class="enlaces_box">
                            <li class="enlaces_titulo">Acciones</li>
                            <li><a href="<?php echo url_for('profile/addCar') ?>" title="Sube tu auto">Sube tu auto</a></li>
                            <li><a href="<?php echo url_for('profile/pedidos') ?>" title="Busca un auto">Busca un auto</a></li>
                        </ul>

                    </div><!-- footer_enlaces -->

                    <p class="footer_copy">Arriendas.cl - Encomenderos 253, Las Condes, Santiago. - (2) 2897 4747</p>

                </div><!-- /footer_contenido -->
            </div><!-- /footer -->

        </div><!-- body_bg -->
    </body>
</html>



