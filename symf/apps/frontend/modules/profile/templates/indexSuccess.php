<?php use_stylesheet('calendario.css') ?>
<?php use_stylesheet('registro.css') ?>
<?php use_stylesheet('mis_autos.css') ?>


<script src="<?php echo url_for('profile/jsCalendar') ?>" ></script>

<script>
 
    $(document).ready(function()
    {

        elementCount = 3;
        elementSize =	$(".misautos_user_item").outerHeight(true);
  
        if($(".content_cars").height()>elementSize*elementCount+1)
        {
		
            $(".content_cars").height(elementSize*elementCount);
            $(".content_cars").css("overflow","scroll");
            $(".content_cars").css("overflow-x","hidden");
            $(".misautos_user_item").css( "width","-=10" );
            $(".misautos_user_item").css( "margin-left","15px" );
        }
	
    });
</script>


<style>
    input[type=text], input[type=password]{
        border: 1px solid #CECECE;
        background-color: white;
        width: 350px;
        height: 30px;
        -webkit-border-radius: 10px;
        -moz-border-radius: 10px;
        border-radius: 10px;
        padding-left: 10px;
    }
    .c1 label { 
        display:table; 
        width:100%;
        margin-bottom:10px
    }

    .c1 {
        margin:20px; 
        margin-top:10px;
    }


    


    

</style>

<div class="main_box_1">
    <div class="main_box_2">

        <div class="main_col_izq">

            <?php include_component('profile', 'userinfo') ?>
		
        </div><!-- fin main_col_izq -->


        <!--  contenido de la seccion -->
        <div class="main_contenido">
			 <h1 class="calen_titulo_seccion">Mis Autos</h1>  
			 
			<div class="usuario_info">
			    <span class="usuario_nombre">
			        <span >Usuario:</span><?php include_component("messages", "linkMessage", array("user" => $user)) ?>
			    </span>
			    <span class="usuario_lugar"><?php echo $user->getNombreComuna()?>, <?php echo $user->getNombreRegion() ?></span>
				 <span class="usuario_lugar">Calificaciones Positivas <?php include_component("messages", "positiveRatings", array("user" => $user)) ?></span>	
			</div><!-- usuario_info -->

			
             

            <a href="<?php echo url_for('profile/addCar') ?>" class="btnAmarillo">Sube tu auto</a>


            <div class="calen_box_1" style="display: none;">
                <div class="calen_box_1_frame">


                    <div id="event_edit_container">


                        <form>
                            <input type="hidden" />


                            <div style="display:none">

                                <label for="title">Nombre: </label><input type="text" style="width:240px" name="title" />
                                <label for="body">Descripci&oacute;n: </label><textarea name="body"></textarea>
                                <label>Fecha</label><input id="date" name="date" style="width:240px" type="text" class="datepicker" /><br/>

                            </div>





                            <label for="start">Hora de inicio: </label>
                            <select name="start"><option value="">Seleccione la hora de inicio</option></select>

                            <label for="end">Hora de finalizaci&oacute;n: </label>
                            <select name="end"><option value="">Seleccione la hora de finalizaci&oacute;n</option></select>

                            <label>Fecha Desde</label>

                            <input id="date" name="date" style="width:240px" type="text" class="datepicker" /><br/><br/> 

                            <label>Auto</label>
                            <select id="car" name="car">
                                <?php foreach ($cars as $c): ?>
                                    <option value="<?= $c->getId(); ?>"><?= $c->getModel()->getBrand()->getName(); ?> <?= $c->getModel()->getName(); ?></option>
                                <?php endforeach; ?>
                            </select>
                            <br/><br/>

                            <div style="background:#efeefe;border:1px solid #dddddd; padding:10px;padding-top:0px;">
                                <label for="end">Repetir:</label>
                                <input type="radio" name="repeatdays" value="hola" id="never" />No Repetir</input><br/>
                                <input type="radio" name="repeatdays" value="all" id="all" />Todos los dias</input><br/>
                                <input type="radio" name="repeatdays" value="1day" id="1day" /><div id="daytext" style="display:inline">Solo los dias -</div></input><br/>
                                <label for="end">Cantidad de dias:</label>
                                <input id="days" name="days" style="width:220px" type="text" /><br/><br/> 
                                <input type="checkbox" name="notenddate" id="notenddate">Sin fecha fin </input><br/>
                                <input type="hidden" id="dayofweek" />
                            </div>

                        </form>  






                    </div>





                    <div id='calendar'></div>
                    <!-- aqui va aplicacion calendario -->
                  <!-- <img src="images/img_calendario/calendario_aplicacion.png" />
                   </div><!-- calen_box_1_frame -->

                </div><!-- calen_box_1 -->
                <div class="clear"></div>


            </div><!-- main_contenido -->
            
            <div class="main_contenido">

                <div class="misautos_box">

                    <div class="misautos_box_header"><h2>Mis autos publicados</h2></div>
                    <div class="content_cars"> 
                        <?php foreach ($cars as $c): ?>

                            <!-- auto_publicado -->
                            <div class="misautos_user_item">

                                <div class="misautos_user_post">
                                    <div class="misautos_marca">
                                        <a href="<?php echo url_for('profile/car?id=' . $c->getId()) ?>" ><span><?= $c->getModel()->getBrand()->getName() ?> <?= $c->getModel()->getName() ?></span></a>
                                    </div><!-- misautos_marca -->

                                    <!--<p class="misautos_info">Kilometraje: <b><?= $c->getKm() ?> km</b></p>-->

                                    <div class="misautos_btn_sector">
                                        <!-- <a href="#" class="misautos_btn_disp"></a>
                                        -->
                                        <a href="<?php echo url_for('profile/rental') ?>" class="misautos_btn_alqui">Mis Arriendos</a>
                                        <!--
                                        <a href="#" class="misautos_btn_denun"></a>
                                        -->
                                    </div><!-- misautos_btn_sector -->


                                    <div class="misautos_user_item_flecha"></div>
                                </div><!-- misautos_user_post -->

                                <div class="misautos_user_frame">

                                    <a href="<?php echo url_for('profile/car?id=' . $c->getId()) ?>" >
                                        <?php if ($c->getPhotoFile('main') != NULL): ?> 
                                            <?= image_tag("cars/" . $c->getPhotoFile('main')->getFileName(), 'size=84x84') ?>
                                        <?php else: ?>
                                            <?= image_tag('default.png', 'size=84x84') ?>  
                                        <?php endif; ?>
                                    </a>

                                </div>

                                <div class="clear"></div>
                            </div><!-- auto_publicado -->


                        <?php endforeach; ?>  
                    </div>  
                </div><!-- misautos_box -->


            </div>


            <div class="main_col_dere"></div><!-- main_col_dere -->
        </div><!-- fin main_contenido -->    
        
        <div class="main_col_right">
			<?php include_component('profile', 'newsfeed') ?>
        </div>
        
    </div><!-- main_box_2 -->
    <div class="clear"></div>
</div><!-- main_box_1 -->

