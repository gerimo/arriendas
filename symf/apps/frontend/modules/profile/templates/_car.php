<style>

.input {
  margin: 5px 0;
  background: white;
  float: left;
  clear: both;
}
.input span {
  position: absolute;
  padding: 12px;
  margin-left: 3px;
  color: #999;
}
.input input, .input textarea, .input select {
  position: relative;
  margin: 0;
  border-width: 1px;
  padding: 6px;
  background: transparent;
  font: inherit;
}
/* Hack to remove Safari's extra padding. Remove if you don't care about pixel-perfection. */
@media screen and (-webkit-min-device-pixel-ratio:0) {
    .input input, .input textarea, .input select { padding: 4px; }
}

.input input
{
	padding-left: 12px;
}

#frm1
{
margin:auto;
	display:table;
	
}



ul.upcar_titles li {
	width: 82px;
	float: left;
	margin-right: 11px;
	position: relative;
}
ul.upcar_titles {
float: left;
margin-top: 20px;
margin-left: 12px;
}
</style>


<script type="text/javascript">

$(document).ready(function()
{

	$("#address").focusout(function() {
		
		if( $(this).val() ) {
			
			var fulladdress = $("#address").val();
			var geocoder = new google.maps.Geocoder();
			var lat = '';
			var lng = '';
	        geocoder.geocode( {'address': fulladdress },
	        function(data, status) {
				if(status == 'OK') {
					$("#thickbox").attr("href", "<?php echo url_for('maps/picLocation') ?>/lat/" + data[0].geometry.location.lat() + "/lng/" + data[0].geometry.location.lng() + "?width=600&height=500&inlineId=maps");
					$("#thickbox").click();
				}
			});
		}	

	})

	function imageUpload(form, formfile, preview, link)
	{

		$(formfile).live('change', function()	
		{
			$(preview).html('');
			$(preview).html('<?php echo image_tag('loader.gif') ?>');
			$(form).ajaxForm(
			{
				target: preview
			}).submit();
		});
		
		$(link).click(function(event) {
			$(formfile).click();
		});

	}		

	var i=1;
	imageUpload('#formmain', "#filemain", '#previewmain','#linkmain');
	for (i=1;i<=5;i++){
		imageUpload('#formphoto'+i, '#filephoto'+i, '#previewphoto'+i, '#linkphoto'+i);
	}
	
		for (i=1;i<=5;i++){
		imageUpload('#formdesperfecto'+i, '#filedesperfecto'+i, '#previewdesperfecto'+i, '#linkdesperfecto'+i);
	}
	
});


      $(document).ready(function() {
			
	
		getModel($("#brand"));
		
		$("#brand").change(function(){
			getModel($(this));
		});
		
		getState($("#country"));
		
		$("#country").change(function(){
			getState($(this));
		});
		
		$("#state").change(function(){
			getCity($(this));
		});
		
		
				
 	});


function getModel(currentElement){
	
     $("#model").html("");
	
	
	
     $.getJSON("<?php echo url_for('profile/getModel')?>",{id:currentElement.val(), ajax: 'true'}, function(j){
      var options = '';

      for (var i = 0; i < j.length; i++) {
      	var selected = "";
        <?php if($selectedModel->getId()!=""){ ?>
		if(j[i].optionValue == <?php echo $selectedModel->getId()?>)
			selected = 'selected="selected"';
      	<?php } ?>
        options += '<option value="' + j[i].optionValue + '" ' + selected + ' >' + j[i].optionDisplay + '</option>';
      }
      $("#model").html(options);
    })
}


function getState(currentElement){
	
     $("#state").html("");
	
     $.getJSON("<?php echo url_for('profile/getState')?>",{id:currentElement.val(), ajax: 'true'}, function(j){
      var options = '';

      for (var i = 0; i < j.length; i++) {
      	var selected = "";
        <?php if($selectedState->getId()!=""){ ?>
		if(j[i].optionValue == <?php echo $selectedState->getId()?>)
			selected = 'selected="selected"';
      	<?php } ?>
        options += '<option value="' + j[i].optionValue + '" ' + selected + ' >' + j[i].optionDisplay + '</option>';
      }
      $("#state").html(options);
      getCity($("#state"));
    })
}


