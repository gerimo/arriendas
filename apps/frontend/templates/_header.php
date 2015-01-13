<?php $U = sfContext::getInstance()->getUser(); ?>

<header>
    <nav class="navbar navbar-default navbar-fixed-top navbar-inverse" id="header-navbar" role="navigation">
        <div class="container">

            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#header">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="<?php echo url_for('homepage') ?>"><img alt="Arriendas.cl" src="/images/newDesign/logoIndex.svg" height="19" width="123"></a>
            </div>

            <div class="collapse navbar-collapse" id="header">
                
                <ul class="nav navbar-nav">

                    <?php if ($sf_user->isAuthenticated()): ?>
                        <li><a href="<?php echo url_for("homepage") ?>" data-target="homepage">Inicio</a></li>
                        <li><a href="<?php echo url_for("profile/cars") ?>" data-target="cars">Mis autos</a></li>
                        <li><a href="<?php echo url_for("opportunities") ?>" data-target="opportunities">Oportunidades</a></li>
                        <li><a href="<?php echo url_for("reserves") ?>" data-target="reserves">Reservas</a></li>
                    <?php else: ?>
                        <li><a class="animate" data-target="#section-map-form-search" href="">Buscar autos</a></li>
                        <li><a class="animate" data-target="#section-how-works" href="">¿Cómo funciona?</a></li>
                        <li><a class="animate" data-target="#section-compare-prices" href="">Compara precios</a></li>
                        <li><a class="animate" data-target="#section-on-news" href="">En las noticias</a></li>
                        <!-- <li><a onclick="playPause()">Play / Pause</a></li> -->
                    <?php endif ?>

                    <li><a href="<?php echo url_for('como_funciona/index') ?>">Ayuda</a></li>
                </ul>

                <ul class="nav navbar-nav navbar-right">
                    <?php if ($sf_user->isAuthenticated()): ?>
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><!--<img src="<?php echo $sf_user->getAttribute('picture_file') ?>">--> <?php echo $sf_user->getAttribute('firstname') ?> <span class="caret"></span></a>
                            <ul class="dropdown-menu" role="menu">
                                <li><a href="<?php echo url_for('profile/edit') ?>">Mi perfil</a></li>
                                <li><a href="<?php echo url_for('profile/changePassword') ?>">Seguridad</a></li>
                                <li class="divider"></li>
                                <li><a href="<?php echo url_for('logout') ?>">Salir</a></li>
                            </ul>
                        </li>
                    <?php else: ?>                        
                        <li><a class="btn-a-primary" href="<?php echo url_for('login') ?>">INGRESAR</a></li>
                    <?php endif ?>
                </ul>

                <ul class="nav navbar-nav navbar-right">
                <li><a href="tel:+56223333714"><span class="glyphicon glyphicon-earphone"></span>(02) 2 333 37 14</a></li>
                </ul>

            </div><!-- /.navbar-collapse -->
        </div><!-- /.container -->
    </nav>
</header>