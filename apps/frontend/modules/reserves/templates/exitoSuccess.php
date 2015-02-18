

<link href="/css/newDesign/exito.css" rel="stylesheet" type="text/css">

<div class="hidden-xs space-100"></div>
<div class="visible-xs space-50"></div>
<div class="row">
    <div class="col-md-offset-3 col-md-6">
      <div class="BCW">
      
          <h1>¡PAGO EXITOSO!</h1>
          <div class="contenido text-center">
            <p>Antes y después de realizar la reserva, debes firmar el 
              <a href="<?php echo url_for('return_form', array('tokenReserve' => $tokenReserve))?>" target='_blank'>informe de daños impreso.</a>
            </p>
            <br>
            <p class="Owner text-center">
              <b>Datos del dueño del auto</b>
            </p>
            <p>
              <b>Nombre:</b>    <?php echo $nameOwner ?> <?php echo $lastnameOwner ?>
              <br>
              <b>Direccion:</b> <?php echo $addressOwner ?> , <?php echo ucfirst(strtolower($comunaOwner)) ?>
              <br>
              <b>Teléfono:</b>  <?php echo $telephoneOwner ?>
              <br>
              <b>Correo:</b>    <?php echo $emailOwner ?>              
            </p>
            <hr>
            <p class="Owner text-center">
              <b>Datos del arriendo</b>
            </p>
            <p>  
              <b>Fecha Inicio:</b>    <?php echo date("d-m-Y H:i",strtotime($durationFrom)) ?>
              <br>
              <b>Fecha Termino:</b>    <?php echo date("d-m-Y H:i",strtotime($durationTo)) ?>
            </p>
          </div>
        <div class="col-md-offset-1">
            <p>Te enviamos estos datos a tu correo</p>
        </div>  
        <div class="row hidden-xs" style="margin-top: 30px">
          <a class="col-md-offset-7 col-md-4 btn btn-a-action" href="<?php echo url_for("reserves")?>">Siguiente</a>
        </div>
        <div class="row visible-xs" style="margin-top: 8px">
          <a class="btn-block btn btn-a-action" href="<?php echo url_for("reserves")?>">Siguiente</a>
        </div> 
      </div>
    </div>
</div>
<div class="hidden-xs space-100"></div>


<script type="text/javascript">
/* <![CDATA[ */
  var google_conversion_id = 996876210;
  var google_conversion_language = "en";
  var google_conversion_format = "2";
  var google_conversion_color = "ffffff";
  var google_conversion_label = "LLsrCL71swQQsr-s2wM";

  var google_conversion_value = 0;
  /* ]]> */
</script>

<script type="text/javascript" src="http://www.googleadservices.com/pagead/conversion.js"></script>
<noscript>
  <div style="display:inline;">
    <img height="1" width="1" style="border-style:none;" alt="" src="http://www.googleadservices.com/pagead/conversion/996876210/?value=0&amp;label=LLsrCL71swQQsr-s2wM&amp;guid=ON&amp;script=0"/>
  </div>
</noscript>


<!-- Google Code for Pago (adwords) Conversion Page de Soporte@arriendas.cl -->
<script type="text/javascript">
/* <![CDATA[ */
var google_conversion_id = 921096551;
var google_conversion_language = "en";
var google_conversion_format = "3";
var google_conversion_color = "ffffff";
var google_conversion_label = "xMNlCLrig1YQ56KbtwM";
var google_remarketing_only = false;
/* ]]> */
</script>

<script type="text/javascript" src="//www.googleadservices.com/pagead/conversion.js"></script>
<noscript>
  <div style="display:inline;">
  <img height="1" width="1" style="border-style:none;" alt="" src="//www.googleadservices.com/pagead/conversion/921096551/?label=xMNlCLrig1YQ56KbtwM&amp;guid=ON&amp;script=0"/>
  </div>
</noscript>


<!-- Facebook Conversion Code for Payment -->

<script>(function() {

  var _fbq = window._fbq || (window._fbq = []);

  if (!_fbq.loaded) {

  var fbds = document.createElement('script');

  fbds.async = true;

  fbds.src = '//connect.facebook.net/en_US/fbds.js';

  var s = document.getElementsByTagName('script')[0];

  s.parentNode.insertBefore(fbds, s);

  _fbq.loaded = true;

  }

  })();

  window._fbq = window._fbq || [];

  window._fbq.push(['track', '6016225594554', {'value':'0.01','currency':'USD'}]);

</script>

<noscript>
  <img height="1" width="1" alt="" style="display:none" src="https://www.facebook.com/tr?ev=6016225594554&amp;cd[value]=0.01&amp;cd[currency]=USD&amp;noscript=1" />
</noscript>

<script >

  $(document).ready(function(){

    var model        = "<?php echo $model?>"
    var brand        = "<?php echo $brand?>"
    var tfn          = "<?php echo $telephoneUser?>"
    var addressCar = "<?php echo $addressCar ?>"
    var comunaCar = "<?php echo ucfirst(strtolower($comunaCar)) ?>"
    console.log(comunaCar+addressCar)

    parameters = {
      
      "model":model,
      "brand" :brand,
      "telephoneUser":tfn,
      "addressCar" : addressCar,
      "comunaCar" : comunaCar
    }
    $.post("<?php echo url_for('message_send_mobile')?>",parameters, function(r){
        if (r.error) {

        }
    });
  
  });

</script>