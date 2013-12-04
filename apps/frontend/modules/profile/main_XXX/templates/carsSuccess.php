 <ul id="mycarousel_search" class="jcarousel-skin-tango">
  <?php foreach ($cars as $c): ?>
 <li>           
 	<div class="countCarrousel" >
            <!-- item auto -->
            <div class="land_ultauto_item" id="<?= $c->getId(); ?>">
                <div class="land_ultauto_frame">
 					
				<?php if ($c->getPhotoFile('main') != NULL): ?> 
							<?= image_tag("cars/".$c->getPhotoFile('main')->getFileName(), 'size=152x122') ?>
				<?php else: ?>
							<?= image_tag('default.png', 'size=152x122') ?>  
				<?php endif; ?>
					
                </div><!-- ultauto_frame -->
                
                <div class="land_ultauto_info">                    
                    <p>
                        <a href="<?php echo url_for('cars/car?id=' . $c->getId() )?>">
                       		<?=$c->getModel()?> <?=$c->getBrand()?>
                        </a><br>
                        A&ntilde;o: <?=$c->getYear()?><br>
                        D&iacute;a: <b>$<?=$c->getPriceday()?></b> - <b>Hora: $<?=$c->getPricehour()?></b>
                    </p>
                </div><!-- land_ultauto_info -->
                
                <div class="land_ultauto_usuario">
                    <p><b><?=$c->getFirstname()?> <?=$c->getLastname()?></b><br>
                        15/03/2012 - 15:20
                    </p>
                </div><!-- land_ultauto_usuario -->                                
            </div><!-- land_ultauto_item -->
            </div>
</li>
<?php endforeach; ?>
</ul>
