<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ReserveDTO
 *
 * @author Juan Manuel Agüero <jaguero@flowcode.com.ar>
 */
class ReserveDTO {

    public static function getFromInstance(Reserve $reserve) {
        $dto = $reserve->toArray();
        unset($dto["Rating_id"]);
        unset($dto["visible_owner"]);
        unset($dto["visible_owner"]);
        unset($dto["visible_renter"]);
        unset($dto["sancion"]);
        unset($dto["ini_km_confirmed"]);
        unset($dto["end_km_confirmed"]);
        unset($dto["ini_km_owner_confirmed"]);
        unset($dto["end_km_owner_confirmed"]);
        unset($dto["kminicial"]);
        unset($dto["kmfinal"]);
        unset($dto["price"]);
        unset($dto["documentos_owner"]);
        unset($dto["documentos_renter"]);
        unset($dto["declaracion_danios"]);
        unset($dto["declaracion_devolucion"]);
        unset($dto["comentario"]);
        unset($dto["token"]);
        unset($dto["id_padre"]);
        unset($dto["cambioEstadoRapido"]);
        return $dto;
    }

}
