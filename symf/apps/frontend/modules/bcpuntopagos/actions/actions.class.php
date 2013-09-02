<?php

/**
 * bcpuntopagos actions.
 *
 * @author     BetterChoice Fabrica de Software
 * @version    SVN: $Id: actions.class.php 23810 2012-10-15 11:07:44Z Israel Gonzalez $
 * Permitido instalar solo bajo condiciones contractuales
 * Queda prohibida explicitamente su distribucion
 */
class bcpuntopagosActions extends sfActions
{
 /**
  * Presenta el formulario con los medidos de pago de Punto Pagos
  *
  * @param sfRequest $request A request object
  */
  public function executeIndex(sfWebRequest $request)
  {
	$customer_in_session = $this->getUser()->getAttribute('userid');
	
	if($customer_in_session){
	
		$this->ppId = null;
		
		if(!$request->hasParameter("id")){
		
			$this->error = 'No se ha recibido un codigo de reserva';
			
		}else{
			
			try{
			
				$order = Doctrine_Core::getTable("Transaction")->getTransactionByReserve($request->getParameter("id"));
				
				if($order != false){
					
					$this->ppId = $order->getId();
					$this->ppMonto = $order->getPrice(); //reemplazar por metodo getMonto
					$this->ppIdReserva = $request->getParameter("id");
					
					if($this->ppMonto <= 0 || $this->ppMonto == null){
					
						$this->error = 'Monto invalido o cero en transaccion #'.$this->ppId.'. No es posible procesar en Punto Pagos.';
						$this->_log("Formulario","Error","Usuario: ".$customer_in_session.". Order ID: ".$order->getId().". Error: ".$this->error);
						$this->ppId = null;
					}
				
				}else{
				
					$this->error = 'Orden no existe o ya esta procesada para la reserva: '.$request->getParameter("id");
					$this->_log("Formulario","Error","Usuario: ".$customer_in_session.". Error: ".$this->error);
					$this->ppId = null;
					
				}
			
			}catch(Exception $e){
			
				$this->error = 'Ha ocurrido un error tratando de procesar el codigo de reserva indicado.<br/>'.$e->getMessage();
				$this->_log("Formulario","Error","Usuario: ".$customer_in_session.". Error: ".$this->error);
				$this->ppId = null;
			
			}
		}
		
	}else{
		$this->redirect('@homepage');
	}
  }
  
