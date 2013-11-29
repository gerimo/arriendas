<?php

class profileComponents extends sfComponents {


    public function executeCredito() {
	$usuario= Doctrine_Core::getTable('user')->findOneById($this->getUser()->getAttribute("userid"));
	$this->credito= $usuario->getCredito();
    }

    public function executeAlerta(){
        //obtener la próxima reserva pagada y obtener idReserve y fecha
        $user = Doctrine_Core::getTable('user')->find(array($this->getUser()->getAttribute("userid")));

        if($user && $user->getCantReservasAprobadas()){
            $reserva =  $user->obtenerUltimaReservaAprobada();
            //var_dump($reserva);
            //die();

            $dias = array("Domingo","Lunes","Martes","Miercoles","Jueves","Viernes","Sábado");
            $meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");

            $fecha = strtotime($reserva['date']);
            $reserva['date'] = $dias[date('w',$fecha)]." ".date('d',$fecha)." de ".$meses[date('n',$fecha)-1];
//            $reserva['date'] = $reserva
            $this->reserva = $reserva;
        }else{
            $this->reserva = null;
        }
    }

    public function executePublico(){

    }

    public function executeColDer(){
        
    }

    public function executeProfile() {
        //nombre de usuario
        $user = Doctrine_Core::getTable('user')->find(array($this->getUser()->getAttribute("userid")));
        $this->user = $user;

        //id del usuario
        $idUsuario = sfContext::getInstance()->getUser()->getAttribute('userid');

        //obtiene cantidad de mensajes no leidos        
        $claseUsuario = Doctrine_Core::getTable('user')->findOneById($idUsuario);
        
        $this->cantMensajes = $claseUsuario->getCantidadMensajes();

        //obtiene la cantidad de pedidos de reserva
        $this->cantPedidos = $claseUsuario->getCantidadPedidosReserva();

        //obtiene la cantidad de calificaciones pendientes
        $this->cantCalificaciones = $claseUsuario->getCantidadCalificaciones();

        //verificación de los autos
        $this->verificacionAutos = $claseUsuario->getModeloAutoYVerificado();

        //telefono confirmado
        $this->telefonoConfirmado = $claseUsuario->getTelefonoConfirmado();
        if($this->telefonoConfirmado){
            $this->telefono = $claseUsuario->getTelefono();
        }else{
            $this->telefono = null;
        }
        
        //facebook confirmado
        $this->facebookConfirmado = $claseUsuario->getFacebookConfirmado();

        //email confirmado
        $this->emailConfirmado = $claseUsuario->getEmailConfirmado();
        if($this->emailConfirmado){
            $this->email = $claseUsuario->getEmail();
        }

    }

    public function executePreguntasFrecuentes(){
    }

    public function executeAutos(){
        $this->user = Doctrine_Core::getTable('user')->find(array($this->getUser()->getAttribute("userid")));
        $autos = $this->user->getCars();
        $cars = null;
        foreach ($autos as $auto) {
            if($auto->getActivo()){
                $cars[] = $auto;
            }
        }
        $this->cars = $cars;
    }



    public function executeUserinfo() {
        /* $c = new Criteria();
          $c->addDescendingOrderByColumn(NewsPeer::PUBLISHED_AT);
          $c->setLimit(5);
          $this->news = NewsPeer::doSelect($c);
         */


        //$plantilla = Doctrine_Core::getTable('user')->find(array($this->getUser()->getAttribute("userid")));


        $user = Doctrine_Core::getTable('user')->find(array($this->getUser()->getAttribute("userid")));

        //$data[] = array(false, "Scan de documento");
        $data[] = array(($user->getDriverLicenseFile() != null), "Scan de licencia");
	//$data[] = array(($user->getRutFile() != null), "Scan de Foto de Rut");
        $data[] = array(($user->getConfirmed()), "Email confirmado");
        $data[] = array(($user->getConfirmedFb()), "Facebook confirmado");
        $data[] = array(($user->getConfirmedSms()), "Telefono confirmado");
        $data[] = array(($user->getFriendInvite()), "Invita a tus amigos");

        //$data[] = array( false , "Identidad verificada");
       // $data[] = array(($user->getPaypalId() != null), "Medios de pagos");

        $this->userdata = $data;
        $this->user = $user;
    }

    public function executeRightsidebar() {
        
    }

    public function executePictureFile() {
//        $this->params = (!$this->params ) ? "" : $this->params;
		
		$toStringParams = explode(" ", $this->params);

		 preg_match( '/(?<==).*?(?=p)/', $toStringParams[0],$width );
		preg_match( '/(?<==).*?(?=p)/', $toStringParams[1],$height);

		
		$this->width=$width[0];
		$this->height=$height[0];
		
    }

