<?php $inicio = $reserve->getDate();?>

<?php use_stylesheet('subi_tu_auto.css') ?>
<?php use_stylesheet('registro.css') ?>
<?php use_stylesheet('calendario.css') ?>
<style>
    input[type=text], input[type=password]{
        border: 1px solid #CECECE;
        background-color: white;
        width: 256px;
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
    #centralcontent {
        margin:0px;
        margin-top:80px;
        display:block;
        width:100%;
    }

    #centralcontent h3 {
        margin-top:20px;
        margin-bottom:10px;
    }
    #linkmain {
        /*position: absolute;*/
        display:table;
        padding-top:10px;
        bottom: 20px;
        left: 54px;
    }
    .btnAmarillo{
        padding: 5px;
        margin-left: 308px;
    }
    .mensaje_alerta {
    background: none repeat scroll 0 0 #F8F8F8;
    border: 1px solid #CCCCCC;
    color: #FF0000;
    display: block;
    height: 25px;
    line-height: 30px;
    margin-left: 20px;
    padding: 9px;
    width: 441px;
}


    /* css for timepicker */
    .ui-timepicker-div .ui-widget-header { margin-bottom: 8px; }
    .ui-timepicker-div dl { text-align: left; }
    .ui-timepicker-div dl dt { height: 25px; margin-bottom: -25px; }
    .ui-timepicker-div dl dd { margin: 0 10px 10px 65px; }
    .ui-timepicker-div td { font-size: 90%; }
    .ui-tpicker-grid-label { background: none; border: none; margin: 0; padding: 0; }


    .ui-widget { font-family: Lucida Grande, Lucida Sans, Arial, sans-serif; font-size: 0.8em; }
</style>

<div class="main_box_1">
    <div class="main_box_2">

        <?php include_component('profile', 'profile') ?>


        <!--  contenido de la seccion -->
        <div class="main_contenido">

            <div class="barraSuperior">
            <p>INFORMACI&Oacute;N DEL PAGO</p>
            </div>

            <!-- transacciones en curso -->
            <div class="upcar_box_1">
                <div class="upcar_box_1_header"><h2>Detalles de la Reserva</h2></div>



                <div class="upcar_sector_1">
					
                    <div class="sector_1_form" style="width:450px">
                        <div class="c1 height" style="width:100%;">
                                <label>Nro. Reserva: <?=$reserve->getId()?></label>
                        </div><!-- /c1 -->


                        <div class="c1 height">
                                <label>Fecha Inicio: <?=date('d-m-Y H:i', strtotime($inicio))?></label>
                        </div><!-- /c1 -->
                        <div class="c1 height">
                        		<?php $termino = strtotime($inicio) + ($reserve->getDuration() * 60 * 60)?>
                                <label>Fecha Termino: <?=date('d-m-Y H:i', $termino)?></label>
                        </div><!-- /c1 -->
 						                       
                        <div class="c1 height">
                                <label>Marca vehículo: <?=$model->getBrand()?></label>
                        </div><!-- /c1 -->
                        <div class="c1 height">
                                <label>Modelo vehículo: <?=$model->getName()?></label>
                        </div><!-- /c1 --> 

                        <div class="c1 height" style="width: 100%">
                                <label>Valor: $<?=number_format($reserve->getPrice(), 0)?></label>
                        </div><!-- /c1 --> 
                       
                        <div class="c1 height" style="width: 100%">
                                <a href="<?php echo url_for('profile/pedidos') ?>" class="btnAmarillo" id="lnkVolver">Volver</a>
                        </div><!-- /c1 --> 
                         
                    </div><!-- upcar_sector_1 -->
					
                </div><!-- upcar_box_1 -->

            </div>

        </div><!-- main_contenido -->

         <?php include_component('profile', 'colDer') ?>

    </div><!-- main_box_2 -->
    <div class="clear"></div>
</div><!-- main_box_1 -->