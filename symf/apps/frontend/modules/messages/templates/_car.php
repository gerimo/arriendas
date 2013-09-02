<?php $cont = 0; ?>
<?php if ($cars->Count()) : ?>
    <?php foreach ($cars as $car) : ?>
        <div class="car_item user_item <?= ($car->getComplete() == 0) ? "alert_mess_unread" : "" ?>"  onclick="window.location='<?= url_for("profile/rentalToConfirm") ?>'">
            <div class="user_frame">
                <?php if ($car->getCar()->getPhotoFile('main') != null): ?>
                    <?php echo image_tag("cars/" . $car->getCar()->getPhotoFile('main')->getFileName()) ?>
                <?php else: ?>
                    <?= image_tag('default.png'); ?>
                <?php endif; ?>
            </div>
            <div class="user_post" >
                <div class="user_nombre">
                    <?= $car->getUser()->getFirstName() ?> <?= substr($car->getUser()->getLastName(), 0, 1) ?>.
                </div>
                <div class="user_body">
                    <b>Auto:</b> <?= $car->getBrand() ?> <?= $car->getModel() ?> (<?= $car->getYear() ?>)<br/>
                </div>
                <div class="user_fecha"><?= $car->getDate() ?></div>
            </div><!-- msg_user_post -->
            <div class="clear"></div>
        </div><!-- msg_item -->
        <?php $cont++; ?>
    <?php endforeach; ?>
<?php endif; ?>
<?php foreach ($porconfirmarkmsiniciales as $car) : ?>
    <div class="car_item user_item <?= ($car->getComplete() == 0) ? "alert_mess_unread" : "" ?>"  onclick="window.location='<?= url_for("profile/rental") ?>'">
        <div class="user_frame">
            <?php if ($car->getCar()->getPhotoFile('main') != null): ?>
                <?php echo image_tag("cars/" . $car->getCar()->getPhotoFile('main')->getFileName()) ?>
            <?php else: ?>
                <?= image_tag('default.png'); ?>
            <?php endif; ?>
        </div>
        <div class="user_post" >
            <div class="user_nombre">
                <?= $car->getUser()->getFirstName() ?> <?= substr($car->getUser()->getLastName(), 0, 1) ?>.
            </div>
            <div class="user_body">
                <b>Auto:</b> <?= $car->getBrand() ?> <?= $car->getModel() ?> (<?= $car->getYear() ?>)<br/>
            </div>
            <div class="user_fecha"><?= $car->getDate() ?></div>
        </div><!-- msg_user_post -->
        <div class="clear"></div>
    </div><!-- msg_item -->
    <?php $cont++; ?>
<?php endforeach; ?>
<?php foreach ($porconfirmarkmsfinales as $car) : ?>
    <div class="car_item user_item <?= ($car->getComplete() == 0) ? "alert_mess_unread" : "" ?>"  onclick="window.location='<?= url_for("profile/rental") ?>'">
        <div class="user_frame">
            <?php if ($car->getCar()->getPhotoFile('main') != null): ?>
                <?php echo image_tag("cars/" . $car->getCar()->getPhotoFile('main')->getFileName()) ?>
            <?php else: ?>
                <?= image_tag('default.png'); ?>
            <?php endif; ?>
        </div>
        <div class="user_post" >
            <div class="user_nombre">
                <?= $car->getUser()->getFirstName() ?> <?= substr($car->getUser()->getLastName(), 0, 1) ?>.
            </div>
            <div class="user_body">
                <b>Auto:</b> <?= $car->getBrand() ?> <?= $car->getModel() ?> (<?= $car->getYear() ?>)<br/>
            </div>
            <div class="user_fecha"><?= $car->getDate() ?></div>
        </div><!-- msg_user_post -->
        <div class="clear"></div>
    </div><!-- msg_item -->
    <?php $cont++; ?>
<?php endforeach; ?>
<?php foreach ($porconfirmarkmsinicialesuser as $car) : ?>
    <div class="car_item user_item <?= ($car->getComplete() == 0) ? "alert_mess_unread" : "" ?>"  onclick="window.location='<?= url_for("profile/rental") ?>'">
        <div class="user_frame">
            <?php if ($car->getCar()->getPhotoFile('main') != null): ?>
                <?php echo image_tag("cars/" . $car->getCar()->getPhotoFile('main')->getFileName()) ?>
            <?php else: ?>
                <?= image_tag('default.png'); ?>
            <?php endif; ?>
        </div>
        <div class="user_post" >
            <div class="user_nombre">
                <?= $car->getUser()->getFirstName() ?> <?= substr($car->getUser()->getLastName(), 0, 1) ?>.
            </div>
            <div class="user_body">
                <b>Auto:</b> <?= $car->getBrand() ?> <?= $car->getModel() ?> (<?= $car->getYear() ?>)<br/>
            </div>
            <div class="user_fecha"><?= $car->getDate() ?></div>
        </div><!-- msg_user_post -->
        <div class="clear"></div>
    </div><!-- msg_item -->
    <?php $cont++; ?>
<?php endforeach; ?>
<?php foreach ($porconfirmarkmsfinalesuser as $car) : ?>
    <div class="car_item user_item <?= ($car->getComplete() == 0) ? "alert_mess_unread" : "" ?>"  onclick="window.location='<?= url_for("profile/rental") ?>'">
        <div class="user_frame">
            <?php if ($car->getCar()->getPhotoFile('main') != null): ?>
                <?php echo image_tag("cars/" . $car->getCar()->getPhotoFile('main')->getFileName()) ?>
            <?php else: ?>
                <?= image_tag('default.png'); ?>
            <?php endif; ?>
        </div>
        <div class="user_post" >
            <div class="user_nombre">
                <?= $car->getUser()->getFirstName() ?> <?= substr($car->getUser()->getLastName(), 0, 1) ?>.
            </div>
            <div class="user_body">
                <b>Auto:</b> <?= $car->getBrand() ?> <?= $car->getModel() ?> (<?= $car->getYear() ?>)<br/>
            </div>
            <div class="user_fecha"><?= $car->getDate() ?></div>
        </div><!-- msg_user_post -->
        <div class="clear"></div>
    </div><!-- msg_item -->
    <?php $cont++; ?>
<?php endforeach; ?>
<div id="dialog_car" style="display: none"><?php echo $cont; ?></div>
<script>
    var cont = $('#dialog_car').html();
    if(cont > 0){
        $('.dialog#car').html(cont); 
        $('.dialog#car').css('display','block');
    } else {
        $('.dialog#car').css('display','none');
    }
</script>
