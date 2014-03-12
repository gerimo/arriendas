<?php

/**
 * reserve actions.
 *
 * @package    api
 * @subpackage usuarios
 * @author     Your name here
 */
class reserveActions extends RestActions {

    /**
     * Executes one action
     *
     * @param sfRequest $request A request object
     */
    public function executeOne(sfWebRequest $request) {

        $reserve = Doctrine::getTable('Reserve')->findOneById($request->getParameter("id"));

        if (!$reserve) {
            $this->feedback = 'Unable to load the specified resource';
            $this->setTemplate('error');
        } else {
            $this->object = ReserveDTO::getFromInstance($reserve);
            $this->setTemplate('object');
        }
    }

    /**
     * Executes one action
     *
     * @param sfRequest $request A request object
     */
    public function executeOwner(sfWebRequest $request) {

        $reserve = Doctrine::getTable('Reserve')->findOneById($request->getParameter("id"));

        if (!empty($reserve) && !is_null($reserve)) {
            $this->object = UserDTO::getFromInstance($reserve->getCar()->getUser());
            $this->setTemplate('object');
        } else {
            $this->feedback = 'Unable to load the specified resource';
            $this->setTemplate('error');
        }
    }

    /**
     * 
     * @param sfWebRequest $request
     */
    public function executeInitializePostVideo(sfWebRequest $request) {

        $this->forward404If($request->getMethod() != sfWebRequest::POST);

        $success = true;

        /* valid reserve */
        $reserveId = $request->getParameter("reserve_id");
        $reserve = Doctrine::getTable("reserve")->findOneById($reserveId);
        if (!is_null($reserve) && !empty($reserve)) {

            /* check if reserve is ready for init */
            if ($reserve->isReadyForInitialize()) {

                /* check for files */
                $files = $request->getFiles();
                if (count($files) > 0 && isset($files["reserveVideo"])) {
                    $file = $files["reserveVideo"];
                    /* validate video */
                    if ($file['size'] < sfConfig::get("app_video_size_min") || $file['size'] > sfConfig::get("app_video_size_max")) {
                        $this->setResponse(RestResponse::$STATUS_ERROR, RestResponse::$CODE_WRONG_SIZE, "Invalid video size");
                    } elseif (!in_array($file['type'], $this->getAllowedVideoFormats())) {
                        $this->setResponse(RestResponse::$STATUS_ERROR, RestResponse::$CODE_UNSUPPORTED_FORMAT, "Unsupported video format");
                    } else {
                        /* move video */
                        try {
                            $theFileName = "car-ini-" . $reserve->getUserId() . "-" . $reserveId . "-" . $file['name'];
                            $uploadDir = sfConfig::get("sf_upload_dir") . "/" . sfConfig::get("app_path_reserve_videos");
                            move_uploaded_file($file['tmp_name'], "$uploadDir/$theFileName");

                            /* inicio arriendo */
                            foreach ($reserve->getReserveFiles() as $reserveFile) {
                                if ($reserveFile->getType() == ReserveFile::$TYPE_VIDEO_CAR_INI) {
                                    $reserveFile->delete();
                                }
                            }

                            $reserveVideoFile = new ReserveFile();
                            $reserveVideoFile->setReserve($reserve);
                            $reserveVideoFile->setType(ReserveFile::$TYPE_VIDEO_CAR_INI);
                            $reserveVideoFile->setPath($theFileName);
                            $reserveVideoFile->save();

                            /* inicio arriendo */
                            $reserve->setInicioArriendoOk(true);
                            $reserve->save();

                            $this->setResponse(RestResponse::$STATUS_SUCCESS, null, "Video uploaded, reserve initialized");
                        } catch (Exception $exc) {
                            $this->feedback = 'Missing video file';
                            $this->setTemplate('error');
                        }
                    }
                } else {
                    $this->feedback = 'Missing video file';
                    $this->setTemplate('error');
                }
            } else {
                /* reserve not ready */
                $this->setResponse(RestResponse::$STATUS_ERROR, RestResponse::$CODE_RESERVE_NOT_INITIALIZABLE, "Reserve not ready for initialize");
            }
        } else {
            /* reserve not found */
            $this->setResponse(RestResponse::$STATUS_ERROR, RestResponse::$CODE_RESERVE_UNKNOWN, "Unknown reserve");
        }
    }

