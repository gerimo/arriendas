<?php

class UpdateRatioAprobacionTask extends sfBaseTask {

    protected function configure() {

        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name'),
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
            new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'doctrine'),
            new sfCommandOption('offset', null, sfCommandOption::PARAMETER_REQUIRED, 'offset', 0),
        ));

        $this->namespace = 'arriendas';
        $this->name = 'updateRatioAprobacion';
        $this->briefDescription = '';
        $this->detailedDescription = <<<EOF
The [UpdateRatioAprobacionTask|INFO] task does things.
Call it with:

  [php symfony arriendas:updateRatioAprobacionTask|INFO]
EOF;
    }

    protected function execute($arguments = array(), $options = array()) {

        $config = ProjectConfiguration::getApplicationConfiguration("frontend", "prod", TRUE);
        sfContext::createInstance($config);

        // Este task debiera correr cada 10 minutos app

        // initialize the database connection
        $databaseManager = new sfDatabaseManager($this->configuration);
        $conn = $databaseManager->getDatabase($options['connection'])->getConnection();

        $offset = $options['offset'];

        // Este task toma todas las reservas pagas que se hayan hecho y que esten pendientes para generar
        //  la lista de usuarios a los cuales se les debe enviar correos de "oportunidades"

        $table = Doctrine_Core::getTable('User');
        $q = $table
            ->createQuery('u')
            ->innerJoin('u.Cars c')
            ->groupBy('u.id')
            ->orderBy('u.id ASC')
            ->offset($offset)
            ;

        $users = $q->execute();

        foreach ($users as $user) {

            $carTable = Doctrine_Core::getTable('Car');
            $carQuery = $carTable
                ->createQuery('c')
                ->where('c.user_id = ?', $user->getId());
            $cars = $carQuery->execute();

            foreach($cars as $car) {

                $car->save();
            }
        }
    }
}