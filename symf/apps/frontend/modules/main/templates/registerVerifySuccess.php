<?php use_stylesheet('registro.css') ?>
<?php use_stylesheet('comunes.css') ?>
<script>
    function submitFrom()
    {
        document.forms["frm1"].submit();
    }
</script>

<div class="main_box_1">
	<div class="main_box_2">
    <h1 class="calen_titulo_seccion">Registro de usuarios</h1>


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

        <!--  <h2 class="regis_usuario">Katia Dolizniak</h2>-->

        <!-- <ul class="regis_validar">
             <li>Validar direccion</li>
             <li>Validar direccion</li>
             <li>Validar direccion</li>
             <li>Validar direccion</li>        
         </ul>
         
        -->

    </div><!-- regis_box_usuario -->


    <!-- sector datos de usuario -->
    <div class="regis_box_info">     

        <div class="regis_titulo_1" style="display:none">Validaciones</div>

        <div style="font-size:12px; padding:20px; padding-top:0px; display:table;"><?php echo $sf_data->getRaw('info') ?></div>


        <div class="regis_titulo_verif">
            <span>
                Verificado
            </span>    
        </div><!-- regis_titulo_verif -->

        <?php for ($i = 0; $i < count($userdata); $i++): ?>
            <?php if ($userdata[$i][0] == true): ?>

                <div class="regis_item_verif">
                    <span><?php echo $userdata[$i][1]; ?></span>
                </div><!-- regis_titulo_verif -->
            <?php endif; ?>
        <?php endfor; ?>



        <div class="regis_titulo_sinverif">
            <span>Sin verificar</span>    
        </div><!-- regis_titulo_verif -->


        <?php for ($i = 0; $i < count($userdata); $i++): ?>
            <?php if ($userdata[$i][0] == false): ?>
                <div class="regis_item_sinverif">
                    <span><?php echo $userdata[$i][1]; ?></span>
                </div><!-- regis_titulo_verif -->
            <?php endif; ?>	
        <?php endfor; ?>

        <div class="regis_botones">
            <!--<button class="regis_btn_formulario left">� Anterior</button>-->
            <button class="regis_btn_formulario right" onclick="location.href='<?php echo url_for('main/registerClose') ?>'">Finalizar</button>        
        </div><!-- regis_botones -->

    </div><!-- regis_box_info -->


    <div class="clear"></div>
    </div>
</div><!-- /regis_box -->






