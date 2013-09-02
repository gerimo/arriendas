<?php if (count($userdata) > 0): ?>
    <div class="user_info">
        <ul class="notif_validado">
            <?php $cont = 0; ?>
            <?php for ($i = 0; $i < count($userdata); $i++): ?>
                <?php if ($userdata[$i][0] == false): ?>
                    <li><a href="<?=url_for('profile/edit')?>"><?php echo $userdata[$i][1]; ?></a></li>
                    <?php $cont++; ?>
                <?php endif; ?>
            <?php endfor; ?>
        </ul>
    </div>
    <div id="dialog" style="display: none"><?php echo $cont; ?></div>
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
