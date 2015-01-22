
<?php if ($user->getFacebookId() != ""): ?>
    <img src="http://res.cloudinary.com/arriendas-cl/image/facebook/w_<?php echo $inboxWidth ? $inboxWidth : $width ?>,h_<?php echo $inboxHeight ? $inboxHeight : $height ?>,c_fill,g_face/<?php echo $user->getFacebookId();?>.jpg" <?php echo $params?> />
<?php else : ?>
    <?php if ($user->getPictureFile() != NULL): ?>
        <img src="http://res.cloudinary.com/arriendas-cl/image/fetch/w_<?php echo $inboxWidth ? $inboxWidth : $width ?>,h_<?php echo $inboxHeight ? $inboxHeight : $height ?>,c_fill,g_face/http://www.arriendas.cl/images/users/<?php echo $user->getFileName() ?>" <?php echo $params?> />
    <?php else: ?>
        <?php echo image_tag('img_registro/tmp_user_foto.jpg',$params) ?>
    <?php endif ?>
<?php endif ?>
<!--
<?php if ($user->getFacebookId() != ""): ?>
    <img src="http://res.cloudinary.com/arriendas-cl/image/facebook/w_<?php echo $width?>,h_<?php echo $height?>,c_fill,g_face/<?php echo $user->getFacebookId();?>.jpg" <?php echo $params?> />
<?php else : ?>
    <?php if ($user->getPictureFile() != NULL): ?>
        <img src="http://res.cloudinary.com/arriendas-cl/image/fetch/w_<?php echo $width?>,h_<?php echo $height?>,c_fill,g_face/http://arriendas.cl/<?php echo "images/users/".$user->getFileName() ?>" <?php echo $params?> />
    <?php else: ?>
        <?php echo image_tag('img_registro/tmp_user_foto.jpg',$params) ?>
    <?php endif ?>
<?php endif ?>
-->

