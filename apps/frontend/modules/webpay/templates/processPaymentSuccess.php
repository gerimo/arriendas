<div style="background-image: url('/images/webpay.gif'); background-repeat: repeat">
    <form action="<?php echo $voucherUrl ?>" method="post" id="voucher">
        <input type="hidden" name="token_ws" value="<?php echo $token ?>"/>
    </form>
</div>

<script>
    $(document).ready(function(){
        $("#voucher").submit();
    });
</script>