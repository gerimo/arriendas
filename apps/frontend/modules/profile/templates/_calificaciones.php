<link href="/css/newDesign/rating.css" rel="stylesheet" type="text/css"> 	
<link href="/css/newDesign/calificaciones.css" rel="stylesheet" type="text/css">


<?php
$useragent=$_SERVER['HTTP_USER_AGENT'];
if(preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i',$useragent)||preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i',substr($useragent,0,4))){
  use_stylesheet('moviles.css');
}
?>

<div class="hidden-xs space-100"></div>
<div class="visible-xs space-100"></div>
<div class="container">
	<div class="row">
		<div class="col-xs-12 col-md-12 ">
  			<div class="saso col-xs-12 col-md-offset-2 col-md-8">
				<div class="barraSuperior col-xs-12 col-md-12 "><h1>Calificaciones</h1></div>
				<div class="barrasNavegadoras col-md-12">
						<a href="#" id="opcion1" class="select"><div id="boton1" class="boton col-md-4">Calificaciones pendientes</div></a>
						<a href="#" id="opcion2"><div id="boton2" class="boton col-md-4">Calificaciones hechas por ti</div></a>
						<a href="#" id="opcion3"><div id="boton3" class="boton col-md-4">Calificaciones sobre ti</div></a>
				</div>
				<div class="opiniones col-xs-12 col-md-12">
					<div class="conten_opcion1">
						<?php
							if(isset($arrayRenter_Pendiente)){
								$cantidadArrendadores = count($arrayRenter_Pendiente);
							}else{
								$cantidadArrendadores = 0;
							}
							if(isset($arrayOwner_Pendiente)){
								$cantidadDuenios = count($arrayOwner_Pendiente);
							}else{
								$cantidadDuenios = 0;
							}
							
							$totalPendientes = 0;
							if($cantidadDuenios == 0 && $cantidadArrendadores == 0){
								?>
									<div class="noHay"><p>No existen calificaciones pendientes por realizar</p></div>
								<?php
							}else{
								?>
								<div class="menu">
						        <ul>
						        	<?php
						        	if($cantidadDuenios>0){
						        		$totalPendientes = $totalPendientes + $cantidadDuenios;
						        		for($i=0;$i<$cantidadDuenios;$i++){
						        			?>
						        			<?php echo form_tag('profile/calificaciones', array('method' => 'post', 'enctype' => 'multipart/form-data', 'class' => 'frmCalificaciones')); ?>
									        <li>
								                <a href="#" class="encabezadoPestania col-md-12">
								                	<?php
										            if($arrayOwner_Pendiente[$i]['facebook_id']!=null && $arrayOwner_Pendiente[$i]['facebook_id']!=""){
						                                echo "<img src='".$arrayOwner_Pendiente[$i]['picture_file']."' class='img_usuario2 '/>";
						                            }else{
						                                if($arrayOwner_Pendiente[$i]['picture_file']!=""){
						                                    $filename = explode("/", $arrayOwner_Pendiente[$i]['picture_file']);
						                                    echo image_tag("users/".$filename[Count($filename) - 1], 'class=img_usuario2');
						                                }else{
						                                    echo image_tag('img_calificaciones/tmp_user_foto.jpg', 'class=img_usuario2');
						                                }
						                            }
								                	?>
								                	<p><u>¿Cómo fue tu experiencia con <span><?php echo strtoupper($arrayOwner_Pendiente[$i]->getFirstname())." ".strtoupper($arrayOwner_Pendiente[$i]->getLastname()); ?></span></u></p>
								                	<div class="fechaAcord"><?=$idCalificacionesComoRenter[$i]['fecha'];?></div><!-- PREGUNTAR SOBRE LA FECHA EN RESERVE -->
								                </a>
								                <ul>
									                <div class="col-md-12 contenidoPestania_R numero<?=($i);?>">
									                	<div class="formularioContenidoPestania">
										                	<div class="recoUsuarios col-xs-12 col-md-12">
										                		<?php echo image_tag('img_evaluaciones/IconoAuto.png','class=img_IconoAuto2') ?>
										                		<p><span>¿Recomendarías este auto a otros usuarios?</span></p>
										                		<div class="botonesSi_No">
										                			<input type="button" class="botSI_usua"></input>
										                			<input type="button" class="botNO_usua"></input>
										                		</div>
										                	</div>
										                	<div class="TextoOculto col-xs-12 col-md-12" style="display:none">
										                		<textarea class="texto1" name="textoRecomenAuto_NO<?=($i);?>" placeholder="¿Por qué no?"></textarea>
										                	</div>
										                	<div class="desperfecto col-xs-12 col-md-12">
										                		<?php echo image_tag('img_evaluaciones/IconoRechazado.png','class=img_Desperfecto') ?>
										                		<p><span>¿Tenía el auto algún desperfecto mecánico?</span></p>
										                		<div class="botonesSi_No">
										                			<input type="button" class="botSI_desp"></input>
										                			<input type="button" class="botNO_desp"></input>
										                		</div>
										                	</div>
										                	<div class="TextoOculto3" style="display:none">
										                		<textarea class="texto3" name="textoDesperfecto_SI<?=($i);?>" placeholder="¿Cual?"></textarea>
										                	</div>
										                	<div class="separacion col-xs-12 col-md-12"></div>
										                	<div class="recomiendas col-xs-12 col-md-12">
										                		<?php echo image_tag('img_evaluaciones/IconoUsuario.png','class=img_IconoUsuario2') ?>
										                		<p><span>¿Recomiendas a <?php echo strtoupper($arrayOwner_Pendiente[$i]->getFirstname());?> para la comunidad Arriendas?</span></p>
										                		<div class="botonesSi_No">
										                			<input type="button" class="botSI_reco"></input>
										                			<input type="button" class="botNO_reco"></input>
										                		</div>
										                	</div>
										                	<div class="TextoOculto2 col-xs-12 col-md-12" style="display:none">
										                		<textarea class="texto2" name="textoRecomen_NO<?=($i);?>" placeholder="¿Por qué no?"></textarea>
										                	</div>
										                	<div class="fuePuntual col-xs-12 col-md-12">
										                		<?php echo image_tag('img_evaluaciones/IconoPuntualidad.png','class=img_IconoPuntualidad2') ?>
										                		<p><span>¿Fue puntual?</span></p>
										                		<div class="botonesSi_No">
										                			<input type="button" class="botSI_punt"></input>
										                			<input type="button" class="botNO_punt"></input>
										                		</div>
										                	</div>
										                	<div class="inputOculto_punt col-xs-12 col-md-12">
										                		<p>Tiempo que se demoró: </p>
										                		<select class="start_punt<?=($i);?>" name="star_impuntualidad<?=($i);?>">
										                			<option value="0">Al comienzo</option><option value="15">15 minutos</option><option value="30">30 minutos</option><option value="60">60 minutos</option><option value="90">90 minutos</option><option value="120">120 minutos</option><option value="180">150 minutos</option><option value="180">3 horas</option>
										                		</select>
										                		<select class="end_punt<?=($i);?>" name="end_impuntualidad<?=($i);?>">
										                			<option value="0">Al término</option><option value="15">15 minutos</option><option value="30">30 minutos</option><option value="60">60 minutos</option><option value="90">90 minutos</option><option value="120">120 minutos</option><option value="180">150 minutos</option><option value="180">3 horas</option>
										                		</select>
										                	</div>
										                	<div class="autoLimpio col-xs-12 col-md-12">
										                		<?php echo image_tag('img_evaluaciones/IconoLimpieza.png','class=img_IconoLimpieza2') ?>
										                		<p><span>¿El interior del auto estaba limpio?</span></p>
										                		<div class="botonesEstrella">
										                			<div class="star<?=($i);?> rating">&nbsp;</div>
										                		</div>
										                	</div>
										                	<div class="escribeSobre col-xs-12 col-md-12">
										                		<?php echo image_tag('img_evaluaciones/IconoComentario.png','class=img_IconoComentario') ?>
										                		<p><span>Escribe de tu experiencia con <?php echo strtoupper($arrayOwner_Pendiente[$i]->getFirstname());?></span><span class="indicador"> (se publicará en el perfil)</span></p>
										                		<textarea class="textoPublicacion<?=($i);?>  col-md-12" name="algoBreve<?=($i);?>" placeholder="Escribe algo breve..."></textarea>
										                	</div>
										                	<input type="hidden" class="preg_reco" name="preg_reco<?=($i);?>" value="0" />
										                	<input type="hidden" class="preg_desp" name="preg_desp<?=($i);?>" value="0" />
										                	<input type="hidden" class="preg_usua" name="preg_usua<?=($i);?>" value="0" />
										                	<input type="hidden" class="preg_punt" name="preg_punt<?=($i);?>" value="0" />
										                	<input type="hidden" class="estrella_llena" name="estrella_llena<?=($i);?>" value="0" />
										                	<div class="botonListo">
										                		<input type="hidden" name="listoOK<?=($i);?>" value="ok<?=($i);?>"/>
										                		<input type="hidden" name="guardandoComo" value="renter">
										                		<input type="hidden" name="posicion" value="<?=($i);?>"/>
										                		<input type="hidden" name="idCalificacion" value="<?=$idCalificacionesComoRenter[$i]['id'];?>"/>
										                		<input class="listo" type="submit" name="<?=($i);?>" value="" />
										                	</div>
										                	<!-- <div id="ajax_loader"></div> -->
									                	</div> <!-- fin formularioContenidoPestania -->
									                </div><!-- fin contenidoPestania -->
								           		</ul>
								            </li>
								            </form>
						        			<?php
						        		}//fin for
						        	}//fin si
						        	if($cantidadArrendadores>0){
						        		$totalPendientes = $totalPendientes + $cantidadArrendadores;
						        		$k=0;
						        		for($i=$cantidadDuenios;$i<$totalPendientes;$i++){
						        			?>
						        			<?php echo form_tag('profile/calificaciones', array('method' => 'post', 'enctype' => 'multipart/form-data', 'class' => 'frmCalificaciones')); ?>
									        <li>
								                <a href="#" class="encabezadoPestania click_owner">
								                	<?php
										            if($arrayRenter_Pendiente[$k]['facebook_id']!=null && $arrayRenter_Pendiente[$k]['facebook_id']!=""){
						                                echo "<img src='".$arrayRenter_Pendiente[$i]['picture_file']."' class='img_usuario2'/>";
						                            }else{
						                                if($arrayRenter_Pendiente[$k]['picture_file']!=""){
						                                    $filename = explode("/", $arrayRenter_Pendiente[$k]['picture_file']);
						                                    echo image_tag("users/".$filename[Count($filename) - 1], 'class=img_usuario2');
						                                }else{
						                                    echo image_tag('img_calificaciones/tmp_user_foto.jpg', 'class=img_usuario2');
						                                }
						                            }
								                	?>
								                	<p><u>¿Cómo fue tu experiencia con <span><?php echo strtoupper($arrayRenter_Pendiente[$k]->getFirstname())." ".strtoupper($arrayRenter_Pendiente[$k]->getLastname()); ?></span></u></p>
								                	<div class="fechaAcord"><?=$idCalificacionesComoOwner[$k]['fecha'];?></div><!-- PREGUNTAR SOBRE LA FECHA EN RESERVE -->
								                </a> 
								                <ul>
									                <div class="contenidoPestania_O numero<?=($i);?>">
									                	<div class="formularioContenidoPestania">
										                	<div class="recomiendas">
										                		<?php echo image_tag('img_evaluaciones/IconoUsuario.png','class=img_IconoUsuario2') ?>
										                		<p><span>¿Recomiendas a <?php echo strtoupper($arrayRenter_Pendiente[$k]->getFirstname());?> para la comunidad Arriendas?</span></p>
										                		<div class="botonesSi_No">
										                			<input type="button" class="botSI_reco"></input>
										                			<input type="button" class="botNO_reco"></input>
										                		</div>
										                	</div>
										                	<div class="TextoOculto2" style="display:none">
										                		<textarea class="texto2" name="textoRecomen_NO<?=($i);?>" placeholder="¿Por qué no?"></textarea>
										                	</div>
										                	<div class="fuePuntual">
										                		<?php echo image_tag('img_evaluaciones/IconoPuntualidad.png','class=img_IconoPuntualidad2') ?>
										                		<p><span>¿Fue puntual?</span></p>
										                		<div class="botonesSi_No">
										                			<input type="button" class="botSI_punt"></input>
										                			<input type="button" class="botNO_punt"></input>
										                		</div>
										                	</div>
										                	<div class="inputOculto_punt">
										                		<p>Tiempo que se demoró: </p>
										                		<select class="start_punt<?=($i);?>" name="star_impuntualidad<?=($i);?>">
										                			<option value="0">Al comienzo</option><option value="15">15 minutos</option><option value="30">30 minutos</option><option value="60">60 minutos</option><option value="90">90 minutos</option><option value="120">120 minutos</option><option value="180">150 minutos</option><option value="180">3 horas</option>
										                		</select>
										                		<select class="end_punt<?=($i);?>" name="end_impuntualidad<?=($i);?>">
										                			<option value="0">Al término</option><option value="15">15 minutos</option><option value="30">30 minutos</option><option value="60">60 minutos</option><option value="90">90 minutos</option><option value="120">120 minutos</option><option value="180">150 minutos</option><option value="180">3 horas</option>
										                		</select>
										                	</div>
										                	<div class="autoLimpio">
										                		<?php echo image_tag('img_evaluaciones/IconoLimpieza.png','class=img_IconoLimpieza2') ?>
										                		<p><span>¿El interior del auto estaba limpio?</span></p>
										                		<div class="botonesEstrella">
										                			<div class="star<?=($i);?> rating">&nbsp;</div>
										                		</div>
										                	</div>
										                	<div class="escribeSobre">
										                		<?php echo image_tag('img_evaluaciones/IconoComentario.png','class=img_IconoComentario') ?>
										                		<p><span>Escribe de tu experiencia con <?php echo strtoupper($arrayRenter_Pendiente[$k]->getFirstname());?></span><div class="indicador"> (se publicará en el perfil)</div></p>
										                		<textarea class="textoPublicacion<?=($i);?>" name="algoBreve<?=($i);?>" placeholder="Escribe algo breve..."></textarea>
										                	</div>

										                	<!-- Campos que se mandan, pero no se guardan -->
										                	<input type="hidden" class="preg_usua" name="preg_usua<?=($i);?>" value="3" />
										                	<input type="hidden" class="preg_desp" name="preg_desp<?=($i);?>" value="3" />

										                	<input type="hidden" class="preg_reco" name="preg_reco<?=($i);?>" value="0" />
										                	<input type="hidden" class="preg_punt" name="preg_punt<?=($i);?>" value="0" />
										                	<input type="hidden" class="estrella_llena" name="estrella_llena<?=($i);?>" value="0" />
										                	<div class="botonListo">
										                		<input type="hidden" name="listoOK<?=($i);?>" value="ok<?=($i);?>"/>
										                		<input type="hidden" name="guardandoComo" value="owner">
										                		<input type="hidden" name="posicion" value="<?=($i);?>"/>
										                		<input type="hidden" name="idCalificacion" value="<?=$idCalificacionesComoOwner[$k]['id'];?>"/>
										                		<input class="listo" type="submit" name="<?=($i);?>" value="" />
										                	</div>
										                	<!-- <div id="ajax_loader"></div> -->
									                	</div> <!-- fin formularioContenidoPestania -->
									                </div><!-- fin contenidoPestania -->
								           		</ul>
								            </li>
								            </form>
						        			<?php
						        			$k++;
						        		}//fin for
						        	}//fin si
						        	?>
						        </ul>
						   		</div><!-- fin menu -->
								<?php
							}//fin if-else
						?>
					</div><!-- fin contenido opcion 1 -->

					<div class="conten_opcion2 col-xs-12 col-md-12">
					<!-- MOSTRAR COMENTARIOS QUE HICISTE A OTROS USUARIOS -->
					    <div class="evaluaciones">
				        <div class="evaluacion">
				            <?php
				            	$cantidadDeComentariosHechosPorMi = count($comentariosHechosPorMi);

				            	if($comentariosHechosPorMi[0] == null){
				            		?>
				            			<div class="noHay"><p>En este momento no existen comentarios hechos por ti</p></div>
				            		<?php
				            	}else{
				                	for ($i=0; $i < $cantidadDeComentariosHechosPorMi ; $i++) {
				            ?>

				            <div class="comentario">
				                <div class="usuario col-xs-12 col-sm-3 col-md-3 text-center">

				                    <?php echo "<a href='".url_for('profile/publicprofile?id='.$comentariosHechosPorMi[$i]['idEvaluado'])."'>"; ?>
				                    <div class="fotoPosteador col-xs-offset-4 col-xs-4 col-sm-offset-2 col-sm-8 col-md-offset-2 col-md-8 text-center">
				                        <?php 
				                            
				                            if($comentariosHechosPorMi[$i]['facebookPersonaEvaluada']!=null && $comentariosHechosPorMi[$i]['facebookPersonaEvaluada']!=""){
				                                echo "<img src='".$comentariosHechosPorMi[$i]['urlFotoPersonaEvaluada']."' class='img_usuario'/>";
				                            }else{
				                                if($comentariosHechosPorMi[$i]['urlFotoPersonaEvaluada']!=""){
				                                    $filename = explode("/", $comentariosHechosPorMi[$i]['urlFotoPersonaEvaluada']);
				                                    echo image_tag("users/".$filename[Count($filename) - 1], 'class=img_usuario');
				                                }else{
				                                    echo image_tag('img_registro/tmp_user_foto.jpg', 'class=img_usuario');
				                                }
				                            }
				                            
				                        ?>
				                    </div>
				                    <div class="nombrePosteador col-xs-12 col-md-offset-2 col-md-8 text-center"><p><?php echo $comentariosHechosPorMi[$i]['nombrePersonaEvaluada']." ".$comentariosHechosPorMi[$i]['apellidoPersonaEvaluada']; ?></p></div>
				                    </a>

				                </div>
				                <div class="campoTexto col-xs-12 col-sm-9 col-md-9">
				                    <div class="col-sm-12 col-md-12 margenTexto">
				                        <div class="texto col-sm-12 col-md-12">
				                            <p><?php echo $comentariosHechosPorMi[$i]['comentarioEvaluacion']; ?></p>
				                            <div class="detallesComentario">
				                            	<div class="fecha"><span>|</span><p><?php echo $comentariosHechosPorMi[$i]['fechaEvaluacion']; ?></p></div>
				                            	<div class="limp">
				                            		<span>|</span><?php echo image_tag('img_evaluaciones/IconoLimpieza.png','class=img_det_limp') ?>
				                            		<p>Limpieza</p>
				                            		<p><?=$comentariosHechosPorMi[$i]['evaluadoLimpieza'];?>/5</p>
				                            		<?php
				                            			if($comentariosHechosPorMi[$i]['evaluadoLimpieza'] > 2){
				                            		?>
				                            			<?php echo image_tag('img_evaluaciones/IconoDedoArriba.png','class=img_dedo_arriba') ?>
				                            		<?php
				                            			}else{
				                            		?>
				                            			<?php echo image_tag('img_evaluaciones/IconoDedoAbajo.png','class=img_dedo_abajo') ?>
				                            		<?php
				                            			}
				                            		?>

				                            	</div>
				                            	<div class="punt">
				                            		<span>|</span><?php echo image_tag('img_evaluaciones/IconoPuntualidad.png','class=img_det_punt') ?>
				                            		<?php
				                            			if($comentariosHechosPorMi[$i]['evaluadoPuntual'] == 1){
				                            		?>
				                            			<p>Puntual</p><?php echo image_tag('img_evaluaciones/IconoDedoArriba.png','class=img_dedo_arriba') ?>
				                            		<?php
				                            			}else{
				                            		?>
				                            			<p>Impuntual [<?php echo $comentariosHechosPorMi[$i]['evaluadoImpunt']; ?> min]</p><?php echo image_tag('img_evaluaciones/IconoDedoAbajo.png','class=img_dedo_abajo') ?>
				                            		<?php
				                            			}
				                            		?>
				                            	</div>
				                            	<div class="reco">
				                            		<?php echo image_tag('img_evaluaciones/IconoUsuario.png','class=img_det_reco') ?>
				                            		<?php
				                            			if($comentariosHechosPorMi[$i]['evaluadoRecomendado'] == 1){
				                            		?>
				                            			<p>Recomendado</p><?php echo image_tag('img_evaluaciones/IconoDedoArriba.png','class=img_dedo_arriba') ?>
				                            		<?php
				                            			}else{
				                            		?>
				                            			<p>No recomendado</p><?php echo image_tag('img_evaluaciones/IconoDedoAbajo.png','class=img_dedo_abajo') ?>
				                            		<?php
				                            			}
				                            		?>
				                            	</div>                            	
				                            </div>
				                        </div>
				                    </div>
				                </div>
				            </div>

				            <?php
				            		}//fin for
				            	}//fin if-else
				            ?>
				        </div><!-- fin evaluacion -->
				    </div><!-- fin evaluaciones -->
					</div>
					<div class="conten_opcion3 col-xs-12 col-md-12">
					<!-- MOSTRAR COMENTARIOS QUE OTROS HICIERON SOBRE TI -->
				    <div class="evaluaciones">
				        <div class="evaluacion">
				        	<?php
				        	$cantidadDeComentariosSobreMi = count($comentariosSobreMi);

				            	if($comentariosSobreMi[0] == null){
				            		?>
				            			<div class="noHay"><p>En este momento no existen comentarios sobre ti</p></div>
				            		<?php
				            	}else{
				        	?>
				            <div class="calificacionEstrellas col-xs-12 col-sm-offset-1 col-sm-11 col-md-offset-1 col-md-11 text-center">
				                <div class="puntualidad col-xs-12	 col-sm-6 col-md-6">
				                    <?php echo image_tag('img_evaluaciones/IconoPuntualidad.png','class=img_IconoPuntualidad') ?>
				                    <p><span>Puntualidad</span></p>
				                    <div class="estrellas">
				                        <?php
				                            for($i=0; $i<5; $i++){
				                                if($i<$puntualidad){//estrella marcada
				                                    echo image_tag('img_evaluaciones/IconoEstrellaCeleste.png');
				                                }else{//estrella no marcada
				                                    echo image_tag('img_evaluaciones/IconoEstrellaGris.png');
				                                }
				                            }
				                        ?>
				                    </div>
				                </div>
				                <div class="limpieza col-xs-12 col-sm-6 col-md-6">
				                    <?php echo image_tag('img_evaluaciones/IconoLimpieza.png','class=img_IconoLimpieza') ?>
				                    <p><span>Limpieza</span></p>
				                    <div class="estrellas">
				                        <?php
				                            for($i=0; $i<5; $i++){
				                                if($i<$limpieza){//estrella marcada
				                                    echo image_tag('img_evaluaciones/IconoEstrellaCeleste.png');
				                                }else{//estrella no marcada
				                                    echo image_tag('img_evaluaciones/IconoEstrellaGris.png');
				                                }
				                            }
				                        ?>
				                    </div>
				                </div>
				            </div>

				            <?php
				                for ($i=0; $i < $cantidadDeComentariosSobreMi ; $i++) {
				            ?>

				            <div class="comentario">
			                   <div class="usuario2 col-xs-12 col-sm-3 col-md-3 text-center">
			                    <?php echo "<a href='".url_for('profile/publicprofile?id='.$comentariosSobreMi[$i]['idEvaluador'])."'>"; ?>
			                    <div class="fotoPosteador col-xs-offset-4 col-xs-4 col-sm-offset-2 col-sm-8 col-md-offset-2 col-md-8 text-center">
		                        <?php       
	                            if($comentariosSobreMi[$i]['facebookEvaluador']!=null && $comentariosSobreMi[$i]['facebookEvaluador']!=""){
	                                echo "<img src='".$comentariosSobreMi[$i]['urlFotoEvaluador']."' class='img_usuario'/>";
	                            }else{
                                if($comentariosSobreMi[$i]['urlFotoEvaluador']!=""){
                                    $filename = explode("/", $comentariosSobreMi[$i]['urlFotoEvaluador']);
                                    echo image_tag("users/".$filename[Count($filename) - 1], 'class=img_usuario');
                                }else{
                                    echo image_tag('img_registro/tmp_user_foto.jpg', 'class=img_usuario');
                                }
	                            }
				                            
				                        ?>
				                    </div>
				                    <div class="nombrePosteador col-xs-12 col-md-offset-2 col-md-8 text-center"><p><?php echo $comentariosSobreMi[$i]['nombreEvaluador']." ".$comentariosSobreMi[$i]['apellidoEvaluador']; ?></p></div>
				                    </a>

				                </div>
				                <div class="campoTexto2 col-xs-12 col-sm-9 col-md-9">
				                    <?php echo image_tag('img_evaluaciones/TrianguloComentarios.png','class=punta') ?>
				                    <div class="margenTexto col-sm-12 col-md-12">
				                        <div class="texto col-sm-12 col-md-12">
				                            <p><?php echo $comentariosSobreMi[$i]['comentarioEvaluacion']; ?></p>
				                            <div class="detallesComentario">
				                            	<div class="fecha"><span>|</span><p><?php echo $comentariosSobreMi[$i]['fechaEvaluacion']; ?></p></div>
				                            	<div class="limp">
				                            		<span>|</span><?php echo image_tag('img_evaluaciones/IconoLimpieza.png','class=img_det_limp') ?>
				                            		<p>Limpieza</p>
				                            		<p><?=$comentariosSobreMi[$i]['limpio'];?>/5</p>
				                            		<?php
				                            			if($comentariosSobreMi[$i]['limpio'] > 2){
				                            		?>
				                            			<?php echo image_tag('img_evaluaciones/IconoDedoArriba.png','class=img_dedo_arriba') ?>
				                            		<?php
				                            			}else{
				                            		?>
				                            			<?php echo image_tag('img_evaluaciones/IconoDedoAbajo.png','class=img_dedo_abajo') ?>
				                            		<?php
				                            			}
				                            		?>

				                            	</div>
				                            	<div class="punt">
				                            		<span>|</span><?php echo image_tag('img_evaluaciones/IconoPuntualidad.png','class=img_det_punt') ?>
				                            		<?php
				                            			if($comentariosSobreMi[$i]['puntual'] == 1){
				                            		?>
				                            			<p>Puntual</p><?php echo image_tag('img_evaluaciones/IconoDedoArriba.png','class=img_dedo_arriba') ?>
				                            		<?php
				                            			}else{
				                            		?>
				                            			<p>Impuntual [<?php echo $comentariosSobreMi[$i]['impunt']; ?> min]</p><?php echo image_tag('img_evaluaciones/IconoDedoAbajo.png','class=img_dedo_abajo') ?>
				                            		<?php
				                            			}
				                            		?>
				                            	</div>
				                            	<div class="reco">
				                            		<?php echo image_tag('img_evaluaciones/IconoUsuario.png','class=img_det_reco') ?>
				                            		<?php
				                            			if($comentariosSobreMi[$i]['recomendado'] == 1){
				                            		?>
				                            			<p>Recomendado</p><?php echo image_tag('img_evaluaciones/IconoDedoArriba.png','class=img_dedo_arriba') ?>
				                            		<?php
				                            			}else{
				                            		?>
				                            			<p>No recomendado</p><?php echo image_tag('img_evaluaciones/IconoDedoAbajo.png','class=img_dedo_abajo') ?>
				                            		<?php
				                            			}
				                            		?>
				                            	</div>
				                            </div>
				                        </div>
				                    </div>
				                </div>
				            </div>

				            <?php
				            		} //fin for
				        		}//fin if-else
				            ?>
				        </div> <!-- fin evaluacion -->
				    </div><!-- fin evaluaciones -->
					</div> <!-- fin content_opcion3 -->
				</div>
			</div>
		</div>
	</div>
