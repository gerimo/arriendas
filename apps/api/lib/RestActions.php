<?php

/**
 * Description of RESTActions
 *
 * @author Juan Manuel Agüero <jaguero@flowcode.com.ar>
 */
class RestActions extends sfActions {

    /**
     * Instances the configuration, enables Doctrine validation, authenticates
     * the request.
     */
    public function preExecute() {
        parent::preExecute();

        /* without layout by default */
        $this->setLayout(false);

        /* set content type */
        $availableContentTypes = array("application/json", "application/xml");
        $accept = "";

        foreach ($this->request->getAcceptableContentTypes() as $format) {
            if (in_array($format, $availableContentTypes)) {
                $accept = $format;
                break;
            }
        }
        switch ($accept) {
            case 'application/json':
                $this->request->setRequestFormat('json');
                $this->getResponse()->setContentType('application/json');
                break;
            case 'application/xml':
                $this->request->setRequestFormat('xml');
                $this->getResponse()->setContentType('application/xml');
                break;

            default:
                $this->request->setRequestFormat('json');
                $this->getResponse()->setContentType('application/json');
                break;
        }
    }

}
