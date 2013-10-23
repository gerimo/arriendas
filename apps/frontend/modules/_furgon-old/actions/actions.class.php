<?php

/**
 * furgon actions.
 *
 * @package    CarSharing
 * @subpackage furgon
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class furgonActions extends sfActions
{
 /**
  * Executes index action
  *
  * @param sfRequest $request A request object
  */
  public function executeIndex(sfWebRequest $request){

  	$ciudad = "santiago";
    $objeto_ciudad = Doctrine_Core::getTable("city")->findOneByName($ciudad);

            $q = Doctrine_Query::create()
                ->select('ca.id, mo.name model,
          br.name brand, ca.uso_vehiculo_id tipo_vehiculo, ca.year year,
          ca.address address, ci.name city,
          st.name state, co.name country,
          owner.firstname firstname,
          owner.lastname lastname,
          ca.price_per_day priceday,
          ca.price_per_hour pricehour'
                )
                ->from('Car ca')
                ->innerJoin('ca.Model mo')
                ->innerJoin('ca.User owner')
                ->innerJoin('mo.Brand br')
                ->innerJoin('ca.City ci')
                ->innerJoin('ci.State st')
                ->innerJoin('st.Country co')
                ->where('ca.activo = ?', 1)
                ->andWhere('ca.seguro_ok = ?', 4)
                ->andWhere('uso_vehiculo_id = ?', 4)
                ->andWhere('ca.city_id = ?', $objeto_ciudad->getId());
        //$this->cars = $q->execute();
        $this->cars = $q->fetchArray();
        //var_dump($this->cars);die();

        $fotos_autos = array();
        for($j=0;$j<count($this->cars);$j++){
            $auto = Doctrine_Core::getTable('car')->find(array($this->cars[$j]['id']));
            $fotos_autos[$j]['id'] = $auto->getId();
            $fotos_autos[$j]['photoS3'] = $auto->getPhotoS3();
            $fotos_autos[$j]['verificationPhotoS3'] = $auto->getVerificationPhotoS3();
            $i=0;
            if($auto->getVerificationPhotoS3() == 1){
                if($auto->getFoto() != null && $auto->getFoto() != "") {
                    $rutaFoto=$auto->getFoto();
                    $fotos_autos[$j][$i] = $rutaFoto;
                    $fotos_autos[$j][$i+1] = "Ver Fotos";
                    $i=$i+2;
                }
                if($auto->getSeguroFotoFrente() != null && $auto->getSeguroFotoFrente() != "") {
                    $rutaFotoFrente=$auto->getSeguroFotoFrente();
                    $fotos_autos[$j][$i] = $rutaFotoFrente;
                    $fotos_autos[$j][$i+1] = "Foto Frente";
                    $i=$i+2;
                }
                if($auto->getSeguroFotoCostadoDerecho() != null && $auto->getSeguroFotoCostadoDerecho() != "") {
                    $rutaFotoCostadoDerecho=$auto->getSeguroFotoCostadoDerecho();
                    $fotos_autos[$j][$i] = $rutaFotoCostadoDerecho;
                    $fotos_autos[$j][$i+1] = "Foto Costado Derecho";
                    $i=$i+2;
                }
                if(strpos($auto->getSeguroFotoCostadoIzquierdo(),"http")!=-1 && $auto->getSeguroFotoCostadoIzquierdo() != "") {
                    $rutaFotoCostadoIzquierdo=$auto->getSeguroFotoCostadoIzquierdo();
                    $fotos_autos[$j][$i] = $rutaFotoCostadoIzquierdo;
                    $fotos_autos[$j][$i+1] = "Foto Costado Izquierdo";
                    $i=$i+2;
                }
                if(strpos($auto->getSeguroFotoTraseroDerecho(),"http")!=-1 && $auto->getSeguroFotoTraseroDerecho() != "") {
                    $rutaFotoTrasera= $auto->getSeguroFotoTraseroDerecho();
                    $fotos_autos[$j][$i] = $rutaFotoTrasera;
                    $fotos_autos[$j][$i+1] = "Foto Trasera";
                    $i=$i+2;
                }   
                if(strpos($auto->getTablero(),"http")!=-1 && $auto->getTablero() != "") {
                    $rutaFotoPanel=$auto->getTablero();  
                    $fotos_autos[$j][$i] = $rutaFotoPanel;
                    $fotos_autos[$j][$i+1] = "Foto del Panel";
                    $i=$i+2;
                }
                if(strpos($auto->getAccesorio1(),"http")!=-1 && $auto->getAccesorio1() != "") {
                    $rutaFotoAccesorios1= $auto->getAccesorio1();
                    $fotos_autos[$j][$i] = $rutaFotoAccesorios1;
                    $fotos_autos[$j][$i+1] = "Foto de Accesorio 1";
                    $i=$i+2;
                }  
                if(strpos($auto->getAccesorio2(),"http")!=-1 && $auto->getAccesorio2() != "") {
                    $rutaFotoAccesorios2=$auto->getAccesorio2();
                    $fotos_autos[$j][$i] = $rutaFotoAccesorios2;
                    $fotos_autos[$j][$i+1] = "Foto de Accesorio 2";
                }
            }else{//if verificationPhotoS3 == 0
                if($auto->getFoto() != null && $auto->getFoto() != "") {
                    $rutaFoto=$auto->getFoto();
                    $fotos_autos[$j][$i] = $rutaFoto;
                    $fotos_autos[$j][$i+1] = "Ver Fotos";
                    $i=$i+2;
                }
                if($auto->getSeguroFotoFrente() != null && $auto->getSeguroFotoFrente() != "") {
                    $rutaFotoFrente=$auto->getSeguroFotoFrente();
                    $fotos_autos[$j][$i] = $rutaFotoFrente;
                    $fotos_autos[$j][$i+1] = "Foto Frente";
                    $i=$i+2;
                }
                if($auto->getSeguroFotoCostadoDerecho() != null && $auto->getSeguroFotoCostadoDerecho() != "") {
                    $rutaFotoCostadoDerecho=$auto->getSeguroFotoCostadoDerecho();
                    $fotos_autos[$j][$i] = $rutaFotoCostadoDerecho;
                    $fotos_autos[$j][$i+1] = "Foto Costado Derecho";
                    $i=$i+2;
                }
                if($auto->getSeguroFotoCostadoIzquierdo() != null && $auto->getSeguroFotoCostadoIzquierdo() != "") {
                    $rutaFotoCostadoIzquierdo=$auto->getSeguroFotoCostadoIzquierdo();
                    $fotos_autos[$j][$i] = $rutaFotoCostadoIzquierdo;
                    $fotos_autos[$j][$i+1] = "Foto Costado Izquierdo";
                    $i=$i+2;
                }
                if($auto->getSeguroFotoTraseroDerecho() != null && $auto->getSeguroFotoTraseroDerecho() != "") {
                    $rutaFotoTrasera= $auto->getSeguroFotoTraseroDerecho();
                    $fotos_autos[$j][$i] = $rutaFotoTrasera;
                    $fotos_autos[$j][$i+1] = "Foto Trasera";
                    $i=$i+2;
                }   
                if($auto->getTablero() != null && $auto->getTablero() != "") {
                    $rutaFotoPanel=$auto->getTablero();  
                    $fotos_autos[$j][$i] = $rutaFotoPanel;
                    $fotos_autos[$j][$i+1] = "Foto del Panel";
                    $i=$i+2;
                }
                if($auto->getAccesorio1() != null && $auto->getAccesorio1() != "") {
                    $rutaFotoAccesorios1= $auto->getAccesorio1();
                    $fotos_autos[$j][$i] = $rutaFotoAccesorios1;
                    $fotos_autos[$j][$i+1] = "Foto de Accesorio 1";
                    $i=$i+2;
                }  
                if($auto->getAccesorio2() != null && $auto->getAccesorio2() != "") {
                    $rutaFotoAccesorios2=$auto->getAccesorio2();
                    $fotos_autos[$j][$i] = $rutaFotoAccesorios2;
                    $fotos_autos[$j][$i+1] = "Foto de Accesorio 2";
                }
            }
        }
        //$this->arrayDescripcionFotos = $arrayDescripcion;
        //$this->arrayFotos = $arrayImagenes;
        //var_dump(count($fotos_autos[13]));die();
        //var_dump($fotos_autos);die();
        $this->fotos_autos = $fotos_autos;
        //var_dump($this->cars[0]);die();

  }
  
}