<div class="hidden-xs space-100"></div>
<div class="visible-xs space-50"></div>	
</div>

<script type="text/javascript">

function cargaStar(){
	var arrayStars = new Array();
    <?php
      	for($i = 0; $i<$cantidadDuenios; $i++){
            echo 'arrayStars['. $i .'] = '. $idCalificacionesComoRenter[$i]['id'].'; ';
      	}
      	$k=0;
      	for($i = $cantidadDuenios; $i<$totalPendientes; $i++){
            echo 'arrayStars['. $i .'] = '. $idCalificacionesComoOwner[$k]['id'].'; ';
            $k++;
      	}
    ?>
    var cantidadStarsOwner = "<?php echo $cantidadDuenios; ?>" ;
	var cantidadStars = "<?php echo $totalPendientes; ?>" ;
	for (var i=0;i<cantidadStars;i++){
		var nombreStarString = '.star'+i;
		indice = arrayStars[i];
		if(cantidadStarsOwner > i){ //Para Estrellas que califiquen los Renters a los Owners
			$(nombreStarString).rating('uploadRatingStar', {maxvalue: 5, curvalue:1, id:indice, tipo:2}); //tipo 1: como owner, tipo 2: como renter
		}else{ //Para Estrellas que califiquen los Owners a los Renters
			$(nombreStarString).rating('uploadRatingStar', {maxvalue: 5, curvalue:1, id:indice, tipo:1}); //tipo 1: como owner, tipo 2: como renter
		}
	
	}
}

	//JS ENVIAR FORM VÍA AJAX
    // definimos las opciones del plugin AJAX FORM
    var opciones= {
                       beforeSubmit: mostrarLoader, //funcion que se ejecuta antes de enviar el form
                       success: mostrarRespuesta, //funcion que se ejecuta una vez enviado el formulario
					   
    };
     //asignamos el plugin ajaxForm al formulario myForm y le pasamos las opciones
    $('.frmCalificaciones').ajaxForm(opciones) ; 
    
     //lugar donde defino las funciones que utilizo dentro de "opciones"
     
     function mostrarLoader(){
     		  $("#ajax_loader").append("<img class='loader_gif' src='(/../../../images/ajax-loader.gif' style=' display:none;'/>");
              $(".loader_gif").fadeIn("slow");
     };
     function mostrarRespuesta (responseText){
                  $(".loader_gif").fadeOut("slow");
 				  
 				  //alert(responseText);
 				  location.href="calificaciones";
 				  /*)
                  if(responseText == "1"){
                  		$("#ajax_loader").append("<br><div id='mensajeAJAX'>TODO BIEN</div>");
                  }else if(responseText == "0"){
                  		$("#ajax_loader").append("<br><div id='mensajeAJAX'>ALGO MALO</div>");
                  }
                  */
     };