    public function executeMyreserves() {
        try {

            $q = Doctrine_Query::create()
                    ->select('r.id , ca.id idcar , mo.name model, 
	  br.name brand, ca.year year, 
          ca.price_per_hour pricehour, ca.price_per_day priceday,
	  ca.address address, ci.name city, 
	  st.name state, co.name country, 
	  user.firstname firstname, user.lastname lastname,
	  r.date date, r.duration, DATE_ADD(r.date,INTERVAL r.duration HOUR) dateend '
                    )
                    ->from('Reserve r')
                    ->innerJoin('r.Car ca')
                    ->innerJoin('ca.Model mo')
                    ->innerJoin('ca.User owner')
                    ->innerJoin('r.User user')
                    ->innerJoin('mo.Brand br')
                    ->innerJoin('ca.City ci')
                    ->innerJoin('ci.State st')
                    ->innerJoin('st.Country co')
                    ->where('r.user_id = ?', $this->getUser()->getAttribute("userid"))
                    ->andWhere('r.confirmed = ?', true)
                    ->andWhere('r.complete = ?', false);

            $reservas = $q->execute();

            $cantreservas = count($reservas);

            $reservasVisibles;

            if ($cantreservas > 3) {

                for ($i = 0; $i < 3; $i++) {

                    $inicioUnix = strtotime($reservas[$i]["date"]);

                    $duracion = $reservas[$i]["duration"] * 60 * 60;

                    $finUnix = $inicioUnix + $duracion;

                    $entrega = date('Y-m-d H:i:s', $finUnix);

                    $reservasVisibles[$i] = $reservas[$i];
                }

                $this->reservasVisibles = $reservasVisibles;
            } else {
                $this->reservasVisibles = $reservas;
            }

            $this->cantidadReservas = $cantreservas;
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    public function executeNewsfeed() {

		//Datos faltantes perfil
        $user = Doctrine_Core::getTable('user')->find(array($this->getUser()->getAttribute("userid")));

        //$data[] = array(false, "Scan de documento");
        $data[] = array(($user->getDriverLicenseFile() != null), "Scan de licencia");
	//$data[] = array(($user->getRutFile() != null), "Scan de Foto de Rut");
        $data[] = array(($user->getConfirmed()), "Email confirmado");
        $data[] = array(($user->getConfirmedFb()), "Facebook confirmado");
        $data[] = array(($user->getConfirmedSms()), "Telefono confirmado");
        $data[] = array(($user->getFriendInvite()), "Invita a tus amigos");

        //$data[] = array( false , "Identidad verificada");
        //$data[] = array(($user->getPaypalId() != null), "Medios de pagos");

        $this->userdata = $data;
		
		//Mensajes nuevos
		$this->messages  = Doctrine_Core::getTable("Message")->findOpenToUserId($this->getUser()->getAttribute("userid"));
		
		//Reservas por confirmar
		$q = Doctrine_Query::create()
                    ->select('r.id , ca.id idcar , mo.name model, 
	  br.name brand, ca.year year, 
	  ca.address address, ci.name city, 
	  st.name state, co.name country, 
	  user.firstname firstname, user.lastname lastname,
	  r.date date, ADDDATE(r.date, INTERVAL r.duration HOUR) fechafin '
                    )
                    ->from('Reserve r')
                    ->innerJoin('r.Car ca')
                    ->innerJoin('ca.Model mo')
                    ->innerJoin('ca.User owner')
                    ->innerJoin('r.User user')
                    ->innerJoin('mo.Brand br')
                    ->innerJoin('ca.City ci')
                    ->innerJoin('ci.State st')
                    ->innerJoin('st.Country co')
                    ->where('owner.id = ?', $this->getUser()->getAttribute("userid"))
                    ->andWhere('r.confirmed = ?', false)
                    ->andWhere('r.complete = ?', false)
                    ->andWhere('r.canceled = ?', false);
		$this->toconfirm = $q->execute();
    }

    public function executeUserdocsvalidation() {
        $user = Doctrine_Core::getTable('user')->find(array($this->getUser()->getAttribute("userid")));

        //$data[] = array(false, "Scan de documento");
        $data[] = array(($user->getDriverLicenseFile() != null), "Scan de licencia");
	//$data[] = array(($user->getRutFile() != null), "Scan de Foto de Rut");
        $data[] = array(($user->getConfirmed()), "Email confirmado");
        $data[] = array(($user->getConfirmedFb()), "Facebook confirmado");
        $data[] = array(($user->getConfirmedSms()), "Telefono confirmado");
        $data[] = array(($user->getFriendInvite()), "Invita a tus amigos");

        //$data[] = array( false , "Identidad verificada");
      //  $data[] = array(($user->getPaypalId() != null), "Medios de pagos");

        $this->userdata = $data;
    }

	public function executeTerminosycondiciones() {}

}
