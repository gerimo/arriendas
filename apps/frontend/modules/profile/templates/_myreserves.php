<!--<pre>
<?php //print_r($reservasVisibles[0]);die;?>
</pre>-->
<script>
    function eliminar(idReserva){
        
        if(confirm('\xBF Est\xE1 seguro ?')){
        
            var url = <?php echo "'" . url_for('profile/deleteReserve') . "'"; ?>;
            
            $.ajax({
               type: "POST",
               url: url,
               data: 'idReserva=' + idReserva,
               success: function(data){
                   if(data=='ok'){
                       location.reload(true);
                   } else {
                       alert('Error al eliminar reserva');
                   }
               }
            });
        
        }
    }
</script>
<style>
.misArriendos {
    border: 1px solid #DCDCDC;
    clear: both;
    margin: 16px;
    width: 520px;
}
.misArriendos th, .misArriendos td {
    background-color: #FFFFFF;
    border-bottom: 1px solid #999999;
    padding: 5px;
    text-align: center;
    vertical-align: middle;
}
.misArriendos th {
    background-color: #EBEBEC;
    color: #575757;
    font-size: 12px;
}
.misArriendos td {
    color: #575757;
    font-size: 12px;
    line-height: 18px;
    text-align: center;
}
</style>

<?php if ($cantidadReservas > 0) : ?>
    <table cellspacing="1" cellpadding="1" border="1" class="misArriendos">
        <tr>
            <th colspan="3">Mis reservas m&aacute;s pr&oacute;ximas</th>
        </tr>
        <tr>
            <th>Desde</th>
            <th>Hasta</th>
            <th>&nbsp;</th>
        </tr>
        <?php foreach ($reservasVisibles as $r) : ?>
            <tr>
                <td><?= $r->getDate() ?></td>
                <td><?= $r->getDateend(); ?></td>
                <td>
                    <?= link_to(image_tag('img_mis_reservas/edit.png', 'size=20x20'), 'profile/detalleReserva?id='.$r->getId(), 'title="Modificar"') ?>
                    <?= image_tag('img_mis_reservas/delete.png', 'size=20x20 onclick="eliminar('.$r->getId().')"')?>
                </td>
            </tr>
            <?php $i++; ?>
        <?php endforeach; ?>
        <tr>
            <th colspan="3"><a href="<?= url_for('profile/myReserves') ?>">Ver todos</a> (<?= $cantidadReservas ?>)</th>
        </tr>
    </table>
<?php endif; ?>
