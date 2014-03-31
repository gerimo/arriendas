<?php

/**
 * Description of ArriendasSoapClient
 *
 * @author Juan Manuel Agüero <jaguero@flowcode.com.ar>
 */
class ArriendasSoapClient extends SoapClient {

    function __doRequest($request, $location, $action, $version, $oneWay = 0) {

        $PRIVATE_KEY_PATH = sfConfig::get('sf_lib_dir') . "/vendor/webpay/certificates/arriendasVentas.key";
        $CERT_FILE_PATH = sfConfig::get('sf_lib_dir') . "/vendor/webpay/certificates/arriendasVentas.csr";

        
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
        $retVal = parent::__doRequest($objWSSE->saveXML(), $location, $action, $version, $oneWay);
        $this->_log("InfoSOAP", "Request", $objWSSE->saveXML() . "    " . $location. "   ". $action);
        
        $this->_log("InfoSOAP", "Response", $retVal . "    " . $location. "   ". $action);
        
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
