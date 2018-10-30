<?php

namespace Application\Console\Command\Command\Admin;

use Application\Config\Config;
use Application\Console\Command\Command;
use Application\Console\Command\Command\CommandParameters;
use Application\Console\Command\CommandException;
use Application\Formatter\Constraint\PasswordMatcher;
use Application\Formatter\Constraint\UsernameMatcher;
use Application\Response\ResponseTypes\ConsoleResponse;
use Model\User as user;
use Model\UserPrivilege;
use Model\UserQuery;

class Create extends Command
{
    protected $parameters = [
        ['username', UsernameMatcher::class],
        ['password', PasswordMatcher::class]
    ];

    /**
     * @param CommandParameters $commandParameters
     * @throws \Application\Console\Command\CommandException
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function validate(CommandParameters $commandParameters): void
    {
        parent::validate($commandParameters);

        $user = UserQuery::create()->findOneByUsername($commandParameters->offsetGet(0));

        if($user instanceof User)
        {
            if($commandParameters->offsetExists(2) && $commandParameters->offsetGet(2))
            {
                $user->delete();
            }

            $this->addError('User already exists');
        }
    }

    /**
     * @param CommandParameters $commandParameters
     * @return ConsoleResponse
     * @throws \Application\Console\Command\CommandException
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function execute(CommandParameters $commandParameters): ConsoleResponse
    {
        $this->validate($commandParameters);

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