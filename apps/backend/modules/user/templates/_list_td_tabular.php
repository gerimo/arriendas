<td class="sf_admin_text sf_admin_list_td_Id">
  <?php echo link_to($user->getId(), 'user_edit', $user) ?>
</td>
<td class="sf_admin_text sf_admin_list_td_lastname">
  <?php echo $user->getLastname() ?>
</td>
<td class="sf_admin_text sf_admin_list_td_firstname">
  <?php echo $user->getFirstname() ?>
</td>
<td class="sf_admin_text sf_admin_list_td_email">
  <a href="mailto:<?php echo $user->getEmail() ?>"><?php echo $user->getEmail() ?></a>
</td>
<td class="sf_admin_text sf_admin_list_td_telephone">
  <?php echo $user->getTelephone() ?>
</td>
<td class="sf_admin_text sf_admin_list_td_facebook_id">
  <a href="http://www.facebook.com/<?php echo $user->getFacebookId() ?>" target="_blank"><?php echo $user->getFacebookId() ?></a>
</td>
<td class="sf_admin_text sf_admin_list_td_address">
  <?php echo $user->getAddress() ?>
</td>
<td class="sf_admin_text sf_admin_list_td_confirmed">
  <?php echo $user->getConfirmed() ?>
</td>
<td class="sf_admin_text sf_admin_list_td_propietario">
  <?php echo $user->getPropietario() ?>
</td>
<td class="sf_admin_text sf_admin_list_td_propietario">
  <?php echo $user->getMessages() ?>
</td>
<td class="sf_admin_text sf_admin_list_td_propietario">
  <?php echo $user->getRecommender() ?>
</td>
<td class="sf_admin_text sf_admin_list_td_propietario">
  <?php if($user->getPictureFile() == "" | is_null($user->getPictureFile())) {echo image_tag("no.png");} else {echo image_tag("Check.png");} ?>
</td>
<td class="sf_admin_text sf_admin_list_td_propietario">
  <?php if($user->getDriverLicenseFile() == "" | is_null($user->getDriverLicenseFile())) {echo image_tag("no.png");} else {echo image_tag("Check.png");} ?>
</td>
<td class="sf_admin_text sf_admin_list_td_propietario">
  <?php if($user->getRutFile() == "" | is_null($user->getRutFile())) {echo image_tag("no.png");} else {echo image_tag("Check.png");} ?>
</td>