    /**
     * 
     * @param sfWebRequest $request
     */
    public function executeFinalizePostVideo(sfWebRequest $request) {

        $this->forward404If($request->getMethod() != sfWebRequest::POST);

        $success = true;

        /* valid reserve */
        $reserveId = $request->getParameter("reserve_id");
        $reserve = Doctrine::getTable("reserve")->findOneById($reserveId);
        if (!is_null($reserve) && !empty($reserve)) {

            /* check if reserve is ready for fin */
            if ($reserve->isReadyForFinalize()) {

                /* check for files */
                $files = $request->getFiles();
                if (count($files) > 0 && isset($files["reserveVideo"])) {
                    $file = $files["reserveVideo"];
                    /* validate video */
                    if ($file['size'] < sfConfig::get("app_video_size_min") || $file['size'] > sfConfig::get("app_video_size_max")) {
                        $this->setResponse(RestResponse::$STATUS_ERROR, RestResponse::$CODE_WRONG_SIZE, "Video with unsupported size");
                    } elseif (!in_array($file['type'], $this->getAllowedVideoFormats())) {
                        $this->setResponse(RestResponse::$STATUS_ERROR, RestResponse::$CODE_UNSUPPORTED_FORMAT, "Video format not supported");
                    } else {
                        /* move video */
                        try {
                            $theFileName = "car-fin-" . $reserve->getUserId() . "-" . $reserveId . "-" . $file['name'];
                            $uploadDir = sfConfig::get("sf_upload_dir") . "/" . sfConfig::get("app_path_reserve_videos");
                            move_uploaded_file($file['tmp_name'], "$uploadDir/$theFileName");

                            /* inicio arriendo */
                            foreach ($reserve->getReserveFiles() as $reserveFile) {
                                if ($reserveFile->getType() == ReserveFile::$TYPE_VIDEO_CAR_FIN) {
                                    $reserveFile->delete();
                                }
                            }

                            $reserveVideoFile = new ReserveFile();
                            $reserveVideoFile->setReserve($reserve);
                            $reserveVideoFile->setType(ReserveFile::$TYPE_VIDEO_CAR_FIN);
                            $reserveVideoFile->setPath($theFileName);
                            $reserveVideoFile->save();

                            /* inicio arriendo */
                            $reserve->setFinArriendoOk(true);
                            $reserve->save();

                            $this->setResponse(RestResponse::$STATUS_SUCCESS, null, "Video uploaded, reserve finalized");
                        } catch (Exception $exc) {
                            $this->feedback = 'Missing video file';
                            $this->setTemplate('error');
                        }
                    }
                } else {
                    $this->feedback = 'Missing video file';
                    $this->setTemplate('error');
                }
            } else {
                /* reserve not ready */
                $this->setResponse(RestResponse::$STATUS_ERROR, RestResponse::$CODE_RESERVE_NOT_INITIALIZABLE, "Reserve not ready for finalize");
            }
        } else {
            /* reserve not found */
            $this->setResponse(RestResponse::$STATUS_ERROR, RestResponse::$CODE_RESERVE_UNKNOWN, "Unknown reserve");
        }
    }

    public function executePostLicenceImage(sfWebRequest $request) {

        $this->forward404If($request->getMethod() != sfWebRequest::POST);

        /* valid reserve */
        $reserveId = $request->getParameter("reserve_id");
        $reserve = Doctrine::getTable("reserve")->findOneById($reserveId);
        if (!is_null($reserve) && !empty($reserve)) {

            /* check for files */
            $files = $request->getFiles();
            if (count($files) > 0 && isset($files["reserveImage"])) {
                $file = $files["reserveImage"];
                /* validate video */
                if ($file['size'] < sfConfig::get("app_image_size_min") || $file['size'] > sfConfig::get("app_image_size_max")) {
                    $this->setResponse(RestResponse::$STATUS_ERROR, RestResponse::$CODE_WRONG_SIZE, "Invalid image size");
                } elseif (!in_array($file['type'], $this->getAllowedImageFormats())) {
                    $this->setResponse(RestResponse::$STATUS_ERROR, RestResponse::$CODE_UNSUPPORTED_FORMAT, "Image format not supported");
                } else {
                    /* move video */
                    try {
                        $theFileName = "licence-" . $reserve->getUserId() . "-" . $reserveId . "-" . $file['name'];
                        $uploadDir = sfConfig::get("sf_upload_dir") . "/" . sfConfig::get("app_path_reserve_images");
                        move_uploaded_file($file['tmp_name'], "$uploadDir/$theFileName");

                        /* inicio arriendo */
                        foreach ($reserve->getReserveFiles() as $reserveFile) {
                            if ($reserveFile->getType() == ReserveFile::$TYPE_IMAGE_LICENCE) {
                                $reserveFile->delete();
                            }
                        }

                        $reserveVideoFile = new ReserveFile();
                        $reserveVideoFile->setReserve($reserve);
                        $reserveVideoFile->setType(ReserveFile::$TYPE_IMAGE_LICENCE);
                        $reserveVideoFile->setPath($theFileName);
                        $reserveVideoFile->save();

                        $this->setResponse(RestResponse::$STATUS_SUCCESS, null, "Image uploaded");
                    } catch (Exception $exc) {
                        $this->feedback = 'Missing image file';
                        $this->setTemplate('error');
                    }
                }
            } else {
                $this->feedback = 'Missing image file';
                $this->setTemplate('error');
            }
        } else {
            /* reserve not found */
            $this->setResponse(RestResponse::$STATUS_ERROR, RestResponse::$CODE_RESERVE_UNKNOWN, "Unknown reserve");
        }
    }

