<div class="hidden-xs col-md-offset-3 imagen">
    <img src="/images/newDesign/sitemap.png">
</div>
<div class="clearfix body-url">             
    <div class="span2 sitemap-columns">
        <div class="row">
            <br class="hidden-xs"/>
            <h2 class="sitemap text-center">Arrienda según Comuna</h2>
            <?php foreach ($Region->getCommunes() as $Commune): ?>
                <div class="col-md-4 text-left">
                    <a class="sitemap-links" title="Arriendo de autos en <?php echo $Commune->name?>"href="<?php echo url_for('rent_a_car_region_commune', array('region' => 'region-metropolitana', 'commune' => "$Commune->slug"), true) ?>">Rent a Car <?php echo $Commune->name?></a>
                </div>
            <?php endforeach ?>
        </div>
        <div class="row"> 
            <div class="col-md-4 text-left">
                <h2 class="sitemap">Arrienda según Región</h2>
                <a class="sitemap-links" href="<?php echo url_for('rent_a_car') ?>"title="Arrienda en Santiago">Rent a car en Santiago</a>
            </div>
            <div class="col-md-4 text-left">
                <h2 class="sitemap">Publica tu auto</h2>
                <a class="sitemap-links" href="<?php echo url_for('value_your_car') ?>" title="Monetiza tu auto">¿Cuánto puedo ganar con mi auto?</a><br />
                <a class="sitemap-links"href="<?php echo url_for('car_create') ?>" title="Sube tu auto">Sube tu auto</a><br />
            </div>
            <div class="col-md-4 text-left">
                <h2 class="sitemap">Suscribete</h2>
                <a class="sitemap-links" href="<?php echo url_for('main/register') ?>" title="Regístrate">Regístrate</a><br />
                <a class="sitemap-links" href="<?php echo url_for('main/login') ?>" title="Ingresar">Ingresar</a><br />
            </div>    
        </div>
        <div class="row">
            <div class="col-md-4 text-left">
                <h2 class="sitemap">Nosotros</h2>
                <a class="sitemap-links" href="<?php echo url_for('about_company') ?>" title="La compañía Arriendas.cl">La Compañía</a><br />
                <a class="sitemap-links" href="<?php echo url_for('terms_and_conditions') ?>" target="_blank" title="Términos y Condiciones">Términos y Condiciones</a><br />
            </div>
            <div class="col-md-4 text-left">
                <h2 class="sitemap">Ayuda</h2>
               <a class="sitemap-links" href="http://www.nuevotransporte.cl" target="_blank" title="Blog">Nuestro Blog</a><br />
                <a class="sitemap-links" href="https://arriendascl.zendesk.com/anonymous_requests/new" target="_blank" title="Contacto">Contacto</a><br />
                <a class="sitemap-links" href="https://arriendascl.zendesk.com/forums" target="_blank" title="Foro">Foro</a><br />
            </div>
        </div>
    </div>
     
</div>