<?php

/**
 * Description of KhipuService
 *
 * @author Juan Manuel Agüero <jaguero@flowcode.com.ar>
 */
class KhipuService {

    private $receiverId;
    private $secret;
    private $khipuUrl;

    function __construct($receiverId, $secret, $khipuUrl) {
        $this->receiverId = $receiverId;
        $this->secret = $secret;
        $this->khipuUrl = $khipuUrl;
    }

    public function createPaymentURL($data) {

        /* build concatenated */
        $concatenated = "receiver_id=" . $data["receiver_id"];
        $concatenated .= "&subject=" . $data["subject"];
        $concatenated .= "&body=" . $data["body"];
        $concatenated .= "&amount=" . $data["amount"];
        $concatenated .= "&payer_email=" . $data["payer_email"];
        $concatenated .= "&bank_id=" . $data["bank_id"];
        $concatenated .= "&transaction_id=" . $data["transaction_id"];
        $concatenated .= "&custom=" . $data["custom"];
        $concatenated .= "&notify_url=" . $data["notify_url"];
        $concatenated .= "&return_url=" . $data["return_url"];
        $concatenated .= "&cancel_url=" . $data["cancel_url"];
        $concatenated .= "&picture_url=" . $data["picture_url"];

        $hash = hash_hmac('sha256', $concatenated, $this->secret);

        /* curl init */
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->khipuUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, true);

        /* add hash to payload */
        $data["hash"] = $hash;

        /* curl exec */
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        $output = curl_exec($ch);
        $info = curl_getinfo($ch);
        curl_close($ch);

        return json_decode($output);
    }

    public function notificationValidation($data) {

        $to_send = 'api_version=' . urlencode($data["api_version"]) .
                '&receiver_id=' . urlencode($data["receiver_id"]) .
                '&notification_id=' . urlencode($data["notification_id"]) .
                '&subject=' . urlencode($data["subject"]) .
                '&amount=' . urlencode($data["amount"]) .
                '&currency=' . urlencode($data["currency"]) .
                '&transaction_id=' . urlencode($data["transaction_id"]) .
                '&payer_email=' . urlencode($data["payer_email"]) .
                '&custom=' . urlencode($data["custom"]);

        $ch = curl_init("https://khipu.com/api/1.2/verifyPaymentNotification");
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $to_send . "&notification_signature=" . urlencode($data["notification_signature"]));
        $response = curl_exec($ch);
        curl_close($ch);
        return $response;
    }

}