    public function executeInitializePostCarPanelImage(sfWebRequest $request) {

        $this->forward404If($request->getMethod() != sfWebRequest::POST);

        /* valid reserve */
        $reserveId = $request->getParameter("reserve_id");
        $reserve = Doctrine::getTable("reserve")->findOneById($reserveId);
        if (!is_null($reserve) && !empty($reserve)) {

            /* check for files */
            $files = $request->getFiles();
            if (count($files) > 0 && isset($files["reserveImage"])) {
                $file = $files["reserveImage"];
                /* validate video */
                if ($file['size'] < sfConfig::get("app_image_size_min") || $file['size'] > sfConfig::get("app_image_size_max")) {
                    $this->setResponse(RestResponse::$STATUS_ERROR, RestResponse::$CODE_WRONG_SIZE, "Invalid image size");
                } elseif (!in_array($file['type'], $this->getAllowedImageFormats())) {
                    $this->setResponse(RestResponse::$STATUS_ERROR, RestResponse::$CODE_UNSUPPORTED_FORMAT, "Image format not supported");
                } else {
                    /* move video */
                    try {
                        $theFileName = "car-panel-ini-" . $reserve->getUserId() . "-" . $reserveId . "-" . $file['name'];
                        $uploadDir = sfConfig::get("sf_upload_dir") . "/" . sfConfig::get("app_path_reserve_images");
                        move_uploaded_file($file['tmp_name'], "$uploadDir/$theFileName");

                        /* inicio arriendo */
                        foreach ($reserve->getReserveFiles() as $reserveFile) {
                            if ($reserveFile->getType() == ReserveFile::$TYPE_IMAGE_CAR_PANEL_INI) {
                                $reserveFile->delete();
                            }
                        }

                        $reserveVideoFile = new ReserveFile();
                        $reserveVideoFile->setReserve($reserve);
                        $reserveVideoFile->setType(ReserveFile::$TYPE_IMAGE_CAR_PANEL_INI);
                        $reserveVideoFile->setPath($theFileName);
                        $reserveVideoFile->save();

                        $this->setResponse(RestResponse::$STATUS_SUCCESS, null, "Image uploaded");
                    } catch (Exception $exc) {
                        $this->feedback = 'Missing image file';
                        $this->setTemplate('error');
                    }
                }
            } else {
                $this->feedback = 'Missing image file';
                $this->setTemplate('error');
            }
        } else {
            /* reserve not found */
            $this->setResponse(RestResponse::$STATUS_ERROR, RestResponse::$CODE_RESERVE_UNKNOWN, "Unknown reserve");
        }
    }

    public function executeFinalizePostCarPanelImage(sfWebRequest $request) {

        $this->forward404If($request->getMethod() != sfWebRequest::POST);

        /* valid reserve */
        $reserveId = $request->getParameter("reserve_id");
        $reserve = Doctrine::getTable("reserve")->findOneById($reserveId);
        if (!is_null($reserve) && !empty($reserve)) {

            /* check for files */
            $files = $request->getFiles();
            if (count($files) > 0 && isset($files["reserveImage"])) {
                $file = $files["reserveImage"];
                /* validate video */
                if ($file['size'] < sfConfig::get("app_image_size_min") || $file['size'] > sfConfig::get("app_image_size_max")) {
                    $this->setResponse(RestResponse::$STATUS_ERROR, RestResponse::$CODE_WRONG_SIZE, "Invalid image size");
                } elseif (!in_array($file['type'], $this->getAllowedImageFormats())) {
                    $this->setResponse(RestResponse::$STATUS_ERROR, RestResponse::$CODE_UNSUPPORTED_FORMAT, "Image format not supported");
                } else {
                    try {
                        /* move video */
                        $theFileName = "car-panel-fin-" . $reserve->getUserId() . "-" . $reserveId . "-" . $file['name'];
                        $uploadDir = sfConfig::get("sf_upload_dir") . "/" . sfConfig::get("app_path_reserve_images");
                        move_uploaded_file($file['tmp_name'], "$uploadDir/$theFileName");

                        /* guardar foto */
                        foreach ($reserve->getReserveFiles() as $reserveFile) {
                            if ($reserveFile->getType() == ReserveFile::$TYPE_IMAGE_CAR_PANEL_FIN) {
                                $reserveFile->delete();
                            }
                        }

                        $reserveVideoFile = new ReserveFile();
                        $reserveVideoFile->setReserve($reserve);
                        $reserveVideoFile->setType(ReserveFile::$TYPE_IMAGE_CAR_PANEL_FIN);
                        $reserveVideoFile->setPath($theFileName);
                        $reserveVideoFile->save();

                        $this->setResponse(RestResponse::$STATUS_SUCCESS, null, "Image uploaded");
                    } catch (Exception $exc) {
                        $this->feedback = 'Missing image file';
                        $this->setTemplate('error');
                    }
                }
            } else {
                $this->feedback = 'Missing image file';
                $this->setTemplate('error');
            }
        } else {
            /* reserve not found */
            $this->setResponse(RestResponse::$STATUS_ERROR, RestResponse::$CODE_RESERVE_UNKNOWN, "Unknown reserve");
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
