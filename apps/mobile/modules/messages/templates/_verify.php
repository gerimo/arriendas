<?php if (count($userdata) > 0): ?>
    <div class="user_info">
        <ul class="notif_validado">
            <?php
                if($cantCalificaciones && $cantCalificaciones > 0){
                    if($cantCalificaciones == 1){
                        echo "<li><a href='".url_for('profile/calificaciones')."'>Tienes $cantCalificaciones calificación pendiente</a></li>";
                    }else{
                        echo "<li><a href='".url_for('profile/calificaciones')."'>Tienes $cantCalificaciones calificaciones pendientes</a></li>";
                    }
                }
            ?>
            <?php $cont = 0; ?>
            <?php for ($i = 0; $i < count($userdata); $i++): ?>
                <?php if ($userdata[$i][0] == false): ?>
                    <li><a href="<?=url_for($userdata[$i][2])?>"><?php echo $userdata[$i][1]; ?></a></li>
                    <?php $cont++; ?>
                <?php endif; ?>
            <?php endfor; ?>
        </ul>
    </div>
    <div id="dialog" style="display: none"><?php echo $cont+$cantCalificaciones; ?></div>
<?php endif; ?>
<script>
    var contVerify = $('#dialog').html();
    if(contVerify > 0){
        $('.dialog#globe').html(contVerify); 
        $('.dialog#globe').css('display','block');
    } else {
        $('.dialog#globe').css('display','none');
    }
</script>
