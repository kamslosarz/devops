<?php

namespace Application;

use Exception;

try
{
    set_include_path(dirname(__DIR__));

    if(!in_array('mod_rewrite', \apache_get_modules()))
    {
        throw new Exception('Module "mod_rewrite" not enabled');
    }

    require_once '../vendor/autoload.php';
    require_once '../config/propel/config.php';

    echo (new Application())();
}
catch(Exception $e)
{
    echo $e->getMessage();

    throw $e;
}