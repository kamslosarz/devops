<?php
$serviceContainer = \Propel\Runtime\Propel::getServiceContainer();
$serviceContainer->checkVersion('2.0.0-dev');
$serviceContainer->setAdapterClass('default', 'sqlite');
$manager = new \Propel\Runtime\Connection\ConnectionManagerSingle();
$manager->setConfiguration([
    'dsn' => sprintf('sqlite:%s/data/devops.db3', dirname(dirname(__DIR__))),
    'user' => 'root',
    'password' => '',
    'settings' =>
        [
            'charset' => 'utf8',
            'queries' => [],
        ],
    'classname' => '\\Propel\\Runtime\\Connection\\ConnectionWrapper',
    'model_paths' =>
        [
            'src',
            'vendor',
        ],
]);
$manager->setName('default');
$serviceContainer->setConnectionManager('default', $manager);
$serviceContainer->setDefaultDatasource('default');