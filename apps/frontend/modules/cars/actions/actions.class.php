<?php

class carsActions extends sfActions {

    public function executeCreate(sfWebRequest $request){
          $this->setLayout("newIndexLayout");
          $this->Communes = Commune::getByRegion(false);
          $this->Brands = Brand::getBrand();
    }

    public function executeGetModels(sfWebRequest $request) {

        $return = array("error" => false);

        try {

            $brandId = $request->getPostParameter("brandId", null);

            if (is_null($brandId) || $brandId == "" || $brandId == 0) {
                throw new Exception("Falta marca", 1);
            }

            $return["models"] = Model::getByBrand($brandId);

        } catch (Exception $e) {
            $return["error"] = true;
            $return["errorCode"] = $e->getCode();
            $return["errorMessage"] = $e->getMessage();
        }

        error_log("T: ".count($return["models"]));

        $this->renderText(json_encode($return));
        
        return sfView::NONE;
    }

    ////////////////////////////////////////

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

    public function recortar_texto($texto, $limite=100){   
        $texto = trim($texto);
        $texto = strip_tags($texto);
        $tamano = strlen($texto);
        $resultado = '';
        if($tamano <= $limite){
            return $texto;
        }else{
            $texto = substr($texto, 0, $limite);
            $palabras = explode(' ', $texto);
            $resultado = implode(' ', $palabras);
            $resultado .= '...';
        }   
        return $resultado;
    }

