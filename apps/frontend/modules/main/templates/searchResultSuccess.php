<style>
    .marcadores{
        width: 30px;
        float: left;
        padding: 14px 0;
    }
    .marcadores img{

    }
    li.sep{
        margin-top: 7px;
        font-size: 11px;
    }
    a.link_user{
        color: #05a4e7;
        text-decoration: none;
        border-bottom: 1px solid #05a4e7;
    }
    .img_verificado{
        width: 14px;
        height: 15px;
    }
</style>
<?php $i = 1; ?>
<?php foreach ($cars as $c): ?>
    <div class="search_arecomend_item" id="<?=$c["id"]; ?>">
        <div class="marcadores">
        <img src='http://chart.apis.google.com/chart?chst=d_map_pin_letter&chld=<?php echo $i; ?>|05a4e7|ffffff'/>
        </div>
        <div class="search_arecomend_frame">
            <?php   if ($c["photo"] != NULL){ 
                        if($c["photoType"] == 1) {
            ?> 
                            <img width="64px" height="64px" src="<?php echo url_for('main/s3thumb').'?alto=64&ancho=64&urlFoto='.urlencode($c['photo']) ?>"/>
                        <?php }else{ ?>
                            <img width="64px" height="64px" src="<?php echo image_path('../uploads/cars/thumbs/'.urlencode($c['photo'])); ?>"/>

            <?php       }
                    }else{ ?>
                <?= image_tag('default.png', 'size=64x64') ?>  
            <?php } ?>

        </div><!-- search_arecomend_frame -->

        <ul class="search_arecomend_info">
            <input type="hidden" class="link" value="<?php echo url_for('auto/economico?chile='.$c["comuna"] .'?id=' . $c["id"]) ?>"/>
            <li class="search_arecomend_marca"><?= '<a target="_blank" title="Ir al perfil del auto" href="'.url_for('auto/economico?chile=' . $c["comuna"].'&id='. $c["id"]).'">';?><?= $c["brand"] ?> <?= $c["model"] ?> </a><?php if($c['verificado']): ?><?php echo image_tag("verificado.png", "class=img_verificado title='Auto Asegurado'"); ?><?php endif; ?></li>
            <li>Día: <b>$<?= $c["price_per_day"] ?> -</b> Hora:<b>$<?= $c["price_per_hour"] ?></b></li>
            <?php if (sfContext::getInstance()->getUser()->getAttribute("logged")){?>
            <li class="sep">Usuario: <?= '<a target="_blank" title="Ir al perfil de '.$c["firstname"].'" class="link_user" href="http://www.arriendas.cl/profile/publicprofile/id/'.$c["userid"].'">';?><?= $c["firstname"]." ".$c["lastname"] ?></a></li>
            <?php } ?>
        </ul>
    </div><!-- search_arecomend_item -->
    <?php $i++; ?>
<?php endforeach; ?>