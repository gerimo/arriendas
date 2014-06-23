<?php

class diffDeDamagesTask extends sfBaseTask {

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
        $this->name = 'diffDeDamages';
        $this->briefDescription = '';
        $this->detailedDescription = <<<EOF
The [diffDeDamages|INFO] task does things.
Call it with:

  [php symfony diffDeDamages|INFO]
EOF;
    }

    protected function execute($arguments = array(), $options = array()) {

        // initialize the database connection
        $databaseManager = new sfDatabaseManager($this->configuration);
        $connection = $databaseManager->getDatabase($options['connection'])->getConnection();

        $damagesDir = "/damages/";
        $dirBase = sfConfig::get('sf_upload_dir');
        
        $logPath = sfConfig::get('sf_log_dir') . '/diffDamages.log';
        if (!($fp = fopen($logPath, 'a'))) {
            die('Cannot open log file');
        } else {
            echo 'verificando...';
            $damages = Doctrine_Core::getTable('damage')->findAll();
            $tienenImagenCount = 0;
            $noTienenImagenCount = 0;
            foreach ($damages as $damage) {
                $carId = $damage->getCarId();
                $car = $damage->getCar();
                $user = $car->getUser();
                $mail = $user->getEmail();
                $image = $damage->getUrlFoto();
                if (!empty($image) && !is_null($image) && $image != "") {
                    echo ".";
                    $file = $dirBase . $damagesDir . $image;
                    if (!is_file($file)) {
                        $noTienenImagenCount++;
                        fwrite($fp, "$carId, $mail, $image\n");
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
