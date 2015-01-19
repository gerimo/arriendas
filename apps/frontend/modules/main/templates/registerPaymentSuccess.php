<?php use_stylesheet('registro.css') ?>

<?php echo form_tag('main/doRegister', array('method'=>'post', 'id'=>'frm1')); ?> 

<script>
function submitFrom()
{
	document.forms["frm1"].submit();
}
</script>

<div class="regis_box">
<div class="regis_box_somb_izq"></div>
<div class="regis_box_somb_dere"></div>
<h1>Registro de usuarios</h1>

<!-- sector usuario -->

<div class="regis_box_usuario">
    <div class="regis_foto_frame">
        <?= image_tag('img_registro/tmp_user_foto.jpg') ?>         
    </div>
    
  <!--  <h2 class="regis_usuario">Katia Dolizniak</h2>-->
    
   <!-- <ul class="regis_validar">
        <li>Validar direccion</li>
        <li>Validar direccion</li>
        <li>Validar direccion</li>
        <li>Validar direccion</li>        
    </ul>
    
   -->
    
</div><!-- regis_box_usuario -->


<!-- sector datos de usuario -->
<div class="regis_box_info">     
   
    <div class="regis_titulo_1">Datos de usuario</div>
    
    <div class="regis_formulario">  
	
	<h2 style="width:400px">IMPLEMENTACION DE PAYPAL</h2><br/>
	
   <?php /* 
    <div class="c1">
        <label>Nombre de usuario:</label>
        <input type="text" id="username" name="username"  >
    </div><!-- /c1 -->
    
    <div class="c1">
        <label>Contrase&ntilde;a</label>
        <input id="password" name="password" type="password" /><br/>
     </div><!-- /c1 -->
     
    <div class="c1">
        <label>Confirma tu contrase&ntilde;a</label>
        <input id="passwordAgain" name="passwordAgain" type="password" /><br/>
     </div><!-- /c1 -->
    
    <div class="c1">
        <label>Nombre:</label>
        <input id="firstname" name="firstname" type="text"  >
    </div><!-- /c1 -->    
    
    <div class="c1">
        <label>Apellido:</label>
        <input id="lastname" name="lastname" type="text"  >
    </div><!-- /c1 -->    
    
    <div class="c1">
        <label>Email:</label>
        <input type="text" id="email" name="email" >
    </div><!-- /c1 -->    
    
    <div class="c1">
        <label>Confirma tu Email:</label>
        <input type="text" id="emailAgain" name="emailAgain" >
    </div><!-- /c1 -->    
    
    <div class="c1">
        <label>Tel&eacute;fono:</label>
        <input type="text" name="" >
    </div><!-- /c1 -->
    
    <div class="c1">
        <label>Pa&iacute;s:</label>
        <input type="text" name="" >
    </div><!-- /c1 -->
    
    <div class="c1">
        <label>Pa&iacute;s / Ciudad:</label>
        <input type="text" name="" >
    </div><!-- /c1 -->
    
    <div class="c1">
        <label>Fecha de nacimiento:</label>
        <input type="text" name="" >
    </div><!-- /c1 -->
       
   
    </div><!-- regis_formulario -->

    <div class="regis_botones">     
    */ ?>
	
    <button class="regis_btn_formulario right" onclick="submitFrom()"> Siguiente </button>        

    </div><!-- regis_botones -->
    

</div><!-- regis_box_info -->

   
<div class="clear"></div>
</div><!-- /regis_box -->


</form>
