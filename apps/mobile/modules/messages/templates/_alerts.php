<?php use_helper("jQuery") ?>
<?php use_stylesheet('messages.css') ?>
<div id="info_alert_menu">

    <!--item1-->
    <div class="alerts_submenu">
        <div class="dialog" id="globe"></div>
        <div class="info_alert_menu" id="globe_menu" style="display:none;margin-left: -90px;">
            <div class="puntaNotificacion"></div>
            <div class="alert_header" style="background-color:#<?php echo $color ?>;">Perfil</div>
            <div class="alert_content">
                <?php //include_component('messages', 'globe', array("color" => $color)); ?>
                <?php include_component('messages', 'verify', array("color" => $color)); ?>
            </div>
            <div class="alert_footer"><?= link_to("Editar perfil", "profile/edit") ?></div>
        </div>
    </div><!--item1-->

    <!--item2-->
    <div class="alerts_submenu">
        <div class="dialog" id="car"></div>
        <div class="info_alert_menu" id="car_menu" style="display: none;margin-left: -90px;">
            <div class="puntaNotificacion"></div>
            <div class="alert_header" style="background-color:#<?php echo $color ?>;">Pedidos de reserva</div>
            <div class="alert_content">
                <?php include_component('messages', 'car', array("color" => $color)); ?>
            </div>
            <div class="alert_footer"><?= link_to("Ver mis pedidos de reserva", "profile/pedidos") ?></div>
        </div>
    </div><!--item2-->

    <!--item3-->
    <div class="alerts_submenu">
        <div class="dialog" id="mail"></div>
        <div class="info_alert_menu" id="mail_menu" style="display: none;margin-top:30px;margin-left: -90px;">
            <div class="puntaNotificacion" style="margin-left:97px;"></div>
            <div class="alert_header" style="background-color:#<?php echo $color ?>;">Mensajes recibidos</div>
            <div class="alert_content">
                <?php include_component('messages', 'mail', array("color" => $color)); ?>
            </div><div class="alert_footer"><?php echo link_to("Ver todos los mensajes", "messages/inbox") ?></div>
        </div>
    </div><!--item3-->

    <script language="javascript">
        $(document).on('ready',function(){
            $('.tool').on('click',function(e){
                e.preventDefault();
            });
        })

        function showAlertMenu(name){
            if ($("#"+name).is(":visible")) {
                $("#"+name).hide("fast");
            } else {
                $(".info_alert_menu").hide();
                $("#"+name).toggle();
            }
        }
    </script>

</div>
<div class="header_tools" id="info_alert_menu_icons">
    <ul>
        <li><a href="#" name="alert_menu_icon" class="tool tool_trans <?php echo ($pends > 0) ? "tool_seleccion" : "" ?>" onclick="showAlertMenu('globe_menu')" title="Ver alertas"></a></li>
        <li><a href="#" name="alert_menu_icon" class="tool tool_car <?php echo ($rents > 0) ? "tool_seleccion" : "" ?>" onclick="showAlertMenu('car_menu')" title="Ver arriendos"></a></li>
        <li><a href="#" name="alert_menu_icon" class="tool tool_msg <?php echo ($news > 0) ? "tool_seleccion" : "" ?>" onclick="showAlertMenu('mail_menu')" title="Ver mensajes"></a></li>
    </ul>
</div><!-- header_tools -->
