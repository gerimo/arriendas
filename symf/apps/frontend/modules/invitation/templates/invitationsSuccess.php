<?php

$app_id = "297296160352803";

$canvas_page = "http://apps.facebook.com/arriendasapp";

$message = "Entra a Arriendas.cl";

$auth_url = "https://www.facebook.com/dialog/oauth?client_id=" . $app_id . "&redirect_uri=" . $canvas_page;

$signed_request = $_REQUEST["signed_request"];

list($encoded_sig, $payload) = explode('.', $signed_request, 2);

$data = json_decode(base64_decode(strtr($payload, '-_', '+/')), true);

if(!is_null($_REQUEST['request_ids']))
{
    $request_id= $_REQUEST['request_ids'];
    
    $request_id= split(",",$request_id);
    $request_id= $request_id[count($request_id)-1];
    
    //Obtenemos el APP TOKEN
    $ch=curl_init();
    curl_setopt($ch,CURLOPT_URL,"https://graph.facebook.com/oauth/access_token?client_id=213116695458112&client_secret=8d8f44d1d2a893e82c89a483f8830c25&grant_type=client_credentials");
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION  ,1);
    curl_setopt($ch, CURLOPT_HEADER      ,0);
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
    $result= curl_exec($ch);
    
    //Obtenemos los datos de la invitacion
    $ch=curl_init();
    curl_setopt($ch,CURLOPT_URL,"https://graph.facebook.com/".$request_id."&".$result);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION  ,1);
    curl_setopt($ch, CURLOPT_HEADER      ,0);
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
    $result= curl_exec($ch);
    
    $datosInvitacion= json_decode($result);
    $recomendador= $datosInvitacion->from->id;
    $_SESSION['sender_user']= $recomendador;
}

if (!isset($_REQUEST["request_ids"])) {
    if(isset($_REQUEST["request"]))
        echo("<script> top.location.href='" .$auth_url. "'</script>");
    else
        echo("<script> top.location.href='" . url_for('main/index') . "'</script>");
} else {
    echo("<script> top.location.href='".url_for('main/loginFacebook')."'</script>");
}
?>