<?php use_stylesheet('transacciones.css') ?>
<?php use_stylesheet('registro.css') ?>
<?php use_stylesheet('calendario.css') ?>


<div class="main_box_1">
<div class="main_box_2">
    

<div class="main_col_izq">

<?php include_component('profile', 'userinfo') ?>

</div><!-- main_col_izq -->
    
<!--  contenido de la seccion -->
<div class="main_contenido">


<style>
#transactions {
 margin:10px;font-size:12px;width:510px;
}
#transactions td {
 height:22px;
}
</style>


 <script>
 
$(document).ready(function()
{

  elementCount = 2;
  
  
  elementSize =	$(".calen_user_box").outerHeight(true);
  $(".content_rent").css("display","table");

  if($(".content_rent").height() > (elementSize*elementCount)+1)
  {
		$(".content_rent").css("display","block");
		$(".content_rent").height(elementSize*elementCount);	
		$(".content_rent").css("overflow","scroll");
		$(".content_rent").css("overflow-x","hidden");
		$(".calen_user_box").css( "width","-=10" );
		$(".calen_user_box").css( "margin-left","15px" );
}
	
});
 </script>


<h1 class="trans_titulo_seccion">Tus transacciones <span>(<?= $transactions->count() ?>)</span></h1>     

<?php if($transactions->count()) :?>

<!-- transacciones en curso -->
<div class="trans_box_1">
<div class="calen_box_3_header"><h2>Transacciones en curso</h2></div>

<table cellpadding="50" CellSpacing="10" id="transactions">

<?php foreach ($transactions as $t): ?>
<tr>
	
	
	<td><?= $t->getCar() ?></td>
	<td><?= date("Y-m-d",strtotime($t->getDate())) ?></td>
	<td><?= $t->getPrice() ?></td>
	<td><?= $t->getTransactionType()->getName() ?></td>
	<td><a href="<?php echo url_for('/bcpuntopagos?id='.$t->getReserveId())?>">
	<!--<a href="#" onclick="alert('Para cancelar su reserva, sirvase escribir un correo a soporte@arriendas.cl, y le daremos las indicaciones para realizar su pago');">-->
	Pagar</a></td>
</tr>
<?php endforeach; ?>
</table>

</div><!-- trans_box_1 -->

<?php endif; ?>

<?php if($paidTransactions->count()) :?>

<!-- Historial de transacciones pagas -->
<div class="trans_box_1">
<div class="trans_box_1_header"><h2>Historial de transacciones pagas</h2></div>

<?php foreach ($paidTransactions as $t): ?>

<!-- trans_item1 -->
<div class="trans_item1">
<div class="trans_item1_frame">


<?php //$userTransction = $t->getReserve()->getCar()->getUser(); ?>
<?php $userTransction = $t->getReserve()->getUser(); ?>

<?php if ($userTransction->getPictureFile() != NULL): ?>
            <?= image_tag("users/".$userTransction->getFileName(), 'width="79" height="58"') ?>
    <?php else: ?>
            <?= image_tag('img_registro/tmp_user_foto.jpg', 'width="79" height="58"') ?>
     <?php endif; ?>

</div>

<!-- historial transacciones pagas item -->
<ul class="trans_item1_info">
	<a href="<?php echo url_for('profile/ratingsHistory?id='. $userTransction->getId()) ?>">
		<li class="trans_item1_nombre"><?php echo $userTransction->getFirstname() . " ".  substr($userTransction->getLastname(), 0, 1); ?>.
	</a>
</li>
<li>Fecha: <td><?= date("Y-m-d",strtotime($t->getDate())) ?></td></li>
<li>Hora: <td><?= date("H:i:s",strtotime($t->getDate())) ?></td></li>
</ul>

<!--<div class="trans_item1_calif">
	<span>
		Calificaci&oacute;n
		100
	</span>
</div>
-->
<div class="trans_item1_pago">
	<span>
		Total pago
		$<?= $t->getPrice(); ?>
	</span>
</div>
</div><!-- trans_item1 -->

<?php endforeach; ?>

</div><!-- trans_box_1 -->

<?php endif; ?>



<?php if($paidTransactions->Count() == 0 &&  $transactions->count() == 0 ): ?>

	<div class="calen_box_3">
		<div style="padding-top:20px; padding-left:20px; font-size:12px">
			No tenes ninguna transacci&oacute;n.
		</div>
	</div>

<?php endif; ?>


<h1 class="trans_titulo_seccion">Denuncias</span></h1>     

<!-- Casos problem&aacute;ticos y multas en curso -->

<?php if($currentReports->count()) :?>

<div class="trans_box_1">
<div class="trans_box_1_header"><h2>Casos problem&aacute;ticos y multas en curso</h2></div>



<?php foreach ($currentReports as $r): ?>

