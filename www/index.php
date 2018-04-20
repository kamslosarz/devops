<?php

namespace Application;

use Exception;

try {

    if (!in_array('mod_rewrite', apache_get_modules())) {

        throw new Exception('Module "mod_rewrite" not enabled');
    }

    include '../vendor/autoload.php';

    (new Application(new ApplicationParameters()))();

} catch (Exception $e) {


    throw $e;
}