function resetearCampos(){
	var cantidadPendientes = "<?php echo $totalPendientes; ?>" ;
	//PUNTUALIDAD
	$(".botSI_punt_Azul").addClass('botSI_punt');
  	$(".botSI_punt").removeClass('botSI_punt_Azul');
  	$(".botSI_punt").removeClass('seleccionado');
  	$('.botSI_punt').css("background-image", "url(../../images/img_evaluaciones/SiGris.png)");
  	$('.preg_punt').attr('value', '0');

	$(".botNO_punt_Azul").addClass('botNO_punt');
  	$(".botNO_punt").removeClass('botNO_punt_Azul');
  	$(".botNO_punt").removeClass('seleccionado');
  	$('.inputOculto_punt').fadeOut("fast");
  	$('.botNO_punt').css("background-image", "url(../../images/img_evaluaciones/NoGris.png)");

	//RECOMEND AUTO a OTROS USUARIOS
	$(".botSI_usua_Azul").addClass('botSI_usua');
  	$(".botSI_usua").removeClass('botSI_usua_Azul');
  	$(".botSI_usua").removeClass('seleccionado');
  	$('.botSI_usua').css("background-image", "url(../../images/img_evaluaciones/SiGris.png)");
  	$('.preg_usua').attr('value', '0');

	$(".botNO_usua_Azul").addClass('botNO_usua');
  	$(".botNO_usua").removeClass('botNO_usua_Azul');
  	$(".botNO_usua").removeClass('seleccionado');
  	$(".TextoOculto").fadeOut("fast");
  	$('.botNO_usua').css("background-image", "url(../../images/img_evaluaciones/NoGris.png)");

  	//RECOMEND a OTROS USUARIOS
	$(".botSI_reco_Azul").addClass('botSI_reco');
  	$(".botSI_reco").removeClass('botSI_reco_Azul');
  	$(".botSI_reco").removeClass('seleccionado');
  	$('.botSI_reco').css("background-image", "url(../../images/img_evaluaciones/SiGris.png)");
  	$('.preg_reco').attr('value', '0');

	$(".botNO_reco_Azul").addClass('botNO_reco');
  	$(".botNO_reco").removeClass('botNO_reco_Azul');
  	$(".botNO_reco").removeClass('seleccionado');
  	$(".TextoOculto2").fadeOut("fast");
  	$('.botNO_reco').css("background-image", "url(../../images/img_evaluaciones/NoGris.png)");

  	//DESPERFECTO AUTO
	$(".botSI_desp_Azul").addClass('botSI_desp');
  	$(".botSI_desp").removeClass('botSI_desp_Azul');
  	$(".botSI_desp").removeClass('seleccionado');
  	$('.botSI_desp').css("background-image", "url(../../images/img_evaluaciones/SiGris.png)");
  	$('.preg_desp').attr('value', '0');

	$(".botNO_desp_Azul").addClass('botNO_desp');
  	$(".botNO_desp").removeClass('botNO_desp_Azul');
  	$(".botNO_desp").removeClass('seleccionado');
  	$(".TextoOculto3").fadeOut("fast");
  	$('.botNO_desp').css("background-image", "url(../../images/img_evaluaciones/NoGris.png)");

  	$('.estrella_llena').attr('value', '0');

  	$(".texto1").val('');
  	$(".texto2").val('');
  	$(".texto3").val('');

  	for(i=0;i<cantidadPendientes;i++){
  		stringText = '.textoPublicacion'+i;
  		$(stringText).val('');
  	}

}

