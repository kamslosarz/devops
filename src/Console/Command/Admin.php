<?php

namespace Application\Console\Command;

use Application\Config\Config;
use Application\Model\Privilege;
use Application\Model\User;
use Application\Router\Router;

class Admin extends Command
{
    /**
     * @param $username
     * @param $password
     * @return bool
     */
    public function isValid($username = '', $password = '')
    {
        if(!$username || !$password)
        {
            $this->setError(sprintf('Nie można utworzyć użytkownika "%s" "%s"', $username, $password));
            return false;
        }

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
        $em = $this->getEntityManager();
        $user = new User();
        $user->setUsername($username)
            ->setPassword(md5($password));

        foreach(Config::get('routes') as $route)
        {
            $privilege = new Privilege();
            $privilege->setName(Router::getCompactName($route[0], $route[1]));
            $user->addPrivilege($privilege);
            $em->persist($privilege);
        }

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