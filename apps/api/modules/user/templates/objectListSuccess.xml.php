<?php echo "<?xml version='1.0' encoding='utf-8'?>" ?>
<objects>
    <?php foreach ($objectList as $object): ?>
        <object>
            <?php foreach ($object as $key => $value): ?>
                <<?php echo $key ?>><?php echo $value ?></<?php echo $key ?>>
            <?php endforeach ?>
        </object>
    <?php endforeach ?>
</objects>