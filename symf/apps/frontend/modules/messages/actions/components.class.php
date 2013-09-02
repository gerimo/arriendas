<?php

class messagesComponents extends sfComponents {

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
        $this->mensaje = Doctrine_Core::getTable("Message")->lastMessageByUserId($this->getUser()->getAttribute("userid"), 5);
    }

    public function executeCar() {
        //$this->cars = Doctrine_Core::getTable("Reserve")->getInProgressByUser($this->getUser()->getAttribute("userid"));
        $this->cars = Doctrine_Core::getTable("Reserve")->getToConfirmByUser($this->getUser()->getAttribute("userid"), 5);
        $this->porconfirmarkmsiniciales = Doctrine_Core::getTable("Reserve")->getToConfirmKmsIniciales($this->getUser()->getAttribute("userid"));
        $this->porconfirmarkmsfinales = Doctrine_Core::getTable("Reserve")->getToConfirmKmsFinales($this->getUser()->getAttribute("userid"));
        $this->porconfirmarkmsinicialesuser = Doctrine_Core::getTable("Reserve")->getToConfirmKmsInicialesUser($this->getUser()->getAttribute("userid"));
        $this->porconfirmarkmsfinalesuser = Doctrine_Core::getTable("Reserve")->getToConfirmKmsFinalesUser($this->getUser()->getAttribute("userid"));
        
        //print_r($this->porconfirmarkmsfinales);die;
    }

    public function executeVerify() {

        $user = Doctrine_Core::getTable('User')->find(array($this->getUser()->getAttribute("userid")));
        //$data[] = array(($user->getPictureFile() != null), "Scan de documento");
        $data[] = array(($user->getDriverLicenseFile() != null), "Scan de licencia");
	//$data[] = array(($user->getRutFile() != null), "Scan de Foto de Rut");
        $data[] = array(($user->getConfirmed()), "Email confirmado");
        $data[] = array(($user->getConfirmedFb()), "Facebook confirmado");
        $data[] = array(($user->getTelephone()), "Telefono confirmado");
        $data[] = array(($user->getPaypalId() != null), "Medios de pagos");
        $this->userdata = $data;
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
