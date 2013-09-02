<?php

//require_once '../symf/symfony/lib/autoload/sfCoreAutoload.class.php';
require_once '/Applications/XAMPP/xamppfiles/htdocs/web/symf/symfony/lib/autoload/sfCoreAutoload.class.php';

sfCoreAutoload::register();

class ProjectConfiguration extends sfProjectConfiguration
{
  public function setup()
  {
    $this->enablePlugins('sfDoctrinePlugin');
    $this->enablePlugins('sfJqueryReloadedPlugin');
    $this->enablePlugins('sfJQueryUIPlugin');
    $this->enablePlugins('sfVideoPlugin');
    setlocale(LC_TIME , "es_CL");
  }
}
