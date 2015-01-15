<div class="space-100 hidden-xs"></div>
              
              <div class="der">   
        <input type="text" id="comuna" name="comuna" placeholder="Comuna"/>                     
    	<select id="valor" style="display:none; width:20px;">
	        <option value="ninguna">---</option>
	        <?php
	        //count($comunas)
	        	foreach ($Communes as $commun):
	        		echo "<option value='".$commun['id']."'>".$commun['name']."</option>";
	        	endforeach;
	        ?>
    	</select>
		<input type="hidden" name="comunaId" id="comunaId" value="">
</div>
<div class="space-100 hidden-xs"></div>


