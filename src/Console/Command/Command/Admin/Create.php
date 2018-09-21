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
     * @param bool $force
     * @return bool
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function isValid($username = '', $password = '', $force = false)
    {
        if(!$username || !$password)
        {
            $this->setError(sprintf('Invalid user data \'%s\' \'%s\'', $username, $password));
            return false;
        }

        $user = UserQuery::create()->findOneByUsername($username);

        if($user instanceof User)
        {
            if($force)
            {
                $user->delete();

                return true;
            }

            $this->setError('User already exists');

            return false;
        }

        return true;
    }

    /**
     * @param $username
     * @param $password
     * @param bool $force
     * @return $this
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function execute($username, $password, $force = false)
    {
        $user = new User();
        $user->setUsername($username)
            ->setPassword(md5($password))
            ->save();

        foreach(Config::get('routes') as $routeName => $route)
        {
            $userPrivilege = new UserPrivilege();
            $userPrivilege->setName($routeName);
            $userPrivilege->setUser($user);
            $userPrivilege->save();
            $user->addUserPrivilege($userPrivilege);
        }

        $user->save();

        return $this->setOutput('Admin created')->sendOutput();
    }
}