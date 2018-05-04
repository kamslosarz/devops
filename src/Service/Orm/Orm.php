<?php

namespace Application\Service\Orm;


use Application\Config\Config;
use Application\Service\ServiceInterface;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Setup;

class Orm implements ServiceInterface
{
    private $entityManager;

    public function __construct()
    {
        $doctrineConfig = Config::get('doctrine');
        $config = Setup::createAnnotationMetadataConfiguration(array($doctrineConfig['models']), false);
        $this->entityManager = EntityManager::create($doctrineConfig, $config);
    }

    public function getEntityManager()
    {
        return $this->entityManager;
    }
}