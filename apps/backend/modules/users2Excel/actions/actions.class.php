<?php

/**
 * users2Excel actions.
 *
 * @package    backend
 * @subpackage users2Excel
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class users2ExcelActions extends sfActions
{
 /**
  * Executes index action
  *
  * @param sfRequest $request A request object
  */
  public function executeExport(sfWebRequest $request)
  {
    //Obtenemos la query de usuarios desde la BD
    $query= Doctrine_Query::create()
            ->select("u.id as id, u.firstname as nombre, u.lastname as apellido, u.email as email,
                     u.telephone as telephone, u.facebook_id as facebook_id, u.address as address,
                     u.confirmed as confirmed, u.confirmed_fb as confirmed_fb,
                     u.propietario, COUNT(m.user_id) as messages, u.rut_file as as rut_file, u.picture_file as picture_file
                     u.driver_license_file as driver_license_file")
            ->from("User u")
            ->leftJoin("u.Car c")
            ->leftJoin("u.Message m")
            ->groupBy("u.id");
    $temporal= $query->execute();
    $salida= "";
      $salida.="Id\t";
      $salida.="Nombre\t";
      $salida.="Apellido\t";
      $salida.="E-Mail\t";
      $salida.="Telefono\t";
      $salida.="Facebook Id\t";
      $salida.="Direccion\t";
      $salida.="Confirmado\t";
      $salida.="FB Confirmado\t";
      $salida.="Propietario\t";
      $salida.="Mensajes\t";
      $salida.="FB Recommender\t";
      $salida.="RUT\t";
      $salida.="Foto\t";
      $salida.="Licencia\n";

    for ($i=0;$i<=count($temporal)-1;$i++){
      $salida.=$temporal[$i]->getId()."\t";
      $salida.=$temporal[$i]->getNombre()."\t";
      $salida.=$temporal[$i]->getApellido()."\t";
      $salida.=$temporal[$i]->getEmail()."\t";
      $salida.=$temporal[$i]->getTelephone()."\t";
      $salida.=$temporal[$i]->getFacebookId()."\t";
      $salida.=$temporal[$i]->getAddress()."\t";
      $salida.=$temporal[$i]->getConfirmed()."\t";
      $salida.=$temporal[$i]->getConfirmedFb()."\t";
      $salida.=$temporal[$i]->getPropietario()."\t";
      $salida.=$temporal[$i]->getMessages()."\t";
      $salida.=$temporal[$i]->getRecommender()."\t";
      $salida.=$temporal[$i]->getRutFile()."\t";
      $salida.=$temporal[$i]->getPictureFile()."\t";
      $salida.=$temporal[$i]->getDriverLicenseFile()."\n";
    }
    $hora= time();
    $resultado=file_put_contents(sfConfig::get('sf_upload_dir')."/users".$hora.".xls",$salida);
    if($resultado===false) {
      die("Error Generando Archivo");
    } else {
      $this->redirect('http://admin.arriendas.cl/uploads/users'.$hora.".xls");
    }
  }
}
