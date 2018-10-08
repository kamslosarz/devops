<?php

include_once dirname(dirname(__DIR__)).'/vendor/autoload.php';

define('FIXTURE_DIR', dirname(__DIR__).'/fixture');

\Application\Application::setEnvironment(\Application\Application::TEST);

