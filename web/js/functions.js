function sendWithEnter(input,e,form_id){
  if (e.keyCode == 13 ) {
      if ($("#"+input).val() != "") {
        $("#"+form_id).submit();
      } else {
        return false;
      }
  }
}
$('html').click(function(event) {
var name = $(event.target).attr("name") ;
if (name != "alert_menu_icon") {
  $(".info_alert_menu").hide();  
}
});
