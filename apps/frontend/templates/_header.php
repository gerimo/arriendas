<?php $U = sfContext::getInstance()->getUser(); ?>

<header>
    <nav class="navbar navbar-default navbar-fixed-top navbar-inverse" id="header-navbar" role="navigation">
        <div class="container">

            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#header">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="<?php echo url_for('main/index') ?>"><img alt="Arriendas.cl" src="/images/newDesign/logoIndex.svg" height="19" width="123"></a>
            </div>

            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="header">
                
                <ul class="nav navbar-nav">

                    <?php if ($sf_user->isAuthenticated()): ?>
                        <li><a class="animate" href="">Inicio</a></li>
                        <li><a class="animate" href="">Mis autos</a></li>
                        <li><a class="animate" href="">Oportunidades</a></li>
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
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><img src="<?php echo $sf_user->getAttribute('picture_file') ?>"> <?php echo $sf_user->getAttribute('firstname') ?> <span class="caret"></span></a>
                            <ul class="dropdown-menu" role="menu">
                                <li><a href="<?php echo url_for('profile/edit') ?>">Mi perfil</a></li>
                                <li><a href="<?php echo url_for('profile/changePassword') ?>">Cambiar contraseña</a></li>
                                <li class="divider"></li>
                                <li><a href="<?php echo url_for('main/logout') ?>">Salir</a></li>
                            </ul>
                        </li>
                    <?php else: ?>                        
                        <li><a href="<?php echo url_for('main/login') ?>" style="font-size: 18px"><strong>INGRESAR</strong></a></li>
                    <?php endif ?>
                </ul>

                <ul class="nav navbar-nav navbar-right">
                <li><a><span class="glyphicon glyphicon-earphone"></span>(02) 2 333 37 14</a></li>
                </ul>

            </div><!-- /.navbar-collapse -->
        </div><!-- /.container -->
    </nav>
</header>