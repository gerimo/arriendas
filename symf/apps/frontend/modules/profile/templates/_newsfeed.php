<div class="texto_normal" style="margin-top:20px; margin-bottom: 20px;">TU></div>
<?php if(count($userdata) > 0):?>
<p class="subtitulos">Actualiza tus datos</p>
<ul class="list_der">
    <?php for ($i = 0; $i < count($userdata); $i++): ?>
        <?php if ($userdata[$i][0] == false): ?>
            <li><a href="<?php echo url_for('profile/edit') ?>" title="<?php echo $userdata[$i][1]; ?>"><?php echo $userdata[$i][1]; ?></a></li>
        <?php endif; ?>	
    <?php endfor; ?>
</ul>
<?php endif;?>
<?php if (count($messages) > 0): ?>
<p class="grupo_nf">Nuevos Mensajes</p>	
<ul class="list_der">
	<?php foreach ($messages as $mess) :?>
	<li><a href="<?php echo url_for('messages/inbox') ?>" title="Nuevo Mensaje">De: <?php echo substr($mess->getUser(),0,17).'...'?></a></li>
	<?php endforeach;?>
</ul>
<?php endif;?>
<?php if (count($toconfirm) > 0): ?>
<p class="grupo_nf">Reservas por confirmar</p>
<ul class="list_der">
	<?php foreach ($toconfirm as $reserve): ?>
	<li><a href="" title="Confirmación de reserva de un auto">De: <?= link_to($reserve->getUser(), 'profile/rentalToConfirm', array('title' => 'Confirmar')) ?></a></li>
	<?php endforeach;?>
</ul>
<?php endif;?>