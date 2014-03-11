<?php

/**
 * ReserveTable
 *
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class ReserveTable extends Doctrine_Table {

    /**
     * Returns an instance of this class.
     *
     * @return object ReserveTable
     */
    public static function getInstance() {
        return Doctrine_Core::getTable('Reserve');
    }

    public function getInProgressByUser($user_id) {
        $q = Doctrine_Query::create()
                ->select('r.id , ca.id idcar , mo.name model,
	  br.name brand, ca.year year,
	  ca.address address, ci.name city,
	  st.name state, co.name country,
	  user.firstname firstname, user.lastname lastname,
	  r.date date'
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
                ->where('user.id = ?', $user_id)
                ->andWhere('r.confirmed = ?', true)
                ->andWhere('r.complete = ?', false);
        return $q->execute();
    }

    public function getLastByUser($user_id, $last) {
        $q = Doctrine_Query::create()
                ->select('r.id , ca.id idcar , mo.name model,
	  br.name brand, ca.year year,
	  ca.address address, ci.name city,
	  st.name state, co.name country,
	  user.firstname firstname, user.lastname lastname,
	  r.date date'
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
                ->where('user.id = ?', $user_id)
                ->andWhere('r.confirmed = ?', true)
                ->orderBy('r.complete asc');
        return $q->execute();
    }

    public function getToConfirmByUser($user_id, $last) {
        $q = Doctrine_Query::create()
                ->select('r.id , ca.id idcar , mo.name model,
	  br.name brand, ca.year year,
	  ca.address address, ci.name city,
	  st.name state, co.name country,
	  user.firstname firstname, user.lastname lastname,
	  r.date date'
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
                ->where('owner.id = ?', $user_id)
                ->andWhere('r.confirmed = ?', false)
                ->andWhere('r.canceled = ?', false);
        //->orderBy('r.complete asc');
        return $q->execute();
    }

    public function getToConfirmKmsIniciales($user_id) {
        $q = Doctrine_Query::create()
                ->select('r.id , ca.id idcar , mo.name model,
	  br.name brand, ca.year year,
	  ca.address address, ci.name city,
	  st.name state, co.name country,
	  user.firstname firstname, user.lastname lastname,
	  r.date date'
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
                ->where('owner.id = ?', $user_id)
                ->andWhere('r.confirmed = ?', true)
                ->andWhere('r.canceled = ?', false)
                ->andWhere('r.complete = ?', false)
                ->andWhere('r.kminicial != ?', 0)
                ->andWhere('r.ini_km_owner_confirmed = ?', false);

        return $q->execute();
    }

    public function getToConfirmKmsFinales($user_id) {
        $q = Doctrine_Query::create()
                ->select('r.id , ca.id idcar , mo.name model,
	  br.name brand, ca.year year,
	  ca.address address, ci.name city,
	  st.name state, co.name country,
	  user.firstname firstname, user.lastname lastname,
	  r.date date'
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
                ->where('owner.id = ?', $user_id)
                ->andWhere('r.confirmed = ?', true)
                ->andWhere('r.canceled = ?', false)
                ->andWhere('r.complete = ?', false)
                ->andWhere('r.kmfinal != ?', 0)
                ->andWhere('r.end_km_owner_confirmed = ?', false);

        return $q->execute();
    }

    public function getToConfirmKmsInicialesUser($user_id) {
        $q = Doctrine_Query::create()
                ->select('r.id , ca.id idcar , mo.name model,
	  br.name brand, ca.year year,
	  ca.address address, ci.name city,
	  st.name state, co.name country,
	  user.firstname firstname, user.lastname lastname,
	  r.date date'
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
                ->where('r.user_id = ?', $user_id)
                ->andWhere('r.confirmed = ?', true)
                ->andWhere('r.canceled = ?', false)
                ->andWhere('r.complete = ?', false)
                ->andWhere('r.kminicial != ?', 0)
                ->andWhere('r.ini_km_confirmed = ?', false);

        return $q->execute();
    }

    public function getToConfirmKmsFinalesUser($user_id) {
        $q = Doctrine_Query::create()
                ->select('r.id , ca.id idcar , mo.name model,
	  br.name brand, ca.year year,
	  ca.address address, ci.name city,
	  st.name state, co.name country,
	  user.firstname firstname, user.lastname lastname,
	  r.date date'
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
                ->where('r.user_id = ?', $user_id)
                ->andWhere('r.confirmed = ?', true)
                ->andWhere('r.canceled = ?', false)
                ->andWhere('r.complete = ?', false)
                ->andWhere('r.kmfinal != ?', 0)
                ->andWhere('r.end_km_confirmed = ?', false);

        return $q->execute();
    }

    public function countInProgressByUser($user_id) {
        $q = Doctrine_Query::create()
                ->from('Reserve r')
                ->innerJoin('r.Car ca')
                ->innerJoin('ca.Model mo')
                ->innerJoin('ca.User owner')
                ->innerJoin('r.User user')
                ->innerJoin('mo.Brand br')
                ->innerJoin('ca.City ci')
                ->innerJoin('ci.State st')
                ->innerJoin('st.Country co')
                ->where('user.id = ?', $user_id)
                ->andWhere('r.confirmed = ?', true)
                ->andWhere('r.complete = ?', false);
        return $q->count();
    }

    /**
     * Obtiene las ultimas reservas aprobada, como owner o renter.
     * @param type $userId
     * @return Reserve reserve.
     */
    public function obtenerUltimasReservasAprobadaByUserId($userId) {
        $q = self::getInstance()->createQuery("r")
                ->leftJoin('r.Car c')
                ->where('(r.user_id = ? OR c.user_id = ? )', array($userId, $userId))
                ->andWhere('(date_add(r.date, INTERVAL r.duration HOUR) > NOW())')
                ->andWhere('EXISTS (SELECT t.id FROM Transaction t WHERE r.id = t.reserve_id and t.completed = ?)', 1);
        return $q->execute();
    }

    /**
     * Cantidad de Reservas aprobadas por id de usuario.
     * @param type $id
     * @return int cantidad.
     */
    public function getCantReservasAprobadasByUserId($userId) {
        $connection = Doctrine_Manager::connection();
        $query = "SELECT COUNT(*) as cantidad FROM Reserve r ";
        $query .= "JOIN Transaction t ON t.reserve_id = r.id ";
        $query .= "LEFT JOIN Car c ON r.car_id=c.id ";
        $query .= "WHERE t.completed = 1 ";
        $query .= "AND (c.user_id = $userId OR r.user_id = $userId) ";
        $query .= "AND (date_add(r.date, INTERVAL r.duration HOUR) > NOW()) ";
        $statement = $connection->execute($query);
        $statement->execute();
        $resultset = $statement->fetch(PDO::FETCH_OBJ);
        return $resultset->cantidad;
    }

    public function getCantReservasPreaprobadasByUserId($userId) {
        $connection = Doctrine_Manager::connection();
        $query = "SELECT COUNT(*) as cantidad FROM Reserve r ";
        $query .= "JOIN Transaction t ON t.reserve_id = r.id ";
        $query .= "LEFT JOIN Car c ON r.car_id=c.id ";
        $query .= "WHERE t.completed = 0 ";
        $query .= "AND r.confirmed = 1 ";
        $query .= "AND (c.user_id = $userId OR r.user_id = $userId) ";
        $query .= "AND (date_add(r.date, INTERVAL 1 HOUR) > NOW()) ";
        $statement = $connection->execute($query);
        $statement->execute();
        $resultset = $statement->fetch(PDO::FETCH_OBJ);
        return $resultset->cantidad;
    }

    public function getCantReservasPendientesByUserId($userId) {
        $connection = Doctrine_Manager::connection();
        $query = "SELECT COUNT(*) as cantidad FROM Reserve r ";
        $query .= "LEFT JOIN Car c ON r.car_id=c.id ";
        $query .= "WHERE r.confirmed = 0 ";
        $query .= "AND (c.user_id = $userId OR r.user_id = $userId) ";
        $query .= "AND (r.date > NOW()) ";
        $statement = $connection->execute($query);
        $statement->execute();
        $resultset = $statement->fetch(PDO::FETCH_OBJ);
        return $resultset->cantidad;
    }

    /**
     * Obtiene las reservas activas del dia de la fecha, que esten pagas y listas para iniciar.
     * @param type $userId
     * @return type
     */
    public function getTodayReservesIds($userId) {
        $q = self::getInstance()->createQuery("r")
                ->where('r.user_id = ?', $userId)
                ->andWhere('DATE(r.date) = CURDATE()');
        return $q->execute();
    }

}
