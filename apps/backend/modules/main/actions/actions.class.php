<?php

class mainActions extends sfActions {

    public function executeComment (sfWebRequest $request) {

        $userId           = $request->getParameter("userId", null);
        $managementId     = $request->getParameter("managementId", null); 

        $this->User = $userId;
        $this->Management = $managementId;
        $this->UserManagements = Doctrine_Core::getTable('UserManagement')->findCommentsUser($userId, $managementId);
    }

    public function executeCommentDelete(sfWebRequest $request) {

        $return = array("error" => false);

        try {   

            $commentId    = $request->getPostParameter("commentId", null);

            $UserManagement = Doctrine_Core::getTable('UserManagement')->find($commentId);

            if (!$UserManagement) {
                throw new Exception("Comentario no encontrado", 1);
            }

            
            $UserManagement->setIsDeleted(1);
            $UserManagement->save();

            $return["comentId"] = $UserManagement->id;

        } catch (Exception $e) {
            $return["error"] = true;
            $return["errorCode"] = $e->getCode();
            $return["errorMessage"] = $e->getMessage();
        }

        $this->renderText(json_encode($return));
        
        return sfView::NONE;
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
            
            $return["comentId"] = $UserManagement->id;

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
    }

    public function executeLoginDo (sfWebRequest $request) {

        if ($request->isMethod("post")) {

            try {

                $q = Doctrine::getTable('user')
                ->createQuery('u')
                ->where('(u.email = ? OR u.username = ?) and u.password = ?', array($this->getRequestParameter('username'), $this->getRequestParameter('username'), md5($this->getRequestParameter('password'))));

                $oUser = $q->fetchOne();
            } catch (Exception $e) {
                error_log("[Backend] [".date("Y-m-d H:i:s")."] [main/loginDo] ".$e->getMessage());
            }

            if ($oUser) {
                if ($oUser->getIsEmployee()) {
                    $this->getUser()->setFlash('msg', 'Autenticado');

                    $this->getUser()->setAuthenticated(true);

                    $this->getUser()->setAttribute("fullname", ucwords(strtolower($oUser->firstname)." ".strtolower($oUser->lastname)));
                    $this->getUser()->setAttribute("firstname", $oUser->getFirstName());

                    $this->redirect($this->getUser()->getAttribute("referer"));
                } else {
                    $this->getUser()->setFlash('msg', 'No tienes autorización');
                    $this->getUser()->setFlash('show', true);
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

    public function executeUploadPhoto(sfWebRequest $request) {

        $return = array();

        try {

            if (!isset($_POST) || $_SERVER['REQUEST_METHOD'] != "POST") {
                throw new Exception("No! No! No!", 1);
            }

            $name = $_FILES['foto1']['name'];
            $size = $_FILES['foto1']['size'];
            $tmp  = $_FILES['foto1']['tmp_name'];
            
            list($txt, $ext) = explode(".", $name);

            $ext = strtolower($ext);

            $carId = 398454;
            $actual_image_name = time() . $carId . "." . $ext;

            $uploadDir = sfConfig::get("sf_web_dir");
            $path      = $uploadDir . '/images/cars/';
            $fileName  = $actual_image_name . "." . $ext;

            $uploaded = move_uploaded_file($tmp, $path . $actual_image_name);

            if (!$uploaded) {
                throw new Exception("No se pudo subir la imagen de perfil", 1);
            }

            sfContext::getInstance()->getConfiguration()->loadHelpers("Asset");

            $Car = Doctrine_Core::getTable('car')->find($carId);
            $Car->setFotoPerfil("/images/cars/".$actual_image_name);
            $Car->save();

        } catch (Exception $e) {
            $return["error"] = true;
            $return["errorMessage"] = $e->getMessage();
            error_log($e->getMessage());
            if ($e->getCode() == 1) {
                $return["errorMessage"] = "Problemas al subir la imagen. El problema ha sido notificado al equipo de desarrollo, por favor, intentalo más tarde";
            }
        }

        $this->renderText(json_encode($return));
        error_log(json_encode($return));
        return sfView::NONE;
    }
}
