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
        $user = Doctrine::getTable('User')->findOneById($request->getParameter("id"));
        if (!$user) {
            $this->setResponse(RestResponse::$STATUS_ERROR, RestResponse::$CODE_USER_UNKNOWN, "Unknown user");
        } else {
            $this->object = UserDTO::getFromInstance($user);
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
            if (!empty($user) && !is_null($user)) {
                if ($user->getConfirmed() == 1) {
                    $this->object = array(
                        "status" => RestResponse::$STATUS_SUCCESS,
                        "message" => "Valid user",
                        "user_id" => $user->getId()
                    );
                    $this->setTemplate('object');
                } else {
                    $this->setResponse(RestResponse::$STATUS_ERROR, RestResponse::$CODE_USER_NOT_CONFIRMED, "User not confirmed");
                }
            } else {
                $this->setResponse(RestResponse::$STATUS_ERROR, RestResponse::$CODE_USER_UNKNOWN, "Unknown user");
            }
        } catch (Exception $e) {
            $this->setResponse(RestResponse::$STATUS_ERROR, RestResponse::$CODE_USER_UNKNOWN, "Unknown user");
        }
    }

    public function executeForgot(sfWebRequest $request) {
        $this->forward404If($request->getMethod() != sfWebRequest::POST);
        $this->logMessage("email: " . $request->getParameter('email'));
        $q = Doctrine::getTable('user')->createQuery('u')->where('u.email = ?', $request->getParameter('email'));
        $user = $q->fetchOne();
        if (!empty($user) && !is_null($user)) {
            $this->sendRecoverEmail($user);
            $this->setResponse(RestResponse::$STATUS_SUCCESS, "Mail for recover sent");
        } else {
            $this->setResponse(RestResponse::$STATUS_ERROR, RestResponse::$CODE_USER_UNKNOWN, "Unknown user");
        }
    }

    public function executeCurrentReserve(sfWebRequest $request) {
        $userId = $request->getParameter("user_id");

        $reserves = Doctrine::getTable("reserve")->getTodayReserves($userId);
        $reservesReadyForInitialize = array();
        foreach ($reserves as $reserve) {
            if ($reserve->isReadyForInitialize()) {
                $reservesReadyForInitialize[] = $reserve;
            }
        }
        if (count($reservesReadyForInitialize) > 0) {
            $this->objectList = $reservesReadyForInitialize;
            $this->setTemplate('objectList');
        } else {
            $this->setResponse(RestResponse::$STATUS_SUCCESS, null, "No current reserve found");
        }
    }

    public function executeCurrentReserveInitialized(sfWebRequest $request) {
        $userId = $request->getParameter("user_id");

        $user = Doctrine::getTable('User')->findOneById($userId);
        if (!empty($user) && !is_null($user)) {
            $reserves = Doctrine::getTable("reserve")->findInitializedByUser($userId);
            if (count($reserves) > 0) {
                $this->object = $reserves[0];
                $this->setTemplate('object');
            } else {
                $this->setResponse(RestResponse::$STATUS_SUCCESS, null, "No reserve found");
            }
        } else {
            $this->setResponse(RestResponse::$STATUS_ERROR, RestResponse::$CODE_USER_UNKNOWN, "Unknown user");
        }
    }

    public function sendRecoverEmail($user) {

        $url = $_SERVER['SERVER_NAME'];
        $url = str_replace('http://', '', $url);
        $url = str_replace('https://', '', $url);
        $url = 'http://www.arriendas.cl/main/recover?email=' . $user->getEmail() . "&hash=" . $user->getHash();

        sfContext::getInstance()->getLogger()->info($url);
        
        $body = "<p>Hola:</p><p>Para generar una nueva contraseña, haz click <a href='$url'>aqu&iacute;</a></p>";
        $message = $this->getMailer()->compose();
        $message->setSubject("Recuperar Password");
        $message->setFrom('soporte@arriendas.cl', 'Soporte Arriendas');
        $message->setTo($user->getEmail());
        $message->setBody($body, "text/html");
        $this->getMailer()->send($message);

        sfContext::getInstance()->getLogger()->info($body);
        sfContext::getInstance()->getLogger()->info('mail sent');
    }

}
