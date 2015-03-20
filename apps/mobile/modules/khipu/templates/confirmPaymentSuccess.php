<?php use_stylesheet('pagoReserva.css') ?>
<div class="main_box_1">
    <div class="main_box_2">
        <div id="fondoPP">
            <?php if ($ppId): ?>
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
                                <td class="bordeDerGris bordeIzqGris"><?php $ppIdReserva; ?></td>
                                <td class="bordeDerGris"><?php $ppId; ?></td>
                                <td class="bordeDerGris"><?php $carMarcaModel; ?><?php " / " . $duracionReserva; ?></td>
                                <td class="bordeDerGris"><?php number_format($ppMonto, 0, ',', '.'); ?> CLP</td>
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
                                        <?php number_format($montoDeposito, 0, ',', '.'); ?> CLP
                                    <?php } ?>
                                </td>
                            </tr>

                            <?php if ($hasDiscountFB): ?>
                                <tr>
                                    <td class="bordeDerGris bordeIzqGris"></td>
                                    <td class="bordeDerGris"></td>
                                    <td class="bordeDerGris">
                                        Descuento Facebook</td>
                                    <td class="bordeDerGris">- <?php number_format($ppMonto * (1 - $priceMultiply), 0, ',', '.'); ?> CLP</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td class="bordeDerGris bordeIzqGris"></td>
                                <td class="bordeDerGris"></td>
                                <th class="bordeDerGris">Valor Total a pagar con Khipu</th>
                                <?php if ($montoDeposito == 0) echo "<th class='bordeDerGris'>ERROR</th>";else { ?>
                                    <th class="bordeDerGris"><?php number_format($finalPrice, 0, ',', '.'); ?> CLP</th>
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

                <div class="contenedorBotonPagar" style="width: 219px;">
                    <input type="hidden" name="carMarcaModel" value="<?php echo $carMarcaModel ?>"/>
                    <input type="hidden" name="duracionReserva" value="<?php echo $duracionReserva ?>"/>
                    <input type="hidden" name="idReserva" value="<?php echo $ppIdReserva ?>" />
                    <div>
                        <img src="https://s3.amazonaws.com/static.khipu.com/buttons/50x25.png" alt="khipu" style="padding-top: 18px;"/>
                        <a class="botonPagar" style="width: 159px;" href="<?php echo $checkOutUrl ?>" ></a>
                    </div>
                </div>
                <div class="recordatorio">
                    Presionando <span>&quot;Pagar&quot;</span> entrar&aacute;s a la plataforma de pagos en l&iacute;nea para generar la transferencia mediante una <b>conexi&oacute;n segura</b>.
                    <br/><br/>
                </div>

            <?php else: ?>
                <?php if ($sf_user->getFlash('error') || $error): ?>
                    <div class="error"><?php echo $sf_user->getFlash('error') ? $sf_user->getFlash('error') : $error ?></div>
                <?php endif ?>
            <?php endif ?>
        </div>
    </div>
    <div class="clear"></div>
</div>

