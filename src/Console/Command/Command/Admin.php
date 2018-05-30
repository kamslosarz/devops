<?php

namespace Application\Console\Command\Command;

use Application\Config\Config;
use Application\Router\Router;
use Model\User as user;
use Model\UserPrivilege;
use Model\UserQuery;

class Admin extends Command
{
    /**
     * @param string $username
     * @param string $password
     * @return bool
     */
    public function isValid($username = '', $password = '')
    {
        if(!$username || !$password)
        {
            $this->setError(sprintf('Invalid user data "%s" "%s"', $username, $password));
            return false;
        }

        $user = UserQuery::create()->findOneByUsername($username);

        if($user instanceof User)
        {
            $this->setError('User already exists');

            return false;
        }

        return true;
    }

    /**
     * @param $username
     * @param $password
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function create($username, $password)
    {
        $user = new User();
        $user->setUsername($username)
            ->setPassword(md5($password));

        foreach(Config::get('routes') as $route)
        {
            $userPrivilege = new UserPrivilege();
            $userPrivilege->setName(Router::getCompactRouteName($route[0], $route[1]));
            $user->addUserPrivilege($userPrivilege);
        }

        $user->save();

        echo 'Admin created';
    }
}