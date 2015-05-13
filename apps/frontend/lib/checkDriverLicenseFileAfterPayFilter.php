<?php

    class CheckDriverLicenseFileAfterPayFilter extends sfFilter
    {
        public function execute($filterChain)
        {
            $request = $this->getContext()->getRequest();
            $user  = $this->getContext()->getUser();
            $action = $this->context->getActionName();

            if ($this->isFirstCall()
                    && $user 
                    && $user->isAuthenticated()
                    && $action != 'warningUploadLicense'
                    && $action != 'uploadLicenseWarning'
                    && $action != 'logout')
            {
                $idUsuario = sfContext::getInstance()->getUser()->getAttribute('userid');
                $User = Doctrine_core::getTable("user")->find($idUsuario);
                $toShow = Doctrine_Core::getTable("Transaction")->countPendingToShowByUser($idUsuario);
                if($toShow > 0 ) {
                    if(is_null($User->driver_license_file)){
                        $this->getContext()->getController()->redirect('reserve_success_license', true, 301);
                        throw new sfStopException();
                    }
                }

            }

            $filterChain->execute();
        }

    }

?>