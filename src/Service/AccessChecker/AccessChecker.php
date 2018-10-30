<?php

namespace Application\Service\AccessChecker;

use Application\Service\Router\Route;
use Application\Service\AuthService\AuthService;
use Application\Service\Request\Request;
use Application\Service\ServiceInterface;
use Model\User;
use Model\UserPrivilege;
use Model\UserRole;

class AccessChecker implements ServiceInterface
{
    private $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    /**
     * @param Route $route
     * @return bool
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function hasAccess(Route $route): bool
    {
        $user = $this->authService->getUser();

        if(!($user instanceof User))
        {
            return false;
        }

        $userPrivileges = $user->getUserPrivileges();
        $userRoles = $user->getUserRoles();

        foreach($userPrivileges as $userPrivilege)
        {
            if($this->isPrivileged($userPrivilege, $route))
            {
                return true;
            }
        }

        /** @var UserRole $userRole */
        foreach($userRoles as $userRole)
        {
            foreach($userRole->getRole()->getPrivileges() as $privilege)
            {
                if($this->isPrivileged($privilege, $route))
                {
                    return true;
                }
            }
        }

        return false;
    }

    private function isPrivileged(UserPrivilege $privilege, Route $route): bool
    {
        return $privilege->getName() === $route->getName();
    }

}