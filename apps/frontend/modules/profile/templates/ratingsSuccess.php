<?php use_stylesheet('calificaciones.css') ?>
<?php use_stylesheet('calendario.css') ?>
<?php use_stylesheet('registro.css') ?>
<?php use_stylesheet('comunes.css') ?>

<div class="main_box_1">
    <div class="main_box_2">
        <style>

            .graycolor {
                color:	#A4A4A4;
                font-size:25px;
            }
            .calif_preguntas
            {
                float: left;
                margin-left: 10px;
                margin-bottom: 20px;
                width: 370px;
                padding: 10px;
                color: #979797;
                font-size: 11px;
                line-height: 14px;
            }
            .content_rating h2{
                clear: both;
                font-family: 'MuseoSlab500';
                font-size: 20px;
                font-weight: normal;
                letter-spacing: -1px;
                margin-bottom: 30px;
                margin-left: 20px;
                margin-top: 20px;

            }
        </style>
        <script>
            function 	submitFrom(nameform)
            {
                //alert(nameform);
                document.forms[nameform].submit();
            }
 
            $(document).ready(function()
            {

                elementCount = 2;
  
  
                elementSize =	$(".calif_user_box").outerHeight(true);
                $(".content_rating").css("display","table");

                if($(".content_rating").height() > (elementSize*elementCount)+1)
                {
                    $(".content_rating").css("display","block");
                    $(".content_rating").height(elementSize*elementCount);
                    $(".content_rating").css("overflow","hidden");
                    $(".content_rating").css("overflow-x","hidden");
                    $(".content_rating").css("overflow-y","scroll");
                    $(".calif_user_box").css( "width","-=10" );
                    $(".calif_user_box").css( "margin-left","15px" );
                    $('.content_rating').css('clear','left');
                    $('.content_rating').css('margin-bottom','30px');
                }
	
            });
            function activeSatisfied(value,elem)
            {	
	
                $("#satisfied").val(value);
                $(".calif_preguntas a").css("color","#a4a4a4");
                //alert(elem.id);
                $("#"+elem.id).css("color",'');
            }
        </script>

        <?php include_component('profile', 'profile') ?>

        <!--  contenido de la seccion -->
        <div class="main_contenido">
            <div class="barraSuperior">
                <p>CALIFICACIONES</p>
            </div>

            <h1 class="calif_titulo_seccion">Calificaciones pendientes <span>(<?= $ratings->Count() + $myRatings->count(); ?>)</span></h1>     


            <?php if ($ratings->count() == 0 && $myRatings->count() == 0): ?>

                <div class="calen_box_3">
                    <div style="padding-top:20px; padding-left:20px; font-size:12px">
                        No tenes ninguna calificacion.
                    </div>
                </div>

            <?php endif; ?>

            <div class="content_rating">

                <h2>Mis Calificaciones</h2>

                <?php foreach ($myRatings as $r): ?>

                    <?php
                    $reserves = $r->getReserve();
                    $reserve = $reserves[0];
                    $car = $reserve->getCar();
                    ?>


                    <?php echo form_tag('profile/confirmMyRating', array('method' => 'post', 'enctype' => 'multipart/form-data', 'id' => 'they' . $r->getId())); ?> 

                    <!-- calificacion de usuario____________________ -->
                    <div class="calif_user_box">

                        <div class="calif_user_info">

                            <!-- calificacion  general -->
                            <ul class="calif_general">    
                                <li class="calif_auto">Auto: <span><?= $car->getModel(); ?> <?= $car->getModel()->getBrand(); ?></span></li>
                                <li><b>Calificaci&oacute;n:</b> <input type="radio" name="qualified" value="1" /> Positiva -  <input type="radio" name="qualified" value="0" /> Negativa </li>
                                <li><b>Fecha:</b> <?= date("Y-m-d", strtotime($reserve->getDate())); ?></li>
                                <li><b>Duraci&oacute;n:</b> <?= $reserve->getDuration() ?> horas</li>
                                <li><b>El auto fue entregado en horario?:</b> <input type="radio" name="intime" value="1" /> Si -  <input type="radio" name="intime" value="0" /> No</li>
                                <li><b>Midi&oacute; kilometraje:</b> <input type="radio" name="km"  value="1"/> Si -  <input type="radio" name="km"  value="0" /> No</li>
                            </ul><!-- calif_general -->

                            <!--  resumen experiencia -->
                            <p>Resumen de tu experiencia con el usuario:</p>
                            <textarea class="calif_experiencia" id="user_owner_opnion" name="user_owner_opnion"></textarea>

                            <!-- preguntas -->


                            <div class="calif_preguntas">    
                                <ul>
                                    <li>&iquest;Est&aacute; satisfecho con el estado de limpieza con el que se entreg&oacute; el auto?<br>
                                        <a id="0" onclick="activeSatisfied(0,this);">No</a> - <a id="1" onclick="activeSatisfied(1,this);" >Poco</a> - <a id="2" onclick="activeSatisfied(2,this);" >Algo</a> - <a id="3" onclick="activeSatisfied(3,this);"  >Si</a></li>
                                    <input id="satisfied" name="satisfied" type="hidden"/>
                                </ul>    
                            </div>


                            <!-- calig_preguntas -->

                            <!-- contraparte --><!--
                            <p>Comentario contraparte:</p>
                            <div class="calif_contraparte">
                            Quisque id dui lorem, eget lobortis libero. Etiam at nunc turpis, vitae posuere nulla. Nulla tempus, odio ac placerat sollicitudin, velit nisi ornare elit,..
                            </div>
                            -->
                            <!-- boton calificar -->

                            <input type="hidden" id="id" name="id" value="<?= $r->getId() ?>">

                            <a href="#" onclick="submitFrom('they<?= $r->getId() ?>')" class="calif_btn_calificar"></a>
                        </div><!-- calif_user_info -->

                        <div class="calif_user_frame">

                            <?php if ($reserve->getUser()->getPictureFile() != NULL): ?>
                                <?= image_tag("users/" . $reserve->getUser()->getFileName(), 'size=84x84') ?>
                            <?php else: ?>
                                <?= image_tag('img_registro/tmp_user_foto.jpg', 'size=84x84') ?>
                            <?php endif; ?>


                        </div>

                        <div class="calif_user_nombre">
                            <a href="<?php echo url_for('profile/ratingsHistory?id=' . $reserve->getUser()->getId()) ?>">
                                <?= $reserve->getUser()->getFirstname(); ?> <?= substr($reserve->getUser()->getLastname(), 0, 1); ?>.
                            </a>
                        </div>
                        <div class="clear"></div>
                    </div><!-- calif_user_box -->

                    <!-- /calificacion de usuario____________________ -->

                    </form>	

                <?php endforeach; ?>



                <h2>Calificaciones Contraparte</h2>

                <?php foreach ($ratings as $r): ?>

                    <?
                    $reserves = $r->getReserve();
                    $reserve = $reserves[0];
                    $car = $reserve->getCar();
                    ?>


                    <?php echo form_tag('profile/confirmTheyRating', array('method' => 'post', 'enctype' => 'multipart/form-data', 'id' => 'my' . $r->getId())); ?> 

                    <!-- calificacion de usuario____________________ -->
                    <div class="calif_user_box">

                        <div class="calif_user_info">

                            <!-- calificacion  general -->
                            <ul class="calif_general">    
                                <li class="calif_auto">Auto: <span><?= $car->getModel(); ?> <?= $car->getModel()->getBrand(); ?></span></li>
                                <li><b>Fecha:</b> <?= date("Y-m-d", strtotime($reserve->getDate())); ?></li>
                                <li><b>Duraci&oacute;n:</b> <?= $reserve->getDuration() ?> horas</li>
                            </ul><!-- calif_general -->

                            <!--  resumen experiencia -->
                            <p>Resumen de tu experiencia con el usuario:</p>
                            <textarea class="calif_experiencia" id="user_renter_opnion" name="user_renter_opnion"></textarea>

                            <!-- preguntas -->
                            <div class="calif_preguntas">    
                            </div>


                            <!-- calig_preguntas -->

                            <!-- contraparte -->
                            <p>Comentario contraparte:</p>
                            <div class="calif_contraparte" >
                                <?= $r->getUserOwnerOpnion() ?>
                            </div>

                            <!-- boton calificar -->

                            <input type="hidden" id="id" name="id" value="<?= $r->getId() ?>">

                            <a href="#" onclick="submitFrom('my<?= $r->getId() ?>')" class="calif_btn_calificar"></a>

                        </div><!-- calif_user_info -->

                        <div class="calif_user_frame">

                            <?php if ($car->getUser()->getPictureFile() != NULL): ?>
                                <?= image_tag("users/" . $car->getUser()->getFileName(), 'size=84x84') ?>
                            <?php else: ?>
                                <?= image_tag('img_registro/tmp_user_foto.jpg', 'size=84x84') ?>
                            <?php endif; ?>


                        </div>

                        <div class="calif_user_nombre">
                            <a href="<?php echo url_for('profile/ratingsHistory?id=' . $car->getUser()->getId()) ?>">
                                <?= $car->getUser()->getFirstname(); ?> <?= substr($car->getUser()->getLastname(), 0, 1); ?>.
                            </a>
                        </div>
                        <div class="clear"></div>
                    </div><!-- calif_user_box -->

                    <!-- /calificacion de usuario____________________ -->

                    </form>	

                <?php endforeach; ?>




                <?php foreach ($ratingsCompleted as $r): ?>

                    <?
                    $reserves = $r->getReserve();
                    $reserve = $reserves[0];
                    $car = $reserve->getCar();
                    ?>

                    <!-- calificacion de usuario____________________ -->
                    <div class="calif_user_box">

                        <div class="calif_user_info">


                            <!-- preguntas -->
                            <ul class="calif_general">    
                                <li class="calif_auto">Auto: <span><?= $car->getModel(); ?> <?= $car->getModel()->getBrand(); ?></span></li>
                                <li><b>Fecha:</b> <?= date("Y-m-d", strtotime($reserve->getDate())); ?></li>
                                <li><b>Duraci&oacute;n:</b> <?= $reserve->getDuration() ?> horas</li>
                                <li><b>Califficci&oacute;n:</b> <?
                $i = $r->getQualified();

                switch ($i) {
                    case 0:
                        echo "Negativa";
                        break;
                    case 1:
                        echo "Positiva";
                        break;
                }
                    ?> </li>
                            </ul><!-- calif_general -->

                            <!--  resumen experiencia -->
                            <p>Resumen de tu experiencia con el usuario:</p>
                            <div class="calif_preguntas">  
                                <?= $r->getUserRenterOpnion() ?>
                            </div>


                            <!-- calig_preguntas -->

                            <!-- contraparte -->
                            <p>Comentario contraparte:</p>
                            <div class="calif_contraparte" >
                                <?= $r->getUserOwnerOpnion() ?>
                            </div>

                            <!-- boton calificar -->

                            <input type="hidden" id="id" name="id" value="<?= $r->getId() ?>">

                            <a href="#" onclick="submitFrom('my<?= $r->getId() ?>')" class="calif_btn_calificar"></a>

                        </div><!-- calif_user_info -->

                        <div class="calif_user_frame">

                            <?php if ($car->getUser()->getPictureFile() != NULL): ?>
                                <?= image_tag("users/" . $car->getUser()->getFileName(), 'size=84x84') ?>
                            <?php else: ?>
                                <?= image_tag('img_registro/tmp_user_foto.jpg', 'size=84x84') ?>
                            <?php endif; ?>


                        </div>

                        <div class="calif_user_nombre">
                            <a href="<?php echo url_for('profile/ratingsHistory?id=' . $car->getUser()->getId()) ?>">
                                <?= $car->getUser()->getFirstname(); ?> <?= substr($car->getUser()->getLastname(), 0, 1); ?>.
                            </a>
                        </div>
                        <div class="clear"></div>
                    </div><!-- calif_user_box -->

                    <!-- /calificacion de usuario____________________ -->


                <?php endforeach; ?>


            </div>


        </div><!-- main_contenido -->
        <?php include_component('profile', 'colDer') ?>

    </div><!-- main_box_2 -->
    <div class="clear"></div>
</div><!-- main_box_1 -->





