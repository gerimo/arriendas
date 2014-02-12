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

<?php include_component('profile', 'profile') ?>
    
<!--  contenido de la seccion -->
<div class="main_contenido">

<div class="barraSuperior">
	<p>RESERVA ENVIADA</p>
</div>

<div style="border:1px solid #00FF00; background: #97F79A; width:435px; display:table;margin:auto;margin-top:20px; padding:20px;font-size:14px;">
	<?php echo html_entity_decode($sf_user->getFlash('msg')); ?>
</div>

</div><!-- main_contenido -->

<?php include_component('profile', 'colDer') ?>
    
</div><!-- main_box_2 -->

<!-- Google Code for Realizar pedido de reserva Conversion Page -->
<script type="text/javascript">
/* <![CDATA[ */
var google_conversion_id = 996876210;
var google_conversion_language = "en";
var google_conversion_format = "2";
var google_conversion_color = "ffffff";
var google_conversion_label = "AEJyCNbyswQQsr-s2wM";
var google_conversion_value = 0;
/* ]]> */
</script>
<script type="text/javascript" src="//www.googleadservices.com/pagead/conversion.js">
</script>
<noscript>
<div style="display:inline;">
<img height="1" width="1" style="border-style:none;" alt="" src="//www.googleadservices.com/pagead/conversion/996876210/?value=0&amp;label=AEJyCNbyswQQsr-s2wM&amp;guid=ON&amp;script=0"/>
</div>
</noscript>

<script>
    $(function(){
        console.log('');
        jQuery.get('<?php echo url_for('profile/reserveCallback?id=' . $idReserve) ?>')
    });
</script>


<div class="clear"></div>
</div><!-- main_box_1 -->


