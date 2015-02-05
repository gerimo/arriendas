<?php

class mainActions extends sfActions {

    public function executeComment (sfWebRequest $request) {

        $userId           = $request->getParameter("userId", null);
        $managementId     = $request->getParameter("managementId", null); 

        $this->User = $userId;
        $this->Management = $managementId;
        $this->UserManagements = Doctrine_Core::getTable('UserManagement')->findCommentsUser($userId, $managementId);
    }

    public function executeCommentDo(sfWebRequest $request) {

        $return = array("error" => false);

        try {   

            $userId         = $request->getPostParameter("userId", null);
            $managementId   = $request->getPostParameter("managementId", null);
            $comment        = $request->getPostParameter("comment", null);

            $User       = Doctrine_Core::getTable('User')->find($userId);
            $Management = Doctrine_Core::getTable('Management')->find($managementId);

            if (!$User) {
                throw new Exception("Usuario no encontrado", 1);
            }
            if (!$Management) {
                throw new Exception("Management no encontrado", 1);
            }
            if (is_null($comment) || $comment == "") {
                throw new Exception("Debe ingresar un comentario", 1);
            }

            
            $UserManagement = new UserManagement();

            $UserManagement -> setUser($User);
            $UserManagement -> setManagement($Management);
            $UserManagement -> setComment($comment);
            $UserManagement -> setCreatedAt(date("Y-m-d H:i"));
            $UserManagement -> save();
    

        } catch (Exception $e) {
            $return["error"] = true;
            $return["errorCode"] = $e->getCode();
            $return["errorMessage"] = $e->getMessage();
        }

        $this->renderText(json_encode($return));
        
        return sfView::NONE;
    }

    public function executeIndex (sfWebRequest $request) {
        /*$this->redirect("user_without_pay");*/
    }

    public function executeLogin (sfWebRequest $request) {

        $this->setLayout("layoutLogin");

        // Si si usuario está logueado se redirecciona
        if ($this->getUser()->isAuthenticated()) {
            $this->redirect('homepage');
        }
        
        $referer = $this->getContext()->getActionStack()->getSize() > 1 ? $request->getUri() : $request->getReferer();
        $this->getUser()->setAttribute("referer", $referer);
        error_log($referer);
    }

    public function executeLoginDo (sfWebRequest $request) {

        if ($request->isMethod("post")) {
            
            $q = Doctrine::getTable('user')
                ->createQuery('u')
                ->where('(u.email = ? OR u.username = ?) and u.password = ?', array($this->getRequestParameter('username'), $this->getRequestParameter('username'), md5($this->getRequestParameter('password'))));

            try {
                $user = $q->fetchOne();
            } catch (Exception $e) {
                Utils::reportError($e->getMessage(), "main/loginDo");
            }

            if ($user) {

                if ($user->getConfirmed() == 1 || $user->getConfirmed() == 0) {

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

                    $this->redirect($this->getUser()->getAttribute("referer"));
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
