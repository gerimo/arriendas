<?php use_stylesheet('pagoReserva.css') ?>
<div class="main_box_1">
    <div class="main_box_2">
        <div id="fondoPP">
            <?php if ($ppId): ?>
                <form name="pp" action="<?php echo url_for('bcpuntopagos/creacion?id=' . $ppId) ?>" method="post">

                    <div id="detallesPP"  <?php if ($hasDiscountFB): ?>style="height:200px;"<?php endif; ?>>
                        <table id="tableDetallesPP">
                            <thead>
                                <tr>
                                    <th class="bordeDerGris bordeIzqGris" style="width:116px">Nro. de Reserva</th>
                                    <th class="bordeDerGris" style="width:136px">Nro. de Transacción</th>
                                    <th class="bordeDerGris">Item</th>
                                    <th class="bordeDerGris" style="width:120px">Valor</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="bordeDerGris bordeIzqGris"><?= $ppIdReserva; ?></td>
                                    <td class="bordeDerGris"><?= $ppId; ?></td>
                                    <td class="bordeDerGris"><?= $carMarcaModel; ?><?= " / " . $duracionReserva; ?></td>
                                    <td class="bordeDerGris"><?= number_format($ppMonto, 0, ',', '.'); ?> CLP</td>
                                </tr>
                                <tr>
                                    <td class="bordeDerGris bordeIzqGris"></td>
                                    <td class="bordeDerGris"></td>
                                    <td class="bordeDerGris">
                                        <?php
                                        if ($deposito == "depositoGarantia")
                                            echo 'Depósito en Garantía: "Pago mediante Transferencia"';
                                        else if ($deposito == "pagoPorDia")
                                            echo 'Deducible 45.000 CLP: "Pago por día"';
                                        ?>
                                    </td>
                                    <td class="bordeDerGris">
                                        <?php if ($montoDeposito == 0) echo "ERROR";else { ?>
                                            <?= number_format($montoDeposito, 0, ',', '.'); ?> CLP
                                        <?php } ?>
                                    </td>
                                </tr>

                                <?php if ($hasDiscountFB): ?>
                                    <tr>
                                        <td class="bordeDerGris bordeIzqGris"></td>
                                        <td class="bordeDerGris"></td>
                                        <td class="bordeDerGris">
                                            Descuento Facebook</td>
                                        <td class="bordeDerGris">- <?= number_format($ppMonto * (1 - $priceMultiply), 0, ',', '.'); ?> CLP</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td class="bordeDerGris bordeIzqGris"></td>
                                    <td class="bordeDerGris"></td>
                                    <th class="bordeDerGris">Valor Total a pagar por PuntoPagos</th>
                                    <?php if ($montoDeposito == 0) echo "<th class='bordeDerGris'>ERROR</th>";else { ?>
                                        <?php if ($deposito == "depositoGarantia") $montoDeposito = 0; ?>
                                        <th class="bordeDerGris"><?= number_format($ppMonto * ($priceMultiply) + $montoDeposito, 0, ',', '.'); ?> CLP</th>
                                    <?php } ?>
                                </tr>
                            </tfoot>

                        </table>
                    </div>

                    <?php if ($sf_user->getFlash('notice')): ?>
                        <div class="notice"><?php echo $sf_user->getFlash('notice') ?></div>
                    <?php endif ?>
                    <?php if ($sf_user->getFlash('error')): ?>
                        <div class="error"><?php echo $sf_user->getFlash('error') ?></div>
                    <?php endif ?>

                    <div id="contenedorMedios">
                        <table id="tablePP">
                            <tr>
                                <td><input type="radio" name="pp_medio_pago" value="2"/> <?php echo image_tag('puntopagos/mp2.gif') ?></td>
                                <td><input type="radio" name="pp_medio_pago" value="3"/> <?php echo image_tag('puntopagos/mp3.gif') ?></td>
                                <td><input type="radio" name="pp_medio_pago" value="4"/> <?php echo image_tag('puntopagos/mp4.gif') ?></td>
                                <td><input type="radio" name="pp_medio_pago" value="5"/> <?php echo image_tag('puntopagos/mp5.gif') ?></td>
                            </tr>
                        </table>
                        <table id="tablePP2">
                            <tr>
                                <td style="width:33%;"><input type="radio" name="pp_medio_pago" value="6"/> <?php echo image_tag('puntopagos/mp6.gif') ?></td>
                                <td style="width:33%;"><input type="radio" name="pp_medio_pago" value="7"/> <?php echo image_tag('puntopagos/mp7.gif') ?></td>
                                <td style="width:33%;"><input type="radio" name="pp_medio_pago" value="10"/> <?php echo image_tag('puntopagos/mp10.gif') ?></td>
                            </tr>
                        </table>


                    </div>
                        <div class="contenedorBotonPagar" style="width: 400px;">
                        <input type="hidden" name="carMarcaModel" value="<?php echo $carMarcaModel ?>"/>
                        <input type="hidden" name="duracionReserva" value="<?php echo $duracionReserva ?>"/>
                        <input type="hidden" name="deposito" value="<?php echo $deposito ?>"/>
                        <input type="hidden" name="idReserva" value="<?php echo $ppIdReserva ?>" />
                        <input type="hidden" name="duracionReservaPagoPorDia" value="<?php echo $montoTotalPagoPorDia ?>" />
                        <div>
                            <?php if($deposito == "pagoPorDia"):?>
                            <a href="<?php echo url_for("paypal/confirmPayment?id=" . $ppId) ?>" class="botonPayPal" style="margin-top: 20px;float: left;" title="Pagar con PayPal"><img src="https://www.paypal.com/es_ES/i/btn/btn_xpressCheckout.gif"></a>
                            <?php endif;?>
                            <input class="botonPagar" type="submit" value=""/>
                        </div>
                    </div>
                    <div class="recordatorio">
                        Presionando <span>&quot;Pagar&quot;</span> entrar&aacute;s a la plataforma de pagos en l&iacute;nea para generar la transferencia mediante una <b>conexi&oacute;n segura</b>.
                        <br/><br/>
                    </div>
                </form>

            <?php else: ?>
                <?php if ($sf_user->getFlash('error') || $error): ?>
                    <div class="error"><?php echo $sf_user->getFlash('error') ? $sf_user->getFlash('error') : $error ?></div>
                <?php endif ?>
            <?php endif ?>
        </div>
    </div>
    <div class="clear"></div>
</div>
<script type="text/javascript">
    $('.botonPayPal').click(function(event) {
        event.preventDefault();
        var data = $('form[name="pp"]').serialize();
        $('body').append($('<form/>', {
            id: 'payPalForm',
            method: 'POST',
            action: $(this).attr("href")
        }));
        var dataArr1 = data.split("&");
        for (var i in dataArr1) {
            var dataArr2 = dataArr1[i].split("=");
            $('#payPalForm').append($('<input/>', {
                type: 'hidden',
                name: dataArr2[0],
                value: dataArr2[1]
            }));
        }
        $('#payPalForm').submit();
    });
</script>