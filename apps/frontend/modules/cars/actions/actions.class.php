<?php

class carsActions extends sfActions {

    /*editar auto*/   
    public function executeEdit(sfWebRequest $request){
        $this->setLayout("newIndexLayout");

        $carId          = $request->getParameter("id", null);
        $userId         = sfContext::getInstance()->getUser()->getAttribute('userid');
        $this->Communes = Commune::getByRegion(false);  
        $this->Brands   = Brand::getBrand();
        $this->Car      = Doctrine_Core::getTable('car')->find($carId);
        if (!$this->Car) {
                throw new Exception("Vehículo ".$carId." no encontrado", 1);
            }

        $this->CarTypes = CarType::getCarType();

        if($userId != $this->Car->getUserId()){

            error_log("[".date("Y-m-d H:i:s")."] [cars/edit] ERROR: Al entrar a la vista de editar el usuario ".$userId." a tratado de editar el vehiculo".$this->Car->getId()." que no es suyo. ");
            $this->redirect('homepage');
        }

        /*precios*/
        $price = $this->Car->getModel()->getPrice();
        $priceDay = $price/300;
        $this->day   = round($priceDay,-2);
        $this->week  = round((($priceDay * 7)*0.9),-2);
        $this->hour  = round(($priceDay/6),-2);
        $this->month = round((($priceDay * 30) * 0.7),-2);

        
    }

    public function executeGetChecked(sfWebRequest $request){

        $return = array("error" => false);

        try {

            $option     = (int)$request->getPostParameter("option", null);
            $isChecked  = $request->getPostParameter("isChecked", null);
            $carId      = $request->getPostParameter("carId", null);

            $Car  = Doctrine_Core::getTable('car')->find($carId);
            $options = $Car->options;

            if ($isChecked) {
                if (!($options & $option)) {
                    $options += $option;
                }
            }

            if (!$isChecked) {
                if ($options & $option) {
                    $options -= $option;
                }
            }

            $Car->setOptions($options);

            $Car->save();

        } catch (Exception $e) {
            $return["error"] = true;
            $return["errorCode"] = $e->getCode();
            $return["errorMessage"] = $e->getMessage();
        }

        $this->renderText(json_encode($return));
        
        return sfView::NONE;
        
    }

    /*Crear auto vista 1*/    
    
    public function executeCreate(sfWebRequest $request){
          $this->setLayout("newIndexLayout");
          $this->Communes = Commune::getByRegion(false);
          $this->Brands = Brand::getBrand();
          $this->CarTypes = CarType::getCarType();

          $carId = $request->getParameter("c", null);     
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

        $this->renderText(json_encode($return));
        
        return sfView::NONE;
    }