function getCity(currentElement){
	
     $("#city").html("");
	
     $.getJSON("<?php echo url_for('profile/getCity')?>",{id:currentElement.val(), ajax: 'true'}, function(j){
      var options = '';

      for (var i = 0; i < j.length; i++) {
      	var selected = "";
        <?php if($selectedCity->getId()!=""){ ?>
		if(j[i].optionValue == <?php echo $selectedCity->getId()?>)
			selected = 'selected="selected"';
      	<?php } ?>
        options += '<option value="' + j[i].optionValue + '" ' + selected + ' >' + j[i].optionDisplay + '</option>';
      }
      $("#city").html(options);
    })
}


function getTextSelect(element){

	return element.find("option:selected").text();
}


function submitCar(){
	 
     var fulladdress = $("#address").val() +', ' + getTextSelect($("#state")) +',' + getTextSelect($("#country"));
     var geocoder = new google.maps.Geocoder();
        geocoder.geocode( {'address': fulladdress },
          function(data, status) {
            	 $("#lat").val(data[0].geometry.location.lat());
            	 $("#lng").val(data[0].geometry.location.lng());
              for (var i = 0; i < data.length ; i++) {
                var lat = data[i].geometry.location.lat();
              }
            	 $("form").submit();
            });
}



(function($) {
    function toggleLabel() {
        var input = $(this);
        setTimeout(function() {
            var def = input.attr('title');
            if (!input.val() || (input.val() == def)) {
                input.prev('span').css('visibility', '');
                if (def) {
                    var dummy = $('<label></label>').text(def).css('visibility','hidden').appendTo('body');
                    input.prev('span').css('margin-left', dummy.width() + 3 + 'px');
                    dummy.remove();
                }
            } else {
                input.prev('span').css('visibility', 'hidden');
            }
        }, 0);
    };

    function resetField() {
        var def = $(this).attr('title');
        if (!$(this).val() || ($(this).val() == def)) {
            $(this).val(def);
            $(this).prev('span').css('visibility', '');
        }
    };

    $('input, textarea').live('keydown', toggleLabel);
    $('input, textarea').live('paste', toggleLabel);
    $('select').live('change', toggleLabel);

    $('input, textarea').live('focusin', function() {
        $(this).prev('span').css('color', '#ccc');
    });
    $('input, textarea').live('focusout', function() {
        $(this).prev('span').css('color', '#999');
    });

    $(function() {
        $('input, textarea').each(function() { toggleLabel.call(this); });
    });

})(jQuery);



 </script>

<style>

#linkmain {
	/*position: absolute;*/
	display:table;
	padding-top:10px;
	bottom: 20px;
	left: 54px;
}

</style>

<!-- btn agregar otro auto -->
<!--<a href="#" class="upcar_btn_agregar_auto"></a>-->
<style>
  
  .fila_form {
    width: 100%;
  }
  
  .item_form {
    width: 66%;
    float: left;
  }
  
  .item_form label {
    color: #00AEFF;
    font-size: 10px;
    margin-bottom: 4px;
  }
  
  .item_form input {
    width: 200px;
    font-size: 14px;
    height: 20px;
    padding: 4px;
    border-width: 1px;
    border-color: #AAAAAA;
    -webkit-border-radius: 5px;
    -moz-border-radius: 5px;
  }
  
</style>

<!-- transacciones en curso -->
<div class="fila_form" style="margin-top: 20px;">
  <div class="item_form">
    <label for="ubicacion">Ubicaci&oacute;n del Veh&iacute;culo</label><br />
    <input type="text" name="ubicacion" value class placeholder="Ingresa la direcci&oacute;n del veh&iacute;culo" />
  </div>
</div>


<div class="upcar_box_1">

<div class="upcar_sector_1">
    <div class="upcar_foto_frame">
	<div id="previewmain">
     <?php if ($car->getPhotoFile('main') != NULL): ?>
            <?php echo image_tag("cars/".$car->getPhotoFile('main')->getFileName(), 'size=174x174') ?>
    <?php else: ?>
            <?php echo image_tag('default.png', 'size=174x174') ?>
     <?php endif; ?>
