<?php use_stylesheet('calendario.css') ?>
<?php use_stylesheet('registro.css') ?>
<?php use_stylesheet('mis_arriendos.css') ?>

<script>

	$(document).ready(function() {
		
		$("a.Confirmar").click(function(event) {
			
			event.preventDefault();
			var id = $(this).attr("id");

			if( !$("#chkcontrato_" + id).is(":checked") ) {
				
				alert("Debes aceptar los terminos del contrato para continuar");
				return false;				
			}
			else {
				
				top.location = $(this).attr("href");
			}
		})

		$("a.vercontrato").live("click", function(event) {

			event.preventDefault();
			
			var inicio = $('#desde_' + $(this).attr('id') ).text();
			var termino = $('#hasta_' + $(this).attr('id') ).text();
			window.open( $(this).attr("href") + '&inicio=' + inicio + '&termino=' + termino );

		})		
	})

    function validar(){
        if(confirm('\xBF Est\xE1s seguro?')){
            return true;
        } else {
            return false;
        }
    }
</script>

<div class="main_box_1">
    <div class="main_box_2">


        <div class="main_col_izq">
            <?php include_component('profile', 'profile') ?>
            <?php include_component('profile', 'userdocsvalidation') ?>

        </div><!-- main_col_izq -->

        <!--  contenido de la seccion -->
        <div class="main_contenido">
            <h1 class="calen_titulo_seccion">Mis Arriendos</span></h1>     
            <?php if (count($toconfirm)): ?>
                <table class="misArriendos">
                    <tr>
                        <th>Desde</th>
                        <th>Hasta</th>
                        <th>Estado</th>
                        <th>Env&iacute;a un mensaje</th>
                        <th>Calificaciones positivas</th>
                        <th>Puntaje de limpieza</th>
                        <th colspan="2">Acciones</th>
                    </tr>
                    <?php foreach ($toconfirm as $reserve): ?>
                        <tr>
                            <td id="desde_<?=$reserve->getId()?>"><?= $reserve->getDate() ?></td>
                            <td id="hasta_<?=$reserve->getId()?>"><?= $reserve->getFechafin() ?></td>
                            <td>Por confirmar</td>
                            <td class="enviarmsj">
                                <?php include_component("profile", 'pictureFile', array("user" => $reserve->getUser(), "params" => "width=23px height=23px")) ?><br/>
                                <?php include_component("messages", "linkMessage", array("user" => $reserve->getUser())) ?>
                            </td>
                            <td><?php include_component("messages", "positiveRatings", array("user" => $reserve->getUser())) ?></td>
                            <td><?php include_component("messages", "cleanRatings", array("user" => $reserve->getUser())) ?></td>
                            <td>
                            	<input type="checkbox" id="chkcontrato_<?php echo $reserve->getId()?>" value="1"><a target="_blank" href="<?php echo url_for('profile/agreePdf2')?>?id=<?php echo $reserve->getId() ?>" class="vercontrato" id="<?php echo $reserve->getId()?>">Contrato</a><br/>
                            	<?= link_to('Confirmar', 'profile/confirmReserve?id=' . $reserve->getId(), array('title' => 'Confirmar', 'id' => $reserve->getId() , 'class' => 'Confirmar')) ?><br/>
                                <?php
                                echo link_to('Cancelar', 'profile/cancelReserveConfirmation?id=' . $reserve->getId(), array(
                                    'onclick' => 'return validar()', 'title' => 'Cancelar'
                                ))
                                ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            <?php else: ?>
                <p class="mensaje_alerta">No tienes arriendos pendientes de confirmar</p>
            <?php endif; ?>

        </div><!-- main_box_2 -->
        <div class="clear"></div>
    </div><!-- main_box_1 -->
