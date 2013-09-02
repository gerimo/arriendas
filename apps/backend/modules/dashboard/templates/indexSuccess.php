Dashboard Arriendas.cl

<h1>Calendario de Reservas</h1>
<table border="1" cellpadding="4" cellspacing="0">
<tr>
    <th>Fecha de Reserva</th>
    <th>Solicitudes</th>
    <th>Confirmadas</th>
    <th>Canceladas</th>
    <th>Completadas</th>
    <th>Monto</th>
</tr>
<?php for($i=0;$i<=count($dashboard)-1;$i++): ?>
<tr>
    <td align="center"><?php echo $dashboard[$i]['fecha']; ?></td>
    <td align="center"><?php echo $dashboard[$i]['solicitudes']; ?></td>
    <td align="center"><?php echo $dashboard[$i]['confirmaciones']; ?></td>
    <td align="center"><?php echo $dashboard[$i]['cancelaciones']; ?></td>
    <td align="center"><?php echo $dashboard[$i]['completadas']; ?></td>
    <td align="right"><?php echo "$".number_format($dashboard[$i]['monto'],0,",","."); ?></td>
</tr>
<?php endfor; ?>
</table>