    public function executeGetValidateCar(sfWebRequest $request) {
        
        $return = array("error" => false);

        try {

            $address        = $request->getPostParameter("address", null);
            $commune        = $request->getPostParameter("commune", null);
            $brand          = $request->getPostParameter("brand", null);
            $model          = $request->getPostParameter("model", null);
            $ano            = $request->getPostParameter("ano", null);
            $door           = $request->getPostParameter("door", null);
            $transmission   = $request->getPostParameter("transmission", null);
            $benzine        = $request->getPostParameter("benzine", null);
            $typeCar        = $request->getPostParameter("typeCar", null);
            $patent         = $request->getPostParameter("patent", null);
            $color          = $request->getPostParameter("color", null);
            $lat            = $request->getPostParameter("lat", null);
            $lng            = $request->getPostParameter("lng", null);
            $carId          = $request->getPostParameter("carId", null);


            /*$userId_session = $this->getUser()->getAttribute("userid");
             error_log($userId_session);*/

            $idUsuario = sfContext::getInstance()->getUser()->getAttribute('userid');

            if (is_null($address) || $address == "") {
                throw new Exception("Debes indicar una dirección", 1);
            }

            if (is_null($commune) || $commune == "") {
                throw new Exception("Debes indicar una comuna", 2);
            }

            if (is_null($brand) || $brand == "") {
                throw new Exception("Debes indicar una marca", 3);
            }

            if (is_null($model) || $model == "") {
                throw new Exception("Debes indicar un modelo", 4);
            }

            if (is_null($ano) || $ano == "") {
                throw new Exception("Debes indicar un año", 5);
            }

            if (is_null($door) || $door == "") {
                throw new Exception("Debes indicar el n° de puertas", 6);
            }   

            if (is_null($transmission) || $transmission == "") {
                throw new Exception("Debes indicar una transmisión", 7);
            }

            if (is_null($benzine) || $benzine == "") {
                throw new Exception("Debes indicar el tipo de bencina", 8);
            }

            if (is_null($typeCar) || $typeCar == "") {
                throw new Exception("Debes indicar el tipo de vehículo", 9);
            }

            if (is_null($patent) || $patent == "") {
                throw new Exception("Debes indicar la patente", 10);
            }

            if (is_null($color) || $color == "") {
                throw new Exception("Debes indicar el color", 11);
            }

            if (is_null($idUsuario) || $idUsuario == "") {
                $this->redirect('main/index');
            }

            if (is_null($lat) || $lat == "") {
                throw new Exception("Debes indicar una dirección", 1);
            }

            /*if ((is_null($User) && $User->getConfirmed()) || !$User->getConfirmedFb()) {
                $this->redirect('main/index');
            }*/
            if (is_null($carId) || $carId == "") {
                $Car = new Car();
            }else{
                $Car = Doctrine_Core::getTable('car')->find($carId);
            }
            
            $fechaHoy = Date("Y-m-d");

            $Car->setAddress($address);
            $Car->setCommuneId($commune);
            $Car->setModelId($model);
            $Car->setYear($ano);
            $Car->setAddress($address);
            $Car->setDoors($door);
            $Car->setTransmission($transmission);
            $Car->setTipoBencina($benzine);
            $Car->setPatente($patent);
            $Car->setUserId($idUsuario);
            $Car->setFechaSubida($fechaHoy);
            $Car->setColor($color);
            $Car->setLat($lat);
            $Car->setLng($lng);
            $Car->setCityId(27);

            $Car->save();

            $this->getUser()->setAttribute("carId", $Car->getId());


            $url = $this->generateUrl('car_price');
            $return["url_complete"] = $url;

        } catch (Exception $e) {
            $return["error"] = true;
            $return["errorCode"] = $e->getCode();
            $return["errorMessage"] = $e->getMessage();
        }

        $this->renderText(json_encode($return));
        return sfView::NONE;
    }

    public function executeGetValidatePatent(sfWebRequest $request){

        $return = array("error" => false);

        try {

            $patente = $request->getPostParameter("patente", null);
            $carId = $request->getPostParameter("carId", null);

            $regex = '/^[a-z]{2}[\.\ ]?[0-9]{2}[\.\ ]?[0-9]{2}|[b-d,f-h,j-l,p,r-t,v-z]{2}[\-\. ]?[b-d,f-h,j-l,p,r-t,v-z]{2}[\.\- ]?[0-9]{2}$/i';

            if ((!preg_match($regex, $patente)) && ($patente != "")) {
                throw new Exception("Debes ingresar una patente valida", 1);
            }

            if(($patente != "") && ($patente != null)){    
                $result = Car::getExistPatent($patente, $carId);

                if (count($result) > 0) {
                    throw new Exception("patente ya existe!", 2);
                }
            }


        } catch (Exception $e) {
            $return["error"] = true;
            $return["errorCode"] = $e->getCode();
            $return["errorMessage"] = $e->getMessage();
        }

        $this->renderText(json_encode($return));
        
        return sfView::NONE;
    }

    /*Crear auto vista 2*/

    public function executePrice(sfWebRequest $request){
            $this->setLayout("newIndexLayout");
            $carId= sfContext::getInstance()->getUser()->getAttribute('carId');
            $this->Car = Doctrine_Core::getTable('car')->find($carId);

            $price = $this->Car->getModel()->getPrice();
            $priceDay = $price/300;
            $this->day   = round($priceDay,-2);
            $this->week  = round((($priceDay * 7)*0.9),-2);
            $this->hour  = round(($priceDay/6),-2);
            $this->month = round((($priceDay * 30) * 0.7),-2);
    }

