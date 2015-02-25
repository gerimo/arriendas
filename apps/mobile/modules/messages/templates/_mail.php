<?php if ($mensaje->Count()) :?>
<?php $cont = 0;?>
<?php foreach ($mensaje as $mess) :?>
<?php if(!$mess->getReceived()):?>
<div class="user_item <?=($mess->getReceived() == 0) ? "alert_mess_unread" : ""?> mensaje" onclick="window.location='<?=url_for("messages/conversation?id=".$mess->getConversationId())?>'">
    <div class="user_frame">
      <?php include_component("profile","pictureFile",array("user"=>$mess->getUser(),"params"=>""));?>
    </div>
     <div class="user_post" onclick="gotoConversation('<?=$mess->getConversationId()?>','<?=$mess->getId()?>')">
        <div class="user_nombre">
		<?=$mess->getUser()->getFirstName()?> <?=  substr($mess->getUser()->getLastName(), 0, 1)?>.
		</div>
        <div class="user_body"><?=$mess->getBody()?></div>
        <div class="user_fecha"><?=$mess->getDateEs()?></div>
      </div><!-- msg_user_post -->
    <div class="clear"></div>
</div><!-- msg_item -->
<?php $cont++; ?>
<?php endif;?>
<?php endforeach;?>
<div id="dialog_mail" style="display: none"><?php echo $cont; ?></div>
<script>
    var cont = $('#dialog_mail').html();
    if(cont > 0){
        $('.dialog#mail').html(cont); 
        $('.dialog#mail').css('display','block');
    } else {
        $('.dialog#mail').css('display','none');
    }
</script>
<?php else:?>
<div class="alerts_noresults"></div>
<?php endif;?>
