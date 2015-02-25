<?php

class notificationTestTask extends sfBaseTask {

    protected function configure() {

        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'frontend'),
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'local'),
            new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'doctrine'),
            new sfCommandOption('userId', null, sfCommandOption::PARAMETER_REQUIRED, 'The user id', ''),
            new sfCommandOption('actionId', null, sfCommandOption::PARAMETER_REQUIRED, 'The user rut', ''),
        ));

        $this->namespace = 'notification';
        $this->name = 'test';
        $this->briefDescription = '';
        $this->detailedDescription = <<<EOF
The [test|INFO] task does things.
Call it with:

  [php symfony notification:test|INFO]
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

        /* arguments */
        $userId = $options["userId"];
        $actionId = $options["actionId"];

        notification::make($userId, $actionId);

        echo " \n";
        $this->log('done. '.$causa);
    }

}
