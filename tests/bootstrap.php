<?php

include_once dirname(__DIR__).'/vendor/autoload.php';

define('FIXTURE_DIR', __DIR__.'/fixture');
define('APP_DIR', dirname(__DIR__));

\Application\Application::setEnvironment(\Application\Application::TEST);

