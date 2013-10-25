<?php
if(strpos(sfContext::getInstance()->getRouting()->getCurrentInternalUri(), 'publicprofile') == false){

if(count($cars)>0){
?>

<div class="main_col_right">
<div class="barraSuperior"><p>MIS AUTOS (<?php echo count($cars) ?>)</p></div>

<?php 
	foreach ($cars as $c){
		echo "<div class=marcoFoto>";
		echo "<a href='".url_for('cars/car?id='.$c->getId())."' title='Ver ficha de auto'>";
		echo image_tag('../uploads/cars/'.$c->getFoto(),array("width"=>"140px","height"=>"140px","class"=>"foto"));
		echo "</a></div>";
	}
?>

</div>

<?php
}//cierre count
}else{//página publicProfile

	if(count($cars)>0){
	?>

	<div class="main_col_right">
	<div class="barraSuperior"><p>AUTOS <?php echo strtoupper($userPublicProfile->getFirstname())." (".count($carsPublicProfile).")"; ?></p></div>

	<?php 
		foreach ($carsPublicProfile as $c){
			echo "<div class=marcoFoto>";
			echo "<a href='".url_for('cars/car?id='.$c->getId())."' title='Ver ficha de auto'>";
			echo image_tag($c->getFoto(),array("width"=>"140px","height"=>"140px","class"=>"foto"));
			echo "</a></div>";
		}
	?>

	</div>

	<?php
	}//cierre count
	
}
?>