function verificar(elemento, url){
	if($(elemento).attr('class') != "seleccionado"){
		//alert($(elemento).attr('class'));
		$(elemento).css("background-image", url);
	}
}


function Desaparecer(){
	//$('.loader_gif').display(true);
    document.getElementById("ajax_loader").innerHTML="";
}
//funcion (ubicacion, elemento1, remove_e1, add_e1, elemento2, remove_e2, add_e2)
function verificarOpcionSeleccionada(enlace, element1, remo1, add1, element2, remo2, add2){
	if($(enlace).attr('class') != "select"){
		$(element1).removeClass(remo1);
		$(element1).addClass(add1);
		$(element2).removeClass(remo2);
		$(element2).addClass(add2);
	}
}
function verificarOpcionSeleccionada2(enlace, element1, remo1, add1, element2, remo2, add2, element3, remo3, add3){
	if($(enlace).attr('class') != "select"){
		$(element1).removeClass(remo1);
		$(element1).addClass(add1);
		$(element2).removeClass(remo2);
		$(element2).addClass(add2);
		$(element3).removeClass(remo3);
		$(element3).addClass(add3);
	}
}
function verificarCamposCompletos(index){
	var stringTexto = '.textoPublicacion'+index;
	var stringStart_punt = '.start_punt'+index;
	var stringEnd_punt = '.end_punt'+index;
	if($('.preg_reco').attr('value') == "0" || $('.preg_usua').attr('value') == "0" || $('.preg_desp').attr('value') == "0" || $('.preg_punt').attr('value') == "0" || $(stringTexto).val() == "" || $('.estrella_llena').attr('value') == "0"){
		return false;
	}else{//Si están llenos, pero...
		if($('.preg_usua').attr('value') == "2" && $('.texto1').val() == "") return false;
		if($('.preg_reco').attr('value') == "2" && $('.texto2').val() == "") return false;
		if($('.preg_desp').attr('value') == "1" && $('.texto3').val() == "") return false;
		if($('.preg_punt').attr('value') == "2" && ($(stringStart_punt).attr('value') == "0" && $(stringEnd_punt).attr('value') == "0")) return false;
		return true;
	}
}

