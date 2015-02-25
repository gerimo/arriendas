<?php

class SMS {

    private $user = "arriendas";

    private $pass = "arriendas";

    private $sender = "Arriendas";

    public function __construct($sender = null) {
        if ($sender) {
            $this->sender = $sender;
        }
    }

    private function getUrl($message, $phoneNumber) {

        if (strlen(str_replace($phoneNumber, " ", "")) > 8) {
            $phoneNumber = substr($phoneNumber, -8);
        }

        $url = "http://api.infobip.com/api/v3/sendsms/plain?user=".$this->user."&password=".$this->pass."&sender=".$this->sender."&SMSText=".urlencode($message)."&GSM=569".urlencode($phoneNumber);

        return $url;
    }

    public function send($message, $phoneNumber) {

        $url = $this->getUrl($this->removeTildes($message), $phoneNumber);
        if (!$url) {
            return false;
        }

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        $r = curl_exec($ch);

        if (curl_errno($ch)) {
            error_log("[SMS/send] ERROR: " . curl_error($ch));
        } else {
            if ($r === false || $r === null) {
                error_log("[SMS/send] NO HAY MAS CREDITO");
            }
            curl_close($ch);
        }

        return true;
    }

    private function removeTildes($message) {

        $notAllowed = array ("á","é","í","ó","ú","Á","É","Í","Ó","Ú","ñ","À","Ã","Ì","Ò","Ù","Ã™","Ã ","Ã¨","Ã¬","Ã²","Ã¹","ç","Ç","Ã¢","ê","Ã®","Ã´","Ã»","Ã‚","ÃŠ","ÃŽ","Ã”","Ã›","ü","Ã¶","Ã–","Ã¯","Ã¤","«","Ò","Ã","Ã„","Ã‹");
        $allowed = array ("a","e","i","o","u","A","E","I","O","U","n","N","A","E","I","O","U","a","e","i","o","u","c","C","a","e","i","o","u","A","E","I","O","U","u","o","O","i","a","e","U","I","A","E");
        
        $text = str_replace($notAllowed , $allowed ,$message);
        
        return $text;
    }
}