    public function executeGetValidatePrice(sfWebRequest $request) {
        
        $return = array("error" => false);

        try {

            $priceHour        = $request->getPostParameter("priceHour", null);
            $priceDay         = $request->getPostParameter("priceDay", null);
            $priceWeek        = $request->getPostParameter("priceWeek", null);
            $priceMonth       = $request->getPostParameter("priceMonth", null);
            $carId            = $request->getPostParameter("carId", null);

            if (is_null($priceHour) || $priceHour == "") {
                throw new Exception("Debes indicar el precio hora", 1);
            }

            if (is_null($priceDay) || $priceDay == "") {
                throw new Exception("Debes indicar el precio dia", 2);
            }

            if($priceHour < 4000){
                throw new Exception("El valor mínimo por hora es $4.000", 3);
            }

            $Car = Doctrine_Core::getTable('car')->find($carId);

            $Car->setPricePerHour($priceHour);
            $Car->setPricePerDay($priceDay);
            $Car->setPricePerWeek($priceWeek);
            $Car->setPricePerMonth($priceMonth);
            $Car->save();



            $url = $this->generateUrl('car_availability');
            $return["url_complete"] = $url;

        } catch (Exception $e) {
            $return["error"] = true;
            $return["errorCode"] = $e->getCode();
            $return["errorMessage"] = $e->getMessage();
        }

        $this->renderText(json_encode($return));
        return sfView::NONE;
    }

    /*Crear auto vista 3*/
    public function executeAvailability(sfWebRequest $request){
            $this->setLayout("newIndexLayout");
            $carId= sfContext::getInstance()->getUser()->getAttribute('carId');
            $this->Car = Doctrine_Core::getTable('car')->find($carId);

            /*$year = $this->Car->getYear();
            $model = $this->Car->getModelId();
            $this->Datos = Car::getSameCar($model, $year);*/
    }

    public function executeGetValidateAvailability(sfWebRequest $request) {
        
        $return = array("error" => false);

        try {

            $option1     = $request->getPostParameter("option1", null);
            $option2     = $request->getPostParameter("option2", null);
            $option3     = $request->getPostParameter("option3", null);
            $option4     = $request->getPostParameter("option4", null);
            $carId       = $request->getPostParameter("carId", null);
            $total = 0;
            if (!is_null($option1)) {
                $total += $option1;
            }
            if (!is_null($option2)) {
                $total += $option2;
            }
            if ($total == 0){
                throw new Exception("Debe elegir almeno una opción", 1);   
            }
            if (!is_null($option3)) {
                $total += $option3;
            }
            if (!is_null($option4)) {
                $total += $option4;
            }
            
            $Car = Doctrine_Core::getTable('car')->find($carId);
           
            $Car->setOptions($total);

            $Car->save();

            $url = $this->generateUrl('car_photo');
            $return["url_complete"] = $url;



        } catch (Exception $e) {
            $return["error"] = true;
            $return["errorCode"] = $e->getCode();
            $return["errorMessage"] = $e->getMessage();
        }

        $this->renderText(json_encode($return));
        return sfView::NONE;
    }