$(document).ready(function(){
	$(".listo").click(function(e){
		index = $(this).attr('name'); //obtener la posicion de la ubicacion del formulario
		if(!verificarCamposCompletos(index)){
			alert("Debe ingresar todos los campos del formulario");
			e.preventDefault();
		}

	});

	// BOTONES BARRA NAVEGADORA

	$("#opcion1").hover(function(){
		posicionado1 = 1;
		$('#boton1').removeClass('boton1_cla');
		$('#boton1').addClass('boton1_osc');
		if($("#opcion2").attr('class') == "select"){ //Si está seleccionado el boton 2
			$('#intermedio1').removeClass('intermedio1_cla_osc');
			$('#intermedio1').addClass('intermedio1_osc_osc');
			posicionado1 = 2;
		}else if($("#opcion3").attr('class') == "select"){ //Si está seleccionado el boton 3
			$('#intermedio1').removeClass('intermedio1_cla_cla');
			$('#intermedio1').addClass('intermedio1_osc_cla');
			posicionado1 = 3;
		}
	},function(){
		if(posicionado1 == 2){
			//funcion (ubicacion, elemento1, remove_e1, add_e1, elemento2, remove_e2, add_e2)
			verificarOpcionSeleccionada('#opcion1','#boton1', 'boton1_osc', 'boton1_cla', '#intermedio1', 'intermedio1_osc_osc', 'intermedio1_cla_osc');
		}else if(posicionado1 == 3){
			//funcion (ubicacion, elemento1, remove_e1, add_e1, elemento2, remove_e2, add_e2)
			verificarOpcionSeleccionada('#opcion1','#boton1', 'boton1_osc', 'boton1_cla', '#intermedio1', 'intermedio1_osc_cla', 'intermedio1_cla_cla');
		}
	});

	$("#opcion2").hover(function(){
		posicionado2 = 1;
		$('#boton2').removeClass('boton2_cla');
		$('#boton2').addClass('boton2_osc');
		if($("#opcion1").attr('class') == "select"){ //Si está seleccionado el boton 1
			$('#intermedio1').removeClass('intermedio1_osc_cla');
			$('#intermedio1').addClass('intermedio1_osc_osc');
			$('#intermedio2').removeClass('intermedio2_cla_cla');
			$('#intermedio2').addClass('intermedio2_osc_cla');
			posicionado2 = 1;
		}else if($("#opcion3").attr('class') == "select"){ //Si está seleccionado el boton 3
			$('#intermedio1').removeClass('intermedio1_cla_cla');
			$('#intermedio1').addClass('intermedio1_cla_osc');
			$('#intermedio2').removeClass('intermedio2_cla_osc');
			$('#intermedio2').addClass('intermedio2_osc_osc');
			posicionado2 = 3;
		}
	},function(){
		if(posicionado2 == 1){
			//funcion (ubicacion, elemento1, remove_e1, add_e1, elemento2, remove_e2, add_e2, elemento2, remove_e3, add_e3)
			verificarOpcionSeleccionada2('#opcion2','#boton2', 'boton2_osc', 'boton2_cla', '#intermedio1', 'intermedio1_osc_osc', 'intermedio1_osc_cla', '#intermedio2', 'intermedio2_osc_cla', 'intermedio2_cla_cla');
		}else if(posicionado2 == 3){
			//funcion (ubicacion, elemento1, remove_e1, add_e1, elemento2, remove_e2, add_e2)
			verificarOpcionSeleccionada2('#opcion2','#boton2', 'boton2_osc', 'boton2_cla', '#intermedio1', 'intermedio1_cla_osc', 'intermedio1_cla_cla', '#intermedio2', 'intermedio2_osc_osc', 'intermedio2_cla_osc');
		}
	});

	$("#opcion3").hover(function(){
		posicionado3 = 1;
		$('#boton3').removeClass('boton3_cla');
		$('#boton3').addClass('boton3_osc');
		if($("#opcion1").attr('class') == "select"){ //Si está seleccionado el boton 1
			$('#intermedio2').removeClass('intermedio2_cla_cla');
			$('#intermedio2').addClass('intermedio2_cla_osc');
			posicionado3 = 1;
		}else if($("#opcion2").attr('class') == "select"){ //Si está seleccionado el boton 2
			$('#intermedio2').removeClass('intermedio2_osc_cla');
			$('#intermedio2').addClass('intermedio2_osc_osc');
			posicionado3 = 2;
		}
	},function(){
		if(posicionado3 == 1){
			//funcion (ubicacion, elemento1, remove_e1, add_e1, elemento2, remove_e2, add_e2)
			verificarOpcionSeleccionada('#opcion3','#boton3', 'boton3_osc', 'boton3_cla', '#intermedio2', 'intermedio2_cla_osc', 'intermedio2_cla_cla');
		}else if(posicionado3 == 2){
			//funcion (ubicacion, elemento1, remove_e1, add_e1, elemento2, remove_e2, add_e2)
			verificarOpcionSeleccionada('#opcion3','#boton3', 'boton3_osc', 'boton3_cla', '#intermedio2', 'intermedio2_osc_osc', 'intermedio2_osc_cla');
		}
	});

	$("#opcion1").on('click',function(e){
		e.preventDefault();
		$(".conten_opcion3").fadeOut("fast");
		$(".conten_opcion2").fadeOut("fast");
		$(".conten_opcion1").fadeIn("fast");

		$("#boton3").removeClass('boton3_osc');
		$("#boton3").addClass('boton3_cla');
		$("#boton2").removeClass('boton2_osc');
		$("#boton2").addClass('boton2_cla');
		$("#boton1").removeClass('boton1_cla');
		$("#boton1").addClass('boton1_osc');

		$("#intermedio1").removeClass('intermedio1_cla_cla');
		$("#intermedio1").removeClass('intermedio1_osc_osc');
		$("#intermedio1").removeClass('intermedio1_cla_osc');
		$("#intermedio1").addClass('intermedio1_osc_cla');

		$("#intermedio2").removeClass('intermedio2_osc_osc');
		$("#intermedio2").removeClass('intermedio2_cla_osc');
		$("#intermedio2").removeClass('intermedio2_osc_cla');
		$("#intermedio2").addClass('intermedio2_cla_cla');

		$("#opcion2").removeClass("select");
		$("#opcion3").removeClass("select");
		$("#opcion1").addClass("select");
	});
	$("#opcion2").on('click',function(e){
		e.preventDefault();
		$(".conten_opcion3").fadeOut("fast");
		$(".conten_opcion1").fadeOut("fast");
		$(".conten_opcion2").fadeIn("fast");

		$("#boton1").removeClass('boton1_osc');
		$("#boton1").addClass('boton1_cla');
		$("#boton3").removeClass('boton3_osc');
		$("#boton3").addClass('boton3_cla');
		$("#boton2").removeClass('boton2_cla');
		$("#boton2").addClass('boton2_osc');

		$("#intermedio1").removeClass('intermedio1_cla_cla');
		$("#intermedio1").removeClass('intermedio1_osc_osc');
		$("#intermedio1").removeClass('intermedio1_osc_cla');
		$("#intermedio1").addClass('intermedio1_cla_osc');

		$("#intermedio2").removeClass('intermedio2_cla_cla');
		$("#intermedio2").removeClass('intermedio2_osc_osc');
		$("#intermedio2").removeClass('intermedio2_cla_osc');
		$("#intermedio2").addClass('intermedio2_osc_cla');

		$("#opcion1").removeClass("select");
		$("#opcion3").removeClass("select");
		$("#opcion2").addClass("select");
	});
	$("#opcion3").on('click',function(e){
		e.preventDefault();
		$(".conten_opcion1").fadeOut("fast");
		$(".conten_opcion2").fadeOut("fast");
		$(".conten_opcion3").fadeIn("fast");

		$("#boton1").removeClass('boton1_osc');
		$("#boton1").addClass('boton1_cla');
		$("#boton2").removeClass('boton2_osc');
		$("#boton2").addClass('boton2_cla');
		$("#boton3").removeClass('boton3_cla');
		$("#boton3").addClass('boton3_osc');

		$("#intermedio1").removeClass('intermedio1_osc_osc');
		$("#intermedio1").removeClass('intermedio1_cla_osc');
		$("#intermedio1").removeClass('intermedio1_osc_cla');
		$("#intermedio1").addClass('intermedio1_cla_cla');

		$("#intermedio2").removeClass('intermedio2_cla_cla');
		$("#intermedio2").removeClass('intermedio2_osc_osc');
		$("#intermedio2").removeClass('intermedio2_osc_cla');
		$("#intermedio2").addClass('intermedio2_cla_osc');

		$("#opcion1").removeClass("select");
		$("#opcion2").removeClass("select");
		$("#opcion3").addClass("select");
	});

//********************** BOTONES FORM
	$(".encabezadoPestania").click(function(){
		$(".formularioContenidoPestania").fadeIn("fast");
		//RESET de todos los campos!
		resetearCampos();
	});
	$(".click_owner").click(function(){
		$('.preg_usua').attr('value', '3');
		$('.preg_desp').attr('value', '3');
	});

	$(".botSI_reco").click(function(){
		$(".botSI_reco").addClass('botSI_reco_Azul');
  		$(".botSI_reco_Azul").removeClass('botSI_reco');
  		$(".botNO_reco_Azul").addClass('botNO_reco');
  		$(".botNO_reco").removeClass('botNO_reco_Azul');

  		$('.botSI_reco_Azul').addClass('seleccionado');
  		$('.botNO_reco').removeClass('seleccionado');

  		$(".TextoOculto2").fadeOut("fast");
  		$(".texto2").val('');

  		$('.preg_reco').attr('value', '1');

  		$('.botNO_reco').css("background-image", "url(../../images/img_evaluaciones/NoGris.png)");
	});
	$(".botSI_reco").hover(function(){
		$('.botSI_reco').css("background-image", "url(../../images/img_evaluaciones/SiAzul.png)");
	},function(){
		verificar(".botSI_reco", "url(../../images/img_evaluaciones/SiGris.png)");
	});

	$(".botNO_reco").click(function(){
		$(".botNO_reco").addClass('botNO_reco_Azul');
  		$(".botNO_reco_Azul").removeClass('botNO_reco');
  		$(".botSI_reco_Azul").addClass('botSI_reco');
  		$(".botSI_reco").removeClass('botSI_reco_Azul');

  		$('.botNO_reco_Azul').addClass('seleccionado');
  		$('.botSI_reco').removeClass('seleccionado');

  		$(".TextoOculto2").fadeIn("slow");

  		$('.preg_reco').attr('value', '2');


  		$('.botSI_reco').css("background-image", "url(../../images/img_evaluaciones/SiGris.png)"); 
	});
	$(".botNO_reco").hover(function(){
		$('.botNO_reco').css("background-image", "url(../../images/img_evaluaciones/NoAzul.png)");
	},function(){
		verificar(".botNO_reco", "url(../../images/img_evaluaciones/NoGris.png)");
	});

	$(".botSI_desp").click(function(){
  		$(".botSI_desp").addClass('botSI_desp_Azul');
  		$(".botSI_desp_Azul").removeClass('botSI_desp');
  		$(".botNO_desp_Azul").addClass('botNO_desp');
  		$(".botNO_desp").removeClass('botNO_desp_Azul');

  		$('.botSI_desp_Azul').addClass('seleccionado');//NO
  		$('.botNO_desp').removeClass('seleccionado');//NO

  		$(".TextoOculto3").fadeIn("slow");
  		$(".texto3").val('');

  		$('.preg_desp').attr('value', '1');

  		$('.botNO_desp').css("background-image", "url(../../images/img_evaluaciones/NoGris.png)");
	});
	$(".botSI_desp").hover(function(){
		$('.botSI_desp').css("background-image", "url(../../images/img_evaluaciones/SiAzul.png)");
	},function(){
		verificar(".botSI_desp", "url(../../images/img_evaluaciones/SiGris.png)");
	});

	$(".botNO_desp").click(function(){
		$(".botNO_desp").addClass('botNO_desp_Azul');
  		$(".botNO_desp_Azul").removeClass('botNO_desp');
  		$(".botSI_desp_Azul").addClass('botSI_desp');
  		$(".botSI_desp").removeClass('botSI_desp_Azul');

  		$('.botNO_desp_Azul').addClass('seleccionado');
  		$('.botSI_desp').removeClass('seleccionado');

  		$(".TextoOculto3").fadeOut("fast");
  		$('.preg_desp').attr('value', '2');

  		$('.botSI_desp').css("background-image", "url(../../images/img_evaluaciones/SiGris.png)");
	});
	$(".botNO_desp").hover(function(){
		$('.botNO_desp').css("background-image", "url(../../images/img_evaluaciones/NoAzul.png)");
	},function(){
		verificar(".botNO_desp", "url(../../images/img_evaluaciones/NoGris.png)");
	});

	$(".botSI_usua").click(function(){
  		$(".botSI_usua").addClass('botSI_usua_Azul');
  		$(".botSI_usua_Azul").removeClass('botSI_usua');
  		$(".botNO_usua_Azul").addClass('botNO_usua');
  		$(".botNO_usua").removeClass('botNO_usua_Azul');

  		$('.botSI_usua_Azul').addClass('seleccionado');
  		$('.botNO_usua').removeClass('seleccionado');

  		$(".TextoOculto").fadeOut("fast");
  		$(".texto1").val('');

  		$('.preg_usua').attr('value', '1');

  		$('.botNO_usua').css("background-image", "url(../../images/img_evaluaciones/NoGris.png)");
	});
	$(".botSI_usua").hover(function(){
		$('.botSI_usua').css("background-image", "url(../../images/img_evaluaciones/SiAzul.png)");
	},function(){
		verificar(".botSI_usua", "url(../../images/img_evaluaciones/SiGris.png)");
	});

	$(".botNO_usua").click(function(){
  		$(".botNO_usua").addClass('botNO_usua_Azul');
  		$(".botNO_usua_Azul").removeClass('botNO_usua');
  		$(".botSI_usua_Azul").addClass('botSI_usua');
  		$(".botSI_usua").removeClass('botSI_usua_Azul');

  		$('.botNO_usua_Azul').addClass('seleccionado');
  		$('.botSI_usua').removeClass('seleccionado');

  		$(".TextoOculto").fadeIn("slow");

  		$('.preg_usua').attr('value', '2');

  		$('.botSI_usua').css("background-image", "url(../../images/img_evaluaciones/SiGris.png)"); 
	});
	$(".botNO_usua").hover(function(){
		$('.botNO_usua').css("background-image", "url(../../images/img_evaluaciones/NoAzul.png)");
	},function(){
		verificar(".botNO_usua", "url(../../images/img_evaluaciones/NoGris.png)");
	});

	$(".botSI_punt").click(function(){
		$(".botSI_punt").addClass('botSI_punt_Azul');
  		$(".botSI_punt_Azul").removeClass('botSI_punt');
  		$(".botNO_punt_Azul").addClass('botNO_punt');
  		$(".botNO_punt").removeClass('botNO_punt_Azul');

  		$('.botSI_punt_Azul').addClass('seleccionado');
  		$('.botNO_punt').removeClass('seleccionado');

  		$('.preg_punt').attr('value', '1');
  		$('.inputOculto_punt').fadeOut("slow");
  		$(".hora_punt").val('0');
  		$(".min_punt").val('0');


  		$('.botNO_punt').css("background-image", "url(../../images/img_evaluaciones/NoGris.png)");  		
	});
	$(".botSI_punt").hover(function(){
		$('.botSI_punt').css("background-image", "url(../../images/img_evaluaciones/SiAzul.png)");
	},function(){
		verificar(".botSI_punt", "url(../../images/img_evaluaciones/SiGris.png)");
	});

	$(".botNO_punt").click(function(){
		$(".botNO_punt").addClass('botNO_punt_Azul');
  		$(".botNO_punt_Azul").removeClass('botNO_punt');
  		$(".botSI_punt_Azul").addClass('botSI_punt');
  		$(".botSI_punt").removeClass('botSI_punt_Azul');

  		$('.botNO_punt_Azul').addClass('seleccionado');
  		$('.botSI_punt').removeClass('seleccionado');

  		$('.preg_punt').attr('value', '2');
  		$('.inputOculto_punt').fadeIn("slow");

  		$('.botSI_punt').css("background-image", "url(../../images/img_evaluaciones/SiGris.png)");
	});
	$(".botNO_punt").hover(function(){
		$('.botNO_punt').css("background-image", "url(../../images/img_evaluaciones/NoAzul.png)");
	},function(){
		verificar(".botNO_punt", "url(../../images/img_evaluaciones/NoGris.png)");
	});
	cargaStar();

	$('.menu').lksMenu();

});
</script>

