<footer id="footer">
    <div class="row">

        <div class="hidden-xs col-sm-3 col-md-3">
            <h1>Usuarios</h1>
            <ul>
                <?php if(sfContext::getInstance()->getUser()->isAuthenticated()): ?>
                    <li><a href="<?php echo url_for('profile/edit') ?>" title="Edita tu perfil">Mi Perfil</a></li>
                    <li><a href="<?php echo url_for('profile/changePassword') ?>" title="Cambia tu contraseña">Seguridad</a></li>
                    <li><a href="<?php echo url_for('main/logout') ?>" title="Salir">Salir</a></li>
                <?php else: ?>
                    <li><a href="<?php echo url_for('main/register') ?>" title="Regístrate">Regístrate</a></li>
                    <li><a href="<?php echo url_for('main/login') ?>" title="Ingresar">Ingresar</a></li>
                <?php endif ?>
            </ul>
        </div>

        <div class="hidden-xs col-sm-3 col-md-3">
            <h1>Arriendo de autos en Santiago</h1>
            <ul>
                <li><a href="<?php echo url_for('rent_a_car_region_commune', array('region' => 'region-metropolitana', 'commune' => 'providencia'), true) ?>">Rent a Car Providencia</a></li>
                <li><a href="<?php echo url_for('rent_a_car_region_commune', array('region' => 'region-metropolitana', 'commune' => 'la-florida'), true) ?>">Rent a Car La Florida</a></li>
                <li><a href="<?php echo url_for('rent_a_car_region_commune', array('region' => 'region-metropolitana', 'commune' => 'nunoa'), true) ?>">Rent a Car Ñuñoa</a></li>
                <li><a href="<?php echo url_for('rent_a_car_region_commune', array('region' => 'region-metropolitana', 'commune' => 'santiago-centro'), true) ?>">Rent a Car Santiago Centro</a></li>
            </ul>
        </div>

        <div class="hidden-xs col-sm-3 col-md-3">
            <h1>Acerca de Arriendas</h1>
            <ul>
                <li><a href="<?php echo url_for('main/compania') ?>" title="La compañía Arriendas.cl">La Compañía</a></li>
                <li><a href="<?php echo url_for('main/terminos') ?>" target="_blank" title="Términos y Condiciones">Términos y Condiciones</a></li>
                <li><a href="https://arriendascl.zendesk.com/anonymous_requests/new" target="_blank" title="Contacto">Contacto</a></li>
                <li><a href="https://arriendascl.zendesk.com/forums" target="_blank" title="Foro">Foro</a></li>
            </ul>
        </div>

        <div class="hidden-xs col-sm-3 col-md-3">
            <h1>Herramientas</h1>
            <ul>
                <li><a href="<?php echo url_for('profile/addCar') ?>" title="Sube tu auto">Sube tu auto</a></li>
                <li><a href="<?php echo url_for('/#section-map-form-search') ?>" title="Busca un auto">Busca un auto</a></li>
                <li><a class="item_thm fancybox" href="<?php echo url_for('main/valueyourcar') ?>">¿Cuánto puedo ganar con mi auto?</a></li>
            </ul>
        </div>
    </div>
    
    <?php if(date("H:i") > "19:00" || date("H:i") < "09:00"):
            $telefono = "tel:0223333714";
            $telefonoText = "(02) 2333-3714"; 
          else:
            $telefono = "tel:0226402900";
            $telefonoText = "(02) 2 640 29 00";
          endif;
    ?>
               
    
    <div class="row">
        <div class="hidden-xs col-sm-12 col-md-12 text-center" id="address"> 
            <p class= "direccion">Manuel Montt 1404, Providencia, Santiago de Chile</p>
            <p>
                <i class="fa fa-phone"></i> <a href="<?php echo $telefono?>"><?php echo $telefonoText?></a> 
                <i class="fa fa-support"></i> <a href="mailto:soporte@arrienda<?php echo $telefonoText?>s.cl">soporte@arriendas.cl</a>
            </p>
        </div>
    </div>

    <div class="row">      
        <div class="hidden-xs col-sm-12 col-md-12 text-center">
            <h3 class = "follow"><span>Síguenos en</span></h3>
        </div>
    </div>

    <div class="row">
        <div class="hidden-xs col-sm-12 col-md-12 text-center">
        <p>
                <a class="fa fa-facebook fa-3x" href="https://www.facebook.com/arriendaschile?fref=ts">facebook</a>
                <a class="fa fa-twitter fa-3x" href="https://twitter.com/arriendas">twitter</a>
        </p>
        </div>
    </div>

    <div class="row">      
        <div class="col-xs-12 visible-xs hidden-sm visible-md">
            <h1><strong>Contacto</strong><h1>
            <p><a href=<?php echo $telefono?>><?php echo $telefonoText?></a></p>
            <p>Manuel Montt 1404</p>
            <p>Providencia, Santiago/Chile </p>
            <a class="fa fa-facebook fa-3x" href="https://www.facebook.com/arriendaschile?fref=ts" target="_blank">facebook</a>
            <a class="fa fa-twitter fa-3x" href="https://twitter.com/arriendas" target="_blank">twitter</a>
        </div>
    </div>
</footer>