  /**
  * Crea la transaccion hacia punto pagos segun datos de config/puntopagos.yml
  *
  * @param sfRequest $request A request object
  */
  public function executeCreacion(sfWebRequest $request)
  {
	$customer_in_session = $this->getUser()->getAttribute('userid');
	
	if($customer_in_session){
	
		if(!$request->hasParameter("id") || $request->getParameter("id") == null){
			
			$this->getUser()->setFlash('error', 'No se ha recibido un codigo de orden para la creacion en Punto Pagos');
			$this->redirect("bcpuntopagos/index");
			
		}else{
			
			if(!$request->hasParameter("pp_medio_pago") || $request->getParameter("pp_medio_pago") == null){
			
				$this->getUser()->setFlash('error', 'Debe especificar un medio de pago');
				$this->redirect("bcpuntopagos/index?id=".$request->getParameter("idReserva"));
			
			}
			
			$order = Doctrine_Core::getTable("Transaction")->getTransaction($request->getParameter('id'));
			
			if($order->getPrice() > 0){
				
				$conf = $this->getConfiguration();
				$conf = $conf["betterchoice"]["puntopagos"];
				$enviroment = $conf["enviroment"];
				
				//Parameters configured in config/puntopagos.yml file:
				$PUNTOPAGOS_URL = $conf[$enviroment]["url"];
				$PUNTOPAGOS_SECRET = $conf[$enviroment]["secret"];
				$PUNTOPAGOS_KEY = $conf[$enviroment]["key"];
				
				//The three parameters for creation in Punto Pagos: ammount, payment method id and transaction id
				$ammount = $order->getPrice();
				$payment_id = $request->getParameter('pp_medio_pago');
				$trx_id = $order->getId();
				
				//Execution itself about punto pagos creation process
				$funcion = "transaccion/crear";
				$ammount_str = number_format($ammount, 2, '.', '');
				
				$http_request = "";
				$http_request .= $data_creation_step = '{"trx_id":"'.$trx_id.'","medio_pago":'.$payment_id.',"monto":'.$ammount_str.'}';
				$http_request .= "<br/><br/>";
				
				$fecha = gmdate("D, d M Y H:i:s", time())." GMT";
				$mensaje = $funcion."\n".$trx_id."\n".$ammount_str."\n".$fecha;
				$signature = base64_encode(hash_hmac('sha1', $mensaje, $PUNTOPAGOS_SECRET, true));
				$firma = "PP ".$PUNTOPAGOS_KEY.":".$signature;

				$header = array();
				$http_request .= $header[] = "Accept: application/json;";
				$http_request .= $header[] = "Accept-Charset: utf-8;";
				$http_request .= $header[] = "Accept-Language: en-us,en;q=0.5";
				$http_request .= $header[] = "Content-type: application/json";
				$http_request .= $header[] = "Fecha: ".$fecha;
				$http_request .= $header[] = "Autorizacion: ".$firma;

				$url_pp = $PUNTOPAGOS_URL."/transaccion/crear";
				
				$curl = curl_init($url_pp);
				curl_setopt($curl, CURL_VERSION_SSL,"SSL_VERSION_SSLv3");
				curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
				curl_setopt($curl, CURL_HTTP_VERSION_1_1, 1);
				curl_setopt($curl, CURLOPT_POST,1);
				curl_setopt($curl, CURLOPT_POSTFIELDS, $data_creation_step);
				curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
				
				$http_response = curl_exec ($curl);
				
				$response = json_decode($http_response);
				
				curl_close($curl);
				
				if($response->token != ""){
					//We store in Session Last Order Id in Punto Pagos. We'll need after payment execution itself
					$this->getUser()->setAttribute('PP_LAST_ORDER_ID', $order->getId());
					$this->_log("Creacion","Exito","Usuario: ".$customer_in_session.". Order ID: ".$order->getId().". Token Punto Pagos: ".$response->token.". JSON Punto Pagos: ".$response);
					
					$this->redirect($PUNTOPAGOS_URL.'/transaccion/procesar/'.$response->token);
				
				}else{
					
					$this->_log("Creacion","Error","Usuario: ".$customer_in_session.". Order ID: ".$order->getId().". Error: ".$response->respuesta." - ".$response->error. ". JSON Punto Pagos: ".$response);
					$this->getUser()->setFlash('error', "Ha ocurrido un error. Error (".$response->respuesta."): ".$response->error);
					
					$this->redirect('bcpuntopagos/index?id='.$request->getParameter("idReserva"));
					
				}
			
			}
		}
		
	}else{
		$this->redirect('@homepage');
	}
  }
  
  /**
  * Process suceess by verifying notification step to Punto Pagos and change status to order
  *
  * @param sfRequest $request A request object
  */
  public function executeExito(sfWebRequest $request)
  {
    $customer_in_session = $this->getUser()->getAttribute('userid');
	
	if($customer_in_session){
	
		$token = $request->getParameter('t');
		$this->notificacion($token);
		
		$last_order_id = $this->getUser()->getAttribute('PP_LAST_ORDER_ID');
		$conf = $this->getConfiguration();
		$conf = $conf["betterchoice"]["puntopagos"];
		$enviroment = $conf["enviroment"];
		$STATE_SUCCESSFULL = $conf[$enviroment]["status_success"];
		$STATE_ERROR = $conf[$enviroment]["status_error"];

		$order = Doctrine_Core::getTable("Transaction")->getTransaction($last_order_id);
		
		if($order->getPrice() > 0){
			
			$this->_log("Pago","Exito","Usuario: ".$customer_in_session.". Order ID: ".$order->getId());
			Doctrine_Core::getTable("Transaction")->successTransaction($last_order_id, $token, $STATE_SUCCESSFULL, 1);
			
		}else{
			echo "No hay compras hechas para ser pagadas (Error de monto invalido)";
			$this->_log("Pago","Error","Usuario: ".$customer_in_session.". Order ID: ".$order->getId());
			Doctrine_Core::getTable("Transaction")->successTransaction($last_order_id, $token, $STATE_ERROR, 1);
		}
	
	}else{
		$this->redirect('@homepage');
	}
  }
  
