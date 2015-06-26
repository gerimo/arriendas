<form action="<?php echo $voucherUrl ?>" method="post" id="voucher">
    <input type="hidden" name="token_ws" value="<?php echo $token ?>"/>
</form>

<br><br><br>
<h1 class="text-center">Validando pago</h1>
<br>
<p class="text-center">Un momento por favor...</p>
<br><br><br><br>

<script>
    $(document).ready(function(){
    	$("#voucher").submit();
    });
</script>