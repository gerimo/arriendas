[
<?php $nb = count($objectList); $i = 0; foreach ($objectList as $object): ++$i ?>
{
<?php $nb1 = count($object); $j = 0; foreach ($object as $key => $value): ++$j ?>
  "<?php echo $key ?>": <?php echo json_encode($value).($nb1 == $j ? '' : ',') ?>
<?php endforeach ?>
}<?php echo $nb == $i ? '' : ',' ?>
<?php endforeach ?>
]
