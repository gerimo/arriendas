<?php use_stylesheet('comunes.css') ?>
<?php use_stylesheet('registro.css') ?>
<style>
.d1 {
width:100%;
margin:20px;
}


</style>


<div class="main_box_1">
<div class="main_box_2">

<div class="main_col_izq">

<?php include_component('profile', 'userinfo') ?>

</div><!-- main_col_izq -->
    
<!--  contenido de la seccion -->
<div class="main_contenido">
<h1 style="float: left;margin-left: 20px;margin-top: 20px;font-family: 'MuseoSlab500';font-size: 30px;font-weight: normal;letter-spacing: -1px;text-shadow: 0px 2px 0px #DDD;margin-bottom: 15px;">
<div style="border:1px solid #00FF00; background: #97F79A; width:470px; display:table;margin-top:100px;margin-bottom:20px; padding:20px;font-size:14px;">
		<?php echo $sf_user->getFlash('msg'); ?>
	</div>

</h1>

	
	




<div class="clear"></div><br/><br/>





</div><!-- main_contenido -->

<div class="main_col_dere">
<?php include_component('profile', 'rightsidebar') ?>
</div><!-- main_col_dere -->
    
</div><!-- main_box_2 -->
<div class="clear"></div>
</div><!-- main_box_1 -->


