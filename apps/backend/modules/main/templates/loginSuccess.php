<link href="/css/newDesign/backend.css" rel="stylesheet" type="text/css">

<div class="hidden-xs space-100"></div>
<div class="visible-xs space-50"></div>

<div class="row">
    <div class="col-md-offset-4 col-md-4">
        
        <h1 class="text-center">Ingresar</h1>

        <form action="<?php echo url_for("login_do") ?>" id="login-form" method="post">

            <input class="form-control" id="username" name="username" placeholder="Correo electrónico" type="text">

            <input class="form-control" id="password" name="password" placeholder="Contraseña" type="password">

            <?php if($sf_user->getFlash('show')): ?>
                <p class="error-message" >
                    <?php echo html_entity_decode($sf_user->getFlash('msg')) ?>
                </p>
            <?php endif ?>

            <input class="btn btn-primary btn-block " type="submit" value="Ingresar">
        </form>
    </div>
</div>

<div class="hidden-xs space-100"></div>