<!-- trans_item2 -->
<div class="trans_item2" style="display:table">    

	

    <div class="trans_item2_info">
        <a href="#" class="trans_item2_nombre"style="top:20px;left:20px" >
		<a href="<?php echo url_for('profile/ratingsHistory?id='. $r->getReserve()->getUser()->getId()) ?>">
		<?php echo  $r->getReserve()->getUser()->getFirstName() . " ". substr($r->getReserve()->getUser()->getLastName(), 0, 1); ?>.
		</a>
		</a>
        
        <p class="trans_item2_calif">Calificaci&oacute;n: 100</p>
        <p class="trans_item2_pago"><?php //$r->getReserve()->getTransaction()->getPrice()?></p>
        
    </div><!-- trans_item2_info -->
        
    <div class="trans_item2_form">

    <div style="backround:#fefefe; border: 1px solid #dddddd; margin:10px; padding:10px;">
	<b><p><?= date("Y-m-d",strtotime($r->getReserve()->getDate())); ?></p></b>
	<p><?= $r->getRenterComment() ?></p>
	</div> 
    
        <!--<input type="text" /> -->
		<label>Respuesta</label><br/><br/>

		<textarea style="width:470px;"></textarea>
		
    </div><!-- trans_item2_form -->
    
        <!-- <a href="#" class="trans_item2_extdesc">[+] Extender descargo</a> -->
</div><!-- trans_item2 -->


<?php endforeach; ?>



</div><!-- trans_box_1 -->


<?php endif; ?>

<?php if($reports->count()) :?>

<!-- Casos problem&aacute;ticos y multas -->
<div class="trans_box_1">
<div class="trans_box_1_header"><h2>Historial de casos problem&aacute;ticos y multas</h2></div>


<?php foreach ($reports as $r): ?>


<!-- trans_item3 -->
<div class="trans_item3">    
   <!-- <div class="trans_item3_info"></div>-->
       
        
    <!-- trans_item2_info -->
	
        
<div style="backround:#fefefe; border: 1px solid #dddddd; margin:10px; padding:10px; display:table;">
	
	<div>
		<div href="#" style="font-size: 13px;font-weight: bold;color: #1D95CB; width:180px;float:left;">
		<a href="<?php echo url_for('profile/ratingsHistory?id='. $r->getReserve()->getUser()->getId()) ?>">
		<?php echo  $r->getReserve()->getUser()->getFirstName() . " ". substr($r->getReserve()->getUser()->getLastName(), 0, 1); ?>.
		</a>
		</div>
        <div style="font-size: 14px; font-weight: bold; color: #383838;width:130px;float:right;text-align:right">Calificaci&oacute;n: 100</div>
        <div style="font-size: 14px; font-weight: bold; color: #383838;width:130px;float:right;text-align:right">Total pago: $245</div>
	</div>	
		<div style="width:100%;float:left;">
			<b><p><?= date("Y-m-d",strtotime($r->getReserve()->getDate())); ?></p></b>
			<p><?= $r->getRenterComment() ?></p>
		</div>
</div> 	

<div style="backround:#fefefe; border: 1px solid #dddddd; margin:10px; padding:10px; display:table;">
	
	
		<div style="width:100%;float:left;">
			<b><p>Respuesta:</p></b>
			<p><?= $r->getOwnerComment() ?></p>
		</div>
</div> 	
		
    
       
</div><!-- trans_item3 -->

<?php endforeach; ?>

</div><!-- trans_box_1 -->



<?php endif; ?>


<?php if($reports->Count() == 0  && $currentReports->count() == 0): ?>

	<div class="calen_box_3">
		<div style="padding-top:20px; padding-left:20px; font-size:12px">
			No tenes ninguna denuncias.
		</div>
	</div>

<?php endif; ?>









<h1 class="calen_titulo_seccion">Los que arrende</span></h1>     

<!-- proximas reservas confirmadas -->

<?php if($rentings->Count()): ?>

<div class="calen_box_3">
<div class="calen_box_3_header"><h2>Pr&oacute;ximas reservas ya confirmadas</h2>
<div class="calen_box_header_nro_2"><span><?=$rentings->Count()?></span></div>
</div><!-- /calen_box_2_header -->

<div class="content_rent"> 

<?php foreach ($rentings as $car): ?>


<!-- usuario item -->
<div class="calen_user_box">
    
    <div class="calen_user_foto_frame">
         <?php echo image_tag("cars/".$car->getCar()->getId().".jpg", "width='84px' height='84px'");?>  
    </div>
    
    <ul class="calen_user_info">
        <li class="calen_user_nombre">
		<a href="<?php echo url_for('profile/ratingsHistory?id='. $car->getUserid()) ?>">
			<?=$car->getFirstname()?> <?= substr($car->getLastname(), 0, 1)?>.
		</a>
		</li>
        <!--<li class="calen_user_calif">GetPositiveRatings()</li>-->
        <li><?=$car->getDate()?></li>
    </ul>
    
    <div class="calen_user_coment">
    	<b>Auto:</b> <?=$car->getBrand()?> <?=$car->getModel()?> (<?=$car->getYear()?>)<br/>
    </div>   
      
</div><!-- calen_user_box -->

<?php endforeach; ?>

</div>

</div><!-- calen_box_3 -->

<?php endif; ?>


<?php if($rentings->Count() == 0 ): ?>

	<div class="calen_box_3">
		<div style="padding-top:20px; padding-left:20px; font-size:12px">
			No tenes ninguna reserva.
		</div>
	</div>

<?php endif; ?>














</div><!-- main_contenido -->

<div class="main_col_right">
	<?php include_component('profile', 'newsfeed') ?>
</div><!-- main_col_dere -->
    
</div><!-- main_box_2 -->
<div class="clear"></div>
</div><!-- main_box_1 -->
