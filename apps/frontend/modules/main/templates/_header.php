<?php

    $U = sfContext::getInstance()->getUser();

    if (strtotime(date("H:i")) > strtotime("19:00") || strtotime(date("H:i")) < strtotime("09:00") || strtotime(date("l")) == strtotime("Saturday") || strtotime(date("l")) == strtotime("Sunday")) {
        $telefono = "tel:0223333714";
        $telefonoText = "(02) 2333 3714"; 
    } else {
        $telefono = "tel:0226402900";
        $telefonoText = "(02) 2640 2900";
    }
?>

<header>
    <nav class="navbar navbar-default navbar-fixed-top navbar-inverse clearfix" id="header-navbar" role="navigation">
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
                    <?php if($this->context->getActionName()!="reserve"): ?>
                    
                        <?php if ($sf_user->isAuthenticated()): ?>
                            <li><a href="<?php echo url_for("homepage") ?>" data-target="homepage">Inicio</a></li>

                            <?php if($hasCars): ?>
                                <li><a href="<?php echo url_for("cars") ?>" data-target="cars">Mis autos</a></li>
                            <?php else: ?>
                                <li><a href="<?php echo url_for("car_create") ?>" data-target="cars">Subir Auto</a></li>
                            <?php endif ?>
                            
                            <?php if ($hasActiveCars): ?>
                                <li><a href="<?php echo url_for("opportunities") ?>" data-target="opportunities">Oportunidades</a></li>
                            <?php endif ?>
                            
                            <li><a href="<?php echo url_for("reserves") ?>" data-target="reserves">Reservas</a></li>
                        <?php else: ?>
                            
                            <li><a href="<?php echo url_for("rent_a_car")?>">Buscar autos</a></li>
                            <li><a href="<?php echo url_for("how-works")?>">¿Cómo funciona?</a></li>
                            <li><a href="<?php echo url_for("prices_compare")?>">Compara precios</a></li>
                            <li><a href="<?php echo url_for("news")?>">En las noticias</a></li>
                            <!-- <li><a onclick="playPause()">Play / Pause</a></li> -->
                        <?php endif?>
                        <li><a href="<?php echo url_for('questions') ?>" data-target="questions">Preguntas Frecuentes</a></li>
                    
                    <?php endif ?>
                </ul>

                <ul class="nav navbar-nav navbar-right">
                    <li><a id="telefono" href="<?php echo $telefono?>"><span class="glyphicon glyphicon-earphone"></span><?php echo $telefonoText?></a></li>
                    <?php if ($sf_user->isAuthenticated()): ?>
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><!--<img src="<?php echo $sf_user->getAttribute('picture_file') ?>">--> <?php echo $sf_user->getAttribute('firstname') ?> <span class="caret"></span></a>
                            <ul class="dropdown-menu" role="menu">
                                <li><a href="<?php echo url_for('profile/edit') ?>">Mi perfil</a></li>
                                <li><a href="<?php echo url_for('transactions') ?>">Mis transacciones</a></li>
                                <li><a href="<?php echo url_for('profile/changePassword') ?>">Seguridad</a></li>
                                <li class="divider"></li>
                                <li><a href="<?php echo url_for('logout') ?>">Salir</a></li>
                            </ul>
                        </li>
                    <?php else: ?>                        
                        <li><a class="btn btn-a-primary" href="<?php echo url_for('login') ?>">INGRESAR</a></li>
                    <?php endif ?>
                </ul>
            </div>
        </div>
    </nav>

    <div class="notbar">
        <span class="remove glyphicon glyphicon-remove" aria-hidden="true"></span>
        <div class="notbar-body">
            <?php if (isset($sf_user->getAttribute('notificationMessage'))): ?>
                <?php echo $sf_user->getAttribute('notificationMessage') ?>
            <?php endif ?>
        </div>
    </div>
</header>
