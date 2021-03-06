<?php

namespace Application;

try
{
    set_include_path(dirname(__DIR__));

    if(!in_array('mod_rewrite', \apache_get_modules()))
    {
        throw new \Exception('Module "mod_rewrite" not enabled');
    }
    if(!is_writable(dirname(__DIR__) . '/data/devops.db3'))
    {
        throw new \Exception('Database file is not writable');
    }

    require_once '../vendor/autoload.php';
    require_once '../config/propel_config.php';

    (new \Application\Application(include '../config/config.php'))()();
}
catch(\Exception $e)
{
    throw $e;
}