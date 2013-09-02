<?php

$app_id = "297296160352803";

$canvas_page = "http://apps.facebook.com/arriendasapp";

$message = "Entra a Arriendas.cl";

$requests_url = "http://www.facebook.com/dialog/apprequests?app_id="
        . $app_id . "&redirect_uri=" . urlencode($canvas_page)
        . "&message=" . $message;

if (empty($_REQUEST["request"])) {
    echo("<script> top.location.href='" . $requests_url . "'</script>");
} else {
    echo("<script> top.location.href='".url_for('main/index')."'</script>");
    echo "Request Ids: ";
    print_r($_REQUEST["request_ids"]);
}
?>