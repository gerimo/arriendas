<?php

require_once sfConfig::get('sf_lib_dir') . '/vendor/fabpot/goutte.phar';

/**
 * Description of ScraperService
 *
 * @author Juan Manuel Agüero <jaguero@flowcode.com.ar>
 */
class ScraperService {

    /**
     * Check if the licence is blocked.
     * return: 
     *      0 - error conexion.
     *      1 - not blocked.
     *      2 - blocked.
     * @param type $rut
     * @return integer status.
     */
    public function getLicenceStatus($rut, $lastname) {
        $status = 1;
        $portions = explode("-", $rut);
        $run = $portions[0];
        $rundv = $portions[1];
        $client = new \Goutte\Client();

        $this->_log("check bloqueado", "info", "se llamo al servicio");
        $crawler = $client->request('POST', 'http://www.srcei.cl/bloqueo/mostrarLicencia.jsp', array('accion' => "C", 'RUN' => $rut, 'RUNNUM' => $run, 'RUNDV' => $rundv));
        
        foreach ($crawler->filter('td.textodatos') as $node) {
            /* si encuentra al nombre esta bloqueada */
            if (preg_match("/" . $lastname . "/i", $node->nodeValue)) {
                $this->_log("check bloqueado", "info", "encontro el nombre");
                $status = 2;
            }
            
            /* bloqueado */
            if (preg_match("/Fecha Bloqueo/i", $node->nodeValue)) {
                $this->_log("check bloqueado", "info", "encontro fecha de bloqueo");
                $status = 2;
            }
            
            /* problemas para procesar */
            if (preg_match("/Problemas para procesar requerimiento/i", $node->nodeValue)) {
                $this->_log("check bloqueado", "info", "encontro problemas");
                $status = 0;
            }
        }
        foreach ($crawler->filter('td.textocuerpo') as $node) {
            /* bloqueado */
            if (preg_match("/Fecha Bloqueo/i", $node->nodeValue)) {
                $this->_log("check bloqueado", "info", "encontro fecha de bloqueo");
                $status = 2;
            }
        }
        return $status;
    }

    protected function _log($step, $status, $msg) {
        $logPath = sfConfig::get('sf_log_dir') . '/scraper.log';
        $custom_logger = new sfFileLogger(new sfEventDispatcher(), array('file' => $logPath));
        $custom_logger->info($step . " - " . $status . ". " . $msg);
    }

}
