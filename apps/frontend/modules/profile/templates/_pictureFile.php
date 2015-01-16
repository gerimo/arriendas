<?php if ($user->getFacebookId() != ""): ?>
    <img src="http://res.cloudinary.com/arriendas-cl/image/facebook/w_<?=$width?>,h_<?=$height?>,c_fill,g_face/<?php echo $user->getFacebookId();?>.jpg" <?=$params?> />
<?php else : ?>
    <?php if ($user->getPictureFile() != NULL): ?>
        <img src="http://res.cloudinary.com/arriendas-cl/image/fetch/w_<?=$width?>,h_<?=$height?>,c_fill,g_face/http://arriendas.cl/<?php echo "images/users/".$user->getFileName() ?>" <?=$params?> />
    <?php else: ?>
        <?php echo image_tag('img_registro/tmp_user_foto.jpg',$params) ?>
    <?php endif ?>
<?php endif ?>
