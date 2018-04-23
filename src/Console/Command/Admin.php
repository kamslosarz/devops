<?php

namespace Application\Console\Command;

use Application\Config\Config;
use Application\Console\ConsoleException;
use Application\Model\User;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\Tools\Setup;

class Admin extends Command
{
    /**
     * @param $username
     * @param $password
     * @return bool
     */
    public function isValid($username, $password)
    {
        $user = $this->getEntityManager()->getRepository(User::class)->findOneBy([
            'username' => $username
        ]);

        if($user instanceof User)
        {
            $this->setError('User already exists');

            return false;
        }

        return true;
    }

    public function create($username, $password)
    {

        $user = new User();
        $user->setUsername($username)
            ->setPassword(md5($password));

        $em = $this->getEntityManager();
        $em->persist($user);
        $em->flush();

        echo 'Admin created';
    }


    private function getEntityManager()
    {
        $doctrineConfig = Config::get('doctrine');
        $config = Setup::createAnnotationMetadataConfiguration(array($doctrineConfig['models']), true);
        return EntityManager::create($doctrineConfig, $config);
    }
}