<?php if ($trans->Count()) :?>
<?php foreach ($trans as $car) :?>
<div class="user_item <?=($car->getCompleted() == 0) ? "alert_mess_unread" : ""?>"  onclick="window.location='<?=url_for("profile/transactions")?>'">
     <div class="user_post" >
        <div class="user_nombre">
		<?=$car->getUser()->getFirstName()?> <?=  substr($car->getUser()->getLastName(), 0, 1)?>.
		</div>
        <div class="user_body">
        	<b>Auto:</b> <?=$car->getCar()?> <br/>
        </div>
        <div class="user_fecha"><?=$car->getDate()?></div>
      </div><!-- msg_user_post -->
    <div class="clear"></div>
</div><!-- msg_item -->
<?php endforeach;?>
<?php else:?>
<div class="alerts_noresults"></div>
<?php endif;?>
