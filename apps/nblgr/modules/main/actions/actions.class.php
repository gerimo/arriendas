<?php

class mainActions extends sfActions {
	
	public function executeIndex(sfWebRequest $request) {

        $this->NTS = Doctrine_Core::getTable('NotificationType')->findAll();
        $Actions = Doctrine_Core::getTable('Action')->findByUserTypeId(1);
        $this->UserTypes = Doctrine_Core::getTable('UserType')->findAll();

        foreach ($Actions as $Action) {
            $Notifications[] = array (
                    "id"        => $Action->id,
                    "name"      => $Action->name,
                    "title"     => "nada",
                    "condition" => 0
                    );
            foreach ($this->NTS as $NT) {
                $Notification = Doctrine_Core::getTable('Notification')->findNotification($Action->id, $NT->id);
                $name2 = substr($Notification->message,0,100)."...";
                $Notifications[] = array (
                    "id"        => $Notification->id,
                    "name"      => $Notification->message,
                    "name2"     => $name2,
                    "title"     => $Notification->message_title,
                    "condition" => 1
                    );
            }
        }

        $this->Notifications = $Notifications;
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
}
