<?php

/**
 * Description of ArriendasSoapClient
 *
 * @author Juan Manuel Agüero <jaguero@flowcode.com.ar>
 */
class ArriendasSoapClient extends SoapClient {

    function __doRequest($request, $location, $action, $version) {

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
        $retVal = parent::__doRequest($objWSSE->saveXML(), $location, $action, $version);
        $doc = new DOMDocument();
        $doc->loadXML($retVal);
        return $doc->saveXML();
    }

}