</div>

   <a href="#" id="linkmain"><?php echo image_tag('img_subi_tu_auto/upcar_ico_agrega_foto.png') ?></a>

    </div><!-- upcar_foto_frame -->





    <div class="sector_1_form" style="width:450px">
   <!-- <div class="c1">
        <label>Nombre del auto:</label>
        <input type="text" name="" >
    </div>-->  <!-- /c1 -->

   <div class="c1">
   <div class="c2">

		
	<select name="brand" id="brand">
		<?php foreach ($brand as $b): ?>
			<option  <?php if($b->getId() == $selectedBrand->getId()){ echo "selected=selected";} ?> value="<?php echo$b->getId() ?>" ><?php echo$b->getName() ?></option> 		
		<?php endforeach; ?>
	</select>
	
   </div><!-- /c2 -->
   <div class="c2">

	<select name="model" id="model">
	</select>

   </div><!-- /c2 -->
    </div><!-- /c1 -->

    
    <div class="c1">
    <div class="c2">
  <label class="input">
		<span>Versión</span>
        <input type="text" name="version" id="version" value="" >
		</label>
    </div><!-- /c2 -->
    <div class="c2">
        <label class="input">
		<span>Color</span>
        <input type="text" name="color" id="color" value="" >
		</label>
    </div><!-- /c2 -->
    </div>

    <div class="c1">
    <div class="c2">
	
	<?php 
	$ppd  = $car->getPricePerDay();
	if($ppd != "")
		$ppd =floor($car->getPricePerDay());
		
	$pph  = $car->getPricePerHour();
	if($pph != "")
		$pph =floor($car->getPricePerHour());
		
	?>
		
        <label class="input">
		<span>Precio por d&iacute;a</span>
        <input type="text" name="priceday" id="priceday" value="<?php echo $ppd?>" >
		</label>
    </div><!-- /c2 -->
    <div class="c2">
        <label class="input">
		<span>Precio por hora</span>
        <input type="text" name="pricehour" id="pricehour" value="<?php echo $pph?>" >
		</label>
    </div><!-- /c2 -->
    </div><!-- /c1 -->


 <div class="c1">
	<label class="input">
	<span>A&ntilde;o</span>
	<input type="text" id="year" name="year" value="<?php echo $car->getYear()?>">
	</label>
</div>

<div class="c1">
	<label class="input">
	<span>N&uacute;mero Patente</span>
	<input type="text" id="patente" name="patente" value="<?php echo $car->getPatente()?>">
	</label>
</div>

<div class="c1">
<div class="c2">
  <label class="input">
<select name="tipobencina" id="tipobencina">
		<option value="">Tipo de Bencina</option>
		<option value="93" <?php if($car->getTipoBencina() == '93') echo 'selected'; ?>>93</option>
		<option value="95" <?php if($car->getTipoBencina() == '95') echo 'selected'; ?>>95</option>
		<option value="97" <?php if($car->getTipoBencina() == '97') echo 'selected'; ?>>97</option>
		<option value="Diesel" <?php if($car->getTipoBencina() == 'Diesel') echo 'selected'; ?>>Diesel</option>
	</select>
		</label>
    </div><!-- /c2 -->
    <div class="c2">
        <label class="input">
	<select name="numero_puertas" id="numero_puertas">
		<option value="">Nº de Puertas</option>
		<option value="3">3</option>
		<option value="5">5</option>
	</select>
		</label>
    </div><!-- /c2 -->
    </div>

<div class="c1">
<div class="c2">
  <label class="input">
	<select name="transmision" id="transmision">
		<option value="">Transmisión</option>
		<option value="M">Manual</option>
		<option value="A">Automática</option>
	</select>
		</label>
    </div><!-- /c2 -->
    <div class="c2">
    </div><!-- /c2 -->
    </div>

    
<!--
<div class="c1">
	<label class="input">
	<span>Nº de Motor</span>
	<input type="text" id="numero_motor" name="numero_motor" value="">
	</label>
</div>

<div class="c1">
	<label class="input">
	<span>N&uacute;mero VIN</span>
	<input type="text" id="numero_vin" value="" name="numero_vin">
	</label>
</div>
-->
<!--
 <div class="c1">
	<label class="input">
	<span>Kilomentros</span>
	<input type="text" id="km" name="km" value="">
</label>
</div>
-->

 <div class="c1">
	<label class="input">
	<span>Direccion</span>
  <input type="text" id="address" name="address" value="<?php echo $car->getAddress()?>" onblur="">
  <a href="#" title="" id="thickbox" class="thickbox"><?php //echo image_tag("globe_24.png")?></a>

<div id="acava"></div>
  <input type="hidden" value="0" id="frommap"/>
	</label>
</div>

 <div class="c1">
	   <div class="c2">
		<select name="country" id="country">
			<?php foreach ($country as $b): ?>
				<option <?php if($b->getId() == $selectedCountry->getId()){ echo "selected=selected";} ?> value="<?php echo $b->getId() ?>" ><?php echo $b->getName() ?></option> 		
			<?php endforeach; ?>
		</select>
	   </div><!-- /c2 -->
	   <div class="c2">
		
		<select name="state" id="state">
		</select>
	   </div><!-- /c2 -->
	   <div class="c2">
		
		<select style="display:none" name="city" id="city">
		</select>
	   </div><!-- /c2 -->
 </div><!-- /c1 -->

 <input type="hidden" id="lat" name="lat" value="0" />
 <input type="hidden" id="lng" name="lng" value="0" />
 <input type="hidden" id="id" name="id" value="<?php echo $car->getId() ?>" />


