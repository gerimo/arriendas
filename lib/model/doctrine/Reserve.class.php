<?php

/**
 * Reserve
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @package    CarSharing
 * @subpackage model
 * @author     Your name here
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
class Reserve extends BaseReserve {
    
    public function getFechaInicio2() {

        return date("Y-m-d H:i:s", strtotime($this->getDate()));
    }

    public function getFechaTermino2() {
        
        return date("Y-m-d H:i:s", strtotime("+".$this->getDuration()." hour", strtotime($this->getDate())));
    }

    public function getChangeOptions($withOriginalReserve = true) {

        $ChangeOptions = array();

        $q = Doctrine_Core::getTable("Reserve")
            ->createQuery('R')
            ->innerJoin('R.Transaction T')
            ->where('R.user_id = ?', $this->getUser()->id)
            ->andWhere('DATE_FORMAT(R.date, "%Y-%m-%d %H:%i") = ?', date("Y-m-d H:i", strtotime($this->date)))
            ->orderBy('T.completed DESC')
            ->addOrderBy('R.fecha_reserva ASC');

        $Reserves = $q->execute();

        foreach ($Reserves as $Reserve) {

            if ($Reserve->getReservaOriginal() == 0) {
                if ($withOriginalReserve) {
                    $ChangeOptions[] = $Reserve;
                }
            } elseif (!$Reserve->getCar()->hasReserve($this->getFechaInicio2(), $this->getFechaTermino2(), $this->getUserId())) {
                $ChangeOptions[] = $Reserve;
            }
        }

        $Holiday = Doctrine_Core::getTable("Holiday")->findOneByDate(date("Y-m-d"));
        if ($Holiday || date("N") == 6 || date("N") == 7) {

            /*$q = Doctrine_Core::getTable("Car")
                ->createQuery('C')
                ->innerJoin('C.CarAvailability CA')
                ->where('CA.day = ?', array($this->id, $this->id))
                ->orderBy('T.selected DESC')
                ->addOrderBy('R.fecha_reserva ASC');*/
        }

        return $ChangeOptions;
    }

    public function getRentalPrice() {

        $Car = $this->getCar();

        return Car::getPrice(
                $this->getFechaInicio2,
                $this->getFechaTermino2,
                $Car->getPricePerHour(),
                $Car->getPricePerDay(),
                $Car->getPricePerWeek(),
                $Car->getPricePerMonth()
            );
    }

    public function getSelectedCar() {

        $q = Doctrine_Core::getTable("Reserve")
            ->createQuery('R')
            ->innerJoin('R.Transaction T')
            ->where('R.user_id = ?', $this->getUser()->id)
            ->andWhere('DATE(R.date) = ?', date("Y-m-d", strtotime($this->date)))
            ->andWhere('T.completed = 1');

        return $q->fetchOne()->getCar();
    }

    public function getSignature() {

        return md5("Arriendas ~ ".$this->getDate());
    }

    // Métodos estáticos

    public static function calcularMontoLiberacionGarantia($price, $from, $to) {

        $duration = floor((strtotime($to) - strtotime($from))/3600);
        $days     = floor($duration/24);
        $hours    = $duration%24;
        
        if ($hours >= 6) {
            if ($days) {
                $total = ($days * $price) + $price;
            } else {
                $total = $price;
            }            
        } else {
            if ($days) {
                $total = ($days * $price) + (($hours/6) * $price);
            } else {
                $total = ($hours/6) * $price;
            }
        }

        return round($total);
    }

    public static function getPaidReserves($userId) {
        
        $q = Doctrine_Core::getTable("Reserve")
            ->createQuery('R')
            ->innerJoin('R.Transaction T')
            ->innerJoin('R.Car C')
            ->where('C.user_id = ?', $userId)
            /*->andWhere('R.confirmed = 0')*/
            ->andWhere('R.canceled = 0')
            ->andWhere("T.completed = 1")
            ->andWhere('NOW() < DATE_ADD(R.date, INTERVAL R.duration+4 HOUR)')
            ->addOrderBy("R.fecha_reserva ASC");

        return $q->execute();
    }

    public static function getReservesByUser($userId) {

        $q = Doctrine_Core::getTable("Reserve")
            ->createQuery('R')
            ->innerJoin('R.Transaction T')
            ->where('R.user_id = ?', $userId)
            ->andWhere("T.completed = 1")
            ->andWhere('NOW() < DATE_ADD(R.date, INTERVAL R.duration+4 HOUR)');

        return $q->execute();
    }

    //////////////////////////////////////////////////////////////////
    
    public function calcularIVA() {
        //Variable que define el IVA actual
        //TODO: modificar la obtenci�n de la variable IVA
        $iva = 1.19;
    }

    public function getFechaInicio() {
        $entrega = $this->getDate();
        $entrega = strtotime($entrega);
        $fechaEntrega = date("d/m/y", $entrega);
        return $fechaEntrega;
    }

    public function getHoraInicio() {
        $entrega = $this->getDate();
        $entrega = strtotime($entrega);
        $horaEntrega = date("H:i", $entrega);
        return $horaEntrega;
    }

    public function getFechaTermino() {
        $entrega = $this->getDate();
        $duracion = $this->getDuration();
        $entrega = strtotime($entrega);
        $fechaDevolucion = date("d/m/y", $entrega + ($duracion * 60 * 60));
        return $fechaDevolucion;
    }

    public function getHoraTermino() {
        $entrega = $this->getDate();
        $duracion = $this->getDuration();
        $entrega = strtotime($entrega);
        $horaDevolucion = date("H:i", $entrega + ($duracion * 60 * 60));
        return $horaDevolucion;
    }

    public function getTiempoArriendoTexto() {
        $duracion = $this->getDuration();

        $dias = floor($duracion / 24);
        $horas = $duracion % 24;

        //echo $dias." ".$horas."<br>";

        if ($dias == 0) {
            $dias = "";
        } elseif ($dias == 1) {
            $dias = $dias . " día";
        } else {
            $dias = $dias . " días";
        }
        //$dias = mb_convert_encoding($dias, 'utf-8');

        if ($horas == 0) {
            $horas = "";
        } elseif ($horas == 1) {
            $horas = $horas . " hora";
        } else {
            $horas = $horas . " horas";
        }

        if ($dias == "") {
            return $horas;
        }
        if ($horas == "") {
            return $dias;
        }

        return $dias . " y " . $horas;
    }

    //Metodo utilizado para crear un registro de calificacion dentro de nuestra tabla, que se habilitar�
    //una vez que la reserva sea pagada
    public function habilitarCalificacion() {
        //Obtenemos la fecha de la reserva y su duraci�n para determinar la fecha desde que estar� habilitada
        //la calificacion. La regla es que esta estar� disponible 2 horas tras el termino de la reserva
        $fecha_reserva = $this->getDate();
        $duracion = $this->getDuration();
        //Convertimos la fecha a timestamp
        $timestamp = strtotime($fecha_reserva);
        $timestamp = 60 * 60 * ($duracion + 2);
        $rating = new Rating();
        $rating->setIdRenter($this->getUserId());
        //Obtenemos el ID del propietario
        $car = Doctrine_Core::getTable("car")->findOneById($this->getCarId());
        $rating->setIdOwner($car->getUserId());
        $rating->setFechaHabilitadaDesde($this->formatearHoraChilena(strftime("%Y-%m-%d %H:%M:%S")), $timestamp);
        $rating->save();
        $this->setRatingId($rating->getId());
        $this->save();
    }

    public function formatearHoraChilena($fecha) {
        $horaChilena = strftime("%Y-%m-%d %H:%M:%S", strtotime('-4 hours', strtotime($fecha)));
        return $horaChilena;
    }

    public function encolarMailCalificaciones() {
        $mailCalificaciones = new MailCalificaciones();
        $mailCalificaciones->setReserveId($this->getId());
        $mailCalificaciones->setDate($this->getFechaHabilitacionRating());
        $mailCalificaciones->save();
    }

    //si es el que arrienda: fecha_pago - fecha_confirmacion
    public function getTiempoRespuestaArrendador() {

        $q = "SELECT fecha_confirmacion, fecha_pago FROM reserve WHERE id=" . $this->getId();
        $query = Doctrine_Query::create()->query($q);
        $autos = $query->toArray();

        $fechaPago = $autos[0]['fecha_pago'];
        $fechaConfirmacion = $autos[0]['fecha_confirmacion'];

        if ($fechaPago == null || $fechaConfirmacion == null) {
            return 0;
        }

        $fechaPago = strtotime($fechaPago);
        $fechaConfirmacion = strtotime($fechaConfirmacion);

        //echo $fechaPago - $fechaConfirmacion;
        //tiempo en minutos
        $duracion = $fechaPago - $fechaConfirmacion;

        return $duracion;
    }

    //si el al que le arriendan: fecha_confirmacion - fecha_reserva
    public function getTiempoRespuestaArrendatario($idCar) {

        $q = "SELECT fecha_confirmacion, fecha_reserva FROM reserve WHERE car_id=" . $idCar;
        $query = Doctrine_Query::create()->query($q);
        $autos = $query->toArray();

        $duracion[0] = 0;

        for ($i = 0; $i < count($autos); $i++) {
            $fechaReseva = $autos[0]['fecha_reserva'];
            $fechaConfirmacion = $autos[0]['fecha_confirmacion'];

            if ($fechaReseva == null || $fechaConfirmacion == null) {
                return 0;
            }

            $fechaReseva = strtotime($fechaReseva);
            $fechaConfirmacion = strtotime($fechaConfirmacion);

            $duracion[$i] = $fechaConfirmacion - $fechaReseva;
        }

        return $duracion;
    }

    public function getMarcaModelo() {
        $car = Doctrine_Core::getTable('car')->findOneById($this->getCarId());
        return $car->getMarcaModelo();
    }

    public function getEmailRenter() {
        $user = Doctrine_Core::getTable('user')->findOneById($this->getUserId());
        return $user->getUsername();
    }

    public function getCorreoRenter() {
        $user = Doctrine_Core::getTable('user')->findOneById($this->getUserId());
        return $user->getEmail();
    }

    public function getTypeCar() {
        $car = Doctrine_Core::getTable('car')->findOneById($this->getCarId());
        return $car->getUsoVehiculoId();
    }

    public function getPricePerDayCar() {
        $car = Doctrine_Core::getTable('car')->findOneById($this->getCarId());
        return $car->getPricePerDay();
    }

    public function getNameRenter() {
        $user = Doctrine_Core::getTable('user')->findOneById($this->getUserId());
        return $user->getFirstname();
    }

    public function getLastNameRenter() {
        $user = Doctrine_Core::getTable('user')->findOneById($this->getUserId());
        return $user->getLastname();
    }

    public function getEmailOwner() {
        $q = "SELECT u.username FROM user u, car c, reserve r WHERE u.id=c.user_id and c.id=r.car_id and r.id=" . $this->getId();
        $query = Doctrine_Query::create()->query($q);
        $owner = $query->toArray();
        return $owner[0]['username'];
    }

    public function getEmailOwnerCorreo() {
        $q = "SELECT u.email FROM user u, car c, reserve r WHERE u.id=c.user_id and c.id=r.car_id and r.id=" . $this->getId();
        $query = Doctrine_Query::create()->query($q);
        $owner = $query->toArray();
        return $owner[0]['email'];
    }

    public function getNameOwner() {
        $q = "SELECT u.firstname, u.lastname FROM user u, car c, reserve r WHERE u.id=c.user_id and c.id=r.car_id and r.id=" . $this->getId();
        $query = Doctrine_Query::create()->query($q);
        $owner = $query->toArray();
        return $owner[0]['firstname'];
    }

    public function getLastnameOwner() {
        $q = "SELECT u.firstname, u.lastname FROM user u, car c, reserve r WHERE u.id=c.user_id and c.id=r.car_id and r.id=" . $this->getId();
        $query = Doctrine_Query::create()->query($q);
        $owner = $query->toArray();
        return $owner[0]['lastname'];
    }

    public function getRutOwner() {
        $q = "SELECT u.rut FROM user u, car c, reserve r WHERE u.id=c.user_id and c.id=r.car_id and r.id=" . $this->getId();
        $query = Doctrine_Query::create()->query($q);
        $owner = $query->toArray();
        return $owner[0]['rut'];
    }

    public function getAddressCar() {
        $car = Doctrine_Core::getTable('car')->findOneById($this->getCarId());
        return $car->getAddress();
    }

    public function getOwner() {
        $car    = Doctrine_Core::getTable('car')->findOneById($this->getCarId());
        $owner  = Doctrine_Core::getTable('user')->findOneById($car->getUserId());
        return $owner;
    }

    public function getRenter() {
        $user = Doctrine_Core::getTable('user')->findOneById($this->getUserId());
        return $user;
    }

    public function getTelephoneRenter() {
        $user = Doctrine_Core::getTable('user')->findOneById($this->getUserId());
        return $user->getTelephone();
    }

    public function getConfirmedSMSRenter() {
        $user = Doctrine_Core::getTable('user')->findOneById($this->getUserId());
        return $user->getConfirmedSms();
    }

    public function getTelephoneOwner() {
        $q = "SELECT u.telephone FROM user u, car c, reserve r WHERE u.id=c.user_id and c.id=r.car_id and r.id=" . $this->getId();
        $query = Doctrine_Query::create()->query($q);
        $owner = $query->toArray();
        return $owner[0]['telephone'];
    }

    public function getConfirmedSMSOwner() {
        $q = "SELECT u.confirmed_sms FROM user u, car c, reserve r WHERE u.id=c.user_id and c.id=r.car_id and r.id=" . $this->getId();
        $query = Doctrine_Query::create()->query($q);
        $owner = $query->toArray();
        return $owner[0]['confirmed_sms'];
    }

    public function getIdOwner() {
        $q = "SELECT u.id FROM user u, car c, reserve r WHERE u.id=c.user_id and c.id=r.car_id and r.id=" . $this->getId();
        $query = Doctrine_Query::create()->query($q);
        $owner = $query->toArray();
        return $owner[0]['id'];
    }

    public function getIdRenter() {
        $user = Doctrine_Core::getTable('user')->findOneById($this->getUserId());
        return $user->getId();
    }

    public function getFechaHabilitacionRating() {
        $entrega = $this->getDate();
        $duracion = $this->getDuration();
        $entrega = strtotime($entrega) + ($duracion + 2) * 60 * 60;
        return date("Y-m-d H:i:s", $entrega);
    }

    /**
     * Check if the reserve is ready for initialize.
     * @return boolean isReady.
     */
    public function isReadyForInitialize() {
        $isReady = TRUE;
        $transaction = $this->getTransaction();
        if (is_null($transaction)) {
            $isReady = FALSE;
        } elseif (date("Y-m-d", strtotime ($this->getDate())) != date("Y-m-d")) {
            $isReady = FALSE;
        } elseif (!$transaction->getCompleted()) {
            $isReady = FALSE;
        }

        return $isReady;
    }
    
    /**
     * Check if the reserve is ready for finalize.
     * @return boolean isReady.
     */
    public function isReadyForFinalize() {
        $isReady = TRUE;
        
        if (!$this->getInicioArriendoOk()) {
            $isReady = FALSE;
        }

        return $isReady;
    }

    public function save(Doctrine_Connection $conn = null) {


        $car = Doctrine_Core::getTable('car')->findOneById($this->getCarId());
        $user = Doctrine_Core::getTable('user')->findOneById($car->getUserId());
        $ownerUserId = $user->getId();



        $this->setUniqueToken();



        if (!$this->getId()) {
            //event to renter
            $session = curl_init();
            $customer_id = 'a_' . $this->getUserId(); // You'll want to set this dynamically to the unique id of the user
            $customerio_url = 'https://track.customer.io/api/v1/customers/' . $customer_id . '/events';
            $site_id = '3a9fdc2493ced32f26ee';
            $api_key = '4f191ca12da03c6edca4';
            sfContext::getInstance()->getLogger()->err($customerio_url);
            $data = array("name" => "hizo_pedido_reserva", "data[auto]" => $this->getCarId(), "data[price]" => $this->getPrice(), "data[id]" => $this->getId());

            curl_setopt($session, CURLOPT_URL, $customerio_url);
            curl_setopt($session, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
            curl_setopt($session, CURLOPT_HEADER, false);
            curl_setopt($session, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($session, CURLOPT_VERBOSE, 1);
            curl_setopt($session, CURLOPT_CUSTOMREQUEST, 'POST');
            curl_setopt($session, CURLOPT_POSTFIELDS, http_build_query($data));

            curl_setopt($session, CURLOPT_USERPWD, $site_id . ":" . $api_key);

            curl_setopt($session, CURLOPT_SSL_VERIFYPEER, false);

            curl_exec($session);
            curl_close($session);

            ///event to owner
            $session = curl_init();
            $customer_id = 'a_' . $ownerUserId; // You'll want to set this dynamically to the unique id of the user
            $customerio_url = 'https://track.customer.io/api/v1/customers/' . $customer_id . '/events';
            $site_id = '3a9fdc2493ced32f26ee';
            $api_key = '4f191ca12da03c6edca4';
            sfContext::getInstance()->getLogger()->err($customerio_url);
            $data = array("name" => "recibio_pedido_reserva", "data[auto]" => $this->getCarId(), "data[price]" => $this->getPrice(), "data[id]" => $this->getId());

            curl_setopt($session, CURLOPT_URL, $customerio_url);
            curl_setopt($session, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
            curl_setopt($session, CURLOPT_HEADER, false);
            curl_setopt($session, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($session, CURLOPT_VERBOSE, 1);
            curl_setopt($session, CURLOPT_CUSTOMREQUEST, 'POST');
            curl_setopt($session, CURLOPT_POSTFIELDS, http_build_query($data));

            curl_setopt($session, CURLOPT_USERPWD, $site_id . ":" . $api_key);

            curl_setopt($session, CURLOPT_SSL_VERIFYPEER, false);

            curl_exec($session);
            curl_close($session);

//						$this->setCustomerio(true);
        }

        if ($this->getCustomerio() <= 0) {

            if ($this->getConfirmed()) {

                ///event to renter
                $session = curl_init();
                $customer_id = 'a_' . $this->getUserId(); // You'll want to set this dynamically to the unique id of the user
                $customerio_url = 'https://track.customer.io/api/v1/customers/' . $customer_id . '/events';
                $site_id = '3a9fdc2493ced32f26ee';
                $api_key = '4f191ca12da03c6edca4';
                sfContext::getInstance()->getLogger()->err($customerio_url);
                $data = array("name" => "pedido_reserva_aprovado", "data[auto]" => $this->getCarId(), "data[price]" => $this->getPrice(), "data[id]" => $this->getId());

                curl_setopt($session, CURLOPT_URL, $customerio_url);
                curl_setopt($session, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
                curl_setopt($session, CURLOPT_HEADER, false);
                curl_setopt($session, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($session, CURLOPT_VERBOSE, 1);
                curl_setopt($session, CURLOPT_CUSTOMREQUEST, 'POST');
                curl_setopt($session, CURLOPT_POSTFIELDS, http_build_query($data));

                curl_setopt($session, CURLOPT_USERPWD, $site_id . ":" . $api_key);

                curl_setopt($session, CURLOPT_SSL_VERIFYPEER, false);

                curl_exec($session);
                curl_close($session);

                ///event to owner
                $session = curl_init();
                $customer_id = 'a_' . $ownerUserId; // You'll want to set this dynamically to the unique id of the user
                $customerio_url = 'https://track.customer.io/api/v1/customers/' . $customer_id . '/events';
                $site_id = '3a9fdc2493ced32f26ee';
                $api_key = '4f191ca12da03c6edca4';
                sfContext::getInstance()->getLogger()->err($customerio_url);
                $data = array("name" => "aprobo_pedido_reserva", "data[auto]" => $this->getCarId(), "data[price]" => $this->getPrice(), "data[id]" => $this->getId(), "data[cambioEstadoRapido]" => $this->getCambioEstadoRapido());

                curl_setopt($session, CURLOPT_URL, $customerio_url);
                curl_setopt($session, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
                curl_setopt($session, CURLOPT_HEADER, false);
                curl_setopt($session, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($session, CURLOPT_VERBOSE, 1);
                curl_setopt($session, CURLOPT_CUSTOMREQUEST, 'POST');
                curl_setopt($session, CURLOPT_POSTFIELDS, http_build_query($data));

                curl_setopt($session, CURLOPT_USERPWD, $site_id . ":" . $api_key);

                curl_setopt($session, CURLOPT_SSL_VERIFYPEER, false);

                curl_exec($session);
                curl_close($session);
                $this->setCustomerio(true);
            }

            if ($this->getCanceled()) {

                ///event to renter
                $session = curl_init();
                $customer_id = 'a_' . $this->getUserId(); // You'll want to set this dynamically to the unique id of the user
                $customerio_url = 'https://track.customer.io/api/v1/customers/' . $customer_id . '/events';
                $site_id = '3a9fdc2493ced32f26ee';
                $api_key = '4f191ca12da03c6edca4';
                sfContext::getInstance()->getLogger()->err($customerio_url);
                $data = array("name" => "pedido_reserva_rechazado", "data[auto]" => $this->getCarId(), "data[price]" => $this->getPrice(), "data[id]" => $this->getId());

                curl_setopt($session, CURLOPT_URL, $customerio_url);
                curl_setopt($session, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
                curl_setopt($session, CURLOPT_HEADER, false);
                curl_setopt($session, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($session, CURLOPT_VERBOSE, 1);
                curl_setopt($session, CURLOPT_CUSTOMREQUEST, 'POST');
                curl_setopt($session, CURLOPT_POSTFIELDS, http_build_query($data));

                curl_setopt($session, CURLOPT_USERPWD, $site_id . ":" . $api_key);

                curl_setopt($session, CURLOPT_SSL_VERIFYPEER, false);

                curl_exec($session);
                curl_close($session);

                ///event to owner
                $session = curl_init();
                $customer_id = 'a_' . $ownerUserId; // You'll want to set this dynamically to the unique id of the user
                $customerio_url = 'https://track.customer.io/api/v1/customers/' . $customer_id . '/events';
                $site_id = '3a9fdc2493ced32f26ee';
                $api_key = '4f191ca12da03c6edca4';
                sfContext::getInstance()->getLogger()->err($customerio_url);
                $data = array("name" => "rechazo_pedido_reserva", "data[auto]" => $this->getCarId(), "data[price]" => $this->getPrice(), "data[id]" => $this->getId());

                curl_setopt($session, CURLOPT_URL, $customerio_url);
                curl_setopt($session, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
                curl_setopt($session, CURLOPT_HEADER, false);
                curl_setopt($session, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($session, CURLOPT_VERBOSE, 1);
                curl_setopt($session, CURLOPT_CUSTOMREQUEST, 'POST');
                curl_setopt($session, CURLOPT_POSTFIELDS, http_build_query($data));

                curl_setopt($session, CURLOPT_USERPWD, $site_id . ":" . $api_key);

                curl_setopt($session, CURLOPT_SSL_VERIFYPEER, false);

                curl_exec($session);
                curl_close($session);
                $this->setCustomerio(true);
            }
        }

//	  return parent::save($conn);

        $ret = parent::save($conn);


        $percTotalContestadas = $user->getPercReservasContestadas();
        $velocidadContestaPedidos = $user->getVelocidadRespuesta('0');
        $CantReservasAprobadas = $user->getCantReservasAprobadasTotalOwner();
        $q = Doctrine_Manager::getInstance()->getCurrentConnection();
        $query = "update Car set Cant_Reservas_Aprobadas= '$CantReservasAprobadas', contesta_pedidos='$percTotalContestadas', velocidad_contesta_pedidos='$velocidadContestaPedidos' where user_id='$ownerUserId'";
        $result = $q->execute($query);

        /* nuevo flujo */

        // Se obtienen todos los autos del dueño
        //  y se llama al metodo save, ya que internamente el save de Car genera el calculo
        //  del ratio de aprobacion (ver: Car.class.php#save)
        $table = Doctrine_Core::getTable('Car');
        $q = $table
            ->createQuery('c')
            ->where('c.user_id = ?', $ownerUserId)
            ;

        $cars = $q->execute();
        foreach($cars as $car) {

            $car->save();
        }

        return $ret;
    }
    
    private function setUniqueToken($try=0){
        
        if (!$this->getToken()) {
            $token = sha1($this->getDuration() . rand(11111, 99999));
            $res = Doctrine_Core::getTable('Reserve')->findOneBy('token', $token);
            if(!$res){
                $this->setToken($token);
            }else{
                // ejecuto hasta 10 intentos
                if($try<10){
                    $try++; 
                    $this->setUniqueToken($try);
                }else{
                    throw new Exception("No se pudo generar el token para la reserva.");
                }
            }
        }
    }

    
}
