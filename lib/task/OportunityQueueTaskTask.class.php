<?php

class OportunityQueueTask extends sfBaseTask {

    protected function configure() {

        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name'),
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
            new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'doctrine'),
            new sfCommandOption('numberOfMails', null, sfCommandOption::PARAMETER_REQUIRED, 'Cantidad de correos', 16),
        ));

        $this->namespace = 'arriendas';
        $this->name = 'oportunityQueueTask';
        $this->briefDescription = '';
        $this->detailedDescription = <<<EOF
The [OportunityQueueTask|INFO] task does things.
Call it with:

  [php symfony arriendas:OportunityQueueTask|INFO]
EOF;
    }

    protected function execute($arguments = array(), $options = array()) {

        $config = ProjectConfiguration::getApplicationConfiguration("frontend", "prod", TRUE);
        sfContext::createInstance($config);

        // initialize the database connection
        $databaseManager = new sfDatabaseManager($this->configuration);
        $conn = $databaseManager->getDatabase($options['connection'])->getConnection();

        // Este task toma todas las reservas pagas que se hayan hecho y que esten pendientes para generar
        //  la lista de usuarios a los cuales se les debe enviar correos de "oportunidades"
        //  Es necesario que la reserva tenga mas de una hora sin estar confirmada (o la transaccion, 
        //  no se bien donde se saca esa info).

        // Se obtienen todas las transacciones
        $table = Doctrine_Core::getTable('OportunityQueue');
        $q = $table
            ->createQuery('o')
            ->innerJoin('o.Reserve r')
            ->where('t.completed = 1')
            ->andWhere('r.confirmed = 0')
            ->orderBy('t.id ASC');

        $transactions = $q->execute();
    }
}