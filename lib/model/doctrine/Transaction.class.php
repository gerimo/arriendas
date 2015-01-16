<?php

class Transaction extends BaseTransaction {

    public function select() {

        $numero_factura = 0;

        try {

            $q = Doctrine_Query::create()
                ->select('T.numero_factura')
                ->from('Transaction T')
                ->innerJoin('T.Reserve R')
                ->where('DATE_FORMAT(R.date, "%Y-%m-%d %H:%i") = ?', date("Y-m-d H:i", strtotime($this->getReserve()->date)))
                ->andWhere('T.numero_factura != 0');

            $numero_factura = $q->fetchArray()[0]['numero_factura'];

            $q = Doctrine_Query::create()
                ->update('Transaction T')
                ->set('T.completed', 0)
                ->set('T.numero_factura', 0)
                ->where('T.user_id = ?', $this->user_id)
                ->andWhere('DATE(T.date) = ?', date("Y-m-d", strtotime($this->date)));

            $q->execute();
        } catch (Exception $e) {
            return false;
        }

        $this->completed = true;
        $this->numero_factura = $numero_factura;
        $this->save();

        return true;
    }

    ///////////////////////////////////////////////////////

    public function getDateFormato(){

        $date = $this->getDate();
        $date = strtotime($date);
        $fechaFormato = date("d/m/y",$date);
        
        return $fechaFormato;
    }
    
    public function save(Doctrine_Connection $conn = null)  {

        $reserve = Doctrine_Core::getTable('reserve')->findOneById($this->getReserveId());  
        $car = Doctrine_Core::getTable('car')->findOneById($reserve->getCarId());   
        $user = Doctrine_Core::getTable('user')->findOneById($car->getUserId());    
        $ownerUserId=$user->getId();
            

        if ($this->getCompleted() && $this->getCustomerio() <= 0) {

            ///event to renter
            $session = curl_init();
            $customer_id = 'a_'.$this->getUserId(); // You'll want to set this dynamically to the unique id of the user
            $customerio_url = 'https://track.customer.io/api/v1/customers/'.$customer_id.'/events';
            $site_id = '3a9fdc2493ced32f26ee';
            $api_key = '4f191ca12da03c6edca4';
            sfContext::getInstance()->getLogger()->err($customerio_url);
            $data = array("name" => "reserva_paga_usuario", "data[id]" => $this->getId());

            curl_setopt($session, CURLOPT_URL, $customerio_url);
            curl_setopt($session, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
            curl_setopt($session, CURLOPT_HEADER, false);
            curl_setopt($session, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($session, CURLOPT_VERBOSE, 1);
            curl_setopt($session, CURLOPT_CUSTOMREQUEST, 'POST');
            curl_setopt($session, CURLOPT_POSTFIELDS,http_build_query($data));

            curl_setopt($session,CURLOPT_USERPWD,$site_id . ":" . $api_key);

            curl_setopt($session,CURLOPT_SSL_VERIFYPEER,false);

            curl_exec($session);
            curl_close($session);
 
            ///event to owner
            $session = curl_init();
            $customer_id = 'a_'.$ownerUserId; // You'll want to set this dynamically to the unique id of the user
            $customerio_url = 'https://track.customer.io/api/v1/customers/'.$customer_id.'/events';
            $site_id = '3a9fdc2493ced32f26ee';
            $api_key = '4f191ca12da03c6edca4';
            sfContext::getInstance()->getLogger()->err($customerio_url);
            $data = array("name" => "reserva_paga_dueno", "data[id]" => $this->getId());

            curl_setopt($session, CURLOPT_URL, $customerio_url);
            curl_setopt($session, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
            curl_setopt($session, CURLOPT_HEADER, false);
            curl_setopt($session, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($session, CURLOPT_VERBOSE, 1);
            curl_setopt($session, CURLOPT_CUSTOMREQUEST, 'POST');
            curl_setopt($session, CURLOPT_POSTFIELDS,http_build_query($data));

            curl_setopt($session,CURLOPT_USERPWD,$site_id . ":" . $api_key);

            curl_setopt($session,CURLOPT_SSL_VERIFYPEER,false);

            curl_exec($session);
            curl_close($session);
            $this->setCustomerio(true);   
        }     
      
        return parent::save($conn);
    }
}
