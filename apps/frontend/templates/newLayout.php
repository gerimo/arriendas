<!doctype html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <?php //include_metas() ?>
        <?php //include_title() ?>
        <?php //include_stylesheets() ?>
        <link href="/css/newDesign/bootstrap.min.css" media="all" rel="stylesheet" type="text/css">
        <link href="/css/newDesign/all.css" media="all" rel="stylesheet" type="text/css">
        <link href='http://fonts.googleapis.com/css?family=Roboto:400,300,300italic,500,500italic,700,900,700italic' rel='stylesheet' type='text/css'>
    </head>

    <body>

        <header id="header">
            <div class="container">
                <div class="row">
                    <nav id="nav">
                        <a href="#" class="nav-opener">Menu</a>
                        <div class="nav-drop">
                            <ul class="nav-mobile">
                                <li><a href="#">RESERVAS</a></li>
                                <li><a href="#">MI PERFIL</a></li>
                                <li><a href="#">MENSAJES</a></li>
                                <li><a href="#">CALIFICACIONES</a></li>
                                <li><a href="#">SUBE UN AUTO</a></li>
                            </ul>
                        </div>
                    </nav>
                    <div class="col-xs-4">
                        <div class="logo pull-left">
                            <a href="#">
                                <img src="/images/newDesign/logo.svg" alt="Arriendas.cl" width="165" height="26">
                                <img src="/images/newDesign/logo-mobile.svg" alt="Arriendas.cl" width="22" height="17" class="mob" >
                            </a>
                        </div>
                    </div>
                    <div class="col-xs-8">
                        <a href="#" class="login-info pull-right">
                            <img src="/images/newDesign/img.jpg" alt="" width="65" height="65">
                            <span class="name text-capitalize">Hola Javiera</span>
                        </a>
                    </div>
                </div>
            </div>
        </header>

        <main role="main" id="main" class="container">
            <?php echo $sf_content ?>
        </main>

        <footer id="footer">
            <div class="holder container">
                <div class="four-col row">
                    <div class="col col-sm-3">
                        <strong class="heading text-uppercase">CONTACTO</strong>
                        <ul class="list list-unstyled">
                            <li>Teléfono <a href="tel:0223333714" class="tel-link">(02) 2333-3714</a></li>
                        </ul>
                    </div>
                    <div class="col col-sm-3">
                        <strong class="heading text-uppercase">USUARIOS</strong>
                        <ul class="list list-unstyled">
                            <?php if(sfContext::getInstance()->getUser()->isAuthenticated()): ?>
                                <li><a href="<?php echo url_for('profile/edit') ?>" title="Edita tu perfil">Mi Perfil</a></li>
                                <li><a href="<?php echo url_for('main/logout') ?>" title="Salir">Salir</a></li>
                            <?php else: ?>
                                <li><a href="<?php echo url_for('main/register') ?>" title="Regístrate">Regístrate</a></li>
                                <li><a href="<?php echo url_for('main/login') ?>" title="Ingresar">Ingresar</a></li>
                            <?php endif ?>
                        </ul>
                    </div>
                    <div class="col col-sm-3">
                        <strong class="heading text-uppercase">ACERCA DE ARRIENDAS</strong>
                        <ul class="list list-unstyled">
                            <li><a href="<?php echo url_for('main/compania') ?>" title="La compañía Arriendas.cl">La Compañía</a></li>
                            <li><a href="<?php echo url_for('main/terminos') ?>" target="_blank" title="Términos y Condiciones">Términos y Condiciones</a></li>
                            <li><a href="https://arriendascl.zendesk.com/anonymous_requests/new" target="_blank" title="Contacto">Contacto</a></li>
                            <li><a href="https://arriendascl.zendesk.com/forums" target="_blank" title="Foro">Foro</a></li>
                        </ul>
                    </div>
                    <div class="col col-sm-3">
                        <strong class="heading text-uppercase">HERRAMIENTAS</strong>
                        <ul class="list list-unstyled">
                            <li><a href="<?php echo url_for('profile/addCar') ?>" title="Sube tu auto">Sube tu auto</a></li>
                            <li><a href="http://www.arriendas.cl" title="Busca un auto">Busca un auto</a></li>
                            <li><a class="item_thm fancybox" href="<?php echo url_for('main/referidos') ?>">Gana dinero invitando Amigos</a></li>
                            <li><a class="item_thm fancybox" href="<?php echo url_for('main/valueyourcar') ?>">¿Cuánto puedo ganar con mi auto?</a></li>
                        </ul>
                    </div>
                </div>
                <p>Providencia 229 (entrada por Perez Espinoza), Providencia, Santiago / Chile</p>
                <h2 class="heading-follow text-uppercase"><span>SÍGUENOS EN</span></h2>
                <ul class="social-networks list-inline">
                    <li><a href="#" class="facebook">facebook</a></li>
                    <li><a href="#" class="twitter">twitter</a></li>
                </ul>
            </div>
        </footer>

        <script type="text/javascript">
            if (navigator.userAgent.match(/IEMobile\/10\.0/) || navigator.userAgent.match(/MSIE 10.*Touch/)) {
                var msViewportStyle = document.createElement('style')
                msViewportStyle.appendChild(
                    document.createTextNode(
                        '@-ms-viewport{width:auto !important}'
                    )
                )
                document.querySelector('head').appendChild(msViewportStyle)
            }
        </script>
        <script type="text/javascript" src="/js/newDesign/jquery-1.11.1.min.js"></script>
        <script type="text/javascript" src="/js/newDesign/bootstrap.min.js"></script>
        <script type="text/javascript" src="/js/newDesign/jquery.main.js"></script>
    </body>
</html>