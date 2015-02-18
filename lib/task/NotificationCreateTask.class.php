<?php

class NotificationCreateTask extends sfBaseTask {

    protected function configure() {

        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'frontend'),
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'local'),
            new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'doctrine')
        ));

        $this->namespace = 'notification';
        $this->name = 'create';
        $this->briefDescription = 'Crea las notificaciones correspondientes al modelo';
        $this->detailedDescription = <<<EOF
The [NotificationCreate|INFO] task does things.
Call it with:

  [php symfony notification:create|INFO]
EOF;
    }

    protected function execute($arguments = array(), $options = array()) {

        $this->log("[".date("Y-m-d H:i:s")."] Comenzando");

        // initialize the database connection
        $databaseManager = new sfDatabaseManager($this->configuration);
        $conn = $databaseManager->getDatabase($options['connection'])->getConnection();

        $host = 'http://local.arriendas.cl';
        if ($options['env'] == 'dev') {
            $host = 'http://dev.arriendas.cl';
        } elseif ($options['env'] == 'prod') {
            $host = 'http://www.arriendas.cl';
        }

        $yesterday = strtotime("-1 day");

        $oNotifications = Doctrine_Core::getTable("Notification")->findAll();

        foreach ($oNotifications as $oN) {

            // User / Auto subido
            if ($oN->getId() == 1) {

                $oCars = Doctrine_Core::getTable("Car")->findNewCars();

                if (count($oCars) == 0) {
                    $this->log("[".date("Y-m-d H:i:s")."] [User/AutoSubido] No se encontraron autos nuevos con fecha ".date("Y-m-d H:i:s", $yesterday));
                    continue;
                }

                foreach ($oCars as $oCar) {

                    $oOwner = $oCar->getUser();

                    $oUN = Doctrine_Core::getTable("UserNotification")->findLastNotification($oOwner->getId(), $oN->getId());
                    if (!$oUN) {
                        $oUN = new UserNotification;
                        $oUN->setCreatedAt(date("Y-m-d H:i:s"));
                        $oUN->setNotification($oN);
                        $oUN->setUser($oOwner);
                        $oUN->save();
                    }
                }
            }
        }
    }
}