<!-- JS ACORDERON -->
<script type="text/javascript">
/* @version 1.0 lksMenu
 * @author Lucas Forchino
 * @webSite: http://www.tutorialjquery.com
 * lksMenu.
 * jQuery Plugin to create a css menu
 */
 
// Esta técnica es una forma de hacer convivir el script con el hecho de que algunas
// páginas tienen mas de una librería que genera conflicto con el simbolo $
// arrancando con esta funcion que pasa por parametro la misma libreria jQuery
// nos aseguramos que todo funcione correctamente en futuros usos , algo fundamental
// si lo que hacemos es una extensión o plugin jquery donde no sabemos donde se
// implementará
(function($){
    // fn es un shortcut al prototipo (prototypo) de la libreria jquery
    // declarando un método dentro de esta librería la extendemos para ser usada
    // con cualquier selector
    $.fn.lksMenu=function(){
        // para mantener la posibilidad de concatenar métodos es que retornamos la función en
        // lugar de solo ejecutar algo y ya.
        // en este caso usamos un each, porque el selector sobre el que aplicamos la función
        // podría contener más de un elemento , esto es , aplicaría $('.menu').menu() lo cual
        // ejecutaría el código para todos los elementos que tengan la clase menu, como puede
        // haber mas de uno es que ejecutamos el each para que corra sobre todos ellos
        return this.each(function(){
            // obtenemos el elemento que se esta analizando en esta vuelta del each
            var menu= $(this);
            // localizamos los links principales y le asignamos un evento click
            menu.find('ul li > a').bind('click',function(event){
            	event.preventDefault();
                // identificamos el elemento sobre el que se hizo click
                var currentlink=$(event.currentTarget);
                // los ul que tengan la clase active serán los que estan abiertos
                // si el link sobre que presionamos ya estaba abierto , es decir tenia
                // la clase active, lo cerramos y nada mas
                if (currentlink.parent().find('ul.active').size()==1)
                {
                    //cerramos el link porque apretamos sobre el mismo abierto
                    currentlink.parent().find('ul.active').slideUp('medium',function(){
                        // le quitamos la clase
                        currentlink.parent().find('ul.active').removeClass('active');
                    });
                }
                // si ningun link estaba apretado
                else if (menu.find('ul li ul.active').size()==0)
                {
                    //no hace falta cerrar ninguno y solo abrimos el elemento al
                    // cual se le hizo click
                    show(currentlink);
                }
                else
                {
                    // si ya había un item abierto , simplemente lo localizamos
                    // con find, y lo cerramos con slideup,
                    menu.find('ul li ul.active').slideUp('medium',function(){
                        // una vez que cerramos el que estaba abierto
                        // le quitamos la clase active
                        menu.find('ul li ul').removeClass('active');
                        // abrimos uno nuevo que corresponde al que se clickeo
                        show(currentlink);
                    });
                }
            });
 
            // esta función es de soporte
            // todo lo que hace es abrir el elemento y asignarle la clase active
            function show(currentlink){
                currentlink.parent().find('ul').addClass('active');
                currentlink.parent().find('ul').slideDown('medium');
            }
        });
    }
    // esto es lo que deciamos al principio , ejecutamos la función por eso los
    // parentesis y le pasamos por parametro la libreria jQuery.
})(jQuery);
</script>

