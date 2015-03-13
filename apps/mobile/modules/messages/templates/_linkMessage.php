<?php if ($user->getId() != $actual):?>
<?php echo $user->getFirstname() . " ". substr($user->getLastname(), 0, 1); ?><?php echo link_to("(Mensaje)","messages/new?id=".$user->getId(),array("style"=>"text-decoration:none;","class"=>"msg_mail_icon"))?>
<?php else:?>
<?php echo $user->getFirstname() . " ". substr($user->getLastname(), 0, 1) ?>.
<?php endif;?>
