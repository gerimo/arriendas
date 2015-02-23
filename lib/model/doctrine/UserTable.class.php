    <?php

class UserTable extends Doctrine_Table {


    public function findUserControl($limit = false) {

        $q = Doctrine_Core::getTable("User")
            ->createQuery('U')
            ->orderBy('U.ID DESC');
            
        if ($limit) {
            $q->limit($limit);
        }

        return $q->execute();
    }
    
    public function findUsersWithPay($from = false, $to = false, $userId) {
        $q = Doctrine_Core::getTable("Reserve")
            ->createQuery('R')
            ->Where('R.user_id = ?', $userId)
            ->andWhere('R.fecha_pago IS NOT NULL')
            ->andWhere('DATE(R.fecha_pago) BETWEEN ? AND ?', array($from, $to));


        $User= $q->fetchOne();

        if ($User) {
            return true;
        }
        return false;
    }

    public function findUsersWithoutPay($from = false, $to = false) {

        $Users = array();

        $q = Doctrine_Core::getTable("Reserve")
            ->createQuery('R')
            ->where('R.fecha_pago IS NULL')
            ->andWhere('R.reserva_original = 0')
            ->groupBy('R.user_id')
            ->orderBy('R.fecha_reserva ASC');

        if ($from && $to) {
            $q->andWhere('DATE(R.fecha_reserva) >= ?', $from);
            $q->andWhere('DATE(R.fecha_reserva) <= ?', $to);
        }

        $Reserves = $q->execute();

        foreach ($Reserves as $Reserve) {
            
            if (!$this->findUsersWithPay($from, $to, $Reserve->getUserId())) {

                $Users[]  = $Reserve;
            }
        }

        return $Users;
    }

    public function findUsersWithoutCar($from = false, $to = false) {

        $q = Doctrine_Core::getTable("User")
            ->createQuery('U')
            ->leftJoin('U.Cars C')
            ->Where('U.propietario = 1')
            ->andWhere('C.id is null')
            ->groupBy('U.id');

        if ($from && $to) {
            $q->andWhere('DATE(U.fecha_registro) >= ?', $from);
            $q->andWhere('DATE(U.fecha_registro) <= ?', $to);
        }        

        return $q->execute();
    }

    //////////////////////////////////

    /**
     * Returns an instance of this class.
     *
     * @return object UserTable
     */
    public static function getInstance() {
        return Doctrine_Core::getTable('User');
    }

    /**
     * Check if the ip corresponds to a blocked user.
     * @param type $ip_number
     * @return boolean
     */
    public function isABlockedIp($ip_number) {
        $isABlockedIp = false;
        $q = $this->getInstance()->createQuery("u")
                ->where('u.blocked = ?', 1);
        $bloquedUsers = $q->execute();
        foreach ($bloquedUsers as $bloquedUser) {
            $trackedIps = explode(";", $bloquedUser->getTrackedIps());
            foreach ($trackedIps as $ip_bloqued) {
                if ($ip_bloqued == $ip_number) {
                    $isABlockedIp = true;
                    break;
                }
            }
        }

        return $isABlockedIp;
    }

    /*
     * Get ip corresponds to a propietario user.
     * @param type $ip_number
     * @return User
     */

    public function getPropietarioByIp($ip_number, $is_propietario = false) {
        $q = $this->getInstance()->createQuery("u")
                ->where("u.tracked_ips LIKE ?", "%$ip_number%")
                ->andWhere("u.propietario = ?", $is_propietario);
        return $q->execute();
    }

}
