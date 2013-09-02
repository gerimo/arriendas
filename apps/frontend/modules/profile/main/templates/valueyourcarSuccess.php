<?php use_stylesheet('registro.css') ?>
<?php use_stylesheet('comunes.css') ?>
<div class="main_box_1">
<div class="main_box_2">
<?php echo form_tag('main/doRegister', array('method'=>'post', 'id'=>'frm1'));?>

<div class="regis_box_somb_izq"></div>
<div class="regis_box_somb_dere"></div>
<h1>Monetiza tu auto</h1>
<style>
.regis_box_somb_izq { background:none;}
.regis_box_somb_dere { background:none;}
.header { margin-bottom: 30px}
</style>
<script>

(function( $ ){
	jQuery.fn.correo=function(){
		if(/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test($(this).val())){
			return true;
		}else{
			$(this).focus();
			return false;
		}
	};
})( jQuery );

 $(document).ready(function() {


				      
		getModel($("#brand"));
		 
		$("#brand").change(function(){
			getModel($(this)); 
		});
		
		
		
$("#calc").click(function() {

	if( $("input#email").val() == '' ) { alert('Debes ingresar un email'); return false; }
	else if(!$("input#email").correo()) { alert('Debes ingresar un email valido'); return false; }

	 var brand = encodeURIComponent(document.getElementById("brand").value);
	 var model = encodeURIComponent(document.getElementById("model").value);
	 var email = encodeURIComponent(document.getElementById("email").value);
	 var year = encodeURIComponent(document.getElementById("year").value);
	 
	 var url = "<?php echo url_for('main/price')?>"+"?year=" + year + "&brand=" + brand + "&model=" + model + "&email=" + email;
	  	
	  	      //document.write(var_dump(url,'html'));
	  	      
	  $("#pricecontainer").load(url,  function() {
				  
	  });

});
		
		
		
		
		

});


function getModel(currentElement){
	
     $("#model").html("");
	
     $.getJSON("<?php echo url_for('main/getModel')?>",{ marca:currentElement.val(), ajax: 'true'}, function(j){
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

</script>


<!-- sector usuario -->

<div class="regis_box_usuario">


</div><!-- regis_box_usuario -->


<!-- sector datos de usuario -->
<div class="regis_box_info">     
 
    
    <div class="regis_formulario">  
 

   <div class="c1">
   <div class="c2">
        <label>Marca del auto:</label>
	<select name="brand" id="brand">
		<!--
		<?php foreach ($brand as $b): ?>
			<option value="<?php echo $b->getId() ?>" ><?php echo $b->getName() ?></option> 		
		<?php endforeach; ?>
		-->

		<?php foreach ($marcas as $m): ?>
			<option value="<?php echo $m->getMarca() ?>" ><?php echo $m->getMarca() ?></option> 		
		<?php endforeach; ?>
		marcas
	</select>
   </div><!-- /c2 -->
   <div class="c2">
        <label>Modelo del auto:</label>
	<select name="model" id="model">
	</select>
   </div><!-- /c2 -->
   <div class="c2">
        <label>A&ntilde;o</label>
	<select name="year" id="year">
		<?php for($i = 2012; $i >= 1996 ;$i--): ?>
			<option value="<?php echo $i?>" ><?php echo $i?></option> 		
		<?php endfor;?>
	</select>
   </div><!-- /c2 -->
   <div class="c2">
        <label>Email:</label>
		<input type="text" name="email" id="email">
	</select>
   </div><!-- /c2 -->
    </div><!-- /c1 -->

	
	
    <div class="clear"></div>
	 
   <div class="c1">
   <div class="c2">
    <div id="pricecontainer"></div>
   </div><!-- /c2 -->
    </div><!-- /c1 -->
    
    </div><!-- regis_formulario -->

    <div class="regis_botones">     
    
    <input  id="calc" type="button" class="regis_btn_formulario right" value="Enviar a mi Email"/>

    </div><!-- regis_botones -->
    

</div><!-- regis_box_info -->

</form>
   
<div class="clear"></div>
</div><!-- /regis_box -->
</div>
