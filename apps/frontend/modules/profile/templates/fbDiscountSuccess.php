<?php $inicio = $reserve->getDate();?>

<?php use_stylesheet('subi_tu_auto.css') ?>
<?php use_stylesheet('registro.css') ?>
<?php use_stylesheet('calendario.css') ?>
<?php use_stylesheet('pagoReserva.css') ?>
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




<script>
    function redireccion() {
        document.location.href="<?php echo url_for('profile/edit?redirect=payReserve&id='.$reserve->getId()); ?>";
    }

    $(document).ready(function() {

        var licenseUp = "<?php echo $licenseUp; ?>";

        if(licenseUp != "" && licenseUp != null){
        }else{
            alert("No has registrado tu licencia de conductor. Te redireccionaremos para que puedas continuar con tu pago");
            setTimeout("redireccion()",2);
        }
        
        $('.botonSiguiente').click(function(event) {
            if($("input[type='radio'][name='deposito']:checked").val()=='depositoGarantia' || $("input[type='radio'][name='deposito']:checked").val()=='pagoPorDia'){
                //alert($("input[type='radio'][name='deposito']:checked").val());
                 event.preventDefault();
                if($("input[type='radio'][name='deposito']:checked").val()=='depositoGarantia'){
                    $("#mensajePagoGarantia").dialog({
                        resizable: false,
                        width: 500,
                        height: 160,
                        modal: false,
                        autoOpen: false,
                        closeOnEscape: false,
                        title: 'Información',
                        position: { my: "center", at: "center"},
                        buttons: {
                            Continuar: function() {
                                $('form#pago').submit();
                                $(this).dialog( "close" );
                            },
                            Cancelar: function() {
                                $(this).dialog( "close" );
                            }
                        }
                    });
                    $("#mensajePagoGarantia").dialog("open");
                }else{
                    $('form#pago').submit();
                }
            }else{
                event.preventDefault();
                alert("Debe indicar una opción de Pago");
            }
        });
        $("input[type='radio'][name='deposito'][value='depositoGarantia']").prop('checked', false);
        $("input[type='radio'][name='deposito'][value='pagoPorDia']").prop('checked', false);
        var opcion = "";
        $("input[type='radio'][name='deposito'][value='depositoGarantia']").click(function() {
            opcion = "opcionDepositoGarantia";
            actualizarPrecioTotal(opcion);
        });
        $("input[type='radio'][name='deposito'][value='pagoPorDia']").click(function() {
            opcion = "opcionPagoPorDia";
            actualizarPrecioTotal(opcion);
        });

        var valorMomentaneo = document.getElementById("valorTotalActualizado");
        var parrafo = document.createElement("span");
        parrafo.innerHTML = "<?php echo number_format($reserve->getPrice()*0.9, 0, ',', '.'); ?>";
        valorMomentaneo.appendChild(parrafo);

		});

    function actualizarPrecioTotal(opcion){
        var precioArriendo = "<?php echo $reserve->getPrice(); ?>";
        var montoDepositoGarantia = "<?php echo $garantia; ?>";
        var montoPorDia = "<?php echo $monto; ?>";
        var montoPorDiaUnico = "<?php echo $montoDiaUnico; ?>";
        var valorActualizado = 0;
        var valorPrecioFinal = document.getElementById("valorTotalActualizado");
        if(opcion == "opcionDepositoGarantia"){
            valorActualizado = parseInt(precioArriendo)+parseInt(montoDepositoGarantia);
            $("input[type='hidden'][name='duracionReservaPagoPorDia']").prop("value","0");
        }else if(opcion == "opcionPagoPorDia"){
            var duracionHoras = "<?php echo $reserve->getDuration(); ?>"
            var numDias = parseInt(duracionHoras/24);
            var numHoras = parseInt(duracionHoras)-(parseInt(numDias)*24);
            
            var precioTotalPrecioPorDia = 0;
            var masDias = 0;
            if(numDias>0){
                if(numHoras>=6){
                    masDias = numDias;
                    precioTotalPrecioPorDia = (masDias*parseInt(montoPorDia))+parseInt(montoPorDiaUnico);
                }else{
                    masDias = numDias - 1;
                    precioTotalPrecioPorDia = (masDias*parseInt(montoPorDia))+(parseInt(montoPorDia)*(numHoras/6))+parseInt(montoPorDiaUnico);
                }
            }else{
                precioTotalPrecioPorDia = parseInt(montoPorDiaUnico);
            }
            valorActualizado = parseInt(precioArriendo)+precioTotalPrecioPorDia;
            $("input[type='hidden'][name='duracionReservaPagoPorDia']").prop("value",precioTotalPrecioPorDia.toString());
        }
        var valorActualizadoString = valorActualizado.toString();
        var parrafoCalculador = document.createElement("span");
        parrafoCalculador.innerHTML = formatNumber(valorActualizadoString);

        document.getElementById("valorTotalActualizado").innerHTML="";
        valorPrecioFinal.appendChild(parrafoCalculador);
    }
    function formatNumber(precio){
        var precioMillones = Math.floor(precio/1000000);
        var precioMiles = parseInt(precio) - (precioMillones*1000000);
        precioMiles = Math.floor(precioMiles/1000);
        var precioCientos = parseInt(precio) - (precioMiles*1000);
        var precioResultado = "";
        if(precioMillones == 0){
            if(precioMiles == 0){
                precioResultado = precioCientos;
            }else{
                if(precioCientos == 0){
                    precioResultado = precioMiles + ".000";
                }else{
                    cantidadDeCientos = ((precioCientos).toString()).length;
                    if(cantidadDeCientos == 1) precioResultado = precioMiles + ".00" + precioCientos;
                    else if(cantidadDeCientos == 2) precioResultado = precioMiles + ".0" + precioCientos;
                    else precioResultado = precioMiles + "." + precioCientos;
                }
            }
        }else{
            if(precioMiles == 0){
                if(precioCientos == 0){
                    precioResultado = precioMillones + ".000.000";
                }else{
                    cantidadDeCientos = ((precioCientos).toString()).length;
                    if(cantidadDeCientos == 1) precioResultado = precioMillones + ".000.00" + precioCientos;
                    else if(cantidadDeCientos == 2) precioResultado = precioMillones + ".000.0" + precioCientos;
                    else precioResultado = precioMillones + ".000." + precioCientos;
                }
            }else{
                cantidadDeMiles = ((precioMiles).toString()).length;
                cantidadDeCientos = ((precioCientos).toString()).length;
                if(cantidadDeMiles == 1){
                    if(cantidadDeCientos == 1) precioResultado = precioMillones + ".00" + precioMiles + ".00" + precioCientos;
                    else if(cantidadDeCientos == 2) precioResultado = precioMillones + ".00" + precioMiles + ".0" + precioCientos;
                    else precioResultado = precioMillones + ".00" + precioMiles + "." + precioCientos;
                }else if(cantidadDeCientos == 2){
                    if(cantidadDeCientos == 1) precioResultado = precioMillones + ".0" + precioMiles + ".00" + precioCientos;
                    else if(cantidadDeCientos == 2) precioResultado = precioMillones + ".0" + precioMiles + ".0" + precioCientos;
                    else precioResultado = precioMillones + ".0" + precioMiles + "." + precioCientos;
                }else{
                    if(cantidadDeCientos == 1) precioResultado = precioMillones + "." + precioMiles + ".00" + precioCientos;
                    else if(cantidadDeCientos == 2) precioResultado = precioMillones + "." + precioMiles + ".0" + precioCientos;
                    else precioResultado = precioMillones + "." + precioMiles + "." + precioCientos;
                }
            }
        }
        return precioResultado;
    }

