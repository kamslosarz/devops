<?php

namespace Application;

use Exception;

try
{
    if(!in_array('mod_rewrite', apache_get_modules()))
    {

        throw new Exception('Module "mod_rewrite" not enabled');
    }

    require_once '../vendor/autoload.php';
    require_once '../config/propel/config.php';

    (new Application())();
}
catch(Exception $e)
{
    echo $e->getMessage();

    throw $e;
}