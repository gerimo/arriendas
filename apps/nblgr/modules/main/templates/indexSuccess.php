<div class="main">
    <div class="container">
        <div class="row">
            <div class="col-md-12 col-xs-12">
                <div style="margin:2px 2px 0 0" class="btn-group">
                    <button type="button" class="btn btn-primary">Nueva</button>
                    <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown"><span class="caret"></span></button>
                    <ul class="dropdown-menu" role="menu">
                      <li><a class="action" data-name="1">Acción</a></li>
                      <li><a class="action" data-name="2">Tipo de notificación</a></li>
                    </ul>
                </div>
                <div class="widget stacked widget-table action-table">
                    <div class="widget-header">
                        <i class="icon-th-list"></i>
                        <h3>Notificaciones Arrendatario</h3>
                    </div>

                    <div class="widget-content table-responsive">
                        <table class="table table-striped table-bordered table-hover" id="table">
                            <thead>
                                <tr>
                                    <th></th>
                                    <?php foreach ($NTS as $NT):?>
                                        <th class="text-center"><?php echo $NT->name?></th>
                                    <?php endforeach ?>
                                </tr>
                            </thead>   
                            <tbody> 
                                <?php foreach ($Notifications as $Notification):?>
                                   <?php if($Notification['condition'] == 0):?>
                                    <tr></tr>
                                    <td class="text-center"><?php echo $Notification['name']?></td>
                                <?php else:?>
                                    <td class="text-center area " data-id="<?php echo $Notification['id']?>" data-title="<?php echo $Notification['title']?>" data-name="<?php echo $Notification['name']?>" ><?php echo $Notification['name2']?></td>
                                <?php endif ?>
                                <?php endforeach ?>
                            </tbody>
                        </table>
                    </div> 
                </div>
            </div>
        
            <div class="col-md-6 col-xs-12"> 
                <div class="widget stacked">    
                    <div class="widget-header">
                        <i class="icon-star"></i>
                        <h3>Quick Stats</h3>
                    </div>

                    <div class="widget-content">
                        <div class="stats">
                            <div class="stat">
                                <span class="stat-value">12,386</span>                                  
                                Site Visits
                            </div> 

                            <div class="stat">
                                <span class="stat-value">9,249</span>                                   
                                Unique Visits
                            </div>

                            <div class="stat">
                                <span class="stat-value">70%</span>                                 
                                New Visits
                            </div>
                        </div>

                        <div id="chart-stats" class="stats">
                            <div class="stat stat-chart">                           
                                <div id="donut-chart" class="chart-holder"></div> <!-- #donut -->                           
                            </div>

                            <div class="stat stat-time">                                    
                                <span class="stat-value">00:28:13</span>
                                Average Time on Site
                            </div>
                        </div>
                    </div>
                </div>

                <div class="widget widget-nopad stacked">      
                    <div class="widget-header">
                        <i class="icon-list-alt"></i>
                        <h3>Recent News</h3>
                    </div>

                    <div class="widget-content">
                        <ul class="news-items">
                            <li>
                                <div class="news-item-detail">                                      
                                    <a href="javascript:;" class="news-item-title">Duis aute irure dolor in reprehenderit</a>
                                    <p class="news-item-preview">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore.</p>
                                </div>
                                
                                <div class="news-item-date">
                                    <span class="news-item-day">08</span>
                                    <span class="news-item-month">Mar</span>
                                </div>
                            </li>
                            <li>
                                <div class="news-item-detail">                                      
                                    <a href="javascript:;" class="news-item-title">Duis aute irure dolor in reprehenderit</a>
                                    <p class="news-item-preview">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore.</p>
                                </div>
                                
                                <div class="news-item-date">
                                    <span class="news-item-day">08</span>
                                    <span class="news-item-month">Mar</span>
                                </div>
                            </li>
                            <li>
                                <div class="news-item-detail">                                      
                                    <a href="javascript:;" class="news-item-title">Duis aute irure dolor in reprehenderit</a>
                                    <p class="news-item-preview">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore.</p>
                                </div>
                                
                                <div class="news-item-date">
                                    <span class="news-item-day">08</span>
                                    <span class="news-item-month">Mar</span>
                                </div>
                            </li>
                        </ul>
                    </div> 
                </div> 
            </div> 
            
            <div class="col-md-6">  
                <div class="widget stacked">  
                    <div class="widget-header">
                        <i class="icon-bookmark"></i>
                        <h3>Quick Shortcuts</h3>
                    </div>
                    
                    <div class="widget-content">
                        <div class="shortcuts">
                            <a href="javascript:;" class="shortcut">
                                <i class="shortcut-icon icon-list-alt"></i>
                                <span class="shortcut-label">Apps</span>
                            </a>
                            
                            <a href="javascript:;" class="shortcut">
                                <i class="shortcut-icon icon-bookmark"></i>
                                <span class="shortcut-label">Bookmarks</span>                               
                            </a>
                            
                            <a href="javascript:;" class="shortcut">
                                <i class="shortcut-icon icon-signal"></i>
                                <span class="shortcut-label">Reports</span> 
                            </a>
                            
                            <a href="javascript:;" class="shortcut">
                                <i class="shortcut-icon icon-comment"></i>
                                <span class="shortcut-label">Comments</span>                                
                            </a>
                            
                            <a href="javascript:;" class="shortcut">
                                <i class="shortcut-icon icon-user"></i>
                                <span class="shortcut-label">Users</span>
                            </a>
                            
                            <a href="javascript:;" class="shortcut">
                                <i class="shortcut-icon icon-file"></i>
                                <span class="shortcut-label">Notes</span>   
                            </a>
                            
                            <a href="javascript:;" class="shortcut">
                                <i class="shortcut-icon icon-picture"></i>
                                <span class="shortcut-label">Photos</span>  
                            </a>
                            
                            <a href="javascript:;" class="shortcut">
                                <i class="shortcut-icon icon-tag"></i>
                                <span class="shortcut-label">Tags</span>
                            </a>                
                        </div>
                    </div> 
                </div>
                     
                <div class="widget stacked"> 
                    <div class="widget-header">
                        <i class="icon-signal"></i>
                        <h3>Chart</h3>
                    </div> 

                    <div class="widget-content">                    
                        <div id="area-chart" class="chart-holder"></div>                    
                    </div> 
                </div>  
            </div>
        </div> 
    </div>
