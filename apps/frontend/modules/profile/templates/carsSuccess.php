<?php use_stylesheet('calendario.css') ?>
<?php use_stylesheet('comunes.css') ?>
<?php use_stylesheet('mis_autos.css??v=05122014') ?>
<?php use_stylesheet('registro.css') ?>
<?php
    $useragent=$_SERVER['HTTP_USER_AGENT'];
    if(preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i',$useragent)||preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i',substr($useragent,0,4))){
        use_stylesheet('moviles.css');
    }
?>
<?php use_javascript('cars.js') ?>

<?php use_stylesheet('jquery.timepicker.css') ?>
<?php use_javascript('jquery.timepicker.js') ?>

<style>

    h3 {
        font-size: 14px;
        font-weight: bold;
        padding: 20px 0 10px 0;
        text-align: center;
    }

    .AOC-container {
        text-align: center;
    }

    .normal-text {
        font-size: 12px;
        font-weight: normal;
        padding: 0 10px 0 5px;
    }

    .btnCars {
        width: 100px;
        height: 26px;
        background-image: url('../images/bt_blue_on_v2.png');
        border: 1px solid #36cced;
        border-radius: 1px 1px 1px 1px;
        color: #FFFFFF;
        text-shadow: 1px 1px 1px #758386;
        font-size: 12px;
        line-height: 23px;
        text-align: center;
        text-decoration: none;

        padding: 3px 9px;
    }

    .btnCars:hover {
        background-image: url('../images/bt_blue_hover_v2.png');
        text-decoration: underline;
    }

    .loader {
        height: 15px;
        width: 15px;
        display: none;
    }
</style>

<script type="text/javascript">
    var urlEliminarCarAjax = <?php echo "'".url_for("profile/eliminarCarAjax")."';" ?>
    var toggleActiveCarAjax = <?php echo "'".url_for("profile/toggleActiveCarAjax")."';" ?>

    var toggleActiveCAEAjax = <?php echo "'".url_for("profile/toggleActiveCAEAjax")."';" ?>

    $(document).on('ready',function(){

        $('.mis_autos_verificado').on('click',function(e){
            e.preventDefault();
        });
        
        $(".selectorActivo").change(function() {
            var id = $(this).parent().attr("id").replace("car_", "");
            var active = $(this).val();
            var divImgActivo = $(this).parent().children(".imgActivo");
            if(active == 0){
                divImgActivo.children(".circuloActivo").css("display","none");
                divImgActivo.children(".circuloInactivo").css("display","block");
            }else{
                divImgActivo.children(".circuloInactivo").css("display","none");
                divImgActivo.children(".circuloActivo").css("display","block");
            }
                
            $('#item_'+id+' .cargando').show();
            $.ajax({
                    type:'post',
                    url: toggleActiveCarAjax,
                    data:{
                        "idCar" : id,
                        "active" : active
                    }
            }).done(function(){
                    $('#item_'+id+' .cargando').hide();
            }).fail(function(){
                    alert('Ha ocurrido un error al eliminar el vehículo, inténtelo nuevamente');
                    $('#item_'+id+' .cargando').hide();
            });
        });

        

        $(".btnCars-save").click(function(){

            var p = $(this).parent();

            var car    = p.data("car-id");
            var day    = p.data("day");
            var from   = p.find(".from").val();
            var to     = p.find(".to").val();
            var loader = p.find(".loader");

            loader.show();

            $.post("<?php echo url_for('profile/carAvailabilitySave') ?>", {"car": car, "day": day, "from": from, "to": to}, function(r){
                console.log(r); // JS no esta interpretando el JSON
                if (r.error) {
                    alert(r.errorMessage);
                }

                loader.hide();
            });
        });

        $(".btnCars-delete").click(function(){

            var p = $(this).parent();

            var car    = p.data("car-id");
            var day    = p.data("day");
            var from   = p.find(".from");
            var to     = p.find(".to");
            var loader = p.find(".loader");

            loader.show();

            $.post("<?php echo url_for('profile/carAvailabilityDelete') ?>", {"car": car, "day": day}, function(r){
console.log(r);
                if (!r.error) {
                    from.val("");
                    to.val("");
                }

                loader.hide();
            });
        });

        $('.ui-timepicker-input').timepicker();

        $(".main_contenido").height($(".availabilityOfCars").height() * $(".availabilityOfCars").length + $(".misautos_user_item").length * 120 + 300);
    });
</script>

<div class="main_box_1">
<div class="main_box_2">

<?php include_component('profile', 'profile') ?>

