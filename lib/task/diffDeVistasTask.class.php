<?php

class diffDeVistasTask extends sfBaseTask {

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
        $this->name = 'diffDeVistas';
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

        $carsDir = "/vistas_seguro/";
        $cars2Dir = "/verificaciones/";
        $dirBase = sfConfig::get('sf_upload_dir');

        $logPath = sfConfig::get('sf_log_dir') . '/diffVistas.log';
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
                $images = array();
                if ($car->getVerificacionOK()) {

                    if (!is_null($car->getSeguroFotoFrente())) {
                        $images[] = $car->getSeguroFotoFrente();
                    }
                    if (!is_null($car->getSeguroFotoCostadoDerecho())) {
                        $images[] = $car->getSeguroFotoCostadoDerecho();
                    }
                    if (!is_null($car->getSeguroFotoCostadoIzquierdo())) {
                        $images[] = $car->getSeguroFotoCostadoIzquierdo();
                    }
                    if (!is_null($car->getSeguroFotoTraseroDerecho())) {
                        $images[] = $car->getSeguroFotoTraseroDerecho();
                    }
                    if (!is_null($car->getLlantaDelDer())) {
                        $images[] = $car->getLlantaDelDer();
                    }
                    if (!is_null($car->getLlantaDelIzq())) {
                        $images[] = $car->getLlantaDelIzq();
                    }
                    if (!is_null($car->getLlantaTraDer())) {
                        $images[] = $car->getLlantaTraDer();
                    }
                    if (!is_null($car->getLlantaTraIzq())) {
                        $images[] = $car->getLlantaTraIzq();
                    }
                    if (!is_null($car->getTablero())) {
                        $images[] = $car->getTablero();
                    }
                    if (!is_null($car->getRuedaRepuesto())) {
                        $images[] = $car->getRuedaRepuesto();
                    }
                    if (!is_null($car->getPadron())) {
                        $images[] = $car->getPadron();
                    }
                    if (!is_null($car->getFotoPadronReverso())) {
                        $images[] = $car->getFotoPadronReverso();
                    }
                }
                foreach ($images as $image) {
                    if (!empty($image) && !is_null($image) && $image != "") {
                        echo ".";
                        $file = $dirBase . $carsDir . $image;
                        $file2 = $dirBase . $cars2Dir . $image;
                        if (!is_file($file) && !is_file($file2)) {
                        	$noTienenImagenCount++;
                            	fwrite($fp, "$carId,$userMail,$image\n");
                        } else {
                            $tienenImagenCount++;
                        }
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
