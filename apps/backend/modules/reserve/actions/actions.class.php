<?php

require_once dirname(__FILE__).'/../lib/reserveGeneratorConfiguration.class.php';
require_once dirname(__FILE__).'/../lib/reserveGeneratorHelper.class.php';

/**
 * reserve actions.
 *
 * @package    arriendas
 * @subpackage reserve
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class reserveActions extends autoReserveActions
{
    protected function isValidSortColumn($column)
    {
      return in_array($column, array('Id','Auto'));
    }

    
    public function executeListConfirmar(sfWebRequest $request) {
        $id= $request->getParameter("id");
        $url="http://www.arriendas.cl/profile/confirmReserve/id/".$id;
        $ch= curl_init($url);
        curl_exec($ch);
        $this->redirect('reserve');
    }
    
    public function executeListCancelar(sfWebRequest $request) {
        $id= $request->getParameter("id");
        $ch= curl_init("http://www.arriendas.cl/profile/cancelReserveConfirmation/id/".$id);
        curl_exec($ch);
        $this->redirect('reserve');       
    }
}
