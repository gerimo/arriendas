<?php
    
    function url_for_frontend($name, $parameters) {
        return sfProjectConfiguration::getActive()->generateFrontendUrl($name, $parameters);
    }