<?php use_stylesheet('registro.css') ?>
<?php use_stylesheet('comunes.css') ?>
<script>
    function submitFrom()
    {
        document.forms["frm1"].submit();
    }
</script>
<style type="text/css">
#FondoReferidos{
    margin: 0 auto;
    width: 920px;
}
#subFondo{
    background-color: white;
    float: left;
    width: 920px;
    padding: 20px;
    height: auto ! important;
    min-height: 200px;
    -webkit-border-radius: 15px;
    -moz-border-radius: 15px;
    border-radius: 15px;
    -webkit-box-shadow: 1px 1px 10px rgba(50, 50, 50, 0.4);
    -moz-box-shadow: 1px 1px 10px rgba(50, 50, 50, 0.4);
    box-shadow: 1px 1px 10px rgba(50, 50, 50, 0.4);
    padding-bottom: 40px;
}
#Enunciado{
    float: left;
    width: 800px;
    text-align: center;
    font-size: 17px;
    margin-left: 50px;
    font-style: italic;
    line-height: 14px;
    margin-top: 16px;
}
#botonCargar{
    width: 166px;
    margin: 0 auto;
    height: 74px;
    margin-top: 134px;
}
input#botonVolver{
    margin: 0px;
    border: 0px;
    padding: 0px;
    cursor: pointer;
    background-image: url("http://www.arriendas.cl/images/Home/BotonIngresa.png");
    background-color: white;
    width: 164px;
    height: 72px;
}

</style>

<div id="FondoReferidos">
    <div id="subFondo">
        <div id="Enunciado">
            <b style="color:#EC008C;font-size:26px;">Registro de usuarios</b><br><br>
            <p style="margin-top:20px;">
                Gracias por registrarse en Arriendas.cl<br><br>
                Hemos enviado un link a tu e-mail para que verifiques tu cuenta.
            </p>
        </div>
    <br /><br />
        <div class="regis_botones" style="margin-left: 324px;">
            <!--<button class="regis_btn_formulario left">� Anterior</button>-->
            <button class="regis_btn_formulario right" onclick="location.href='<?php echo url_for('main/registerClose') ?>'">Finalizar</button>        
        </div><!-- regis_botones -->
    </div>
</div>

    <!--
    <div class="regis_box_usuario">
        <div class="regis_foto_frame">
            <?php if ($user != NULL): ?>

                <?php if ($user->getPictureFile() != NULL): ?>
                    <?= image_tag("users/" . $user->getFileName(), 'size=194x204') ?>
                <?php else: ?>
                    <?= image_tag('img_registro/tmp_user_foto.jpg', 'size=194x204') ?>
                <?php endif; ?>

            <?php else: ?>

                <?= image_tag('img_registro/tmp_user_foto.jpg', 'size=194x204') ?>

            <?php endif; ?>


        </div>

    </div>
    -->

<div class="clear"></div>





