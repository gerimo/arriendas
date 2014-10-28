<?php use_stylesheet('comunes.css') ?>
<?php use_stylesheet('subi_tu_auto.css') ?>
<?php use_stylesheet('registro.css') ?>
<?php use_stylesheet('jquery.lightbox-0.5.css') ?>
<?php
$useragent=$_SERVER['HTTP_USER_AGENT'];
if(preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i',$useragent)||preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i',substr($useragent,0,4))){
  use_stylesheet('moviles.css');
}
?>
<?php use_javascript('jquery.lightbox-0.5.js') ?>
<?php use_javascript('jquery.lightbox-0.5.min.js') ?>
<?php use_javascript('jquery.lightbox-0.5.pack.js') ?>


<script type="text/javascript">
	$(function() {
		$('#gallery a').lightBox();
		$('#galleryDanios a').lightBox();
	});

	var redirect = "<?php if($redireccionamiento) echo 1;else echo 0; ?>" ;

	if (redirect == 1){
		setTimeout("redireccion()",segundos);
	}

	var pagina = 'http://www.arriendas.cl';
	var segundos = 6000;
	function redireccion() {
		document.location.href=pagina;
	}


</script>


<style type="text/css">

/* jQuery lightBox plugin - Gallery style */

#gallery ul,#galleryDanios ul { list-style: none; }
#gallery ul li,#galleryDanios ul li { display: inline; }
#gallery ul img,#galleryDanios ul img {
border: 2px solid #fff; /*A4A4A4*/
border-width: 2px 2px 2px;
}
#gallery ul a:hover img,#galleryDanios ul a:hover img {
border: 2px solid #EC008C;
border-width: 2px 2px 2px;
color: #fff;
}
#gallery ul a:hover,#galleryDanios ul a:hover { color: #fff; }
a{text-decoration:none;}
</style>

<style>
	.texto_normal{
		font-size: 12px;
		margin-right: 20px;
	}
	.texto_normal_grande {
		color: #2D2D2D;
		font-size: 17px;
		font-family: Arial, sans-serif;
	}
	.texto_normal, .subtitulos{
		float: left;
		width: 95%;
	}
	.subtitulos{
		font-style: italic;
		font-size: 15px;
	}
	
	.texto_auto {
		color:#00AEEF;
		font-size: 24px;
	}
	
	.punteado {
		border-bottom: solid;
		border-bottom-width: 1px;
		border-bottom-color: #BDBDBD;
		margin-left: 20px;
		margin-bottom: 20px;
		padding-bottom: 4px;
		margin-top: 20px;
		float: left;
	}
	
	.precios{
		margin-left: 40px;
		margin-bottom: 15px;
	}
	
	.texto_magenta {
		color: #EC008C;
	}
	
	.espaciado {
		margin-top: 20px;
	}
	
	.galeria{
		margin-bottom: 10px;
		margin-left: 30px;
	}

	.botones{
		height: 70px;
		width: 325px;
	}

	.boton{
		margin-right: 10px;
	}
	.boton2{
		margin-left: 10px;
		float: left;
		margin-top: -4px;
		margin-bottom: -4px;
	}

	.titulo{
		float: left;
		margin-left: 18px;
		/*border-bottom: 1px solid #00AEEF;
		padding-bottom: 5px;*/
		font-size: 21px;
		font-weight: bold;
		color: #00AEEF; /* celeste */
		width: 100%;
	}
	.ladoIzquierdo{
		float: left;
		width: 220px;
	}
	.subtitulos p{
		float: left;
	}
	.colorSub{
		color:#00AEEF;
	}
	a.colorSub:hover{
		padding-bottom: 2px;
		text-decoration: underline;
	}
	.interlineado{
		float: left;
	}
	.interlineado2{
		float: left;
		width: 165px;
		margin-left: 5px;

	}
	.img_verificado{
		margin-left: 8px;
	}
	
</style>
<?php if(sfContext::getInstance()->getUser()->getAttribute("logged")){?>
	<div class="main_box_1">
<?php }else{ ?>
	<div class="main_box_1" style="width:520px;">
<?php } ?>
    <div class="main_box_2">


<?php if(sfContext::getInstance()->getUser()->getAttribute("logged")) include_component('profile', 'profile') ?>


<div class="main_contenido">

	<div class="barraSuperior">
		<p>INFORMACIÓN DEL AUTO PARA RENT A CAR</p>
	</div>

	<br>
	<p style="margin-left:15px;font-style:italic;"><?php echo $mensajeError; ?></p>
	<br>
<?php
	if($redireccionamiento){
?>
	<p style="margin-left:15px;color:#6E6E6E;font-style:italic;">En unos segundos lo redireccionaremos al Home</p>
<?php 
	}else{
?>
	<p style="margin-left:15px;color:#6E6E6E;font-style:italic;">Le agradeceríamos que nos informara de este acontecimiento <a href="https://arriendascl.zendesk.com/anonymous_requests/new" title="Ir a contacto" target="_blank">aquí</a></p>
<?php
	}
?>
</div>

<?php if(sfContext::getInstance()->getUser()->getAttribute("logged")) include_component('profile', 'colDer') ?>  

    </div><!-- main_box_2 -->
    <div class="clear"></div>
</div><!-- main_box_1 -->

<style type="text/css">
	#imagenPerfil{
		padding-top: 6px;
		padding-left: 6px;
		width: 193px;
		height: 192px;
		float: left;
		margin-left: 20px;

		-webkit-box-shadow: 0px 0px 9px rgba(50, 50, 50, 0.32);
		-moz-box-shadow:    0px 0px 9px rgba(50, 50, 50, 0.32);
		box-shadow:         0px 0px 9px rgba(50, 50, 50, 0.32;
	}

</style>