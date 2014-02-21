<?php

/**
 * auto actions.
 *
 * @package    AutoSharing
 * @subpackage auto
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class autoActions extends sfActions {

    /**
     * Executes index action
     *
     * @param sfRequest $request A request object
     */
    public function executeIndex(sfWebRequest $request) {


        $q = Doctrine_Query::create()
                ->select('ca.id , av.id idav , mo.name model, 
		  br.name brand, ca.year year, 
		  ca.address address, ci.name city, 
		  st.name state, co.name country'
                )
                ->from('Car ca')
                ->innerJoin('ca.Availabilities av')
                ->innerJoin('ca.Model mo')
                ->innerJoin('mo.Brand br')
                ->innerJoin('ca.City ci')
                ->innerJoin('ci.State st')
                ->innerJoin('st.Country co');
        $this->cars = $q->execute();
    }

    public function recortar_texto($texto, $limite = 100) {
        $texto = trim($texto);
        $texto = strip_tags($texto);
        $tamano = strlen($texto);
        $resultado = '';
        if ($tamano <= $limite) {
            return $texto;
        } else {
            $texto = substr($texto, 0, $limite);
            $palabras = explode(' ', $texto);
            $resultado = implode(' ', $palabras);
            $resultado .= '...';
        }
        return $resultado;
    }

    public function executeEconomico(sfWebRequest $request) {


        $nombreComunaURL = $request->getParameter('chile');
        $comunaURL = Doctrine_Core::getTable('comunas')->findOneByNombre($nombreComunaURL);
        if (!$comunaURL)
            $this->redirect('auto/error?tipo=2');
        else
            $codigoInterno_comunaURL = $comunaURL->getCodigoInterno();

        $this->car = Doctrine_Core::getTable('car')->find(array($request->getParameter('id')));


        $this->df = '';
        if ($request->getParameter('df'))
            $this->df = $request->getParameter('df');
        $this->hf = '';
        if ($request->getParameter('hf'))
            $this->df = $request->getParameter('hf');
        $this->dt = '';
        if ($request->getParameter('dt'))
            $this->df = $request->getParameter('dt');
        $this->ht = '';
        if ($request->getParameter('ht'))
            $this->df = $request->getParameter('ht');

        /*
          //si el due�o del auto lo ve, es redireccionado
          if ($this->car->getUser()->getId() == $this->getUser()->getAttribute("userid")) {
          $this->forward('profile', 'cars');
          }
         */
        $this->user = $this->car->getUser();
        //$idDuenioCar = $this->user->getId();
        $this->aprobacionDuenio = $this->user->getPorcentajeAprobacion();
        $this->velocidadMensajes = $this->user->getVelocidadRespuesta_mensajes();

        /*
          $this->nombreAcortado =	$this->recortar_texto($this->user->getFirstname()." ". $this->user->getLastname(), 15); */
        $this->primerNombre = ucwords(current(explode(' ', $this->user->getFirstname())));
        $this->inicialApellido = ucwords(substr($this->user->getLastName(), 0, 1)) . ".";
        //Modificaci�n para llevar el conteo de la cantidad de consulas que recibe el perfil del auto

        $q = Doctrine_Query::create()
                ->update("car")
                ->set("consultas", "consultas + 1")
                ->where("id = ?", $request->getParameter('id'));
        $q->execute();

        //Cargamos las fotos por defecto de los autos
        //unnecessary: the car was already loaded ($this->car). 
        //$auto= Doctrine_Core::getTable('car')->find(array($request->getParameter('id')));
        $auto = $this->car;

        $tipoTrans = $auto->getTransmission();
        if ($tipoTrans == 0)
            $this->transmision = "Manual";
        if ($tipoTrans == 1)
            $this->transmision = "Autom&aacute;tica";



        $id_comuna = $auto->getComunaId($request->getParameter('id'));

        if ($id_comuna == $codigoInterno_comunaURL) { //Si las comunas del auto y de la URL son las mismas
            if ($id_comuna) {
                $comuna = Doctrine_Core::getTable('comunas')->findOneByCodigoInterno($id_comuna);
                if ($comuna->getNombre() == null) {
                    $this->nombreComunaAuto = $auto->getCity() . ".";
                    $nombreComuna = $auto->getCity();
                } else {
                    $comuna = strtolower($comuna->getNombre());
                    $comuna = ucwords($comuna);
                    $this->nombreComunaAuto = $comuna . ", " . $auto->getCity();
                    $nombreComuna = $comuna . ", " . $auto->getCity();
                }
            } else {
                $this->nombreComunaAuto = "";
            }
            $arrayImagenes = null;
            $arrayDescripcion = null;
            $i = 0;
            if ($auto->getSeguroFotoFrente() != null && $auto->getSeguroFotoFrente() != "") {
                $rutaFotoFrente = $auto->getSeguroFotoFrente();
                $arrayImagenes[$i] = $rutaFotoFrente;
                $arrayDescripcion[$i] = "Foto Frente";
                $i++;
            }
            if ($auto->getSeguroFotoCostadoDerecho() != null && $auto->getSeguroFotoCostadoDerecho() != "") {
                $rutaFotoCostadoDerecho = $auto->getSeguroFotoCostadoDerecho();
                $arrayImagenes[$i] = $rutaFotoCostadoDerecho;
                $arrayDescripcion[$i] = "Foto Costado Derecho";
                $i++;
            }
            if (strpos($auto->getSeguroFotoCostadoIzquierdo(), "http") != -1 && $auto->getSeguroFotoCostadoIzquierdo() != "") {
                $rutaFotoCostadoIzquierdo = $auto->getSeguroFotoCostadoIzquierdo();
                $arrayImagenes[$i] = $rutaFotoCostadoIzquierdo;
                $arrayDescripcion[$i] = "Foto Costado Izquierdo";
                $i++;
            }
            if (strpos($auto->getSeguroFotoTraseroDerecho(), "http") != -1 && $auto->getSeguroFotoTraseroDerecho() != "") {
                $rutaFotoTrasera = $auto->getSeguroFotoTraseroDerecho();
                $arrayImagenes[$i] = $rutaFotoTrasera;
                $arrayDescripcion[$i] = "Foto Trasera";
                $i++;
            }
            if (strpos($auto->getTablero(), "http") != -1 && $auto->getTablero() != "") {
                $rutaFotoPanel = $auto->getTablero();
                $arrayImagenes[$i] = $rutaFotoPanel;
                $arrayDescripcion[$i] = "Foto del Panel";
                $i++;
            }
            if (strpos($auto->getAccesorio1(), "http") != -1 && $auto->getAccesorio1() != "") {
                $rutaFotoAccesorios1 = $auto->getAccesorio1();
                $arrayImagenes[$i] = $rutaFotoAccesorios1;
                $arrayDescripcion[$i] = "Foto de Accesorio 1";
                $i++;
            }
            if (strpos($auto->getAccesorio2(), "http") != -1 && $auto->getAccesorio2() != "") {
                $rutaFotoAccesorios2 = $auto->getAccesorio2();
                $arrayImagenes[$i] = $rutaFotoAccesorios2;
                $arrayDescripcion[$i] = "Foto de Accesorio 2";
            }
            $this->opcionFotosEnS3 = $auto->getVerificationPhotoS3();
            $this->arrayDescripcionFotos = $arrayDescripcion;
            $this->arrayFotos = $arrayImagenes;
            $arrayFotoDanios = null;
            $arrayDescripcionDanios = null;
            $danios = Doctrine_Core::getTable('damage')->findByCar(array($auto->getId()));
            for ($i = 0; $i < count($danios); $i++) {
                $arrayFotoDanios[$i] = $danios[$i]->getUrlFoto();
                $arrayDescripcionDanios[$i] = $danios[$i]->getDescription();
            }
            $this->arrayFotosDanios = $arrayFotoDanios;
            $this->arrayDescripcionesDanios = $arrayDescripcionDanios;

            //obtiene los comentarios (texto, nombre, fecha, fotoPerfil)
            $misComentarios = $this->user->getMyComments_aboutMe();
            $comentariosOrd = array();
            for ($y = 0; $y < count($misComentarios); $y++) { //Ordeno del mas actual, al mas antiguo
                $comentariosOrd[$y] = $misComentarios[(count($misComentarios) - 1) - $y];
            }
            $this->comentarios = $comentariosOrd;
            $this->puntualidad = $this->user->getScorePuntualidad();
            $this->limpieza = $this->user->getScoreLimpieza();
            
            $title = "Arriendo ".$this->car->getModel() . " " . $this->car->getModel()->getBrand()." en " . $nombreComuna . " - Rent a Car Vecino";
            $this->getResponse()->setTitle($title);
            
        } else {
            $this->redirect('auto/error?tipo=1');
        }
    }

    public function executeError(sfWebRequest $request) {
        $errorTipo = $request->getParameter('tipo');
        $this->redireccionamiento = TRUE;
        if ($errorTipo == 1)
            $this->mensajeError = "Error de comunas inconsistentes";
        if ($errorTipo == 2) {
            $this->mensajeError = "El auto no tiene comuna registrada";
            $this->redireccionamiento = FALSE;
        }
    }

}
