<?php use_stylesheet('demo_page.css') ?>
<?php use_stylesheet('demo_table.css') ?>
<?php use_javascript('inicio.dataTables.js') ?>
<?php use_javascript('jquery.dataTables.js') ?>

<?php use_stylesheet('jquery.lightbox-0.5.css') ?>
<?php use_javascript('jquery.lightbox-0.5.js') ?>
<?php use_javascript('jquery.lightbox-0.5.min.js') ?>
<?php use_javascript('jquery.lightbox-0.5.pack.js') ?>
<style type="text/css">
#Fondo{
    margin: 0 auto;
    width: 920px;
}
#subFondo{
    background-color: white;
    float: left;
    width: 920px;
    padding: 20px;
    height: auto ! important;
    min-height: 200px;
    -webkit-box-shadow: 1px 1px 10px rgba(50, 50, 50, 0.4);
    -moz-box-shadow: 1px 1px 10px rgba(50, 50, 50, 0.4);
    box-shadow: 1px 1px 10px rgba(50, 50, 50, 0.4);
    padding-bottom: 40px;
}
#Enunciado{
    float: left;
    width: 900px;
    text-align: center;
    margin-left: 10px;
    line-height: 20px;
    margin-top: 16px;
}

p.textoPie{
    float: left;
    text-align: left;
    margin-top: 20px;
}
h2{
    font-size: 24px;
    margin: 0 auto;
    width: 520px;
    padding-bottom: 10px;
    border-bottom: 2px solid #E2E3E4;
}

</style>
<script type="text/javascript">
    $(function() {
        <?php 
        $canti_autos = count($cars);
        for($i=0;$i<$canti_autos;$i++){
            echo "$('.galeria_".$i." a').lightBox();";
        }
        ?>
    }); 
</script>
<style type="text/css">

/* jQuery lightBox plugin - Gallery style */

