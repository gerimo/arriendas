<div class="container">
 
        <h1>Agregar un servicio a la comunidad</h1>
 
        <ul class="steps hidden-xs">
            <li data-target="#hk-container" data-slide-to="0" class="active">1</li>
            <li data-target="#hk-container" data-slide-to="1">2</li>
            <li data-target="#hk-container" data-slide-to="2">3</li>
            <li data-target="#hk-container" data-slide-to="3">4</li>
        </ul>
 
        <div id="hk-container" class="carousel slide" data-ride="carousel">
 
            <div class="carousel-inner" role="listbox">
                <div class="item active">
 
                    <h2>¿Quién?</h2>
 
                    <div class="container hk-container">                      
 
                        <div class="form-group">
                            <label for="worker">Persona que presta el servicio</label>
                            <select class="form-control" id="worker">
                                <option value="1">{{ app.user.name }} {{ app.user.lastname }}</option>
                                <option value="2">El servicio lo prestara un tercero</option>
                            </select>
                        </div>
 
                        <div class="row" id="worker-container" style="display:none">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="worker-name">Nombre(s)</label>
                                    <input class="form-control" id="worker-name">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="worker-lastname">Apellido(s)</label>
                                    <input class="form-control" id="worker-lastname">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="worker-email">Correo electrónico</label>
                                    <input class="form-control" id="worker-email">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="worker-telephone">Teléfono</label>
                                    <input class="form-control" id="worker-telephone">
                                </div>
                            </div>
                        </div>
 
                    </div>
 
                    <div class="row">
                        <div class="col-xs-offset-6 col-xs-6 col-sm-offset-6 col-sm-6 col-md-offset-6 col-md-6"><button class="btn btn-block btn-default" data-target="#hk-container" data-slide-to="1">Siguiente</button></div>
                    </div>
 
                </div>
 
                <div class="item">
 
                    <h2>¿Qué?</h2>
 
                    <div class="container hk-container">
                        <div class="form-group">
                            <label for="service-types">Tipo de servicio</label>
                            <select class="form-control" id="service-types">
                                <option value="0"></option>
                                {% for oServiceType in oServiceTypes %}
                                    <option value="{{ oServiceType.id }}">{{ oServiceType.name }}</option>
                                {% endfor %}
                                <option value="-1">Nuevo servicio</option>
                            </select>
                        </div>
 
                        <div id="new-service-type-container" style="display:none">
                            <div class="form-group">
                                <label for="new-service-type-name">Nombre</label>
                                <input class="form-control" id="new-service-type-name">
                            </div>
                            <div class="form-group">
                                <label for="new-service-type-description">Descripción</label>
                                <textarea class="form-control" id="new-service-type-description"></textarea>
                            </div>
                        </div>
                    </div>
 
                    <div class="row">
                        <div class="col-xs-6 col-sm-6 col-md-6"><button class="btn btn-block btn-default" data-target="#hk-container" data-slide-to="0">Anterior</button></div>
                        <div class="col-xs-6 col-sm-6 col-md-6"><button class="btn btn-block btn-default" data-target="#hk-container" data-slide-to="2">Siguiente</button></div>
                    </div>
 
                </div>
 
                <div class="item">
 
                    <h2>¿?</h2>
 
                    <div class="container hk-container">                      
 
                        <h3>Step 3</h3>
 
                    </div>
 
                    <div class="row">
                        <div class="col-xs-6 col-sm-6 col-md-6"><button class="btn btn-block btn-default" data-target="#hk-container" data-slide-to="1">Anterior</button></div>
                        <div class="col-xs-6 col-sm-6 col-md-6"><button class="btn btn-block btn-default" data-target="#hk-container" data-slide-to="3">Siguiente</button></div>
                    </div>
 
                </div>
 
                <div class="item">
 
                    <h2>¿?</h2>
 
                    <div class="container hk-container">                      
 
                        <h3>Step 4</h3>
 
                    </div>
 
                    <div class="row">
                        <div class=" col-xs-6 col-sm-6 col-md-6"><button class="btn btn-block btn-default" data-target="#hk-container" data-slide-to="2">Anterior</button></div>
                        <div class=" col-xs-6 col-sm-6 col-md-6"><button class="btn btn-block btn-success">Finalizar</button></div>
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
    </script>