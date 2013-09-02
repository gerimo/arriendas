<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="es" xml:lang="es">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	<link href="http://www.arriendas.cl/images/SubeTuAuto.ico" rel="shortcut icon" type="image/x-icon" />
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

        <div class="body_bg">

            <script>
                var mouse_is_inside = false;
            $(document).ready(function(){
                $('.link_logout').click(function(){
                    $('.submenu_logout').css('display','block');
                });
                $('.submenu_logout').mouseleave(function(){
                    $(this).css('display','none');
                });
                $('.submenu_logout').hover(function(){ 
                        mouse_is_inside=true; 
                    }, function(){ 
                        mouse_is_inside=false; 
                    });
                    
                    $("body").mouseup(function(){ 
                        if(! mouse_is_inside) $('.submenu_logout').hide();
                    });
                $('.submenu_logout a').click(function(){
                    $('.submenu_logout').css('display','none');
                });
                });
        </script>
            <style>

                .submenu {
                    left: 520px;
                }
				
				
				.footer_enlaces { width:930px;}
				
            </style>



            <?php

            function inModuleAndAction($module, $action) {
                return (sfContext::getInstance()->getActionName() == $action && sfContext::getInstance()->getModuleName() == $module);
            }

            $colors = array();
            $colors[] = array("00aff0", "header_menu_1.png");
//            $colors[] = array("83c028", "header_menu_2.png");
//            $colors[] = array("9334b1", "header_menu_3.png");

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
	                <?php echo link_to(image_tag("arriendas_c.png", 'size=120x18'),'main/index') ?>
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
		             <a class="item_thm" href="<?php echo url_for('main/valueyourcar')?>">&iquest;Cu&aacute;nto puedo ganar con mi auto?</a>
		             <a class="item_thm fancybox" data-fancybox-type="iframe" href="http://player.vimeo.com/video/45668172?title=0&amp;byline=0&amp;portrait=0&amp;color=ffffff">Ver Video Explicativo</a>
		        </div>
		        
		        <?php if (!sfContext::getInstance()->getUser()->getAttribute("logged")): ?>

                            <a href="<?php echo url_for('main/register') ?>" class="menu_btn_registro"></a>111
                            <ul class="menu_social" style="right:25px; top:7px;">
                                <li><a href="<?php echo url_for('main/login') ?>" style="color: white;font-weight: bold;text-decoration: none;text-shadow: 0px 1px 0px #0873CE;font-size: 10px;"  id="logout" name="logout" >Login</a></li>
                            </ul>
<?php else: ?>
                            
                            <div class="cuadro_logueado">
                                    <?php
                                    if ($sf_user->getAttribute('picture_url') != null){
                                        if($sf_user->getAttribute('fb')){
                                            echo image_tag($sf_user->getAttribute('picture_url'));
                                        } else {
                                            echo image_tag('users/'.$sf_user->getAttribute('picture_url'));
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
                                <ul class="menu_social">
                                <li><?php include_component("messages", "alerts", array("color" => $colors[$number][0])); ?></li>
<!--                                <li><a href="<?php echo url_for('main/logout') ?>" style="color: white;font-weight: bold;text-decoration: none;text-shadow: 0px 1px 0px #0873CE;font-size: 14px;"  id="logout" name="logout" >Logout</a></li>-->
                            </ul>
						<?php endif; ?>
		        
              </div><!-- header_contenido -->
                
                <div class="header_menu">
					<ul class="menu_1">
<li><a href="http://www.arriendas.cl" class="item_1<?php if (inModuleAndAction("main", "index")) { echo "_in"; } ?>"
	    title="Inicio"><span>Home</span></a>
</li>
<li><a href="<?php echo url_for('http://player.vimeo.com/video/45668172?title=0&byline=0&portrait=0&color=ffffff') ?>"
	    class="item_1<?php if (inModuleAndAction("main", "index")) { echo "_in"; } ?>"
	    title="Inicio - Ver Video Explicativo"><span>Ver Video Explicativo</span></a>
</li>
<li><a href="<?php echo url_for("profile/cars"); ?>" class="item_2<?php
							if (inModuleAndAction("profile", "index")) {
								echo "_in";
							}
 								?>" title="Mis autos"><span>Mis Autos</span></a></li>
                           
			   
<?php  if(sfContext::getInstance()->getUser()->getAttribute("propietario")) {  ?>
<li><a href="<?php echo url_for('profile/rental') ?>"
    class="item_5<?php if (inModuleAndAction("profile", "rental")) { echo "_in"; }?>"
    title="Reservas de mis Autos"><span>Reservas de mis Autos</span></a></li>                                    
<?php } else { ?>
    <li><a href="<?php echo url_for('profile/pedidos') ?>"
    class="item_5<?php if (inModuleAndAction("profile", "pedidos")) { echo "_in"; }?>"
    title="Pedidos de Reserva"><span>Pedidos de Reserva</span></a></li>
<?php } ?>
						    <li><a href="<?php echo url_for('main/contact') ?>" class="item_5<?php
							if (inModuleAndAction("main", "contact")) {
								echo "_in";
							}
 							?>" title="Contacto"><span>Contacto</span></a></li>
 							<li><a href="<?php echo url_for('main/contact') ?>" target="_blank" class="item_2"><span>Ayuda</span></a></li>
                        </ul><!-- menu_1 -->
	
                        

                    </div><!-- header_menu -->
                
            </div><!-- header -->



            <style>
				#tabsmenu2 ul li {
					display: inline;
					padding: 7px; /*Separar el texto*/
					margin-right: 5px; /* Separar los botones */
				}

				#tabsmenu2 ul {
					margin: 0;
					padding: 0;
				}

				#tabsmenu2 {
					width: 100%;
					margin-top: 7px; /* Separar los botones */
					font-size: 12px;
					margin-bottom: 30px;
				}
            </style>

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
                            <li><a href="<?php echo url_for('main/register') ?>" title="Tu Perfil">Tu Perfil</a></i>
                        </ul>
                        <ul class="enlaces_box">
                            <li class="enlaces_titulo">Acerca de Arriendas</li>
                            <li><a href="<?php echo url_for('main/compania') ?>" title="La compa&ntilde;&iacute;a">La compa&ntilde;&iacute;a</a></li>
                            <li><a href="<?php echo url_for('main/terminos') ?>" title="Terminos y Condiciones">Terminos y Condiciones</a></i>
                            <li><a href="<?php echo url_for('main/contact') ?>" title="Contacto">Contacto</a></i>
                        </ul>
                        
                        <ul class="enlaces_box">
                            <li class="enlaces_titulo">Acciones</li>
                            <li><a href="<?php echo url_for('profile/addCar') ?>" title="Sube tu auto">Sube tu auto</a></li>
                            <li><a href="<?php echo url_for('profile/rental') ?>" title="Busca un auto">Busca un auto</a></li>
                        </ul>

                    </div><!-- footer_enlaces -->

                                            <p class="footer_copy">Arriendas.cl Rimoldi SpA Encomenderos 253, Las Condes, Santiago.</p>

                                            </div><!-- /footer_contenido -->
                                            </div><!-- /footer -->

                                            </div><!-- body_bg -->
                                            </body>
                                            </html>

