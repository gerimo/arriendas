<br><br>
<p style='color: #aaa; font-size:14px; margin: 0; padding: 3px 0 0 0'>Atentamente</p>
<p style='color: #aaa; font-size:14px; margin: 0; padding: 3px 0 0 0'>Equipo Arriendas.cl</p>
<br><br>
<?php if (isset($userId) && isset($mailingId)): ?>

    <?php //$host = "http://www.arriendas.cl" ?>

    <?php $signature = md5("Arriendas.cl ~ ".$userId." ~ ".$mailingId) ?>
    <?php $url = url_for('emails_unsubscribe', array("user_id" => $userId, "mailing_id" => $mailingId, "signature" => $signature), true) ?>

    <?php if ($mailingId == 1): ?>
        <p style='color: #aaa; font-size:10px; margin: 0; padding: 3px 0 0 0'>Si no deseas recibir más notificaciones de oportunindades has <a href="<?php echo $url ?>">click aquí</a>.</p>
    <?php elseif ($mailingId == 2): ?>
    <?php endif ?>
<?php endif ?>