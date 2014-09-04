<div id="Fondo">
    <div id="subFondo">
        <div id="Enunciado">
            <div id="demo">
                <table cellpadding="0" cellspacing="0" border="0" class="display" id="example" width="100%">
                    <thead>
                        <tr>
                            <th>PRECIO</th>
                            <th>FOTO</th>
                            <th>TIPO</th>
                            <th>MODELO</th>
                            <th>COBERTURA</th>
                            <th>RESERVAR</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php for ($i = 0; $i < count($cars); $i++) { ?>
                            <tr class="gradeA">
                                <td><div class="posicionamiento1">$ <?= number_format(intval($cars[$i]['priceday']), 0, ',', '.'); ?> <span style="font-style: italic;">(por día)</span><br>$ <?= number_format(intval($cars[$i]['pricehour']), 0, ',', '.'); ?> <span style="font-style: italic;">(por hora)</span></div>
                                </td>
                                <td>
                                    <div class="posicionamiento2">
                                        <div class="gallery galeria_<?= $i; ?>">
                                            <ul>
                                                <?php
                                                $cantidadFotos = count($fotos_autos[$i]) - 3;
                                                if ($cantidadFotos > 0) {
                                                    for ($j = 0; $j < $cantidadFotos; $j = $j + 2) {
                                                        $k = $j + 1;
                                                        if ($j == 0) {
                                                            if ($fotos_autos[$i]['photoS3'] == TRUE) {
                                                                ?>
                                                                <li>
                                                                    <a title="Foto Perfil" href="http://www.arriendas.cl/main/s3thumb?alto=185&ancho=185&urlFoto=<?= $fotos_autos[$i][$j]; ?>">
                                                                        <img title="<?= $fotos_autos[$i][$k]; ?>" src="http://www.arriendas.cl/main/s3thumb?alto=185&ancho=185&urlFoto=<?= $fotos_autos[$i][$j]; ?>" width="68" height="68">
                                                                    </a>
                                                                </li>
                                                                <?php
                                                            } else {
                                                                ?>
                                                                <li>
                                                                    <a title="Foto Perfil" href="<?= image_path('../uploads/cars/' . $fotos_autos[$i][$j]); ?>">
                                                                        <img title="<?= $fotos_autos[$i][$k]; ?>" src="<?= image_path('../uploads/cars/thumbs/' . $fotos_autos[$i][$j]); ?>" width="68" height="68">
                                                                    </a>
                                                                </li>
                                                                <?php
                                                            }
                                                        } else {
                                                            if ($fotos_autos[$i]['verificationPhotoS3'] == TRUE) {
                                                                ?>
                                                                <li>
                                                                    <a style="display:none;" title="<?= $fotos_autos[$i][$k]; ?>" href="http://www.arriendas.cl/main/s3thumb?alto=600&ancho=600&urlFoto=<?= $fotos_autos[$i][$j]; ?>">
                                                                        <img title="<?= $fotos_autos[$i][$k]; ?>" src="http://www.arriendas.cl/main/s3thumb?alto=40&ancho=40&urlFoto=<?= $fotos_autos[$i][$j]; ?>" width="68" height="68">
                                                                    </a>
                                                                </li>
                                                                <?php
                                                            } else {
                                                                ?>
                                                                <li>
                                                                    <a style="display:none;" title="<?= $fotos_autos[$i][$k]; ?>" href="<?= image_path('../uploads/verificaciones/' . $fotos_autos[$i][$j]); ?>">
                                                                        <img title="<?= $fotos_autos[$i][$k]; ?>" src="<?= image_path('../uploads/verificaciones/thumbs/' . $fotos_autos[$i][$j]); ?>" width="68" height="68">
                                                                    </a>
                                                                </li>
                                                                <?php
                                                            }
                                                        }//fin else
                                                    }//fin for j
                                                } else {//Si no existen fotos
                                                    ?>
                                                    <li>
                                                        <img title="No presenta fotos" src="http://www.arriendas.cl/images/default.png" width="68" height="68">
                                                    </li>
                                                    <?php
                                                }//fin else   
                                                ?>
                                            </ul>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="posicionamiento3">
                                        <?php
                                        if (!$cars[$i]['tipo_vehiculo'])
                                            echo "<span style='font-style:italic'>Sin tipo</span>";
                                        else if ($cars[$i]['tipo_vehiculo'] == '1') {
                                            if (intval($cars[$i]['priceday']) < 22900)
                                                echo "Citycar";
                                            else if (intval($cars[$i]['priceday']) >= 22900 && intval($cars[$i]['priceday']) < 26000)
                                                echo "Sedán";
                                            else if (intval($cars[$i]['priceday']) >= 26000 && intval($cars[$i]['priceday']) < 32000)
                                                echo "Mediano";
                                            else if (intval($cars[$i]['priceday']) >= 32000 && intval($cars[$i]['priceday']) < 36000)
                                                echo "Familiar";
                                            else if (intval($cars[$i]['priceday']) >= 36000)
                                                echo "Auto de lujo";
                                        }else if ($cars[$i]['tipo_vehiculo'] == '2')
                                            echo "Utilitario";
                                        else if ($cars[$i]['tipo_vehiculo'] == '3')
                                            echo "Pick-up";
                                        else if ($cars[$i]['tipo_vehiculo'] == '4')
                                            echo "SUV";
                                        ?>
                                    </div>
                                </td>
                                <td><div class="posicionamiento4"><?= $cars[$i]['brand']; ?> <?= $cars[$i]['model']; ?></div></td>
                                <td><div class="posicionamiento5">Robo/Daños/CWD<br>Asistencia de viaje<br>Deducible 5UF</div></td>
                                <td><div class="posicionamiento6"><a title="Realizar reserva" href="<?php echo url_for('profile/reserve?id=' . $cars[$i]['id']) ?>"><?php echo image_tag('Home/BotonReservarHome.png', array('class' => 'botonReservar', 'width' => '90px', 'height' => '30px')) ?></a></div></td>
                            </tr>
                            <?php
                        }//fin for i 
                        ?>        
                    </tbody>
                    <tfoot>
                        <tr>
                            <th>PRECIO</th>
                            <th>FOTO</th>
                            <th>TIPO</th>
                            <th>MODELO</th>
                            <th>COBERTURA</th>
                            <th>RESERVAR</th>
                        </tr>
                    </tfoot>
                </table>
            </div>

        </div><!-- fin enunciado -->
        <br /><br />
    </div>
</div> 
<button id="footer_search_box_2"  onclick="window.open('<?= url_for('main/arriendo?autos=santiago'); ?>')" title="Ver todos los autos" >VER TODOS</button>