<form action="<?php echo url_for('khipuNotify') ?>" method="post">
    <!-- <input placeholder="ID Reserva" type="integer" value="<?php echo $reserveId ?>"> -->
    <input placeholder="ID Transacción" name="transaction_id" type="integer" value="<?php echo $transactionId ?>">
    <input type="submit" value="Enviar">
</form>