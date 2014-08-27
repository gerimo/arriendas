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

        $this->_log("getLicenceStatus", "info", "se llamo al servicio");
        $crawler = $client->request('POST', 'http://www.srcei.cl/bloqueo/mostrarLicencia.jsp', array('accion' => "C", 'RUN' => $rut, 'RUNNUM' => $run, 'RUNDV' => $rundv));

        $nodeCount = count($crawler->filter('td.textodatos'));
        $chequedNodeCount = 0;

        /* conditions */
        $lastname_match = false;
        $blocked_date_found = false;
        $problems_found = false;

        foreach ($crawler->filter('td.textodatos') as $node) {
            /* si encuentra al nombre esta bloqueada */
            if (preg_match("/" . $lastname . "/i", $node->nodeValue)) {
                $this->_log("getLicenceStatus", "info", "encontro el nombre");
                $lastname_match = true;
            }

            /* problemas para procesar */
            if (preg_match("/Problemas para procesar requerimiento/i", $node->nodeValue)) {
                $this->_log("getLicenceStatus", "info", "encontro problemas");
                $problems_found = true;
            }
            $chequedNodeCount++;
        }

        foreach ($crawler->filter('td.textocuerpo') as $node) {
            /* bloqueado */
            if (preg_match("/Fecha Bloqueo/i", $node->nodeValue)) {
                $this->_log("getLicenceStatus", "info", "encontro fecha de bloqueo");
                $blocked_date_found = true;
            }
        }

        if ($blocked_date_found) {
            $status = 2;
        }
        if (!$lastname_match && $chequedNodeCount >= $nodeCount) {
            $status = 2;
        }
        if ($problems_found) {
            $status = 0;
        }

        return $status;
    }

    protected function _log($step, $status, $msg) {
        if (sfConfig::get('sf_environment') == "dev") {
            $logPath = sfConfig::get('sf_log_dir') . '/scraper.log';
            $custom_logger = new sfFileLogger(new sfEventDispatcher(), array('file' => $logPath));
            $custom_logger->info($step . " - " . $status . ". " . $msg);
        }
    }

}