    /*Crear auto vista 4*/
    public function executePhoto(sfWebRequest $request){
            $this->setLayout("newIndexLayout");
            $carId= sfContext::getInstance()->getUser()->getAttribute('carId');
            $this->Car = Doctrine_Core::getTable('car')->find($carId);

            /*$year = $this->Car->getYear();
            $model = $this->Car->getModelId();
            $this->Datos = Car::getSameCar($model, $year);*/
    }
    /*fotos auto*/
    public function executeUploadPhoto(sfWebRequest $request) {

        $return = array("error" => false);
        $valid_formats = array("jpg", "png", "gif", "bmp", "jpeg");

        try {

            if (!isset($_POST) || $_SERVER['REQUEST_METHOD'] != "POST") {
                throw new Exception("No! No! No!", 1);
            }

            $name = $_FILES[$request->getParameter('file')]['name'];
            $size = $_FILES[$request->getParameter('file')]['size'];
            $tmp  = $_FILES[$request->getParameter('file')]['tmp_name'];
            if (!strlen($name)) {
                throw new Exception("Por favor, seleccione una imagen", 2);
            }

            list($txt, $ext) = explode(".", $name);

            $ext = strtolower($ext);

            if (!in_array($ext, $valid_formats)) {
                throw new Exception("Formato de la imagen inválido", 2);
            }

            if ($size > (5 * 1024 * 1024)) {
                throw new Exception("La imagen excede el tamaño máximo permitido (1 MB)", 2);                
            }
            
            /*$sizewh = getimagesize($tmp);

            if($sizewh[0] > 194 || $sizewh[1] > 204) {
                echo '<script>alert(\'La imagen no cumple con las dimensiones especificadas. Puede continuar si lo desea\')</script>';
            }*/
            $carId= sfContext::getInstance()->getUser()->getAttribute('carId');
            $actual_image_name = time() . $carId . "." . $ext;

            $uploadDir = sfConfig::get("sf_web_dir");
            $path      = $uploadDir . '/images/cars/';
            $fileName  = $actual_image_name . "." . $ext;

            $tmp = $_FILES[$request->getParameter('file')]['tmp_name'];

            $uploaded = move_uploaded_file($tmp, $path . $actual_image_name);

            if (!$uploaded) {
                throw new Exception("No se pudo subir la imagen de perfil", 1);
            }

            sfContext::getInstance()->getConfiguration()->loadHelpers("Asset");

            /*echo "<input type='hidden' name='" . $request->getParameter('photo') . "' value='" . $path . $actual_image_name . "'/>";
            echo "<img src='" . image_path("users/" . $actual_image_name) . "' class='preview' height='" . $request->getParameter('height') . "' width='" . $request->getParameter('width') . "' />";*/

            $Car = Doctrine_Core::getTable('car')->find($carId);
            $Car->setFotoPerfil("/images/cars/".$actual_image_name);
            $Car->save();
        } catch (Exception $e) {
            $return["error"] = true;
            $return["errorMessage"] = $e->getMessage();
            if ($e->getCode() == 1) {
                $return["errorMessage"] = "Problemas al subir la imagen. El problema ha sido notificado al equipo de desarrollo, por favor, intentalo más tarde";
            }
            if ($request->getHost() == "www.arriendas.cl" && $e->getCode() == 1) {
                Utils::reportError($e->getMessage(), "main/uploadPhoto");
            }
        }

        $this->renderText(json_encode($return));

        return sfView::NONE;
    }

    public function executeUploadPhotoAccessory(sfWebRequest $request) {

        $return = array("error" => false);
        $valid_formats = array("jpg", "png", "gif", "bmp", "jpeg");

        try {

            if (!isset($_POST) || $_SERVER['REQUEST_METHOD'] != "POST") {
                throw new Exception("No! No! No!", 1);
            }

            $name = $_FILES[$request->getParameter('file')]['name'];
            $size = $_FILES[$request->getParameter('file')]['size'];
            $tmp  = $_FILES[$request->getParameter('file')]['tmp_name'];
            if (!strlen($name)) {
                throw new Exception("Por favor, seleccione una imagen", 2);
            }

            list($txt, $ext) = explode(".", $name);

            $ext = strtolower($ext);

            if (!in_array($ext, $valid_formats)) {
                throw new Exception("Formato de la imagen inválido", 2);
            }

            if ($size > (5 * 1024 * 1024)) {
                throw new Exception("La imagen excede el tamaño máximo permitido (1 MB)", 2);                
            }
            
            /*$sizewh = getimagesize($tmp);

            if($sizewh[0] > 194 || $sizewh[1] > 204) {
                echo '<script>alert(\'La imagen no cumple con las dimensiones especificadas. Puede continuar si lo desea\')</script>';
            }*/
            $carId= sfContext::getInstance()->getUser()->getAttribute('carId');
            $actual_image_name = time() . $carId . "." . $ext;

            $uploadDir = sfConfig::get("sf_web_dir");
            $path      = $uploadDir . '/images/cars/';
            $fileName  = $actual_image_name . "." . $ext;

            $tmp = $_FILES[$request->getParameter('file')]['tmp_name'];

            $uploaded = move_uploaded_file($tmp, $path . $actual_image_name);

            if (!$uploaded) {
                throw new Exception("No se pudo subir la imagen de perfil", 1);
            }

            sfContext::getInstance()->getConfiguration()->loadHelpers("Asset");

            /*echo "<input type='hidden' name='" . $request->getParameter('photo') . "' value='" . $path . $actual_image_name . "'/>";
            echo "<img src='" . image_path("users/" . $actual_image_name) . "' class='preview' height='" . $request->getParameter('height') . "' width='" . $request->getParameter('width') . "' />";*/

            $Car = Doctrine_Core::getTable('car')->find($carId);
            $Car->setAccesoriosSeguro("/images/cars/".$actual_image_name);
            $Car->save();
        } catch (Exception $e) {
            $return["error"] = true;
            $return["errorMessage"] = $e->getMessage();
            if ($e->getCode() == 1) {
                $return["errorMessage"] = "Problemas al subir la imagen. El problema ha sido notificado al equipo de desarrollo, por favor, intentalo más tarde";
            }
            if ($request->getHost() == "www.arriendas.cl" && $e->getCode() == 1) {
                Utils::reportError($e->getMessage(), "main/uploadPhoto");
            }
        }

        $this->renderText(json_encode($return));

        return sfView::NONE;
    }

