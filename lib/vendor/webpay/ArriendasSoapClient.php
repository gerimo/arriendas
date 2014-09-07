<?php

/**
 * Description of ArriendasSoapClient
 *
 * @author Juan Manuel Agüero <jaguero@flowcode.com.ar>
 */
class ArriendasSoapClient extends SoapClient {

    function __doRequest($request, $location, $action, $version, $oneWay = 0) {


        $PRIVATE_KEY_PATH = sfConfig::get('sf_lib_dir') . "/vendor/webpay/certificates/arriendasVentas.key";
        $CERT_FILE_PATH = sfConfig::get('sf_lib_dir') . "/vendor/webpay/certificates/arriendasVentas.crt";
        $SERVER_CERT_PATH = sfConfig::get('sf_lib_dir') . "/vendor/webpay/certificates/certificate_server.crt";

        $doc = new DOMDocument('1.0');
        $doc->loadXML($request);
        $objWSSE = new WSSESoap($doc);
        $objKey = new XMLSecurityKey(XMLSecurityKey::RSA_SHA1, array('type' => 'private'));
        $objKey->loadKey($PRIVATE_KEY_PATH, TRUE);
        $options = array("insertBefore" => TRUE);
        $objWSSE->signSoapDoc($objKey, $options);
        $objWSSE->addIssuerSerial($CERT_FILE_PATH);
        $objKey = new XMLSecurityKey(XMLSecurityKey::AES256_CBC);
        $objKey->generateSessionKey();
        $env = sfConfig::get('sf_environment');
        /* log */
        if ($env == "dev") {
            $this->_log("__doRequest key", "info", $PRIVATE_KEY_PATH);
            $this->_log("__doRequest", "info", $objWSSE->saveXML());
        }

        $retVal = parent::__doRequest($objWSSE->saveXML(), $location, $action, $version, $oneWay);
        
        /* log */
        if ($env == "dev") {
            $this->_log("__response", "info", $retVal);
        }
        $doc = new DOMDocument();
        $doc->loadXML($retVal);
        return $doc->saveXML();
    }

    protected function _log($step, $status, $msg) {

        $logPath = sfConfig::get('sf_log_dir') . '/webpay.log';
        $custom_logger = new sfFileLogger(new sfEventDispatcher(), array('file' => $logPath));
        $custom_logger->info($step . " - " . $status . ". " . $msg);
    }

}
