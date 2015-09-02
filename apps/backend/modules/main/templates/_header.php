<header>
    <nav class="navbar navbar-default navbar-fixed-top" id="header-navbar" role="navigation">
        <div class="container">

            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#header">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="<?php echo url_for('homepage') ?>"><img alt="Arriendas.cl" src="/images/newDesign/logobackend.svg" height="44" width="123"></a>
            </div>

            <div class="collapse navbar-collapse" id="header">
                
                <ul class="nav navbar-nav" data-section="Autos">
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Autos<span class="caret"></span></a>
                        <ul class="dropdown-menu" role="menu">
                            <li><a href="<?php echo url_for('car_control') ?>">Control auto</a></li>
                            <li><a href="<?php echo url_for('car_verify_car') ?>">Verificar auto</a></li>
                        </ul>
                    </li>
                    <li class="dropdown" data-section="Notificaciones">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Notificaciones<span class="caret"></span></a>
                        <ul class="dropdown-menu" role="menu">
                            <li><a href="<?php echo url_for('notification') ?>">Notificaciones</a></li>
                            <li role="presentation" class="divider"></li>
                            <li><a href="<?php echo url_for('notification_management_action') ?>">Acciones</a></li>
                            <li><a href="<?php echo url_for('notification_management_notification_type') ?>">Tipos de notificaciones</a></li>
                        </ul>
                    </li>
                    <li class="dropdown" data-section="Oportunidades">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Oportunidades<span class="caret"></span></a>
                        <ul class="dropdown-menu" role="menu">
                            <li><a href="<?php echo url_for('opportunity_dashboard') ?>">Dashboard</a></li>
                            <li role="presentation" class="divider"></li>
                            <li><a href="<?php echo url_for('opportunity_create') ?>">Generar</a></li>                            
                        </ul>
                    </li>
                    <li class="dropdown" data-section="Reservas">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Reservas<span class="caret"></span></a>
                        <ul class="dropdown-menu" role="menu">
                            <li><a href="<?php echo url_for('reserve_problematic_reservations') ?>">Reservas Problematicas</a></li>
                            <li><a href="<?php echo url_for('reserve_expired_reserves') ?>">Devolución Pago Garantía</a></li>
                            <li><a href="<?php echo url_for('reserve_fortnightly_Payments') ?>">Pagos Quincenales</a></li>
                        </ul>
                    </li>
                    <li class="dropdown" data-section="Usuarios">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Usuarios<span class="caret"></span></a>
                        <ul class="dropdown-menu" role="menu">
                            <li><a href="<?php echo url_for('user_control') ?>">Control Usuarios</a></li>
                            <li><a href="<?php echo url_for('user_whitout_pay') ?>">Usuario que no terminaron pago</a></li>
                            <li><a href="<?php echo url_for('user_whitout_car') ?>">Usuario que no registraron auto</a></li>
                        </ul>
                    </li>
                    <li class="dropdown" data-section="Gps">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Gps<span class="caret"></span></a>
                        <ul class="dropdown-menu" role="menu">
                            <li><a href="<?php echo url_for('gps_control') ?>">Control de Gps por autos</a></li>
                        </ul>
                    </li>
                </ul>

                <ul class="nav navbar-nav navbar-right">
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><?php echo $sf_user->getAttribute('fullname') ?><span class="caret"></span></a>
                        <ul class="dropdown-menu" role="menu">
                            <li><a href="<?php echo url_for('logout') ?>">Salir</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</header>