.gallery ul{ list-style: none; }
.gallery ul li{ display: inline; }
.gallery ul img{
border: 4px solid #D1D2CA; /*A4A4A4*/
border-width: 4px 4px 4px;
}
.gallery ul a:hover img{
border: 4px solid #EC008C;
border-width: 4px 4px 4px;
color: #fff;
}
.gallery ul a:hover{ color: #fff; }
a{text-decoration:none;}
.posicionamiento1,.posicionamiento2,.posicionamiento3,.posicionamiento4,.posicionamiento5,.posicionamiento6{
    float: left;
}
.posicionamiento1{
    margin-left: 20px;
    padding-bottom: 16px;    
}
.posicionamiento2{
    margin-left: 36px;
    padding-top: 12px;    
}
.posicionamiento3{
    margin-left: 40px;
    padding-bottom: 26px;
}
.posicionamiento4{
    margin-left: 10px;
    padding-bottom: 26px;
}
.posicionamiento5{
    margin-left: 30px;
    padding-bottom: 8px;
}
.posicionamiento6{
    margin-left: 32px;
    padding-bottom: 23px;
}

</style>
<div id="Fondo">
    <div id="subFondo">
        <div id="Enunciado">
    <!--
        <h2>Arrienda el auto de un vecino | Arriendas.cl</h2>
        <br>
    -->

    <div id="demo">
<table cellpadding="0" cellspacing="0" border="0" class="display" id="example" width="100%">
    <thead>
        <tr>
            <th>PRECIO</th>
            <th>FOTO</th>
            <th>TIPO</th>
            <th>MODELO</th>
            <th>COBERTURA</th>
            <th>RESERVAR</th>
        </tr>
    </thead>
    <tbody>
        <?php for($i=0;$i<count($cars);$i++){ ?>
        <tr class="gradeA">
            <td><div class="posicionamiento1">$ <?=number_format(intval($cars[$i]['priceday']),0,',','.');?> <span style="font-style: italic;">(por día)</span><br>$ <?=number_format(intval($cars[$i]['pricehour']),0,',','.');?> <span style="font-style: italic;">(por hora)</span></div>
            </td>
            <td>
                <div class="posicionamiento2">
                <div class="gallery galeria_<?=$i;?>">
                  <ul>
                    <?php
                    $cantidadFotos = count($fotos_autos[$i])-3;
                    if($cantidadFotos>0){
                        for($j=0;$j<$cantidadFotos; $j=$j+2){
                            $k=$j+1;
                            if($j==0){
                                if($fotos_autos[$i]['photoS3'] == TRUE){
                            ?>
                            <li>
                                <a title="Foto Perfil" href="http://www.arriendas.cl/main/s3thumb?alto=185&ancho=185&urlFoto=<?=$fotos_autos[$i][$j];?>">
                                    <img title="<?=$fotos_autos[$i][$k];?>" src="http://www.arriendas.cl/main/s3thumb?alto=185&ancho=185&urlFoto=<?=$fotos_autos[$i][$j];?>" width="68" height="68">
                                </a>
                            </li>
                            <?php
                                }else{
                            ?>
                            <li>
                                <a title="Foto Perfil" href="<?=image_path('../uploads/cars/'.$fotos_autos[$i][$j]);?>">
                                    <img title="<?=$fotos_autos[$i][$k];?>" src="<?=image_path('../uploads/cars/thumbs/'.$fotos_autos[$i][$j]);?>" width="68" height="68">
                                </a>
                            </li>
                            <?php
                                }
                            }else{
                                if($fotos_autos[$i]['verificationPhotoS3'] == TRUE){
                            ?>
                            <li>
                                <a style="display:none;" title="<?=$fotos_autos[$i][$k];?>" href="http://www.arriendas.cl/main/s3thumb?alto=600&ancho=600&urlFoto=<?=$fotos_autos[$i][$j];?>">
                                    <img title="<?=$fotos_autos[$i][$k];?>" src="http://www.arriendas.cl/main/s3thumb?alto=40&ancho=40&urlFoto=<?=$fotos_autos[$i][$j];?>" width="68" height="68">
                                </a>
                            </li>
                            <?php
                                }else{
                            ?>
                            <li>
                                <a style="display:none;" title="<?=$fotos_autos[$i][$k];?>" href="<?=image_path('../uploads/verificaciones/'.$fotos_autos[$i][$j]);?>">
                                    <img title="<?=$fotos_autos[$i][$k];?>" src="<?=image_path('../uploads/verificaciones/thumbs/'.$fotos_autos[$i][$j]);?>" width="68" height="68">
                                </a>
                            </li>
                            <?php
                                }
                            }//fin else
                        }//fin for j
                    }else{//Si no existen fotos
                    ?>
                        <li>
                            <img title="No presenta fotos" src="http://www.arriendas.cl/images/default.png" width="68" height="68">
                        </li>
                    <?php
                    }//fin else   
                    ?>
                  </ul>
              </div>
            </div>
            </td>
            <td>
            <div class="posicionamiento3">
            <?php
            if(!$cars[$i]['tipo_vehiculo']) echo "<span style='font-style:italic'>Sin tipo</span>";
            else if($cars[$i]['tipo_vehiculo']=='1'){
                if(intval($cars[$i]['priceday'])<22900) echo "Citycar";
                else if(intval($cars[$i]['priceday'])>=22900 && intval($cars[$i]['priceday'])<26000 ) echo "Sedán";
                else if(intval($cars[$i]['priceday'])>=26000 && intval($cars[$i]['priceday'])<32000 ) echo "Mediano";
                else if(intval($cars[$i]['priceday'])>=32000 && intval($cars[$i]['priceday'])<36000 ) echo "Familiar";
                else if(intval($cars[$i]['priceday'])>=36000) echo "Auto de lujo";
            }else if($cars[$i]['tipo_vehiculo']=='2') echo "Utilitario";
            else if($cars[$i]['tipo_vehiculo']=='3') echo "Pick-up";
            else if($cars[$i]['tipo_vehiculo']=='4') echo "SUV";

            ?>
            </div>
            </td>
            <td><div class="posicionamiento4"><?=$cars[$i]['brand'];?> <?=$cars[$i]['model'];?></div></td>
            <td><div class="posicionamiento5">Robo/Daños/CWD<br>Asistencia de viaje<br>Deducible 5UF</div></td>
            <td><div class="posicionamiento6"><a title="Realizar reserva" href="<?php echo url_for('profile/reserve?id='.$cars[$i]['id']) ?>"><?php echo image_tag('Home/BotonReservarHome.png', array('class'=>'botonReservar','width'=>'90px','height'=>'30px')) ?></a></div></td>
        </tr>
        <?php 
            }//fin for i 
        ?>        
    </tbody>
    <tfoot>
        <tr>
            <th>PRECIO</th>
            <th>FOTO</th>
            <th>TIPO</th>
            <th>MODELO</th>
            <th>COBERTURA</th>
            <th>RESERVAR</th>
        </tr>
    </tfoot>
</table>
    </div>
            
        </div><!-- fin enunciado -->
    <br /><br />
    </div>
</div> 