<style>
    .marcadores{
        width: 30px;
        float: left;
        padding: 14px 0;
    }
    .marcadores img{

    }
</style>
<?php $i = 1; ?>
<?php foreach ($cars as $c): ?>
    <div class="search_arecomend_item" id="<?=$c["id"]; ?>">
        <div class="marcadores"><img src='http://chart.apis.google.com/chart?chst=d_map_pin_letter&chld=<?php echo $i; ?>|05a4e7|ffffff'/></div>
        <div class="search_arecomend_frame">
            <?php if ($c["photo"] != NULL): ?> 
                <?= image_tag($c["photo"], 'size=64x64') ?>
            <?php else: ?>
                <?= image_tag('default.png', 'size=64x64') ?>  
            <?php endif; ?>

        </div><!-- search_arecomend_frame -->

        <ul class="search_arecomend_info">
            <input type="hidden" class="link" value="<?php echo url_for('cars/car') . '?id=' . $c["id"] ?>"/>
            <li class="search_arecomend_marca"><a href="<?php echo url_for('cars/car?id=' . $c["id"]) ?>"><?= $c["brand"] ?> <?= $c["model"] ?> </a></li>
            <li>Usuario: <?= $c["username"] ?></li>
            <li>Año: <?= $c["year"] ?></li>
            <li>Día: $<?= $c["price_per_day"] ?> - Hora: $<?= $c["price_per_hour"] ?></li>
        </ul>
    </div><!-- search_arecomend_item -->
    <?php $i++; ?>
<?php endforeach; ?>