    public function executeUploadPhotoSideRight(sfWebRequest $request) {

        $return = array("error" => false);
        $valid_formats = array("jpg", "png", "gif", "bmp", "jpeg");

        try {

            if (!isset($_POST) || $_SERVER['REQUEST_METHOD'] != "POST") {
                throw new Exception("No! No! No!", 1);
            }

            $name = $_FILES[$request->getParameter('file')]['name'];
            $size = $_FILES[$request->getParameter('file')]['size'];
            $tmp  = $_FILES[$request->getParameter('file')]['tmp_name'];
            if (!strlen($name)) {
                throw new Exception("Por favor, seleccione una imagen", 2);
            }

            list($txt, $ext) = explode(".", $name);

            $ext = strtolower($ext);

            if (!in_array($ext, $valid_formats)) {
                throw new Exception("Formato de la imagen inválido", 2);
            }

            if ($size > (5 * 1024 * 1024)) {
                throw new Exception("La imagen excede el tamaño máximo permitido (1 MB)", 2);                
            }
            
            /*$sizewh = getimagesize($tmp);

            if($sizewh[0] > 194 || $sizewh[1] > 204) {
                echo '<script>alert(\'La imagen no cumple con las dimensiones especificadas. Puede continuar si lo desea\')</script>';
            }*/
            $carId= sfContext::getInstance()->getUser()->getAttribute('carId');
            $actual_image_name = time() . $carId . "." . $ext;

            $uploadDir = sfConfig::get("sf_web_dir");
            $path      = $uploadDir . '/images/cars/';
            $fileName  = $actual_image_name . "." . $ext;

            $tmp = $_FILES[$request->getParameter('file')]['tmp_name'];

            $uploaded = move_uploaded_file($tmp, $path . $actual_image_name);

            if (!$uploaded) {
                throw new Exception("No se pudo subir la imagen de perfil", 1);
            }

            sfContext::getInstance()->getConfiguration()->loadHelpers("Asset");

            /*echo "<input type='hidden' name='" . $request->getParameter('photo') . "' value='" . $path . $actual_image_name . "'/>";
            echo "<img src='" . image_path("users/" . $actual_image_name) . "' class='preview' height='" . $request->getParameter('height') . "' width='" . $request->getParameter('width') . "' />";*/

            $Car = Doctrine_Core::getTable('car')->find($carId);
            $Car->setSeguroFotoCostadoDerecho("/images/cars/".$actual_image_name);
            $Car->save();
        } catch (Exception $e) {
            $return["error"] = true;
            $return["errorMessage"] = $e->getMessage();
            if ($e->getCode() == 1) {
                $return["errorMessage"] = "Problemas al subir la imagen. El problema ha sido notificado al equipo de desarrollo, por favor, intentalo más tarde";
            }
            if ($request->getHost() == "www.arriendas.cl" && $e->getCode() == 1) {
                Utils::reportError($e->getMessage(), "main/uploadPhoto");
            }
        }

        $this->renderText(json_encode($return));

        return sfView::NONE;
    }

