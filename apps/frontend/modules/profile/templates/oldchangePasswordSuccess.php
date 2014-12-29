<?php use_stylesheet('registro.css') ?>
<?php use_stylesheet('comunes.css') ?>
<?php
$useragent=$_SERVER['HTTP_USER_AGENT'];
if(preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i',$useragent)||preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i',substr($useragent,0,4))){
  use_stylesheet('moviles.css');
}
?>
<style>

    .input {
        margin: 5px 0;
        background: white;
        float: left;
        clear: both;
    }
    .input span {
        position: absolute;
        padding: 12px;
        margin-left: 3px;
        color: #999;
    }
    .input p.fono {
        position: absolute;
        padding: 12px;
        margin-left: 3px;
        color: #999;
    }
    .input input, .input textarea, .input select {
        position: relative;
        margin: 0;
        border-width: 1px;

        background: transparent; 
        font: inherit;
    }



    /* Hack to remove Safari's extra padding. Remove if you don't care about pixel-perfection. */
    @media screen and (-webkit-min-device-pixel-ratio:0) {
        .input input, .input textarea, .input select { padding: 4px; }
    }

    .input input 
    {
        padding-left: 12px;
    }

    #frm1 
    {
        margin:auto;
        display:table;

    }
    .c1 { 
        margin-bottom: 7px;
    }

    /* css for timepicker */
    .ui-timepicker-div .ui-widget-header { margin-bottom: 8px; }
    .ui-timepicker-div dl { text-align: left; }
    .ui-timepicker-div dl dt { height: 25px; margin-bottom: -25px; }
    .ui-timepicker-div dl dd { margin: 0 10px 10px 65px; }
    .ui-timepicker-div td { font-size: 90%; }
    .ui-tpicker-grid-label { background: none; border: none; margin: 0; padding: 0; }


    .ui-widget { font-family: Lucida Grande, Lucida Sans, Arial, sans-serif; font-size: 0.8em; }

    .titulolteral {
        font-size: 20px;
        color: #333;
        font-family: Arial, Helvetica, sans-serif;
        padding-bottom: 5px;
        float: left;
        border-bottom: 1px solid #E8E8E8;
        margin-top: 20px;
        margin-left: 20px;
        width:200px;
    }

	input.inputerror { border: 1px solid #FF8003; background-color:#FFFADC;}
	select.inputerror { border: 1px solid #FF8003; }

	button.regis_btn_formulario{
		margin-left: 242px;
		margin-top: -20px;
		border-radius: 0px 0px 0px 0px;
	}
	.c1 input{
		-webkit-border-radius: 0px;
		-moz-border-radius: 0px;
		border-radius: 0px;
	}
	#msg_fb{
		float: left;
		width: 470px;
		margin-left: 25px;
		margin-top: 120px;
		height: 80px;
	}
</style>

<script type="text/javascript">

    $(document).ready(function() 
    { 
	
	$('#tandc').attr('checked',true);
                    
        $( ".datepicker" ).datepicker({
            dateFormat: 'yy-mm-dd',
            buttonImageOnly: true,
            changeMonth: true,
            changeYear: true,
            yearRange: ' 1920 : * ',
            onSelect: function(dateText, inst) { 
                            
                            
                var input = $(this);
                var def = input.attr('title');
                if (!input.val() || (input.val() == def)) {
                    input.prev('span').css('visibility', '');
                    if (def) {
                        var dummy = $('<label></label>').text(def).css('visibility','hidden').appendTo('body');
                        input.prev('span').css('margin-left', dummy.width() + 3 + 'px');
                        dummy.remove();
                    }
                } else {
                    input.prev('span').css('visibility', 'hidden');
                }
                            
                            
                            
                            
            }
        });		

    var i=1;
	

    function toggleLabel() {
        var input = $(this);
        setTimeout(function() {
            var def = input.attr('title');
            if (!input.val() || (input.val() == def)) {
                input.prev('span').css('visibility', '');
                if (def) {
                    var dummy = $('<label></label>').text(def).css('visibility','hidden').appendTo('body');
                    input.prev('span').css('margin-left', dummy.width() + 3 + 'px');
                    dummy.remove();
                }
            } else {
                input.prev('span').css('visibility', 'hidden');
            }
        }, 0);
    };

    function resetField() {
        var def = $(this).attr('title');
        if (!$(this).val() || ($(this).val() == def)) {
            $(this).val(def);
            $(this).prev('span').css('visibility', '');
        }
    };

    $('input, textarea').live('change', toggleLabel);
    $('input, textarea').live('keydown', toggleLabel);
    $('input, textarea').live('paste', toggleLabel);
    $('select').live('change', toggleLabel);
    $('select').live('change', toggleLabel);
    $('input, textarea').live('focusin', function() {
        $(this).prev('span').css('color', '#ccc');
    });	
    $('input, textarea').live('focusin', toggleLabel);
    $('input, textarea').live('focusout', function() {
        $(this).prev('span').css('color', '#999');
    });
    $(function() {
        $('input, textarea').each(function() { toggleLabel.call(this); });
    });

});



</script>


<?php echo form_tag('profile/doUpdatePassword', array('method' => 'post', 'id' => 'frm1')); ?> 

<script>
    function submitFrom()
    {
    	
    	$('.inputerror').each(function() {
    		
    		$(this).removeClass('inputerror');
    	});
	
        if(document.myform.onsubmit())
        {
            document.forms["frm1"].submit();
        }
    }
    
    $(function() {
    	
    	$("#frm1").submit(function() {
    		
			if( $("#password").val() != '' ) {
				
				if( $("#passwordAgain").val() == '' ) {
					
					alert("Debe confirmar la nueva contraseña");
					return false;
				}
				else if( $("#password").val() != $("#passwordAgain").val() ) {
					
					alert("La contraseña no coincide. Ingresela nuevamente");
					return false;					
				}
				else { return true; }
			}
			else return true;
    	}) 
    })

    function checkclear(what){
        if(!what._haschanged){
            what.value=''
        };
        what._haschanged=true;
    }
</script>

<div class="main_box_1">
<div class="main_box_2">
<?php include_component('profile', 'profile') ?>
<!--  contenido de la seccion -->
<div class="main_contenido">
    <div class="barraSuperior" style="margin-bottom: 50px;">
		<p>CAMBIAR MI CONTRASEÑA</p>
	</div>
        <div class="c1" style="margin-left: 100px;">
            <label class="input">
                <span>Nueva Contrase&ntilde;a</span>
                <input id="password" name="password" type="password" />
            </label>
            <br/>
        </div><!-- /c1 -->

        <div class="c1" style="margin-left: 100px;">
            <label class="input">
                <span>Confirma tu contrase&ntilde;a</span>
                <input id="passwordAgain" name="passwordAgain" type="password" />
            </label>
            <br/>
        </div><!-- /c1 -->

        <div class="regis_botones posit">     

            <button class="regis_btn_formulario" name="save" onclick="submitFrom()"> Guardar </button>        

        </div><!-- regis_botones -->
	<?php if ($user->getFacebookId() != null){ ?>
	<div id="msg_fb">
    	<p style="margin-left:20px;margin-right:20px;font-style:italic;"><b>Nota</b>: Al cambiar su contraseña, podrá iniciar sesión tanto con Facebook cómo con su correo asociado a la cuenta de Facebook.</p>
    <div>
    <?php } ?>
    </div><!-- main_contenido -->
  </form>
<?php include_component('profile', 'colDer') ?>
</div>
</div>

<script>

    function DoPassValidation()
    {
        var frm = document.forms["frm1"];
        if(frm.password.value != frm.passwordAgain.value)
        {
            sfm_show_error_msg('El password no coincide.',frm.password);
            return false;
        }
        else
        {
            return true;
        }
    }

    function DoEmailValidation()
    {
        var frm = document.forms["frm1"];
        if(frm.email.value != frm.emailAgain.value)
        {
            sfm_show_error_msg('El correo electronico no coincide.',frm.email);
            return false;
        }
        else
        {
            return true;
        }
    }

    var frmvalidator = new Validator("frm1");
 
    frmvalidator.EnableMsgsTogether();
    frmvalidator.setAddnlValidationFunction(DoPassValidation);
 

 
</script>