<!-- JS STARS RATING -->
<script type="text/javascript">
// JavaScript Document
/*************************************************
Star Rating System
Modified Version: 17 February, 2009
Author: Ritesh Agrawal
Modified by: Miguel Manchego
Inspriation: Will Stuckey's star rating system (http://miguelmanchego.com/wp-content/demos/jquery/star-rating/)
Demonstration: http://php.scripts.psu.edu/rja171/widgets/rating.php
Usage: $('#rating').rating('www.url.to.post.com', {maxvalue:5, curvalue:0});
Return result to .starRpta
You can send element id $('#rating').rating('script.php', {maxvalue:5, curvalue:0, id:25});
That is useful to insert results in a database

arguments
url : required -- post changes to 
options
    maxvalue: number of stars
    curvalue: number of selected stars
    

************************************************/

jQuery.fn.rating = function(url, options) {
    
    if(url == null) return;
    
    var settings = {
        url       : url, // post changes to 
        maxvalue  : 5,   // max number of stars
        curvalue  : 0    // number of selected stars
    };
    
    if(options) {
       jQuery.extend(settings, options);
    };
   jQuery.extend(settings, {cancel: (settings.maxvalue > 1) ? true : false});
   
   
   var container = jQuery(this);
    
    jQuery.extend(container, {
            averageRating: settings.curvalue,
            url: settings.url,
            id: settings.id
        });

    for(var i= 1; i <= settings.maxvalue ; i++){
        var size = i
         
         if(i==1){
            var div = '<div class="star"><a href="#'+i+'" title="Muy Sucio">'+i+'</a></div>';
         }else if(i==2){
            var div = '<div class="star"><a href="#'+i+'" title="Algo Sucio">'+i+'</a></div>';
         }else if(i==3){
            var div = '<div class="star"><a href="#'+i+'" title="Aceptable">'+i+'</a></div>';
         }else if(i==4){
            var div = '<div class="star"><a href="#'+i+'" title="Limpio">'+i+'</a></div>';
         }else if(i==5){
            var div = '<div class="star"><a href="#'+i+'" title="Impecable">'+i+'</a></div>';
         }
         container.append(div);

    }
    var divrpta = '<div class="starRpta"></div>';
    container.append(divrpta);
    
    var stars = jQuery(container).children('.star');
    var cancel = jQuery(container).children('.cancel');
    
    stars
            .mouseover(function(){
                event.drain();
                event.fill(this);
            })
            .mouseout(function(){
                event.drain();
                event.reset();
            })
            .focus(function(){
                event.drain();
                event.fill(this)
            })
            .blur(function(){
                event.drain();
                event.reset();
            });

    stars.click(function(){
        if(settings.cancel == true){
            settings.curvalue = stars.index(this) + 1;
            var ok = container.url+"/idStar/"+settings.id+"/i/"+settings.curvalue+"/tipo/"+settings.tipo;
            $('.estrella_llena').attr('value', '1');
            $.post(ok, 
                { rating: jQuery(this).children('a')[0].href.split('#')[1],
                    id: container.id
                },
                function(data){
                    //$(".starRpta").html(data);
                    jQuery(container).children('.starRpta').html(data);
                }
            );
            return false;
        }
        else if(settings.maxvalue == 1){
            settings.curvalue = (settings.curvalue == 0) ? 1 : 0;
            $(this).toggleClass('on');
            $.post(container.url, 
                { rating: jQuery(this).children('a')[0].href.split('#')[1],
                  id: container.id
                },
                function(data){
                    //$(".starRpta").html(data);
                    jQuery(container).children('.starRpta').html(data);
                }
            );
            return false;
        }
        return true;
            
    });

        // cancel button events
    if(cancel){
        cancel
            .mouseover(function(){
                event.drain();
                jQuery(this).addClass('on')
            })
            .mouseout(function(){
                event.reset();
                jQuery(this).removeClass('on')
            })
            .focus(function(){
                event.drain();
                jQuery(this).addClass('on')
            })
            .blur(function(){
                event.reset();
                jQuery(this).removeClass('on')
            });
        
        // click events.
        cancel.click(function(){
            event.drain();
            settings.curvalue = 0;
            //$(".starRpta").html("");
            jQuery(container).children('.starRpta').html("");
            /*
            $.post(container.url, {
                "rating": jQuery(this).children('a')[0].href.split('#')[1], 
            });
            */
            return false;
        });
    }
        
    var event = {
        fill: function(el){ // fill to the current mouse position.
            var index = stars.index(el) + 1;
            /*
            stars
                .children('a').css('width', '100%').end()
                .lt(index).addClass('hover').end();
            */
            stars
                .children('a').css('width', '100%').end()
                .slice(0,index).addClass('hover').end();
        },
        drain: function() { // drain all the stars.
            stars
                .filter('.on').removeClass('on').end()
                .filter('.hover').removeClass('hover').end();
        },
        reset: function(){ // Reset the stars to the default index.
            ///stars.lt(settings.curvalue).addClass('on').end();            
            stars.slice(0,settings.curvalue).addClass('on').end();
            //jQuery(container).children('.starRpta').slice(0,settings.curvalue).addClass('on').end();
        }
    }        
    event.reset();
    
    return(this);   

}
</script>