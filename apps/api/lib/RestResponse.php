<?php

/**
 * Description of RestResponse
 *
 * @author Juan Manuel Agüero <jaguero@flowcode.com.ar>
 */
class RestResponse {

    public static $STATUS_SUCCESS = "success";
    public static $STATUS_ERROR = "error";
    /** error codes **/
    /* file error codes */
    public static $CODE_UNSUPPORTED_FORMAT = 100;
    public static $CODE_WRONG_SIZE = 101;
    /* reserve error codes */
    public static $CODE_RESERVE_UNKNOWN = 200;
    public static $CODE_RESERVE_NOT_INITIALIZABLE = 201;
    public static $CODE_RESERVE_NOT_FINALIZABLE = 202;
    /* user error codes */
    public static $CODE_USER_UNKNOWN = 300;
    public static $CODE_USER_NOT_CONFIRMED = 301;

}
