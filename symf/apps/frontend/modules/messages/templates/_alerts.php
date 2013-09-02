<?php use_helper("jQuery") ?>
<?php use_stylesheet('messages.css') ?>
<div id="info_alert_menu">
    <div class="alerts_submenu">
        <div class="dialog" id="globe"></div>
        <div class="info_alert_menu" id="globe_menu" style="display:none">
            <div class="alert_header" style="background-color:#<?php echo $color ?>;">Transacciones pendientes</div>
            <div class="alert_content">
                <?php include_component('messages', 'globe', array("color" => $color)); ?>
                <?php include_component('messages', 'verify', array("color" => $color)); ?>
            </div>
            <div class="alert_footer"><?= link_to("Ver todas las transacciones", "profile/transactions") ?></div>
        </div>
    </div><!--item1-->

    <div class="alerts_submenu">
        <div class="dialog" id="car"></div>
        <div class="info_alert_menu" id="car_menu" style="display: none; width: 300px;">
            <div class="alert_header" style="background-color:#<?php echo $color ?>;">Alquileres en curso</div>
            <div class="alert_content">
                <?php include_component('messages', 'car', array("color" => $color)); ?>
            </div>
            <div class="alert_footer"><?= link_to("Ver mis pedidos de reserva", "profile/rental") ?></div>
        </div>
    </div><!--item2-->

    <div class="alerts_submenu">
        <div class="dialog" id="mail"></div>
        <div class="info_alert_menu" id="mail_menu" style="display: none;margin-left: 6px;">
            <div class="alert_header" style="background-color:#<?php echo $color ?>;">Mensajes recibidos</div>
            <div class="alert_content">
                <?php include_component('messages', 'mail', array("color" => $color)); ?>
            </div><div class="alert_footer"><?php echo link_to("Ver todos los mensajes", "messages/inbox") ?></div>
        </div>
    </div><!--item3-->
    <script language="javascript">
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
        <li><a href="#" name="alert_menu_icon" class="tool_trans <?php echo ($pends > 0) ? "tool_seleccion" : "" ?>" onclick="showAlertMenu('globe_menu')" title="Ver alertas"></a></li>
        <li><a href="#" name="alert_menu_icon" class="tool_car <?php echo ($rents > 0) ? "tool_seleccion" : "" ?>" onclick="showAlertMenu('car_menu')" title="Ver arriendos"></a></li>
        <li><a href="#" name="alert_menu_icon" class="tool_msg <?php echo ($news > 0) ? "tool_seleccion" : "" ?>" onclick="showAlertMenu('mail_menu')" title="Ver mensajes"></a></li>
    </ul>
</div><!-- header_tools -->
