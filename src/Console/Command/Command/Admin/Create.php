<?php

namespace Application\Console\Command\Command\Admin;

use Application\Config\Config;
use Application\Console\Command\Command;
use Application\Console\Command\Command\CommandParameters;
use Model\User as user;
use Model\UserPrivilege;
use Model\UserQuery;

class Create extends Command
{
    /**
     * @param CommandParameters $commandParameters
     * @return bool
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function isValid(CommandParameters $commandParameters)
    {
        $parameters = $commandParameters->toArray();

        if(!$parameters[0] || !$parameters[1])
        {
            $this->setError(sprintf('Invalid user data \'%s\' \'%s\'', $parameters[0], $parameters[1]));

            return false;
        }

        $user = UserQuery::create()->findOneByUsername($parameters[0]);

        if($user instanceof User)
        {
            if(isset($parameters[2]) && $parameters[2])
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
     * @param CommandParameters $commandParameters
     * @return $this
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function execute(CommandParameters $commandParameters)
    {
        $parameters = $commandParameters->toArray();
        $user = new User();
        $user->setUsername($parameters[0])
            ->setPassword(md5($parameters[1]))
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