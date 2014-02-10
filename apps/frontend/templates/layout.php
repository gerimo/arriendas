<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="es" xml:lang="es">
    <head>
	<meta name="google-site-verification" content="HxfRs3eeO-VFQdT1_QX7j8hfHPNMh-EIxA7BOyNtHiU" />
	<?php echo  (stripos($_SERVER['SERVER_NAME'], "arrendas") !== FALSE)? "<title>Arrendas</title>":"<title>Arriendas.cl | Rent a car vecino en Chile</title>";?>
        
        <?php include_metas() ?>
	<link href="http://arriendas.assets.s3.amazonaws.com/images/favicon.ico" rel="shortcut icon" type="image/x-icon" />
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1"/>
        <meta property="og:description" content="Arrienda un auto vecino con seguro premium, asistencia de viaje y TAGs incluídos. Busca un auto por ubicación o por precio. Rent a car Vecino."/>
        <meta property="og:title" content="Arriendas.cl - Arrienda un auto vecino, cerca de ti" />
        <meta property="og:type" content="website" />
        <meta property="og:image" content="<?php echo image_path('Home/logo_arriendas3.jpg', 'absolute=true');?>" />
        <meta property="og:url" content="http://arriendas.cl<?php echo sfContext::getInstance()->getController()->genUrl(sfContext::getInstance()->getRouting()->getCurrentInternalUri());?>" />
        <meta property="og:site_name" content="Arriendas.cl - Arrienda un auto vecino, cerca de ti" />
        <meta property="fb:admins" content="germanrimo" />
        <meta property="og:description" content="Arrienda un auto por horas o Gana dinero todos los meses arrendando tu auto, con seguro." />
        <meta name="google-site-verification" content="Y0Lya1N8r_8QIRxeyg3ht2lcwxR2B0VmBLAgopF2lgQ" />        
            <?php /* include_http_metas() */ ?>
            
        	<?php if (sfContext::getInstance()->getModuleName()=='main' && sfContext::getInstance()->getActionName()=='index'): ?>
			<script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?libraries=places&amp;sensor=false"></script>
            <?php endif ?>

			<?php include_stylesheets() ?>
			
		
			<script type="text/javascript" src="//cdn.optimizely.com/js/241768225.js"></script>

            <?php include_javascripts() ?>
			<script type="text/javascript">
//				$(document).ready(function() {
//					$(".fancybox").fancybox();
 //                   $(".enlaceCerrarInvitar").click(function(e){
  //                      e.preventDefault();
   //                     $(".invitar_amigos").fadeOut("slow");
   //                 });
    //                $(".invitar_amigos").delay(3000).fadeIn("slow");

