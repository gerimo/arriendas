<td class="sf_admin_text sf_admin_list_td_Id">
  <?php echo link_to($reserve->getId(), 'reserve_edit', $reserve) ?>
</td>
<td class="sf_admin_text sf_admin_list_td_User" style="background-color: <?php echo $reserve->getColor(); ?>">
  <?php echo $reserve->getEstado(); ?>
</td>
<td class="sf_admin_text sf_admin_list_td_Date">
  <?php echo $reserve->getDate() ?>
</td>
<td class="sf_admin_text sf_admin_list_td_Duration">
  <?php echo $reserve->getDuration() ?>
</td>
<td class="sf_admin_text sf_admin_list_td_Car">
  <?php echo $reserve->getDescripcionAuto(); ?>
</td>
<td class="sf_admin_text sf_admin_list_td_User">
  <?php echo $reserve->getUsername_solicitante(); ?>
</td>
<td class="sf_admin_text sf_admin_list_td_User">
  <?php echo $reserve->getMail_solicitante(); ?>
</td>
<td class="sf_admin_text sf_admin_list_td_User">
  <?php echo $reserve->getFono_solicitante(); ?>
</td>
<td class="sf_admin_text sf_admin_list_td_User">
  <?php echo $reserve->getUsername_dueno(); ?>
</td>
<td class="sf_admin_text sf_admin_list_td_User">
  <?php echo $reserve->getMail_dueno(); ?>
</td>
<td class="sf_admin_text sf_admin_list_td_User">
  <?php echo $reserve->getFono_dueno(); ?>
</td>