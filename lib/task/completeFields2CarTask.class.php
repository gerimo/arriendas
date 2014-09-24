<?php

class completeFields2CarTask extends sfBaseTask {

    protected function configure() {

        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name'),
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
            new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'doctrine'),
        ));

        $this->namespace = 'arriendas';
        $this->name = 'CompleteFields2Car';
        $this->briefDescription = '';
        $this->detailedDescription = <<<EOF
The [completeFields2Car|INFO] task does things.
Call it with:

  [php symfony completeFields2Car|INFO]
EOF;
    }

    protected function execute($arguments = array(), $options = array()) {

        $config = ProjectConfiguration::getApplicationConfiguration("frontend", "prod", TRUE);
        sfContext::createInstance($config);

        echo "verificando...\n";
        $cars = Doctrine_Core::getTable('car')->findAll();
        $processed = 0;
        $fromCar = 0;
        $fromUser = 0;
        $fromDefault = 0;
        foreach ($cars as $car) {

            /* try comuna */
            $comunaId = $car->getComunaId();
            if ($comunaId) {
                $comuna = Doctrine_Core::getTable('comunas')->findOneByCodigoInterno($comunaId);
                $fromCar++;
            } else {
                $comunaId = $car->getUser()->getComuna();
                $comuna = Doctrine_Core::getTable('comunas')->findOneByCodigoInterno($comunaId);
                $fromUser++;
            }

            if (!$comuna) {
                /* si al auto no tiene comuna, le asignamos la de santiago */
                $santiagoComunaId = "4";
                $comuna = Doctrine_Core::getTable('comunas')->findOneByCodigoInterno($santiagoComunaId);
                $fromDefault++;
            }

            /* only with valid comuna */
            if ($comuna) {
                $car->setComunaId($comunaId);
                $car->setStateId($comuna->getStateId());
                $car->setRegion($comuna->getPadre());
                $car->setCityId($comuna->getCity());

                $car->save();
            }
            echo ".";
            /* processed count */
            $processed++;
        }
        echo " \n";
        $this->log('processed:' . $processed);
        $this->log('data from car:' . $fromCar);
        $this->log('data from user:' . $fromUser);
        $this->log('data from default:' . $fromDefault);
        $this->log('done.');
    }

}