//                    var loginFacebook = "<?php echo $sf_user->getAttribute('loggedFb'); ?>";
 //                   if(loginFacebook == true){
   //                     $("#contenedorSnippetLoginFacebook").css("display","block");
                        <?php 
    //                    $sf_user->setAttribute("loggedFb", false);
                        ?>
        //            }else{
       //                 $("#contenedorSnippetLoginFacebook").remove();
      //              }
     //           });
			</script>
			
                        <style type="text/css">
				
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
        <script type="text/javascript">
        <?php if (inModuleAndAction("main", "login")): ?>
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
        <?php endif; ?>

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

                //Condición para calendario
                $('#day_from').on('change',function(){
                    $('#day_to').attr("value", $('#day_from').val());
                    //calcularPrecio();
                });
                $('#hour_from').click(function(){
                    $('div.time-picker ul li.horaImpar').css('display', 'block');
                    $('div.time-picker ul li.horaPar').css('display', 'block');
                });
                $('#hour_from').on('change',function(){
                    var hora = $("#hour_from").timePicker('getTime').val();
                    hora = hora.split(':');
                    var tiempoHora = (parseInt(hora[0])+parseInt(1));
                        if(parseInt(tiempoHora)==parseInt(24)){
                        tiempoHora = "00";
                        var fecha = $("#day_from").timePicker('getTime').val();//obteniendo la fecha_from
                        fecha = fecha.split('-');//sacandole los '-'
                        mi_fecha = new Date(fecha[2],fecha[1]-1,fecha[0]);//creando un nuevo Date con los datos de la fecha obtenida de fecha_from
                        mi_fecha.setDate(mi_fecha.getDate()+parseInt(1));//sumandole un dia a la fecha
                        dia = obtenerNumeroCorrecto(mi_fecha.getDate());//obteniendo los dias
                        mes = obtenerNumeroCorrecto(mi_fecha.getMonth()+parseInt(1));//obteniendo los meses(siempre se les suma 1)
                        anio = mi_fecha.getFullYear();//obteniendo los años
                        var nuevaFecha = dia+"-"+mes+"-"+anio;//creando un string de la nueva fecha
                        $('#day_to').attr("value", nuevaFecha);//mostrar la nueva fecha en fecha_to
                    }
                    var tiempoTo = tiempoHora+":"+hora[1];
                    $('#hour_to').attr("value", tiempoTo);
                });

                $('#hour_to').click(function(){
                    var hora_inicial= $("#hour_from").timePicker('getTime').val();
                    hora_inicial = hora_inicial.split(':');
                    var tipoMinutos = hora_inicial[1];
                    if(tipoMinutos==00){
                        $('div.time-picker ul li.horaImpar').css('display', 'none');
                    }
                    if(tipoMinutos==30){
                        $('div.time-picker ul li.horaPar').css('display', 'none');
                    }
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

            <?php
            if (inModuleAndAction("main", "index")) {
                if(sfContext::getInstance()->getUser()->isAuthenticated()){
            ?>
            <?php
/*            <div id="contenedorInvitaAmigos">
               <div class="invitar_amigos" style="display:none;">
                    <div class="fondo_invitar"> 
                        <a href="#" title="Cerrar" class="enlaceCerrarInvitar"><div class="cerrarInvitar"></div></a>
                        <a href="<?php echo url_for('main/referidos')?>" title="Invita a tus amigos" class="enlaceInvitar"><div class="botonInvitar"></div></a>
                    </div>
                </div>
            </div>
  */?>
  <?php 
                /*
                <div id="contenedorOpcionArrendadorArrendatario">
                   <div class="seleccionar_tipo_usuario">
                        <div class="fondo_elegir"> 
                            <div id="margenBotones">
                                <div id="capa_arriendaUnAuto">
                                    <input class="boton_arriendaUnAuto botonSeleccionado" type="button" value="Arrendar un Auto"/>
                                </div>
                                <div id="capa_ponerAutoArriendo">
                                    <input class="boton_ponerAutoArriendo" type="button" value="Poner mi auto en arriendo"/>
                                </div>
                            </div>
                        </div><!-- fin fondo invitar -->
                    </div>
                </div>
                */
                ?>
                    
            <?php
                include_component("profile","alerta");
                }else{
            ?>  
                <?php
				/*
							<div id="contenedorInvitaAmigos">
                   <div class="invitar_amigos" style="display:none;">
                        <div class="fondo_invitar"> 
                            <a href="#" title="Cerrar" class="enlaceCerrarInvitar"><div class="cerrarInvitar"></div></a>
                            <a href="<?php echo url_for('main/referidos')?>" title="Invita a tus amigos" class="enlaceInvitar"><div class="botonInvitar"></div></a>
                        </div>
                    </div>
                </div>
				*/?>
				<?php
                    /*
                    <div id="contenedorOpcionArrendadorArrendatario">
                       <div class="seleccionar_tipo_usuario">
                            <div class="fondo_elegir"> 
                                <div id="margenBotones">
                                    <div id="capa_arriendaUnAuto">
                                        <input class="boton_arriendaUnAuto botonSeleccionado" type="button" value="Arrendar un Auto"/>
                                    </div>
                                    <div id="capa_ponerAutoArriendo">
                                        <input class="boton_ponerAutoArriendo" type="button" value="Poner mi auto en arriendo"/>
                                    </div>
                                </div>
                            </div><!-- fin fondo invitar -->
                        </div>
                    </div>
                    */
                    ?>
            <?php
                } //end else
            }else{
                if(sfContext::getInstance()->getUser()->isAuthenticated()){
            ?>
	            <?php
				/*
				<div id="contenedorInvitaAmigos">
                       <div class="invitar_amigos" style="display:none;">
                            <div class="fondo_invitar"> 
                                <a href="#" title="Cerrar" class="enlaceCerrarInvitar"><div class="cerrarInvitar"></div></a>
                                <a href="<?php echo url_for('main/referidos')?>" title="Invita a tus amigos" class="enlaceInvitar"><div class="botonInvitar"></div></a>
                            </div>
                        </div>
                    </div>
				*/?>
            <?php
                    include_component("profile","alerta");
                }else{
            ?>
	            <?php
				/*
                    <div id="contenedorInvitaAmigos">
                       <div class="invitar_amigos" style="display:none;">
                            <div class="fondo_invitar"> 
                                <a href="#" title="Cerrar" class="enlaceCerrarInvitar"><div class="cerrarInvitar"></div></a>
                                <a href="<?php echo url_for('main/referidos')?>" title="Invita a tus amigos" class="enlaceInvitar"><div class="botonInvitar"></div></a>
                            </div>
                        </div>
                    </div>
				*/?>
				<?php
                } //end else
            }
            ?>
            
            <div class="header">
                <div class="headerImage">
                <div class="header_contenido">
                
                    <div class="header_logo">
                        <?php echo link_to(image_tag("arriendas_c.png", 'size=120x18'), 'main/index',array('title' => 'Inicio')) ?>
                    </div>
                    
                    <?php if (!sfContext::getInstance()->getUser()->isAuthenticated()): ?>
		    
                        <a class="item_thm" href="<?php echo url_for('main/login') ?>"><div class="top_login"></div>

									</a>  

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
                            
                            <ul class="menu_social" >
                                <li><?php include_component("messages", "alerts", array("color" => $colors[$number][0])); ?></li>
    						<!-- <li><a href="<?php echo url_for('main/logout') ?>" style="color: white;font-weight: bold;text-decoration: none;text-shadow: 0px 1px 0px #0873CE;font-size: 14px;"  id="logout" name="logout" >Logout</a></li>-->
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
                <!--  fin botones login / registro -->

                 <?php if (!sfContext::getInstance()->getUser()->isAuthenticated()): ?>              
                <!-- inicio header_menu -->
                    <div class="header_menu"> 
                        <ul class="menu_1">
       <!--     <li><a href="<?php echo url_for('main/index') ?>" class="item_1<?php
                            if (inModuleAndAction("main", "index")) {
                                echo "_in";
                            }
                        ?>" title="Inicio">
                            <span>HOME</span></a></li>
    -->                        <li><a href="<?php echo url_for('como_funciona/index') ?>" class="item_1<?php
                            if (inModuleAndAction("como_funciona", "index")) {
                                echo "_in";
                            }
                        ?>" title="¿Como funciona Arriendas.cl?">
                            <span>¿COMO FUNCIONA?</span></a></li>    

                        <li><a href="<?php echo url_for('en_los_medios/index') ?>" class="item_1<?php
                            if (inModuleAndAction("en_los_medios", "index")) {
                                echo "_in";
                            }
                        ?>" title="Arriendas.cl En Los Medios">
                            <span>EN LAS NOTICIAS</span></a></li>    

    
<!--                <li><a href="https://arriendascl.zendesk.com/anonymous_requests/new"
                    class="item_2<?php if (inModuleAndAction("main", "contact")) { echo "_in"; } ?>"
                    target="_blank" title="Contacto"><span>CONTACTO</span></a></li>
    -->                
                <li class="item_ayuda"><a href="https://arriendascl.zendesk.com/" target="_blank" class="item_2"><span>AYUDA</span></a></li>
                </ul><!-- menu_1 -->
                </div><!-- Fin header_menu -->
            <?php else: ?>

                <div class="header_menu">
                    <ul class="menu_1">
                        <?php  if(sfContext::getInstance()->getUser()->getAttribute("propietario")) {  ?>
                            <li><a href="<?php echo url_for('profile/pedidos') ?>" class="item_5<?php if (inModuleAndAction("profile", "pedidos")) { echo "_in"; }?>" title="Reservas de mis Autos"><span>RESERVAS</span></a>
                            </li>
                            <li><a href="<?php echo url_for("profile/cars"); ?>" class="item_2<?php if (inModuleAndAction("profile", "index")) { echo "_in";} ?>" title="Mis autos"><span>MIS AUTOS</span></a>
                            </li>                                   
                        <?php } else { ?>
                            <li><a href="<?php echo url_for('profile/pedidos') ?>" class="item_5<?php if (inModuleAndAction("profile", "pedidos")) { echo "_in"; }?>" title="Pedidos de Reserva"><span>RESERVAS</span></a>
                            </li>
                            <li><a href="<?php echo url_for("profile/addCar"); ?>" class="item_2<?php if (inModuleAndAction("profile", "index")) { echo "_in";} ?>" title="Sube un auto"><span>SUBE UN AUTO</span></a>
                        </li>
                        <?php } ?>
                        <li class="item_ayuda"><a href="https://arriendascl.zendesk.com/" target="_blank" class="item_2"><span>AYUDA</span></a></li>
                        <?php  if(sfContext::getInstance()->getUser()->getAttribute("propietario")) {  ?>
                            <li><a href="<?php echo url_for('profile/oportunidades') ?>" class="item_5<?php if (inModuleAndAction("profile", "oportunidades")) { echo "_in"; }?>" title="Oportunidades"><span>OPORTUNIDADES</span></a></li>                                
                        <?php } ?>
                    </ul><!-- menu_1 -->  
                </div><!-- header_menu -->
            <?php endif; ?>							

                </div><!-- header_contenido -->
                </div><!-- headerImage -->
            </div><!-- header -->

			<?php echo $sf_content ?>

            <div id="contenedorSnippetLoginFacebook" style="display:none;">
            <!-- Google Code for Registro con Facebook Conversion Page -->
<script type="text/javascript">
/* <![CDATA[ */
var google_conversion_id = 996876210;
var google_conversion_language = "en";
var google_conversion_format = "3";
var google_conversion_color = "ffffff";
var google_conversion_label = "99d7CMb-iAUQsr-s2wM";
var google_conversion_value = 0;
/* ]]> */
</script>
<script type="text/javascript" src="//www.googleadservices.com/pagead/conversion.js">
</script>
<noscript>
<div style="display:inline;">
<img height="1" width="1" style="border-style:none;" alt="" src="//www.googleadservices.com/pagead/conversion/996876210/?value=0&amp;label=99d7CMb-iAUQsr-s2wM&amp;guid=ON&amp;script=0"/>
</div>
</noscript>
            </div> <!-- contenedorSnippetLoginFacebook -->
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
                            <li><a href="<?php echo url_for('profile/edit') ?>" title="Edita tu perfil">Mi Perfil</a></li>
                            <?php } ?>
                        </ul>
                        <ul class="enlaces_box movil">
                            <li class="enlaces_titulo">Acerca de Arriendas</li>
                            <li><a href="<?php echo url_for('main/compania') ?>"  title="La compañía Arriendas.cl">La Compañía</a></li>
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
                        <ul class="enlaces_box movil">
                            <li class="enlaces_titulo">Redes Sociales</li>
                             <li><a class="item_thm fancybox" target="_blank" href="https://www.facebook.com/arriendaschile">Facebook</a></li>
                             <li><a class="item_thm fancybox" target="_blank" href="https://twitter.com/Arriendas">Twitter</a></li>
                        </ul>
            
                    </div><!-- footer_enlaces -->

                    <p class="footer_copy" style='text-align:center;'>Teléfono (02) 2333-3714 - Providencia 229 (entrada por Perez Espinoza), Providencia, Santiago.</p>

                    <br><br>
                </div><!-- /footer_contenido -->
            </div><!-- /footer -->
        </div><!-- body_bg -->
		<script type="text/javascript">
		  var _cio = _cio || [];
		  (function() {
			var a,b,c;a=function(f){return function(){_cio.push([f].
			concat(Array.prototype.slice.call(arguments,0)))}};b=["load","identify",
			"sidentify","track","page"];for(c=0;c<b.length;c++){_cio[b[c]]=a(b[c])};
			var t = document.createElement('script'),
				s = document.getElementsByTagName('script')[0];
			t.async = true;
			t.id    = 'cio-tracker';
			t.setAttribute('data-site-id', '3a9fdc2493ced32f26ee');
			t.src = 'https://assets.customer.io/assets/track.js';
			s.parentNode.insertBefore(t, s);
		  })();
		</script>
<?php if (sfContext::getInstance()->getUser()->isAuthenticated()){ ?>
       		<script type="text/javascript">
		  _cio.identify({
			id: 'a_<?php echo ucwords($sf_user->getAttribute('userid')) ?>',
			email: '<?php echo ucwords($sf_user->getAttribute('email')) ?>',
			created_at: <?php echo (strtotime($sf_user->getAttribute('fecha_registro'))) ?>,
			name: '<?php echo ucwords($sf_user->getAttribute('firstname')) ?>',
			propietario: '<?php echo ucwords($sf_user->getAttribute('propietario')) ?>',
			telephone: '<?php echo ucwords($sf_user->getAttribute('telephone')) ?>',
			comuna: '<?php echo ucwords($sf_user->getAttribute('comuna')) ?>',
			region: '<?php echo ucwords($sf_user->getAttribute('region')) ?>',
		  });
		</script>		
<?php }?>
</body>
</html>


