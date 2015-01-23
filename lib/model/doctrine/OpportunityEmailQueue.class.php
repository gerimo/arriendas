<?php

class OpportunityEmailQueue extends BaseOpportunityEmailQueue {

    public function getSignature() {
        return md5("Arriendas.cl ~ ".$this->created_at);
    }
}
