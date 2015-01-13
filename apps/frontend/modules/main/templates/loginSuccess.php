<link href="/css/newDesign/login.css" rel="stylesheet" type="text/css">

<div class="hidden-xs space-100"></div>
<div class="visible-xs space-50"></div>

<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-offset-4 col-md-4 BCW">

        <div class="text-center">
            <h2 class="fb-title">Regístrate o Ingresa con Facebook</h2>
            <a href="<?php echo url_for("main/loginFacebook")?>" style="margin-top: 15px"><?php echo image_tag('img_login/login_btn_fb') ?></a>
        </div>

        <hr>

        <div class="text-center">
            <h2>Ingresa con tu usuario Arriendas</h2>
            <form action="<?php echo url_for("login_do") ?>" class="clearfix" id="login-form" method="post">

                <input class="form-control" id="username" name="username" placeholder="Correo electrónico" type="text">

                <input class="form-control" id="password" name="password" placeholder="Contraseña" type="password">

                <?php if($sf_user->getFlash('show')): ?>
                    <p class="error-message" >
                        <?php echo html_entity_decode($sf_user -> getFlash('msg')); ?>
                    </p>
                <?php endif ?>

                <div class="row">
                    <div class="col-md-6" style="padding: 0">
                        <a class="pull-left forgot" href="<?php echo url_for('main/forgot')?>">&iquest;Olvidaste tu clave?</a>
                    </div>
                    <div class="col-md-6" style="padding: 0"> 
                        <input class="btn-block btn-a-primary pull-right" type="submit" value="Ingresar">
                    </div>
                </div>
            </form>
        </div>

        <div class="text-center register-here">
            <a href="<?php echo url_for('main/register')?>">Registrate aquí</a>
        </div>
    </div>
</div>

<div class="hidden-xs space-100"></div>