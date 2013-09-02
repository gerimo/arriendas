<?php

require_once dirname(__FILE__).'/../lib/carGeneratorConfiguration.class.php';
require_once dirname(__FILE__).'/../lib/carGeneratorHelper.class.php';

/**
 * car actions.
 *
 * @package    arriendas
 * @subpackage car
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class carActions extends autoCarActions
{
    public function executeSubirPadron(sfWebRequest $request) {
        $this->idAuto=$request['id'];
        $this->accion= $request['accion'];
    }
    
    public function executeUploadFoto(sfWebRequest $request) {
        $fileName = $request->getFiles();
        $idAuto=$request['idAuto'];
        $accion= $request['accion'];
        
        $this->car= Doctrine_Core::getTable('Car')->find($idAuto);
        
        $uploadDir = sfConfig::get("sf_upload_dir");
        $Contacts_uploads = $uploadDir.'/'.$accion;
        $etiqueta= md5(rand(0,100000));
        $archivo = $Contacts_uploads."/".$etiqueta.".jpg";
        echo $archivo."<br />".$fileName['archivo']['tmp_name'];
        if(move_uploaded_file($fileName['archivo']['tmp_name'], $archivo)==false) {
            die ("problema subiendo el archivo");
        }
        
        switch ($accion) {
             case "padron":
                $this->car->setPadron($etiqueta);
                break;
            case "frontal_der":
                $this->car->setFrontalDer($etiqueta);
                break;
            case "frontal_cen":
                $this->car->setFrontalCen($etiqueta);
                break;
            case "frontal_izq":
                $this->car->setFrontalIzq($etiqueta);
                break;
            case "trasera_der":
                $this->car->setTraseroDer($etiqueta);
                break;
            case "trasera_cen":
                $this->car->setTraseroCen($etiqueta);
                break;
            case "trasera_izq":
                $this->car->setTraseroIzq($etiqueta);
                break;
            case "lateral_der":
                $this->car->setLateralDer($etiqueta);
                break;
            case "lateral_izq":
                $this->car->setLateralIzq($etiqueta);
                break;
            case "tablero":
                $this->car->setTablero($etiqueta);
                break;
            case "equipo_audio":
                $this->car->setEquipoAudio($etiqueta);
                break;
            case "llanta_del_der":
                $this->car->setLlantaDelDer($etiqueta);
                break;
            case "llanta_del_izq":
                $this->car->setLlantaDelIzq($etiqueta);
                break;
            case "llanta_tra_der":
                $this->car->setLlantaTraDer($etiqueta);
                break;
            case "llanta_tra_izq":
                $this->car->setLlantaTraIzq($etiqueta);
                break;           
        }
        $this->car->setPadron($etiqueta);
        $this->car->save();
    }
}
