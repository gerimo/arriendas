

<div class="container">

    <div class="hidde-xs space-100"></div>
    <div class="col-md-offset-4 col-md-4" id="buscar">
        <label>Busqueda</label> 
        <input class="form-control" id="carId" placeholder="Id auto" type="text">
        <div class="hidde-xs space-20"></div>
        <button class="buscar btn btn-block btn-primary" onclick="findCar()">Buscar</button>
    </div>

    <div id="formulario">

        <h1>Verificar auto</h1>
 
        <ul class="steps hidden-xs">
            <li data-target="#hk-container" data-slide-to="0" class="active">Paso 1</li>
            <li data-target="#hk-container" data-slide-to="1">Paso 2</li>
            <li data-target="#hk-container" data-slide-to="2">Paso 3</li>
            <li data-target="#hk-container" data-slide-to="3">Paso 4</li>
        </ul>
 
        <div id="hk-container" class="carousel slide" data-ride="carousel">
 
            <div class="carousel-inner" role="listbox">
                <div class="item active">
 
                    <h2>Fotos del auto</h2>
                    <div class="col-md-12">
                        <div class="container hk-container">                      
                            <div class="photo text-center col-md-6">
                                <label>Foto Perfil</label>
                                <?php echo image_tag('img_asegura_tu_auto/AutoVistaAerea.png') ?>
                                <img class="fotoPerfil">
                                <a id="linkPhotoCar" href=""><i class="fa fa-edit"></i> subir</a>
                            </div>                   
                            <div class="photo text-center col-md-6">
                                <label>Foto Perfil</label>
                                <?php echo image_tag('img_asegura_tu_auto/AutoVistaAerea.png') ?>
                                <img class="fotoPerfil">
                                <a id="linkPhotoCar" href=""><i class="fa fa-edit"></i> subir</a>
                            </div>
                        </div>  
                    </div>
                    <div class="row">
                        <div class="col-xs-offset-6 col-xs-6 col-sm-offset-6 col-sm-6 col-md-offset-6 col-md-6"><button class="btn btn-block btn-primary" data-target="#hk-container" data-slide-to="1">Siguiente</button></div>
                    </div>
                </div>
 
                <div class="item">
 
                    <h2>Daños del auto </h2>
 
                    <div class="container hk-container">
                      
                    </div>
 
                    <div class="row">
                        <div class="col-xs-6 col-sm-6 col-md-6"><button class="btn btn-block btn-primary" data-target="#hk-container" data-slide-to="0">Anterior</button></div>
                        <div class="col-xs-6 col-sm-6 col-md-6"><button class="btn btn-block btn-primary" data-target="#hk-container" data-slide-to="2">Siguiente</button></div>
                    </div>
 
                </div>
 
                <div class="item">
 
                    <h2>Audio del auto</h2>
 
                    <div class="container hk-container">                      
 
                        
 
                    </div>
 
                    <div class="row">
                        <div class="col-xs-6 col-sm-6 col-md-6"><button class="btn btn-block btn-primary" data-target="#hk-container" data-slide-to="1">Anterior</button></div>
                        <div class="col-xs-6 col-sm-6 col-md-6"><button class="btn btn-block btn-primary" data-target="#hk-container" data-slide-to="3">Siguiente</button></div>
                    </div>
 
                </div>
 
                <div class="item">
 
                    <h2>Accesorios del auto</h2>
 
                    <div class="container hk-container">                      
 
 
                    </div>
 
                    <div class="row">
                        <div class=" col-xs-6 col-sm-6 col-md-6"><button class="btn btn-block btn-primary" data-target="#hk-container" data-slide-to="2">Anterior</button></div>
                        <div class=" col-xs-6 col-sm-6 col-md-6"><button class="btn btn-block btn-success">Finalizar</button></div>
                    </div>
 
                </div>
            </div>
        </div>
    </div>
</div>
 
<script>
 
    $(document).ready(function(){
   
        $('#hk-container').carousel({
            interval: false
        });

        $('#formulario').hide();
   
    });

    $(document).on("click", ".btn-default", function(){
       
        var step = $(this).data("slide-to");

        $(".steps li").removeClass("active");

        setTimeout(function() {
            $(".steps li[data-slide-to='"+step+"']").addClass("active");
        }, 400);
    });

    $(document).on("click", ".steps li", function(){        
        $(".steps li").removeClass("active");
        $(this).addClass("active");
    });

    $(document).on("change", "#service-types", function(){
   
        if ($(this).val() == -1) {
            $("#new-service-type-container").show();
            $("#new-service-type-name").focus();
        } else {
            $("#new-service-type-container").hide();
            $("#new-service-type-container input").val("");
        }
    });

    $(document).on("change", "#worker", function(){
   
        if ($(this).val() == 2) {
            $("#worker-container").show();
            $("#worker-name").focus();
        } else {
            $("#worker-container").hide();
            $("#worker-container input").val("");
        }
    });

    function findCar() {
        var carId = $("#carId").val();

        $.post("<?php echo url_for('car_find_car') ?>", {"carId": carId}, function(r){
            if (r.error) {
                console.log(r.errorMessage);
            } else {
                console.log(r.data);
                $("#buscar").hide();
                $("#formulario").show();
                var urlFotoTipo      = "<?php echo image_path('http://www.arriendas.cl/uploads/cars/" + r.data + "'); ?>";

                var str = 0;
                if (r.data) {
                    str = r.data.indexOf("cars");
                }

                if(str > 0) {
                    urlFotoTipo = r.data;
                }

                console.log(urlFotoTipo);
                $(".fotoPerfil" ).attr( "src", urlFotoTipo );

            }

        }, 'json');
    }

    function urlPhoto(url) {

        var urlFotoTipo = "/uploads/cars/"+url;

        var str = 0;
        if (r.data) {
            str = r.data.indexOf("cars");
        }

        var windowMarker

        if(str > 0) {
            windowMarker = "<img class='img-responsive' src='http://www.arriendas.cl" + urlFotoTipo + "'/>";
        } else {
            windowMarker = "<img class='img-responsive' src='http://res.cloudinary.com/arriendas-cl/image/fetch/w_112,h_84,c_fill,g_center/http://www.arriendas.cl" + urlFotoTipo + "'/>";
        }

        return windowMarker;
    }
</script>