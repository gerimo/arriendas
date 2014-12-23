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
                    <li><a class="animate" data-target="#section-map" href="">Buscar autos</a></li>
                    <li><a class="animate" data-target="#section-home" href="">¿Cómo funciona?</a></li>
                    <li><a class="animate" data-target="#section-home" href="">Compara precios</a></li>
                    <li><a class="animate" data-target="#section-home" href="">En las noticias</a></li>
                    <li><a href="<?php echo url_for('como_funciona/index') ?>">Ayuda</a></li>
                    <!-- <li><a onclick="playPause()">Play / Pause</a></li> -->
                </ul>

                <ul class="nav navbar-nav navbar-right">
                    <?php if (!sfContext::getInstance()->getUser()->isAuthenticated()): ?>
                        <li><a href="<?php echo url_for('main/login') ?>" style="font-size: 18px"><strong>INGRESAR</strong></a></li>
                        <!-- <li><a class="btn-a-primary MA" href="<?php //echo url_for('main/login') ?>" style="margin-top: 8px">INGRESAR</a></li> -->
                    <?php endif ?>
                </ul>
            </div><!-- /.navbar-collapse -->
        </div><!-- /.container -->
    </nav>
</header>