    public function executeUploadPhotoFront(sfWebRequest $request) {

        $return = array("error" => false);
        $valid_formats = array("jpg", "png", "gif", "bmp", "jpeg");

        try {

            if (!isset($_POST) || $_SERVER['REQUEST_METHOD'] != "POST") {
                throw new Exception("No! No! No!", 1);
            }

            $name = $_FILES[$request->getParameter('file')]['name'];
            $size = $_FILES[$request->getParameter('file')]['size'];
            $tmp  = $_FILES[$request->getParameter('file')]['tmp_name'];
            if (!strlen($name)) {
                throw new Exception("Por favor, seleccione una imagen", 2);
            }

            list($txt, $ext) = explode(".", $name);

            $ext = strtolower($ext);

            if (!in_array($ext, $valid_formats)) {
                throw new Exception("Formato de la imagen inválido", 2);
            }

            if ($size > (5 * 1024 * 1024)) {
                throw new Exception("La imagen excede el tamaño máximo permitido (1 MB)", 2);                
            }
            
            /*$sizewh = getimagesize($tmp);

            if($sizewh[0] > 194 || $sizewh[1] > 204) {
                echo '<script>alert(\'La imagen no cumple con las dimensiones especificadas. Puede continuar si lo desea\')</script>';
            }*/
            $carId= sfContext::getInstance()->getUser()->getAttribute('carId');
            $actual_image_name = time() . $carId . "." . $ext;

            $uploadDir = sfConfig::get("sf_web_dir");
            $path      = $uploadDir . '/images/cars/';
            $fileName  = $actual_image_name . "." . $ext;

            $tmp = $_FILES[$request->getParameter('file')]['tmp_name'];

            $uploaded = move_uploaded_file($tmp, $path . $actual_image_name);

            if (!$uploaded) {
                throw new Exception("No se pudo subir la imagen de perfil", 1);
            }

            sfContext::getInstance()->getConfiguration()->loadHelpers("Asset");

            /*echo "<input type='hidden' name='" . $request->getParameter('photo') . "' value='" . $path . $actual_image_name . "'/>";
            echo "<img src='" . image_path("users/" . $actual_image_name) . "' class='preview' height='" . $request->getParameter('height') . "' width='" . $request->getParameter('width') . "' />";*/

            $Car = Doctrine_Core::getTable('car')->find($carId);
            $Car->setSeguroFotoFrente("/images/cars/".$actual_image_name);
            $Car->save();
        } catch (Exception $e) {
            $return["error"] = true;
            $return["errorMessage"] = $e->getMessage();
            if ($e->getCode() == 1) {
                $return["errorMessage"] = "Problemas al subir la imagen. El problema ha sido notificado al equipo de desarrollo, por favor, intentalo más tarde";
            }
            if ($request->getHost() == "www.arriendas.cl" && $e->getCode() == 1) {
                Utils::reportError($e->getMessage(), "main/uploadPhoto");
            }
        }

        $this->renderText(json_encode($return));

        return sfView::NONE;
    }

