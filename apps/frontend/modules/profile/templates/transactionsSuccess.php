<link href="/css/newDesign/transactions.css" rel="stylesheet" type="text/css">

<div class="hidden-xs space-100"></div>
<div class="visible-xs space-50"></div>

<div class="row">
    <div class="col-md-offset-1 col-md-10">

        <div class="BCW">

            <?php if (count($TransactionsRenter) > 0): ?>
                <h1>Transacciones como Arrendatario</h1>
                
                <table class="display responsive no-wrap" id="transactions-renter" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th>Fecha</th>
                            <th>Monto</th>
                            <th>Comisión Arriendas.cl</th>
                            <th>Precio seguro</th>
                            <th>Neto</th>
                            <th>Depósito en garantía</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($TransactionsRenter as $T): ?>
                            <tr>
                                <td><small><?php echo date("Y-m-d H:i", strtotime($T['fechaInicio'])) ?><br><?php echo date("Y-m-d H:i", strtotime($T['fechaTermino'])) ?></small></td>
                                <td>$<?php echo $T['monto'] ?> CLP</td>
                                <td>$<?php echo $T['comisionArriendas'] ?> CLP</td>
                                <td>$<?php echo $T['precioSeguro'] ?> CLP</td>
                                <td>$<?php echo $T['neto'] ?> CLP</td>
                                <td>$<?php echo $T['depositoGarantia'] ?> CLP</td>
                            </tr>
                        <?php endforeach ?>
                    </tbody>
                </table>
            <?php endif ?>

            <?php if (count($TransactionsOwner) > 0): ?>
                <h1>Transacciones como dueño</h1>

                <table class="dt display responsive no-wrap" id="transactions-owner" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th>Fecha</th>
                            <th>Monto</th>
                            <th>Comisión Arriendas.cl</th>
                            <th>Precio seguro</th>
                            <th>Neto</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($TransactionsOwner as $T): ?>
                            <tr>
                                <td><small><?php echo date("Y-m-d H:i", strtotime($T['fechaInicio'])) ?><br><?php echo date("Y-m-d H:i", strtotime($T['fechaTermino'])) ?></small></td>
                                <td>$<?php echo $T['monto'] ?> CLP</td>
                                <td>$<?php echo $T['comisionArriendas'] ?> CLP</td>
                                <td>$<?php echo $T['precioSeguro'] ?> CLP</td>
                                <td>$<?php echo $T['neto'] ?> CLP</td>
                            </tr>
                        <?php endforeach ?>
                    </tbody>
                </table>
            <?php else: ?>
                <?php if (count($TransactionsRenter) == 0): ?>
                    <h2 class="text-center">No tienes transacciones registradas</h2>
                <?php endif ?>
            <?php endif ?>
        </div>
    </div>
</div>

<div class="hidden-xs space-100"></div>

<script>
    $(document).ready(function(){
    
        $('#transactions-renter, #transactions-owner').DataTable({
            info: false,
            paging: false,
            responsive: true,
            searching: false
        });    
    });
</script>