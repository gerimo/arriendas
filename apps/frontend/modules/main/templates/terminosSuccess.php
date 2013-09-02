<style type="text/css">
#FondoReferidos{
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
    width: 800px;
    text-align: center;
    font-size: 17px;
    margin-left: 50px;
    font-style: italic;
    line-height: 20px;
    margin-top: 16px;
}
#botonCargar{
    width: 166px;
    margin: 0 auto;
    height: 74px;
    margin-top: 134px;
}
input#botonVolver{
    margin: 0px;
    border: 0px;
    padding: 0px;
    cursor: pointer;
    background-image: url("http://www.arriendas.cl/images/Home/BotonIngresa.png");
    background-color: white;
    width: 164px;
    height: 72px;
}

</style>
<div id="FondoReferidos">
    <div id="subFondo">
        <div id="Enunciado">
            <b>Términos y Condiciones</b><br><br>
            <p style="margin-top:20px;">
                <?php include_partial('main/terminosycondiciones') ?>
            </p>
        </div>
    <br /><br />
    </div>
</div> 