    public function executeUploadPhotoSideLeft(sfWebRequest $request) {

        $return = array("error" => false);
        $valid_formats = array("jpg", "png", "gif", "bmp", "jpeg");

        try {

            if (!isset($_POST) || $_SERVER['REQUEST_METHOD'] != "POST") {
                throw new Exception("No! No! No!", 1);
            }

            $name = $_FILES[$request->getParameter('file')]['name'];
            $size = $_FILES[$request->getParameter('file')]['size'];
            $tmp  = $_FILES[$request->getParameter('file')]['tmp_name'];
            if (!strlen($name)) {
                throw new Exception("Por favor, seleccione una imagen", 2);
            }

            list($txt, $ext) = explode(".", $name);

            $ext = strtolower($ext);

            if (!in_array($ext, $valid_formats)) {
                throw new Exception("Formato de la imagen inválido", 2);
            }

            if ($size > (5 * 1024 * 1024)) {
                throw new Exception("La imagen excede el tamaño máximo permitido (1 MB)", 2);                
            }
            
            /*$sizewh = getimagesize($tmp);

            if($sizewh[0] > 194 || $sizewh[1] > 204) {
                echo '<script>alert(\'La imagen no cumple con las dimensiones especificadas. Puede continuar si lo desea\')</script>';
            }*/
            $carId= sfContext::getInstance()->getUser()->getAttribute('carId');
            $actual_image_name = time() . $carId . "." . $ext;

            $uploadDir = sfConfig::get("sf_web_dir");
            $path      = $uploadDir . '/images/cars/';
            $fileName  = $actual_image_name . "." . $ext;

            $tmp = $_FILES[$request->getParameter('file')]['tmp_name'];

            $uploaded = move_uploaded_file($tmp, $path . $actual_image_name);

            if (!$uploaded) {
                throw new Exception("No se pudo subir la imagen de perfil", 1);
            }

            sfContext::getInstance()->getConfiguration()->loadHelpers("Asset");

            /*echo "<input type='hidden' name='" . $request->getParameter('photo') . "' value='" . $path . $actual_image_name . "'/>";
            echo "<img src='" . image_path("users/" . $actual_image_name) . "' class='preview' height='" . $request->getParameter('height') . "' width='" . $request->getParameter('width') . "' />";*/

            $Car = Doctrine_Core::getTable('car')->find($carId);
            $Car->setSeguroFotoCostadoIzquierdo("/images/cars/".$actual_image_name);
            $Car->save();
        } catch (Exception $e) {
            $return["error"] = true;
            $return["errorMessage"] = $e->getMessage();
            if ($e->getCode() == 1) {
                $return["errorMessage"] = "Problemas al subir la imagen. El problema ha sido notificado al equipo de desarrollo, por favor, intentalo más tarde";
            }
            if ($request->getHost() == "www.arriendas.cl" && $e->getCode() == 1) {
                Utils::reportError($e->getMessage(), "main/uploadPhoto");
            }
        }

        $this->renderText(json_encode($return));

        return sfView::NONE;
    }

    public function executeUploadPhotoBackRight(sfWebRequest $request) {

        $return = array("error" => false);
        $valid_formats = array("jpg", "png", "gif", "bmp", "jpeg");

        try {

            if (!isset($_POST) || $_SERVER['REQUEST_METHOD'] != "POST") {
                throw new Exception("No! No! No!", 1);
            }

            $name = $_FILES[$request->getParameter('file')]['name'];
            $size = $_FILES[$request->getParameter('file')]['size'];
            $tmp  = $_FILES[$request->getParameter('file')]['tmp_name'];
            if (!strlen($name)) {
                throw new Exception("Por favor, seleccione una imagen", 2);
            }

            list($txt, $ext) = explode(".", $name);

            $ext = strtolower($ext);

            if (!in_array($ext, $valid_formats)) {
                throw new Exception("Formato de la imagen inválido", 2);
            }

            if ($size > (5 * 1024 * 1024)) {
                throw new Exception("La imagen excede el tamaño máximo permitido (1 MB)", 2);                
            }
            
            /*$sizewh = getimagesize($tmp);

            if($sizewh[0] > 194 || $sizewh[1] > 204) {
                echo '<script>alert(\'La imagen no cumple con las dimensiones especificadas. Puede continuar si lo desea\')</script>';
            }*/
            $carId= sfContext::getInstance()->getUser()->getAttribute('carId');
            $actual_image_name = time() . $carId . "." . $ext;

            $uploadDir = sfConfig::get("sf_web_dir");
            $path      = $uploadDir . '/images/cars/';
            $fileName  = $actual_image_name . "." . $ext;

            $tmp = $_FILES[$request->getParameter('file')]['tmp_name'];

            $uploaded = move_uploaded_file($tmp, $path . $actual_image_name);

            if (!$uploaded) {
                throw new Exception("No se pudo subir la imagen de perfil", 1);
            }

            sfContext::getInstance()->getConfiguration()->loadHelpers("Asset");

            /*echo "<input type='hidden' name='" . $request->getParameter('photo') . "' value='" . $path . $actual_image_name . "'/>";
            echo "<img src='" . image_path("users/" . $actual_image_name) . "' class='preview' height='" . $request->getParameter('height') . "' width='" . $request->getParameter('width') . "' />";*/

            $Car = Doctrine_Core::getTable('car')->find($carId);
            $Car->setSeguroFotoTraseroDerecho("/images/cars/".$actual_image_name);
            $Car->save();
        } catch (Exception $e) {
            $return["error"] = true;
            $return["errorMessage"] = $e->getMessage();
            if ($e->getCode() == 1) {
                $return["errorMessage"] = "Problemas al subir la imagen. El problema ha sido notificado al equipo de desarrollo, por favor, intentalo más tarde";
            }
            if ($request->getHost() == "www.arriendas.cl" && $e->getCode() == 1) {
                Utils::reportError($e->getMessage(), "main/uploadPhoto");
            }
        }

        $this->renderText(json_encode($return));

        return sfView::NONE;
    }

