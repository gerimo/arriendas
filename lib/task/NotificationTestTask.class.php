<?php

class notificationTestTask extends sfBaseTask {

    protected function configure() {

        $this->addArguments(array(
            new sfCommandArgument('userId', sfCommandArgument::REQUIRED, 'ID del usuario'),
            new sfCommandArgument('actionId', sfCommandArgument::REQUIRED, 'ID de la acción a notificar')
        ));

        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'frontend'),
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'local'),
            new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'doctrine')
        ));

        $this->namespace = 'notification';
        $this->name = 'test';
        $this->briefDescription = '';
        $this->detailedDescription = <<<EOF
The [test|INFO] task does things.
Call it with:

  [php symfony notification:test userId actionId|INFO]
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

        $userId = $arguments["userId"];
        $actionId = $arguments["actionId"];

        try {
            notification::make($userId, $actionId);
            $this->log('Accion notificada');
        } catch (Exception $e) {
            $this->log('Hubo un problema: '.$e->getMessage());
        }
    }
}
