<!doctype html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <?php include_metas() ?>
        <?php include_title() ?>
        <?php //include_stylesheets() ?>
        <link href="css/designResponsive/designResponsive.css" rel="stylesheet" media="all">
        <link href="css/designResponsive/jquery.datetimepicker.css" rel="stylesheet" type="text/css">
        <link href='http://fonts.googleapis.com/css?family=Roboto:400,300,700' rel='stylesheet' type='text/css'>
        <link href='http://fonts.googleapis.com/css?family=Open+Sans:400,600,700,700italic,800,400italic' rel='stylesheet' type='text/css'>
    </head>

    <body>

        <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js" type="text/javascript"></script>
        <script type="text/javascript">window.jQuery || document.write('<script src="js/designResponsive/jquery-1.11.1.min.js"><\/script>')</script>
        <script src="js/designResponsive/jquery.main.js" type="text/javascript"></script>
        <script src="js/designResponsive/jquery.datetimepicker.js" type="text/javascript"></script>

        <?php echo $sf_content ?>

        <footer id="footer">
            <div class="holder">
                <div class="four-col">
                    <div class="col">
                        <strong class="heading">CONTACTO</strong>
                        <ul class="list">
                            <li>Teléfono <a href="tel:0223333714" class="tel-link">(02) 2333-3714</a></li>
                            <li>Providencia 229 (entrada por Perez Espinoza), </li>
                            <li>Providencia, Santiago / Chile</li>
                        </ul>
                    </div>
                    <div class="col">
                        <strong class="heading">USUARIOS</strong>
                        <ul class="list">
                            <?php if(sfContext::getInstance()->getUser()->isAuthenticated()): ?>
                                <li><a href="<?php echo url_for('profile/edit') ?>" title="Edita tu perfil">Mi Perfil</a></li>
                                <li><a href="<?php echo url_for('main/logout') ?>" title="Salir">Salir</a></li>
                            <?php else: ?>
                                <li><a href="<?php echo url_for('main/register') ?>" title="Regístrate">Regístrate</a></li>
                                <li><a href="<?php echo url_for('main/login') ?>" title="Ingresar">Ingresar</a></li>
                            <?php endif ?>
                        </ul>
                        <br>
                        <strong class="heading">ARRIENDO DE AUTOS EN SANTIAGO</strong>
                        <ul class="list">
                            <li><a href="http://arriendas.cl/arriendo-de-autos/region-metropolitana">Rent a Car Santiago Centro</a></li>
                            <li><a href="http://arriendas.cl/arriendo-de-autos/region-metropolitana">Rent a Car Providencia</a></li>
                            <li><a href="http://arriendas.cl/arriendo-de-autos/region-metropolitana">Rent a Car Ñuñoa</a></li>
                        </ul>
                    </div>
                    <div class="col">
                        <strong class="heading">ACERCA DE ARRIENDAS</strong>
                        <ul class="list">
                            <li><a href="<?php echo url_for('main/compania') ?>" title="La compañía Arriendas.cl">La Compañía</a></li>
                            <li><a href="<?php echo url_for('main/terminos') ?>" target="_blank" title="Términos y Condiciones">Términos y Condiciones</a></li>
                            <li><a href="https://arriendascl.zendesk.com/anonymous_requests/new" target="_blank" title="Contacto">Contacto</a></li>
                            <li><a href="https://arriendascl.zendesk.com/forums" target="_blank" title="Foro">Foro</a></li>
                        </ul>
                    </div>
                    <div class="col">
                        <strong class="heading">HERRAMIENTAS</strong>
                        <ul class="list">
                            <li><a href="<?php echo url_for('profile/addCar') ?>" title="Sube tu auto">Sube tu auto</a></li>
                            <li><a href="http://www.arriendas.cl" title="Busca un auto">Busca un auto</a></li>
                            <li><a class="item_thm fancybox" href="<?php echo url_for('main/referidos') ?>">Gana dinero invitando Amigos</a></li>
                            <li><a class="item_thm fancybox" href="<?php echo url_for('main/valueyourcar') ?>">¿Cuánto puedo ganar con mi auto?</a></li>
                        </ul>
                    </div>
                </div>
                <p>Providencia 229 (entrada por Perez Espinoza), Providencia, Santiago / Chile</p>
                <h3 class="heading-follow"><span>SÍGUENOS EN</span></h3>
                <ul class="social-networks">
                    <li><a href="#" class="facebook">facebook</a></li>
                    <li><a href="#" class="twitter">twitter</a></li>
                </ul>
            </div>
        </footer>
    </body>
</html>