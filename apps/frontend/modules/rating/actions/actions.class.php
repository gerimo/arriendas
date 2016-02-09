<?php

/**
 * rating actions.
 *
 * @package    CarSharing
 * @subpackage rating
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class ratingActions extends sfActions
{
 /**
  * Executes index action
  *
  * @param sfRequest $request A request object
  */
  public function executeIndex(sfWebRequest $request)  {
	$this->setLayout("newIndexLayout");

  }

  public function executeOwnerForm(sfWebRequest $request) {
  		$this->setLayout("newIndexLayout");
  		$this->reserveId = $request->getParameter("reserveId");
  		$userId = $this->getUser()->getAttribute("userid");
  		$Reserves = Doctrine_core::getTable("reserve")->findById($this->reserveId);
  		$flag=false;
  		
  		foreach ($Reserves as $Reserve) {
	  		if($Reserve){
	  			$renterId = $Reserve->getUserId();
				$ownerId = $Reserve->getCar()->getUserId();
				$Rating = Doctrine_core::getTable("rating")->find($Reserve->getRatingId());

				// si el usuario es arrendatario, lo redirecciona
	  			if($ownerId != $userId){
	  				$flag=true;
				}

				// si la reserva ya está calificada, redirecciona.
				if($Rating->state_owner){
					$flag=true;
				}
	  		}
	  	}
  		if($flag){
  			$this->redirect("rating_show_reserves");
  		}

    }

  public function executeRenterForm(sfWebRequest $request) {
  		$this->setLayout("newIndexLayout");
  		
  		$this->reserveId = $request->getParameter("reserveId");
  		$userId = $this->getUser()->getAttribute("userid");
  		$Reserves = Doctrine_core::getTable("reserve")->findById($this->reserveId);
  		$flag=false;
  		
  		foreach ($Reserves as $Reserve) {
	  		if($Reserve){
	  			$renterId = $Reserve->getUserId();
				$ownerId = $Reserve->getCar()->getUserId();
				$Rating = Doctrine_core::getTable("rating")->find($Reserve->getRatingId());

				// si el usuario es dueño, lo redirecciona
	  			if($renterId != $userId){
	  				$flag=true;
				}

				// si la reserva ya está calificada, redirecciona.
				if($Rating->state_renter){
					$flag=true;
				}
	  		}
	  	}
  		if($flag){
  			$this->redirect("rating_show_reserves");
  		}
  		
    }

  public function executeGetPendingsList(sfWebRequest $request) {
		$return = array("error" => false);
		$pendingListAsOwner = "";
		$pendingListAsRenter = "";
		try {
			$userId = $this->getUser()->getAttribute("userid");
			$Reserves = Doctrine_core::getTable('reserve')->findPaidReservesByUser($userId);
			$isOwner = null;

			foreach ($Reserves as $Reserve) {
				$renterId = $Reserve->getUserId();
				$ownerId = $Reserve->getCar()->getUserId();
				$Car = $Reserve->getCar();
				$Rating = $Reserve->getRating();

				// se tratan las fotos ( se verifica si están en el s3 o en el directorio del proyecto)
				$urlFotoTipo      = "../uploads/cars/".$Car->foto_perfil;
				$urlFotoThumbTipo = "../uploads/cars/".$Car->foto_perfil;                    

				if($Car->photoS3 == 1) {
					$urlFotoTipo = $Car->foto_perfil;
					$urlFotoThumbTipo = $Car->foto_perfil;
				}

				$str = 0;
				if ($Car->foto_perfil) {
					$str = stripos($Car->foto_perfil, "cars");
				}

				if($str > 0) {
					$urlFotoTipo = $Car->foto_perfil;
					$urlFotoThumbTipo = $Car->foto_perfil;
				}

				// verifica si el usuario es un dueño en la reserva iterada.
				if($ownerId == $userId && $renterId != $userId){
					
					$isOwner = true;
				} elseif($ownerId != $userId && $renterId == $userId) {
					
					$isOwner = false;
				}

				$Renter = Doctrine_core::getTable("user")->find($renterId);
				$Owner = Doctrine_core::getTable("user")->find($ownerId);
				if(!$Rating){
					
					if($isOwner){
						
						$pendingListAsOwner = $pendingListAsOwner."<article class='box thumbnail' data-id='".$Reserve->id."'>";
						$pendingListAsOwner = $pendingListAsOwner."<div class='row'>";
						$pendingListAsOwner = $pendingListAsOwner."<div class='col-xs-4 col-md-4 image'>";
						$pendingListAsRenter = $pendingListAsRenter."<div class='hidden-sm space-10'></div>";
						if(!$Car->getFotoPerfilS3("md")){
							if($str > 0) {
								$pendingListAsOwner = $pendingListAsOwner."<img class='img-responsive car-r-image' src='http://www.arriendas.cl".$urlFotoThumbTipo."' height='99' width='134' alt='rent a car ".$Car->getModel()->getBrand()->name." ".$Car->getModel()->name."'/>";
							}else{
								$pendingListAsOwner = $pendingListAsOwner."<img class='img-responsive car-r-image' src='http://res.cloudinary.com/arriendas-cl/image/fetch/w_112,h_84,c_fill,g_center/http://www.arriendas.cl".$urlFotoThumbTipo."' height='99' width='134' alt='rent a car ".$Car->getModel()->getBrand()->name." ".$Car->getModel()->name."'/>";
							}
						} else {
							$pendingListAsOwner = $pendingListAsOwner."<img class='img-responsive car-r-image' src='".$Car->getFotoPerfilS3("md")."' height='99' width='134' alt='rent a car ".$Car->getModel()->getBrand()->name." ".$Car->getModel()->name."'/>";
						}
						$pendingListAsOwner = $pendingListAsOwner."</div>";
						$pendingListAsOwner = $pendingListAsOwner."<div class='col-xs-8 col-md-8 text'>";
						$pendingListAsOwner = $pendingListAsOwner."<h2>".$Car->getModel()->getBrand()->name." ".$Car->getModel()->name."<small>, ".$Car->year."</small></h2>";
						$pendingListAsOwner = $pendingListAsOwner."<p><b>Nombre del arrendatario:</b> &nbsp;&nbsp;".$Renter->getFirstname()." ".$Renter->getLastname()."</p>";
						$pendingListAsOwner = $pendingListAsOwner."<p><b>Fecha de la reserva:</b> &nbsp;&nbsp;".$Reserve->getFechaReserva()."</p>";
						$pendingListAsOwner = $pendingListAsOwner."<p><b>Fecha de fin:</b> &nbsp;&nbsp;".$Reserve->getFechaTermino2()."</p>";
						$pendingListAsOwner = $pendingListAsOwner."<p class='text-right'><a class='btn btn-a-action btn-sm' href='".$this->generateUrl("rating_show_owner_form", array("reserveId" => $Reserve->id))."' class='reserve' target='_blank'>Calificar al arrendatario</a></p>";
						$pendingListAsOwner = $pendingListAsOwner."</div>";
						$pendingListAsOwner = $pendingListAsOwner."</div>";
						$pendingListAsOwner = $pendingListAsOwner."<br>";
						$pendingListAsOwner = $pendingListAsOwner."</article>";
					} else {

						$pendingListAsRenter = $pendingListAsRenter."<article class='box thumbnail' data-id='".$Reserve->id."'>";
						$pendingListAsRenter = $pendingListAsRenter."<div class='row'>";
						$pendingListAsRenter = $pendingListAsRenter."<div class='col-xs-4 col-md-4 image'>";
						$pendingListAsRenter = $pendingListAsRenter."<div class='hidden-sm space-10'></div>";
						if(!$Car->getFotoPerfilS3("md")){
							if($str > 0) {
								$pendingListAsRenter = $pendingListAsRenter."<img class='img-responsive car-r-image' src='http://www.arriendas.cl".$urlFotoThumbTipo."' height='99' width='134' alt='rent a car ".$Car->getModel()->getBrand()->name." ".$Car->getModel()->name."'/>";
							}else{
								$pendingListAsRenter = $pendingListAsRenter."<img class='img-responsive car-r-image' src='http://res.cloudinary.com/arriendas-cl/image/fetch/w_112,h_84,c_fill,g_center/http://www.arriendas.cl".$urlFotoThumbTipo."' height='99' width='134' alt='rent a car ".$Car->getModel()->getBrand()->name." ".$Car->getModel()->name."'/>";
							}
						} else {
							$pendingListAsRenter = $pendingListAsRenter."<img class='img-responsive car-r-image' src='".$Car->getFotoPerfilS3("md")."' height='99' width='134' alt='rent a car ".$Car->getModel()->getBrand()->name." ".$Car->getModel()->name."'/>";
						}
						$pendingListAsRenter = $pendingListAsRenter."</div>";
						$pendingListAsRenter = $pendingListAsRenter."<div class='col-xs-8 col-md-8 text'>";
						$pendingListAsRenter = $pendingListAsRenter."<h2>".$Car->getModel()->getBrand()->name." ".$Car->getModel()->name."<small>, ".$Car->year."</small></h2>";
						$pendingListAsRenter = $pendingListAsRenter."<p><b>Nombre del dueño:</b> &nbsp;&nbsp;".$Owner->getFirstname()." ".$Owner->getLastname()."</p>";
						$pendingListAsRenter = $pendingListAsRenter."<p><b>Fecha de la reserva:</b> &nbsp;&nbsp;".$Reserve->getFechaReserva()."</p>";
						$pendingListAsRenter = $pendingListAsRenter."<p><b>Fecha de fin:</b> &nbsp;&nbsp;".$Reserve->getFechaTermino2()."</p>";
						$pendingListAsRenter = $pendingListAsRenter."<p class='text-right'><a class='btn btn-a-action btn-sm' href='".$this->generateUrl("rating_show_renter_form", array("reserveId" => $Reserve->id))."' class='reserve' target='_blank'>Calificar al dueño</a></p>";
						$pendingListAsRenter = $pendingListAsRenter."</div>";
						$pendingListAsRenter = $pendingListAsRenter."</div>";
						$pendingListAsRenter = $pendingListAsRenter."<br>";
						$pendingListAsRenter = $pendingListAsRenter."</article>";
					}
				} else {
					
					if($isOwner){
						
						if($Rating->getStateOwner() == 0){
							
							$pendingListAsOwner = $pendingListAsOwner."<article class='box thumbnail' data-id='".$Reserve->id."'>";
							$pendingListAsOwner = $pendingListAsOwner."<div class='row'>";
							$pendingListAsOwner = $pendingListAsOwner."<div class='col-xs-4 col-md-4 image'>";
							$pendingListAsRenter = $pendingListAsRenter."<div class='hidden-sm space-10'></div>";
							if(!$Car->getFotoPerfilS3("md")){
								if($str > 0) {
									$pendingListAsOwner = $pendingListAsOwner."<img class='img-responsive car-r-image' src='http://www.arriendas.cl".$urlFotoThumbTipo."' height='99' width='134' alt='rent a car ".$Car->getModel()->getBrand()->name." ".$Car->getModel()->name."'/>";
								}else{
									$pendingListAsOwner = $pendingListAsOwner."<img class='img-responsive car-r-image' src='http://res.cloudinary.com/arriendas-cl/image/fetch/w_112,h_84,c_fill,g_center/http://www.arriendas.cl".$urlFotoThumbTipo."' height='99' width='134' alt='rent a car ".$Car->getModel()->getBrand()->name." ".$Car->getModel()->name."'/>";
								}
							} else {
								$pendingListAsOwner = $pendingListAsOwner."<img class='img-responsive car-r-image' src='".$Car->getFotoPerfilS3("md")."' height='99' width='134' alt='rent a car ".$Car->getModel()->getBrand()->name." ".$Car->getModel()->name."'/>";
							}
							$pendingListAsOwner = $pendingListAsOwner."</div>";
							$pendingListAsOwner = $pendingListAsOwner."<div class='col-xs-8 col-md-8 text'>";
							$pendingListAsOwner = $pendingListAsOwner."<h2>".$Car->getModel()->getBrand()->name." ".$Car->getModel()->name."<small>, ".$Car->year."</small></h2>";
							$pendingListAsOwner = $pendingListAsOwner."<p><b>Nombre del arrendatario:</b> &nbsp;&nbsp;".$Renter->getFirstname()." ".$Renter->getLastname()."</p>";
							$pendingListAsOwner = $pendingListAsOwner."<p><b>Fecha de la reserva:</b> &nbsp;&nbsp;".$Reserve->getFechaReserva()."</p>";
							$pendingListAsOwner = $pendingListAsOwner."<p><b>Fecha de fin:</b> &nbsp;&nbsp;".$Reserve->getFechaTermino2()."</p>";
							$pendingListAsOwner = $pendingListAsOwner."<p class='text-right'><a class='btn btn-a-action btn-sm' href='".$this->generateUrl("rating_show_owner_form", array("reserveId" => $Reserve->id))."' class='reserve' target='_blank'>Calificar al arrendatario</a></p>";
							$pendingListAsOwner = $pendingListAsOwner."</div>";
							$pendingListAsOwner = $pendingListAsOwner."</div>";
							$pendingListAsOwner = $pendingListAsOwner."<br>";
							$pendingListAsOwner = $pendingListAsOwner."</article>";
						}
					} else {
						
						if($Rating->getStateRenter() == 0){
							
							$pendingListAsRenter = $pendingListAsRenter."<article class='box thumbnail' data-id='".$Reserve->id."'>";
							$pendingListAsRenter = $pendingListAsRenter."<div class='row'>";
							$pendingListAsRenter = $pendingListAsRenter."<div class='col-xs-4 col-md-4 image'>";
							$pendingListAsRenter = $pendingListAsRenter."<div class='hidden-sm space-10'></div>";
							if(!$Car->getFotoPerfilS3("md")){
								if($str > 0) {
									$pendingListAsRenter = $pendingListAsRenter."<img class='img-responsive car-r-image' src='http://www.arriendas.cl".$urlFotoThumbTipo."' height='99' width='134' alt='rent a car ".$Car->getModel()->getBrand()->name." ".$Car->getModel()->name."'/>";
								}else{
									$pendingListAsRenter = $pendingListAsRenter."<img class='img-responsive car-r-image' src='http://res.cloudinary.com/arriendas-cl/image/fetch/w_112,h_84,c_fill,g_center/http://www.arriendas.cl".$urlFotoThumbTipo."' height='99' width='134' alt='rent a car ".$Car->getModel()->getBrand()->name." ".$Car->getModel()->name."'/>";
								}
							} else {
								$pendingListAsRenter = $pendingListAsRenter."<img class='img-responsive car-r-image' src='".$Car->getFotoPerfilS3("md")."' height='99' width='134' alt='rent a car ".$Car->getModel()->getBrand()->name." ".$Car->getModel()->name."'/>";
							}
							$pendingListAsRenter = $pendingListAsRenter."</div>";
							$pendingListAsRenter = $pendingListAsRenter."<div class='col-xs-8 col-md-8 text'>";
							$pendingListAsRenter = $pendingListAsRenter."<h2>".$Car->getModel()->getBrand()->name." ".$Car->getModel()->name."<small>, ".$Car->year."</small></h2>";
							$pendingListAsRenter = $pendingListAsRenter."<p><b>Nombre del dueño:</b> &nbsp;&nbsp;".$Owner->getFirstname()." ".$Owner->getLastname()."</p>";
							$pendingListAsRenter = $pendingListAsRenter."<p><b>Fecha de la reserva:</b> &nbsp;&nbsp;".$Reserve->getFechaReserva()."</p>";
							$pendingListAsRenter = $pendingListAsRenter."<p><b>Fecha de fin:</b> &nbsp;&nbsp;".$Reserve->getFechaTermino2()."</p>";
							$pendingListAsRenter = $pendingListAsRenter."<p class='text-right'><a class='btn btn-a-action btn-sm' href='".$this->generateUrl("rating_show_renter_form", array("reserveId" => $Reserve->id))."' class='reserve' target='_blank'>Calificar al dueño</a></p>";
							$pendingListAsRenter = $pendingListAsRenter."</div>";
							$pendingListAsRenter = $pendingListAsRenter."</div>";
							$pendingListAsRenter = $pendingListAsRenter."<br>";
							$pendingListAsRenter = $pendingListAsRenter."</article>";
						}
					}
				}
				
				
			}

			$return["renterList"] = $pendingListAsRenter;
			$return["ownerList"] = $pendingListAsOwner;
			
		} catch (Exception $e) {
			$return["error"] = true;
			$return["errorMessage"] = $e->getMessage();
		}

		$this->renderText(json_encode($return));

		return sfView::NONE;
	}

  	// Función que crea la calificacion que hacen los propietarios ( propietario a arrendatario ).
	public function executeMakeARate(sfWebRequest $request) {
		$return = array("error" => false);

		try {
			$isRenter = null;

			$userId = $this->getUser()->getAttribute("userid");


			$reserveId 	= $request->getPostParameter("reserveId");

			$Reserve 	= Doctrine_core::getTable('reserve')->find($reserveId);
			$Car 		= Doctrine_core::getTable('car')->find($Reserve->getCarId());
			// Discrimina si es usuario es un renter o owner,
			if($Reserve->getUserId() == $userId) {

				// Este proceso se ejecuta solo si el usuario calificando ES ARRENDATARIO
				$isRenter = true;

				// renter's form
				$opinionAboutOwner = $request->getParameter('opinion_about_owner');
				$optionRecommendCar = $request->getParameter('op_recom_car');
				$comentNoRecommendCar = $request->getParameter('coment_no_recom_car');
				$optionDesperfectoCar = $request->getParameter('op_desp_car');
				$comentSiDesperfectoCar = $request->getParameter('coment_si_desp_car');
				$optionRecommendOwner = $request->getParameter('op_recom_owner');
				$comentNoRecommendOwner = $request->getParameter('coment_no_recom_owner');
				$optionCleaningAboutOwner = $request->getParameter('op_cleaning_about_owner');
				$optionPuntualAboutOwner = $request->getParameter('op_puntual_about_owner');
				$timeDelayStartOwner = $request->getParameter('time_delay_start_owner');
				$timeDelayEndOwner = $request->getParameter('time_delay_end_owner');


				// Validacion de datos
				if (is_null($opinionAboutOwner) || $opinionAboutOwner == "") {
					throw new Exception("debes escribir tu opinion a cerca del dueño", 1);
				}

				if (is_null($optionRecommendCar) || $optionRecommendCar == "") {
					throw new Exception("debes indicar si recomiendarías el automóvil", 1);
				} elseif($optionRecommendCar == 0) {

					if (is_null($comentNoRecommendCar) || $comentNoRecommendCar == "") {
						throw new Exception("debes escribir escribir porque no recomendarías el automóvil", 1);
					}
				}

				if (is_null($optionDesperfectoCar) || $optionDesperfectoCar == "") {
					throw new Exception("debes indicar si el automóvil tuvo algún desperfecto mecánico", 1);
				} elseif($optionDesperfectoCar == 1) {

					if (is_null($comentSiDesperfectoCar) || $comentSiDesperfectoCar == "") {
						throw new Exception("debes indicar qué desperfecto mecánico tuvo", 1);
					}
				}

				if (is_null($optionRecommendOwner) || $optionRecommendOwner == "") {
					throw new Exception("debes indicar si recomiendas al dueño", 1);
				} elseif($optionRecommendOwner == 0) {

					if (is_null($comentNoRecommendOwner) || $comentNoRecommendOwner == "") {
						throw new Exception("debes indicar porqué no recomiendas al dueño", 1);
					}
				}

				if (is_null($optionCleaningAboutOwner) || $optionCleaningAboutOwner == "") {
					throw new Exception("debes indicar que tan limpio se entregó el automóvil", 1);
				}

				if (is_null($optionPuntualAboutOwner) || $optionPuntualAboutOwner == "") {
					throw new Exception("debes indicar si el dueño fué puntual a la hora de entregar el vehículo", 1);
				} elseif($optionPuntualAboutOwner == 0) {

					if (is_null($timeDelayStartOwner) || $timeDelayStartOwner == "") {
						throw new Exception("debes indicar el tiempo del retraso en la entrega del vehículo al inicio del arriendo", 1);
					}

					if (is_null($timeDelayEndOwner) || $timeDelayEndOwner == "") {
						throw new Exception("debes indicar el tiempo del retraso en la entrega del vehículo al término del arriendo", 1);
					}

				}


			} elseif($Car->getUserId() == $userId) {

				// Este proceso se ejecuta solo si el usuario calificando ES DUEÑO
				$isRenter = false;

				// owner's form
				$opinionAboutRenter = $request->getParameter('opinion_about_renter');
				$optionRecommendRenter = $request->getParameter('op_recom_renter');
				$comentNoRecommendRenter = $request->getParameter('coment_no_recom_renter');
				$optionCleaningAboutRenter = $request->getParameter('op_cleaning_about_renter');
				$optionPuntualAboutRenter = $request->getParameter('op_puntual_about_renter');
				$timeDelayStartRenter = $request->getParameter('time_delay_start_renter');
				$timeDelayEndRenter = $request->getParameter('time_delay_end_renter');

				// validacion de datos
				if (is_null($opinionAboutRenter) || $opinionAboutRenter == "") {
					throw new Exception("debes escribir tu experiencia con el arrendatario", 1);
				}

				if (is_null($optionRecommendRenter) || $optionRecommendRenter == "") {
					throw new Exception("debes indicar si recomiendarías al arrendatario", 1);
				} elseif($optionRecommendRenter == 0) {

					if (is_null($comentNoRecommendRenter) || $comentNoRecommendRenter == "") {
						throw new Exception("debes escribir porqué no recomendarías al arrendatario", 1);
					}
				}

				if (is_null($optionCleaningAboutRenter) || $optionCleaningAboutRenter == "") {
					throw new Exception("debes indicar la calificación que le pondrías", 1);
				}

				if (is_null($optionPuntualAboutRenter) || $optionPuntualAboutRenter == "") {
					throw new Exception("debes indicar si el arrendatario fué puntual a la hora de retirar y entregar el vehículo", 1);
				} elseif($optionPuntualAboutRenter == 0) {

					if (is_null($timeDelayStartRenter) || $timeDelayStartRenter == "") {
						throw new Exception("debes el tiempo del retraso en el retiro del vehículo al inicio del arriendo", 1);
					}

					if (is_null($timeDelayEndRenter) || $timeDelayEndRenter == "") {
						throw new Exception("debes el tiempo del retraso en la entrega del vehículo al término del arriendo", 1);
					}
				}
			}

			$ratinId = $Reserve->getRatingId();
			$Rating = Doctrine_core::getTable('rating')->find($ratinId);

			$dateNow = date('Y-m-d H:i:s');
			// Se verifica si yá existe un registro rating asociada a la reserva.
			// Si esxiste, se llena y se guarda la review.
			// Si no, se crea y se guarda.
			if($Rating){
				// se verifica que tipo de usuario está haciendo la calificacion (dueño o arrendatario).
				if($isRenter){

					$Rating->setOpinionAboutOwner($opinionAboutOwner);
					$Rating->setOpRecomCar($optionRecommendCar);
					$Rating->setComentNoRecomCar($comentNoRecommendCar);
					$Rating->setOpDespCar($optionDesperfectoCar);
					$Rating->setComentSiDespCar($comentSiDesperfectoCar);
					$Rating->setOpRecomOwner($optionRecommendOwner);
					$Rating->setComentNoRecomOwner($comentNoRecommendOwner);
					$Rating->setOpCleaningAboutOwner($optionCleaningAboutOwner);
					$Rating->setOpPuntualAboutOwner($optionPuntualAboutOwner);
					$Rating->setTimeDelayStartOwner($timeDelayStartOwner);
					$Rating->setTimeDelayEndOwner($timeDelayEndOwner);
					$Rating->setIdRenter($userId);
					$Rating->setFechaCalificacionRenter($dateNow);
					$Rating->setStateRenter(1);
					$Rating->save();

				} else {

					$Rating->setOpinionAboutRenter($opinionAboutRenter);
					$Rating->setOpRecomRenter($optionRecommendRenter);
					$Rating->setComentNoRecomRenter($comentNoRecommendRenter);
					$Rating->setOpCleaningAboutRenter($optionCleaningAboutRenter);
					$Rating->setOpPuntualAboutRenter($optionPuntualAboutRenter);
					$Rating->setTimeDelayStartRenter($timeDelayStartRenter);
					$Rating->setTimeDelayEndRenter($timeDelayEndRenter);
					$Rating->setIdOwner($userId);
					$Rating->setFechaCalificacionOwner($dateNow);
					$Rating->setStateOwner(1);
					$Rating->save();
				}

			}else{

				$Rating = new Rating();
				$Rating->setFechaHabilitadaDesde($Reserve->getFechaTermino2());
				// se verifica que tipo de usuario está haciendo la calificacion (dueño o arrendatario).
				if($isRenter){

					$Rating->setOpinionAboutOwner($opinionAboutOwner);
					$Rating->setOpRecomCar($optionRecommendCar);
					$Rating->setComentNoRecomCar($comentNoRecommendCar);
					$Rating->setOpDespCar($optionDesperfectoCar);
					$Rating->setComentSiDespCar($comentSiDesperfectoCar);
					$Rating->setOpRecomOwner($optionRecommendOwner);
					$Rating->setComentNoRecomOwner($comentNoRecommendOwner);
					$Rating->setOpCleaningAboutOwner($optionCleaningAboutOwner);
					$Rating->setOpPuntualAboutOwner($optionPuntualAboutOwner);
					$Rating->setTimeDelayStartOwner($timeDelayStartOwner);
					$Rating->setTimeDelayEndOwner($timeDelayEndOwner);
					$Rating->setIdRenter($userId);
					$Rating->setFechaCalificacionRenter($dateNow);
					$Rating->setStateRenter(1);
					$Rating->save();

					$Reserve->setRatingId($Rating->id);
					$Reserve->save();

				} else {

					$Rating->setOpinionAboutRenter($opinionAboutRenter);
					$Rating->setOpRecomRenter($optionRecommendRenter);
					$Rating->setComentNoRecomRenter($comentNoRecommendRenter);
					$Rating->setOpCleaningAboutRenter($optionCleaningAboutRenter);
					$Rating->setOpPuntualAboutRenter($optionPuntualAboutRenter);
					$Rating->setTimeDelayStartRenter($timeDelayStartRenter);
					$Rating->setTimeDelayEndRenter($timeDelayEndRenter);
					$Rating->setIdOwner($userId);
					$Rating->setFechaCalificacionOwner($dateNow);
					$Rating->setStateOwner(1);
					$Rating->save();

					$Reserve->setRatingId($Rating->id);
					$Reserve->save();
				}

			}
			
		} catch (Exception $e) {
			$return["error"] = true;
			$return["errorMessage"] = $e->getMessage();
		}

		$this->renderText(json_encode($return));

		return sfView::NONE;
	}

	public function executeGetHistoryList(sfWebRequest $request) {  
		$return = array("error" => false);
		$historyListAsOwner = "";
		$historyListAsRenter = "";
		try{

			$userId = $this->getUser()->getAttribute("userid");
			$Reserves = Doctrine_core::getTable('reserve')->findRatingReservesByUser($userId);
			$isOwner = null;

			foreach ($Reserves as $Reserve) {
				$renterId = $Reserve->getUserId();
				$ownerId = $Reserve->getCar()->getUserId();
				$Car = $Reserve->getCar();
				$Rating = $Reserve->getRating();

				// se tratan las fotos ( se verifica si están en el s3 o en el directorio del proyecto)
				$urlFotoTipo      = "../uploads/cars/".$Car->foto_perfil;
				$urlFotoThumbTipo = "../uploads/cars/".$Car->foto_perfil;                    

				if($Car->photoS3 == 1) {
					$urlFotoTipo = $Car->foto_perfil;
					$urlFotoThumbTipo = $Car->foto_perfil;
				}

				$str = 0;
				if ($Car->foto_perfil) {
					$str = stripos($Car->foto_perfil, "cars");
				}

				if($str > 0) {
					$urlFotoTipo = $Car->foto_perfil;
					$urlFotoThumbTipo = $Car->foto_perfil;
				}

				// verifica si el usuario es un dueño en la reserva iterada.
				if($ownerId == $userId && $renterId != $userId){
					
					$isOwner = true;
				} elseif($ownerId != $userId && $renterId == $userId) {
					
					$isOwner = false;
				}

				if($Rating){
					$Renter = Doctrine_core::getTable("user")->find($renterId);
					$Owner = Doctrine_core::getTable("user")->find($ownerId);
					if($isOwner){
						
						if($Rating->getStateOwner()){
							$historyListAsOwner = $historyListAsOwner."<article class='box thumbnail' data-id='".$Reserve->id."'>";
							$historyListAsOwner = $historyListAsOwner."<div class='row'>";
							$historyListAsOwner = $historyListAsOwner."<div class='col-xs-4 col-md-4 image'>";
							$historyListAsOwner = $historyListAsOwner."<div class='hidden-sm space-10'></div>";
							if(!$Car->getFotoPerfilS3("md")){
								if($str > 0) {
									$historyListAsOwner = $historyListAsOwner."<img class='img-responsive car-r-image' src='http://www.arriendas.cl".$urlFotoThumbTipo."' height='99' width='134' alt='rent a car ".$Car->getModel()->getBrand()->name." ".$Car->getModel()->name."'/>";
								}else{
									$historyListAsOwner = $historyListAsOwner."<img class='img-responsive car-r-image' src='http://res.cloudinary.com/arriendas-cl/image/fetch/w_112,h_84,c_fill,g_center/http://www.arriendas.cl".$urlFotoThumbTipo."' height='99' width='134' alt='rent a car ".$Car->getModel()->getBrand()->name." ".$Car->getModel()->name."'/>";
								}
							} else {
								$historyListAsOwner = $historyListAsOwner."<img class='img-responsive car-r-image' src='".$$Car->getFotoPerfilS3("md")."' height='99' width='134' alt='rent a car ".$Car->getModel()->getBrand()->name." ".$Car->getModel()->name."'/>";
							}
							$historyListAsOwner = $historyListAsOwner."</div>";
							$historyListAsOwner = $historyListAsOwner."<div class='col-xs-8 col-md-8 text'>";
							$historyListAsOwner = $historyListAsOwner."<h2>".$Car->getModel()->getBrand()->name." ".$Car->getModel()->name."<small>, ".$Car->year."</small></h2>";
							$historyListAsOwner = $historyListAsOwner."<p><b>Nombre del arrendatario:</b> &nbsp;&nbsp;".$Renter->getFirstname()." ".$Renter->getLastname()."</p>";
							$historyListAsOwner = $historyListAsOwner."<p><b>Fecha de la reserva:</b> &nbsp;&nbsp;".$Reserve->getFechaReserva()."</p>";
							$historyListAsOwner = $historyListAsOwner."<p><b>Fecha de fin:</b> &nbsp;&nbsp;".$Reserve->getFechaTermino2()."</p>";
							$historyListAsOwner = $historyListAsOwner."<p><b>Calificacion:</b> &nbsp;&nbsp; <span class='history-rate' data-score='".$Rating->getOpCleaningAboutRenter()."'></span></p>";
							$historyListAsOwner = $historyListAsOwner."</div>";
							$historyListAsOwner = $historyListAsOwner."</div>";
							$historyListAsOwner = $historyListAsOwner."<br>";
							$historyListAsOwner = $historyListAsOwner."</article>";
						}
					} else {
						if($Rating->getStateRenter()){
							$historyListAsRenter = $historyListAsRenter."<article class='box thumbnail' data-id='".$Reserve->id."'>";
							$historyListAsRenter = $historyListAsRenter."<div class='row'>";
							$historyListAsRenter = $historyListAsRenter."<div class='col-xs-4 col-md-4 image'>";
							$historyListAsRenter = $historyListAsRenter."<div class='hidden-sm space-10'></div>";
							if(!$Car->getFotoPerfilS3("md")){
								if($str > 0) {
									$historyListAsRenter = $historyListAsRenter."<img class='img-responsive car-r-image' src='http://www.arriendas.cl".$urlFotoThumbTipo."' height='99' width='134' alt='rent a car ".$Car->getModel()->getBrand()->name." ".$Car->getModel()->name."'/>";
								}else{
									$historyListAsRenter = $historyListAsRenter."<img class='img-responsive car-r-image' src='http://res.cloudinary.com/arriendas-cl/image/fetch/w_112,h_84,c_fill,g_center/http://www.arriendas.cl".$urlFotoThumbTipo."' height='99' width='134' alt='rent a car ".$Car->getModel()->getBrand()->name." ".$Car->getModel()->name."'/>";
								}
							} else {
								$historyListAsRenter = $historyListAsRenter."<img class='img-responsive car-r-image' src='".$$Car->getFotoPerfilS3("md")."' height='99' width='134' alt='rent a car ".$Car->getModel()->getBrand()->name." ".$Car->getModel()->name."'/>";
							}
							$historyListAsRenter = $historyListAsRenter."</div>";
							$historyListAsRenter = $historyListAsRenter."<div class='col-xs-8 col-md-8 text'>";
							$historyListAsRenter = $historyListAsRenter."<h2>".$Car->getModel()->getBrand()->name." ".$Car->getModel()->name."<small>, ".$Car->year."</small></h2>";
							$historyListAsRenter = $historyListAsRenter."<p><b>Nombre del Dueño:</b> &nbsp;&nbsp;".$Owner->getFirstname()." ".$Owner->getLastname()."</p>";
							$historyListAsRenter = $historyListAsRenter."<p><b>Fecha de la reserva:</b> &nbsp;&nbsp;".$Reserve->getFechaReserva()."</p>";
							$historyListAsRenter = $historyListAsRenter."<p><b>Fecha de fin:</b> &nbsp;&nbsp;".$Reserve->getFechaTermino2()."</p>";
							$historyListAsRenter = $historyListAsRenter."<p><b>Calificacion:</b> &nbsp;&nbsp; <span class='history-rate' data-score='".$Rating->getOpCleaningAboutOwner()."'></span></p>";						
							$historyListAsRenter = $historyListAsRenter."</div>";
							$historyListAsRenter = $historyListAsRenter."</div>";
							$historyListAsRenter = $historyListAsRenter."<br>";
							$historyListAsRenter = $historyListAsRenter."</article>";
						}
					}
				}
				
				
			}

			$return["renterList"] = $historyListAsRenter;
			$return["ownerList"] = $historyListAsOwner;
			

		} catch (Exception $e) {
			$return["error"] = true;
			$return["errorMessage"] = $e->getMessage();
		}

		$this->renderText(json_encode($return));

		return sfView::NONE;

	}

	public function executeGetMyRatesList(sfWebRequest $request) {  
		$return = array("error" => false);
		$historyListAsOwner = "";
		$historyListAsRenter = "";
		try{

			$userId = $this->getUser()->getAttribute("userid");
			$Reserves = Doctrine_core::getTable('reserve')->findRatingReservesByUser($userId);
			$isOwner = null;

			foreach ($Reserves as $Reserve) {
				$renterId = $Reserve->getUserId();
				$ownerId = $Reserve->getCar()->getUserId();
				$Car = $Reserve->getCar();
				$Rating = $Reserve->getRating();

				// se tratan las fotos ( se verifica si están en el s3 o en el directorio del proyecto)
				$urlFotoTipo      = "../uploads/cars/".$Car->foto_perfil;
				$urlFotoThumbTipo = "../uploads/cars/".$Car->foto_perfil;                    

				if($Car->photoS3 == 1) {
					$urlFotoTipo = $Car->foto_perfil;
					$urlFotoThumbTipo = $Car->foto_perfil;
				}

				$str = 0;
				if ($Car->foto_perfil) {
					$str = stripos($Car->foto_perfil, "cars");
				}

				if($str > 0) {
					$urlFotoTipo = $Car->foto_perfil;
					$urlFotoThumbTipo = $Car->foto_perfil;
				}

				// verifica si el usuario es un dueño en la reserva iterada.
				if($ownerId == $userId && $renterId != $userId){
					
					$isOwner = true;
				} elseif($ownerId != $userId && $renterId == $userId) {
					
					$isOwner = false;
				}

				if($Rating){
					$Renter = Doctrine_core::getTable("user")->find($renterId);
					$Owner = Doctrine_core::getTable("user")->find($ownerId);
					if($isOwner){
						
						if($Rating->getStateRenter()){
							$historyListAsOwner = $historyListAsOwner."<article class='box thumbnail' data-id='".$Reserve->id."'>";
							$historyListAsOwner = $historyListAsOwner."<div class='row'>";
							$historyListAsOwner = $historyListAsOwner."<div class='col-xs-4 col-md-4 image'>";
							$historyListAsOwner = $historyListAsOwner."<div class='hidden-sm space-10'></div>";
							if(!$Car->getFotoPerfilS3("md")){
								if($str > 0) {
									$historyListAsOwner = $historyListAsOwner."<img class='img-responsive car-r-image' src='http://www.arriendas.cl".$urlFotoThumbTipo."' height='99' width='134' alt='rent a car ".$Car->getModel()->getBrand()->name." ".$Car->getModel()->name."'/>";
								}else{
									$historyListAsOwner = $historyListAsOwner."<img class='img-responsive car-r-image' src='http://res.cloudinary.com/arriendas-cl/image/fetch/w_112,h_84,c_fill,g_center/http://www.arriendas.cl".$urlFotoThumbTipo."' height='99' width='134' alt='rent a car ".$Car->getModel()->getBrand()->name." ".$Car->getModel()->name."'/>";
								}
							} else {
								$historyListAsOwner = $historyListAsOwner."<img class='img-responsive car-r-image' src='".$$Car->getFotoPerfilS3("md")."' height='99' width='134' alt='rent a car ".$Car->getModel()->getBrand()->name." ".$Car->getModel()->name."'/>";
							}
							$historyListAsOwner = $historyListAsOwner."</div>";
							$historyListAsOwner = $historyListAsOwner."<div class='col-xs-8 col-md-8 text'>";
							$historyListAsOwner = $historyListAsOwner."<h2>".$Car->getModel()->getBrand()->name." ".$Car->getModel()->name."<small>, ".$Car->year."</small></h2>";
							$historyListAsOwner = $historyListAsOwner."<p><small>Calificado como dueño</small></p>";
							$historyListAsOwner = $historyListAsOwner."<p><b>Nombre del arrendatario:</b> &nbsp;&nbsp;".$Renter->getFirstname()." ".$Renter->getLastname()."</p>";
							$historyListAsOwner = $historyListAsOwner."<p><b>Fecha de la reserva:</b> &nbsp;&nbsp;".$Reserve->getFechaReserva()."</p>";
							$historyListAsOwner = $historyListAsOwner."<p><b>Fecha de fin:</b> &nbsp;&nbsp;".$Reserve->getFechaTermino2()."</p>";
							$historyListAsOwner = $historyListAsOwner."<p><b>Calificacion:</b> &nbsp;&nbsp; <span class='history-rate' data-score='".$Rating->getOpCleaningAboutOwner()."'></span></p>";
							$historyListAsOwner = $historyListAsOwner."</div>";
							$historyListAsOwner = $historyListAsOwner."</div>";
							$historyListAsOwner = $historyListAsOwner."<br>";
							$historyListAsOwner = $historyListAsOwner."</article>";
						}
					} else {
						if($Rating->getStateOwner()){
							$historyListAsRenter = $historyListAsRenter."<article class='box thumbnail' data-id='".$Reserve->id."'>";
							$historyListAsRenter = $historyListAsRenter."<div class='row'>";
							$historyListAsRenter = $historyListAsRenter."<div class='col-xs-4 col-md-4 image'>";
							$historyListAsRenter = $historyListAsRenter."<div class='hidden-sm space-10'></div>";
							if(!$Car->getFotoPerfilS3("md")){
								if($str > 0) {
									$historyListAsRenter = $historyListAsRenter."<img class='img-responsive car-r-image' src='http://www.arriendas.cl".$urlFotoThumbTipo."' height='99' width='134' alt='rent a car ".$Car->getModel()->getBrand()->name." ".$Car->getModel()->name."'/>";
								}else{
									$historyListAsRenter = $historyListAsRenter."<img class='img-responsive car-r-image' src='http://res.cloudinary.com/arriendas-cl/image/fetch/w_112,h_84,c_fill,g_center/http://www.arriendas.cl".$urlFotoThumbTipo."' height='99' width='134' alt='rent a car ".$Car->getModel()->getBrand()->name." ".$Car->getModel()->name."'/>";
								}
							} else {
								$historyListAsRenter = $historyListAsRenter."<img class='img-responsive car-r-image' src='".$$Car->getFotoPerfilS3("md")."' height='99' width='134' alt='rent a car ".$Car->getModel()->getBrand()->name." ".$Car->getModel()->name."'/>";
							}
							$historyListAsRenter = $historyListAsRenter."</div>";
							$historyListAsRenter = $historyListAsRenter."<div class='col-xs-8 col-md-8 text'>";
							$historyListAsRenter = $historyListAsRenter."<h2>".$Car->getModel()->getBrand()->name." ".$Car->getModel()->name."<small>, ".$Car->year."</small></h2>";
							$historyListAsRenter = $historyListAsRenter."<p><small>Calificado como arrendatario</small></p>";
							$historyListAsRenter = $historyListAsRenter."<p><b>Nombre del Dueño:</b> &nbsp;&nbsp;".$Owner->getFirstname()." ".$Owner->getLastname()."</p>";
							$historyListAsRenter = $historyListAsRenter."<p><b>Fecha de la reserva:</b> &nbsp;&nbsp;".$Reserve->getFechaReserva()."</p>";
							$historyListAsRenter = $historyListAsRenter."<p><b>Fecha de fin:</b> &nbsp;&nbsp;".$Reserve->getFechaTermino2()."</p>";
							$historyListAsRenter = $historyListAsRenter."<p><b>Calificacion:</b> &nbsp;&nbsp; <span class='history-rate' data-score='".$Rating->getOpCleaningAboutRenter()."'></span></p>";						
							$historyListAsRenter = $historyListAsRenter."</div>";
							$historyListAsRenter = $historyListAsRenter."</div>";
							$historyListAsRenter = $historyListAsRenter."<br>";
							$historyListAsRenter = $historyListAsRenter."</article>";
						}
					}
				}
				
				
			}

			$return["renterList"] = $historyListAsRenter;
			$return["ownerList"] = $historyListAsOwner;
			

		} catch (Exception $e) {
			$return["error"] = true;
			$return["errorMessage"] = $e->getMessage();
		}

		$this->renderText(json_encode($return));

		return sfView::NONE;

	}    
}
