<?php

class emailsActions extends sfActions {

    public function executeIndex(sfWebRequest $request) {}

    public function executeUnsubscribe (sfWebRequest $request) {

        $this->setLayout("newIndexLayout");

        $this->isError = false;
        $this->message = null;

        $userId    = $request->getParameter("user_id", null);
        $mailingId = $request->getParameter("mailing_id", null);
        $signature = $request->getParameter("signature", null);

        try {

            if (is_null($signature)) {
                throw new Exception("Falta la firma para User ".$userId." y Mailing ".$mailingId, 1);
            }

            $S = md5("Arriendas.cl ~ ".$userId." ~ ".$mailingId);
            if ($S != $signature) {
                throw new Exception("Firmas no coinciden para User ".$userId." y Mailing ".$mailingId, 1);
            }

            if (is_null($userId)) {
                throw new Exception("Falta el User ID", 1);
            }

            $User = Doctrine_Core::getTable('User')->find($userId);
            if (!$User) {
                throw new Exception("No se encontro el User ".$userId, 1);
            }

            if (is_null($mailingId)) {
                throw new Exception("Falta el Mailing ID para User ".$userId, 1);
            }

            $Mailing = Doctrine_Core::getTable('Mailing')->find($mailingId);
            if (!$Mailing) {
                throw new Exception("No se encontro el Mailing ".$mailingId, 1);
            }

            $UserMailingConfig = Doctrine_Core::getTable('UserMailingConfig')->findOneByUserIdAndMailingId($userId, $mailingId);
            
            if (!$UserMailingConfig) {
                $UserMailingConfig = new UserMailingConfig;
                $UserMailingConfig->setUser($User);
                $UserMailingConfig->setMailing($Mailing);
            }

            $UserMailingConfig->setIsSubscribed(false);
            $UserMailingConfig->save();
            error_log("suscripcion cancelada");
            $this->message = "Suscripción cancelada";

        } catch (Exception $e) {
            $this->isError = true;
            $this->message = "Ha habido un problema cancelando la suscripción. El equipo ha sido notificado";

            error_log("[".date("Y-m-d H:i:s")."] [emails/unsubscribe] ERROR: ".$e->getMessage());

            if ($request->getHost() == "www.arriendas.cl") {
                Utils::reportError($e->getMessage(), "emails/unsubscribe");
            }
        }
    }
}
