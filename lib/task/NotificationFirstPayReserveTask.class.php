<?php

class NotificationFirstPayReserveTask extends sfBaseTask {

    protected function configure() {

        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'frontend'),
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'local'),
            new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'doctrine')
        ));

        $this->namespace = 'notification';
        $this->name = 'FirstPayReserve';
        $this->briefDescription = 'Busca a los usuario que hayan registrado una reserva sin haberla pagado';
        $this->detailedDescription = <<<EOF
The [FirstPayReserve|INFO] task does things.
Call it with:

  [php symfony notification:FirstPayReserve|INFO]
EOF;
    }



    protected function execute($arguments = array(), $options = array()) {

        $config = ProjectConfiguration::getApplicationConfiguration("frontend", "prod", TRUE);
        sfContext::createInstance($config);
        $context = sfContext::createInstance($this->configuration);
        $context->getConfiguration()->loadHelpers('Partial');

        // initialize the database connection
        $databaseManager = new sfDatabaseManager($this->configuration);
        $connection = $databaseManager->getDatabase($options['connection'])->getConnection();

        $Users = Doctrine_core::getTable("user")->findAll();

        foreach ($Users as $User) {
            if(Reserve::IsFirstReserve($User->id)){
                Notification::make($User->id, 3);
            }
        }
        
    }
}