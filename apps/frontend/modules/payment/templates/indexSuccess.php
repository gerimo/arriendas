<?php use_stylesheet('pagoReserva.css') ?>
<style>
    .contenedor-medios-pago{
        width: 800px;
        margin-left: 66px;
    }
    .contenedor-medios-pago > div{
        width: 20%;
        float: left;
        height: 100px;
        width: 110px;
        display: table-cell;
        line-height: 100px;
        padding: 10px;
    }
    .contenedor-medios-pago > div > input{
        max-width: 10%;
        vertical-align: middle;
    }
    .contenedor-medios-pago > div > img{
        max-width: 80%;
        display: inline-block;
        vertical-align: middle;
    }
</style>
<div class="main_box_1">
    <div class="main_box_2">
        <div id="fondoPP">
            <?php if ($ppId): ?>
                <form name="pp" action="<?php echo url_for('payment/creacion?id=' . $ppId) ?>" method="post">

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
                        <div class="contenedor-medios-pago">
<!--                            <div>
                                <input type="radio" name="pp_medio_pago" value="3"/>
                                <?php echo image_tag('puntopagos/webpay.gif') ?>
                            </div>-->
                            <div>
                                <input type="radio" name="pp_medio_pago" value="21"/>
                                <?php echo image_tag('payment/bancodechile.jpg') ?>
                            </div>
                            <div>
                                <input type="radio" name="pp_medio_pago" value="21"/>
                                <?php echo image_tag('payment/bancoestados.jpg') ?>
                            </div>
                            <div>
                                <input type="radio" name="pp_medio_pago" value="21"/>
                                <?php echo image_tag('payment/bcobci.jpg') ?>
                            </div>
                            <div> 
                                <input type="radio" name="pp_medio_pago" value="21"/>
                                <?php echo image_tag('payment/bice.jpg') ?>
                            </div>
                            <div>
                                <input type="radio" name="pp_medio_pago" value="21"/>
                                <?php echo image_tag('payment/city.jpg') ?>
                            </div>
                            <div>
                                <input type="radio" name="pp_medio_pago" value="21"/>
                                <?php echo image_tag('payment/corpbanca.jpg') ?>
                            </div>
                            <div>
                                <input type="radio" name="pp_medio_pago" value="21"/>
                                <?php echo image_tag('payment/edwars.jpg') ?>
                            </div>
                            <div>
                                <input type="radio" name="pp_medio_pago" value="21"/>
                                <?php echo image_tag('payment/fala.jpg') ?>
                            </div>
                            <div>
                                <input type="radio" name="pp_medio_pago" value="21"/>
                                <?php echo image_tag('payment/internacional.jpg') ?>
                            </div>
                            <div>
                                <input type="radio" name="pp_medio_pago" value="21"/>
                                <?php echo image_tag('payment/santander.jpg') ?>
                            </div>
                            <div>
                                <input type="radio" name="pp_medio_pago" value="21"/>
                                <?php echo image_tag('payment/scotiabank.jpg') ?>
                            </div>
                            <div>
                                <input type="radio" name="pp_medio_pago" value="21"/>
                                <?php echo image_tag('payment/security.jpg') ?>
                            </div>
                            <div>
                                <input type="radio" name="pp_medio_pago" value="21"/>
                                <?php echo image_tag('payment/tbanc.jpg') ?>
                            </div>
                            <div>
                                <input type="radio" name="pp_medio_pago" value="21"/>
                                <img src="https://s3.amazonaws.com/static.khipu.com/buttons/50x25.png" border="0" alt="Khipu">
                            </div>
                            <?php if ($isForeign): ?>
                                <div>
                                    <input type="radio" name="pp_medio_pago" value="20"/>
                                    <?php echo image_tag('paypal/logotipo_paypal_pagos.png') ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="contenedorBotonPagar" style="width: 400px;">
                        <input type="hidden" name="carMarcaModel" value="<?php echo $carMarcaModel ?>"/>
                        <input type="hidden" name="duracionReserva" value="<?php echo $duracionReserva ?>"/>
                        <input type="hidden" name="deposito" value="<?php echo $deposito ?>"/>
                        <input type="hidden" name="idReserva" value="<?php echo $ppIdReserva ?>" />
                        <input type="hidden" name="duracionReservaPagoPorDia" value="<?php echo $montoTotalPagoPorDia ?>" />
                        <div>
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
