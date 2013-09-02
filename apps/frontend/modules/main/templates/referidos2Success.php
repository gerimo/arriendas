<div id="fb-root"></div>
<script>
  
  
  window.fbAsyncInit = function() {
    FB.init({
      appId      : '213116695458112', // App ID
      channelUrl : '//www.arriendas.cl/channel.html', // Channel File
      status     : true, // check login status
      cookie     : true, // enable cookies to allow the server to access the session
      xfbml      : true  // parse XFBML
    });

  FB.getLoginStatus(function(response) {
  if (response.status === 'connected') {
    // connected
    var url= "https://www.facebook.com/dialog/apprequests?app_id=213116695458112&" +
      "message=Invita a tus amigos a ser parte de la comunidad Arriendas.cl&" +
      "redirect_uri=http://www.arriendas.cl/main/agradecimiento&display=iframe&access_token=" + response.authResponse.accessToken;
      document.getElementById("frame_request").src=url;
  } else if (response.status === 'not_authorized') {
    // not_authorized
    login();
  } else {
    // not_logged_in
    login();
  }
 });
    
  };

  function login() {
    FB.login(function(response) {
        if (response.authResponse) {
    var url= "https://www.facebook.com/dialog/apprequests?app_id=213116695458112&" +
      "message=Invita a tus amigos a ser parte de la comunidad Arriendas.cl&" +
      "redirect_uri=http://www.arriendas.cl/main/agradecimiento&display=iframe&access_token=" + response.authResponse.accessToken;
      document.getElementById("frame_request").src=url;
          
        } else {
           alert("No obtuvimos permiso para conectar con su cuenta FB");
           window.location="http://www.arriendas.cl";
        }
    });
}

  // Load the SDK Asynchronously
  (function(d){
     var js, id = 'facebook-jssdk', ref = d.getElementsByTagName('script')[0];
     if (d.getElementById(id)) {return;}
     js = d.createElement('script'); js.id = id; js.async = true;
     js.src = "//connect.facebook.net/en_US/all.js";
     ref.parentNode.insertBefore(js, ref);
   }(document));
</script>

<iframe id="frame_request" width="100%" height="450px;"></iframe>