    public function executeUploadPhotoBackLeft(sfWebRequest $request) {

        $return = array("error" => false);
        $valid_formats = array("jpg", "png", "gif", "bmp", "jpeg");

        try {

            if (!isset($_POST) || $_SERVER['REQUEST_METHOD'] != "POST") {
                throw new Exception("No! No! No!", 1);
            }

            $name = $_FILES[$request->getParameter('file')]['name'];
            $size = $_FILES[$request->getParameter('file')]['size'];
            $tmp  = $_FILES[$request->getParameter('file')]['tmp_name'];
            if (!strlen($name)) {
                throw new Exception("Por favor, seleccione una imagen", 2);
            }

            list($txt, $ext) = explode(".", $name);

            $ext = strtolower($ext);

            if (!in_array($ext, $valid_formats)) {
                throw new Exception("Formato de la imagen inválido", 2);
            }

            if ($size > (5 * 1024 * 1024)) {
                throw new Exception("La imagen excede el tamaño máximo permitido (1 MB)", 2);                
            }
            
            /*$sizewh = getimagesize($tmp);

            if($sizewh[0] > 194 || $sizewh[1] > 204) {
                echo '<script>alert(\'La imagen no cumple con las dimensiones especificadas. Puede continuar si lo desea\')</script>';
            }*/
            $carId= sfContext::getInstance()->getUser()->getAttribute('carId');
            $actual_image_name = time() . $carId . "." . $ext;

            $uploadDir = sfConfig::get("sf_web_dir");
            $path      = $uploadDir . '/images/cars/';
            $fileName  = $actual_image_name . "." . $ext;

            $tmp = $_FILES[$request->getParameter('file')]['tmp_name'];

            $uploaded = move_uploaded_file($tmp, $path . $actual_image_name);

            if (!$uploaded) {
                throw new Exception("No se pudo subir la imagen de perfil", 1);
            }

            sfContext::getInstance()->getConfiguration()->loadHelpers("Asset");

            /*echo "<input type='hidden' name='" . $request->getParameter('photo') . "' value='" . $path . $actual_image_name . "'/>";
            echo "<img src='" . image_path("users/" . $actual_image_name) . "' class='preview' height='" . $request->getParameter('height') . "' width='" . $request->getParameter('width') . "' />";*/

            $Car = Doctrine_Core::getTable('car')->find($carId);
            $Car->setSeguroFotoTraseroIzquierdo("/images/cars/".$actual_image_name);
            $Car->save();
        } catch (Exception $e) {
            $return["error"] = true;
            $return["errorMessage"] = $e->getMessage();
            if ($e->getCode() == 1) {
                $return["errorMessage"] = "Problemas al subir la imagen. El problema ha sido notificado al equipo de desarrollo, por favor, intentalo más tarde";
            }
            if ($request->getHost() == "www.arriendas.cl" && $e->getCode() == 1) {
                Utils::reportError($e->getMessage(), "main/uploadPhoto");
            }
        }

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