    public function executeCar(sfWebRequest $request) {

        
        $this->car = Doctrine_Core::getTable('car')->find(array($request->getParameter('id')));
        $id_comuna=$this->car->getComunaId($request->getParameter('id'));
        $comuna = Doctrine_Core::getTable('comunas')->findOneByCodigoInterno($id_comuna);
        $nombreComuna = strtolower($comuna->getNombre());

        $this->redirect('auto/economico?chile='.$nombreComuna.'&id='.$request->getParameter('id'));

        //Versi�n anterior: No se ocupa.

        $this->df = ''; if($request->getParameter('df')) $this->df = $request->getParameter('df');
        $this->hf = ''; if($request->getParameter('hf')) $this->df = $request->getParameter('hf');
        $this->dt = ''; if($request->getParameter('dt')) $this->df = $request->getParameter('dt');
        $this->ht = ''; if($request->getParameter('ht')) $this->df = $request->getParameter('ht');

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
        $this->nombreAcortado = $this->recortar_texto($this->user->getFirstname()." ". $this->user->getLastname(), 15);*/
        $this->primerNombre = ucwords(current(explode(' ' ,$this->user->getFirstname())));
        $this->inicialApellido = ucwords(substr($this->user->getLastName(), 0, 1)).".";
        //Modificaci�n para llevar el conteo de la cantidad de consulas que recibe el perfil del auto
        $q= Doctrine_Query::create()
            ->update("car")
            ->set("consultas","consultas + 1")
            ->where("id = ?",$request->getParameter('id'));
        $q->execute();

        //Cargamos las fotos por defecto de los autos
        $auto= Doctrine_Core::getTable('car')->find(array($request->getParameter('id')));
        $id_comuna=$auto->getComunaId($request->getParameter('id'));

        if($id_comuna){
            $comuna = Doctrine_Core::getTable('comunas')->findOneByCodigoInterno($id_comuna);
            if($comuna->getNombre() == null){
                $this->nombreComunaAuto = ", ".$auto->getCity().".";
            }else{
                $comuna=strtolower($comuna->getNombre());
                $comuna=ucwords($comuna);
                //$this->nombreComunaAuto =", ".$comuna.", ".$auto->getCity();
                $this->nombreComunaAuto =", ".$comuna.".";
            }
        }else{
            $this->nombreComunaAuto = "";
        }
        $arrayImagenes = null;
        $arrayDescripcion = null;
        $i=0;
        if($auto->getSeguroFotoFrente() != null && $auto->getSeguroFotoFrente() != "") {
            $rutaFotoFrente=$auto->getSeguroFotoFrente();
            $arrayImagenes[$i] = $rutaFotoFrente;
            $arrayDescripcion[$i] = "Foto Frente";
            $i++;
        }
        if($auto->getSeguroFotoCostadoDerecho() != null && $auto->getSeguroFotoCostadoDerecho() != "") {
            $rutaFotoCostadoDerecho=$auto->getSeguroFotoCostadoDerecho();
            $arrayImagenes[$i] = $rutaFotoCostadoDerecho;
            $arrayDescripcion[$i] = "Foto Costado Derecho";
            $i++;
        }
        if(strpos($auto->getSeguroFotoCostadoIzquierdo(),"http")!=-1 && $auto->getSeguroFotoCostadoIzquierdo() != "") {
            $rutaFotoCostadoIzquierdo=$auto->getSeguroFotoCostadoIzquierdo();
            $arrayImagenes[$i] = $rutaFotoCostadoIzquierdo;
            $arrayDescripcion[$i] = "Foto Costado Izquierdo";
            $i++;
        }
        if(strpos($auto->getSeguroFotoTraseroDerecho(),"http")!=-1 && $auto->getSeguroFotoTraseroDerecho() != "") {
            $rutaFotoTrasera= $auto->getSeguroFotoTraseroDerecho();
            $arrayImagenes[$i] = $rutaFotoTrasera;
            $arrayDescripcion[$i] = "Foto Trasera";
            $i++;
        }   
        if(strpos($auto->getTablero(),"http")!=-1 && $auto->getTablero() != "") {
            $rutaFotoPanel=$auto->getTablero();  
            $arrayImagenes[$i] = $rutaFotoPanel;
            $arrayDescripcion[$i] = "Foto del Panel";
            $i++; 
        }
        if(strpos($auto->getAccesorio1(),"http")!=-1 && $auto->getAccesorio1() != "") {
            $rutaFotoAccesorios1= $auto->getAccesorio1();
            $arrayImagenes[$i] = $rutaFotoAccesorios1;
            $arrayDescripcion[$i] = "Foto de Accesorio 1";
            $i++; 
        }  
        if(strpos($auto->getAccesorio2(),"http")!=-1 && $auto->getAccesorio2() != "") {
            $rutaFotoAccesorios2=$auto->getAccesorio2();
            $arrayImagenes[$i] = $rutaFotoAccesorios2;
            $arrayDescripcion[$i] = "Foto de Accesorio 2";
        }
        $this->arrayDescripcionFotos = $arrayDescripcion;
        $this->arrayFotos = $arrayImagenes;
        $arrayFotoDanios = null;
        $arrayDescripcionDanios = null;
        $danios= Doctrine_Core::getTable('damage')->findByCar(array($auto->getId()));
        for($i=0;$i<count($danios);$i++){
            $arrayFotoDanios[$i] = $danios[$i]->getUrlFoto();
            $arrayDescripcionDanios[$i] = $danios[$i]->getDescription();
        }
        $this->arrayFotosDanios = $arrayFotoDanios;
        $this->arrayDescripcionesDanios = $arrayDescripcionDanios;
    }

    public function executeGetByReserserVehicleType(sfWebRequest $request) {
        $availableCars = array();
        $reserve = Doctrine_Core::getTable('reserve')->find($request->getParameter("reserve_id"));
        $cars = Doctrine_Core::getTable('user')->find(array($this->getUser()->getAttribute("userid")))->getCars();
        foreach ($cars as $car) {
            if ($car->getSeguroOk() == 4 && $car->getActivo() == 1 && $car->getActivo() == 1 && $reserve->getCar()->getModel()->getIdTipoVehiculo() == $car->getModel()->getIdTipoVehiculo()) {
                $carArray = array(
                  "id" => $car->getId(),
                  "model" => $car->getModel()->__toString(),
                  "brand" => $car->getModel()->getBrand()->__toString(),
                  "patente" => $car->getPatente()
                );
                $availableCars[] = $carArray;
            }
        }
        $this->getResponse()->setContentType('application/json');
        return $this->renderText(json_encode($availableCars));
    }
}