</div><!-- upcar_sector_1 -->

<div class="upcar_sector_2">
    <label class="input">
	<span>Breve descripci&oacute;n de tu auto:</span>
    <textarea id="description" name="description"row="20" cols="20"><?php echo $car->getDescription()?></textarea>
	</label>	
</div><!-- upcar_sector_2 -->



<div class="upcar_sector_2">
    <label class="input">
   	<div class="upcar_box_2_header"><h2>Reporte de daños</h2></div>
	<span>Ingrese los daños que posee el vehículo separados por coma (,)</span>
    <textarea id="danos" name="danos"row="20" cols="20"><?php
    $dam = array(); foreach ($damages as $value) { $dam[] = trim($value); }	
	echo trim(implode(', ',$dam));
    ?></textarea>
	</label>	
</div><!-- upcar_sector_2 -->


<!-- fotografias adicionales -->


<div class="upcar_box_2">
<div class="upcar_box_2_header"><h2>Fotograf&iacute;as adicionales</h2></div>

<ul class="upcar_fotos">
    <li>
	<div id="previewphoto1">
		
		
		    <?php if($car->getPhotoFile('photo1')): ?>
			    <?php echo image_tag("cars/".$car->getPhotoFile('photo1')->getFileName(), 'size=74x74') ?>
		    <?php else: ?>
			    <?php echo image_tag('img_subi_tu_auto/upcar_sin_foto.png', 'size=74x74 class=imagen') ?>
		    <?php endif; ?>
		
	</div>
        <a id="linkphoto1" href="#"><?php echo image_tag('img_subi_tu_auto/upcar_ico_agrega_foto.png') ?> </a>
     </li>

         <li>
	<div id="previewphoto2">
	
		    <?php if($car->getPhotoFile('photo2') != NULL): ?>
			    <?php echo image_tag("cars/".$car->getPhotoFile('photo2')->getFileName(), 'size=74x74') ?>
		    <?php else: ?>
			    <?php echo image_tag('img_subi_tu_auto/upcar_sin_foto.png', 'size=74x74 class=imagen') ?>
		    <?php endif; ?>

	 </div>
       <a id="linkphoto2" href="#"><?php echo image_tag('img_subi_tu_auto/upcar_ico_agrega_foto.png') ?> </a>
     </li>

         <li>
		<div id="previewphoto3">
			
			    <?php if($car->getPhotoFile('photo3') != NULL): ?>
				    <?php echo image_tag("cars/".$car->getPhotoFile('photo3')->getFileName(), 'size=74x74') ?>
			    <?php else: ?>
				    <?php echo image_tag('img_subi_tu_auto/upcar_sin_foto.png', 'size=74x74 class=imagen') ?>
			    <?php endif; ?>
		
		</div>
        <a id="linkphoto3" href="#"><?php echo image_tag('img_subi_tu_auto/upcar_ico_agrega_foto.png') ?> </a>
     </li>

         <li>
		 <div id="previewphoto4">
			
		
		 	   <?php if($car->getPhotoFile('photo4') != NULL): ?>
				    <?php echo image_tag("cars/".$car->getPhotoFile('photo4')->getFileName(), 'size=74x74') ?>
			    <?php else: ?>
				    <?php echo image_tag('img_subi_tu_auto/upcar_sin_foto.png', 'size=74x74 class=imagen') ?>
			    <?php endif; ?>
		
		
		</div>
        <a id="linkphoto4" href="#"><?php echo image_tag('img_subi_tu_auto/upcar_ico_agrega_foto.png') ?> </a>
     </li>

         <li>
		<div id="previewphoto5">
			    <?php if($car->getPhotoFile('photo5') != NULL): ?>
				    <?php echo image_tag("cars/".$car->getPhotoFile('photo5')->getFileName(), 'size=74x74') ?>
			    <?php else: ?>
				    <?php echo image_tag('img_subi_tu_auto/upcar_sin_foto.png', 'size=74x74 class=imagen') ?>
			    <?php endif; ?>
			
		</div>
       <a id="linkphoto5" href="#"><?php echo image_tag('img_subi_tu_auto/upcar_ico_agrega_foto.png') ?> </a>
     </li>
