<?php if ($user->getFacebookId() != "") :?>
<img src="<?echo $user->getPictureFile();?>?type=large" <?=$params?>/>
<?php else : ?>
  <?php if ($user->getPictureFile() != NULL): ?>
     <?= image_tag("users/".$user->getFileName(),$params) ?>
  <?php else: ?>
    <?= image_tag('img_registro/tmp_user_foto.jpg',$params) ?>
  <?php endif;?>
<?php endif; ?>
