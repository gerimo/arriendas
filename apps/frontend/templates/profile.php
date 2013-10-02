<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="es" xml:lang="es">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
        <meta name="description" content="Arrienda un auto vecino con seguro premium, asistencia de viaje y TAGs incluídos. Busca un auto por ubicación o por precio. Rent a car Vecino.">
        
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
                //Version Móviles
                $('.link_opciones_moviles').click(function(){
                    $('.subMenu_moviles').css('display','block');
                });
                $('.subMenu_moviles').mouseleave(function(){
                    $(this).css('display','none');
                });
                $('.subMenu_moviles').hover(function(){ 
                        mouse_is_inside=true; 
                    }, function(){ 
                        mouse_is_inside=false; 
                    });
                    
                    $("body").mouseup(function(){ 
                        if(! mouse_is_inside) $('.subMenu_moviles').hide();
                    });
                $('.subMenu_moviles a').click(function(){
                    $('.subMenu_moviles').css('display','none');
                });
            });

            function obtenerNumeroCorrecto(numero){
                var numeroNuevo = "";
                cantidad = ((numero).toString()).length;
                if(cantidad == 1){
                    numeroNuevo = "0" + numero;
                }else{
                    numeroNuevo = numero;
                }
                return numeroNuevo;
            }
        </script>
            <style>

                .submenu {
                    left: 520px;
                }
				
				
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

            <?php include_component("profile","alerta"); ?>
            <div class="header">
            <div class="headerImage">
              <div class="header_contenido">
	            <div class="header_logo">
	                <?php echo link_to(image_tag("arriendas_c.png", 'size=120x18'),'main/index') ?>
	            </div>
		        
		        <?php if (!sfContext::getInstance()->getUser()->getAttribute("logged")): ?>

                <a class="item_thm" href="<?php echo url_for('main/login') ?>"><div class="top_login"></div></a>

                <?php else: ?>
                        <div class="fondoOscuro">
                            <div class="cuadro_logueado">   
                                    <?php
                                    /*
                                    if ($sf_user->getAttribute('picture_url') != null){
                                        if($sf_user->getAttribute('fb')){
                                            echo image_tag($sf_user->getAttribute('picture_url'));
                                        } else {
                                            echo image_tag('users/'.$sf_user->getAttribute('picture_url'));
                                        }
                                    } else
                                        echo image_tag('img_registro/tmp_user_foto.png', 'size=23x23')
                                        */
                                        $idUsuario = $sf_user->getAttribute('userid');
                                        $user= Doctrine_Core::getTable("user")->findOneById($idUsuario);

                                        include_component("profile", "pictureFile", array("user" => $user, "params" => "width=145px height=147px"));
                                    ?>
                                <div class="LadoDer">
                                    <div class="group_name">                               
                                	<div class="hola"></div><br/>
                                    <span class="name_user"><?php echo ucwords($sf_user->getAttribute('name')) ?></span>
                                    </div>
                                    <a class="link_logout">
                                        <?php echo image_tag('Home/FlechaDespliega.png', 'id=logout') ?>
                                    </a>
                                </div> 
                                <div class="submenu_logout">
                                    <dl>
                                        <dt><a href="<?php echo url_for('profile/edit') ?>">Mi Perfil</a></dt>
                                        <dt><a href="<?php echo url_for('profile/changePassword') ?>">Cambiar Contraseña</a></dt>
                                        <dt><a href="<?php echo url_for('main/logout') ?>">Logout</a></dt>
                                    </dl>
                                </div>
                            </div>
                            <ul class="menu_social">
                                <li><?php include_component("messages", "alerts", array("color" => $colors[$number][0])); ?></li>
