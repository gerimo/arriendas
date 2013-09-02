
        <!--  contenido de la seccion -->
        <div class="main_contenido">




            <style>

                #linkmain {
                    /*position: absolute;*/
                    display:table;
                    padding-top:10px;
                    bottom: 20px;
                    left: 54px;
                }

                ul.upcar_titles li {
                    width: 82px;
                    float: left;
                    margin-right: 11px;
                    position: relative;
                }
                ul.upcar_titles {
                    float: left;
                    margin-top: 20px;
                    margin-left: 10px;
                }

                ul.upcar_fotos li a.thickbox {
                    position: relative;
                    bottom: 0;
                    left: 0;
                }

            </style>

            <!-- btn agregar otro auto -->
            <!--<a href="#" class="upcar_btn_agregar_auto"></a>-->


            <div class="calen_titulo_seccion">Informaci&oacute;n del auto > <span class="calen_titulo_nombreAuto"></span></div>


            <!-- transacciones en curso -->
            <div class="upcar_box_1">

                <div class="upcar_sector_1">
                    <div class="upcar_foto_frame">
                        <div id="previewmain">
                            <?php if ($car->getPhotoFile('main') != NULL): ?>
                                <a href="<?php echo image_path("cars/" . $car->getPhotoFile('main')->getFileName()); ?>" class="thickbox" ><?php echo image_tag("cars/" . $car->getPhotoFile('main')->getFileName(), 'size=174x174') ?></a>
                            <?php else: ?>
                                <?php echo image_tag('default.png', 'size=174x174') ?>
                            <?php endif; ?>

                        </div>
                        <br/>
                        <br/>
                        <center>
                        </center>

                    </div><!-- upcar_foto_frame -->




                <div class="sector_1_form" style="width:300px; margin-top: 70px;">
			<div class="perfil_titulo_campos"><span>Precio</span></div>
		    
                        <div class="c1">
                            <div class="c2">
                                <label><strong>Precio por d&iacute;a:</strong></strong></label>
                                <label style="width: 116px"></label>
                            </div><!-- /c2 -->
                            <div class="c2">
                                <label><strong>Precio por hora:</strong></label>
                                <label style="width: 116px"></label>
                            </div><!-- /c2 -->
                        </div><!-- /c1 -->


                        <div class="c1">
                            <label><strong>A&ntilde;o</strong></label>
                            <label style="width: 264px"></label>
                        </div>

                        <div class="c1">
                            <label><strong>Kilometros</strong></label>
                            <label style="width: 264px"></label>
                        </div>

                        <div class="c1">
                            <label><strong>Direcci&oacute;n</strong></label>
							<label style="width: 264px"><?php echo $car->getAddress() ?></label>
                        </div>

                        <div class="c1">
                            <div class="c2">
                                <label><strong>Pa&iacute;s:</strong></label>
								<label style="width: 120px"><?php echo $car->getCity()->getState()->getCountry() ?></label>

                            </div><!-- /c2 -->
                            <div class="c2">
                                <label><strong>Provincia:</strong></label>
								<label style="width: 120px"><?php echo $car->getCity()->getState() ?></label>

                            </div><!-- /c2 -->
                            <div class="c2 clear">
                                <label><strong>Localidad:</strong></label>
								<label style="width: 120px"><?php echo $car->getCity() ?></label>

                            </div><!-- /c2 -->
                        </div><!-- /c1 -->

                        <input type="hidden" id="lat" name="lat" value="" />
                        <input type="hidden" id="lng" name="lng" value="" />
                        <input type="hidden" id="id" name="id" value="<?php echo $car->getId() ?>" />


                    </div><!-- upcar_sector_1 -->

                    <div class="upcar_sector_2">
                        <p><strong>Breve descripci&oacute;n de tu auto:</strong></p>
                        <label style="width: 470px"><?php echo $car->getDescription() ?></label>
                    </div><!-- upcar_sector_2 -->




                    <!-- fotografias adicionales -->

					<?php if($car->getPhotoFile('photo1') && $car->getPhotoFile('photo2') && $car->getPhotoFile('photo3') && $car->getPhotoFile('photo4') && $car->getPhotoFile('photo5')) :?>
                    <div class="upcar_box_2">
                        <div class="upcar_box_2_header"><h2>Fotograf&iacute;as adicionales</h2></div>

                        <ul class="upcar_fotos">
                            <li>
                                <div id="previewphoto1">


                                    <?php if ($car->getPhotoFile('photo1')): ?>
                                        <a href="<?php echo image_path("cars/" . $car->getPhotoFile('photo1')->getFileName()); ?>" class="thickbox" ><?php echo image_tag("cars/" . $car->getPhotoFile('photo1')->getFileName(), 'size=74x74') ?></a>
                                    <?php else: ?>
                                        <?php echo image_tag('img_subi_tu_auto/upcar_sin_foto.png', 'size=74x74 class=imagen') ?>
                                    <?php endif; ?>

                                </div>

                            </li>

                            <li>
                                <div id="previewphoto2">

                                    <?php if ($car->getPhotoFile('photo2') != NULL): ?>
                                        <a href="<?php echo image_path("cars/" . $car->getPhotoFile('photo2')->getFileName()); ?>" class="thickbox" > <?php echo image_tag("cars/" . $car->getPhotoFile('photo2')->getFileName(), 'size=74x74') ?></a>
                                    <?php else: ?>
                                        <?php echo image_tag('img_subi_tu_auto/upcar_sin_foto.png', 'size=74x74 class=imagen') ?>
                                    <?php endif; ?>

                                </div>

                            </li>

                            <li>
                                <div id="previewphoto3">

                                    <?php if ($car->getPhotoFile('photo3') != NULL): ?>
                                        <a href="<?php echo image_path("cars/" . $car->getPhotoFile('photo3')->getFileName()); ?>" class="thickbox" > <?php echo image_tag("cars/" . $car->getPhotoFile('photo3')->getFileName(), 'size=74x74') ?></a>
                                    <?php else: ?>
                                        <?php echo image_tag('img_subi_tu_auto/upcar_sin_foto.png', 'size=74x74 class=imagen') ?>
                                    <?php endif; ?>

                                </div>

                            </li>

                            <li>
                                <div id="previewphoto4">


                                    <?php if ($car->getPhotoFile('photo4') != NULL): ?>
                                        <a href="<?php echo image_path("cars/" . $car->getPhotoFile('photo4')->getFileName()); ?>" class="thickbox" > <?php echo image_tag("cars/" . $car->getPhotoFile('photo4')->getFileName(), 'size=74x74') ?></a>
                                    <?php else: ?>
                                        <?php echo image_tag('img_subi_tu_auto/upcar_sin_foto.png', 'size=74x74 class=imagen') ?>
                                    <?php endif; ?>


                                </div>

                            </li>

                            <li>
                                <div id="previewphoto5">
                                    <?php if ($car->getPhotoFile('photo5') != NULL): ?>
                                        <a href="<?php echo image_path("cars/" . $car->getPhotoFile('photo5')->getFileName()); ?>" class="thickbox" >  <?php echo image_tag("cars/" . $car->getPhotoFile('photo5')->getFileName(), 'size=74x74') ?></a>
                                    <?php else: ?>
                                        <?php echo image_tag('img_subi_tu_auto/upcar_sin_foto.png', 'size=74x74 class=imagen') ?>
                                    <?php endif; ?>

                                </div>

                            </li>
                        </ul>

                        <ul class="upcar_titles">
                            <li>
                                <h2>Frente</h2>
                            </li>
                            <li>
                                <h2>Interior</h2>
                            </li>
                            <li>
                                <h2>Trasera</h2>
                            </li>
                            <li>
                                <h2>Lateral</h2>
                            </li>
                            <li>
                                <h2>Extra</h2>
                            </li>
                        </ul>

                    </div><!-- upcar_box_2 -->
					<?php endif?>

					<?php if($car->getPhotoFile('desperfecto1') && $car->getPhotoFile('desperfecto2') && $car->getPhotoFile('desperfecto3') && $car->getPhotoFile('desperfecto4') && $car->getPhotoFile('desperfecto5')) :?>
                    <div class="upcar_box_2">
                        <div class="upcar_box_2_header"><h2>Desperfectos</h2></div>

                        <ul class="upcar_fotos">
                            <li>
                                <div id="previewdesperfecto1">


                                    <?php if ($car->getPhotoFile('desperfecto1')): ?>
                                        <a href="<?php echo image_path("cars/" . $car->getPhotoFile('desperfecto1')->getFileName()); ?>" class="thickbox" ><?php echo image_tag("cars/" . $car->getPhotoFile('desperfecto1')->getFileName(), 'size=74x74') ?></a>
                                    <?php else: ?>
                                        <?php echo image_tag('img_subi_tu_auto/upcar_sin_foto.png', 'size=74x74 class=imagen') ?>
                                    <?php endif; ?>

                                </div>

                            </li>

                            <li>
                                <div id="previewdesperfecto2">

                                    <?php if ($car->getPhotoFile('desperfecto2') != NULL): ?>
                                        <a href="<?php echo image_path("cars/" . $car->getPhotoFile('desperfecto2')->getFileName()); ?>" class="thickbox" ><?php echo image_tag("cars/" . $car->getPhotoFile('desperfecto2')->getFileName(), 'size=74x74') ?></a>
                                    <?php else: ?>
                                        <?php echo image_tag('img_subi_tu_auto/upcar_sin_foto.png', 'size=74x74 class=imagen') ?>
                                    <?php endif; ?>

                                </div>

                            </li>

                            <li>
                                <div id="previewdesperfecto3">

                                    <?php if ($car->getPhotoFile('desperfecto3') != NULL): ?>
                                        <a href="<?php echo image_path("cars/" . $car->getPhotoFile('desperfecto3')->getFileName()); ?>" class="thickbox" ><?php echo image_tag("cars/" . $car->getPhotoFile('desperfecto3')->getFileName(), 'size=74x74') ?></a>
                                    <?php else: ?>
                                        <?php echo image_tag('img_subi_tu_auto/upcar_sin_foto.png', 'size=74x74 class=imagen') ?>
                                    <?php endif; ?>

                                </div>

                            </li>

                            <li>
                                <div id="previewdesperfecto4">


                                    <?php if ($car->getPhotoFile('desperfecto4') != NULL): ?>
                                        <a href="<?php echo image_path("cars/" . $car->getPhotoFile('desperfecto4')->getFileName()); ?>" class="thickbox" ><?php echo image_tag("cars/" . $car->getPhotoFile('desperfecto4')->getFileName(), 'size=74x74') ?></a>
                                    <?php else: ?>
                                        <?php echo image_tag('img_subi_tu_auto/upcar_sin_foto.png', 'size=74x74 class=imagen') ?>
                                    <?php endif; ?>


                                </div>

                            </li>

                            <li>
                                <div id="previewdesperfecto5">
                                    <?php if ($car->getPhotoFile('desperfecto5') != NULL): ?>
                                        <a href="<?php echo image_path("cars/" . $car->getPhotoFile('desperfecto5')->getFileName()); ?>" class="thickbox" ><?php echo image_tag("cars/" . $car->getPhotoFile('desperfecto5')->getFileName(), 'size=74x74') ?></a>
                                    <?php else: ?>
                                        <?php echo image_tag('img_subi_tu_auto/upcar_sin_foto.png', 'size=74x74 class=imagen') ?>
                                    <?php endif; ?>

                                </div>

                            </li>
                        </ul>


                    </div><!-- upcar_box_2 -->
					<?php endif?>



                </div><!-- upcar_box_1 -->


            </div>