</script>



<div class="main_box_1">
    <div class="main_box_2">

        <?php include_component('profile', 'profile') ?>


        <!--  contenido de la seccion -->
        <div class="main_contenido">

            <div class="barraSuperior">
            <p>DETALLES DEL ARRIENDO</p>
            </div>

            <form name="pago" method="post" action="<?php echo url_for('profile/fbDiscount') ?>?id=<?=$reserve->getId()?>" id="pago">




			
            <div id="contenidoPayReserva">

                <div style="float: left;
width: 440px;
margin-top: 10px;
margin-left: 30px;">
<center>
            <b style="color:#EC008C;font-size:16px;text-align:center;">COMPARTE Y OBTÉN UN 10% DE DESCUENTO</b><br><br>
                    
                </div>
                <?php $termino = strtotime($inicio) + ($reserve->getDuration() * 60 * 60);
                      if($reserve->getDuration()>24){
                        $duracion = intval($reserve->getDuration()/24)." día(s)";
                        if((intval($reserve->getDuration())-intval(intval($reserve->getDuration()/24)*24))>0){
                            $duracion = $duracion." - ".(intval($reserve->getDuration())-intval(intval($reserve->getDuration()/24)*24))." hr(s)";
                        }
                      }else{
                        $duracion = $reserve->getDuration()." hr(s)";
                      } 
                ?>

                <div id="eleccionDeposito" style="height: 80px;">
<center>

				Comparta ahora Arriendas.cl en Facebook y obtén un 10% de descuento!
				<br><br><br>
			
<center>
				
				<a href="#" 
					  onclick="
						window.open(
						  'https://www.facebook.com/sharer/sharer.php?u=<?php echo url_for("main/fbShare?id=".$reserve->getId(),true)?>', 
						  'facebook-share-dialog', 
						  'width=626,height=436'); 
						return false;"><?php echo image_tag('compartir_en_face.png') ?></a>
				</center>

					
                </div>

                <div class="botones">
                    <a href="<?php echo url_for("profile/pedidos") ?>" class="botonVolver" title="Vuelve a pedidos"></a>
                    <a href="<?php echo url_for("profile/payReserve?id=".$reserve->getId())?>" class="botonSiguiente" title="Ir a punto pago"></a>
                </div>
                <div id="mensajePagoGarantia" style="display:none;"><p>En los próximos segundos te enviaremos un e-mail con los datos de la cuenta para hacer la transferencia.<p></div>
            </div>
        <input type="hidden" name="monto" id="monto" value="<?php echo $reserve->getPrice(); ?>" />
        <input type="hidden" name="carMarcaModel" value="<?php echo $model->getBrand().', '.$model->getName() ?>"/>
        <input type="hidden" name="duracionReserva" value="<?php echo $duracion ?>"/>
        <input type="hidden" name="duracionReservaPagoPorDia" value="" />
        <input type="hidden" name="valorTotalActualizado" id="valorTotalActualizado" value="" />
        </form>
        </div><!-- main_contenido -->

         <?php include_component('profile', 'colDer') ?>

    </div><!-- main_box_2 -->
    <div class="clear"></div>
</div><!-- main_box_1 -->