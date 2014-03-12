<?php

/**
 * Description of UserDTO
 *
 * @author Juan Manuel Agüero <jaguero@flowcode.com.ar>
 */
class UserDTO {

    public static function getFromInstance(User $user) {
        $dto = $user->toArray();
        unset($dto["username"]);
        unset($dto["password"]);
        unset($dto["url"]);
        unset($dto["rut"]);
        unset($dto["friend_invite"]);
        unset($dto["facebook_id"]);
        unset($dto["serie_rut"]);
        unset($dto["paypal_id"]);
        unset($dto["driver_license_number"]);
        unset($dto["hash"]);
        unset($dto["confirmed"]);
        unset($dto["autoconfirm"]);
        unset($dto["identification"]);
        unset($dto["country"]);
        unset($dto["city"]);
        unset($dto["comuna"]);
        unset($dto["confirmed_fb"]);
        unset($dto["confirmed_sms"]);
        unset($dto["rut_file"]);
        unset($dto["driver_license_file"]);
        unset($dto["picture_file"]);
        unset($dto["deleted"]);
        unset($dto["blocked"]);
        unset($dto["fecha_registro"]);
        unset($dto["credito"]);
        unset($dto["propietario"]);
        unset($dto["codigo_confirmacion"]);
        unset($dto["como"]);
        unset($dto["customerio"]);
        return $dto;
    }

}
