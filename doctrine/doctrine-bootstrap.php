<?php
// bootstrap.php
use Application\Model\User;
use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;

require_once "../vendor/autoload.php";

$doctrineConfig = \Application\Config\Config::get('doctrine');
$config = Setup::createAnnotationMetadataConfiguration(array($doctrineConfig['models']), true);
$entityManager = EntityManager::create($doctrineConfig, $config);
