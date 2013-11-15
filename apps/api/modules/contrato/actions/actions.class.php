<?php

/**
 * contrato actions.
 *
 * @package    backend
 * @subpackage contrato
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class contratoActions extends sfActions
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
  
  public function executeGenerarContrato(sfWebRequest $request) {
    //Obtenemos el ID de la reserva
    $idReserva=  $request->getParameter("idReserva");
    $tokenReserva=  $request->getParameter("tokenReserva");
    
    if (!$tokenReserva){
		$reserva= Doctrine_Core::getTable('reserve')->findOneById($idReserva);
    }else{
		$reserva= Doctrine_Core::getTable('reserve')->findOneByToken($tokenReserva);
    };
	
    $datosContrato= new stdClass();
    
    $confirmed=$reserva->getConfirmed();
	
	
    $datosContrato->fecha_arriendo=substr($reserva->getDate(),8,2)."/".substr($reserva->getDate(),5,2)."/".substr($reserva->getDate(),0,4);
    $datosContrato->hora_arriendo=substr($reserva->getDate(),11,5);
    $datosContrato->duracion_arriendo=$reserva->getDuration()." horas";
    $datosContrato->precio_arriendo="$".number_format($reserva->getPrice(),0,",",".");
    
    //datos del auto
    $auto= CarTable::getInstance()->findOneById($reserva->getCarId());
    $datosContrato->patente_auto=$auto->getPatente();
    $datosContrato->anio_auto=$auto->getYear();
    
    
    //Modelo
    $modelo= ModelTable::getInstance()->findOneById($auto->getModelId());
    $datosContrato->modelo_auto=$modelo->getName();
  
    //Marca
    $marca= BrandTable::getInstance()->findOneById($modelo->getBrandId());
    $datosContrato->marca_auto=$marca->getName();
    
    //Arrendatario
    $arrendatario= UserTable::getInstance()->findOneById($reserva->getUserId());
    $datosContrato->nombre_arrendatario=$arrendatario->getFirstname()." ".$arrendatario->getLastname();
	
	if (!$confirmed){
		$datosContrato->rut_arrendatario=preg_replace('/[\w\.]/','X',$arrendatario->getRut());
		$datosContrato->domicilio_arrendatario=preg_replace('/[\w\.]/','X',$arrendatario->getAddress());
    }else{
		$datosContrato->rut_arrendatario=$arrendatario->getRut();
		$datosContrato->domicilio_arrendatario=$arrendatario->getAddress();
    };
	
    //Comuna Arrendatario
    $comuna_arr= ComunasTable::getInstance()->findOneByCodigoInterno($arrendatario->getComuna());
    $datosContrato->comuna_arrendatario=$comuna_arr->getNombre();
    
    //Dueno
    $duenio= UserTable::getInstance()->findOneById($auto->getUserId());
    $datosContrato->nombre_duenio=$duenio->getFirstname()." ".$duenio->getLastname();

	if (!$confirmed){
		$datosContrato->rut_duenio=preg_replace('/[\w\.]/','X',$duenio->getRut());
		$datosContrato->domicilio_duenio=preg_replace('/[\w\.]/','X',$duenio->getAddress());
	}else{
		$datosContrato->rut_duenio=$duenio->getRut();
		$datosContrato->domicilio_duenio=$duenio->getAddress();
	};

    //Comuna Duenio
    $comuna_due= ComunasTable::getInstance()->findOneByCodigoInterno($duenio->getComuna());
    $datosContrato->comuna_duenio= $comuna_due->getNombre();
    
    
  
    //Cargamos el texto del contrato desde el archivo fuente
    $contrato= file_get_contents(sfConfig::get('sf_app_template_dir')."/contrato_arriendas.html");
    
    //Reemplazamos los datos por la informacion del contrato
    $contrato= str_replace("{fecha_arriendo}",utf8_decode($datosContrato->fecha_arriendo),$contrato);
    $contrato= str_replace("{nombre_completo_duenio}",utf8_decode($datosContrato->nombre_duenio),$contrato);
    $contrato= str_replace("{rut_duenio}",$datosContrato->rut_duenio,$contrato);
    $contrato= str_replace("{comuna_duenio}",utf8_decode($datosContrato->comuna_duenio),$contrato);
    $contrato= str_replace("{domicilio_duenio}",utf8_decode($datosContrato->domicilio_duenio),$contrato);
    $contrato= str_replace("{nombre_usuario}",utf8_decode($datosContrato->nombre_arrendatario),$contrato);
    $contrato= str_replace("{rut_arrendatario}",utf8_decode($datosContrato->rut_arrendatario),$contrato);
    $contrato= str_replace("{domicilio_arrendatario}",utf8_decode($datosContrato->domicilio_arrendatario),$contrato);
    $contrato= str_replace("{comuna_arrendatario}",utf8_decode($datosContrato->comuna_arrendatario),$contrato);
    $contrato= str_replace("{marca_auto}",utf8_decode($datosContrato->marca_auto),$contrato);
    $contrato= str_replace("{modelo_auto}",utf8_decode($datosContrato->modelo_auto),$contrato);
    $contrato= str_replace("{anio_auto}",utf8_decode($datosContrato->anio_auto),$contrato);
    $contrato= str_replace("{patente_auto}",utf8_decode($datosContrato->patente_auto),$contrato);
    $contrato= str_replace("{duracion_arriendo}",utf8_decode($datosContrato->duracion_arriendo),$contrato);
    $contrato= str_replace("{inicio}",$datosContrato->fecha_arriendo." a las ".$datosContrato->hora_arriendo,$contrato);
    $contrato= str_replace("{precio}",$datosContrato->precio_arriendo,$contrato);
    
    //Creamos la instancia de la libreria mPDF
    require sfConfig::get('sf_app_lib_dir')."/mpdf53/mpdf.php";
    
    $this->getResponse()->setContentType('application/pdf');
    $pdf= new mPDF();
    $pdf->WriteHTML(utf8_encode($contrato));
    return $this->renderText($pdf->Output());
  }
  
  
}
