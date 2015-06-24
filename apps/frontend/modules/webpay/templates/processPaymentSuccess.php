<form action="<?php echo $voucherUrl ?>" method="post" id="voucher">
    <input type="hidden" name="token_ws" value="<?php echo $token ?>"/>
</form>

<h1>Validando pago</h1>
<p>Un momento por favor...</p>

<script>
    $(document).ready(function(){
    	$("#voucher").submit();
    });
</script>