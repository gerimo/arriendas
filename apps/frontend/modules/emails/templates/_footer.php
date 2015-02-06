<br><br>
<p style='color: #aaa; font-size:14px; margin: 0; padding: 3px 0 0 0'>Atentamente</p>
<p style='color: #aaa; font-size:14px; margin: 0; padding: 3px 0 0 0'>Equipo Arriendas.cl</p>
<br><br>
<?php if (isset($userId) && isset($mailingId)): ?>

    <?php
        error_log("HOST: ".$_SERVER ['HTTP_HOST']);
        $host = "http://local.arriendas.cl";
        if ($_SERVER ['HTTP_HOST'] == "www.arriendas.cl") {
            $host = "http://www.arriendas.cl";
        } elseif ($_SERVER ['HTTP_HOST'] == "dev.arriendas.cl") {
            $host = "http://dev.arriendas.cl";
        }
    ?>

    <?php $signature = md5("Arriendas.cl ~ ".$userId." ~ ".$mailingId) ?>
    <?php $url = str_replace("symfony", "", url_for('emails_unsubscribe', array("user_id" => $userId, "mailing_id" => $mailingId, "signature" => $signature))) ?>

    <?php if ($mailingId == 1): ?>
        <p style='color: #aaa; font-size:10px; margin: 0; padding: 3px 0 0 0'>Si no deseas recibir más notificaciones de oportunindades has click <a href="<?php echo $host.$url ?>">aquí</a>.</p>
    <?php elseif ($mailingId == 2): ?>
        <p style='color: #aaa; font-size:10px; margin: 0; padding: 3px 0 0 0'>Si no deseas recibir más solicitudes de disponibilidad has click <a href="<?php echo $host.$url ?>">aquí</a>.</p>
    <?php endif ?>
<?php endif ?>
