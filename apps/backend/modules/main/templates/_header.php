<?php

    $U = sfContext::getInstance()->getUser();

    if (strtotime(date("H:i")) > strtotime("19:00") || strtotime(date("H:i")) < strtotime("09:00")) {
        $telefono = "tel:0223333714";
        $telefonoText = "(02) 2333 3714"; 
    } else {
        $telefono = "tel:0226402900";
        $telefonoText = "(02) 2640 2900";
    }
?>

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
                <a class="navbar-brand" href="<?php echo url_for('homepage') ?>"><img alt="Arriendas.cl" src="/images/newDesign/logoIndex.svg" height="19" width="123"></a>
            </div>

            <div class="collapse navbar-collapse" id="header">
                
                <ul class="nav navbar-nav">
                    <li><a href="#">Reservas</a></li>
                    <li><a href="#">Oportunidades</a></li>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Usuarios<span class="caret"></span></a>
                        <ul class="dropdown-menu" role="menu">
                            <li><a href="#">Administrar</a></li>
                        </ul>
                    </li>
                </ul>

                <ul class="nav navbar-nav navbar-right">
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><!--<?php echo $sf_user->getAttribute('firstname')?> -->Nombre<span class="caret"></span></a>
                        <ul class="dropdown-menu" role="menu">
                            <li><a href="#">Salir</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</header>


