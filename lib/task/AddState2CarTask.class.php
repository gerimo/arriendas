<?php

class AddState2CarTask extends sfBaseTask {

    protected function configure() {

        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name'),
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
            new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'doctrine'),
        ));

        $this->namespace = 'arriendas';
        $this->name = 'AddState2Car';
        $this->briefDescription = '';
        $this->detailedDescription = <<<EOF
The [AddProvince2Car|INFO] task does things.
Call it with:

  [php symfony AddState2Car|INFO]
EOF;
    }

    protected function execute($arguments = array(), $options = array()) {

        $config = ProjectConfiguration::getApplicationConfiguration("frontend", "prod", TRUE);
        sfContext::createInstance($config);

        echo "verificando...\n";
        $cars = Doctrine_Core::getTable('car')->findAll();
        $processed = 0;
        $updated = 0;
        //echo "searching cars...\n";
        foreach ($cars as $car) {
            //echo "processing car id:" . $car->getId();
            /* try comuna */
            $comunaId = $car->getComunaId();
            if ($comunaId) {
                $comuna = Doctrine_Core::getTable('comunas')->findOneByCodigoInterno($comunaId);
            }
            if (!$comuna) {
                /* si al auto no tiene comuna, le asignamos la de santiago */
                $santiagoComunaId = "4";
                $comuna = Doctrine_Core::getTable('comunas')->findOneByCodigoInterno($santiagoComunaId);
            }

            /* only with valid comuna */
            if ($comuna) {
                $stateId = $comuna->getStateId();
                $oldStateId = $car->getStateId();
                //echo "    oldStateId:" . $oldStateId . " | stateId:" . $stateId;
                if ($oldStateId != $stateId) {
                    $car->setStateId($stateId);
                    $car->save();

                    /* updated count */
                    $updated++;
                }
            }
            echo ".";
            /* processed count */
            $processed++;
        }
        echo " \n";
        $this->log('processed:' . $processed);
        $this->log('updated:' . $updated);
        $this->log('done.');
    }

}
