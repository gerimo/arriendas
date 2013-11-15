<?php

/**
 * autos actions.
 *
 * @package    backend
 * @subpackage autos
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class autosActions extends sfActions
{

 /**
  * Executes index action
  *
  * @param sfRequest $request A request object
  */
  public function executeIndex(sfWebRequest $request)
  {
    $this->forward('default', 'module');
  }
  
  public function executeObtenerDatosAuto(sfWebRequest $request) {
    $datosAuto= new stdClass();
    $datosAuto->marca= "Ford";
    $datosAuto->modelo="Ecosport";
    $datosAuto->duenio="Patricio Lopez";
    $datosAuto->telefono="71604622";
    $datosAuto->direccion="Waterloo 110";
    $datosAuto->precioHora="5000";
    $datosAuto->precioDia="30000";
    $datosAuto->patente="PW9860";
    $datosAuto->anio="2007";
    $datosAuto->descripcion="Station Wagon ideal para viajes a la playa o fuera de Santiago";
    $datosAuto->kilometraje="30500";
    $salida= json_encode($datosAuto);
    $this->getResponse()->setContentType('text/json');
    return $this->renderText($salida);
  }
  
  public function executeObtenerDaniosAuto(sfWebRequest $request) {
    $danios= new stdClass();
    $danios->fotos=array();
    
    $temp=new stdClass();
    $temp->urlFoto="http://admin.arriendas.cl/uploads/frontal_cen/7ea51fd2f0dd47dff6eb12321e54e9b0.jpg";
    $temp->idZona="1";
    $temp->descripcion="Rotura parachoque";
    
    $temp2=new stdClass();
    $temp2->urlFoto="http://admin.arriendas.cl/uploads/frontal_cen/8a4488c177d9dc8c3da7c745c89ca214.jpg";
    $temp2->idZona="4";
    $temp2->descripcion="Abolladura puerta conductor";

    $temp3=new stdClass();
    $temp3->urlFoto="http://admin.arriendas.cl/uploads/frontal_cen/bbf94b34eb32268ada57a3be5062fe7d.jpg";
    $temp3->idZona="4";
    $temp3->descripcion="Trizadura ventana puerta conductor";

    
    array_push($danios->fotos,$temp);
    array_push($danios->fotos,$temp2);
    array_push($danios->fotos,$temp3);
    
    $salida= json_encode($danios);
    $this->getResponse()->setContentType('text/json');
    return $this->renderText($salida);
  }

}
