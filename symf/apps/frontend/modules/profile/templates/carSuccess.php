<?php use_stylesheet('subi_tu_auto.css') ?>
<?php use_stylesheet('registro.css') ?>
<?php use_stylesheet('comunes.css') ?>


<div class="main_box_1">
<div class="main_box_2">
    


<div class="main_col_izq">

<?php include_component('profile', 'userinfo') ?>

</div><!-- main_col_izq -->

    
<!--  contenido de la seccion -->
<div class="main_contenido">


<div style="visibility:hidden;overflow:hidden;height:0px;">

	<form id="formmain" method="post" enctype="multipart/form-data" action='<?php echo url_for('profile/uploadPhoto?photo=main&width=174&height=174&file=filemain') ?>'>
		<input type="file" name="filemain" id="filemain" />
		<input type="submit" />
	</form>

		
	<form id="formphoto1" method="post" enctype="multipart/form-data" action='<?php echo url_for('profile/uploadPhoto?photo=photo1&width=74&height=74&file=filephoto1') ?>'>
		<input type="file" name="filephoto1" id="filephoto1" />
		<input type="submit" />
	</form>

	<form id="formphoto2" method="post" enctype="multipart/form-data" action='<?php echo url_for('profile/uploadPhoto?photo=photo2&width=74&height=74&file=filephoto2') ?>'>
		<input type="file" name="filephoto2" id="filephoto2" />
		<input type="submit" />
	</form>

	<form id="formphoto3" method="post" enctype="multipart/form-data" action='<?php echo url_for('profile/uploadPhoto?photo=photo3&width=74&height=74&file=filephoto3') ?>'>
		<input type="file" name="filephoto3" id="filephoto3" />
		<input type="submit" />
	</form>

	<form id="formphoto4" method="post" enctype="multipart/form-data" action='<?php echo url_for('profile/uploadPhoto?photo=photo4&width=74&height=74&file=filephoto4') ?>'>
		<input type="file" name="filephoto4" id="filephoto4" />
		<input type="submit" />
	</form>

	<form id="formphoto5" method="post" enctype="multipart/form-data" action='<?php echo url_for('profile/uploadPhoto?photo=photo5&width=74&height=74&file=filephoto5') ?>'>
		<input type="file" name="filephoto5" id="filephoto5" />
		<input type="submit" />
	</form>


	
	
	
	
	<form id="formdesperfecto1" method="post" enctype="multipart/form-data" action='<?php echo url_for('profile/uploadPhoto?photo=desperfecto1&width=74&height=74&file=filedesperfecto1') ?>'>
		<input type="file" name="filedesperfecto1" id="filedesperfecto1" />
		<input type="submit" />
	</form>

	<form id="formdesperfecto2" method="post" enctype="multipart/form-data" action='<?php echo url_for('profile/uploadPhoto?photo=desperfecto2&width=74&height=74&file=filedesperfecto2') ?>'>
		<input type="file" name="filedesperfecto2" id="filedesperfecto2" />
		<input type="submit" />
	</form>

	<form id="formdesperfecto3" method="post" enctype="multipart/form-data" action='<?php echo url_for('profile/uploadPhoto?photo=desperfecto3&width=74&height=74&file=filedesperfecto3') ?>'>
		<input type="file" name="filedesperfecto3" id="filedesperfecto3" />
		<input type="submit" />
	</form>

	<form id="formdesperfecto4" method="post" enctype="multipart/form-data" action='<?php echo url_for('profile/uploadPhoto?photo=desperfecto4&width=74&height=74&file=filedesperfecto4') ?>'>
		<input type="file" name="filedesperfecto4" id="filedesperfecto4" />
		<input type="submit" />
	</form>

	<form id="formdesperfecto5" method="post" enctype="multipart/form-data" action='<?php echo url_for('profile/uploadPhoto?photo=desperfecto5&width=74&height=74&file=filedesperfecto5') ?>'>
		<input type="file" name="filedesperfecto5" id="filedesperfecto5" />
		<input type="submit" />
	</form>
	
	
	
	
</div>	


	
<?php echo form_tag('profile/doUpdateCar', array('method'=>'post', 'enctype'=>'multipart/form-data', 'id'=>'frm1')); ?> 


<h1 class="calen_titulo_seccion">Publica tu auto</h1>   

<?php 
include_partial('profile/car', array(	'car' => $car, 
						'brand' => $brand,
						'country' => $country,
						'selectedModel' => $selectedModel, 
						'selectedBrand' => $selectedBrand, 
						'selectedCity' => $selectedCity, 
						'selectedState' => $selectedState, 
						'selectedCountry' => $selectedCountry,
						'damages' => $damages
			)) ?>



</form>

</div><!-- main_contenido -->

        <div class="main_col_right">
			<?php include_component('profile', 'newsfeed') ?>
        </div>    
    
</div><!-- main_box_2 -->
<div class="clear"></div>
</div><!-- main_box_1 -->

