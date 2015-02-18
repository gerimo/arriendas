<?php

/**
 * BaseCarAudioAccessories
 * 
 * 
 * @property integer $id
 * @property integer $car_id
 * @property string  $radio_marca
 * @property string  $radio_modelo
 * @property string  $radio_tipo
 * @property string  $parlantes_marca
 * @property string  $parlantes_modelo
 * @property string  $tweeters_marca
 * @property string  $tweeters_modelo
 * @property string  $ecualizador_marca
 * @property string  $ecualizador_modelo
 * @property string  $amplificador_marca
 * @property string  $amplificador_modelo
 * @property string  $compact_cd_marca
 * @property string  $compact_cd_modelo
 * @property string  $subwoofer_marca
 * @property string  $subwoofer_modelo
 * @property string  $sistema_dvd_marca
 * @property string  $sistema_dvd_modelo
 * @property string  $otros
 * @property Car     $Car
 * 
 * @method integer          getId()                    Returns the current record's "id" value
 * @method integer          getCarId()                 Returns the current record's "car_id" value
 * @method string           getRadioMarca()            Returns the current record's "radio_marca" value
 * @method string           getRadioModelo()           Returns the current record's "radio_modelo" value
 * @method string           getRadioTipo()             Returns the current record's "radio_tipo" value
 * @method string           getParlantesMarca()        Returns the current record's "parlantes_marca" value
 * @method string           getParlantesModelo()       Returns the current record's "parlantes_modelo" value
 * @method string           getTweetersMarca()         Returns the current record's "tweeters_marca" value
 * @method string           getTweetersModelo()        Returns the current record's "tweeters_modelo" value
 * @method string           getEcualizadorMarca()      Returns the current record's "ecualizador_marca" value
 * @method string           getEcualizadorModelo()     Returns the current record's "ecualizador_modelo" value
 * @method string           getAmplificadorMarca()     Returns the current record's "amplificador_marca" value
 * @method string           getAmplificadorModelo()    Returns the current record's "amplificador_modelo" value
 * @method string           getCompactCdMarca()        Returns the current record's "compact_cd_marca" value
 * @method string           getCompactCdModelo()       Returns the current record's "compact_cd_modelo" value
 * @method string           getSubwooferMarca()        Returns the current record's "subwoofer_marca" value
 * @method string           getSubwooferModelo()       Returns the current record's "subwoofer_modelo" value
 * @method string           getSistemaDvdMarca()       Returns the current record's "sistema_dvd_marca" value
 * @method string           getSistemaDvdModelo()      Returns the current record's "sistema_dvd_modelo" value
 * @method string           getOtros()                 Returns the current record's "otros" value
 * @method car              getCar()                   Returns the current record's "car" value
 *
 * @method Car              setId()                    Sets the current record's "id" value
 * @method Car              setCarId()                 Sets the current record's "car_id" value
 * @method Car              setRadioMarca()            Sets the current record's "radio_marca" value
 * @method Car              setRadioModelo()           Sets the current record's "radio_modelo" value
 * @method Car              setRadioTipo()             Sets the current record's "radio_tipo" value
 * @method Car              setParlantesMarca()        Sets the current record's "parlantes_marca" value
 * @method Car              setParlantesModelo()       Sets the current record's "parlantes_modelo" value
 * @method Car              setTweetersMarca()         Sets the current record's "tweeters_marca" value
 * @method Car              setTweetersModelo()        Sets the current record's "tweeters_modelo" value
 * @method Car              setEcualizadorMarca()      Sets the current record's "ecualizador_marca" value
 * @method Car              setEcualizadorModelo()     Sets the current record's "ecualizador_modelo" value
 * @method Car              setAmplificadorMarca()     Sets the current record's "amplificador_marca" value
 * @method Car              setAmplificadorModelo()    Sets the current record's "amplificador_modelo" value
 * @method Car              setCompactCdMarca()        Sets the current record's "compact_cd_marca" value
 * @method Car              setCompactCdModelo()       Sets the current record's "compact_cd_modelo" value
 * @method Car              setSubwooferMarca()        Sets the current record's "subwoofer_marca" value
 * @method Car              setSubwooferModelo()       Sets the current record's "subwoofer_modelo" value
 * @method Car              setSistemaDvdMarca()       Sets the current record's "sistema_dvd_marca" value
 * @method Car              setSistemaDvdModelo()      Sets the current record's "sistema_dvd_modelo" value
 * @method Car              setOtros()                 Sets the current record's "otros" value
 * @method Car              setCar()                   Sets the current record's "otros" car
 *
 * @package    CarAudioAccessoriesSharing
 * @subpackage model
 * @author     Your name here
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseCarAudioAccessories extends sfDoctrineRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('CarAudioAccessories');
        $this->hasColumn('id', 'integer', 4, array(
             'type' => 'integer',
             'primary' => true,
             'autoincrement' => true,
             'length' => 4,
             ));
        $this->hasColumn('car_id', 'integer', 4, array(
             'type' => 'integer',
             'notnull' => true,
             'length' => 4,
             ));
        $this->hasColumn('radio_marca', 'string', 255, array(
             'type' => 'string',
             'length' => 255,
             ));
        $this->hasColumn('radio_modelo', 'string', 255, array(
             'type' => 'string',
             'length' => 255,
             ));
        $this->hasColumn('radio_tipo', 'string', 255, array(
             'type' => 'string',
             'length' => 255,
             ));
        $this->hasColumn('parlantes_marca', 'string', 255, array(
             'type' => 'string',
             'length' => 255,
             ));
        $this->hasColumn('parlantes_modelo', 'string', 255, array(
             'type' => 'string',
             'length' => 255,
             ));
        $this->hasColumn('tweeters_marca', 'string', 255, array(
             'type' => 'string',
             'length' => 255,
             ));
        $this->hasColumn('tweeters_modelo', 'string', 255, array(
             'type' => 'string',
             'length' => 255,
             ));
        $this->hasColumn('ecualizador_marca', 'string', 255, array(
             'type' => 'string',
             'length' => 255,
             ));
        $this->hasColumn('ecualizador_modelo', 'string', 255, array(
             'type' => 'string',
             'length' => 255,
             ));
        $this->hasColumn('amplificador_marca', 'string', 255, array(
             'type' => 'string',
             'length' => 255,
             ));
        $this->hasColumn('amplificador_modelo', 'string', 255, array(
             'type' => 'string',
             'length' => 255,
             ));
        $this->hasColumn('compact_cd_marca', 'string', 255, array(
             'type' => 'string',
             'length' => 255,
             ));
        $this->hasColumn('compact_cd_modelo', 'string', 255, array(
             'type' => 'string',
             'length' => 255,
             ));
        $this->hasColumn('subwoofer_marca', 'string', 255, array(
             'type' => 'string',
             'length' => 255,
             ));
        $this->hasColumn('subwoofer_modelo', 'string', 255, array(
             'type' => 'string',
             'length' => 255,
             ));
        $this->hasColumn('sistema_dvd_marca', 'string', 255, array(
             'type' => 'string',
             'length' => 255,
             ));
        $this->hasColumn('sistema_dvd_modelo', 'string', 255, array(
             'type' => 'string',
             'length' => 255,
             ));
        $this->hasColumn('otros', 'string', 255, array(
             'type' => 'string',
             'length' => 255,
             ));

        // Indices
/*        $this->index('fk_CarAudioAccessories_Car', array(
            'fields' => array(
                0 => 'car_id',
            ),
        ));*/
        
        $this->index('id_UNIQUE', array(
            'fields' => array(
                0 => 'id',
            ),
            'type' => 'unique',
        ));

        $this->option('charset', 'utf8');
        $this->option('type', 'InnoDB');
    }

    public function setUp() {

        parent::setUp();
        
        $this->hasOne('Car', array(
             'local' => 'car_id',
             'foreign' => 'id',
             'onDelete' => 'no action',
             'onUpdate' => 'no action'
        ));

    }
}