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

    /**
     * 
     * @param array $data
     * @return type
     */
    public function createPaymentURL($data) {

        /* build concatenated */
        $concatenated = "receiver_id=" . $data["receiver_id"];
        $concatenated .= "&subject=" . $data["subject"];
        $concatenated .= "&body=" . $data["body"];
        $concatenated .= "&amount=" . $data["amount"];
        $concatenated .= "&payer_email=";
        $concatenated .= "&bank_id=";
        $concatenated .= "&expires_date=";
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
        if ((curl_errno($ch) == 60 || curl_errno($ch) == 77)) {
            //curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_CAINFO, dirname(__FILE__) . '/cacert.pem');
            $output = curl_exec($ch);
        }

        if (curl_errno($ch) > 0) {
            $info = curl_getinfo($ch);
            $msg = "khipu connection error:" . curl_error($ch) . " " . curl_errno($ch) . " info:" . var_dump($info);
            $this->_log("createPaymentURL", "error", $msg);
            throw new Exception($msg);
        }
        curl_close($ch);

        return json_decode($output);
    }

    /**
     * 
     * @param type $data
     * @return type
     */
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

        $ch = curl_init($this->khipuUrl);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $to_send . "&notification_signature=" . urlencode($data["notification_signature"]));
        $response = curl_exec($ch);
        if (curl_errno($ch) > 0) {
            $info = curl_getinfo($ch);
            throw new Exception("khipu connection error:" . curl_errno($ch) . " info:" . $info);
        }
        curl_close($ch);
        return $response;
    }

    /**
     * 
     * @param array $data
     * @return json $result.
     */
    public function paymentStatus($data) {
        $concatenated = "receiver_id=" . $data["receiver_id"] . "&payment_id=" . $data["payment_id"];
        $hash = hash_hmac('sha256', $concatenated, $this->secret);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->khipuUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, true);

        $data["hash"] = $hash;

        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        $output = curl_exec($ch);
        if (curl_errno($ch) > 0) {
            $info = curl_getinfo($ch);
            throw new Exception("khipu connection error:" . curl_errno($ch) . " info:" . $info);
        }
        curl_close($ch);
        $response = json_decode($output);
        return $response;
    }

    protected function _log($step, $status, $msg) {
        $logPath = sfConfig::get('sf_log_dir') . '/khipu.log';
        $custom_logger = new sfFileLogger(new sfEventDispatcher(), array('file' => $logPath));
        $custom_logger->info($step . " - " . $status . ". " . $msg);
    }

}
