<?php use_stylesheet('search.css') ?>
<?php use_stylesheet('landing.css') ?>
<?php use_stylesheet('registro.css') ?>
<?php use_stylesheet('comparaprecios.css') ?>

<?php
$useragent=$_SERVER['HTTP_USER_AGENT'];
if(preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i',$useragent)||preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i',substr($useragent,0,4))){
  use_stylesheet('moviles.css');
}
?>

<style>
    .land_ultauto_item {
        margin-right:20px;
    }
    /* css for timepicker */
    .ui-timepicker-div .ui-widget-header { margin-bottom: 8px; }
    .ui-timepicker-div dl { text-align: left; }
    .ui-timepicker-div dl dt { height: 25px; margin-bottom: -25px; }
    .ui-timepicker-div dl dd { margin: 0 10px 10px 65px; }
    .ui-timepicker-div td { font-size: 90%; }
    .ui-tpicker-grid-label { background: none; border: none; margin: 0; padding: 0; }


    .ui-widget { font-family: Lucida Grande, Lucida Sans, Arial, sans-serif; font-size: 0.8em; }


    

    .time-picker { width:121px;}

    .comparaPrecio{
        height: 30px;
        margin-top: 10px;
        text-align: center;
        text-decoration: none;
        font-weight: bold;
        color: #ec00b8;
    }

    .img_instrucciones_2{
        cursor: pointer;
    }
 	
</style>


<style type="text/css">



    #loading {
        z-index:10;
        position:absolute;
        width: 630px;
        height: 520px;
        background: white url(<?php echo image_path("ajax-loader.gif") ?>) no-repeat center center;
        zoom: 1;
        filter: alpha(opacity=50);
        opacity: 0.5; 
    }	  

    #loadingSearch {
        /* z-index:10;
       position:absolute;*/
		display:table;
        width: 950px;
        height: 215px;
        background: white url(<?php echo image_path("ajax-loader.gif") ?>) no-repeat center center;
        zoom: 1;
        filter: alpha(opacity=50);
        opacity: 0.5; 
    }	  
	
	
	
    #loading #icon {
        position:absolute;
        top: 240px;
        left: 300px;

    }

    #offers {
        width: 300px;
        height: 400px;
        float:right;
    }

    #InfoCar{
        font-size:12px;
        width:300px;
        height:150px;


    }

    #InfoCar h1{
        font-size:14px;
    }

    .imagemark
    {
        margin-bottom:10px;
        float:left;
    }



    .land_sector3{

        display:none;
    }

    #search{

    }


.divTable
{
    width: 100%;
    height: 100%;
    display: table;
	text-align: center;
}

.divTableRow
{
    width: 100%;
    height: 100%;
    display: table-row;
}

.divTableCell
{
    width: 25%;
    height: 100%;
    display: table-cell;
	padding: 10px;
	padding-top: 30px;
	padding-bottom: 30px;
}
</style>

<input type="hidden" id="hour_from_hidden" />
<input type="hidden" id="hour_to_hidden" />
<input type="hidden" id="price_hidden" />
<input type="hidden" id="model_hidden" value="0"  />
<input type="hidden" id="brand_hidden" />
<input type="hidden" id="location_hidden" />
<input type="hidden" id="day_from_hidden" />
<input type="hidden" id="day_to_hidden" />


<div class="search_container">




<div class="main_box_1_anterior">
    <div id="centro">





<div class="titulos" style="margin-left: 10px;
margin-top: 5px;
padding: 10px 0;float: left;width: 940px;"> Arriendas.cl En las Noticias </div>

<div id="video">

<div class="divTable">
        <div class="divTableRow" style="padding-top:20px">
            <div class="divTableCell">
			<?php echo link_to(image_tag("/images/logos_canais/LogoTerra.png"), 'http://www.terra.cl/economia/emprendimiento/?pagina=noticias&id_reg=1793372','target=_blank') ?>
            </div>
            <div class="divTableCell ">
                <?php echo image_tag("/images/logos_canais/LogoPulso.png") ?>
            </div>
            <div class="divTableCell ">
			<?php echo link_to(image_tag("/images/logos_canais/13.png"), 'http://www.13.cl/t13/sociedad/arrienda-tu-auto-es-la-nueva-tendencia-entre-los-chilenos','target=_blank') ?>
            </div>
            <div class="divTableCell ">
			<?php echo link_to(image_tag("/images/logos_canais/LogoLUN.png","size=175x27"), 'http://www.lun.com/lunmobile//pages/NewsDetailMobile.aspx?IsNPHR=1&dt=2012-10-23&NewsID=0&BodyId=0&PaginaID=6&Name=6&PagNum=0&SupplementId=0&Anchor=20121023_6_0_0','target=_blank') ?>
            </div>
        </div>
        <div class="divTableRow">
            <div class="divTableCell">
			<?php echo link_to(image_tag("/images/logos_canais/LogoPublimetro.png"), 'http://www.tacometro.cl/prontus_tacometro/site/artic/20121030/pags/20121030152946.html','target=_blank') ?>
            </div>
            <div class="divTableCell ">
            <?php echo image_tag("/images/logos_canais/logo caras.png") ?>
            </div>
            <div class="divTableCell ">
			<?php echo link_to(image_tag("/images/logos_canais/LogoEmol.png"), 'http://www.emol.com/noticias/economia/2012/07/27/552815/emprendedor-estrenara-primer-sistema-de-arriendo-de-vehiculos-por-hora-de-chile.html','target=_blank') ?>
            </div>
            <div class="divTableCell ">
			<?php echo link_to(image_tag("/images/logos_canais/LogoLaSegunda.png","size=150x25"), 'http://www.lasegunda.com/Noticias/CienciaTecnologia/2012/08/774751/arriendascl-sistema-de-alquiler-de-autos-por-horas-debuta-en-septiembre','target=_blank') ?>
            </div>
        </div>            
        <div class="divTableRow">
            <div class="divTableCell">
			<?php echo link_to(image_tag("/images/logos_canais/LogoLaCuarta.png"), 'http://www.lacuarta.com/noticias/cronica/2013/08/63-157571-9-ahora-puedes-arrendar-el-automovil-de-tu-vecino.shtml','target=_blank') ?>
            </div>
            <div class="divTableCell ">
                <?php echo image_tag("/images/logos_canais/Logo_LaTercera.png","size=175x30") ?>
            </div>
            <div class="divTableCell ">
			<?php echo link_to(image_tag("/images/logos_canais/LogoDiarioPyme.png","size=150x25"), 'http://www.diariopyme.cl/arrienda-tu-auto-y-gana-dinero-extra-a-fin-de-mes/prontus_diariopyme/2013-06-23/212000.html','target=_blank') ?>
            </div>
            <div class="divTableCell ">
 			<?php echo link_to(image_tag("/images/logos_canais/logotvn2.png"), 'http://www.24horas.cl/nacional/rent-a-car-vecino-la-nueva-forma-de-viajar-906946','target=_blank') ?>
            </div>
        </div>		
        </div>
        </div

</div>
<br><br>

</div>

</div>
</div>

</div><!-- search_container -->
