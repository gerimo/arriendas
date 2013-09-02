
$(document).ready(function(){
    
    // header menu muestra/oculta submenu
    $(".submenu").hide();
    $(".show_hide").show(100);    
    $('.show_hide').click(function(){
        $(".submenu").slideToggle(100);
    });
    
    
    $(".submenu_profile").hide();
    $(".show_hide_profile").show(100);    
    $('.show_hide_profile').click(function(){
        $(".submenu_profile").slideToggle(100);
    });
    
    $('input[type="text"],input[type="password"]').focusin(function() {  
        $(this).addClass("focusField");  
    });  
    $('input[type="text"],input[type="password"]').focusout(function() {  
        $(this).removeClass("focusField");
        
    }); 
    
// otros scripts...

});