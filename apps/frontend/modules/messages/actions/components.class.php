<?php

class messagesComponents extends sfComponents {

    public function executeCredito() {
        $userid = $this->getUser()->getAttribute("userid");
    $user= Doctrine_Core::getTable("user")->findOneById($userid);
    $this->credito= $user->getCredito();
    }

    public function executeLinkMessage() {
        $this->actual = $this->getUser()->getAttribute("userid");
    }

    public function executeAlerts() {
        
        $this->news = Doctrine_Core::getTable("Message")->countNewsByUserId($this->getUser()->getAttribute("userid"));
        $this->pends = Doctrine_Core::getTable("Transaction")->countIncompleteByUser($this->getUser()->getAttribute("userid"));
        $this->rents = Doctrine_Core::getTable("Reserve")->countInProgressByUser($this->getUser()->getAttribute("userid"));
    }

    public function executeGlobe() {
        $this->trans = Doctrine_Core::getTable("Transaction")->findByUser($this->getUser()->getAttribute("userid"), 5);
    }

    public function executeMail() {
        $mensajes = Doctrine_Core::getTable("Message")->lastMessageByUserId($this->getUser()->getAttribute("userid"), 5);
        for($i=0;$i<count($mensajes);$i++){
            $bodyMensaje = $this->recortar_texto($mensajes[$i]->getBody(),15);
            $mensajes[$i]->setBody($bodyMensaje);
        }
        $this->mensaje = $mensajes;
    }

    public function recortar_texto($texto, $limite=100){   
    $texto = trim($texto);
    $texto = strip_tags($texto);
    $tamano = strlen($texto);
    $resultado = '';
    if($tamano <= $limite){
        return $texto;
    }else{
        $texto = substr($texto, 0, $limite);
        $palabras = explode(' ', $texto);
        $resultado = implode(' ', $palabras);
        $resultado .= '...';
    }   
    return $resultado;
    }

    public function executeCar() {
        $idUsuario = sfContext::getInstance()->getUser()->getAttribute('userid');
        $this->user= Doctrine_Core::getTable("user")->findOneById($idUsuario);
        $this->aprobadas = Doctrine_Core::getTable("reserve")->getCantReservasAprobadasByUserId($idUsuario);
        $this->preaprobadas = Doctrine_Core::getTable("reserve")->getCantReservasPreaprobadasByUserId($idUsuario);
        $this->pendientes = Doctrine_Core::getTable("reserve")->getCantReservasPendientesByUserId($idUsuario);
        $this->cont = $this->aprobadas + $this->preaprobadas + $this->pendientes;
       
    }

    public function executeVerify() {

        $user = Doctrine_Core::getTable('User')->find(array($this->getUser()->getAttribute("userid")));
        //$data[] = array(($user->getPictureFile() != null), "Scan de documento",'profile/edit');
        $data[] = array(($user->getDriverLicenseFile() != null || sfContext::getInstance()->getUser()->getAttribute("propietario") == true), "Scan de licencia",'profile/edit');
    //$data[] = array(($user->getRutFile() != null), "Scan de Foto de Rut",'profile/edit');
        $data[] = array(($user->getConfirmed()), "Email confirmado",'profile/edit');
        $data[] = array(($user->getConfirmedFb()), "Facebook confirmado",'main/loginFacebook?logged=true');
        $data[] = array(($user->getTelephone()), "Telefono confirmado",'profile/edit');
     //   $data[] = array(($user->getPaypalId() != null), "Medios de pagos",'profile/edit');
        $this->userdata = $data;

        //verifica la existencia de calificaciones pendientes
        $this->cantCalificaciones = $user->getCantidadCalificaciones();
    }

    public function executePositiveRatings() {

        $positiveRatings = 0;

        //var_dump($this->user->getId());die;

        $reservasUsuario = Doctrine_Core::getTable('Reserve')->findByUserId(array($this->user->getId()));
        
        //echo 'cantidad: '. count($reservasUsuario);die;

        if (count($reservasUsuario)) {
            foreach ($reservasUsuario as $r) {
                $calificacion = $r->getRating()->getQualified();

                if ($calificacion) {
                    $positiveRatings++;
                }
            }
        }
        $this->positiveRatings = $positiveRatings;
    }
    
    public function executeCleanRatings() {

        $cleanRatings = 0;

        //var_dump($this->user->getId());die;

        $reservasUsuario = Doctrine_Core::getTable('Reserve')->findByUserId(array($this->user->getId()));
        
        //echo 'cantidad: '. count($reservasUsuario);die;

        if (count($reservasUsuario)) {
            foreach ($reservasUsuario as $r) {
                $calificacion = $r->getRating()->getCleanSatisfied();

                if ($calificacion) {
                    $cleanRatings++;
                }
            }
        }
        $this->cleanRatings = $cleanRatings;
    }

}

?>
