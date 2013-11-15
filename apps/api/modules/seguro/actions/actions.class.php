<?php

require sfConfig::get('sf_app_lib_dir')."/resultadoApi.class.php";
/**
 * seguro actions.
 *
 * @package    backend
 * @subpackage seguro
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class seguroActions extends sfActions
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
  
  /*
   * INPUT: token, idAuto, upload de la Foto, id de la posicion
   * OUTPUT: OK o error en caso de corresponsda
   **/
  public function executeSubirFoto(sfWebRequest $request) {
      $token= $request->getPostParameter("token");
      $resultado= new resultadoApi();
      
      //Validacion de existencia de par‡metros
      if(is_null($token)) {
        $resultado->resultado="0";
        $resultado->mensaje="Token Inexistente";
        $salida= json_encode($resultado);
        return $this->renderText($salida);
      }
      
      $idAuto= $request->getPostParameter("idAuto");
      if(is_null($idAuto)) {
        $resultado->resultado="0";
        $resultado->mensaje="Falta ID Auto";
        $salida= json_encode($resultado);
        return $this->renderText($salida);
      }
      
      $idPosicion= $request->getPostParameter("idPosicion");
      if(is_null($idPosicion)) {
        $resultado->resultado="0";
        $resultado->mensaje="Falta ID Posicion";
        $salida= json_encode($resultado);
        return $this->renderText($salida);
      }
      
      $foto= $request->getFiles("file");
      if(is_null($foto)) {
        $resultado->resultado="0";
        $resultado->mensaje="Falta el archivo de la foto";
        $salida= json_encode($resultado);
        return $this->renderText($salida);
      }
    
      //En este punto, se tienen los datos necesarios para subir la foto correspondiente.
      //Incluimos el SDK para interactuar con AWS
      require sfConfig::get('sf_app_lib_dir')."/aws_sdk/sdk.class.php";
      $s3= new AmazonS3();
      
      $bucket= "fotos.arriendas";
      
      $response= $s3->create_object($bucket,$foto['name'],array("fileUpload"=>$foto['tmp_name']));
      return $this->renderText(json_encode($response));
  }
  
  
}
