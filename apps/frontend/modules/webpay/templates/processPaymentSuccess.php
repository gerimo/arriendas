<form action="<?php echo $voucherUrl ?>" method="post" id="voucher">
    <input type="hidden" name="token_ws" value="<?php echo $token ?>"/>
</form>

<script>
    $(document).ready(function(){
        $("#voucher").submit();
    });
</script>