<!--  contenido de la seccion -->
<div class="main_contenido">

    <div class="barraSuperior">
        <p>MIS AUTOS</p>
    </div>

    <div class="botonSubeTuAuto">
        <a href="<?php echo url_for('profile/addCar') ?>"><?php echo image_tag('img_mis_autos/Subeunauto.png') ?></a>
    </div>
 

    <div class="misautos_box">

        <?php foreach ($cars as $c): ?>
            
            <div class="misautos_user_item" id="item_<?php echo $c->getId() ?>">
                
                <div class="misautos_user_post">
                        
                    <div class="activo" id="car_<?php echo $c->getId() ?>">
                        <div class="imgActivo">
                                <div class="circuloActivo" style="display: <?= ($c->getActivo() == 1)?"block":"none" ?>"></div>
                                <div class="circuloInactivo" style="display: <?= ($c->getActivo() == 0)?"block":"none" ?>" ></div>
                        </div>
                        <select class="selectorActivo">
                            <option value="1" <?= ($c->getActivo() == 1)?"selected":"" ?>>Activo</option>
                            <option value="0" <?= ($c->getActivo() == 0)?"selected":"" ?>>Inactivo</option>
                        </select>
                    </div>
                        
                    <div class="misautos_marca">
                        <a href="<?php echo url_for('profile/addCar?id=' . $c->getId() )?>" ><span><?=$c->getModel()->getBrand()->getName()?> <?=$c->getModel()->getName()?></span></a>
                    </div><!-- misautos_marca -->
                
                
                    <div class="misautos_btn_sector">
                        <!-- <a href="<?php echo url_for('profile/pedidos') ?>" class="misautos_btn_alqui">Mis Arriendos</a> -->
                    
                        <!-- Obtener variable $ok del Auto, rescatandolo de la DB, por mientras se usará de variable -->
                        <!-- $ok = 0; -->
                        <?php $ok = $c->getSeguroOk(); ?>
                        <?php if($ok <> 4): ?>
                            <div class="divBotonVerificar" style="backgound-color:#ec008c;">
                                <!-- <a href="mailto:soporte@arriendas.cl?subject=Reserva%20un%20horario%20para%20que%20verifiquemos%20tu%20auto" class="misautos_btn_alqui">Verificar</a> -->
                            </div>
                        <?php else: ?>
                            <div class="divBotonVerificar">
                                <a href="#" class="mis_autos_verificado">Verificado</a>
                            </div>
                        <?php endif; ?>
                    </div><!-- misautos_btn_sector -->
                
                    <div class='cargando'><?php echo image_tag('../images/ajax-loader.gif', 'class=img_cargando'); ?></div>

                    <div class="misautos_user_item_flecha"></div>
                </div><!-- misautos_user_post -->
            
                <div class="misautos_user_frame">
                
                    <a href="<?php echo url_for('profile/addCar?id=' . $c->getId() )?>" >
                        <?php if($c->getPhotoS3() == 1): ?>
                            <?php echo image_tag($c->getFoto(),array("width"=>"84px","height"=>"84px")) ?>
                        <?php else: ?>
                            <?php $base_url = $sf_request->getUriPrefix().$sf_request->getRelativeUrlRoot() ?>
                            <?php echo "<img class='foto' src='http://res.cloudinary.com/arriendas-cl/image/fetch/w_84,h_84,c_fill,g_center/".$base_url."/uploads/cars/".$c->getFoto()."'/>" ?>
                        <?php endif; ?>
                    </a>
                </div>

                <?php if (isset($availabilityOfCars[$c->getId()])): ?>

                    <div class="availabilityOfCars">
                        <h2>Horario para recibir un cliente</h2>
                        
                        <?php foreach ($availabilityOfCars[$c->getId()] as $AOC): ?>

                            <h3><?php echo $AOC["dayName"] ." ". date("j", strtotime($AOC["day"])) ?></h3>
                            <div class="AOC-container" data-car-id="<?php echo $c->getId() ?>" data-day="<?php echo $AOC['day'] ?>">
                                <span class="normal-text">Desde</span>
                                <input autocomplete="off" class="from time ui-timepicker-input" type="text" <?php if (isset($AOC['from'])): echo "value='".date("H:ia", strtotime($AOC['from']))."'"; endif; ?>></td>
                                <input autocomplete="off" class="to time ui-timepicker-input" type="text" <?php if (isset($AOC['to'])): echo "value='".date("H:ia", strtotime($AOC['to']))."'"; endif; ?>></td>
                                <span class="normal-text">Hasta</span>
                                <br><br>
                                <a class="btnCars btnCars-delete" href="">Eliminar</a>
                                <a class="btnCars btnCars-save" href="">Guardar</a>
                                <br><br>
                                <img class="loader" src="../images/ajax-loader.gif">
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <div class="clear"></div>
            </div>
        <?php endforeach; ?>
    </div><!-- misautos_box -->

</div><!-- main_contenido -->

<?php include_component('profile', 'colDer') ?>

</div><!-- main_box_2 -->
</div><!-- main_box_1 -->
