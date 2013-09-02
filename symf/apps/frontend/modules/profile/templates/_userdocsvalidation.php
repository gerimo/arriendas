<ul class="regis_validado">
    <?php for ($i = 0; $i < count($userdata); $i++): ?>
        <?php if ($userdata[$i][0] == true): ?>
            <li><?php echo $userdata[$i][1]; ?></li>
        <?php endif; ?>
    <?php endfor; ?>
</ul>
<ul class="regis_validar">
    <?php for ($i = 0; $i < count($userdata); $i++): ?>
        <?php if ($userdata[$i][0] == false): ?>
            <li><a href="<?php echo url_for('profile/edit') ?>"><?php echo $userdata[$i][1]; ?></a></li>
        <?php endif; ?>	
    <?php endfor; ?>
</ul>

<a href="<?php echo url_for('profile/edit') ?>" class="btn_editar">Editar</a>
