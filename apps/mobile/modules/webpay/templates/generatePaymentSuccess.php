<form action="<?php echo $checkOutUrl ?>" method="post" id="checkform">
    <input type="hidden" name="token_ws" value="<?php echo $checkOutToken ?>"/>
</form>

<script>
    $(document).ready(function(){
    	$("#checkform").submit();
    });
</script>