<?php

class UserAvailabilityTask extends sfBaseTask {

    protected function configure() {

        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'frontend'),
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'local'),
            new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'doctrine'),
            new sfCommandOption('expectedAvailabilities', null, sfCommandOption::PARAMETER_REQUIRED, 'Minimum quantity of availabilities required for stop email sending', 30)
            /*new sfCommandOption('expectedUsers', null, sfCommandOption::PARAMETER_REQUIRED, 'Expected users confirmed', null)*/

        ));

        $this->namespace = 'user';
        $this->name = 'availability';
        $this->briefDescription = 'Envía un correo preguntando por la disponibilidad del usuario para recibir a un cliente';
        $this->detailedDescription = <<<EOF
The [UserAvailability|INFO] task does things.
Call it with:

  [php symfony user:availability|INFO]
EOF;
    }

    protected function execute($arguments = array(), $options = array()) {

        $config = ProjectConfiguration::getApplicationConfiguration("frontend", "prod", TRUE);
        sfContext::createInstance($config);

        // initialize the database connection
        $databaseManager = new sfDatabaseManager($this->configuration);
        $conn = $databaseManager->getDatabase($options['connection'])->getConnection();

        $host = 'http://local.arriendas.cl';
        if ($options['env'] == 'dev') {
            $host = 'http://test.arriendas.cl';
        } elseif ($options['env'] == 'prod') {
            $host = 'http://www.arriendas.cl';
        }

        $mailingsSended = 0;

        $week = array(
            1 => "Lunes",
            2 => "Martes",
            3 => "Miércoles",
            4 => "Jueves",
            5 => "Viernes",
            6 => "Sábado",
            7 => "Domingo"
        );

        try {

            $this->log("Chequeando...");

            if (date("N") == 6 || date("N") == 7 || Doctrine_Core::getTable("Holiday")->findOneByDate(date("Y-m-d"))) {

                $this->log("[".date("Y-m-d H:i:s")."] Es fin de semana o festivo.");

                $availabilitiesMissing = $this->getAvailabilitiesMissing(date("Y-m-d"), $options['expectedAvailabilities']);

                if ($availabilitiesMissing == 0) {
                    $this->log("[".date("Y-m-d H:i:s")."] Se cuenta con las disponibilidades esperadas. Terminado");
                    exit;
                }                
            } elseif (date("N", strtotime("+1 day")) == 6 || Doctrine_Core::getTable("Holiday")->findOneByDate(date("Y-m-d", strtotime("+1 day")))) {

                $this->log("[".date("Y-m-d H:i:s")."] Mañana es fin de semana o festivo.");

                $availabilitiesMissing = $options['expectedAvailabilities'];
            } else {
                $this->log("[".date("Y-m-d H:i:s")."] No es, ni mañana sera fin de semana o festivo. Terminado");
                exit;
            }

            $usersAlreadyNotified = $this->getNotifiedUsers(date("Y-m-d"));

            $q = Doctrine_Core::getTable("Car")
                ->createQuery('C')
                ->where('C.activo = 1')
                ->andWhere('C.seguro_ok = 4')
                ->andWhereNotIn('C.id', $usersAlreadyNotified)
                ->orderBy('C.ratio_aprobacion DESC')
                ->limit($availabilitiesMissing * 10);

            $Cars = $q->execute();

        } catch (Exception $e) {
            Utils::reportError($e->getMessage(), "task/UserAvailabilityTask");
        }
    }

    private function getAvailabilitiesMissing($day, $expectedAvailabilities) {

        $q = Doctrine_Core::getTable("CarAvailability")
            ->createQuery('CA')
            ->where('CA.day = ?', $day)
            ->andWhere('CA.is_deleted = 0');

        $CarAvailabilities = $q->execute();

        $availabilities = count($CarAvailabilities);
        $missing        = $expectedAvailabilities - $availabilities;

        if ($missing > 0) {
            return $missing;
        }

        return 0;
    }

    private function getNotifiedUsers($day) {

        $usersAlreadyNotified = array();

        $q = Doctrine_Core::getTable("User")
            ->createQuery('U')
            ->innerJoin('U.cars C')
            ->innerJoin('C.carAvailabilityEmails CAE')
            ->where('DATE(CAE.sent_at) >= ?', $day);

        $Users = $q->execute();

        if (count($Users) > 0) {
            
            foreach ($Users as $User) {
                $usersAlreadyNotified[] = $User->getId();
            }

            return $usersAlreadyNotified;
        }

        return array(0);
    }
}