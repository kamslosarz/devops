<?php

namespace Application\Console\Command\Command\Admin;

use Application\Console\Command\Command;
use Application\Console\Command\CommandException;
use Application\Console\Command\CommandParameters;
use Application\Response\ResponseTypes\ConsoleResponse;
use Model\User as user;
use Model\UserPrivilege;
use Model\UserQuery;

class Create extends Command
{
    /**
     * @throws CommandException
     */
    public function validate(): void
    {
        if(!$this->commandParameters->offsetExists('force'))
        {
            $user = UserQuery::create()->findOneByUsername($this->commandParameters->offsetGet('username'));

            if($user instanceof User)
            {
                throw new CommandException('User already exists');
            }
        }
    }

    /**
     * @param CommandParameters $username
     * @param $password
     * @param bool $force
     * @return ConsoleResponse
     * @throws \Application\Service\ServiceContainer\ServiceContainerException
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function execute($username, $password, $force = false): ConsoleResponse
    {
        if($force)
        {
            $user = UserQuery::create()->findOneByUsername($username);

            if($user instanceof User)
            {
                $user->delete();
            }
        }

        $user = new User();
        $user->setUsername($username)
            ->setPassword(md5($password))
            ->save();

        foreach($this->event->getServiceContainer()->getService('router')->getRoutes() as $routeName => $route)
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