  /**
  * Cambia orden a status fallido e Informa a usuario en pagina de exito
  *
  * @param sfRequest $request A request object
  */
  public function executeFracaso(sfWebRequest $request)
  {
    $customer_in_session = $this->getUser()->getAttribute('userid');
	
	if($customer_in_session){
	
		$token = $request->getParameter('t');
		$this->notificacion($token);
		
		$last_order_id = $this->getUser()->getAttribute('PP_LAST_ORDER_ID');
		$conf = $this->getConfiguration();
		$conf = $conf["betterchoice"]["puntopagos"];
		$enviroment = $conf["enviroment"];
		$STATE_FAILURE = $conf[$enviroment]["status_failure"];
		$STATE_ERROR = $conf[$enviroment]["status_error"];

		$order = Doctrine_Core::getTable("Transaction")->getTransaction($last_order_id);
		
		if($order->getPrice() > 0){
			
			$this->_log("Pago","Fracaso","Usuario: ".$customer_in_session.". Order ID: ".$order->getId());
			Doctrine_Core::getTable("Transaction")->successTransaction($last_order_id, $token, $STATE_FAILURE, 1);
			
		}else{
			echo "No hay compras hechas para ser pagadas (Error de monto invalido)";
			$this->_log("Pago","Error","Usuario: ".$customer_in_session.". Order ID: ".$order->getId());
			Doctrine_Core::getTable("Transaction")->successTransaction($last_order_id, $token, $STATE_ERROR, 1);
		}
	
	}else{
		$this->redirect('@homepage');
	}
  }
  
  /**
  * Se usa para el proceso de notificacion de Punto Pagos
  *
  * @param sfRequest $request A request object
  */
  public function executeNotificacion(sfWebRequest $request)
  {
    echo "Notificacion Punto Pagos"; //We prefer doing trasaction step by curl and not literally by url
	exit;
  }

  /**
  * Hello word only for basic test porpouse
  *
  * @param sfRequest $request A request object
  */
  public function executeHello(sfWebRequest $request)
  {
    echo "Hello Word! Punto Pagos";
	exit;
  }
  
  protected function getConfiguration(){
   return sfYaml::load(dirname(dirname(__FILE__))."/config/puntopagos.yml");
  }
  
  protected function notificacion($token){
		
	//Rescatamos el Ultimo ID de orden enviado a crear a Punto Pagos
	$orderId = $this->getUser()->getAttribute('PP_LAST_ORDER_ID');
	
	if($orderId){
	
		$order = Doctrine_Core::getTable("Transaction")->getTransaction($orderId);
		
		$conf = $this->getConfiguration();
		$conf = $conf["betterchoice"]["puntopagos"];
		$enviroment = $conf["enviroment"];
		//Campos configurados segun config/puntopagos.yml:
		$PUNTOPAGOS_URL = $conf[$enviroment]["url"];
		$PUNTOPAGOS_SECRET = $conf[$enviroment]["secret"];
		$PUNTOPAGOS_KEY = $conf[$enviroment]["key"];
		
		$funcion = "transaccion/traer";
		$trx_id = $order->getId();
		$ammount = $order->getPrice();
		$ammount_str = number_format($ammount, 2, '.', '');
		
		$http_request = "";
		
		$fecha = gmdate("D, d M Y H:i:s", time())." GMT";
		$mensaje = $funcion."\n".$token."\n".$trx_id."\n".$ammount_str."\n".$fecha;
		$signature = base64_encode(hash_hmac('sha1', $mensaje, $PUNTOPAGOS_SECRET, true));
		$firma = "PP ".$PUNTOPAGOS_KEY.":".$signature;

		$header = array();
		$http_request .= $header[] = "Accept: application/json;";
		$http_request .= $header[] = "Accept-Charset: utf-8;";
		$http_request .= $header[] = "Accept-Language: en-us,en;q=0.5";
		$http_request .= $header[] = "Content-type: application/json";
		$http_request .= $header[] = "Fecha: ".$fecha;
		$http_request .= $header[] = "Autorizacion: ".$firma;

		$url_pp = $PUNTOPAGOS_URL."/transaccion/".$token;
		
		$curl = curl_init($url_pp);
		curl_setopt($curl, CURL_VERSION_SSL,"SSL_VERSION_SSLv3");
		curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
		curl_setopt($curl, CURL_HTTP_VERSION_1_1, 1);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		
		$http_response = curl_exec ($curl);
		
		$response = json_decode($http_response);
		
		curl_close($curl);
		
		if($response->error != null){
			$this->_log("Notificacion","Error","Usuario: ".$customer_in_session.". Order ID: ".$order->getId().". Token Punto Pagos: ".$response->token.". JSON Punto Pagos: ".$http_response);
		}else{
			$this->_log("Notificacion","Exito","Usuario: ".$customer_in_session.". Order ID: ".$order->getId().". Token Punto Pagos: ".$response->token.". JSON Punto Pagos: ".$http_response);
		}
	
	}
  }
  
  protected function _log($step,$status,$msg){
	
	$logPath = sfConfig::get('sf_log_dir').'/puntopagos.log';
	$custom_logger = new sfFileLogger(new sfEventDispatcher(), array('file' => $logPath));
	$custom_logger->info($step." - ".$status.". ".$msg);
	
  }
  
}