</div>
        
<!-- Modal -->
<div class="modal fade" id="notificationModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Notificación</h4>
            </div>

            <div class="modal-body">
                <div id="carDamages">
                    <label for="recipient-name" class="control-label">Titulo:</label>

                    <input type="text" class="form-control" id="title" placeholder="Titulo">

                    <div class="space-30"></div>

                    <div id="franco">
                        <label class="type">Tipo de Usuario</label>

                        <select class="form-control" id="type" type="text">
                            <option value="">--</option>
                            <?php foreach ($UserTypes as $UserType): ?>
                                <option value="<?php echo $UserType->id?>"><?php echo $UserType->name?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                     <div class="space-30"></div>

                    <label for="recipient-name" class="control-label">Descripción:</label>

                    <textarea type="text" class="form-control" id="message" placeholder="Escriba Descripicion aquí.." rows="5"></textarea>
                </div>
            </div>

            <div class="modal-footer">
                <button data-dismiss="modal" datatype="button" class="btn btn-danger">Cerrar</button>
                <button data-dismiss="modal" type="button" class="btn1 btn btn-success" onclick="createNotificationOrAction()">Crear</button>
                <button data-dismiss="modal" type="button" class="btn2 btn btn-success" onclick="editNotificacion()">Editar</button>
            </div>
        </div>
    </div>
</div>

<div style="display:none">
    <input id="notificationId">

    <input id="option">
</div>

<script>

    $(document).ready(function() {   

               
    }); 

    $("#uno").addClass("active");
    $("#dos").removeClass("active");

    $('body').on("click", ".action", function(e){

        $(".btn2").hide();
        $(".btn1").show();
        $("#title").val("");
        $("#message").val("");
        $("#option").val($(this).data("name"));

        if($(this).data("name") == 1){
            $("#myModalLabel").html("Nueva Acción");
            $("#franco").show();
        } else {
            $("#myModalLabel").html("Nueva tipo de Notificación");
            $("#franco").hide();
        }
        $('#notificationModal').modal('show'); 
    }); 

    function createNotificationOrAction() {

        var description     = $("#message").val();
        var name            = $("#title").val();
        var option          = $("#option").val();
        var userTypeId      = $("#type option:selected").val();


        var parameters = {
            "name"              : name,
            "description"       : description,
            "option"            : option,
            "userTypeId"        : userTypeId
        }

        $.post("<?php echo url_for('notification_create') ?>", parameters, function(r){
            if (r.error) {
                console.log(r.errorMessage);
            } else {         
                location.reload(); 
            }
        }, 'json');
    }

    $('body').on("click", ".area", function(e) {

        $("#notificationId").val($(this).data("id"));
        $("#title").val($(this).data("title"));
        $("#message").val($(this).data("name"));
        $("#franco").hide();
        $(".btn1").hide();
        $(".btn2").show();
        $('#notificationModal').modal('show') 
    });

    function editNotificacion() {

        var notificationId  = $("#notificationId").val();
        var message         = $("#message").val();
        var title           = $("#title").val();

        var parameters = {
            "title"             : title,
            "message"           : message,
            "notificationId"    : notificationId
        }

        $.post("<?php echo url_for('notification_edit') ?>", parameters, function(r){
            if (r.error) {
                console.log(r.errorMessage);
            } else {         
                location.reload(); 
            }
        }, 'json');
    }
</script>   