<!--                                <li><a href="<?php echo url_for('main/logout') ?>" style="color: white;font-weight: bold;text-decoration: none;text-shadow: 0px 1px 0px #0873CE;font-size: 14px;"  id="logout" name="logout" >Logout</a></li>-->
                            </ul>
                        </div><!-- fin fondoOscuro -->
                        <?php if (sfContext::getInstance()->getUser()->isAuthenticated()){ ?>
                            <a class="link_opciones_moviles">
                                <p id="logout_moviles">Acciones
                                <?php echo image_tag('Home/FlechaDespliega.png') ?></p>
                            </a>
                            <div class="subMenu_moviles">
                                <dl>
                                    <dt><a href="<?php echo url_for('profile/pedidos') ?>">Reservas</a></dt>
                                    <dt><a href="<?php echo url_for('profile/calificaciones') ?>">Calificaciones</a></dt>
                                </dl>
                            </div>
                        <?php }?>
						<?php endif; ?>
		        
                <div class="header_menu">
                    <ul class="menu_1">
                        <li><a href="http://www.arriendas.cl" class="item_1<?php if (inModuleAndAction("main", "index")) { echo "_in"; } ?>" title="Inicio"><span>HOME</span></a>
                        </li>  
                        <?php  if(sfContext::getInstance()->getUser()->getAttribute("propietario")) {  ?>
                            <li><a href="<?php echo url_for('profile/pedidos') ?>" class="item_5<?php if (inModuleAndAction("profile", "pedidos")) { echo "_in"; }?>" title="Reservas de mis Autos"><span>RESERVAS</span></a>
                            </li>
                            <li><a href="<?php echo url_for("profile/cars"); ?>" class="item_2<?php if (inModuleAndAction("profile", "index")) { echo "_in";} ?>" title="Mis autos"><span>MIS AUTOS</span></a>
                            </li>                                   
                        <?php } else { ?>
                            <li><a href="<?php echo url_for('profile/pedidos') ?>" class="item_5<?php if (inModuleAndAction("profile", "pedidos")) { echo "_in"; }?>" title="Pedidos de Reserva"><span>RESERVAS</span></a>
                            </li>
                            <li><a href="<?php echo url_for("profile/addCar"); ?>" class="item_2<?php if (inModuleAndAction("profile", "index")) { echo "_in";} ?>" title="Sube un auto"><span>SUBE UN AUTO</span></a>
                        <?php } ?>
                        <li><a href="https://arriendascl.zendesk.com/anonymous_requests/new" class="item_2<?php if (inModuleAndAction("main", "contact")) { echo "_in"; } ?>" target="_blank" title="Contacto"><span>CONTACTO</span></a>
                        </li>
                        <li class="item_ayuda"><a href="https://arriendascl.zendesk.com/forums" target="_blank" class="item_2"><span>AYUDA</span></a>
                        </li>
                        </ul><!-- menu_1 -->
                    </div><!-- header_menu -->

                    </div><!-- header_contenido -->
                </div><!-- headerImage-->
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
                            <?php if (!sfContext::getInstance()->getUser()->isAuthenticated()){ ?>
                            <li><a href="<?php echo url_for('main/register') ?>" title="Registrate">Registrate</a></li>
                            <?php } ?>
                            <?php if (sfContext::getInstance()->getUser()->isAuthenticated()){ ?>
                            <li><a href="<?php echo url_for('main/logout') ?>" title="Logout">Logout</a></li>
                            <?php }else{?>
                            <li><a href="<?php echo url_for('main/login') ?>" title="Login">Login</a></li>
                            <?php } ?>
                            <?php if (sfContext::getInstance()->getUser()->isAuthenticated()){ ?>
                            <!--<li><a href="<?php echo url_for('profile/publicprofile?id='.$sf_user->getAttribute("userid")) ?>" title="Tu Perfil">Tu Perfil</a></li>-->
                            <li><a href="<?php echo url_for('profile/edit') ?>" title="Edita tu perfil">Mi Perfil</a></li>
                            <?php } ?>
                        </ul>
                        <ul class="enlaces_box movil">
                            <li class="enlaces_titulo">Acerca de Arriendas</li>
                            <li><a href="<?php echo url_for('main/compania') ?>" target="_blank" title="La compañía Arriendas.cl">La Compañía</a></li>
                            <li><a href="<?php echo url_for('main/terminos') ?>" target="_blank" title="Términos y Condiciones">Términos y Condiciones</a></li>
                            <li><a href="https://arriendascl.zendesk.com/anonymous_requests/new" target="_blank" title="Contacto">Contacto</a></li>
                            <li><a href="https://arriendascl.zendesk.com/forums" target="_blank" title="Foro">Foro</a></li>
                        </ul>
                        <?php if (sfContext::getInstance()->getUser()->isAuthenticated()){ ?>
                        <ul class="enlaces_box movil">
                            <li class="enlaces_titulo">Acciones</li>
                            <li><a href="<?php echo url_for('profile/pedidos') ?>" title="Tus reservas">Tus reservas</a></li>
                            <li><a href="<?php echo url_for('profile/calificaciones') ?>" title="Tus calificaciones">Calificaciones</a></li>
                            <li><a href="<?php echo url_for('messages/inbox') ?>" title="Tus mensajes">Mensajes</a></li>
                            <li><a href="<?php echo url_for('profile/transactions') ?>" title="Tus transacciones">Transacciones</a></li>
                        <?php }?>
                        </ul>
                        <ul class="enlaces_box movil">
                            <li class="enlaces_titulo">Herramientas</li>
                            <li><a href="<?php echo url_for('profile/addCar') ?>" title="Sube tu auto">Sube tu auto</a></li>
                            <li><a href="http://www.arriendas.cl" title="Busca un auto">Busca un auto</a></li>
                             <li><a class="item_thm fancybox" href="<?php echo url_for('main/referidos') ?>">Gana dinero invitando Amigos</a></li>
                             <li><a class="item_thm fancybox" href="<?php echo url_for('main/valueyourcar') ?>">¿Cuánto puedo ganar con mi auto?</a></li>
                        </ul>

                    </div><!-- footer_enlaces -->

                    <p class="footer_copy">Arriendas.cl - Encomenderos 253, Las Condes, Santiago - (02) 2 333 3714.</p>

                </div><!-- /footer_contenido -->
            </div><!-- /footer -->

        </div><!-- body_bg -->
    </body>
</html>

