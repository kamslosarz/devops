<?php

namespace Application\Console\Command\Command\Admin;

use Application\Config\Config;
use Application\Console\Command\Command;
use Application\Router\Router;
use Model\User as user;
use Model\UserPrivilege;
use Model\UserQuery;

class Create extends Command
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
            $this->setError(sprintf('Invalid user data \'%s\' \'%s\'', $username, $password));
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
     * @return string
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function execute($username, $password)
    {
        $user = new User();
        $user->setUsername($username)
            ->setPassword(md5($password))
            ->save();

        foreach(Config::get('routes') as $route)
        {
            $userPrivilege = new UserPrivilege();
            $userPrivilege->setName(Router::getCompactRouteName($route[0], $route[1]));
            $userPrivilege->setUser($user);
            $userPrivilege->save();
            $user->addUserPrivilege($userPrivilege);
        }

        $user->save();

        return $this->output('Admin created');
    }
}