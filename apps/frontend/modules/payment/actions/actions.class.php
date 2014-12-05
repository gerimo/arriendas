<?php

/**
 * payment actions.
 *
 * @package    CarSharing
 * @subpackage payment
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class paymentActions extends sfActions {

    /**
     * Presenta el formulario con los medidos de pago de Punto Pagos
     *
     * @param sfRequest $request A request object
     */
     
    public function enviarCorreoTransferenciaBancaria() {
        $idUsuario = sfContext::getInstance()->getUser()->getAttribute('userid');
        $usuario = Doctrine_Core::getTable('user')->findOneById($idUsuario);

        $correo = $usuario->getEmail();
        $name = $usuario->getFirstname();

        require sfConfig::get('sf_app_lib_dir') . "/mail/mail.php";
        $mail = new Email();
        $mail->setSubject('Realizar Depósito en Garantía');
        $mailBody = "<p>Hola $name</p><p>Realiza el depósito en garantía por transferecia bancaria de <b>$180.000</b>:</p><p><ul><li><b>Banco BCI</b></li><li>Cuenta Corriente: <b>70107459</b></li><li>Rut: <b>76208249-7</b></li><li><b>Rimoldi SPA</b></li><li><b>soporte@arriendas.cl</b></li></ul></p>";
        $mailBody .= "<p>Si pagas con Khipu, el deposito en garantía se debitará automáticamente.</p>";
        $mail->setBody($mailBody);
        $mail->setTo($correo);
        $mail->setCc('soporte@arriendas.cl');
        $mail->submit();
    }

    /**
     * Executes index action
     *
     * @param sfRequest $request A request object
     */
    public function executeIndex(sfWebRequest $request) {
        $this->carMarcaModel = $request->getParameter("carMarcaModel");
        $this->duracionReserva = $request->getParameter("duracionReserva");
        $montoTotalPagoPorDia = $request->getParameter("duracionReservaPagoPorDia");
        $this->montoTotalPagoPorDia = $montoTotalPagoPorDia;


        $this->deposito = $request->getParameter("deposito");
        $this->montoDeposito = 0;
        if ($this->deposito == "depositoGarantia") {
            //$deposito = Doctrine_Core::getTable("liberacionDeposito")->findById(2);
            $this->montoDeposito = sfConfig::get("app_monto_garantia");
            /*$this->enviarCorreoTransferenciaBancaria();*/
        } else if ($this->deposito == "pagoPorDia") {
            //$deposito = Doctrine_Core::getTable("liberacionDeposito")->findById(1);
            $this->montoDeposito = $montoTotalPagoPorDia;
        }

        $customer_in_session = $this->getUser()->getAttribute('userid');

        if ($customer_in_session) {

            $this->ppId = null;
            if (!$request->hasParameter("id")) {
                $this->error = 'No se ha recibido un codigo de reserva';
            } else {

                try {
                    $order = Doctrine_Core::getTable("Transaction")->getTransactionByReserve($request->getParameter("id"));
                    $idReserve = $order->getReserveId();
                    $reserve = Doctrine_Core::getTable('reserve')->findOneById($idReserve);
                    if ($this->deposito == "depositoGarantia") {
                        $reserve->setLiberadoDeGarantia(0);
                        $reserve->setMontoLiberacion($this->montoDeposito);
                    } else if ($this->deposito == "pagoPorDia") {
                        $reserve->setLiberadoDeGarantia(1);
                        $reserve->setMontoLiberacion($this->montoDeposito);
                    }
                    $reserve->save();

                    $this->hasDiscountFB = $order->getDiscountfb();
                    $this->priceMultiply = 1 - (0.05 * $order->getDiscountfb());

                    if ($order != false) {

                        $this->ppId = $order->getId();
                        $this->ppMonto = $order->getPrice(); //reemplazar por metodo getMonto
                        $this->ppIdReserva = $request->getParameter("id");

                        if ($this->ppMonto <= 0 || $this->ppMonto == null) {

                            $this->error = 'Monto invalido o cero en transaccion #' . $this->ppId . '. No es posible procesar en Punto Pagos.';
                            $this->_log("Formulario", "Error", "Usuario: " . $customer_in_session . ". Order ID: " . $order->getId() . ". Error: " . $this->error);
                            $this->ppId = null;
                        }
                    } else {

                        $this->error = 'Orden no existe o ya esta procesada para la reserva: ' . $request->getParameter("id");
                        $this->_log("Formulario", "Error", "Usuario: " . $customer_in_session . ". Error: " . $this->error);
                        $this->ppId = null;
                    }

                    $this->isForeign = $reserve->getUser()->getExtranjero();
                } catch (Exception $e) {

                    $this->error = 'Ha ocurrido un error tratando de procesar el codigo de reserva indicado.<br/>' . $e->getMessage();
                    $this->_log("Formulario", "Error", "Usuario: " . $customer_in_session . ". Error: " . $this->error);
                    $this->ppId = null;
                    Utils::reportError($e->getMessage(), "payment/executeIndex");
                }
            }
        } else {
            $this->redirect('@homepage');
        }
    }

    /**
     * Crea la transaccion hacia punto pagos segun datos de config/puntopagos.yml
     *
     * @param sfRequest $request A request object
     */
    public function executeCreacion(sfWebRequest $request) {
        $this->carMarcaModel = $request->getParameter("carMarcaModel");
        $this->duracionReserva = $request->getParameter("duracionReserva");

        $customer_in_session = $this->getUser()->getAttribute('userid');

        if ($customer_in_session) {

            if (!$request->hasParameter("id") || $request->getParameter("id") == null) {

                $this->getUser()->setFlash('error', 'No se ha recibido un codigo de orden para la creacion en Punto Pagos');
                $this->redirect("payment/index");
            } else {

                if (!$request->hasParameter("pp_medio_pago") || $request->getParameter("pp_medio_pago") == null) {
                    $this->getUser()->setFlash('error', 'Debe especificar un medio de pago');
                    $this->redirect("payment/index?id=" . $request->getParameter("idReserva"));
                }
                
                /* pago con transbank */
                if ($request->getParameter("pp_medio_pago") == "3") {
                    $this->forward("webpay", "confirmPayment");
                }
                
                /* pago con punto pagos */
//                if ($request->getParameter("pp_medio_pago") == "3") {
//                    $this->forward("bcpuntopagos", "creacion");
//                }
                if ($request->getParameter("pp_medio_pago") == "20") {
                    $this->forward("paypal", "confirmPayment");
                }
                if ($request->getParameter("pp_medio_pago") == "21") {
                    $this->forward("khipu", "confirmPayment");
                }
            }
        } else {
            $this->redirect('@homepage');
        }
    }

}
