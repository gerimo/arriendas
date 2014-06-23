<?php

class diffDeAutosTask extends sfBaseTask {

    protected function configure() {
        // // add your own arguments here
        // $this->addArguments(array(
        //   new sfCommandArgument('my_arg', sfCommandArgument::REQUIRED, 'My argument'),
        // ));

        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name'),
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
            new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'doctrine'),
                // add your own options here
        ));

        $this->namespace = 'magnetico';
        $this->name = 'diffDeAutos';
        $this->briefDescription = '';
        $this->detailedDescription = <<<EOF
The [diffDeAutos|INFO] task does things.
Call it with:

  [php symfony diffDeAutos|INFO]
EOF;
    }

    protected function execute($arguments = array(), $options = array()) {

        // initialize the database connection
        $databaseManager = new sfDatabaseManager($this->configuration);
        $connection = $databaseManager->getDatabase($options['connection'])->getConnection();

        $carsDir = "/cars/";
        $dirBase = sfConfig::get('sf_upload_dir');
        
        $logPath = sfConfig::get('sf_log_dir') . '/diffAutos.log';
        if (!($fp = fopen($logPath, 'a'))) {
            die('Cannot open log file');
        } else {
            echo 'verificando...';
            $cars = Doctrine_Core::getTable('car')->findAll();
            $tienenImagenCount = 0;
            $noTienenImagenCount = 0;
            foreach ($cars as $car) {
                $carId = $car->getId();
                $userMail = $car->getUser()->getEmail();
                $image = $car->getFotoPerfil();
                if (!empty($image) && !is_null($image) && $image != "") {
                    echo ".";
                    $file = $dirBase . $carsDir . $image;
                    if (!is_file($file)) {
                        $noTienenImagenCount++;
                        fwrite($fp, "$carId , $userMail, $image \n");
                    } else {
                        $tienenImagenCount++;
                    }
                }
            }
            $this->log('.');
        }
        $this->log('tienen imagen:' . $tienenImagenCount);
        $this->log('no tienen imagen:' . $noTienenImagenCount);
        $this->log('el log esta en:' . $logPath);
        $this->log('listo.');
    }

}