</ul>

<ul class="upcar_titles">
    <li>
		<h2>Frente</h2>
	</li>
	<li>
		<h2>Interior</h2>
	</li>
	<li>
		<h2>Trasera</h2>
	</li>
	<li>
		<h2>Lateral</h2>
	</li>
	<li>
		<h2>Extra</h2>
	</li>
</ul>


</div><!-- upcar_box_2 -->




<div class="upcar_box_2">
<div class="upcar_box_2_header"><h2>Desperfectos</h2></div>

<ul class="upcar_fotos">
    <li>
	<div id="previewdesperfecto1">
		
		
		    <?php if($car->getPhotoFile('desperfecto1')): ?>
			    <?php echo image_tag("cars/".$car->getPhotoFile('desperfecto1')->getFileName(), 'size=74x74') ?>
		    <?php else: ?>
			    <?php echo image_tag('img_subi_tu_auto/upcar_sin_foto.png', 'size=74x74 class=imagen') ?>
		    <?php endif; ?>
		
	</div>
        <a id="linkdesperfecto1" href="#"><?php echo image_tag('img_subi_tu_auto/upcar_ico_agrega_foto.png') ?> </a>
     </li>

         <li>
	<div id="previewdesperfecto2">
	
		    <?php if($car->getPhotoFile('desperfecto2') != NULL): ?>
			    <?php echo image_tag("cars/".$car->getPhotoFile('desperfecto2')->getFileName(), 'size=74x74') ?>
		    <?php else: ?>
			    <?php echo image_tag('img_subi_tu_auto/upcar_sin_foto.png', 'size=74x74 class=imagen') ?>
		    <?php endif; ?>

	 </div>
       <a id="linkdesperfecto2" href="#"><?php echo image_tag('img_subi_tu_auto/upcar_ico_agrega_foto.png') ?> </a>
     </li>

         <li>
		<div id="previewdesperfecto3">
			
			    <?php if($car->getPhotoFile('desperfecto3') != NULL): ?>
				    <?php echo image_tag("cars/".$car->getPhotoFile('desperfecto3')->getFileName(), 'size=74x74') ?>
			    <?php else: ?>
				    <?php echo image_tag('img_subi_tu_auto/upcar_sin_foto.png', 'size=74x74 class=imagen') ?>
			    <?php endif; ?>
		
		</div>
        <a id="linkdesperfecto3" href="#"><?php echo image_tag('img_subi_tu_auto/upcar_ico_agrega_foto.png') ?> </a>
     </li>

         <li>
		 <div id="previewdesperfecto4">
			
		
		 	   <?php if($car->getPhotoFile('desperfecto4') != NULL): ?>
				    <?php echo image_tag("cars/".$car->getPhotoFile('desperfecto4')->getFileName(), 'size=74x74') ?>
			    <?php else: ?>
				    <?php echo image_tag('img_subi_tu_auto/upcar_sin_foto.png', 'size=74x74 class=imagen') ?>
			    <?php endif; ?>
		
		
		</div>
        <a id="linkdesperfecto4" href="#"><?php echo image_tag('img_subi_tu_auto/upcar_ico_agrega_foto.png') ?> </a>
     </li>

         <li>
		<div id="previewdesperfecto5">
			    <?php if($car->getPhotoFile('desperfecto5') != NULL): ?>
				    <?php echo image_tag("cars/".$car->getPhotoFile('desperfecto5')->getFileName(), 'size=74x74') ?>
			    <?php else: ?>
				    <?php echo image_tag('img_subi_tu_auto/upcar_sin_foto.png', 'size=74x74 class=imagen') ?>
			    <?php endif; ?>
			
		</div>
       <a id="linkdesperfecto5" href="#"><?php echo image_tag('img_subi_tu_auto/upcar_ico_agrega_foto.png') ?> </a>
     </li>
</ul>


</div><!-- upcar_box_2 -->




<?php /*

<!-- calendario y ubicacion del auto -->
<div class="upcar_box_3">
<div class="upcar_box_3_header">



</div><!-- upcar_box_3_header -->
<br><br><br><br><br><br><br><br><br><br>
</div><!-- upcar_box_3 -->
*/ ?>

<!-- btn agregar otro auto -->






<a href="#" class="upcar_btn_guardar_datos" onclick="submitCar()">Guardar Datos</a>

</div><!-- upcar_box_1 -->


</div>



<!-- btn agregar otro auto -->

