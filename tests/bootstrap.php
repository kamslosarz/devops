<?php

include_once dirname(__DIR__).'/vendor/autoload.php';

define('FIXTURE_DIR', __DIR__.'/fixture');

\Application\Application::setEnvironment(\Application\Application::TEST);

